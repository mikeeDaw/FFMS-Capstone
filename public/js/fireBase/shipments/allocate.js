// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
  
import { getFirestore, collection, addDoc, updateDoc,
    query, getDoc, getDocs, orderBy, setDoc, doc, where, limit, onSnapshot, QuerySnapshot } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";


  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyBiBqY3HRb7g6QPqDKrNg-F0DnlJ6j6McU",
    authDomain: "lebriafms.firebaseapp.com",
    databaseURL: "https://lebriafms-default-rtdb.firebaseio.com",
    projectId: "lebriafms",
    storageBucket: "lebriafms.appspot.com",
    messagingSenderId: "1012644695892",
    appId: "1:1012644695892:web:6a3c0a6f15fb80d0ea02c1",
    measurementId: "G-WDCQD0VW6B"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  //const analytics = getAnalytics(app);
  const db = getFirestore(app);

  
/* --! WORK AREA !-- */
const dateInst = new Date();
var dayNames = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
var dayNum = dateInst.getDay()
var currHour = dateInst.getHours()

const delay = ms => new Promise(res => setTimeout(res, ms));
var drivList = [];
var carList = {};
console.log(batches, services)

const drivRef = collection(db, 'Drivers');
const q = query( drivRef, where('available', '==', true))
const snapDriv = await getDocs(q)


await snapDriv.forEach( async (snapDoc) => {

    var drivData = snapDoc.data()
    var vehiSnap = await getDoc( doc(db, "Drivers", snapDoc.id, 'Vehicles', 'vehicleList'));
    // .then((vdoc) => {return vdoc.data()});
    var vehiFreq = vehiSnap.data();

    var rate = {
        'Electronics' : [],
        'Perishable' : [],
        'Industrial' : [],
        'Consumer' : [],
        'Fragile' : [],
    };

    //Ratings
    var ratingSnap = await getDocs( collection(db, "Drivers", snapDoc.id, "Ratings") );
    
    if(!ratingSnap.empty){
        await ratingSnap.forEach( (rateDoc) => {
            let rateData = rateDoc.data()
            rate[rateData['category']].push(Number(rateData['ratingAvg']));
        })

        Object.keys(rate).forEach( key => {
       
            if(!rate[key].length){
                rate[key] = 0;
            } else {
                let sum = rate[key].reduce( (a,b) => a + b, 0 );
                rate[key] = Number((sum / rate[key].length).toFixed(1));
            }
            
        })

    }
    
    drivList.push( {'driverID' : snapDoc.id ,...drivData, ...vehiFreq, ...rate} );

});

services.forEach( async (type) => {
    carList[type] = [];

    var vehiRef = collection(db, 'Vehicles', 'List', type);
    const vq = query(vehiRef, where('available', '==', true));
    const snapper = await getDocs(vq);

    if( !snapper.empty ){
        snapper.forEach( (vdoc) => {
            var carData = vdoc.data()
            carList[type].push( { 'vehicleID' : vdoc.id, 'last_used' : carData['last_used'] } )
        })
    }

})

$( async function() {
    await delay(2600)

    // Populate choices in dropdown & set onSnapshot
    $('tbody tr').each( function() {
        var selectBarDr = $(this).find('select[name$="[driver]"]');
        var selectBarVe = $(this).find('select[name$="[vehicle]"]');
        let vehiType = $(this).closest('tr').find(' .servType input:hidden').val();
        let batchID = $(this).find('input:hidden[name="batchID"]').val();
        let ordersContainer = $(this).find('td .orderList');

        $(this).find('select option[value="XX"]').remove()

        for (const value of drivList ) {
            if(value[`can_${vehiType}`]){
                $(selectBarDr).append(
                    $('<option/>', {'value' : value['driverID'], 'text' : `${value['Fname']} ${value['Lname']}`})
                )
            }
          }

        for (var arrVal of carList[vehiType]) {
            $(selectBarVe).append(
                $('<option/>', {'value' : arrVal['vehicleID'], 'text' : arrVal['vehicleID']})
            )
        }

        let btchRefer = doc(db, 'DelivBatch', batchID );
        let initsOk = false;
        let subs = onSnapshot(btchRefer, (docSnapp) => {

            if(!initsOk) { initsOk = true }
            else {

                ordersContainer.empty();
                let docData = docSnapp.data()
                let ncount = 1;

                for ( let [key,val] of Object.entries(docData['ordersList'])){
                    
                    ordersContainer.append(
                        `<span class="clickRow" data-href='/dash/orders/${val}'>
                        <span class="fs-12 text-secondary poppins me-2">OID ${ncount} :</span> ${val} 
                        </span>`
                    )
                    ++ncount;
                }


            }

        })
        
    })

    function drivQuickSort(vehiType, category){
        return function (a,b) {
            if(a[vehiType] < b[vehiType]) { return -1 }
            else if ( a[vehiType] > b[vehiType]) { return 1 }
            else {
                if (a[category] < b[category] ) { return -1 }
                else if (a[category] > b[category] ) { return 1 }
                else {
                    if (a['lastDelivery'] <= b['lastDelivery']) { return 1 }
                    else { return -1 }
                }
            }
        }
    }

    function vehiQuickSort(){
        return function(a,b) {
            if( a['last_used'] <= b['last_used']) { return 1 }
            else { return -1 }
        } 
    }

    // When new batch arrives
    var initOk = false;
    const batchRef = collection(db, 'DelivBatch');
    const sub = onSnapshot( batchRef, (batchSnap) => {

        if(!initOk){
            initOk = true
        } else {
            batchSnap.docChanges().forEach( (changed) => {
                if(changed.type === 'added'){
                    var nums = 1;
                    var newBhData = changed.doc.data()
                    var bchID = changed.doc.id;
                    $('#allocTbl').prepend(
                        $('<tr/>', { 'id' : bchID }).append(
                            $('<td/>', {'class' : 'align-middle', 'text' : newBhData['dateCreated'].split(' ')[0] }),
                            $('<td>', {'class' : 'align-middle batchCateg', 'text' : newBhData['category']}).append(
                                $('<input/>', {'type' : 'hidden', 'name' : 'batchID', 'value' : bchID})
                            ),
                            $('<td/>', {'class' : 'align-middle'}).append(
                                $('<div/>', {'class' : 'd-flex flex-column orderLister'})
                            ),
                            $('<td/>', {'class' : 'align-middle servType', 'text' : newBhData['servType']}).append(
                                $('<input/>', { 'type' : 'hidden', 'name' : 'servType', 'value' : newBhData['servType']})
                            ),
                            $('<td>', {'class' : 'align-middle'}).append(
                                $('<select/>', {'class' : 'form-select drivSel', 'style' : 'width: unset; border-color: #696969;', 'name' : `manualAllo[${bchID}][driver]`}).append(
                                    $('<option/>', {'value' : '', 'disabled' : 'disabled', 'selected' : 'selected', 'text' : '- Select Driver -'})
                                )
                            ),
                            $('<input/>', {'type' : 'hidden', 'name' : `manualAllo[${bchID}][serv-typ]`, 'value' : newBhData['servType'], 'disabled' : 'disabled'}),
                            $('<td/>', {'class' : 'align-middle'}).append(
                                $('<select/>', {'class' : 'form-select vehiSel', 'style' : 'width: unset; border-color: #696969;', 'name' : `manualAllo[${bchID}][vehicle]`}).append(
                                    $('<option/>', {'value' : '', 'disabled' : 'disabled', 'selected' : 'selected', 'text' : '- Select Vehicle -'})
                                )
                            ),
                            $('<td/>', {'class' : 'align-middle'}).append(
                                $('<button/>', {'class' : 'btn btn-outline-success fs-14 poppins autoBtn', 'type' : 'button' , 'text' : 'Auto'})
                            )
                        )
                    )

                    var theRow = $(`#${bchID}`)
                    for(const [key, val] of Object.entries(newBhData['ordersList'])){
                        $(`#${bchID} td .orderLister`).append(
                            `<span class="clickRow" data-href='/dash/orders/${val}'>
                            <span class="fs-12 text-secondary poppins me-2">OID ${nums} :</span> ${val} 
                            </span>`
                        )
                        ++nums;
                    }

                    var selDr = theRow.find('select[name$="[driver]"]');
                    var selVe = theRow.find('select[name$="[vehicle]"]');
                    var vehTyp = theRow.find(' .servType input:hidden').val();

                    for (const value of drivList ) {
                        if(value[`can_${vehTyp}`]){
                            $(selDr).append(
                                $('<option/>', {'value' : value['driverID'], 'text' : `${value['Fname']} ${value['Lname']}`})
                            )
                        }
                      }
            
                    for (var arrVal of carList[vehTyp]) {
                        $(selVe).append(
                            $('<option/>', {'value' : arrVal['vehicleID'], 'text' : arrVal['vehicleID']})
                        )
                    }

                    // Reapply some Functions
                    $('select[name$="[driver]"]').on('change', function(){

                        checkDrops('select[name$="[driver]"]')
                        $(this).css( ($(this).val() == '') ? {'color' : '#737272'} : {'color' : '#000'})
            
                        // Enable hidden input
                        var hidden = $(this).closest('tr').find('input[type=hidden]');
                        hidden.prop('disabled', false);
                        
                        // Remove check on 'auto' if user entered input in manual allocation
                        // var aCheck = $(this).closest('tr').find('td .form-check input')
                        //     if(aCheck.is(':checked')){
                        //         aCheck.prop('checked', false);
                        //     }
            
                        // Show submit button if theres item selected
                        if($('select option:not(:disabled):selected').length == 0){
                            $('.shUpd').css('top', '-65px');
                        } else{
                            $('.shUpd').css('top', '60px');
                        }
                    });
            
                    $('select[name$="[vehicle]"]').on('change', function(){
            
                            checkDrops('select[name$="[vehicle]"]')
                            $(this).css( ($(this).val() == '') ? {'color' : '#737272'} : {'color' : '#000'})
            
                            // Enable hidden input
                            var hidden = $(this).closest('tr').find('input[type=hidden]');
                            hidden.prop('disabled', false);
            
                            // Show submit button if theres item selected
                            if($('select option:not(:disabled):selected').length == 0){
                                $('.shUpd').css('top', '-65px');
                            } else{
                                $('.shUpd').css('top', '60px');
                            }
                    });
            
                    // Checkbox functions
                    $(':checkbox').change(function() {
                        // If 'auto' checkbox is checked, remove values in manual allocation
                        
                        var hidden = $(this).closest('tr').find('input[type=hidden]');
            
                        if($(this).is(':checked')){
                            var dSel = $(this).closest('tr').find('td select[name$="[driver]"]');
                            var vSel = $(this).closest('tr').find('td select[name$="[vehicle]"]');
                            $(dSel).val('');
                            $(vSel).val('');
            
                            checkDrops('select[name$="[driver]"]')
                            checkDrops('select[name$="[vehicle]"]')
                        } else{
                            hidden.prop('disabled', true);
                        }
                        // Show submit button if 'auto' is checked
                        if($(':checkbox:checked').length == 0 && $('select option:not(:disabled):selected').length == 0 ){     
                            $('.shUpd').css('top', '-65px');
                        }else{
                            $('.shUpd').css('top', '60px');
                        }
            
                    })
            
                    // For clicking each order row
                    $(".clickRow").click(function() {
                        window.location = $(this).data("href");
                    });

                    // When auto button is pressed
                    $('.autoBtn').on('click', function() {
                        var drivSel = $(this).closest('tr').find('select[name$="[driver]"]');
                        var vehiSel = $(this).closest('tr').find('select[name$="[vehicle]"]');
                        var servType = $(this).closest('tr').find('input:hidden[name="servType"]').val();
                        var categ = $(this).closest('tr').find('.batchCateg').text().trim()

                        drivList.sort(drivQuickSort(servType, categ)).reverse();
                        carList[servType].sort(vehiQuickSort()).reverse();
                        console.log(drivList, carList)

                        /* Setting the select bar to the result in best fit */
                        // Driver
                        for (const i in drivList){
                            var driver = drivList[i];
                            var drivOpt = $(drivSel).find(`option[value='${driver['driverID']}']`)
                
                            if($(drivOpt).css('display') != 'none'){
                                console.log(driver['driverID'])
                                $(drivSel).val(`${driver['driverID']}`).change();
                                break;
                            }
                        }
                        // Vehicle
                        for (const i in  carList[servType]){
                            var vehicle =  carList[servType][i];
                            var vehiOpt = $(vehiSel).find(`option[value='${vehicle['vehicleID']}']`)
                
                            if($(vehiOpt).css('display') != 'none'){
                                console.log(vehicle['vehicleID'])
                                $(vehiSel).val(`${vehicle['vehicleID']}`).change();
                                break;
                            }
                        }

                    } )


                }
            })
        }

    })

    // When auto button is pressed
    $('.autoBtn').on('click', function() {
        var drivSel = $(this).closest('tr').find('select[name$="[driver]"]');
        var vehiSel = $(this).closest('tr').find('select[name$="[vehicle]"]');
        var servType = $(this).closest('tr').find('input:hidden[name="servType"]').val();
        var categ = $(this).closest('tr').find('.batchCateg').text().trim()
        var emailDriv = $(this).closest('tr').find('input:hidden[name="drivEmail"]')

        drivList.sort(drivQuickSort(servType, categ)).reverse();
        carList[servType].sort(vehiQuickSort()).reverse();

        /* Setting the select bar to the result in best fit */
        // Driver
        for (const i in drivList){
            var driver = drivList[i];
            var drivOpt = $(drivSel).find(`option[value='${driver['driverID']}']`)
  
            if($(drivOpt).css('display') != 'none'){
                $(drivSel).val(`${driver['driverID']}`).change();
                $(emailDriv).val(`${driver['Email']}`)
                break;
            }
        }
        // Vehicle
        for (const i in  carList[servType]){
            var vehicle =  carList[servType][i];
            var vehiOpt = $(vehiSel).find(`option[value='${vehicle['vehicleID']}']`)
  
            if($(vehiOpt).css('display') != 'none'){
                console.log(vehicle['vehicleID'])
                $(vehiSel).val(`${vehicle['vehicleID']}`).change();
                break;
            }
        }

    } )

    $('#saveAlloc').one('submit', async function(e) {
        e.preventDefault();

        // if(dayNames[dayNum] == 'Sunday' || ( currHour < 8 && currHour > 16 ) ) { 
        //     $('#timeCanAllo').val(false)
        //     $(this).submit();
        //     return false;
        // }

        // Get Current Date
        const dateTime = dateInst.toJSON().slice(0, 10) + ' ' + dateInst.getHours() + ':' + dateInst.getMinutes();
        var updNo = 0;


        // Start Updating Allocated Batches
        $('tbody tr').each( async function() {

            var driVal = $(this).find('select[name$="[driver]"]').val()
            var vehVal = $(this).find('select[name$="[vehicle]"]').val()
            var batchID = $(this).find('input:hidden[name="batchID"]').val()
            var serviceTyp = $(this).find('input:hidden[name="servType"]').val();
            
            if( driVal == null && vehVal == null){
                return;
            } else if ( !(driVal==null) != !(vehVal==null) ){
                $(this).submit();
                return false;
            } else {

                var batchRef = doc(db, 'DelivBatch', batchID);

                updateDoc(batchRef, {
                    driverID : driVal,
                    vehicleID : vehVal,
                    allocated : true
                })

                var batchDoc = await getDoc( batchRef);
                var batchData = batchDoc.data()
                
                // Update each shipments to 'outForDelivery' stage & send Notif.
                Object.entries(batchData['ordersList']).forEach( async ([key, valu] ) => {
                    ++updNo
                    // Update Status
                    const shipUpdRef = doc(db, 'Shipments', key)
                    updateDoc( shipUpdRef, {
                        outForDelivery : dateTime,
                    })
                    // Send Notif
                    const ordSnap = await getDoc( doc(db, 'Orders', valu));
                    const ordDataa = ordSnap.data();

                    const cusNotifCol = collection(db, `Users/${ordDataa['User']}/Notifications`);
                    addDoc(cusNotifCol, {
                        'orderID': valu,
                        'timestamp': dateTime,
                        'title' : 'Order is Out for Delivery',
                        'body' : `Order: ${valu} was deployed to arrive at your destination.`,
                        'dismissed' : false,
                    });

                    var data = {
                        service_id: 'service_dsa523j',
                        template_id: 'template_8vhztoe',
                        user_id: 'XsarNf-5YZQOm5oKE',
                        template_params: {
                            'email' : ordDataa['email'],
                            'subject' : `OrderID: ${valu} Status Update`,
                            'body' : `Order is Out for Delivery. \n\n Order: ${valu} was deployed to arrive at your destination.`
                        }
                    };
        
                    $.ajax('https://api.emailjs.com/api/v1.0/email/send', {
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json'
                    });


                })

                // Update Driver Status & send Notif
                const drivUpdRef = doc(db, 'Drivers', driVal);
                updateDoc(drivUpdRef, {
                    available : false,
                })

                // Send notif to driver
                const drivNotif = collection(db, `Drivers/${driVal}/Notifications`);
                addDoc(drivNotif, {
                    'timestamp': dateTime,
                    'title' : 'New Delivery',
                    'body' : `You have a new delivery. Please proceed with your assigned vehicle.`,
                    'dismissed' : false,
                });

                // Update Vehicle Status
                const vehiUpdRef = doc(db, 'Vehicles', 'List', serviceTyp, vehVal)
                updateDoc(vehiUpdRef, {
                    available : false,
                })

            }

        })

        // Update Stats Docu
        var shipRef = doc(db, 'Statistics', 'ShipmentStats');
        var shipSnap = await getDoc( shipRef );
        var shipData = shipSnap.data()

        updateDoc(shipRef, {
            outForDelivery : shipData['outForDelivery'] + updNo,
            forShipping : shipData['forShipping'] - updNo,
        });

        await delay(2000)
        $(this).submit()
    })
})