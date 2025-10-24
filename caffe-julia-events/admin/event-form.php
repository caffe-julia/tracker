<?php
/**
 * Event-Formular
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap cje-add-event-page">
    <h1>☕ Neues Event hinzufügen</h1>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="cje-event-form">
        <?php wp_nonce_field('cje_add_event', 'cje_nonce'); ?>
        <input type="hidden" name="action" value="cje_add_event">

        <div class="cje-form-section">
            <h2>📅 Event-Informationen</h2>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="event_name">Event Name *</label>
                    </th>
                    <td>
                        <input type="text"
                               id="event_name"
                               name="event_name"
                               class="regular-text"
                               required
                               placeholder="z.B. Hochzeit Müller">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="event_date">Datum *</label>
                    </th>
                    <td>
                        <input type="date"
                               id="event_date"
                               name="event_date"
                               required>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
                            <input type="checkbox"
                                   id="is_all_day"
                                   name="is_all_day"
                                   onchange="toggleTimeFields(this)">
                            Ganztägiges Event
                        </label>
                    </th>
                    <td></td>
                </tr>
                <tr class="cje-time-row">
                    <th scope="row">
                        <label for="start_time">Start-Zeit</label>
                    </th>
                    <td>
                        <input type="time"
                               id="start_time"
                               name="start_time">
                    </td>
                </tr>
                <tr class="cje-time-row">
                    <th scope="row">
                        <label for="end_time">End-Zeit</label>
                    </th>
                    <td>
                        <input type="time"
                               id="end_time"
                               name="end_time">
                        <p class="description">Arbeitszeit wird automatisch berechnet</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cje-form-section">
            <h2>☕ Kaffeemühlen-Zählerstände</h2>

            <div class="cje-muehlen-grid">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="cje-muehle-box">
                    <div class="cje-muehle-header">
                        <label>
                            <input type="checkbox"
                                   name="muehle_<?php echo $i; ?>_active"
                                   id="muehle_<?php echo $i; ?>_active"
                                   onchange="toggleMuehle(<?php echo $i; ?>, this)">
                            <strong>Mühle <?php echo $i; ?></strong>
                        </label>
                    </div>
                    <div class="cje-muehle-fields" id="muehle_<?php echo $i; ?>_fields" style="display: none;">
                        <div class="cje-field-group">
                            <label>Start-Stand</label>
                            <input type="number"
                                   name="muehle_<?php echo $i; ?>_start"
                                   id="muehle_<?php echo $i; ?>_start"
                                   min="0"
                                   step="1"
                                   placeholder="0">
                        </div>
                        <div class="cje-field-group">
                            <label>End-Stand</label>
                            <input type="number"
                                   name="muehle_<?php echo $i; ?>_ende"
                                   id="muehle_<?php echo $i; ?>_ende"
                                   min="0"
                                   step="1"
                                   placeholder="0">
                        </div>
                        <div class="cje-muehle-diff" id="muehle_<?php echo $i; ?>_diff">
                            Differenz: <strong>0</strong>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="cje-form-section">
            <h2>🥛 Verbrauch</h2>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="milch_liter">Milch (Liter)</label>
                    </th>
                    <td>
                        <input type="number"
                               id="milch_liter"
                               name="milch_liter"
                               min="0"
                               step="0.1"
                               value="0"
                               style="width: 150px;">
                        <span class="description">z.B. 5.5</span>
                    </td>
                </tr>
            </table>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary button-hero">
                Event speichern
            </button>
            <a href="<?php echo admin_url('admin.php?page=caffe-julia-events'); ?>" class="button button-secondary button-hero">
                Abbrechen
            </a>
        </p>
    </form>
</div>

<script>
function toggleTimeFields(checkbox) {
    const timeRows = document.querySelectorAll('.cje-time-row');
    timeRows.forEach(row => {
        row.style.display = checkbox.checked ? 'none' : 'table-row';
    });
}

function toggleMuehle(nr, checkbox) {
    const fields = document.getElementById('muehle_' + nr + '_fields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}

// Auto-Berechnung Differenz
document.addEventListener('DOMContentLoaded', function() {
    for (let i = 1; i <= 4; i++) {
        const startInput = document.getElementById('muehle_' + i + '_start');
        const endeInput = document.getElementById('muehle_' + i + '_ende');
        const diffDiv = document.getElementById('muehle_' + i + '_diff');

        if (startInput && endeInput && diffDiv) {
            function updateDiff() {
                const start = parseInt(startInput.value) || 0;
                const ende = parseInt(endeInput.value) || 0;
                const diff = ende - start;
                diffDiv.innerHTML = 'Differenz: <strong>' + diff + '</strong> Kaffees';
            }

            startInput.addEventListener('input', updateDiff);
            endeInput.addEventListener('input', updateDiff);
        }
    }
});
</script>
