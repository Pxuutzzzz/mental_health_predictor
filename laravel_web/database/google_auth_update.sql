-- Add Google OAuth columns to users table
-- This update adds support for Google Sign-In

USE mental_health_db;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) NULL UNIQUE AFTER email,
ADD COLUMN IF NOT EXISTS google_picture VARCHAR(500) NULL AFTER google_id;

-- Create index for google_id
CREATE INDEX IF NOT EXISTS idx_google_id ON users(google_id);

-- Update password column to allow NULL for Google-only users
ALTER TABLE users MODIFY password VARCHAR(255) NULL;
