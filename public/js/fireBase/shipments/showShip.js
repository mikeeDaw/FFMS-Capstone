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
console.log(currProg, nextProg)

var q;
var q2;
var number = 1
var noDone = (currProg != 'awaitPay' && currProg != 'completed' && currProg != 'forShipping' && currProg != 'outForDelivery');
var isAwait = (currProg == 'awaitPay');
const delay = ms => new Promise(res => setTimeout(res, ms));
var orderDocs = {};
var cityGrp = {
    grp1 : ['Caloocan', 'Valenzuela', 'Malabon', 'Navotas'],
    grp2 : ['Quezon City', 'Marikina', 'San Juan'],
    grp3 : ['Manila', 'Mandaluyong', 'Makati', 'Pasay'],
    grp4 : ['Pasig', 'Pateros', 'Taguig'],
    grp5 : ['Parañaque', 'Muntinlupa', 'Las Piñas']
}

// Querying The Shipments
if(currProg != 'completed'){
    q = query(collection(db, "Shipments"), where(currProg, '!=', false), where(nextProg, '==', null), where('cancelled', '==', null), orderBy(currProg, 'desc'))
    q2 = query(collection(db, "Shipments"), where(currProg, '!=', false), where(nextProg, '==', null), where('cancelled', '==', null), orderBy(currProg, 'desc'), limit(1))
} else {
    q = query(collection(db, "Shipments"), where('completed', '!=', false), orderBy('completed', 'desc'))
    q2 = query(collection(db, "Shipments"), where('completed', '!=', false), orderBy('completed', 'desc'), limit(1))
}

const qRes = await getDocs(q);

$(async function() {

    if(!qRes.empty){
        console.log("start Populating")
        $('#headerTitle').show()
        $('#tableWrapper').css("height", 'auto')

        // Put table Header
        $('#tableWrapper').append(
            $('<div/>', { 'class':'col-12'} ).append(
                $('<div/>', { 'class':'table-responsive tblHeight'} ).append(
                    $('<table/>', { 'class':'table table-hover table-striped table-bordered'} ).append(
                        $('<thead/>', {'id':'tblHeader'}).append(
                            $('<tr/>').append(
    
                                $('<th/>'),
                                $('<th/>', {'text' : 'Status Date'}),
                                $('<th/>', {'text' : 'Order ID'}),
                                $('<th/>', {'text' : 'Customer'}),
                                $('<th/>', {'text' : 'Item Description'}),
                                (noDone == true ? 
                                $('<th/>', {'text' : ''}) : ''),
    
                            )
                        ),
                        $('<tbody/>', {'id' : 'tblBody'})
                    )
                )
            )
        )
    
        const tbody = $('#tblBody');
    
        await qRes.forEach( async (docu) => {
    
            var shipData = docu.data()
            var orderID = shipData['orderID'];
            var docRef = doc(db, 'Orders', orderID)
            var docData = await getDoc(docRef).then((docum) => {return docum.data()});
            
            // Records of each Order
            tbody.append(
                $('<tr/>').append(
                    $('<td/>', {'text':number, 'style' : 'vertical-align: middle;'}),
                    $('<td/>', {'text':shipData[currProg].split(" ")[0], 'style' : 'vertical-align: middle;'}),
                    $('<td/>', {'text':orderID, 'class': 'clickRow', 'data-href':`/dash/orders/${orderID}`, 'style' : 'vertical-align: middle;'}).append(
                        $('<input/>', {'type' : 'hidden', 'name' : 'ordCateg', 'value' : `${docData['itm-categ']}`}),
                        $('<input/>', {'type' : 'hidden', 'name' : 'totalWeight', 'value' : `${docData['totalWeight']}`}),
                        $('<input/>', {'type' : 'hidden', 'name' : 'totalVol', 'value' : `${docData['totalVol']}`}),
                        $('<input/>', {'type' : 'hidden', 'name' : 'serv-typ', 'value' : `${docData['serv-typ']}`}),
                        $('<input/>', {'type' : 'hidden', 'name' : 'ordCity', 'value' : `${docData['city']}`})
                    ),
                    $('<td/>', {'text':`${docData['fname']} ${docData['lname']}`, 'style' : 'vertical-align: middle;'}),
                    $('<td/>', {'text':docData['itm-desc'], 'id' : `itemDesc${number}`, 'style' : 'vertical-align: middle;'}),
                )
            )
    
            // 'Done' Checkbox
            if(noDone){
                $(`#itemDesc${number}`).after(
                    $('<td/>').append(
                        $('<div/>', {'class': 'form-check d-flex justify-content-center align-items-center'}).append(
                            
                            $('<input/>', {'class':'form-check-input shChecker me-2', 'type':'checkbox', 'name':`${checkNm}Checks[]`, 'id': `${checkNm}Checker${number}`, 'value' : docu.id}),
                            $('<label/>', {'class':'form-check-label shChckLbl', 'for' : `${checkNm}Checker${number}`, 'text' : 'Done'})

                        )
                    )
                )
            }

            orderDocs[docu.id] = [docData['User'], orderID, docData['email']];
            ++number;
        })

        $('#loadArea').hide()
        //await delay(1800);

        var initDone = false;
        const sub = onSnapshot(q2, async (querySnapy) => {

            if(!initDone){     
                // Script for Update Status button
                $(':checkbox').change(function(){
            
                    if($(':checkbox:checked').length == 0){
                        
                        $('.shUpd').css('top', '-65px');
                    }else{
                        $('.shUpd').css('top', '60px');
                    }

                    if($(this).is(':checked')){
                        var checkBox = $(this);
                        console.log($(this).val())
                    }
                })    
                // For clicking each order row
                $(".clickRow").click(function() {
                    window.location = $(this).data("href");
                });
                initDone = true
            } else {

                var oneDocu = querySnapy.docs[0];
                var oneDocData = oneDocu.data();
                var oneOrdID = oneDocData['orderID'];
                var ordData = await getDoc( doc(db, 'Orders', oneOrdID) ).then( (oDoc) => { return oDoc.data()})

                // Record of new Order
                tbody.prepend(
                    $('<tr/>').append(
                        $('<td/>', {'text':number, 'style' : 'vertical-align: middle;'}),
                        $('<td/>', {'text':oneDocData[currProg].split(" ")[0], 'style' : 'vertical-align: middle;'}),
                        $('<td/>', {'text':oneOrdID, 'class': 'clickRow', 'data-href':`/dash/orders/${oneOrdID}`, 'style' : 'vertical-align: middle;'}),
                        $('<td/>', {'text':`${ordData['fname']} ${ordData['lname']}`, 'style' : 'vertical-align: middle;'}),
                        $('<td/>', {'text':ordData['itm-desc'], 'id' : `itemDesc${number}`, 'style' : 'vertical-align: middle;'}),
                    )
                )
        
                // 'Done' Checkbox
                if(notAwaitCompl){
                    $(`#itemDesc${number}`).after(
                        $('<td/>').append(
                            $('<div/>', {'class': 'form-check d-flex justify-content-center align-items-center'}).append(
                                
                                $('<input/>', {'class':'form-check-input shChecker me-2', 'type':'checkbox', 'name':`${checkNm}Checks[]`, 'id': `${checkNm}Checker${number}`, 'value' : oneDocu.id}),
                                $('<label/>', {'class':'form-check-label shChckLbl', 'for' : `${checkNm}Checker${number}`, 'text' : 'Done'})

                            )
                        )
                    )
                }

                orderDocs[oneDocu.id] = [ordData['User'], oneOrdID, ordData['email']];
                ++number;

                // Script for Update Status button
                $(':checkbox').change(function(){
            
                    if($(':checkbox:checked').length == 0){
                        
                        $('.shUpd').css('top', '-65px');
                    }else{
                        $('.shUpd').css('top', '60px');
                    }

                    if($(this).is(':checked')){
                        var checkBox = $(this);
                        console.log($(this).val())
                    }
                })    
                // For clicking each order row
                $(".clickRow").click(function() {
                    window.location = $(this).data("href");
                });

                var rowNum = 1
                $('#tableMain tr td:nth-child(1)').each( function() {
                    $(this).text(rowNum);
                    ++rowNum;
                })

            }
        })

        // // Script for Update Status button
        // $(':checkbox').change(function(){
    
        //     if($(':checkbox:checked').length == 0){
                
        //         $('.shUpd').css('top', '-65px');
        //     }else{
        //         $('.shUpd').css('top', '60px');
        //     }

        //     if($(this).is(':checked')){
        //         var checkBox = $(this);
        //         console.log($(this).val())
        //     }
        // })    
        // // For clicking each order row
        // $(".clickRow").click(function() {
        //     window.location = $(this).data("href");
        // });
    
    } else {

        $('#tableWrapper').css("height", 'auto');
        $('#loadArea').hide();

        const ndImg = $('#noDataImg').val();
        $('#tableWrapper').append(
            $('<div/>', {'class': 'col-12'}).append(
                $('<div/>', {'class' : 'd-flex justify-content-center align-items-center flex-column nodata my-3'}).append(
                    $('<img/>', {'src' : ndImg}),
                    $('<p/>', {'class': 'my-3', 'text' : 'No Data Found.'})
                )
            )
        )
    }

})

/* Misc */
const dateInst = new Date();
const dateTime = dateInst.toJSON().slice(0, 10) + ' ' + dateInst.getHours() + ':' + dateInst.getMinutes();
console.log(dateTime, typeof dateTime, dateInst.toLocaleTimeString())

var notifTitle;
switch(currProg){
    case 'apprOrder':
        notifTitle = "Order Approved"
        break;
    case 'payVerif':
        notifTitle = 'Package Arrived at Branch'
        break;
    case 'arrived':
        notifTitle = 'Items Under Examination'
        break;
    case 'underExamine':
        notifTitle = 'Preparing Order for Shipment'
        break;
    case 'forShipping':
        notifTitle = 'Order is Out for Delivery'
        break;
    case 'outForDelivery':
        notifTitle = 'Package is Delivered Successfully'
        break;
}
var notifBody;
switch(currProg){
    case 'apprOrder':
        notifBody = `was approved. Please proceed to payment.`
        break;
    case 'payVerif':
        notifBody = `arrived at our branch located in Manila.`
        break;
    case 'arrived':
        notifBody = `is under inspection and being examined.`
        break;
    case 'underExamine':
        notifBody = `has completed examination and is now being prepared for delivery.`
        break;
    case 'forShipping':
        notifBody = `was deployed to arrive at your destination.`
        break;
    case 'outForDelivery':
        notifBody = `has been successfully completed.`
        break;
}

function getVehiMaxes(servicer){
    // [maxWeig, maxSpace]
    switch(servicer){
        case 'ClVan4':
            return [4000, 10.4];
        case 'ClVan6':
            return [9000, 16];
        case 'WingVan':
            return [13000, 25.78];
        case 'Truck10W':
            return [15000, 29];
        case 'Chassis20':
            return [25000, 33.14];
        case 'Chassis40':
            return [30000, 67.32];
        case 'TrackHead':
            return [40000, 109.51];
    }
}
function getCityGrp(cityFind){

    for( var grp in cityGrp){
        console.log(grp)
        if( cityGrp.hasOwnProperty(grp)){

            for (let city of cityGrp[grp]){
                if( cityFind == city) { 
                    return cityGrp[grp];
                    break;
                }
            }
        }
    }
}

/* Updating Status */
$('#updateBtn').on('click', async function() {

    const dateInst = new Date();
    const dateTime = dateInst.toJSON().slice(0, 10) + ' ' + dateInst.getHours() + ':' + dateInst.getMinutes();

    var checkers = $(':checkbox:checked');
    var updCount = checkers.length;

    // Prep Statistics Document
    const shipStat = doc(db, 'Statistics', 'ShipmentStats');
    const statData = await getDoc(shipStat).then((statDoc) => {return statDoc.data()});
    const currCount = Number(statData[currProg]);
    const nextCount = Number(statData[nextProg]);

    $('#updLoad').show()
    $('#loadOverlay').show()
    
    // Update Statistics Document
    if(currProg != 'apprOrder'){
        updateDoc(shipStat, {
            [currProg]: currCount - updCount,
            [nextProg]: nextCount + updCount,
            })
    } else{
        updateDoc(shipStat, {
            [currProg]: currCount - updCount,
            'awaitPay': Number(statData['awaitPay']) + updCount,
            })
    }

    // Update Status of the selected orders & send Notif
    if(currProg != 'apprOrder'){

        for( var i=0; i<updCount; i++){
            var elem = checkers[i];
            var shipID = $(elem).val();
            console.log(elem)

            if(nextProg == 'forShipping'){

                var categ = $(elem).closest('tr').find('input[name="ordCateg"]').val().trim()
                var ordID = $(elem).closest('tr').find('.clickRow').text().trim()
                var totalWeight = $(elem).closest('tr').find('input[name="totalWeight"]').val().trim()
                var totalVol = $(elem).closest('tr').find('input[name="totalVol"]').val().trim();
                var vehicle = $(elem).closest('tr').find('input[name="serv-typ"]').val().trim();
                var maxWeiVol = getVehiMaxes(vehicle);
                var orderCity = $(elem).closest('tr').find('input[name="ordCity"]').val().trim();
                var ordCityGrp = getCityGrp(orderCity);

                // Deciding the Delivery batch of the order
                const delivRef = collection(db, 'DelivBatch');
                const q = query( delivRef, where('delivStat', '==', false), where('servType', '==', vehicle), where('category', '==', categ), where('cityGroup', "array-contains", orderCity), orderBy('dateCreated') )

                const snapQueryer = await getDocs(q).then( (snapQuery) => {
                    if(!snapQuery.empty){
                        console.log("may laman quer")
                        var assigned = false;
                        
                        for( let idx in snapQuery.docs){
                            var batchDocu = snapQuery.docs[idx];
                            var batchData = batchDocu.data();
                            
                            if(batchData['spaceAvail'] > totalVol && batchData['weightAvail'] > totalWeight){
                                var btchDocRef = doc(db, 'DelivBatch', batchDocu.id)

                                setDoc(btchDocRef, {
                                    'ordersList' : { [shipID] : `${ordID}`},
                                    'spaceAvail' : batchData['spaceAvail'] - totalVol,
                                    'weightAvail' : batchData['weightAvail'] - totalWeight,
                                }, 
                                { merge : true});
            
                                assigned = true;
                                break;
            
                            } else {
                                continue;
                            }
                        }
            
                        // Pag wala nang batch na may space, create ng bagong batch.
                        if(!assigned){
            
                            addDoc( collection(db, 'DelivBatch'), {
                                'cityGroup' : ordCityGrp,
                                'category' : categ,
                                'ordersList' : { [shipID] : `${ordID}` },
                                'vehicleID' : null,
                                'driverID' : null,
                                'delivStat' : false,
                                'allocated' : false,
                                'servType' : vehicle,
                                'maxCapacity' : maxWeiVol[1],
                                'maxWeight' : maxWeiVol[0],
                                'spaceAvail' : maxWeiVol[1] - totalVol,
                                'weightAvail' : maxWeiVol[0] - totalWeight,
                                'dateCreated' : dateTime,
                                'dateDone' : null,
                            })
                        }
            
                    } else {
                        console.log('wala laman query')
                        // Pag lahat ng batch delivered na & wala nang pending, create ng bagong batch.
                        addDoc( collection(db, 'DelivBatch'), {
                            'cityGroup' : ordCityGrp,
                            'category' : categ,
                            'ordersList' : { [shipID] : `${ordID}` },
                            'vehicleID' : null,
                            'driverID' : null,
                            'allocated' : false,
                            'delivStat' : false,
                            'servType' : vehicle,
                            'maxCapacity' : maxWeiVol[1],
                            'maxWeight' : maxWeiVol[0],
                            'spaceAvail' : maxWeiVol[1] - totalVol,
                            'weightAvail' : maxWeiVol[0] - totalWeight,
                            'dateCreated' : dateTime,
                            'dateDone' : null,
                        })
                    }
                    
                })

                // (not .then) If may batch pa na hindi pa nadedeliver at may space pa
                // if(!snapQuery.empty){
                //     var assigned = false;
        
                //     for( let idx in snapQuery.docs){
                //         var batchDocu = snapQuery.docs[idx];
                //         var batchData = batchDocu.data();
                        
                //         if(batchData['spaceAvail'] > totalVol && batchData['weightAvail'] > totalWeight){
                //             var btchDocRef = doc(db, 'DelivBatch', batchDocu.id)
        
                //             setDoc(btchDocRef, {
                //                 'ordersList' : { [shipID] : `${ordID}`},
                //                 'spaceAvail' : batchData['spaceAvail'] - totalVol,
                //                 'weightAvail' : batchData['weightAvail'] - totalWeight,
                //             }, 
                //             { merge : true});
        
                //             assigned = true;
                //             break;
        
                //         } else {
                //             continue;
                //         }
                //     }
        
                //     // Pag wala nang batch na may space, create ng bagong batch.
                //     if(!assigned){
        
                //         addDoc( collection(db, 'DelivBatch'), {
                //             'category' : categ,
                //             'ordersList' : { [shipID] : `${ordID}` },
                //             'vehicleID' : null,
                //             'driverID' : null,
                //             'delivStat' : false,
                //             'servType' : vehicle,
                //             'maxCapacity' : maxWeiVol[1],
                //             'maxWeight' : maxWeiVol[0],
                //             'spaceAvail' : maxWeiVol[1] - totalVol,
                //             'weightAvail' : maxWeiVol[0] - totalWeight,
                //             'dateCreated' : dateTime,
                //             'dateDone' : null,
                //         })
                //     }
        
                // } else {
                //     // Pag lahat ng batch delivered na & wala nang pending, create ng bagong batch.
                //     addDoc( collection(db, 'DelivBatch'), {
                //         'category' : categ,
                //         'ordersList' : { [shipID] : `${ordID}` },
                //         'vehicleID' : null,
                //         'driverID' : null,
                //         'delivStat' : false,
                //         'servType' : vehicle,
                //         'maxCapacity' : maxWeiVol[1],
                //         'maxWeight' : maxWeiVol[0],
                //         'spaceAvail' : maxWeiVol[1] - totalVol,
                //         'weightAvail' : maxWeiVol[0] - totalWeight,
                //         'dateCreated' : dateTime,
                //         'dateDone' : null,
                //     })
                // }
            }

            const shipDoc = doc(db, 'Shipments', shipID);
            const custNotifCol = collection(db, `Users/${orderDocs[shipID][0]}/Notifications`)

            // Updating Shipment Status
            updateDoc(shipDoc, {
                [nextProg] : dateTime
            });
            
            // Adding Notif to Collection
            addDoc(custNotifCol, {
                'orderID': orderDocs[shipID][1],
                'timestamp': dateTime,
                'title' : notifTitle,
                'body' : `Order: ${orderDocs[shipID][1]} ${notifBody}`,
                'dismissed' : false,
            })

            // Send Email
            var data = {
                service_id: 'service_dsa523j',
                template_id: 'template_8vhztoe',
                user_id: 'XsarNf-5YZQOm5oKE',
                template_params: {
                    'email' : orderDocs[shipID][2],
                    'subject' : `OrderID: ${orderDocs[shipID][1]} Status Update`,
                    'body' : `${notifTitle}. \n\n Order: ${orderDocs[shipID][1]} ${notifBody}`
                }
            };

            $.ajax('https://api.emailjs.com/api/v1.0/email/send', {
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json'
            });
        }

        // await checkers.each( async (idx, elem) => {

        //     var shipID = $(elem).val();

        //     if(nextProg == 'forShipping'){

        //         var categ = $(elem).closest('tr').find('input[name="ordCateg"]').val().trim()
        //         var ordID = $(elem).closest('tr').find('.clickRow').text().trim()
        //         var totalWeight = $(elem).closest('tr').find('input[name="totalWeight"]').val().trim()
        //         var totalVol = $(elem).closest('tr').find('input[name="totalVol"]').val().trim();
        //         var vehicle = $(elem).closest('tr').find('input[name="serv-typ"]').val().trim();
        //         var maxWeiVol = getVehiMaxes(vehicle)

        //         console.log("b4 query")
        //         // Deciding the Delivery batch of the order
        //         const delivRef = collection(db, 'DelivBatch');
        //         const q = query( delivRef, where('delivStat', '==', false), where('servType', '==', vehicle), where('category', '==', categ), orderBy('dateCreated') )
        //         const snapQueryer = await getDocs(q).then( (snapQuery) => {
        //             console.log("start each batch")
        //             if(!snapQuery.empty){
        //                 var assigned = false;
                        
        //                 for( let idx in snapQuery.docs){
        //                     var batchDocu = snapQuery.docs[idx];
        //                     var batchData = batchDocu.data();
                            
        //                     if(batchData['spaceAvail'] > totalVol && batchData['weightAvail'] > totalWeight){
        //                         var btchDocRef = doc(db, 'DelivBatch', batchDocu.id)
            
        //                         setDoc(btchDocRef, {
        //                             'ordersList' : { [shipID] : `${ordID}`},
        //                             'spaceAvail' : batchData['spaceAvail'] - totalVol,
        //                             'weightAvail' : batchData['weightAvail'] - totalWeight,
        //                         }, 
        //                         { merge : true});
            
        //                         assigned = true;
        //                         break;
            
        //                     } else {
        //                         continue;
        //                     }
        //                 }
            
        //                 // Pag wala nang batch na may space, create ng bagong batch.
        //                 if(!assigned){
            
        //                     addDoc( collection(db, 'DelivBatch'), {
        //                         'category' : categ,
        //                         'ordersList' : { [shipID] : `${ordID}` },
        //                         'vehicleID' : null,
        //                         'driverID' : null,
        //                         'delivStat' : false,
        //                         'servType' : vehicle,
        //                         'maxCapacity' : maxWeiVol[1],
        //                         'maxWeight' : maxWeiVol[0],
        //                         'spaceAvail' : maxWeiVol[1] - totalVol,
        //                         'weightAvail' : maxWeiVol[0] - totalWeight,
        //                         'dateCreated' : dateTime,
        //                         'dateDone' : null,
        //                     })
        //                 }
            
        //             } else {
        //                 // Pag lahat ng batch delivered na & wala nang pending, create ng bagong batch.
        //                 addDoc( collection(db, 'DelivBatch'), {
        //                     'category' : categ,
        //                     'ordersList' : { [shipID] : `${ordID}` },
        //                     'vehicleID' : null,
        //                     'driverID' : null,
        //                     'delivStat' : false,
        //                     'servType' : vehicle,
        //                     'maxCapacity' : maxWeiVol[1],
        //                     'maxWeight' : maxWeiVol[0],
        //                     'spaceAvail' : maxWeiVol[1] - totalVol,
        //                     'weightAvail' : maxWeiVol[0] - totalWeight,
        //                     'dateCreated' : dateTime,
        //                     'dateDone' : null,
        //                 })
        //             }
                    
        //         })
        //         await delay(1000)
        //         console.log("got Docs")

        //         // If may batch pa na hindi pa nadedeliver at may space pa
        //         // if(!snapQuery.empty){
        //         //     var assigned = false;
        
        //         //     for( let idx in snapQuery.docs){
        //         //         var batchDocu = snapQuery.docs[idx];
        //         //         var batchData = batchDocu.data();
                        
        //         //         if(batchData['spaceAvail'] > totalVol && batchData['weightAvail'] > totalWeight){
        //         //             var btchDocRef = doc(db, 'DelivBatch', batchDocu.id)
        
        //         //             setDoc(btchDocRef, {
        //         //                 'ordersList' : { [shipID] : `${ordID}`},
        //         //                 'spaceAvail' : batchData['spaceAvail'] - totalVol,
        //         //                 'weightAvail' : batchData['weightAvail'] - totalWeight,
        //         //             }, 
        //         //             { merge : true});
        
        //         //             assigned = true;
        //         //             break;
        
        //         //         } else {
        //         //             continue;
        //         //         }
        //         //     }
        
        //         //     // Pag wala nang batch na may space, create ng bagong batch.
        //         //     if(!assigned){
        
        //         //         addDoc( collection(db, 'DelivBatch'), {
        //         //             'category' : categ,
        //         //             'ordersList' : { [shipID] : `${ordID}` },
        //         //             'vehicleID' : null,
        //         //             'driverID' : null,
        //         //             'delivStat' : false,
        //         //             'servType' : vehicle,
        //         //             'maxCapacity' : maxWeiVol[1],
        //         //             'maxWeight' : maxWeiVol[0],
        //         //             'spaceAvail' : maxWeiVol[1] - totalVol,
        //         //             'weightAvail' : maxWeiVol[0] - totalWeight,
        //         //             'dateCreated' : dateTime,
        //         //             'dateDone' : null,
        //         //         })
        //         //     }
        
        //         // } else {
        //         //     // Pag lahat ng batch delivered na & wala nang pending, create ng bagong batch.
        //         //     addDoc( collection(db, 'DelivBatch'), {
        //         //         'category' : categ,
        //         //         'ordersList' : { [shipID] : `${ordID}` },
        //         //         'vehicleID' : null,
        //         //         'driverID' : null,
        //         //         'delivStat' : false,
        //         //         'servType' : vehicle,
        //         //         'maxCapacity' : maxWeiVol[1],
        //         //         'maxWeight' : maxWeiVol[0],
        //         //         'spaceAvail' : maxWeiVol[1] - totalVol,
        //         //         'weightAvail' : maxWeiVol[0] - totalWeight,
        //         //         'dateCreated' : dateTime,
        //         //         'dateDone' : null,
        //         //     })
        //         // }
        //     }

        //     const shipDoc = doc(db, 'Shipments', shipID);
        //     const custNotifCol = collection(db, `Users/${orderDocs[shipID][0]}/Notifications`)

        //     // Updating Shipment Status
        //     updateDoc(shipDoc, {
        //         [nextProg] : dateTime
        //     });
            
        //     // Adding Notif to Collection
        //     addDoc(custNotifCol, {
        //         'orderID': orderDocs[shipID][1],
        //         'timestamp': dateTime,
        //         'title' : notifTitle,
        //         'body' : `Order: ${orderDocs[shipID][1]} ${notifBody}`,
        //         'dismissed' : false,
        //     })

        //     // Send Email
        //     var data = {
        //         service_id: 'service_dsa523j',
        //         template_id: 'template_8vhztoe',
        //         user_id: 'XsarNf-5YZQOm5oKE',
        //         template_params: {
        //             'email' : orderDocs[shipID][2],
        //             'subject' : `OrderID: ${orderDocs[shipID][1]} Status Update`,
        //             'body' : `${notifTitle}. \n\n Order: ${orderDocs[shipID][1]} ${notifBody}`
        //         }
        //     };

        //     $.ajax('https://api.emailjs.com/api/v1.0/email/send', {
        //         type: 'POST',
        //         data: JSON.stringify(data),
        //         contentType: 'application/json'
        //     });

        // })

    } else {

        checkers.each( (idx, elem) => {
            var shipID = $(elem).val();
            const shipDoc = doc(db, 'Shipments', shipID);

            updateDoc(shipDoc, {
                'ordApped' : dateTime,
                'awaitPay' : dateTime
            });

            
            // Adding Notif to Collection
            const custNotifCol2 = collection(db, `Users/${orderDocs[shipID][0]}/Notifications`)
            addDoc(custNotifCol2, {
                'orderID': orderDocs[shipID][1],
                'timestamp': dateTime,
                'title' : notifTitle,
                'body' : `Order: ${orderDocs[shipID][1]} ${notifBody}`,
                'dismissed' : false,
            })

            // Send Email
            var data = {
                service_id: 'service_dsa523j',
                template_id: 'template_8vhztoe',
                user_id: 'XsarNf-5YZQOm5oKE',
                template_params: {
                    'email' : orderDocs[shipID][2],
                    'subject' : `OrderID: ${orderDocs[shipID][1]} Status Update`,
                    'body' : `${notifTitle}. \n\n Order: ${orderDocs[shipID][1]} ${notifBody}`
                }
            };

            $.ajax('https://api.emailjs.com/api/v1.0/email/send', {
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json'
            });
        })

        
    }

    $('#statForm').submit()

})








        
