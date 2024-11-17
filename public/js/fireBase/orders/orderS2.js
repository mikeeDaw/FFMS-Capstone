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

var packsRef = doc(db, 'Vehicles', 'PackTypes')
console.log("getting pack")
var packSnap = await getDoc(packsRef)
var packData = packSnap.data();
console.log("got pack")
console.log(packData)

$(function() {

    $('#itm-pack option[value="wait"]').remove()
    for (const [key, val] of Object.entries(packData)){
        $('#itm-pack').append(
            $('<option/>', {'value' : key, 'text' : val })
        )
    }

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

// Static Dimensions each Vehicle type
// const services = {
//     'ClVan4' : {
//         'length' : 3.05,
//         'width' : 1.83,
//         'height' : 1.83,
//         'maxVolume' : 10.4,
//         'maxWeight' : 4000
//     },
//     'ClVan6' : {
//         'length' : 4,
//         'width' : 2,
//         'height' : 2,
//         'maxVolume' : 16,
//         'maxWeight' : 9000
//     },
//     'WingVan' : {
//         'length' : 4.7,
//         'width' : 2.4,
//         'height' : 2.4,
//         'maxVolume' : 25.78,
//         'maxWeight' : 13000
//     },
//     'Truck10W' : {
//         'length' : 5.5,
//         'width' : 2.3,
//         'height' : 2.3,
//         'maxVolume' : 29.01,
//         'maxWeight' : 15000
//     },
//     'Chassis20' : {
//         'length' : 6.5,
//         'width' : 2.35,
//         'height' : 2.35,
//         'maxVolume' : 33.14,
//         'maxWeight' : 25000
//     },
//     'Chassis40' : {
//         'length' : 12.19,
//         'width' : 2.35,
//         'height' : 2.35,
//         'maxVolume' : 67.32,
//         'maxWeight' : 30000
//     },
//     'TrackHead' : {
//         'length' : 16.2,
//         'width' : 2.6,
//         'height' : 2.6,
//         'maxVolume' : 109.51,
//         'maxWeight' : 40000
//     }

// }

// Manila Branch Coords
const lebLat = 14.567366514603227
const lebLong = 120.98767322811389
var rtDist = 0;
var map = L.map('map');

$(async function() {

    // Getting Distance
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var city = reciever['city'];
    var zipcode = reciever['zipcode'];
    var barangay = reciever['barang'];
    var street = reciever['street'];
    var prov = reciever['province'];

    var list = [street, barangay, city, zipcode, prov];
    var stuff = []

    list.forEach(function(item){
      if(item){
        stuff.push(item)
      }
    })

    var toQuer = stuff.join(", ")
    console.log(toQuer)
    var xd = await $.get( 'https://nominatim.openstreetmap.org/search?format=json&limit=3&q=' + toQuer, async function(data){
        if(data.length > 0){
          console.log('Has Value')
          // Get Route of First Result
          await getRoute(data[0].lat, data[0].lon)    
        } else{
          stuff.shift()
          toQuer = stuff.join(", ")
          //Layer 2
          if(stuff.length > 2){
              $.get('https://nominatim.openstreetmap.org/search?format=json&limit=3&q=' + toQuer, function(data){
              if(data.length > 0){
                console.log('Has Value')
                // Get Route of First Result
                getRoute(data[0].lat, data[0].lon)
              } else{    
                //console.log('waley nakita layer 2')     
                $("input[name=distance]").val(1.8)
                $("input:hidden[name=route]").val(' ')
              }
              return
            })
          } else{
            //console.log("waley nakita")
            $("input[name=distance]").val(1.8)
            $("input:hidden[name=route]").val(' ')
          }
        }
        return
      })


    // Assigning of Service Type
    $('#s2form').one('submit', async function(e) {
        e.preventDefault();
        
        var weightKG = $('#weight').val() * $('#quant').val();
        var lengthM = $('#lg').val() / 100;
        var widthM = $('#wd').val() / 100;
        var heightM = $('#hg').val() / 100;
        var packVol = (lengthM * widthM * heightM);
        console.log(weightKG, lengthM, widthM)
        // Deciding which service type for order:
        for( let objVal of servList){
            
            let critWg = weightKG < objVal['maxWeight'];
            let critL = lengthM < objVal['length'];
            let critWd = widthM < objVal['width'];
            let critH = heightM < objVal['height'];

            if(critWd && critL && critWg && critH){

                $('#serv-typ').val(objVal['serv'])
                $('#packVol').val(packVol)
                break;
            }
        }
        console.log($('#serv-typ').val())

        $('#loadArea').show()
        $('#loadOverlay').show()
        
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
  
        rtDist = (summary.totalDistance / 1000).toFixed(2)
        var trvlTime = Math.round(summary.totalTime % 3600 / 60)
    
        console.log("route found") 
        $("input:hidden[name=distance]").val((rtDist < 1) ? 1 : rtDist)
        $("input:hidden[name=route]").val(routes[0].name)
        let timeTaken = Date.now() - start;
        console.log(timeTaken);
  
      }).addTo(map);
      console.log("route end")
  }