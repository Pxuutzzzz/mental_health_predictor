<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mental Health Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            padding: 20px 0;
        }
        
        .register-container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--success), #17a673);
            color: white;
            padding: 35px 30px;
            text-align: center;
        }
        
        .register-header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 600;
        }
        
        .register-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .register-body {
            padding: 35px 30px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: var(--success);
            box-shadow: 0 0 0 0.2rem rgba(28, 200, 138, 0.25);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #17a673);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
        }
        
        .alert {
            border-radius: 8px;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        
        .strength-weak { background: #e74a3b; width: 33%; }
        .strength-medium { background: #f6c23e; width: 66%; }
        .strength-strong { background: #1cc88a; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <i class="bi bi-person-plus-fill" style="font-size: 48px;"></i>
                    <h1>Create Account</h1>
                    <p>Join Mental Health Predictor</p>
                </div>
                
                <div class="register-body">
                    <div id="alertContainer"></div>
                    
                    <form id="registerForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Minimum 6 characters" required>
                            </div>
                            <div id="passwordStrength" class="password-strength"></div>
                            <small class="text-muted">Use at least 6 characters</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Re-enter password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3" id="registerBtn">
                            <i class="bi bi-check-circle"></i> Create Account
                        </button>
                    </form>
                    
                    <div class="divider">
                        <span>Already have an account?</span>
                    </div>
                    
                    <a href="login" class="btn btn-outline-success w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            
            if (password.length < 6) {
                strengthBar.className = 'password-strength strength-weak';
            } else if (password.length < 10) {
                strengthBar.className = 'password-strength strength-medium';
            } else {
                strengthBar.className = 'password-strength strength-strong';
            }
        });
        
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('registerBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('register-process', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Account created successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = 'assessment';
                    }, 1500);
                } else {
                    showAlert(result.error, 'danger');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                showAlert('Connection error: ' + error.message, 'danger');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
        
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
