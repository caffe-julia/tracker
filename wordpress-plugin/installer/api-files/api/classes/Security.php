<?php
/**
 * Sicherheitsklasse mit umfassenden Security-Features
 */

class Security
{
    private static $logger;

    public function __construct()
    {
        self::$logger = new Logger();
    }

    /**
     * XSS-Schutz: HTML-Entities escapen
     */
    public static function sanitizeOutput($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeOutput'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Input-Validierung und Sanitization
     */
    public static function sanitizeInput($data, $type = 'string')
    {
        if (is_array($data)) {
            return array_map(function ($item) use ($type) {
                return self::sanitizeInput($item, $type);
            }, $data);
        }

        switch ($type) {
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
            case 'string':
            default:
                return strip_tags(trim($data));
        }
    }

    /**
     * Passwort hashen (bcrypt mit Cost 12)
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Passwort verifizieren
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Passwort-Stärke prüfen
     */
    public static function validatePasswordStrength($password)
    {
        $errors = [];

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'Passwort muss mindestens ' . PASSWORD_MIN_LENGTH . ' Zeichen lang sein';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Passwort muss mindestens einen Großbuchstaben enthalten';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Passwort muss mindestens einen Kleinbuchstaben enthalten';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Passwort muss mindestens eine Zahl enthalten';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Passwort muss mindestens ein Sonderzeichen enthalten';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * CSRF-Token generieren
     */
    public static function generateCSRFToken()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME] = $token;
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();

        return $token;
    }

    /**
     * CSRF-Token validieren
     */
    public static function validateCSRFToken($token)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION[CSRF_TOKEN_NAME]) || !isset($_SESSION[CSRF_TOKEN_NAME . '_time'])) {
            return false;
        }

        // Token abgelaufen?
        if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_LIFETIME) {
            return false;
        }

        // Timing-Safe Vergleich
        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * Sichere zufällige Token generieren
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * IP-Adresse sicher abrufen
     */
    public static function getClientIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Validiere IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '0.0.0.0';
    }

    /**
     * User-Agent abrufen (begrenzte Länge)
     */
    public static function getUserAgent()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        return substr($userAgent, 0, 500);
    }

    /**
     * Rate Limiting prüfen
     */
    public static function checkRateLimit($identifier, $maxAttempts = API_RATE_LIMIT, $window = API_RATE_WINDOW)
    {
        $db = Database::getInstance();

        // Bereinige alte Einträge
        $db->delete(
            "DELETE FROM audit_log WHERE action = 'RATE_LIMIT' AND created_at < DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$window]
        );

        // Zähle Anfragen im Zeitfenster
        $count = $db->selectOne(
            "SELECT COUNT(*) as count FROM audit_log
             WHERE action = 'RATE_LIMIT'
             AND ip_address = ?
             AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$identifier, $window]
        );

        if ($count && $count['count'] >= $maxAttempts) {
            self::$logger->warning('Rate Limit überschritten', [
                'identifier' => $identifier,
                'attempts' => $count['count']
            ]);
            return false;
        }

        // Protokolliere Anfrage
        $db->insert(
            "INSERT INTO audit_log (action, table_name, ip_address, user_agent) VALUES (?, ?, ?, ?)",
            ['RATE_LIMIT', 'api', $identifier, self::getUserAgent()]
        );

        return true;
    }

    /**
     * Daten verschlüsseln (AES-256-GCM)
     */
    public static function encrypt($data)
    {
        $key = ENCRYPTION_KEY;
        $iv = random_bytes(16);
        $tag = '';

        $encrypted = openssl_encrypt(
            $data,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $encrypted);
    }

    /**
     * Daten entschlüsseln (AES-256-GCM)
     */
    public static function decrypt($data)
    {
        $key = ENCRYPTION_KEY;
        $data = base64_decode($data);

        $iv = substr($data, 0, 16);
        $tag = substr($data, 16, 16);
        $encrypted = substr($data, 32);

        $decrypted = openssl_decrypt(
            $encrypted,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return $decrypted;
    }

    /**
     * SQL-Injection-Schutz: Validiere Spaltennamen
     */
    public static function validateColumnName($column, $allowedColumns)
    {
        return in_array($column, $allowedColumns, true);
    }

    /**
     * Sichere Headers für File-Download
     */
    public static function setDownloadHeaders($filename, $contentType = 'application/octet-stream')
    {
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('X-Content-Type-Options: nosniff');
    }

    /**
     * Prüfe auf verdächtige Patterns (Injection-Versuche)
     */
    public static function detectInjectionAttempt($data)
    {
        $suspiciousPatterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/eval\s*\(/i',
            '/base64_decode/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $data)) {
                self::$logger->warning('Injection-Versuch erkannt', [
                    'pattern' => $pattern,
                    'data' => substr($data, 0, 100),
                    'ip' => self::getClientIP()
                ]);
                return true;
            }
        }

        return false;
    }

    /**
     * JSON sicher ausgeben
     */
    public static function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Fehler-Response
     */
    public static function errorResponse($message, $statusCode = 400, $details = null)
    {
        $response = [
            'success' => false,
            'error' => $message
        ];

        if ($details !== null && APP_DEBUG) {
            $response['details'] = $details;
        }

        self::jsonResponse($response, $statusCode);
    }

    /**
     * Erfolg-Response
     */
    public static function successResponse($data, $message = null)
    {
        $response = [
            'success' => true,
            'data' => $data
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        self::jsonResponse($response, 200);
    }
}
