<?php
/**
 * Haupt-Plugin-Klasse
 */

if (!defined('ABSPATH')) {
    exit;
}

class Caffe_Julia_Tracker {

    /**
     * Plugin-Optionen
     */
    private $options;

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->options = get_option('cjt_options', array());
    }

    /**
     * Plugin initialisieren
     */
    public function run() {
        // Lade Text-Domain für Übersetzungen
        add_action('init', array($this, 'load_textdomain'));

        // Registriere Assets (CSS/JS)
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // Admin-Menü
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));

        // Shortcodes
        add_shortcode('caffe_julia_tracker', array($this, 'tracker_shortcode'));
        add_shortcode('caffe_julia_stats', array($this, 'stats_shortcode'));

        // Widgets
        add_action('widgets_init', array($this, 'register_widgets'));

        // Gutenberg-Block
        add_action('init', array($this, 'register_gutenberg_block'));

        // AJAX-Endpoints
        add_action('wp_ajax_cjt_get_statistics', array($this, 'ajax_get_statistics'));
        add_action('wp_ajax_nopriv_cjt_get_statistics', array($this, 'ajax_get_statistics'));

        add_action('wp_ajax_cjt_test_connection', array($this, 'ajax_test_connection'));

        // REST API
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    /**
     * Lade Text-Domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'caffe-julia-tracker',
            false,
            dirname(CJT_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Frontend-Assets laden
     */
    public function enqueue_frontend_assets() {
        // CSS
        wp_enqueue_style(
            'cjt-frontend',
            CJT_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            CJT_VERSION
        );

        // JavaScript
        wp_enqueue_script(
            'cjt-frontend',
            CJT_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            CJT_VERSION,
            true
        );

        // Localize Script
        wp_localize_script('cjt-frontend', 'cjtData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cjt_nonce'),
            'apiUrl' => $this->get_option('api_url', ''),
            'themeColor' => $this->get_option('theme_color', '#d97706')
        ));
    }

    /**
     * Admin-Assets laden
     */
    public function enqueue_admin_assets($hook) {
        // Nur auf Plugin-Seiten laden
        if (strpos($hook, 'caffe-julia-tracker') === false) {
            return;
        }

        wp_enqueue_style(
            'cjt-admin',
            CJT_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CJT_VERSION
        );

        wp_enqueue_script(
            'cjt-admin',
            CJT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            CJT_VERSION,
            true
        );

        wp_localize_script('cjt-admin', 'cjtAdminData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cjt_admin_nonce')
        ));
    }

    /**
     * Admin-Menü hinzufügen
     */
    public function add_admin_menu() {
        // Haupt-Menü
        add_menu_page(
            __('Caffe Julia Tracker', 'caffe-julia-tracker'),
            __('Tracker', 'caffe-julia-tracker'),
            'manage_options',
            'caffe-julia-tracker',
            array($this, 'render_dashboard_page'),
            'dashicons-chart-bar',
            30
        );

        // Dashboard
        add_submenu_page(
            'caffe-julia-tracker',
            __('Dashboard', 'caffe-julia-tracker'),
            __('Dashboard', 'caffe-julia-tracker'),
            'manage_options',
            'caffe-julia-tracker',
            array($this, 'render_dashboard_page')
        );

        // Einstellungen
        add_submenu_page(
            'caffe-julia-tracker',
            __('Einstellungen', 'caffe-julia-tracker'),
            __('Einstellungen', 'caffe-julia-tracker'),
            'manage_options',
            'caffe-julia-tracker-settings',
            array($this, 'render_settings_page')
        );

        // Hilfe
        add_submenu_page(
            'caffe-julia-tracker',
            __('Hilfe', 'caffe-julia-tracker'),
            __('Hilfe', 'caffe-julia-tracker'),
            'manage_options',
            'caffe-julia-tracker-help',
            array($this, 'render_help_page')
        );
    }

    /**
     * Einstellungen registrieren
     */
    public function register_settings() {
        register_setting('cjt_options_group', 'cjt_options', array($this, 'sanitize_options'));
    }

    /**
     * Optionen sanitizen
     */
    public function sanitize_options($input) {
        $sanitized = array();

        if (isset($input['api_url'])) {
            $sanitized['api_url'] = esc_url_raw($input['api_url']);
        }

        if (isset($input['api_token'])) {
            $sanitized['api_token'] = sanitize_text_field($input['api_token']);
        }

        $sanitized['cache_enabled'] = isset($input['cache_enabled']);
        $sanitized['cache_duration'] = isset($input['cache_duration']) ? absint($input['cache_duration']) : 300;
        $sanitized['show_in_admin'] = isset($input['show_in_admin']);
        $sanitized['widget_height'] = isset($input['widget_height']) ? sanitize_text_field($input['widget_height']) : '800px';
        $sanitized['theme_color'] = isset($input['theme_color']) ? sanitize_hex_color($input['theme_color']) : '#d97706';

        return $sanitized;
    }

    /**
     * Dashboard-Seite rendern
     */
    public function render_dashboard_page() {
        require_once CJT_PLUGIN_DIR . 'includes/admin/dashboard.php';
    }

    /**
     * Einstellungs-Seite rendern
     */
    public function render_settings_page() {
        require_once CJT_PLUGIN_DIR . 'includes/admin/settings.php';
    }

    /**
     * Hilfe-Seite rendern
     */
    public function render_help_page() {
        require_once CJT_PLUGIN_DIR . 'includes/admin/help.php';
    }

    /**
     * Tracker-Shortcode
     */
    public function tracker_shortcode($atts) {
        $atts = shortcode_atts(array(
            'height' => $this->get_option('widget_height', '800px'),
            'show_stats' => 'true',
            'theme' => 'light'
        ), $atts, 'caffe_julia_tracker');

        ob_start();
        require CJT_PLUGIN_DIR . 'includes/templates/tracker-widget.php';
        return ob_get_clean();
    }

    /**
     * Statistik-Shortcode
     */
    public function stats_shortcode($atts) {
        $atts = shortcode_atts(array(
            'period' => '30',
            'layout' => 'grid'
        ), $atts, 'caffe_julia_stats');

        ob_start();
        require CJT_PLUGIN_DIR . 'includes/templates/stats-widget.php';
        return ob_get_clean();
    }

    /**
     * Widgets registrieren
     */
    public function register_widgets() {
        require_once CJT_PLUGIN_DIR . 'includes/widgets/class-statistics-widget.php';
        register_widget('CJT_Statistics_Widget');
    }

    /**
     * Gutenberg-Block registrieren
     */
    public function register_gutenberg_block() {
        if (!function_exists('register_block_type')) {
            return;
        }

        wp_register_script(
            'cjt-block',
            CJT_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            CJT_VERSION
        );

        register_block_type('caffe-julia-tracker/tracker', array(
            'editor_script' => 'cjt-block',
            'render_callback' => array($this, 'render_tracker_block')
        ));
    }

    /**
     * Tracker-Block rendern
     */
    public function render_tracker_block($attributes) {
        return $this->tracker_shortcode($attributes);
    }

    /**
     * AJAX: Statistiken abrufen
     */
    public function ajax_get_statistics() {
        check_ajax_referer('cjt_nonce', 'nonce');

        $stats = $this->get_statistics();

        if ($stats) {
            wp_send_json_success($stats);
        } else {
            wp_send_json_error(__('Statistiken konnten nicht geladen werden.', 'caffe-julia-tracker'));
        }
    }

    /**
     * AJAX: Verbindung testen
     */
    public function ajax_test_connection() {
        check_ajax_referer('cjt_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Keine Berechtigung.', 'caffe-julia-tracker'));
        }

        $api_url = $this->get_option('api_url', '');

        if (empty($api_url)) {
            wp_send_json_error(__('API-URL ist nicht konfiguriert.', 'caffe-julia-tracker'));
        }

        $response = wp_remote_get($api_url . '/health', array(
            'timeout' => 10
        ));

        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data && isset($data['success'])) {
            wp_send_json_success(__('Verbindung erfolgreich!', 'caffe-julia-tracker'));
        } else {
            wp_send_json_error(__('API hat ungültige Daten zurückgegeben.', 'caffe-julia-tracker'));
        }
    }

    /**
     * REST API Routes registrieren
     */
    public function register_rest_routes() {
        register_rest_route('caffe-julia-tracker/v1', '/statistics', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_statistics'),
            'permission_callback' => function() {
                return current_user_can('read');
            }
        ));
    }

    /**
     * REST: Statistiken abrufen
     */
    public function rest_get_statistics($request) {
        $stats = $this->get_statistics();

        if ($stats) {
            return new WP_REST_Response($stats, 200);
        }

        return new WP_Error('no_stats', __('Statistiken nicht verfügbar.', 'caffe-julia-tracker'), array('status' => 500));
    }

    /**
     * Statistiken von API abrufen (mit Caching)
     */
    private function get_statistics() {
        $api_url = $this->get_option('api_url', '');
        $api_token = $this->get_option('api_token', '');

        if (empty($api_url)) {
            return null;
        }

        // Cache prüfen
        $cache_enabled = $this->get_option('cache_enabled', true);
        $cache_key = 'cjt_stats_' . md5($api_url);

        if ($cache_enabled) {
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                return $cached;
            }
        }

        // API-Anfrage
        $args = array(
            'timeout' => 15,
            'headers' => array()
        );

        if (!empty($api_token)) {
            $args['headers']['Authorization'] = 'Bearer ' . $api_token;
        }

        $response = wp_remote_get($api_url . '/statistics', $args);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data && isset($data['success']) && $data['success']) {
            // Cache speichern
            if ($cache_enabled) {
                $cache_duration = $this->get_option('cache_duration', 300);
                set_transient($cache_key, $data['data'], $cache_duration);
            }

            return $data['data'];
        }

        return null;
    }

    /**
     * Option abrufen
     */
    private function get_option($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
}
