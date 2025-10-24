<?php
/**
 * Admin Einstellungs-Seite
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('cjt_options', array());

// Speichere Einstellungen
if (isset($_POST['cjt_save_settings']) && check_admin_referer('cjt_settings_nonce')) {
    $options['api_url'] = isset($_POST['api_url']) ? esc_url_raw($_POST['api_url']) : '';
    $options['api_token'] = isset($_POST['api_token']) ? sanitize_text_field($_POST['api_token']) : '';
    $options['cache_enabled'] = isset($_POST['cache_enabled']);
    $options['cache_duration'] = isset($_POST['cache_duration']) ? absint($_POST['cache_duration']) : 300;
    $options['show_in_admin'] = isset($_POST['show_in_admin']);
    $options['widget_height'] = isset($_POST['widget_height']) ? sanitize_text_field($_POST['widget_height']) : '800px';
    $options['theme_color'] = isset($_POST['theme_color']) ? sanitize_hex_color($_POST['theme_color']) : '#d97706';

    update_option('cjt_options', $options);

    echo '<div class="notice notice-success is-dismissible"><p>' . __('Einstellungen gespeichert!', 'caffe-julia-tracker') . '</p></div>';
}
?>

<div class="wrap cjt-admin-page">
    <h1><?php _e('Caffe Julia Tracker - Einstellungen', 'caffe-julia-tracker'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('cjt_settings_nonce'); ?>

        <div class="cjt-settings-grid">
            <!-- API-Konfiguration -->
            <div class="cjt-card">
                <h2><?php _e('🔌 API-Konfiguration', 'caffe-julia-tracker'); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="api_url"><?php _e('API-URL', 'caffe-julia-tracker'); ?> *</label>
                        </th>
                        <td>
                            <input type="url"
                                   id="api_url"
                                   name="api_url"
                                   value="<?php echo esc_attr($options['api_url'] ?? ''); ?>"
                                   class="regular-text"
                                   placeholder="https://ihre-domain.de/tracker/api"
                                   required>
                            <p class="description">
                                <?php _e('URL zur Tracker-API (z.B. https://ihre-domain.de/tracker/api)', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="api_token"><?php _e('API-Token', 'caffe-julia-tracker'); ?></label>
                        </th>
                        <td>
                            <input type="password"
                                   id="api_token"
                                   name="api_token"
                                   value="<?php echo esc_attr($options['api_token'] ?? ''); ?>"
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Optional: Session-Token für authentifizierte API-Anfragen', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Cache-Einstellungen -->
            <div class="cjt-card">
                <h2><?php _e('⚡ Cache-Einstellungen', 'caffe-julia-tracker'); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Cache aktivieren', 'caffe-julia-tracker'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox"
                                       name="cache_enabled"
                                       <?php checked(isset($options['cache_enabled']) && $options['cache_enabled']); ?>>
                                <?php _e('API-Antworten zwischenspeichern', 'caffe-julia-tracker'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Reduziert API-Anfragen und verbessert Performance', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cache_duration"><?php _e('Cache-Dauer', 'caffe-julia-tracker'); ?></label>
                        </th>
                        <td>
                            <input type="number"
                                   id="cache_duration"
                                   name="cache_duration"
                                   value="<?php echo esc_attr($options['cache_duration'] ?? 300); ?>"
                                   min="60"
                                   max="3600"
                                   class="small-text">
                            <?php _e('Sekunden', 'caffe-julia-tracker'); ?>
                            <p class="description">
                                <?php _e('Wie lange sollen Daten gecacht werden? (60-3600 Sekunden)', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Anzeige-Einstellungen -->
            <div class="cjt-card">
                <h2><?php _e('🎨 Anzeige-Einstellungen', 'caffe-julia-tracker'); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="widget_height"><?php _e('Widget-Höhe', 'caffe-julia-tracker'); ?></label>
                        </th>
                        <td>
                            <input type="text"
                                   id="widget_height"
                                   name="widget_height"
                                   value="<?php echo esc_attr($options['widget_height'] ?? '800px'); ?>"
                                   class="small-text">
                            <p class="description">
                                <?php _e('Standard-Höhe für Tracker-Widget (z.B. 800px, 100vh)', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="theme_color"><?php _e('Theme-Farbe', 'caffe-julia-tracker'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="theme_color"
                                   name="theme_color"
                                   value="<?php echo esc_attr($options['theme_color'] ?? '#d97706'); ?>">
                            <p class="description">
                                <?php _e('Primärfarbe für das Plugin-Frontend', 'caffe-julia-tracker'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('In Admin anzeigen', 'caffe-julia-tracker'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox"
                                       name="show_in_admin"
                                       <?php checked(isset($options['show_in_admin']) && $options['show_in_admin']); ?>>
                                <?php _e('Statistiken im WordPress-Dashboard anzeigen', 'caffe-julia-tracker'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <p class="submit">
            <input type="submit"
                   name="cjt_save_settings"
                   class="button button-primary button-large"
                   value="<?php _e('Einstellungen speichern', 'caffe-julia-tracker'); ?>">
        </p>
    </form>
</div>
