<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1>☕ Caffe Julia Tracker - Dashboard</h1>

    <div class="cjtp-dashboard">
        <div class="cjtp-stats-grid">
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">📊</div>
                <div class="cjtp-stat-label">Total Events</div>
                <div class="cjtp-stat-value" id="totalEvents">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">☕</div>
                <div class="cjtp-stat-label">Total Kaffees</div>
                <div class="cjtp-stat-value" id="totalKaffees">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">🥛</div>
                <div class="cjtp-stat-label">Milch (Liter)</div>
                <div class="cjtp-stat-value" id="totalMilch">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">🍵</div>
                <div class="cjtp-stat-label">Getränke</div>
                <div class="cjtp-stat-value" id="totalGetraenke">-</div>
            </div>
            <div class="cjtp-stat-card">
                <div class="cjtp-stat-icon">⏱️</div>
                <div class="cjtp-stat-label">Arbeitsstunden</div>
                <div class="cjtp-stat-value" id="totalStunden">-</div>
            </div>
        </div>

        <div class="cjtp-actions">
            <h2>📥 Export</h2>
            <p>Laden Sie alle Events als Excel/CSV-Datei herunter.</p>
            <a href="<?php echo admin_url('admin-ajax.php?action=cjtp_export_csv&nonce=' . wp_create_nonce('wp_rest')); ?>"
               class="button button-primary button-hero">
                📥 Excel/CSV herunterladen
            </a>
        </div>

        <div class="cjtp-events-manager">
            <h2>🗂️ Events verwalten</h2>
            <p>Alle erfassten Events. Hier können Sie Events löschen.</p>
            <div id="eventsTable">
                <p>Lade Events...</p>
            </div>
        </div>

        <div class="cjtp-info">
            <h2>📱 Tracker verwenden</h2>
            <p>Fügen Sie den Tracker auf einer WordPress-Seite ein mit dem Shortcode:</p>
            <pre style="background: #f0f0f1; padding: 15px; border-radius: 4px; font-size: 14px;">[caffe_tracker]</pre>

            <p><strong>Empfehlung:</strong> Erstellen Sie eine neue Seite "Tracker" und fügen Sie den Shortcode ein.</p>
            <p>Der Tracker ist <strong>iPhone-optimiert</strong> und kann direkt vom Handy verwendet werden!</p>
        </div>
    </div>
</div>

<style>
.cjtp-dashboard {
    max-width: 1200px;
}
.cjtp-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}
.cjtp-stat-card {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.cjtp-stat-icon {
    font-size: 48px;
    margin-bottom: 10px;
}
.cjtp-stat-label {
    font-size: 13px;
    color: #646970;
    margin-bottom: 8px;
}
.cjtp-stat-value {
    font-size: 32px;
    font-weight: bold;
    color: #1d2327;
}
.cjtp-actions, .cjtp-info, .cjtp-events-manager {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 8px;
    padding: 24px;
    margin: 20px 0;
}
.cjtp-actions h2, .cjtp-info h2, .cjtp-events-manager h2 {
    margin-top: 0;
}
.cjtp-events-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.cjtp-events-table th {
    background: #f6f7f7;
    padding: 12px;
    text-align: left;
    border-bottom: 2px solid #c3c4c7;
    font-weight: 600;
}
.cjtp-events-table td {
    padding: 12px;
    border-bottom: 1px solid #dcdcde;
}
.cjtp-events-table tr:hover {
    background: #f9f9f9;
}
.cjtp-delete-btn {
    color: #b32d2e;
    cursor: pointer;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 3px;
}
.cjtp-delete-btn:hover {
    background: #b32d2e;
    color: white;
}
</style>

<script>
jQuery(document).ready(function($) {
    const API_BASE = '<?php echo rest_url('cjtp/v1/'); ?>';
    const API_NONCE = '<?php echo wp_create_nonce('wp_rest'); ?>';

    // Lade Statistiken
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'cjtp_get_stats',
            nonce: API_NONCE
        },
        success: function(response) {
            if (response.success) {
                $('#totalEvents').text(response.data.totalEvents);
                $('#totalKaffees').text(response.data.totalKaffees.toLocaleString());
                $('#totalMilch').text(response.data.totalMilch.toLocaleString());
                $('#totalGetraenke').text(response.data.totalGetraenke.toLocaleString());
                $('#totalStunden').text(response.data.totalArbeitsstunden.toFixed(1));
            }
        }
    });

    // Lade Events
    loadEvents();

    function loadEvents() {
        $.ajax({
            url: API_BASE + 'events',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', API_NONCE);
            },
            success: function(events) {
                renderEventsTable(events);
            },
            error: function() {
                $('#eventsTable').html('<p style="color: #b32d2e;">Fehler beim Laden der Events.</p>');
            }
        });
    }

    function renderEventsTable(events) {
        if (events.length === 0) {
            $('#eventsTable').html('<p>Keine Events vorhanden.</p>');
            return;
        }

        // Sortiere Events nach Datum (neueste zuerst)
        events.sort((a, b) => (b.date || '').localeCompare(a.date || ''));

        let html = '<table class="cjtp-events-table">';
        html += '<thead><tr>';
        html += '<th>Datum</th>';
        html += '<th>Event Name</th>';
        html += '<th>Arbeitsstunden</th>';
        html += '<th>wpId</th>';
        html += '<th>Aktion</th>';
        html += '</tr></thead><tbody>';

        events.forEach(function(event) {
            html += '<tr>';
            html += '<td>' + (event.date || '-') + '</td>';
            html += '<td>' + (event.name || '-') + '</td>';
            html += '<td>' + (event.workHours || 0) + ' h</td>';
            html += '<td>' + (event.wpId ? event.wpId : '<span style="color: #b32d2e;">Keine wpId</span>') + '</td>';
            html += '<td><a href="#" class="cjtp-delete-btn" data-id="' + event.wpId + '" data-name="' + (event.name || '') + '">🗑️ Löschen</a></td>';
            html += '</tr>';
        });

        html += '</tbody></table>';
        $('#eventsTable').html(html);
    }

    // Event-Löschung
    $(document).on('click', '.cjtp-delete-btn', function(e) {
        e.preventDefault();
        const wpId = $(this).data('id');
        const name = $(this).data('name');

        if (!wpId) {
            alert('❌ Event hat keine wpId und kann nicht gelöscht werden.\n\nBitte löschen Sie es direkt im Tracker unter /eventracker');
            return;
        }

        if (!confirm('Event "' + name + '" wirklich löschen?')) {
            return;
        }

        // Verwende direkten AJAX-Handler
        const deleteBtn = $(this);
        deleteBtn.text('Löscht...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cjtp_delete_event',
                post_id: wpId,
                nonce: API_NONCE
            },
            success: function(response) {
                if (response.success) {
                    alert('✅ Event erfolgreich gelöscht!');
                    location.reload();
                } else {
                    alert('❌ Fehler beim Löschen: ' + (response.data?.message || 'Unbekannter Fehler'));
                    deleteBtn.text('🗑️ Löschen');
                }
            },
            error: function(xhr) {
                console.error('Delete error:', xhr);
                alert('❌ Fehler beim Löschen: ' + xhr.statusText);
                deleteBtn.text('🗑️ Löschen');
            }
        });
    });
});
</script>
<?php
