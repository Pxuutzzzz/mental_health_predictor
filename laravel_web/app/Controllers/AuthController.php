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
                
                // Check for redirect after login
                $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard';
                unset($_SESSION['redirect_after_login']);
                
                echo json_encode(['success' => true, 'redirect' => $redirect]);
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
    
    public function googleLogin()
    {
        // Clear output buffer and set JSON header
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        
        try {
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            $credential = $input['credential'] ?? '';
            
            if (empty($credential)) {
                echo json_encode(['success' => false, 'error' => 'No credential provided']);
                return;
            }
            
            // Decode JWT token from Google
            $parts = explode('.', $credential);
            if (count($parts) !== 3) {
                echo json_encode(['success' => false, 'error' => 'Invalid credential format']);
                return;
            }
            
            // Decode the payload (second part of JWT)
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            
            if (!$payload) {
                echo json_encode(['success' => false, 'error' => 'Invalid token payload']);
                return;
            }
            
            // Extract user information
            $email = $payload['email'] ?? '';
            $name = $payload['name'] ?? '';
            $googleId = $payload['sub'] ?? '';
            $picture = $payload['picture'] ?? '';
            
            if (empty($email) || empty($googleId)) {
                echo json_encode(['success' => false, 'error' => 'Missing required user information']);
                return;
            }
            
            // Check if user exists
            $user = $this->db->fetchOne(
                "SELECT * FROM users WHERE email = ? OR google_id = ? LIMIT 1",
                [$email, $googleId]
            );
            
            if ($user) {
                // Update Google ID if not set
                if (empty($user['google_id'])) {
                    $this->db->update(
                        "UPDATE users SET google_id = ?, google_picture = ? WHERE id = ?",
                        [$googleId, $picture, $user['id']]
                    );
                }
                
                $userId = $user['id'];
                $userName = $user['name'];
            } else {
                // Create new user
                $userId = $this->db->insert(
                    "INSERT INTO users (name, email, google_id, google_picture, password) VALUES (?, ?, ?, ?, ?)",
                    [$name, $email, $googleId, $picture, password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)]
                );
                
                $userName = $name;
                
                // Log registration
                $this->logger->log(
                    \AuditLogger::EVENT_REGISTER,
                    $userId,
                    'New user registered via Google',
                    ['name' => $name, 'email' => $email, 'google_id' => $googleId],
                    'SUCCESS'
                );
            }
            
            // Set session
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_email'] = $email;
            $_SESSION['google_picture'] = $picture;
            
            // Log successful login
            $this->logger->log(
                \AuditLogger::EVENT_LOGIN,
                $userId,
                'User logged in via Google',
                ['email' => $email],
                'SUCCESS'
            );
            
            // Load user's predictions
            $this->loadUserPredictions($userId);
            
            // Check for redirect
            $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard';
            unset($_SESSION['redirect_after_login']);
            
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
