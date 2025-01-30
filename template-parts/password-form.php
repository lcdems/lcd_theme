<?php
/**
 * Template part for displaying the password protection form
 *
 * @package LCD_Theme
 */

if (!defined('ABSPATH')) exit;
?>

<div class="post-password-form-wrapper">
    <div class="password-form-content">
        <i class="fas fa-lock"></i>
        <h2><?php esc_html_e('Password Required', 'lcd-theme'); ?></h2>
        <?php echo get_the_password_form(); ?>
    </div>
</div> 