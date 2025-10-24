<?php
/**
 * Admin Dashboard-Seite
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('cjt_options', array());
$api_url = isset($options['api_url']) ? $options['api_url'] : '';
$has_api = !empty($api_url);
?>

<div class="wrap cjt-admin-page">
    <h1><?php _e('Caffe Julia Tracker - Dashboard', 'caffe-julia-tracker'); ?></h1>

    <?php if (!$has_api): ?>
        <div class="notice notice-warning">
            <p>
                <strong><?php _e('API noch nicht konfiguriert!', 'caffe-julia-tracker'); ?></strong><br>
                <?php _e('Bitte gehen Sie zu', 'caffe-julia-tracker'); ?>
                <a href="<?php echo admin_url('admin.php?page=caffe-julia-tracker-settings'); ?>">
                    <?php _e('Einstellungen', 'caffe-julia-tracker'); ?>
                </a>
                <?php _e('und konfigurieren Sie die API-URL.', 'caffe-julia-tracker'); ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="cjt-dashboard-grid">
        <!-- Quick Stats -->
        <div class="cjt-card cjt-quick-stats">
            <h2><?php _e('📊 Schnell-Statistiken', 'caffe-julia-tracker'); ?></h2>
            <div id="cjt-quick-stats-content">
                <?php if ($has_api): ?>
                    <div class="cjt-loading">
                        <span class="spinner is-active"></span>
                        <?php _e('Lade Statistiken...', 'caffe-julia-tracker'); ?>
                    </div>
                <?php else: ?>
                    <p class="cjt-no-data"><?php _e('Keine API konfiguriert', 'caffe-julia-tracker'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="cjt-card">
            <h2><?php _e('⚡ Schnellaktionen', 'caffe-julia-tracker'); ?></h2>
            <div class="cjt-actions">
                <a href="<?php echo admin_url('admin.php?page=caffe-julia-tracker-settings'); ?>" class="button button-primary">
                    <?php _e('⚙️ Einstellungen', 'caffe-julia-tracker'); ?>
                </a>
                <button id="cjt-test-connection" class="button">
                    <?php _e('🔌 Verbindung testen', 'caffe-julia-tracker'); ?>
                </button>
                <button id="cjt-clear-cache" class="button">
                    <?php _e('🗑️ Cache leeren', 'caffe-julia-tracker'); ?>
                </button>
            </div>
        </div>

        <!-- Verwendung -->
        <div class="cjt-card cjt-usage-guide">
            <h2><?php _e('📖 Verwendung', 'caffe-julia-tracker'); ?></h2>
            <h3><?php _e('Shortcodes', 'caffe-julia-tracker'); ?></h3>
            <p><?php _e('Kopieren Sie einen dieser Shortcodes in Ihre Seite oder Ihren Beitrag:', 'caffe-julia-tracker'); ?></p>

            <div class="cjt-shortcode-box">
                <code>[caffe_julia_tracker]</code>
                <button class="button button-small cjt-copy-shortcode" data-shortcode="[caffe_julia_tracker]">
                    <?php _e('Kopieren', 'caffe-julia-tracker'); ?>
                </button>
            </div>

            <div class="cjt-shortcode-box">
                <code>[caffe_julia_stats period="30"]</code>
                <button class="button button-small cjt-copy-shortcode" data-shortcode='[caffe_julia_stats period="30"]'>
                    <?php _e('Kopieren', 'caffe-julia-tracker'); ?>
                </button>
            </div>

            <h3><?php _e('Gutenberg-Block', 'caffe-julia-tracker'); ?></h3>
            <p><?php _e('Im Block-Editor suchen Sie nach "Caffe Julia Tracker" und fügen den Block ein.', 'caffe-julia-tracker'); ?></p>

            <h3><?php _e('Widget', 'caffe-julia-tracker'); ?></h3>
            <p>
                <?php _e('Gehen Sie zu', 'caffe-julia-tracker'); ?>
                <a href="<?php echo admin_url('widgets.php'); ?>"><?php _e('Design → Widgets', 'caffe-julia-tracker'); ?></a>
                <?php _e('und fügen Sie das "Caffe Julia Statistiken" Widget hinzu.', 'caffe-julia-tracker'); ?>
            </p>
        </div>

        <!-- System-Info -->
        <div class="cjt-card">
            <h2><?php _e('ℹ️ System-Informationen', 'caffe-julia-tracker'); ?></h2>
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><strong><?php _e('Plugin-Version', 'caffe-julia-tracker'); ?></strong></td>
                        <td><?php echo CJT_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('WordPress-Version', 'caffe-julia-tracker'); ?></strong></td>
                        <td><?php echo get_bloginfo('version'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('PHP-Version', 'caffe-julia-tracker'); ?></strong></td>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('API-Status', 'caffe-julia-tracker'); ?></strong></td>
                        <td id="cjt-api-status">
                            <?php if ($has_api): ?>
                                <span class="cjt-status-unknown">⚪ <?php _e('Unbekannt', 'caffe-julia-tracker'); ?></span>
                            <?php else: ?>
                                <span class="cjt-status-error">🔴 <?php _e('Nicht konfiguriert', 'caffe-julia-tracker'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Cache', 'caffe-julia-tracker'); ?></strong></td>
                        <td>
                            <?php echo isset($options['cache_enabled']) && $options['cache_enabled'] ?
                                '✅ ' . __('Aktiviert', 'caffe-julia-tracker') :
                                '❌ ' . __('Deaktiviert', 'caffe-julia-tracker'); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Lade Statistiken
    function loadStats() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cjt_get_statistics',
                nonce: cjtAdminData.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    displayStats(response.data);
                    $('#cjt-api-status').html('<span class="cjt-status-success">🟢 <?php _e('Verbunden', 'caffe-julia-tracker'); ?></span>');
                } else {
                    $('#cjt-quick-stats-content').html('<p class="cjt-error"><?php _e('Fehler beim Laden der Statistiken', 'caffe-julia-tracker'); ?></p>');
                    $('#cjt-api-status').html('<span class="cjt-status-error">🔴 <?php _e('Fehler', 'caffe-julia-tracker'); ?></span>');
                }
            },
            error: function() {
                $('#cjt-quick-stats-content').html('<p class="cjt-error"><?php _e('Verbindungsfehler', 'caffe-julia-tracker'); ?></p>');
                $('#cjt-api-status').html('<span class="cjt-status-error">🔴 <?php _e('Verbindungsfehler', 'caffe-julia-tracker'); ?></span>');
            }
        });
    }

    function displayStats(data) {
        var totals = data.totals || {};
        var html = '<div class="cjt-stats-grid">';
        html += '<div class="cjt-stat-item"><span class="cjt-stat-value">' + (totals.total_events || 0) + '</span><span class="cjt-stat-label"><?php _e('Events', 'caffe-julia-tracker'); ?></span></div>';
        html += '<div class="cjt-stat-item"><span class="cjt-stat-value">' + (totals.total_kaffees || 0) + '</span><span class="cjt-stat-label"><?php _e('Kaffees', 'caffe-julia-tracker'); ?></span></div>';
        html += '<div class="cjt-stat-item"><span class="cjt-stat-value">' + (totals.total_work_hours || 0) + '</span><span class="cjt-stat-label"><?php _e('Arbeitsstunden', 'caffe-julia-tracker'); ?></span></div>';
        html += '<div class="cjt-stat-item"><span class="cjt-stat-value">' + ((totals.total_milch || 0) + (totals.total_hafermilch || 0)) + ' L</span><span class="cjt-stat-label"><?php _e('Milch', 'caffe-julia-tracker'); ?></span></div>';
        html += '</div>';
        $('#cjt-quick-stats-content').html(html);
    }

    // Verbindung testen
    $('#cjt-test-connection').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).text('<?php _e('Teste...', 'caffe-julia-tracker'); ?>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cjt_test_connection',
                nonce: cjtAdminData.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.data);
                    $('#cjt-api-status').html('<span class="cjt-status-success">🟢 <?php _e('Verbunden', 'caffe-julia-tracker'); ?></span>');
                } else {
                    alert('❌ ' + response.data);
                    $('#cjt-api-status').html('<span class="cjt-status-error">🔴 <?php _e('Fehler', 'caffe-julia-tracker'); ?></span>');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).text('<?php _e('🔌 Verbindung testen', 'caffe-julia-tracker'); ?>');
            }
        });
    });

    // Cache leeren
    $('#cjt-clear-cache').on('click', function() {
        if (confirm('<?php _e('Cache wirklich leeren?', 'caffe-julia-tracker'); ?>')) {
            // Reload um Cache zu erneuern
            loadStats();
            alert('<?php _e('Cache wurde geleert und Statistiken neu geladen.', 'caffe-julia-tracker'); ?>');
        }
    });

    // Shortcode kopieren
    $('.cjt-copy-shortcode').on('click', function() {
        var shortcode = $(this).data('shortcode');
        navigator.clipboard.writeText(shortcode).then(function() {
            alert('<?php _e('Shortcode kopiert!', 'caffe-julia-tracker'); ?>');
        });
    });

    // Lade Statistiken beim Laden
    <?php if ($has_api): ?>
    loadStats();
    <?php endif; ?>
});
</script>
