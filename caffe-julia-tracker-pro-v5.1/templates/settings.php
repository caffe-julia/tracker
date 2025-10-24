<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1>⚙️ Caffe Julia Tracker - Einstellungen</h1>

    <div class="cjtp-settings" style="max-width: 800px;">
        <form method="post" action="">
            <?php wp_nonce_field('cjtp_settings'); ?>

            <div class="cjtp-settings-section">
                <h2>🔐 Benutzer & Passwort</h2>
                <p>Ändern Sie Ihr WordPress-Passwort, um den Zugang zum Tracker zu sichern.</p>

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
                                Klicken Sie hier, um Ihr WordPress-Passwort zu ändern.<br>
                                Dieses Passwort wird auch für den Zugang zum Tracker verwendet.
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

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
        </form>
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
</style>
