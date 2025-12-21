<?php
// Development helper - shows reset password link
// In production, this would be sent via email

session_start();

if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
    header('Location: login');
    exit;
}

$token = $_SESSION['reset_token'];
$email = $_SESSION['reset_email'];
$resetLink = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/reset-password?token=' . $token;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Link - Development Mode</title>
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
        
        .dev-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 2rem;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        
        .link-box {
            background: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 1rem;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.9rem;
        }
        
        .btn-copy {
            background: #667eea;
            color: white;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-copy:hover {
            background: #764ba2;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dev-card">
        <div class="text-center mb-4">
            <i class="bi bi-envelope-open text-primary" style="font-size: 4rem;"></i>
            <h3 class="mt-3">Reset Password Link</h3>
            <p class="text-muted">Development Mode - Email Not Sent</p>
        </div>
        
        <div class="alert alert-warning mb-4">
            <h6 class="alert-heading">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Development Mode
            </h6>
            <p class="mb-0">
                Dalam mode produksi, link ini akan dikirim via email ke <strong><?= htmlspecialchars($email) ?></strong>
            </p>
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold">
                <i class="bi bi-link-45deg me-2"></i>
                Reset Password Link:
            </label>
            <div class="link-box mb-2" id="resetLink">
                <?= htmlspecialchars($resetLink) ?>
            </div>
            <button class="btn btn-copy w-100" onclick="copyLink()">
                <i class="bi bi-clipboard me-2"></i>
                Copy Link
            </button>
        </div>
        
        <div class="mb-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                Informasi Token:
            </h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <i class="bi bi-person-fill text-primary me-2"></i>
                    <strong>Email:</strong> <?= htmlspecialchars($email) ?>
                </li>
                <li class="mb-2">
                    <i class="bi bi-key-fill text-primary me-2"></i>
                    <strong>Token:</strong> <code><?= substr($token, 0, 16) ?>...</code>
                </li>
                <li class="mb-2">
                    <i class="bi bi-clock-fill text-primary me-2"></i>
                    <strong>Berlaku selama:</strong> 1 jam
                </li>
                <li class="mb-2">
                    <i class="bi bi-shield-check text-primary me-2"></i>
                    <strong>Penggunaan:</strong> Sekali pakai
                </li>
            </ul>
        </div>
        
        <div class="d-grid gap-2">
            <a href="<?= htmlspecialchars($resetLink) ?>" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-right-circle-fill me-2"></i>
                Buka Link Reset Password
            </a>
            <a href="login" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Login
            </a>
        </div>
        
        <hr class="my-4">
        
        <div class="alert alert-info mb-0">
            <h6 class="alert-heading">
                <i class="bi bi-envelope-fill me-2"></i>
                Untuk Implementasi Email:
            </h6>
            <p class="mb-2 small">Tambahkan konfigurasi SMTP di <code>AuthController.php</code>:</p>
            <pre class="mb-0 small" style="background: #f8f9fa; padding: 10px; border-radius: 5px;"><code>// Contoh menggunakan PHPMailer
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('noreply@mentalhealthpredictor.com');
$mail->addAddress($email);
$mail->Subject = 'Reset Password';
$mail->Body = "Click this link: $resetLink";
$mail->send();</code></pre>
        </div>
    </div>
    
    <script>
        function copyLink() {
            const link = document.getElementById('resetLink').textContent;
            navigator.clipboard.writeText(link).then(() => {
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Link Disalin!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-copy');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-copy');
                }, 2000);
            });
        }
    </script>
</body>
</html>
