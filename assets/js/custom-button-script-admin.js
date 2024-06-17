jQuery(document).ready(function($) {
    $('.edit-shortcode').on('click', function() {
        var shortcodeId = $(this).data('shortcode-id');
        var row = $(this).closest('tr');
        var shortcodeData = {
            label_on: row.find('td:eq(1)').text(),
            label_off: row.find('td:eq(2)').text(),
            button_on_image: row.find('td:eq(3)').text(),
            button_off_image: row.find('td:eq(4)').text(),
            label_position: row.find('td:eq(5)').text(),
        };

        // Charger les données actuelles du shortcode dans les champs de modification
        $('#edit-shortcode-id').val(shortcodeId);
        $('#edit-label-on').val(shortcodeData.label_on);
        $('#edit-label-off').val(shortcodeData.label_off);
        $('#edit-button-on-image').val(shortcodeData.button_on_image);
        $('#edit-button-off-image').val(shortcodeData.button_off_image);
        $('#edit-label-position').val(shortcodeData.label_position);

        $('#edit-shortcode-modal').show();
    });

    $('.close-modal').on('click', function() {
        $('#edit-shortcode-modal').hide();
    });
});


