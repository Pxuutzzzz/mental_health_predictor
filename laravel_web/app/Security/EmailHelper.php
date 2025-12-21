<?php

/**
 * Simple Email Helper
 * Supports both PHP mail() function and SMTP (Gmail)
 */
class EmailHelper
{
    private $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/email.php';
    }
    
    /**
     * Send email
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $htmlBody HTML body
     * @param string $plainBody Plain text body (optional)
     * @return bool Success status
     */
    public function send($to, $subject, $htmlBody, $plainBody = '')
    {
        if ($this->config['method'] === 'smtp') {
            return $this->sendSMTP($to, $subject, $htmlBody, $plainBody);
        } else {
            return $this->sendMail($to, $subject, $htmlBody);
        }
    }
    
    /**
     * Send email using PHP mail() function
     */
    private function sendMail($to, $subject, $htmlBody)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$this->config['mail']['from_name']} <{$this->config['mail']['from_email']}>\r\n";
        $headers .= "Reply-To: {$this->config['reply_to']}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        return @mail($to, $subject, $htmlBody, $headers);
    }
    
    /**
     * Send email using SMTP (Gmail)
     */
    private function sendSMTP($to, $subject, $htmlBody, $plainBody)
    {
        $smtp = $this->config['smtp'];
        
        try {
            // Connect to SMTP server
            $socket = @fsockopen($smtp['host'], $smtp['port'], $errno, $errstr, 30);
            
            if (!$socket) {
                error_log("SMTP connection failed: {$errstr} ({$errno})");
                return false;
            }
            
            // Helper function to send command and get response
            $sendCommand = function($command) use ($socket) {
                fwrite($socket, $command . "\r\n");
                return fgets($socket, 512);
            };
            
            // Read initial greeting
            fgets($socket, 512);
            
            // Send EHLO
            $sendCommand("EHLO {$smtp['host']}");
            
            // Start TLS if encryption is enabled
            if ($smtp['encryption'] === 'tls') {
                $sendCommand("STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $sendCommand("EHLO {$smtp['host']}");
            }
            
            // Authenticate
            $sendCommand("AUTH LOGIN");
            $sendCommand(base64_encode($smtp['username']));
            $response = $sendCommand(base64_encode($smtp['password']));
            
            if (strpos($response, '235') === false) {
                error_log("SMTP authentication failed: {$response}");
                fclose($socket);
                return false;
            }
            
            // Send email
            $sendCommand("MAIL FROM: <{$smtp['from_email']}>");
            $sendCommand("RCPT TO: <{$to}>");
            $sendCommand("DATA");
            
            // Email headers and body
            $message = "From: {$smtp['from_name']} <{$smtp['from_email']}>\r\n";
            $message .= "To: {$to}\r\n";
            $message .= "Subject: {$subject}\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "\r\n";
            $message .= $htmlBody;
            $message .= "\r\n.\r\n";
            
            fwrite($socket, $message);
            $response = fgets($socket, 512);
            
            // Quit
            $sendCommand("QUIT");
            fclose($socket);
            
            return strpos($response, '250') !== false;
            
        } catch (Exception $e) {
            error_log("SMTP error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send password reset email
     * @param string $to Recipient email
     * @param string $name Recipient name
     * @param string $resetLink Reset password link
     * @return bool Success status
     */
    public function sendPasswordReset($to, $name, $resetLink)
    {
        $subject = 'Reset Password - Mental Health Predictor';
        
        $htmlBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; }
        .button { display: inline-block; padding: 15px 30px; background: #667eea; color: white !important; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: bold; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .link-box { background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 5px; word-break: break-all; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1 style='margin: 0;'>🔐 Reset Password</h1>
            <p style='margin: 10px 0 0 0;'>Mental Health Predictor</p>
        </div>
        <div class='content'>
            <p>Halo <strong>{$name}</strong>,</p>
            <p>Kami menerima permintaan untuk mereset password akun Anda.</p>
            <p>Klik tombol di bawah ini untuk membuat password baru:</p>
            <center>
                <a href='{$resetLink}' class='button'>Reset Password Sekarang</a>
            </center>
            <p>Atau copy link berikut ke browser Anda:</p>
            <div class='link-box'>{$resetLink}</div>
            <div class='warning'>
                <strong>⚠️ Penting:</strong>
                <ul style='margin: 10px 0;'>
                    <li>Link ini berlaku selama <strong>1 jam</strong></li>
                    <li>Link hanya bisa digunakan <strong>sekali</strong></li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                </ul>
            </div>
            <p>Untuk keamanan akun Anda, jangan bagikan link ini kepada siapapun.</p>
            <p>Salam,<br><strong>Tim Mental Health Predictor</strong></p>
        </div>
        <div class='footer'>
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
            <p>© 2025 Mental Health Predictor. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
        ";
        
        return $this->send($to, $subject, $htmlBody);
    }
}
