<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package LCD_Theme
 */

get_header();

// If this is the main homepage URL
if (is_front_page() && is_home() && !is_paged()) {
    get_template_part('template-parts/content', 'front-page');
} 
// If this is the blog page or any other archive
else {
    get_template_part('template-parts/content', 'blog');
}

get_footer();
?> 