<?php
/**
 * YouTube Embed Shortcode
 *
 * @package LCD_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function lcd_youtube_embed_shortcode($atts) {
    $atts = shortcode_atts(array(
        'video_id' => '',
    ), $atts, 'youtube_embed');
    return '<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; margin-bottom: 2em; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class="embed-container"><iframe src="https://www.youtube.com/embed/' . esc_attr($atts['video_id']) . '" frameborder="0" allowfullscreen></iframe></div> ';
}
add_shortcode('youtube_embed', 'lcd_youtube_embed_shortcode');