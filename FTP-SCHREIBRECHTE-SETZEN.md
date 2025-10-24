# Schreibrechte via FTP setzen

## Benötigt:
- FTP-Programm (z.B. FileZilla)
- FTP-Zugangsdaten von Ihrem Hoster

## Schritt-für-Schritt:

### 1. FileZilla öffnen

Download: https://filezilla-project.org/ (falls noch nicht installiert)

### 2. Mit Server verbinden

**Verbindungsdaten** (bekommen Sie von Ihrem Hoster):
- Host: `ftp.caffejulia.ch` oder `www.caffejulia.ch`
- Benutzername: [Ihr FTP-User]
- Passwort: [Ihr FTP-Passwort]
- Port: 21 (oder 22 für SFTP)

Klicken Sie auf **"Verbinden"**

### 3. Zu public_html navigieren

Im rechten Fenster (Server):
- Doppelklick auf `public_html` oder `www` Ordner

### 4. Berechtigungen setzen

**Rechtsklick** auf den Ordner `public_html`
→ **Dateiberechtigungen** / **File Permissions**

**Setzen Sie:**
- Numerischer Wert: **755**
- ✓ Haken bei: **"Rekursiv in Unterverzeichnisse"**

Klicken Sie **OK**

### 5. Setup-Wizard nochmal durchführen

1. Zurück zu WordPress-Admin
2. Plugins → Caffe Julia Tracker → Deaktivieren → Aktivieren
3. Tracker Setup → Schritt 1 nochmal

**Sollte jetzt funktionieren!** ✓

---

## Alternative: Ordner manuell erstellen

Falls Schreibrechte-Setzen nicht hilft:

### Via FTP:

1. Verbinden Sie sich mit Ihrem Server (FileZilla)
2. Navigieren Sie zu `/public_html/`
3. Rechtsklick → **Verzeichnis erstellen**
4. Name: `tracker`
5. OK
6. Rechtsklick auf `tracker` → Berechtigungen → **755**
7. Setup-Wizard nochmal durchführen

---

## Testen

Nach dem Setup:

Öffnen Sie: https://www.caffejulia.ch/tracker/api/

**Erwartung:**
```json
{
  "success": true,
  "message": "Caffe Julia Tracker API",
  "version": "2.0",
  "status": "running"
}
```

✓ Wenn Sie das sehen → **API läuft!**
