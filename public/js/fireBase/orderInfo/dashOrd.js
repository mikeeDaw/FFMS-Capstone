// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
  
import { getFirestore, collection, addDoc, connectFirestoreEmulator,
    query, getDoc, getDocs, setDoc, doc, where, deleteDoc, onSnapshot, QuerySnapshot } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";


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

function progressAdd(date, text, lastUp = false, lastDown = false){

    $('#progWrapper').append(
        $('<div/>', {'class': 'd-flex flex-row ps-5 ps-md-3'}).append(
    
            $('<div/>', {'class': 'progArea'}).append(
                $('<div/>', {'class': 'd-flex flex-column align-items-center justify-content-center'}).append(

                    (lastUp == false ? '<div class="progBar"> </div>' : ''),

                    $('<div/>', {'class': 'progDot'}).append(
                        $('<ion-icon/>', {'class': 'progArea', 'name' : 'checkmark', 'style' : 'font-size: 20px;'})
                    ),

                    (lastDown == false ? '<div class="progBarBottom"> </div>' : ''),

                )
            ),
    
            $('<div/>', {'class': 'd-flex flex-column justify-content-center align-items-start textArea flex-grow-1  ps-md-3'}).append(
                $('<div/>', {'class': 'd-flex flex-column justify-content-center align-items-start textArea flex-grow-1 ps-5 ps-md-3'}).append(
    
                    $('<span/>', {'class': 'fs-11', 'text' : date}),
                    $('<span/>', {'text' : text}),
                )
            )
        )
    )

}

const q = query( collection(db, 'Shipments'), where("orderID", "==", orderID) );
const sub = onSnapshot(q,(querySnap) => {


    var shipData = querySnap.docs[0].data()
    var shipID = querySnap.docs[0]['id']
    var progress = null;

    $("#progWrapper").css('height', 'auto');
    $("#loaderShip").hide();
    
    $("#progWrapper").empty()

    switch(true){
        case (shipData['completed'] !== null):
            progress = progress ?? 'completed';  
            progressAdd(shipData['completed'], "Delivery Completed", true, false)
    
        case (shipData['outForDelivery'] !== null):
            progress = progress ?? 'outForDelivery'; 
            progressAdd(shipData['outForDelivery'], "Out For Delivery")
    
        case (shipData['forShipping'] !== null):
            progress = progress ?? 'forShipping'; 
            progressAdd(shipData['forShipping'], "Preparing For Shipment")
    
        case (shipData['underExamine'] !== null):
            progress = progress ?? 'underExamine'; 
            progressAdd(shipData['underExamine'], "Under Examination")
    
        case (shipData['arrived'] !== null):
            progress = progress ?? 'arrived'; 
            progressAdd(shipData['arrived'], "Package Arrived at Branch")
    
        case (shipData['payVerif'] !== null):
            progress = progress ?? 'payVerif'; 
            progressAdd(shipData['payVerif'], "Payment Verified")
    
        case (shipData['awaitPay'] !== null):
            progress = progress ?? 'awaitPay'; 
            progressAdd(shipData['awaitPay'], "Awaiting for Payment")
    
        case (shipData['ordApped'] !== null):
            progress = progress ?? 'ordApped'; 
            progressAdd(shipData['ordApped'], "Order Approved")
    
        case (shipData['apprOrder'] !== null):
            progress = progress ?? 'apprOrder'; 
            progressAdd(shipData['apprOrder'], "Order For Approval")
            progressAdd(shipData['apprOrder'], "Order Created", false, true)
            break;
    }

    // Showing/Disabling Elements Area
    if(shipData['cancelled'] === null && shipData['payVerif'] === null){
        $('#dismiss').show();
    }

    if(shipData['awaitPay'] === null && shipData['cancelled'] === null){
        $('#editOrd').show();
    }

    if(shipData['payVerif'] !== null){
        $('#verifyBtn').prop('disabled', true);
    }else {
        $('#verifyBtn').prop('disabled', false);
    }

    if(shipData['payVerif'] === null && shipData['cancelled'] === null){
        $('#rejectDialog').show();
    }
    
    $('#hiddenShipID').val(shipID)
    $('#dismissConfirm').attr("href", `/order/dismiss/'.${shipID}."?current=${progress}`)
    
})


/* Listener when order document is updated */

var initDone = false

const listener =  onSnapshot(doc(db, "Orders", orderID), (doc) => {

    if(!initDone){
        initDone = true;
    } else {
        location.reload()
    }
});
