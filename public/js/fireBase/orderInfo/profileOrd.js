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
    
            $('<div/>', {'class': 'd-flex flex-column justify-content-center align-items-start textArea flex-grow-1 ps-5 ps-md-3'}).append(
                $('<div/>', {'class': 'd-flex flex-column justify-content-center align-items-start textArea flex-grow-1 ps-md-3'}).append(
    
                    $('<span/>', {'class': 'fs-11', 'text' : date}),
                    $('<span/>', {'text' : text}),
                )
            )
        )
    )

}

var shipInit = false
var progress = null;

const q = query( collection(db, 'Shipments'), where("orderID", "==", orderID) );
const sub = onSnapshot(q,(querySnap) => {

    var shipData = querySnap.docs[0].data()

    $("#progWrapper").css('height', 'auto');
    $("#loaderShip").hide();
    
    $("#progWrapper").empty()

    if(shipData['cancelled'] !== null){
        progressAdd(shipData['cancelled'], "Order Cancelled", true, false);
    }
    switch(true){
        case (shipData['completed'] !== null):
            progressAdd(shipData['completed'], "Delivery Completed", true, false);
            progress = progress ?? 'completed';
        case (shipData['outForDelivery'] !== null):
            progressAdd(shipData['outForDelivery'], "Out For Delivery")
            progress = progress ?? 'outForDelivery';
        case (shipData['forShipping'] !== null):
            progressAdd(shipData['forShipping'], "Preparing For Shipment")
            progress = progress ?? 'forShipping';
        case (shipData['underExamine'] !== null):
            progressAdd(shipData['underExamine'], "Under Examination")
            progress = progress ?? 'underExamine';
        case (shipData['arrived'] !== null):
            progressAdd(shipData['arrived'], "Package Arrived at Branch")
            progress = progress ?? 'arrived';
        case (shipData['payVerif'] !== null):
            progressAdd(shipData['payVerif'], "Payment Verified")
            progress = progress ?? 'payVerif';
        case (shipData['awaitPay'] !== null):
            progressAdd(shipData['awaitPay'], "Awaiting for Payment")
            progress = progress ?? 'awaitPay';
        case (shipData['ordApped'] !== null):
            progressAdd(shipData['ordApped'], "Order Approved")
            progress = progress ?? 'ordApped';
        case (shipData['apprOrder'] !== null):
            progressAdd(shipData['apprOrder'], "Order For Approval")
            progressAdd(shipData['apprOrder'], "Order Created", false, true)
            progress = progress ?? 'apprOrder';
            break;
    }

    $('#canceller').attr('action', `/cancelOrder/${querySnap.docs[0].id}?current=${progress}`)

    // Showing/Disabling Elements Area
    if(shipData['cancelled'] === null && shipData['payVerif'] === null){
        $('#cancelBtn').removeAttr("disabled");
    }

    if(shipData['awaitPay'] === null){
        $('#payDiscl').show()
    } else {
        $('#payDiscl').hide()
    }
    
    if(shipData['awaitPay'] !== null && shipData['payVerif'] === null){
        // $('#receipt').removeAttr('disabled')
        // $('#recLbl').css({'opacity': '1', 'pointer-events':'all'})
        $('#payRedirect').css({'pointer-events' : 'all', 'opacity' : '1' });
    } else if(shipData['awaitPay'] === null) {
        $('#proceedDisc').append(
            $('<div/>', {'class': 'mt-2 fs-12 text-center text-primary', 'text' : 'You can proceed to payment once your order is approved.'})
        )
    }
    
})



console.log("after hide")


/* Listener when order document is updated */

var initDone = false

const listener =  onSnapshot(doc(db, "Orders", orderID), (doc) => {

    if(!initDone){
        initDone = true;
    } else {
        location.reload()
    }
});
