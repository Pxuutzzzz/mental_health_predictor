<?php
/**
 * Database Update Script - Add missing columns to assessments table
 * Run this file once to update database schema
 */

require_once __DIR__ . '/app/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "<h2>Database Update for Assessments Table</h2>";
    echo "<pre>";

    // 1. Add gender
    echo "1. Adding column gender...\n";
    try {
        $conn->exec("ALTER TABLE assessments ADD COLUMN gender VARCHAR(20) NOT NULL DEFAULT 'Female' AFTER age");
        echo "   ✓ Column gender added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Column gender already exists\n";
        } else {
            echo "   ! Error: " . $e->getMessage() . "\n";
        }
    }

    // 2. Add employment_status
    echo "\n2. Adding column employment_status...\n";
    try {
        $conn->exec("ALTER TABLE assessments ADD COLUMN employment_status VARCHAR(50) NOT NULL DEFAULT 'Employed' AFTER gender");
        echo "   ✓ Column employment_status added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Column employment_status already exists\n";
        } else {
            echo "   ! Error: " . $e->getMessage() . "\n";
        }
    }

    // 3. Add work_environment
    echo "\n3. Adding column work_environment...\n";
    try {
        $conn->exec("ALTER TABLE assessments ADD COLUMN work_environment VARCHAR(50) NOT NULL DEFAULT 'Office' AFTER employment_status");
        echo "   ✓ Column work_environment added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Column work_environment already exists\n";
        } else {
            echo "   ! Error: " . $e->getMessage() . "\n";
        }
    }

    // 4. Add seeks_treatment
    echo "\n4. Adding column seeks_treatment...\n";
    try {
        $conn->exec("ALTER TABLE assessments ADD COLUMN seeks_treatment VARCHAR(10) NOT NULL DEFAULT 'No' AFTER mental_history");
        echo "   ✓ Column seeks_treatment added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Column seeks_treatment already exists\n";
        } else {
            echo "   ! Error: " . $e->getMessage() . "\n";
        }
    }

    // 5. Add productivity
    echo "\n5. Adding column productivity...\n";
    try {
        $conn->exec("ALTER TABLE assessments ADD COLUMN productivity INT NOT NULL DEFAULT 70 AFTER social_support");
        echo "   ✓ Column productivity added\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Column productivity already exists\n";
        } else {
            echo "   ! Error: " . $e->getMessage() . "\n";
        }
    }

    // 6. Modify social_support from ENUM to INT (score)
    // Note: older values (Yes/No) will likely become 0.
    echo "\n6. Modifying social_support to INT...\n";
    try {
        // First change to varchar to avoid data loss if possible, then to int, or just force it.
        // Since original was ENUM('Yes','No'), casting to INT might produce 0. 
        // Let's set default 50.
        $conn->exec("ALTER TABLE assessments MODIFY social_support INT NOT NULL DEFAULT 50");
        echo "   ✓ Column social_support modified to INT\n";
    } catch (PDOException $e) {
        echo "   ! Error: " . $e->getMessage() . "\n";
    }

    // 7. Show current structure
    echo "\n7. Current assessments table structure:\n";
    echo "   ========================================\n";
    $stmt = $conn->query("SHOW COLUMNS FROM assessments");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }

    echo "\n========================================\n";
    echo "✓ DATABASE UPDATE COMPLETED!\n";
    echo "========================================\n";

    echo "</pre>";

} catch (Exception $e) {
    echo "<pre>";
    echo "❌ FATAL ERROR:\n";
    echo $e->getMessage();
    echo "\n\nStack trace:\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
