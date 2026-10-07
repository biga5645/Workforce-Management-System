<?php include('includes/header.php'); ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-slate-800">إدارة العمال</h2>
        <p class="text-sm text-slate-500 mt-1">قائمة بجميع العمال المسجلين في النظام وحالتهم الحالية.</p>
    </div>
    <button onclick="openAddWorkerModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        إضافة عامل جديد
    </button>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b">
                <tr>
                    <th class="px-8 py-5">الاسم الكامل</th>
                    <th class="px-8 py-5">رقم الهاتف</th>
                    <th class="px-8 py-5">تاريخ البداية</th>
                    <th class="px-8 py-5">الحالة</th>
                    <th class="px-8 py-5 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody id="workers-table-body" class="divide-y divide-slate-50 text-sm font-bold">
                <tr><td colspan="5" class="p-12 text-center text-slate-400">جاري تحميل قائمة العمال...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add/Edit Worker -->
<div id="worker-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 id="modal-title" class="text-xl font-black text-slate-800">إضافة عامل</h3>
            <button onclick="closeWorkerModal()" class="text-slate-400 hover:text-slate-600 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <form id="worker-form" onsubmit="saveWorker(event)" class="p-8 space-y-5">
            <input type="hidden" id="worker-id">
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-tighter">الاسم الكامل</label>
                <input type="text" id="worker-name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-tighter">رقم الهاتف</label>
                <input type="text" id="worker-phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none transition">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-tighter">تاريخ بداية العمل</label>
                <input type="date" id="worker-date" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none transition">
            </div>

            <div class="pt-2">
                <div class="flex items-center justify-between mb-3 bg-indigo-50/50 p-4 rounded-2xl ring-1 ring-indigo-500/10">
                    <div>
                        <p class="text-sm font-black text-indigo-900">زيادة خاصة في الأجر</p>
                        <p class="text-[10px] text-indigo-400 font-bold">تضاف إلى السعر اليومي المطبق على الجميع</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="worker-bonus-toggle" class="sr-only peer" onchange="toggleBonusInput(this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div id="bonus-input-container" class="hidden animate-in fade-in slide-in-from-top-2">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 mr-2">مبلغ الزيادة (DH)</label>
                    <input type="number" id="worker-daily-bonus" step="0.5" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none font-black text-indigo-600" placeholder="مثلاً: 5 أو 10">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeWorkerModal()" class="flex-1 px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-black transition">إلغاء</button>
                <button type="submit" class="flex-[2] px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg shadow-indigo-100 transition">حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
