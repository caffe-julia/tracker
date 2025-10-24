<?php
/**
 * Statistik Endpoint
 * Abruf von aggregierten Statistiken
 */

if (!defined('APP_INIT')) {
    die('Direkter Zugriff nicht erlaubt');
}

$db = Database::getInstance();

if ($method !== 'GET') {
    Security::errorResponse('Methode nicht erlaubt', 405);
}

$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Standard: Letzten 30 Tage
if (!$startDate) {
    $startDate = date('Y-m-d', strtotime('-30 days'));
}
if (!$endDate) {
    $endDate = date('Y-m-d');
}

// Gesamtstatistiken
$stats = $db->selectOne(
    "SELECT
        COUNT(DISTINCT e.id) as total_events,
        SUM(e.work_hours) as total_work_hours,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2), 0) as total_doppel_kaffees,
        COALESCE(SUM(m.einzel_ende - m.einzel_start), 0) as total_einzel_kaffees,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)), 0) as total_kaffees,
        COALESCE(SUM(v.milch_liter), 0) as total_milch,
        COALESCE(SUM(v.hafermilch_liter), 0) as total_hafermilch,
        COALESCE(SUM(v.ausgabe_matcha), 0) as total_matcha,
        COALESCE(SUM(v.ausgabe_schokolade), 0) as total_schokolade,
        COALESCE(SUM(v.ausgabe_tee), 0) as total_tee
     FROM events e
     LEFT JOIN muehlen m ON e.id = m.event_id
     LEFT JOIN verbrauch v ON e.id = v.event_id
     WHERE e.user_id = ?
     AND e.event_date BETWEEN ? AND ?",
    [$currentUser['user_id'], $startDate, $endDate]
);

// Top Events (nach Kaffee-Produktion)
$topEvents = $db->select(
    "SELECT
        e.id,
        e.name,
        e.event_date,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)), 0) as total_kaffees
     FROM events e
     LEFT JOIN muehlen m ON e.id = m.event_id
     WHERE e.user_id = ?
     AND e.event_date BETWEEN ? AND ?
     GROUP BY e.id
     ORDER BY total_kaffees DESC
     LIMIT 10",
    [$currentUser['user_id'], $startDate, $endDate]
);

// Statistiken nach Monat
$monthlyStats = $db->select(
    "SELECT
        DATE_FORMAT(e.event_date, '%Y-%m') as month,
        COUNT(DISTINCT e.id) as events_count,
        SUM(e.work_hours) as work_hours,
        COALESCE(SUM((m.doppel_ende - m.doppel_start) * 2 + (m.einzel_ende - m.einzel_start)), 0) as total_kaffees,
        COALESCE(SUM(v.milch_liter + v.hafermilch_liter), 0) as total_milch
     FROM events e
     LEFT JOIN muehlen m ON e.id = m.event_id
     LEFT JOIN verbrauch v ON e.id = v.event_id
     WHERE e.user_id = ?
     AND e.event_date BETWEEN ? AND ?
     GROUP BY month
     ORDER BY month DESC",
    [$currentUser['user_id'], $startDate, $endDate]
);

// Durchschnittswerte
$averages = [
    'avg_kaffees_per_event' => $stats['total_events'] > 0 ? round($stats['total_kaffees'] / $stats['total_events'], 2) : 0,
    'avg_work_hours_per_event' => $stats['total_events'] > 0 ? round($stats['total_work_hours'] / $stats['total_events'], 2) : 0,
    'avg_milch_per_event' => $stats['total_events'] > 0 ? round(($stats['total_milch'] + $stats['total_hafermilch']) / $stats['total_events'], 2) : 0
];

Security::successResponse([
    'period' => [
        'start_date' => $startDate,
        'end_date' => $endDate
    ],
    'totals' => $stats,
    'averages' => $averages,
    'top_events' => $topEvents,
    'monthly' => $monthlyStats
]);
