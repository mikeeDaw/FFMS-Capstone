// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";

import { getFirestore, collection, addDoc, connectFirestoreEmulator,
    query, getDoc, orderBy, getDocs, setDoc, doc, deleteDoc, onSnapshot, QuerySnapshot } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";


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

console.log(app)
  
const FrStore = getFirestore(app); // removing 'app' for emulator linking.

// const docRef = doc(FrStore, "Orders", "173acb3756ec448384b1")
// const docSnap = await getDoc(docRef)
// if(docSnap.exists()){
//     console.log(docSnap.data())
// }else{
//     console.log("nonce")
// }

// const unsub =  onSnapshot(doc(FrStore, "Orders", "173acb3756ec448384b1"), (doc) => {
//     var docyData = doc.data()
//     console.log("Updated!");
// });

// var newImg = $('#newOrdImg').val()
// var noticeImg = $('#noticeImg').val()

// var initDone = false
// var notifBar = $('#notifDrop');

// const q = query(collection(FrStore, 'Users/FInocX3FrLUeNcly7QSLcxHYb0M2/Notifications'), orderBy('timestamp', 'desc'))
// const unsub = onSnapshot(q,(querySnap) => {
    
//     if(!initDone){
//         querySnap.forEach( (doc) => {
//             var data = doc.data()
//             notifBar.append(
//                 $('<a/>', {'class' : 'dropdown-item', 'href' : 'some#'}).append(

//                     $('<div/>', {'class' : 'item-thumbnail'}).append(
//                         $('<img/>', {'src' : (data['title'].startsWith('New') ?  newImg : noticeImg ) })
//                     ),

//                     $('<div/>', {'class' : 'item-content flex-grow w-100'}).append(
              
//                         $('<div/>', {'class' : 'd-flex align-items-center justify-content-between'}).append(

//                             $('<span/>', { 'class' : 'content-title fw-normal ws-norm', 'text' : data['title']}),
//                             $('<span/>', { 'class' : 'fs-12 text-secondary', 'text' : data['timestamp']})
//                         ),
                        
//                         $('<div/>', {'class' : 'fw-normal text-muted mb-0 ws-norm fs-12', 'text' : data['body']})
//                     )
//                 )
//             )
//         })
//     }
// })