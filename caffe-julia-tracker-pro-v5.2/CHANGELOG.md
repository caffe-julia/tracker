# Changelog - Caffe Julia Tracker Pro

## Version 5.2.0 - WICHTIGES UPDATE!

**Veröffentlicht:** Oktober 2024

### 🆕 Neues Feature: Eigenes Tracker-Passwort!

**Haupt-Feature:**
- ✅ **Separates Tracker-Passwort** - unabhängig vom WordPress-Login!
- ✅ **Passwort-Verwaltung** im WordPress-Admin (Caffe Tracker → Einstellungen)
- ✅ **Sicherer SHA-256-Hash** - Passwort wird verschlüsselt gespeichert
- ✅ **Standard-Passwort-Warnung** - zeigt an, wenn noch "CyberSecure" aktiv ist

### 📝 Details

**Tracker-Passwort:**
- Neuer Menüpunkt: Caffe Tracker → Einstellungen
- Passwort-Formular mit Bestätigung
- Mindestlänge: 6 Zeichen
- Hash wird in WordPress-Optionen gespeichert (`cjtp_password_hash`)
- Standard-Passwort: "CyberSecure" (bitte ändern!)

**Verbesserungen:**
- Übersichtliche Einstellungsseite mit mehreren Sektionen:
  - 🔐 Tracker-Passwort (unabhängig von WordPress)
  - 🔑 WordPress-Admin-Passwort
  - 📊 Tracker-Information
  - 💾 Daten & Backup
  - 📱 iPhone App-Icon Anleitung

**Sicherheit:**
- Nonce-Validierung
- Passwort-Längen-Prüfung
- Bestätigungs-Feld
- SHA-256 Hashing mit Salt

---

## Version 5.1.0 - Bugfix Release

**Veröffentlicht:** Oktober 2024

### 🐛 Behobene Probleme

1. **Mehrtägige Events:**
   - ✅ Alle Tage sind jetzt klickbar und bearbeitbar
   - ✅ Deutlicher oranger Rahmen (3px)
   - ✅ Hover-Effekt zeigt Klickbarkeit
   - ✅ "→ Klicken zum Bearbeiten" Text

2. **Mitteilungsfeld:**
   - ✅ Gilt jetzt für den GESAMTEN Event (nicht mehr pro Tag)
   - ✅ Zentrale Bearbeitung in Event-Gruppen-Ansicht
   - ✅ In Einzeltag-Ansicht: Read-only mit Link zur Übersicht

3. **Einstellungen:**
   - ✅ Neue Einstellungsseite hinzugefügt
   - ✅ Zugriff auf WordPress-Profil (Passwort ändern)
   - ✅ Tracker-Informationen
   - ✅ iPhone App-Icon Anleitung

---

## Version 5.0.0 - Initial Release

**Veröffentlicht:** Oktober 2024

### ✨ Features

**Tracker:**
- ✅ Original-Design beibehalten (100% iPhone-optimiert)
- ✅ Event-Verwaltung (eintägig und mehrtägig)
- ✅ Arbeitszeiterfassung (Start/Ende/Pause)
- ✅ 1-4 Kaffeemühlen (Doppel-/Einzelbezug)
- ✅ Getränke-Counter (Milch, Hafermilch, Matcha, Schokolade, Tee)
- ✅ Mitteilungsfeld
- ✅ Passwortschutz ("CyberSecure" als Standard)

**WordPress-Integration:**
- ✅ Custom Post Type `cjtp_event`
- ✅ WordPress-Datenbank-Speicherung
- ✅ Shortcode `[caffe_tracker]`
- ✅ Admin-Dashboard mit Statistiken
- ✅ Excel/CSV-Export

**Sicherheit:**
- ✅ SHA-256 Passwort-Hash
- ✅ Session-Token (8 Stunden gültig)
- ✅ Brute-Force-Schutz (Max 5 Versuche)
- ✅ WordPress-Nonce-Schutz

---

## Upgrade-Anleitung

### Von 5.1 auf 5.2

1. **WordPress-Admin** → **Plugins** → **Installieren**
2. **Plugin hochladen** → `caffe-julia-tracker-pro-v5.2.zip`
3. **Jetzt installieren** und **Aktivieren**
4. **Caffe Tracker** → **Einstellungen** öffnen
5. **Neues Tracker-Passwort** setzen (wichtig!)

**Wichtig:** Ihre Daten bleiben erhalten! Kein Datenverlust.

### Von 5.0 auf 5.2

Gleiche Schritte wie oben. Das neue Passwort-System ist abwärtskompatibel.

---

## Lizenz

GPL-2.0+
