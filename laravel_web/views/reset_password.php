<?php
$title = 'Reset Password';
$token = $_GET['token'] ?? '';
$error = $_GET['error'] ?? '';

if (empty($token)) {
    header('Location: login');
    exit;
}

// Verify token validity
require_once __DIR__ . '/../app/Database.php';
$db = Database::getInstance()->getConnection();

$stmt = $db->prepare("SELECT * FROM password_reset_tokens WHERE token = ? AND used_at IS NULL AND expires_at > NOW()");
$stmt->execute([$token]);
$tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tokenData) {
    header('Location: login?error=invalid_or_expired_token');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Mental Health Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reset-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .card-header-custom i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }
        
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="reset-card">
                    <div class="card-header-custom">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h3 class="mb-0">Reset Password</h3>
                        <p class="mb-0 mt-2" style="font-size: 0.9rem;">Masukkan password baru Anda</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                <?php
                                switch($error) {
                                    case 'password_required':
                                        echo 'Password wajib diisi';
                                        break;
                                    case 'password_too_short':
                                        echo 'Password minimal 6 karakter';
                                        break;
                                    case 'password_mismatch':
                                        echo 'Konfirmasi password tidak cocok';
                                        break;
                                    default:
                                        echo 'Terjadi kesalahan. Silakan coba lagi';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-person-fill me-2"></i>
                            <strong>Email:</strong> <?= htmlspecialchars($tokenData['email']) ?>
                        </div>
                        
                        <form method="POST" action="reset-password-process" id="resetForm">
                            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Password Baru
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        class="form-control form-control-lg" 
                                        id="password" 
                                        name="password" 
                                        placeholder="Minimal 6 karakter"
                                        required
                                        minlength="6"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="strengthBar"></div>
                                <small class="form-text text-muted" id="strengthText"></small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Konfirmasi Password
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        class="form-control form-control-lg" 
                                        id="confirm_password" 
                                        name="confirm_password" 
                                        placeholder="Ketik ulang password"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted" id="matchText"></small>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary-custom btn-lg" id="submitBtn">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Reset Password
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="alert alert-warning mb-0">
                            <h6 class="alert-heading">
                                <i class="bi bi-shield-exclamation me-2"></i>
                                Tips Password Aman
                            </h6>
                            <ul class="mb-0 small">
                                <li>Gunakan kombinasi huruf besar, kecil, angka</li>
                                <li>Minimal 8 karakter (lebih panjang lebih baik)</li>
                                <li>Jangan gunakan tanggal lahir atau nama</li>
                                <li>Gunakan password yang unik untuk setiap akun</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        });
        
        document.getElementById('toggleConfirm').addEventListener('click', function() {
            const confirm = document.getElementById('confirm_password');
            const icon = this.querySelector('i');
            
            if (confirm.type === 'password') {
                confirm.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                confirm.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        });
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Kekuatan: Lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Kekuatan: Sedang';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Kekuatan: Kuat';
                strengthText.style.color = '#28a745';
            }
        });
        
        // Password match checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchText = document.getElementById('matchText');
            
            if (confirm.length > 0) {
                if (password === confirm) {
                    matchText.textContent = '✓ Password cocok';
                    matchText.style.color = '#28a745';
                } else {
                    matchText.textContent = '✗ Password tidak cocok';
                    matchText.style.color = '#dc3545';
                }
            } else {
                matchText.textContent = '';
            }
        });
        
        // Form submission
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        });
    </script>
</body>
</html>
