jQuery(document).ready(function($) {
    console.log("Custom switch data: ", custom_switch_data);

    $('[data-shortcode-id]').each(function() {
        var shortcodeId = $(this).data('shortcode-id');
        var ajaxObject = custom_switch_data['ajax_object_' + shortcodeId];

        if (!ajaxObject) {
            console.error("Ajax object not found for shortcode ID: " + shortcodeId);
            return;
        }

        console.log("Initializing buttons for shortcode ID: " + shortcodeId + ", initial state: " + ajaxObject.initial_state);
        console.log("ajaxObject for " + shortcodeId, ajaxObject);

        var initialState = ajaxObject.initial_state;
        toggleButtons(initialState, shortcodeId, ajaxObject);

        if (ajaxObject.can_edit) { // Ajout de la vérification des permissions
            $(this).on('click', function() {
                var newState = initialState === 'on' ? 'off' : 'on';
                toggleButtons(newState, shortcodeId, ajaxObject);
                initialState = newState;

                $.ajax({
                    url: ajaxObject.ajax_url,
                    method: 'POST',
                    data: {
                        action: 'toggle_button_state',
                        state: newState,
                        shortcode_id: shortcodeId
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log("Button state toggled to: " + newState);
                        }
                    }
                });
            });
        } else {
            console.log("User does not have permission to change the button state.");
        }
    });

    function toggleButtons(state, shortcodeId, ajaxObject) {
        var $buttonOn = $('#button-on-' + shortcodeId);
        var $buttonOff = $('#button-off-' + shortcodeId);
        var $label = $('.service-status-text-' + shortcodeId);

        $buttonOn.find('img').attr('src', ajaxObject.button_on);
        $buttonOff.find('img').attr('src', ajaxObject.button_off);

        if (state === 'on') {
            $buttonOn.removeClass('hidden');
            $buttonOff.addClass('hidden');
            $label.text(ajaxObject.label_on);
        } else {
            $buttonOn.addClass('hidden');
            $buttonOff.removeClass('hidden');
            $label.text(ajaxObject.label_off);
        }
    }
});



