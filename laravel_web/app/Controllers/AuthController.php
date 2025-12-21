<?php
namespace Controllers;

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Security/AuditLogger.php';

/**
 * Authentication Controller
 * Handles user registration and login with MySQL Database
 */
class AuthController
{
    private $db;
    private $logger;
    
    public function __construct()
    {
        $this->db = \Database::getInstance();
        $this->logger = new \AuditLogger($this->db->getConnection());
    }
    
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: assessment');
            exit;
        }
        require __DIR__ . '/../../views/login.php';
    }
    
    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: assessment');
            exit;
        }
        require __DIR__ . '/../../views/register.php';
    }
    
    public function login()
    {
        header('Content-Type: application/json');
        
        try {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'error' => 'Email and password are required']);
                return;
            }
            
            $user = $this->db->fetchOne(
                "SELECT * FROM users WHERE email = ? LIMIT 1",
                [$email]
            );
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Log successful login
                $this->logger->log(
                    \AuditLogger::EVENT_LOGIN,
                    $user['id'],
                    'User logged in successfully',
                    ['email' => $email],
                    'SUCCESS'
                );
                
                // Load user's predictions from database
                $this->loadUserPredictions($user['id']);
                
                echo json_encode(['success' => true]);
            } else {
                // Log failed login attempt
                $this->logger->log(
                    \AuditLogger::EVENT_LOGIN_FAILED,
                    null,
                    'Failed login attempt',
                    ['email' => $email, 'reason' => 'Invalid credentials'],
                    'FAILURE'
                );
                
                echo json_encode(['success' => false, 'error' => 'Invalid email or password']);
            }
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function register()
    {
        header('Content-Type: application/json');
        
        try {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'error' => 'All fields are required']);
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'error' => 'Invalid email format']);
                return;
            }
            
            if (strlen($password) < 6) {
                echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters']);
                return;
            }
            
            if ($password !== $confirmPassword) {
                echo json_encode(['success' => false, 'error' => 'Passwords do not match']);
                return;
            }
            
            // Check if email already exists
            $existingUser = $this->db->fetchOne(
                "SELECT id FROM users WHERE email = ? LIMIT 1",
                [$email]
            );
            
            if ($existingUser) {
                echo json_encode(['success' => false, 'error' => 'Email already registered']);
                return;
            }
            
            // Insert new user
            $userId = $this->db->insert(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)",
                [$name, $email, password_hash($password, PASSWORD_DEFAULT)]
            );
            
            // Log registration
            $this->logger->log(
                \AuditLogger::EVENT_REGISTER,
                $userId,
                'New user registered',
                ['name' => $name, 'email' => $email],
                'SUCCESS'
            );
            
            // Auto login after registration
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['predictions'] = [];
            
            echo json_encode(['success' => true]);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function logout()
    {
        $userId = $_SESSION['user_id'] ?? null;
        
        if ($userId) {
            // Log logout
            $this->logger->log(
                \AuditLogger::EVENT_LOGOUT,
                $userId,
                'User logged out',
                [],
                'SUCCESS'
            );
        }
        
        session_destroy();
        header('Location: login');
        exit;
    }
    
    public function showForgotPassword()
    {
        require __DIR__ . '/../../views/forgot_password.php';
    }
    
    public function forgotPassword()
    {
        try {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                header('Location: forgot-password?error=email_required');
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: forgot-password?error=invalid_email');
                exit;
            }
            
            // Check if user exists
            $user = $this->db->fetchOne(
                "SELECT id, name FROM users WHERE email = ? LIMIT 1",
                [$email]
            );
            
            if (!$user) {
                header('Location: forgot-password?error=email_not_found');
                exit;
            }
            
            // Check for recent reset requests (prevent abuse)
            $stmt = $this->db->getConnection()->prepare(
                "SELECT COUNT(*) as count FROM password_reset_tokens 
                WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)"
            );
            $stmt->execute([$email]);
            $recentRequests = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($recentRequests['count'] > 0) {
                header('Location: forgot-password?error=too_many_requests');
                exit;
            }
            
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
            
            // Save token to database
            $this->db->insert(
                "INSERT INTO password_reset_tokens (email, token, expires_at, ip_address) VALUES (?, ?, ?, ?)",
                [$email, $token, $expiresAt, $ipAddress]
            );
            
            // Log the request
            $this->logger->log(
                \AuditLogger::EVENT_DATA_ACCESS,
                $user['id'],
                'Password reset requested',
                ['email' => $email],
                'SUCCESS'
            );
            
            // In production, send email with reset link
            // For development, we'll show the link in the response
            // TODO: Implement email sending
            
            // For now, save token to session for demo purposes
            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_email'] = $email;
            
            // Don't redirect here, let index.php handle it
            return;
            
        } catch (\Exception $e) {
            error_log("Forgot password error: " . $e->getMessage());
            header('Location: forgot-password?error=server_error');
            exit;
        }
    }
    
    public function showResetPassword()
    {
        require __DIR__ . '/../../views/reset_password.php';
    }
    
    public function resetPassword()
    {
        try {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($password)) {
                header('Location: reset-password?token=' . urlencode($token) . '&error=password_required');
                exit;
            }
            
            if (strlen($password) < 6) {
                header('Location: reset-password?token=' . urlencode($token) . '&error=password_too_short');
                exit;
            }
            
            if ($password !== $confirmPassword) {
                header('Location: reset-password?token=' . urlencode($token) . '&error=password_mismatch');
                exit;
            }
            
            // Verify token
            $stmt = $this->db->getConnection()->prepare(
                "SELECT * FROM password_reset_tokens 
                WHERE token = ? AND used_at IS NULL AND expires_at > NOW()"
            );
            $stmt->execute([$token]);
            $tokenData = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$tokenData) {
                header('Location: login?error=invalid_or_expired_token');
                exit;
            }
            
            // Update user password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            
            $this->db->getConnection()->prepare(
                "UPDATE users SET password = ?, last_password_change = NOW() WHERE email = ?"
            )->execute([$hashedPassword, $tokenData['email']]);
            
            // Mark token as used
            $this->db->getConnection()->prepare(
                "UPDATE password_reset_tokens SET used_at = NOW() WHERE id = ?"
            )->execute([$tokenData['id']]);
            
            // Get user for logging
            $user = $this->db->fetchOne(
                "SELECT id FROM users WHERE email = ?",
                [$tokenData['email']]
            );
            
            // Log the password reset
            $this->logger->log(
                \AuditLogger::EVENT_DATA_UPDATE,
                $user['id'],
                'Password reset completed',
                ['email' => $tokenData['email']],
                'SUCCESS'
            );
            
            // Clear session reset data
            unset($_SESSION['reset_token']);
            unset($_SESSION['reset_email']);
            
            header('Location: login?success=password_reset');
            exit;
            
        } catch (\Exception $e) {
            error_log("Reset password error: " . $e->getMessage());
            header('Location: login?error=server_error');
            exit;
        }
    }
    
    private function loadUserPredictions($userId)
    {
        $assessments = $this->db->fetchAll(
            "SELECT * FROM assessments WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
        
        $_SESSION['predictions'] = [];
        
        foreach ($assessments as $assessment) {
            $_SESSION['predictions'][] = [
                'id' => $assessment['id'],
                'timestamp' => $assessment['created_at'],
                'input' => [
                    'age' => $assessment['age'],
                    'stress' => $assessment['stress_level'],
                    'anxiety' => $assessment['anxiety_level'],
                    'depression' => $assessment['depression_level'],
                    'mental_history' => $assessment['mental_history'],
                    'sleep' => $assessment['sleep_hours'],
                    'exercise' => $assessment['exercise_level'],
                    'social_support' => $assessment['social_support']
                ],
                'prediction' => $assessment['prediction'],
                'confidence' => (float)$assessment['confidence'],
                'probabilities' => json_decode($assessment['probabilities'], true) ?: [],
                'recommendations' => json_decode($assessment['recommendations'], true) ?: []
            ];
        }
    }
}
