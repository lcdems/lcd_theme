<?php

/**
 * Homepage Builder functionality
 *
 * @package LCD_Theme
 */

if (!defined('ABSPATH')) exit;

/**
 * Register Homepage Section post type
 */
function lcd_register_homepage_section_post_type()
{
    $labels = array(
        'name'               => _x('Homepage Sections', 'post type general name', 'lcd-theme'),
        'singular_name'      => _x('Homepage Section', 'post type singular name', 'lcd-theme'),
        'menu_name'          => _x('Homepage Sections', 'admin menu', 'lcd-theme'),
        'add_new'            => _x('Add New Section', 'homepage section', 'lcd-theme'),
        'add_new_item'       => __('Add New Homepage Section', 'lcd-theme'),
        'edit_item'          => __('Edit Homepage Section', 'lcd-theme'),
        'new_item'           => __('New Homepage Section', 'lcd-theme'),
        'view_item'          => __('View Homepage Section', 'lcd-theme'),
        'search_items'       => __('Search Homepage Sections', 'lcd-theme'),
        'not_found'          => __('No homepage sections found', 'lcd-theme'),
        'not_found_in_trash' => __('No homepage sections found in Trash', 'lcd-theme'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=page',
        'capability_type'     => 'page',
        'hierarchical'        => false,
        'supports'            => array('title'),
        'menu_position'       => 20,
        'register_meta_box_cb' => 'lcd_add_homepage_section_metaboxes',
    );

    register_post_type('homepage_section', $args);
}
add_action('init', 'lcd_register_homepage_section_post_type');

/**
 * Add metaboxes to Homepage Section post type
 */
function lcd_add_homepage_section_metaboxes()
{
    add_meta_box(
        'homepage_section_type',
        __('Section Type', 'lcd-theme'),
        'lcd_section_type_metabox',
        'homepage_section',
        'normal',
        'high'
    );

    add_meta_box(
        'homepage_section_content',
        __('Section Content', 'lcd-theme'),
        'lcd_section_content_metabox',
        'homepage_section',
        'normal',
        'high'
    );

    add_meta_box(
        'homepage_section_order',
        __('Section Order', 'lcd-theme'),
        'lcd_section_order_metabox',
        'homepage_section',
        'side',
        'default'
    );
}

/**
 * Section Type metabox callback
 */
function lcd_section_type_metabox($post)
{
    wp_nonce_field('lcd_homepage_section_type_nonce', 'homepage_section_type_nonce');
    
    $section_type = get_post_meta($post->ID, '_section_type', true);
    
    // Set default to three_card only if it's a new post and no type is set
    if (empty($section_type) && !wp_is_post_revision($post->ID) && get_post_status($post->ID) === 'auto-draft') {
        $section_type = 'three_card';
    }
    ?>
    <select name="section_type" id="section_type" class="widefat">
        <option value="three_card" <?php selected($section_type, 'three_card'); ?>><?php _e('3 Card Layout', 'lcd-theme'); ?></option>
        <option value="widget_area" <?php selected($section_type, 'widget_area'); ?>><?php _e('Widget Area', 'lcd-theme'); ?></option>
        <option value="text" <?php selected($section_type, 'text'); ?>><?php _e('Text', 'lcd-theme'); ?></option>
        <option value="html" <?php selected($section_type, 'html'); ?>><?php _e('HTML', 'lcd-theme'); ?></option>
    </select>
    <?php
}

/**
 * Section Content metabox callback
 */
function lcd_section_content_metabox($post)
{
    // Add nonce field at the start of the metabox
    wp_nonce_field('lcd_homepage_section_content_nonce', 'homepage_section_content_nonce');

    $section_type = get_post_meta($post->ID, '_section_type', true);
    $section_content = get_post_meta($post->ID, '_section_content', true);

    // Set default to three_card only if it's a new post
    if (empty($section_type) && !wp_is_post_revision($post->ID)) {
        $section_type = 'three_card';
    }

    // Initialize content array if empty
    if (empty($section_content)) {
        $section_content = array();
    }

    // Add a wrapper div to help preserve form fields during AJAX updates
    echo '<div id="section-content-fields">';

    switch ($section_type) {
        case 'three_card':
            lcd_render_three_card_fields($section_content);
            break;
        case 'widget_area':
            lcd_render_widget_area_fields($section_content);
            break;
        case 'text':
            lcd_render_text_fields($section_content);
            break;
        case 'html':
            lcd_render_html_fields($section_content);
            break;
    }

    echo '</div>';
}

/**
 * Render fields for Three Card Layout
 */
function lcd_render_three_card_fields($content)
{
    for ($i = 1; $i <= 3; $i++) {
        $card = isset($content['card_' . $i]) ? $content['card_' . $i] : array();
        ?>
        <div class="card-settings" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
            <h3><?php printf(__('Card %d', 'lcd-theme'), $i); ?></h3>
            
            <p>
                <label for="card_<?php echo $i; ?>_icon"><?php _e('Icon:', 'lcd-theme'); ?></label><br>
                <div class="card-icon-upload">
                    <div class="icon-preview" style="margin-bottom: 10px;">
                        <?php if (!empty($card['icon'])): ?>
                            <img src="<?php echo esc_url($card['icon']); ?>" style="max-width: 100px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="section_content[card_<?php echo $i; ?>][icon]" 
                           id="card_<?php echo $i; ?>_icon" 
                           value="<?php echo esc_attr(isset($card['icon']) ? $card['icon'] : ''); ?>" 
                           class="card-icon-input">
                    <button type="button" class="button upload-icon-button" data-card="<?php echo $i; ?>">
                        <?php _e('Upload Icon', 'lcd-theme'); ?>
                    </button>
                    <?php if (!empty($card['icon'])): ?>
                        <button type="button" class="button remove-icon-button" data-card="<?php echo $i; ?>">
                            <?php _e('Remove Icon', 'lcd-theme'); ?>
                        </button>
                    <?php endif; ?>
                    <p class="description"><?php _e('Upload a square icon image (recommended size: 100x100px).', 'lcd-theme'); ?></p>
                </div>
            </p>

            <p>
                <label for="card_<?php echo $i; ?>_title"><?php _e('Title:', 'lcd-theme'); ?></label><br>
                <input type="text" id="card_<?php echo $i; ?>_title" name="section_content[card_<?php echo $i; ?>][title]" 
                       value="<?php echo esc_attr(isset($card['title']) ? $card['title'] : ''); ?>" class="widefat">
            </p>

            <p>
                <label for="card_<?php echo $i; ?>_content"><?php _e('Content:', 'lcd-theme'); ?></label><br>
                <textarea id="card_<?php echo $i; ?>_content" name="section_content[card_<?php echo $i; ?>][content]" 
                          class="widefat" rows="4"><?php echo esc_textarea(isset($card['content']) ? $card['content'] : ''); ?></textarea>
            </p>

            <p>
                <label for="card_<?php echo $i; ?>_link"><?php _e('Link:', 'lcd-theme'); ?></label><br>
                <input type="url" id="card_<?php echo $i; ?>_link" name="section_content[card_<?php echo $i; ?>][link]" 
                       value="<?php echo esc_url(isset($card['link']) ? $card['link'] : ''); ?>" class="widefat">
            </p>

            <p>
                <label for="card_<?php echo $i; ?>_button_text"><?php _e('Button Text:', 'lcd-theme'); ?></label><br>
                <input type="text" id="card_<?php echo $i; ?>_button_text" name="section_content[card_<?php echo $i; ?>][button_text]" 
                       value="<?php echo esc_attr(isset($card['button_text']) ? $card['button_text'] : ''); ?>" class="widefat">
            </p>
        </div>
        <?php
    }
}

/**
 * Render fields for Widget Area
 */
function lcd_render_widget_area_fields($content)
{
    global $wp_registered_sidebars;
    ?>
    <p>
        <label for="widget_area_id"><?php _e('Select Widget Area:', 'lcd-theme'); ?></label><br>
        <select id="widget_area_id" name="section_content[widget_area_id]" class="widefat">
            <option value=""><?php _e('Select a Widget Area', 'lcd-theme'); ?></option>
            <?php
            foreach ($wp_registered_sidebars as $id => $sidebar) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($id),
                    selected(isset($content['widget_area_id']) ? $content['widget_area_id'] : '', $id, false),
                    esc_html($sidebar['name'])
                );
            }
            ?>
        </select>
    </p>
    <?php
}

/**
 * Render fields for Text section
 */
function lcd_render_text_fields($content)
{
    global $post;

    // Handle both AJAX calls and direct page loads
    $is_new_post = false;
    if ($post) {
        $is_new_post = get_post_status($post->ID) === 'auto-draft';
    } else {
        // During AJAX calls, $post might not be set
        $is_new_post = true;
    }

    if ($is_new_post) {
    ?>
        <div class="postbox" style="margin-top: 10px;">
            <div class="inside">
                <div class="notice notice-info inline" style="margin: 10px 0; padding: 10px;">
                    <?php _e('The text editor will appear here after the first save.', 'lcd-theme'); ?>
                </div>
                
            </div>
        </div>
    <?php
        return;
    }

    $editor_id = 'text_content_' . uniqid();
    ?>
    <div class="postbox" style="margin-top: 10px;">
        <div class="inside">
            <?php
            wp_editor(
                isset($content['text']) ? $content['text'] : '',
                $editor_id,
                array(
                    'textarea_name' => 'section_content[text]',
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'editor_class' => 'section-text-editor',
                    'tinymce'      => true,
                    'quicktags'    => true,
                    'editor_height' => 300
                )
            );
            ?>
        </div>
    </div>
<?php
}

/**
 * Render fields for HTML section
 */
function lcd_render_html_fields($content)
{
?>
    <p>
        <label for="html_content"><?php _e('HTML Content:', 'lcd-theme'); ?></label><br>
        <textarea id="html_content" name="section_content[html]" class="widefat code" rows="15"><?php
                                                                                                echo isset($content['html']) ? esc_textarea($content['html']) : '';
                                                                                                ?></textarea>
    <p class="description"><?php _e('Enter raw HTML code here. For security, some HTML tags and attributes may be filtered.', 'lcd-theme'); ?></p>
    </p>
<?php
}

/**
 * Section Order metabox callback
 */
function lcd_section_order_metabox($post)
{
    wp_nonce_field('lcd_homepage_section_order_nonce', 'homepage_section_order_nonce');

    $section_order = get_post_meta($post->ID, '_section_order', true);
?>
    <p>
        <label for="section_order"><?php _e('Order:', 'lcd-theme'); ?></label>
        <input type="number" id="section_order" name="section_order" value="<?php echo esc_attr($section_order); ?>"
            class="widefat" min="0" step="1">
    </p>
    <p class="description">
        <?php _e('Lower numbers will appear first on the homepage.', 'lcd-theme'); ?>
    </p>
<?php
}

/**
 * Save homepage section meta
 */
function lcd_save_homepage_section_meta($post_id)
{
    // Skip if this is the initial auto-draft creation (no POST data and no action)
    if (empty($_POST) || (empty($_POST['action']) && isset($_POST['auto_draft']) && $_POST['auto_draft'] === '1')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // If this is a revision, don't save
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Check if our nonce is set
    if (!isset($_POST['homepage_section_type_nonce'])) {
        return;
    }

    // Verify the type nonce
    if (!wp_verify_nonce($_POST['homepage_section_type_nonce'], 'lcd_homepage_section_type_nonce')) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    // Get the current section type
    $current_type = get_post_meta($post_id, '_section_type', true);

    // Save section type
    if (isset($_POST['section_type'])) {
        $new_type = sanitize_text_field($_POST['section_type']);
        update_post_meta($post_id, '_section_type', $new_type);
        $current_type = $new_type;
    }

    // Save section content based on the current section type
    if (isset($_POST['section_content']) && !empty($current_type)) {
        $content = $_POST['section_content'];
        
        // Sanitize based on section type
        switch ($current_type) {
            case 'three_card':
                foreach ($content as $card_key => $card) {
                    if (is_array($card)) {
                        $content[$card_key]['title'] = sanitize_text_field($card['title']);
                        $content[$card_key]['content'] = wp_kses_post($card['content']);
                        $content[$card_key]['link'] = esc_url_raw($card['link']);
                        $content[$card_key]['button_text'] = sanitize_text_field($card['button_text']);
                        $content[$card_key]['icon'] = esc_url_raw($card['icon']);
                    }
                }
                break;
            case 'widget_area':
                $content['widget_area_id'] = sanitize_text_field($content['widget_area_id']);
                break;
            case 'text':
                $content['text'] = wp_kses_post($content['text']);
                break;
            case 'html':
                // Allow more HTML tags for the HTML section
                $allowed_html = array_merge(
                    wp_kses_allowed_html('post'),
                    array(
                        'iframe' => array(
                            'src' => true,
                            'width' => true,
                            'height' => true,
                            'frameborder' => true,
                            'scrolling' => true,
                            'style' => true,
                            'title' => true,
                            'allow' => true,
                            'allowfullscreen' => true
                        )
                    )
                );
                $content['html'] = wp_kses($content['html'], $allowed_html);
                break;
        }
        
        update_post_meta($post_id, '_section_content', $content);
    }

    // Save section order if the nonce is valid
    if (isset($_POST['homepage_section_order_nonce']) && 
        wp_verify_nonce($_POST['homepage_section_order_nonce'], 'lcd_homepage_section_order_nonce') &&
        isset($_POST['section_order'])) {
        update_post_meta($post_id, '_section_order', absint($_POST['section_order']));
    }
}
add_action('save_post_homepage_section', 'lcd_save_homepage_section_meta');

/**
 * Enqueue admin scripts and styles
 */
function lcd_enqueue_homepage_builder_admin_scripts($hook)
{
    global $post_type;

    if ($post_type !== 'homepage_section') {
        return;
    }

    // Enqueue WordPress media scripts
    wp_enqueue_media();

    wp_enqueue_script(
        'lcd-homepage-builder',
        get_template_directory_uri() . '/js/homepage-builder-admin.js',
        array('jquery', 'media-upload'),
        '1.0.0',
        true
    );

    wp_localize_script('lcd-homepage-builder', 'lcdHomepageBuilder', array(
        'nonce' => wp_create_nonce('lcd_homepage_builder_nonce'),
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    wp_enqueue_style(
        'lcd-homepage-builder',
        get_template_directory_uri() . '/css/homepage-builder-admin.css',
        array(),
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'lcd_enqueue_homepage_builder_admin_scripts');

/**
 * Add custom columns to the homepage sections list
 */
function lcd_add_homepage_section_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['section_type'] = __('Section Type', 'lcd-theme');
            $new_columns['section_order'] = __('Order', 'lcd-theme');
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_homepage_section_posts_columns', 'lcd_add_homepage_section_columns');

/**
 * Display custom column content
 */
function lcd_display_homepage_section_columns($column, $post_id) {
    switch ($column) {
        case 'section_type':
            $section_type = get_post_meta($post_id, '_section_type', true);
            $types = array(
                'three_card' => __('3 Card Layout', 'lcd-theme'),
                'widget_area' => __('Widget Area', 'lcd-theme'),
                'text' => __('Text', 'lcd-theme'),
                'html' => __('HTML', 'lcd-theme')
            );
            echo isset($types[$section_type]) ? esc_html($types[$section_type]) : esc_html($section_type);
            break;
        case 'section_order':
            $order = get_post_meta($post_id, '_section_order', true);
            echo '<div class="section-order">' . esc_html($order) . '</div>';
            break;
    }
}
add_action('manage_homepage_section_posts_custom_column', 'lcd_display_homepage_section_columns', 10, 2);

/**
 * Make the order column sortable
 */
function lcd_sortable_homepage_section_columns($columns) {
    $columns['section_order'] = 'section_order';
    return $columns;
}
add_filter('manage_edit-homepage_section_sortable_columns', 'lcd_sortable_homepage_section_columns');

/**
 * Add order to quick/bulk edit
 */
function lcd_add_edit_fields($column_name, $post_type, $taxonomy = null) {
    if ($post_type !== 'homepage_section' || $column_name !== 'section_order') return;

    // Different layout for quick edit vs bulk edit
    $is_bulk = did_action('bulk_edit_custom_box');
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label>
                <span class="title"><?php _e('Order', 'lcd-theme'); ?></span>
                <span class="input-text-wrap">
                    <?php if ($is_bulk): ?>
                        <input type="number" name="section_order" class="section-order bulk-order" value="" min="0" step="1">
                        <p class="description"><?php _e('Leave empty to keep existing values.', 'lcd-theme'); ?></p>
                    <?php else: ?>
                        <input type="number" name="section_order" class="section-order" value="" min="0" step="1">
                    <?php endif; ?>
                </span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'lcd_add_edit_fields', 10, 3);
add_action('bulk_edit_custom_box', 'lcd_add_edit_fields', 10, 3);

/**
 * Save quick edit changes
 */
function lcd_save_quick_edit_order($post_id) {
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check post type
    if (get_post_type($post_id) !== 'homepage_section') return;

    // Check permissions
    if (!current_user_can('edit_page', $post_id)) return;

    // Check if our nonce is set and verify it
    if (!isset($_POST['_inline_edit']) || !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) return;

    // Save the order if it's set
    if (isset($_POST['section_order'])) {
        update_post_meta($post_id, '_section_order', absint($_POST['section_order']));
    }
}
add_action('save_post', 'lcd_save_quick_edit_order');

/**
 * Save bulk edit changes
 */
function lcd_save_bulk_edit_order() {
    // Check permissions and nonce
    if (!current_user_can('edit_pages')) {
        wp_die(__('You do not have permission to perform this action.', 'lcd-theme'));
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lcd-bulk-edit-nonce')) {
        wp_die(__('Invalid nonce.', 'lcd-theme'));
    }

    // Get the IDs of the posts being edited
    $post_ids = (isset($_POST['post_ids']) && !empty($_POST['post_ids'])) ? explode(',', $_POST['post_ids']) : array();

    if (!empty($post_ids) && isset($_POST['section_order'])) {
        $order = absint($_POST['section_order']);
        foreach ($post_ids as $post_id) {
            update_post_meta($post_id, '_section_order', $order);
        }
    }

    wp_send_json_success();
}
add_action('wp_ajax_lcd_save_bulk_edit_order', 'lcd_save_bulk_edit_order');

/**
 * Add JavaScript for quick/bulk edit
 */
function lcd_enqueue_admin_list_scripts($hook) {
    global $post_type;
    
    if ($hook === 'edit.php' && $post_type === 'homepage_section') {
        wp_enqueue_script(
            'lcd-admin-list',
            get_template_directory_uri() . '/js/homepage-builder-admin-list.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('lcd-admin-list', 'lcdAdminList', array(
            'nonce' => wp_create_nonce('lcd-bulk-edit-nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('admin_enqueue_scripts', 'lcd_enqueue_admin_list_scripts');
