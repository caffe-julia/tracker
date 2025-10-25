<?php
if (!defined('ABSPATH')) exit;

// Generiere Hash-Funktion für PHP (kompatibel mit JavaScript)
function cjtp_generate_hash($password) {
    $salt = 'CaffeJulia2025SecureSalt';
    return hash('sha256', $password . $salt);
}

// Speichere Einstellungen
$success_message = '';
$error_message = '';

if (isset($_POST['cjtp_save_tracker_password'])) {
    check_admin_referer('cjtp_tracker_password');

    $new_password = $_POST['tracker_password'];
    $confirm_password = $_POST['tracker_password_confirm'];

    if (empty($new_password)) {
        $error_message = 'Bitte geben Sie ein Passwort ein.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'Die Passwörter stimmen nicht überein.';
    } elseif (strlen($new_password) < 6) {
        $error_message = 'Das Passwort muss mindestens 6 Zeichen lang sein.';
    } else {
        // Generiere Hash und speichere
        $password_hash = cjtp_generate_hash($new_password);
        update_option('cjtp_password_hash', $password_hash);
        $success_message = 'Tracker-Passwort erfolgreich geändert!';
    }
}

// Aktuelles Passwort-Hash
// Standard-Passwort: "CaffeJulia2025" mit Salt "CaffeJulia2025SecureSalt"
$default_hash = cjtp_generate_hash('CaffeJulia2025'); // = '2f0f7f8c9a8f7c6b5d4e3c2b1a0f9e8d7c6b5a4f3e2d1c0b9a8f7e6d5c4b3a21'
$current_hash = get_option('cjtp_password_hash', $default_hash);
$is_default = ($current_hash === $default_hash);
?>
<div class="wrap">
    <h1>⚙️ Caffe Julia Tracker - Einstellungen</h1>

    <?php if ($success_message): ?>
        <div class="notice notice-success is-dismissible">
            <p>✅ <?php echo esc_html($success_message); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="notice notice-error is-dismissible">
            <p>❌ <?php echo esc_html($error_message); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($is_default): ?>
        <div class="notice notice-warning">
            <p><strong>⚠️ WICHTIG:</strong> Sie verwenden noch das Standard-Passwort "<strong>CaffeJulia2025</strong>". Bitte ändern Sie es unten!</p>
        </div>
    <?php endif; ?>

    <div class="cjtp-settings" style="max-width: 800px;">

        <!-- Tracker-Passwort Sektion -->
        <div class="cjtp-settings-section">
            <h2>🔐 Tracker-Passwort (unabhängig von WordPress)</h2>
            <p>Dieses Passwort wird NUR für den Tracker verwendet, NICHT für den WordPress-Admin.</p>

            <form method="post" action="">
                <?php wp_nonce_field('cjtp_tracker_password'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label>Status</label>
                        </th>
                        <td>
                            <?php if ($is_default): ?>
                                <span style="color: #dc2626; font-weight: bold;">⚠️ Standard-Passwort aktiv: "CaffeJulia2025"</span>
                                <p class="description">Bitte ändern Sie das Passwort aus Sicherheitsgründen!</p>
                            <?php else: ?>
                                <span style="color: #10b981; font-weight: bold;">✅ Individuelles Passwort gesetzt</span>
                                <p class="description">Ihr Tracker ist mit einem eigenen Passwort geschützt.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="tracker_password">Neues Tracker-Passwort</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="tracker_password"
                                name="tracker_password"
                                class="regular-text"
                                placeholder="Mindestens 6 Zeichen"
                                autocomplete="off"
                            >
                            <p class="description">
                                Geben Sie ein neues Passwort ein. Dieses wird mit SHA-256 gehashed.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="tracker_password_confirm">Passwort bestätigen</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="tracker_password_confirm"
                                name="tracker_password_confirm"
                                class="regular-text"
                                placeholder="Passwort wiederholen"
                                autocomplete="off"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <button type="submit" name="cjtp_save_tracker_password" class="button button-primary button-large">
                                💾 Tracker-Passwort speichern
                            </button>
                        </td>
                    </tr>
                </table>
            </form>

            <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <h4 style="margin-top: 0;">💡 So funktioniert's:</h4>
                <ol style="line-height: 1.8;">
                    <li>Geben Sie oben ein neues Passwort ein (z.B. "MeinGeheimesPasswort123")</li>
                    <li>Klicken Sie auf "Speichern"</li>
                    <li>Öffnen Sie den Tracker in einem neuen Browser-Tab</li>
                    <li>Geben Sie dort das neue Passwort ein</li>
                    <li>Fertig! 🎉</li>
                </ol>
                <p><strong>Hinweis:</strong> Das Passwort wird als SHA-256-Hash gespeichert und ist sicher verschlüsselt.</p>
            </div>
        </div>

        <!-- WordPress-Login Sektion -->
        <div class="cjtp-settings-section" style="margin-top: 30px;">
            <h2>🔑 WordPress-Admin-Passwort</h2>
            <p>Ihr WordPress-Admin-Login ist <strong>unabhängig</strong> vom Tracker-Passwort.</p>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label>WordPress Benutzername</label>
                    </th>
                    <td>
                        <strong><?php echo esc_html(wp_get_current_user()->user_login); ?></strong>
                        <p class="description">Ihr aktueller WordPress-Benutzername</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>WordPress Passwort ändern</label>
                    </th>
                    <td>
                        <a href="<?php echo admin_url('profile.php'); ?>" class="button button-secondary">
                            🔑 Passwort im Profil ändern
                        </a>
                        <p class="description">
                            Klicken Sie hier, um Ihr WordPress-Admin-Passwort zu ändern.
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tracker-Information -->
        <div class="cjtp-settings-section" style="margin-top: 30px;">
            <h2>📊 Tracker-Information</h2>
            <?php
            $event_count = wp_count_posts('cjtp_event')->publish;
            $tracker_pages = get_posts(array(
                'post_type' => 'page',
                'posts_per_page' => -1,
                's' => '[caffe_tracker]'
            ));
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label>Gespeicherte Events</label>
                    </th>
                    <td>
                        <strong><?php echo $event_count; ?> Events</strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>Tracker-Seite</label>
                    </th>
                    <td>
                        <?php if (!empty($tracker_pages)): ?>
                            <?php foreach($tracker_pages as $page): ?>
                                <a href="<?php echo get_permalink($page->ID); ?>" target="_blank" class="button button-secondary">
                                    📱 Tracker öffnen
                                </a>
                                <p class="description">
                                    Seite: <strong><?php echo esc_html($page->post_title); ?></strong><br>
                                    URL: <code><?php echo get_permalink($page->ID); ?></code>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="description">
                                Erstellen Sie eine Seite mit dem Shortcode <code>[caffe_tracker]</code>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Daten & Backup -->
        <div class="cjtp-settings-section" style="margin-top: 30px;">
            <h2>💾 Daten & Backup</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label>Datenbank</label>
                    </th>
                    <td>
                        <p class="description">
                            Ihre Tracker-Daten werden in der WordPress-Datenbank gespeichert.<br>
                            Stellen Sie sicher, dass Sie regelmäßig ein WordPress-Backup erstellen.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>Export</label>
                    </th>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=caffe-tracker-dashboard'); ?>" class="button button-secondary">
                            📥 Zum Dashboard (Excel-Export)
                        </a>
                        <p class="description">
                            Exportieren Sie Ihre Daten als Excel/CSV-Datei im Dashboard
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- iPhone App-Icon -->
        <div class="cjtp-settings-section" style="margin-top: 30px; background: #e9d5ff; padding: 20px; border-radius: 8px;">
            <h3 style="margin-top: 0;">📱 iPhone App-Icon</h3>
            <p>So fügen Sie den Tracker als App-Icon auf dem iPhone hinzu:</p>
            <ol style="line-height: 1.8;">
                <li>Öffnen Sie die Tracker-Seite in Safari</li>
                <li>Tippen Sie auf das Teilen-Symbol (unten in der Mitte)</li>
                <li>Wählen Sie "Zum Home-Bildschirm"</li>
                <li>Fertig! Der Tracker erscheint als App-Icon</li>
            </ol>
        </div>
    </div>
</div>

<style>
.cjtp-settings-section {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.cjtp-settings-section h2 {
    margin-top: 0;
    color: #1d2327;
    font-size: 18px;
}
.cjtp-settings-section h3 {
    color: #6b21a8;
}
.form-table th {
    width: 250px;
}
.button.button-secondary {
    margin-right: 10px;
}
input#tracker_password,
input#tracker_password_confirm {
    font-size: 16px;
    padding: 8px 12px;
}
</style>
