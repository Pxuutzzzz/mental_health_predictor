# 🔧 TROUBLESHOOTING GUIDE
## Panduan Mengatasi Masalah Mental Health Predictor

---

## ❌ Problem: "Project tidak bisa di-run"

### Solusi Lengkap:

---

## 1️⃣ MASALAH PHP SERVER

### Gejala:
- Website tidak bisa diakses
- Error 404 Not Found
- "Connection refused"

### Solusi:

**Step 1: Stop server yang salah**
```powershell
# Cek process PHP yang berjalan
Get-Process | Where-Object {$_.ProcessName -eq "php"}

# Stop process (ganti PID dengan nomor yang muncul)
Stop-Process -Id [PID] -Force
```

**Step 2: Start server dari folder yang BENAR**
```powershell
# PENTING: Harus dari folder laravel_web!
cd C:\Users\putri\mental_health_predictor\laravel_web
php -S localhost:8000
```

**Step 3: Verifikasi server berjalan**
```
Output yang benar:
[Thu Dec  4 12:48:50 2025] PHP 8.2.12 Development Server (http://localhost:8000) started
```

**Step 4: Buka browser**
```
http://localhost:8000
```

---

## 2️⃣ MASALAH DATABASE

### Gejala:
- "Database connection failed"
- "Table doesn't exist"
- Error saat login/register

### Solusi:

**Step 1: Start XAMPP**
```
1. Buka XAMPP Control Panel
2. Start Apache (opsional)
3. Start MySQL (WAJIB)
4. Tunggu sampai hijau
```

**Step 2: Cek MySQL berjalan**
```powershell
netstat -ano | findstr :3306
```

Jika tidak ada output, MySQL tidak berjalan!

**Step 3: Import Database**
```
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Klik tab "Import"
3. Choose File → Select: laravel_web/database/schema.sql
4. Klik "Go"
5. Database mental_health_db akan terbuat
```

**Step 4: Verifikasi Database**
```
1. Di phpMyAdmin, klik database "mental_health_db"
2. Harus ada 2 tables:
   - users
   - assessments
```

**Step 5: Test Login**
```
Email: demo@example.com
Password: demo123
```

---

## 3️⃣ MASALAH PYTHON/ML MODEL

### Gejala:
- "Model not found"
- "Python command not found"
- Prediction error

### Solusi:

**Step 1: Cek model files exist**
```powershell
dir models\
```

Harus ada:
- mental_health_model.pkl
- scaler.pkl
- label_encoder.pkl

Jika tidak ada, train model dulu!

**Step 2: Train Model**
```powershell
python src\model_train.py
```

**Step 3: Cek Conda Environment**
```powershell
# Cek conda installed
conda --version

# Cek environment exist
conda env list
```

**Step 4: Test Prediction**
```powershell
# Test via web: http://localhost:8000
# Go to "Cek Kesehatan Mental"
# Isi form dan submit
```

---

## 4️⃣ PORT SUDAH DIGUNAKAN

### Gejala:
- "Address already in use"
- "Port 8000 is already in use"

### Solusi:

**Option 1: Stop process di port 8000**
```powershell
# Cek siapa yang pakai port 8000
netstat -ano | findstr :8000

# Output contoh:
# TCP    [::1]:8000    [::]:0    LISTENING    17192
#                                              ^^^^^ ini PID

# Stop process
Stop-Process -Id 17192 -Force

# Start ulang server
cd laravel_web
php -S localhost:8000
```

**Option 2: Gunakan port lain**
```powershell
cd laravel_web
php -S localhost:8080  # Ganti ke port 8080
```

---

## 5️⃣ MASALAH PERMISSION/FILE ACCESS

### Gejala:
- "Permission denied"
- "Cannot open file"
- "Access denied"

### Solusi:

**Step 1: Run PowerShell as Administrator**
```
1. Klik kanan PowerShell
2. Pilih "Run as Administrator"
3. Navigate ke project folder
```

**Step 2: Cek file permissions**
```powershell
# Cek akses ke folder
cd C:\Users\putri\mental_health_predictor
dir
```

**Step 3: Fix permissions jika perlu**
```powershell
# Disable read-only
attrib -r laravel_web\* /s
```

---

## 6️⃣ BROWSER ISSUES

### Gejala:
- Halaman blank
- CSS tidak load
- JavaScript error

### Solusi:

**Step 1: Clear Browser Cache**
```
Chrome/Edge: Ctrl + Shift + Delete
Pilih "Cached images and files"
Clear
```

**Step 2: Hard Reload**
```
Chrome/Edge: Ctrl + Shift + R
Firefox: Ctrl + F5
```

**Step 3: Try Incognito/Private Mode**
```
Chrome: Ctrl + Shift + N
Edge: Ctrl + Shift + P
Firefox: Ctrl + Shift + P
```

**Step 4: Cek Console untuk error**
```
F12 → Console tab
Lihat apakah ada error
```

---

## 7️⃣ SESSION/LOGIN ISSUES

### Gejala:
- "Not logged in"
- Redirect loop
- Session expired terus

### Solusi:

**Step 1: Clear PHP Sessions**
```powershell
# Hapus session files
cd laravel_web
Remove-Item -Recurse -Force sessions\* -ErrorAction SilentlyContinue
```

**Step 2: Clear Browser Cookies**
```
1. F12 → Application tab
2. Cookies → http://localhost:8000
3. Clear All
```

**Step 3: Login ulang**
```
Email: demo@example.com
Password: demo123
```

---

## 8️⃣ CHECKLIST LENGKAP SEBELUM RUN

### ✅ Pre-flight Check:

```powershell
# 1. Cek lokasi
pwd
# Harus di: C:\Users\putri\mental_health_predictor

# 2. Cek folder laravel_web exist
dir laravel_web

# 3. Cek MySQL running (XAMPP)
netstat -ano | findstr :3306

# 4. Cek port 8000 kosong
netstat -ano | findstr :8000

# 5. Start server
cd laravel_web
php -S localhost:8000

# 6. Buka browser
# http://localhost:8000
```

---

## 9️⃣ QUICK START (DARI NOL)

### Langkah Cepat:

```powershell
# 1. Masuk folder project
cd C:\Users\putri\mental_health_predictor

# 2. Start XAMPP MySQL
# (Manual via XAMPP Control Panel)

# 3. Import database (sekali saja)
# Buka: http://localhost/phpmyadmin
# Import: laravel_web/database/schema.sql

# 4. Start PHP server
cd laravel_web
php -S localhost:8000

# 5. Buka browser
# http://localhost:8000

# 6. Login
# Email: demo@example.com
# Password: demo123
```

---

## 🔟 ERROR MESSAGES COMMON

### Error 1: "Database connection failed"
**Solusi:** Start MySQL di XAMPP, lalu refresh halaman

### Error 2: "Model not found"
**Solusi:** Run `python src\model_train.py`

### Error 3: "404 Not Found"
**Solusi:** Pastikan server running dari folder `laravel_web`

### Error 4: "Invalid email or password"
**Solusi:** Gunakan demo account atau register baru

### Error 5: "Port already in use"
**Solusi:** Stop process lama dengan `Stop-Process -Id [PID]`

### Error 6: "Python not found"
**Solusi:** Install Python atau gunakan conda environment

### Error 7: "XAMPP MySQL won't start"
**Solusi:** Port 3306 mungkin digunakan program lain
```powershell
netstat -ano | findstr :3306
Stop-Process -Id [PID]
```

### Error 8: "Call to undefined function password_hash()"
**Solusi:** PHP version < 5.5, upgrade ke PHP 8.2+

---

## 📞 SUPPORT

Jika masih error setelah semua solusi:

1. **Check PHP Error Logs**
   ```powershell
   # Cek terminal dimana server running
   # Akan ada error messages
   ```

2. **Check Browser Console**
   ```
   F12 → Console tab
   Lihat error JavaScript
   ```

3. **Check Network Tab**
   ```
   F12 → Network tab
   Lihat request yang failed
   ```

4. **Restart Everything**
   ```powershell
   # Stop PHP server (Ctrl+C)
   # Stop XAMPP MySQL
   # Close browser
   # Start XAMPP MySQL
   # Start PHP server
   # Buka browser baru
   ```

---

## ✅ STATUS CHECK COMMAND

Copy-paste command ini untuk cek semua status:

```powershell
Write-Host "`n=== STATUS CHECK ===" -ForegroundColor Cyan

# 1. PHP Version
Write-Host "`n1. PHP Version:" -ForegroundColor Yellow
php --version

# 2. Current Directory
Write-Host "`n2. Current Directory:" -ForegroundColor Yellow
Get-Location

# 3. Laravel Web Folder
Write-Host "`n3. Laravel Web Folder:" -ForegroundColor Yellow
Test-Path "laravel_web" | ForEach-Object { if($_) { Write-Host "✅ Exists" -ForegroundColor Green } else { Write-Host "❌ Not Found" -ForegroundColor Red } }

# 4. MySQL Port
Write-Host "`n4. MySQL Status (port 3306):" -ForegroundColor Yellow
$mysql = netstat -ano | findstr :3306
if($mysql) { Write-Host "✅ Running" -ForegroundColor Green; $mysql } else { Write-Host "❌ Not Running" -ForegroundColor Red }

# 5. PHP Server Port
Write-Host "`n5. PHP Server (port 8000):" -ForegroundColor Yellow
$php = netstat -ano | findstr :8000
if($php) { Write-Host "✅ Running" -ForegroundColor Green; $php } else { Write-Host "❌ Not Running" -ForegroundColor Red }

# 6. Model Files
Write-Host "`n6. ML Model Files:" -ForegroundColor Yellow
Test-Path "models\mental_health_model.pkl" | ForEach-Object { if($_) { Write-Host "✅ Model exists" -ForegroundColor Green } else { Write-Host "❌ Model not found" -ForegroundColor Red } }

# 7. Database Schema
Write-Host "`n7. Database Schema File:" -ForegroundColor Yellow
Test-Path "laravel_web\database\schema.sql" | ForEach-Object { if($_) { Write-Host "✅ Schema exists" -ForegroundColor Green } else { Write-Host "❌ Schema not found" -ForegroundColor Red } }

Write-Host "`n===================`n" -ForegroundColor Cyan
```

---

**Last Updated:** 4 Desember 2025  
**Status:** Production Ready ✅
