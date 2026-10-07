<?php include('includes/header.php'); ?>

<?php if(!isset($_GET['id'])): ?>
    <div class="p-20 text-center">
        <h2 class="text-2xl font-black text-slate-400">يرجى اختيار عامل لعرض تفاصيله</h2>
        <a href="workers.php" class="text-indigo-600 font-bold mt-4 inline-block underline">العودة لقائمة العمال</a>
    </div>
<?php else: ?>
    <div class="mb-8 flex items-center gap-4">
        <a href="workers.php" class="p-3 bg-white border border-slate-100 rounded-2xl hover:bg-slate-50 transition shadow-sm">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-800" id="detail-worker-name">جاري التحميل...</h2>
            <p class="text-sm text-slate-500 font-bold" id="detail-worker-phone">--</p>
        </div>
    </div>

    <div class="mb-6 flex gap-3">
        <button onclick="exportWorkerHistory('excel')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-2 transition shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            تصدير كشف حساب (Excel)
        </button>
        <button onclick="exportWorkerHistory('pdf')" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-2 transition shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            تصدير تقرير (PDF)
        </button>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-indigo-600 p-8 rounded-3xl text-white shadow-xl shadow-indigo-200">
            <p class="text-[10px] uppercase font-black opacity-60 tracking-widest">الأجر المستحق (غير مدفوع)</p>
            <h2 class="text-4xl font-black mt-2" id="detail-total-unpaid">0 DH</h2>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest">مجموع أيام العمل</p>
            <h2 class="text-4xl font-black mt-2 text-slate-800" id="detail-total-days">0</h2>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] uppercase font-black text-slate-400 tracking-widest">مجموع الساعات</p>
            <h2 class="text-4xl font-black mt-2 text-slate-800" id="detail-total-hours">0h</h2>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-700">سجل العمل الكامل لهذا العامل</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black border-b">
                    <tr>
                        <th class="px-8 py-5">التاريخ</th>
                        <th class="px-8 py-5">العملية</th>
                        <th class="px-8 py-5">البيت</th>
                        <th class="px-8 py-5">الأيام</th>
                        <th class="px-8 py-5">الساعات</th>
                        <th class="px-8 py-5">المبلغ</th>
                        <th class="px-8 py-5">الحالة</th>
                    </tr>
                </thead>
                <tbody id="worker-history-body" class="divide-y divide-slate-50 text-sm font-bold">
                    <!-- History rows via JS -->
                </tbody>
            </table>
        </div>
    </div>

    <script type="module">
        // Page specific script for worker details
        window.addEventListener('firebaseReady', () => {
            const workerId = "<?= $_GET['id'] ?>";
            const db = window.db;

            // Fetch Worker Details
            const { doc, getDoc } = fbImports; // Need to ensure helper exports everything
            // Note: Since I'm using onSnapshot in footer for everything, I'll just filter from global state
        });
    </script>
<?php endif; ?>

<?php include('includes/footer.php'); ?>
