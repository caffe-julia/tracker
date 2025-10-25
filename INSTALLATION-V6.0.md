# Installation & Upgrade Guide - Version 6.0

## 🎯 Was ist neu in Version 6.0?

**PROBLEM GELÖST:** Ihre Daten sind jetzt geräteübergreifend verfügbar!

### Vorher (v5.2.1):
```
Safari:    [Events A, B, C]  ← NUR in Safari sichtbar (localStorage)
Edge:      [Events X, Y, Z]  ← NUR in Edge sichtbar (localStorage)
MySQL-DB:  [leer]            ← Keine Events gespeichert!
```

### Jetzt (v6.0):
```
Safari:    [Events A, B, C]  ← Aus MySQL geladen
Edge:      [Events A, B, C]  ← Dieselben Events aus MySQL!
iPhone:    [Events A, B, C]  ← Überall verfügbar!
MySQL-DB:  [Events A, B, C]  ← Zentrale Datenbank ✅
```

## 📥 Installation

### Option 1: Neues Plugin installieren

1. **Download:** `caffe-julia-tracker-pro-v6.0.zip` (bereits im Repository)
2. **WordPress-Admin** → **Plugins** → **Installieren**
3. **Plugin hochladen** → ZIP-Datei auswählen
4. **Jetzt installieren**
5. **Aktivieren**

### Option 2: Von v5.x upgraden

**WICHTIG:** Exportieren Sie zuerst Ihre Daten aus v5.x!

1. **ZUERST:** Öffnen Sie den alten Tracker (v5.2.1) in dem Browser, wo Ihre Daten sind
2. Klicken Sie auf **"📥 CSV Export"** → Datei wird heruntergeladen
3. **WordPress-Admin** → **Plugins** → alte Version deaktivieren
4. **Plugin hochladen** → `caffe-julia-tracker-pro-v6.0.zip`
5. **Jetzt installieren** und **Aktivieren**
6. Öffnen Sie den Tracker: `https://caffejulia.ch/mein-tracker`
7. Klicken Sie auf **"📁 CSV Import"**
8. Wählen Sie die exportierte CSV-Datei
9. **Fertig!** Alle Daten sind jetzt in MySQL

## 🧪 Testen

### Test 1: Cross-Browser-Synchronisation

1. **Safari öffnen:** `https://caffejulia.ch/mein-tracker`
2. **Login** mit Tracker-Passwort
3. **Neues Event erstellen:** z.B. "Test Event" am heutigen Datum
4. **Wichtig:** Warten Sie 2-3 Sekunden (Event wird zu MySQL gespeichert)
5. **Edge öffnen:** `https://caffejulia.ch/mein-tracker`
6. **Login** mit Tracker-Passwort
7. **Überprüfen:** "Test Event" sollte in der Liste erscheinen ✅

### Test 2: Event bearbeiten

1. **In Edge:** Öffnen Sie "Test Event"
2. **Ändern Sie etwas:** z.B. Mühle 1 Doppelbezug Start = 100
3. **Speichern** (automatisch)
4. **Zurück zu Safari:** Aktualisieren Sie die Seite
5. **Öffnen Sie "Test Event":** Änderung sollte sichtbar sein ✅

### Test 3: Event löschen

1. **In Safari:** Löschen Sie "Test Event"
2. **Zurück zu Edge:** Aktualisieren Sie die Seite
3. **Überprüfen:** "Test Event" sollte verschwunden sein ✅

## 🔍 Technische Details

### Was wurde geändert?

**Hauptdatei:** `templates/tracker.php`

**Geänderte Funktionen:**
1. `initializeApp()` → lädt jetzt aus WordPress REST API
2. `saveNewEvent()` → async, speichert zu MySQL
3. `updateEvent()` → async, speichert zu MySQL
4. `updateMuehle()` → async, speichert zu MySQL
5. `updateWorkTime()` → async, speichert zu MySQL
6. `increment()` / `decrement()` → async, speichern zu MySQL
7. `deleteEvent()` → async, löscht aus MySQL
8. `deleteEventGroup()` → async, löscht aus MySQL
9. `importFromCSV()` → async, importiert in MySQL

**Neue Funktionen:**
- `loadEventsFromWordPress()` → Lädt alle Events aus MySQL
- `saveEventToWordPress(event)` → Speichert einzelnes Event zu MySQL
- `deleteEventFromWordPress(eventId)` → Löscht Event aus MySQL

### WordPress REST API Endpoints

Verwendet werden:
- `GET /wp-json/cjtp/v1/events` → Alle Events laden
- `POST /wp-json/cjtp/v1/events` → Neues Event erstellen
- `PUT /wp-json/cjtp/v1/events/{id}` → Event aktualisieren
- `DELETE /wp-json/cjtp/v1/events/{id}` → Event löschen

## ⚠️ Bekannte Einschränkungen

1. **Internet-Verbindung erforderlich:** Im Gegensatz zu localStorage benötigt MySQL eine aktive Verbindung
2. **Etwas langsamer:** MySQL-Operationen sind minimal langsamer als localStorage (aber kaum spürbar)
3. **Alte Daten:** localStorage-Daten aus v5.x werden NICHT automatisch migriert (siehe CSV Export/Import oben)

## 🆘 Troubleshooting

### Problem: "Fehler beim Laden der Events"

**Lösung:**
1. Überprüfen Sie die Browser-Konsole (F12)
2. Checken Sie WordPress-Permalinks: **Einstellungen** → **Permalinks** → **Speichern**
3. Überprüfen Sie die REST API: `https://caffejulia.ch/wp-json/cjtp/v1/events`

### Problem: "Fehler beim Speichern"

**Lösung:**
1. Überprüfen Sie, ob Sie eingeloggt sind
2. Checken Sie Browser-Konsole auf Nonce-Fehler
3. Aktualisieren Sie die Seite und versuchen Sie es erneut

### Problem: Events erscheinen nicht in anderen Browsern

**Lösung:**
1. Warten Sie 2-3 Sekunden nach dem Erstellen
2. Aktualisieren Sie die andere Browser-Seite (F5)
3. Überprüfen Sie Browser-Konsole auf Fehler

## 📊 Datenmigration

### Von v5.x zu v6.0

**Schritt 1: Export aus v5.x**
```
1. Öffnen: caffejulia.ch/mein-tracker (in dem Browser mit Daten)
2. Login mit Tracker-Passwort
3. Scrollen zu "Events"
4. Klick auf "📥 CSV Export"
5. Datei speichern: z.B. "caffe-julia-events-2024-10-25.csv"
```

**Schritt 2: Import in v6.0**
```
1. Installieren: v6.0 Plugin
2. Öffnen: caffejulia.ch/mein-tracker
3. Login mit Tracker-Passwort
4. Scrollen zu "Events"
5. Klick auf "📁 CSV Import"
6. Wählen: gespeicherte CSV-Datei
7. Bestätigen: Import-Dialog
8. Fertig! Alle Events sind jetzt in MySQL
```

## ✅ Checkliste

Nach der Installation:

- [ ] Plugin v6.0 installiert und aktiviert
- [ ] Shortcode auf Seite vorhanden: `[caffe_tracker]`
- [ ] Tracker-Passwort gesetzt (WordPress-Admin → Caffe Tracker → Einstellungen)
- [ ] Alte Daten aus v5.x exportiert (falls vorhanden)
- [ ] Alte Daten in v6.0 importiert (falls vorhanden)
- [ ] Test-Event in Safari erstellt
- [ ] Test-Event in Edge sichtbar
- [ ] Test-Event bearbeitet und Änderungen in Safari sichtbar
- [ ] Test-Event gelöscht und in Edge verschwunden

## 🎉 Erfolg!

Wenn alle Tests bestanden sind, haben Sie jetzt:

✅ Geräteübergreifende Datensynchronisation
✅ Zentrale MySQL-Datenbank
✅ Keine Browser-Beschränkungen mehr
✅ Sichere Datenspeicherung in WordPress
✅ Automatische Backups über WordPress

---

**Bei Problemen:** Öffnen Sie ein Issue auf GitHub oder kontaktieren Sie den Support.

**Version:** 6.0.0
**Datum:** Oktober 2024
**Lizenz:** GPL-2.0+
