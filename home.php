<?php
/**
 * The template for displaying the blog posts index
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

<main id="primary" class="site-main archive-template blog-index">
    <header class="entry-header<?php echo esc_attr($header_class); ?>"<?php echo $header_style ? ' style="' . esc_attr($header_style) . '"' : ''; ?>>
        <div class="entry-header-content">
            <h1 class="entry-title"><?php echo get_the_title($blog_page_id); ?></h1>
            <?php if ($blog_content = get_post_field('post_content', $blog_page_id)): ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($blog_content); ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <div class="posts-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
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

                <?php
                the_posts_pagination(array(
                    'prev_text' => '<i class="fas fa-arrow-left"></i> ' . __('Previous', 'lcd-theme'),
                    'next_text' => __('Next', 'lcd-theme') . ' <i class="fas fa-arrow-right"></i>',
                    'mid_size'  => 2
                ));
                ?>

            <?php else : ?>
                <div class="no-posts">
                    <p><?php esc_html_e('No posts found.', 'lcd-theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer(); 