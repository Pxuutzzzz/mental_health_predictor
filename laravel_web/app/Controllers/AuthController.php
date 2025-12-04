<?php
namespace Controllers;

require_once __DIR__ . '/../Database.php';

/**
 * Authentication Controller
 * Handles user registration and login with MySQL Database
 */
class AuthController
{
    private $db;
    
    public function __construct()
    {
        $this->db = \Database::getInstance();
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
                
                // Load user's predictions from database
                $this->loadUserPredictions($user['id']);
                
                echo json_encode(['success' => true]);
            } else {
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
