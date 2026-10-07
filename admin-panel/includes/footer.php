            </main>
        </div>
    </div>

    <!-- Firebase SDKs & Logic -->
    <?php require_once(__DIR__ . '/firebase_helper.php'); echo getFirebaseJSInit(); ?>

    <script type="module">
        let isInitialized = false;
        let allWorkers = [];
        let allRecords = [];
        let companySettings = {};
        let filteredRecords = [];

        function startLogic() {
            if (isInitialized) return;
            isInitialized = true;
            console.log("Web Admin Logic Started");
            initAppLogic();
        }

        window.addEventListener('firebaseReady', startLogic);
        window.addEventListener('firebaseAuthSuccess', startLogic);
        setTimeout(() => { if(!isInitialized && window.db) startLogic(); }, 2000);

        function initAppLogic() {
            const db = window.db;
            if(!db) return;

            const { collection, onSnapshot, query, orderBy, limit, where, doc, setDoc, updateDoc, deleteDoc, addDoc, getDoc, getDocs } = window.fbImports;

            // --- Observers ---

            // Admins Observer
            onSnapshot(query(collection(db, "users"), where("role", "==", "ADMIN")), (snapshot) => {
                const admins = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
                updateAdminsUI(admins);
            });

            onSnapshot(doc(db, "settings", "company"), (snapshot) => {
                companySettings = snapshot.data() || {};
                updateSettingsUI();
                populateFilterOptions();
            });

            onSnapshot(collection(db, "workers"), (snapshot) => {
                allWorkers = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() })).filter(w => !w.deletedAt);
                updateWorkersUI();
                updateDashboardStats();
                populateFilterOptions();
            });

            const fetchRecords = (useOrdering = true) => {
                const recordsCol = collection(db, "work_records");
                let qRecords = useOrdering ? query(recordsCol, orderBy("date", "desc"), limit(1000)) : query(recordsCol, limit(1000));

                return onSnapshot(qRecords, (snapshot) => {
                    allRecords = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
                    if(!useOrdering) allRecords.sort((a, b) => (b.date || "").localeCompare(a.date || ""));

                    applyFilters(); // Initial render
                    updateDashboardStats();
                    updateWorkerDetailsPage();
                }, (error) => {
                    if (useOrdering && error.message.includes("index")) fetchRecords(false);
                });
            };
            fetchRecords(true);

            // --- Filtering Logic ---
            window.applyFilters = () => {
                const start = document.getElementById('filter-start-date')?.value;
                const end = document.getElementById('filter-end-date')?.value;
                const workerId = document.getElementById('filter-worker')?.value;
                const operation = document.getElementById('filter-operation')?.value;

                filteredRecords = allRecords.filter(r => {
                    const matchesStart = !start || r.date >= start;
                    const matchesEnd = !end || r.date <= end;
                    const matchesWorker = !workerId || r.workerId === workerId;
                    const matchesOp = !operation || r.operation === operation;
                    return matchesStart && matchesEnd && matchesWorker && matchesOp;
                });

                updateRecordsUI();
                calculateFilteredTotals();
            };

            window.resetFilters = () => {
                ['filter-start-date', 'filter-end-date', 'filter-worker', 'filter-operation'].forEach(id => {
                    const el = document.getElementById(id);
                    if(el) el.value = "";
                });
                applyFilters();
            };

            function populateFilterOptions() {
                const workerSelect = document.getElementById('filter-worker');
                const opSelect = document.getElementById('filter-operation');
                if(!workerSelect || !opSelect) return;

                const currentWorker = workerSelect.value;
                const currentOp = opSelect.value;

                workerSelect.innerHTML = '<option value="">الكل</option>' + allWorkers.map(w => `<option value="${w.id}">${w.name}</option>`).join('');
                opSelect.innerHTML = '<option value="">الكل</option>' + (companySettings.operations || []).map(op => `<option value="${op}">${op}</option>`).join('');

                workerSelect.value = currentWorker;
                opSelect.value = currentOp;
            }

            function calculateFilteredTotals() {
                const days = filteredRecords.reduce((s, r) => s + (parseFloat(r.days) || 0), 0);
                const hours = filteredRecords.reduce((s, r) => s + (parseFloat(r.hours) || 0), 0);
                const amount = filteredRecords.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0);

                if(document.getElementById('total-filtered-days')) document.getElementById('total-filtered-days').innerText = days;
                if(document.getElementById('total-filtered-hours')) document.getElementById('total-filtered-hours').innerText = hours;
                if(document.getElementById('total-filtered-amount')) document.getElementById('total-filtered-amount').innerText = `${amount.toLocaleString()} DH`;
            }

            // --- UI Updaters ---
            function updateWorkersUI() {
                const table = document.getElementById('workers-table-body');
                if(!table) return;
                table.innerHTML = allWorkers.map(w => `
                    <tr class="hover:bg-slate-50 transition border-b border-slate-50">
                        <td class="px-8 py-4 font-black text-slate-700 underline cursor-pointer" onclick="window.location.href='worker_details.php?id=${w.id}'">${w.name}</td>
                        <td class="px-8 py-4 text-slate-500 font-bold">${w.phone || '--'}</td>
                        <td class="px-8 py-4 font-medium text-slate-400">${w.startDate || '--'}</td>
                        <td class="px-8 py-4 text-xs">
                            <span class="px-3 py-1 rounded-lg ${w.status === 'ACTIVE' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-red-600'} font-black ring-1 ring-inset ${w.status === 'ACTIVE' ? 'ring-emerald-600/20' : 'ring-red-600/20'}">
                                ${w.status === 'ACTIVE' ? 'نشط' : 'متوقف'}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-left flex items-center justify-end gap-2">
                             <button onclick="openEditWorkerModal('${w.id}')" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                             <button onclick="deleteWorker('${w.id}')" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </td>
                    </tr>
                `).join('');
            }

            let globalAdmins = [];
            function updateAdminsUI(admins) {
                globalAdmins = admins;
                const table = document.getElementById('admins-table-body');
                if(!table) return;
                if (admins.length === 0) {
                    table.innerHTML = '<tr><td colspan="4" class="p-12 text-center text-slate-400 font-bold italic">لا يوجد مسؤولون مضافون</td></tr>';
                    return;
                }
                table.innerHTML = admins.map(adm => `
                    <tr class="hover:bg-slate-50 transition border-b border-slate-50">
                        <td class="px-8 py-4 font-black text-slate-700">${adm.name}</td>
                        <td class="px-8 py-4 text-slate-500 font-bold">${adm.phone}</td>
                        <td class="px-8 py-4 text-xs"><span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-black ring-1 ring-inset ring-indigo-600/20">مسؤول</span></td>
                        <td class="px-8 py-4 text-left">
                             <button onclick="deleteAdmin('${adm.id}')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </td>
                    </tr>
                `).join('');
            }

            window.openAddAdminModal = () => {
                const modal = document.getElementById('admin-modal');
                if(!modal) return;
                if(document.getElementById('admin-id')) document.getElementById('admin-id').value = '';
                if(document.getElementById('admin-name')) document.getElementById('admin-name').value = '';
                if(document.getElementById('admin-phone')) document.getElementById('admin-phone').value = '';
                if(document.getElementById('admin-modal-title')) document.getElementById('admin-modal-title').innerText = 'إضافة مسؤول جديد';
                modal.classList.remove('hidden');
            };

            window.closeAdminModal = () => document.getElementById('admin-modal')?.classList.add('hidden');

            window.saveAdmin = async (e) => {
                e.preventDefault();
                const name = document.getElementById('admin-name').value;
                const phone = document.getElementById('admin-phone').value.trim();

                try {
                    // Check if already exists
                    const q = query(collection(db, "users"), where("phone", "==", phone));
                    const snapshot = await getDocs(q);

                    if (!snapshot.empty) {
                        const existingId = snapshot.docs[0].id;
                        await updateDoc(doc(db, "users", existingId), {
                            role: 'ADMIN',
                            status: 'ACTIVE',
                            name: name,
                            updatedAt: Date.now()
                        });
                    } else {
                        const newRef = doc(collection(db, "users"));
                        await setDoc(newRef, {
                            uid: newRef.id,
                            name,
                            phone,
                            role: 'ADMIN',
                            status: 'ACTIVE',
                            isPasswordSet: false,
                            createdAt: Date.now(),
                            updatedAt: Date.now()
                        });
                    }
                    closeAdminModal();
                    alert('تمت إضافة المسؤول بنجاح. يمكنه الآن الدخول من تطبيق الهاتف لتعيين كلمة سره.');
                } catch (err) { alert("خطأ: " + err.message); }
            };

            window.deleteAdmin = async (id) => {
                if (confirm('هل أنت متأكد من سحب صلاحية المسؤول؟')) {
                    await updateDoc(doc(db, "users", id), { role: 'WORKER' });
                }
            };

            function updateRecordsUI() {
                const table = document.getElementById('records-table-body');
                if(!table) return;
                const recordsToDisplay = (filteredRecords.length > 0 || allRecords.length === 0) ? filteredRecords : allRecords;

                if (recordsToDisplay.length === 0) {
                    table.innerHTML = '<tr><td colspan="7" class="p-12 text-center text-slate-300 font-bold">لا توجد سجلات مطابقة</td></tr>';
                    return;
                }

                table.innerHTML = recordsToDisplay.slice(0, 200).map(rec => `
                    <tr class="hover:bg-slate-50 transition border-b border-slate-50">
                        <td class="px-8 py-4 font-bold text-slate-900 text-sm">${rec.date || '--'}</td>
                        <td class="px-8 py-4 font-black text-slate-600 text-sm">${rec.workerName || '--'}</td>
                        <td class="px-8 py-4"><span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md font-black text-[10px]">${rec.operation || '--'}</span></td>
                        <td class="px-8 py-4 font-black text-slate-400 text-xs">${rec.serre || '--'}</td>
                        <td class="px-8 py-4 text-center">${rec.days || 0}</td>
                        <td class="px-8 py-4 text-center">${rec.hours || 0}</td>
                        <td class="px-8 py-4 text-indigo-600 font-black text-sm">${(parseFloat(rec.amount) || 0).toFixed(2)} DH</td>
                    </tr>
                `).join('');
            }

            function updateWorkerDetailsPage() {
                const nameEl = document.getElementById('detail-worker-name');
                if (!nameEl) return;
                const urlParams = new URLSearchParams(window.location.search);
                const workerId = urlParams.get('id');
                const worker = allWorkers.find(w => w.id === workerId);
                if (!worker) return;

                nameEl.innerText = worker.name;
                document.getElementById('detail-worker-phone').innerText = worker.phone;

                const myRecords = allRecords.filter(r => r.workerId === workerId);
                const unpaid = myRecords.filter(r => !r.isPaid);

                document.getElementById('detail-total-unpaid').innerText = `${unpaid.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0).toLocaleString()} DH`;
                document.getElementById('detail-total-days').innerText = myRecords.reduce((s, r) => s + (parseFloat(r.days) || 0), 0);
                document.getElementById('detail-total-hours').innerText = `${myRecords.reduce((s, r) => s + (parseFloat(r.hours) || 0), 0)}h`;

                const historyTable = document.getElementById('worker-history-body');
                if(historyTable) {
                    historyTable.innerHTML = myRecords.map(rec => `
                        <tr class="hover:bg-slate-50 border-b border-slate-50">
                            <td class="px-8 py-4">${rec.date}</td>
                            <td class="px-8 py-4"><span class="bg-slate-100 px-2 py-1 rounded text-xs">${rec.operation || '--'}</span></td>
                            <td class="px-8 py-4 text-xs font-bold text-slate-400">${rec.serre || '--'}</td>
                            <td class="px-8 py-4">${rec.days || 0}</td>
                            <td class="px-8 py-4">${rec.hours || 0}</td>
                            <td class="px-8 py-4 text-indigo-600 font-black">${(parseFloat(rec.amount) || 0).toFixed(2)} DH</td>
                            <td class="px-8 py-4 text-[10px] font-black ${rec.isPaid ? 'text-emerald-500' : 'text-orange-400'} uppercase">${rec.isPaid ? 'مدفوع' : 'قيد الانتظار'}</td>
                        </tr>
                    `).join('');
                }
            }

            function updateDashboardStats() {
                const unpaid = allRecords.filter(r => !r.isPaid);
                const totalWages = unpaid.reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
                const totalHours = unpaid.reduce((sum, r) => sum + (parseFloat(r.hours) || 0), 0);

                if(document.getElementById('stat-total-workers')) document.getElementById('stat-total-workers').innerText = allWorkers.length;
                if(document.getElementById('stat-total-wages')) document.getElementById('stat-total-wages').innerText = `${totalWages.toLocaleString()} DH`;
                if(document.getElementById('stat-total-hours')) document.getElementById('stat-total-hours').innerText = `${totalHours.toLocaleString()}h`;

                const canvas = document.getElementById('workerChart');
                if (canvas && allRecords.length > 0) {
                    const workerStats = {};
                    allRecords.forEach(r => { if(r.workerName) workerStats[r.workerName] = (workerStats[r.workerName] || 0) + (parseFloat(r.amount) || 0); });
                    const sorted = Object.entries(workerStats).sort((a,b) => b[1]-a[1]).slice(0, 5);
                    renderDashboardChart(sorted);
                }
            }

            function updateSettingsUI() {
                if(!document.getElementById('setting-daily-rate')) return;
                document.getElementById('setting-daily-rate').value = companySettings.dailyRate || 0;
                document.getElementById('setting-hourly-rate').value = companySettings.hourlyRate || 0;
                document.getElementById('setting-hours-per-day').value = companySettings.hoursPerDay || 8;
                document.getElementById('setting-company-name').value = companySettings.companyName || '';

                const opsList = document.getElementById('settings-operations-list');
                if(opsList) opsList.innerHTML = (companySettings.operations || []).map(op => `
                    <div class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm flex items-center gap-3">
                        ${op}
                        <button onclick="removeListItem('operations', '${op}')" class="text-indigo-300 hover:text-rose-500 transition">×</button>
                    </div>
                `).join('');

                const serresList = document.getElementById('settings-serres-list');
                if(serresList) serresList.innerHTML = (companySettings.serres || []).map(s => `
                    <div class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm flex items-center gap-3">
                        ${s}
                        <button onclick="removeListItem('serres', '${s}')" class="text-slate-300 hover:text-rose-500 transition">×</button>
                    </div>
                `).join('');
            }

            // --- Worker Modal ---
            window.openAddWorkerModal = () => {
                const modal = document.getElementById('worker-modal');
                if(!modal) return;
                if(document.getElementById('worker-id')) document.getElementById('worker-id').value = '';
                if(document.getElementById('worker-name')) document.getElementById('worker-name').value = '';
                if(document.getElementById('worker-phone')) document.getElementById('worker-phone').value = '';
                if(document.getElementById('worker-date')) document.getElementById('worker-date').value = new Date().toISOString().split('T')[0];
                if(document.getElementById('worker-bonus-toggle')) document.getElementById('worker-bonus-toggle').checked = false;
                window.toggleBonusInput?.(false);
                if(document.getElementById('modal-title')) document.getElementById('modal-title').innerText = 'إضافة عامل جديد';
                modal.classList.remove('hidden');
            };
            window.openEditWorkerModal = (id) => {
                const modal = document.getElementById('worker-modal');
                if(!modal) return;
                const w = allWorkers.find(x => x.id === id);
                if (!w) return;
                document.getElementById('worker-id').value = w.id;
                document.getElementById('worker-name').value = w.name;
                document.getElementById('worker-phone').value = w.phone;
                document.getElementById('worker-date').value = w.startDate || '';

                const hasBonus = w.dailyRateBonus != null && w.dailyRateBonus > 0;
                const toggle = document.getElementById('worker-bonus-toggle');
                if(toggle) toggle.checked = hasBonus;
                window.toggleBonusInput?.(hasBonus);
                if(document.getElementById('worker-daily-bonus')) document.getElementById('worker-daily-bonus').value = w.dailyRateBonus || '';

                if(document.getElementById('modal-title')) document.getElementById('modal-title').innerText = 'تعديل بيانات العامل';
                modal.classList.remove('hidden');
            };

            window.toggleBonusInput = (show) => {
                const container = document.getElementById('bonus-input-container');
                if(show) container?.classList.remove('hidden');
                else container?.classList.add('hidden');
            };

            window.closeWorkerModal = () => document.getElementById('worker-modal')?.classList.add('hidden');
            window.saveWorker = async (e) => {
                e.preventDefault();
                const id = document.getElementById('worker-id').value;
                const name = document.getElementById('worker-name').value;
                const phone = document.getElementById('worker-phone').value;
                const startDate = document.getElementById('worker-date').value;

                const isBonusActive = document.getElementById('worker-bonus-toggle')?.checked;
                const bonusVal = document.getElementById('worker-daily-bonus')?.value;
                const dailyRateBonus = isBonusActive && bonusVal ? parseFloat(bonusVal) : null;

                try {
                    const data = {
                        name, phone, startDate,
                        dailyRateBonus,
                        updatedAt: Date.now()
                    };
                    if (id) await updateDoc(doc(db, "workers", id), data);
                    else {
                        const newRef = doc(collection(db, "workers"));
                        data.status = 'ACTIVE';
                        data.createdAt = Date.now();
                        await setDoc(newRef, data);
                    }
                    closeWorkerModal();
                } catch (err) { alert("خطأ في الحفظ: " + err.message); }
            };
            window.deleteWorker = async (id) => {
                if (confirm('حذف هذا العامل؟')) await updateDoc(doc(db, "workers", id), { deletedAt: Date.now() });
            };
            window.saveGeneralSettings = async (e) => {
                e.preventDefault();
                await updateDoc(doc(db, "settings", "company"), {
                    dailyRate: parseFloat(document.getElementById('setting-daily-rate').value),
                    hourlyRate: parseFloat(document.getElementById('setting-hourly-rate').value),
                    hoursPerDay: parseFloat(document.getElementById('setting-hours-per-day').value),
                    companyName: document.getElementById('setting-company-name').value
                });
                alert('تم الحفظ');
            };
            window.addListItem = async (field) => {
                const name = prompt('أدخل الاسم:');
                if (!name) return;
                const current = companySettings[field] || [];
                if (current.includes(name)) return alert('موجود');
                await updateDoc(doc(db, "settings", "company"), { [field]: [...current, name] });
            };
            window.removeListItem = async (field, name) => {
                if (!confirm(`حذف "${name}"؟`)) return;
                const current = companySettings[field] || [];
                await updateDoc(doc(db, "settings", "company"), { [field]: current.filter(x => x !== name) });
            };
        }

        let dashChart;
        function renderDashboardChart(data) {
            const ctx = document.getElementById('workerChart')?.getContext('2d');
            if (!ctx) return;
            if (dashChart) dashChart.destroy();
            dashChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(d => d[0]),
                    datasets: [{
                        data: data.map(d => d[1]),
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ec4899', '#6366f1'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { font: { family: 'Cairo', size: 10 } } } } }
            });
        }
    </script>
</body>
</html>
