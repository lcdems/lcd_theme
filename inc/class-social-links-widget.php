<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_Widget')) {
    require_once(ABSPATH . 'wp-includes/class-wp-widget.php');
}

class LCD_Social_Links_Widget extends WP_Widget {
    /**
     * Sets up the widget
     */
    public function __construct() {
        parent::__construct(
            'lcd_social_links',
            __('LCD Social Links', 'lcd-theme'),
            array(
                'description' => __('Display social media links with icons', 'lcd-theme'),
                'classname' => 'social-links-widget',
            )
        );
    }

    /**
     * Front-end display of the widget
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $networks = array(
            'facebook' => array(
                'label' => __('Facebook', 'lcd-theme'),
                'icon' => 'fa-brands fa-facebook-f',
            ),
            'twitter' => array(
                'label' => __('X (Twitter)', 'lcd-theme'),
                'icon' => 'fa-brands fa-x-twitter',
            ),
            'instagram' => array(
                'label' => __('Instagram', 'lcd-theme'),
                'icon' => 'fa-brands fa-instagram',
            ),
            'tiktok' => array(
                'label' => __('TikTok', 'lcd-theme'),
                'icon' => 'fa-brands fa-tiktok',
            ),
            'bluesky' => array(
                'label' => __('BlueSky', 'lcd-theme'),
                'icon' => 'fa-brands fa-bluesky', // Using cloud icon as BlueSky doesn't have an official FA icon yet
            ),
            'youtube' => array(
                'label' => __('YouTube', 'lcd-theme'),
                'icon' => 'fa-brands fa-youtube',
            ),
        );

        echo '<div class="social-links">';
        foreach ($networks as $network => $data) {
            if (!empty($instance[$network])) {
                printf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link %s" aria-label="%s"><i class="%s" aria-hidden="true"></i></a>',
                    esc_url($instance[$network]),
                    esc_attr($network),
                    esc_attr($data['label']),
                    esc_attr($data['icon'])
                );
            }
        }
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $networks = array(
            'facebook' => __('Facebook URL', 'lcd-theme'),
            'twitter' => __('X (Twitter) URL', 'lcd-theme'),
            'instagram' => __('Instagram URL', 'lcd-theme'),
            'tiktok' => __('TikTok URL', 'lcd-theme'),
            'bluesky' => __('BlueSky URL', 'lcd-theme'),
            'youtube' => __('YouTube URL', 'lcd-theme'),
        );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'lcd-theme'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <?php foreach ($networks as $network => $label): 
            $value = !empty($instance[$network]) ? $instance[$network] : '';
        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id($network)); ?>"><?php echo esc_html($label); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id($network)); ?>" name="<?php echo esc_attr($this->get_field_name($network)); ?>" type="url" value="<?php echo esc_attr($value); ?>" placeholder="https://">
            </p>
        <?php endforeach; ?>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        
        $networks = array('facebook', 'twitter', 'instagram', 'tiktok', 'bluesky', 'youtube');
        foreach ($networks as $network) {
            $instance[$network] = (!empty($new_instance[$network])) ? esc_url_raw($new_instance[$network]) : '';
        }

        return $instance;
    }
}
