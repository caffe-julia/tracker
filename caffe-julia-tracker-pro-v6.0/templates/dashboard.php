<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1>☕ Caffe Julia Tracker - Dashboard</h1>

    <div class="cjtp-dashboard">
        <div class="cjtp-stats-grid">
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">📊</div>
                <div class="cjtp-stat-label">Total Events</div>
                <div class="cjtp-stat-value" id="totalEvents">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">☕</div>
                <div class="cjtp-stat-label">Total Kaffees</div>
                <div class="cjtp-stat-value" id="totalKaffees">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">🥛</div>
                <div class="cjtp-stat-label">Milch (Liter)</div>
                <div class="cjtp-stat-value" id="totalMilch">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">🍵</div>
                <div class="cjtp-stat-label">Getränke</div>
                <div class="cjtp-stat-value" id="totalGetraenke">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">⏱️</div>
                <div class="cjtp-stat-label">Arbeitsstunden</div>
                <div class="cjtp-stat-value" id="totalStunden">-</div>
            </div>
        </div>

        <div class="cjtp-actions">
            <h2>📥 Export</h2>
            <p>Laden Sie alle Events als Excel/CSV-Datei herunter.</p>
            <a href="<?php echo admin_url('admin-ajax.php?action=cjtp_export_csv&nonce=' . wp_create_nonce('wp_rest')); ?>"
               class="button button-primary button-hero">
                📥 Excel/CSV herunterladen
            </a>
        </div>

        <div class="cjtp-info">
            <h2>📱 Tracker verwenden</h2>
            <p>Fügen Sie den Tracker auf einer WordPress-Seite ein mit dem Shortcode:</p>
            <pre style="background: #f0f0f1; padding: 15px; border-radius: 4px; font-size: 14px;">[caffe_tracker]</pre>

            <p><strong>Empfehlung:</strong> Erstellen Sie eine neue Seite "Tracker" und fügen Sie den Shortcode ein.</p>
            <p>Der Tracker ist <strong>iPhone-optimiert</strong> und kann direkt vom Handy verwendet werden!</p>
        </div>
    </div>
</div>

<style>
.cjtp-dashboard {
    max-width: 1200px;
}
.cjtp-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}
.cjtp-stat-card {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.cjtp-stat-icon {
    font-size: 48px;
    margin-bottom: 10px;
}
.cjtp-stat-label {
    font-size: 13px;
    color: #646970;
    margin-bottom: 8px;
}
.cjtp-stat-value {
    font-size: 32px;
    font-weight: bold;
    color: #1d2327;
}
.cjtp-actions, .cjtp-info {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 8px;
    padding: 24px;
    margin: 20px 0;
}
.cjtp-actions h2, .cjtp-info h2 {
    margin-top: 0;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Lade Statistiken
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'cjtp_get_stats',
            nonce: '<?php echo wp_create_nonce('wp_rest'); ?>'
        },
        success: function(response) {
            if (response.success) {
                $('#totalEvents').text(response.data.totalEvents);
                $('#totalKaffees').text(response.data.totalKaffees.toLocaleString());
                $('#totalMilch').text(response.data.totalMilch.toLocaleString());
                $('#totalGetraenke').text(response.data.totalGetraenke.toLocaleString());
                $('#totalStunden').text(response.data.totalArbeitsstunden.toFixed(1));
            }
        }
    });
});
</script>
<?php
