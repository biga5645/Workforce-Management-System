<?php
require_once('config.php');

/**
 * Firebase Helper
 * Generates the JavaScript initialization code for Firebase
 */
function getFirebaseJSInit() {
    global $firebaseConfig;
    $jsonConfig = json_encode($firebaseConfig);

    // We will use the admin phone from config to form the email
    $adminEmail = "u_" . ADMIN_PHONE . "@bigaapp.local";
    $adminPass  = defined('ADMIN_DEFAULT_PASSWORD') ? ADMIN_DEFAULT_PASSWORD : '123456789';

    return "
    <script type='module'>
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js';
        import { getFirestore, collection, onSnapshot, query, orderBy, limit, where, doc, getDoc, getDocs, setDoc, updateDoc, deleteDoc, addDoc } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js';
        import { getAuth, signInWithEmailAndPassword } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js';

        const firebaseConfig = $jsonConfig;

        try {
            const app = initializeApp(firebaseConfig);
            const db = getFirestore(app);
            const auth = getAuth(app);

            signInWithEmailAndPassword(auth, '$adminEmail', '$adminPass')
                .then(() => {
                    console.log('Firebase Web: Admin Authenticated Successfully');
                    window.dispatchEvent(new CustomEvent('firebaseAuthSuccess'));
                })
                .catch((error) => {
                    console.error('Firebase Web Auth Error:', error.message);
                    window.dispatchEvent(new CustomEvent('firebaseReady'));
                });

            // Export to global scope
            window.db = db;
            window.fbImports = { collection, onSnapshot, query, orderBy, limit, where, doc, getDoc, getDocs, setDoc, updateDoc, deleteDoc, addDoc };

            console.log('Firebase Web SDK Initialized');
            window.dispatchEvent(new CustomEvent('firebaseReady'));

        } catch (e) {
            console.error('Firebase Init Error:', e);
        }
    </script>
    ";
}
?>
