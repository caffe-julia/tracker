<?php
/**
 * Login Endpoint
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$username = Security::sanitizeInput($input['username'] ?? '');
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    Security::errorResponse('Benutzername und Passwort erforderlich', 400);
}

// Prüfe auf Injection-Versuche
if (Security::detectInjectionAttempt($username)) {
    Security::errorResponse('Ungültige Eingabe erkannt', 400);
}

$result = $auth->login($username, $password);

if (!$result['success']) {
    Security::errorResponse($result['error'], 401);
}

// Setze Session-Cookie
setcookie(
    SESSION_NAME,
    $result['session_token'],
    [
        'expires' => time() + SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => SESSION_COOKIE_SECURE,
        'httponly' => SESSION_COOKIE_HTTPONLY,
        'samesite' => SESSION_COOKIE_SAMESITE
    ]
);

Security::successResponse([
    'user' => $result['user'],
    'session_token' => $result['session_token'],
    'csrf_token' => Security::generateCSRFToken()
], 'Login erfolgreich');
