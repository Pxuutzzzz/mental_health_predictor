# Mode Tenaga Kesehatan (Hospital Staff Mode) – Dokumentasi

## 📋 **Deskripsi Umum**

Fitur **Hospital Staff Mode** memungkinkan tenaga kesehatan (dokter, psikiater, psikolog, perawat, konselor) di rumah sakit untuk melakukan assessment mental health **atas nama pasien** saat konsultasi. Data tersimpan langsung di sistem rumah sakit tanpa perlu integrasi eksternal.

**Keunggulan:**
- ✅ Interface khusus untuk tenaga medis (clinical mode)
- ✅ Quick-score buttons untuk input cepat
- ✅ Field klinis lengkap (MRN, ICD code, catatan medis)
- ✅ Auto-save ke sistem rumah sakit
- ✅ Tidak perlu kirim data keluar (sudah di RS)
- ✅ Support lookup pasien by MRN
- ✅ Generate instant report untuk print/EMR

---

## 🏗️ **Arsitektur**

### User Flow Comparison

**Mode Normal (Pasien):**
```
Pasien → Form Online → AI Prediction → (Opsional) Rujuk ke RS
```

**Mode Staff (Tenaga Kesehatan):**
```
Pasien Datang ke RS → Tenaga Kesehatan Input → AI Prediction → Tersimpan di Sistem RS
```

### File & Lokasi

| File | Lokasi | Deskripsi |
|------|---------|-----------|
| `hospital_staff.php` | `config/` | Konfigurasi staff mode |
| `assessment_clinical.php` | `views/` | UI khusus tenaga kesehatan |
| `PredictionController.php` | `app/Controllers/` | Handle staff workflow |
| `schema.sql` | `database/` | Kolom `clinical_data` di `assessments` |

---

## 🔧 **Setup & Instalasi**

### 1. Update Database Schema

Jalankan schema update untuk menambah kolom `clinical_data`:

```bash
mysql -u root -p mental_health_db < database/schema.sql
```

Atau manual:

```sql
ALTER TABLE assessments 
ADD COLUMN clinical_data JSON AFTER recommendations;

ALTER TABLE assessments
MODIFY COLUMN user_id INT NULL;
```

### 2. Aktifkan Staff Mode

Edit **`config/hospital_staff.php`**:

```php
return [
    'enabled' => true,
    'require_staff_login' => false, // Set true jika butuh login staff
    'supported_facilities' => [
        'rs_hermina',
        'rsud_jakarta'
    ]
];
```

### 3. Access URL

Tenaga kesehatan akses via:

```
http://localhost:9000/assessment-clinical
# atau
http://localhost:9000/clinical
# atau
http://localhost:9000/clinical?facility_id=rs_hermina
```

---

## 🎨 **Tampilan Interface**

### Header Clinical Mode

```
╔═══════════════════════════════════════════════════╗
║  🏥 MODE TENAGA KESEHATAN                         ║
║                                                   ║
║  Assessment Kesehatan Mental - Klinis             ║
║  📍 RS Hermina Depok                              ║
╚═══════════════════════════════════════════════════╝
```

### Form Informasi Pasien

```
┌───────────────────────────────────────────────────┐
│  👤 Informasi Pasien                              │
├───────────────────────────────────────────────────┤
│                                                   │
│  No. Rekam Medis (MRN)      [Nama Pasien]        │
│  [MRN-2026-001    ] [🔍]    [A.B.C.      ]        │
│                                                   │
│  Usia    Jenis Kelamin    Tipe Kunjungan         │
│  [25 ]   [Laki-laki ▼]    [Rawat Jalan ▼]        │
└───────────────────────────────────────────────────┘
```

### Quick-Score Buttons (Parameter Mental Health)

```
┌───────────────────────────────────────────────────┐
│  📋 Parameter Kesehatan Mental                    │
├───────────────────────────────────────────────────┤
│                                                   │
│  Tingkat Stres (0-10)                             │
│  ┌─┬─┬─┬─┬─┬─┬─┬─┬─┬─┬─┐                        │
│  │0│1│2│3│4│5│6│7│8│9│10│  ← Klik angka          │
│  └─┴─┴─┴─┴─┴─┴─┴─┴─┴─┴─┘                        │
│                                                   │
│  Tingkat Kecemasan (0-10)                         │
│  ┌─┬─┬─┬─┬─┬─┬─┬─┬─┬─┬─┐                        │
│  │0│1│2│3│4│5│6│7│8│9│10│                        │
│  └─┴─┴─┴─┴─┴─┴─┴─┴─┴─┴─┘                        │
│                                                   │
│  Tingkat Depresi (0-10)                           │
│  [Tombol 0-10 serupa]                             │
│                                                   │
│  Durasi Tidur           Aktivitas Fisik           │
│  [7-8 jam (Normal) ▼]   [Sedang ▼]               │
│                                                   │
│  Riwayat Mental Health  Dukungan Sosial           │
│  [Tidak Ada ▼]          [Baik ▼]                  │
└───────────────────────────────────────────────────┘
```

### Clinical Notes Section

```
┌───────────────────────────────────────────────────┐
│  📝 Catatan Klinis                                │
├───────────────────────────────────────────────────┤
│                                                   │
│  Keluhan Utama / Chief Complaint                  │
│  ┌───────────────────────────────────────────┐   │
│  │ Pasien mengeluh sulit tidur sejak 2       │   │
│  │ minggu terakhir, merasa cemas berlebihan  │   │
│  └───────────────────────────────────────────┘   │
│                                                   │
│  Observasi Klinis      Rencana Tindak Lanjut     │
│  [____________]         [____________]            │
│                                                   │
│  Kode Diagnosa (ICD-10)  Nama Dokter/Konselor    │
│  [F41.9 (Anxiety...)]    [dr. [Nama], Sp.KJ]     │
└───────────────────────────────────────────────────┘
```

### Tombol Submit

```
┌───────────────────────────────────────────────────┐
│              [✓ Simpan & Proses Assessment]       │
│                   [⟲ Reset Form]                  │
└───────────────────────────────────────────────────┘
```

### Hasil Assessment

```
┌───────────────────────────────────────────────────┐
│  📄 Hasil Assessment AI                           │
├───────────────────────────────────────────────────┤
│                                                   │
│    Kategori Risiko      Tingkat Keyakinan AI     │
│   ┌─────────────┐       ┌─────────────┐         │
│   │ Moderate    │       │    82.5%    │         │
│   │   Risk      │       │             │         │
│   └─────────────┘       └─────────────┘         │
│                                                   │
│  💡 Rekomendasi Klinis:                           │
│  • Konsultasi lanjutan dengan psikolog            │
│  • CBT therapy direkomendasikan                   │
│  • Monitor sleep pattern                          │
│                                                   │
│  ℹ️ Data tersimpan dengan ID: 12345              │
│                                                   │
│  [🖨️ Cetak] [💾 Simpan ke EMR] [➕ Assessment Baru]│
└───────────────────────────────────────────────────┘
```

---

## 🔐 **Security & Access Control**

### Demo Mode (Saat Ini)

- **Tidak perlu login** (`require_staff_login = false`)
- Bisa langsung akses `/clinical`
- Cocok untuk demo/testing

### Production Mode (Rekomendasi)

```php
// config/hospital_staff.php
return [
    'enabled' => true,
    'require_staff_login' => true, // Wajib login
    'allow_anonymous_staff' => false,
    
    // Staff authentication via access code
    'demo_access_codes' => [
        'STAFF-HERMINA-2026' => 'rs_hermina',
        'STAFF-RSUD-2026' => 'rsud_jakarta'
    ]
];
```

**TODO (Production):**
- Implementasi login staff dengan role-based access
- Integrasi dengan SSO rumah sakit
- Session timeout & activity logging

---

## 📊 **Data yang Tersimpan**

### Database Schema

```sql
-- Assessments table (updated)
assessments {
    id INT,
    user_id INT NULL,  -- NULL untuk staff mode
    age, stress_level, anxiety_level, depression_level,
    mental_history, sleep_hours, exercise_level, social_support,
    prediction, confidence, probabilities, recommendations,
    clinical_data JSON,  -- NEW: Data klinis dari staff
    created_at
}
```

### Format `clinical_data` JSON

```json
{
  "patient_mrn": "MRN-2026-001",
  "patient_name": "A.B.C.",
  "patient_gender": "L",
  "visit_type": "outpatient",
  "chief_complaint": "Pasien mengeluh insomnia...",
  "clinical_observation": "Tampak cemas, kontak mata baik",
  "follow_up_plan": "Rujuk psikolog, kontrol 1 minggu",
  "icd_code": "F41.9",
  "clinician_name": "dr. Jane Doe, Sp.KJ",
  "facility_id": "rs_hermina",
  "staff_mode": true
}
```

---

## 🔄 **Workflow Comparison**

### Mode Pasien (Self-Assessment)

1. Pasien buka `http://localhost:9000/assessment`
2. Jawab 8 pertanyaan wizard
3. Submit → AI prediksi
4. (Opsional) Centang rujukan ke RS → Data dikirim ke endpoint RS

### Mode Staff (Clinical Assessment)

1. Pasien datang ke RS untuk konsultasi
2. Staf medis buka `http://localhost:9000/clinical?facility_id=rs_hermina`
3. Input MRN pasien (lookup jika sudah terdaftar)
4. Isi quick assessment (klik angka untuk score)
5. Tambahkan catatan klinis & ICD code
6. Submit → AI prediksi
7. **Data langsung tersimpan di sistem RS** (tidak perlu integrasi eksternal)
8. Cetak laporan atau simpan ke EMR

---

## 🎯 **Use Cases**

### 1. Rawat Jalan (Outpatient)

Pasien datang ke poli jiwa/psikologi:
- Dokter lakukan quick screening
- Input hasil ke sistem clinical mode
- Dapatkan rekomendasi AI instant
- Cetak laporan untuk pasien

### 2. IGD (Emergency)

Pasien dengan gejala krisis mental:
- Perawat IGD lakukan rapid assessment
- System berikan risk level (High/Moderate/Low)
- Rujukan internal ke psikiater jika High Risk

### 3. Rawat Inap (Inpatient)

Pasien di bangsal psikiatri:
- Assessment berkala (daily/weekly)
- Track progress via historical data
- Export ke EMR untuk discharge summary

---

## 📈 **Reporting & Analytics**

### Query: Assessments by Staff

```sql
SELECT 
    id,
    JSON_EXTRACT(clinical_data, '$.facility_id') AS facility,
    JSON_EXTRACT(clinical_data, '$.clinician_name') AS clinician,
    prediction,
    confidence,
    created_at
FROM assessments
WHERE clinical_data IS NOT NULL
ORDER BY created_at DESC
LIMIT 20;
```

### Query: Risk Distribution per Facility

```sql
SELECT 
    JSON_EXTRACT(clinical_data, '$.facility_id') AS facility,
    prediction,
    COUNT(*) AS total
FROM assessments
WHERE clinical_data IS NOT NULL
GROUP BY facility, prediction;
```

---

## 🛠️ **Troubleshooting**

### Problem: "User_id cannot be NULL"

**Solution:** Update schema untuk allow NULL:
```sql
ALTER TABLE assessments MODIFY COLUMN user_id INT NULL;
```

### Problem: Clinical page tidak muncul

**Solution:** Cek routing di `index.php`:
```php
case 'assessment-clinical':
case 'clinical':
    require __DIR__ . '/views/assessment_clinical.php';
    break;
```

### Problem: Data tidak tersimpan

**Solution:** Cek `clinical_data` column exists:
```sql
DESCRIBE assessments;
-- Harus ada: clinical_data JSON
```

---

## ✅ **Testing Checklist**

- [ ] Database schema updated (`clinical_data` column exists)
- [ ] Config `hospital_staff.php` enabled
- [ ] URL `/clinical` accessible
- [ ] Form pasien info bisa diisi
- [ ] Quick-score buttons berfungsi
- [ ] Submit form → dapat hasil AI
- [ ] Data tersimpan di DB dengan `clinical_data` not null
- [ ] No error di console browser
- [ ] Print report works (window.print)

---

## 📞 **Production Deployment Notes**

1. **Authentication:** Implementasi login staff dengan role verification
2. **Authorization:** Pastikan hanya staff RS tertentu bisa akses facility mereka
3. **Audit Trail:** Log semua aktivitas staff (siapa, kapan, patient MRN)
4. **Data Privacy:** Mask/encrypt patient name di database
5. **EMR Integration:** Hook ke API EMR rumah sakit untuk sync data
6. **Session Management:** Auto-logout setelah idle 15 menit

---

**Kesimpulan:** Dengan fitur ini, rumah sakit dapat menggunakan sistem secara langsung saat konsultasi pasien tanpa perlu pasien mengisi form online sendiri. Data tersimpan rapi dengan konteks klinis lengkap.
