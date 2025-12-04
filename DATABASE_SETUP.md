# Setup Database MySQL - Mental Health Predictor

## Langkah-langkah Setup:

### 1. Start XAMPP
- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**

### 2. Buka phpMyAdmin
- Buka browser: `http://localhost/phpmyadmin`
- Login (default username: `root`, password: kosong)

### 3. Import Database
Ada 2 cara:

#### Cara A: Import File SQL (Recommended)
1. Di phpMyAdmin, klik tab **"Import"**
2. Klik **"Choose File"**
3. Pilih file: `laravel_web/database/schema.sql`
4. Klik **"Go"**
5. Database `mental_health_db` dan table akan dibuat otomatis

#### Cara B: Manual via SQL Tab
1. Di phpMyAdmin, klik tab **"SQL"**
2. Copy semua isi file `laravel_web/database/schema.sql`
3. Paste di textarea SQL
4. Klik **"Go"**

### 4. Verifikasi Database
- Refresh phpMyAdmin
- Anda akan lihat database baru: `mental_health_db`
- Klik database tersebut
- Harus ada 2 tables:
  - `users` (untuk akun user)
  - `assessments` (untuk history assessment)

### 5. Test Demo Account
Database sudah include demo account:
- Email: `demo@example.com`
- Password: `demo123`

### 6. Konfigurasi Database (Jika Perlu)
Edit file: `laravel_web/config/database.php`

```php
return [
    'host' => 'localhost',          // Biasanya localhost
    'database' => 'mental_health_db',
    'username' => 'root',           // Default XAMPP
    'password' => '',               // Default XAMPP kosong
    'charset' => 'utf8mb4',
];
```

### 7. Test Aplikasi
1. Pastikan PHP server jalan: `php -S localhost:8000 -t laravel_web`
2. Buka browser: `http://localhost:8000`
3. Login dengan demo account atau register baru
4. Coba buat assessment
5. Cek history - data akan tersimpan di database!

## Struktur Database

### Table: users
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key (Auto Increment) |
| name | VARCHAR(100) | Nama user |
| email | VARCHAR(100) | Email (Unique) |
| password | VARCHAR(255) | Password (Hashed) |
| created_at | TIMESTAMP | Waktu register |
| updated_at | TIMESTAMP | Waktu update |

### Table: assessments
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key (Auto Increment) |
| user_id | INT | Foreign Key ke users |
| age | INT | Usia |
| stress_level | INT | Level stress (1-10) |
| anxiety_level | INT | Level anxiety (1-10) |
| depression_level | INT | Level depression (1-10) |
| mental_history | ENUM | Yes/No |
| sleep_hours | DECIMAL | Jam tidur |
| exercise_level | ENUM | Low/Medium/High |
| social_support | ENUM | Yes/No |
| prediction | VARCHAR(50) | Hasil prediksi |
| confidence | DECIMAL(5,4) | Confidence score |
| probabilities | JSON | Distribusi probabilitas |
| recommendations | JSON | Rekomendasi |
| created_at | TIMESTAMP | Waktu assessment |

## Troubleshooting

### Error: "Database connection failed"
- Pastikan MySQL di XAMPP sudah running (hijau)
- Check config di `laravel_web/config/database.php`
- Cek username/password MySQL Anda

### Error: "Table doesn't exist"
- Import ulang file `schema.sql` di phpMyAdmin
- Pastikan database `mental_health_db` sudah dibuat

### Error: "Access denied"
- Username/password salah
- Default XAMPP: username=`root`, password=kosong
- Cek di `config/database.php`

## Backup Data

### Export Database:
1. Buka phpMyAdmin
2. Pilih database `mental_health_db`
3. Klik tab **"Export"**
4. Pilih format **SQL**
5. Klik **"Go"**
6. File `.sql` akan didownload

### Restore Database:
1. Buka phpMyAdmin
2. Buat database baru atau pilih existing
3. Klik tab **"Import"**
4. Upload file backup `.sql`
5. Klik **"Go"**

## Keamanan

✅ Password di-hash dengan bcrypt (PHP password_hash)
✅ SQL Injection protected (PDO Prepared Statements)
✅ Session-based authentication
✅ Foreign key constraints untuk data integrity

Selamat, database MySQL sudah siap digunakan! 🎉
