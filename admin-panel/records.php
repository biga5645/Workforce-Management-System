<?php include('includes/header.php'); ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-slate-800">سجلات العمل</h2>
        <p class="text-sm text-slate-500 mt-1">عرض جميع السجلات اليومية والبيانات المدخلة من الحقول مع خيارات الفلترة.</p>
    </div>
    <div class="flex gap-3">
        <button onclick="exportFilteredToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg transition active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Excel
        </button>
        <button onclick="exportFilteredToPDF()" class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg transition active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            PDF
        </button>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">
    <div>
        <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">من تاريخ</label>
        <input type="date" id="filter-start-date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold">
    </div>
    <div>
        <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">إلى تاريخ</label>
        <input type="date" id="filter-end-date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold">
    </div>
    <div>
        <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">العامل</label>
        <select id="filter-worker" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold appearance-none bg-slate-50 cursor-pointer">
            <option value="">الكل</option>
        </select>
    </div>
    <div>
        <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">العملية</label>
        <select id="filter-operation" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold appearance-none bg-slate-50 cursor-pointer">
            <option value="">الكل</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button onclick="applyFilters()" class="flex-1 bg-slate-800 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-slate-900 transition">تطبيق</button>
        <button onclick="resetFilters()" class="px-4 bg-slate-100 text-slate-500 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-200 transition">مسح</button>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full text-right text-sm font-bold">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black border-b tracking-widest">
                <tr>
                    <th class="px-8 py-5">التاريخ</th>
                    <th class="px-8 py-5">العامل</th>
                    <th class="px-8 py-5">العملية</th>
                    <th class="px-8 py-5">البيت</th>
                    <th class="px-8 py-5 text-center">الأيام</th>
                    <th class="px-8 py-5 text-center">الساعات</th>
                    <th class="px-8 py-5">المبلغ</th>
                </tr>
            </thead>
            <tbody id="records-table-body" class="divide-y divide-slate-50">
                <tr><td colspan="7" class="p-12 text-center text-slate-400">جاري جلب سجلات العمل...</td></tr>
            </tbody>
            <tfoot class="bg-indigo-50/30 border-t border-indigo-100">
                <tr class="text-indigo-900">
                    <td colspan="4" class="px-8 py-5 font-black text-left uppercase">المجموع الكلي المفلتر:</td>
                    <td id="total-filtered-days" class="px-8 py-5 text-center font-black">0</td>
                    <td id="total-filtered-hours" class="px-8 py-5 text-center font-black">0</td>
                    <td id="total-filtered-amount" class="px-8 py-5 font-black">0 DH</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
