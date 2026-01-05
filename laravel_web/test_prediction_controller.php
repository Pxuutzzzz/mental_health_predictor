<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing PredictionController load...\n\n";

try {
    session_start();
    $_SESSION['user_id'] = 1;
    
    require_once __DIR__ . '/app/Database.php';
    echo "Database loaded\n";
    
    require_once __DIR__ . '/app/Controllers/PredictionController.php';
    echo "PredictionController loaded\n";
    
    $controller = new \Controllers\PredictionController();
    echo "SUCCESS: PredictionController instantiated\n";
    
} catch (ParseError $e) {
    echo "PARSE ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Exception $e) {
    echo "RUNTIME ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
