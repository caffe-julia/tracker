<?php
/**
 * Events-Liste Ansicht
 */

if (!defined('ABSPATH')) {
    exit;
}

// Erfolgsmeldung
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Events holen
$events = get_posts(array(
    'post_type' => 'cje_event',
    'posts_per_page' => -1,
    'orderby' => 'meta_value',
    'meta_key' => '_cje_event_date',
    'order' => 'DESC',
));
?>

<div class="wrap cje-events-page">
    <h1 class="wp-heading-inline">
        ☕ Caffe Julia Events
    </h1>

    <a href="<?php echo admin_url('admin.php?page=caffe-julia-add-event'); ?>" class="page-title-action">
        Neues Event
    </a>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline;">
        <input type="hidden" name="action" value="cje_export_excel">
        <button type="submit" class="page-title-action cje-export-btn">
            📥 Excel Download (<?php echo count($events); ?> Events)
        </button>
    </form>

    <hr class="wp-header-end">

    <?php if ($message === 'added'): ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✓ Event erfolgreich hinzugefügt!</strong></p>
        </div>
    <?php endif; ?>

    <?php if (empty($events)): ?>
        <div class="cje-empty-state">
            <div class="cje-empty-icon">📅</div>
            <h2>Noch keine Events vorhanden</h2>
            <p>Fügen Sie Ihr erstes Event hinzu!</p>
            <a href="<?php echo admin_url('admin.php?page=caffe-julia-add-event'); ?>" class="button button-primary button-hero">
                Erstes Event erstellen
            </a>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped cje-events-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Event Name</th>
                    <th style="width: 12%;">Datum</th>
                    <th style="width: 8%;">Zeit</th>
                    <th style="width: 10%;">Arbeitszeit</th>
                    <th style="width: 15%;">Kaffeemühlen</th>
                    <th style="width: 10%;">Total Kaffees</th>
                    <th style="width: 10%;">Milch (L)</th>
                    <th style="width: 10%;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event):
                    $event_date = get_post_meta($event->ID, '_cje_event_date', true);
                    $is_all_day = get_post_meta($event->ID, '_cje_is_all_day', true);
                    $start_time = get_post_meta($event->ID, '_cje_start_time', true);
                    $end_time = get_post_meta($event->ID, '_cje_end_time', true);
                    $arbeitszeit = get_post_meta($event->ID, '_cje_arbeitszeit_stunden', true);
                    $muehlen = get_post_meta($event->ID, '_cje_muehlen', true);
                    $milch = get_post_meta($event->ID, '_cje_milch_liter', true);
                    $total_kaffees = get_post_meta($event->ID, '_cje_total_kaffees', true);
                ?>
                <tr>
                    <td>
                        <strong><?php echo esc_html($event->post_title); ?></strong>
                    </td>
                    <td>
                        <?php echo date('d.m.Y', strtotime($event_date)); ?>
                    </td>
                    <td>
                        <?php if ($is_all_day): ?>
                            <span class="cje-badge cje-badge-all-day">Ganztägig</span>
                        <?php else: ?>
                            <?php echo esc_html($start_time); ?> - <?php echo esc_html($end_time); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($arbeitszeit): ?>
                            <strong><?php echo number_format($arbeitszeit, 1); ?> h</strong>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($muehlen): ?>
                            <div class="cje-muehlen-mini">
                                <?php foreach ($muehlen as $nr => $data): ?>
                                    <span class="cje-muehle-badge">
                                        M<?php echo $nr; ?>: <?php echo $data['ende'] - $data['start']; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong class="cje-total-kaffees"><?php echo number_format($total_kaffees); ?></strong>
                    </td>
                    <td>
                        <?php echo number_format($milch, 1); ?> L
                    </td>
                    <td>
                        <a href="<?php echo get_delete_post_link($event->ID, '', true); ?>"
                           class="button button-small cje-delete-btn"
                           onclick="return confirm('Event wirklich löschen?');">
                            Löschen
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">
                        Gesamt:
                    </td>
                    <td>
                        <strong style="font-size: 1.2em;">
                            <?php
                            $total = array_sum(wp_list_pluck($events, 'ID'));
                            $sum_kaffees = 0;
                            foreach ($events as $e) {
                                $sum_kaffees += get_post_meta($e->ID, '_cje_total_kaffees', true);
                            }
                            echo number_format($sum_kaffees);
                            ?>
                        </strong>
                    </td>
                    <td>
                        <strong>
                            <?php
                            $sum_milch = 0;
                            foreach ($events as $e) {
                                $sum_milch += get_post_meta($e->ID, '_cje_milch_liter', true);
                            }
                            echo number_format($sum_milch, 1);
                            ?> L
                        </strong>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>
