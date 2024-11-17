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
const dateTime = dateInst.toJSON().slice(0, 10) + ' ' + dateInst.getHours() + ':' + dateInst.getMinutes();
var spaceArea = Number( (((packOrd['lg']/ 100) * (packOrd['wd']/ 100) * (packOrd['hg']/ 100)) * packOrd['quant']).toFixed(1) );
var totalWeight = Number( (packOrd['weight'] * packOrd['quant']).toFixed(1) );

var maxWeig;
var maxSpace;

switch(packOrd['serv-typ']){
    case 'ClVan4':
        maxWeig = 4000;
        maxSpace = 10.4;
        break;
    case 'ClVan6':
        maxWeig = 9000;
        maxSpace = 16;
        break;
    case 'WingVan':
        maxWeig = 13000;
        maxSpace = 25.78;
        break;
    case 'Truck10W':
        maxWeig = 15000;
        maxSpace = 29.01;
        break;
    case 'Chassis20':
        maxWeig = 25000;
        maxSpace = 33.14;
        break;
    case 'Chassis40':
        maxWeig = 30000;
        maxSpace = 67.32;
        break;
    case 'TrackHead':
        maxWeig = 40000;
        maxSpace = 109.51;
        break;
}

$( async function() {

    console.log(spaceArea, totalWeight)

    $('#checkoutForm').one('submit', async function(e) {
        e.preventDefault();

        var payMethod = $('input[name=pay]:checked').val()
        // Prep the order Details
        var miscProp = {
            'User' : currUser,
            'CreatedAt' : dateInst.toJSON().slice(0, 10),
            'timeCreated' : dateInst.getHours() + ':' + dateInst.getMinutes(),
            'payStatus' : false,
            'status' : 0,
            'pay_date' : null,
            'rpt_img_name' : null,
            'cancelReason' : null,
            'balance' : charges['total'],
            'pay' : payMethod,
            'totalWeight' : totalWeight,
            'totalVol' : spaceArea,
            
        }
        var orderData = { ...packOrd, ...consig, ...charges, ...miscProp};
        // Save order to Database
        var orderRef = collection(db, 'Orders');
        var orderDocu = await addDoc(orderRef, orderData);
       // Prep shipment data
        var shipData = {
            'customerID' : currUser,
            // 'driverID' : null,
            // 'vehicleID' : null,
            'orderID' : orderDocu.id,
            'orderCreated' : dateTime,
            'apprOrder' : dateTime,
            'ordApped' : null,
            'awaitPay' : null,
            'payVerif' : null,
            'arrived' : null,
            'underExamine' : null,
            'forShipping' : null,
            'outForDelivery' : null,
            'completed' : null,
            // 'cancelNotice' : null,
            'cancelled' : null,
        }

        // *DITO YUNG SHOW LOADING
        $('#loadArea').show()
        $('#loadOverlay').show()

        // Add shipment document to DB
        var shipmentsRef = collection(db, 'Shipments') 
        var shipDocu = await addDoc(shipmentsRef, shipData)
    
        // Update Statisticss
        var shipRef = doc(db, 'Statistics', 'ShipmentStats');
        var servRef = doc(db, 'Statistics', 'ServiceStats');
        var categRef = doc(db, 'Statistics', 'CategoryStats');
        var packRef = doc(db, 'Statistics', 'PackagingStats');
        var shipStat = await getDoc( shipRef ).then((doc1) => {return doc1.data() });
        var servStat = await getDoc( servRef ).then((doc2) => {return doc2.data() });
        var categStat = await getDoc( categRef ).then((doc3) => {return doc3.data() });
        var packStat = await getDoc( packRef ).then((doc4) => {return doc4.data() });
    
        updateDoc(shipRef, {
            'apprOrder' : shipStat['apprOrder'] + 1,
        });
        updateDoc(servRef, {
            [packOrd['serv-typ']] : servStat[packOrd['serv-typ']] + 1,
        });
        updateDoc(categRef, {
            [packOrd['itm-categ']] : categStat[packOrd['itm-categ']] + 1,
        });
        updateDoc(packRef, {
            [packOrd['itm-pack']] : packStat[packOrd['itm-pack']] + 1,
        });
    
    
        // Send Notif 
        const notTit = 'New Order Created';
        const notBod = `Order ${orderDocu.id} was created. Please review the order.`;
    
        const orgNotifRef = collection(db, 'OrgNotif')
        addDoc(orgNotifRef, {
            'orderID': orderDocu.id,
            'timestamp': dateTime,
            'title' : notTit,
            'body' : notBod,
            'dismissed' : false,
        })
    
        var data = {
            service_id: 'service_dsa523j',
            template_id: 'template_8vhztoe',
            user_id: 'XsarNf-5YZQOm5oKE',
            template_params: {
                'email' : 'lebria.project@gmail.com',
                'subject' : `OrderID: ${orderDocu.id} was Created`,
                'body' : `${notTit}. \n\n ${notBod}`
            }
        };
    
        $.ajax('https://api.emailjs.com/api/v1.0/email/send', {
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json'
        });
    
        $(this).submit();
    })

})