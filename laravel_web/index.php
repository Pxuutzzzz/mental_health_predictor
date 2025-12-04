<?php
/**
 * Mental Health Predictor - Laravel Style Entry Point
 * Simple Laravel-style implementation with Bootstrap UI
 */

// Auto-load classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Start session
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
        header('Location: login');
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
    
    // Protected routes
    case 'assessment':
        requireAuth();
        require __DIR__ . '/views/assessment.php';
        break;
    
    case 'predict':
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/app/Controllers/PredictionController.php';
            $controller = new Controllers\PredictionController();
            $controller->predict();
        }
        break;
    
    case 'history':
        requireAuth();
        require __DIR__ . '/views/history.php';
        break;
    
    case 'professionals':
        requireAuth();
        require __DIR__ . '/views/professionals.php';
        break;
    
    case 'dashboard':
        requireAuth();
        require __DIR__ . '/views/dashboard.php';
        break;
    
    default:
        http_response_code(404);
        echo "404 - Page Not Found";
        break;
}
