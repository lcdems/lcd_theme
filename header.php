<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e('Skip to content', 'lcd-theme'); ?>
    </a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-inner">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        // Get the custom logo URL
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $custom_logo_url = wp_get_attachment_image_src($custom_logo_id, 'full');
                        
                        // Get the scrolled logo URL
                        $scrolled_logo_url = get_theme_mod('lcd_scrolled_logo');
                        
                        // Output both logos
                        echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">';
                        echo '<img src="' . esc_url($custom_logo_url[0]) . '" alt="' . get_bloginfo('name') . '" class="custom-logo default-logo">';
                        if ($scrolled_logo_url) {
                            echo '<img src="' . esc_url($scrolled_logo_url) . '" alt="' . get_bloginfo('name') . '" class="custom-logo scrolled-logo">';
                        }
                        echo '</a>';
                    else :
                    ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php
                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) :
                        ?>
                            <p class="site-description"><?php echo $description; ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php
                // Add Max Mega Menu
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ));
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
</body>
</html>