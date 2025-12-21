<?php

/**
 * Audit Logger Class
 * Logs all security-relevant events for HIPAA/GDPR compliance
 */
class AuditLogger
{
    private $db;
    private $logFile;
    
    // Event types
    const EVENT_LOGIN = 'LOGIN';
    const EVENT_LOGOUT = 'LOGOUT';
    const EVENT_LOGIN_FAILED = 'LOGIN_FAILED';
    const EVENT_REGISTER = 'REGISTER';
    const EVENT_DATA_ACCESS = 'DATA_ACCESS';
    const EVENT_DATA_CREATE = 'DATA_CREATE';
    const EVENT_DATA_UPDATE = 'DATA_UPDATE';
    const EVENT_DATA_DELETE = 'DATA_DELETE';
    const EVENT_DATA_EXPORT = 'DATA_EXPORT';
    const EVENT_CONSENT_GIVEN = 'CONSENT_GIVEN';
    const EVENT_CONSENT_WITHDRAWN = 'CONSENT_WITHDRAWN';
    const EVENT_PREDICTION = 'PREDICTION';
    const EVENT_PERMISSION_CHANGE = 'PERMISSION_CHANGE';
    const EVENT_ACCOUNT_DELETE = 'ACCOUNT_DELETE';
    
    public function __construct($db = null)
    {
        $this->db = $db ?: Database::getInstance()->getConnection();
        $this->logFile = __DIR__ . '/../../logs/audit.log';
        
        // Create logs directory if not exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0750, true);
        }
        
        // Initialize database table if not exists
        $this->initializeTable();
    }
    
    /**
     * Log an event
     * @param string $eventType Type of event (use class constants)
     * @param int $userId User ID (null for anonymous)
     * @param string $action Description of action
     * @param array $details Additional details (will be JSON encoded)
     * @param string $status 'SUCCESS' or 'FAILURE'
     * @return bool Success status
     */
    public function log($eventType, $userId, $action, $details = [], $status = 'SUCCESS')
    {
        try {
            $timestamp = date('Y-m-d H:i:s');
            $ipAddress = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $sessionId = session_id() ?? 'No Session';
            
            // Log to database
            $stmt = $this->db->prepare("
                INSERT INTO audit_logs 
                (event_type, user_id, action, details, status, ip_address, user_agent, session_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $detailsJson = json_encode($details);
            
            $stmt->execute([
                $eventType,
                $userId,
                $action,
                $detailsJson,
                $status,
                $ipAddress,
                $userAgent,
                $sessionId,
                $timestamp
            ]);
            
            // Also log to file for redundancy
            $logEntry = sprintf(
                "[%s] %s | User: %s | IP: %s | Action: %s | Status: %s | Details: %s\n",
                $timestamp,
                $eventType,
                $userId ?? 'Anonymous',
                $ipAddress,
                $action,
                $status,
                $detailsJson
            );
            
            file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
            
            return true;
        } catch (Exception $e) {
            // If logging fails, at least write to error log
            error_log("Audit logging failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get audit logs with filters
     * @param array $filters Filters (user_id, event_type, date_from, date_to)
     * @param int $limit Number of records to return
     * @param int $offset Pagination offset
     * @return array Audit log records
     */
    public function getLogs($filters = [], $limit = 100, $offset = 0)
    {
        $query = "SELECT * FROM audit_logs WHERE 1=1";
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $query .= " AND user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['event_type'])) {
            $query .= " AND event_type = ?";
            $params[] = $filters['event_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $query .= " AND created_at >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $query .= " AND created_at <= ?";
            $params[] = $filters['date_to'];
        }
        
        $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user's own audit trail
     * @param int $userId User ID
     * @param int $limit Number of records
     * @return array Audit records
     */
    public function getUserAuditTrail($userId, $limit = 50)
    {
        return $this->getLogs(['user_id' => $userId], $limit);
    }
    
    /**
     * Export audit logs (for compliance reporting)
     * @param array $filters Filters
     * @param string $format 'csv' or 'json'
     * @return string File path of exported logs
     */
    public function exportLogs($filters = [], $format = 'csv')
    {
        $logs = $this->getLogs($filters, 10000, 0); // Max 10k records per export
        
        $filename = 'audit_export_' . date('Y-m-d_His') . '.' . $format;
        $filepath = __DIR__ . '/../../logs/exports/' . $filename;
        
        // Create exports directory if not exists
        $exportDir = dirname($filepath);
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0750, true);
        }
        
        if ($format === 'csv') {
            $fp = fopen($filepath, 'w');
            
            // Header
            fputcsv($fp, ['ID', 'Event Type', 'User ID', 'Action', 'Status', 'IP Address', 'Date/Time', 'Details']);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($fp, [
                    $log['id'],
                    $log['event_type'],
                    $log['user_id'],
                    $log['action'],
                    $log['status'],
                    $log['ip_address'],
                    $log['created_at'],
                    $log['details']
                ]);
            }
            
            fclose($fp);
        } else {
            file_put_contents($filepath, json_encode($logs, JSON_PRETTY_PRINT));
        }
        
        // Log the export action
        $this->log(
            self::EVENT_DATA_EXPORT,
            $_SESSION['user_id'] ?? null,
            'Exported audit logs',
            ['filename' => $filename, 'record_count' => count($logs)],
            'SUCCESS'
        );
        
        return $filepath;
    }
    
    /**
     * Get client IP address (handles proxies)
     * @return string IP address
     */
    private function getClientIP()
    {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
    
    /**
     * Initialize audit logs table
     */
    private function initializeTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_type VARCHAR(50) NOT NULL,
            user_id INT NULL,
            action VARCHAR(255) NOT NULL,
            details TEXT NULL,
            status VARCHAR(20) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT NULL,
            session_id VARCHAR(255) NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_event_type (event_type),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
            error_log("Audit table initialization: " . $e->getMessage());
        }
    }
    
    /**
     * Clean up old audit logs (retention policy: 7 years)
     * Should be run as a scheduled task
     */
    public function cleanupOldLogs()
    {
        $retentionDate = date('Y-m-d', strtotime('-7 years'));
        
        $stmt = $this->db->prepare("DELETE FROM audit_logs WHERE created_at < ?");
        $stmt->execute([$retentionDate]);
        
        $deletedCount = $stmt->rowCount();
        
        error_log("Audit log cleanup: Deleted {$deletedCount} records older than {$retentionDate}");
        
        return $deletedCount;
    }
}
