# Quick-Start: API-Backend für WordPress installieren

## ⚡ Schnellanleitung in 5 Schritten

### ✅ Schritt 1: Dateien hochladen (via FTP)

Laden Sie diese Ordner auf Ihren Webserver in `/public_html/tracker/`:

```
✓ api/          (kompletter Ordner)
✓ config/       (kompletter Ordner)
✓ database/     (kompletter Ordner)
✓ logs/         (leeren Ordner erstellen)
✓ .htaccess     (Datei)
✓ index.html    (Datei)
✓ error.html    (Datei)
```

---

### ✅ Schritt 2: MySQL-Datenbank erstellen (via phpMyAdmin)

1. **Neue Datenbank:** `caffe_julia_tracker` (utf8mb4_unicode_ci)
2. **Neuer Benutzer:** `caffe_julia_app` mit starkem Passwort
3. **Rechte vergeben:** Alle Rechte auf die Datenbank
4. **Schema importieren:** `database/schema.sql` importieren

**Notieren Sie:**
- Datenbankname: _______________
- Benutzername: _______________
- Passwort: _______________

---

### ✅ Schritt 3: config.php erstellen

Via FTP:

1. **Kopieren:** `config/config.example.php` → `config/config.php`
2. **Bearbeiten:** config.php öffnen und anpassen:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'caffe_julia_tracker');
define('DB_USER', 'caffe_julia_app');
define('DB_PASS', 'IHR_PASSWORT_HIER');

define('ENCRYPTION_KEY', 'ZUFÄLLIGEN_64_ZEICHEN_KEY_HIER');

define('CORS_ALLOWED_ORIGINS', [
    'https://www.caffejulia.ch',
    'https://caffejulia.ch',
]);
```

3. **Berechtigung setzen:** config.php → 640

---

### ✅ Schritt 4: Installation testen

Öffnen Sie in Ihrem Browser:

**API-Test:**
```
https://www.caffejulia.ch/tracker/api/
```

**Erwartete Antwort:**
```json
{
  "success": true,
  "message": "Caffe Julia Tracker API",
  "version": "2.0",
  "status": "running"
}
```

**Tracker-Test:**
```
https://www.caffejulia.ch/tracker/
```

**Login:**
- Benutzername: `admin`
- Passwort: `admin123`

⚠️ **Ändern Sie das Passwort sofort!**

---

### ✅ Schritt 5: WordPress-Plugin verbinden

Im WordPress-Admin:

1. **Menü:** Caffe Julia Tracker → **Einstellungen**
2. **API-URL:** `https://www.caffejulia.ch/tracker/api`
3. **Speichern**
4. **Testen:** Gehen Sie zum Dashboard → sollte Statistiken anzeigen

**Tracker auf Seite einfügen:**
```
[caffe_julia_tracker height="800px"]
```

---

## 🔧 Häufige Probleme

### Problem: 404-Fehler
**Lösung:** Prüfen Sie, ob alle Dateien hochgeladen wurden und `.htaccess` existiert

### Problem: Database connection failed
**Lösung:** Prüfen Sie die Zugangsdaten in `config/config.php`

### Problem: CORS-Fehler
**Lösung:** Fügen Sie Ihre Domain in `config/config.php` bei `CORS_ALLOWED_ORIGINS` hinzu

---

## 📖 Ausführliche Anleitung

Für detaillierte Schritt-für-Schritt-Anleitung siehe:
👉 **WORDPRESS-API-INSTALLATION.md**

---

## ☕ Fertig!

Nach erfolgreicher Installation:

✓ Tracker läuft auf: https://www.caffejulia.ch/tracker/
✓ WordPress-Plugin ist verbunden
✓ Statistiken werden angezeigt

**Viel Erfolg mit Ihrem Caffe Julia Tracker!**
