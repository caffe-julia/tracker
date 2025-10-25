# Changelog - Caffe Julia Tracker Pro

## Version 6.0.3 - Passwort-Verwaltung vereinfacht!

**Veröffentlicht:** Oktober 2024

### ✅ FERTIG: Einfache Passwort-Verwaltung!

**Problem behoben:**
- ✅ **Standard-Passwort wird automatisch beim Plugin-Aktivieren gesetzt!**
- ✅ **"Passwort vergessen" Link direkt im Login-Screen!**
- ✅ **Standard-Passwort sichtbar im Login-Screen**
- ✅ **Kein phpMyAdmin mehr nötig!**

**Was ist neu:**
1. **Aktivierungs-Hook:** Plugin setzt automatisch Standard-Passwort beim Aktivieren
2. **"Passwort vergessen" Link:** Führt direkt zur WordPress-Admin Einstellungsseite
3. **Standard-Passwort Anzeige:** Im Login-Screen sichtbar (`CaffeJulia2025`)
4. **Einstellungen korrigiert:** Standard-Passwort richtig angezeigt

**Verwendung:**
1. Plugin installieren & aktivieren → Standard-Passwort wird gesetzt
2. Tracker öffnen: `https://ihre-domain.ch/mein-tracker`
3. Login mit: `CaffeJulia2025`
4. Passwort ändern:
   - Klick auf "Passwort vergessen?" Link
   - ODER: WordPress-Admin → Caffe Tracker → Einstellungen
   - Neues Passwort eingeben (mind. 6 Zeichen)
   - Speichern → Fertig!

**Standard-Passwort:**
- Passwort: `CaffeJulia2025`
- Hash: SHA-256 mit Salt `CaffeJulia2025SecureSalt`
- Wird automatisch beim Plugin-Aktivieren in `wp_options` gespeichert

**Technische Änderungen:**
- `activate()` Methode: Setzt Standard-Passwort bei Plugin-Aktivierung
- Login-Screen: "Passwort vergessen" Link hinzugefügt
- Login-Screen: Standard-Passwort wird angezeigt
- Settings-Page: Standard-Passwort Text korrigiert

---

## Version 6.0.2 - KRITISCHER BUGFIX: Session & Cookie Persistenz

**Veröffentlicht:** Oktober 2024

### 🐛 KRITISCHER FIX: Session-Persistenz repariert!

**Problem behoben:**
- ✅ **"REST API restricted to authenticated users" behoben!**
- ✅ Session wird jetzt früh gestartet (init Hook mit Priorität 1)
- ✅ Auth-Cookie wird beim Login gesetzt (8 Stunden Gültigkeit)
- ✅ Permission Callback prüft jetzt Session UND Cookie
- ✅ Cross-Browser-Persistenz funktioniert jetzt!

**Technische Änderungen:**
- `start_session()` Methode hinzugefügt (aufgerufen bei 'init', Priorität 1)
- Login setzt jetzt Auth-Cookie: `cjtp_auth_token` (8h Gültigkeit)
- Session speichert Token: `$_SESSION['cjtp_token']`
- `check_tracker_permission()` prüft jetzt: Session || Cookie || WP-Admin
- Cookie-Flags: HttpOnly=true, Secure=is_ssl()

**Was passiert beim Login (v6.0.2):**
1. Passwort-Eingabe → Client hasht (SHA-256)
2. Client POST zu /login mit Hash
3. Server validiert Hash
4. Server startet Session: `$_SESSION['cjtp_authenticated'] = true`
5. Server generiert Token und speichert in Session
6. Server setzt Cookie: `cjtp_auth_token` (8h gültig)
7. Client speichert Token lokal
8. Bei jedem REST API Call: Cookie wird mitgesendet → Session validiert

**Problem war:**
- Session wurde nicht früh genug gestartet
- Keine Cookie-Persistenz → Session ging verloren bei neuen Requests
- Permission Callback schlug fehl bei REST API Calls

**Lösung:**
- Session-Start in init Hook (Priorität 1)
- Cookie-basierte Authentifizierung zusätzlich zur Session
- Doppelte Prüfung: Session UND Cookie

---

## Version 6.0.1 - KRITISCHER BUGFIX: Login-Authentifizierung

**Veröffentlicht:** Oktober 2024

### 🐛 KRITISCHER FIX: REST API Authentifizierung repariert!

**Problem behoben:**
- ✅ **"Fehler beim Laden der Events" in Edge behoben!**
- ✅ Server-seitiger Login-Endpoint implementiert
- ✅ PHP Session-basierte Authentifizierung für REST API
- ✅ Cross-Browser-Login funktioniert jetzt einwandfrei

**Technische Änderungen:**
- Neuer REST API Endpoint: `/wp-json/cjtp/v1/login`
- Client-seitiger Login ruft jetzt Server-Login auf
- PHP Session wird beim Login gesetzt (`$_SESSION['cjtp_authenticated']`)
- REST API Permission Callback prüft jetzt PHP Session
- Bessere Fehlerbehandlung beim Login

**Was passiert beim Login:**
1. Passwort-Eingabe → Client hasht Passwort (SHA-256)
2. Client validiert Hash lokal → ✅
3. Client sendet Hash an Server → REST API `/login`
4. Server validiert Hash und startet PHP Session → ✅
5. Client kann jetzt REST API nutzen (Events laden/speichern) → ✅

---

## Version 6.0.0 - VOLLSTÄNDIGE MYSQL-INTEGRATION!

**Veröffentlicht:** Oktober 2024

### 🎯 HAUPT-FEATURE: Geräteübergreifende Datensynchronisation!

**Problem gelöst:**
- ✅ **Daten werden jetzt in MySQL-Datenbank gespeichert!** Keine localStorage mehr
- ✅ **Geräteübergreifend:** Safari ↔ Edge ↔ iPhone ↔ iPad - überall dieselben Daten!
- ✅ **Zentrale Datenbank:** Alle Events sicher in WordPress MySQL gespeichert
- ✅ **Keine Browser-Beschränkungen:** Safari-Daten ≠ Edge-Daten war gestern!

**Technische Änderungen:**
- Vollständige WordPress REST API Integration
- Alle Events werden in Echtzeit zu MySQL synchronisiert
- Asynchrone Speicher-Operationen (async/await)
- Automatisches Laden der Events beim Start
- Kein localStorage mehr - nur noch MySQL-Datenbank

### 💡 Was jetzt funktioniert:
- ✅ Event erstellen → sofort in MySQL gespeichert
- ✅ Event bearbeiten → sofort in MySQL aktualisiert
- ✅ Event löschen → sofort aus MySQL entfernt
- ✅ Browser wechseln → alle Daten verfügbar
- ✅ Gerät wechseln → alle Daten verfügbar
- ✅ Multi-Day Events → vollständig synchronisiert
- ✅ CSV Import → direkt in MySQL importiert

### 🔄 Upgrade-Hinweis:

**WICHTIG:** localStorage-Daten aus v5.x werden NICHT automatisch migriert!

**So migrieren Sie Ihre alten Daten:**
1. Öffnen Sie den **alten Tracker (v5.2.1)** in dem Browser, wo Ihre Daten sind
2. Klicken Sie auf **"📥 CSV Export"**
3. Installieren Sie **v6.0**
4. Öffnen Sie den Tracker und klicken Sie auf **"📁 CSV Import"**
5. Wählen Sie die exportierte CSV-Datei
6. Fertig! Alle Daten sind jetzt in MySQL

---

## Version 5.2.1 - KRITISCHER BUGFIX!

**Veröffentlicht:** Oktober 2024

### 🐛 KRITISCHER FIX: Datenspeicherung repariert!

**Problem behoben:**
- ✅ **Daten werden jetzt gespeichert!** localStorage-Fehler behoben
- ✅ Fehlermeldung "privater Modus" entfernt
- ✅ Tracker funktioniert jetzt stabil

**Technische Änderungen:**
- Zurück zum bewährten localStorage (schnell & zuverlässig)
- Passwort-Hash wird dynamisch aus WordPress geladen
- Stabiler und getestet

### 💡 Was funktioniert:
- ✅ Events erstellen und speichern (lokal im Browser)
- ✅ Passwort im WordPress-Admin ändern
- ✅ Alle Original-Features funktionieren
- ✅ iPhone-optimiert

---

## Version 5.2.0 - Eigenes Tracker-Passwort

### ✨ Haupt-Feature:
- ✅ Separates Tracker-Passwort (unabhängig von WordPress)
- ✅ Passwort-Verwaltung im WordPress-Admin
- ✅ SHA-256 Verschlüsselung

⚠️ **Bekanntes Problem:** Datenspeicherung funktionierte nicht → behoben in v5.2.1

---

## Version 5.1.0 - Bugfix Release

### 🐛 Behobene Probleme:
1. Mehrtägige Events - alle Tage klickbar
2. Mitteilungsfeld für gesamten Event
3. Einstellungsseite hinzugefügt

---

## Version 5.0.0 - Initial Release

### ✨ Features:
- Original-Tracker mit WordPress-Integration
- Event-Verwaltung
- Arbeitszeiterfassung
- Kaffeemühlen-Tracking
- Getränke-Counter
- Passwortschutz

---

## Lizenz

GPL-2.0+
