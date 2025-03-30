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

    // Enqueue Tax Calculator CSS and JS if we're on the tax calculator page template
    if (is_page_template('page-tax-calculator.php')) {
        wp_enqueue_style(
            'lcd-tax-calculator-style',
            get_template_directory_uri() . '/css/tax-calculator.css',
            array(),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'lcd-tax-calculator-script',
            get_template_directory_uri() . '/js/tax-calculator.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Enqueue styles
    wp_enqueue_style('lcd-theme-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

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

/**
 * Register Events Post Type
 */
function lcd_register_events_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'Post type general name', 'lcd-theme'),
        'singular_name'         => _x('Event', 'Post type singular name', 'lcd-theme'),
        'menu_name'            => _x('Events', 'Admin Menu text', 'lcd-theme'),
        'add_new'              => __('Add New', 'lcd-theme'),
        'add_new_item'         => __('Add New Event', 'lcd-theme'),
        'edit_item'            => __('Edit Event', 'lcd-theme'),
        'new_item'             => __('New Event', 'lcd-theme'),
        'view_item'            => __('View Event', 'lcd-theme'),
        'search_items'         => __('Search Events', 'lcd-theme'),
        'not_found'            => __('No events found', 'lcd-theme'),
        'not_found_in_trash'   => __('No events found in Trash', 'lcd-theme'),
        'all_items'            => __('All Events', 'lcd-theme'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'events'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('event', $args);
}
add_action('init', 'lcd_register_events_post_type');

/**
 * Add Event Meta Boxes
 */
function lcd_add_event_meta_boxes() {
    add_meta_box(
        'event_details',
        __('Event Details', 'lcd-theme'),
        'lcd_event_details_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'lcd_add_event_meta_boxes');

/**
 * Event Details Meta Box Callback
 */
function lcd_event_details_callback($post) {
    wp_nonce_field('lcd_event_details', 'lcd_event_details_nonce');

    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_time = get_post_meta($post->ID, '_event_time', true);
    $event_end_time = get_post_meta($post->ID, '_event_end_time', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    $event_address = get_post_meta($post->ID, '_event_address', true);
    $event_map_link = get_post_meta($post->ID, '_event_map_link', true);
    $event_registration_url = get_post_meta($post->ID, '_event_registration_url', true);
    $event_capacity = get_post_meta($post->ID, '_event_capacity', true);
    $event_cost = get_post_meta($post->ID, '_event_cost', true);
    $event_poster_id = get_post_meta($post->ID, '_event_poster', true);
    $event_button_text = get_post_meta($post->ID, '_event_button_text', true);
    $event_ticketing_notes = get_post_meta($post->ID, '_event_ticketing_notes', true);
    ?>
    <div class="lcd-event-meta">
        <p>
            <label for="event_date"><?php _e('Event Date:', 'lcd-theme'); ?></label><br>
            <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
        </p>
        <p>
            <label for="event_time"><?php _e('Start Time:', 'lcd-theme'); ?></label><br>
            <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>" class="widefat">
        </p>
        <p>
            <label for="event_end_time"><?php _e('End Time:', 'lcd-theme'); ?></label><br>
            <input type="time" id="event_end_time" name="event_end_time" value="<?php echo esc_attr($event_end_time); ?>" class="widefat">
        </p>
        <p>
            <label for="event_location"><?php _e('Location Name:', 'lcd-theme'); ?></label><br>
            <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" class="widefat">
        </p>
        <p>
            <label for="event_address"><?php _e('Address:', 'lcd-theme'); ?></label><br>
            <textarea id="event_address" name="event_address" class="widefat" rows="3"><?php echo esc_textarea($event_address); ?></textarea>
        </p>
        <p>
            <label for="event_map_link"><?php _e('Map Link:', 'lcd-theme'); ?></label><br>
            <input type="url" id="event_map_link" name="event_map_link" value="<?php echo esc_url($event_map_link); ?>" class="widefat">
        </p>
        <p>
            <label for="event_registration_url"><?php _e('Registration URL:', 'lcd-theme'); ?></label><br>
            <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url($event_registration_url); ?>" class="widefat">
        </p>
        <p>
            <label for="event_button_text"><?php _e('Registration Button Text:', 'lcd-theme'); ?></label><br>
            <input type="text" id="event_button_text" name="event_button_text" value="<?php echo esc_attr($event_button_text); ?>" class="widefat" placeholder="Register Now">
        </p>
        <p>
            <label for="event_ticketing_notes"><?php _e('Ticketing Notes:', 'lcd-theme'); ?></label><br>
            <textarea id="event_ticketing_notes" name="event_ticketing_notes" class="widefat" rows="3" placeholder="Enter any additional notes about registration or ticketing"><?php echo esc_textarea($event_ticketing_notes); ?></textarea>
            <span class="description"><?php _e('This text will appear below the registration button.', 'lcd-theme'); ?></span>
        </p>
        <p>
            <label for="event_capacity"><?php _e('Capacity:', 'lcd-theme'); ?></label><br>
            <input type="number" id="event_capacity" name="event_capacity" value="<?php echo esc_attr($event_capacity); ?>" class="widefat">
        </p>
        <p>
            <label for="event_cost"><?php _e('Event Cost:', 'lcd-theme'); ?></label><br>
            <input type="text" id="event_cost" name="event_cost" value="<?php echo esc_attr($event_cost); ?>" class="widefat" placeholder="Free or enter amount">
        </p>
        <p>
            <label><?php _e('Event Poster:', 'lcd-theme'); ?></label><br>
            <div class="event-poster-preview" style="margin-bottom: 10px;">
                <?php if ($event_poster_id) : ?>
                    <?php echo wp_get_attachment_image($event_poster_id, 'medium'); ?>
                <?php endif; ?>
            </div>
            <input type="hidden" id="event_poster" name="event_poster" value="<?php echo esc_attr($event_poster_id); ?>">
            <button type="button" class="button event-poster-upload"><?php _e('Upload Poster', 'lcd-theme'); ?></button>
            <?php if ($event_poster_id) : ?>
                <button type="button" class="button event-poster-remove"><?php _e('Remove Poster', 'lcd-theme'); ?></button>
            <?php endif; ?>
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var frame;
        $('.event-poster-upload').on('click', function(e) {
            e.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: '<?php _e('Select or Upload Event Poster', 'lcd-theme'); ?>',
                button: {
                    text: '<?php _e('Use this image', 'lcd-theme'); ?>'
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#event_poster').val(attachment.id);
                $('.event-poster-preview').html('<img src="' + attachment.sizes.medium.url + '" style="max-width: 100%; height: auto;">');
                $('.event-poster-remove').show();
            });

            frame.open();
        });

        $('.event-poster-remove').on('click', function(e) {
            e.preventDefault();
            $('#event_poster').val('');
            $('.event-poster-preview').empty();
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Save Event Meta Box Data
 */
function lcd_save_event_meta($post_id) {
    if (!isset($_POST['lcd_event_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['lcd_event_details_nonce'], 'lcd_event_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'event_date',
        'event_time',
        'event_end_time',
        'event_location',
        'event_address',
        'event_map_link',
        'event_registration_url',
        'event_capacity',
        'event_cost',
        'event_poster',
        'event_button_text',
        'event_ticketing_notes'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post_event', 'lcd_save_event_meta');

/**
 * Add custom columns to events list
 */
function lcd_event_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['event_date'] = __('Event Date', 'lcd-theme');
    $new_columns['event_location'] = __('Location', 'lcd-theme');
    $new_columns['event_cost'] = __('Cost', 'lcd-theme');
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_event_posts_columns', 'lcd_event_columns');

/**
 * Add content to custom columns
 */
function lcd_event_column_content($column, $post_id) {
    switch ($column) {
        case 'event_date':
            $date = get_post_meta($post_id, '_event_date', true);
            echo $date ? date_i18n(get_option('date_format'), strtotime($date)) : '—';
            break;
        case 'event_location':
            echo get_post_meta($post_id, '_event_location', true) ?: '—';
            break;
        case 'event_cost':
            echo get_post_meta($post_id, '_event_cost', true) ?: '—';
            break;
    }
}
add_action('manage_event_posts_custom_column', 'lcd_event_column_content', 10, 2);

/**
 * Make event date column sortable
 */
function lcd_event_sortable_columns($columns) {
    $columns['event_date'] = 'event_date';
    return $columns;
}
add_filter('manage_edit-event_sortable_columns', 'lcd_event_sortable_columns');

/**
 * Sort events by date
 */
function lcd_event_sort_by_date($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') === 'event' && $query->get('orderby') === 'event_date') {
        $query->set('meta_key', '_event_date');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'lcd_event_sort_by_date');

  