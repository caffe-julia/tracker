# 🎉 Installation Version 5.1 - Bugfix Update!

## 🐛 Was wurde behoben?

Version 5.1 behebt wichtige Probleme aus Version 5.0:

### ✅ Problem 1: Mehrtägige Events - Alle Tage bearbeitbar
- **Vorher:** Nur Tag 1 war klickbar und bearbeitbar
- **Jetzt:** ALLE Tage sind direkt klickbar mit deutlichem orangen Rahmen
- **Neu:** Hover-Effekt zeigt, dass Tage klickbar sind

### ✅ Problem 2: Mitteilungsfeld für gesamten Event
- **Vorher:** Mitteilung musste pro Tag eingegeben werden
- **Jetzt:** Mitteilung gilt für den GESAMTEN Event (alle Tage)
- **Wo:** In der Event-Übersicht (bei mehrtägigen Events)
- **Sync:** Wird automatisch über alle Tage synchronisiert

### ✅ Problem 3: Passwort-Verwaltung
- **Neu:** Einstellungsseite im WordPress-Admin
- **Zugriff:** WordPress-Admin → Caffe Tracker → Einstellungen
- **Features:** Passwort ändern, Tracker-Info, iPhone-Anleitung

---

## 📦 Installation (Update von v5.0 auf v5.1)

### Option A: Neues Plugin hochladen (Empfohlen)

1. **WordPress-Admin öffnen:** www.caffejulia.ch/wp-admin
2. **Plugins → Installieren**
3. **Plugin hochladen**
4. **Datei wählen:** `caffe-julia-tracker-pro-v5.1.zip` (40 KB)
5. **Jetzt installieren**
6. **Plugin aktivieren**

✅ **Fertig!** Ihre Daten bleiben erhalten.

### Option B: Altes Plugin erst deaktivieren

Falls Sie Version 5.0 schon installiert haben:

1. **Plugins → Installierte Plugins**
2. Suchen Sie **"Caffe Julia Tracker Pro"**
3. Klicken Sie **Deaktivieren** (NICHT Löschen!)
4. Dann installieren Sie v5.1 wie oben beschrieben
5. **Plugin aktivieren**

✅ **Fertig!** Ihre Daten sind sicher.

---

## 🎯 Was ist neu in v5.1?

### 1. Mehrtägige Events - Jetzt voll funktional!

**In der Event-Übersicht:**
- Klicken Sie auf ein mehrtägiges Event
- Sie sehen die Gesamtübersicht mit allen Statistiken
- **NEU:** Mitteilungsfeld ganz oben (gilt für ALLE Tage)
- Darunter: Liste aller Tage

**Einzelne Tage bearbeiten:**
- Jeder Tag hat einen **dicken orangen Rahmen** (3px)
- Text zeigt: "→ Klicken zum Bearbeiten"
- Bei Hover: Rahmen wird heller, Shadow erscheint
- Klick auf einen Tag → Detail-Ansicht für diesen Tag

### 2. Mitteilungsfeld - Zentral für gesamten Event

**Bei mehrtägigen Events:**
```
┌─────────────────────────────────────┐
│ 📝 Mitteilung für gesamtes Event    │
│                                     │
│ [Textfeld - gilt für ALLE 3 Tage]  │
│ 💡 Diese Mitteilung gilt für ALLE  │
│    3 Tage des Events               │
└─────────────────────────────────────┘
```

**In der Einzeltag-Ansicht:**
- Mitteilung wird angezeigt (read-only)
- Button: "← Zur Event-Übersicht (Mitteilung bearbeiten)"

### 3. Einstellungsseite

**WordPress-Admin → Caffe Tracker → Einstellungen**

Hier finden Sie:
- 🔐 **Passwort ändern:** Link zum WordPress-Profil
- 📊 **Tracker-Info:** Anzahl Events, Tracker-URL
- 💾 **Daten & Backup:** Info über Datenbank und Export
- 📱 **iPhone-Anleitung:** App-Icon zum Homescreen hinzufügen

---

## 🧪 Testen Sie die neuen Features!

### Test 1: Mehrtägiges Event erstellen

1. **Neues Event** → Event-Name: "Test Event"
2. **Mehrtägig** ✓
3. **Start:** Heute, **Ende:** In 2 Tagen
4. **Speichern**

→ Es werden 3 Events erstellt: "Test Event - Tag 1", "Tag 2", "Tag 3"

### Test 2: Event-Gruppe öffnen

1. In der **Übersicht:** Klick auf "Test Event"
2. Sie sehen:
   - Gesamtübersicht (alle 3 Tage zusammen)
   - **Mitteilungsfeld** (ganz oben)
   - Liste der einzelnen Tage (mit orangen Rahmen)

### Test 3: Mitteilung für gesamtes Event

1. In der Event-Gruppe
2. Geben Sie eine Mitteilung ein: "Wichtig: Backup nicht vergessen!"
3. **Feld verlassen** (onChange wird getriggert)
4. Rahmen wird kurz grün → Gespeichert!
5. Klicken Sie auf **Tag 2**
6. → Mitteilung wird auch bei Tag 2 angezeigt (read-only)

### Test 4: Einzelnen Tag bearbeiten

1. In der Event-Gruppe
2. Klicken Sie auf **"Test Event - Tag 2"**
3. Sie sehen:
   - Event-Details für Tag 2
   - Mühlen, Getränke, Arbeitszeit
   - Mitteilung (read-only)
   - Button: "← Zur Event-Übersicht"
4. Ändern Sie z.B. Mühle 1 Doppelbezug
5. Klicken Sie "← Zur Event-Übersicht"
6. → Änderungen sind gespeichert

### Test 5: Einstellungen

1. **WordPress-Admin** → **Caffe Tracker** → **Einstellungen**
2. Sie sehen:
   - Ihr WordPress-Benutzername
   - Button "Passwort im Profil ändern"
   - Anzahl gespeicherter Events
   - Link zur Tracker-Seite
   - iPhone-Anleitung

---

## 📱 iPhone verwenden

**Alles funktioniert wie vorher:**
1. Öffnen Sie die Tracker-Seite in Safari
2. Events erstellen
3. Mühlen-Stände eingeben
4. Plus/Minus für Getränke
5. **NEU:** Bei mehrtägigen Events auf jeden Tag klicken!

**Als App-Icon hinzufügen:**
1. Safari → Teilen-Button
2. "Zum Home-Bildschirm"
3. Fertig! 🎉

---

## ❓ Häufige Fragen

### Sind meine Daten sicher?

**Ja!** Das Update ändert NICHTS an Ihren Daten. Alle Events, Mühlen-Stände, Getränke und Mitteilungen bleiben erhalten.

### Muss ich etwas manuell ändern?

**Nein!** Das Plugin aktualisiert sich automatisch. Ihre bestehenden mehrtägigen Events funktionieren sofort mit den neuen Features.

### Wo ist die Mitteilung von Tag 2/3 hin?

Die Mitteilungen von einzelnen Tagen wurden **NICHT gelöscht**. Sie werden jetzt nur zentral in der Event-Gruppe angezeigt. Wenn Sie vorher unterschiedliche Mitteilungen pro Tag hatten, wird die Mitteilung von Tag 1 für alle Tage übernommen.

### Kann ich das alte Mitteilungsfeld zurück haben?

Bei **eintägigen Events** bleibt alles wie vorher. Nur bei **mehrtägigen Events** wird die Mitteilung zentral verwaltet. Das macht Sinn, weil ein mehrtägiges Event typischerweise eine gemeinsame Mitteilung für alle Tage hat.

### Was passiert mit Tag 1, 2, 3 einzeln?

Sie können **jeden Tag einzeln** anklicken und bearbeiten:
- Mühlen-Stände
- Getränke (Milch, Hafermilch, Matcha, Schokolade, Tee)
- Arbeitszeit

Nur die **Mitteilung** wird zentral für alle Tage bearbeitet.

---

## 🎉 Das war's!

**Version 5.1 ist jetzt installiert!**

### Was Sie jetzt haben:

- ✅ Mehrtägige Events vollständig bearbeitbar
- ✅ Zentrale Mitteilung für alle Tage
- ✅ Einstellungsseite mit Passwort-Verwaltung
- ✅ Bessere visuelle Hinweise (oranger Rahmen, Hover-Effekt)
- ✅ Alle bisherigen Features wie vorher

### Empfehlung:

1. Testen Sie ein mehrtägiges Event
2. Probieren Sie die neue Mitteilungs-Funktion
3. Schauen Sie in die Einstellungen
4. Bei Problemen: Kontaktieren Sie den Support

---

**Viel Erfolg mit Version 5.1! ☕**

Version 5.1.0 - Oktober 2024
