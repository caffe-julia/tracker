<?php
/**
 * TRACKER-PASSWORT ZURÜCKSETZEN
 *
 * ANLEITUNG:
 * 1. Diese Datei per FTP in Ihr WordPress-Root-Verzeichnis hochladen
 * 2. Im Browser aufrufen: https://caffejulia.ch/RESET-PASSWORT.php
 * 3. Neues Passwort wird gesetzt: "CaffeJulia2025"
 * 4. Diese Datei SOFORT LÖSCHEN nach Benutzung!
 */

// WordPress laden
require_once('wp-load.php');

// Neues Standard-Passwort
$new_password = 'CaffeJulia2025';
$salt = 'CaffeJulia2025SecureSalt';
$password_hash = hash('sha256', $new_password . $salt);

// In WordPress-Datenbank speichern
update_option('cjtp_password_hash', $password_hash);

echo '<!DOCTYPE html>';
echo '<html><head><meta charset="UTF-8"><title>Passwort zurückgesetzt</title>';
echo '<style>body{font-family:Arial;padding:40px;background:#f0f0f0}';
echo '.box{background:white;padding:30px;border-radius:8px;max-width:500px;margin:0 auto;box-shadow:0 2px 10px rgba(0,0,0,0.1)}';
echo 'h1{color:#92400e}p{line-height:1.6}.success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:20px 0}';
echo '.warning{background:#fff3cd;color:#856404;padding:15px;border-radius:5px;margin:20px 0}';
echo 'code{background:#f4f4f4;padding:2px 6px;border-radius:3px;font-family:monospace}</style></head>';
echo '<body><div class="box">';
echo '<h1>✅ Passwort erfolgreich zurückgesetzt!</h1>';
echo '<div class="success">';
echo '<strong>Ihr neues Tracker-Passwort:</strong><br>';
echo '<code style="font-size:18px">CaffeJulia2025</code>';
echo '</div>';
echo '<p><strong>Was jetzt zu tun ist:</strong></p>';
echo '<ol>';
echo '<li>Öffnen Sie den Tracker: <a href="/mein-tracker" target="_blank">https://caffejulia.ch/mein-tracker</a></li>';
echo '<li>Login mit Passwort: <code>CaffeJulia2025</code></li>';
echo '<li>Gehen Sie zu: <strong>WordPress-Admin → Caffe Tracker → Einstellungen</strong></li>';
echo '<li>Ändern Sie das Passwort in ein neues, sicheres Passwort</li>';
echo '</ol>';
echo '<div class="warning">';
echo '<strong>⚠️ WICHTIG - SICHERHEIT:</strong><br>';
echo 'LÖSCHEN Sie diese Datei <code>RESET-PASSWORT.php</code> SOFORT vom Server!<br>';
echo 'Per FTP oder im Hosting-Panel.';
echo '</div>';
echo '<p>Passwort-Hash in Datenbank: <code>' . substr($password_hash, 0, 20) . '...</code></p>';
echo '</div></body></html>';

// Log für Debugging
error_log('Tracker-Passwort wurde zurückgesetzt auf Standard-Passwort');
?>
