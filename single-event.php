<?php
/**
 * The template for displaying single events
 *
 * @package LCD_Theme
 */

get_header();
?>

<main id="primary" class="site-main single-event-template">
    <?php while (have_posts()) : the_post();
        // Get event meta
        $event_date = get_post_meta(get_the_ID(), '_event_date', true);
        $event_time = get_post_meta(get_the_ID(), '_event_time', true);
        $event_end_time = get_post_meta(get_the_ID(), '_event_end_time', true);
        $event_location = get_post_meta(get_the_ID(), '_event_location', true);
        $event_address = get_post_meta(get_the_ID(), '_event_address', true);
        $event_map_link = get_post_meta(get_the_ID(), '_event_map_link', true);
        $event_registration_url = get_post_meta(get_the_ID(), '_event_registration_url', true);
        $event_capacity = get_post_meta(get_the_ID(), '_event_capacity', true);
        $event_organizer = get_post_meta(get_the_ID(), '_event_organizer', true);
        $event_organizer_email = get_post_meta(get_the_ID(), '_event_organizer_email', true);
        $event_cost = get_post_meta(get_the_ID(), '_event_cost', true);

        // Format date and time
        $date_formatted = date_i18n(get_option('date_format'), strtotime($event_date));
        $time_formatted = $event_time ? date_i18n(get_option('time_format'), strtotime($event_time)) : '';
        $end_time_formatted = $event_end_time ? date_i18n(get_option('time_format'), strtotime($event_end_time)) : '';
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header<?php echo has_post_thumbnail() ? ' has-featured-image' : ''; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="container">
                    <div class="entry-header-content">
                        <div class="breadcrumbs">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                            <span class="separator"> › </span>
                            <a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>"><?php esc_html_e('Events', 'lcd-theme'); ?></a>
                            <span class="separator"> › </span>
                            <span class="current"><?php the_title(); ?></span>
                        </div>

                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                        <div class="event-meta-header">
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <div class="meta-content">
                                    <span class="event-date"><?php echo esc_html($date_formatted); ?></span>
                                </div>
                            </div>
                            <?php if ($time_formatted) : ?>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <div class="meta-content">
                                        <span class="event-time">
                                            <?php 
                                            echo esc_html($time_formatted);
                                            if ($end_time_formatted) {
                                                echo ' - ' . esc_html($end_time_formatted);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($event_location) : ?>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div class="meta-content">
                                        <span><?php echo esc_html($event_location); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <div class="container">
                <div class="event-content-wrapper">
                    <div class="event-main-content">
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <?php 
                        // Get poster image
                        $poster_id = get_post_meta(get_the_ID(), '_event_poster', true);
                        if ($poster_id) : 
                            $full_image_url = wp_get_attachment_image_url($poster_id, 'full');
                        ?>
                            <div class="event-poster">
                                <a href="<?php echo esc_url($full_image_url); ?>" class="event-poster-link" target="_blank">
                                    <?php echo wp_get_attachment_image($poster_id, 'large', false, array('class' => 'event-poster-image')); ?>
                                    <span class="zoom-hint"><?php esc_html_e('Click to enlarge', 'lcd-theme'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="event-sidebar">
                        <?php if ($event_registration_url) : ?>
                            <div class="event-registration-sticky">
                                <a href="<?php echo esc_url($event_registration_url); ?>" class="button button-primary registration-button" target="_blank">
                                    <?php 
                                    $button_text = get_post_meta(get_the_ID(), '_event_button_text', true);
                                    echo esc_html($button_text ?: __('Register Now', 'lcd-theme')); 
                                    ?>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <?php 
                                $ticketing_notes = get_post_meta(get_the_ID(), '_event_ticketing_notes', true);
                                if ($ticketing_notes) : ?>
                                    <div class="ticketing-notes">
                                        <?php echo wpautop(esc_html($ticketing_notes)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="event-details-card">
                            <h3><?php esc_html_e('Event Details', 'lcd-theme'); ?></h3>
                            
                            <div class="event-details-list">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <div class="detail-content">
                                        <strong><?php esc_html_e('Date', 'lcd-theme'); ?></strong>
                                        <span><?php echo esc_html($date_formatted); ?></span>
                                    </div>
                                </div>

                                <?php if ($time_formatted) : ?>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <div class="detail-content">
                                            <strong><?php esc_html_e('Time', 'lcd-theme'); ?></strong>
                                            <span>
                                                <?php 
                                                echo esc_html($time_formatted);
                                                if ($end_time_formatted) {
                                                    echo ' - ' . esc_html($end_time_formatted);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($event_location || $event_address) : ?>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="detail-content">
                                            <strong><?php esc_html_e('Location', 'lcd-theme'); ?></strong>
                                            <?php if ($event_location) : ?>
                                                <span class="location-name"><?php echo esc_html($event_location); ?></span>
                                            <?php endif; ?>
                                            <?php if ($event_address) : ?>
                                                <span class="location-address"><?php echo nl2br(esc_html($event_address)); ?></span>
                                            <?php endif; ?>
                                            <?php if ($event_map_link) : ?>
                                                <a href="<?php echo esc_url($event_map_link); ?>" class="map-link" target="_blank">
                                                    <i class="fas fa-directions"></i>
                                                    <?php esc_html_e('Get Directions', 'lcd-theme'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($event_cost) : ?>
                                    <div class="detail-item">
                                        <i class="fas fa-ticket-alt"></i>
                                        <div class="detail-content">
                                            <strong><?php esc_html_e('Cost', 'lcd-theme'); ?></strong>
                                            <span><?php echo esc_html($event_cost); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($event_capacity) : ?>
                                    <div class="detail-item">
                                        <i class="fas fa-users"></i>
                                        <div class="detail-content">
                                            <strong><?php esc_html_e('Capacity', 'lcd-theme'); ?></strong>
                                            <span><?php echo esc_html($event_capacity); ?> <?php esc_html_e('people', 'lcd-theme'); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="event-share">
                                <h4><?php esc_html_e('Share This Event', 'lcd-theme'); ?></h4>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" title="Share on Facebook" target="_blank" class="share-button facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://bsky.app/intent/compose?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" title="Share on Bluesky" target="_blank" class="share-button bluesky">
                                        <i class="fab fa-bluesky"></i>
                                    </a>
                                    <a href="mailto:?subject=Check out this event: <?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" title="Share via Email" class="share-button email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="event-calendar">
                                <h4><?php esc_html_e('Add to Calendar', 'lcd-theme'); ?></h4>
                                <div class="calendar-buttons">
                                    <?php
                                    // Format date and time for calendar links
                                    $start_datetime = $event_date . ($event_time ? 'T' . date('His', strtotime($event_time)) : '');
                                    $end_datetime = $event_date . ($event_end_time ? 'T' . date('His', strtotime($event_end_time)) : ($event_time ? 'T' . date('His', strtotime($event_time . '+2 hours')) : ''));
                                    $location = $event_location . ($event_address ? ', ' . $event_address : '');
                                    
                                    // Google Calendar link
                                    $google_url = add_query_arg(array(
                                        'action' => 'TEMPLATE',
                                        'text' => get_the_title(),
                                        'dates' => str_replace('-', '', $start_datetime) . '/' . str_replace('-', '', $end_datetime),
                                        'details' => wp_strip_all_tags(get_the_content()),
                                        'location' => $location,
                                    ), 'https://calendar.google.com/calendar/render');

                                    // iCal date format
                                    $ical_start = date('Ymd\THis', strtotime($start_datetime));
                                    $ical_end = date('Ymd\THis', strtotime($end_datetime));
                                    
                                    // Generate iCal data
                                    $ical_data = "BEGIN:VCALENDAR\n";
                                    $ical_data .= "VERSION:2.0\n";
                                    $ical_data .= "BEGIN:VEVENT\n";
                                    $ical_data .= "DTSTART:" . $ical_start . "\n";
                                    $ical_data .= "DTEND:" . $ical_end . "\n";
                                    $ical_data .= "SUMMARY:" . get_the_title() . "\n";
                                    $ical_data .= "DESCRIPTION:" . wp_strip_all_tags(get_the_content()) . "\n";
                                    $ical_data .= "LOCATION:" . $location . "\n";
                                    $ical_data .= "END:VEVENT\n";
                                    $ical_data .= "END:VCALENDAR";
                                    ?>
                                    
                                    <a href="<?php echo esc_url($google_url); ?>" target="_blank" class="calendar-button google">
                                        <i class="fab fa-google"></i>
                                        <?php esc_html_e('Google Calendar', 'lcd-theme'); ?>
                                    </a>
                                    
                                    <a href="data:text/calendar;charset=utf8,<?php echo urlencode($ical_data); ?>" download="event.ics" class="calendar-button ical">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php esc_html_e('iCal / Outlook', 'lcd-theme'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Get related events
                $related_events = new WP_Query(array(
                    'post_type' => 'event',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'meta_key' => '_event_date',
                    'orderby' => 'meta_value',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => '_event_date',
                            'value' => date('Y-m-d'),
                            'compare' => '>=',
                            'type' => 'DATE'
                        )
                    )
                ));

                if ($related_events->have_posts()) : ?>
                    <div class="related-events">
                        <h2><?php esc_html_e('Other Upcoming Events', 'lcd-theme'); ?></h2>
                        <div class="events-grid">
                            <?php while ($related_events->have_posts()) : $related_events->the_post();
                                $rel_event_date = get_post_meta(get_the_ID(), '_event_date', true);
                                $rel_event_time = get_post_meta(get_the_ID(), '_event_time', true);
                                $rel_event_location = get_post_meta(get_the_ID(), '_event_location', true);
                                
                                $rel_date_formatted = date_i18n(get_option('date_format'), strtotime($rel_event_date));
                                $rel_time_formatted = $rel_event_time ? date_i18n(get_option('time_format'), strtotime($rel_event_time)) : '';
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
                                                <span><?php echo esc_html($rel_date_formatted); ?></span>
                                                <?php if ($rel_time_formatted) : ?>
                                                    <span class="event-time">
                                                        <i class="fas fa-clock"></i>
                                                        <?php echo esc_html($rel_time_formatted); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($rel_event_location) : ?>
                                                <div class="event-location">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?php echo esc_html($rel_event_location); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php the_title('<h3 class="event-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>

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
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer(); 