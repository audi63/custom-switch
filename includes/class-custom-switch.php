<?php

class Custom_Switch {

    /**
     * Constructor for the Custom_Switch class.
     *
     * Initializes the class by registering hooks for enqueueing assets,
     * rendering the shortcode, and handling AJAX requests to toggle the button state.
     */
    public function __construct() {
        add_action('enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('custom_switch', array($this, 'render_switch'));
        add_action('ajax_toggle_button_state', array($this, 'toggle_button_state'));
        add_action('ajax_nopriv_toggle_button_state', array($this, 'toggle_button_state'));
    }

    /**
     * Enqueues the necessary styles and scripts for the custom switch.
     *
     * @return void
     */
    public function enqueue_assets() {
        enqueue_style('custom-button-style', CUSTOM_SWITCH_PLUGIN_URL . 'assets/css/custom-button-style.css');
        enqueue_script('custom-button-script', CUSTOM_SWITCH_PLUGIN_URL . 'assets/js/custom-button-script.js', array('jquery'), null, true);

        $shortcodes = get_option('custom_switch_shortcodes', array());
        $localized_data = [];
        foreach ($shortcodes as $id => $shortcode) {
            error_log('Creating ajax_object_' . $id);
            $localized_data['ajax_object_' . $id] = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'initial_state' => $shortcode['state'],
                'shortcode_id' => $id,
                'label_on' => $shortcode['label_on'] ?: 'Service disponible',
                'label_off' => $shortcode['label_off'] ?: 'Service indisponible',
                'button_on' => $shortcode['button_on_image'] ?: CUSTOM_SWITCH_PLUGIN_URL . 'assets/images/default/button_on.svg',
                'button_off' => $shortcode['button_off_image'] ?: CUSTOM_SWITCH_PLUGIN_URL . 'assets/images/default/button_off.svg',
                'can_edit' => current_user_can('manage_options')
            );
        }

        // Localize the script with multiple objects
        localize_script('custom-button-script', 'custom_switch_data', $localized_data);
    }

    /**
     * Renders the custom switch shortcode.
     *
     * @param array $atts The shortcode attributes.
     * @return string The rendered HTML for the custom switch.
     */
    public function render_switch($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
            'label-on' => 'Service disponible',
            'label-off' => 'Service indisponible',
            'button-on' => CUSTOM_SWITCH_PLUGIN_URL . 'assets/images/default/button_on.svg',
            'button-off' => CUSTOM_SWITCH_PLUGIN_URL . 'assets/images/default/button_off.svg',
            'label-position' => 'after'
        ), $atts, 'custom_switch');

        $shortcode_id = esc_attr($atts['id']);
        $label_on = esc_attr($atts['label-on']);
        $label_off = esc_attr($atts['label-off']);
        $button_on = esc_url($atts['button-on']);
        $button_off = esc_url($atts['button-off']);
        $label_position = esc_attr($atts['label-position']);

        ob_start();
        ?>
        <div class="service-status-container" data-shortcode-id="<?php echo $shortcode_id; ?>">
            <?php if ($label_position === 'before'): ?>
                <span class="service-status-text-<?php echo $shortcode_id; ?> service-status-text-before"><?php echo $label_off; ?></span>
            <?php endif; ?>
            <div id="button-on-<?php echo $shortcode_id; ?>" class="toggle-button-container hidden">
                <img src="<?php echo $button_on; ?>" alt="<?php echo $label_on; ?>">
            </div>
            <div id="button-off-<?php echo $shortcode_id; ?>" class="toggle-button-container">
                <img src="<?php echo $button_off; ?>" alt="<?php echo $label_off; ?>">
            </div>
            <?php if ($label_position === 'after'): ?>
                <span class="service-status-text-<?php echo $shortcode_id; ?> service-status-text-after"><?php echo $label_off; ?></span>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handles the AJAX request to toggle the button state.
     *
     * @return void
     */
    public function toggle_button_state() {
        if (!current_user_can('manage_options')) {
            send_json_error('You do not have sufficient permissions to perform this action.');
        }

        if (!isset($_POST['shortcode_id']) || !isset($_POST['state'])) {
            send_json_error('Invalid data');
        }

        $shortcode_id = sanitize_text_field($_POST['shortcode_id']);
        $new_state = sanitize_text_field($_POST['state']);

        $shortcodes = get_option('custom_switch_shortcodes', array());
        if (isset($shortcodes[$shortcode_id])) {
            $shortcodes[$shortcode_id]['state'] = $new_state;
            update_option('custom_switch_shortcodes', $shortcodes);
            send_json_success($new_state);
        } else {
            send_json_error('Shortcode not found');
        }
    }
}

new Custom_Switch();
