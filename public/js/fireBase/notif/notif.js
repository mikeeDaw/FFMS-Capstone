// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
//import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
  
import { getFirestore, collection, addDoc, connectFirestoreEmulator,
    query, getDoc, orderBy, limit, getDocs, setDoc, doc, where, deleteDoc, onSnapshot, QuerySnapshot } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";


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

var newImg = $('#newOrdImg').val()
var noticeImg = $('#noticeImg').val()

var initDone = false
var notifBar = $('#notifDrop');
var notifHead = $('#notifHead');
var reddot = $('#reddot');

if(userID !== null){
    if(userLvl == "User"){
        var notifQ = query(collection(db, `Users/${userID}/Notifications`), orderBy('timestamp', 'desc'))
        const notifDocs = await getDocs(notifQ)
        
        $('#loader').css("display", "none")
        
        if(notifDocs.empty){
            $('#emptySpace').css("display", "flex")
        } else {
            // Initial Outputing of notifs
            notifDocs.forEach( (doc) => {
            
                var data = doc.data() 
                notifBar.append(
                    $('<a/>',
                     {'class' : 'dropdown-item',
                      'href' : (userLvl == "User" ? `/profile/orders/${data['orderID']}` : `/dash/orders/${data  ['orderID']}`) }).append(
            
                        $('<div/>', {'class' : 'item-thumbnail'}).append(
                            $('<img/>', {'src' : (data['title'].startsWith('New') ?  newImg : noticeImg ) })
                        ),
            
                        $('<div/>', {'class' : 'item-content w-100', 'style' : 'white-space: normal;'}).append(
                    
                            $('<div/>', {'class' : 'd-flex align-items-start justify-content-between'}).append(
            
                                $('<span/>', { 'class' : 'content-title fw-normal ws-norm', 'text' : data['title']}),
                                $('<span/>', { 'class' : 'fs-12 text-secondary text-nowrap', 'text' : data['timestamp']})
                            ),
                            
                            $('<div/>', {'class' : 'fw-normal text-muted mb-0 ws-norm fs-12','style' : 'word-break: break-all;', 'text' : data['body']})
                        )
                    )
                )
            })
        }

        const q = query(collection(db, `Users/${userID}/Notifications`), orderBy('timestamp', 'desc'), limit(1))
        const unsub = onSnapshot(q,(querySnap) => {
            
            if(!initDone){
                initDone = true
            } else {
                $('#emptySpace').css("display", "none")

                querySnap.forEach( (doc) => {
                    var data = doc.data()
                    notifHead.after(
                        $('<a/>', 
                        {'class' : 'dropdown-item',
                         'href' : (userLvl == "User" ? `/profile/orders/${data['orderID']}` : `/dash/orders/${data  ['orderID']}`), 
                         'style' : 'background: #f7f7f7;'}).append(
        
                            $('<div/>', {'class' : 'item-thumbnail'}).append(
                                $('<img/>', {'src' : (data['title'].startsWith('New') ?  newImg : noticeImg ) })
                            ),
        
                            $('<div/>', {'class' : 'item-content w-100', 'style' : 'white-space: normal;'}).append(
                    
                                $('<div/>', {'class' : 'd-flex align-items-start justify-content-between'}).append(
        
                                    $('<span/>', { 'class' : 'content-title fw-normal ws-norm', 'text' : data['title']}),
                                    $('<span/>', { 'class' : 'fs-12 text-secondary text-nowrap', 'text' : data['timestamp']})
                                ),
                                
                                $('<div/>', {'class' : 'fw-normal text-muted mb-0 ws-norm fs-12','style' : 'word-break: break-all;', 'text' : data['body']})
                            )
                        )
                    )
                })
        
                reddot.css("display", "block")
        
            }
        })
        
        $('#notifBtn').on('click', function() {
            reddot.css("display", "none")
        })

    } else {

        var orgNotifQ = query(collection(db, 'OrgNotif'), orderBy('timestamp', 'desc'))
        const orgNotifDocs = await getDocs(orgNotifQ)

        $('#loader').css("display", "none")

        if(orgNotifDocs.empty){
            $('#emptySpace').css("display", "flex")
        } else {
            // Initial Outputing of notifs
            orgNotifDocs.forEach( (doc) => {
            
                var data = doc.data() 
                notifBar.append(
                    $('<a/>', 
                    {'class' : 'dropdown-item', 
                    'href' : (userLvl == "User" ? `/profile/orders/${data['orderID']}` : `/dash/orders/${data['orderID']}`) }).append(
            
                        $('<div/>', {'class' : 'item-thumbnail'}).append(
                            $('<img/>', {'src' : (data['title'].startsWith('New') ?  newImg : noticeImg ) })
                        ),
            
                        $('<div/>', {'class' : 'item-content w-100', 'style' : 'white-space: normal;'}).append(
                    
                            $('<div/>', {'class' : 'd-flex align-items-center justify-content-between'}).append(
            
                                $('<span/>', { 'class' : 'content-title fw-normal ws-norm', 'text' : data['title']}),
                                $('<span/>', { 'class' : 'fs-12 text-secondary', 'text' : data['timestamp']})
                            ),
                            
                            $('<div/>', {'class' : 'fw-normal text-muted mb-0 ws-norm fs-12','style' : 'word-break: break-all;', 'text' : data['body']})
                        )
                    )
                )
            })
        }

        const q = query(collection(db, 'OrgNotif'), orderBy('timestamp', 'desc'), limit(1))
        const unsub = onSnapshot(q,(querySnap) => {
            
            if(!initDone){
                initDone = true
            } else {
                $('#emptySpace').css("display", "none")

                querySnap.forEach( (doc) => {
                    var data = doc.data()
                    notifHead.after(
                        $('<a/>',
                         {'class' : 'dropdown-item',
                         'href' : (userLvl == "User" ? `/profile/orders/${data['orderID']}` : `/dash/orders/${data  ['orderID']}`),
                         'style' : 'background: #f7f7f7;'}).append(
        
                            $('<div/>', {'class' : 'item-thumbnail'}).append(
                                $('<img/>', {'src' : (data['title'].startsWith('New') ?  newImg : noticeImg ) })
                            ),
        
                            $('<div/>', {'class' : 'item-content w-100', 'style' : 'white-space: normal;'}).append(
                    
                                $('<div/>', {'class' : 'd-flex align-items-center justify-content-between'}).append(
        
                                    $('<span/>', { 'class' : 'content-title fw-normal ws-norm', 'text' : data['title']}),
                                    $('<span/>', { 'class' : 'fs-12 text-secondary', 'text' : data['timestamp']})
                                ),
                                
                                $('<div/>', {'class' : 'fw-normal text-muted mb-0 ws-norm fs-12','style' : 'word-break: break-all;', 'text' : data['body']})
                            )
                        )
                    )
                })
        
                reddot.css("display", "block")
        
            }
        })
        
        $('#notifBtn').on('click', function() {
            reddot.css("display", "none")
        })

    }
}


