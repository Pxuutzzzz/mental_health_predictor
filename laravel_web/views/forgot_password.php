<?php
$title = 'Lupa Password';
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
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
        
        .forgot-card {
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
        
        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="forgot-card">
                    <div class="card-header-custom">
                        <i class="bi bi-key-fill"></i>
                        <h3 class="mb-0">Lupa Password?</h3>
                        <p class="mb-0 mt-2" style="font-size: 0.9rem;">Masukkan email Anda untuk reset password</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                <?php
                                switch($error) {
                                    case 'email_required':
                                        echo 'Email wajib diisi';
                                        break;
                                    case 'invalid_email':
                                        echo 'Format email tidak valid';
                                        break;
                                    case 'email_not_found':
                                        echo 'Email tidak terdaftar dalam sistem';
                                        break;
                                    case 'too_many_requests':
                                        echo 'Terlalu banyak permintaan. Coba lagi dalam 5 menit';
                                        break;
                                    default:
                                        echo 'Terjadi kesalahan. Silakan coba lagi';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error === 'email_failed'): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Gagal mengirim email. Silakan hubungi administrator atau coba lagi nanti.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="forgot-password-process" id="forgotForm">
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2"></i>
                                    Alamat Email
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control form-control-lg" 
                                    id="email" 
                                    name="email" 
                                    placeholder="nama@email.com"
                                    required
                                    autocomplete="email"
                                >
                                <small class="form-text text-muted">
                                    Kami akan mengirimkan link reset password ke email ini
                                </small>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary-custom btn-lg" id="submitBtn">
                                    <i class="bi bi-send-fill me-2"></i>
                                    Kirim Link Reset Password
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="login" class="back-link">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali ke Login
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Informasi
                            </h6>
                            <ul class="mb-0 small">
                                <li>Link reset password berlaku selama 1 jam</li>
                                <li>Jika tidak menerima email, cek folder spam</li>
                                <li>Untuk keamanan, token hanya bisa digunakan sekali</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
        });
    </script>
</body>
</html>
