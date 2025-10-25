<?php
/**
 * DEBUG: Passwort-Hash überprüfen
 *
 * ANLEITUNG:
 * 1. Diese Datei per FTP ins WordPress-Root hochladen
 * 2. Im Browser aufrufen: https://caffejulia.ch/debug-hash.php
 * 3. Ergebnis ansehen
 * 4. Datei SOFORT löschen nach Benutzung!
 */

// WordPress laden
require_once('wp-load.php');

// Farben für bessere Lesbarkeit
echo '<!DOCTYPE html><html><head><meta charset="UTF-8">';
echo '<title>Password-Hash Debug</title>';
echo '<style>body{font-family:monospace;padding:40px;background:#1e293b;color:#e2e8f0}';
echo '.box{background:#334155;padding:20px;margin:20px 0;border-radius:8px;border-left:4px solid #3b82f6}';
echo '.error{border-left-color:#ef4444}.success{border-left-color:#10b981}.warning{border-left-color:#f59e0b}';
echo 'h1{color:#60a5fa}h2{color:#93c5fd;margin-top:0}';
echo 'code{background:#1e293b;padding:2px 8px;border-radius:4px;color:#fbbf24}';
echo 'pre{background:#0f172a;padding:15px;border-radius:5px;overflow-x:auto;color:#94a3b8}</style></head><body>';

echo '<h1>🔍 Caffe Julia Tracker - Password-Hash Debug</h1>';

// Aktueller Hash aus Datenbank
$current_hash = get_option('cjtp_password_hash');

// Erwarteter Hash berechnen
$password = 'CaffeJulia2025';
$salt = 'CaffeJulia2025SecureSalt';
$expected_hash = hash('sha256', $password . $salt);

// Alter Hash (v6.0.2 und früher)
$old_hash = 'c03c1a054bd18b924e3d7134b2b0b7ce8b6d0e94a49893e1ae3af7c1cba2168c';

echo '<div class="box">';
echo '<h2>📊 Status-Übersicht</h2>';

if (empty($current_hash)) {
    echo '<div class="box error">';
    echo '<strong>❌ FEHLER:</strong> Kein Password-Hash in Datenbank!<br>';
    echo 'Das Plugin wurde nicht korrekt aktiviert.';
    echo '</div>';
} elseif ($current_hash === $expected_hash) {
    echo '<div class="box success">';
    echo '<strong>✅ PERFEKT:</strong> Password-Hash ist korrekt!<br>';
    echo 'Login sollte mit <code>CaffeJulia2025</code> funktionieren.';
    echo '</div>';
} elseif ($current_hash === $old_hash) {
    echo '<div class="box error">';
    echo '<strong>❌ PROBLEM:</strong> Alter Hash aktiv!<br>';
    echo 'Das ist der Hash für <code>CyberSecure</code> (alte Version).<br>';
    echo 'Die Auto-Update Funktion hat nicht funktioniert!';
    echo '</div>';
} else {
    echo '<div class="box warning">';
    echo '<strong>⚠️ UNBEKANNT:</strong> Hash stimmt nicht überein!<br>';
    echo 'Der Hash in der Datenbank ist weder alt noch neu.';
    echo '</div>';
}
echo '</div>';

echo '<div class="box">';
echo '<h2>🔐 Hash-Details</h2>';
echo '<pre>';
echo 'Aktueller Hash (Datenbank):\n';
echo '<code>' . ($current_hash ?: '(leer)') . '</code>\n\n';
echo 'Erwarteter Hash (CaffeJulia2025):\n';
echo '<code>' . $expected_hash . '</code>\n\n';
echo 'Alter Hash (CyberSecure):\n';
echo '<code>' . $old_hash . '</code>\n\n';
echo 'Stimmen überein? ' . ($current_hash === $expected_hash ? '✅ JA' : '❌ NEIN');
echo '</pre>';
echo '</div>';

echo '<div class="box">';
echo '<h2>🔧 Passwort-Berechnung</h2>';
echo '<pre>';
echo 'Passwort:  ' . $password . '\n';
echo 'Salt:      ' . $salt . '\n';
echo 'Methode:   SHA-256(Passwort + Salt)\n';
echo 'Ergebnis:  ' . $expected_hash;
echo '</pre>';
echo '</div>';

echo '<div class="box">';
echo '<h2>🛠️ Lösung</h2>';

if ($current_hash !== $expected_hash) {
    echo '<p><strong>So beheben Sie das Problem:</strong></p>';
    echo '<ol>';
    echo '<li>Klicken Sie unten auf "Hash jetzt korrigieren"</li>';
    echo '<li>Seite neu laden</li>';
    echo '<li>Login sollte funktionieren</li>';
    echo '</ol>';

    if (isset($_GET['fix'])) {
        update_option('cjtp_password_hash', $expected_hash);
        echo '<div class="box success">';
        echo '<strong>✅ KORRIGIERT!</strong> Hash wurde aktualisiert.<br>';
        echo '<a href="?" style="color:#60a5fa">Seite neu laden</a> um zu überprüfen.';
        echo '</div>';
    } else {
        echo '<p><a href="?fix=1" style="display:inline-block;background:#3b82f6;color:white;padding:12px 24px;';
        echo 'text-decoration:none;border-radius:6px;font-weight:bold">Hash jetzt korrigieren</a></p>';
    }
} else {
    echo '<p><strong>✅ Alles in Ordnung!</strong></p>';
    echo '<p>Der Hash ist korrekt. Login sollte funktionieren mit: <code>CaffeJulia2025</code></p>';
}
echo '</div>';

echo '<div class="box error">';
echo '<h2>⚠️ SICHERHEIT</h2>';
echo '<p><strong>WICHTIG:</strong> Löschen Sie diese Datei <code>debug-hash.php</code> SOFORT nach der Benutzung!</p>';
echo '<p>Diese Datei zeigt sensible Informationen über Ihr Passwort-System.</p>';
echo '</div>';

echo '<div class="box">';
echo '<h2>📍 Plugin-Info</h2>';
echo '<pre>';
echo 'Plugin-Version: ' . (defined('CJTP_VERSION') ? CJTP_VERSION : 'nicht geladen') . '\n';
echo 'WordPress:      ' . get_bloginfo('version') . '\n';
echo 'PHP:            ' . PHP_VERSION;
echo '</pre>';
echo '</div>';

echo '</body></html>';
?>
