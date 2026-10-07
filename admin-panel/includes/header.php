<?php
require_once(__DIR__ . '/auth_check.php');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIGAAPP | لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f1f5f9; }
        .sidebar { transition: all 0.3s; }
        .nav-link { display: flex; align-items: center; padding: 0.85rem 1.5rem; gap: 0.85rem; transition: 0.2s; border-radius: 0.75rem; margin: 0.25rem 0.75rem; }
        .nav-link.active { background-color: #4f46e5; color: white; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .nav-link:not(.active):hover { background-color: rgba(255, 255, 255, 0.05); color: white; }
    </style>
</head>
<body class="text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-72 bg-slate-900 text-slate-300 flex-shrink-0 sidebar hidden md:flex flex-col shadow-2xl">
            <div class="p-8 border-b border-slate-800 flex items-center gap-3">
                <div class="bg-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-indigo-900/20">
                    BA
                </div>
                <div>
                    <h2 class="text-white font-black tracking-tight text-xl">BIGAAPP</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Web Admin Portal</p>
                </div>
            </div>

            <nav class="mt-8 flex-1">
                <p class="px-8 mb-4 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black">إدارة النظام</p>

                <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-bold">الرئيسية</span>
                </a>

                <a href="workers.php" class="nav-link <?= $current_page == 'workers.php' ? 'active' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-bold">إدارة العمال</span>
                </a>

                <a href="records.php" class="nav-link <?= $current_page == 'records.php' ? 'active' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="font-bold">سجلات العمل</span>
                </a>

                <a href="admins.php" class="nav-link <?= $current_page == 'admins.php' ? 'active' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="font-bold">إدارة المسؤولين</span>
                </a>

                <div class="mt-10 px-8">
                   <p class="mb-4 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black">أدوات إضافية</p>
                   <button onclick="exportToExcel()" class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-900/20 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تصدير Excel
                   </button>
                </div>
            </nav>

            <div class="p-6">
                <a href="logout.php" class="flex items-center gap-3 p-4 bg-slate-800/50 hover:bg-red-500/10 hover:text-red-400 rounded-2xl transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 font-bold group-hover:bg-red-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-white">تسجيل الخروج</p>
                        <p class="text-[10px] text-slate-500 font-bold"><?= $_SESSION['user_phone'] ?></p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <header class="bg-white/80 backdrop-blur-md shadow-sm border-b px-8 py-4 flex items-center justify-between sticky top-0 z-50">
                <div>
                    <h1 class="text-xl font-black text-slate-800">اللوحة السحابية</h1>
                    <p class="text-xs text-slate-400 font-bold mt-0.5" id="current-date-display"></p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full uppercase tracking-tighter" id="online-status">Cloud Sync Active</span>
                    </div>
                    <button onclick="window.location.reload()" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </header>

            <main class="p-8">
