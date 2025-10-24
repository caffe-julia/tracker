# Caffe Julia Tracker Pro v5.1

**Ihr Original-Tracker - jetzt in WordPress integriert!**

## 🆕 Version 5.1 - Bugfix Release

### Behobene Probleme:
- ✅ **Mehrtägige Events:** Alle Tage sind jetzt direkt klickbar und bearbeitbar
- ✅ **Mitteilungsfeld:** Gilt jetzt für den GESAMTEN Event, nicht mehr pro Tag
- ✅ **Einstellungen:** Neue Einstellungsseite im WordPress-Admin

### Verbesserungen:
- 🎨 Visuelle Hervorhebung: Einzelne Tage haben jetzt einen deutlichen orangen Rahmen
- 👆 Klick-Feedback: Hover-Effekt zeigt, dass Tage klickbar sind
- 📝 Zentrale Mitteilung: Mitteilung wird in der Event-Übersicht bearbeitet und gilt für alle Tage
- ⚙️ Einstellungsseite: Zugriff auf Passwort-Verwaltung und Tracker-Informationen

## ✨ Features

- ✅ **100% Ihr Design** - Der Original-Tracker bleibt gleich!
- ✅ **WordPress-Speicherung** - Daten werden in WordPress-DB gespeichert
- ✅ **Kein FTP/SSH** - Alles läuft in WordPress
- ✅ **Dashboard** - Statistiken im WordPress-Admin
- ✅ **Excel-Export** - Mit einem Klick downloaden
- ✅ **iPhone-optimiert** - Mobile First Design

## 📦 Installation (2 Minuten)

### Schritt 1: Plugin hochladen

1. WordPress-Admin → **Plugins** → **Installieren**
2. **Plugin hochladen**
3. Datei wählen: `caffe-julia-tracker-pro.zip`
4. **Jetzt installieren**
5. **Plugin aktivieren**

### Schritt 2: Seite erstellen

1. WordPress-Admin → **Seiten** → **Erstellen**
2. Titel: "Tracker" (oder beliebig)
3. Im Editor einfügen:
   ```
   [caffe_tracker]
   ```
4. **Veröffentlichen**

### Schritt 3: Fertig! 🎉

Öffnen Sie die Seite - Ihr Tracker läuft!

## 🎯 Verwendung

### Tracker öffnen

- Am **Computer:** Öffnen Sie die erstellte WordPress-Seite
- Am **iPhone:** Gleiche URL - funktioniert perfekt!

### Dashboard ansehen

WordPress-Admin → **Caffe Tracker** (im Menü)

Hier sehen Sie:
- 📊 Statistiken (Events, Kaffees, Milch, Getränke, Stunden)
- 📥 Excel-Download-Button

### Excel exportieren

1. WordPress-Admin → **Caffe Tracker**
2. Klick auf: **📥 Excel/CSV herunterladen**
3. Datei öffnet sich in Excel

## ✨ Alle Features

### Events

- ✅ Event-Name
- ✅ Datum (mehrtägig möglich!)
- ✅ Ganztägig oder mit Zeiten

### Arbeitszeit

- ✅ Start-Zeit
- ✅ End-Zeit
- ✅ Pause (Minuten)
- ✅ **Automatische Berechnung**

### Kaffeemühlen

- ✅ 1-4 Mühlen wählbar
- ✅ **Doppelbezug** (Start/Ende)
- ✅ **Einzelbezug** (Start/Ende)
- ✅ **Endstand wird automatisch Startstand vom nächsten Tag!**

### Getränke (Plus/Minus Counter)

- ✅ Milch (Liter)
- ✅ Hafermilch (Liter)
- ✅ Matcha (Stück)
- ✅ Schokolade (Stück)
- ✅ Tee (Stück)

### Sonstiges

- ✅ Mitteilungsfeld (Notizen)
- ✅ Dashboard mit Statistiken
- ✅ Excel/CSV Export

## 📊 Dashboard-Statistiken

Im WordPress-Admin sehen Sie:
- Total Events
- Total Kaffees
- Total Milch (Liter)
- Total Getränke
- Total Arbeitsstunden

## 📥 Excel-Export

Die exportierte Datei enthält:
- Event Name, Datum
- Arbeitszeiten
- Alle Mühlen-Stände (Doppel/Einzel)
- Total Kaffees
- Milch, Hafermilch
- Matcha, Schokolade, Tee
- Mitteilungen

**Format:** CSV mit Semikolon (öffnet direkt in Excel)

## 🔒 Sicherheit

- ✅ Daten in WordPress-Datenbank (sicher!)
- ✅ Nur eingeloggte User können Daten ändern
- ✅ WordPress-Nonce-Schutz
- ✅ REST API mit Authentifizierung

## 📱 iPhone-Optimierung

Der Tracker ist speziell für iPhone optimiert:
- ✅ Touch-freundliche Buttons
- ✅ Große Eingabefelder
- ✅ Responsive Design
- ✅ Plus/Minus Counter für schnelle Eingabe
- ✅ Funktioniert im Safari perfekt!

## ❓ FAQ

### Wo werden die Daten gespeichert?

In Ihrer WordPress-Datenbank als Custom Post Type `cjtp_event`.

### Kann ich meine alten Daten importieren?

Wenn Sie vorher den localStorage-Tracker verwendet haben:
1. Exportieren Sie die Daten als CSV
2. Im neuen Tracker: CSV Import verwenden

### Funktioniert es offline?

Nein - es braucht Internet, da Daten in WordPress gespeichert werden.

### Kann ich mehrere Benutzer haben?

Ja! Jeder WordPress-User mit `edit_posts` Berechtigung kann den Tracker nutzen.

### Wo finde ich Support?

Bei Fragen: GitHub Issues oder WordPress-Support-Forum

## 🎉 Das war's!

**Viel Spaß mit Ihrem Tracker!**

Jetzt können Sie:
- ✅ Events vom iPhone erfassen
- ✅ Statistiken im Dashboard sehen
- ✅ Excel-Dateien herunterladen
- ✅ Alles ohne FTP/SSH!

---

**Version:** 5.1.0
**Lizenz:** GPL-2.0+
**Author:** Caffe Julia
