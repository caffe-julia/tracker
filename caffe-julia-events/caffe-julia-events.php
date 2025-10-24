<?php
/**
 * Plugin Name: Caffe Julia Events
 * Plugin URI: https://github.com/caffe-julia/tracker
 * Description: Einfaches Event-Tracking mit Excel-Export. Keine externe API, alles in WordPress!
 * Version: 4.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Caffe Julia
 * Author URI: https://caffejulia.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: caffe-julia-events
 * Domain Path: /languages
 */

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// Plugin-Konstanten
define('CJE_VERSION', '4.0.0');
define('CJE_PLUGIN_FILE', __FILE__);
define('CJE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CJE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CJE_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class Caffe_Julia_Events {

    public function __construct() {
        add_action('init', array($this, 'register_event_post_type'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_post_cje_add_event', array($this, 'handle_add_event'));
        add_action('admin_post_cje_export_excel', array($this, 'handle_excel_export'));
    }

    /**
     * Register Custom Post Type für Events
     */
    public function register_event_post_type() {
        register_post_type('cje_event', array(
            'labels' => array(
                'name' => __('Events', 'caffe-julia-events'),
                'singular_name' => __('Event', 'caffe-julia-events'),
            ),
            'public' => false,
            'show_ui' => false,
            'supports' => array('title'),
            'has_archive' => false,
        ));
    }

    /**
     * Admin-Menü hinzufügen
     */
    public function add_admin_menu() {
        add_menu_page(
            'Caffe Julia Events',
            'Caffe Events',
            'manage_options',
            'caffe-julia-events',
            array($this, 'render_events_page'),
            'dashicons-calendar-alt',
            30
        );

        add_submenu_page(
            'caffe-julia-events',
            'Neues Event',
            'Neues Event',
            'manage_options',
            'caffe-julia-add-event',
            array($this, 'render_add_event_page')
        );
    }

    /**
     * CSS und JS laden
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'caffe-julia') === false) {
            return;
        }

        wp_enqueue_style('cje-admin-css', CJE_PLUGIN_URL . 'assets/css/admin.css', array(), CJE_VERSION);
        wp_enqueue_script('cje-admin-js', CJE_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), CJE_VERSION, true);
    }

    /**
     * Events-Liste anzeigen
     */
    public function render_events_page() {
        require_once CJE_PLUGIN_DIR . 'admin/events-list.php';
    }

    /**
     * Neues Event-Formular anzeigen
     */
    public function render_add_event_page() {
        require_once CJE_PLUGIN_DIR . 'admin/event-form.php';
    }

    /**
     * Event speichern
     */
    public function handle_add_event() {
        // Sicherheit prüfen
        if (!isset($_POST['cje_nonce']) || !wp_verify_nonce($_POST['cje_nonce'], 'cje_add_event')) {
            wp_die('Sicherheitsprüfung fehlgeschlagen');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Keine Berechtigung');
        }

        // Event-Daten
        $event_name = sanitize_text_field($_POST['event_name']);
        $event_date = sanitize_text_field($_POST['event_date']);
        $is_all_day = isset($_POST['is_all_day']) ? 1 : 0;
        $start_time = sanitize_text_field($_POST['start_time']);
        $end_time = sanitize_text_field($_POST['end_time']);

        // Kaffeemühlen
        $muehlen = array();
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST["muehle_{$i}_active"])) {
                $muehlen[$i] = array(
                    'start' => intval($_POST["muehle_{$i}_start"]),
                    'ende' => intval($_POST["muehle_{$i}_ende"]),
                );
            }
        }

        // Milch-Verbrauch
        $milch = floatval($_POST['milch_liter']);

        // Event erstellen
        $post_id = wp_insert_post(array(
            'post_type' => 'cje_event',
            'post_title' => $event_name,
            'post_status' => 'publish',
        ));

        if ($post_id) {
            // Meta-Daten speichern
            update_post_meta($post_id, '_cje_event_date', $event_date);
            update_post_meta($post_id, '_cje_is_all_day', $is_all_day);
            update_post_meta($post_id, '_cje_start_time', $start_time);
            update_post_meta($post_id, '_cje_end_time', $end_time);
            update_post_meta($post_id, '_cje_muehlen', $muehlen);
            update_post_meta($post_id, '_cje_milch_liter', $milch);

            // Arbeitszeit berechnen
            if (!$is_all_day && $start_time && $end_time) {
                $start = strtotime($start_time);
                $end = strtotime($end_time);
                $hours = ($end - $start) / 3600;
                update_post_meta($post_id, '_cje_arbeitszeit_stunden', $hours);
            }

            // Kaffees berechnen
            $total_kaffees = 0;
            foreach ($muehlen as $muehle) {
                $total_kaffees += ($muehle['ende'] - $muehle['start']);
            }
            update_post_meta($post_id, '_cje_total_kaffees', $total_kaffees);

            // Erfolgsmeldung
            wp_redirect(add_query_arg(
                array('page' => 'caffe-julia-events', 'message' => 'added'),
                admin_url('admin.php')
            ));
            exit;
        }

        wp_die('Fehler beim Speichern des Events');
    }

    /**
     * Excel-Export
     */
    public function handle_excel_export() {
        if (!current_user_can('manage_options')) {
            wp_die('Keine Berechtigung');
        }

        // Alle Events holen
        $events = get_posts(array(
            'post_type' => 'cje_event',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => '_cje_event_date',
            'order' => 'DESC',
        ));

        // CSV erstellen (einfacher als Excel, funktioniert in Excel)
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=caffe-julia-events-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // UTF-8 BOM für Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header-Zeile
        fputcsv($output, array(
            'Event Name',
            'Datum',
            'Ganztägig',
            'Start-Zeit',
            'End-Zeit',
            'Arbeitszeit (h)',
            'Mühle 1 Start',
            'Mühle 1 Ende',
            'Mühle 1 Differenz',
            'Mühle 2 Start',
            'Mühle 2 Ende',
            'Mühle 2 Differenz',
            'Mühle 3 Start',
            'Mühle 3 Ende',
            'Mühle 3 Differenz',
            'Mühle 4 Start',
            'Mühle 4 Ende',
            'Mühle 4 Differenz',
            'Total Kaffees',
            'Milch (Liter)',
        ), ';');

        // Daten-Zeilen
        foreach ($events as $event) {
            $event_date = get_post_meta($event->ID, '_cje_event_date', true);
            $is_all_day = get_post_meta($event->ID, '_cje_is_all_day', true);
            $start_time = get_post_meta($event->ID, '_cje_start_time', true);
            $end_time = get_post_meta($event->ID, '_cje_end_time', true);
            $arbeitszeit = get_post_meta($event->ID, '_cje_arbeitszeit_stunden', true);
            $muehlen = get_post_meta($event->ID, '_cje_muehlen', true);
            $milch = get_post_meta($event->ID, '_cje_milch_liter', true);
            $total_kaffees = get_post_meta($event->ID, '_cje_total_kaffees', true);

            $row = array(
                $event->post_title,
                $event_date,
                $is_all_day ? 'Ja' : 'Nein',
                $start_time,
                $end_time,
                number_format($arbeitszeit, 2, ',', ''),
            );

            // Mühlen-Daten
            for ($i = 1; $i <= 4; $i++) {
                if (isset($muehlen[$i])) {
                    $row[] = $muehlen[$i]['start'];
                    $row[] = $muehlen[$i]['ende'];
                    $row[] = $muehlen[$i]['ende'] - $muehlen[$i]['start'];
                } else {
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                }
            }

            $row[] = $total_kaffees;
            $row[] = number_format($milch, 2, ',', '');

            fputcsv($output, $row, ';');
        }

        fclose($output);
        exit;
    }
}

// Plugin initialisieren
new Caffe_Julia_Events();

/**
 * Plugin-Aktivierung
 */
function cje_activate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cje_activate');

/**
 * Plugin-Deaktivierung
 */
function cje_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cje_deactivate');
