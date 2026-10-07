<?php include('includes/header.php'); ?>

<div class="mb-8">
    <h2 class="text-3xl font-black text-slate-800">إعدادات النظام</h2>
    <p class="text-sm text-slate-500 mt-1">تحكم في أسعار الأجور، العمليات، والبيوت (Serres) لجميع المستخدمين.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- General Settings -->
    <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100">
        <h3 class="text-lg font-black text-slate-700 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            الأسعار الافتراضية
        </h3>

        <form onsubmit="saveGeneralSettings(event)" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">ثمن النهار (DH)</label>
                    <input type="number" id="setting-daily-rate" step="0.1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition font-bold">
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">ثمن الساعة (DH)</label>
                    <input type="number" id="setting-hourly-rate" step="0.1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition font-bold">
                </div>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">ساعات العمل في اليوم</label>
                <input type="number" id="setting-hours-per-day" step="0.5" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition font-bold">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-black text-slate-400 mb-2">اسم المزرعة / الشركة</label>
                <input type="text" id="setting-company-name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition font-bold">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition active:scale-95">حفظ التغييرات العامة</button>
        </form>
    </div>

    <!-- Lists Management -->
    <div class="space-y-8">
        <!-- Operations -->
        <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-700">العمليات (Opérations)</h3>
                <button onclick="addListItem('operations')" class="text-indigo-600 font-bold text-sm hover:underline">+ إضافة</button>
            </div>
            <div id="settings-operations-list" class="flex flex-wrap gap-2">
                <!-- Chips injected here -->
            </div>
        </div>

        <!-- Serres -->
        <div class="bg-white rounded-3xl shadow-sm p-8 border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-700">البيوت (Serres)</h3>
                <button onclick="addListItem('serres')" class="text-indigo-600 font-bold text-sm hover:underline">+ إضافة</button>
            </div>
            <div id="settings-serres-list" class="flex flex-wrap gap-2">
                <!-- Chips injected here -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
