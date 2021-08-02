import firebase from 'firebase'

if (!firebase.apps.length) {
  firebase.initializeApp(
    {
    apiKey: "AIzaSyCsCZ-Qb4MBWYP3IHxwT6ygitLML5EQJSE",
    authDomain: "snstestpj.firebaseapp.com",
    projectId: "snstestpj",
    storageBucket: "snstestpj.appspot.com",
    messagingSenderId: "27498001563",
    appId: "1:27498001563:web:164816b3f0737c19e4023e",
    measurementId: "G-SYVYFBD5MH"
    }
  )
}

export default firebase
