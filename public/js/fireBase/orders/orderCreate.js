// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
  
import { getFirestore, collection, addDoc, connectFirestoreEmulator,
    query, getDoc, getDocs, setDoc, doc, where,orderBy, deleteDoc, onSnapshot, QuerySnapshot } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";


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

/* City Part */

const docRef = doc(db, 'Address', 'Cities')
const cities = await getDoc(docRef).then( (snap) => {
    return snap.data()
})
const citiVal = Object.values(cities).sort()

/* Changing Option Values */
var citiEl = $("#city");
citiEl.empty();
citiEl.append(
    $('<option/>').attr({"value": '', 'disabled': 'disabled', 'selected':'selected', 'hidden':'hidden'}).text("-- Choose a City --")
)
citiVal.forEach( (item, idx) => {
    citiEl.append(
        $('<option/>').attr("value", item).text(item)
    )
})

/* Barangay Part */

const barangRef = doc(db, 'Address', 'Barangays')
const barangs = await getDoc(barangRef)
var barangEl = $("#barang");
var zipEl = $('#zipcode');

var opts = null;

$("#city").on('change', function() {
    
    var selected = $("#city option:selected").val()
    opts = barangs.get(selected)
    var optsLoc = Object.keys(opts).sort()
    var optsZip = Object.values(opts)

    // Change Barangay Options
    barangEl.empty()
    barangEl.append(
        $('<option/>').attr({"value": '', 'disabled': 'disabled', 'selected':'selected', 'hidden':'hidden'}).text("-- Choose --")
    )
    optsLoc.forEach( (item) => {
        barangEl.append(
            $('<option/>').attr("value", item).text(item)
        )
    })
    zipEl.val('');

})

// Auto Zipcode

barangEl.on('change', function() {
    var barVal = barangEl.val()
    var zippy = opts[barVal]

    zipEl.val(zippy)

})



