<?php include('includes/header.php'); ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-8 rounded-2xl shadow-sm border-b-4 border-indigo-500 hover:shadow-md transition">
        <p class="text-xs uppercase font-black text-indigo-400 tracking-widest">إجمالي العمال</p>
        <h2 class="text-4xl font-black mt-2 text-indigo-900" id="stat-total-workers">0</h2>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-sm border-b-4 border-green-500 hover:shadow-md transition">
        <p class="text-xs uppercase font-black text-green-400 tracking-widest">أجور غير مدفوعة</p>
        <h2 class="text-4xl font-black mt-2 text-green-600" id="stat-total-wages">0 DH</h2>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-sm border-b-4 border-orange-500 hover:shadow-md transition">
        <p class="text-xs uppercase font-black text-orange-400 tracking-widest">ساعات العمل (HS)</p>
        <h2 class="text-4xl font-black mt-2 text-orange-600" id="stat-total-hours">0h</h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Records -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-black text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-12 0 9 9 0 0112 0z"></path></svg>
                آخر سجلات العمل
            </h3>
            <a href="records.php" class="text-indigo-600 text-xs font-bold hover:underline">عرض الكل ←</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-tighter">
                    <tr>
                        <th class="px-6 py-4">التاريخ</th>
                        <th class="px-6 py-4">العامل</th>
                        <th class="px-6 py-4">العملية</th>
                        <th class="px-6 py-4">البيت</th>
                        <th class="px-6 py-4">المبلغ</th>
                    </tr>
                </thead>
                <tbody id="records-table-body" class="divide-y divide-gray-50 font-medium">
                    <tr><td colspan="5" class="p-8 text-center text-gray-400">جاري تحميل البيانات من السحاب...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Productivity Chart -->
    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
        <h3 class="font-black text-gray-700 mb-6 border-b pb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            توزيع الأجور
        </h3>
        <div class="relative h-64">
            <canvas id="workerChart"></canvas>
        </div>
        <p class="mt-6 text-center text-xs text-gray-400 leading-relaxed font-medium">يوضح هذا الرسم البياني أكثر 5 عمال استحقاقاً للأجور في الفترة الحالية.</p>
    </div>
</div>

<?php include('includes/footer.php'); ?>
