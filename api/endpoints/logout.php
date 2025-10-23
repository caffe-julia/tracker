<?php
/**
 * Logout Endpoint
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$token = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE[SESSION_NAME] ?? null;

if ($token && strpos($token, 'Bearer ') === 0) {
    $token = substr($token, 7);
}

if ($token) {
    $auth->logout($token);
}

// Lösche Session-Cookie
setcookie(
    SESSION_NAME,
    '',
    [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => SESSION_COOKIE_SECURE,
        'httponly' => SESSION_COOKIE_HTTPONLY,
        'samesite' => SESSION_COOKIE_SAMESITE
    ]
);

Security::successResponse(null, 'Erfolgreich abgemeldet');
