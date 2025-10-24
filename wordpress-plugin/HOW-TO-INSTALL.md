# 🚀 WordPress Plugin Installation - Schritt für Schritt

## Schnellstart für Installation

### Methode 1: ZIP-Datei erstellen und hochladen (EINFACHSTE METHODE)

#### Schritt 1: ZIP-Datei erstellen

**Auf dem Server:**
```bash
cd /home/user/tracker
zip -r caffe-julia-tracker.zip wordpress-plugin/ -x "*.git*" "*.DS_Store"
```

**Auf Ihrem lokalen Computer:**
```bash
# Windows (PowerShell)
Compress-Archive -Path wordpress-plugin -DestinationPath caffe-julia-tracker.zip

# Mac/Linux
cd tracker
zip -r caffe-julia-tracker.zip wordpress-plugin/ -x "*.git*" "*.DS_Store"
```

#### Schritt 2: In WordPress hochladen

1. Melden Sie sich in Ihrem WordPress-Admin an
2. Gehen Sie zu **Plugins → Installieren**
3. Klicken Sie auf **Plugin hochladen** (oben)
4. Klicken Sie auf **Datei auswählen**
5. Wählen Sie `caffe-julia-tracker.zip`
6. Klicken Sie auf **Jetzt installieren**
7. Klicken Sie auf **Plugin aktivieren**

✅ **Fertig!** Das Plugin ist jetzt installiert.

---

### Methode 2: Direkter Upload via FTP/SFTP

1. Verbinden Sie sich via FTP/SFTP zu Ihrem Server
2. Navigieren Sie zu: `/wp-content/plugins/`
3. Laden Sie den gesamten Ordner `wordpress-plugin` hoch
4. Benennen Sie ihn um in: `caffe-julia-tracker`
5. In WordPress: Gehen Sie zu **Plugins** und aktivieren Sie "Caffe Julia Tracker"

---

### Methode 3: SSH/Terminal

```bash
# Mit SSH verbinden
ssh user@ihre-domain.de

# Zum WordPress-Verzeichnis navigieren
cd /var/www/html/wordpress/wp-content/plugins/

# Plugin-Ordner kopieren
cp -r /home/user/tracker/wordpress-plugin ./caffe-julia-tracker

# Berechtigungen setzen
chown -R www-data:www-data caffe-julia-tracker
chmod -R 755 caffe-julia-tracker
```

Dann in WordPress das Plugin aktivieren.

---

## Nach der Installation

### 1. Plugin konfigurieren

1. Gehen Sie zu **Tracker → Einstellungen**
2. Tragen Sie Ihre **API-URL** ein:
   ```
   https://ihre-domain.de/tracker/api
   ```
3. Optional: Tragen Sie ein **API-Token** ein (für authentifizierte Anfragen)
4. Klicken Sie auf **Einstellungen speichern**

### 2. Verbindung testen

1. Gehen Sie zu **Tracker → Dashboard**
2. Klicken Sie auf **🔌 Verbindung testen**
3. Sie sollten "✅ Verbindung erfolgreich!" sehen

Wenn nicht, siehe [Troubleshooting](#troubleshooting) weiter unten.

### 3. Tracker einbinden

**Option A: Shortcode verwenden**

Erstellen Sie eine neue Seite:
1. Gehen Sie zu **Seiten → Erstellen**
2. Fügen Sie folgenden Shortcode ein:
   ```
   [caffe_julia_tracker]
   ```
3. Veröffentlichen Sie die Seite

**Option B: Gutenberg-Block verwenden**

1. Erstellen oder bearbeiten Sie eine Seite
2. Klicken Sie auf **(+)** um einen Block hinzuzufügen
3. Suchen Sie nach "Caffe Julia Tracker"
4. Fügen Sie den Block ein
5. Konfigurieren Sie die Optionen rechts

**Option C: Widget verwenden**

1. Gehen Sie zu **Design → Widgets**
2. Suchen Sie "Caffe Julia Statistiken"
3. Ziehen Sie es in Ihre Sidebar
4. Konfigurieren Sie Titel und Zeitraum

---

## API-Backend Installation

**WICHTIG:** Das WordPress-Plugin benötigt das Caffe Julia Tracker API-Backend!

Falls noch nicht installiert:

1. Siehe [INSTALLATION.md](../INSTALLATION.md) im Hauptverzeichnis
2. Installieren Sie MySQL-Datenbank
3. Konfigurieren Sie API
4. Aktivieren Sie HTTPS
5. Notieren Sie die API-URL für die Plugin-Konfiguration

---

## Troubleshooting

### ❌ "API noch nicht konfiguriert"

**Lösung:**
1. Gehen Sie zu **Tracker → Einstellungen**
2. Tragen Sie die API-URL ein
3. Speichern Sie

### ❌ "Verbindung fehlgeschlagen"

**Mögliche Ursachen:**
1. API ist nicht erreichbar → Prüfen Sie die URL im Browser
2. CORS-Problem → Siehe CORS-Konfiguration unten
3. Firewall blockt → Prüfen Sie Server-Firewall
4. HTTPS-Problem → Verwenden Sie HTTPS für API

**CORS konfigurieren:**

In `tracker/config/config.php`:
```php
define('CORS_ALLOWED_ORIGINS', [
    'https://ihre-wordpress-domain.de',
    'https://www.ihre-wordpress-domain.de'
]);
```

### ❌ "Statistiken nicht verfügbar"

**Lösung:**
1. Gehen Sie zu **Tracker → Dashboard**
2. Klicken Sie auf **🗑️ Cache leeren**
3. Testen Sie die Verbindung erneut

### ❌ Plugin lässt sich nicht aktivieren

**Lösung:**
1. Prüfen Sie **PHP-Version** (mindestens 7.4 erforderlich)
2. Prüfen Sie **WordPress-Version** (mindestens 5.8 erforderlich)
3. Aktivieren Sie WordPress Debug:
   ```php
   // In wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
4. Prüfen Sie Logs in: `/wp-content/debug.log`

### ❌ Tracker wird nicht angezeigt (leere Seite)

**Lösung:**
1. Öffnen Sie Browser-Console (F12)
2. Suchen Sie nach Fehlermeldungen
3. Häufig: Mixed-Content-Warning (HTTP vs HTTPS)
   - Lösung: Verwenden Sie HTTPS für API

---

## Empfohlene Einstellungen

### Für beste Performance:

```
Cache aktivieren: ✅
Cache-Dauer: 300 Sekunden (5 Minuten)
```

### Für beste Sicherheit:

```
API-URL: https://... (HTTPS!)
API-Token: Verwenden Sie ein Session-Token
```

### Für beste Anzeige:

```
Widget-Höhe: 800px
Theme-Farbe: #d97706 (Orange)
In Admin anzeigen: ✅
```

---

## Deinstallation

### Plugin sauber deinstallieren:

1. Gehen Sie zu **Plugins**
2. Deaktivieren Sie "Caffe Julia Tracker"
3. Klicken Sie auf **Löschen**
4. Bestätigen Sie

**Hinweis:** Alle Plugin-Einstellungen werden gelöscht. API-Daten bleiben erhalten.

---

## Support

Bei Problemen:

📧 **Email:** admin@caffejulia.com
🐛 **GitHub:** https://github.com/caffe-julia/tracker/issues
📖 **Dokumentation:** https://github.com/caffe-julia/tracker

---

## Checkliste

Nach Installation prüfen:

- [ ] Plugin aktiviert
- [ ] API-URL konfiguriert
- [ ] Verbindung getestet (Dashboard)
- [ ] Tracker auf Test-Seite eingefügt
- [ ] Tracker wird korrekt angezeigt
- [ ] Statistiken werden geladen
- [ ] HTTPS aktiviert
- [ ] CORS konfiguriert (falls nötig)

---

**Viel Erfolg mit Ihrem Caffe Julia Tracker WordPress-Plugin! ☕**
