<?php
/**
 * Export Endpoint
 * CSV-Export von Events
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

if ($method !== 'GET') {
    Security::errorResponse('Methode nicht erlaubt', 405);
}

$format = $_GET['format'] ?? 'csv';

if ($format !== 'csv') {
    Security::errorResponse('Nur CSV-Export wird unterstützt', 400);
}

// Hole alle Events des Users
$events = $db->select(
    "SELECT
        e.*,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2), 0) as total_doppel_kaffees,
        COALESCE(SUM(m.einzel_ende - m.einzel_start), 0) as total_einzel_kaffees,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)), 0) as total_kaffees,
        v.milch_liter,
        v.hafermilch_liter,
        v.ausgabe_matcha,
        v.ausgabe_schokolade,
        v.ausgabe_tee
     FROM events e
     LEFT JOIN muehlen m ON e.id = m.event_id
     LEFT JOIN verbrauch v ON e.id = v.event_id
     WHERE e.user_id = ?
     GROUP BY e.id
     ORDER BY e.event_date DESC",
    [$currentUser['user_id']]
);

// CSV generieren
$output = fopen('php://temp', 'r+');

// BOM für Excel UTF-8 Unterstützung
fputs($output, "\xEF\xBB\xBF");

// Header
fputcsv($output, [
    'Event',
    'Datum',
    'Ganztägig',
    'Arbeitsbeginn',
    'Arbeitsende',
    'Pause (Min)',
    'Arbeitsstunden',
    'Anzahl Mühlen',
    'Total Kaffees',
    'Doppelbezüge',
    'Einzelbezüge',
    'Milch (L)',
    'Hafermilch (L)',
    'Matcha',
    'Schokolade',
    'Tee',
    'Mitteilung'
], ';');

// Daten
foreach ($events as $event) {
    fputcsv($output, [
        $event['name'],
        $event['event_date'],
        $event['is_all_day'] ? 'Ja' : 'Nein',
        $event['work_start_time'] ?? '',
        $event['work_end_time'] ?? '',
        $event['work_break_minutes'] ?? 0,
        $event['work_hours'] ?? 0,
        $event['anzahl_muehlen'] ?? 0,
        $event['total_kaffees'] ?? 0,
        $event['total_doppel_kaffees'] ?? 0,
        $event['total_einzel_kaffees'] ?? 0,
        $event['milch_liter'] ?? 0,
        $event['hafermilch_liter'] ?? 0,
        $event['ausgabe_matcha'] ?? 0,
        $event['ausgabe_schokolade'] ?? 0,
        $event['ausgabe_tee'] ?? 0,
        $event['mitteilung'] ?? ''
    ], ';');
}

rewind($output);
$csv = stream_get_contents($output);
fclose($output);

// Download-Headers setzen
$filename = 'CaffeJulia_Export_' . date('Y-m-d') . '.csv';
Security::setDownloadHeaders($filename, 'text/csv; charset=utf-8');

echo $csv;
exit;
