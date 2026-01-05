<?php
/**
 * Mental Health Predictor - Laravel Style Entry Point
 * Simple Laravel-style implementation with Bootstrap UI
 */

// Enable output buffering for better performance
ob_start();

// Auto-load classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Handle static files for built-in PHP server
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

// Start session with optimized settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Set session start time if not exists
if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
}

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($script_name, '', $request_uri);
$path = trim(parse_url($path, PHP_URL_PATH), '/');

// Auth middleware helper
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login');
        exit;
    }
}

// Handle clear history before routing
if (isset($_GET['clear_history'])) {
    if (isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/app/Database.php';
        $db = \Database::getInstance();
        $db->delete("DELETE FROM assessments WHERE user_id = ?", [$_SESSION['user_id']]);
    }
    unset($_SESSION['predictions']);
    header('Location: history');
    exit;
}

// Handle delete single assessment
if (isset($_GET['delete_assessment']) && isset($_SESSION['user_id'])) {
    $assessmentId = (int)$_GET['delete_assessment'];
    require_once __DIR__ . '/app/Database.php';
    $db = \Database::getInstance();
    $db->delete("DELETE FROM assessments WHERE id = ? AND user_id = ?", [$assessmentId, $_SESSION['user_id']]);
    
    // Reload predictions
    require __DIR__ . '/app/Controllers/AuthController.php';
    $controller = new Controllers\AuthController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('loadUserPredictions');
    $method->setAccessible(true);
    $method->invoke($controller, $_SESSION['user_id']);
    
    header('Location: history');
    exit;
}

// Route handling
switch ($path) {
    // Public routes
    case '':
    case 'index.php':
        header('Location: home');
        break;
    
    case 'home':
        require __DIR__ . '/views/home.php';
        break;
    
    case 'login':
        require __DIR__ . '/app/Controllers/AuthController.php';
        $controller = new Controllers\AuthController();
        $controller->showLogin();
        break;
    
    case 'register':
        require __DIR__ . '/app/Controllers/AuthController.php';
        $controller = new Controllers\AuthController();
        $controller->showRegister();
        break;
    
    case 'login-process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/app/Controllers/AuthController.php';
            $controller = new Controllers\AuthController();
            $controller->login();
        }
        break;
    
    case 'register-process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/app/Controllers/AuthController.php';
            $controller = new Controllers\AuthController();
            $controller->register();
        }
        break;
    
    case 'logout':
        require __DIR__ . '/app/Controllers/AuthController.php';
        $controller = new Controllers\AuthController();
        $controller->logout();
        break;
    
    case 'change-password':
        require __DIR__ . '/app/Controllers/AuthController.php';
        $controller = new Controllers\AuthController();
        $controller->showChangePassword();
        break;
    
    case 'change-password-process':
        require __DIR__ . '/app/Controllers/AuthController.php';
        $controller = new Controllers\AuthController();
        $controller->changePassword();
        break;
    
    // Protected routes
    case 'assessment':
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = 'assessment';
            header('Location: login?error=login_required');
            exit;
        }
        require __DIR__ . '/views/assessment.php';
        break;
    
    case 'assessment-clinical':
    case 'clinical':
        // Clinical mode for hospital staff
        require __DIR__ . '/views/assessment_clinical.php';
        break;
    
    case 'predict':
        // Allow predict for both auth users and staff mode
        if (!isset($_SESSION['user_id']) && !isset($_POST['staff_mode'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Authentication required']);
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/app/Controllers/PredictionController.php';
            $controller = new Controllers\PredictionController();
            $controller->predict();
        }
        break;
    
    case 'history':
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = 'history';
            header('Location: login?error=login_required');
            exit;
        }
        requireAuth();
        require __DIR__ . '/views/history.php';
        break;
    
    case 'professionals':
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = 'professionals';
            header('Location: login?error=login_required');
            exit;
        }
        requireAuth();
        require __DIR__ . '/views/professionals.php';
        break;
    
    case 'about':
        requireAuth();
        require __DIR__ . '/views/about.php';
        break;
    
    case 'consent':
        requireAuth();
        require __DIR__ . '/views/consent.php';
        break;
    
    case 'audit-trail':
        requireAuth();
        require __DIR__ . '/views/audit_trail.php';
        break;
    
    case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = 'dashboard';
            header('Location: login?error=login_required');
            exit;
        }
        requireAuth();
        require __DIR__ . '/views/dashboard.php';
        break;
    
    // Screening Tools Routes
    case 'screening-phq9':
        require __DIR__ . '/views/screening_phq9.php';
        break;
    
    case 'screening-gad7':
        require __DIR__ . '/views/screening_gad7.php';
        break;
    
    case 'screening-tools':
        require __DIR__ . '/views/screening_dashboard.php';
        break;
    
    case 'save-screening':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/app/Controllers/ScreeningController.php';
            $controller = new \Controllers\ScreeningController();
            $controller->saveScreening();
        }
        break;
    
    case 'screening-history':
        require __DIR__ . '/app/Controllers/ScreeningController.php';
        $controller = new \Controllers\ScreeningController();
        $controller->getHistory();
        break;
    
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
