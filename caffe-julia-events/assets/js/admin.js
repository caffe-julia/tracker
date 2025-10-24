/**
 * Caffe Julia Events - Admin JavaScript
 */

jQuery(document).ready(function($) {

    // Bestätigungsdialog beim Löschen
    $('.cje-delete-btn').on('click', function(e) {
        if (!confirm('Event wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')) {
            e.preventDefault();
            return false;
        }
    });

    // Erfolgsmeldung automatisch ausblenden
    setTimeout(function() {
        $('.notice.is-dismissible').fadeOut();
    }, 3000);

});
