=== Caffe Julia Tracker ===
Contributors: caffejulia
Tags: tracker, events, cafe, coffee, statistics
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Professionelles Event-Tracking für Cafés. Verwalten Sie Events, Kaffeemühlen-Zählerstände, Verbrauch und Arbeitszeiten direkt in WordPress.

== Description ==

**Caffe Julia Tracker** ist ein professionelles WordPress-Plugin für Event-Management und Tracking in Cafés und Gastronomiebetrieben. Perfekt für die Verwaltung von Events, Kaffeemühlen-Zählerständen, Verbrauchserfassung und Arbeitszeitdokumentation.

### ✨ Hauptfunktionen

* 📊 **Event-Management**: Erfassen und verwalten Sie alle Events
* ☕ **Kaffeemühlen-Tracking**: Zählerstände für bis zu 3 Mühlen
* 🥛 **Verbrauchserfassung**: Milch, Hafermilch und weitere Getränke
* ⏱️ **Arbeitszeiterfassung**: Automatische Berechnung der Arbeitsstunden
* 📈 **Statistiken**: Umfassende Auswertungen und Reports
* 📥 **CSV-Export**: Exportieren Sie Ihre Daten
* 🔐 **Sicher**: Höchste Cybersecurity-Standards

### 🔌 Einfache Integration

* **Shortcodes**: `[caffe_julia_tracker]` und `[caffe_julia_stats]`
* **Gutenberg-Blocks**: Moderne Block-Editor Integration
* **Widgets**: Statistik-Widget für Sidebar
* **REST API**: Vollständige API-Unterstützung

### 🚀 Voraussetzungen

Dieses Plugin benötigt:
* Caffe Julia Tracker API-Backend (separat zu installieren)
* HTTPS-Verbindung (empfohlen)
* PHP 7.4 oder höher
* WordPress 5.8 oder höher

### 💡 Verwendung

1. Installieren Sie das Plugin
2. Konfigurieren Sie die API-URL in den Einstellungen
3. Fügen Sie den Tracker mit Shortcode, Block oder Widget ein

**Shortcode-Beispiele:**

`[caffe_julia_tracker]`
`[caffe_julia_tracker height="1000px" show_stats="true"]`
`[caffe_julia_stats period="30" layout="grid"]`

### 🔗 Links

* [GitHub Repository](https://github.com/caffe-julia/tracker)
* [Dokumentation](https://github.com/caffe-julia/tracker/blob/main/README.md)
* [Support](mailto:admin@caffejulia.com)

== Installation ==

### Automatische Installation

1. Gehen Sie zu "Plugins" → "Installieren"
2. Suchen Sie nach "Caffe Julia Tracker"
3. Klicken Sie auf "Jetzt installieren"
4. Aktivieren Sie das Plugin

### Manuelle Installation

1. Laden Sie die Plugin-ZIP-Datei herunter
2. Gehen Sie zu "Plugins" → "Installieren" → "Plugin hochladen"
3. Wählen Sie die ZIP-Datei aus
4. Klicken Sie auf "Jetzt installieren"
5. Aktivieren Sie das Plugin

### Nach der Installation

1. Gehen Sie zu "Tracker" → "Einstellungen"
2. Tragen Sie Ihre API-URL ein (z.B. `https://ihre-domain.de/tracker/api`)
3. Optional: Geben Sie ein API-Token ein
4. Speichern Sie die Einstellungen
5. Testen Sie die Verbindung im Dashboard

### API-Backend installieren

Das Plugin benötigt das Caffe Julia Tracker API-Backend:

1. Laden Sie den Tracker von GitHub herunter
2. Installieren Sie ihn auf Ihrem Server (siehe INSTALLATION.md)
3. Konfigurieren Sie MySQL-Datenbank
4. Aktivieren Sie HTTPS
5. Notieren Sie die API-URL für die Plugin-Konfiguration

Vollständige Installationsanleitung: [INSTALLATION.md](https://github.com/caffe-julia/tracker/blob/main/INSTALLATION.md)

== Frequently Asked Questions ==

= Benötige ich eine separate API-Installation? =

Ja, das Plugin kommuniziert mit dem Caffe Julia Tracker API-Backend, das separat auf Ihrem Server installiert werden muss. Siehe Installationsanleitung.

= Ist HTTPS erforderlich? =

HTTPS wird dringend empfohlen für sichere Datenübertragung. Bei Mixed-Content (HTTPS WordPress + HTTP API) können Browser Probleme auftreten.

= Wie ändere ich die Theme-Farbe? =

Gehen Sie zu "Tracker" → "Einstellungen" und ändern Sie die Theme-Farbe im Abschnitt "Anzeige-Einstellungen".

= Werden die Daten in WordPress gespeichert? =

Nein, alle Event-Daten werden in der API-Datenbank (MySQL) gespeichert. Das Plugin cached nur Statistiken für bessere Performance.

= Wie lösche ich den Cache? =

Gehen Sie zu "Tracker" → "Dashboard" und klicken Sie auf "Cache leeren". Der Cache wird auch automatisch nach der konfigurierten Zeit erneuert.

= Funktioniert das Plugin mit Gutenberg? =

Ja, es gibt dedizierte Gutenberg-Blocks: "Caffe Julia Tracker" und "Caffe Julia Statistiken".

= Ist das Plugin mehrsprachig? =

Ja, das Plugin ist übersetzungsbereit (i18n). Deutsche Übersetzung ist enthalten.

= Wo finde ich Support? =

* Email: admin@caffejulia.com
* GitHub Issues: https://github.com/caffe-julia/tracker/issues
* Dokumentation: https://github.com/caffe-julia/tracker

== Screenshots ==

1. Dashboard mit Schnell-Statistiken
2. Einstellungsseite mit API-Konfiguration
3. Tracker-Widget im Frontend
4. Statistik-Widget in der Sidebar
5. Gutenberg-Block-Einstellungen
6. Hilfe-Seite mit Dokumentation

== Changelog ==

= 2.0.0 - 2025-10-23 =
* Erste öffentliche Version
* Vollständiges WordPress-Plugin mit Admin-Interface
* Shortcodes für Tracker und Statistiken
* Gutenberg-Blocks für modernen Editor
* Sidebar-Widget für Statistiken
* REST API Integration
* Caching für bessere Performance
* Umfassende Dokumentation
* Mehrsprachig (Deutsch/Englisch)
* DSGVO-konform

== Upgrade Notice ==

= 2.0.0 =
Erste Version. Keine Upgrade-Schritte erforderlich.

== Additional Info ==

### Datenschutz

Dieses Plugin kommuniziert mit Ihrem eigenen API-Backend. Es werden keine Daten an Dritte übertragen. Stellen Sie sicher, dass Ihre API DSGVO-konform konfiguriert ist.

### Sicherheit

* Alle API-Anfragen verwenden Nonces für CSRF-Schutz
* Session-Tokens können für authentifizierte Anfragen verwendet werden
* Daten werden über HTTPS übertragen (empfohlen)
* XSS-Schutz durch Input-Sanitization

### Performance

* Caching-System reduziert API-Anfragen
* Konfigurierbare Cache-Dauer (Standard: 5 Minuten)
* Lazy Loading für Statistiken
* Optimierte Datenbankabfragen

### Kompatibilität

* WordPress 5.8+
* PHP 7.4+
* Gutenberg Block Editor
* Classic Editor (mit Shortcodes)
* Multisite-fähig

### Credits

Entwickelt mit ❤️ und ☕ für Caffe Julia

* GitHub: https://github.com/caffe-julia/tracker
* Website: https://caffejulia.com
