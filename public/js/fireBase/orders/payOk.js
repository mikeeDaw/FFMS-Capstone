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

console.log(orderData, datePaid, orderID, shipID)

// Update Shipment

const shipDocRef = doc(db, 'Shipments', shipID);
updateDoc(shipDocRef, {
    payVerif : datePaid
})

// Update Order

const orderDocRef = doc(db, 'Orders', orderID);
updateDoc(orderDocRef, {
    balance : orderData['total'] - paid
})


// Send Notif 
const notTit = 'Payment Received';
const notBod = `Payment for Order ${orderID} was made.`;

const orgNotifRef = collection(db, 'OrgNotif')
addDoc(orgNotifRef, {
    'orderID': orderID,
    'timestamp': datePaid,
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
        'subject' : `OrderID: ${orderID} Payment Recieved.`,
        'body' : `${notTit}. \n\n ${notBod}`
    }
};


$.ajax('https://api.emailjs.com/api/v1.0/email/send', {
    type: 'POST',
    data: JSON.stringify(data),
    contentType: 'application/json'
});

$(async function() {

    // Statistics Area

    const shipStat = doc(db, 'Statistics', 'ShipmentStats')
    const shipSnap = await getDoc(shipStat)
    const statData = shipSnap.data();

    updateDoc(shipStat, {
        awaitPay : (statData['awaitPay'] - 1),
        payVerif : (statData['payVerif'] + 1)
    })


    // Sales
    const weekDoc = doc(db, 'Sales', year, monthW, `Week${weekNum}`);
    const mdCol = collection(db, 'Sales', year, monthW, `Week${weekNum}`, `${monthN}-${day}`)
    addDoc(mdCol, {
        amount : Number(paid),
        orderID : orderID
    })

    var weekSnap = await getDoc(weekDoc)
    var weekData = weekSnap.data()
    console.log(weekData === undefined, weekData)
    if(weekData === undefined || Object.keys(weekData).length == 0){
        setDoc(weekDoc, {
            week : Number(weekYr)
        }, { merge : true});
    }


})