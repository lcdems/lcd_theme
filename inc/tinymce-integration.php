<?php
/**
 * TinyMCE Custom Shortcode Buttons
 *
 * @package LCD_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add custom buttons to TinyMCE editor.
 */
function lcd_add_tinymce_shortcode_buttons() {
    // Check if the current user has permission to edit posts and pages
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    // Check if WYSIWYG is enabled
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'lcd_add_tinymce_shortcode_plugin');
        add_filter('mce_buttons', 'lcd_register_tinymce_shortcode_buttons');
    }
}
add_action('admin_head', 'lcd_add_tinymce_shortcode_buttons');

/**
 * Register the TinyMCE plugin.
 *
 * @param array $plugin_array Array of existing TinyMCE plugins.
 * @return array Array of TinyMCE plugins with our custom plugin added.
 */
function lcd_add_tinymce_shortcode_plugin($plugin_array) {
    $plugin_array['lcd_shortcodes'] = get_template_directory_uri() . '/assets/js/admin/lcd-tinymce-shortcodes.js';
    return $plugin_array;
}

/**
 * Register the custom buttons with TinyMCE.
 *
 * @param array $buttons Array of existing TinyMCE buttons.
 * @return array Array of TinyMCE buttons with our custom buttons added.
 */
function lcd_register_tinymce_shortcode_buttons($buttons) {
    array_push($buttons, 'lcd_cols_button');
    array_push($buttons, 'lcd_col_button');
    return $buttons;
} 