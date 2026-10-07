<?php include('includes/header.php'); ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-slate-800">إدارة المسؤولين</h2>
        <p class="text-sm text-slate-500 mt-1">إضافة مدراء جدد للنظام وتعيين كلمات السر الخاصة بهم.</p>
    </div>
    <button onclick="openAddAdminModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        إضافة مسؤول جديد
    </button>
</div>

<div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b">
                <tr>
                    <th class="px-8 py-5">الاسم</th>
                    <th class="px-8 py-5">رقم الهاتف</th>
                    <th class="px-8 py-5">الحالة</th>
                    <th class="px-8 py-5 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody id="admins-table-body" class="divide-y divide-slate-50 text-sm font-bold">
                <tr><td colspan="4" class="p-12 text-center text-slate-400 font-bold italic">جاري تحميل قائمة المسؤولين...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add/Edit Admin -->
<div id="admin-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 id="admin-modal-title" class="text-xl font-black text-slate-800">إضافة مسؤول</h3>
            <button onclick="closeAdminModal()" class="text-slate-400 hover:text-slate-600 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <form id="admin-form" onsubmit="saveAdmin(event)" class="p-8 space-y-5">
            <input type="hidden" id="admin-id">
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-tighter">اسم المسؤول</label>
                <input type="text" id="admin-name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none transition" placeholder="مثلاً: أحمد المدير">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2 tracking-tighter">رقم الهاتف (يستخدم للدخول)</label>
                <input type="text" id="admin-phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none transition" placeholder="06XXXXXXXX">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeAdminModal()" class="flex-1 px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-black transition">إلغاء</button>
                <button type="submit" class="flex-[2] px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black shadow-lg shadow-indigo-100 transition">حفظ المسؤول</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
