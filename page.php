<?php
/**
 * The template for displaying all pages
 *
 * @package LCD_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-template">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
            <header class="entry-header<?php echo has_post_thumbnail() ? ' has-featured-image' : ''; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-header-content">
                    <?php if (!is_front_page()) : ?>
                        <div class="breadcrumbs">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                            <span class="separator"> › </span>
                            <?php
                            if ($post->post_parent) {
                                $ancestors = get_post_ancestors($post->ID);
                                $ancestors = array_reverse($ancestors);
                                foreach ($ancestors as $ancestor) {
                                    $ancestor_post = get_post($ancestor);
                                    echo '<a href="' . get_permalink($ancestor) . '">' . esc_html($ancestor_post->post_title) . '</a>';
                                    echo '<span class="separator"> › </span>';
                                }
                            }
                            ?>
                            <span class="current"><?php the_title(); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </div>
            </header>

            <div class="content-wrapper<?php echo get_post_meta(get_the_ID(), 'full_width_content', true) ? ' full-width-content' : ''; ?>">
                <div class="entry-content">
                    <?php 
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'lcd-theme'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <footer class="entry-footer">
                        <?php comments_template(); ?>
                    </footer>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?> 