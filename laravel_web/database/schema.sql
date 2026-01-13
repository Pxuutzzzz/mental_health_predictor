-- Mental Health Predictor Database Schema
-- Created: 2025-12-04

CREATE DATABASE IF NOT EXISTS mental_health_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE mental_health_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assessments Table
CREATE TABLE IF NOT EXISTS assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    age INT NOT NULL,
    stress_level INT NOT NULL,
    anxiety_level INT NOT NULL,
    depression_level INT NOT NULL,
    mental_history ENUM('Yes', 'No') DEFAULT 'No',
    sleep_hours DECIMAL(3,1) NOT NULL,
    exercise_level ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    social_support ENUM('Yes', 'No') DEFAULT 'Yes',
    prediction VARCHAR(50) NOT NULL,
    confidence DECIMAL(5,4) NOT NULL,
    probabilities JSON,
    recommendations JSON,
    clinical_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hospital Integrations Table
CREATE TABLE IF NOT EXISTS hospital_integrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    user_id INT NULL,
    hospital_id VARCHAR(50) NOT NULL,
    hospital_name VARCHAR(150) NOT NULL,
    patient_reference VARCHAR(100) NULL,
    status ENUM('PENDING','SUCCESS','FAILED','QUEUED') DEFAULT 'PENDING',
    request_payload JSON,
    response_payload JSON,
    response_code VARCHAR(20) NULL,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_hospital_id (hospital_id),
    INDEX idx_assessment (assessment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert demo user (password: demo123)
INSERT INTO users (name, email, password) VALUES 
('Demo User', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
