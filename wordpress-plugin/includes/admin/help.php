<?php
/**
 * Admin Hilfe-Seite
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap cjt-admin-page">
    <h1><?php _e('Caffe Julia Tracker - Hilfe & Dokumentation', 'caffe-julia-tracker'); ?></h1>

    <div class="cjt-help-grid">
        <!-- Erste Schritte -->
        <div class="cjt-card">
            <h2><?php _e('🚀 Erste Schritte', 'caffe-julia-tracker'); ?></h2>
            <ol>
                <li>
                    <strong><?php _e('API konfigurieren', 'caffe-julia-tracker'); ?></strong><br>
                    <?php _e('Gehen Sie zu', 'caffe-julia-tracker'); ?>
                    <a href="<?php echo admin_url('admin.php?page=caffe-julia-tracker-settings'); ?>">
                        <?php _e('Einstellungen', 'caffe-julia-tracker'); ?>
                    </a>
                    <?php _e('und tragen Sie Ihre API-URL ein.', 'caffe-julia-tracker'); ?>
                </li>
                <li>
                    <strong><?php _e('Verbindung testen', 'caffe-julia-tracker'); ?></strong><br>
                    <?php _e('Im Dashboard können Sie die Verbindung zur API testen.', 'caffe-julia-tracker'); ?>
                </li>
                <li>
                    <strong><?php _e('Tracker einbinden', 'caffe-julia-tracker'); ?></strong><br>
                    <?php _e('Nutzen Sie Shortcodes, Gutenberg-Blocks oder Widgets um den Tracker anzuzeigen.', 'caffe-julia-tracker'); ?>
                </li>
            </ol>
        </div>

        <!-- Shortcodes -->
        <div class="cjt-card">
            <h2><?php _e('📝 Shortcodes', 'caffe-julia-tracker'); ?></h2>

            <h3><?php _e('Vollständiger Tracker', 'caffe-julia-tracker'); ?></h3>
            <div class="cjt-code-block">
                <code>[caffe_julia_tracker]</code>
            </div>
            <p><?php _e('Zeigt den kompletten Tracker mit allen Funktionen.', 'caffe-julia-tracker'); ?></p>

            <h4><?php _e('Parameter:', 'caffe-julia-tracker'); ?></h4>
            <ul>
                <li><code>height</code> - <?php _e('Höhe des Widgets (Standard: 800px)', 'caffe-julia-tracker'); ?></li>
                <li><code>show_stats</code> - <?php _e('Statistiken anzeigen (true/false)', 'caffe-julia-tracker'); ?></li>
                <li><code>theme</code> - <?php _e('Farbschema (light/dark)', 'caffe-julia-tracker'); ?></li>
            </ul>

            <h4><?php _e('Beispiel:', 'caffe-julia-tracker'); ?></h4>
            <div class="cjt-code-block">
                <code>[caffe_julia_tracker height="1000px" show_stats="true"]</code>
            </div>

            <hr>

            <h3><?php _e('Nur Statistiken', 'caffe-julia-tracker'); ?></h3>
            <div class="cjt-code-block">
                <code>[caffe_julia_stats]</code>
            </div>
            <p><?php _e('Zeigt nur die Statistiken in kompakter Form.', 'caffe-julia-tracker'); ?></p>

            <h4><?php _e('Parameter:', 'caffe-julia-tracker'); ?></h4>
            <ul>
                <li><code>period</code> - <?php _e('Zeitraum in Tagen (Standard: 30)', 'caffe-julia-tracker'); ?></li>
                <li><code>layout</code> - <?php _e('Layout (grid/list)', 'caffe-julia-tracker'); ?></li>
            </ul>

            <h4><?php _e('Beispiel:', 'caffe-julia-tracker'); ?></h4>
            <div class="cjt-code-block">
                <code>[caffe_julia_stats period="7" layout="grid"]</code>
            </div>
        </div>

        <!-- Gutenberg Block -->
        <div class="cjt-card">
            <h2><?php _e('🧩 Gutenberg Block', 'caffe-julia-tracker'); ?></h2>
            <p><?php _e('Im Block-Editor:', 'caffe-julia-tracker'); ?></p>
            <ol>
                <li><?php _e('Klicken Sie auf das (+) Symbol', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Suchen Sie nach "Caffe Julia Tracker"', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Fügen Sie den Block ein', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Konfigurieren Sie die Optionen in der Seitenleiste', 'caffe-julia-tracker'); ?></li>
            </ol>
        </div>

        <!-- Widget -->
        <div class="cjt-card">
            <h2><?php _e('🔲 Widget', 'caffe-julia-tracker'); ?></h2>
            <ol>
                <li>
                    <?php _e('Gehen Sie zu', 'caffe-julia-tracker'); ?>
                    <a href="<?php echo admin_url('widgets.php'); ?>">
                        <?php _e('Design → Widgets', 'caffe-julia-tracker'); ?>
                    </a>
                </li>
                <li><?php _e('Suchen Sie "Caffe Julia Statistiken"', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Ziehen Sie das Widget in Ihre Sidebar', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Konfigurieren Sie Titel und Optionen', 'caffe-julia-tracker'); ?></li>
            </ol>
        </div>

        <!-- Fehlerbehebung -->
        <div class="cjt-card">
            <h2><?php _e('🔧 Fehlerbehebung', 'caffe-julia-tracker'); ?></h2>

            <h3><?php _e('Tracker wird nicht angezeigt', 'caffe-julia-tracker'); ?></h3>
            <ul>
                <li><?php _e('Prüfen Sie, ob die API-URL korrekt konfiguriert ist', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Testen Sie die Verbindung im Dashboard', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Prüfen Sie, ob die API erreichbar ist (HTTPS!)', 'caffe-julia-tracker'); ?></li>
            </ul>

            <h3><?php _e('Statistiken werden nicht geladen', 'caffe-julia-tracker'); ?></h3>
            <ul>
                <li><?php _e('Leeren Sie den Cache', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Prüfen Sie das API-Token (falls verwendet)', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Überprüfen Sie Browser-Console auf Fehler', 'caffe-julia-tracker'); ?></li>
            </ul>

            <h3><?php _e('CORS-Fehler', 'caffe-julia-tracker'); ?></h3>
            <ul>
                <li><?php _e('Konfigurieren Sie CORS in der API (config.php)', 'caffe-julia-tracker'); ?></li>
                <li><?php _e('Fügen Sie Ihre WordPress-Domain zu CORS_ALLOWED_ORIGINS hinzu', 'caffe-julia-tracker'); ?></li>
            </ul>
        </div>

        <!-- Support -->
        <div class="cjt-card">
            <h2><?php _e('💬 Support', 'caffe-julia-tracker'); ?></h2>
            <p><strong><?php _e('Email:', 'caffe-julia-tracker'); ?></strong> admin@caffejulia.com</p>
            <p><strong><?php _e('Telefon:', 'caffe-julia-tracker'); ?></strong> +41 XX XXX XX XX</p>
            <p><strong><?php _e('GitHub:', 'caffe-julia-tracker'); ?></strong> <a href="https://github.com/caffe-julia/tracker" target="_blank">github.com/caffe-julia/tracker</a></p>
        </div>

        <!-- System-Anforderungen -->
        <div class="cjt-card">
            <h2><?php _e('⚙️ System-Anforderungen', 'caffe-julia-tracker'); ?></h2>
            <ul>
                <li><strong>WordPress:</strong> 5.8 oder höher</li>
                <li><strong>PHP:</strong> 7.4 oder höher</li>
                <li><strong>HTTPS:</strong> Erforderlich für sichere API-Kommunikation</li>
                <li><strong>API:</strong> Tracker-Backend muss installiert sein</li>
            </ul>
        </div>
    </div>
</div>

<style>
.cjt-help-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.cjt-code-block {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
    font-family: monospace;
}

.cjt-code-block code {
    font-size: 14px;
}
</style>
