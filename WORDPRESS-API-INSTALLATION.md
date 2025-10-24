# WordPress-Integration: API-Backend Installation

## Übersicht

Sie haben das WordPress-Plugin bereits installiert. Jetzt müssen Sie noch das **API-Backend** auf Ihrem Webserver installieren, damit das Plugin funktioniert.

## Was Sie brauchen

- FTP/SFTP-Zugang zu Ihrem Webserver (www.caffejulia.ch)
- Zugang zu phpMyAdmin (oder MySQL-Kommandozeile)
- Die Dateien aus diesem Repository

---

## Schritt 1: Dateien auf den Webserver hochladen

### 1.1 Verbinden Sie sich via FTP/SFTP

Nutzen Sie ein FTP-Programm wie:
- **FileZilla** (kostenlos, Windows/Mac/Linux)
- **Cyberduck** (kostenlos, Mac/Windows)
- Oder das FTP-Tool Ihres Hosting-Anbieters

Verbindungsdaten (erhalten Sie von Ihrem Hoster):
- Host: ftp.caffejulia.ch (oder www.caffejulia.ch)
- Benutzername: [Ihr FTP-Benutzername]
- Passwort: [Ihr FTP-Passwort]
- Port: 21 (FTP) oder 22 (SFTP - empfohlen!)

### 1.2 Erstellen Sie den Tracker-Ordner

Navigieren Sie zu Ihrem **public_html** oder **www** Ordner und erstellen Sie einen neuen Ordner:

```
/public_html/tracker/
```

### 1.3 Laden Sie folgende Dateien/Ordner hoch

Aus Ihrem lokalen `/home/user/tracker/` Verzeichnis in den `/public_html/tracker/` Ordner auf dem Server:

**Ordner (mit allen Unterordnern und Dateien):**
- `api/` → kompletter Ordner mit allen PHP-Dateien
- `config/` → kompletter Ordner
- `database/` → kompletter Ordner

**Einzelne Dateien:**
- `index.html` → Die Tracker-Oberfläche
- `.htaccess` → Sicherheitseinstellungen
- `error.html` → Fehlerseite

**Erstellen Sie zusätzlich einen leeren Ordner:**
- `logs/` → Für Log-Dateien (leer lassen)

### 1.4 Endstruktur auf dem Server

Nach dem Upload sollte Ihre Struktur so aussehen:

```
/public_html/tracker/
├── api/
│   ├── classes/
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   ├── Logger.php
│   │   └── Security.php
│   ├── endpoints/
│   │   ├── events.php
│   │   ├── export.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── muehlen.php
│   │   ├── statistics.php
│   │   ├── user.php
│   │   └── verbrauch.php
│   ├── index.php
│   └── init.php
├── config/
│   └── config.example.php
├── database/
│   └── schema.sql
├── logs/
│   └── (leer)
├── .htaccess
├── error.html
└── index.html
```

---

## Schritt 2: MySQL-Datenbank erstellen

### 2.1 Öffnen Sie phpMyAdmin

Loggen Sie sich in Ihr Hosting-Control-Panel ein (cPanel, Plesk, etc.) und öffnen Sie **phpMyAdmin**.

### 2.2 Neue Datenbank erstellen

1. Klicken Sie auf **"Neu"** oder **"Datenbanken"**
2. Erstellen Sie eine neue Datenbank:
   - **Name:** `caffe_julia_tracker` (oder einen eigenen Namen)
   - **Zeichensatz:** `utf8mb4_unicode_ci`

### 2.3 Datenbankbenutzer erstellen

1. Gehen Sie zu **"Benutzerkonten"** → **"Benutzerkonto hinzufügen"**
2. Erstellen Sie einen neuen Benutzer:
   - **Benutzername:** `caffe_julia_app` (oder eigener Name)
   - **Hostname:** `localhost`
   - **Passwort:** Starkes Passwort generieren (z.B. mit Passwort-Generator)
   - ✅ Alle Rechte auf die Datenbank `caffe_julia_tracker` vergeben

**WICHTIG: Notieren Sie sich:**
- Datenbankname
- Benutzername
- Passwort

### 2.4 Datenbankschema importieren

1. Wählen Sie Ihre Datenbank `caffe_julia_tracker` aus
2. Klicken Sie auf den Reiter **"Importieren"**
3. Klicken Sie auf **"Datei auswählen"**
4. Wählen Sie die Datei `database/schema.sql` aus Ihrem lokalen Ordner
5. Klicken Sie auf **"OK"**

Die Datenbank wird nun mit allen Tabellen, Stored Procedures und dem Standard-Admin-User erstellt.

**Standard-Login (nach Import):**
- **Benutzername:** `admin`
- **Passwort:** `admin123`
- ⚠️ **WICHTIG:** Ändern Sie dieses Passwort sofort nach dem ersten Login!

---

## Schritt 3: Konfigurationsdatei erstellen

### 3.1 config.php erstellen

Via FTP/SFTP:

1. Öffnen Sie den Ordner `/public_html/tracker/config/`
2. Kopieren Sie die Datei `config.example.php`
3. Benennen Sie die Kopie um in: `config.php`

### 3.2 config.php bearbeiten

Öffnen Sie `config.php` in einem Texteditor und passen Sie folgende Werte an:

```php
<?php
// === DATENBANK-KONFIGURATION ===
define('DB_HOST', 'localhost');  // Meist 'localhost', prüfen Sie bei Ihrem Hoster
define('DB_NAME', 'caffe_julia_tracker');  // Ihr Datenbankname aus Schritt 2.2
define('DB_USER', 'caffe_julia_app');  // Ihr DB-Benutzername aus Schritt 2.3
define('DB_PASS', 'IHR_STARKES_PASSWORT_HIER');  // Passwort aus Schritt 2.3

// === VERSCHLÜSSELUNG ===
// Generieren Sie einen zufälligen 64-Zeichen Schlüssel
// Online Tool: https://www.random.org/strings/
define('ENCRYPTION_KEY', 'GENERIEREN_SIE_EINEN_ZUFÄLLIGEN_64_ZEICHEN_KEY');

// === CORS (Cross-Origin) ===
define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
    'https://caffejulia.ch',
]);

// === SESSION ===
define('SESSION_COOKIE_SECURE', true);  // true = nur HTTPS (empfohlen!)
define('SESSION_COOKIE_SAMESITE', 'Lax');

// === API ===
define('API_VERSION', '2.0');
define('API_RATE_LIMIT', 100);  // Max. 100 Anfragen pro Minute

// === LOGGING ===
define('LOG_ERRORS', true);
define('LOG_DIR', __DIR__ . '/../logs');
define('LOG_FILE', LOG_DIR . '/app.log');

// === SECURITY ===
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRY', 3600);
define('BCRYPT_COST', 12);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900);  // 15 Minuten

// === DEVELOPMENT ===
define('ENVIRONMENT', 'production');  // 'production' oder 'development'
define('DEBUG_MODE', false);  // false in Production!
```

### 3.3 Speichern Sie die Datei

Speichern Sie `config.php` und laden Sie sie via FTP auf den Server hoch (falls Sie sie lokal bearbeitet haben).

---

## Schritt 4: Dateiberechtigungen setzen

### Via FTP (FileZilla)

Rechtsklick auf Ordner → **Dateiberechtigungen**:

- `api/` → **755**
- `config/` → **755**
- `config/config.php` → **640** (wichtig für Sicherheit!)
- `logs/` → **755** (und schreibbar für Webserver)
- `index.html` → **644**
- `.htaccess` → **644**

### Via SSH (falls verfügbar)

```bash
cd /public_html/tracker/
chmod -R 755 api/
chmod -R 755 config/
chmod 640 config/config.php
chmod -R 755 logs/
chmod 644 index.html
chmod 644 .htaccess
```

---

## Schritt 5: Testen der Installation

### 5.1 API-Health-Check

Öffnen Sie in Ihrem Browser:

```
https://www.caffejulia.ch/tracker/api/
```

Sie sollten eine JSON-Antwort sehen:

```json
{
  "success": true,
  "message": "Caffe Julia Tracker API",
  "version": "2.0",
  "status": "running"
}
```

### 5.2 Tracker-Oberfläche testen

Öffnen Sie:

```
https://www.caffejulia.ch/tracker/
```

Sie sollten den Tracker mit Login-Maske sehen.

**Testlogin:**
- Benutzername: `admin`
- Passwort: `admin123`

⚠️ **WICHTIG:** Ändern Sie das Admin-Passwort sofort!

---

## Schritt 6: WordPress-Plugin konfigurieren

### 6.1 WordPress-Admin öffnen

Gehen Sie zu:

```
https://www.caffejulia.ch/wp-admin/
```

### 6.2 Plugin-Einstellungen öffnen

1. Klicken Sie im WordPress-Menü auf **"Caffe Julia Tracker"** → **"Einstellungen"**

### 6.3 API-URL eintragen

Tragen Sie ein:

- **API-URL:** `https://www.caffejulia.ch/tracker/api`
- **API-Token:** (optional, später konfigurierbar)
- **Cache aktivieren:** ✅ (empfohlen)
- **Cache-Dauer:** `300` Sekunden (5 Minuten)

### 6.4 Speichern und Verbindung testen

1. Klicken Sie auf **"Änderungen speichern"**
2. Gehen Sie zum **Dashboard** (Caffe Julia Tracker → Dashboard)
3. Wenn die Verbindung funktioniert, sehen Sie **Live-Statistiken**

---

## Schritt 7: Tracker in WordPress-Seite einbauen

### Methode 1: Shortcode

Bearbeiten Sie Ihre Seite `www.caffejulia.ch/tracker` und fügen Sie ein:

```
[caffe_julia_tracker height="800px" show_stats="true"]
```

### Methode 2: Gutenberg-Block

1. Seite bearbeiten
2. Block hinzufügen (+)
3. Suchen nach **"Caffe Julia Tracker"**
4. Block einfügen
5. Höhe und Optionen im Block-Inspektor anpassen

### Methode 3: Widget (Sidebar)

1. **Design** → **Widgets**
2. Widget **"Caffe Julia Statistiken"** in gewünschte Sidebar ziehen
3. Titel und Zeitraum konfigurieren

---

## Troubleshooting

### Problem: 404-Fehler beim Laden der API

**Ursache:** API-Dateien sind nicht auf dem Server oder .htaccess funktioniert nicht

**Lösung:**
1. Prüfen Sie via FTP, ob alle Dateien hochgeladen wurden
2. Prüfen Sie, ob `.htaccess` vorhanden ist
3. Testen Sie: `https://www.caffejulia.ch/tracker/api/index.php` (direkt)

### Problem: "Database connection failed"

**Ursache:** Falsche Datenbank-Zugangsdaten in config.php

**Lösung:**
1. Öffnen Sie `config/config.php`
2. Prüfen Sie DB_HOST, DB_NAME, DB_USER, DB_PASS
3. Testen Sie die Zugangsdaten in phpMyAdmin

### Problem: CORS-Fehler in Browser-Console

**Ursache:** WordPress-Domain ist nicht in CORS_ALLOWED_ORIGINS

**Lösung:**
1. Öffnen Sie `config/config.php`
2. Fügen Sie Ihre Domain hinzu:
   ```php
   define('CORS_ALLOWED_ORIGINS', [
       'https://www.caffejulia.ch',
       'https://caffejulia.ch',
   ]);
   ```

### Problem: "Permission denied" beim Schreiben von Logs

**Ursache:** Ordner `logs/` ist nicht beschreibbar

**Lösung:**
```bash
chmod 755 logs/
```

Oder via FTP: Rechtsklick auf `logs/` → Dateiberechtigungen → 755

### Problem: Admin-Login funktioniert nicht

**Ursache:** Datenbankschema wurde nicht korrekt importiert

**Lösung:**
1. Öffnen Sie phpMyAdmin
2. Prüfen Sie, ob Tabelle `users` existiert
3. Falls nicht: Importieren Sie `database/schema.sql` erneut

---

## Sicherheitshinweise nach Installation

### Sofort durchführen:

1. ✅ **Admin-Passwort ändern** (Standard: admin123)
2. ✅ **ENCRYPTION_KEY generieren** (64 zufällige Zeichen)
3. ✅ **config.php Berechtigung auf 640 setzen**
4. ✅ **SSL/HTTPS aktivieren** (falls noch nicht aktiv)
5. ✅ **Backups einrichten** (Datenbank + Dateien)

### Empfohlen:

- Regelmäßige Datenbank-Backups (täglich/wöchentlich)
- Logs regelmäßig prüfen (`logs/app.log`)
- PHP und MySQL auf dem neuesten Stand halten
- Starke Passwörter für alle Benutzer

---

## Support

Bei Fragen oder Problemen:

1. **Logs prüfen:** `/public_html/tracker/logs/app.log`
2. **Browser-Console prüfen:** F12 → Console (Fehler sehen)
3. **API direkt testen:** `https://www.caffejulia.ch/tracker/api/`

---

## Zusammenfassung

✅ **Nach erfolgreicher Installation haben Sie:**

- Funktionierenden Tracker auf www.caffejulia.ch/tracker
- WordPress-Plugin verbunden mit der API
- Statistiken auf WordPress-Seiten und in Widgets
- Sichere MySQL-Datenbank mit verschlüsselten Daten
- HTTPS-geschützte API mit CORS-Sicherheit

**Viel Erfolg mit Ihrem Caffe Julia Tracker! ☕**
