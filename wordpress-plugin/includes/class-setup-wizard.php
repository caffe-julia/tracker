<?php
/**
 * Setup-Wizard für automatische Installation
 *
 * Installiert automatisch:
 * - API-Dateien ins /tracker/ Verzeichnis
 * - Erstellt MySQL-Datenbank-Tabellen
 * - Konfiguriert config.php
 */

if (!defined('ABSPATH')) {
    exit;
}

class CJT_Setup_Wizard {

    private $errors = array();
    private $success = array();

    public function __construct() {
        add_action('admin_menu', array($this, 'add_setup_menu'), 5);
        add_action('admin_init', array($this, 'handle_setup_steps'));
    }

    /**
     * Füge Setup-Menü hinzu (nur wenn noch nicht installiert)
     */
    public function add_setup_menu() {
        $setup_complete = get_option('cjt_setup_complete', false);

        if (!$setup_complete) {
            add_menu_page(
                'Tracker Setup',
                'Tracker Setup',
                'manage_options',
                'cjt-setup',
                array($this, 'render_setup_page'),
                'dashicons-admin-tools',
                2
            );
        }
    }

    /**
     * Handle Setup-Schritte
     */
    public function handle_setup_steps() {
        if (!isset($_POST['cjt_setup_action'])) {
            return;
        }

        check_admin_referer('cjt_setup_wizard');

        $action = sanitize_text_field($_POST['cjt_setup_action']);

        switch ($action) {
            case 'install_files':
                $this->install_api_files();
                break;

            case 'create_database':
                $this->create_database_tables();
                break;

            case 'configure':
                $this->create_configuration();
                break;

            case 'complete':
                $this->complete_setup();
                break;
        }
    }

    /**
     * Installiere API-Dateien
     */
    private function install_api_files() {
        $source_dir = CJT_PLUGIN_DIR . 'installer/api-files/';
        $target_dir = ABSPATH . 'tracker/';

        // Erstelle Zielverzeichnis
        if (!file_exists($target_dir)) {
            if (!wp_mkdir_p($target_dir)) {
                $this->errors[] = 'Konnte Verzeichnis /tracker/ nicht erstellen. Bitte prüfen Sie die Schreibrechte.';
                return false;
            }
        }

        // Kopiere Dateien
        try {
            $this->copy_directory($source_dir, $target_dir);

            // Erstelle logs/ Verzeichnis
            $logs_dir = $target_dir . 'logs/';
            if (!file_exists($logs_dir)) {
                wp_mkdir_p($logs_dir);
                file_put_contents($logs_dir . '.htaccess', 'Deny from all');
            }

            $this->success[] = 'API-Dateien erfolgreich installiert!';
            update_option('cjt_files_installed', true);

        } catch (Exception $e) {
            $this->errors[] = 'Fehler beim Kopieren der Dateien: ' . $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Erstelle Datenbank-Tabellen
     */
    private function create_database_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Verwende WordPress-Datenbank mit Präfix
        $prefix = 'cjt_'; // Plugin-spezifisches Präfix

        // Users-Tabelle
        $sql_users = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role ENUM('admin', 'staff', 'viewer') DEFAULT 'staff',
            is_active BOOLEAN DEFAULT TRUE,
            failed_login_attempts INT DEFAULT 0,
            locked_until DATETIME NULL,
            last_login DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_username (username),
            INDEX idx_email (email),
            INDEX idx_is_active (is_active)
        ) $charset_collate;";

        // Sessions-Tabelle
        $sql_sessions = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}sessions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            session_token VARCHAR(255) NOT NULL UNIQUE,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            expires_at DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session_token (session_token),
            INDEX idx_user_id (user_id),
            INDEX idx_expires_at (expires_at)
        ) $charset_collate;";

        // Events-Tabelle
        $sql_events = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}events (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(255) NOT NULL,
            event_date DATE NOT NULL,
            is_all_day BOOLEAN DEFAULT FALSE,
            start_time TIME NULL,
            end_time TIME NULL,
            anzahl_muehlen INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_event_date (event_date),
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at)
        ) $charset_collate;";

        // Muehlen-Tabelle
        $sql_muehlen = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}muehlen (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            event_id BIGINT UNSIGNED NOT NULL,
            muehle_nr INT UNSIGNED NOT NULL,
            stand_anfang INT UNSIGNED NOT NULL,
            stand_ende INT UNSIGNED NOT NULL,
            differenz INT UNSIGNED GENERATED ALWAYS AS (stand_ende - stand_anfang) STORED,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_event_id (event_id),
            INDEX idx_muehle_nr (muehle_nr)
        ) $charset_collate;";

        // Verbrauch-Tabelle
        $sql_verbrauch = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}verbrauch (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            event_id BIGINT UNSIGNED NOT NULL,
            milch_liter DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_event_id (event_id)
        ) $charset_collate;";

        // Audit-Log-Tabelle
        $sql_audit = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}audit_log (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NULL,
            action VARCHAR(100) NOT NULL,
            table_name VARCHAR(100) NULL,
            record_id BIGINT UNSIGNED NULL,
            old_values TEXT NULL,
            new_values TEXT NULL,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_action (action),
            INDEX idx_created_at (created_at)
        ) $charset_collate;";

        // Settings-Tabelle
        $sql_settings = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$prefix}settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) $charset_collate;";

        // Führe Queries aus
        try {
            dbDelta($sql_users);
            dbDelta($sql_sessions);
            dbDelta($sql_events);
            dbDelta($sql_muehlen);
            dbDelta($sql_verbrauch);
            dbDelta($sql_audit);
            dbDelta($sql_settings);

            // Erstelle Standard-Admin-User (nur wenn noch nicht vorhanden)
            $existing_user = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$prefix}users");

            if ($existing_user == 0) {
                $password_hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);

                $wpdb->insert(
                    $wpdb->prefix . $prefix . 'users',
                    array(
                        'username' => 'admin',
                        'email' => get_option('admin_email'),
                        'password_hash' => $password_hash,
                        'role' => 'admin',
                        'is_active' => 1
                    )
                );

                $this->success[] = 'Standard-Admin-User erstellt (Benutzername: admin, Passwort: admin123) - BITTE ÄNDERN!';
            }

            $this->success[] = 'Datenbank-Tabellen erfolgreich erstellt!';
            update_option('cjt_database_created', true);

            return true;

        } catch (Exception $e) {
            $this->errors[] = 'Fehler beim Erstellen der Datenbank: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Erstelle Konfigurationsdatei
     */
    private function create_configuration() {
        $config_file = ABSPATH . 'tracker/config/config.php';

        // Generiere Encryption-Key
        $encryption_key = bin2hex(random_bytes(32));

        // Hole WordPress-DB-Zugangsdaten
        $db_host = DB_HOST;
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_pass = DB_PASSWORD;

        // Hole Site-URL
        $site_url = get_site_url();

        $config_content = "<?php
/**
 * Caffe Julia Tracker - Konfiguration
 * Automatisch generiert durch WordPress-Setup-Wizard
 */

// === DATENBANK-KONFIGURATION ===
define('DB_HOST', '{$db_host}');
define('DB_NAME', '{$db_name}');
define('DB_USER', '{$db_user}');
define('DB_PASS', '{$db_pass}');
define('DB_CHARSET', 'utf8mb4');

// Verwende WordPress-Datenbank mit Präfix
define('DB_TABLE_PREFIX', '" . $GLOBALS['wpdb']->prefix . "cjt_');

// === VERSCHLÜSSELUNG ===
define('ENCRYPTION_KEY', '{$encryption_key}');

// === CORS (Cross-Origin Resource Sharing) ===
define('CORS_ALLOWED_ORIGINS', [
    '{$site_url}',
    '" . str_replace('https://', 'http://', $site_url) . "',
    '" . str_replace('http://', 'https://', $site_url) . "',
]);

// === SESSION-KONFIGURATION ===
define('SESSION_LIFETIME', 28800); // 8 Stunden
define('SESSION_COOKIE_SECURE', " . (is_ssl() ? 'true' : 'false') . ");
define('SESSION_COOKIE_SAMESITE', 'Lax');

// === API-KONFIGURATION ===
define('API_VERSION', '2.0');
define('API_RATE_LIMIT', 100); // Max. Anfragen pro Minute pro IP
define('API_RATE_LIMIT_PERIOD', 60); // Sekunden

// === LOGGING ===
define('LOG_ERRORS', true);
define('LOG_DIR', __DIR__ . '/../logs');
define('LOG_FILE', LOG_DIR . '/app.log');
define('LOG_MAX_SIZE', 10485760); // 10 MB

// === SICHERHEIT ===
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRY', 3600); // 1 Stunde
define('BCRYPT_COST', 12);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 Minuten

// === DEVELOPMENT ===
define('ENVIRONMENT', 'production');
define('DEBUG_MODE', false);

// === ZEITZONE ===
date_default_timezone_set('Europe/Zurich');
";

        // Schreibe Datei
        try {
            if (file_put_contents($config_file, $config_content) === false) {
                throw new Exception('Konnte config.php nicht schreiben');
            }

            // Setze Berechtigungen
            chmod($config_file, 0640);

            $this->success[] = 'Konfigurationsdatei erfolgreich erstellt!';
            update_option('cjt_config_created', true);

            // Speichere API-URL in Plugin-Optionen
            $options = get_option('cjt_options', array());
            $options['api_url'] = $site_url . '/tracker/api';
            update_option('cjt_options', $options);

            return true;

        } catch (Exception $e) {
            $this->errors[] = 'Fehler beim Erstellen der Konfiguration: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Setup abschließen
     */
    private function complete_setup() {
        update_option('cjt_setup_complete', true);
        $this->success[] = 'Setup erfolgreich abgeschlossen!';

        // Redirect zum Dashboard
        wp_redirect(admin_url('admin.php?page=caffe-julia-tracker'));
        exit;
    }

    /**
     * Kopiere Verzeichnis rekursiv
     */
    private function copy_directory($source, $dest) {
        if (!is_dir($source)) {
            throw new Exception("Quellverzeichnis existiert nicht: $source");
        }

        if (!is_dir($dest)) {
            wp_mkdir_p($dest);
        }

        $dir = opendir($source);
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $src_file = $source . '/' . $file;
                $dst_file = $dest . '/' . $file;

                if (is_dir($src_file)) {
                    $this->copy_directory($src_file, $dst_file);
                } else {
                    if (!copy($src_file, $dst_file)) {
                        throw new Exception("Konnte Datei nicht kopieren: $file");
                    }
                }
            }
        }
        closedir($dir);
    }

    /**
     * Rendere Setup-Seite
     */
    public function render_setup_page() {
        $current_step = $this->get_current_step();
        ?>
        <div class="wrap cjt-setup-wizard">
            <h1>🚀 Caffe Julia Tracker - Automatische Installation</h1>

            <?php $this->display_messages(); ?>

            <div class="cjt-setup-progress">
                <div class="cjt-step <?php echo $current_step >= 1 ? 'completed' : ''; ?> <?php echo $current_step == 1 ? 'active' : ''; ?>">
                    <span class="step-number">1</span>
                    <span class="step-title">Dateien installieren</span>
                </div>
                <div class="cjt-step <?php echo $current_step >= 2 ? 'completed' : ''; ?> <?php echo $current_step == 2 ? 'active' : ''; ?>">
                    <span class="step-number">2</span>
                    <span class="step-title">Datenbank erstellen</span>
                </div>
                <div class="cjt-step <?php echo $current_step >= 3 ? 'completed' : ''; ?> <?php echo $current_step == 3 ? 'active' : ''; ?>">
                    <span class="step-number">3</span>
                    <span class="step-title">Konfiguration</span>
                </div>
                <div class="cjt-step <?php echo $current_step >= 4 ? 'completed' : ''; ?> <?php echo $current_step == 4 ? 'active' : ''; ?>">
                    <span class="step-number">4</span>
                    <span class="step-title">Fertig!</span>
                </div>
            </div>

            <div class="cjt-setup-content">
                <?php
                switch ($current_step) {
                    case 1:
                        $this->render_step_files();
                        break;
                    case 2:
                        $this->render_step_database();
                        break;
                    case 3:
                        $this->render_step_configuration();
                        break;
                    case 4:
                        $this->render_step_complete();
                        break;
                }
                ?>
            </div>

            <style>
                .cjt-setup-wizard {
                    max-width: 800px;
                    margin: 20px auto;
                }
                .cjt-setup-progress {
                    display: flex;
                    justify-content: space-between;
                    margin: 40px 0;
                    position: relative;
                }
                .cjt-setup-progress::before {
                    content: '';
                    position: absolute;
                    top: 30px;
                    left: 60px;
                    right: 60px;
                    height: 2px;
                    background: #ddd;
                    z-index: 0;
                }
                .cjt-step {
                    flex: 1;
                    text-align: center;
                    position: relative;
                    z-index: 1;
                }
                .step-number {
                    display: block;
                    width: 60px;
                    height: 60px;
                    line-height: 60px;
                    border-radius: 50%;
                    background: #ddd;
                    color: #666;
                    font-size: 24px;
                    font-weight: bold;
                    margin: 0 auto 10px;
                }
                .cjt-step.active .step-number {
                    background: #d97706;
                    color: white;
                }
                .cjt-step.completed .step-number {
                    background: #10b981;
                    color: white;
                }
                .cjt-setup-content {
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .cjt-setup-box {
                    background: #f9fafb;
                    padding: 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                }
                .button-primary {
                    background: #d97706 !important;
                    border-color: #d97706 !important;
                    font-size: 16px !important;
                    padding: 10px 30px !important;
                    height: auto !important;
                }
                .notice {
                    margin: 20px 0;
                }
            </style>
        </div>
        <?php
    }

    /**
     * Hole aktuellen Setup-Schritt
     */
    private function get_current_step() {
        if (!get_option('cjt_files_installed')) return 1;
        if (!get_option('cjt_database_created')) return 2;
        if (!get_option('cjt_config_created')) return 3;
        return 4;
    }

    /**
     * Zeige Nachrichten an
     */
    private function display_messages() {
        if (!empty($this->errors)) {
            echo '<div class="notice notice-error">';
            foreach ($this->errors as $error) {
                echo '<p>' . esc_html($error) . '</p>';
            }
            echo '</div>';
        }

        if (!empty($this->success)) {
            echo '<div class="notice notice-success">';
            foreach ($this->success as $message) {
                echo '<p>' . esc_html($message) . '</p>';
            }
            echo '</div>';
        }
    }

    /**
     * Rendere Schritt 1: Dateien
     */
    private function render_step_files() {
        ?>
        <h2>Schritt 1: API-Dateien installieren</h2>
        <div class="cjt-setup-box">
            <p><strong>Was wird installiert:</strong></p>
            <ul>
                <li>✓ API-Backend (PHP-Dateien)</li>
                <li>✓ Tracker-Oberfläche (HTML)</li>
                <li>✓ Sicherheitseinstellungen (.htaccess)</li>
                <li>✓ Log-Verzeichnis</li>
            </ul>
            <p><strong>Installationsort:</strong> <code><?php echo ABSPATH; ?>tracker/</code></p>
        </div>

        <form method="post">
            <?php wp_nonce_field('cjt_setup_wizard'); ?>
            <input type="hidden" name="cjt_setup_action" value="install_files">
            <button type="submit" class="button button-primary button-hero">
                Dateien jetzt installieren
            </button>
        </form>
        <?php
    }

    /**
     * Rendere Schritt 2: Datenbank
     */
    private function render_step_database() {
        global $wpdb;
        ?>
        <h2>Schritt 2: Datenbank-Tabellen erstellen</h2>
        <div class="cjt-setup-box">
            <p><strong>Verwendet wird Ihre bestehende WordPress-Datenbank:</strong></p>
            <ul>
                <li>Datenbank: <code><?php echo DB_NAME; ?></code></li>
                <li>Benutzer: <code><?php echo DB_USER; ?></code></li>
                <li>Präfix: <code><?php echo $wpdb->prefix; ?>cjt_</code></li>
            </ul>
            <p><strong>Es werden folgende Tabellen erstellt:</strong></p>
            <ul>
                <li>✓ users (Benutzer)</li>
                <li>✓ sessions (Sitzungen)</li>
                <li>✓ events (Veranstaltungen)</li>
                <li>✓ muehlen (Kaffeemühlen-Zählerstände)</li>
                <li>✓ verbrauch (Verbrauchsdaten)</li>
                <li>✓ audit_log (Aktivitätsprotokoll)</li>
                <li>✓ settings (Einstellungen)</li>
            </ul>
            <p><em>Ein Standard-Admin-User wird automatisch erstellt.</em></p>
        </div>

        <form method="post">
            <?php wp_nonce_field('cjt_setup_wizard'); ?>
            <input type="hidden" name="cjt_setup_action" value="create_database">
            <button type="submit" class="button button-primary button-hero">
                Datenbank-Tabellen erstellen
            </button>
        </form>
        <?php
    }

    /**
     * Rendere Schritt 3: Konfiguration
     */
    private function render_step_configuration() {
        ?>
        <h2>Schritt 3: Konfiguration erstellen</h2>
        <div class="cjt-setup-box">
            <p><strong>Folgende Einstellungen werden automatisch konfiguriert:</strong></p>
            <ul>
                <li>✓ Datenbank-Verbindung (verwendet Ihre WordPress-Zugangsdaten)</li>
                <li>✓ Verschlüsselung (automatisch generierter Schlüssel)</li>
                <li>✓ CORS-Einstellungen (Ihre WordPress-Domain)</li>
                <li>✓ Sicherheitseinstellungen</li>
                <li>✓ Logging</li>
            </ul>
            <p><strong>Konfigurationsdatei:</strong> <code><?php echo ABSPATH; ?>tracker/config/config.php</code></p>
        </div>

        <form method="post">
            <?php wp_nonce_field('cjt_setup_wizard'); ?>
            <input type="hidden" name="cjt_setup_action" value="configure">
            <button type="submit" class="button button-primary button-hero">
                Konfiguration erstellen
            </button>
        </form>
        <?php
    }

    /**
     * Rendere Schritt 4: Fertig
     */
    private function render_step_complete() {
        $site_url = get_site_url();
        ?>
        <h2>🎉 Installation erfolgreich abgeschlossen!</h2>
        <div class="cjt-setup-box">
            <p><strong>Ihr Tracker ist jetzt einsatzbereit!</strong></p>

            <h3>📝 Wichtige Informationen:</h3>
            <ul>
                <li><strong>Tracker-URL:</strong> <a href="<?php echo $site_url; ?>/tracker/" target="_blank"><?php echo $site_url; ?>/tracker/</a></li>
                <li><strong>API-URL:</strong> <code><?php echo $site_url; ?>/tracker/api</code></li>
            </ul>

            <h3>🔐 Standard-Login (BITTE ÄNDERN!):</h3>
            <ul>
                <li><strong>Benutzername:</strong> <code>admin</code></li>
                <li><strong>Passwort:</strong> <code>admin123</code></li>
            </ul>

            <h3>📄 Tracker auf Seite einfügen:</h3>
            <p>Verwenden Sie diesen Shortcode:</p>
            <pre>[caffe_julia_tracker height="800px"]</pre>

            <h3>✅ Nächste Schritte:</h3>
            <ol>
                <li>Testen Sie den Tracker: <a href="<?php echo $site_url; ?>/tracker/" target="_blank">Tracker öffnen</a></li>
                <li>Ändern Sie das Admin-Passwort sofort!</li>
                <li>Fügen Sie den Shortcode auf Ihrer Seite ein</li>
                <li>Prüfen Sie die Statistiken im Dashboard</li>
            </ol>
        </div>

        <form method="post">
            <?php wp_nonce_field('cjt_setup_wizard'); ?>
            <input type="hidden" name="cjt_setup_action" value="complete">
            <button type="submit" class="button button-primary button-hero">
                Zum Dashboard
            </button>
        </form>

        <p style="text-align: center; margin-top: 30px;">
            <a href="<?php echo $site_url; ?>/tracker/" target="_blank" class="button">Tracker öffnen</a>
            <a href="<?php echo admin_url('admin.php?page=cjt-settings'); ?>" class="button">Einstellungen</a>
        </p>
        <?php
    }
}

// Initialisiere Setup-Wizard
new CJT_Setup_Wizard();
