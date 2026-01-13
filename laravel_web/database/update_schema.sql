-- Update existing database untuk menambahkan kolom clinical_data
-- Jalankan script ini jika database sudah ada sebelumnya

USE mental_health_db;

-- Cek dan tambahkan kolom clinical_data ke table assessments jika belum ada
ALTER TABLE assessments 
ADD COLUMN IF NOT EXISTS clinical_data JSON AFTER recommendations;

-- Ubah user_id menjadi nullable untuk support clinical assessments tanpa user login
ALTER TABLE assessments 
MODIFY COLUMN user_id INT NULL;

-- Buat table hospital_integrations jika belum ada
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

SELECT 'Database updated successfully!' AS message;
