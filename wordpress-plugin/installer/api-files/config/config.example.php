<?php
/**
 * Caffe Julia Tracker - Sichere Konfigurationsdatei
 *
 * WICHTIG: Diese Datei nach config.php kopieren und anpassen!
 * Die config.php sollte NICHT in Git eingecheckt werden!
 */

// Verhindere direkten Zugriff
if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

// ============================================
// DATENBANK-KONFIGURATION
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'caffe_julia_tracker');
define('DB_USER', 'caffe_julia_app');
define('DB_PASS', 'HIER_STARKES_PASSWORT_EINGEBEN');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// SICHERHEITS-EINSTELLUNGEN
// ============================================

// Session-Einstellungen
define('SESSION_LIFETIME', 28800); // 8 Stunden in Sekunden
define('SESSION_NAME', 'CAFFE_JULIA_SESSION');
define('SESSION_COOKIE_SECURE', true); // true wenn HTTPS
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Strict');

// Login-Sicherheit
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 3600); // 1 Stunde in Sekunden
define('PASSWORD_MIN_LENGTH', 12);

// CSRF-Schutz
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 Stunde

// Rate Limiting
define('API_RATE_LIMIT', 100); // Requests pro Stunde
define('API_RATE_WINDOW', 3600); // Zeitfenster in Sekunden

// ============================================
// VERSCHLÜSSELUNGS-EINSTELLUNGEN
// ============================================

// Generiere mit: openssl rand -base64 32
define('ENCRYPTION_KEY', 'HIER_ZUFÄLLIGEN_KEY_GENERIEREN');

// JWT-Token (falls benötigt)
define('JWT_SECRET', 'HIER_ZUFÄLLIGEN_JWT_SECRET_GENERIEREN');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRATION', 28800); // 8 Stunden

// ============================================
// ANWENDUNGS-EINSTELLUNGEN
// ============================================
define('APP_NAME', 'Caffe Julia Tracker');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'production'); // 'development' oder 'production'
define('APP_DEBUG', false); // NUR IN ENTWICKLUNG true!
define('APP_TIMEZONE', 'Europe/Zurich');

// Basis-URL (ohne Trailing Slash)
define('APP_URL', 'https://ihr-domain.de/tracker');
define('APP_PATH', '/home/user/tracker');

// ============================================
// LOGGING
// ============================================
define('LOG_ENABLED', true);
define('LOG_PATH', APP_PATH . '/logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Audit-Logging
define('AUDIT_LOG_ENABLED', true);
define('AUDIT_LOG_RETENTION_DAYS', 365);

// ============================================
// FILE UPLOAD (falls benötigt)
// ============================================
define('UPLOAD_ENABLED', false);
define('UPLOAD_MAX_SIZE', 5242880); // 5MB in Bytes
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);
define('UPLOAD_PATH', APP_PATH . '/uploads/');

// ============================================
// E-MAIL-EINSTELLUNGEN (optional)
// ============================================
define('MAIL_ENABLED', false);
define('MAIL_FROM', 'noreply@caffejulia.com');
define('MAIL_FROM_NAME', 'Caffe Julia Tracker');
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_ENCRYPTION', 'tls'); // 'tls' oder 'ssl'

// ============================================
// WORDPRESS-INTEGRATION
// ============================================
define('WP_INTEGRATION_ENABLED', false);
define('WP_PATH', '/path/to/wordpress');
define('WP_TABLE_PREFIX', 'wp_');

// ============================================
// CORS-EINSTELLUNGEN
// ============================================
define('CORS_ENABLED', true);
define('CORS_ALLOWED_ORIGINS', [
    'https://ihre-wordpress-domain.de',
    'https://www.ihre-wordpress-domain.de'
]);

// ============================================
// ENTWICKLER-KONTAKT
// ============================================
define('ADMIN_EMAIL', 'admin@caffejulia.com');
define('ADMIN_PHONE', '+41 XX XXX XX XX');

// ============================================
// SICHERHEITS-HEADERS (werden von PHP gesetzt)
// ============================================
$SECURITY_HEADERS = [
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'no-referrer',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; object-src 'none'; media-src 'none'; frame-src 'none'; base-uri 'self'; form-action 'self';"
];

// ============================================
// FEATURE FLAGS
// ============================================
define('FEATURE_MULTI_DAY_EVENTS', true);
define('FEATURE_EXPORT_CSV', true);
define('FEATURE_EXPORT_PDF', false);
define('FEATURE_STATISTICS', true);
define('FEATURE_ADVANCED_REPORTING', false);

// ============================================
// DATENSCHUTZ & DSGVO
// ============================================
define('GDPR_ENABLED', true);
define('DATA_RETENTION_DAYS', 730); // 2 Jahre
define('COOKIE_CONSENT_REQUIRED', true);

// ============================================
// ENDE DER KONFIGURATION
// ============================================
