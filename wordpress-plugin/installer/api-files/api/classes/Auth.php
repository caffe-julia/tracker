<?php
/**
 * Authentifizierungs-Klasse
 */

class Auth
{
    private $db;
    private $logger;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->logger = new Logger();
    }

    /**
     * Benutzer-Login
     */
    public function login($username, $password)
    {
        $ip = Security::getClientIP();

        // Prüfe ob Account gesperrt ist
        $user = $this->db->selectOne(
            "SELECT * FROM users WHERE username = ? OR email = ?",
            [$username, $username]
        );

        if (!$user) {
            $this->logFailedAttempt($ip, $username, 'Benutzer nicht gefunden');
            return [
                'success' => false,
                'error' => 'Ungültige Anmeldedaten'
            ];
        }

        // Prüfe ob Account aktiv ist
        if (!$user['is_active']) {
            $this->logger->warning('Login-Versuch für deaktivierten Account', [
                'user_id' => $user['id'],
                'username' => $username,
                'ip' => $ip
            ]);
            return [
                'success' => false,
                'error' => 'Dieser Account wurde deaktiviert'
            ];
        }

        // Prüfe ob Account gesperrt ist
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $remainingTime = ceil((strtotime($user['locked_until']) - time()) / 60);
            $this->logger->warning('Login-Versuch für gesperrten Account', [
                'user_id' => $user['id'],
                'username' => $username,
                'ip' => $ip,
                'remaining_minutes' => $remainingTime
            ]);
            return [
                'success' => false,
                'error' => "Account ist noch für $remainingTime Minuten gesperrt"
            ];
        }

        // Prüfe Passwort
        if (!Security::verifyPassword($password, $user['password_hash'])) {
            $this->handleFailedLogin($user['id'], $ip, $username);
            return [
                'success' => false,
                'error' => 'Ungültige Anmeldedaten'
            ];
        }

        // Login erfolgreich - Reset failed attempts
        $this->db->update(
            "UPDATE users SET failed_login_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = ?",
            [$user['id']]
        );

        // Erstelle Session
        $sessionToken = $this->createSession($user['id']);

        $this->logger->info('Erfolgreicher Login', [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'ip' => $ip
        ]);

        // Audit-Log
        $this->logAudit($user['id'], 'LOGIN', 'users', $user['id']);

        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ],
            'session_token' => $sessionToken
        ];
    }

    /**
     * Fehlgeschlagenen Login behandeln
     */
    private function handleFailedLogin($userId, $ip, $username)
    {
        // Erhöhe Failed Attempts
        $this->db->update(
            "UPDATE users SET failed_login_attempts = failed_login_attempts + 1 WHERE id = ?",
            [$userId]
        );

        // Hole aktualisierte Daten
        $user = $this->db->selectOne("SELECT failed_login_attempts FROM users WHERE id = ?", [$userId]);

        // Sperre Account bei zu vielen Versuchen
        if ($user['failed_login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $lockoutTime = date('Y-m-d H:i:s', time() + LOCKOUT_DURATION);
            $this->db->update(
                "UPDATE users SET locked_until = ? WHERE id = ?",
                [$lockoutTime, $userId]
            );

            $this->logger->warning('Account gesperrt nach zu vielen Login-Versuchen', [
                'user_id' => $userId,
                'username' => $username,
                'ip' => $ip,
                'attempts' => $user['failed_login_attempts']
            ]);
        }

        $this->logFailedAttempt($ip, $username, 'Falsches Passwort');
    }

    /**
     * Fehlversuch protokollieren
     */
    private function logFailedAttempt($ip, $username, $reason)
    {
        $this->logger->warning('Fehlgeschlagener Login-Versuch', [
            'username' => $username,
            'ip' => $ip,
            'reason' => $reason
        ]);

        $this->db->insert(
            "INSERT INTO audit_log (action, table_name, ip_address, user_agent, new_data) VALUES (?, ?, ?, ?, ?)",
            [
                'FAILED_LOGIN',
                'users',
                $ip,
                Security::getUserAgent(),
                json_encode(['username' => $username, 'reason' => $reason])
            ]
        );
    }

    /**
     * Session erstellen
     */
    private function createSession($userId)
    {
        $token = Security::generateToken(64);
        $expiresAt = date('Y-m-d H:i:s', time() + SESSION_LIFETIME);

        $this->db->insert(
            "INSERT INTO sessions (user_id, session_token, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, ?)",
            [
                $userId,
                $token,
                Security::getClientIP(),
                Security::getUserAgent(),
                $expiresAt
            ]
        );

        return $token;
    }

    /**
     * Session validieren
     */
    public function validateSession($token)
    {
        if (!$token) {
            return null;
        }

        $session = $this->db->selectOne(
            "SELECT s.*, u.* FROM sessions s
             JOIN users u ON s.user_id = u.id
             WHERE s.session_token = ? AND s.expires_at > NOW() AND u.is_active = 1",
            [$token]
        );

        if (!$session) {
            return null;
        }

        // Prüfe IP-Adresse (optional, für erhöhte Sicherheit)
        $currentIP = Security::getClientIP();
        if ($session['ip_address'] !== $currentIP) {
            $this->logger->warning('Session IP-Mismatch', [
                'user_id' => $session['user_id'],
                'session_ip' => $session['ip_address'],
                'current_ip' => $currentIP
            ]);
            // Optional: Session invalidieren
            // return null;
        }

        return [
            'user_id' => $session['user_id'],
            'username' => $session['username'],
            'email' => $session['email'],
            'role' => $session['role']
        ];
    }

    /**
     * Logout (Session löschen)
     */
    public function logout($token)
    {
        $session = $this->db->selectOne(
            "SELECT user_id FROM sessions WHERE session_token = ?",
            [$token]
        );

        if ($session) {
            $this->db->delete(
                "DELETE FROM sessions WHERE session_token = ?",
                [$token]
            );

            $this->logger->info('Logout', [
                'user_id' => $session['user_id'],
                'ip' => Security::getClientIP()
            ]);

            $this->logAudit($session['user_id'], 'LOGOUT', 'users', $session['user_id']);

            return true;
        }

        return false;
    }

    /**
     * Alle Sessions eines Users löschen
     */
    public function logoutAllSessions($userId)
    {
        $this->db->delete(
            "DELETE FROM sessions WHERE user_id = ?",
            [$userId]
        );

        $this->logger->info('Alle Sessions gelöscht', ['user_id' => $userId]);
        $this->logAudit($userId, 'LOGOUT_ALL', 'users', $userId);
    }

    /**
     * Berechtigungs-Prüfung
     */
    public function checkPermission($user, $requiredRole)
    {
        $roleHierarchy = [
            'viewer' => 1,
            'staff' => 2,
            'admin' => 3
        ];

        $userLevel = $roleHierarchy[$user['role']] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 999;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Audit-Log Eintrag erstellen
     */
    private function logAudit($userId, $action, $tableName, $recordId, $oldData = null, $newData = null)
    {
        if (!AUDIT_LOG_ENABLED) {
            return;
        }

        $this->db->insert(
            "INSERT INTO audit_log (user_id, action, table_name, record_id, old_data, new_data, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $action,
                $tableName,
                $recordId,
                $oldData ? json_encode($oldData) : null,
                $newData ? json_encode($newData) : null,
                Security::getClientIP(),
                Security::getUserAgent()
            ]
        );
    }

    /**
     * Passwort-Reset Token erstellen
     */
    public function createPasswordResetToken($email)
    {
        $user = $this->db->selectOne("SELECT id FROM users WHERE email = ? AND is_active = 1", [$email]);

        if (!$user) {
            // Aus Sicherheitsgründen keine Info ob Email existiert
            return [
                'success' => true,
                'message' => 'Falls die E-Mail-Adresse existiert, wurde ein Reset-Link versendet.'
            ];
        }

        $token = Security::generateToken(32);
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 Stunde gültig

        // Speichere Token (in settings oder neue Tabelle)
        $this->db->insert(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)",
            ['password_reset_' . $token, json_encode(['user_id' => $user['id'], 'expires' => $expiresAt])]
        );

        $this->logger->info('Passwort-Reset angefordert', [
            'user_id' => $user['id'],
            'email' => $email
        ]);

        return [
            'success' => true,
            'token' => $token,
            'message' => 'Falls die E-Mail-Adresse existiert, wurde ein Reset-Link versendet.'
        ];
    }

    /**
     * Abgelaufene Sessions bereinigen
     */
    public function cleanupExpiredSessions()
    {
        $deleted = $this->db->delete("DELETE FROM sessions WHERE expires_at < NOW()");
        $this->logger->info("Abgelaufene Sessions bereinigt", ['count' => $deleted]);
        return $deleted;
    }
}
