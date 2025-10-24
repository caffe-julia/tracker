# Dashboard-Verbindungsfehler beheben

## Schnelldiagnose

### Test 1: API erreichbar?

Öffnen Sie: https://www.caffejulia.ch/tracker/api/

**Ergebnis:**
- [ ] JSON mit "success": true → API läuft, weiter zu Test 2
- [ ] 404 Not Found → API nicht installiert, Setup nochmal durchführen
- [ ] Andere Fehlermeldung → Notieren und melden

### Test 2: Plugin-Einstellungen korrekt?

1. WordPress-Admin → Caffe Julia Tracker → Einstellungen
2. Prüfen Sie **API-URL** Feld:

**Sollte genau sein:**
```
https://www.caffejulia.ch/tracker/api
```

**Häufige Fehler:**
- ❌ `https://www.caffejulia.ch/tracker/api/` (mit / am Ende)
- ❌ `http://www.caffejulia.ch/tracker/api` (http statt https)
- ❌ `www.caffejulia.ch/tracker/api` (ohne https://)

3. Falls falsch: Korrigieren → Speichern → Dashboard neu laden

### Test 3: Browser-Console prüfen

1. Drücken Sie **F12**
2. Klicken Sie auf **"Console"**
3. Laden Sie das Dashboard neu (F5)
4. Sehen Sie rote Fehler?

**Häufige Fehler:**

#### Fehler A: "Failed to fetch"
**Ursache:** API ist nicht erreichbar
**Lösung:** API-URL in Einstellungen prüfen

#### Fehler B: "CORS policy"
**Ursache:** CORS nicht richtig konfiguriert
**Lösung:** Siehe unten "CORS-Fix"

#### Fehler C: "404"
**Ursache:** API-Dateien fehlen
**Lösung:** Setup-Wizard nochmal durchführen

---

## CORS-Fix (falls nötig)

Falls Browser-Console zeigt:
```
Access to fetch at '...' has been blocked by CORS policy
```

### Via FTP:

1. Öffnen Sie die Datei: `/public_html/tracker/config/config.php`

2. Suchen Sie diese Zeilen:
   ```php
   define('CORS_ALLOWED_ORIGINS', [
   ```

3. Ändern Sie zu:
   ```php
   define('CORS_ALLOWED_ORIGINS', [
       'https://www.caffejulia.ch',
       'https://caffejulia.ch',
       'http://www.caffejulia.ch',
       'http://caffejulia.ch',
   ]);
   ```

4. Speichern Sie die Datei

5. WordPress-Dashboard neu laden (F5)

---

## Setup-Wizard erneut starten

Falls API-Dateien fehlen (404-Fehler):

1. WordPress-Admin → **Plugins**
2. Suchen: **"Caffe Julia Tracker"**
3. Klicken: **"Deaktivieren"**
4. Klicken: **"Aktivieren"**
5. Menü: **"Tracker Setup"** erscheint wieder
6. Alle 4 Schritte durchklicken

**Bei Schritt 1 (Dateien installieren) achten auf:**
- ✅ Grün: "API-Dateien erfolgreich installiert!" → Weiter
- ❌ Rot: Fehlermeldung → Screenshot machen und melden

**Häufige Fehler bei Schritt 1:**
- "Konnte Verzeichnis nicht erstellen" → Schreibrechte fehlen
- "Permission denied" → Schreibrechte fehlen
- Lösung: Hoster kontaktieren oder via FTP Ordner /public_html/ auf 755 setzen

---

## Schreibrechte-Problem beheben

### Via FTP (FileZilla):

1. Verbinden Sie sich mit Ihrem Server
2. Navigieren Sie zu `/public_html/`
3. Rechtsklick auf `public_html`
4. **Dateiberechtigungen** / **File Permissions**
5. Setzen Sie auf: **755**
6. ✓ Rekursiv anwenden (alle Unterordner)
7. OK
8. Setup-Wizard nochmal durchführen

### Via Hoster-Support:

Schreiben Sie Ihrem Hoster:

```
Hallo,

ich benötige Schreibrechte für WordPress auf:
/public_html/

Bitte setzen Sie die Berechtigungen auf 755.

Vielen Dank!
```

---

## Checkliste

Gehen Sie diese Punkte durch:

- [ ] Plugin ist aktiviert (Plugins → Caffe Julia Tracker zeigt "Deaktivieren")
- [ ] Setup-Wizard wurde komplett durchlaufen (alle 4 Schritte)
- [ ] API ist erreichbar: https://www.caffejulia.ch/tracker/api/ zeigt JSON
- [ ] API-URL in Einstellungen korrekt: `https://www.caffejulia.ch/tracker/api`
- [ ] Keine CORS-Fehler in Browser-Console (F12)
- [ ] Schreibrechte sind gesetzt (755 auf /public_html/)

Wenn alle Punkte ✓ sind, sollte das Dashboard funktionieren!

---

## Immer noch Probleme?

Senden Sie mir:

1. **Screenshot vom Verbindungsfehler** (Dashboard)
2. **Screenshot von Browser-Console** (F12 → Console, rote Fehler)
3. **Ergebnis von:** https://www.caffejulia.ch/tracker/api/
4. **Screenshot von Plugin-Einstellungen** (API-URL Feld)

Dann kann ich Ihnen gezielt helfen!
