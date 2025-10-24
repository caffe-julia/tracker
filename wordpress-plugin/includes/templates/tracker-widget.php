<?php
/**
 * Tracker Widget Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('cjt_options', array());
$api_url = isset($options['api_url']) ? $options['api_url'] : '';
$height = isset($atts['height']) ? $atts['height'] : '800px';
$show_stats = isset($atts['show_stats']) ? $atts['show_stats'] === 'true' : true;
$theme = isset($atts['theme']) ? $atts['theme'] : 'light';

if (empty($api_url)) {
    echo '<div class="cjt-error">';
    echo '<p>' . __('⚠️ API noch nicht konfiguriert. Bitte konfigurieren Sie die API-URL in den Plugin-Einstellungen.', 'caffe-julia-tracker') . '</p>';
    if (current_user_can('manage_options')) {
        echo '<p><a href="' . admin_url('admin.php?page=caffe-julia-tracker-settings') . '" class="button">' . __('Zu den Einstellungen', 'caffe-julia-tracker') . '</a></p>';
    }
    echo '</div>';
    return;
}
?>

<div class="cjt-tracker-wrapper" data-theme="<?php echo esc_attr($theme); ?>">
    <?php if ($show_stats): ?>
    <div class="cjt-stats-header">
        <h3><?php _e('📊 Statistiken', 'caffe-julia-tracker'); ?></h3>
        <div class="cjt-stats-quick" id="cjt-stats-quick-<?php echo uniqid(); ?>">
            <div class="cjt-loading">
                <span class="cjt-spinner"></span>
                <?php _e('Lade...', 'caffe-julia-tracker'); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="cjt-tracker-container" style="height: <?php echo esc_attr($height); ?>;">
        <iframe src="<?php echo esc_url($api_url . '/../index.html'); ?>"
                class="cjt-tracker-iframe"
                frameborder="0"
                allowfullscreen></iframe>
    </div>

    <div class="cjt-tracker-footer">
        <small>
            <?php _e('Powered by', 'caffe-julia-tracker'); ?>
            <strong>Caffe Julia Tracker</strong>
        </small>
    </div>
</div>
