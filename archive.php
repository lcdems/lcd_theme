<?php 
/**
 * The template for displaying archive pages
 *
 * @package LCD_Theme
 */

get_header();

// Get blog page featured image
$blog_page_id = get_option('page_for_posts');
$header_image = get_the_post_thumbnail_url($blog_page_id, 'full');
$header_class = $header_image ? ' has-featured-image' : '';
$header_style = '';

if ($header_image) {
    $header_style = sprintf('background-image: url(%s);', esc_url($header_image));
    $header_style .= 'background-position: center center;';
    $header_style .= 'background-size: cover;';
    $header_style .= 'background-repeat: no-repeat;';
    $header_style .= '--overlay-color: ' . esc_attr(lcd_get_overlay_rgba('#002B50', 70)) . ';';
}
?>

<main id="primary" class="site-main archive-template">
    <header class="archive-header<?php echo esc_attr($header_class); ?>"<?php echo $header_style ? ' style="' . esc_attr($header_style) . '"' : ''; ?>>
        <div class="container">
            <div class="archive-header-content">
                <?php
                if (is_category()) {
                    ?>
                    <div class="breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                        <span class="separator"> › </span>
                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'lcd-theme'); ?></a>
                        <span class="separator"> › </span>
                        <span class="current"><?php single_cat_title(); ?></span>
                    </div>
                    <h1 class="archive-title"><?php single_cat_title(); ?></h1>
                    <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
                    <?php
                } else {
                    ?>
                    <div class="breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                        <span class="separator"> › </span>
                        <span class="current"><?php esc_html_e('Blog', 'lcd-theme'); ?></span>
                    </div>
                    <h1 class="archive-title"><?php esc_html_e('Blog', 'lcd-theme'); ?></h1>
                    <?php
                }
                ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="posts-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="post-header">
                                <div class="post-meta">
                                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                                    <?php
                                    $categories_list = get_the_category_list(', ');
                                    if ($categories_list) :
                                    ?>
                                        <span class="cat-links">
                                            <?php echo $categories_list; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php the_title('<h2 class="post-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                            </header>

                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Read More', 'lcd-theme'); ?>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>

                <?php
                the_posts_pagination(array(
                    'prev_text' => '<i class="fas fa-arrow-left"></i> ' . __('Previous', 'lcd-theme'),
                    'next_text' => __('Next', 'lcd-theme') . ' <i class="fas fa-arrow-right"></i>',
                    'mid_size'  => 2,
                ));
                ?>

            <?php else : ?>
                <p><?php esc_html_e('No posts found.', 'lcd-theme'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
?> 