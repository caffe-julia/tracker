# Caffe Julia Tracker - WordPress Plugin

Version: 2.0.0
Requires: WordPress 5.8+, PHP 7.4+

## 📦 Installation

### Schritt 1: Plugin installieren

**Option A: Über WordPress-Admin (empfohlen)**

1. Packen Sie den `wordpress-plugin` Ordner als ZIP:
   ```bash
   cd tracker
   zip -r caffe-julia-tracker.zip wordpress-plugin/
   ```

2. In WordPress:
   - Gehen Sie zu **Plugins → Installieren → Plugin hochladen**
   - Wählen Sie die `caffe-julia-tracker.zip`
   - Klicken Sie auf **Jetzt installieren**
   - Aktivieren Sie das Plugin

**Option B: Manueller Upload**

1. Laden Sie den `wordpress-plugin` Ordner hoch:
   ```bash
   scp -r wordpress-plugin/ user@server:/var/www/html/wordpress/wp-content/plugins/caffe-julia-tracker/
   ```

2. In WordPress:
   - Gehen Sie zu **Plugins**
   - Aktivieren Sie "Caffe Julia Tracker"

### Schritt 2: API konfigurieren

1. Gehen Sie zu **Tracker → Einstellungen**
2. Tragen Sie Ihre API-URL ein:
   ```
   https://ihre-domain.de/tracker/api
   ```
3. Optional: Geben Sie ein Session-Token ein
4. Speichern Sie die Einstellungen
5. Testen Sie die Verbindung im Dashboard

---

## 🚀 Verwendung

### Shortcodes

**Vollständiger Tracker:**
```
[caffe_julia_tracker]
[caffe_julia_tracker height="1000px" show_stats="true" theme="light"]
```

**Parameter:**
- `height` - Höhe des Widgets (Standard: 800px)
- `show_stats` - Statistiken anzeigen (true/false)
- `theme` - Farbschema (light/dark)

**Nur Statistiken:**
```
[caffe_julia_stats]
[caffe_julia_stats period="7" layout="grid"]
```

**Parameter:**
- `period` - Zeitraum in Tagen (Standard: 30)
- `layout` - Layout (grid/list)

### Gutenberg-Blocks

1. Im Block-Editor auf **(+)** klicken
2. Nach "Caffe Julia" suchen
3. Block "Caffe Julia Tracker" oder "Caffe Julia Statistiken" wählen
4. Einstellungen in der Sidebar anpassen

### Widget

1. Gehen Sie zu **Design → Widgets**
2. Fügen Sie "Caffe Julia Statistiken" zu einer Sidebar hinzu
3. Konfigurieren Sie Titel und Zeitraum

---

## 📖 Funktionen

### Admin-Dashboard

- 📊 Schnell-Statistiken
- 🔌 Verbindungstest
- ⚡ Schnellaktionen
- ℹ️ System-Informationen

### Einstellungen

- API-URL Konfiguration
- API-Token (optional)
- Cache-Einstellungen
- Anzeige-Optionen
- Theme-Farbe

### Frontend

- Vollständiger Tracker als Iframe
- Statistik-Widgets
- Responsive Design
- Anpassbare Höhe und Farben

---

## 🔧 Entwicklung

### Ordnerstruktur

```
wordpress-plugin/
├── caffe-julia-tracker.php    # Haupt-Plugin-Datei
├── includes/
│   ├── class-caffe-julia-tracker.php
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── settings.php
│   │   └── help.php
│   ├── templates/
│   │   ├── tracker-widget.php
│   │   └── stats-widget.php
│   └── widgets/
│       └── class-statistics-widget.php
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── frontend.css
│   └── js/
│       ├── admin.js
│       ├── frontend.js
│       └── block.js
├── languages/                  # Übersetzungen (optional)
├── readme.txt                  # WordPress.org readme
└── README.md                   # Diese Datei
```

### Hooks & Filter

**Actions:**
```php
do_action('cjt_before_tracker_render');
do_action('cjt_after_tracker_render');
do_action('cjt_stats_loaded', $stats);
```

**Filters:**
```php
apply_filters('cjt_api_url', $url);
apply_filters('cjt_cache_duration', $duration);
apply_filters('cjt_stats_display', $stats);
```

### JavaScript-API

```javascript
// Statistiken neu laden
CaffeJuliaTracker.refreshStats();

// Zahl formatieren
CaffeJuliaTracker.formatNumber(1234); // "1'234"

// Datum formatieren
CaffeJuliaTracker.formatDate('2025-01-15'); // "15. Januar 2025"
```

---

## 🔐 Sicherheit

- ✅ Nonce-Validierung für alle AJAX-Requests
- ✅ Capability-Checks für Admin-Seiten
- ✅ Input-Sanitization
- ✅ Output-Escaping
- ✅ HTTPS-Unterstützung
- ✅ CSRF-Schutz

---

## 🌍 Übersetzung

Das Plugin ist übersetzungsbereit (i18n).

**Neue Sprache hinzufügen:**

1. Erstellen Sie `.po`-Datei mit Poedit
2. Übersetzen Sie alle Strings
3. Generieren Sie `.mo`-Datei
4. Speichern Sie in `languages/` Ordner

**Text-Domain:** `caffe-julia-tracker`

---

## 🐛 Troubleshooting

### Plugin wird nicht aktiviert

- Prüfen Sie PHP-Version (min. 7.4)
- Prüfen Sie WordPress-Version (min. 5.8)
- Prüfen Sie PHP-Errors in Debug-Log

### Tracker wird nicht angezeigt

- Prüfen Sie API-URL in Einstellungen
- Testen Sie Verbindung im Dashboard
- Prüfen Sie Browser-Console auf CORS-Fehler
- Aktivieren Sie HTTPS

### Statistiken werden nicht geladen

- Leeren Sie Cache im Dashboard
- Prüfen Sie API-Token (falls verwendet)
- Prüfen Sie WordPress Debug-Log
- Testen Sie API-Endpoint direkt im Browser

### CORS-Fehler

Fügen Sie Ihre WordPress-Domain zur API-Konfiguration hinzu:

```php
// In tracker/config/config.php
define('CORS_ALLOWED_ORIGINS', [
    'https://ihre-wordpress-domain.de',
    'https://www.ihre-wordpress-domain.de'
]);
```

---

## 📋 System-Anforderungen

**Minimum:**
- WordPress 5.8+
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+

**Empfohlen:**
- WordPress 6.0+
- PHP 8.1+
- HTTPS aktiviert
- Mindestens 64MB PHP Memory

---

## 📄 Lizenz

GPL-2.0+

---

## 💬 Support

- **Email:** admin@caffejulia.com
- **GitHub:** https://github.com/caffe-julia/tracker/issues
- **Dokumentation:** https://github.com/caffe-julia/tracker

---

## 🙏 Credits

Entwickelt mit ❤️ und ☕ für Caffe Julia

**Version:** 2.0.0
**Autor:** Caffe Julia
**Lizenz:** GPL-2.0+
