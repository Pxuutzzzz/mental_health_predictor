<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mental Health Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php
    // Load Google OAuth config
    $googleConfig = require __DIR__ . '/../config/google.php';
    $googleClientId = $googleConfig['client_id'];
    ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4e73df;
            --primary-dark: #224abe;
            --success: #1cc88a;
            --danger: #e74a3b;
            --purple: #764ba2;
            --blue: #667eea;
        }

        body {
            background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background shapes */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
        }

        body::after {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        .login-container {
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .login-header .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .login-header h1 {
            font-size: 32px;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-header p {
            margin: 12px 0 0 0;
            opacity: 0.95;
            font-size: 15px;
            font-weight: 500;
        }

        .login-body {
            padding: 45px 40px;
        }

        .form-control {
            padding: 14px 18px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .google-signin-wrapper {
            margin: 25px 0;
        }

        .google-signin-wrapper>div {
            display: flex;
            justify-content: center;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #ddd, transparent);
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 13px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #e3f2fd;
            color: #1976d2;
        }

        .alert-success {
            background: #e8f5e9;
            color: #388e3c;
        }

        .alert-danger {
            background: #ffebee;
            color: #d32f2f;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .back-link:hover {
            color: var(--primary);
            background: #f5f5f5;
            transform: translateX(-5px);
        }

        .info-text {
            text-align: center;
            color: #666;
            font-size: 13px;
            margin: 15px 0;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-header {
                padding: 40px 30px;
            }

            .login-header h1 {
                font-size: 26px;
            }

            .login-body {
                padding: 35px 30px;
            }

            .login-card {
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="icon-wrapper">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 42px;"></i>
                    </div>
                    <h1>Selamat Datang</h1>
                    <p>Sistem Prediksi Kesehatan Mental</p>
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
                    <div id="g_id_onload" data-client_id="<?php echo htmlspecialchars($googleClientId); ?>"
                        data-callback="handleCredentialResponse" data-auto_prompt="false">
                    </div>

                    <div class="google-signin-wrapper">
                        <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"
                            data-text="signin_with" data-shape="pill" data-logo_alignment="left" data-width="400">
                        </div>
                    </div>

                    <div class="info-text">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Masuk dengan aman menggunakan akun Google Anda
                    </div>

                    <div class="divider">
                        <span>atau</span>
                    </div>

                    <div class="text-center">
                        <a href="home" class="back-link">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <small style="color: rgba(255,255,255,0.9); font-weight: 500;">
                    <i class="bi bi-lock-fill me-1"></i>
                    Data Anda dilindungi dengan enkripsi end-to-end
                </small>
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
                            showAlert('<i class="bi bi-check-circle-fill me-2"></i>Login berhasil! Mengalihkan...', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect || 'dashboard';
                            }, 1000);
                        } else {
                            showAlert('<i class="bi bi-exclamation-circle-fill me-2"></i>' + (data.error || 'Login gagal'), 'danger');
                        }
                    } catch (e) {
                        // If not JSON, show the raw response for debugging
                        console.error('Not valid JSON:', text);
                        showAlert('<i class="bi bi-exclamation-circle-fill me-2"></i>Server error. Check console for details.', 'danger');
                    }
                })
                .catch(error => {
                    showAlert('<i class="bi bi-exclamation-circle-fill me-2"></i>Terjadi kesalahan: ' + error.message, 'danger');
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