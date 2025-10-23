<?php
/**
 * User Endpoint
 * Benutzerprofil und -einstellungen
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

switch ($method) {
    case 'GET':
        // Hole Benutzerprofil
        $user = $db->selectOne(
            "SELECT id, username, email, role, last_login, created_at FROM users WHERE id = ?",
            [$currentUser['user_id']]
        );

        if (!$user) {
            Security::errorResponse('Benutzer nicht gefunden', 404);
        }

        Security::successResponse($user);
        break;

    case 'PUT':
        // Aktualisiere Benutzerprofil
        $email = Security::sanitizeInput($input['email'] ?? '', 'email');
        $currentPassword = $input['current_password'] ?? null;
        $newPassword = $input['new_password'] ?? null;

        // Email aktualisieren
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $db->update(
                "UPDATE users SET email = ? WHERE id = ?",
                [$email, $currentUser['user_id']]
            );

            $logger->info('Email aktualisiert', ['user_id' => $currentUser['user_id']]);
        }

        // Passwort ändern
        if ($currentPassword && $newPassword) {
            $user = $db->selectOne(
                "SELECT password_hash FROM users WHERE id = ?",
                [$currentUser['user_id']]
            );

            if (!Security::verifyPassword($currentPassword, $user['password_hash'])) {
                Security::errorResponse('Aktuelles Passwort ist falsch', 401);
            }

            // Validiere neues Passwort
            $validation = Security::validatePasswordStrength($newPassword);
            if (!$validation['valid']) {
                Security::errorResponse('Passwort erfüllt nicht die Anforderungen', 400, $validation['errors']);
            }

            $newHash = Security::hashPassword($newPassword);
            $db->update(
                "UPDATE users SET password_hash = ? WHERE id = ?",
                [$newHash, $currentUser['user_id']]
            );

            $logger->info('Passwort geändert', ['user_id' => $currentUser['user_id']]);
        }

        Security::successResponse(null, 'Profil erfolgreich aktualisiert');
        break;

    default:
        Security::errorResponse('Methode nicht erlaubt', 405);
}
