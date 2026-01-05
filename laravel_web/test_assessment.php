<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing assessment.php load...\n";

session_start();
$_SESSION['user_id'] = 1;

try {
    ob_start();
    include __DIR__ . '/views/assessment.php';
    $output = ob_get_clean();
    echo "SUCCESS: assessment.php loaded without errors\n";
    echo "Output length: " . strlen($output) . " bytes\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
