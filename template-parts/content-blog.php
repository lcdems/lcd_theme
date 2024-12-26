<?php
/**
 * Template part for displaying blog index content
 *
 * @package LCD_Theme
 */

// Set up custom query for blog posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$blog_query = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => get_option('posts_per_page'),
    'paged' => $paged,
    'post_status' => 'publish'
));
?>

<div class="archive-template blog-index">
    <?php
    // Get header image and settings from customizer
    $header_image = get_theme_mod('lcd_default_post_header', '');
    $overlay_color = get_theme_mod('lcd_post_header_overlay_color', '#002B50');
    $overlay_opacity = get_theme_mod('lcd_post_header_overlay_opacity', 70);
    $banner_position = get_theme_mod('lcd_blog_banner_position', 'center center');
    
    // Create header style with overlay
    $header_style = array();
    if ($header_image) {
        $header_style[] = sprintf('background-image: url(%s)', esc_url($header_image));
        $header_style[] = sprintf('background-position: %s', esc_attr($banner_position));
        $header_style[] = 'background-size: cover';
        $header_style[] = 'background-repeat: no-repeat';
    }
    
    // Add overlay style
    $overlay_rgba = lcd_get_overlay_rgba($overlay_color, $overlay_opacity);
    $header_style[] = sprintf('--overlay-color: %s', $overlay_rgba);
    
    // Combine all styles
    $header_style = implode('; ', $header_style);
    ?>
    
    <header class="entry-header<?php echo $header_image ? ' has-featured-image' : ''; ?>"<?php echo $header_style ? ' style="' . esc_attr($header_style) . '"' : ''; ?>>
        <div class="entry-header-content">

            <h1 class="entry-title"><?php esc_html_e('Blog', 'lcd-theme'); ?></h1>
            <div class="archive-description">
                <?php echo esc_html__('Stay informed with the latest news, updates, and insights from the Lewis County Democrats.', 'lcd-theme'); ?>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if ($blog_query->have_posts()) : ?>
            <div class="posts-grid">
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <div class="post-meta">
                                <span class="posted-on"><?php echo get_the_date(); ?></span>
                                <?php
                                $categories_list = get_the_category_list(', ');
                                if ($categories_list) :
                                ?>
                                    <span class="cat-links"><?php echo $categories_list; ?></span>
                                <?php endif; ?>
                            </div>

                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Read More', 'lcd-theme'); ?>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            echo paginate_links(array(
                'total' => $blog_query->max_num_pages,
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . __('Previous', 'lcd-theme'),
                'next_text' => __('Next', 'lcd-theme') . ' <i class="fas fa-arrow-right"></i>',
                'mid_size'  => 2,
            ));
            ?>

        <?php else : ?>
            <div class="no-posts">
                <p><?php esc_html_e('No posts found.', 'lcd-theme'); ?></p>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); // Reset the custom query ?>
    </div>
</div>