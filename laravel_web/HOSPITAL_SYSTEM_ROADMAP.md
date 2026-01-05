# Roadmap Sistem Prediksi Risiko Kesehatan untuk Rumah Sakit

## 🎯 Overview
Mengembangkan sistem mental health predictor menjadi platform komprehensif untuk prediksi berbagai risiko kesehatan (diabetes, hipertensi, penyakit jantung, dll) yang terintegrasi dengan sistem RS.

## 📋 Fitur yang Sudah Ada (Existing)
- ✅ Mental health risk prediction
- ✅ User authentication & session management
- ✅ Assessment history tracking
- ✅ Hospital integration framework (sandbox)
- ✅ Clinical staff mode
- ✅ Audit logging & security
- ✅ Bootstrap UI with responsive design

## 🚀 Pengembangan yang Diperlukan

### 1. Multiple Disease Prediction Models
**Priority: HIGH**

**A. Model Machine Learning**
- [ ] **Diabetes Risk Predictor**
  - Input: Usia, BMI, tekanan darah, gula darah puasa, HbA1c, riwayat keluarga
  - Output: Risiko diabetes (Low/Medium/High), prediksi onset
  
- [ ] **Hypertension Risk Predictor**
  - Input: Tekanan darah, usia, BMI, kolesterol, merokok, aktivitas fisik
  - Output: Kategori hipertensi, risiko komplikasi
  
- [ ] **Cardiovascular Disease Risk Predictor**
  - Input: Usia, gender, kolesterol (LDL/HDL), tekanan darah, merokok, diabetes
  - Output: 10-year CVD risk score, kategori risiko
  
- [ ] **Multi-Disease Risk Assessment**
  - Kombinasi semua model untuk comprehensive health screening

**B. Model Integration**
```
src/
├── predictor.py (existing - mental health)
├── diabetes_predictor.py (new)
├── hypertension_predictor.py (new)
├── cardiovascular_predictor.py (new)
└── multi_disease_predictor.py (new - orchestrator)
```

---

### 2. Enhanced Clinical Data Input Forms
**Priority: HIGH**

**A. Demographic & Vital Signs**
```php
- Nama lengkap, NIK, No. MR (Medical Record)
- Tanggal lahir, usia, jenis kelamin
- Tinggi badan, berat badan, BMI (auto-calculate)
- Tekanan darah (sistol/diastol)
- Denyut nadi, suhu tubuh, saturasi O2
```

**B. Medical History**
```php
- Riwayat penyakit sekarang
- Riwayat penyakit dahulu
- Riwayat penyakit keluarga (diabetes, hipertensi, jantung)
- Riwayat alergi & obat-obatan
```

**C. Laboratory Results**
```php
- Gula darah puasa (GDP), gula darah 2 jam PP
- HbA1c (glycated hemoglobin)
- Kolesterol total, LDL, HDL, Trigliserida
- Fungsi ginjal (Ureum, Creatinine, eGFR)
- Fungsi hati (SGOT, SGPT)
- Hemoglobin, Leukosit, Trombosit
```

**D. Lifestyle Factors**
```php
- Status merokok (aktif/pasif/tidak)
- Konsumsi alkohol
- Aktivitas fisik (frekuensi & intensitas)
- Pola makan
- Kualitas tidur
```

---

### 3. Role-Based Access Control (RBAC)
**Priority: HIGH**

**Roles:**
```php
1. Administrator
   - Manage users (dokter, perawat)
   - System configuration
   - View all audit logs
   - Data export & reporting

2. Dokter (Doctor)
   - Perform comprehensive assessments
   - View patient history
   - Override risk predictions
   - Write clinical notes
   - Order referrals

3. Perawat (Nurse)
   - Perform basic assessments
   - Input vital signs & lab results
   - View assigned patients
   - Cannot override predictions

4. Pasien (Patient)
   - Self-assessment (limited)
   - View own history
   - Cannot see other patients

5. Staff Lab
   - Input lab results only
   - Link results to patient MR
```

**Implementation:**
```sql
-- New tables
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    permissions JSON
);

CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    department VARCHAR(100),
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 4. Integration dengan Sistem RS (EMR/EHR)
**Priority: MEDIUM**

**A. HL7/FHIR Support**
```php
- Terima data pasien dari EMR via HL7 ADT messages
- Export hasil prediksi ke EMR via FHIR resources
- Bidirectional sync dengan sistem SIMRS
```

**B. BPJS Integration**
```php
- Validasi No. BPJS/Kartu Indonesia Sehat
- Klaim data untuk analytics
- Compliance reporting
```

**C. API Endpoints untuk Integrasi**
```
POST /api/v1/patient/import        # Import data pasien dari EMR
POST /api/v1/assessment/create     # Create assessment dari sistem lain
GET  /api/v1/assessment/{id}       # Get assessment result
POST /api/v1/lab-results/import    # Import hasil lab
GET  /api/v1/reports/hospital      # Hospital-wide statistics
```

---

### 5. Dashboard untuk Tenaga Medis
**Priority: HIGH**

**A. Dashboard Dokter**
- List pasien hari ini dengan risk scores
- High-risk patient alerts
- Pending assessments
- Statistical overview (pie charts, trends)

**B. Dashboard Perawat**
- Task list (vital signs collection)
- Patient queue management
- Quick assessment tools

**C. Dashboard Admin**
- System usage statistics
- Model performance metrics
- User activity logs
- Data quality reports

---

### 6. Advanced Features

**A. Risk Score Visualization**
```javascript
- Color-coded risk indicators (green/yellow/red)
- Trend charts (risk over time)
- Comparative analysis (patient vs population)
- Interactive graphs with Chart.js/D3.js
```

**B. Clinical Decision Support**
```php
- Automated alerts for high-risk patients
- Treatment recommendations based on guidelines
- Drug interaction warnings
- Follow-up scheduling suggestions
```

**C. Reporting & Analytics**
```php
- Generate PDF reports for patients
- Export data for research (anonymized)
- Monthly/quarterly hospital statistics
- Compliance reports for Kemenkes
```

---

### 7. Security & Compliance Enhancements
**Priority: HIGH**

**A. Data Privacy (Sesuai UU PDP)**
```php
- Patient consent management
- Data anonymization for research
- Right to be forgotten (delete patient data)
- Data access logging
```

**B. Security Features**
```php
- Two-factor authentication (2FA)
- Session timeout & automatic logout
- IP whitelist untuk clinical workstations
- Encrypted data at rest & in transit
```

**C. Audit & Compliance**
```php
- Comprehensive audit trail (who, what, when, where)
- Compliance with ISO 27001
- Regular security scans
- Backup & disaster recovery
```

---

## 📁 Struktur File Baru yang Diperlukan

```
laravel_web/
├── app/
│   ├── Controllers/
│   │   ├── PredictionController.php (existing - enhanced)
│   │   ├── DiabetesController.php (new)
│   │   ├── HypertensionController.php (new)
│   │   ├── CardiovascularController.php (new)
│   │   ├── MultiDiseaseController.php (new)
│   │   ├── PatientController.php (new)
│   │   └── RoleController.php (new)
│   ├── Models/
│   │   ├── Patient.php (new)
│   │   ├── Assessment.php (new)
│   │   ├── LabResult.php (new)
│   │   └── Role.php (new)
│   └── Services/
│       ├── EMRIntegrationService.php (new)
│       ├── FHIRService.php (new)
│       └── ReportGenerator.php (new)
├── views/
│   ├── dashboard/
│   │   ├── doctor_dashboard.php (new)
│   │   ├── nurse_dashboard.php (new)
│   │   └── admin_dashboard.php (new)
│   ├── assessments/
│   │   ├── diabetes_assessment.php (new)
│   │   ├── hypertension_assessment.php (new)
│   │   ├── cardiovascular_assessment.php (new)
│   │   └── comprehensive_assessment.php (new)
│   └── reports/
│       └── patient_report.php (new)
├── config/
│   ├── roles.php (new)
│   └── integrations.php (new)
└── database/
    └── hospital_system_schema.sql (new)

src/ (Python ML)
├── models/
│   ├── diabetes_model.pkl
│   ├── hypertension_model.pkl
│   └── cardiovascular_model.pkl
├── diabetes_predictor.py (new)
├── hypertension_predictor.py (new)
├── cardiovascular_predictor.py (new)
└── multi_disease_predictor.py (new)
```

---

## 🔧 Teknologi yang Diperlukan

**Backend:**
- PHP 8.0+ (existing)
- Python 3.8+ dengan scikit-learn, pandas, numpy
- MySQL 8.0+ atau PostgreSQL

**Frontend:**
- Bootstrap 5.3 (existing)
- Chart.js untuk visualisasi
- DataTables untuk tabel interaktif
- Select2 untuk dropdown advanced

**ML/AI:**
- Scikit-learn untuk model training
- XGBoost/LightGBM untuk advanced models
- SHAP untuk explainability

**Integration:**
- REST API dengan JWT authentication
- HL7 parser (PHP HL7 library)
- FHIR client library

**Security:**
- SSL/TLS certificate
- Rate limiting (untuk API)
- Web Application Firewall (WAF)

---

## 📊 Timeline Implementasi

### Phase 1: Core Features (2-3 bulan)
1. Multiple disease prediction models
2. Enhanced clinical forms
3. Role-based access control
4. Dashboard untuk dokter & perawat

### Phase 2: Integration (1-2 bulan)
5. EMR/EHR integration
6. BPJS integration
7. API development

### Phase 3: Advanced Features (1-2 bulan)
8. Advanced analytics & reporting
9. Clinical decision support
10. Mobile app (optional)

### Phase 4: Testing & Deployment (1 bulan)
11. User acceptance testing (UAT)
12. Security audit
13. Training untuk staff RS
14. Go-live & monitoring

---

## 📝 Next Steps (Immediate Actions)

1. **Create database schema untuk multi-disease system**
2. **Develop diabetes prediction model** (pilot)
3. **Build comprehensive assessment form**
4. **Implement RBAC system**
5. **Create doctor dashboard prototype**

**Mau saya mulai implementasi dari mana?**
