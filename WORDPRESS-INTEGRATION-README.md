# WordPress-Integration: Komplettlösung für Caffe Julia Tracker

## 📦 Übersicht

Diese Lösung besteht aus **zwei Teilen**:

1. **WordPress-Plugin** (Frontend-Integration)
2. **API-Backend** (Server mit MySQL-Datenbank)

Beide Teile müssen installiert werden, damit der Tracker funktioniert.

---

## 🎯 Was Sie bekommen

Nach der Installation haben Sie:

✅ **Vollständig funktionierenden Tracker** auf Ihrer WordPress-Seite
✅ **MySQL-Datenbank** mit allen Events, Verbrauchsdaten, Statistiken
✅ **Sichere API** mit Authentifizierung, Verschlüsselung, CSRF-Schutz
✅ **WordPress-Integration** mit Shortcodes, Gutenberg-Blocks, Widgets
✅ **Live-Statistiken** im WordPress-Dashboard
✅ **Responsive Design** für Desktop und Mobile

---

## 📥 Downloads

### Teil 1: WordPress-Plugin

**Datei:** `caffe-julia-tracker.zip` (32 KB)

**Installation:**
1. WordPress-Admin öffnen
2. Plugins → Installieren → Plugin hochladen
3. ZIP-Datei auswählen und installieren
4. Plugin aktivieren

### Teil 2: Server-Files (API-Backend)

**Datei:** `caffe-julia-server-files.zip` (43 KB)

**Inhalt:**
- `api/` - PHP-Backend mit RESTful API
- `config/` - Konfigurationsdateien
- `database/` - MySQL-Schema
- `.htaccess` - Sicherheitseinstellungen
- `index.html` - Tracker-Oberfläche
- `error.html` - Fehlerseiten

**Installation:**
Via FTP/SFTP auf Ihren Webserver hochladen → siehe Installationsanleitung

---

## 🚀 Installationsanleitungen

### Quick-Start (Schnelleinstieg)

📄 **QUICK-START.md** - 5-Schritte-Anleitung (2 Minuten Lesezeit)

Für erfahrene Nutzer, die schnell loslegen wollen.

### Ausführliche Anleitung

📄 **WORDPRESS-API-INSTALLATION.md** - Detaillierte Schritt-für-Schritt-Anleitung (10 Minuten Lesezeit)

Mit Screenshots-Beschreibungen, Troubleshooting, Sicherheitshinweisen.

### Allgemeine Dokumentation

📄 **INSTALLATION.md** - Server-Setup, PHP-Konfiguration, MySQL-Hardening
📄 **SECURITY.md** - Sicherheitsfeatures, Best Practices
📄 **README.md** - Allgemeine Übersicht

---

## 🔧 Installation auf einen Blick

### Schritt 1: WordPress-Plugin installieren ✅

1. WordPress-Admin → Plugins → Installieren
2. `caffe-julia-tracker.zip` hochladen
3. Aktivieren

**Status:** Wenn Sie das bereits gemacht haben → ✅ Erledigt!

### Schritt 2: Server-Files hochladen

1. `caffe-julia-server-files.zip` entpacken
2. Via FTP auf Webserver hochladen nach `/public_html/tracker/`
3. Struktur:
   ```
   /public_html/tracker/
   ├── api/
   ├── config/
   ├── database/
   ├── logs/ (leer erstellen)
   ├── .htaccess
   ├── index.html
   └── error.html
   ```

### Schritt 3: MySQL-Datenbank erstellen

Via phpMyAdmin:
1. Datenbank erstellen: `caffe_julia_tracker`
2. Benutzer erstellen: `caffe_julia_app`
3. Schema importieren: `database/schema.sql`

### Schritt 4: Konfiguration

`config/config.example.php` → `config/config.php` kopieren und anpassen:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'caffe_julia_tracker');
define('DB_USER', 'caffe_julia_app');
define('DB_PASS', 'IHR_PASSWORT');

define('ENCRYPTION_KEY', 'ZUFÄLLIGER_64_ZEICHEN_KEY');

define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
]);
```

### Schritt 5: WordPress-Plugin konfigurieren

WordPress-Admin → Caffe Julia Tracker → Einstellungen:

- **API-URL:** `https://www.caffejulia.ch/tracker/api`
- **Cache aktivieren:** ✅ Ja
- Speichern

### Schritt 6: Tracker auf Seite einfügen

Seite bearbeiten und einfügen:

```
[caffe_julia_tracker height="800px"]
```

Oder Gutenberg-Block verwenden: "Caffe Julia Tracker"

---

## 🧪 Funktionstest

### Test 1: API erreichbar?

Öffnen Sie:
```
https://www.caffejulia.ch/tracker/api/
```

**Erwartete Antwort:**
```json
{
  "success": true,
  "message": "Caffe Julia Tracker API",
  "version": "2.0",
  "status": "running"
}
```

### Test 2: Tracker lädt?

Öffnen Sie:
```
https://www.caffejulia.ch/tracker/
```

**Erwartung:** Login-Maske wird angezeigt

**Test-Login:**
- Benutzername: `admin`
- Passwort: `admin123`

⚠️ **Passwort sofort ändern!**

### Test 3: WordPress-Verbindung?

WordPress-Admin → Caffe Julia Tracker → Dashboard

**Erwartung:** Statistiken werden angezeigt (Events, Kaffees, Stunden, Milch)

### Test 4: Widget auf Seite?

Öffnen Sie Ihre WordPress-Seite mit dem Shortcode/Block

**Erwartung:** Tracker wird in iFrame angezeigt

---

## ❌ Troubleshooting

### Problem: "Failed to load resource: 404"

**Ursache:** API-Backend nicht installiert oder URL falsch

**Lösung:**
1. Prüfen Sie via FTP: Existiert `/public_html/tracker/api/`?
2. Testen Sie: https://www.caffejulia.ch/tracker/api/
3. Prüfen Sie Plugin-Einstellungen → API-URL korrekt?

### Problem: "Database connection failed"

**Ursache:** Falsche Datenbank-Zugangsdaten

**Lösung:**
1. Öffnen Sie `config/config.php` via FTP
2. Prüfen Sie: DB_HOST, DB_NAME, DB_USER, DB_PASS
3. Testen Sie Zugangsdaten in phpMyAdmin

### Problem: CORS-Fehler in Browser-Console

**Ursache:** WordPress-Domain nicht in CORS erlaubt

**Lösung:**
Öffnen Sie `config/config.php` und fügen Sie hinzu:
```php
define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
    'https://caffejulia.ch',
    'http://localhost',  // für lokale Tests
]);
```

### Problem: Nichts wird angezeigt (leere Seite)

**Ursache:** PHP-Fehler oder fehlende Dateien

**Lösung:**
1. Prüfen Sie PHP-Error-Logs Ihres Hosters
2. Prüfen Sie `/tracker/logs/app.log`
3. Prüfen Sie Browser-Console (F12)

### Problem: "Permission denied" beim Schreiben

**Ursache:** Falsche Dateiberechtigungen

**Lösung:**
Via FTP Berechtigungen setzen:
- Ordner `logs/` → 755
- Dateien → 644
- `config/config.php` → 640

---

## 🔒 Sicherheit

Nach der Installation **sofort durchführen:**

1. ✅ **Admin-Passwort ändern** (Standard: admin123)
2. ✅ **ENCRYPTION_KEY generieren** (64 zufällige Zeichen)
3. ✅ **config.php schützen** (Berechtigung 640)
4. ✅ **HTTPS aktivieren** (falls noch nicht aktiv)
5. ✅ **Backups einrichten** (täglich/wöchentlich)

**Empfohlen:**
- Regelmäßige Updates von PHP und MySQL
- Starke Passwörter für alle Benutzer
- Zwei-Faktor-Authentifizierung für WordPress-Admin
- Firewall-Regeln (falls verfügbar)
- Monitoring der Logs

---

## 📚 Technische Details

### Systemvoraussetzungen

**Server:**
- PHP 7.4 oder höher
- MySQL 5.7+ oder MariaDB 10.3+
- Apache mit mod_rewrite (oder Nginx)
- SSL/HTTPS-Zertifikat (empfohlen)

**WordPress:**
- WordPress 5.8 oder höher
- PHP 7.4 oder höher

### Features

**Sicherheit:**
- ✅ PDO mit Prepared Statements (SQL Injection-Schutz)
- ✅ Bcrypt Password Hashing (Cost Factor 12)
- ✅ CSRF-Protection mit Tokens
- ✅ XSS-Protection durch Sanitization
- ✅ Rate Limiting (Brute Force-Schutz)
- ✅ Session Token Rotation
- ✅ Audit Logging
- ✅ AES-256-GCM Verschlüsselung
- ✅ Security Headers (CSP, HSTS, X-Frame-Options)

**WordPress-Integration:**
- ✅ Shortcodes: `[caffe_julia_tracker]`, `[caffe_julia_stats]`
- ✅ Gutenberg-Blocks (2 Blocks)
- ✅ Sidebar-Widget
- ✅ Admin-Dashboard mit Live-Statistiken
- ✅ REST-API-Integration
- ✅ i18n-ready (Übersetzungen)
- ✅ Caching-System (Transients)

**API:**
- ✅ RESTful API-Design
- ✅ JSON-Responses
- ✅ CORS-Support
- ✅ Token-basierte Authentifizierung
- ✅ Rate Limiting (100 Requests/Minute)
- ✅ Health-Check-Endpoint

---

## 🆘 Support

### Logs prüfen

**Server-Logs:**
- `/public_html/tracker/logs/app.log`
- PHP-Error-Logs Ihres Hosters

**Browser-Logs:**
- F12 → Console (Chrome/Firefox)
- Fehler in rot werden angezeigt

### API direkt testen

**Health-Check:**
```bash
curl https://www.caffejulia.ch/tracker/api/
```

**Login-Test:**
```bash
curl -X POST https://www.caffejulia.ch/tracker/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

### WordPress-Debug-Modus

In `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Logs: `/wp-content/debug.log`

---

## 📁 Dateistruktur

### Auf dem Server (`/public_html/tracker/`)

```
tracker/
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
│   ├── config.php (erstellen!)
│   └── config.example.php
├── database/
│   └── schema.sql
├── logs/
│   └── app.log (automatisch erstellt)
├── .htaccess
├── error.html
└── index.html
```

### Im WordPress (`/wp-content/plugins/caffe-julia-tracker/`)

```
caffe-julia-tracker/
├── includes/
│   ├── admin/
│   ├── blocks/
│   ├── templates/
│   ├── widgets/
│   └── class-caffe-julia-tracker.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── languages/
├── caffe-julia-tracker.php
└── readme.txt
```

---

## ✅ Checkliste für erfolgreiche Installation

- [ ] WordPress-Plugin installiert und aktiviert
- [ ] Server-Files hochgeladen nach `/public_html/tracker/`
- [ ] MySQL-Datenbank erstellt (`caffe_julia_tracker`)
- [ ] Datenbank-Benutzer erstellt (`caffe_julia_app`)
- [ ] Schema importiert (`database/schema.sql`)
- [ ] `config/config.php` erstellt und konfiguriert
- [ ] Dateiberechtigungen gesetzt (logs/ → 755, config.php → 640)
- [ ] API-Test erfolgreich: https://www.caffejulia.ch/tracker/api/
- [ ] Tracker-Test erfolgreich: https://www.caffejulia.ch/tracker/
- [ ] WordPress-Plugin konfiguriert (API-URL eingetragen)
- [ ] Dashboard zeigt Statistiken an
- [ ] Tracker auf Seite eingefügt (Shortcode/Block)
- [ ] Admin-Passwort geändert
- [ ] ENCRYPTION_KEY generiert
- [ ] HTTPS aktiv
- [ ] Backups eingerichtet

---

## 🎉 Fertig!

Nach erfolgreicher Installation haben Sie:

✅ **Vollständig funktionierenden Tracker** auf www.caffejulia.ch/tracker
✅ **WordPress-Integration** mit Widgets und Shortcodes
✅ **Sichere MySQL-Datenbank** mit Verschlüsselung
✅ **Live-Statistiken** im WordPress-Dashboard
✅ **Responsive Design** für alle Geräte

**Viel Erfolg mit Ihrem Caffe Julia Tracker! ☕**

---

## 📞 Kontakt

Bei Fragen oder Problemen:

1. **Logs prüfen:** `/tracker/logs/app.log`
2. **Browser-Console:** F12 → Console
3. **API testen:** https://www.caffejulia.ch/tracker/api/

---

**Version:** 2.0
**Letztes Update:** Oktober 2024
