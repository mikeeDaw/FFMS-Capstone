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

const delay = ms => new Promise(res => setTimeout(res, ms));
var servsRef = doc(db, 'Vehicles', 'ServiceTypes')
var packsRef = doc(db, 'Vehicles', 'PackTypes')
var chrgRef = doc(db, 'Prices', 'Charges')
var perKMRef = doc(db, 'Prices', 'ServicePerKM')
var packRef = doc(db, 'Prices', 'PackagingCost')
var servTypRef = doc(db, 'Prices', 'ServiceType')

var getDocus = [ servTypRef, perKMRef, packRef, chrgRef, servsRef, packsRef ];
var docuData = [];

getDocus.forEach( async (ref) => {
    console.log('getting data')
    let snapCont = await getDoc(ref);
    docuData.push(snapCont.data());
    console.log("got Data");
})

await delay(2600)

const servVehi = docuData[4];
const packers = docuData[5];
const chargers = { Insurance : 'Insurance', ServiceChg : 'Service Charge', TotalCost : 'Total Cost' }

console.log(docuData);
console.log(servVehi, packers)

$(function() {

    console.log("docu REady")

    // Service Type Populate
    for ( let [key, val] of Object.entries(docuData[0])){
        var vehiName = servVehi[key]['name']
        $('#servTbl').append(
            $('<tr/>').append(
                $('<td/>', {'class' : 'text-start', 'text' : vehiName}),
                $('<td/>', {'class' : 'text-center', 'text' : `₱ ${val}` })
            )
        )
    }

    // Per KM Populate
    for ( let [key, val] of Object.entries(docuData[1])){
        var vehiName = servVehi[key]['name'];
        $('#perKmTbl').append(
            $('<tr/>').append(
                $('<td/>', {'class' : 'text-start', 'text' : vehiName}),
                $('<td/>', {'class' : 'text-center', 'text' : `₱ ${val}` })
            )
        )
    }

    // Packaging Type Populate
    for ( let [key, val] of Object.entries(docuData[2])){
        var packName = packers[key];
        $('#packTbl').append(
            $('<tr/>').append(
                $('<td/>', {'class' : 'text-start', 'text' : packName}),
                $('<td/>', {'class' : 'text-center', 'text' : `₱ ${val}` })
            )
        )
    }

    // Charges Populate
    for ( let [key, val] of Object.entries(docuData[3])){
        var chrgName = chargers[key];
        let percent = val * 100;
        $('#chrgTbl').append(
            $('<tr/>').append(
                $('<td/>', {'class' : 'text-start', 'text' : chrgName}),
                $('<td/>', {'class' : 'text-center', 'text' : `${percent} %` })
            )
        )
    }

    $('#cover').css({'opacity' : '0', 'pointer-events' : 'none'});
    
})


