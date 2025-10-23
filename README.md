# ☕ Caffe Julia Tracker - Sichere Webapplikation

## 🎯 Über das Projekt

Der Caffe Julia Tracker ist eine **professionelle, sichere Webapplikation** zur Erfassung und Verwaltung von Event-Daten für Ihr Café. Die Anwendung wurde nach den **höchsten Cybersecurity-Standards** entwickelt und bietet eine MySQL-basierte Datenpersistenz.

### ✨ Features

- 🔐 **Maximale Sicherheit**: Bcrypt-Passwort-Hashing, CSRF-Schutz, SQL-Injection-Prävention, XSS-Schutz
- 📊 **Event-Management**: Verwaltung von Events mit Datum, Arbeitszeit und Notizen
- ☕ **Kaffeemühlen-Tracking**: Erfassung von Doppel- und Einzelbezügen für bis zu 3 Mühlen
- 🥛 **Verbrauchserfassung**: Tracking von Milch, Hafermilch und anderen Getränken
- ⏱️ **Arbeitszeiterfassung**: Automatische Berechnung von Arbeitsstunden inkl. Pausen
- 📈 **Statistiken**: Umfassende Auswertungen und Reports
- 📥 **CSV-Export**: Datenexport für weitere Analysen
- 🔌 **WordPress-Integration**: Einfache Einbindung in WordPress-Websites
- 📱 **Responsive Design**: Optimiert für Desktop, Tablet und Mobile
- 🌐 **RESTful API**: Moderne API-Architektur
- 📝 **Audit-Logging**: Vollständige Protokollierung aller Aktivitäten

---

## 🚀 Schnellstart

### Voraussetzungen

- PHP 7.4+ (empfohlen: 8.1+)
- MySQL 5.7+ oder MariaDB 10.3+
- Apache 2.4+ oder Nginx 1.18+
- SSL-Zertifikat (HTTPS zwingend erforderlich)

### Installation

Siehe detaillierte Anleitung in **[INSTALLATION.md](INSTALLATION.md)**

**Kurzfassung**:

```bash
# 1. Repository klonen
git clone https://github.com/IHR-REPO/tracker.git
cd tracker

# 2. Konfiguration erstellen
cp config/config.example.php config/config.php
# config.php anpassen!

# 3. Datenbank erstellen und importieren
mysql -u root -p < database/schema.sql

# 4. Berechtigungen setzen
chmod 770 logs/
chmod 750 config/
chmod 600 config/config.php

# 5. Webserver konfigurieren (siehe INSTALLATION.md)
```

**Standard-Login**:
- Username: `admin`
- Passwort: `CyberSecure2025!`

⚠️ **WICHTIG**: Ändern Sie das Passwort nach der ersten Anmeldung!

---

## 📁 Projektstruktur

```
tracker/
├── api/                      # Backend-API
│   ├── classes/              # PHP-Klassen
│   │   ├── Auth.php          # Authentifizierung
│   │   ├── Database.php      # Datenbankverbindung
│   │   ├── Logger.php        # Logging
│   │   └── Security.php      # Sicherheitsfunktionen
│   ├── endpoints/            # API-Endpoints
│   │   ├── events.php        # Event-CRUD
│   │   ├── muehlen.php       # Mühlen-Verwaltung
│   │   ├── verbrauch.php     # Verbrauchsdaten
│   │   ├── statistics.php    # Statistiken
│   │   └── export.php        # CSV-Export
│   ├── index.php             # API-Router
│   ├── init.php              # Bootstrap
│   └── .htaccess             # API-Sicherheit
├── config/                   # Konfiguration
│   ├── config.example.php    # Beispiel-Konfiguration
│   ├── config.php            # Echte Konfiguration (nicht in Git!)
│   └── .htaccess             # Zugriffssperre
├── database/                 # Datenbank
│   └── schema.sql            # Datenbankschema
├── logs/                     # Log-Dateien
│   └── .htaccess             # Zugriffssperre
├── wordpress/                # WordPress-Plugin
│   ├── caffe-julia-tracker.php
│   └── README.md
├── index.html                # Frontend
├── .htaccess                 # Root-Sicherheit
├── INSTALLATION.md           # Installationsanleitung
└── README.md                 # Diese Datei
```

---

## 🔐 Sicherheitsfeatures

### Backend-Sicherheit

- ✅ **Prepared Statements**: Schutz vor SQL-Injection
- ✅ **Password Hashing**: Bcrypt mit Cost Factor 12
- ✅ **Session Management**: Sichere Token-basierte Sessions
- ✅ **CSRF-Protection**: Token-Validierung für alle State-ändernden Operationen
- ✅ **XSS-Protection**: Input-Sanitization und Output-Escaping
- ✅ **Rate Limiting**: Schutz vor Brute-Force-Angriffen
- ✅ **Audit Logging**: Vollständige Aktivitätsprotokolle
- ✅ **Input Validation**: Strikte Validierung aller Eingaben
- ✅ **Encryption**: AES-256-GCM für sensible Daten

### Server-Sicherheit

- ✅ **HTTPS Erzwingung**: Automatische Umleitung
- ✅ **Security Headers**: CSP, HSTS, X-Frame-Options, etc.
- ✅ **File Protection**: .htaccess-basierte Zugriffskontrollen
- ✅ **Directory Listing**: Deaktiviert
- ✅ **Error Handling**: Keine sensiblen Informationen in Errors
- ✅ **PHP Hardening**: Deaktivierung gefährlicher Funktionen

### Datenbank-Sicherheit

- ✅ **Least Privilege**: Minimale Datenbankberechtigungen
- ✅ **Bind to localhost**: MySQL nur lokal erreichbar
- ✅ **Encrypted Connection**: SSL/TLS für DB-Verbindungen (optional)
- ✅ **Backup Strategy**: Automatische Backups

---

## 📖 API-Dokumentation

### Basis-URL
```
https://ihre-domain.de/tracker/api
```

### Authentifizierung

Alle Endpoints (außer `/login` und `/health`) erfordern Authentifizierung via Session-Token:

```http
Authorization: Bearer YOUR_SESSION_TOKEN
```

### Endpoints

#### 🔓 Öffentliche Endpoints

**Health Check**
```http
GET /health
```

**Login**
```http
POST /login
Content-Type: application/json

{
  "username": "admin",
  "password": "your-password"
}
```

#### 🔒 Geschützte Endpoints

**Events auflisten**
```http
GET /events
GET /events?start_date=2025-01-01&end_date=2025-12-31
```

**Event erstellen**
```http
POST /events
Content-Type: application/json

{
  "name": "Hochzeit Müller",
  "event_date": "2025-06-15",
  "is_all_day": true,
  "anzahl_muehlen": 3
}
```

**Event aktualisieren**
```http
PUT /events/{id}
Content-Type: application/json

{
  "name": "Hochzeit Müller (aktualisiert)",
  "work_start_time": "08:00",
  "work_end_time": "17:00"
}
```

**Event löschen**
```http
DELETE /events/{id}
```

**Statistiken abrufen**
```http
GET /statistics?start_date=2025-01-01&end_date=2025-12-31
```

**CSV-Export**
```http
GET /export?format=csv
```

Vollständige API-Dokumentation: Siehe Code-Kommentare in `api/endpoints/`

---

## 🔌 WordPress-Integration

### Installation

1. Kopieren Sie den `wordpress/` Ordner nach `/wp-content/plugins/caffe-julia-tracker/`
2. Aktivieren Sie das Plugin in WordPress
3. Konfigurieren Sie die API-URL unter "Tracker" → "Einstellungen"

### Verwendung

**Shortcode**:
```
[caffe_julia_tracker]
[caffe_julia_tracker width="100%" height="1000px"]
```

**Widget**:
- Fügen Sie das "Caffe Julia Statistiken" Widget zu einer Sidebar hinzu

Mehr Details: [wordpress/README.md](wordpress/README.md)

---

## 🛠️ Entwicklung

### Lokale Entwicklungsumgebung

```bash
# PHP Development Server (nur für Entwicklung!)
php -S localhost:8000 -t .
```

**Wichtig**: Verwenden Sie dies NICHT in Produktion!

### Logging

Logs werden in `logs/` gespeichert:
- `app-YYYY-MM-DD.log` - Allgemeine Logs
- `errors-YYYY-MM-DD.log` - Fehler-Logs

### Debug-Modus

In `config/config.php`:
```php
define('APP_ENV', 'development');
define('APP_DEBUG', true);
```

⚠️ **Niemals in Produktion aktivieren!**

---

## 📋 Wartung

### Backups

**Datenbank-Backup**:
```bash
mysqldump -u caffe_julia_app -p caffe_julia_tracker | gzip > backup_$(date +%Y%m%d).sql.gz
```

**Datei-Backup**:
```bash
tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/html/tracker --exclude='logs/*'
```

### Log-Rotation

Logs werden automatisch nach 30 Tagen gelöscht (konfigurierbar in `Logger.php`).

### Updates

```bash
# 1. Backup erstellen!
# 2. Code aktualisieren
git pull origin main
# 3. Cache leeren
rm -rf logs/*
```

---

## 🆘 Troubleshooting

Siehe [INSTALLATION.md - Troubleshooting](INSTALLATION.md#troubleshooting)

**Häufige Probleme**:
- Datenbankverbindung: Prüfen Sie `config/config.php`
- 500 Error: Prüfen Sie Apache/PHP Error Logs
- 403 Forbidden: Prüfen Sie Berechtigungen und .htaccess

---

## 📄 Lizenz

Dieses Projekt ist proprietär. Alle Rechte vorbehalten.

---

## 👥 Support

- **Email**: admin@caffejulia.com
- **Telefon**: +41 XX XXX XX XX

**Security-Probleme**: security@caffejulia.com

---

## 🙏 Danksagung

Entwickelt mit ❤️ und ☕ für Caffe Julia

**Verwendete Technologien**:
- PHP
- MySQL/MariaDB
- Apache/Nginx
- JavaScript (Vanilla)
- HTML5/CSS3

---

**Version**: 2.0.0 | **Letzte Aktualisierung**: 2025-10-23
