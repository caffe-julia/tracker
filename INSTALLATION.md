# 🔐 Caffe Julia Tracker - Sichere Installation

## Installationsanleitung nach höchsten Cybersecurity-Standards

Version: 2.0.0
Letzte Aktualisierung: 2025

---

## 📋 Inhaltsverzeichnis

1. [Systemvoraussetzungen](#systemvoraussetzungen)
2. [Sicherheitsvorbereitung](#sicherheitsvorbereitung)
3. [Schritt-für-Schritt Installation](#installation)
4. [Datenbank-Setup](#datenbank-setup)
5. [Konfiguration](#konfiguration)
6. [Sicherheits-Härtung](#sicherheits-härtung)
7. [WordPress-Integration](#wordpress-integration)
8. [Wartung & Updates](#wartung)
9. [Troubleshooting](#troubleshooting)
10. [Sicherheits-Checkliste](#sicherheits-checkliste)

---

## 📦 Systemvoraussetzungen

### Minimale Anforderungen

- **Webserver**: Apache 2.4+ oder Nginx 1.18+
- **PHP**: Version 7.4+ (empfohlen: PHP 8.1+)
- **MySQL**: Version 5.7+ oder MariaDB 10.3+
- **SSL-Zertifikat**: HTTPS ist zwingend erforderlich (Let's Encrypt empfohlen)
- **Festplatte**: Mindestens 100 MB freier Speicher

### Erforderliche PHP-Extensions

```bash
php -m | grep -E 'pdo|pdo_mysql|openssl|json|mbstring|session'
```

Benötigte Extensions:
- `pdo`
- `pdo_mysql`
- `openssl`
- `json`
- `mbstring`
- `session`

### Empfohlene Apache-Module

```bash
a2enmod rewrite
a2enmod headers
a2enmod deflate
a2enmod expires
a2enmod ssl
```

---

## 🔒 Sicherheitsvorbereitung

### 1. Server-Absicherung

**Firewall konfigurieren (UFW)**:
```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

**Fail2Ban installieren**:
```bash
sudo apt-get install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 2. MySQL-Sicherheit

**MySQL-Installation absichern**:
```bash
sudo mysql_secure_installation
```

Antworten Sie:
- ✅ Root-Passwort setzen
- ✅ Anonyme Benutzer entfernen
- ✅ Root-Login remote deaktivieren
- ✅ Test-Datenbank entfernen
- ✅ Privilegien neu laden

---

## 🚀 Installation

### Schritt 1: Repository klonen

```bash
cd /var/www/html
sudo git clone https://github.com/IHR-REPO/tracker.git caffe-julia-tracker
cd caffe-julia-tracker
```

### Schritt 2: Berechtigungen setzen

```bash
# Setze Besitzer (www-data für Apache, nginx für Nginx)
sudo chown -R www-data:www-data .

# Verzeichnisse: 755
sudo find . -type d -exec chmod 755 {} \;

# Dateien: 644
sudo find . -type f -exec chmod 644 {} \;

# Logs-Verzeichnis: 770
sudo chmod 770 logs/

# Config-Verzeichnis: 750
sudo chmod 750 config/
```

### Schritt 3: Verzeichnisse erstellen

```bash
mkdir -p logs uploads
chmod 770 logs
chmod 750 config
```

---

## 💾 Datenbank-Setup

### Schritt 1: Datenbank-Benutzer erstellen

**Als MySQL-Root einloggen**:
```bash
sudo mysql -u root -p
```

**SQL-Befehle ausführen**:
```sql
-- Datenbank erstellen
CREATE DATABASE caffe_julia_tracker
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Benutzer erstellen (STARKES PASSWORT verwenden!)
CREATE USER 'caffe_julia_app'@'localhost'
    IDENTIFIED BY 'HIER_SEHR_STARKES_PASSWORT';

-- Berechtigungen vergeben (Principle of Least Privilege)
GRANT SELECT, INSERT, UPDATE, DELETE
    ON caffe_julia_tracker.*
    TO 'caffe_julia_app'@'localhost';

-- Privilegien neu laden
FLUSH PRIVILEGES;

-- Verbindung testen
SHOW GRANTS FOR 'caffe_julia_app'@'localhost';

-- Ausloggen
EXIT;
```

### Schritt 2: Schema importieren

```bash
mysql -u caffe_julia_app -p caffe_julia_tracker < database/schema.sql
```

**Prüfen Sie die Installation**:
```bash
mysql -u caffe_julia_app -p caffe_julia_tracker -e "SHOW TABLES;"
```

Sie sollten folgende Tabellen sehen:
- users
- sessions
- events
- muehlen
- verbrauch
- audit_log
- settings

### Schritt 3: Standard-Admin-Passwort ändern

**WICHTIG**: Der Standard-Admin hat folgende Zugangsdaten:
- **Username**: `admin`
- **Passwort**: `CyberSecure2025!`

**⚠️ ÄNDERN SIE DIESES PASSWORT SOFORT!**

```sql
mysql -u caffe_julia_app -p caffe_julia_tracker

-- Neues Passwort-Hash generieren (in PHP):
-- password_hash('IHR_NEUES_SICHERES_PASSWORT', PASSWORD_BCRYPT)

UPDATE users
SET password_hash = '$2y$10$HIER_IHR_NEUER_HASH'
WHERE username = 'admin';

EXIT;
```

Oder über PHP:
```bash
php -r "echo password_hash('IHR_NEUES_PASSWORT', PASSWORD_BCRYPT);"
```

---

## ⚙️ Konfiguration

### Schritt 1: Config-Datei erstellen

```bash
cd config
cp config.example.php config.php
chmod 600 config.php
```

### Schritt 2: config.php anpassen

Öffnen Sie `config/config.php` und ändern Sie:

**Datenbank-Zugangsdaten**:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'caffe_julia_tracker');
define('DB_USER', 'caffe_julia_app');
define('DB_PASS', 'HIER_IHR_DATENBANK_PASSWORT');
```

**Encryption Key generieren**:
```bash
# Generiere 32-Byte Key
openssl rand -base64 32
```

Fügen Sie den Key in config.php ein:
```php
define('ENCRYPTION_KEY', 'HIER_DER_GENERIERTE_KEY');
```

**JWT Secret generieren** (falls benötigt):
```bash
openssl rand -base64 64
```

```php
define('JWT_SECRET', 'HIER_DER_GENERIERTE_SECRET');
```

**App-URL anpassen**:
```php
define('APP_URL', 'https://ihre-domain.de/caffe-julia-tracker');
define('APP_PATH', '/var/www/html/caffe-julia-tracker');
```

**Produktions-Einstellungen**:
```php
define('APP_ENV', 'production');
define('APP_DEBUG', false);
```

### Schritt 3: Berechtigungen prüfen

```bash
ls -la config/
# config.php sollte: -rw------- (600) sein
```

---

## 🛡️ Sicherheits-Härtung

### 1. SSL/TLS konfigurieren (Let's Encrypt)

```bash
# Certbot installieren
sudo apt-get install certbot python3-certbot-apache

# Zertifikat beantragen
sudo certbot --apache -d ihre-domain.de
```

**Automatische Erneuerung einrichten**:
```bash
sudo crontab -e
# Fügen Sie hinzu:
0 3 * * * /usr/bin/certbot renew --quiet
```

### 2. Apache-Konfiguration

Erstellen Sie VirtualHost: `/etc/apache2/sites-available/caffe-julia-tracker.conf`

```apache
<VirtualHost *:80>
    ServerName ihre-domain.de
    Redirect permanent / https://ihre-domain.de/
</VirtualHost>

<VirtualHost *:443>
    ServerName ihre-domain.de
    DocumentRoot /var/www/html/caffe-julia-tracker

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/ihre-domain.de/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/ihre-domain.de/privkey.pem

    # Moderne SSL-Konfiguration
    SSLProtocol all -SSLv3 -TLSv1 -TLSv1.1
    SSLCipherSuite HIGH:!aNULL:!MD5:!3DES
    SSLHonorCipherOrder on

    # HSTS
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

    <Directory /var/www/html/caffe-julia-tracker>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/caffe-julia-error.log
    CustomLog ${APACHE_LOG_DIR}/caffe-julia-access.log combined
</VirtualHost>
```

**Aktivieren**:
```bash
sudo a2ensite caffe-julia-tracker
sudo systemctl reload apache2
```

### 3. PHP-Sicherheit

Bearbeiten Sie `/etc/php/8.1/apache2/php.ini`:

```ini
# Sicherheitseinstellungen
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log

# File Upload
file_uploads = Off
upload_max_filesize = 5M
post_max_size = 8M

# Sessions
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = Strict
session.use_strict_mode = 1

# Disable gefährliche Funktionen
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
```

**Apache neu starten**:
```bash
sudo systemctl restart apache2
```

### 4. MySQL-Sicherheits-Einstellungen

Bearbeiten Sie `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# Bind nur auf localhost
bind-address = 127.0.0.1

# Deaktiviere LOCAL INFILE
local-infile = 0

# Logging
log_error = /var/log/mysql/error.log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log

# Limitiere Connections
max_connections = 100
max_connect_errors = 10
```

**MySQL neu starten**:
```bash
sudo systemctl restart mysql
```

### 5. Automatische Updates einrichten

```bash
# Unattended-upgrades installieren
sudo apt-get install unattended-upgrades

# Konfigurieren
sudo dpkg-reconfigure --priority=low unattended-upgrades
```

---

## 🔌 WordPress-Integration

### Installation

1. **Plugin kopieren**:
```bash
cp -r wordpress/ /var/www/html/wordpress/wp-content/plugins/caffe-julia-tracker/
```

2. **In WordPress aktivieren**:
   - Gehen Sie zu Admin → Plugins
   - Aktivieren Sie "Caffe Julia Tracker Integration"

3. **Konfigurieren**:
   - Tracker → Einstellungen
   - API-URL eingeben: `https://ihre-domain.de/caffe-julia-tracker/api`
   - Token eingeben (optional)

4. **Shortcode verwenden**:
```
[caffe_julia_tracker]
```

Siehe `wordpress/README.md` für Details.

---

## 🔧 Wartung & Updates

### Backup-Strategie

**1. Datenbank-Backup (täglich)**:
```bash
#!/bin/bash
# /usr/local/bin/backup-tracker-db.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/tracker"
DB_NAME="caffe_julia_tracker"

mkdir -p $BACKUP_DIR

# Backup erstellen
mysqldump -u caffe_julia_app -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/backup_$DATE.sql.gz

# Alte Backups löschen (älter als 30 Tage)
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +30 -delete
```

**Cron-Job einrichten**:
```bash
sudo crontab -e
# Täglich um 2 Uhr
0 2 * * * /usr/local/bin/backup-tracker-db.sh
```

**2. Datei-Backup (wöchentlich)**:
```bash
# Backup des gesamten Verzeichnisses
tar -czf /var/backups/tracker/files_$(date +%Y%m%d).tar.gz \
    /var/www/html/caffe-julia-tracker \
    --exclude='logs/*' \
    --exclude='node_modules'
```

### Log-Rotation

Erstellen Sie `/etc/logrotate.d/caffe-julia-tracker`:

```
/var/www/html/caffe-julia-tracker/logs/*.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 0640 www-data www-data
}
```

### Update-Prozess

```bash
# 1. Backup erstellen (siehe oben)

# 2. Wartungsmodus aktivieren (optional)
touch maintenance.flag

# 3. Code aktualisieren
git pull origin main

# 4. Datenbank-Migrationen (falls vorhanden)
mysql -u caffe_julia_app -p caffe_julia_tracker < database/migrations/XXX.sql

# 5. Cache leeren
rm -rf logs/*

# 6. Wartungsmodus deaktivieren
rm maintenance.flag
```

---

## 🆘 Troubleshooting

### Problem: "Datenbankverbindung fehlgeschlagen"

**Lösung**:
```bash
# 1. MySQL-Status prüfen
sudo systemctl status mysql

# 2. Verbindung testen
mysql -u caffe_julia_app -p caffe_julia_tracker

# 3. Logs prüfen
tail -f logs/app-*.log
tail -f /var/log/mysql/error.log
```

### Problem: "500 Internal Server Error"

**Lösung**:
```bash
# 1. Apache Error Log prüfen
tail -f /var/log/apache2/caffe-julia-error.log

# 2. PHP Error Log prüfen
tail -f /var/log/php_errors.log

# 3. Berechtigungen prüfen
ls -la config/
ls -la logs/
```

### Problem: "Session abgelaufen"

**Lösung**:
```sql
-- Alte Sessions löschen
mysql -u caffe_julia_app -p caffe_julia_tracker

DELETE FROM sessions WHERE expires_at < NOW();
EXIT;
```

### Problem: API gibt "403 Forbidden"

**Lösung**:
```bash
# 1. .htaccess prüfen
cat api/.htaccess

# 2. mod_rewrite aktivieren
sudo a2enmod rewrite
sudo systemctl restart apache2

# 3. AllowOverride All in VHost setzen
```

---

## ✅ Sicherheits-Checkliste

Nach der Installation prüfen:

- [ ] HTTPS ist aktiv und erzwungen
- [ ] SSL-Zertifikat ist gültig
- [ ] Standard-Admin-Passwort wurde geändert
- [ ] Datenbank-Passwort ist stark (min. 20 Zeichen)
- [ ] config.php hat Berechtigung 600
- [ ] Firewall (UFW) ist aktiviert
- [ ] Fail2Ban ist aktiv
- [ ] Automatische Updates sind konfiguriert
- [ ] Backup-Jobs sind eingerichtet
- [ ] Log-Rotation ist konfiguriert
- [ ] PHP display_errors = Off
- [ ] Alle .htaccess Dateien sind vorhanden
- [ ] MySQL ist nur auf localhost erreichbar
- [ ] Unnötige PHP-Funktionen sind deaktiviert
- [ ] Security-Headers werden gesetzt (prüfen mit securityheaders.com)
- [ ] CSRF-Token funktioniert
- [ ] Rate-Limiting ist aktiv
- [ ] Audit-Log schreibt Einträge

---

## 📞 Support

Bei Fragen oder Problemen:
- **Email**: admin@caffejulia.com
- **Telefon**: +41 XX XXX XX XX

**Security-Probleme melden**:
- Bitte melden Sie Sicherheitslücken vertraulich an: security@caffejulia.com

---

## 📄 Lizenz

Dieses Projekt ist proprietär. Alle Rechte vorbehalten.

---

**Viel Erfolg mit Ihrem sicheren Caffe Julia Tracker! ☕🔐**
