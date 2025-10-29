<?php
/**
 * Plugin Name: Caffe Julia Tracker Pro
 * Plugin URI: https://github.com/caffe-julia/tracker
 * Description: Professioneller Event-Tracker mit Mühlen, Getränken, Arbeitszeit - GENAU wie Ihr Original! 100% in WordPress, iPhone-optimiert. Version 7.0: WordPress-Authentifizierung!
 * Version: 7.4.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Caffe Julia
 * Author URI: https://caffejulia.com
 * License: GPL-2.0+
 * Text Domain: caffe-julia-tracker-pro
 */

if (!defined('ABSPATH')) exit;

define('CJTP_VERSION', '7.4.0');
define('CJTP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CJTP_PLUGIN_URL', plugin_dir_url(__FILE__));

class Caffe_Julia_Tracker_Pro {

    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_shortcode('caffe_tracker', array($this, 'render_tracker'));

        // AJAX Actions
        add_action('wp_ajax_cjtp_export_csv', array($this, 'export_csv'));
        add_action('wp_ajax_cjtp_get_stats', array($this, 'get_statistics'));
        add_action('wp_ajax_cjtp_delete_event', array($this, 'ajax_delete_event'));

        // Logout Redirect (hohe Priorität, um andere Plugins zu überschreiben)
        add_filter('logout_redirect', array($this, 'tracker_logout_redirect'), 999, 3);
    }

    public function ajax_delete_event() {
        check_ajax_referer('wp_rest', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Keine Berechtigung'), 403);
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        if (!$post_id) {
            wp_send_json_error(array('message' => 'Keine Post ID angegeben'), 400);
        }

        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'cjtp_event') {
            wp_send_json_error(array('message' => 'Event nicht gefunden'), 404);
        }

        $result = wp_delete_post($post_id, true);

        if ($result) {
            wp_send_json_success(array('message' => 'Event gelöscht'));
        } else {
            wp_send_json_error(array('message' => 'Löschen fehlgeschlagen'), 500);
        }
    }

    public function tracker_logout_redirect($redirect_to, $requested_redirect_to, $user) {
        // Nach Logout immer zu caffejulia.ch (unabhängig von anderen Redirects)
        return 'https://www.caffejulia.ch';
    }


    public function register_post_type() {
        register_post_type('cjtp_event', array(
            'labels' => array(
                'name' => 'Tracker Events',
                'singular_name' => 'Event',
            ),
            'public' => false,
            'show_ui' => false,
            'supports' => array('title'),
            'capability_type' => 'post',
        ));
    }

    public function enqueue_assets() {
        if (is_page() && has_shortcode(get_post()->post_content, 'caffe_tracker')) {
            wp_enqueue_style('cjtp-style', CJTP_PLUGIN_URL . 'assets/css/tracker.css', array(), CJTP_VERSION);
            wp_enqueue_script('cjtp-script', CJTP_PLUGIN_URL . 'assets/js/tracker.js', array(), CJTP_VERSION, true);

            wp_localize_script('cjtp-script', 'cjtpData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'restUrl' => rest_url('cjtp/v1/'),
                'nonce' => wp_create_nonce('wp_rest'),
            ));
        }
    }

    public function add_admin_menu() {
        add_menu_page(
            'Caffe Tracker',
            'Caffe Tracker',
            'manage_options',
            'caffe-tracker-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-chart-bar',
            30
        );

        add_submenu_page(
            'caffe-tracker-dashboard',
            'Einstellungen',
            'Einstellungen',
            'manage_options',
            'caffe-tracker-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_rest_routes() {
        // Events abrufen
        register_rest_route('cjtp/v1', '/events', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_events'),
            'permission_callback' => array($this, 'check_tracker_permission'),
        ));

        // Event erstellen
        register_rest_route('cjtp/v1', '/events', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_event'),
            'permission_callback' => array($this, 'check_tracker_permission'),
        ));

        // Event aktualisieren
        register_rest_route('cjtp/v1', '/events/(?P<id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array($this, 'update_event'),
            'permission_callback' => array($this, 'check_tracker_permission'),
        ));

        // Event löschen
        register_rest_route('cjtp/v1', '/events/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'delete_event'),
            'permission_callback' => array($this, 'check_tracker_permission'),
        ));
    }

    public function check_tracker_permission() {
        // Erlaube Zugriff für ALLE eingeloggten WordPress-Benutzer
        // Die Seite selbst sollte mit WordPress "Privat" oder "Password Protected" Plugin geschützt werden
        return is_user_logged_in();
    }


    public function get_events($request) {
        $args = array(
            'post_type' => 'cjtp_event',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => '_cjtp_date',
            'order' => 'DESC',
        );

        $posts = get_posts($args);
        $events = array();

        foreach ($posts as $post) {
            $events[] = $this->format_event($post);
        }

        return rest_ensure_response($events);
    }

    public function create_event($request) {
        $data = $request->get_json_params();

        $post_id = wp_insert_post(array(
            'post_type' => 'cjtp_event',
            'post_title' => sanitize_text_field($data['name']),
            'post_status' => 'publish',
        ));

        if (is_wp_error($post_id)) {
            return new WP_Error('create_failed', 'Event konnte nicht erstellt werden', array('status' => 500));
        }

        $this->save_event_meta($post_id, $data);

        return rest_ensure_response(array(
            'id' => $post_id,
            'message' => 'Event erstellt',
        ));
    }

    public function update_event($request) {
        $id = $request->get_param('id');
        $data = $request->get_json_params();

        wp_update_post(array(
            'ID' => $id,
            'post_title' => sanitize_text_field($data['name']),
        ));

        $this->save_event_meta($id, $data);

        $post = get_post($id);
        return rest_ensure_response($this->format_event($post));
    }

    public function delete_event($request) {
        $id = $request->get_param('id');
        wp_delete_post($id, true);
        return rest_ensure_response(array('message' => 'Event gelöscht'));
    }

    private function save_event_meta($post_id, $data) {
        $fields = array(
            'date', 'ganztaegig', 'anzahlMuehlen', 'mehrtagig',
            'isPartOfMultiDay', 'multiDayIndex', 'multiDayTotal',
            'workStartTime', 'workEndTime', 'workBreakMinutes', 'workHours',
            'muehlen', 'milch', 'hafermilch',
            'ausgabeMatcha', 'ausgabeSchokolade', 'ausgabeTee',
            'mitteilung'
        );

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                update_post_meta($post_id, '_cjtp_' . $field, $data[$field]);
            }
        }
    }

    private function format_event($post) {
        return array(
            'id' => $post->ID,
            'name' => $post->post_title,
            'date' => get_post_meta($post->ID, '_cjtp_date', true),
            'ganztaegig' => (bool) get_post_meta($post->ID, '_cjtp_ganztaegig', true),
            'anzahlMuehlen' => (int) get_post_meta($post->ID, '_cjtp_anzahlMuehlen', true) ?: 1,
            'mehrtagig' => (bool) get_post_meta($post->ID, '_cjtp_mehrtagig', true),
            'isPartOfMultiDay' => (bool) get_post_meta($post->ID, '_cjtp_isPartOfMultiDay', true),
            'multiDayIndex' => (int) get_post_meta($post->ID, '_cjtp_multiDayIndex', true),
            'multiDayTotal' => (int) get_post_meta($post->ID, '_cjtp_multiDayTotal', true),
            'workStartTime' => get_post_meta($post->ID, '_cjtp_workStartTime', true),
            'workEndTime' => get_post_meta($post->ID, '_cjtp_workEndTime', true),
            'workBreakMinutes' => (int) get_post_meta($post->ID, '_cjtp_workBreakMinutes', true),
            'workHours' => (float) get_post_meta($post->ID, '_cjtp_workHours', true),
            'muehlen' => get_post_meta($post->ID, '_cjtp_muehlen', true) ?: array(),
            'milch' => (int) get_post_meta($post->ID, '_cjtp_milch', true),
            'hafermilch' => (int) get_post_meta($post->ID, '_cjtp_hafermilch', true),
            'ausgabeMatcha' => (int) get_post_meta($post->ID, '_cjtp_ausgabeMatcha', true),
            'ausgabeSchokolade' => (int) get_post_meta($post->ID, '_cjtp_ausgabeSchokolade', true),
            'ausgabeTee' => (int) get_post_meta($post->ID, '_cjtp_ausgabeTee', true),
            'mitteilung' => get_post_meta($post->ID, '_cjtp_mitteilung', true),
        );
    }

    public function render_tracker($atts) {
        // Prüfe ob Benutzer eingeloggt ist
        if (!is_user_logged_in()) {
            // Nicht eingeloggt -> Weiterleitung zur Login-Seite
            $login_url = wp_login_url(get_permalink());
            wp_redirect($login_url);
            exit;
        }

        ob_start();
        include CJTP_PLUGIN_DIR . 'templates/tracker.php';
        return ob_get_clean();
    }

    public function render_dashboard() {
        include CJTP_PLUGIN_DIR . 'templates/dashboard.php';
    }

    public function render_settings_page() {
        include CJTP_PLUGIN_DIR . 'templates/settings.php';
    }

    public function export_csv() {
        check_ajax_referer('wp_rest', 'nonce');

        $events = get_posts(array(
            'post_type' => 'cjtp_event',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => '_cjtp_date',
            'order' => 'DESC',
        ));

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=caffe-julia-events-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, array(
            'Event Name', 'Datum', 'Ganztägig', 'Start', 'Ende', 'Pause (min)',
            'Arbeitsstunden', 'Anzahl Mühlen', 'Mühlen-Details',
            'Total Kaffees', 'Milch (L)', 'Hafermilch (L)',
            'Matcha', 'Schokolade', 'Tee', 'Mitteilung'
        ), ';');

        foreach ($events as $post) {
            $event = $this->format_event($post);

            $totalKaffees = 0;
            $muehlenDetails = '';
            if (!empty($event['muehlen'])) {
                foreach ($event['muehlen'] as $muehle) {
                    $doppel = ($muehle['doppelBezug']['ende'] ?? 0) - ($muehle['doppelBezug']['start'] ?? 0);
                    $einzel = ($muehle['einzelBezug']['ende'] ?? 0) - ($muehle['einzelBezug']['start'] ?? 0);
                    $totalKaffees += ($doppel * 2) + $einzel;
                    $muehlenDetails .= sprintf('%s: D=%d E=%d; ', $muehle['name'], $doppel, $einzel);
                }
            }

            fputcsv($output, array(
                $event['name'],
                $event['date'],
                $event['ganztaegig'] ? 'Ja' : 'Nein',
                $event['workStartTime'],
                $event['workEndTime'],
                $event['workBreakMinutes'],
                number_format($event['workHours'], 2, ',', ''),
                $event['anzahlMuehlen'],
                trim($muehlenDetails),
                $totalKaffees,
                $event['milch'],
                $event['hafermilch'],
                $event['ausgabeMatcha'],
                $event['ausgabeSchokolade'],
                $event['ausgabeTee'],
                $event['mitteilung'],
            ), ';');
        }

        fclose($output);
        exit;
    }

    public function get_statistics() {
        check_ajax_referer('wp_rest', 'nonce');

        $events = get_posts(array(
            'post_type' => 'cjtp_event',
            'posts_per_page' => -1,
        ));

        $stats = array(
            'totalEvents' => count($events),
            'totalKaffees' => 0,
            'totalMilch' => 0,
            'totalGetraenke' => 0,
            'totalArbeitsstunden' => 0,
        );

        foreach ($events as $post) {
            $event = $this->format_event($post);

            if (!empty($event['muehlen'])) {
                foreach ($event['muehlen'] as $muehle) {
                    $doppel = ($muehle['doppelBezug']['ende'] ?? 0) - ($muehle['doppelBezug']['start'] ?? 0);
                    $einzel = ($muehle['einzelBezug']['ende'] ?? 0) - ($muehle['einzelBezug']['start'] ?? 0);
                    $stats['totalKaffees'] += ($doppel * 2) + $einzel;
                }
            }

            $stats['totalMilch'] += $event['milch'] + $event['hafermilch'];
            $stats['totalGetraenke'] += $event['ausgabeMatcha'] + $event['ausgabeSchokolade'] + $event['ausgabeTee'];
            $stats['totalArbeitsstunden'] += $event['workHours'];
        }

        wp_send_json_success($stats);
    }
}

new Caffe_Julia_Tracker_Pro();

register_activation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
