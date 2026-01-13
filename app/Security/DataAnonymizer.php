<?php

/**
 * Data Anonymization Class
 * Provides methods to anonymize sensitive data for GDPR compliance
 */
class DataAnonymizer
{
    /**
     * Anonymize user data for research/analytics
     * @param array $userData User data array
     * @return array Anonymized data
     */
    public static function anonymizeUser($userData)
    {
        return [
            'user_id' => self::hashId($userData['id']),
            'age_range' => self::generalizeAge($userData['birth_date'] ?? null),
            'gender' => $userData['gender'] ?? 'Undisclosed',
            'registration_month' => date('Y-m', strtotime($userData['created_at'] ?? 'now')),
            // Remove all identifiable information
            'name' => null,
            'email' => null,
            'phone' => null,
            'address' => null
        ];
    }
    
    /**
     * Anonymize prediction data
     * @param array $predictionData Prediction data array
     * @return array Anonymized prediction
     */
    public static function anonymizePrediction($predictionData)
    {
        return [
            'prediction_id' => self::hashId($predictionData['id']),
            'user_id_hash' => self::hashId($predictionData['user_id']),
            'result' => $predictionData['result'],
            'confidence' => round($predictionData['confidence'], 2),
            'prediction_month' => date('Y-m', strtotime($predictionData['created_at'])),
            // Keep aggregated features only
            'features_summary' => [
                'depression_level' => self::generalizeScore($predictionData['depression'] ?? 0),
                'anxiety_level' => self::generalizeScore($predictionData['anxiety'] ?? 0),
                'stress_level' => self::generalizeScore($predictionData['stress'] ?? 0),
                'social_support_level' => self::generalizeScore($predictionData['social_support'] ?? 0)
            ]
        ];
    }
    
    /**
     * Hash ID for pseudonymization
     * @param int $id Original ID
     * @return string Hashed ID
     */
    private static function hashId($id)
    {
        // Use HMAC for consistent pseudonymization
        $secret = getenv('ANONYMIZATION_SECRET') ?: 'default_secret_change_in_production';
        return hash_hmac('sha256', (string)$id, $secret);
    }
    
    /**
     * Generalize age to age ranges
     * @param string $birthDate Birth date (Y-m-d format)
     * @return string Age range
     */
    private static function generalizeAge($birthDate)
    {
        if (!$birthDate) {
            return 'Unknown';
        }
        
        $age = date_diff(date_create($birthDate), date_create('now'))->y;
        
        if ($age < 18) return 'Under 18';
        if ($age < 25) return '18-24';
        if ($age < 35) return '25-34';
        if ($age < 45) return '35-44';
        if ($age < 55) return '45-54';
        if ($age < 65) return '55-64';
        return '65+';
    }
    
    /**
     * Generalize score to levels
     * @param float $score Original score
     * @return string Level (Low/Medium/High)
     */
    private static function generalizeScore($score)
    {
        if ($score < 0.33) return 'Low';
        if ($score < 0.67) return 'Medium';
        return 'High';
    }
    
    /**
     * Apply k-anonymity to dataset
     * Ensures at least k individuals share same quasi-identifiers
     * @param array $dataset Array of records
     * @param int $k Minimum group size
     * @return array K-anonymized dataset
     */
    public static function applyKAnonymity($dataset, $k = 5)
    {
        $anonymized = [];
        
        // Group by quasi-identifiers (age_range, gender)
        $groups = [];
        foreach ($dataset as $record) {
            $key = $record['age_range'] . '_' . $record['gender'];
            if (!isset($groups[$key])) {
                $groups[$key] = [];
            }
            $groups[$key][] = $record;
        }
        
        // Only include groups with at least k members
        foreach ($groups as $group) {
            if (count($group) >= $k) {
                $anonymized = array_merge($anonymized, $group);
            }
        }
        
        return $anonymized;
    }
    
    /**
     * Add statistical noise to numeric data (differential privacy)
     * @param float $value Original value
     * @param float $epsilon Privacy budget (smaller = more privacy)
     * @return float Noisy value
     */
    public static function addLaplaceNoise($value, $epsilon = 0.1)
    {
        // Laplace mechanism for differential privacy
        $scale = 1 / $epsilon;
        $u = (mt_rand() / mt_getrandmax()) - 0.5;
        $noise = -$scale * (($u < 0) ? 1 : -1) * log(1 - 2 * abs($u));
        
        return $value + $noise;
    }
    
    /**
     * Mask email for display purposes
     * @param string $email Email address
     * @return string Masked email
     */
    public static function maskEmail($email)
    {
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid Email';
        }
        
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        // Show first and last character of name
        $nameLength = strlen($name);
        if ($nameLength <= 2) {
            $maskedName = str_repeat('*', $nameLength);
        } else {
            $maskedName = $name[0] . str_repeat('*', $nameLength - 2) . $name[$nameLength - 1];
        }
        
        return $maskedName . '@' . $domain;
    }
    
    /**
     * Mask phone number
     * @param string $phone Phone number
     * @return string Masked phone
     */
    public static function maskPhone($phone)
    {
        if (!$phone) {
            return 'No Phone';
        }
        
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $length = strlen($phone);
        
        if ($length < 4) {
            return str_repeat('*', $length);
        }
        
        // Show last 3 digits only
        return str_repeat('*', $length - 3) . substr($phone, -3);
    }
    
    /**
     * Export anonymized dataset for research
     * @param PDO $db Database connection
     * @param string $tableName Table to export
     * @param int $limit Maximum records
     * @return array Anonymized dataset
     */
    public static function exportAnonymizedDataset($db, $tableName = 'predictions', $limit = 1000)
    {
        $stmt = $db->prepare("
            SELECT p.*, u.birth_date, u.gender, u.created_at as user_created_at
            FROM {$tableName} p
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        
        $stmt->execute([$limit]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $anonymized = [];
        foreach ($data as $row) {
            $anonymized[] = self::anonymizePrediction($row);
        }
        
        // Apply k-anonymity
        $anonymized = self::applyKAnonymity($anonymized, 5);
        
        return $anonymized;
    }
    
    /**
     * Securely delete user data (DoD 5220.22-M standard)
     * @param PDO $db Database connection
     * @param int $userId User ID to delete
     * @return bool Success status
     */
    public static function secureDeleteUser($db, $userId)
    {
        try {
            $db->beginTransaction();
            
            // Get all user's data
            $stmt = $db->prepare("SELECT id FROM predictions WHERE user_id = ?");
            $stmt->execute([$userId]);
            $predictions = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Overwrite sensitive data multiple times before deletion (3-pass)
            for ($pass = 0; $pass < 3; $pass++) {
                $randomData = bin2hex(random_bytes(16));
                
                $stmt = $db->prepare("
                    UPDATE users 
                    SET name = ?, email = ?, password = ?, phone = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $randomData,
                    $randomData . '@deleted.com',
                    $randomData,
                    $randomData,
                    $userId
                ]);
                
                // Overwrite prediction data
                $stmt = $db->prepare("
                    UPDATE predictions
                    SET result = ?, confidence = ?, features = ?
                    WHERE user_id = ?
                ");
                $stmt->execute([
                    $randomData,
                    rand(0, 100) / 100,
                    json_encode(['deleted' => true]),
                    $userId
                ]);
            }
            
            // Final deletion
            $stmt = $db->prepare("DELETE FROM predictions WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            $stmt = $db->prepare("DELETE FROM user_consents WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            
            // Log the deletion
            require_once __DIR__ . '/AuditLogger.php';
            $logger = new AuditLogger($db);
            $logger->log(
                AuditLogger::EVENT_ACCOUNT_DELETE,
                $userId,
                'User account securely deleted',
                ['prediction_count' => count($predictions)],
                'SUCCESS'
            );
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Secure deletion failed: " . $e->getMessage());
            return false;
        }
    }
}
