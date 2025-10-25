<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Caffe Julia">
    
    <!-- Content Security Policy -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; object-src 'none'; media-src 'none'; frame-src 'none'; base-uri 'self'; form-action 'self';">
    
    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta name="referrer" content="no-referrer">
    
    <title>Caffe Julia - Event Tracker (Erweitert)</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            min-height: 100vh;
            padding: 16px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 24px;
            margin-bottom: 24px;
        }
        h1 { color: #78350f; font-size: 28px; margin-bottom: 8px; }
        h2 { color: #78350f; font-size: 24px; margin-bottom: 16px; }
        h3 { color: #78350f; font-size: 18px; margin-bottom: 12px; font-weight: 600; }
        .subtitle { color: #92400e; font-size: 14px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .stat-card { padding: 16px; border-radius: 12px; }
        .stat-card.amber { background: #fef3c7; }
        .stat-card.blue { background: #dbeafe; }
        .stat-card.green { background: #d1fae5; }
        .stat-card.orange { background: #fed7aa; }
        .stat-card.purple { background: #e9d5ff; }
        .stat-label { font-size: 12px; margin-bottom: 4px; }
        .stat-value { font-size: 24px; font-weight: bold; }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary { background: #d97706; color: white; flex: 1; }
        .btn-success { background: #059669; color: white; }
        .btn-info { background: #2563eb; color: white; }
        .btn-danger { background: #dc2626; color: white; width: 100%; }
        .btn-group { display: flex; gap: 12px; margin-bottom: 24px; }
        .event-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 16px;
            cursor: pointer;
        }
        .event-header { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .event-title { font-size: 18px; font-weight: bold; color: #78350f; }
        .event-date { font-size: 14px; color: #92400e; margin-top: 4px; }
        .event-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .event-stat { background: #fef3c7; padding: 8px; border-radius: 8px; font-size: 14px; color: #92400e; }
        .input-group { margin-bottom: 16px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #78350f; margin-bottom: 8px; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #fde68a;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus, select:focus { outline: none; border-color: #d97706; }
        .input-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .back-btn {
            color: #92400e;
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 16px;
            padding: 8px;
        }
        .total-box {
            background: linear-gradient(135deg, #fed7aa 0%, #fef3c7 100%);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .total-content { display: flex; justify-content: space-between; align-items: center; }
        .total-label { font-weight: bold; font-size: 18px; color: #78350f; }
        .total-value { font-size: 24px; font-weight: bold; color: #78350f; }
        .empty-state { text-align: center; padding: 48px 0; color: #92400e; }
        .empty-icon { font-size: 48px; opacity: 0.5; margin-bottom: 16px; }
        input[type="file"] { display: none; }
        .counter-row { background: #fed7aa; padding: 16px; border-radius: 12px; margin-bottom: 12px; }
        .counter-row.blue { background: #dbeafe; }
        .counter-row.green { background: #d1fae5; }
        .counter-content { display: flex; justify-content: space-between; align-items: center; }
        .counter-label { font-weight: 600; color: #78350f; }
        .counter-controls { display: flex; align-items: center; gap: 12px; }
        .counter-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
        }
        .counter-btn.minus { background: #dc2626; color: white; }
        .counter-btn.plus { background: #059669; color: white; }
        .counter-value { font-size: 24px; font-weight: bold; color: #78350f; min-width: 80px; text-align: center; }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 8px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
        }
        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            user-select: none;
        }
        .info-box {
            background: #e0f2fe;
            padding: 12px;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 12px;
            color: #0369a1;
        }
        .duration-badge {
            display: inline-block;
            background: #7c3aed;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            margin-left: 8px;
        }
        .time-info-box {
            background: #fef3c7;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .time-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #fde68a;
        }
        .time-info-row:last-child { border-bottom: none; }
        .muehle-section {
            background: #f0fdf4;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 2px solid #bbf7d0;
        }
        .muehle-header {
            font-size: 16px;
            font-weight: bold;
            color: #166534;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bezug-row {
            background: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .bezug-header {
            font-size: 14px;
            font-weight: 600;
            color: #78350f;
            margin-bottom: 8px;
        }
        
        /* Mobile Optimierungen */
        @media (max-width: 768px) {
            body { padding: 8px; }
            .card { padding: 16px; margin-bottom: 16px; }
            h1 { font-size: 24px; }
            h2 { font-size: 20px; }
            h3 { font-size: 16px; }
            .stats-grid { grid-template-columns: 1fr; gap: 12px; }
            .input-row { grid-template-columns: 1fr; gap: 12px; }
            .btn-group { flex-direction: column; }
            .btn { width: 100%; }
            input[type="date"], 
            input[type="time"], 
            input[type="number"],
            input[type="text"],
            select {
                max-width: 100%;
                font-size: 16px;
                -webkit-appearance: none;
            }
            .stat-value { font-size: 20px; }
            .counter-value { font-size: 20px; min-width: 60px; }
            .counter-btn { width: 36px; height: 36px; font-size: 18px; }
            .event-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="app">
            <!-- Lade-Indikator -->
            <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
                <div class="card" style="text-align: center;">
                    <h2 style="color: #78350f; margin-bottom: 16px;">☕ Caffe Julia</h2>
                    <p style="color: #92400e;">Wird geladen...</p>
                    <div style="margin-top: 20px;">
                        <div style="width: 50px; height: 50px; border: 5px solid #fde68a; border-top: 5px solid #d97706; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        // PASSWORTSCHUTZ - Security Enhanced
        // Passwort wird NICHT im Klartext gespeichert
        // Hash generiert mit: SHA-256('CaffeJulia2025' + Salt)
        const APP_PASSWORD_HASH = '<?php echo get_option("cjtp_password_hash", "3f15fdc8b618a9ea5007b26655acfb904daa571a3cf2cfa6f932fae79e63fdaa"); ?>';
        const SALT = 'CaffeJulia2025SecureSalt'; // Salt für zusätzliche Sicherheit
        
        // SHA-256 Hash Funktion (Native Web Crypto API)
        async function hashPassword(password) {
            const encoder = new TextEncoder();
            const data = encoder.encode(password + SALT);
            const hashBuffer = await crypto.subtle.digest('SHA-256', data);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        }
        
        // Prüfe ob bereits eingeloggt
        let isAuthenticated = false;
        let sessionToken = null;
        try {
            sessionToken = sessionStorage.getItem('caffeJuliaToken');
            // Validiere Session Token
            if (sessionToken && sessionToken.length === 64) {
                const tokenTimestamp = sessionStorage.getItem('caffeJuliaTokenTime');
                const now = Date.now();
                // Token ist 8 Stunden gültig
                if (tokenTimestamp && (now - parseInt(tokenTimestamp)) < 8 * 60 * 60 * 1000) {
                    isAuthenticated = true;
                } else {
                    // Token abgelaufen
                    sessionStorage.removeItem('caffeJuliaToken');
                    sessionStorage.removeItem('caffeJuliaTokenTime');
                }
            }
        } catch(e) {
            console.log('SessionStorage nicht verfügbar');
        }

        // Zeige Login-Screen wenn nicht authentifiziert
        function initApp() {
            if (!isAuthenticated) {
                showLoginScreen();
            } else {
                initializeApp();
            }
        }

        // Starte sofort wenn DOM bereit
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initApp);
        } else {
            // DOM ist bereits geladen
            initApp();
        }

        function showLoginScreen() {
            const app = document.getElementById('app');
            app.innerHTML = `
                <div class="card" style="max-width: 400px; margin: 100px auto;">
                    <h2 style="text-align: center; margin-bottom: 24px;">🔒 Caffe Julia Tracker</h2>
                    <p style="text-align: center; color: #92400e; margin-bottom: 24px;">
                        Bitte Passwort eingeben
                    </p>
                    <div class="input-group">
                        <label>Passwort</label>
                        <input type="password" id="passwordInput" 
                            placeholder="Passwort eingeben"
                            style="font-size: 18px;"
                            autocomplete="off"
                            onkeypress="if(event.key === 'Enter') checkPassword()">
                    </div>
                    <div id="errorMsg" style="color: #dc2626; text-align: center; margin-bottom: 12px; display: none;">
                        ❌ Falsches Passwort
                    </div>
                    <div id="attemptsMsg" style="color: #92400e; text-align: center; margin-bottom: 12px; font-size: 12px; display: none;">
                        Zu viele Fehlversuche. Bitte warten Sie <span id="waitTime">60</span> Sekunden.
                    </div>
                    <button class="btn btn-primary" onclick="checkPassword()" id="loginBtn" style="width: 100%;">
                        Anmelden
                    </button>
                    <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Passwort vergessen?</p>
                        <a href="<?php echo admin_url('admin.php?page=caffe-tracker-settings'); ?>"
                           style="color: #92400e; text-decoration: none; font-weight: 500; font-size: 14px;">
                            → Zum WordPress-Admin (Passwort ändern)
                        </a>
                        <p style="font-size: 12px; color: #9ca3af; margin-top: 12px;">
                            Standard-Passwort: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 3px;">CaffeJulia2025</code>
                        </p>
                    </div>
                </div>
            `;
            // Fokus auf Passwort-Feld
            setTimeout(function() {
                document.getElementById('passwordInput').focus();
            }, 100);
        }

        // Brute-Force Protection
        let loginAttempts = 0;
        let lastAttemptTime = 0;
        let lockoutUntil = 0;

        async function checkPassword() {
            const now = Date.now();
            
            // Prüfe Lockout
            if (lockoutUntil > now) {
                const remainingSeconds = Math.ceil((lockoutUntil - now) / 1000);
                document.getElementById('attemptsMsg').style.display = 'block';
                document.getElementById('waitTime').textContent = remainingSeconds;
                return;
            }
            
            const input = document.getElementById('passwordInput').value;
            const errorMsg = document.getElementById('errorMsg');
            const attemptsMsg = document.getElementById('attemptsMsg');
            
            // Rate Limiting: Max 5 Versuche in 60 Sekunden
            if (now - lastAttemptTime < 60000) {
                loginAttempts++;
                if (loginAttempts >= 5) {
                    lockoutUntil = now + 60000;
                    attemptsMsg.style.display = 'block';
                    document.getElementById('loginBtn').disabled = true;
                    
                    // Countdown
                    const countdown = setInterval(function() {
                        const remaining = Math.ceil((lockoutUntil - Date.now()) / 1000);
                        if (remaining <= 0) {
                            clearInterval(countdown);
                            attemptsMsg.style.display = 'none';
                            document.getElementById('loginBtn').disabled = false;
                            loginAttempts = 0;
                        } else {
                            document.getElementById('waitTime').textContent = remaining;
                        }
                    }, 1000);
                    return;
                }
            } else {
                loginAttempts = 1;
            }
            lastAttemptTime = now;
            
            // Hash das eingegebene Passwort und vergleiche
            const inputHash = await hashPassword(input);

            if (inputHash === APP_PASSWORD_HASH) {
                // Client-seitige Validierung erfolgreich
                // Jetzt Server-seitigen Login durchführen (für PHP Session)
                try {
                    const loginResponse = await fetch(API_BASE + 'login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            passwordHash: inputHash
                        })
                    });

                    if (!loginResponse.ok) {
                        throw new Error('Server-Login fehlgeschlagen');
                    }

                    const loginData = await loginResponse.json();

                    if (loginData.success && loginData.token) {
                        // Server-Login erfolgreich - speichere Token
                        const token = loginData.token;

                        try {
                            // Speichere Token in localStorage (persistent!)
                            localStorage.setItem('caffeJuliaAuthToken', token);
                            localStorage.setItem('caffeJuliaTokenTime', Date.now().toString());
                        } catch(e) {
                            console.error('localStorage nicht verfügbar:', e);
                        }

                        isAuthenticated = true;
                        sessionToken = token;
                        errorMsg.style.display = 'none';
                        attemptsMsg.style.display = 'none';
                        loginAttempts = 0;
                        startApp();
                    } else {
                        throw new Error('Server-Authentifizierung fehlgeschlagen');
                    }
                } catch(e) {
                    console.error('Login-Fehler:', e);
                    errorMsg.textContent = 'Server-Verbindungsfehler. Bitte erneut versuchen.';
                    errorMsg.style.display = 'block';
                    document.getElementById('passwordInput').value = '';
                    document.getElementById('passwordInput').focus();
                }
            } else {
                errorMsg.textContent = 'Falsches Passwort';
                errorMsg.style.display = 'block';
                document.getElementById('passwordInput').value = '';
                document.getElementById('passwordInput').focus();

                // Log fehlgeschlagenen Versuch (nur in Console für Debugging)
                console.warn('Fehlgeschlagener Login-Versuch: ' + new Date().toISOString());
            }
        }

        function logout() {
            try {
                localStorage.removeItem('caffeJuliaAuthToken');
                localStorage.removeItem('caffeJuliaTokenTime');
            } catch(e) {}
            isAuthenticated = false;
            sessionToken = null;
            location.reload();
        }

        // Hilfsfunktion: Hole Auth-Token
        function getAuthToken() {
            try {
                return localStorage.getItem('caffeJuliaAuthToken');
            } catch(e) {
                return null;
            }
        }

        // Hilfsfunktion: Get Headers mit Auth-Token
        function getApiHeaders() {
            const headers = {
                'X-WP-Nonce': API_NONCE
            };

            const token = getAuthToken();
            if (token) {
                headers['X-Tracker-Auth'] = token;
            }

            return headers;
        }

        function startApp() {
            // Original App Code startet hier
            initializeApp();
        }

        // Globale Variablen
        var events = [];
        var currentView = 'overview';
        var currentEventId = null;
        var currentEventGroup = null;
        var nextId = 1;

        // WordPress REST API Configuration
        const API_BASE = '<?php echo rest_url("cjtp/v1/"); ?>';
        const API_NONCE = '<?php echo wp_create_nonce("wp_rest"); ?>';

        // Initialisierung mit WordPress-Daten
        async function initializeApp() {
            try {
                await loadEventsFromWordPress();
                nextId = events.length > 0 ? Math.max(...events.map(function(e) { return e.id; })) + 1 : 1;
                render();
            } catch(e) {
                console.error('Initialisierung fehlgeschlagen:', e);
                alert('Fehler beim Laden der Events. Bitte Seite neu laden.');
                events = [];
                render();
            }
        }

        // Events von WordPress laden
        async function loadEventsFromWordPress() {
            try {
                const response = await fetch(API_BASE + 'events', {
                    headers: getApiHeaders()
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const data = await response.json();
                events = data || [];
                console.log('✅ Events geladen:', events.length);
            } catch(e) {
                console.error('❌ Laden fehlgeschlagen:', e);
                throw e;
            }
        }

        // Event in WordPress speichern
        async function saveEventToWordPress(event) {
            try {
                const url = API_BASE + 'events' + (event.wpId ? '/' + event.wpId : '');
                const method = event.wpId ? 'PUT' : 'POST';

                const headers = getApiHeaders();
                headers['Content-Type'] = 'application/json';

                const response = await fetch(url, {
                    method: method,
                    headers: headers,
                    body: JSON.stringify(event)
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const result = await response.json();

                // Speichere WordPress-ID
                if (!event.wpId && result.id) {
                    event.wpId = result.id;
                }

                console.log('✅ Event gespeichert:', event.name);
                return true;
            } catch(e) {
                console.error('❌ Speichern fehlgeschlagen:', e);
                alert('Fehler beim Speichern: ' + e.message);
                return false;
            }
        }

        // Event aus WordPress löschen
        async function deleteEventFromWordPress(eventId) {
            const event = events.find(e => e.id === eventId);
            if (!event || !event.wpId) return true;

            try {
                await fetch(API_BASE + 'events/' + event.wpId, {
                    method: 'DELETE',
                    headers: getApiHeaders()
                });
                console.log('✅ Event gelöscht:', event.name);
                return true;
            } catch(e) {
                console.error('❌ Löschen fehlgeschlagen:', e);
                return false;
            }
        }

        // Globale saveData() - ruft für JEDES Event saveEventToWordPress auf
        async function saveData() {
            // Speichere alle modifizierten Events
            for (const event of events) {
                await saveEventToWordPress(event);
            }
            render();
        }

        // XSS Protection: Sanitize user input
        function sanitizeString(str) {
            if (typeof str !== 'string') return str;
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;')
                .replace(/\//g, '&#x2F;');
        }

        function sanitizeEvent(event) {
            return {
                ...event,
                name: sanitizeString(event.name),
                mitteilung: sanitizeString(event.mitteilung),
                date: sanitizeString(event.date)
            };
        }

        // Validiere numerische Eingaben
        function validateNumber(value, min = 0, max = 999999) {
            const num = parseFloat(value);
            if (isNaN(num)) return min;
            return Math.max(min, Math.min(max, num));
        }

        function validateInteger(value, min = 0, max = 999999) {
            const num = parseInt(value);
            if (isNaN(num)) return min;
            return Math.max(min, Math.min(max, num));
        }

        async function copyPreviousDayStands(currentEvent) {
            // Nur bei mehrtägigen Events
            if (!currentEvent.isPartOfMultiDay || currentEvent.multiDayIndex === 1) {
                return; // Erster Tag oder kein mehrtägiges Event
            }

            // Finde den Vortag
            const baseName = getEventBaseName(currentEvent.name);
            const previousDayIndex = currentEvent.multiDayIndex - 1;
            const previousDayName = baseName + ' - Tag ' + previousDayIndex;

            const previousEvent = events.find(e => e.name === previousDayName);

            if (previousEvent && previousEvent.muehlen && currentEvent.muehlen) {
                // Kopiere für jede Mühle
                for (let i = 0; i < currentEvent.muehlen.length; i++) {
                    if (previousEvent.muehlen[i]) {
                        // Doppelbezug: Endstand vom Vortag wird Anfangsstand heute
                        if (!currentEvent.muehlen[i].doppelBezug) {
                            currentEvent.muehlen[i].doppelBezug = { start: 0, ende: 0 };
                        }
                        currentEvent.muehlen[i].doppelBezug.start = previousEvent.muehlen[i].doppelBezug?.ende || 0;

                        // Einzelbezug: Endstand vom Vortag wird Anfangsstand heute
                        if (!currentEvent.muehlen[i].einzelBezug) {
                            currentEvent.muehlen[i].einzelBezug = { start: 0, ende: 0 };
                        }
                        currentEvent.muehlen[i].einzelBezug.start = previousEvent.muehlen[i].einzelBezug?.ende || 0;
                    }
                }
                await saveEventToWordPress(currentEvent);
                return true;
            }
            return false;
        }

        function getEventBaseName(eventName) {
            // Entfernt " - Tag X" vom Namen
            return eventName.replace(/ - Tag \d+$/, '');
        }

        function groupEventsByName() {
            const grouped = {};
            events.forEach(event => {
                const baseName = event.isPartOfMultiDay ? getEventBaseName(event.name) : event.name;
                if (!grouped[baseName]) {
                    grouped[baseName] = [];
                }
                grouped[baseName].push(event);
            });
            
            // Sortiere Events innerhalb jeder Gruppe nach Datum
            Object.keys(grouped).forEach(key => {
                grouped[key].sort((a, b) => new Date(a.date) - new Date(b.date));
            });
            
            return grouped;
        }

        function viewEventGroup(baseName) {
            currentEventGroup = baseName;
            currentView = 'group';
            render();
        }

        // saveData() ist bereits oben definiert (async version)

        function addNewEvent() {
            currentView = 'new';
            render();
        }

        function cancelNewEvent() {
            currentView = 'overview';
            render();
        }

        async function saveNewEvent() {
            const eventName = document.getElementById('eventName').value;
            const mehrtagig = document.getElementById('mehrtagig').checked;
            const ganztaegig = document.getElementById('ganztaegig').checked;
            const anzahlMuehlen = parseInt(document.getElementById('anzahlMuehlen').value) || 1;

            if (!eventName) {
                alert('Bitte Event-Name eingeben');
                return;
            }

            let dates = [];

            if (mehrtagig) {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (!startDate || !endDate) {
                    alert('Bitte Start- und Enddatum eingeben');
                    return;
                }

                // Generiere alle Daten zwischen Start und Ende
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (end < start) {
                    alert('Enddatum muss nach Startdatum liegen');
                    return;
                }

                for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                    dates.push(d.toISOString().split('T')[0]);
                }
            } else {
                const eventDate = document.getElementById('eventDate').value;
                if (!eventDate) {
                    alert('Bitte Datum eingeben');
                    return;
                }
                dates.push(eventDate);
            }

            const newEvents = [];

            // Erstelle für jeden Tag ein separates Event
            for (let index = 0; index < dates.length; index++) {
                const date = dates[index];
                const dayNumber = dates.length > 1 ? ` - Tag ${index + 1}` : '';
                const newEvent = {
                    id: nextId++,
                    name: eventName + dayNumber,
                    date: date,
                    ganztaegig: ganztaegig,
                    anzahlMuehlen: anzahlMuehlen,
                    mehrtagig: mehrtagig,
                    isPartOfMultiDay: dates.length > 1,
                    multiDayIndex: index + 1,
                    multiDayTotal: dates.length,
                    workStartTime: '',
                    workEndTime: '',
                    workBreakMinutes: 0,
                    workHours: 0,
                    muehlen: []
                };

                // Initialisiere Mühlen-Array
                for (let i = 1; i <= anzahlMuehlen; i++) {
                    newEvent.muehlen.push({
                        nummer: i,
                        name: `Mühle ${i}`,
                        doppelBezug: { start: 0, ende: 0 },
                        einzelBezug: { start: 0, ende: 0 }
                    });
                }

                events.push(newEvent);
                newEvents.push(newEvent);
            }

            // Speichere alle neuen Events zu WordPress
            for (const evt of newEvents) {
                await saveEventToWordPress(evt);
            }

            currentView = 'overview';
            render();

            if (dates.length > 1) {
                alert(`${dates.length} Events (${dates[0]} bis ${dates[dates.length-1]}) wurden erstellt`);
            }
        }

        function viewEvent(id) {
            currentEventId = id;
            const event = events.find(e => e.id === id);
            if (event && event.isPartOfMultiDay) {
                currentEventGroup = getEventBaseName(event.name);
                
                // Automatisch Vortagesstände übernehmen wenn alle Stände noch 0 sind
                if (event.multiDayIndex > 1) {
                    const allZero = event.muehlen.every(m => 
                        (m.doppelBezug?.start || 0) === 0 && 
                        (m.doppelBezug?.ende || 0) === 0 &&
                        (m.einzelBezug?.start || 0) === 0 && 
                        (m.einzelBezug?.ende || 0) === 0
                    );
                    
                    if (allZero) {
                        copyPreviousDayStands(event);
                    }
                }
            }
            currentView = 'detail';
            render();
        }

        function backToOverview() {
            currentView = 'overview';
            currentEventId = null;
            currentEventGroup = null;
            render();
        }

        function backToGroup() {
            currentView = 'group';
            currentEventId = null;
            render();
        }

        async function updateEvent(id, field, value) {
            const event = events.find(e => e.id === id);
            if (event) {
                // Input Validation basierend auf Feld-Typ
                switch(field) {
                    case 'name':
                    case 'mitteilung':
                    case 'date':
                        event[field] = sanitizeString(value);
                        break;
                    case 'workHours':
                        event[field] = validateNumber(value, 0, 24);
                        break;
                    case 'workBreakMinutes':
                        event[field] = validateInteger(value, 0, 480);
                        break;
                    case 'anzahlMuehlen':
                        event[field] = validateInteger(value, 1, 3);
                        break;
                    case 'milch':
                    case 'hafermilch':
                        event[field] = validateInteger(value, 0, 1000);
                        break;
                    default:
                        event[field] = value;
                }
                await saveEventToWordPress(event);
                render();
            }
        }

        async function updateMuehle(eventId, muehleIndex, bezugType, field, value) {
            const event = events.find(e => e.id === eventId);
            if (event && event.muehlen[muehleIndex]) {
                // Validiere numerische Eingabe
                const validatedValue = validateInteger(value, 0, 999999);

                if (bezugType === 'doppel') {
                    event.muehlen[muehleIndex].doppelBezug[field] = validatedValue;
                } else {
                    event.muehlen[muehleIndex].einzelBezug[field] = validatedValue;
                }
                await saveEventToWordPress(event);
                render();
            }
        }

        function calculateWorkHours(event) {
            if (!event.workStartTime || !event.workEndTime) {
                return '0.0';
            }

            const start = event.workStartTime.split(':');
            const end = event.workEndTime.split(':');
            
            const startMinutes = parseInt(start[0]) * 60 + parseInt(start[1]);
            let endMinutes = parseInt(end[0]) * 60 + parseInt(end[1]);
            
            // Handle overnight shifts
            if (endMinutes < startMinutes) {
                endMinutes += 24 * 60;
            }
            
            const totalMinutes = endMinutes - startMinutes - (event.workBreakMinutes || 0);
            const hours = (totalMinutes / 60).toFixed(2);
            
            // Update workHours field
            event.workHours = parseFloat(hours);
            saveEventToWordPress(event); // Async, fire and forget

            return hours;
        }

        async function updateWorkTime(eventId) {
            const event = events.find(e => e.id === eventId);
            if (event) {
                const startTime = document.getElementById(`startTime${eventId}`).value;
                const endTime = document.getElementById(`endTime${eventId}`).value;
                const breakMinutes = parseInt(document.getElementById(`breakMinutes${eventId}`).value) || 0;

                event.workStartTime = startTime;
                event.workEndTime = endTime;
                event.workBreakMinutes = breakMinutes;

                await saveEventToWordPress(event);
                render();
            }
        }

        function calculateTotalKaffees(muehlen) {
            let totalDoppel = 0;
            let totalEinzel = 0;

            muehlen.forEach(muehle => {
                // Einzelner Doppelbezug
                if (muehle.doppelBezug) {
                    const diff = (muehle.doppelBezug.ende || 0) - (muehle.doppelBezug.start || 0);
                    totalDoppel += Math.max(0, diff);
                }
                // Einzelner Einzelbezug
                if (muehle.einzelBezug) {
                    const diff = (muehle.einzelBezug.ende || 0) - (muehle.einzelBezug.start || 0);
                    totalEinzel += Math.max(0, diff);
                }
            });

            return {
                doppel: totalDoppel,
                einzel: totalEinzel,
                total: totalDoppel * 2 + totalEinzel
            };
        }

        async function increment(id, field) {
            const event = events.find(e => e.id === id);
            if (event) {
                event[field] = (event[field] || 0) + 1;
                await saveEventToWordPress(event);
                render();
            }
        }

        async function decrement(id, field) {
            const event = events.find(e => e.id === id);
            if (event && event[field] > 0) {
                event[field]--;
                await saveEventToWordPress(event);
                render();
            }
        }

        async function deleteEvent(id) {
            if (confirm('Event wirklich löschen?')) {
                // Lösche zuerst aus WordPress
                await deleteEventFromWordPress(id);

                // Dann aus lokalem Array entfernen
                events = events.filter(e => e.id !== id);
                currentView = 'overview';
                render();
            }
        }

        async function deleteEventGroup(baseName) {
            const grouped = groupEventsByName();
            const eventGroup = grouped[baseName];
            const eventCount = eventGroup.length;

            if (confirm(`Gesamtes Event "${baseName}" mit allen ${eventCount} Tag(en) wirklich löschen?`)) {
                // Lösche alle Events dieser Gruppe aus WordPress
                for (const event of eventGroup) {
                    await deleteEventFromWordPress(event.id);
                }

                // Dann aus lokalem Array entfernen
                events = events.filter(e => {
                    const eventBaseName = e.isPartOfMultiDay ? getEventBaseName(e.name) : e.name;
                    return eventBaseName !== baseName;
                });

                currentView = 'overview';
                currentEventGroup = null;
                render();
            }
        }

        function exportToCSV() {
            // CSV Header
            let csv = 'Event;Datum;Ganztägig;Arbeitsbeginn;Arbeitsende;Pause (Min);Arbeitsstunden;Anzahl Mühlen;Total Kaffees;Doppelbezüge;Einzelbezüge;Milch (L);Hafermilch (L);Matcha;Schokolade;Tee;Mitteilung\n';
            
            // Daten
            events.forEach(function(e) {
                const kaffees = calculateTotalKaffees(e.muehlen || []);
                csv += '"' + (e.name || '').replace(/"/g, '""') + '";';
                csv += (e.date || '') + ';';
                csv += (e.ganztaegig ? 'Ja' : 'Nein') + ';';
                csv += (e.workStartTime || '') + ';';
                csv += (e.workEndTime || '') + ';';
                csv += (e.workBreakMinutes || 0) + ';';
                csv += (e.workHours || 0) + ';';
                csv += (e.anzahlMuehlen || 0) + ';';
                csv += kaffees.total + ';';
                csv += kaffees.doppel + ';';
                csv += kaffees.einzel + ';';
                csv += (e.milch || 0) + ';';
                csv += (e.hafermilch || 0) + ';';
                csv += (e.ausgabeMatcha || 0) + ';';
                csv += (e.ausgabeSchokolade || 0) + ';';
                csv += (e.ausgabeTee || 0) + ';';
                csv += '"' + (e.mitteilung || '').replace(/"/g, '""').replace(/\n/g, ' ') + '"';
                csv += '\n';
            });

            // Download
            const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'CaffeJulia_Export_' + new Date().toISOString().split('T')[0] + '.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function importFromCSV(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    const text = e.target.result;
                    const lines = text.split('\n');
                    const imported = [];

                    // Skip header
                    for (let i = 1; i < lines.length; i++) {
                        const line = lines[i].trim();
                        if (!line) continue;

                        // Einfaches CSV-Parsing
                        const cols = line.split(';');
                        if (cols.length < 10) continue;

                        imported.push({
                            id: nextId++,
                            name: cols[0].replace(/^"|"$/g, '').replace(/""/g, '"'),
                            date: cols[1],
                            ganztaegig: cols[2] === 'Ja',
                            anzahlMuehlen: parseInt(cols[7]) || 1,
                            workStartTime: cols[3] || '',
                            workEndTime: cols[4] || '',
                            workBreakMinutes: parseInt(cols[5]) || 0,
                            workHours: parseFloat(cols[6]) || 0,
                            milch: parseInt(cols[11]) || 0,
                            hafermilch: parseInt(cols[12]) || 0,
                            ausgabeMatcha: parseInt(cols[13]) || 0,
                            ausgabeSchokolade: parseInt(cols[14]) || 0,
                            ausgabeTee: parseInt(cols[15]) || 0,
                            mitteilung: cols[16] ? cols[16].replace(/^"|"$/g, '').replace(/""/g, '"') : '',
                            muehlen: []
                        });
                    }

                    if (imported.length > 0 && confirm(imported.length + ' Events importieren? Dies überschreibt alle aktuellen Daten.')) {
                        events = imported;

                        // Speichere alle importierten Events zu WordPress
                        for (const evt of imported) {
                            await saveEventToWordPress(evt);
                        }

                        render();
                    }
                } catch(err) {
                    alert('Fehler beim Import: ' + err.message);
                }
            };
            reader.readAsText(file, 'UTF-8');
        }

        function renderOverview() {
            const totalKaffees = events.reduce((sum, e) => {
                const kaffees = calculateTotalKaffees(e.muehlen || []);
                return sum + kaffees.total;
            }, 0);
            const totalMilch = events.reduce((sum, e) => sum + (e.milch || 0), 0);
            const totalHafermilch = events.reduce((sum, e) => sum + (e.hafermilch || 0), 0);
            const totalGetraenke = events.reduce((sum, e) => 
                sum + (e.ausgabeMatcha || 0) + (e.ausgabeSchokolade || 0) + (e.ausgabeTee || 0), 0);
            const totalArbeitsstunden = events.reduce((sum, e) => sum + (e.workHours || 0), 0);

            return `
                <div class="card">
                    <h1>☕ Caffe Julia</h1>
                    <p class="subtitle">Event Tracker (Erweitert mit Arbeitszeiterfassung & 3 Mühlen)</p>
                    <button onclick="logout()" style="position: absolute; top: 24px; right: 24px; background: none; border: 1px solid #d97706; color: #d97706; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;">
                        🔒 Abmelden
                    </button>
                </div>

                <div class="card">
                    <h2>📊 Statistik</h2>
                    <div class="stats-grid">
                        <div class="stat-card amber">
                            <div class="stat-label">Total Kaffees</div>
                            <div class="stat-value">${totalKaffees}</div>
                        </div>
                        <div class="stat-card blue">
                            <div class="stat-label">Milch (Liter)</div>
                            <div class="stat-value">${totalMilch + totalHafermilch}</div>
                        </div>
                        <div class="stat-card green">
                            <div class="stat-label">Getränke</div>
                            <div class="stat-value">${totalGetraenke}</div>
                        </div>
                        <div class="stat-card purple">
                            <div class="stat-label">Arbeitsstunden</div>
                            <div class="stat-value">${totalArbeitsstunden.toFixed(1)}</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="addNewEvent()">+ Neues Event</button>
                        <button class="btn btn-success" onclick="exportToCSV()">📥 CSV Export</button>
                    </div>
                    <label for="importFile" class="btn btn-info" style="display: block; text-align: center;">
                        📤 CSV Import
                    </label>
                    <input type="file" id="importFile" accept=".csv" onchange="importFromCSV(event)">
                </div>

                <div class="card">
                    <h2>📅 Events (${events.length})</h2>
                    ${events.length === 0 ? `
                        <div class="empty-state">
                            <div class="empty-icon">☕</div>
                            <p>Noch keine Events erfasst</p>
                        </div>
                    ` : (() => {
                        const grouped = groupEventsByName();
                        return Object.entries(grouped).map(([baseName, eventGroup]) => {
                            const isMultiDay = eventGroup.length > 1 && eventGroup[0].isPartOfMultiDay;
                            const totalKaffees = eventGroup.reduce((sum, e) => {
                                const k = calculateTotalKaffees(e.muehlen || []);
                                return sum + k.total;
                            }, 0);
                            const totalMilch = eventGroup.reduce((sum, e) => sum + (e.milch || 0) + (e.hafermilch || 0), 0);
                            const totalGetraenke = eventGroup.reduce((sum, e) => 
                                sum + (e.ausgabeMatcha || 0) + (e.ausgabeSchokolade || 0) + (e.ausgabeTee || 0), 0);
                            const totalArbeitsstunden = eventGroup.reduce((sum, e) => sum + (e.workHours || 0), 0);
                            
                            const firstDate = eventGroup[0].date;
                            const lastDate = eventGroup[eventGroup.length - 1].date;
                            
                            if (isMultiDay) {
                                return `
                                    <div class="event-card" onclick="viewEventGroup('${baseName}')">
                                        <div class="event-header">
                                            <div>
                                                <div class="event-title">${baseName}</div>
                                                <div class="event-date">
                                                    📅 ${firstDate} - ${lastDate}
                                                    <span class="duration-badge" style="background: #8b5cf6;">${eventGroup.length} Tage</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="event-stats">
                                            <div class="event-stat">☕ ${totalKaffees} Kaffees</div>
                                            <div class="event-stat">🥛 ${totalMilch} L Milch</div>
                                            <div class="event-stat">🍵 ${totalGetraenke} Getränke</div>
                                            <div class="event-stat">⏱️ ${totalArbeitsstunden.toFixed(1)} Std</div>
                                        </div>
                                    </div>
                                `;
                            } else {
                                const event = eventGroup[0];
                                const kaffees = calculateTotalKaffees(event.muehlen || []);
                                const getraenke = (event.ausgabeMatcha || 0) + (event.ausgabeSchokolade || 0) + (event.ausgabeTee || 0);
                                return `
                                    <div class="event-card" onclick="viewEvent(${event.id})">
                                        <div class="event-header">
                                            <div>
                                                <div class="event-title">${event.name}</div>
                                                <div class="event-date">
                                                    📅 ${event.date}
                                                    ${event.ganztaegig ? '<span class="duration-badge">Ganztägig</span>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="event-stats">
                                            <div class="event-stat">☕ ${kaffees.total} Kaffees</div>
                                            <div class="event-stat">🥛 ${(event.milch || 0) + (event.hafermilch || 0)} L Milch</div>
                                            <div class="event-stat">🍵 ${getraenke} Getränke</div>
                                            <div class="event-stat">⏱️ ${event.workHours || 0} Std</div>
                                        </div>
                                    </div>
                                `;
                            }
                        }).join('');
                    })()}
                </div>
            `;
        }

        function renderNewEvent() {
            return `
                <div class="card">
                    <button class="back-btn" onclick="cancelNewEvent()">← Zurück</button>
                    <h2>Neues Event erstellen</h2>

                    <div class="input-group">
                        <label>Event Name *</label>
                        <input type="text" id="eventName" placeholder="z.B. Hochzeit Müller">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="mehrtagig" onchange="
                            const isChecked = this.checked;
                            document.getElementById('singleDateGroup').style.display = isChecked ? 'none' : 'block';
                            document.getElementById('multiDateGroup').style.display = isChecked ? 'block' : 'none';
                        ">
                        <label for="mehrtagig">Mehrtägiges Event</label>
                    </div>

                    <div id="singleDateGroup">
                        <div class="input-group">
                            <label>Datum *</label>
                            <input type="date" id="eventDate">
                        </div>
                    </div>

                    <div id="multiDateGroup" style="display: none;">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Startdatum *</label>
                                <input type="date" id="startDate">
                            </div>
                            <div class="input-group">
                                <label>Enddatum *</label>
                                <input type="date" id="endDate">
                            </div>
                        </div>
                        <div class="info-box">
                            ℹ️ Bei mehrtägigen Events wird für jeden Tag ein separater Eintrag erstellt
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="ganztaegig">
                        <label for="ganztaegig">Ganztägiges Event</label>
                    </div>

                    <div class="input-group">
                        <label>Anzahl Mühlen</label>
                        <select id="anzahlMuehlen">
                            <option value="1">1 Mühle</option>
                            <option value="2">2 Mühlen</option>
                            <option value="3" selected>3 Mühlen</option>
                        </select>
                    </div>

                    <div class="info-box">
                        ℹ️ Jede Mühle kann 1 Doppelbezug und 1 Einzelbezug erfassen
                    </div>

                    <button class="btn btn-primary" onclick="saveNewEvent()" style="width: 100%; margin-top: 16px;">
                        Event Erstellen
                    </button>
                </div>
            `;
        }

        function renderEventGroup() {
            const grouped = groupEventsByName();
            const eventGroup = grouped[currentEventGroup];
            
            if (!eventGroup) return '<div class="card">Event-Gruppe nicht gefunden</div>';

            const totalKaffees = eventGroup.reduce((sum, e) => {
                const k = calculateTotalKaffees(e.muehlen || []);
                return sum + k.total;
            }, 0);
            const totalDoppel = eventGroup.reduce((sum, e) => {
                const k = calculateTotalKaffees(e.muehlen || []);
                return sum + k.doppel;
            }, 0);
            const totalEinzel = eventGroup.reduce((sum, e) => {
                const k = calculateTotalKaffees(e.muehlen || []);
                return sum + k.einzel;
            }, 0);
            const totalMilch = eventGroup.reduce((sum, e) => sum + (e.milch || 0), 0);
            const totalHafermilch = eventGroup.reduce((sum, e) => sum + (e.hafermilch || 0), 0);
            const totalMatcha = eventGroup.reduce((sum, e) => sum + (e.ausgabeMatcha || 0), 0);
            const totalSchokolade = eventGroup.reduce((sum, e) => sum + (e.ausgabeSchokolade || 0), 0);
            const totalTee = eventGroup.reduce((sum, e) => sum + (e.ausgabeTee || 0), 0);
            const totalArbeitsstunden = eventGroup.reduce((sum, e) => sum + (e.workHours || 0), 0);
            
            const firstDate = eventGroup[0].date;
            const lastDate = eventGroup[eventGroup.length - 1].date;

            return `
                <div class="card">
                    <button class="back-btn" onclick="backToOverview()">← Zurück zur Übersicht</button>
                    <h2>${currentEventGroup}</h2>
                    <div style="background: #e9d5ff; padding: 12px; border-radius: 8px; margin-bottom: 16px; text-align: center; font-weight: 600; color: #7c3aed;">
                        🗓️ ${firstDate} bis ${lastDate} (${eventGroup.length} Tage)
                    </div>

                    <h3>📊 Gesamtübersicht</h3>
                    <div class="stats-grid" style="margin-bottom: 24px;">
                        <div class="stat-card amber">
                            <div class="stat-label">Total Kaffees</div>
                            <div class="stat-value">${totalKaffees}</div>
                            <div style="font-size: 12px; margin-top: 4px; color: #92400e;">
                                ${totalDoppel} Doppel + ${totalEinzel} Einzel
                            </div>
                        </div>
                        <div class="stat-card blue">
                            <div class="stat-label">Milch gesamt</div>
                            <div class="stat-value">${totalMilch + totalHafermilch} L</div>
                            <div style="font-size: 12px; margin-top: 4px; color: #1e40af;">
                                ${totalMilch} L + ${totalHafermilch} L Hafer
                            </div>
                        </div>
                        <div class="stat-card green">
                            <div class="stat-label">Getränke gesamt</div>
                            <div class="stat-value">${totalMatcha + totalSchokolade + totalTee}</div>
                            <div style="font-size: 12px; margin-top: 4px; color: #166534;">
                                ${totalMatcha} M | ${totalSchokolade} S | ${totalTee} T
                            </div>
                        </div>
                        <div class="stat-card purple">
                            <div class="stat-label">Arbeitsstunden</div>
                            <div class="stat-value">${totalArbeitsstunden.toFixed(1)}</div>
                            <div style="font-size: 12px; margin-top: 4px; color: #6b21a8;">
                                Ø ${(totalArbeitsstunden / eventGroup.length).toFixed(1)} Std/Tag
                            </div>
                        </div>
                    </div>

                    <h3>📅 Einzelne Tage</h3>
                    ${eventGroup.map(event => {
                        const kaffees = calculateTotalKaffees(event.muehlen || []);
                        const getraenke = (event.ausgabeMatcha || 0) + (event.ausgabeSchokolade || 0) + (event.ausgabeTee || 0);
                        return `
                            <div class="event-card" onclick="viewEvent(${event.id})" style="margin-bottom: 12px;">
                                <div class="event-header">
                                    <div>
                                        <div class="event-title">${event.name}</div>
                                        <div class="event-date">
                                            📅 ${event.date}
                                            ${event.ganztaegig ? '<span class="duration-badge">Ganztägig</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="event-stats">
                                    <div class="event-stat">☕ ${kaffees.total} Kaffees</div>
                                    <div class="event-stat">🥛 ${(event.milch || 0) + (event.hafermilch || 0)} L Milch</div>
                                    <div class="event-stat">🍵 ${getraenke} Getränke</div>
                                    <div class="event-stat">⏱️ ${event.workHours || 0} Std</div>
                                </div>
                                ${event.mitteilung ? `
                                    <div style="margin-top: 8px; padding: 8px; background: #fef3c7; border-radius: 6px; font-size: 12px; color: #78350f;">
                                        📝 ${event.mitteilung}
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }).join('')}

                    <button class="btn btn-danger" onclick="event.stopPropagation(); deleteEventGroup('${currentEventGroup}')">
                        Gesamtes Event mit allen ${eventGroup.length} Tag(en) löschen
                    </button>
                </div>
            `;
        }

        function renderEventDetail() {
            const event = events.find(e => e.id === currentEventId);
            if (!event) return '<div class="card">Event nicht gefunden</div>';

            const kaffees = calculateTotalKaffees(event.muehlen || []);
            const totalGetraenke = (event.ausgabeMatcha || 0) + (event.ausgabeSchokolade || 0) + (event.ausgabeTee || 0);

            const backButton = event.isPartOfMultiDay && currentEventGroup ? 
                `<button class="back-btn" onclick="backToGroup()">← Zurück zur Event-Übersicht</button>` :
                `<button class="back-btn" onclick="backToOverview()">← Zurück zur Übersicht</button>`;

            return `
                <div class="card">
                    ${backButton}
                    <h2>${event.name}</h2>
                    ${event.isPartOfMultiDay ? `
                        <div style="background: #e9d5ff; padding: 12px; border-radius: 8px; margin-bottom: 12px; text-align: center; font-weight: 600; color: #7c3aed;">
                            🗓️ Mehrtägiges Event: Tag ${event.multiDayIndex} von ${event.multiDayTotal}
                        </div>
                    ` : ''}
                    
                    <h3>📅 Event-Details</h3>
                    <div class="time-info-box">
                        <div class="input-group" style="margin-bottom: 12px;">
                            <label>Datum</label>
                            <input type="date" 
                                value="${event.date}" 
                                onchange="updateEvent(${event.id}, 'date', this.value)"
                                style="font-size: 16px; font-weight: bold;">
                        </div>
                        <div class="checkbox-group" style="margin-bottom: 0; background: white;">
                            <input type="checkbox" 
                                id="ganztaegig${event.id}" 
                                ${event.ganztaegig ? 'checked' : ''}
                                onchange="updateEvent(${event.id}, 'ganztaegig', this.checked);">
                            <label for="ganztaegig${event.id}">Ganztägiges Event</label>
                        </div>
                    </div>

                    <h3>📝 Mitteilung / Notizen</h3>
                    <div class="input-group">
                        <textarea 
                            placeholder="Notizen, Besonderheiten, Bemerkungen..."
                            onchange="updateEvent(${event.id}, 'mitteilung', this.value)"
                            style="width: 100%; min-height: 100px; padding: 12px; border: 2px solid #fde68a; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"
                        >${event.mitteilung || ''}</textarea>
                    </div>

                    ${event.isPartOfMultiDay && event.multiDayIndex > 1 ? `
                        <div style="margin-bottom: 16px;">
                            <button class="btn btn-info" onclick="if(copyPreviousDayStands(events.find(e => e.id === ${event.id}))) { render(); alert('✅ Mühlenstände vom Vortag übernommen!'); } else { alert('❌ Vortag nicht gefunden'); }" style="width: 100%;">
                                📋 Mühlenstände vom Vortag (Tag ${event.multiDayIndex - 1}) übernehmen
                            </button>
                        </div>
                    ` : ''}

                    <h3>⏱️ Arbeitszeit</h3>
                    <div style="background: #e9d5ff; padding: 16px; border-radius: 12px; margin-bottom: 16px;">
                        <div class="input-row" style="margin-bottom: 12px;">
                            <div class="input-group" style="margin-bottom: 0;">
                                <label>Arbeitsbeginn</label>
                                <input type="time" 
                                    value="${event.workStartTime || ''}" 
                                    onchange="updateWorkTime(${event.id})" 
                                    id="startTime${event.id}"
                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                            </div>
                            <div class="input-group" style="margin-bottom: 0;">
                                <label>Arbeitsende</label>
                                <input type="time" 
                                    value="${event.workEndTime || ''}" 
                                    onchange="updateWorkTime(${event.id})" 
                                    id="endTime${event.id}"
                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                            </div>
                        </div>
                        <div class="input-group" style="margin-bottom: 12px;">
                            <label>Pause (Minuten)</label>
                            <input type="number" 
                                value="${event.workBreakMinutes || 0}" 
                                min="0" 
                                step="5"
                                onchange="updateWorkTime(${event.id})" 
                                id="breakMinutes${event.id}"
                                style="font-size: 16px; font-weight: bold; text-align: center;">
                        </div>
                        <div style="padding: 12px; background: rgba(255,255,255,0.5); border-radius: 8px; text-align: center;">
                            <div style="font-weight: bold; font-size: 20px; color: #581c87;">
                                💼 Gesamte Arbeitszeit: ${calculateWorkHours(event)} Stunden
                            </div>
                        </div>
                    </div>

                    <h3>☕ Kaffeemühlen & Bezüge</h3>
                    ${(event.muehlen || []).map((muehle, muehleIndex) => {
                        const doppelDiff = Math.max(0, (muehle.doppelBezug?.ende || 0) - (muehle.doppelBezug?.start || 0));
                        const einzelDiff = Math.max(0, (muehle.einzelBezug?.ende || 0) - (muehle.einzelBezug?.start || 0));
                        const muehleTotal = doppelDiff * 2 + einzelDiff;
                        
                        return `
                            <div class="muehle-section">
                                <div class="muehle-header">
                                    ⚙️ ${muehle.name} - Total: ${muehleTotal} Kaffees (${doppelDiff} Doppel × 2, ${einzelDiff} Einzel)
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <div class="bezug-row">
                                        <div class="bezug-header">🔵 Doppelbezug (${doppelDiff} × 2 = ${doppelDiff * 2} Kaffees)</div>
                                        <div class="input-row">
                                            <div class="input-group" style="margin-bottom: 0;">
                                                <label>Anfangsstand</label>
                                                <input type="number" 
                                                    value="${muehle.doppelBezug?.start || 0}"
                                                    onchange="updateMuehle(${event.id}, ${muehleIndex}, 'doppel', 'start', this.value)"
                                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                                            </div>
                                            <div class="input-group" style="margin-bottom: 0;">
                                                <label>Endstand</label>
                                                <input type="number" 
                                                    value="${muehle.doppelBezug?.ende || 0}"
                                                    onchange="updateMuehle(${event.id}, ${muehleIndex}, 'doppel', 'ende', this.value)"
                                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="bezug-row">
                                        <div class="bezug-header">🟢 Einzelbezug (${einzelDiff} Kaffees)</div>
                                        <div class="input-row">
                                            <div class="input-group" style="margin-bottom: 0;">
                                                <label>Anfangsstand</label>
                                                <input type="number" 
                                                    value="${muehle.einzelBezug?.start || 0}"
                                                    onchange="updateMuehle(${event.id}, ${muehleIndex}, 'einzel', 'start', this.value)"
                                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                                            </div>
                                            <div class="input-group" style="margin-bottom: 0;">
                                                <label>Endstand</label>
                                                <input type="number" 
                                                    value="${muehle.einzelBezug?.ende || 0}"
                                                    onchange="updateMuehle(${event.id}, ${muehleIndex}, 'einzel', 'ende', this.value)"
                                                    style="font-size: 16px; font-weight: bold; text-align: center;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}

                    <div class="total-box">
                        <div class="total-content">
                            <span class="total-label">☕ Gesamte Kaffees</span>
                            <span class="total-value">${kaffees.total}</span>
                        </div>
                        <div style="font-size: 12px; color: #92400e; margin-top: 4px; text-align: right;">
                            ${kaffees.doppel} Doppel (×2) + ${kaffees.einzel} Einzel = ${kaffees.total} Kaffees
                        </div>
                    </div>

                    <h3 style="margin-top: 24px;">🥛 Milchverbrauch (Liter)</h3>
                    <div class="counter-row blue">
                        <div class="counter-content">
                            <span class="counter-label">Milch</span>
                            <div class="counter-controls">
                                <button class="counter-btn minus" onclick="decrement(${event.id}, 'milch')">−</button>
                                <span class="counter-value">${event.milch || 0} L</span>
                                <button class="counter-btn plus" onclick="increment(${event.id}, 'milch')">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="counter-row green">
                        <div class="counter-content">
                            <span class="counter-label">Hafermilch</span>
                            <div class="counter-controls">
                                <button class="counter-btn minus" onclick="decrement(${event.id}, 'hafermilch')">−</button>
                                <span class="counter-value">${event.hafermilch || 0} L</span>
                                <button class="counter-btn plus" onclick="increment(${event.id}, 'hafermilch')">+</button>
                            </div>
                        </div>
                    </div>

                    <h3 style="margin-top: 24px;">🍵 Ausgeschenkte Getränke (Stück)</h3>
                    <div class="counter-row">
                        <div class="counter-content">
                            <span class="counter-label">Matcha</span>
                            <div class="counter-controls">
                                <button class="counter-btn minus" onclick="decrement(${event.id}, 'ausgabeMatcha')">−</button>
                                <span class="counter-value">${event.ausgabeMatcha || 0}</span>
                                <button class="counter-btn plus" onclick="increment(${event.id}, 'ausgabeMatcha')">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="counter-row">
                        <div class="counter-content">
                            <span class="counter-label">Schokolade</span>
                            <div class="counter-controls">
                                <button class="counter-btn minus" onclick="decrement(${event.id}, 'ausgabeSchokolade')">−</button>
                                <span class="counter-value">${event.ausgabeSchokolade || 0}</span>
                                <button class="counter-btn plus" onclick="increment(${event.id}, 'ausgabeSchokolade')">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="counter-row">
                        <div class="counter-content">
                            <span class="counter-label">Tee</span>
                            <div class="counter-controls">
                                <button class="counter-btn minus" onclick="decrement(${event.id}, 'ausgabeTee')">−</button>
                                <span class="counter-value">${event.ausgabeTee || 0}</span>
                                <button class="counter-btn plus" onclick="increment(${event.id}, 'ausgabeTee')">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="total-box">
                        <div class="total-content">
                            <span class="total-label">Total Getränke</span>
                            <span class="total-value">${totalGetraenke} Stück</span>
                        </div>
                    </div>

                    ${event.isPartOfMultiDay ? `
                        <button class="btn btn-danger" style="margin-bottom: 12px;" onclick="if(confirm('Nur diesen Tag (${event.name}) löschen?')) { deleteEvent(${event.id}); }">
                            Nur diesen Tag löschen
                        </button>
                        <button class="btn btn-danger" onclick="deleteEventGroup('${getEventBaseName(event.name)}')" style="background: #991b1b;">
                            Gesamtes Event mit allen ${event.multiDayTotal} Tagen löschen
                        </button>
                    ` : `
                        <button class="btn btn-danger" onclick="deleteEvent(${event.id})">Event Löschen</button>
                    `}
                </div>
            `;
        }

        function render() {
            try {
                const app = document.getElementById('app');
                if (!app) {
                    console.error('App-Element nicht gefunden!');
                    document.body.innerHTML = '<div style="padding: 20px; background: white; margin: 20px; border-radius: 10px;"><h2>Fehler</h2><p>App konnte nicht geladen werden. Bitte Seite neu laden.</p></div>';
                    return;
                }
                if (currentView === 'overview') {
                    app.innerHTML = renderOverview();
                } else if (currentView === 'new') {
                    app.innerHTML = renderNewEvent();
                } else if (currentView === 'group') {
                    app.innerHTML = renderEventGroup();
                } else if (currentView === 'detail') {
                    app.innerHTML = renderEventDetail();
                }
            } catch(e) {
                console.error('Render-Fehler:', e);
                const app = document.getElementById('app');
                if (app) {
                    app.innerHTML = '<div class="card"><h2>Fehler beim Laden</h2><p>Bitte Seite neu laden. Fehler: ' + e.message + '</p></div>';
                }
            }
        }
    </script>
</body>
</html>
