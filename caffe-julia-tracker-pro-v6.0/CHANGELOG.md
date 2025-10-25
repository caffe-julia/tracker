# Changelog - Caffe Julia Tracker Pro

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
