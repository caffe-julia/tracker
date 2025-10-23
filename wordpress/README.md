# WordPress-Integration für Caffe Julia Tracker

## Installation

1. **Plugin-Ordner hochladen**
   - Laden Sie den gesamten `wordpress` Ordner in Ihr WordPress-Verzeichnis: `/wp-content/plugins/caffe-julia-tracker/`

2. **Plugin aktivieren**
   - Gehen Sie zu WordPress Admin → Plugins
   - Aktivieren Sie "Caffe Julia Tracker Integration"

3. **Einstellungen konfigurieren**
   - Gehen Sie zu "Tracker" → "Einstellungen"
   - Geben Sie die API-URL ein (z.B. `https://ihr-domain.de/tracker/api`)
   - Optional: Geben Sie ein API-Token für authentifizierte Anfragen ein

## Verwendung

### Shortcode

Fügen Sie den Tracker auf jeder Seite oder jedem Beitrag ein:

```
[caffe_julia_tracker]
```

Mit optionalen Parametern:

```
[caffe_julia_tracker width="100%" height="1000px" mode="iframe"]
```

**Parameter:**
- `width`: Breite des Trackers (Standard: 100%)
- `height`: Höhe des Trackers (Standard: 800px)
- `mode`: Anzeigemodus - `iframe` oder `embed` (Standard: iframe)

### Widget

1. Gehen Sie zu "Design" → "Widgets"
2. Fügen Sie das Widget "Caffe Julia Statistiken" zu einer Sidebar hinzu
3. Konfigurieren Sie den Titel

### Admin-Dashboard

- Gehen Sie zu "Tracker" im WordPress-Menü
- Sehen Sie Statistiken und Einstellungen

## Sicherheit

- Alle API-Anfragen verwenden HTTPS (empfohlen)
- Session-Token werden sicher gespeichert
- WordPress-Nonces schützen vor CSRF-Angriffen

## Support

Bei Problemen wenden Sie sich an: admin@caffejulia.com
