# 🚀 PANDUAN DEPLOYMENT - MENTAL HEALTH PREDICTOR

**Tanggal:** 4 Desember 2025  
**Project:** Mental Health Risk Prediction System  
**Status:** Production Ready ✅

---

## 📋 DAFTAR ISI

1. [Deployment Lokal (Development)](#1-deployment-lokal-development)
2. [Deployment ke Hosting Shared (cPanel)](#2-deployment-ke-hosting-shared-cpanel)
3. [Deployment ke VPS (Linux)](#3-deployment-ke-vps-linux)
4. [Deployment ke Cloud (Heroku/Railway)](#4-deployment-ke-cloud-herokurailway)
5. [Checklist Pre-Deployment](#5-checklist-pre-deployment)
6. [Troubleshooting Deployment](#6-troubleshooting-deployment)

---

## 1. DEPLOYMENT LOKAL (Development)

### ✅ Status: SUDAH BERJALAN

Project sudah running di:
- **URL:** http://localhost:8000
- **Server:** PHP Built-in Development Server
- **Database:** MySQL (mental_health_db)

### Cara Menjalankan:

```powershell
# 1. Buka terminal di VS Code
cd c:\Users\putri\mental_health_predictor

# 2. Pastikan MySQL sudah running (XAMPP/WAMP)
# Buka XAMPP Control Panel, start Apache & MySQL

# 3. Start PHP Server
cd laravel_web
php -S localhost:8000

# 4. Buka browser
# http://localhost:8000
```

### Untuk Presentasi/Demo:
✅ Gunakan localhost (sudah perfect)  
✅ Pastikan internet aktif (untuk maps API)  
✅ Siapkan akun demo: user/password atau registrasi baru

---

## 2. DEPLOYMENT KE HOSTING SHARED (cPanel)

### 📌 Cocok untuk: Tugas kuliah, project pribadi, budget rendah

### Rekomendasi Hosting:
- **Hostinger** (Rp 20-40rb/bulan)
- **Niagahoster** (Rp 15-30rb/bulan)
- **DomainRacer** (gratis 1 tahun untuk mahasiswa)
- **InfinityFree** (gratis, terbatas)

### Langkah Deployment:

#### A. Persiapan Files

```powershell
# 1. Compress project (exclude yang tidak perlu)
cd c:\Users\putri\mental_health_predictor

# 2. Buat folder untuk upload
New-Item -ItemType Directory -Path "deploy_package" -Force

# 3. Copy files yang diperlukan
Copy-Item -Path "laravel_web\*" -Destination "deploy_package\" -Recurse
Copy-Item -Path "models\*" -Destination "deploy_package\models\" -Recurse
Copy-Item -Path "data\Data.csv" -Destination "deploy_package\data\"

# 4. Compress menjadi ZIP
Compress-Archive -Path "deploy_package\*" -DestinationPath "mental_health_app.zip"
```

#### B. Upload ke Hosting

1. **Login ke cPanel**
2. **Buka File Manager**
3. **Navigate ke public_html**
4. **Upload mental_health_app.zip**
5. **Extract ZIP file**

#### C. Setup Database

1. **Buka MySQL Databases di cPanel**
2. **Create Database:** `namauser_mental_health_db`
3. **Create User:** `namauser_dbuser` dengan password kuat
4. **Assign User ke Database** (All Privileges)
5. **Import database:**
   - Buka phpMyAdmin
   - Pilih database yang dibuat
   - Import file SQL (buat dulu dari lokal)

#### D. Export Database dari Lokal

```powershell
# Di komputer lokal (jika ada mysqldump)
mysqldump -u root -p mental_health_db > database_backup.sql

# Atau export manual dari phpMyAdmin lokal
# Database → Export → Go
```

#### E. Konfigurasi Database

Edit `config/database.php` di hosting:

```php
<?php
return [
    'host' => 'localhost',
    'database' => 'namauser_mental_health_db',  // Ganti dengan nama database hosting
    'username' => 'namauser_dbuser',             // Ganti dengan username hosting
    'password' => 'password_kuat_anda',          // Ganti dengan password database
    'charset' => 'utf8mb4'
];
```

#### F. Setup Python Environment (⚠️ MASALAH)

**PROBLEM:** Shared hosting biasanya TIDAK support Python atau akses terbatas.

**SOLUSI 1 - Gunakan Mock Prediction:**

Edit `app/Controllers/PredictionController.php` (baris ~73):

```php
// Comment baris Python execution:
// $output = shell_exec($command);

// Langsung gunakan mock:
$result = $this->getMockResult($age, $stress, $anxiety, $depression, $sleep, $exercise, $social_support, $mental_history);
```

**SOLUSI 2 - API External:**

Setup API terpisah di VPS/cloud untuk ML prediction (lihat bagian 4).

#### G. Set Permissions

```bash
# Via cPanel Terminal atau SSH
chmod 755 -R public_html/
chmod 644 public_html/index.php
chmod 666 public_html/logs/*.log
```

#### H. Testing

1. Buka: `http://yourdomain.com`
2. Test register/login
3. Test questionnaire (akan gunakan mock prediction)
4. Check error: `yourdomain.com/logs/` atau cPanel Error Log

---

## 3. DEPLOYMENT KE VPS (Linux)

### 📌 Cocok untuk: Production, full control, scalability

### Rekomendasi VPS:
- **DigitalOcean** ($4-6/month)
- **Vultr** ($2.50-5/month)
- **Linode** ($5/month)
- **AWS EC2** (Free tier 1 tahun)
- **Google Cloud** ($300 free credit)

### A. Setup Server (Ubuntu 22.04)

```bash
# 1. Update system
sudo apt update && sudo apt upgrade -y

# 2. Install LEMP Stack
sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl -y

# 3. Install Python & dependencies
sudo apt install python3 python3-pip -y
pip3 install scikit-learn pandas numpy joblib

# 4. Secure MySQL
sudo mysql_secure_installation
```

### B. Upload Project

```bash
# Di komputer lokal, upload via SCP
scp -r c:\Users\putri\mental_health_predictor root@your-vps-ip:/var/www/

# Atau gunakan FileZilla/WinSCP
```

### C. Setup Nginx

```bash
# Buat config file
sudo nano /etc/nginx/sites-available/mental-health

# Isi config:
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/mental_health_predictor/laravel_web;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/mental-health /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### D. Setup Database

```bash
# Login MySQL
sudo mysql -u root -p

# Create database dan user
CREATE DATABASE mental_health_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mentalhealth_user'@'localhost' IDENTIFIED BY 'password_kuat_123!';
GRANT ALL PRIVILEGES ON mental_health_db.* TO 'mentalhealth_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database
mysql -u mentalhealth_user -p mental_health_db < database_backup.sql
```

### E. Setup Python ML Model

```bash
# Set proper paths di PredictionController.php
# Model path: /var/www/mental_health_predictor/models/

# Test Python
python3 -c "import joblib; import sklearn; print('OK')"

# Set permissions
sudo chown -R www-data:www-data /var/www/mental_health_predictor
sudo chmod -R 755 /var/www/mental_health_predictor
```

### F. SSL Certificate (HTTPS)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate (gratis)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal sudah aktif otomatis
```

### G. Setup Firewall

```bash
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

---

## 4. DEPLOYMENT KE CLOUD (Heroku/Railway)

### 📌 Cocok untuk: Quick deployment, auto-scaling

### Option A: Railway.app (Recommended)

**Kelebihan:**
- ✅ Free tier generous
- ✅ Support PHP & Python
- ✅ PostgreSQL included
- ✅ Auto SSL
- ✅ Easy deployment

**Langkah:**

1. **Install Railway CLI:**
```powershell
npm install -g @railway/cli
```

2. **Buat railway.json:**
```json
{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php -S 0.0.0.0:$PORT -t laravel_web",
    "restartPolicyType": "ON_FAILURE"
  }
}
```

3. **Deploy:**
```powershell
cd c:\Users\putri\mental_health_predictor
railway login
railway init
railway up
```

### Option B: Heroku

**Langkah:**

1. **Buat Procfile:**
```
web: cd laravel_web && php -S 0.0.0.0:$PORT
```

2. **Buat composer.json (jika belum ada):**
```json
{
    "require": {
        "php": "^8.2"
    }
}
```

3. **Deploy:**
```powershell
# Install Heroku CLI
# Download dari: https://devcenter.heroku.com/articles/heroku-cli

heroku login
heroku create mental-health-app
git init
git add .
git commit -m "Initial deployment"
git push heroku main

# Add MySQL ClearDB
heroku addons:create cleardb:ignite
```

### Option C: Python ML sebagai Microservice (API)

**Setup terpisah untuk ML model:**

```python
# Buat file: ml_api/app.py
from flask import Flask, request, jsonify
import joblib
import numpy as np

app = Flask(__name__)

# Load models
model = joblib.load('mental_health_model.pkl')
scaler = joblib.load('scaler.pkl')
label_encoder = joblib.load('label_encoder.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    
    # Prepare features
    features = np.array([[
        data['age'], data['stress'], data['anxiety'], 
        data['depression'], data['mental_history'],
        data['sleep'], data['exercise'], data['social_support']
    ]])
    
    # Scale and predict
    features_scaled = scaler.transform(features)
    prediction = model.predict(features_scaled)[0]
    probabilities = model.predict_proba(features_scaled)[0]
    
    # Format response
    result = {
        'prediction': label_encoder.inverse_transform([prediction])[0],
        'confidence': float(max(probabilities)),
        'probabilities': {
            label_encoder.inverse_transform([i])[0]: float(prob)
            for i, prob in enumerate(probabilities)
        }
    }
    
    return jsonify(result)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
```

**Deploy ke Railway/Heroku, lalu update PredictionController:**

```php
// Ganti Python execution dengan API call
$response = file_get_contents('https://your-ml-api.railway.app/predict', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($data)
    ]
]));
$result = json_decode($response, true);
```

---

## 5. CHECKLIST PRE-DEPLOYMENT

### ✅ Code Preparation

```markdown
- [ ] Update database credentials di config/database.php
- [ ] Ganti URL hardcoded (localhost → domain real)
- [ ] Set error reporting ke production mode
- [ ] Hapus debug/console.log statements
- [ ] Test semua fitur di lokal
- [ ] Backup database lokal
- [ ] Buat file .htaccess untuk Apache
- [ ] Set proper file permissions
- [ ] Update API keys (Google Maps, dll)
- [ ] Test responsive di mobile
```

### ✅ Security Checklist

```markdown
- [ ] Ganti semua password default
- [ ] Enable HTTPS/SSL
- [ ] Set secure session configuration
- [ ] Validate & sanitize all inputs
- [ ] Use prepared statements (✅ sudah)
- [ ] Hide error messages dari user
- [ ] Protect directory listing
- [ ] Set proper CORS headers
- [ ] Enable rate limiting
- [ ] Regular security updates
```

### ✅ Performance Optimization

```markdown
- [ ] Enable PHP OPcache
- [ ] Minify CSS/JS
- [ ] Compress images
- [ ] Enable GZIP compression
- [ ] Set browser caching headers
- [ ] Optimize database queries
- [ ] Add database indexes
- [ ] Use CDN for static assets
- [ ] Enable lazy loading images
- [ ] Monitor server resources
```

---

## 6. TROUBLESHOOTING DEPLOYMENT

### Error: "500 Internal Server Error"

```bash
# Check PHP error log
tail -f /var/log/nginx/error.log
# atau
tail -f /var/log/apache2/error.log

# Enable display errors (temporary)
# Di index.php tambahkan:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Error: "Database Connection Failed"

```bash
# Test MySQL connection
mysql -u username -p database_name

# Check MySQL service
sudo systemctl status mysql

# Verify credentials di config/database.php
```

### Error: "Permission Denied"

```bash
# Set proper ownership (Linux)
sudo chown -R www-data:www-data /var/www/your-app
sudo chmod -R 755 /var/www/your-app

# Directories writable
sudo chmod -R 777 /var/www/your-app/logs
```

### Python/ML Model Not Working

```bash
# Opsi 1: Gunakan Mock Prediction (recommended untuk shared hosting)
# Edit PredictionController.php, comment Python execution

# Opsi 2: Deploy ML sebagai API terpisah (recommended untuk production)

# Opsi 3: Test Python di server
python3 -c "import sklearn, joblib; print('OK')"
```

### CSS/JS Not Loading

```html
<!-- Ganti path relative ke absolute -->
<!-- Dari: -->
<link href="assets/css/style.css">

<!-- Ke: -->
<link href="/assets/css/style.css">
<!-- atau -->
<link href="https://yourdomain.com/assets/css/style.css">
```

---

## 7. REKOMENDASI DEPLOYMENT

### Untuk Tugas Kuliah / Demo:
✅ **Localhost** (paling mudah, gratis)
- Presentasi langsung dari laptop
- Tidak perlu hosting

### Untuk Skripsi / Portfolio:
✅ **Shared Hosting (Hostinger/Niagahoster)**
- Murah (Rp 20-40rb/bulan)
- cPanel mudah
- Gunakan Mock Prediction untuk ML

### Untuk Production / Real App:
✅ **VPS (DigitalOcean/Vultr)**
- Full control
- Python support penuh
- Scalable
- SSL gratis (Let's Encrypt)

### Untuk Quick Test Online:
✅ **Railway.app**
- Gratis (limited)
- Deploy dalam 5 menit
- Auto SSL

---

## 8. MAINTENANCE POST-DEPLOYMENT

### Backup Rutin

```bash
# Backup database (weekly)
mysqldump -u user -p database_name > backup_$(date +%Y%m%d).sql

# Backup files (weekly)
tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/your-app
```

### Monitoring

```bash
# Check server resources
htop
df -h  # Disk usage
free -m  # Memory usage

# Check logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

### Updates

```bash
# Update system (monthly)
sudo apt update && sudo apt upgrade -y

# Update PHP packages
composer update

# Update Python packages
pip3 install --upgrade scikit-learn pandas numpy
```

---

## 📞 SUPPORT & RESOURCES

### Documentation:
- PHP: https://www.php.net/docs.php
- MySQL: https://dev.mysql.com/doc/
- Nginx: https://nginx.org/en/docs/
- scikit-learn: https://scikit-learn.org/

### Hosting Tutorials:
- cPanel: https://docs.cpanel.net/
- DigitalOcean: https://www.digitalocean.com/community/tutorials
- Railway: https://docs.railway.app/

### Get Help:
- Stack Overflow: https://stackoverflow.com/
- GitHub Issues: https://github.com/

---

## ✅ QUICK START - DEPLOYMENT TERCEPAT

### Untuk Demo Hari Ini:
```powershell
# SUDAH JALAN! Cukup:
cd c:\Users\putri\mental_health_predictor\laravel_web
php -S localhost:8000

# Buka: http://localhost:8000
```

### Untuk Online dalam 1 Jam (Railway):
```powershell
npm install -g @railway/cli
cd c:\Users\putri\mental_health_predictor
railway login
railway init
railway up
# Selesai! URL otomatis: https://your-app.railway.app
```

### Untuk Production dalam 1 Hari (VPS):
1. Beli VPS (DigitalOcean/Vultr) - $5/bulan
2. Follow "Section 3" step by step
3. Setup domain & SSL
4. Done!

---

**Dibuat oleh:** GitHub Copilot  
**Tanggal:** 4 Desember 2025  
**Status:** Complete Deployment Guide ✅

**CATATAN:** Pilih metode deployment sesuai kebutuhan dan budget. Untuk presentasi/demo, localhost sudah sempurna!
