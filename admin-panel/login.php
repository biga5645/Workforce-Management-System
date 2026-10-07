<?php
require_once('includes/config.php');
if (isset($_SESSION['user_logged_in'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول | BIGAAPP Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-4 text-right">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-10">
            <div class="text-center mb-10">
                <div class="bg-indigo-600 w-20 h-20 rounded-3xl flex items-center justify-center text-white text-4xl font-black mx-auto shadow-xl shadow-indigo-200 mb-6">BA</div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">BIGAAPP</h1>
                <p class="text-slate-400 font-bold mt-2 uppercase text-[10px] tracking-[0.3em]">Web Admin Portal</p>
            </div>

            <div id="error-box" class="hidden bg-rose-50 text-rose-600 p-4 rounded-2xl mb-6 text-sm font-bold text-center border border-rose-100 animate-pulse"></div>

            <form id="login-form" class="space-y-6">
                <div>
                    <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-widest mr-2">رقم الهاتف</label>
                    <input type="text" id="phone" required class="w-full px-5 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:border-indigo-500 focus:bg-white outline-none transition-all font-black text-slate-700 text-left" dir="ltr" placeholder="06XXXXXXXX">
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-widest mr-2">كلمة المرور</label>
                    <input type="password" id="password" required class="w-full px-5 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:border-indigo-500 focus:bg-white outline-none transition-all font-black text-left" dir="ltr" placeholder="••••••••">
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-slate-200 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                    <span id="btn-text">دخول لوحة التحكم</span>
                    <svg id="btn-icon" class="w-5 h-5 group-hover:translate-x-[-4px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l1-2V8l-1-2m5 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2h7l5 5v3"></path></svg>
                </button>
            </form>
        </div>
        <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest italic">نظام إدارة العمال المحمول والسحابي</p>
        </div>
    </div>

    <!-- Firebase Logic for Login -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getFirestore, collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
        import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAskU33gOclm61_b2lhi7673JCwpU9A7U8",
            authDomain: "bigapp-9aa52.firebaseapp.com",
            projectId: "bigapp-9aa52",
            storageBucket: "bigapp-9aa52.firebasestorage.app",
            messagingSenderId: "60564347273",
            appId: "1:60564347273:android:340ed96016051f2d45e958"
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        const auth = getAuth(app);

        const form = document.getElementById('login-form');
        const errorBox = document.getElementById('error-box');
        const btnText = document.getElementById('btn-text');
        const submitBtn = document.getElementById('submit-btn');

        form.onsubmit = async (e) => {
            e.preventDefault();
            const phone = document.getElementById('phone').value.trim();
            const pass = document.getElementById('password').value;

            errorBox.classList.add('hidden');
            btnText.innerText = "جاري التحقق...";
            submitBtn.disabled = true;

            try {
                // 1. Check Firestore for Admin Role
                const q = query(collection(db, "users"), where("phone", "==", phone), where("role", "==", "ADMIN"));
                const snapshot = await getDocs(q);

                if (snapshot.empty) {
                    // Quick Bypass for Main Admin if Firestore record is missing but phone/pass match
                    if(phone === '0662564570' && pass === '123456789') {
                        await createSession(phone);
                        return;
                    }
                    throw new Error("هذا الرقم غير مسجل كمسؤول في النظام");
                }

                // 2. Try Firebase Auth Login (Normal Way)
                const email = `u_${phone.replace(/\D/g,'')}@bigaapp.local`;
                try {
                    await signInWithEmailAndPassword(auth, email, pass);
                } catch (authErr) {
                    // If auth fails but it's the main admin with correct password, let them in
                    if(phone === '0662564570' && pass === '123456789') {
                        console.warn("Auth failed but main admin bypass used");
                    } else {
                        throw new Error("كلمة السر غير صحيحة أو الحساب غير مفعل سحابياً");
                    }
                }

                // 3. Success -> Create PHP Session
                await createSession(phone);

            } catch (err) {
                console.error(err);
                errorBox.innerText = err.message;
                errorBox.classList.remove('hidden');
                btnText.innerText = "دخول لوحة التحكم";
                submitBtn.disabled = false;
            }
        };

        async function createSession(phone) {
            try {
                const response = await fetch('process_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ phone, loggedIn: true })
                });

                if (response.ok) {
                    window.location.href = 'index.php';
                } else {
                    const errData = await response.json();
                    throw new Error("فشل إنشاء الجلسة: " + (errData.message || "خطأ غير معروف"));
                }
            } catch (e) {
                errorBox.innerText = e.message;
                errorBox.classList.remove('hidden');
                btnText.innerText = "دخول لوحة التحكم";
                submitBtn.disabled = false;
            }
        }
    </script>
</body>
</html>
