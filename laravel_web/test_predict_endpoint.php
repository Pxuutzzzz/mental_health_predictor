<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing /predict endpoint simulation...\n\n";

// Simulate POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'age' => 25,
    'stress' => 7,
    'anxiety' => 6,
    'depression' => 5,
    'mental_history' => 'No',
    'sleep' => 6,
    'exercise' => 'Medium',
    'support' => 'Good',
    'hospital_integration' => 'false'
];

session_start();
$_SESSION['user_id'] = 1;

try {
    require_once __DIR__ . '/app/Database.php';
    require_once __DIR__ . '/app/Controllers/PredictionController.php';
    
    $controller = new \Controllers\PredictionController();
    
    // Capture output
    ob_start();
    $controller->predict();
    $output = ob_get_clean();
    
    echo "Response:\n";
    echo $output . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
