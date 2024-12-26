<?php
/**
 * Template part for displaying front page content
 *
 * @package LCD_Theme
 */

// Get banner image and overlay settings
$banner_image = get_theme_mod('lcd_home_banner');
$banner_position = get_theme_mod('lcd_banner_position', 'center center');
$overlay_color = get_theme_mod('lcd_banner_overlay_color', '#0B57D0');
$overlay_opacity = get_theme_mod('lcd_banner_overlay_opacity', 60);

// Create inline styles for banner
$banner_style = '';
if ($banner_image) {
    $banner_style = 'background-image: url(' . esc_url($banner_image) . ');';
    $banner_style .= 'background-position: ' . esc_attr($banner_position) . ';';
}
$banner_style .= '--overlay-color: ' . esc_attr(lcd_get_overlay_rgba($overlay_color, $overlay_opacity)) . ';';
?>

<section class="hero-section" style="<?php echo esc_attr($banner_style); ?>">
    <div class="container">
        <h1 class="hero-title">
            <?php echo esc_html(get_theme_mod('lcd_hero_title', 'Your Organization Name')); ?>
        </h1>
        <p class="hero-description">
            <?php echo esc_html(get_theme_mod('lcd_hero_description', 'Add your organization\'s tagline or brief description here.')); ?>
        </p>
        
        <div class="hero-buttons">
            <?php
            // Button 1
            $button1_text = get_theme_mod('lcd_hero_button_1_text', 'Primary Button');
            $button1_url = get_theme_mod('lcd_hero_button_1_url', '#');
            $button1_class = get_theme_mod('lcd_hero_button_1_class', 'button');
            
            if ($button1_text) {
                printf(
                    '<a href="%s" class="%s">%s</a>',
                    esc_url($button1_url),
                    esc_attr($button1_class),
                    esc_html($button1_text)
                );
            }

            // Button 2
            $button2_text = get_theme_mod('lcd_hero_button_2_text', 'Secondary Button');
            $button2_url = get_theme_mod('lcd_hero_button_2_url', '#');
            $button2_class = get_theme_mod('lcd_hero_button_2_class', 'button button-outline');
            
            if ($button2_text) {
                printf(
                    '<a href="%s" class="%s">%s</a>',
                    esc_url($button2_url),
                    esc_attr($button2_class),
                    esc_html($button2_text)
                );
            }

            // Button 3 (Optional)
            $button3_text = get_theme_mod('lcd_hero_button_3_text');
            $button3_url = get_theme_mod('lcd_hero_button_3_url');
            $button3_class = get_theme_mod('lcd_hero_button_3_class', 'button');
            
            if ($button3_text) {
                printf(
                    '<a href="%s" class="%s">%s</a>',
                    esc_url($button3_url),
                    esc_attr($button3_class),
                    esc_html($button3_text)
                );
            }
            ?>
        </div>
    </div>
</section>

<?php
// Get all homepage sections ordered by section order
$homepage_sections = get_posts(array(
    'post_type' => 'homepage_section',
    'posts_per_page' => -1,
    'orderby' => 'meta_value_num',
    'meta_key' => '_section_order',
    'order' => 'ASC',
));

// Loop through each section and render it
foreach ($homepage_sections as $section) {
    $section_type = get_post_meta($section->ID, '_section_type', true);
    $section_content = get_post_meta($section->ID, '_section_content', true);

    switch ($section_type) {
        case 'three_card':
            ?>
            <div class="home-actions">
                <div class="container">
                    <div class="action-grid">
                        <?php
                        for ($i = 1; $i <= 3; $i++) {
                            $card = isset($section_content['card_' . $i]) ? $section_content['card_' . $i] : array();
                            $title = isset($card['title']) ? $card['title'] : '';
                            $content = isset($card['content']) ? $card['content'] : '';
                            $link = isset($card['link']) ? $card['link'] : '#';
                            $button_text = isset($card['button_text']) ? $card['button_text'] : __('Learn More', 'lcd-theme');
                            $icon = isset($card['icon']) ? $card['icon'] : '';
                            ?>
                            <div class="action-card">
                                <?php if ($icon): ?>
                                    <div class="action-icon">
                                        <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?> icon">
                                    </div>
                                <?php endif; ?>
                                <h2><?php echo esc_html($title); ?></h2>
                                <p><?php echo wp_kses_post($content); ?></p>
                                <a href="<?php echo esc_url($link); ?>" class="button">
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'widget_area':
            if (!empty($section_content['widget_area_id'])) {
                ?>
                <div class="home-widget-area">
                    <div class="container">
                        <?php dynamic_sidebar($section_content['widget_area_id']); ?>
                    </div>
                </div>
                <?php
            }
            break;

        case 'text':
            if (!empty($section_content['text'])) {
                ?>
                <div class="home-text-section">
                    <div class="container">
                        <?php echo wp_kses_post($section_content['text']); ?>
                    </div>
                </div>
                <?php
            }
            break;

        case 'html':
            if (!empty($section_content['html'])) {
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
                ?>
                <div class="home-html-section">
                    <div class="container">
                        <?php echo wp_kses($section_content['html'], $allowed_html); ?>
                    </div>
                </div>
                <?php
            }
            break;
    }
}
?> 