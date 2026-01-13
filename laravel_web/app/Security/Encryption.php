<?php

/**
 * Encryption Class
 * Provides AES-256-GCM encryption for sensitive data
 * Compliant with HIPAA and GDPR requirements
 */
class Encryption
{
    private const METHOD = 'aes-256-gcm';
    private const KEY_LENGTH = 32; // 256 bits
    
    private $key;
    
    /**
     * Constructor
     * @param string $key Base64 encoded encryption key
     */
    public function __construct($key = null)
    {
        if ($key === null) {
            // Get from environment variable or config
            $key = getenv('ENCRYPTION_KEY') ?: $this->getDefaultKey();
        }
        
        $this->key = base64_decode($key);
        
        if (strlen($this->key) !== self::KEY_LENGTH) {
            throw new Exception('Invalid encryption key length. Must be 32 bytes.');
        }
    }
    
    /**
     * Encrypt data
     * @param string $data Data to encrypt
     * @return string Base64 encoded encrypted data with IV and tag
     */
    public function encrypt($data)
    {
        if (empty($data)) {
            return '';
        }
        
        // Generate random IV (Initialization Vector)
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METHOD));
        
        // Encrypt with authentication tag
        $tag = '';
        $encrypted = openssl_encrypt(
            $data,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            16 // tag length
        );
        
        if ($encrypted === false) {
            throw new Exception('Encryption failed');
        }
        
        // Combine IV + encrypted data + tag and encode
        return base64_encode($iv . $encrypted . $tag);
    }
    
    /**
     * Decrypt data
     * @param string $data Base64 encoded encrypted data
     * @return string Decrypted data
     */
    public function decrypt($data)
    {
        if (empty($data)) {
            return '';
        }
        
        // Decode from base64
        $decoded = base64_decode($data);
        
        if ($decoded === false) {
            throw new Exception('Invalid encrypted data format');
        }
        
        $ivLength = openssl_cipher_iv_length(self::METHOD);
        $tagLength = 16;
        
        // Extract IV, encrypted data, and tag
        $iv = substr($decoded, 0, $ivLength);
        $tag = substr($decoded, -$tagLength);
        $encrypted = substr($decoded, $ivLength, -$tagLength);
        
        // Decrypt
        $decrypted = openssl_decrypt(
            $encrypted,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        if ($decrypted === false) {
            throw new Exception('Decryption failed - data may be corrupted or tampered');
        }
        
        return $decrypted;
    }
    
    /**
     * Generate a new encryption key
     * @return string Base64 encoded key
     */
    public static function generateKey()
    {
        return base64_encode(openssl_random_pseudo_bytes(self::KEY_LENGTH));
    }
    
    /**
     * Hash password using bcrypt
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
    
    /**
     * Verify password against hash
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool True if password matches
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Get default key (should be replaced with environment variable in production)
     * @return string Base64 encoded key
     */
    private function getDefaultKey()
    {
        // WARNING: This is for development only!
        // In production, use environment variable: putenv('ENCRYPTION_KEY=your_key_here')
        return 'bWVudGFsX2hlYWx0aF9lbmNyeXB0aW9uX2tleV8yMDI1X3NlY3VyZQ==';
    }
}
