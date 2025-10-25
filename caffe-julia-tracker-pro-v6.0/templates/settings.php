<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1>⚙️ Caffe Julia Tracker - Einstellungen</h1>

    <div class="notice notice-info">
        <h3>🔐 Authentifizierung via WordPress</h3>
        <p><strong>Der Tracker nutzt jetzt WordPress-Authentifizierung!</strong></p>
        <p>Das bedeutet: Sie müssen mit Ihrem WordPress-Account eingeloggt sein, um den Tracker zu nutzen.</p>
    </div>

    <div class="cjtp-settings" style="max-width: 800px;">

        <!-- Zugriff schützen Sektion -->
        <div class="cjtp-settings-section" style="background: white; padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin: 20px 0;">
            <h2>🔒 So schützen Sie den Tracker-Zugriff</h2>

            <h3>Option 1: Seite auf "Privat" setzen (Empfohlen)</h3>
            <ol>
                <li>Gehen Sie zu: <strong>Seiten → "Mein Tracker" bearbeiten</strong></li>
                <li>Rechts im Editor: <strong>Sichtbarkeit → "Privat"</strong></li>
                <li>Klicken Sie auf <strong>"Aktualisieren"</strong></li>
            </ol>
            <p><strong>Resultat:</strong> Nur eingeloggte WordPress-Benutzer können die Seite sehen.</p>

            <hr style="margin: 30px 0;">

            <h3>Option 2: Password Protected Plugin</h3>
            <ol>
                <li>Installieren Sie das <strong>"Password Protected"</strong> Plugin</li>
                <li>Aktivieren Sie es</li>
                <li>Setzen Sie ein Passwort für die gesamte Website</li>
            </ol>
            <p><strong>Resultat:</strong> Besucher müssen ein Passwort eingeben, bevor sie irgendeine Seite sehen.</p>

            <hr style="margin: 30px 0;">

            <h3>Option 3: Private Site Plugin</h3>
            <ol>
                <li>Installieren Sie ein Plugin wie <strong>"Private Site"</strong></li>
                <li>Konfigurieren Sie, welche Seiten geschützt werden sollen</li>
            </ol>
            <p><strong>Resultat:</strong> Flexiblere Kontrolle über geschützte Bereiche.</p>
        </div>

        <!-- Verwendung Sektion -->
        <div class="cjtp-settings-section" style="background: white; padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin: 20px 0;">
            <h2>✅ So nutzen Sie den Tracker</h2>
            <ol>
                <li><strong>WordPress-Login:</strong> Loggen Sie sich mit Ihrem WordPress-Account ein</li>
                <li><strong>Tracker öffnen:</strong> Gehen Sie zu <code><?php echo home_url('/mein-tracker'); ?></code></li>
                <li><strong>Nutzen:</strong> Der Tracker funktioniert automatisch - Events werden in MySQL gespeichert!</li>
            </ol>
        </div>

        <!-- Vorteile Sektion -->
        <div class="cjtp-settings-section" style="background: #f0fdf4; padding: 20px; border: 1px solid #86efac; border-radius: 8px; margin: 20px 0;">
            <h2>🎉 Vorteile dieser Lösung</h2>
            <ul style="list-style: none; padding-left: 0;">
                <li>✅ <strong>Einfach:</strong> Kein separates Passwort mehr nötig</li>
                <li>✅ <strong>Sicher:</strong> WordPress-Authentifizierung ist bewährt</li>
                <li>✅ <strong>Stabil:</strong> Funktioniert garantiert (keine Session-Probleme)</li>
                <li>✅ <strong>MySQL:</strong> Alle Events werden in der Datenbank gespeichert</li>
                <li>✅ <strong>Cross-Browser:</strong> Daten sind überall verfügbar</li>
            </ul>
        </div>

        <!-- Technische Info -->
        <div class="cjtp-settings-section" style="background: #f8fafc; padding: 20px; border: 1px solid #cbd5e1; border-radius: 8px; margin: 20px 0;">
            <h2>ℹ️ Technische Details</h2>
            <table class="form-table">
                <tr>
                    <th>Plugin-Version:</th>
                    <td><code><?php echo CJTP_VERSION; ?></code></td>
                </tr>
                <tr>
                    <th>Authentifizierung:</th>
                    <td>WordPress Login (current_user_can)</td>
                </tr>
                <tr>
                    <th>Datenspeicherung:</th>
                    <td>MySQL-Datenbank (Custom Post Type: cjtp_event)</td>
                </tr>
                <tr>
                    <th>REST API:</th>
                    <td><code><?php echo rest_url('cjtp/v1/'); ?></code></td>
                </tr>
                <tr>
                    <th>Tracker-URL:</th>
                    <td><a href="<?php echo home_url('/mein-tracker'); ?>" target="_blank"><?php echo home_url('/mein-tracker'); ?></a></td>
                </tr>
            </table>
        </div>

    </div>
</div>

<style>
.cjtp-settings-section h2 {
    margin-top: 0;
    color: #1e293b;
}

.cjtp-settings-section h3 {
    color: #475569;
    margin-top: 20px;
}

.cjtp-settings-section ol,
.cjtp-settings-section ul {
    line-height: 1.8;
}

.cjtp-settings-section code {
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
}

.cjtp-settings-section strong {
    color: #0f172a;
}
</style>
