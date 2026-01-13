-- Simplified Security Schema (Essential Tables Only)
-- This creates only the required tables without modifying existing structure

-- Audit Logs Table (for HIPAA/GDPR compliance)
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL COMMENT 'Type of event (LOGIN, LOGOUT, DATA_ACCESS, etc.)',
    user_id INT NULL COMMENT 'User who performed the action',
    action VARCHAR(255) NOT NULL COMMENT 'Description of action',
    details TEXT NULL COMMENT 'Additional details (JSON format)',
    status VARCHAR(20) NOT NULL COMMENT 'SUCCESS or FAILURE',
    ip_address VARCHAR(45) NOT NULL COMMENT 'IP address of client',
    user_agent TEXT NULL COMMENT 'Browser/client user agent',
    session_id VARCHAR(255) NULL COMMENT 'Session identifier',
    created_at DATETIME NOT NULL COMMENT 'Timestamp of event',
    INDEX idx_user_id (user_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Consents Table (for GDPR consent management)
CREATE TABLE IF NOT EXISTS user_consents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'User who gave consent',
    consent_data TEXT NOT NULL COMMENT 'JSON of consent checkboxes',
    consent_version VARCHAR(10) NOT NULL COMMENT 'Version of consent form',
    ip_address VARCHAR(45) NOT NULL COMMENT 'IP when consent was given',
    user_agent TEXT NULL COMMENT 'Browser when consent was given',
    created_at DATETIME NOT NULL COMMENT 'When consent was given',
    withdrawn_at DATETIME NULL COMMENT 'When consent was withdrawn (if applicable)',
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Encrypted Data Table (for sensitive information)
CREATE TABLE IF NOT EXISTS encrypted_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Owner of the data',
    data_type VARCHAR(50) NOT NULL COMMENT 'Type of encrypted data',
    encrypted_value TEXT NOT NULL COMMENT 'AES-256 encrypted data',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_data_type (data_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Access Log (for tracking who accessed what data)
CREATE TABLE IF NOT EXISTS data_access_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'User who accessed the data',
    accessed_user_id INT NULL COMMENT 'User whose data was accessed',
    resource_type VARCHAR(50) NOT NULL COMMENT 'Type of resource (prediction, profile, etc.)',
    resource_id INT NULL COMMENT 'ID of accessed resource',
    access_type VARCHAR(20) NOT NULL COMMENT 'READ, UPDATE, DELETE, EXPORT',
    ip_address VARCHAR(45) NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_accessed_user_id (accessed_user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Retention Policy Table
CREATE TABLE IF NOT EXISTS data_retention (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'User whose data is scheduled for deletion',
    scheduled_deletion_date DATE NOT NULL COMMENT 'Date when data should be deleted',
    reason VARCHAR(100) NOT NULL COMMENT 'Reason for deletion (inactive, user_request, etc.)',
    status VARCHAR(20) NOT NULL DEFAULT 'PENDING' COMMENT 'PENDING, COMPLETED, CANCELLED',
    created_at DATETIME NOT NULL,
    completed_at DATETIME NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_scheduled_date (scheduled_deletion_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Incidents Table
CREATE TABLE IF NOT EXISTS security_incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_type VARCHAR(50) NOT NULL COMMENT 'Type of security incident',
    severity VARCHAR(20) NOT NULL COMMENT 'LOW, MEDIUM, HIGH, CRITICAL',
    description TEXT NOT NULL COMMENT 'Description of incident',
    affected_users TEXT NULL COMMENT 'JSON array of affected user IDs',
    status VARCHAR(20) NOT NULL DEFAULT 'OPEN' COMMENT 'OPEN, INVESTIGATING, RESOLVED, CLOSED',
    detected_at DATETIME NOT NULL COMMENT 'When incident was detected',
    resolved_at DATETIME NULL COMMENT 'When incident was resolved',
    resolution_notes TEXT NULL COMMENT 'How incident was resolved',
    reported_by INT NULL COMMENT 'User or admin who reported',
    INDEX idx_detected_at (detected_at),
    INDEX idx_severity (severity),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial security configuration
INSERT IGNORE INTO audit_logs (event_type, user_id, action, status, ip_address, created_at)
VALUES ('SYSTEM_INIT', NULL, 'Security tables initialized', 'SUCCESS', 'SYSTEM', NOW());

SELECT 'Security tables created successfully!' as message;
