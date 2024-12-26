<?php
if (!defined('ABSPATH')) exit;

/**
 * Include theme customizer
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Include Social Links Widget
 */
require get_template_directory() . '/inc/class-social-links-widget.php';

/**
 * Include Homepage Builder
 */
require get_template_directory() . '/inc/homepage-builder.php';

/**
 * Include shortcodes
 */
require get_template_directory() . '/inc/shortcodes/columns.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function lcd_theme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds.
    add_theme_support('responsive-embeds');

    // Add support for custom logo.
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Add support for HTML5 features.
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Register navigation menus.
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'lcd-theme'),
        'footer' => esc_html__('Footer Menu', 'lcd-theme'),
        'footer-secondary' => esc_html__('Footer Secondary Menu', 'lcd-theme'),
    ));
}
add_action('after_setup_theme', 'lcd_theme_setup');

/**
 * Register widget areas.
 */
function lcd_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'lcd-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'lcd-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer Widget Area
    register_sidebar(array(
        'name'          => esc_html__('Footer Widgets', 'lcd-theme'),
        'id'            => 'footer-widgets',
        'description'   => esc_html__('Add widgets to the footer area. Widgets will be displayed horizontally.', 'lcd-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Register custom widget areas
    $custom_widget_areas = get_option('lcd_custom_widget_areas', array());
    if (!empty($custom_widget_areas) && is_array($custom_widget_areas)) {
        foreach ($custom_widget_areas as $area) {
            register_sidebar(array(
                'name'          => esc_html($area['name']),
                'id'            => esc_attr($area['id']),
                'description'   => esc_html($area['description']),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ));
        }
    }

    // Register Social Links Widget
    register_widget('LCD_Social_Links_Widget');
}
add_action('widgets_init', 'lcd_widgets_init');

/**
 * Add Widget Areas admin page
 */
function lcd_add_widget_areas_page() {
    add_theme_page(
        __('Widget Areas', 'lcd-theme'),
        __('Widget Areas', 'lcd-theme'),
        'manage_options',
        'lcd-widget-areas',
        'lcd_widget_areas_page'
    );
}
add_action('admin_menu', 'lcd_add_widget_areas_page');

/**
 * Widget Areas admin page callback
 */
function lcd_widget_areas_page() {
    // Save new widget area
    if (isset($_POST['lcd_add_widget_area']) && check_admin_referer('lcd_widget_areas_nonce')) {
        $name = sanitize_text_field($_POST['widget_area_name']);
        $description = sanitize_textarea_field($_POST['widget_area_description']);
        
        if (!empty($name)) {
            $custom_widget_areas = get_option('lcd_custom_widget_areas', array());
            $id = 'custom-widget-' . sanitize_title($name);
            
            // Check if ID already exists
            $counter = 1;
            $base_id = $id;
            while (lcd_widget_area_id_exists($id, $custom_widget_areas)) {
                $id = $base_id . '-' . $counter;
                $counter++;
            }
            
            $custom_widget_areas[] = array(
                'name' => $name,
                'id' => $id,
                'description' => $description
            );
            
            update_option('lcd_custom_widget_areas', $custom_widget_areas);
            echo '<div class="notice notice-success"><p>' . esc_html__('Widget area added successfully.', 'lcd-theme') . '</p></div>';
        }
    }

    // Delete widget area
    if (isset($_POST['lcd_delete_widget_area']) && check_admin_referer('lcd_widget_areas_nonce')) {
        $area_id = sanitize_text_field($_POST['widget_area_id']);
        $custom_widget_areas = get_option('lcd_custom_widget_areas', array());
        
        foreach ($custom_widget_areas as $key => $area) {
            if ($area['id'] === $area_id) {
                unset($custom_widget_areas[$key]);
                break;
            }
        }
        
        update_option('lcd_custom_widget_areas', array_values($custom_widget_areas));
        echo '<div class="notice notice-success"><p>' . esc_html__('Widget area deleted successfully.', 'lcd-theme') . '</p></div>';
    }

    // Display the admin page
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Manage Widget Areas', 'lcd-theme'); ?></h1>
        
        <div class="lcd-widget-areas-form">
            <h2><?php echo esc_html__('Add New Widget Area', 'lcd-theme'); ?></h2>
            <form method="post" action="">
                <?php wp_nonce_field('lcd_widget_areas_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="widget_area_name"><?php echo esc_html__('Name', 'lcd-theme'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="widget_area_name" id="widget_area_name" class="regular-text" required>
                            <p class="description"><?php echo esc_html__('The name of the widget area as it appears in the Widgets admin screen', 'lcd-theme'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="widget_area_description"><?php echo esc_html__('Description', 'lcd-theme'); ?></label>
                        </th>
                        <td>
                            <textarea name="widget_area_description" id="widget_area_description" class="large-text" rows="3"></textarea>
                            <p class="description"><?php echo esc_html__('A brief description of what this widget area is used for', 'lcd-theme'); ?></p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="lcd_add_widget_area" class="button button-primary" value="<?php echo esc_attr__('Add Widget Area', 'lcd-theme'); ?>">
                </p>
            </form>
        </div>

        <div class="lcd-widget-areas-list">
            <h2><?php echo esc_html__('Existing Widget Areas', 'lcd-theme'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col"><?php echo esc_html__('Name', 'lcd-theme'); ?></th>
                        <th scope="col"><?php echo esc_html__('ID', 'lcd-theme'); ?></th>
                        <th scope="col"><?php echo esc_html__('Description', 'lcd-theme'); ?></th>
                        <th scope="col"><?php echo esc_html__('Actions', 'lcd-theme'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $custom_widget_areas = get_option('lcd_custom_widget_areas', array());
                    if (!empty($custom_widget_areas)) {
                        foreach ($custom_widget_areas as $area) {
                            ?>
                            <tr>
                                <td><?php echo esc_html($area['name']); ?></td>
                                <td><code><?php echo esc_html($area['id']); ?></code></td>
                                <td><?php echo esc_html($area['description']); ?></td>
                                <td>
                                    <form method="post" action="" style="display:inline;">
                                        <?php wp_nonce_field('lcd_widget_areas_nonce'); ?>
                                        <input type="hidden" name="widget_area_id" value="<?php echo esc_attr($area['id']); ?>">
                                        <input type="submit" name="lcd_delete_widget_area" class="button button-link-delete" value="<?php echo esc_attr__('Delete', 'lcd-theme'); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this widget area?', 'lcd-theme')); ?>');">
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4"><?php echo esc_html__('No custom widget areas found.', 'lcd-theme'); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Check if widget area ID already exists
 */
function lcd_widget_area_id_exists($id, $areas) {
    foreach ($areas as $area) {
        if ($area['id'] === $id) {
            return true;
        }
    }
    return false;
}

/**
 * Enqueue scripts and styles.
 */
function lcd_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'lcd-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;600&display=swap',
        array(),
        null
    );

    // Enqueue theme stylesheet
    wp_enqueue_style(
        'lcd-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Enqueue theme script
    wp_enqueue_script(
        'lcd-script',
        get_template_directory_uri() . '/js/script.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'lcd_scripts');

/**
 * Add custom classes to body
 */
function lcd_body_classes($classes) {
    // Add a class if we're viewing the front page
    if (is_front_page()) {
        $classes[] = 'front-page';
    }

    return $classes;
}
add_filter('body_class', 'lcd_body_classes');

/**
 * Customize excerpt length
 */
function lcd_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'lcd_excerpt_length');

/**
 * Customize excerpt more string
 */
function lcd_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'lcd_excerpt_more');

/**
 * Add custom image sizes
 */
function lcd_add_image_sizes() {
    add_image_size('lcd-featured', 1200, 600, true);
    add_image_size('lcd-card', 600, 400, true);
}
add_action('after_setup_theme', 'lcd_add_image_sizes');

/**
 * Remove "Category:", "Tag:", "Author:" from archive titles
 */
function lcd_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
}
add_filter('get_the_archive_title', 'lcd_archive_title');

// Register navigation menus
function lcd_register_menus() {
    register_nav_menus(array(
        'primary'          => __('Primary Menu', 'lcd-theme'),
        'footer'           => __('Footer Menu', 'lcd-theme'),
        'footer-secondary' => __('Footer Secondary Menu', 'lcd-theme'),
    ));
}
add_action('after_setup_theme', 'lcd_register_menus');

/**
 * Set default values for theme mods
 */
function lcd_default_theme_mods($value, $key = '') {
    if ($value === false || empty($value)) {
        // If key is not provided, try to determine it from the current filter
        if (empty($key)) {
            $current_filter = current_filter();
            $key = str_replace('theme_mod_', '', $current_filter);
        }
        
        switch ($key) {
            case 'lcd_footer_copyright':
                return '© {year} Lewis County Democrats. All rights reserved.';
            default:
                return $value;
        }
    }
    return $value;
}
add_filter('theme_mod_lcd_footer_copyright', 'lcd_default_theme_mods', 10, 2);

/**
 * Debug theme mods (only for administrators)
 */
function lcd_debug_theme_mods() {
    if (current_user_can('manage_options') && is_customize_preview()) {
        echo '<!-- Theme Mods Debug: ';
        echo 'Copyright: ' . esc_html(get_theme_mod('lcd_footer_copyright')) . "\n";
        echo '-->';
    }
}
add_action('wp_footer', 'lcd_debug_theme_mods');

/**
 * Register Meeting Notes Custom Post Type
 */
function lcd_register_meeting_notes_post_type() {
    $labels = array(
        'name'                  => _x('Meeting Notes', 'Post type general name', 'lcd-theme'),
        'singular_name'         => _x('Meeting Note', 'Post type singular name', 'lcd-theme'),
        'menu_name'            => _x('Meeting Notes', 'Admin Menu text', 'lcd-theme'),
        'add_new'              => __('Add New', 'lcd-theme'),
        'add_new_item'         => __('Add New Meeting Note', 'lcd-theme'),
        'edit_item'            => __('Edit Meeting Note', 'lcd-theme'),
        'new_item'             => __('New Meeting Note', 'lcd-theme'),
        'view_item'            => __('View Meeting Note', 'lcd-theme'),
        'search_items'         => __('Search Meeting Notes', 'lcd-theme'),
        'not_found'            => __('No meeting notes found', 'lcd-theme'),
        'not_found_in_trash'   => __('No meeting notes found in Trash', 'lcd-theme'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_in_menu'        => true,
        'show_ui'            => true,
        'menu_icon'           => 'dashicons-clipboard',
        'supports'            => array('editor', 'author', 'revisions'),
        'menu_position'       => 5,
        'show_in_rest'        => true,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
    );

    register_post_type('meeting_notes', $args);

    // Register Meeting Type Taxonomy
    if (!taxonomy_exists('meeting_type')) {
        register_taxonomy('meeting_type', 'meeting_notes', array(
            'label'              => __('Meeting Type', 'lcd-theme'),
            'hierarchical'       => true,
            'show_in_rest'       => true,
            'show_admin_column'  => true,
            'rewrite'            => array('slug' => 'meeting-type'),
            'meta_box_cb'        => 'lcd_meeting_type_meta_box',
        ));
    }

    // Register Meeting Locations Taxonomy
    if (!taxonomy_exists('meeting_location')) {
        register_taxonomy('meeting_location', 'meeting_notes', array(
            'label'              => __('Meeting Locations', 'lcd-theme'),
            'hierarchical'       => true,
            'show_in_rest'       => true,
            'show_admin_column'  => true,
            'rewrite'            => array('slug' => 'meeting-location'),
            'show_in_menu'       => true,
        ));
    }
}
add_action('init', 'lcd_register_meeting_notes_post_type');

/**
 * Add Meeting Location term meta fields
 */
function lcd_register_location_meta() {
    register_meta('term', 'location_address', array(
        'type' => 'string',
        'description' => 'Meeting location address',
        'single' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'lcd_register_location_meta');

/**
 * Add fields to Meeting Location taxonomy
 */
function lcd_meeting_location_add_form_fields() {
    ?>
    <div class="form-field">
        <label for="location_address"><?php _e('Address', 'lcd-theme'); ?></label>
        <textarea name="location_address" id="location_address" rows="3"></textarea>
        <p class="description"><?php _e('Full address of the meeting location', 'lcd-theme'); ?></p>
    </div>
    <?php
}
add_action('meeting_location_add_form_fields', 'lcd_meeting_location_add_form_fields');

/**
 * Edit fields for Meeting Location taxonomy
 */
function lcd_meeting_location_edit_form_fields($term) {
    $address = get_term_meta($term->term_id, 'location_address', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="location_address"><?php _e('Address', 'lcd-theme'); ?></label>
        </th>
        <td>
            <textarea name="location_address" id="location_address" rows="3"><?php echo esc_textarea($address); ?></textarea>
            <p class="description"><?php _e('Full address of the meeting location', 'lcd-theme'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('meeting_location_edit_form_fields', 'lcd_meeting_location_edit_form_fields');

/**
 * Save Meeting Location term meta
 */
function lcd_save_location_meta($term_id) {
    if (isset($_POST['location_address'])) {
        update_term_meta(
            $term_id,
            'location_address',
            sanitize_textarea_field($_POST['location_address'])
        );
    }
}
add_action('created_meeting_location', 'lcd_save_location_meta');
add_action('edited_meeting_location', 'lcd_save_location_meta');

/**
 * Add default meeting locations on theme activation
 */
function lcd_add_default_meeting_locations() {
    if (!term_exists('LCDCC Office', 'meeting_location')) {
        $term = wp_insert_term('LCDCC Office', 'meeting_location');
        if (!is_wp_error($term)) {
            update_term_meta($term['term_id'], 'location_address', "123 Main Street\nChehalis, WA 98532");
        }
    }
}
add_action('after_switch_theme', 'lcd_add_default_meeting_locations');

/**
 * Custom meta box for Meeting Type to ensure single selection
 */
function lcd_meeting_type_meta_box($post, $box) {
    $taxonomy = 'meeting_type';
    $selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
    $selected_id = !empty($selected) ? $selected[0] : 0;
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
    ?>
    <div class="meeting-type-selector">
        <select name="tax_input[meeting_type][]" id="meeting_type" class="widefat" required>
            <option value=""><?php _e('Select Meeting Type', 'lcd-theme'); ?></option>
            <?php foreach ($terms as $term): ?>
                <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($selected_id, $term->term_id); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

/**
 * Add meta boxes for Meeting Notes
 */
function lcd_add_meeting_notes_meta_boxes() {
    // Add title field first
    add_meta_box(
        'meeting_title',
        __('Meeting Title', 'lcd-theme'),
        'lcd_title_field_callback',
        'meeting_notes',
        'normal',
        'high'
    );

    // Add meeting details
    add_meta_box(
        'meeting_details',
        __('Meeting Details', 'lcd-theme'),
        'lcd_meeting_details_callback',
        'meeting_notes',
        'normal',
        'high'
    );

    // Add export panel
    add_meta_box(
        'meeting_export',
        __('Export Options', 'lcd-theme'),
        'lcd_meeting_export_callback',
        'meeting_notes',
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'lcd_add_meeting_notes_meta_boxes');

/**
 * Export panel meta box callback
 */
function lcd_meeting_export_callback($post) {
    // Add nonce for security
    wp_nonce_field('lcd_meeting_export_nonce', 'meeting_export_nonce');
    ?>
    <div class="meeting-export-options">
        <div class="export-option">
            <button type="button" class="button button-primary" onclick="lcdExportToPDF(<?php echo $post->ID; ?>)">
                <span class="dashicons dashicons-pdf"></span>
                <?php _e('Export to PDF', 'lcd-theme'); ?>
            </button>
            <p class="description"><?php _e('Generate a formatted PDF of these meeting notes', 'lcd-theme'); ?></p>
        </div>

        <div class="export-option" style="margin-top: 15px;">
            <button type="button" class="button" onclick="lcdGenerateEmail(<?php echo $post->ID; ?>)">
                <span class="dashicons dashicons-email"></span>
                <?php _e('Generate Email', 'lcd-theme'); ?>
            </button>
            <p class="description"><?php _e('Create an email draft with meeting notes', 'lcd-theme'); ?></p>
        </div>

        <div id="lcd-export-modal" class="lcd-modal" style="display: none;">
            <div class="lcd-modal-content">
                <span class="lcd-modal-close">&times;</span>
                <h3><?php _e('Email Preview', 'lcd-theme'); ?></h3>
                <div class="email-preview">
                    <p><strong><?php _e('Subject:', 'lcd-theme'); ?></strong> <span id="email-subject"></span></p>
                    <div id="email-body"></div>
                </div>
                <div class="email-actions">
                    <button type="button" class="button button-primary" id="copy-email">
                        <?php _e('Copy to Clipboard', 'lcd-theme'); ?>
                    </button>
                    <button type="button" class="button" id="send-email">
                        <?php _e('Send Email', 'lcd-theme'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function lcdExportToPDF(postId) {
        // Create form and submit it to our PDF endpoint
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
        form.target = '_blank';

        const data = {
            action: 'lcd_export_meeting_pdf',
            post_id: postId,
            nonce: '<?php echo wp_create_nonce('lcd_export_pdf'); ?>'
        };

        for (const key in data) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    function lcdGenerateEmail(postId) {
        const modal = document.getElementById('lcd-export-modal');
        const closeBtn = document.querySelector('.lcd-modal-close');
        const copyBtn = document.getElementById('copy-email');
        const sendBtn = document.getElementById('send-email');

        // Fetch email content
        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'lcd_generate_meeting_email',
                post_id: postId,
                nonce: '<?php echo wp_create_nonce('lcd_generate_email'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('email-subject').textContent = data.data.subject;
                document.getElementById('email-body').innerHTML = data.data.body;
                modal.style.display = 'block';
            }
        });

        // Handle modal close
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Handle copy to clipboard
        copyBtn.onclick = function() {
            const subject = document.getElementById('email-subject').textContent;
            const body = document.getElementById('email-body').innerText;
            const fullText = `Subject: ${subject}\n\n${body}`;
            
            navigator.clipboard.writeText(fullText).then(() => {
                copyBtn.textContent = '✓ Copied!';
                setTimeout(() => {
                    copyBtn.textContent = '<?php _e('Copy to Clipboard', 'lcd-theme'); ?>';
                }, 2000);
            });
        }

        // Handle send email (mock for now)
        sendBtn.onclick = function() {
            alert('Email sending feature will be implemented soon!');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    }
    </script>
    <?php
}

/**
 * Handle PDF export
 */
function lcd_handle_pdf_export() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lcd_export_pdf')) {
        wp_die('Invalid nonce');
    }

    if (!isset($_POST['post_id'])) {
        wp_die('No post ID provided');
    }

    $post_id = intval($_POST['post_id']);
    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'meeting_notes') {
        wp_die('Invalid post');
    }

    // Get meeting details
    $meeting_date = get_post_meta($post_id, '_meeting_date', true);
    $meeting_location = get_post_meta($post_id, '_meeting_location', true);
    $attendees = get_post_meta($post_id, '_attendees', true);
    $action_items = get_post_meta($post_id, '_action_items', true);
    $meeting_types = wp_get_object_terms($post_id, 'meeting_type');
    $meeting_type = !empty($meeting_types) ? $meeting_types[0]->name : 'Unknown';

    // TODO: Implement actual PDF generation
    // For now, we'll just output the content in a printable format
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo esc_html($post->post_title); ?></title>
    </head>
    <body>
        <div class="no-print" style="margin-bottom: 20px;">
            <button onclick="window.print()"><?php _e('Print/Save as PDF', 'lcd-theme'); ?></button>
        </div>

        <h1><?php echo esc_html($post->post_title); ?></h1>

        <div class="meeting-meta">
            <p><strong><?php _e('Date:', 'lcd-theme'); ?></strong> <?php echo esc_html(date('F j, Y', strtotime($meeting_date))); ?></p>
            <p><strong><?php _e('Location:', 'lcd-theme'); ?></strong> <?php echo esc_html($meeting_location); ?></p>
            <p><strong><?php _e('Type:', 'lcd-theme'); ?></strong> <?php echo esc_html($meeting_type); ?></p>
            <p><strong><?php _e('Attendees:', 'lcd-theme'); ?></strong></p>
            <pre><?php echo esc_html($attendees); ?></pre>
        </div>

        <div class="meeting-content">
            <?php echo wp_kses_post($post->post_content); ?>
        </div>

        <?php if (!empty($action_items)): ?>
        <div class="action-items">
            <h2><?php _e('Action Items', 'lcd-theme'); ?></h2>
            <pre><?php echo esc_html($action_items); ?></pre>
        </div>
        <?php endif; ?>
    </body>
    </html>
    <?php
    exit;
}
add_action('wp_ajax_lcd_export_meeting_pdf', 'lcd_handle_pdf_export');

/**
 * Handle email generation
 */
function lcd_handle_email_generation() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lcd_generate_email')) {
        wp_send_json_error('Invalid nonce');
    }

    if (!isset($_POST['post_id'])) {
        wp_send_json_error('No post ID provided');
    }

    $post_id = intval($_POST['post_id']);
    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'meeting_notes') {
        wp_send_json_error('Invalid post');
    }

    // Get meeting details
    $meeting_date = get_post_meta($post_id, '_meeting_date', true);
    $meeting_location = get_post_meta($post_id, '_meeting_location', true);
    $attendees = get_post_meta($post_id, '_attendees', true);
    $action_items = get_post_meta($post_id, '_action_items', true);

    // Generate email content
    $subject = $post->post_title;
    $body = sprintf(
        "Meeting Notes: %s\n\n" .
        "Date: %s\n" .
        "Location: %s\n\n" .
        "Attendees:\n%s\n\n" .
        "Meeting Notes:\n%s\n\n",
        esc_html($post->post_title),
        esc_html(date('F j, Y', strtotime($meeting_date))),
        esc_html($meeting_location),
        esc_html($attendees),
        wp_strip_all_tags($post->post_content)
    );

    if (!empty($action_items)) {
        $body .= sprintf(
            "Action Items:\n%s\n",
            esc_html($action_items)
        );
    }

    wp_send_json_success(array(
        'subject' => $subject,
        'body' => nl2br($body)
    ));
}
add_action('wp_ajax_lcd_generate_meeting_email', 'lcd_handle_email_generation');

/**
 * Callback for title field meta box
 */
function lcd_title_field_callback($post) {
    ?>
    <input type="text" id="title" name="post_title" value="<?php echo esc_attr($post->post_title); ?>" readonly="readonly" class="widefat">
    <p class="description"><?php _e('Title is automatically generated from Meeting Type and Date', 'lcd-theme'); ?></p>
    <?php
}

/**
 * Auto-generate title when saving meeting note
 */
function lcd_auto_generate_meeting_title($post_id) {
    static $is_updating = false;

    // Prevent infinite loops
    if ($is_updating) {
        return;
    }

    // Skip autosaves and revisions
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
        return;
    }
    
    // Only process meeting notes
    if (get_post_type($post_id) !== 'meeting_notes') {
        return;
    }

    // Verify user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Get meeting type
    $meeting_types = wp_get_object_terms($post_id, 'meeting_type');
    if (is_wp_error($meeting_types)) {
        return;
    }
    $meeting_type = !empty($meeting_types) ? $meeting_types[0]->name : 'Unknown';
    
    // Get meeting date
    $meeting_date = get_post_meta($post_id, '_meeting_date', true);
    if (empty($meeting_date)) {
        $meeting_date = current_time('Y-m-d');
    }
    
    // Format date
    $formatted_date = date('F j, Y', strtotime($meeting_date));
    
    // Generate title
    $title = sprintf('LCDCC %s Meeting - %s', $meeting_type, $formatted_date);
    
    // Update post title and slug
    $is_updating = true;
    wp_update_post(array(
        'ID' => $post_id,
        'post_title' => $title,
        'post_name' => sanitize_title($title)
    ));
    $is_updating = false;
}
add_action('save_post_meeting_notes', 'lcd_auto_generate_meeting_title', 20);

/**
 * Meeting Details meta box callback
 */
function lcd_meeting_details_callback($post) {
    wp_nonce_field('lcd_meeting_notes_nonce', 'meeting_notes_nonce');

    $meeting_date = get_post_meta($post->ID, '_meeting_date', true);
    if (empty($meeting_date)) {
        $meeting_date = current_time('Y-m-d');
    }
    
    // Get selected location
    $selected_location = wp_get_object_terms($post->ID, 'meeting_location', array('fields' => 'ids'));
    $selected_location_id = !empty($selected_location) ? $selected_location[0] : 0;
    
    // Get all locations
    $locations = get_terms(array(
        'taxonomy' => 'meeting_location',
        'hide_empty' => false,
    ));

    $attendees = get_post_meta($post->ID, '_attendees', true);
    $action_items = get_post_meta($post->ID, '_action_items', true);

    ?>
    <div class="meeting-notes-meta">
        <p>
            <label for="meeting_date"><?php _e('Meeting Date:', 'lcd-theme'); ?></label><br>
            <input type="date" id="meeting_date" name="meeting_date" value="<?php echo esc_attr($meeting_date); ?>" class="widefat" required>
        </p>
        <p>
            <label for="meeting_location"><?php _e('Meeting Location:', 'lcd-theme'); ?></label><br>
            <select name="tax_input[meeting_location][]" id="meeting_location" class="widefat">
                <option value=""><?php _e('Select Location', 'lcd-theme'); ?></option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo esc_attr($location->term_id); ?>" 
                            <?php selected($selected_location_id, $location->term_id); ?>
                            data-address="<?php echo esc_attr(get_term_meta($location->term_id, 'location_address', true)); ?>">
                        <?php echo esc_html($location->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">
                <span class="location-address"></span>
                <?php if (!empty($locations)): ?>
                    <br>
                <?php endif; ?>
                <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=meeting_location&post_type=meeting_notes')); ?>" 
                   target="_blank"><?php _e('Add New Location', 'lcd-theme'); ?></a>
            </p>
        </p>
        <p>
            <label for="attendees"><?php _e('Attendees:', 'lcd-theme'); ?></label><br>
            <textarea id="attendees" name="attendees" rows="3" class="widefat"><?php echo esc_textarea($attendees); ?></textarea>
            <span class="description"><?php _e('Enter attendee names, one per line or comma-separated', 'lcd-theme'); ?></span>
        </p>
        <p>
            <label for="action_items"><?php _e('Action Items:', 'lcd-theme'); ?></label><br>
            <textarea id="action_items" name="action_items" rows="5" class="widefat"><?php echo esc_textarea($action_items); ?></textarea>
            <span class="description"><?php _e('Enter action items, one per line', 'lcd-theme'); ?></span>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function updateLocationAddress() {
            var selected = $('#meeting_location option:selected');
            var address = selected.data('address');
            if (address) {
                $('.location-address').html(address.replace(/\n/g, '<br>'));
            } else {
                $('.location-address').empty();
            }
        }

        $('#meeting_location').on('change', updateLocationAddress);
        updateLocationAddress(); // Run on page load
    });
    </script>
    <?php
}

/**
 * Save Meeting Notes meta box data
 */
function lcd_save_meeting_notes_meta($post_id) {
    if (!isset($_POST['meeting_notes_nonce']) || !wp_verify_nonce($_POST['meeting_notes_nonce'], 'lcd_meeting_notes_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['meeting_date'])) {
        update_post_meta($post_id, '_meeting_date', sanitize_text_field($_POST['meeting_date']));
    }

    if (isset($_POST['attendees'])) {
        update_post_meta($post_id, '_attendees', sanitize_textarea_field($_POST['attendees']));
    }

    if (isset($_POST['action_items'])) {
        update_post_meta($post_id, '_action_items', sanitize_textarea_field($_POST['action_items']));
    }
}
add_action('save_post_meeting_notes', 'lcd_save_meeting_notes_meta');

/**
 * AJAX handler for loading section fields
 */
function lcd_load_section_fields() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lcd_homepage_builder_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check permissions
    if (!current_user_can('edit_pages')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Get section type
    $section_type = isset($_POST['section_type']) ? sanitize_text_field($_POST['section_type']) : '';
    if (!$section_type) {
        wp_send_json_error('No section type specified');
    }

    // Get post ID if it exists
    $post_id = isset($_POST['post_ID']) ? intval($_POST['post_ID']) : 0;
    
    // Get existing content if post exists
    $section_content = array();
    if ($post_id) {
        $section_content = get_post_meta($post_id, '_section_content', true);
    }

    // Start output buffering
    ob_start();

    // Render appropriate fields based on section type
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

    // Get the buffered content
    $content = ob_get_clean();

    wp_send_json_success($content);
}
add_action('wp_ajax_lcd_load_section_fields', 'lcd_load_section_fields');

/**
 * Enqueue homepage builder scripts
 */
function lcd_enqueue_homepage_builder_scripts($hook) {
    global $post_type;
    
    if ($post_type === 'homepage_section') {
        wp_enqueue_script(
            'lcd-homepage-builder',
            get_template_directory_uri() . '/js/homepage-builder-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('lcd-homepage-builder', 'lcdHomepageBuilder', array(
            'nonce' => wp_create_nonce('lcd_homepage_builder_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('admin_enqueue_scripts', 'lcd_enqueue_homepage_builder_scripts');

/**
 * Check if current page is blog index
 */
function lcd_is_blog_page() {
    // Get current URL path
    $current_url = $_SERVER['REQUEST_URI'];
    $path = parse_url($current_url, PHP_URL_PATH);
    
    // Get the last segment after final slash
    $segments = explode('/', trim($path, '/'));
    $last_segment = end($segments);
    
    // Check if the last segment is exactly 'blog'
    return $last_segment === 'blog';
}

/**
 * Modify page title for blog index
 */
function lcd_modify_blog_title($title) {
    if (lcd_is_blog_page()) {
        $blog_title = __('Blog', 'lcd-theme');
        $site_name = get_bloginfo('name');
        return sprintf('%s - %s', $blog_title, $site_name);
    }
    return $title;
}
add_filter('pre_get_document_title', 'lcd_modify_blog_title');

/**
 * Add meta description for blog index
 */
function lcd_add_blog_meta() {
    if (lcd_is_blog_page()) {
        echo '<meta name="description" content="' . esc_attr__('Stay informed with the latest news, updates, and insights from the Lewis County Democrats.', 'lcd-theme') . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr__('Blog - Lewis County Democrats', 'lcd-theme') . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr__('Stay informed with the latest news, updates, and insights from the Lewis County Democrats.', 'lcd-theme') . '">' . "\n";
        echo '<meta property="og:type" content="blog">' . "\n";
        
        // Add canonical URL to prevent duplicate content issues
        $blog_url = home_url('/blog/');
        echo '<link rel="canonical" href="' . esc_url($blog_url) . '">' . "\n";
    }
}
add_action('wp_head', 'lcd_add_blog_meta', 1);

/**
 * Add blog to Yoast SEO breadcrumbs if installed
 */
function lcd_modify_breadcrumbs($links) {
    if (lcd_is_blog_page()) {
        $new_links = array();
        $new_links[] = array(
            'text' => __('Home', 'lcd-theme'),
            'url' => home_url('/'),
            'allow_html' => false
        );
        $new_links[] = array(
            'text' => __('Blog', 'lcd-theme'),
            'url' => home_url('/blog/'),
            'allow_html' => false
        );
        return $new_links;
    }
    return $links;
}
add_filter('wpseo_breadcrumb_links', 'lcd_modify_breadcrumbs');

  