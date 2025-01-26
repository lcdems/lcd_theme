<?php
/**
 * The template for displaying event archives
 *
 * @package LCD_Theme
 */

get_header();

// Get current date
$current_date = date('Y-m-d');

// Modify the query to show only upcoming events
global $wp_query;
$wp_query->set('meta_key', '_event_date');
$wp_query->set('orderby', 'meta_value');
$wp_query->set('order', 'ASC');
$wp_query->set('meta_query', array(
    array(
        'key' => '_event_date',
        'value' => $current_date,
        'compare' => '>=',
        'type' => 'DATE'
    )
));
$wp_query->get_posts();
?>

<main id="primary" class="site-main archive-template events-archive">
    <header class="archive-header">
        <div class="container">
            <div class="archive-header-content">
                <div class="breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                    <span class="separator"> › </span>
                    <span class="current"><?php esc_html_e('Events', 'lcd-theme'); ?></span>
                </div>
                <h1 class="archive-title"><?php esc_html_e('Upcoming Events', 'lcd-theme'); ?></h1>
                <div class="archive-description">
                    <p><?php esc_html_e('Join us at our upcoming events and get involved with the Lewis County Democrats.', 'lcd-theme'); ?></p>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="events-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); 
                    // Get event meta
                    $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                    $event_time = get_post_meta(get_the_ID(), '_event_time', true);
                    $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                    $event_cost = get_post_meta(get_the_ID(), '_event_cost', true);
                    
                    // Format date and time
                    $date_formatted = date_i18n(get_option('date_format'), strtotime($event_date));
                    $time_formatted = $event_time ? date_i18n(get_option('time_format'), strtotime($event_time)) : '';
                ?>
                    <article <?php post_class('event-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="event-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="event-content">
                            <div class="event-meta">
                                <div class="event-date">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo esc_html($date_formatted); ?></span>
                                    <?php if ($time_formatted) : ?>
                                        <span class="event-time">
                                            <i class="fas fa-clock"></i>
                                            <?php echo esc_html($time_formatted); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($event_location) : ?>
                                    <div class="event-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo esc_html($event_location); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($event_cost) : ?>
                                    <div class="event-cost">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span><?php echo esc_html($event_cost); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php the_title('<h2 class="event-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

                            <div class="event-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="event-footer">
                                <a href="<?php the_permalink(); ?>" class="button">
                                    <?php esc_html_e('Event Details', 'lcd-theme'); ?>
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
                <div class="no-events-found">
                    <h2><?php esc_html_e('No Upcoming Events', 'lcd-theme'); ?></h2>
                    <p><?php esc_html_e('Check back soon for new events!', 'lcd-theme'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer(); 