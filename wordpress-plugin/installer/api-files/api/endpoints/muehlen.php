<?php
/**
 * Mühlen Endpoint
 * Aktualisierung von Mühlen-Zählerständen
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

switch ($method) {
    case 'GET':
        // Hole Mühlen für ein Event
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

        $muehlen = $db->select(
            "SELECT * FROM muehlen WHERE event_id = ? ORDER BY muehle_nummer",
            [$eventId]
        );

        Security::successResponse($muehlen);
        break;

    case 'PUT':
        // Mühle aktualisieren
        if (!$id) {
            Security::errorResponse('Mühlen-ID erforderlich', 400);
        }

        // Prüfe ob Mühle existiert und dem User gehört
        $muehle = $db->selectOne(
            "SELECT m.*, e.user_id FROM muehlen m
             JOIN events e ON m.event_id = e.id
             WHERE m.id = ? AND e.user_id = ?",
            [$id, $currentUser['user_id']]
        );

        if (!$muehle) {
            Security::errorResponse('Mühle nicht gefunden', 404);
        }

        // Aktualisierbare Felder (nur Zählerstände)
        $doppelStart = isset($input['doppel_start']) ? intval($input['doppel_start']) : null;
        $doppelEnde = isset($input['doppel_ende']) ? intval($input['doppel_ende']) : null;
        $einzelStart = isset($input['einzel_start']) ? intval($input['einzel_start']) : null;
        $einzelEnde = isset($input['einzel_ende']) ? intval($input['einzel_ende']) : null;

        $updates = [];
        $params = [];

        if ($doppelStart !== null) {
            $updates[] = "doppel_start = ?";
            $params[] = $doppelStart;
        }
        if ($doppelEnde !== null) {
            $updates[] = "doppel_ende = ?";
            $params[] = $doppelEnde;
        }
        if ($einzelStart !== null) {
            $updates[] = "einzel_start = ?";
            $params[] = $einzelStart;
        }
        if ($einzelEnde !== null) {
            $updates[] = "einzel_ende = ?";
            $params[] = $einzelEnde;
        }

        if (empty($updates)) {
            Security::errorResponse('Keine Aktualisierungen angegeben', 400);
        }

        $params[] = $id;

        $db->update(
            "UPDATE muehlen SET " . implode(', ', $updates) . " WHERE id = ?",
            $params
        );

        $logger->info('Mühle aktualisiert', [
            'muehle_id' => $id,
            'event_id' => $muehle['event_id'],
            'user_id' => $currentUser['user_id']
        ]);

        Security::successResponse(null, 'Mühle erfolgreich aktualisiert');
        break;

    default:
        Security::errorResponse('Methode nicht erlaubt', 405);
}
