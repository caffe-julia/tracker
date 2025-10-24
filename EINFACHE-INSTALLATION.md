# ✨ Einfache Installation - Alles automatisch!

## 🎯 NEU in Version 3.0!

**Jetzt mit automatischem Setup-Wizard!**

Sie müssen **NICHT MEHR** manuell:
- ❌ FTP benutzen
- ❌ phpMyAdmin öffnen
- ❌ Datenbank-Tabellen erstellen
- ❌ config.php bearbeiten

**Alles geschieht automatisch über WordPress!** 🚀

---

## 📦 Was Sie brauchen

- WordPress 5.8 oder höher
- PHP 7.4 oder höher
- Schreibrechte auf dem Server (normalerweise vorhanden)

---

## 🚀 Installation in 4 einfachen Schritten

### Schritt 1: Plugin hochladen

1. Gehen Sie in Ihren **WordPress-Admin**
2. Klicken Sie auf **Plugins** → **Installieren**
3. Klicken Sie auf **Plugin hochladen**
4. Wählen Sie die Datei: **`caffe-julia-tracker-v3.zip`** (80 KB)
5. Klicken Sie auf **Jetzt installieren**
6. Klicken Sie auf **Plugin aktivieren**

✅ **Fertig! Plugin ist installiert.**

---

### Schritt 2: Setup-Wizard starten

Nach der Aktivierung sehen Sie im WordPress-Menü einen neuen Punkt:

**"Tracker Setup"** (mit 🔧 Icon)

Klicken Sie darauf!

---

### Schritt 3: Setup-Wizard durchlaufen

Der Setup-Wizard führt Sie durch 4 einfache Schritte:

#### **Schritt 1: Dateien installieren**
- Klicken Sie auf **"Dateien jetzt installieren"**
- Die API-Dateien werden automatisch nach `/tracker/` kopiert
- ✅ Dauert 5 Sekunden

#### **Schritt 2: Datenbank erstellen**
- Klicken Sie auf **"Datenbank-Tabellen erstellen"**
- Verwendet automatisch Ihre WordPress-Datenbank
- Erstellt 7 Tabellen mit Präfix `wp_cjt_`
- Legt Standard-Admin-User an
- ✅ Dauert 5 Sekunden

#### **Schritt 3: Konfiguration**
- Klicken Sie auf **"Konfiguration erstellen"**
- Verwendet automatisch Ihre WordPress-Zugangsdaten
- Generiert Verschlüsselungs-Keys
- Richtet CORS ein
- ✅ Dauert 3 Sekunden

#### **Schritt 4: Fertig!**
- Klicken Sie auf **"Zum Dashboard"**
- 🎉 **Installation abgeschlossen!**

---

### Schritt 4: Tracker auf Seite einfügen

1. Bearbeiten Sie eine **Seite oder Beitrag**
2. Fügen Sie ein:

   ```
   [caffe_julia_tracker height="800px"]
   ```

3. Oder verwenden Sie den **Gutenberg-Block**:
   - Klicken Sie auf **"Block hinzufügen"** (+)
   - Suchen Sie nach: **"Caffe Julia Tracker"**
   - Block einfügen

4. **Speichern und ansehen!**

---

## ✅ Das war's!

**Gesamtdauer:** 2-3 Minuten

**Was Sie jetzt haben:**
- ✅ Vollständig funktionierender Tracker
- ✅ MySQL-Datenbank mit allen Tabellen
- ✅ Sichere API mit Verschlüsselung
- ✅ Dashboard mit Live-Statistiken
- ✅ Shortcodes und Widgets

---

## 🔐 Wichtig nach der Installation

### Admin-Passwort ändern!

**Standard-Login:**
- Benutzername: `admin`
- Passwort: `admin123`

⚠️ **Ändern Sie dieses Passwort sofort!**

**So geht's:**
1. Öffnen Sie: `https://www.caffejulia.ch/tracker/`
2. Loggen Sie sich ein (admin / admin123)
3. Gehen Sie zu Ihrem Profil
4. Ändern Sie das Passwort

---

## 📍 URLs nach Installation

**Tracker:**
```
https://www.caffejulia.ch/tracker/
```

**API:**
```
https://www.caffejulia.ch/tracker/api
```

**WordPress-Dashboard:**
```
WordPress-Admin → Caffe Julia Tracker → Dashboard
```

---

## 🎨 Tracker verwenden

### Auf Seite einfügen

**Methode 1: Shortcode**
```
[caffe_julia_tracker height="800px" show_stats="true"]
```

**Methode 2: Gutenberg-Block**
- Block hinzufügen → "Caffe Julia Tracker" suchen

**Methode 3: Widget (Sidebar)**
- Design → Widgets → "Caffe Julia Statistiken"

### Im Dashboard ansehen

- WordPress-Admin → Caffe Julia Tracker → Dashboard
- Zeigt Live-Statistiken an

---

## ❓ Häufige Fragen

### Wo werden die Daten gespeichert?

In Ihrer **WordPress-Datenbank** mit Präfix `wp_cjt_`

Beispiel:
- `wp_cjt_events`
- `wp_cjt_users`
- `wp_cjt_muehlen`
- etc.

### Muss ich FTP verwenden?

**Nein!** Alles läuft über WordPress.

### Muss ich phpMyAdmin verwenden?

**Nein!** Der Setup-Wizard erstellt alles automatisch.

### Kann ich das Plugin deinstallieren?

Ja, beim Deinstallieren werden automatisch:
- Alle Plugin-Optionen gelöscht
- Alle Cache-Einträge gelöscht

**ABER:** Die Datenbank-Tabellen und Tracker-Dateien bleiben erhalten (Sicherheit).

Um alles zu entfernen:
1. Plugin deinstallieren
2. Via FTP Ordner `/tracker/` löschen
3. Via phpMyAdmin Tabellen `wp_cjt_*` löschen

### Funktioniert es auf Shared-Hosting?

**Ja!** Funktioniert auf allen Standard-WordPress-Hostings:
- Hostinger
- ALL-INKL
- SiteGround
- Bluehost
- etc.

### Brauche ich Root-Zugriff?

**Nein!** Normale WordPress-Installation reicht.

---

## 🔧 Troubleshooting

### Problem: "Konnte Verzeichnis nicht erstellen"

**Ursache:** Fehlende Schreibrechte

**Lösung:**
1. Kontaktieren Sie Ihren Hoster
2. Bitten Sie um Schreibrechte für `/public_html/`
3. Oder setzen Sie via FTP Berechtigung 755

### Problem: "Datenbank-Fehler"

**Ursache:** MySQL-Benutzer hat keine Rechte

**Lösung:**
- Das sollte nicht passieren, da WordPress-Zugangsdaten verwendet werden
- Prüfen Sie via phpMyAdmin, ob Sie Tabellen erstellen können

### Problem: Setup-Wizard ist weg

**Ursache:** Setup wurde als abgeschlossen markiert

**Lösung:**
Um den Wizard erneut zu starten:
1. Via phpMyAdmin oder Plugin "WP phpMyAdmin"
2. Öffnen Sie Tabelle `wp_options`
3. Löschen Sie Zeile: `cjt_setup_complete`
4. Setup-Wizard erscheint wieder

### Problem: 404-Fehler beim Tracker

**Ursache:** .htaccess funktioniert nicht

**Lösung:**
1. Prüfen Sie, ob Datei `/tracker/.htaccess` existiert
2. Testen Sie direkt: `https://www.caffejulia.ch/tracker/index.html`
3. Falls das funktioniert: .htaccess-Problem beim Hoster melden

---

## 📊 Nach der Installation

### Dashboard öffnen

WordPress-Admin → **Caffe Julia Tracker** → **Dashboard**

**Zeigt an:**
- 📅 Anzahl Events
- ☕ Gesamt Kaffees
- ⏱️ Gesamt Stunden
- 🥛 Verbrauch Milch

### Statistiken auf Seite

Shortcode:
```
[caffe_julia_stats period="30"]
```

Zeigt Statistiken der letzten 30 Tage.

### Einstellungen anpassen

WordPress-Admin → **Caffe Julia Tracker** → **Einstellungen**

**Konfigurieren Sie:**
- Cache-Dauer (Standard: 5 Minuten)
- Widget-Höhe (Standard: 800px)
- Theme-Farbe (Standard: Orange)
- API-Token (optional)

---

## 🎉 Fertig!

Ihr Tracker ist jetzt **vollständig installiert und einsatzbereit**!

**Was Sie jetzt tun können:**
1. ✅ Events erstellen
2. ✅ Kaffeemühlen-Zählerstände erfassen
3. ✅ Verbrauch tracken
4. ✅ Arbeitszeiten verwalten
5. ✅ Statistiken ansehen
6. ✅ Daten exportieren (Excel, CSV)

---

## 📚 Weitere Dokumentation

**Ausführliche Anleitungen:**
- `QUICK-START.md` - Schnellanleitung
- `WORDPRESS-API-INSTALLATION.md` - Manuelle Installation
- `README.md` - Allgemeine Übersicht
- `SECURITY.md` - Sicherheitsfeatures

**Bei Fragen:**
- Logs prüfen: `/tracker/logs/app.log`
- Browser-Console: F12 → Console
- API testen: `https://www.caffejulia.ch/tracker/api/`

---

**Viel Erfolg mit Ihrem Caffe Julia Tracker! ☕**

Version 3.0 - Mit automatischem Setup-Wizard
