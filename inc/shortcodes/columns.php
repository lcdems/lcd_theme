<?php
/**
 * Column Shortcodes
 *
 * @package LCD_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Column Container Shortcode
 */
function lcd_cols_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'class' => '',
        'id' => '',
    ), $atts);

    $id_attr = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';
    $class_attr = ' class="lcd-cols' . (!empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '') . '"';

    return '<div' . $id_attr . $class_attr . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('lcd_cols', 'lcd_cols_shortcode');

/**
 * Individual Column Shortcode
 */
function lcd_col_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'width' => '',
        'class' => '',
        'id' => '',
    ), $atts);

    $id_attr = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';
    $classes = array('lcd-col');
    
    if (!empty($atts['class'])) {
        $classes[] = $atts['class'];
    }
    
    if (!empty($atts['width'])) {
        // Convert percentage to decimal for flex-basis
        $width = str_replace('%', '', $atts['width']);
        $width_style = ' style="flex-basis: ' . esc_attr($width) . '%; max-width: ' . esc_attr($width) . '%;"';
        $classes[] = 'lcd-col-custom-width';
    } else {
        $width_style = '';
    }

    $class_attr = ' class="' . esc_attr(implode(' ', $classes)) . '"';

    return '<div' . $id_attr . $class_attr . $width_style . '>' . do_shortcode($content) . '</div>';
}
add_shortcode('lcd_col', 'lcd_col_shortcode'); 