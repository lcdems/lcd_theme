<?php
/**
 * LCD Theme Customizer
 *
 * @package LCD_Theme
 */

// Only define custom controls when in customizer context
if (class_exists('WP_Customize_Control')) {
    // Add custom separator control class
    class LCD_Separator_Control extends WP_Customize_Control {
        public function render_content() {
            echo '<hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">';
        }
    }
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function lcd_customize_register($wp_customize) {
    // Remove default static front page description since we're adding our own controls
    $wp_customize->get_section('static_front_page')->description = '';

    // Add Blog Settings section
    $wp_customize->add_section('lcd_blog_options', array(
        'title'       => __('Blog Settings', 'lcd-theme'),
        'priority'    => 120,
        'description' => __('Customize the appearance of your blog posts and archives.', 'lcd-theme'),
    ));

    // Add default post header image setting
    $wp_customize->add_setting('lcd_default_post_header', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    // Add default post header image control
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'lcd_default_post_header', array(
        'label'       => __('Blog Header Image', 'lcd-theme'),
        'description' => __('Upload a header image for the blog page. Recommended size: 1920x400 pixels.', 'lcd-theme'),
        'section'     => 'lcd_blog_options',
        'settings'    => 'lcd_default_post_header',
        'priority'    => 10,
    )));

    // Add banner position setting
    $wp_customize->add_setting('lcd_blog_banner_position', array(
        'default'           => 'center center',
        'sanitize_callback' => 'lcd_sanitize_select',
        'transport'         => 'postMessage',
    ));

    // Add banner position control
    $wp_customize->add_control('lcd_blog_banner_position', array(
        'label'       => __('Banner Image Position', 'lcd-theme'),
        'description' => __('Choose how to position the banner image.', 'lcd-theme'),
        'section'     => 'lcd_blog_options',
        'type'        => 'select',
        'choices'     => array(
            'center center' => __('Center (default)', 'lcd-theme'),
            'top center'    => __('Top', 'lcd-theme'),
            'bottom center' => __('Bottom', 'lcd-theme'),
        ),
        'priority'    => 15,
    ));

    // Add default post header overlay color setting
    $wp_customize->add_setting('lcd_post_header_overlay_color', array(
        'default'           => '#002B50',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add default post header overlay color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'lcd_post_header_overlay_color', array(
        'label'       => __('Header Overlay Color', 'lcd-theme'),
        'description' => __('Choose the overlay color for the blog header.', 'lcd-theme'),
        'section'     => 'lcd_blog_options',
        'settings'    => 'lcd_post_header_overlay_color',
        'priority'    => 20,
    )));

    // Add default post header overlay opacity setting
    $wp_customize->add_setting('lcd_post_header_overlay_opacity', array(
        'default'           => 70,
        'sanitize_callback' => 'lcd_sanitize_opacity',
        'transport'         => 'postMessage',
    ));

    // Add default post header overlay opacity control
    $wp_customize->add_control('lcd_post_header_overlay_opacity', array(
        'label'       => __('Header Overlay Opacity', 'lcd-theme'),
        'description' => __('Adjust the transparency of the overlay (0 = fully transparent, 100 = fully opaque).', 'lcd-theme'),
        'section'     => 'lcd_blog_options',
        'type'        => 'range',
        'input_attrs' => array(
            'min'   => 0,
            'max'   => 100,
            'step'  => 5,
        ),
        'priority'    => 30,
    ));

    // Add Footer section
    $wp_customize->add_section('lcd_footer_options', array(
        'title'    => __('Footer', 'lcd-theme'),
        'priority' => 130,
    ));

    // Copyright Text
    $wp_customize->add_setting('lcd_footer_copyright', array(
        'default'           => '© {year} Lewis County Democrats. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('lcd_footer_copyright', array(
        'label'       => __('Copyright Text', 'lcd-theme'),
        'description' => __('Use {year} to dynamically insert the current year.', 'lcd-theme'),
        'section'     => 'lcd_footer_options',
        'type'        => 'text',
        'priority'    => 60,
    ));

    // Add banner image setting
    $wp_customize->add_setting('lcd_home_banner', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    // Add banner image control
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'lcd_home_banner', array(
        'label'       => __('Banner Background Image', 'lcd-theme'),
        'description' => __('Upload an image to replace the default blue background in the home page banner. Recommended size: 1920x400 pixels.', 'lcd-theme'),
        'section'     => 'static_front_page',
        'settings'    => 'lcd_home_banner',
        'priority'    => 20,
    )));

    // Add banner position setting
    $wp_customize->add_setting('lcd_banner_position', array(
        'default'           => 'center center',
        'sanitize_callback' => 'lcd_sanitize_select',
        'transport'         => 'refresh',
    ));

    // Add banner position control
    $wp_customize->add_control('lcd_banner_position', array(
        'label'       => __('Banner Image Position', 'lcd-theme'),
        'description' => __('Choose how to position the background image.', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'select',
        'choices'     => array(
            'center center' => __('Center (Default)', 'lcd-theme'),
            'top center'    => __('Top', 'lcd-theme'),
            'bottom center' => __('Bottom', 'lcd-theme'),
            'center left'   => __('Left', 'lcd-theme'),
            'center right'  => __('Right', 'lcd-theme'),
            'top left'      => __('Top Left', 'lcd-theme'),
            'top right'     => __('Top Right', 'lcd-theme'),
            'bottom left'   => __('Bottom Left', 'lcd-theme'),
            'bottom right'  => __('Bottom Right', 'lcd-theme'),
        ),
        'priority'    => 25,
    ));

    // Add banner overlay color setting
    $wp_customize->add_setting('lcd_banner_overlay_color', array(
        'default'           => '#0B57D0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));

    // Add banner overlay opacity setting
    $wp_customize->add_setting('lcd_banner_overlay_opacity', array(
        'default'           => 60,
        'sanitize_callback' => 'lcd_sanitize_opacity',
        'transport'         => 'refresh',
    ));

    // Add banner overlay color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'lcd_banner_overlay_color', array(
        'label'       => __('Banner Overlay Color', 'lcd-theme'),
        'description' => __('Choose a color for the banner overlay.', 'lcd-theme'),
        'section'     => 'static_front_page',
        'settings'    => 'lcd_banner_overlay_color',
        'priority'    => 30,
    )));

    // Add banner overlay opacity control
    $wp_customize->add_control('lcd_banner_overlay_opacity', array(
        'label'       => __('Banner Overlay Opacity', 'lcd-theme'),
        'description' => __('Adjust the transparency of the overlay (0 = fully transparent, 100 = fully opaque).', 'lcd-theme'),
        'section'     => 'static_front_page',
        'settings'    => 'lcd_banner_overlay_opacity',
        'type'        => 'range',
        'input_attrs' => array(
            'min'   => 0,
            'max'   => 100,
            'step'  => 5,
        ),
        'priority'    => 40,
    ));

    // Hero Section Settings
    $wp_customize->add_setting('lcd_hero_title', array(
        'default'           => 'Your Organization Name',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('lcd_hero_title', array(
        'label'       => __('Hero Title', 'lcd-theme'),
        'description' => __('The main heading text in the hero section.', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 15,
    ));

    $wp_customize->add_setting('lcd_hero_description', array(
        'default'           => 'Add your organization\'s tagline or brief description here.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('lcd_hero_description', array(
        'label'       => __('Hero Description', 'lcd-theme'),
        'description' => __('The subtitle text below the main heading.', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'textarea',
        'priority'    => 16,
    ));

    // Hero Buttons
    $wp_customize->add_setting('lcd_hero_button_1_text', array(
        'default'           => 'Primary Button',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_1_text', array(
        'label'       => __('Button 1 Text', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 17,
    ));

    $wp_customize->add_setting('lcd_hero_button_1_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('lcd_hero_button_1_url', array(
        'label'       => __('Button 1 URL', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'url',
        'priority'    => 18,
    ));

    $wp_customize->add_setting('lcd_hero_button_1_class', array(
        'default'           => 'button',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_1_class', array(
        'label'       => __('Button 1 Classes', 'lcd-theme'),
        'description' => __('Add classes separated by spaces (e.g., "button button-primary")', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 19,
    ));

    // Button 2
    $wp_customize->add_setting('lcd_hero_button_2_text', array(
        'default'           => 'Secondary Button',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_2_text', array(
        'label'       => __('Button 2 Text', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 20,
    ));

    $wp_customize->add_setting('lcd_hero_button_2_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('lcd_hero_button_2_url', array(
        'label'       => __('Button 2 URL', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'url',
        'priority'    => 21,
    ));

    $wp_customize->add_setting('lcd_hero_button_2_class', array(
        'default'           => 'button button-outline',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_2_class', array(
        'label'       => __('Button 2 Classes', 'lcd-theme'),
        'description' => __('Add classes separated by spaces (e.g., "button button-outline")', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 22,
    ));

    // Button 3 (Optional)
    $wp_customize->add_setting('lcd_hero_button_3_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_3_text', array(
        'label'       => __('Button 3 Text (Optional)', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 23,
    ));

    $wp_customize->add_setting('lcd_hero_button_3_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('lcd_hero_button_3_url', array(
        'label'       => __('Button 3 URL', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'url',
        'priority'    => 24,
    ));

    $wp_customize->add_setting('lcd_hero_button_3_class', array(
        'default'           => 'button',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lcd_hero_button_3_class', array(
        'label'       => __('Button 3 Classes', 'lcd-theme'),
        'description' => __('Add classes separated by spaces (e.g., "button")', 'lcd-theme'),
        'section'     => 'static_front_page',
        'type'        => 'text',
        'priority'    => 25,
    ));

    // Add scrolled logo setting
    $wp_customize->add_setting('lcd_scrolled_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    // Add scrolled logo control
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'lcd_scrolled_logo', array(
        'label'       => __('Scrolled Logo', 'lcd-theme'),
        'description' => __('Upload a logo to display when the header is sticky/scrolled. This should be a shorter version of your main logo.', 'lcd-theme'),
        'section'     => 'title_tagline',
        'settings'    => 'lcd_scrolled_logo',
        'priority'    => 9,
    )));

    // Add customizer preview script
    if ($wp_customize->is_preview() && !is_admin()) {
        add_action('wp_footer', 'lcd_customizer_preview_script');
    }
}
add_action('customize_register', 'lcd_customize_register');

/**
 * Sanitize select field
 */
function lcd_sanitize_select($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize opacity value
 */
function lcd_sanitize_opacity($input) {
    return absint(min(100, max(0, $input)));
}

/**
 * Get RGBA color for overlay
 */
function lcd_get_overlay_rgba($hex_color, $opacity) {
    list($r, $g, $b) = sscanf($hex_color, "#%02x%02x%02x");
    return "rgba($r, $g, $b, " . ($opacity / 100) . ")";
}

/**
 * Add live preview script for blog settings
 */
function lcd_customizer_preview_script() {
    ?>
    <script type="text/javascript">
        (function($) {
            // Live preview for header image
            wp.customize('lcd_default_post_header', function(value) {
                value.bind(function(newval) {
                    var header = $('.blog-index .entry-header');
                    if (newval) {
                        header.css('background-image', 'url(' + newval + ')');
                        header.addClass('has-featured-image');
                    } else {
                        header.css('background-image', '');
                        header.removeClass('has-featured-image');
                    }
                });
            });

            // Live preview for banner position
            wp.customize('lcd_blog_banner_position', function(value) {
                value.bind(function(newval) {
                    $('.blog-index .entry-header').css('background-position', newval);
                });
            });

            // Live preview for overlay color and opacity
            function updateOverlay() {
                var color = wp.customize('lcd_post_header_overlay_color').get();
                var opacity = wp.customize('lcd_post_header_overlay_opacity').get() / 100;
                var rgba = 'rgba(' + hexToRgb(color) + ',' + opacity + ')';
                document.documentElement.style.setProperty('--overlay-color', rgba);
            }

            wp.customize('lcd_post_header_overlay_color', function(value) {
                value.bind(updateOverlay);
            });

            wp.customize('lcd_post_header_overlay_opacity', function(value) {
                value.bind(updateOverlay);
            });

            // Helper function to convert hex to RGB
            function hexToRgb(hex) {
                hex = hex.replace('#', '');
                var bigint = parseInt(hex, 16);
                var r = (bigint >> 16) & 255;
                var g = (bigint >> 8) & 255;
                var b = bigint & 255;
                return r + ',' + g + ',' + b;
            }
        })(jQuery);
    </script>
    <?php
} 