# ✅ Was Sie jetzt tun müssen

## 🎯 Ihre aktuelle Situation

Sie haben das **WordPress-Plugin** bereits installiert ✅

**Problem:** Die Seite zeigt nichts an (404-Fehler)

**Ursache:** Das **API-Backend** fehlt noch auf Ihrem Webserver!

---

## 💡 Die Lösung besteht aus 2 Teilen

### Teil 1: WordPress-Plugin ✅ ERLEDIGT
Das haben Sie schon installiert!

### Teil 2: API-Backend ⚠️ FEHLT NOCH
Das müssen Sie jetzt auf Ihren Webserver hochladen.

---

## 🚀 So geht's weiter (einfach!)

### Schritt 1: Dateien herunterladen 📥

**Von GitHub:**
1. Gehen Sie zu: https://github.com/caffe-julia/tracker
2. Wechseln Sie zum Branch: `claude/secure-tracker-webapp-011CUQ73xZEUyo1WtZWYzaVu`
3. Laden Sie die Datei herunter: **`caffe-julia-server-files.zip`** (43 KB)

**Oder verwenden Sie die lokalen Dateien:**
- Pfad: `/home/user/tracker/caffe-julia-server-files.zip`

---

### Schritt 2: ZIP entpacken 📦

Entpacken Sie `caffe-julia-server-files.zip` auf Ihrem Computer.

**Inhalt:**
```
✓ api/          (Ordner mit PHP-Backend)
✓ config/       (Konfigurationsdateien)
✓ database/     (MySQL-Schema)
✓ .htaccess     (Sicherheitseinstellungen)
✓ index.html    (Tracker-Oberfläche)
✓ error.html    (Fehlerseite)
```

---

### Schritt 3: Via FTP auf Webserver hochladen 🌐

**A) FTP-Zugang herstellen:**

Nutzen Sie ein FTP-Programm wie **FileZilla** (kostenlos):
- Download: https://filezilla-project.org/

**Verbindungsdaten** (erhalten Sie von Ihrem Hoster):
- Host: `ftp.caffejulia.ch` oder `www.caffejulia.ch`
- Benutzername: [Ihr FTP-User]
- Passwort: [Ihr FTP-Passwort]
- Port: 21 (FTP) oder 22 (SFTP - besser!)

**B) Ordner erstellen:**

Navigieren Sie zu `/public_html/` und erstellen Sie:
```
/public_html/tracker/
```

**C) Dateien hochladen:**

Laden Sie **alle entpackten Dateien** in den Ordner `/public_html/tracker/` hoch:
```
/public_html/tracker/
├── api/          ← kompletter Ordner
├── config/       ← kompletter Ordner
├── database/     ← kompletter Ordner
├── .htaccess     ← einzelne Datei
├── index.html    ← einzelne Datei
└── error.html    ← einzelne Datei
```

**D) Leeren Ordner erstellen:**

Erstellen Sie zusätzlich:
```
/public_html/tracker/logs/
```
(leer lassen, für Log-Dateien)

---

### Schritt 4: MySQL-Datenbank erstellen 🗄️

**A) phpMyAdmin öffnen:**

Loggen Sie sich in Ihr **Hosting-Control-Panel** ein (z.B. cPanel, Plesk) und öffnen Sie **phpMyAdmin**.

**B) Neue Datenbank erstellen:**

1. Klicken Sie auf **"Neu"** oder **"Datenbanken"**
2. Name: `caffe_julia_tracker`
3. Zeichensatz: `utf8mb4_unicode_ci`
4. Klicken Sie auf **"Anlegen"**

**C) Datenbankbenutzer erstellen:**

1. Gehen Sie zu **"Benutzerkonten"** → **"Benutzerkonto hinzufügen"**
2. Benutzername: `caffe_julia_app`
3. Hostname: `localhost`
4. Passwort: **Starkes Passwort generieren** (z.B. mit Passwort-Generator)
5. ✅ Alle Rechte auf `caffe_julia_tracker` vergeben
6. Speichern

**📝 WICHTIG: Notieren Sie sich:**
- Datenbankname: `caffe_julia_tracker`
- Benutzername: `caffe_julia_app`
- Passwort: `________________`

**D) Schema importieren:**

1. Wählen Sie die Datenbank `caffe_julia_tracker` aus
2. Klicken Sie auf **"Importieren"**
3. Wählen Sie die Datei: `database/schema.sql`
4. Klicken Sie auf **"OK"**

✅ Fertig! Datenbank ist jetzt eingerichtet.

---

### Schritt 5: config.php erstellen ⚙️

**Via FTP:**

1. Gehen Sie zu `/public_html/tracker/config/`
2. **Kopieren** Sie `config.example.php`
3. **Benennen Sie die Kopie um** in: `config.php`

**Bearbeiten Sie config.php:**

Öffnen Sie `config.php` und ändern Sie:

```php
// === DATENBANK-KONFIGURATION ===
define('DB_HOST', 'localhost');
define('DB_NAME', 'caffe_julia_tracker');
define('DB_USER', 'caffe_julia_app');
define('DB_PASS', 'IHR_PASSWORT_AUS_SCHRITT_4_HIER');

// === VERSCHLÜSSELUNG ===
// Generieren Sie einen zufälligen 64-Zeichen Key
// Online: https://www.random.org/strings/
define('ENCRYPTION_KEY', 'ZUFÄLLIGEN_64_ZEICHEN_KEY_HIER_EINFÜGEN');

// === CORS (WordPress-Domain erlauben) ===
define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
    'https://caffejulia.ch',
]);
```

**Speichern und hochladen** (falls Sie lokal bearbeitet haben).

**Berechtigung setzen:**
- Rechtsklick auf `config.php` in FileZilla
- **Dateiberechtigungen** → `640`

---

### Schritt 6: Testen ✅

**A) API testen:**

Öffnen Sie in Ihrem Browser:
```
https://www.caffejulia.ch/tracker/api/
```

**Erwartete Antwort (JSON):**
```json
{
  "success": true,
  "message": "Caffe Julia Tracker API",
  "version": "2.0",
  "status": "running"
}
```

✅ **Wenn Sie das sehen → API funktioniert!**

**B) Tracker testen:**

Öffnen Sie:
```
https://www.caffejulia.ch/tracker/
```

**Erwartung:** Login-Maske wird angezeigt

**Test-Login:**
- Benutzername: `admin`
- Passwort: `admin123`

⚠️ **WICHTIG: Ändern Sie das Passwort sofort nach dem ersten Login!**

---

### Schritt 7: WordPress-Plugin verbinden 🔗

**Im WordPress-Admin:**

1. Gehen Sie zu: **Caffe Julia Tracker** → **Einstellungen**
2. Tragen Sie ein:
   - **API-URL:** `https://www.caffejulia.ch/tracker/api`
   - **Cache aktivieren:** ✅ Ja
   - **Cache-Dauer:** `300` (5 Minuten)
3. Klicken Sie auf **"Änderungen speichern"**

**Verbindung testen:**

1. Gehen Sie zu: **Caffe Julia Tracker** → **Dashboard**
2. **Erwartung:** Sie sehen jetzt **Live-Statistiken** (Events, Kaffees, Stunden, Milch)

✅ **Wenn Sie Statistiken sehen → Verbindung funktioniert!**

---

### Schritt 8: Tracker auf Ihrer Seite einbauen 📄

**Bearbeiten Sie die Seite:** `www.caffejulia.ch/tracker`

**Methode 1: Shortcode (einfach)**

Fügen Sie ein:
```
[caffe_julia_tracker height="800px" show_stats="true"]
```

**Methode 2: Gutenberg-Block**

1. Klicken Sie auf **"Block hinzufügen"** (+)
2. Suchen Sie nach: **"Caffe Julia Tracker"**
3. Fügen Sie den Block ein
4. Passen Sie Höhe und Optionen an

**Speichern und Seite ansehen!**

---

## ✅ Fertig!

Nach diesen Schritten sollte Ihr Tracker funktionieren!

**Testen Sie:**
```
https://www.caffejulia.ch/tracker
```

Der Tracker sollte jetzt auf Ihrer WordPress-Seite angezeigt werden. 🎉

---

## 📖 Detaillierte Anleitungen

Wenn Sie mehr Details benötigen:

### Quick-Start (5 Schritte)
📄 **QUICK-START.md** - Schnellanleitung (2 Minuten Lesezeit)

### Ausführlich mit Troubleshooting
📄 **WORDPRESS-API-INSTALLATION.md** - Detaillierte Anleitung (10 Minuten)

### Übersicht
📄 **WORDPRESS-INTEGRATION-README.md** - Komplette Übersicht

---

## ❌ Probleme?

### "404-Fehler bleibt"

**Prüfen Sie:**
1. Sind alle Dateien hochgeladen? (`/public_html/tracker/api/` existiert?)
2. Existiert `.htaccess` im tracker-Ordner?
3. Testen Sie direkt: https://www.caffejulia.ch/tracker/api/index.php

### "Database connection failed"

**Prüfen Sie:**
1. Sind die Zugangsdaten in `config/config.php` korrekt?
2. Funktioniert der Login in phpMyAdmin mit denselben Daten?
3. Ist das Schema importiert? (Tabelle `users` in phpMyAdmin sichtbar?)

### "CORS-Fehler" in Browser-Console

**Lösung:**
Öffnen Sie `config/config.php` und stellen Sie sicher:
```php
define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
    'https://caffejulia.ch',
]);
```

### "Nichts wird angezeigt"

**Prüfen Sie:**
1. Browser-Console (F12 → Console) - Fehler in rot?
2. WordPress-Plugin aktiviert?
3. API-URL in Plugin-Einstellungen korrekt?
4. Shortcode/Block richtig eingefügt?

---

## 🔒 Sicherheit (nach Installation)

### Sofort durchführen:

1. ✅ **Admin-Passwort ändern** (Standard: admin123)
2. ✅ **ENCRYPTION_KEY generieren** (64 zufällige Zeichen)
3. ✅ **config.php Berechtigung** auf 640 setzen
4. ✅ **HTTPS aktivieren** (falls noch nicht)

---

## 📞 Support

**Logs prüfen:**
- Server: `/public_html/tracker/logs/app.log` (via FTP)
- Browser: F12 → Console
- WordPress: `/wp-content/debug.log`

**API direkt testen:**
```
https://www.caffejulia.ch/tracker/api/
```

---

## 🎯 Zusammenfassung

**Was Sie haben:**
- ✅ WordPress-Plugin (installiert)

**Was Sie noch brauchen:**
- ⚠️ API-Backend auf Webserver hochladen (Schritte 1-8 oben)

**Zeitaufwand:** 15-30 Minuten

**Ergebnis:** Vollständig funktionierender Tracker auf Ihrer WordPress-Seite! ☕

---

**Viel Erfolg! 🚀**

Bei Fragen einfach melden - ich helfe gerne weiter!
