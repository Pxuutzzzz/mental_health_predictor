<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mental Health Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?php
    // Load Google OAuth config
    $googleConfig = require __DIR__ . '/../config/google.php';
    $googleClientId = $googleConfig['client_id'];
    ?>
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --danger: #e74a3b;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            max-width: 450px;
            margin: 0 auto;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary), #224abe);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 600;
        }
        
        .login-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #224abe);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
        }
        
        .alert {
            border-radius: 8px;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 48px;"></i>
                    <h1>Welcome Back</h1>
                    <p>Mental Health Predictor System</p>
                </div>
                
                <div class="login-body">
                    <div id="alertContainer">
                    <?php if (isset($_GET['error']) && $_GET['error'] === 'login_required'): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Silakan login untuk mengakses halaman tersebut.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                </div>
                    
                    <?php 
                    $success = $_GET['success'] ?? '';
                    $error = $_GET['error'] ?? '';
                    
                    if ($success === 'password_reset'): 
                    ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Password berhasil direset! Silakan login dengan password baru Anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error === 'invalid_or_expired_token'): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            Token reset password tidak valid atau sudah kadaluarsa. Silakan minta link baru.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Google Sign In Button -->
                    <div id="g_id_onload"
                         data-client_id="<?php echo htmlspecialchars($googleClientId); ?>"
                         data-callback="handleCredentialResponse"
                         data-auto_prompt="false">
                    </div>
                    
                    <div class="g_id_signin mb-3" 
                         data-type="standard" 
                         data-size="large" 
                         data-theme="outline" 
                         data-text="signin_with"
                         data-shape="rectangular"
                         data-logo_alignment="left"
                         data-width="380">
                    </div>
                    
                    <div class="text-center text-muted my-3">
                        <small>Masuk menggunakan akun Google Anda</small>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="home" class="text-muted" style="text-decoration: none;">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function handleCredentialResponse(response) {
            // Show loading state
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert alert-info" role="alert">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Memproses login dengan Google...
                </div>
            `;
            
            // Send the credential to the server
            fetch('google-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ credential: response.credential })
            })
            .then(response => {
                // Get raw text first to debug
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        showAlert('Login berhasil! Mengalihkan...', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect || 'dashboard';
                        }, 1000);
                    } else {
                        showAlert(data.error || 'Login gagal', 'danger');
                    }
                } catch (e) {
                    // If not JSON, show the raw response for debugging
                    console.error('Not valid JSON:', text);
                    showAlert('Server error. Check console for details.', 'danger');
                }
            })
            .catch(error => {
                showAlert('Terjadi kesalahan: ' + error.message, 'danger');
            });
        }
        
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
