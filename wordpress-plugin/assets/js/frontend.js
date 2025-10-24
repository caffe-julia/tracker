/**
 * Frontend JavaScript für Caffe Julia Tracker Plugin
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialisiere alle Tracker-Widgets
        initTrackerWidgets();

        // Lade Statistiken für Stats-Widgets
        loadStatsWidgets();
    });

    /**
     * Tracker-Widgets initialisieren
     */
    function initTrackerWidgets() {
        $('.cjt-tracker-wrapper').each(function() {
            var $wrapper = $(this);
            var $iframe = $wrapper.find('.cjt-tracker-iframe');

            // Responsive Höhe anpassen
            adjustIframeHeight($iframe);

            // Bei Window-Resize anpassen
            $(window).on('resize', function() {
                adjustIframeHeight($iframe);
            });

            // Lade Quick-Stats wenn aktiviert
            var $statsContainer = $wrapper.find('.cjt-stats-quick');
            if ($statsContainer.length) {
                loadQuickStats($statsContainer);
            }
        });
    }

    /**
     * Iframe-Höhe anpassen
     */
    function adjustIframeHeight($iframe) {
        if ($(window).width() < 768) {
            var newHeight = Math.max(500, $(window).height() - 200);
            $iframe.css('height', newHeight + 'px');
        }
    }

    /**
     * Quick-Stats laden
     */
    function loadQuickStats($container) {
        if (typeof cjtData === 'undefined') {
            return;
        }

        $.ajax({
            url: cjtData.ajaxurl,
            type: 'POST',
            data: {
                action: 'cjt_get_statistics',
                nonce: cjtData.nonce
            },
            success: function(response) {
                if (response.success && response.data && response.data.totals) {
                    displayQuickStats($container, response.data.totals);
                } else {
                    $container.html('<p class="cjt-error">Fehler beim Laden der Statistiken</p>');
                }
            },
            error: function() {
                $container.html('<p class="cjt-error">Verbindungsfehler</p>');
            }
        });
    }

    /**
     * Quick-Stats anzeigen
     */
    function displayQuickStats($container, totals) {
        var html = '';
        html += '<div class="cjt-stat-box cjt-stat-events">';
        html += '<span class="cjt-stat-value">' + (totals.total_events || 0) + '</span>';
        html += '<span class="cjt-stat-label">Events</span>';
        html += '</div>';

        html += '<div class="cjt-stat-box cjt-stat-coffee">';
        html += '<span class="cjt-stat-value">' + (totals.total_kaffees || 0) + '</span>';
        html += '<span class="cjt-stat-label">Kaffees</span>';
        html += '</div>';

        html += '<div class="cjt-stat-box cjt-stat-hours">';
        html += '<span class="cjt-stat-value">' + parseFloat(totals.total_work_hours || 0).toFixed(1) + '</span>';
        html += '<span class="cjt-stat-label">Stunden</span>';
        html += '</div>';

        html += '<div class="cjt-stat-box cjt-stat-milk">';
        var totalMilk = (totals.total_milch || 0) + (totals.total_hafermilch || 0);
        html += '<span class="cjt-stat-value">' + totalMilk + ' L</span>';
        html += '<span class="cjt-stat-label">Milch</span>';
        html += '</div>';

        $container.html(html);
    }

    /**
     * Stats-Widgets laden
     */
    function loadStatsWidgets() {
        $('.cjt-stats-widget').each(function() {
            var $widget = $(this);
            // Template hat bereits eigenes JS für das Laden
            // Hier könnten zusätzliche Funktionen hinzugefügt werden
        });
    }

    /**
     * Utility: Zahlen formatieren
     */
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'");
    }

    /**
     * Utility: Datum formatieren
     */
    function formatDate(dateString) {
        var date = new Date(dateString);
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('de-DE', options);
    }

    // Global verfügbar machen
    window.CaffeJuliaTracker = {
        refreshStats: function() {
            $('.cjt-stats-quick').each(function() {
                loadQuickStats($(this));
            });
        },
        formatNumber: formatNumber,
        formatDate: formatDate
    };

})(jQuery);
