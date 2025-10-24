<?php
/**
 * Stats Widget Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('cjt_options', array());
$api_url = isset($options['api_url']) ? $options['api_url'] : '';
$period = isset($atts['period']) ? intval($atts['period']) : 30;
$layout = isset($atts['layout']) ? $atts['layout'] : 'grid';
$widget_id = 'cjt-stats-' . uniqid();

if (empty($api_url)) {
    echo '<div class="cjt-error">';
    echo '<p>' . __('⚠️ API noch nicht konfiguriert.', 'caffe-julia-tracker') . '</p>';
    echo '</div>';
    return;
}
?>

<div class="cjt-stats-widget" data-layout="<?php echo esc_attr($layout); ?>" id="<?php echo esc_attr($widget_id); ?>">
    <div class="cjt-stats-loading">
        <span class="cjt-spinner"></span>
        <?php _e('Lade Statistiken...', 'caffe-julia-tracker'); ?>
    </div>
</div>

<script>
(function() {
    var widgetId = '<?php echo esc_js($widget_id); ?>';
    var apiUrl = '<?php echo esc_js($api_url); ?>';
    var period = <?php echo intval($period); ?>;

    jQuery(document).ready(function($) {
        $.ajax({
            url: cjtData.ajaxurl,
            type: 'POST',
            data: {
                action: 'cjt_get_statistics',
                nonce: cjtData.nonce,
                period: period
            },
            success: function(response) {
                if (response.success && response.data) {
                    displayStats(response.data);
                } else {
                    $('#' + widgetId).html('<p class="cjt-error"><?php _e('Fehler beim Laden', 'caffe-julia-tracker'); ?></p>');
                }
            },
            error: function() {
                $('#' + widgetId).html('<p class="cjt-error"><?php _e('Verbindungsfehler', 'caffe-julia-tracker'); ?></p>');
            }
        });

        function displayStats(data) {
            var totals = data.totals || {};
            var html = '<div class="cjt-stats-grid">';

            html += '<div class="cjt-stat-box cjt-stat-events">';
            html += '<span class="cjt-stat-icon">📅</span>';
            html += '<span class="cjt-stat-value">' + (totals.total_events || 0) + '</span>';
            html += '<span class="cjt-stat-label"><?php _e('Events', 'caffe-julia-tracker'); ?></span>';
            html += '</div>';

            html += '<div class="cjt-stat-box cjt-stat-coffee">';
            html += '<span class="cjt-stat-icon">☕</span>';
            html += '<span class="cjt-stat-value">' + (totals.total_kaffees || 0) + '</span>';
            html += '<span class="cjt-stat-label"><?php _e('Kaffees', 'caffe-julia-tracker'); ?></span>';
            html += '</div>';

            html += '<div class="cjt-stat-box cjt-stat-hours">';
            html += '<span class="cjt-stat-icon">⏱️</span>';
            html += '<span class="cjt-stat-value">' + parseFloat(totals.total_work_hours || 0).toFixed(1) + '</span>';
            html += '<span class="cjt-stat-label"><?php _e('Stunden', 'caffe-julia-tracker'); ?></span>';
            html += '</div>';

            html += '<div class="cjt-stat-box cjt-stat-milk">';
            html += '<span class="cjt-stat-icon">🥛</span>';
            html += '<span class="cjt-stat-value">' + ((totals.total_milch || 0) + (totals.total_hafermilch || 0)) + ' L</span>';
            html += '<span class="cjt-stat-label"><?php _e('Milch', 'caffe-julia-tracker'); ?></span>';
            html += '</div>';

            html += '</div>';

            html += '<p class="cjt-stats-period"><?php _e('Letzten', 'caffe-julia-tracker'); ?> ' + period + ' <?php _e('Tage', 'caffe-julia-tracker'); ?></p>';

            $('#' + widgetId).html(html);
        }
    });
})();
</script>
