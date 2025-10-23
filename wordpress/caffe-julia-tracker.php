<?php
/**
 * Plugin Name: Caffe Julia Tracker Integration
 * Plugin URI: https://caffejulia.com
 * Description: Integriert den Caffe Julia Event Tracker in WordPress
 * Version: 2.0.0
 * Author: Caffe Julia
 * Author URI: https://caffejulia.com
 * License: GPL-2.0+
 * Text Domain: caffe-julia-tracker
 */

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// Plugin-Konstanten
define('CJT_VERSION', '2.0.0');
define('CJT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CJT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Tracker-API-URL (anpassen!)
define('CJT_API_URL', 'https://ihr-domain.de/tracker/api');

/**
 * Shortcode: [caffe_julia_tracker]
 * Zeigt den Tracker als Iframe oder embedded
 */
function cjt_tracker_shortcode($atts)
{
    $atts = shortcode_atts([
        'width' => '100%',
        'height' => '800px',
        'mode' => 'iframe' // 'iframe' oder 'embed'
    ], $atts, 'caffe_julia_tracker');

    if ($atts['mode'] === 'iframe') {
        // Iframe-Modus
        return sprintf(
            '<iframe src="%s" width="%s" height="%s" frameborder="0" style="border: none; max-width: 100%%;"></iframe>',
            esc_url(CJT_API_URL . '/../index.html'),
            esc_attr($atts['width']),
            esc_attr($atts['height'])
        );
    } else {
        // Embed-Modus (direktes Einbinden)
        ob_start();
        ?>
        <div id="caffe-julia-tracker-container" style="width: <?php echo esc_attr($atts['width']); ?>;">
            <div id="caffe-julia-tracker-app"></div>
        </div>
        <script>
            // Lade Tracker-App in Container
            (function() {
                const container = document.getElementById('caffe-julia-tracker-app');
                const apiUrl = '<?php echo esc_js(CJT_API_URL); ?>';

                // Hier könnte die Frontend-App geladen werden
                // Falls Sie die App direkt einbetten möchten
            })();
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode('caffe_julia_tracker', 'cjt_tracker_shortcode');

/**
 * Admin-Menü hinzufügen
 */
function cjt_admin_menu()
{
    add_menu_page(
        'Caffe Julia Tracker',
        'Tracker',
        'manage_options',
        'caffe-julia-tracker',
        'cjt_admin_page',
        'dashicons-chart-bar',
        30
    );

    add_submenu_page(
        'caffe-julia-tracker',
        'Einstellungen',
        'Einstellungen',
        'manage_options',
        'caffe-julia-tracker-settings',
        'cjt_settings_page'
    );
}
add_action('admin_menu', 'cjt_admin_menu');

/**
 * Admin-Seite
 */
function cjt_admin_page()
{
    ?>
    <div class="wrap">
        <h1>Caffe Julia Tracker</h1>
        <p>Event-Tracking und Statistiken für Ihr Café</p>

        <div class="card" style="max-width: none;">
            <h2>Tracker einbinden</h2>
            <p>Verwenden Sie folgenden Shortcode, um den Tracker auf einer Seite einzubinden:</p>
            <code>[caffe_julia_tracker]</code>
            <p>Mit optionalen Parametern:</p>
            <code>[caffe_julia_tracker width="100%" height="1000px" mode="iframe"]</code>
        </div>

        <div class="card" style="max-width: none; margin-top: 20px;">
            <h2>Statistiken</h2>
            <p>Statistiken werden über die API abgerufen:</p>
            <p><strong>API-URL:</strong> <?php echo esc_html(CJT_API_URL); ?></p>
            <?php
            // Hole Statistiken von API (falls verfügbar)
            $stats = cjt_get_statistics();
            if ($stats && isset($stats['totals'])) {
                ?>
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Metrik</th>
                        <th>Wert</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total Events</td>
                        <td><?php echo esc_html($stats['totals']['total_events'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <td>Total Kaffees</td>
                        <td><?php echo esc_html($stats['totals']['total_kaffees'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <td>Arbeitsstunden</td>
                        <td><?php echo esc_html($stats['totals']['total_work_hours'] ?? 0); ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<p><em>Keine Statistiken verfügbar. Prüfen Sie die API-Verbindung.</em></p>';
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Einstellungen-Seite
 */
function cjt_settings_page()
{
    // Speichere Einstellungen
    if (isset($_POST['cjt_save_settings']) && check_admin_referer('cjt_settings')) {
        update_option('cjt_api_url', sanitize_text_field($_POST['cjt_api_url']));
        update_option('cjt_api_token', sanitize_text_field($_POST['cjt_api_token']));
        echo '<div class="notice notice-success"><p>Einstellungen gespeichert!</p></div>';
    }

    $api_url = get_option('cjt_api_url', CJT_API_URL);
    $api_token = get_option('cjt_api_token', '');
    ?>
    <div class="wrap">
        <h1>Tracker Einstellungen</h1>
        <form method="post" action="">
            <?php wp_nonce_field('cjt_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="cjt_api_url">API-URL</label></th>
                    <td>
                        <input type="url" id="cjt_api_url" name="cjt_api_url"
                               value="<?php echo esc_attr($api_url); ?>"
                               class="regular-text" required>
                        <p class="description">URL zur Tracker-API (z.B. https://ihr-domain.de/tracker/api)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cjt_api_token">API-Token (optional)</label></th>
                    <td>
                        <input type="password" id="cjt_api_token" name="cjt_api_token"
                               value="<?php echo esc_attr($api_token); ?>"
                               class="regular-text">
                        <p class="description">Session-Token für API-Zugriff</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="cjt_save_settings" class="button button-primary"
                       value="Einstellungen speichern">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Statistiken von API abrufen
 */
function cjt_get_statistics()
{
    $api_url = get_option('cjt_api_url', CJT_API_URL);
    $api_token = get_option('cjt_api_token', '');

    $args = [
        'timeout' => 15,
        'headers' => []
    ];

    if ($api_token) {
        $args['headers']['Authorization'] = 'Bearer ' . $api_token;
    }

    $response = wp_remote_get($api_url . '/statistics', $args);

    if (is_wp_error($response)) {
        return null;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($data && isset($data['success']) && $data['success']) {
        return $data['data'];
    }

    return null;
}

/**
 * Widget: Tracker-Statistiken
 */
class CJT_Statistics_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'cjt_statistics_widget',
            'Caffe Julia Statistiken',
            ['description' => 'Zeigt Tracker-Statistiken']
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $stats = cjt_get_statistics();
        if ($stats && isset($stats['totals'])) {
            echo '<ul style="list-style: none; padding: 0;">';
            echo '<li><strong>Events:</strong> ' . esc_html($stats['totals']['total_events'] ?? 0) . '</li>';
            echo '<li><strong>Kaffees:</strong> ' . esc_html($stats['totals']['total_kaffees'] ?? 0) . '</li>';
            echo '<li><strong>Arbeitsstunden:</strong> ' . esc_html($stats['totals']['total_work_hours'] ?? 0) . '</li>';
            echo '</ul>';
        } else {
            echo '<p><em>Keine Daten verfügbar</em></p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : 'Statistiken';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titel:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

// Widget registrieren
function cjt_register_widgets()
{
    register_widget('CJT_Statistics_Widget');
}
add_action('widgets_init', 'cjt_register_widgets');

/**
 * Plugin-Aktivierung
 */
function cjt_activate()
{
    // Initialisiere Optionen
    add_option('cjt_api_url', CJT_API_URL);
    add_option('cjt_api_token', '');
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cjt_activate');

/**
 * Plugin-Deaktivierung
 */
function cjt_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cjt_deactivate');
