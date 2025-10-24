# CORS-Fehler beheben

## Schritt 1: config.php bearbeiten

Via FTP:

1. Öffnen Sie: `/public_html/tracker/config/config.php`
2. Suchen Sie die Zeile:
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
5. Laden Sie die Seite neu (F5)

## Schritt 2: Testen

Öffnen Sie: www.caffejulia.ch/tracker

Funktioniert es jetzt?

## Alternative: .htaccess anpassen

Falls CORS-Problem weiterhin besteht:

1. Öffnen Sie via FTP: `/public_html/tracker/api/.htaccess`
2. Fügen Sie am Anfang hinzu:
   ```apache
   Header always set Access-Control-Allow-Origin "*"
   Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
   Header always set Access-Control-Allow-Headers "Content-Type, Authorization"
   ```
3. Speichern
4. Testen
