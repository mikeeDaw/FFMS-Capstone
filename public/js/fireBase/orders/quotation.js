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
const delay = ms => new Promise(res => setTimeout(res, ms));
/* Distance Init */
const lebLat = 14.567366514603227
const lebLong = 120.98767322811389
var rtDist = 0;
var route = '';
console.log("OK!");

var map = L.map('map');

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);



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
    $('<option/>').attr({"value": '', 'disabled': 'disabled', 'selected':'selected', 'hidden':'hidden'}).text("City *")
)
citiVal.forEach( (item, idx) => {
    citiEl.append(
        $('<option/>').attr("value", item).text(item)
    )
})

console.log("getting vehi")
var servsRef = doc(db, 'Vehicles', 'ServiceTypes')
var servSnap = await getDoc(servsRef)
var servData = servSnap.data();
console.log("got vehi")
var servList = [];

for (var vkey in servData){
    servList.push({ serv : vkey, ...servData[vkey] })
}

// (From Database) Dimensions each Vehicle type
servList.sort( (a,b) => {
    if(a['maxVolume'] <= b['maxVolume']){ return -1; }
    else { return 1 }
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
        $('<option/>').attr({"value": '', 'disabled': 'disabled', 'selected':'selected', 'hidden':'hidden'}).text("Barangay *")
    )
    optsLoc.forEach( (item) => {
        barangEl.append(
            $('<option/>').attr("value", item).text(item)
        )
    })
    zipEl.val('')
})

// Auto Zipcode

barangEl.on('change', function() {
    var barVal = barangEl.val()
    var zippy = opts[barVal]

    $('#zipcode').val(zippy)

})

$(async function() {

    $("#quoteForm").one('submit', async function(e) {
        e.preventDefault();
        
        var weightKG = $('#weight').val();
        var lengthM = $('#length').val() / 100;
        var widthM = $('#width').val() / 100;
        var heightM = $('#height').val() / 100;
        var packVol = (lengthM * widthM * heightM);

        // Deciding which service type for order:
        for( let objVal of servList){
            
            let critWg = weightKG < objVal['maxWeight'];
            let critL = lengthM < objVal['length'];
            let critWd = widthM < objVal['width'];
            let critH = heightM < objVal['height'];

            if(critWd && critL && critWg && critH){
                $('#service').val(objVal['serv'])
                break;
            }
        }
        console.log($('#service').val())

        $('#loadArea').show()
        $('#loadOverlay').show()

        // Distance Calculation
        var city = $('#city').val()
        var zipcode = $('#zipcode').val()
        var barangay = $('#barang').val()
        var street = $('#street').val() != '' ? $('input[name=street]').val() : ' '
        var prov = $('#province').val()

        var list = [street, barangay, city, zipcode, prov]
        var stuff = []

        list.forEach(function(item){
            if(item){
              stuff.push(item)
            }
        })

        var toQuer = stuff.join(", ")
        console.log("before get")
        console.log(toQuer)
        var xd = await $.get( 'https://nominatim.openstreetmap.org/search?format=json&limit=3&q=' + toQuer, async function(data){
          if(data.length > 0){
            console.log('Has Value')
      
            // Get Route of First Result
            console.log("in get")
            await getRoute(data[0].lat, data[0].lon)
            
          } else{
      
            stuff.shift()
            toQuer = stuff.join(", ")
            console.log(stuff)
            //Layer 2
            if(stuff.length > 2){
      
                $.get('https://nominatim.openstreetmap.org/search?format=json&limit=3&q=' + toQuer, function(data){
                if(data.length > 0){
                  console.log('Has Value')
                  // Get Route of First Result
                  getRoute(data[0].lat, data[0].lon)
      
                } else{         
                  $("input[name=distance]").val(0)
                }
                return
              })
      
            } else{
      
              $("input[name=distance]").val(0)
            }
          }
          return
        })

        // Delay for 2.7 sec to wait for the result of map.
        await delay(2500);
        $(this).submit();
    }) 

})

async function getRoute(targLat, targLon){
    console.log("route start")
    let start = Date.now();
    var distance = await L.Routing.control({
      waypoints: [
        L.latLng(lebLat, lebLong),
        L.latLng(targLat, targLon)
      ],
      // router: L.Routing.graphHopper('aec43ac0-80dc-4ed3-87a4-370537755abd'), 
      show: true
    }).on('routesfound', async function(e) {
        console.log('route findiing start')
        var routes = e.routes;
        var summary = routes[0].summary;
  
        rtDist = summary.totalDistance / 1000
        var trvlTime = Math.round(summary.totalTime % 3600 / 60)
    
        console.log("route found") 
        $("input:hidden[name=distance]").val((rtDist < 1) ? 1 : rtDist)
        $("input:hidden[name=route]").val(routes[0].name)
        let timeTaken = Date.now() - start;
        console.log(timeTaken);
  
      }).addTo(map);
      console.log(distance);
      console.log("route end")
  }
