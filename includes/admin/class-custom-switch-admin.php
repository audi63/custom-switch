<?php

class Custom_Switch_Admin {

    /**
     * Constructor for the Custom_Switch_Admin class.
     *
     * Initializes the class by registering hooks for the admin menu,
     * enqueueing admin assets, and handling admin post actions.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_post_save_custom_switch', array($this, 'save_custom_switch'));
        add_action('admin_post_delete_custom_switch', array($this, 'delete_custom_switch'));
    }

    /**
     * Adds a menu page for managing custom switch shortcodes.
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page(
            __('Custom Switch', 'custom-switch'),
            __('Custom Switch', 'custom-switch'),
            'manage_options',
            'custom-switch',
            array($this, 'admin_page'),
            'dashicons-admin-generic'
        );
    }

    /**
     * Enqueues admin-specific styles and scripts for the custom switch page.
     *
     * @param string $hook The current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_custom-switch') {
            return;
        }
        enqueue_script('custom-switch-admin-script', CUSTOM_SWITCH_PLUGIN_URL . 'assets/js/custom-button-script-admin.js', array('jquery'), null, true);
        enqueue_style('custom-switch-admin-style', CUSTOM_SWITCH_PLUGIN_URL . 'assets/css/custom-button-style.css');
    }

    /**
     * Renders the admin page for managing custom switch shortcodes.
     *
     * @return void
     */
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            die(__('Permission denied', 'custom-switch'));
        }

        $shortcodes = get_option('custom_switch_shortcodes', array());
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Custom Switch Shortcodes', 'custom-switch'); ?></h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="save_custom_switch">
                <?php nonce_field('save_custom_switch_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Shortcode ID', 'custom-switch'); ?></th>
                        <td><input type="text" name="shortcode_id" required></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Label ON', 'custom-switch'); ?></th>
                        <td><input type="text" name="label_on"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Label OFF', 'custom-switch'); ?></th>
                        <td><input type="text" name="label_off"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Button ON Image URL', 'custom-switch'); ?></th>
                        <td><input type="url" name="button_on_image"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Button OFF Image URL', 'custom-switch'); ?></th>
                        <td><input type="url" name="button_off_image"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Label Position', 'custom-switch'); ?></th>
                        <td>
                            <select name="label_position">
                                <option value="after"><?php echo esc_html__('After', 'custom-switch'); ?></option>
                                <option value="before"><?php echo esc_html__('Before', 'custom-switch'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php echo esc_html__('Save Shortcode', 'custom-switch'); ?>">
                </p>
            </form>
            <h2><?php echo esc_html__('Existing Shortcodes', 'custom-switch'); ?></h2>
            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Shortcode ID', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Label ON', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Label OFF', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Button ON Image', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Button OFF Image', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Label Position', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Shortcode', 'custom-switch'); ?></th>
                        <th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html__('Actions', 'custom-switch'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($shortcodes)): ?>
                        <?php foreach ($shortcodes as $id => $shortcode): ?>
                            <tr>
                                <td><?php echo esc_html($id); ?></td>
                                <td><?php echo esc_html($shortcode['label_on']); ?></td>
                                <td><?php echo esc_html($shortcode['label_off']); ?></td>
                                <td><?php echo esc_url($shortcode['button_on_image']); ?></td>
                                <td><?php echo esc_url($shortcode['button_off_image']); ?></td>
                                <td><?php echo esc_html($shortcode['label_position']); ?></td>
                                <td><input type="text" readonly="readonly" value='<?php echo esc_attr('[custom_switch id="' . $id . '" label-on="' . esc_attr($shortcode['label_on']) . '" label-off="' . esc_attr($shortcode['label_off']) . '" button-on="' . esc_url($shortcode['button_on_image']) . '" button-off="' . esc_url($shortcode['button_off_image']) . '" label-position="' . esc_attr($shortcode['label_position']) . '"]'); ?>' onclick="this.select();"></td>
                                <td>
                                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_custom_switch">
                                        <input type="hidden" name="shortcode_id" value="<?php echo esc_attr($id); ?>">
                                        <?php nonce_field('delete_custom_switch_nonce'); ?>
                                        <input type="submit" class="button-primary" value="<?php echo esc_html__('Delete', 'custom-switch'); ?>" onclick="return confirm('<?php echo esc_html__('Are you sure you want to delete this shortcode?', 'custom-switch'); ?>');">
                                    </form>
                                    <button class="button edit-shortcode" data-shortcode-id="<?php echo esc_attr($id); ?>"><?php echo esc_html__('Edit', 'custom-switch'); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8"><?php echo esc_html__('No shortcodes found.', 'custom-switch'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="edit-shortcode-modal" style="display:none;">
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="save_custom_switch">
                    <input type="hidden" name="shortcode_id" id="edit-shortcode-id">
                    <?php nonce_field('save_custom_switch_nonce'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__('Label ON', 'custom-switch'); ?></th>
                            <td><input type="text" name="label_on" id="edit-label-on"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__('Label OFF', 'custom-switch'); ?></th>
                            <td><input type="text" name="label_off" id="edit-label-off"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__('Button ON Image URL', 'custom-switch'); ?></th>
                            <td><input type="url" name="button_on_image" id="edit-button-on-image"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__('Button OFF Image URL', 'custom-switch'); ?></th>
                            <td><input type="url" name="button_off_image" id="edit-button-off-image"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__('Label Position', 'custom-switch'); ?></th>
                            <td>
                                <select name="label_position" id="edit-label-position">
                                    <option value="after"><?php echo esc_html__('After', 'custom-switch'); ?></option>
                                    <option value="before"><?php echo esc_html__('Before', 'custom-switch'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php echo esc_html__('Save Changes', 'custom-switch'); ?>">
                        <button type="button" class="button close-modal"><?php echo esc_html__('Close', 'custom-switch'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Handles the saving of a custom switch shortcode.
     *
     * @return void
     */
    public function save_custom_switch() {
        if (!isset($_POST['_wpnonce']) || !verify_nonce($_POST['_wpnonce'], 'save_custom_switch_nonce')) {
            die(__('Nonce verification failed', 'custom-switch'));
        }

        if (!current_user_can('manage_options')) {
            die(__('Permission denied', 'custom-switch'));
        }

        $shortcode_id = sanitize_text_field($_POST['shortcode_id']);
        $label_on = sanitize_text_field($_POST['label_on']);
        $label_off = sanitize_text_field($_POST['label_off']);
        $button_on_image = esc_url_raw($_POST['button_on_image']);
        $button_off_image = esc_url_raw($_POST['button_off_image']);
        $label_position = sanitize_text_field($_POST['label_position']);

        $shortcodes = get_option('custom_switch_shortcodes', array());

        $shortcodes[$shortcode_id] = array(
            'label_on' => $label_on,
            'label_off' => $label_off,
            'button_on_image' => $button_on_image,
            'button_off_image' => $button_off_image,
            'label_position' => $label_position,
            'state' => isset($shortcodes[$shortcode_id]['state']) ? $shortcodes[$shortcode_id]['state'] : 'off'
        );

        update_option('custom_switch_shortcodes', $shortcodes);

        redirect(admin_url('admin.php?page=custom-switch'));
        exit;
    }

    /**
     * Handles the deletion of a custom switch shortcode.
     *
     * @return void
     */
    public function delete_custom_switch() {
        if (!isset($_POST['_wpnonce']) || !verify_nonce($_POST['_wpnonce'], 'delete_custom_switch_nonce')) {
            die(__('Nonce verification failed', 'custom-switch'));
        }

        if (!current_user_can('manage_options')) {
            die(__('Permission denied', 'custom-switch'));
        }

        $shortcode_id = sanitize_text_field($_POST['shortcode_id']);
        $shortcodes = get_option('custom_switch_shortcodes', array());

        if (isset($shortcodes[$shortcode_id])) {
            unset($shortcodes[$shortcode_id]);
            update_option('custom_switch_shortcodes', $shortcodes);
        }

        redirect(admin_url('admin.php?page=custom-switch'));
        exit;
    }
}

new Custom_Switch_Admin();
