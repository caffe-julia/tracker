/**
 * Admin JavaScript für Caffe Julia Tracker Plugin
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Tab-System (falls benötigt)
        initTabs();

        // Tooltips
        initTooltips();

        // Color Picker
        if ($.fn.wpColorPicker) {
            $('input[type="color"]').wpColorPicker();
        }

        // Form-Validierung
        validateSettingsForm();
    });

    /**
     * Tab-System initialisieren
     */
    function initTabs() {
        $('.cjt-tabs').each(function() {
            var $tabs = $(this);
            var $tabButtons = $tabs.find('.cjt-tab-button');
            var $tabPanels = $tabs.find('.cjt-tab-panel');

            $tabButtons.on('click', function() {
                var target = $(this).data('tab');

                $tabButtons.removeClass('active');
                $(this).addClass('active');

                $tabPanels.removeClass('active');
                $('#' + target).addClass('active');
            });
        });
    }

    /**
     * Tooltips initialisieren
     */
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            var $el = $(this);
            var text = $el.data('tooltip');

            $el.on('mouseenter', function() {
                var $tooltip = $('<div class="cjt-tooltip">' + text + '</div>');
                $('body').append($tooltip);

                var pos = $el.offset();
                $tooltip.css({
                    top: pos.top - $tooltip.outerHeight() - 10,
                    left: pos.left + ($el.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                }).fadeIn(200);
            });

            $el.on('mouseleave', function() {
                $('.cjt-tooltip').fadeOut(200, function() {
                    $(this).remove();
                });
            });
        });
    }

    /**
     * Settings-Form validieren
     */
    function validateSettingsForm() {
        $('form').on('submit', function(e) {
            var $form = $(this);
            var $apiUrl = $form.find('input[name="api_url"]');

            if ($apiUrl.length && $apiUrl.val()) {
                var url = $apiUrl.val();

                // Prüfe ob HTTPS
                if (url.indexOf('http://') === 0 && location.protocol === 'https:') {
                    if (!confirm('⚠️ Die API-URL verwendet HTTP, während Ihre WordPress-Seite HTTPS verwendet. Dies kann zu Mixed-Content-Problemen führen.\n\nTrotzdem fortfahren?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Prüfe ob /api am Ende
                if (url.slice(-4) !== '/api' && url.indexOf('/api/') === -1) {
                    if (confirm('ℹ️ Die URL sollte normalerweise auf "/api" enden.\n\nMöchten Sie "/api" automatisch anhängen?')) {
                        $apiUrl.val(url.replace(/\/$/, '') + '/api');
                    }
                }
            }
        });
    }

    /**
     * AJAX-Helper
     */
    window.cjtAdmin = {
        /**
         * Test API Connection
         */
        testConnection: function(apiUrl, callback) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'cjt_test_connection',
                    nonce: cjtAdminData.nonce,
                    api_url: apiUrl
                },
                success: function(response) {
                    callback(response.success, response.data);
                },
                error: function() {
                    callback(false, 'Verbindungsfehler');
                }
            });
        },

        /**
         * Clear Cache
         */
        clearCache: function(callback) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'cjt_clear_cache',
                    nonce: cjtAdminData.nonce
                },
                success: function(response) {
                    callback(response.success);
                },
                error: function() {
                    callback(false);
                }
            });
        },

        /**
         * Get Statistics
         */
        getStatistics: function(callback) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'cjt_get_statistics',
                    nonce: cjtAdminData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        callback(true, response.data);
                    } else {
                        callback(false, response.data);
                    }
                },
                error: function() {
                    callback(false, null);
                }
            });
        }
    };

})(jQuery);
