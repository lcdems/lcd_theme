<?php
/**
 * Facebook Pixel Tracking Events
 * 
 * Allows per-page configuration of Facebook tracking events with
 * auto-detect query parameters or manual value options.
 */

if (!defined('ABSPATH')) exit;

/**
 * Get available Facebook standard events and their parameters
 */
function lcd_get_fb_standard_events() {
    return array(
        'Purchase' => array(
            'description' => 'When a purchase is made or checkout flow is completed',
            'params' => array(
                'value' => array('type' => 'number', 'required' => true, 'description' => 'Value of the purchase'),
                'currency' => array('type' => 'string', 'required' => true, 'description' => 'Currency code (e.g., USD)'),
                'content_ids' => array('type' => 'array', 'required' => false, 'description' => 'Product IDs'),
                'content_type' => array('type' => 'string', 'required' => false, 'description' => 'product or product_group'),
                'num_items' => array('type' => 'number', 'required' => false, 'description' => 'Number of items'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the page/product'),
            ),
        ),
        'Lead' => array(
            'description' => 'When a sign up or lead form is completed',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Value of the lead'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the form/page'),
                'content_category' => array('type' => 'string', 'required' => false, 'description' => 'Category of the content'),
            ),
        ),
        'CompleteRegistration' => array(
            'description' => 'When a registration form is completed',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Value of the registration'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the registration'),
                'status' => array('type' => 'string', 'required' => false, 'description' => 'Status of registration'),
            ),
        ),
        'AddToCart' => array(
            'description' => 'When a product is added to cart',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Value of the item'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_ids' => array('type' => 'array', 'required' => false, 'description' => 'Product IDs'),
                'content_type' => array('type' => 'string', 'required' => false, 'description' => 'product or product_group'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the product'),
            ),
        ),
        'InitiateCheckout' => array(
            'description' => 'When checkout process is initiated',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Total value'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_ids' => array('type' => 'array', 'required' => false, 'description' => 'Product IDs'),
                'num_items' => array('type' => 'number', 'required' => false, 'description' => 'Number of items'),
            ),
        ),
        'ViewContent' => array(
            'description' => 'When a key page is viewed',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Value of the content'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_ids' => array('type' => 'array', 'required' => false, 'description' => 'Content IDs'),
                'content_type' => array('type' => 'string', 'required' => false, 'description' => 'Type of content'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the content'),
            ),
        ),
        'Search' => array(
            'description' => 'When a search is made',
            'params' => array(
                'search_string' => array('type' => 'string', 'required' => false, 'description' => 'Search query'),
                'content_ids' => array('type' => 'array', 'required' => false, 'description' => 'Result IDs'),
                'content_category' => array('type' => 'string', 'required' => false, 'description' => 'Category searched'),
            ),
        ),
        'Contact' => array(
            'description' => 'When contact is made (phone, email, chat, etc.)',
            'params' => array(
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Contact method or page'),
            ),
        ),
        'Donate' => array(
            'description' => 'When a donation is made',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Donation amount'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Campaign name'),
            ),
        ),
        'Schedule' => array(
            'description' => 'When an appointment or visit is scheduled',
            'params' => array(
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Name of the appointment'),
            ),
        ),
        'SubmitApplication' => array(
            'description' => 'When an application is submitted',
            'params' => array(
                'content_name' => array('type' => 'string', 'required' => false, 'description' => 'Application name'),
                'content_category' => array('type' => 'string', 'required' => false, 'description' => 'Application category'),
            ),
        ),
        'Subscribe' => array(
            'description' => 'When a subscription is started',
            'params' => array(
                'value' => array('type' => 'number', 'required' => false, 'description' => 'Subscription value'),
                'currency' => array('type' => 'string', 'required' => false, 'description' => 'Currency code'),
                'predicted_ltv' => array('type' => 'number', 'required' => false, 'description' => 'Predicted lifetime value'),
            ),
        ),
        'CustomEvent' => array(
            'description' => 'A custom event with your own name',
            'params' => array(
                'custom_event_name' => array('type' => 'string', 'required' => true, 'description' => 'Your custom event name'),
            ),
            'is_custom' => true,
        ),
    );
}

/**
 * Add settings page for Facebook Pixel ID
 */
function lcd_fb_tracking_menu() {
    add_options_page(
        __('Facebook Tracking', 'lcd-theme'),
        __('Facebook Tracking', 'lcd-theme'),
        'manage_options',
        'lcd-facebook-tracking',
        'lcd_fb_tracking_settings_page'
    );
}
add_action('admin_menu', 'lcd_fb_tracking_menu');

/**
 * Register settings
 */
function lcd_fb_tracking_register_settings() {
    register_setting('lcd_fb_tracking_settings', 'lcd_fb_pixel_id', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    register_setting('lcd_fb_tracking_settings', 'lcd_fb_tracking_enabled', array(
        'sanitize_callback' => 'absint',
    ));
    register_setting('lcd_fb_tracking_settings', 'lcd_fb_advanced_matching_enabled', array(
        'sanitize_callback' => 'absint',
    ));
    register_setting('lcd_fb_tracking_settings', 'lcd_fb_advanced_matching', array(
        'sanitize_callback' => 'lcd_fb_sanitize_advanced_matching',
    ));
}
add_action('admin_init', 'lcd_fb_tracking_register_settings');

/**
 * Sanitize advanced matching settings
 */
function lcd_fb_sanitize_advanced_matching($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $sanitized = array();
    $valid_fields = lcd_get_fb_advanced_matching_fields();
    
    foreach ($valid_fields as $field_key => $field_data) {
        if (isset($input[$field_key])) {
            $sanitized[$field_key] = array(
                'enabled' => !empty($input[$field_key]['enabled']) ? 1 : 0,
                'source' => isset($input[$field_key]['source']) ? sanitize_text_field($input[$field_key]['source']) : 'manual',
                'query_key' => isset($input[$field_key]['query_key']) ? sanitize_text_field($input[$field_key]['query_key']) : $field_key,
                'manual_value' => isset($input[$field_key]['manual_value']) ? sanitize_text_field($input[$field_key]['manual_value']) : '',
            );
        }
    }
    
    return $sanitized;
}

/**
 * Get available Advanced Matching fields
 */
function lcd_get_fb_advanced_matching_fields() {
    return array(
        'em' => array(
            'label' => __('Email', 'lcd-theme'),
            'description' => __('User email address (will be hashed by Facebook)', 'lcd-theme'),
            'supports_wp_user' => true,
        ),
        'ph' => array(
            'label' => __('Phone Number', 'lcd-theme'),
            'description' => __('Phone number with country code (e.g., 12025551234)', 'lcd-theme'),
            'supports_wp_user' => false,
        ),
        'fn' => array(
            'label' => __('First Name', 'lcd-theme'),
            'description' => __('User first name', 'lcd-theme'),
            'supports_wp_user' => true,
        ),
        'ln' => array(
            'label' => __('Last Name', 'lcd-theme'),
            'description' => __('User last name', 'lcd-theme'),
            'supports_wp_user' => true,
        ),
        'ct' => array(
            'label' => __('City', 'lcd-theme'),
            'description' => __('City name (lowercase, no spaces)', 'lcd-theme'),
            'supports_wp_user' => false,
        ),
        'st' => array(
            'label' => __('State', 'lcd-theme'),
            'description' => __('State code (2-letter, lowercase)', 'lcd-theme'),
            'supports_wp_user' => false,
        ),
        'zp' => array(
            'label' => __('Zip/Postal Code', 'lcd-theme'),
            'description' => __('Zip or postal code', 'lcd-theme'),
            'supports_wp_user' => false,
        ),
        'country' => array(
            'label' => __('Country', 'lcd-theme'),
            'description' => __('Country code (2-letter, lowercase)', 'lcd-theme'),
            'supports_wp_user' => false,
        ),
        'external_id' => array(
            'label' => __('External ID', 'lcd-theme'),
            'description' => __('Unique user ID from your system', 'lcd-theme'),
            'supports_wp_user' => true,
        ),
    );
}

/**
 * Settings page callback
 */
function lcd_fb_tracking_settings_page() {
    $advanced_matching = get_option('lcd_fb_advanced_matching', array());
    $matching_fields = lcd_get_fb_advanced_matching_fields();
    ?>
    <style>
        .lcd-fb-advanced-matching-table {
            margin-top: 15px;
        }
        .lcd-fb-advanced-matching-table th {
            text-align: left;
            padding: 10px;
        }
        .lcd-fb-advanced-matching-table td {
            padding: 8px 10px;
            vertical-align: middle;
        }
        .lcd-fb-advanced-matching-table .description {
            font-size: 12px;
            color: #666;
        }
        .lcd-fb-am-source-fields {
            display: none;
            margin-top: 5px;
        }
        .lcd-fb-am-source-fields.active {
            display: block;
        }
        .lcd-fb-am-row {
            background: #f9f9f9;
        }
        .lcd-fb-am-row:nth-child(even) {
            background: #fff;
        }
        .lcd-fb-info-box {
            background: #e7f3fe;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
        }
        .lcd-fb-info-box code {
            background: rgba(0,0,0,0.05);
            padding: 2px 6px;
        }
    </style>
    <div class="wrap">
        <h1><?php echo esc_html__('Facebook Tracking Settings', 'lcd-theme'); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('lcd_fb_tracking_settings'); ?>
            
            <h2><?php echo esc_html__('General Settings', 'lcd-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="lcd_fb_tracking_enabled"><?php echo esc_html__('Enable Facebook Tracking', 'lcd-theme'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="lcd_fb_tracking_enabled" id="lcd_fb_tracking_enabled" value="1" <?php checked(1, get_option('lcd_fb_tracking_enabled', 0)); ?>>
                        <p class="description"><?php echo esc_html__('Enable or disable Facebook Pixel tracking globally.', 'lcd-theme'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="lcd_fb_pixel_id"><?php echo esc_html__('Facebook Pixel ID', 'lcd-theme'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="lcd_fb_pixel_id" id="lcd_fb_pixel_id" class="regular-text" value="<?php echo esc_attr(get_option('lcd_fb_pixel_id', '')); ?>">
                        <p class="description"><?php echo esc_html__('Enter your Facebook Pixel ID (e.g., 123456789012345).', 'lcd-theme'); ?></p>
                    </td>
                </tr>
            </table>
            
            <hr>
            
            <h2><?php echo esc_html__('Advanced Matching', 'lcd-theme'); ?></h2>
            <p><?php echo esc_html__('Advanced Matching helps improve conversion tracking by sending hashed user data to Facebook. All data is automatically hashed using SHA-256 before being sent.', 'lcd-theme'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="lcd_fb_advanced_matching_enabled"><?php echo esc_html__('Enable Advanced Matching', 'lcd-theme'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="lcd_fb_advanced_matching_enabled" id="lcd_fb_advanced_matching_enabled" value="1" <?php checked(1, get_option('lcd_fb_advanced_matching_enabled', 0)); ?>>
                        <p class="description"><?php echo esc_html__('Enable Advanced Matching to send user data with pixel events.', 'lcd-theme'); ?></p>
                    </td>
                </tr>
            </table>
            
            <div class="lcd-fb-info-box">
                <strong><?php echo esc_html__('Data Sources:', 'lcd-theme'); ?></strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li><strong><?php echo esc_html__('WordPress User', 'lcd-theme'); ?>:</strong> <?php echo esc_html__('Uses logged-in user data (email, name, etc.)', 'lcd-theme'); ?></li>
                    <li><strong><?php echo esc_html__('Auto-Detect (Query Params)', 'lcd-theme'); ?>:</strong> <?php echo esc_html__('Reads from URL query parameters (e.g., ?em=email@example.com)', 'lcd-theme'); ?></li>
                    <li><strong><?php echo esc_html__('Manual Value', 'lcd-theme'); ?>:</strong> <?php echo esc_html__('Uses a fixed value you specify', 'lcd-theme'); ?></li>
                </ul>
            </div>
            
            <table class="widefat lcd-fb-advanced-matching-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><?php echo esc_html__('Enable', 'lcd-theme'); ?></th>
                        <th style="width: 150px;"><?php echo esc_html__('Field', 'lcd-theme'); ?></th>
                        <th style="width: 180px;"><?php echo esc_html__('Data Source', 'lcd-theme'); ?></th>
                        <th><?php echo esc_html__('Configuration', 'lcd-theme'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matching_fields as $field_key => $field_data) : 
                        $field_settings = isset($advanced_matching[$field_key]) ? $advanced_matching[$field_key] : array();
                        $enabled = !empty($field_settings['enabled']);
                        $source = isset($field_settings['source']) ? $field_settings['source'] : 'auto';
                        $query_key = isset($field_settings['query_key']) ? $field_settings['query_key'] : $field_key;
                        $manual_value = isset($field_settings['manual_value']) ? $field_settings['manual_value'] : '';
                    ?>
                    <tr class="lcd-fb-am-row">
                        <td>
                            <input type="checkbox" 
                                   name="lcd_fb_advanced_matching[<?php echo esc_attr($field_key); ?>][enabled]" 
                                   value="1" 
                                   <?php checked($enabled); ?>
                                   class="lcd-fb-am-enable">
                        </td>
                        <td>
                            <strong><?php echo esc_html($field_data['label']); ?></strong>
                            <code style="font-size: 11px; margin-left: 5px;"><?php echo esc_html($field_key); ?></code>
                            <p class="description"><?php echo esc_html($field_data['description']); ?></p>
                        </td>
                        <td>
                            <select name="lcd_fb_advanced_matching[<?php echo esc_attr($field_key); ?>][source]" class="lcd-fb-am-source">
                                <?php if ($field_data['supports_wp_user']) : ?>
                                <option value="wp_user" <?php selected($source, 'wp_user'); ?>><?php echo esc_html__('WordPress User', 'lcd-theme'); ?></option>
                                <?php endif; ?>
                                <option value="auto" <?php selected($source, 'auto'); ?>><?php echo esc_html__('Auto-Detect (Query Params)', 'lcd-theme'); ?></option>
                                <option value="manual" <?php selected($source, 'manual'); ?>><?php echo esc_html__('Manual Value', 'lcd-theme'); ?></option>
                            </select>
                        </td>
                        <td>
                            <div class="lcd-fb-am-source-fields lcd-fb-am-auto <?php echo $source === 'auto' ? 'active' : ''; ?>">
                                <label><?php echo esc_html__('Query Parameter Key:', 'lcd-theme'); ?></label>
                                <input type="text" 
                                       name="lcd_fb_advanced_matching[<?php echo esc_attr($field_key); ?>][query_key]" 
                                       value="<?php echo esc_attr($query_key); ?>" 
                                       placeholder="<?php echo esc_attr($field_key); ?>"
                                       class="regular-text">
                            </div>
                            <div class="lcd-fb-am-source-fields lcd-fb-am-manual <?php echo $source === 'manual' ? 'active' : ''; ?>">
                                <label><?php echo esc_html__('Fixed Value:', 'lcd-theme'); ?></label>
                                <input type="text" 
                                       name="lcd_fb_advanced_matching[<?php echo esc_attr($field_key); ?>][manual_value]" 
                                       value="<?php echo esc_attr($manual_value); ?>" 
                                       class="regular-text">
                            </div>
                            <div class="lcd-fb-am-source-fields lcd-fb-am-wp_user <?php echo $source === 'wp_user' ? 'active' : ''; ?>">
                                <span class="description"><?php echo esc_html__('Will use data from logged-in WordPress user.', 'lcd-theme'); ?></span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php submit_button(); ?>
        </form>
        
        <hr>
        
        <h2><?php echo esc_html__('How to Use', 'lcd-theme'); ?></h2>
        <p><?php echo esc_html__('Once enabled, you can configure Facebook tracking events on individual pages:', 'lcd-theme'); ?></p>
        <ol>
            <li><?php echo esc_html__('Edit any page', 'lcd-theme'); ?></li>
            <li><?php echo esc_html__('Look for the "Facebook Tracking Events" meta box', 'lcd-theme'); ?></li>
            <li><?php echo esc_html__('Add events and configure their parameters', 'lcd-theme'); ?></li>
            <li><?php echo esc_html__('Choose to auto-detect values from URL query parameters or set them manually', 'lcd-theme'); ?></li>
        </ol>
        
        <h3><?php echo esc_html__('Auto-Detect Example', 'lcd-theme'); ?></h3>
        <p><?php echo esc_html__('If you set "value" to auto-detect with query key "value", visiting:', 'lcd-theme'); ?></p>
        <code>yoursite.com/thank-you/?value=2000&num_items=2</code>
        <p><?php echo esc_html__('Will fire the event with value=2000 and num_items=2.', 'lcd-theme'); ?></p>
        
        <h3><?php echo esc_html__('Advanced Matching Example', 'lcd-theme'); ?></h3>
        <p><?php echo esc_html__('With email auto-detect enabled, visiting:', 'lcd-theme'); ?></p>
        <code>yoursite.com/thank-you/?em=john@example.com&value=2000</code>
        <p><?php echo esc_html__('Will include the hashed email in the pixel initialization for better conversion matching.', 'lcd-theme'); ?></p>
    </div>
    
    <script>
    jQuery(function($) {
        // Handle source change
        $('.lcd-fb-am-source').on('change', function() {
            var source = $(this).val();
            var row = $(this).closest('tr');
            row.find('.lcd-fb-am-source-fields').removeClass('active');
            row.find('.lcd-fb-am-' + source).addClass('active');
        });
    });
    </script>
    <?php
}

/**
 * Add meta box for Facebook tracking events on pages
 */
function lcd_fb_tracking_meta_box() {
    $post_types = apply_filters('lcd_fb_tracking_post_types', array('page'));
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'lcd_fb_tracking_events',
            __('Facebook Tracking Events', 'lcd-theme'),
            'lcd_fb_tracking_meta_box_callback',
            $post_type,
            'normal',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'lcd_fb_tracking_meta_box');

/**
 * Meta box callback
 */
function lcd_fb_tracking_meta_box_callback($post) {
    wp_nonce_field('lcd_fb_tracking_meta_box', 'lcd_fb_tracking_nonce');
    
    $events = get_post_meta($post->ID, '_lcd_fb_tracking_events', true);
    if (!is_array($events)) {
        $events = array();
    }
    
    $available_events = lcd_get_fb_standard_events();
    ?>
    <style>
        .lcd-fb-events-container {
            margin-top: 10px;
        }
        .lcd-fb-event-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .lcd-fb-event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .lcd-fb-event-header h4 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .lcd-fb-event-params {
            margin-top: 10px;
        }
        .lcd-fb-param-row {
            display: grid;
            grid-template-columns: 150px 150px 1fr 1fr auto;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
        }
        .lcd-fb-param-row label {
            font-weight: 600;
        }
        .lcd-fb-param-row input,
        .lcd-fb-param-row select {
            width: 100%;
        }
        .lcd-fb-param-row .lcd-fb-query-key {
            display: none;
        }
        .lcd-fb-param-row.source-auto .lcd-fb-query-key {
            display: block;
        }
        .lcd-fb-param-row.source-auto .lcd-fb-manual-value {
            display: none;
        }
        .lcd-fb-param-row .lcd-fb-manual-value {
            display: block;
        }
        .lcd-fb-add-event {
            margin-top: 15px;
        }
        .lcd-fb-remove-event,
        .lcd-fb-remove-param {
            color: #a00;
            cursor: pointer;
            text-decoration: none;
        }
        .lcd-fb-remove-event:hover,
        .lcd-fb-remove-param:hover {
            color: #dc3232;
        }
        .lcd-fb-add-param {
            margin-top: 10px;
        }
        .lcd-fb-event-description {
            color: #666;
            font-style: italic;
            margin-left: 10px;
            font-size: 12px;
        }
        .lcd-fb-param-required {
            color: #dc3232;
        }
        .lcd-fb-empty-state {
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border: 2px dashed #ddd;
            border-radius: 4px;
            color: #666;
        }
        .lcd-fb-param-description {
            font-size: 11px;
            color: #888;
            grid-column: 1 / -1;
            margin-top: -5px;
        }
    </style>
    
    <div class="lcd-fb-events-container" id="lcd-fb-events-container">
        <?php if (empty($events)) : ?>
            <div class="lcd-fb-empty-state" id="lcd-fb-empty-state">
                <p><?php echo esc_html__('No tracking events configured for this page.', 'lcd-theme'); ?></p>
                <p><?php echo esc_html__('Add an event below to start tracking.', 'lcd-theme'); ?></p>
            </div>
        <?php else : ?>
            <?php foreach ($events as $index => $event) : ?>
                <?php lcd_render_fb_event_item($event, $index, $available_events); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="lcd-fb-add-event">
        <label for="lcd-fb-new-event-type"><?php echo esc_html__('Add Event:', 'lcd-theme'); ?></label>
        <select id="lcd-fb-new-event-type">
            <option value=""><?php echo esc_html__('-- Select Event --', 'lcd-theme'); ?></option>
            <?php foreach ($available_events as $event_name => $event_data) : ?>
                <option value="<?php echo esc_attr($event_name); ?>"><?php echo esc_html($event_name); ?> - <?php echo esc_html($event_data['description']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="button" id="lcd-fb-add-event-btn"><?php echo esc_html__('Add Event', 'lcd-theme'); ?></button>
    </div>
    
    <script>
        (function($) {
            var eventIndex = <?php echo count($events); ?>;
            var availableEvents = <?php echo json_encode($available_events); ?>;
            
            // Add event
            $('#lcd-fb-add-event-btn').on('click', function() {
                var eventType = $('#lcd-fb-new-event-type').val();
                if (!eventType) {
                    alert('<?php echo esc_js(__('Please select an event type.', 'lcd-theme')); ?>');
                    return;
                }
                
                $('#lcd-fb-empty-state').hide();
                
                var eventData = availableEvents[eventType];
                var html = renderEventItem(eventType, eventData, eventIndex);
                $('#lcd-fb-events-container').append(html);
                eventIndex++;
                
                $('#lcd-fb-new-event-type').val('');
            });
            
            // Remove event
            $(document).on('click', '.lcd-fb-remove-event', function(e) {
                e.preventDefault();
                if (confirm('<?php echo esc_js(__('Are you sure you want to remove this event?', 'lcd-theme')); ?>')) {
                    $(this).closest('.lcd-fb-event-item').remove();
                    if ($('.lcd-fb-event-item').length === 0) {
                        $('#lcd-fb-empty-state').show();
                    }
                }
            });
            
            // Handle source change
            $(document).on('change', '.lcd-fb-source-select', function() {
                var row = $(this).closest('.lcd-fb-param-row');
                if ($(this).val() === 'auto') {
                    row.addClass('source-auto').removeClass('source-manual');
                } else {
                    row.removeClass('source-auto').addClass('source-manual');
                }
            });
            
            // Add parameter
            $(document).on('click', '.lcd-fb-add-param', function() {
                var container = $(this).prev('.lcd-fb-event-params');
                var eventIdx = $(this).data('event-index');
                var paramIdx = container.find('.lcd-fb-param-row').length;
                var html = renderParamRow(eventIdx, paramIdx, '', '', 'manual', '', '');
                container.append(html);
            });
            
            // Remove parameter
            $(document).on('click', '.lcd-fb-remove-param', function(e) {
                e.preventDefault();
                $(this).closest('.lcd-fb-param-row').remove();
            });
            
            function renderEventItem(eventType, eventData, idx) {
                var isCustom = eventData.is_custom || false;
                var html = '<div class="lcd-fb-event-item">';
                html += '<div class="lcd-fb-event-header">';
                html += '<h4><span class="dashicons dashicons-chart-bar"></span> ' + escapeHtml(eventType);
                html += '<span class="lcd-fb-event-description">' + escapeHtml(eventData.description) + '</span></h4>';
                html += '<a href="#" class="lcd-fb-remove-event"><?php echo esc_js(__('Remove Event', 'lcd-theme')); ?></a>';
                html += '</div>';
                
                html += '<input type="hidden" name="lcd_fb_events[' + idx + '][event_type]" value="' + escapeHtml(eventType) + '">';
                
                html += '<div class="lcd-fb-event-params">';
                
                var paramIdx = 0;
                for (var paramKey in eventData.params) {
                    var param = eventData.params[paramKey];
                    var required = param.required ? ' <span class="lcd-fb-param-required">*</span>' : '';
                    html += renderParamRow(idx, paramIdx, paramKey, param.type, 'manual', '', param.description, required);
                    paramIdx++;
                }
                
                html += '</div>';
                html += '<button type="button" class="button lcd-fb-add-param" data-event-index="' + idx + '"><?php echo esc_js(__('Add Custom Parameter', 'lcd-theme')); ?></button>';
                html += '</div>';
                
                return html;
            }
            
            function renderParamRow(eventIdx, paramIdx, key, type, source, value, description, required) {
                required = required || '';
                var sourceClass = source === 'auto' ? 'source-auto' : 'source-manual';
                var html = '<div class="lcd-fb-param-row ' + sourceClass + '">';
                html += '<label>' + escapeHtml(key) + required + '</label>';
                html += '<select class="lcd-fb-source-select" name="lcd_fb_events[' + eventIdx + '][params][' + paramIdx + '][source]">';
                html += '<option value="manual"' + (source === 'manual' ? ' selected' : '') + '><?php echo esc_js(__('Manual Value', 'lcd-theme')); ?></option>';
                html += '<option value="auto"' + (source === 'auto' ? ' selected' : '') + '><?php echo esc_js(__('Auto-Detect', 'lcd-theme')); ?></option>';
                html += '</select>';
                html += '<input type="text" class="lcd-fb-query-key" name="lcd_fb_events[' + eventIdx + '][params][' + paramIdx + '][query_key]" placeholder="<?php echo esc_attr__('Query param key', 'lcd-theme'); ?>" value="' + escapeHtml(key) + '">';
                html += '<input type="text" class="lcd-fb-manual-value" name="lcd_fb_events[' + eventIdx + '][params][' + paramIdx + '][value]" placeholder="<?php echo esc_attr__('Value', 'lcd-theme'); ?>" value="' + escapeHtml(value) + '">';
                html += '<input type="hidden" name="lcd_fb_events[' + eventIdx + '][params][' + paramIdx + '][key]" value="' + escapeHtml(key) + '">';
                html += '<input type="hidden" name="lcd_fb_events[' + eventIdx + '][params][' + paramIdx + '][type]" value="' + escapeHtml(type) + '">';
                html += '<a href="#" class="lcd-fb-remove-param dashicons dashicons-trash" title="<?php echo esc_attr__('Remove', 'lcd-theme'); ?>"></a>';
                if (description) {
                    html += '<span class="lcd-fb-param-description">' + escapeHtml(description) + '</span>';
                }
                html += '</div>';
                return html;
            }
            
            function escapeHtml(text) {
                if (!text) return '';
                var div = document.createElement('div');
                div.appendChild(document.createTextNode(text));
                return div.innerHTML;
            }
        })(jQuery);
    </script>
    <?php
}

/**
 * Render a single event item
 */
function lcd_render_fb_event_item($event, $index, $available_events) {
    $event_type = isset($event['event_type']) ? $event['event_type'] : '';
    $event_data = isset($available_events[$event_type]) ? $available_events[$event_type] : null;
    $params = isset($event['params']) ? $event['params'] : array();
    
    if (!$event_data) {
        return;
    }
    ?>
    <div class="lcd-fb-event-item">
        <div class="lcd-fb-event-header">
            <h4>
                <span class="dashicons dashicons-chart-bar"></span>
                <?php echo esc_html($event_type); ?>
                <span class="lcd-fb-event-description"><?php echo esc_html($event_data['description']); ?></span>
            </h4>
            <a href="#" class="lcd-fb-remove-event"><?php echo esc_html__('Remove Event', 'lcd-theme'); ?></a>
        </div>
        
        <input type="hidden" name="lcd_fb_events[<?php echo $index; ?>][event_type]" value="<?php echo esc_attr($event_type); ?>">
        
        <div class="lcd-fb-event-params">
            <?php foreach ($params as $param_index => $param) : 
                $key = isset($param['key']) ? $param['key'] : '';
                $source = isset($param['source']) ? $param['source'] : 'manual';
                $value = isset($param['value']) ? $param['value'] : '';
                $query_key = isset($param['query_key']) ? $param['query_key'] : $key;
                $type = isset($param['type']) ? $param['type'] : 'string';
                
                // Get parameter info from event data
                $param_info = isset($event_data['params'][$key]) ? $event_data['params'][$key] : array();
                $description = isset($param_info['description']) ? $param_info['description'] : '';
                $required = isset($param_info['required']) && $param_info['required'];
                
                $source_class = $source === 'auto' ? 'source-auto' : 'source-manual';
            ?>
                <div class="lcd-fb-param-row <?php echo esc_attr($source_class); ?>">
                    <label>
                        <?php echo esc_html($key); ?>
                        <?php if ($required) : ?><span class="lcd-fb-param-required">*</span><?php endif; ?>
                    </label>
                    <select class="lcd-fb-source-select" name="lcd_fb_events[<?php echo $index; ?>][params][<?php echo $param_index; ?>][source]">
                        <option value="manual" <?php selected($source, 'manual'); ?>><?php echo esc_html__('Manual Value', 'lcd-theme'); ?></option>
                        <option value="auto" <?php selected($source, 'auto'); ?>><?php echo esc_html__('Auto-Detect', 'lcd-theme'); ?></option>
                    </select>
                    <input type="text" class="lcd-fb-query-key" name="lcd_fb_events[<?php echo $index; ?>][params][<?php echo $param_index; ?>][query_key]" placeholder="<?php echo esc_attr__('Query param key', 'lcd-theme'); ?>" value="<?php echo esc_attr($query_key); ?>">
                    <input type="text" class="lcd-fb-manual-value" name="lcd_fb_events[<?php echo $index; ?>][params][<?php echo $param_index; ?>][value]" placeholder="<?php echo esc_attr__('Value', 'lcd-theme'); ?>" value="<?php echo esc_attr($value); ?>">
                    <input type="hidden" name="lcd_fb_events[<?php echo $index; ?>][params][<?php echo $param_index; ?>][key]" value="<?php echo esc_attr($key); ?>">
                    <input type="hidden" name="lcd_fb_events[<?php echo $index; ?>][params][<?php echo $param_index; ?>][type]" value="<?php echo esc_attr($type); ?>">
                    <a href="#" class="lcd-fb-remove-param dashicons dashicons-trash" title="<?php echo esc_attr__('Remove', 'lcd-theme'); ?>"></a>
                    <?php if ($description) : ?>
                        <span class="lcd-fb-param-description"><?php echo esc_html($description); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button lcd-fb-add-param" data-event-index="<?php echo $index; ?>"><?php echo esc_html__('Add Custom Parameter', 'lcd-theme'); ?></button>
    </div>
    <?php
}

/**
 * Save meta box data
 */
function lcd_fb_tracking_save_meta($post_id) {
    // Check nonce
    if (!isset($_POST['lcd_fb_tracking_nonce']) || !wp_verify_nonce($_POST['lcd_fb_tracking_nonce'], 'lcd_fb_tracking_meta_box')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Sanitize and save events
    $events = array();
    if (isset($_POST['lcd_fb_events']) && is_array($_POST['lcd_fb_events'])) {
        foreach ($_POST['lcd_fb_events'] as $event) {
            $sanitized_event = array(
                'event_type' => sanitize_text_field($event['event_type']),
                'params' => array(),
            );
            
            if (isset($event['params']) && is_array($event['params'])) {
                foreach ($event['params'] as $param) {
                    $sanitized_event['params'][] = array(
                        'key' => sanitize_text_field($param['key']),
                        'source' => sanitize_text_field($param['source']),
                        'value' => sanitize_text_field($param['value']),
                        'query_key' => sanitize_text_field($param['query_key']),
                        'type' => sanitize_text_field($param['type']),
                    );
                }
            }
            
            $events[] = $sanitized_event;
        }
    }
    
    update_post_meta($post_id, '_lcd_fb_tracking_events', $events);
}
add_action('save_post', 'lcd_fb_tracking_save_meta');

/**
 * Output Facebook Pixel base code in header
 */
function lcd_fb_output_pixel_base() {
    // Check if tracking is enabled
    if (!get_option('lcd_fb_tracking_enabled', 0)) {
        return;
    }
    
    $pixel_id = get_option('lcd_fb_pixel_id', '');
    if (empty($pixel_id)) {
        return;
    }
    
    // Don't track admins if in debug mode
    if (current_user_can('manage_options') && defined('WP_DEBUG') && WP_DEBUG) {
        echo "<!-- Facebook Pixel disabled for admins in debug mode -->\n";
        return;
    }
    
    // Build Advanced Matching data
    $advanced_matching_data = lcd_fb_get_advanced_matching_data();
    $has_matching_data = !empty($advanced_matching_data);
    ?>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        <?php if ($has_matching_data) : ?>
        // Advanced Matching enabled
        (function() {
            var advancedMatchingConfig = <?php echo json_encode($advanced_matching_data['config']); ?>;
            var matchingData = {};
            
            // Process each configured field
            for (var key in advancedMatchingConfig) {
                var config = advancedMatchingConfig[key];
                var value = null;
                
                if (config.source === 'auto') {
                    // Get from URL query parameters
                    var params = new URLSearchParams(window.location.search);
                    value = params.get(config.query_key || key);
                } else if (config.source === 'wp_user' && config.wp_value) {
                    // Use WordPress user value
                    value = config.wp_value;
                } else if (config.source === 'manual' && config.manual_value) {
                    // Use manual value
                    value = config.manual_value;
                }
                
                if (value && value.trim() !== '') {
                    // Normalize values per Facebook requirements
                    value = value.toString().toLowerCase().trim();
                    matchingData[key] = value;
                }
            }
            
            // Initialize with Advanced Matching data if any values found
            if (Object.keys(matchingData).length > 0) {
                fbq('init', '<?php echo esc_js($pixel_id); ?>', matchingData);
            } else {
                fbq('init', '<?php echo esc_js($pixel_id); ?>');
            }
        })();
        <?php else : ?>
        fbq('init', '<?php echo esc_js($pixel_id); ?>');
        <?php endif; ?>
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=<?php echo esc_attr($pixel_id); ?>&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
    <?php
}
add_action('wp_head', 'lcd_fb_output_pixel_base', 1);

/**
 * Get Advanced Matching data configuration
 */
function lcd_fb_get_advanced_matching_data() {
    // Check if advanced matching is enabled
    if (!get_option('lcd_fb_advanced_matching_enabled', 0)) {
        return array();
    }
    
    $settings = get_option('lcd_fb_advanced_matching', array());
    if (empty($settings)) {
        return array();
    }
    
    $config = array();
    $current_user = wp_get_current_user();
    
    foreach ($settings as $field_key => $field_settings) {
        if (empty($field_settings['enabled'])) {
            continue;
        }
        
        $source = isset($field_settings['source']) ? $field_settings['source'] : 'auto';
        
        $field_config = array(
            'source' => $source,
        );
        
        // Add source-specific configuration
        switch ($source) {
            case 'wp_user':
                // Get value from current logged-in user
                if ($current_user->ID > 0) {
                    switch ($field_key) {
                        case 'em':
                            $field_config['wp_value'] = $current_user->user_email;
                            break;
                        case 'fn':
                            $field_config['wp_value'] = $current_user->first_name;
                            break;
                        case 'ln':
                            $field_config['wp_value'] = $current_user->last_name;
                            break;
                        case 'external_id':
                            $field_config['wp_value'] = (string) $current_user->ID;
                            break;
                    }
                }
                break;
                
            case 'auto':
                $field_config['query_key'] = isset($field_settings['query_key']) ? $field_settings['query_key'] : $field_key;
                break;
                
            case 'manual':
                $field_config['manual_value'] = isset($field_settings['manual_value']) ? $field_settings['manual_value'] : '';
                break;
        }
        
        $config[$field_key] = $field_config;
    }
    
    if (empty($config)) {
        return array();
    }
    
    return array(
        'config' => $config,
    );
}

/**
 * Output page-specific tracking events in footer
 */
function lcd_fb_output_page_events() {
    // Check if tracking is enabled
    if (!get_option('lcd_fb_tracking_enabled', 0)) {
        return;
    }
    
    $pixel_id = get_option('lcd_fb_pixel_id', '');
    if (empty($pixel_id)) {
        return;
    }
    
    // Only on singular pages
    if (!is_singular()) {
        return;
    }
    
    global $post;
    $events = get_post_meta($post->ID, '_lcd_fb_tracking_events', true);
    
    if (empty($events) || !is_array($events)) {
        return;
    }
    
    // Pass event configuration to JS
    wp_localize_script('lcd-fb-tracking', 'lcdFbTracking', array(
        'events' => $events,
    ));
}
add_action('wp_enqueue_scripts', 'lcd_fb_output_page_events', 20);

/**
 * Enqueue frontend tracking script
 */
function lcd_fb_enqueue_tracking_script() {
    // Check if tracking is enabled
    if (!get_option('lcd_fb_tracking_enabled', 0)) {
        return;
    }
    
    $pixel_id = get_option('lcd_fb_pixel_id', '');
    if (empty($pixel_id)) {
        return;
    }
    
    // Only on singular pages with events
    if (!is_singular()) {
        return;
    }
    
    global $post;
    $events = get_post_meta($post->ID, '_lcd_fb_tracking_events', true);
    
    if (empty($events) || !is_array($events)) {
        return;
    }
    
    wp_enqueue_script(
        'lcd-fb-tracking',
        get_template_directory_uri() . '/js/facebook-tracking.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
    
    wp_localize_script('lcd-fb-tracking', 'lcdFbTracking', array(
        'events' => $events,
    ));
}
add_action('wp_enqueue_scripts', 'lcd_fb_enqueue_tracking_script');
