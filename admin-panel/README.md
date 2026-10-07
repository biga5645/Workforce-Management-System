# 🌐 BIGAAPP — Web Admin Portal

A real-time administrative dashboard for **BIGAAPP**, providing enterprise management, timesheet auditing, productivity analytics, and payroll export capabilities.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.x
- **Frontend / UI**: Modern HTML5, Tailwind CSS, Cairo Typography
- **Charts & Visualization**: Chart.js (Dynamic wage distribution & metrics)
- **Database / Cloud**: Google Cloud Firestore & Firebase Auth (Modular Web SDK v10)
- **Export Engines**: SheetJS (`xlsx.full.min.js`) for Excel & `html2pdf.js` for PDF reports

---

## 🚀 Key Modules & Pages

| Page | Description | Key Features |
| :--- | :--- | :--- |
| `index.php` | **Overview Dashboard** | Real-time counters (Active workers, unpaid wages, overtime hours), live activity stream, Chart.js wage distribution graph |
| `workers.php` | **Workforce Directory** | Filter by Active / Pending / Inactive status, phone search, quick actions |
| `worker_details.php` | **Worker Audit Profile** | Individual timesheet history, total hours worked, calculated earnings |
| `records.php` | **Work Records & Timesheets** | Date-range filtering, bulk shift audit, **Instant Excel (.xlsx) & PDF Export** |
| `admins.php` | **Admin Access Control** | Manage dashboard administrators and privilege tiers |
| `settings.php` | **System Preferences** | Default shift hours, standard hourly/daily rates, currency configuration |

---

## ⚙️ Quick Setup

1. **Prerequisites**: PHP 8.0+ and any local web server (Apache, XAMPP, Laragon, or PHP built-in server).
2. **Configure Firebase**:
   - Duplicate `includes/config.sample.php` to `includes/config.php`.
   - Insert your Firebase Project credentials:
     ```php
     $firebaseConfig = [
         'apiKey'            => 'YOUR_FIREBASE_API_KEY',
         'authDomain'        => 'your-project-id.firebaseapp.com',
         'projectId'         => 'your-project-id',
         'storageBucket'     => 'your-project-id.firebasestorage.app',
         'messagingSenderId' => 'YOUR_MESSAGING_SENDER_ID',
         'appId'             => 'YOUR_FIREBASE_APP_ID'
     ];
     ```
3. **Launch Server**:
   ```bash
   # From inside the admin-panel directory:
   php -S localhost:8080
   ```
4. Access `http://localhost:8080` in your browser.
