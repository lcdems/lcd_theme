<?php
/**
 * The template for displaying the footer
 *
 * @package LCD_Theme
 */
?>

<footer class="site-footer">
    <div class="container">
        <div class="footer-widgets">
            <?php 
            if (is_active_sidebar('footer-widgets')) {
                dynamic_sidebar('footer-widgets');
            } else {
                // Default content if widget area is empty
                ?>
                <div class="widget">
                    <h3 class="widget-title"><?php esc_html_e('Footer Widgets', 'lcd-theme'); ?></h3>
                    <p><?php esc_html_e('Add widgets to the footer area. They will be displayed horizontally.', 'lcd-theme'); ?></p>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="site-info">
            <p><?php 
                $copyright = get_theme_mod('lcd_footer_copyright', '© {year} Lewis County Democrats. All rights reserved.');
                echo wp_kses_post(str_replace('{year}', date('Y'), $copyright));
            ?></p>
            <?php
            if (has_nav_menu('footer-secondary')) {
                wp_nav_menu(array(
                    'theme_location' => 'footer-secondary',
                    'menu_class'     => 'footer-menu-secondary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ));
            }
            ?>
        </div>
    </div>
</footer>

<button class="back-to-top" aria-label="Back to top">
    <span class="screen-reader-text">Back to top</span>
</button>

<?php wp_footer(); ?> 