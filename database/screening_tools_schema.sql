-- Mental Health Screening Tools Schema
-- PHQ-9 (Depression) & GAD-7 (Anxiety) Screening

USE mental_health_db;

-- Screening Tools Results Table
CREATE TABLE IF NOT EXISTS screening_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    patient_name VARCHAR(100) NULL,
    patient_mrn VARCHAR(50) NULL,
    screening_type ENUM('PHQ9', 'GAD7', 'COMBINED') NOT NULL,
    
    -- PHQ-9 Questions (each 0-3 score)
    phq9_q1 TINYINT NULL COMMENT 'Little interest or pleasure',
    phq9_q2 TINYINT NULL COMMENT 'Feeling down, depressed',
    phq9_q3 TINYINT NULL COMMENT 'Sleep problems',
    phq9_q4 TINYINT NULL COMMENT 'Feeling tired',
    phq9_q5 TINYINT NULL COMMENT 'Poor appetite',
    phq9_q6 TINYINT NULL COMMENT 'Feeling bad about yourself',
    phq9_q7 TINYINT NULL COMMENT 'Trouble concentrating',
    phq9_q8 TINYINT NULL COMMENT 'Moving or speaking slowly/fidgety',
    phq9_q9 TINYINT NULL COMMENT 'Thoughts of self-harm',
    phq9_total_score TINYINT NULL,
    phq9_severity ENUM('Minimal', 'Mild', 'Moderate', 'Moderately Severe', 'Severe') NULL,
    
    -- GAD-7 Questions (each 0-3 score)
    gad7_q1 TINYINT NULL COMMENT 'Feeling nervous, anxious',
    gad7_q2 TINYINT NULL COMMENT 'Not able to stop worrying',
    gad7_q3 TINYINT NULL COMMENT 'Worrying too much',
    gad7_q4 TINYINT NULL COMMENT 'Trouble relaxing',
    gad7_q5 TINYINT NULL COMMENT 'Restless',
    gad7_q6 TINYINT NULL COMMENT 'Easily annoyed or irritable',
    gad7_q7 TINYINT NULL COMMENT 'Feeling afraid',
    gad7_total_score TINYINT NULL,
    gad7_severity ENUM('Minimal', 'Mild', 'Moderate', 'Severe') NULL,
    
    -- Clinical Data
    clinician_name VARCHAR(100) NULL,
    clinician_role ENUM('Psychiatrist', 'Psychologist', 'Nurse', 'Counselor', 'Doctor') NULL,
    clinical_notes TEXT NULL,
    recommendations TEXT NULL,
    referral_needed BOOLEAN DEFAULT FALSE,
    urgent_flag BOOLEAN DEFAULT FALSE COMMENT 'For suicide risk or severe cases',
    
    -- Metadata
    assessment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_patient_mrn (patient_mrn),
    INDEX idx_screening_type (screening_type),
    INDEX idx_urgent_flag (urgent_flag),
    INDEX idx_assessment_date (assessment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add screening_result_id to hospital_integrations for tracking
ALTER TABLE hospital_integrations 
ADD COLUMN screening_result_id INT NULL AFTER assessment_id,
ADD FOREIGN KEY (screening_result_id) REFERENCES screening_results(id) ON DELETE CASCADE;

-- Update users table to support roles
ALTER TABLE users 
ADD COLUMN role ENUM('Admin', 'Psychiatrist', 'Psychologist', 'Nurse', 'Counselor', 'Patient') DEFAULT 'Patient' AFTER password,
ADD COLUMN department VARCHAR(100) NULL AFTER role,
ADD COLUMN license_number VARCHAR(50) NULL AFTER department;

SELECT 'Screening tools schema created successfully!' AS message;
