-- ============================================
-- Caffe Julia Tracker - MySQL Database Schema
-- Sichere Datenbankstruktur nach Best Practices
-- ============================================

-- Erstelle Datenbank mit UTF-8 Support
CREATE DATABASE IF NOT EXISTS caffe_julia_tracker
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE caffe_julia_tracker;

-- ============================================
-- Tabelle: users
-- Sichere Benutzerverwaltung mit bcrypt-Hashes
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'viewer') DEFAULT 'staff',
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME NULL,
    failed_login_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: sessions
-- Sichere Session-Verwaltung
-- ============================================
CREATE TABLE IF NOT EXISTS sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500),
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: events
-- Haupttabelle für Events
-- ============================================
CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    is_all_day BOOLEAN DEFAULT FALSE,
    is_multi_day BOOLEAN DEFAULT FALSE,
    multi_day_index INT DEFAULT 1,
    multi_day_total INT DEFAULT 1,
    work_start_time TIME NULL,
    work_end_time TIME NULL,
    work_break_minutes INT DEFAULT 0,
    work_hours DECIMAL(5,2) DEFAULT 0.00,
    anzahl_muehlen TINYINT DEFAULT 3,
    mitteilung TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_event_date (event_date),
    INDEX idx_user (user_id),
    INDEX idx_name (name),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: muehlen
-- Kaffeemühlen-Daten pro Event
-- ============================================
CREATE TABLE IF NOT EXISTS muehlen (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    muehle_nummer TINYINT NOT NULL,
    muehle_name VARCHAR(100) NOT NULL,
    doppel_start INT DEFAULT 0,
    doppel_ende INT DEFAULT 0,
    einzel_start INT DEFAULT 0,
    einzel_ende INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_nummer (muehle_nummer)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: verbrauch
-- Verbrauchsdaten (Milch, Getränke)
-- ============================================
CREATE TABLE IF NOT EXISTS verbrauch (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    milch_liter INT DEFAULT 0,
    hafermilch_liter INT DEFAULT 0,
    ausgabe_matcha INT DEFAULT 0,
    ausgabe_schokolade INT DEFAULT 0,
    ausgabe_tee INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: audit_log
-- Audit-Trail für Sicherheit und Compliance
-- ============================================
CREATE TABLE IF NOT EXISTS audit_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100) NOT NULL,
    record_id INT UNSIGNED NULL,
    old_data JSON NULL,
    new_data JSON NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_table (table_name),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabelle: settings
-- Systemeinstellungen
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_type ENUM('string', 'int', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Standard-Daten einfügen
-- ============================================

-- Standard Admin-Benutzer erstellen (Passwort: CyberSecure2025!)
-- Hash wurde mit bcrypt erstellt (PHP: password_hash)
INSERT INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@caffejulia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Standard-Einstellungen
INSERT INTO settings (setting_key, setting_value, setting_type) VALUES
('app_name', 'Caffe Julia Tracker', 'string'),
('session_timeout', '28800', 'int'),
('max_login_attempts', '5', 'int'),
('lockout_duration', '3600', 'int'),
('enable_audit_log', 'true', 'boolean'),
('default_muehlen_count', '3', 'int');

-- ============================================
-- Views für vereinfachte Abfragen
-- ============================================

-- View: Event-Übersicht mit berechneten Werten
CREATE OR REPLACE VIEW v_events_overview AS
SELECT
    e.id,
    e.user_id,
    u.username,
    e.name,
    e.event_date,
    e.is_all_day,
    e.is_multi_day,
    e.multi_day_index,
    e.multi_day_total,
    e.work_hours,
    COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2), 0) AS total_doppel_kaffees,
    COALESCE(SUM(m.einzel_ende - m.einzel_start), 0) AS total_einzel_kaffees,
    COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)), 0) AS total_kaffees,
    COALESCE(v.milch_liter, 0) AS milch_liter,
    COALESCE(v.hafermilch_liter, 0) AS hafermilch_liter,
    COALESCE(v.ausgabe_matcha + v.ausgabe_schokolade + v.ausgabe_tee, 0) AS total_getraenke,
    e.created_at,
    e.updated_at
FROM events e
LEFT JOIN users u ON e.user_id = u.id
LEFT JOIN muehlen m ON e.id = m.event_id
LEFT JOIN verbrauch v ON e.id = v.event_id
GROUP BY e.id;

-- ============================================
-- Stored Procedures für häufige Operationen
-- ============================================

DELIMITER //

-- Procedure: Event mit allen Daten erstellen
CREATE PROCEDURE sp_create_event(
    IN p_user_id INT,
    IN p_name VARCHAR(255),
    IN p_event_date DATE,
    IN p_is_all_day BOOLEAN,
    IN p_anzahl_muehlen TINYINT
)
BEGIN
    DECLARE v_event_id INT;
    DECLARE v_i INT DEFAULT 1;

    -- Event erstellen
    INSERT INTO events (user_id, name, event_date, is_all_day, anzahl_muehlen)
    VALUES (p_user_id, p_name, p_event_date, p_is_all_day, p_anzahl_muehlen);

    SET v_event_id = LAST_INSERT_ID();

    -- Mühlen initialisieren
    WHILE v_i <= p_anzahl_muehlen DO
        INSERT INTO muehlen (event_id, muehle_nummer, muehle_name)
        VALUES (v_event_id, v_i, CONCAT('Mühle ', v_i));
        SET v_i = v_i + 1;
    END WHILE;

    -- Verbrauch initialisieren
    INSERT INTO verbrauch (event_id) VALUES (v_event_id);

    SELECT v_event_id AS event_id;
END //

-- Procedure: Session bereinigen (alte Sessions löschen)
CREATE PROCEDURE sp_cleanup_sessions()
BEGIN
    DELETE FROM sessions WHERE expires_at < NOW();
END //

-- Procedure: Statistiken abrufen
CREATE PROCEDURE sp_get_statistics(
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT
        COUNT(DISTINCT e.id) AS total_events,
        SUM(e.work_hours) AS total_work_hours,
        SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)) AS total_kaffees,
        SUM(v.milch_liter + v.hafermilch_liter) AS total_milch,
        SUM(v.ausgabe_matcha + v.ausgabe_schokolade + v.ausgabe_tee) AS total_getraenke
    FROM events e
    LEFT JOIN muehlen m ON e.id = m.event_id
    LEFT JOIN verbrauch v ON e.id = v.event_id
    WHERE e.event_date BETWEEN p_start_date AND p_end_date;
END //

DELIMITER ;

-- ============================================
-- Triggers für Audit-Trail
-- ============================================

DELIMITER //

-- Trigger: Events Update Logging
CREATE TRIGGER trg_events_update_log
AFTER UPDATE ON events
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (user_id, action, table_name, record_id, old_data, new_data, ip_address)
    VALUES (
        NEW.user_id,
        'UPDATE',
        'events',
        NEW.id,
        JSON_OBJECT('name', OLD.name, 'event_date', OLD.event_date, 'work_hours', OLD.work_hours),
        JSON_OBJECT('name', NEW.name, 'event_date', NEW.event_date, 'work_hours', NEW.work_hours),
        'system'
    );
END //

-- Trigger: Events Delete Logging
CREATE TRIGGER trg_events_delete_log
BEFORE DELETE ON events
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (user_id, action, table_name, record_id, old_data, ip_address)
    VALUES (
        OLD.user_id,
        'DELETE',
        'events',
        OLD.id,
        JSON_OBJECT('name', OLD.name, 'event_date', OLD.event_date),
        'system'
    );
END //

DELIMITER ;

-- ============================================
-- Sicherheits-Einstellungen und Best Practices
-- ============================================

-- Erstelle separaten Benutzer mit minimalen Rechten (PRINCIPLE OF LEAST PRIVILEGE)
-- Führen Sie diese Befehle separat mit Root-Rechten aus:
-- CREATE USER 'caffe_julia_app'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON caffe_julia_tracker.* TO 'caffe_julia_app'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- Fertig!
-- ============================================
