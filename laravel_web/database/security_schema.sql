-- Database schema for security and compliance features
-- Add these tables to your existing database

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
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
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
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
    INDEX idx_data_type (data_type),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (accessed_user_id) REFERENCES users(id) ON DELETE CASCADE
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
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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

-- Add encrypted columns to existing users table
-- Execute these ALTER statements only if columns don't exist
ALTER TABLE users 
ADD COLUMN encrypted_phone TEXT NULL COMMENT 'Encrypted phone number' AFTER phone,
ADD COLUMN encrypted_address TEXT NULL COMMENT 'Encrypted address' AFTER encrypted_phone,
ADD COLUMN last_password_change DATETIME NULL COMMENT 'Last time password was changed',
ADD COLUMN failed_login_attempts INT DEFAULT 0 COMMENT 'Failed login counter',
ADD COLUMN account_locked_until DATETIME NULL COMMENT 'Account lock expiration',
ADD COLUMN data_retention_date DATE NULL COMMENT 'Scheduled data deletion date',
ADD COLUMN consent_given TINYINT(1) DEFAULT 0 COMMENT 'Has user given consent';

-- Add indexes for performance
ALTER TABLE users
ADD INDEX idx_email (email),
ADD INDEX idx_created_at (created_at),
ADD INDEX idx_consent_given (consent_given);

-- Add encrypted fields to predictions table
ALTER TABLE predictions
ADD COLUMN encrypted_features TEXT NULL COMMENT 'Encrypted sensitive features' AFTER features,
ADD COLUMN anonymized_user_hash VARCHAR(64) NULL COMMENT 'Anonymized user identifier for research';

-- Create view for anonymized data (for research/analytics)
CREATE OR REPLACE VIEW anonymized_predictions AS
SELECT 
    SHA2(CONCAT(p.user_id, 'salt_string'), 256) as user_hash,
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 18 THEN 'Under 18'
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 25 THEN '18-24'
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 35 THEN '25-34'
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 45 THEN '35-44'
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 55 THEN '45-54'
        WHEN TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) < 65 THEN '55-64'
        ELSE '65+'
    END as age_range,
    u.gender,
    p.result,
    ROUND(p.confidence, 2) as confidence,
    DATE_FORMAT(p.created_at, '%Y-%m') as prediction_month,
    p.features as feature_summary
FROM predictions p
JOIN users u ON p.user_id = u.id
WHERE u.consent_given = 1;

-- Stored procedure for secure data deletion (DoD 5220.22-M standard)
DELIMITER //

CREATE PROCEDURE secure_delete_user(IN target_user_id INT)
BEGIN
    DECLARE v_random VARCHAR(255);
    DECLARE v_pass INT DEFAULT 0;
    
    -- Start transaction
    START TRANSACTION;
    
    -- 3-pass overwrite
    WHILE v_pass < 3 DO
        SET v_random = MD5(RAND());
        
        -- Overwrite user data
        UPDATE users 
        SET 
            name = v_random,
            email = CONCAT(v_random, '@deleted.com'),
            password = v_random,
            phone = v_random,
            encrypted_phone = NULL,
            encrypted_address = NULL
        WHERE id = target_user_id;
        
        -- Overwrite prediction data
        UPDATE predictions
        SET 
            result = v_random,
            confidence = RAND(),
            features = '{"deleted": true}',
            encrypted_features = NULL
        WHERE user_id = target_user_id;
        
        SET v_pass = v_pass + 1;
    END WHILE;
    
    -- Final deletion
    DELETE FROM predictions WHERE user_id = target_user_id;
    DELETE FROM user_consents WHERE user_id = target_user_id;
    DELETE FROM encrypted_data WHERE user_id = target_user_id;
    DELETE FROM data_access_log WHERE user_id = target_user_id OR accessed_user_id = target_user_id;
    DELETE FROM users WHERE id = target_user_id;
    
    -- Log the deletion in audit_logs (manual insert before user is deleted)
    INSERT INTO audit_logs (event_type, user_id, action, status, ip_address, created_at)
    VALUES ('ACCOUNT_DELETE', target_user_id, 'Secure user deletion completed', 'SUCCESS', 'SYSTEM', NOW());
    
    COMMIT;
END //

DELIMITER ;

-- Stored procedure for cleanup old audit logs (retention: 7 years)
DELIMITER //

CREATE PROCEDURE cleanup_old_audit_logs()
BEGIN
    DELETE FROM audit_logs 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 YEAR);
    
    SELECT ROW_COUNT() as deleted_records;
END //

DELIMITER ;

-- Stored procedure for cleanup inactive user data (retention: 2 years)
DELIMITER //

CREATE PROCEDURE cleanup_inactive_users()
BEGIN
    -- Mark users for deletion if inactive for 2 years
    INSERT INTO data_retention (user_id, scheduled_deletion_date, reason, created_at)
    SELECT 
        id,
        CURDATE(),
        'INACTIVE_2_YEARS',
        NOW()
    FROM users
    WHERE last_login < DATE_SUB(NOW(), INTERVAL 2 YEAR)
    AND id NOT IN (SELECT user_id FROM data_retention WHERE status = 'PENDING');
    
    SELECT ROW_COUNT() as users_marked_for_deletion;
END //

DELIMITER ;

-- Create event scheduler for automatic cleanup (if not exists)
SET GLOBAL event_scheduler = ON;

-- Daily cleanup of old audit logs
CREATE EVENT IF NOT EXISTS daily_audit_cleanup
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO CALL cleanup_old_audit_logs();

-- Monthly check for inactive users
CREATE EVENT IF NOT EXISTS monthly_inactive_user_check
ON SCHEDULE EVERY 1 MONTH
STARTS CURRENT_TIMESTAMP
DO CALL cleanup_inactive_users();

-- Insert initial security configuration
INSERT INTO audit_logs (event_type, user_id, action, status, ip_address, created_at)
VALUES ('SYSTEM_INIT', NULL, 'Security tables initialized', 'SUCCESS', 'SYSTEM', NOW());
