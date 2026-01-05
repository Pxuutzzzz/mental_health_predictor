<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing HospitalIntegrationService load...\n\n";

try {
    require_once __DIR__ . '/app/Services/HospitalIntegrationService.php';
    echo "SUCCESS: HospitalIntegrationService.php loaded without syntax errors\n";
    
    // Try to instantiate
    $mockDb = new stdClass();
    $mockLogger = new stdClass();
    
    $service = new HospitalIntegrationService($mockDb, $mockLogger);
    echo "SUCCESS: HospitalIntegrationService instantiated\n";
    
} catch (ParseError $e) {
    echo "PARSE ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Exception $e) {
    echo "RUNTIME ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
