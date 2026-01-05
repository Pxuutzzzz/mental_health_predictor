# Fitur Integrasi Rumah Sakit (Hospital Integration) – Dokumentasi

## 📋 **Deskripsi Umum**

Fitur **Hospital Integration** memungkinkan sistem mengirimkan hasil assessment mental health langsung ke rumah sakit mitra untuk tindak lanjut klinis. Data dikirim secara otomatis atau opsional oleh user melalui API/endpoint rumah sakit yang telah terdaftar.

**Keunggulan:**
- ✅ Rujukan otomatis ke rumah sakit mitra
- ✅ Pengiriman terenkripsi via HTTPS + token
- ✅ Mendukung berbagai skema autentikasi (Bearer, Basic)
- ✅ Logging & audit trail lengkap (HIPAA compliant)
- ✅ Mode sandbox untuk testing
- ✅ Fallback ke queue jika endpoint tidak tersedia

---

## 🏗️ **Arsitektur**

### Komponen Sistem

```
┌──────────────┐     ┌────────────────────────┐     ┌─────────────────────┐
│ Assessment   │────▶│ PredictionController   │────▶│ HospitalIntegration │
│ Form (UI)    │     │ (Backend Controller)   │     │ Service             │
└──────────────┘     └────────────────────────┘     └─────────────────────┘
                                                               │
                                                               ▼
                                          ┌─────────────────────────────────┐
                                          │ Hospital Partner Endpoints      │
                                          │ (RS Hermina, RSUD Jakarta, dll) │
                                          └─────────────────────────────────┘
```

### File & Lokasi

| File | Lokasi | Deskripsi |
|------|---------|-----------|
| `hospital.php` | `config/` | Konfigurasi RS mitra (endpoint, auth) |
| `HospitalIntegrationService.php` | `app/Services/` | Service layer untuk integrasi |
| `PredictionController.php` | `app/Controllers/` | Controller assessment |
| `assessment.php` | `views/` | UI form rujukan RS |
| `schema.sql` | `database/` | Tabel `hospital_integrations` |
| `hospital_integrations.log` | `logs/` | Log khusus RS integrasi |

---

## 🔧 **Instalasi & Setup**

### 1. Jalankan Schema Database

```bash
# Pastikan database sudah dibuat
mysql -u root -p mental_health_db < database/schema.sql
```

Tabel **`hospital_integrations`** akan otomatis dibuat dengan struktur:
- `id`, `assessment_id`, `user_id`, `hospital_id`, `hospital_name`
- `patient_reference`, `status` (PENDING, SUCCESS, FAILED, QUEUED)
- `request_payload`, `response_payload`, `error_message`
- Timestamp creation & update

### 2. Konfigurasi Rumah Sakit Mitra

Edit file **`config/hospital.php`**:

```php
return [
    'enabled' => true,  // Aktifkan/nonaktifkan fitur
    'mode' => 'sandbox',  // 'sandbox' atau 'production'
    'timeout' => 8,  // Timeout HTTP (detik)
    'max_retries' => 2,  // Jumlah retry
    'facilities' => [
        [
            'id' => 'rs_hermina',
            'name' => 'RS Hermina Depok',
            'type' => 'Rumah Sakit Swasta',
            'location' => 'Depok, Jawa Barat',
            'endpoint' => 'https://api.hermina.id/v1/mental-health/intake',
            'auth' => [
                'type' => 'token',  // 'token' atau 'basic'
                'token' => getenv('RS_HERMINA_TOKEN') ?: 'demo-token'
            ],
            'active' => true
        ]
    ]
];
```

**Environment Variables (Opsional):**

Tambahkan ke `.env` atau environment server:

```env
RS_HERMINA_TOKEN=your_actual_bearer_token_here
RS_HERMINA_ENDPOINT=https://prod.hermina.id/api/v1/intake
RSUD_JKT_USER=api_username
RSUD_JKT_PASS=api_password
```

### 3. Cek Konfigurasi

```bash
php -r "print_r(require 'config/hospital.php');"
```

---

## 🎨 **Cara Menggunakan (User Flow)**

### Dari Sisi User (Pasien)

1. Isi form kuesioner 8 pertanyaan.
2. Pada pertanyaan terakhir (#8), muncul **Hospital Integration Card** (jika fitur aktif):
   - Toggle **"Aktifkan Rujukan"** untuk mengirim hasil ke RS.
   - Pilih rumah sakit mitra dari dropdown.
   - Isi nomor rekam medis (MRN) jika sudah punya (opsional).
   - Tambahkan catatan klinis (keluhan utama, konteks, dll).
3. Klik **"Selesai & Lihat Hasil"**.
4. Sistem:
   - Menyimpan assessment ke database.
   - Mengirimkan payload JSON ke endpoint RS mitra.
   - Menampilkan status integrasi pada halaman hasil.

### Status yang Muncul:

| Status | Icon | Warna | Arti |
|--------|------|-------|------|
| **SUCCESS** | ✅ | Hijau | Data berhasil diterima RS |
| **QUEUED** | ⏳ | Kuning | Disimpan lokal untuk pengiriman manual |
| **FAILED** | ❌ | Merah | Pengiriman gagal (akan retry) |

---

## 🔐 **Security & Compliance**

### Enkripsi & Keamanan

- ✅ **HTTPS Only**: Semua endpoint RS wajib HTTPS.
- ✅ **Bearer Token / Basic Auth**: Autentikasi API standar.
- ✅ **Data Minimization**: Hanya data essensial dikirim (tanpa identitas penuh).
- ✅ **Audit Logging**: Setiap pengiriman dicatat di `audit_logs` dan `hospital_integrations`.
- ✅ **Rate Limiting**: Retry mechanism + timeout untuk hindari spam.

### Payload yang Dikirim ke RS

```json
{
  "facility_id": "rs_hermina",
  "facility_name": "RS Hermina Depok",
  "assessment_id": 123,
  "patient_reference": "RS-ABC123XYZ",
  "submitted_at": "2026-01-05T10:30:00+07:00",
  "risk_category": "Moderate Risk",
  "risk_score": 0.65,
  "confidence": 0.82,
  "probabilities": {
    "Low Risk": 0.25,
    "Moderate Risk": 0.55,
    "High Risk": 0.20
  },
  "recommendations": ["Konsultasi dengan psikolog", "..."],
  "notes": "Pasien mengeluh insomnia sejak 2 minggu",
  "features": {
    "age": 28,
    "stress": 7,
    "anxiety": 6,
    "depression": 5,
    "sleep": 4.5,
    "exercise": "Low",
    "social_support": "Yes",
    "mental_history": "No"
  },
  "meta": {
    "source": "mental_health_predictor_laravel",
    "mode": "sandbox",
    "user_agent": "Mozilla/5.0..."
  }
}
```

**PENTING:** Tidak ada nama, email, alamat, atau data identitas pribadi (HIPAA/GDPR compliant).

---

## 📊 **Monitoring & Troubleshooting**

### Log Files

1. **`logs/hospital_integrations.log`** – Log khusus pengiriman RS:
   ```
   [2026-01-05 14:23:10] Queued integration #45 -> RS Hermina Depok
   [2026-01-05 14:23:12] Integration 45 status -> SUCCESS
   ```

2. **`logs/audit.log`** – Audit trail lengkap (via `AuditLogger`).

3. **Database `hospital_integrations`** – Semua transaksi tersimpan.

### Query Status Integrasi

```sql
-- Lihat 10 integrasi terakhir
SELECT id, hospital_name, status, created_at, error_message
FROM hospital_integrations
ORDER BY created_at DESC
LIMIT 10;

-- Count status
SELECT status, COUNT(*) AS total
FROM hospital_integrations
GROUP BY status;

-- Cek error terbaru
SELECT * FROM hospital_integrations
WHERE status = 'FAILED'
ORDER BY created_at DESC;
```

### Testing dengan cURL

```bash
# Test endpoint RS (sandbox)
curl -X POST https://sandbox.hermina.id/api/mental-health/intake \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer demo-token" \
  -d '{
    "assessment_id": 999,
    "risk_category": "Low Risk",
    "confidence": 0.89,
    "features": {"age": 25, "stress": 3}
  }'
```

---

## 🛠️ **Konfigurasi Lanjutan**

### Menambah RS Mitra Baru

Edit `config/hospital.php`, tambahkan elemen baru di array `facilities`:

```php
[
    'id' => 'rs_cipto',
    'name' => 'RSUP Cipto Mangunkusumo',
    'type' => 'RS Pendidikan',
    'location' => 'Jakarta Pusat',
    'endpoint' => getenv('RSUPN_CM_ENDPOINT'),
    'auth' => [
        'type' => 'token',
        'token' => getenv('RSUPN_CM_TOKEN')
    ],
    'supports_realtime' => true,
    'active' => true
]
```

### Mode Sandbox vs Production

- **Sandbox**: Endpoint dummy/testing, tidak ada aksi nyata.
- **Production**: Endpoint live rumah sakit, data masuk ke sistem EHR RS.

Toggle via `'mode' => 'production'` di `config/hospital.php`.

### Menonaktifkan Fitur

```php
return [
    'enabled' => false,  // Matikan total fitur
    // ... sisa config
];
```

UI akan otomatis hide hospital integration card.

---

## ✅ **Testing Checklist**

- [ ] Database table `hospital_integrations` ter-created.
- [ ] Config `hospital.php` sudah benar (`enabled = true`).
- [ ] Ada minimal 1 RS aktif di config.
- [ ] Form assessment menampilkan "Integrasi Rumah Sakit Mitra".
- [ ] Toggle switch "Aktifkan Rujukan" berfungsi.
- [ ] Dropdown RS berisi data RS aktif.
- [ ] Submit form tanpa rujukan → tidak ada error.
- [ ] Submit form dengan rujukan → muncul status pada hasil.
- [ ] Cek log `logs/hospital_integrations.log` terisi.
- [ ] Query DB `hospital_integrations` ada record.
- [ ] Audit log (`audit_logs`) mencatat event `HOSPITAL_SYNC`.

---

## 📞 **Kontak & Support**

Untuk integrasi RS baru atau troubleshooting:

1. Hubungi IT/DevOps tim rumah sakit terkait.
2. Minta **endpoint API** + **credential** (token/basic auth).
3. Tambahkan ke `config/hospital.php`.
4. Test dengan mode sandbox terlebih dahulu.

**Dokumentasi API RS** harus disediakan oleh pihak rumah sakit (format request, response, error codes).

---

## 🔄 **Update Log**

| Tanggal | Versi | Perubahan |
|---------|-------|-----------|
| 2026-01-05 | v1.0 | Initial release hospital integration |

---

**Catatan:** Fitur ini memerlukan persetujuan & perjanjian kerjasama (MOU/SLA) dengan rumah sakit mitra. Pastikan aspek legal & compliance sudah clear sebelum deploy ke production.
