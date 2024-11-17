// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
  
import { getFirestore, collection, addDoc,
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
const delay = ms => new Promise(res => setTimeout(res, ms));
const servRef = doc(db, 'Statistics', 'ServiceStats');
const categRef = doc(db, 'Statistics', 'CategoryStats');
const packRef = doc(db, 'Statistics', 'PackagingStats');
var types = [servRef, categRef, packRef];
var data = [];
// var servData;
// var categData;
// var packData;

types.forEach( async (docuRef) => {
    console.log('start')
    const snapHolder = await getDoc(docuRef);
    data.push(snapHolder.data());
    console.log('gotted')
})

await delay(2300)
console.log(data)

// console.log('start')
// const servData = await getDoc(servRef).then((snap) => { return snap.data()})
// console.log('got service')
// const categData = await getDoc(categRef).then((snap) => { return snap.data()})
// console.log('got category')
// const packData = await getDoc(packRef).then((snap) => { return snap.data()})
// console.log('got package')

$('.loaderBox').css({'opacity' : '0', 'pointer-events' : 'none'})

var servKeys = []
Object.keys(data[0]).forEach((item) => {
    switch(item){
        case 'TrackHead':
            servKeys.push('Tractor Head');
            break;
        case 'ClVan6':
            servKeys.push('Closed Van 6-W');
            break;
        case 'Chassis20':
            servKeys.push('20ft Chassis');
            break;
        case 'Chassis40':
            servKeys.push('40ft Chassis');
            break;
        case 'Truck10W':
            servKeys.push('Truck 10-W');
            break;
        case 'WingVan':
            servKeys.push('Wing Van');
            break;
        case 'ClVan4':
            servKeys.push('Closed Van 4-W');
            break;
    }
})

var packKeys = []
Object.keys(data[2]).forEach((item) => {
    switch(item){
        case 'Box10':
            packKeys.push('10kg Box');
            break;
        case 'Box25':
            packKeys.push('25kg Box');
            break;
        case 'Envelope':
            packKeys.push('Envelope');
            break;
        case 'ReusePak':
            packKeys.push('Reusable Pak');
            break;
        case 'Tube':
            packKeys.push('Tube');
            break;
    }
})

/* Charts */

// Pie Chart
var pieChartClass;

pieChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'doughnut',
            data: {
                labels: servKeys,
                datasets: [{
                    label: 'Service Type',
                    data: Object.values(data[0]),
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(211, 205, 86)',
                        'rgb(158, 25, 86)',
                        'rgb(58, 77, 227)',
                        'rgb(8, 209, 16)'
                    ],
                    hoverOffset: 4,
                    }],
                },
            
        })
    }
}

// Bar Chart

var barChartClass;

barChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data[1]),
                datasets: [{
                  label: 'Categories',
                  data: Object.values(data[1]),
                  backgroundColor: [
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                  ],
                  borderColor: [
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                  ],
                  borderWidth: 1
                }]              
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                      ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}},
                      },
                      suggestedMin: 0,
                      suggestedMax: 10,
                    }
                  }
            }
        })
    }
}

// Packaging Bar

var packChartClass;

packChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'bar',
            data: {
                labels: packKeys,
                datasets: [{
                  label: 'Package Type',
                  data: Object.values(data[2]),
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                  ],
                  borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                  ],
                  borderWidth: 1
                }]              
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                      ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}},
                      },
                      suggestedMin: 0,
                      suggestedMax: 10,
                    }
                  }
            }
        })
    }
}

$(function() {

    const pchart = $('#pieChart');
    pieChartClass.ChartData(pchart);

    const bchart = $('#barChart');
    barChartClass.ChartData(bchart);

    const packB = $('#packBar');
    packChartClass.ChartData(packB);

});

const sub = onSnapshot(doc(db,'Statistics', 'ShipmentStats'), (doc) => {
    var docData = doc.data();
    $('#approve').text(docData['apprOrder']);
    $('#awaitPay').text(docData['awaitPay']);
    $('#paid').text(docData['payVerif']);
    $('#arrived').text(docData['arrived']);
    $('#examine').text(docData['underExamine']);
    $('#forShip').text(docData['forShipping']);
    $('#inDeliv').text(docData['outForDelivery']);
    $('#forCancel').text(docData['cancelled']);
})







