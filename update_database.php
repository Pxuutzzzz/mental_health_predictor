<?php
/**
 * Database Update Script - Add Google OAuth columns
 * Jalankan file ini sekali untuk update database
 */

require_once __DIR__ . '/app/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<h2>Database Update untuk Google OAuth</h2>";
    echo "<pre>";
    
    // 1. Add google_id column
    echo "1. Menambahkan kolom google_id...\n";
    try {
        $conn->exec("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER email");
        echo "   ✓ Kolom google_id berhasil ditambahkan\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Kolom google_id sudah ada\n";
        } else {
            throw $e;
        }
    }
    
    // 2. Add google_picture column
    echo "\n2. Menambahkan kolom google_picture...\n";
    try {
        $conn->exec("ALTER TABLE users ADD COLUMN google_picture VARCHAR(500) NULL AFTER google_id");
        echo "   ✓ Kolom google_picture berhasil ditambahkan\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   - Kolom google_picture sudah ada\n";
        } else {
            throw $e;
        }
    }
    
    // 3. Add index
    echo "\n3. Menambahkan index untuk google_id...\n";
    try {
        $conn->exec("CREATE INDEX idx_google_id ON users(google_id)");
        echo "   ✓ Index berhasil ditambahkan\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "   - Index sudah ada\n";
        } else {
            throw $e;
        }
    }
    
    // 4. Modify password to allow NULL
    echo "\n4. Mengubah kolom password menjadi nullable...\n";
    $conn->exec("ALTER TABLE users MODIFY password VARCHAR(255) NULL");
    echo "   ✓ Kolom password berhasil diubah\n";
    
    // 5. Show current structure
    echo "\n5. Struktur tabel users saat ini:\n";
    echo "   ========================================\n";
    $stmt = $conn->query("SHOW COLUMNS FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']}\n";
    }
    
    echo "\n========================================\n";
    echo "✓ DATABASE UPDATE SELESAI!\n";
    echo "========================================\n";
    echo "\nSekarang Anda bisa:\n";
    echo "1. Hapus file ini (update_database.php)\n";
    echo "2. Kembali ke halaman login: <a href='login'>http://localhost:9000/login</a>\n";
    echo "3. Coba login dengan Google\n";
    
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<pre>";
    echo "❌ ERROR:\n";
    echo $e->getMessage();
    echo "\n\nStack trace:\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
