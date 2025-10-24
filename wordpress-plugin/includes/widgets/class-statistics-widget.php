<?php
/**
 * Statistik Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class CJT_Statistics_Widget extends WP_Widget {

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct(
            'cjt_statistics_widget',
            __('Caffe Julia Statistiken', 'caffe-julia-tracker'),
            array(
                'description' => __('Zeigt Tracker-Statistiken in Ihrer Sidebar', 'caffe-julia-tracker'),
                'classname' => 'cjt-statistics-widget'
            )
        );
    }

    /**
     * Frontend-Ausgabe
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Statistiken', 'caffe-julia-tracker');
        $period = !empty($instance['period']) ? intval($instance['period']) : 30;
        $show_icon = isset($instance['show_icon']) ? (bool)$instance['show_icon'] : true;

        echo $args['before_widget'];

        if (!empty($title)) {
            echo $args['before_title'];
            if ($show_icon) {
                echo '📊 ';
            }
            echo apply_filters('widget_title', $title);
            echo $args['after_title'];
        }

        // Hole Statistiken
        $stats = $this->get_statistics($period);

        if ($stats && isset($stats['totals'])) {
            $this->display_statistics($stats['totals'], $period);
        } else {
            echo '<p class="cjt-widget-error">' . __('Statistiken nicht verfügbar', 'caffe-julia-tracker') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Backend-Formular
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Statistiken', 'caffe-julia-tracker');
        $period = isset($instance['period']) ? $instance['period'] : 30;
        $show_icon = isset($instance['show_icon']) ? (bool)$instance['show_icon'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Titel:', 'caffe-julia-tracker'); ?>
            </label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('period'); ?>">
                <?php _e('Zeitraum (Tage):', 'caffe-julia-tracker'); ?>
            </label>
            <input class="tiny-text"
                   id="<?php echo $this->get_field_id('period'); ?>"
                   name="<?php echo $this->get_field_name('period'); ?>"
                   type="number"
                   min="1"
                   max="365"
                   value="<?php echo esc_attr($period); ?>">
        </p>

        <p>
            <input class="checkbox"
                   type="checkbox"
                   <?php checked($show_icon); ?>
                   id="<?php echo $this->get_field_id('show_icon'); ?>"
                   name="<?php echo $this->get_field_name('show_icon'); ?>">
            <label for="<?php echo $this->get_field_id('show_icon'); ?>">
                <?php _e('Icon im Titel anzeigen', 'caffe-julia-tracker'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Widget-Einstellungen aktualisieren
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['period'] = (!empty($new_instance['period'])) ? absint($new_instance['period']) : 30;
        $instance['show_icon'] = isset($new_instance['show_icon']);
        return $instance;
    }

    /**
     * Statistiken abrufen
     */
    private function get_statistics($period = 30) {
        $options = get_option('cjt_options', array());
        $api_url = isset($options['api_url']) ? $options['api_url'] : '';
        $api_token = isset($options['api_token']) ? $options['api_token'] : '';

        if (empty($api_url)) {
            return null;
        }

        // Cache-Key
        $cache_key = 'cjt_widget_stats_' . $period;
        $cache_enabled = isset($options['cache_enabled']) && $options['cache_enabled'];

        if ($cache_enabled) {
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                return $cached;
            }
        }

        // API-Anfrage
        $start_date = date('Y-m-d', strtotime("-{$period} days"));
        $end_date = date('Y-m-d');

        $args = array(
            'timeout' => 10,
            'headers' => array()
        );

        if (!empty($api_token)) {
            $args['headers']['Authorization'] = 'Bearer ' . $api_token;
        }

        $url = add_query_arg(array(
            'start_date' => $start_date,
            'end_date' => $end_date
        ), $api_url . '/statistics');

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data && isset($data['success']) && $data['success']) {
            // Cache speichern
            if ($cache_enabled) {
                $cache_duration = isset($options['cache_duration']) ? $options['cache_duration'] : 300;
                set_transient($cache_key, $data['data'], $cache_duration);
            }
            return $data['data'];
        }

        return null;
    }

    /**
     * Statistiken anzeigen
     */
    private function display_statistics($totals, $period) {
        ?>
        <div class="cjt-widget-stats">
            <ul class="cjt-widget-stats-list">
                <li class="cjt-widget-stat-item">
                    <span class="cjt-widget-stat-icon">📅</span>
                    <span class="cjt-widget-stat-label"><?php _e('Events', 'caffe-julia-tracker'); ?>:</span>
                    <strong class="cjt-widget-stat-value"><?php echo esc_html($totals['total_events'] ?? 0); ?></strong>
                </li>
                <li class="cjt-widget-stat-item">
                    <span class="cjt-widget-stat-icon">☕</span>
                    <span class="cjt-widget-stat-label"><?php _e('Kaffees', 'caffe-julia-tracker'); ?>:</span>
                    <strong class="cjt-widget-stat-value"><?php echo esc_html($totals['total_kaffees'] ?? 0); ?></strong>
                </li>
                <li class="cjt-widget-stat-item">
                    <span class="cjt-widget-stat-icon">⏱️</span>
                    <span class="cjt-widget-stat-label"><?php _e('Stunden', 'caffe-julia-tracker'); ?>:</span>
                    <strong class="cjt-widget-stat-value"><?php echo esc_html(number_format($totals['total_work_hours'] ?? 0, 1)); ?></strong>
                </li>
                <li class="cjt-widget-stat-item">
                    <span class="cjt-widget-stat-icon">🥛</span>
                    <span class="cjt-widget-stat-label"><?php _e('Milch', 'caffe-julia-tracker'); ?>:</span>
                    <strong class="cjt-widget-stat-value">
                        <?php echo esc_html(($totals['total_milch'] ?? 0) + ($totals['total_hafermilch'] ?? 0)); ?> L
                    </strong>
                </li>
            </ul>
            <p class="cjt-widget-period">
                <small><?php printf(__('Letzten %d Tage', 'caffe-julia-tracker'), $period); ?></small>
            </p>
        </div>
        <?php
    }
}
