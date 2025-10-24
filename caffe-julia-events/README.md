# Caffe Julia Events - Einfaches Event-Tracking Plugin

Version 4.0.0 - **100% in WordPress, kein FTP/SSH nötig!**

## ✨ Features

- ✅ **Event-Verwaltung** in WordPress-Admin
- ✅ **Kaffeemühlen-Zählerstände** (1-4 Mühlen)
- ✅ **Milch-Verbrauch** tracken
- ✅ **Arbeitszeiten** automatisch berechnen
- ✅ **Excel-Export** aller Events mit einem Klick
- ✅ **Keine externe API** - alles in WordPress
- ✅ **Keine Schreibrechte** nötig - funktioniert sofort

## 📥 Installation

### Schritt 1: Plugin hochladen

1. WordPress-Admin → **Plugins** → **Installieren**
2. **Plugin hochladen**
3. Datei auswählen: `caffe-julia-events.zip`
4. **Jetzt installieren**
5. **Plugin aktivieren**

### Schritt 2: Fertig!

**Das war's!** Keine weitere Konfiguration nötig.

Im WordPress-Menü links erscheint: **"Caffe Events"**

## 🎯 Verwendung

### Event hinzufügen

1. **Caffe Events** → **Neues Event**
2. Formular ausfüllen:
   - Event Name (z.B. "Hochzeit Müller")
   - Datum
   - Zeiten (oder ganztägig)
   - Kaffeemühlen aktivieren und Zählerstände eingeben
   - Milch-Verbrauch eintragen
3. **Event speichern**

### Events ansehen

**Caffe Events** → Übersicht

Hier sehen Sie:
- Alle Events in Tabelle
- Gesamt-Statistik unten (Total Kaffees, Total Milch)

### Excel exportieren

1. **Caffe Events** → Übersicht
2. Klicken Sie oben auf: **📥 Excel Download**
3. Datei wird heruntergeladen: `caffe-julia-events-YYYY-MM-DD.csv`
4. In Excel/LibreOffice öffnen

**Format:** CSV mit Semikolon-Trennung (öffnet direkt in Excel)

## 📊 Excel-Format

Die exportierte Datei enthält:

| Spalte | Beschreibung |
|--------|-------------|
| Event Name | Name des Events |
| Datum | Datum im Format TT.MM.JJJJ |
| Ganztägig | Ja/Nein |
| Start-Zeit | Start-Zeit (HH:MM) |
| End-Zeit | End-Zeit (HH:MM) |
| Arbeitszeit (h) | Automatisch berechnet |
| Mühle 1-4 Start | Start-Zählerstand |
| Mühle 1-4 Ende | End-Zählerstand |
| Mühle 1-4 Differenz | Anzahl Kaffees |
| Total Kaffees | Summe aller Mühlen |
| Milch (Liter) | Verbrauch |

## 🔧 Technische Details

**Anforderungen:**
- WordPress 5.8+
- PHP 7.4+

**Was verwendet wird:**
- WordPress Custom Post Types
- WordPress Meta Fields
- WordPress Admin Pages
- CSV-Export (kompatibel mit Excel)

**Was NICHT verwendet wird:**
- ❌ Externe Dateien
- ❌ Separate API
- ❌ Setup-Wizard
- ❌ FTP/SSH
- ❌ Schreibrechte

## ❓ FAQ

### Wo werden die Daten gespeichert?

In der WordPress-Datenbank:
- Post Type: `cje_event`
- Meta Fields für Event-Daten

### Kann ich Events löschen?

Ja, in der Events-Übersicht → **Löschen** Button

### Kann ich Events bearbeiten?

In Version 4.0 noch nicht - kommt in Version 4.1

Aktuell: Event löschen und neu anlegen

### Funktioniert es auf Shared Hosting?

Ja! Funktioniert auf allen WordPress-Hostings:
- Hostinger
- ALL-INKL
- SiteGround
- Bluehost
- etc.

### Brauche ich FTP-Zugang?

**Nein!** Alles läuft in WordPress.

### Werden die Daten beim Deinstallieren gelöscht?

Nein, die Events bleiben in der Datenbank.

Um alles zu löschen:
1. Plugin deinstallieren
2. Via phpMyAdmin: Posts vom Typ `cje_event` löschen

## 📝 Changelog

### Version 4.0.0 (2024-10-24)

- ✨ **NEU:** Komplett in WordPress integriert
- ✨ **NEU:** Keine externe API mehr
- ✨ **NEU:** Keine Setup-Wizard-Probleme
- ✨ **NEU:** Einfaches Event-Formular
- ✨ **NEU:** Excel-Export (CSV)
- ✅ **FIX:** Keine FTP/SSH mehr nötig
- ✅ **FIX:** Funktioniert sofort nach Aktivierung

## 🎉 Fertig!

**Viel Spaß mit dem Plugin!**

Bei Fragen einfach melden. ☕
