# Fitur Mental Health System untuk Rumah Sakit

## 🎯 Fokus: Kesehatan Mental di RS

### Fitur yang Perlu Ditambahkan untuk RS:

## 1. **Standardized Mental Health Screening Tools**

### A. PHQ-9 (Depression Screening)
- 9 pertanyaan standar untuk depresi
- Scoring: 0-27 (minimal, mild, moderate, severe)
- Rekomendasi treatment berdasarkan score

### B. GAD-7 (Anxiety Screening)
- 7 pertanyaan untuk gangguan kecemasan
- Scoring: 0-21 (minimal, mild, moderate, severe)
- Alert untuk severe anxiety

### C. Suicide Risk Assessment
- Columbia Suicide Severity Rating Scale (C-SSRS)
- Emergency protocol activation
- Immediate alert ke psikiater on-duty

### D. DASS-21 (Depression, Anxiety, Stress Scale)
- Comprehensive screening
- Subscale scores untuk ketiga aspek

## 2. **Role Khusus Mental Health**

```
- Psikiater (Psychiatrist)
  * Diagnosis & resep obat
  * Review semua assessment
  * Crisis intervention
  
- Psikolog Klinis (Clinical Psychologist)
  * Psychological testing
  * Terapi (CBT, DBT, dll)
  * Assessment mendalam
  
- Perawat Jiwa (Psychiatric Nurse)
  * Monitoring pasien rawat inap
  * Administrasi obat
  * Basic counseling
  
- Konselor (Counselor)
  * Initial screening
  * Support counseling
  * Follow-up
```

## 3. **Mental Health Specific Features**

### A. Crisis Management
```php
- Red flag detection (suicide ideation, self-harm)
- Automatic emergency alert
- Emergency contact notification
- Crisis protocol checklist
```

### B. Medication Tracking
```php
- Current psychiatric medications
- Dosage & frequency
- Side effects monitoring
- Compliance tracking
```

### C. Treatment Planning
```php
- Individual treatment goals
- Therapy sessions tracking
- Progress notes
- Treatment outcomes measurement
```

### D. Follow-up System
```php
- Scheduled follow-up appointments
- Reminder notifications
- Progress evaluation
- Relapse prevention
```

## 4. **Dashboard untuk Tim Mental Health**

### Psikiater Dashboard:
- Pasien dengan high-risk scores
- Medication review alerts
- Emergency cases
- Appointment schedule

### Psikolog Dashboard:
- Assessment results
- Therapy session notes
- Client progress tracking
- Testing schedules

### Nurse Dashboard:
- Inpatient monitoring
- Medication administration
- Vital signs tracking
- Behavior observations

## 5. **Mental Health Records**

```sql
-- Clinical Notes
- Assessment notes
- Progress notes
- Therapy session notes
- Discharge summary

-- History Tracking
- Previous episodes
- Hospitalization history
- Suicide attempts
- Treatment response history
```

## 6. **Confidentiality & Privacy**

```php
- Extra security layer untuk mental health records
- Restricted access (hanya authorized personnel)
- Patient consent for information sharing
- Anonymized data untuk research
```

## 7. **Integrasi dengan RS**

### A. Rawat Jalan (Outpatient)
- Online appointment booking
- Queue management
- Teleconsultation (optional)

### B. Rawat Inap (Inpatient)
- Admission assessment
- Daily monitoring
- Ward round notes
- Discharge planning

### C. Emergency/IGD
- Rapid screening tools
- Crisis assessment
- Emergency psychiatric consultation

## 8. **Reporting untuk RS**

```php
- Monthly statistics:
  * Jumlah pasien per diagnosis
  * Severity distribution
  * Treatment outcomes
  * Readmission rates
  
- Quality indicators:
  * Assessment completion time
  * Treatment adherence
  * Patient satisfaction
  * Recovery rates
```

---

## 📋 Implementasi Priority untuk RS

### HIGH PRIORITY:
1. ✅ PHQ-9 & GAD-7 screening forms
2. ✅ Suicide risk assessment
3. ✅ Role-based access (Psikiater, Psikolog, Perawat)
4. ✅ Crisis alert system
5. ✅ Medication tracking

### MEDIUM PRIORITY:
6. Treatment planning module
7. Follow-up scheduling
8. Progress notes system
9. Dashboard untuk each role
10. Reporting & statistics

### LOW PRIORITY:
11. Teleconsultation
12. Mobile app
13. Patient portal
14. Research analytics

---

**Mau saya implementasikan fitur mana dulu untuk RS?**

A. **PHQ-9 & GAD-7 screening** (Standar international)
B. **Suicide risk assessment** (Critical untuk safety)
C. **Role system khusus mental health** (Psikiater/Psikolog/Perawat)
D. **Crisis alert system** (Emergency response)
