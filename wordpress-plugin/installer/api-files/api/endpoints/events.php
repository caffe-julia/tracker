<?php
/**
 * Events Endpoint
 * CRUD-Operationen für Events
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

switch ($method) {
    case 'GET':
        // Liste alle Events oder hole einzelnes Event
        if ($id) {
            // Einzelnes Event mit allen Details
            $event = $db->selectOne(
                "SELECT * FROM events WHERE id = ? AND user_id = ?",
                [$id, $currentUser['user_id']]
            );

            if (!$event) {
                Security::errorResponse('Event nicht gefunden', 404);
            }

            // Hole Mühlen-Daten
            $muehlen = $db->select(
                "SELECT * FROM muehlen WHERE event_id = ? ORDER BY muehle_nummer",
                [$id]
            );

            // Hole Verbrauchsdaten
            $verbrauch = $db->selectOne(
                "SELECT * FROM verbrauch WHERE event_id = ?",
                [$id]
            );

            $event['muehlen'] = $muehlen;
            $event['verbrauch'] = $verbrauch;

            Security::successResponse($event);

        } else {
            // Liste aller Events
            $startDate = $_GET['start_date'] ?? null;
            $endDate = $_GET['end_date'] ?? null;
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

            $sql = "SELECT e.*,
                    (SELECT SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start))
                     FROM muehlen m WHERE m.event_id = e.id) as total_kaffees,
                    (SELECT v.milch_liter + v.hafermilch_liter
                     FROM verbrauch v WHERE v.event_id = e.id) as total_milch
                    FROM events e
                    WHERE e.user_id = ?";

            $params = [$currentUser['user_id']];

            if ($startDate) {
                $sql .= " AND e.event_date >= ?";
                $params[] = $startDate;
            }

            if ($endDate) {
                $sql .= " AND e.event_date <= ?";
                $params[] = $endDate;
            }

            $sql .= " ORDER BY e.event_date DESC, e.id DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $events = $db->select($sql, $params);

            Security::successResponse([
                'events' => $events,
                'count' => count($events),
                'limit' => $limit,
                'offset' => $offset
            ]);
        }
        break;

    case 'POST':
        // Neues Event erstellen
        $name = Security::sanitizeInput($input['name'] ?? '');
        $eventDate = Security::sanitizeInput($input['event_date'] ?? '');
        $isAllDay = isset($input['is_all_day']) ? (bool)$input['is_all_day'] : false;
        $isMultiDay = isset($input['is_multi_day']) ? (bool)$input['is_multi_day'] : false;
        $anzahlMuehlen = isset($input['anzahl_muehlen']) ? intval($input['anzahl_muehlen']) : 3;

        if (empty($name) || empty($eventDate)) {
            Security::errorResponse('Name und Datum erforderlich', 400);
        }

        // Validiere Datum
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $eventDate)) {
            Security::errorResponse('Ungültiges Datumsformat', 400);
        }

        // Beginne Transaktion
        $db->beginTransaction();

        try {
            // Event erstellen
            $eventId = $db->insert(
                "INSERT INTO events (user_id, name, event_date, is_all_day, is_multi_day, anzahl_muehlen)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$currentUser['user_id'], $name, $eventDate, $isAllDay, $isMultiDay, $anzahlMuehlen]
            );

            // Mühlen initialisieren
            for ($i = 1; $i <= $anzahlMuehlen; $i++) {
                $db->insert(
                    "INSERT INTO muehlen (event_id, muehle_nummer, muehle_name)
                     VALUES (?, ?, ?)",
                    [$eventId, $i, "Mühle $i"]
                );
            }

            // Verbrauch initialisieren
            $db->insert(
                "INSERT INTO verbrauch (event_id) VALUES (?)",
                [$eventId]
            );

            $db->commit();

            $logger->info('Event erstellt', [
                'event_id' => $eventId,
                'user_id' => $currentUser['user_id'],
                'name' => $name
            ]);

            Security::successResponse([
                'event_id' => $eventId
            ], 'Event erfolgreich erstellt');

        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
        break;

    case 'PUT':
        // Event aktualisieren
        if (!$id) {
            Security::errorResponse('Event-ID erforderlich', 400);
        }

        // Prüfe ob Event existiert und dem User gehört
        $existingEvent = $db->selectOne(
            "SELECT * FROM events WHERE id = ? AND user_id = ?",
            [$id, $currentUser['user_id']]
        );

        if (!$existingEvent) {
            Security::errorResponse('Event nicht gefunden', 404);
        }

        // Aktualisierbare Felder
        $allowedFields = [
            'name', 'event_date', 'is_all_day', 'work_start_time', 'work_end_time',
            'work_break_minutes', 'work_hours', 'mitteilung'
        ];

        $updates = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = ?";
                $params[] = Security::sanitizeInput($input[$field]);
            }
        }

        if (empty($updates)) {
            Security::errorResponse('Keine Aktualisierungen angegeben', 400);
        }

        $params[] = $id;
        $params[] = $currentUser['user_id'];

        $db->update(
            "UPDATE events SET " . implode(', ', $updates) . " WHERE id = ? AND user_id = ?",
            $params
        );

        $logger->info('Event aktualisiert', [
            'event_id' => $id,
            'user_id' => $currentUser['user_id'],
            'fields' => array_keys(array_filter($input, fn($key) => in_array($key, $allowedFields), ARRAY_FILTER_USE_KEY))
        ]);

        Security::successResponse(null, 'Event erfolgreich aktualisiert');
        break;

    case 'DELETE':
        // Event löschen
        if (!$id) {
            Security::errorResponse('Event-ID erforderlich', 400);
        }

        $deleted = $db->delete(
            "DELETE FROM events WHERE id = ? AND user_id = ?",
            [$id, $currentUser['user_id']]
        );

        if ($deleted === 0) {
            Security::errorResponse('Event nicht gefunden', 404);
        }

        $logger->info('Event gelöscht', [
            'event_id' => $id,
            'user_id' => $currentUser['user_id']
        ]);

        Security::successResponse(null, 'Event erfolgreich gelöscht');
        break;

    default:
        Security::errorResponse('Methode nicht erlaubt', 405);
}
