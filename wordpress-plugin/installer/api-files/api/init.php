<?php
/**
 * Caffe Julia Tracker - Initialization
 * Bootstrap-Datei für die Anwendung
 */

// Definiere Konstante für sicheren Zugriff
define('APP_INIT', true);

// Error Reporting basierend auf Umgebung
if (file_exists(__DIR__ . '/../config/config.php')) {
    require_once __DIR__ . '/../config/config.php';
} else {
    die('FEHLER: config.php nicht gefunden! Bitte config.example.php zu config.php kopieren und anpassen.');
}

// Error Handling
if (APP_ENV === 'development' && APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', LOG_PATH . 'php_errors.log');
}

// Timezone setzen
date_default_timezone_set(APP_TIMEZONE);

// Session-Konfiguration (sicher)
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', SESSION_COOKIE_SECURE ? '1' : '0');
ini_set('session.cookie_samesite', SESSION_COOKIE_SAMESITE);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_lifetime', SESSION_LIFETIME);
session_name(SESSION_NAME);

// Sicherheits-Headers setzen
if (isset($SECURITY_HEADERS)) {
    foreach ($SECURITY_HEADERS as $header => $value) {
        header("$header: $value");
    }
}

// CORS-Headers (falls aktiviert)
if (CORS_ENABLED) {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (in_array($origin, CORS_ALLOWED_ORIGINS)) {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
    }

    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

// Autoloader für Klassen
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Require wichtige Klassen
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Security.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/Logger.php';

// Logger initialisieren
$logger = new Logger();

// Globale Exception Handler
set_exception_handler(function ($exception) use ($logger) {
    $logger->error('Uncaught Exception: ' . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);

    if (APP_ENV === 'development' && APP_DEBUG) {
        echo json_encode([
            'success' => false,
            'error' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Ein interner Fehler ist aufgetreten.'
        ]);
    }
    exit;
});

// Globale Error Handler
set_error_handler(function ($severity, $message, $file, $line) use ($logger) {
    if (!(error_reporting() & $severity)) {
        return;
    }

    $logger->error("PHP Error: $message", [
        'severity' => $severity,
        'file' => $file,
        'line' => $line
    ]);

    throw new ErrorException($message, 0, $severity, $file, $line);
});
