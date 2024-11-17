// Lebria Lati & Longti
// 14.567366514603227, 120.98767322811389

const lebLat = 14.567366514603227
const lebLong = 120.98767322811389
var rtDist = 0;
var route = '';
console.log("OK!");


/* Distance Calc */
var map = L.map('map');

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


$("form").one('submit', async function(e){

  e.preventDefault();

  let start = Date.now();
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

  const delay = ms => new Promise(res => setTimeout(res, ms));

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

  $('#loadArea').show()
  $('#loadOverlay').show()

  let timeTaken = Date.now() - start;
  console.log(timeTaken);

  console.log("submit end")

  // Delay for 2.7 sec to wait for the result of map.
  await delay(2700);
  $(this).submit();
  
  });


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
      trvlTime = Math.round(summary.totalTime % 3600 / 60)
  
      console.log("route found") 
      $("input[name=distance]").val((rtDist < 1) ? 1 : rtDist)
      $("input[name=route]").val(routes[0].name)
      let timeTaken = Date.now() - start;
      console.log(timeTaken);

    }).addTo(map);
    console.log(distance);
    console.log("route end")
}


