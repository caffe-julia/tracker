# Caffe Julia Tracker Pro - Changelog

## Version 5.1 (Bugfix)

### 🐛 Fixes

1. **Mehrtägige Events - alle Tage bearbeitbar**
   - Problem: Andere Tage als Tag 1 waren nicht bearbeitbar
   - Lösung: Alle Tage sind jetzt direkt bearbeitbar

2. **Mitteilungsfeld für gesamten Event**
   - Problem: Mitteilung war pro Tag
   - Lösung: Mitteilung gilt jetzt für den GESAMTEN Event
   - Wird in Event-Gruppe gespeichert
   - Alle Tage zeigen dieselbe Mitteilung

### 📝 Änderungen

**Mitteilungsfeld:**
- Jetzt in der Event-Gruppen-Ansicht (bei mehrtägigen Events)
- Gilt für ALLE Tage des Events
- Wird bei allen Tagen synchronisiert
- In Einzeltag-Ansicht: Read-only mit Hinweis zur Event-Übersicht

**Navigation:**
- Alle Tage haben deutlichen orangen Rahmen (3px statt Standard)
- "→ Klicken zum Bearbeiten" Text zur Verdeutlichung
- Hover-Effekt: Rahmen wird heller, Shadow erscheint
- Einzelne Tage können bearbeitet werden (Mühlen, Getränke, Arbeitszeit)
- Mitteilung wird auf Event-Ebene bearbeitet

**Einstellungen:**
- Neue Einstellungsseite im WordPress-Admin
- Zugriff auf Passwort-Verwaltung (WordPress-Profil)
- Tracker-Informationen (Events, Seite, URL)
- iPhone App-Icon Anleitung

---

## Version 5.0 (Initial Release)

- Original-Tracker mit WordPress-Integration
- localStorage → WordPress REST API
- Dashboard mit Statistiken
- Excel-Export
- iPhone-optimiert
