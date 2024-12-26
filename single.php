<?php

/**
 * The template for displaying all single posts
 *
 * @package LCD_Theme
 */

get_header();
?>

<main id="primary" class="site-main single-post-template">
    <?php while (have_posts()) : the_post();
        // Get header image - use featured image, fallback to default post header
        $header_image = '';
        if (has_post_thumbnail()) {
            $header_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        } else {
            $header_image = get_theme_mod('lcd_default_post_header', '');
        }

        // Get overlay settings
        $overlay_color = get_theme_mod('lcd_post_header_overlay_color', '#002B50');
        $overlay_opacity = get_theme_mod('lcd_post_header_overlay_opacity', 70);

        // Create header style
        $header_style = '';
        if ($header_image) {
            $header_style .= 'background-image: url(' . esc_url($header_image) . ');';
        }
        $header_style .= '--overlay-color: ' . esc_attr(lcd_get_overlay_rgba($overlay_color, $overlay_opacity)) . ';';
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
            <header class="entry-header<?php echo $header_image ? ' has-featured-image' : ''; ?>" style="<?php echo esc_attr($header_style); ?>">
                <div class="container">
                    <div class="entry-header-content">
                        <a href="<?php echo esc_url(get_bloginfo('url') . '/blog'); ?>" class="back-to-blog">
                            <i class="fas fa-arrow-left"></i>
                            <?php esc_html_e('Back to Blog', 'lcd-theme'); ?>
                        </a>

                        <div class="entry-meta">
                            <span class="posted-on">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="byline">
                                <?php echo esc_html__('by', 'lcd-theme'); ?> <?php the_author(); ?>
                            </span>
                            <?php
                            $categories_list = get_the_category_list(', ');
                            if ($categories_list) :
                            ?>
                                <span class="cat-links">
                                    <?php echo $categories_list; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </div>
                </div>
            </header>

            <div class="container">
                <div class="content-wrapper">
                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'lcd-theme'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <footer class="entry-footer">
                        <?php
                        $tags_list = get_the_tag_list('', ', ');
                        if ($tags_list) :
                        ?>
                            <div class="tags-links">
                                <i class="fas fa-tags"></i>
                                <span class="tags-label"><?php echo esc_html__('Tags:', 'lcd-theme'); ?></span>
                                <?php echo $tags_list; ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();

                        if (!empty($prev_post) || !empty($next_post)) :
                        ?>
                            <nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e('Post Navigation', 'lcd-theme'); ?>">
                                <div class="nav-links">
                                    <?php if (!empty($prev_post)) : ?>
                                        <div class="nav-previous">
                                            <a href="<?php echo get_permalink($prev_post); ?>" rel="prev">
                                                <span class="nav-direction"><i class="fas fa-arrow-left"></i> <?php echo esc_html__('Previous', 'lcd-theme'); ?></span>
                                                <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($next_post)) : ?>
                                        <div class="nav-next">
                                            <a href="<?php echo get_permalink($next_post); ?>" rel="next">
                                                <span class="nav-direction"><?php echo esc_html__('Next', 'lcd-theme'); ?> <i class="fas fa-arrow-right"></i></span>
                                                <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </nav>
                        <?php endif; ?>

                        <?php
                        if (comments_open() || get_comments_number()) :
                            echo ' <div class="comments-disclaimer">
            <p>We welcome your comments and feedback. Please note that all comments are moderated and approved before being published.</p>
        </div>';
                            comments_template();
                        endif;
                        ?>
                    </footer>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>