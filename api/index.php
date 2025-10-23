<?php
/**
 * Caffe Julia Tracker - REST API
 * Haupt-Einstiegspunkt für alle API-Anfragen
 */

require_once __DIR__ . '/init.php';

// Rate Limiting
$ip = Security::getClientIP();
if (!Security::checkRateLimit($ip)) {
    Security::errorResponse('Zu viele Anfragen. Bitte warten Sie eine Weile.', 429);
}

// Parse Request
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Entferne 'api' aus Path falls vorhanden
if (isset($pathParts[0]) && $pathParts[0] === 'api') {
    array_shift($pathParts);
}
if (isset($pathParts[0]) && $pathParts[0] === 'index.php') {
    array_shift($pathParts);
}

$endpoint = $pathParts[0] ?? '';
$id = $pathParts[1] ?? null;

// JSON Input parsen
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Auth-Instanz erstellen
$auth = new Auth();
$currentUser = null;

// Öffentliche Endpoints (kein Auth erforderlich)
$publicEndpoints = ['login', 'health'];

if (!in_array($endpoint, $publicEndpoints)) {
    // Session-Token aus Header oder Cookie
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? $_COOKIE[SESSION_NAME] ?? null;

    if ($token && strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    $currentUser = $auth->validateSession($token);

    if (!$currentUser) {
        Security::errorResponse('Nicht authentifiziert', 401);
    }
}

// Router
try {
    switch ($endpoint) {
        case 'health':
            // Health Check
            Security::successResponse([
                'status' => 'ok',
                'timestamp' => date('c'),
                'version' => APP_VERSION
            ]);
            break;

        case 'login':
            if ($method !== 'POST') {
                Security::errorResponse('Methode nicht erlaubt', 405);
            }
            require_once __DIR__ . '/endpoints/login.php';
            break;

        case 'logout':
            if ($method !== 'POST') {
                Security::errorResponse('Methode nicht erlaubt', 405);
            }
            require_once __DIR__ . '/endpoints/logout.php';
            break;

        case 'events':
            require_once __DIR__ . '/endpoints/events.php';
            break;

        case 'muehlen':
            require_once __DIR__ . '/endpoints/muehlen.php';
            break;

        case 'verbrauch':
            require_once __DIR__ . '/endpoints/verbrauch.php';
            break;

        case 'statistics':
            require_once __DIR__ . '/endpoints/statistics.php';
            break;

        case 'export':
            require_once __DIR__ . '/endpoints/export.php';
            break;

        case 'user':
            require_once __DIR__ . '/endpoints/user.php';
            break;

        default:
            Security::errorResponse('Endpoint nicht gefunden', 404);
    }

} catch (Exception $e) {
    $logger->error('API Error: ' . $e->getMessage(), [
        'endpoint' => $endpoint,
        'method' => $method,
        'user_id' => $currentUser['user_id'] ?? null,
        'trace' => $e->getTraceAsString()
    ]);

    Security::errorResponse(
        APP_DEBUG ? $e->getMessage() : 'Ein Fehler ist aufgetreten',
        500
    );
}
