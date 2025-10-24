<?php
/**
 * Verbrauch Endpoint
 * Aktualisierung von Verbrauchsdaten (Milch, Getränke)
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

switch ($method) {
    case 'GET':
        // Hole Verbrauchsdaten für ein Event
        $eventId = $_GET['event_id'] ?? null;

        if (!$eventId) {
            Security::errorResponse('Event-ID erforderlich', 400);
        }

        // Prüfe ob Event dem User gehört
        $event = $db->selectOne(
            "SELECT id FROM events WHERE id = ? AND user_id = ?",
            [$eventId, $currentUser['user_id']]
        );

        if (!$event) {
            Security::errorResponse('Event nicht gefunden', 404);
        }

        $verbrauch = $db->selectOne(
            "SELECT * FROM verbrauch WHERE event_id = ?",
            [$eventId]
        );

        Security::successResponse($verbrauch ?: []);
        break;

    case 'PUT':
        // Verbrauch aktualisieren
        $eventId = $input['event_id'] ?? null;

        if (!$eventId) {
            Security::errorResponse('Event-ID erforderlich', 400);
        }

        // Prüfe ob Event dem User gehört
        $event = $db->selectOne(
            "SELECT id FROM events WHERE id = ? AND user_id = ?",
            [$eventId, $currentUser['user_id']]
        );

        if (!$event) {
            Security::errorResponse('Event nicht gefunden', 404);
        }

        // Prüfe ob Verbrauchseintrag existiert
        $existing = $db->selectOne(
            "SELECT id FROM verbrauch WHERE event_id = ?",
            [$eventId]
        );

        $milchLiter = isset($input['milch_liter']) ? intval($input['milch_liter']) : 0;
        $hafermilchLiter = isset($input['hafermilch_liter']) ? intval($input['hafermilch_liter']) : 0;
        $ausgabeMatcha = isset($input['ausgabe_matcha']) ? intval($input['ausgabe_matcha']) : 0;
        $ausgabeSchokolade = isset($input['ausgabe_schokolade']) ? intval($input['ausgabe_schokolade']) : 0;
        $ausgabeTee = isset($input['ausgabe_tee']) ? intval($input['ausgabe_tee']) : 0;

        if ($existing) {
            // Update
            $db->update(
                "UPDATE verbrauch
                 SET milch_liter = ?, hafermilch_liter = ?, ausgabe_matcha = ?,
                     ausgabe_schokolade = ?, ausgabe_tee = ?
                 WHERE event_id = ?",
                [$milchLiter, $hafermilchLiter, $ausgabeMatcha, $ausgabeSchokolade, $ausgabeTee, $eventId]
            );
        } else {
            // Insert
            $db->insert(
                "INSERT INTO verbrauch (event_id, milch_liter, hafermilch_liter, ausgabe_matcha, ausgabe_schokolade, ausgabe_tee)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$eventId, $milchLiter, $hafermilchLiter, $ausgabeMatcha, $ausgabeSchokolade, $ausgabeTee]
            );
        }

        $logger->info('Verbrauch aktualisiert', [
            'event_id' => $eventId,
            'user_id' => $currentUser['user_id']
        ]);

        Security::successResponse(null, 'Verbrauch erfolgreich aktualisiert');
        break;

    default:
        Security::errorResponse('Methode nicht erlaubt', 405);
}
