# Security Implementation Guide
## Mental Health Predictor - HIPAA/GDPR Compliance

### Overview
This guide explains how to implement and use the security features added to ensure HIPAA and GDPR compliance.

---

## 1. Installation & Setup

### Step 1: Run Database Migrations

Execute the security schema to add required tables:

```bash
# Connect to MySQL
mysql -u your_username -p your_database < laravel_web/database/security_schema.sql
```

Or manually in phpMyAdmin/MySQL Workbench:
- Import `laravel_web/database/security_schema.sql`

This creates:
- `audit_logs` - Logs all security events
- `user_consents` - GDPR consent records
- `encrypted_data` - Encrypted sensitive data storage
- `data_access_log` - Data access tracking
- `data_retention` - Scheduled data deletions
- `security_incidents` - Incident tracking

### Step 2: Generate Encryption Key

For production, generate a secure encryption key:

```php
<?php
require_once 'laravel_web/app/Security/Encryption.php';
$key = Encryption::generateKey();
echo "ENCRYPTION_KEY=" . $key;
```

Add to `.env` file or set as environment variable:
```bash
ENCRYPTION_KEY=your_generated_key_here
ANONYMIZATION_SECRET=your_anonymization_secret_here
```

### Step 3: Enable Consent Management

Add consent check to `index.php` after login:

```php
// After authentication check
if (isset($_SESSION['user_id']) && !isset($_SESSION['consent_given'])) {
    // Check if user has given consent
    $stmt = $db->prepare("SELECT * FROM user_consents WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $consent = $stmt->fetch();
    
    if (!$consent) {
        // Redirect to consent page
        header('Location: ?page=consent');
        exit;
    } else {
        $_SESSION['consent_given'] = true;
    }
}
```

---

## 2. Using Security Features

### A. Audit Logging

All authentication events are automatically logged. To log custom events:

```php
require_once 'app/Security/AuditLogger.php';

$logger = new AuditLogger($db);

// Log data access
$logger->log(
    AuditLogger::EVENT_DATA_ACCESS,
    $_SESSION['user_id'],
    'User viewed prediction history',
    ['prediction_id' => 123],
    'SUCCESS'
);

// Log data export
$logger->log(
    AuditLogger::EVENT_DATA_EXPORT,
    $_SESSION['user_id'],
    'User exported their data',
    ['format' => 'csv', 'record_count' => 10],
    'SUCCESS'
);
```

**View Audit Logs:**
```php
// Get user's audit trail
$logs = $logger->getUserAuditTrail($_SESSION['user_id'], 50);

// Search logs with filters
$logs = $logger->getLogs([
    'user_id' => 123,
    'event_type' => AuditLogger::EVENT_LOGIN,
    'date_from' => '2025-01-01',
    'date_to' => '2025-12-31'
], 100, 0);

// Export logs for compliance
$filepath = $logger->exportLogs([], 'csv');
```

### B. Data Encryption

Encrypt sensitive data before storing:

```php
require_once 'app/Security/Encryption.php';

$encryption = new Encryption();

// Encrypt phone number
$phone = '+62812345678';
$encryptedPhone = $encryption->encrypt($phone);

// Store in database
$stmt = $db->prepare("UPDATE users SET encrypted_phone = ? WHERE id = ?");
$stmt->execute([$encryptedPhone, $userId]);

// Decrypt when needed
$decryptedPhone = $encryption->decrypt($encryptedPhone);
```

### C. Consent Management

Users must give consent before data processing. The consent page shows:
- Required consents (data collection, processing, storage)
- Optional consents (sharing, analytics, marketing)
- User rights (access, rectification, erasure, portability)

**Check consent status:**
```php
// In any controller
if (!isset($_SESSION['consent_given'])) {
    header('Location: ?page=consent');
    exit;
}
```

**Withdraw consent:**
```php
require_once 'app/Security/AuditLogger.php';

$stmt = $db->prepare("UPDATE user_consents SET withdrawn_at = NOW() WHERE user_id = ? AND withdrawn_at IS NULL");
$stmt->execute([$userId]);

$logger->log(
    AuditLogger::EVENT_CONSENT_WITHDRAWN,
    $userId,
    'User withdrew consent',
    [],
    'SUCCESS'
);

// Stop all data processing
$_SESSION['consent_given'] = false;
```

### D. Data Anonymization

For research and analytics, anonymize user data:

```php
require_once 'app/Security/DataAnonymizer.php';

// Anonymize single user
$user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);
$anonymizedUser = DataAnonymizer::anonymizeUser($user);

// Export anonymized dataset for research
$dataset = DataAnonymizer::exportAnonymizedDataset($db, 'predictions', 1000);

// Apply k-anonymity (ensure at least 5 users in each group)
$kAnonymized = DataAnonymizer::applyKAnonymity($dataset, 5);

// Mask email for display
$maskedEmail = DataAnonymizer::maskEmail('user@example.com');
// Output: u***r@example.com
```

### E. Secure Data Deletion

When user requests account deletion:

```php
require_once 'app/Security/DataAnonymizer.php';

// Multi-pass secure deletion (DoD 5220.22-M standard)
$success = DataAnonymizer::secureDeleteUser($db, $userId);

if ($success) {
    session_destroy();
    echo "Account successfully deleted";
}

// Or use stored procedure
$db->exec("CALL secure_delete_user($userId)");
```

---

## 3. User Rights Implementation

### Right to Access (Data Portability)

Create a data export page:

```php
// views/data_export.php
require_once 'app/Security/AuditLogger.php';

$userId = $_SESSION['user_id'];

// Collect all user data
$user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);
$predictions = $db->fetchAll("SELECT * FROM assessments WHERE user_id = ?", [$userId]);
$consents = $db->fetchAll("SELECT * FROM user_consents WHERE user_id = ?", [$userId]);

$exportData = [
    'user_profile' => [
        'name' => $user['name'],
        'email' => $user['email'],
        'created_at' => $user['created_at']
    ],
    'predictions' => $predictions,
    'consents' => $consents
];

// Log the export
$logger = new AuditLogger($db);
$logger->log(
    AuditLogger::EVENT_DATA_EXPORT,
    $userId,
    'User exported personal data',
    ['format' => 'json'],
    'SUCCESS'
);

// Download as JSON
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="my_data.json"');
echo json_encode($exportData, JSON_PRETTY_PRINT);
```

### Right to Rectification

Users can update their information through profile page.

### Right to Erasure

Add delete account button:

```php
// Handle account deletion request
if ($_POST['action'] === 'delete_account') {
    require_once 'app/Security/DataAnonymizer.php';
    
    // Confirm password
    if (!password_verify($_POST['password'], $user['password'])) {
        echo "Incorrect password";
        exit;
    }
    
    // Secure deletion
    DataAnonymizer::secureDeleteUser($db, $_SESSION['user_id']);
    session_destroy();
    
    header('Location: ?page=login&message=account_deleted');
    exit;
}
```

---

## 4. Compliance Checklist

### HIPAA Compliance

- [x] **Access Controls**: Session-based authentication with secure cookies
- [x] **Audit Logging**: All data access logged in `audit_logs` table
- [x] **Data Encryption**: AES-256-GCM for sensitive data
- [x] **Transmission Security**: Use HTTPS/TLS in production
- [x] **Integrity Controls**: Prepared statements prevent SQL injection
- [x] **Backup & Recovery**: Encrypted backups (configure separately)

### GDPR Compliance

- [x] **Lawfulness**: Explicit consent mechanism implemented
- [x] **Transparency**: Privacy policy and consent form
- [x] **Purpose Limitation**: Data used only for stated purposes
- [x] **Data Minimization**: Only essential data collected
- [x] **Accuracy**: Users can update information
- [x] **Storage Limitation**: 2-year retention policy
- [x] **Integrity & Confidentiality**: Encryption and access controls
- [x] **Accountability**: Audit logs and DPO contact
- [x] **Right to Access**: Data export functionality
- [x] **Right to Erasure**: Secure deletion implemented
- [x] **Right to Portability**: JSON/CSV export
- [x] **Data Breach Notification**: 72-hour incident response plan

---

## 5. Production Configuration

### Required Environment Variables

```bash
# .env file
ENCRYPTION_KEY=base64_encoded_32_byte_key
ANONYMIZATION_SECRET=random_secret_for_hashing
DB_HOST=localhost
DB_NAME=mental_health_db
DB_USER=your_username
DB_PASS=your_password
```

### HTTPS Configuration

**Apache (.htaccess):**
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security Headers
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set X-Content-Type-Options "nosniff"
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

### Secure Session Configuration

Add to `index.php`:

```php
// Secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only over HTTPS
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
```

### Regular Maintenance

**Setup Cron Jobs:**

```bash
# Daily audit log cleanup (7-year retention)
0 2 * * * mysql -u user -p database -e "CALL cleanup_old_audit_logs();"

# Monthly inactive user check (2-year retention)
0 3 1 * * mysql -u user -p database -e "CALL cleanup_inactive_users();"
```

---

## 6. Monitoring & Alerts

### Failed Login Monitoring

```sql
-- Check for suspicious login patterns
SELECT 
    ip_address, 
    COUNT(*) as failed_attempts,
    MAX(created_at) as last_attempt
FROM audit_logs
WHERE event_type = 'LOGIN_FAILED'
AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY ip_address
HAVING failed_attempts > 5;
```

### Data Access Monitoring

```sql
-- Who accessed data in last 24 hours
SELECT 
    u.name,
    a.action,
    a.created_at
FROM audit_logs a
JOIN users u ON a.user_id = u.id
WHERE a.event_type = 'DATA_ACCESS'
AND a.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY a.created_at DESC;
```

---

## 7. Testing

### Test Audit Logging

```bash
# Login and check audit log
# Expected: LOGIN event in audit_logs table
```

### Test Encryption

```php
$encryption = new Encryption();
$original = "Sensitive Data";
$encrypted = $encryption->encrypt($original);
$decrypted = $encryption->decrypt($encrypted);
assert($original === $decrypted);
```

### Test Consent Flow

1. Register new user
2. Should redirect to consent page
3. Accept required consents
4. Should proceed to dashboard

### Test Data Export

1. Login as user
2. Navigate to data export page
3. Download should include all user data
4. Check audit_logs for DATA_EXPORT event

---

## 8. Documentation for Users

### Privacy Policy Summary

**What data we collect:**
- Name, email, date of birth (account)
- Mental health questionnaire responses
- Prediction results and recommendations

**How we use it:**
- Generate AI-powered mental health predictions
- Improve our algorithms (anonymized)
- Provide personalized recommendations

**Your rights:**
- Access your data anytime
- Correct inaccurate information
- Delete your account permanently
- Export your data in JSON/CSV
- Withdraw consent at any time

**Security:**
- AES-256 encryption
- HTTPS/TLS for all connections
- Regular security audits
- 7-year audit log retention

---

## Support & Compliance Contact

**Data Protection Officer:**
- Email: privacy@mentalhealthpredictor.com
- Response time: 48 hours

**Security Incidents:**
- Email: security@mentalhealthpredictor.com
- Phone: [Hotline Number]
- 24/7 monitoring

---

**Last Updated:** December 21, 2025
**Version:** 1.0
