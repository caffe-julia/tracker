<?php
/**
 * Plugin Name: Caffe Julia Tracker
 * Plugin URI: https://github.com/caffe-julia/tracker
 * Description: Professionelles Event-Tracking für Cafés mit MySQL-Backend. Verwalten Sie Events, Kaffeemühlen-Zählerstände, Verbrauch und Arbeitszeiten direkt in WordPress.
 * Version: 2.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Caffe Julia
 * Author URI: https://caffejulia.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: caffe-julia-tracker
 * Domain Path: /languages
 */

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// Plugin-Konstanten
define('CJT_VERSION', '2.0.0');
define('CJT_PLUGIN_FILE', __FILE__);
define('CJT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CJT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CJT_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Lade Plugin-Klasse
require_once CJT_PLUGIN_DIR . 'includes/class-caffe-julia-tracker.php';

/**
 * Initialisiere Plugin
 */
function cjt_init_plugin() {
    $plugin = new Caffe_Julia_Tracker();
    $plugin->run();
}
add_action('plugins_loaded', 'cjt_init_plugin');

/**
 * Plugin-Aktivierung
 */
function cjt_activate() {
    // Default-Optionen setzen
    $default_options = array(
        'api_url' => '',
        'api_token' => '',
        'cache_enabled' => true,
        'cache_duration' => 300, // 5 Minuten
        'show_in_admin' => true,
        'widget_height' => '800px',
        'theme_color' => '#d97706'
    );

    add_option('cjt_options', $default_options);

    // Erstelle Custom Post Type für gecachte Daten
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cjt_activate');

/**
 * Plugin-Deaktivierung
 */
function cjt_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cjt_deactivate');

/**
 * Plugin-Deinstallation
 */
function cjt_uninstall() {
    // Lösche Optionen (nur wenn User explizit deinstalliert)
    delete_option('cjt_options');

    // Lösche Transients
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_cjt_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_cjt_%'");
}
register_uninstall_hook(__FILE__, 'cjt_uninstall');
