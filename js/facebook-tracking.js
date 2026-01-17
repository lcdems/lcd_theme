/**
 * Facebook Tracking Events Handler
 * 
 * Fires Facebook Pixel events based on page configuration.
 * Supports auto-detection of query parameters and manual values.
 */
(function() {
    'use strict';

    // Wait for DOM and fbq to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Check if fbq is available
        if (typeof fbq === 'undefined') {
            console.warn('Facebook Pixel not loaded');
            return;
        }

        // Check if we have event configuration
        if (typeof lcdFbTracking === 'undefined' || !lcdFbTracking.events) {
            return;
        }

        var events = lcdFbTracking.events;
        
        // Process each configured event
        events.forEach(function(eventConfig) {
            fireEvent(eventConfig);
        });
    });

    /**
     * Fire a single Facebook event
     */
    function fireEvent(eventConfig) {
        var eventType = eventConfig.event_type;
        var params = eventConfig.params || [];
        var eventData = {};
        var customEventName = null;

        // Build event data from parameters
        params.forEach(function(param) {
            var key = param.key;
            var value = null;

            if (param.source === 'auto') {
                // Auto-detect from query parameters
                var queryKey = param.query_key || key;
                value = getQueryParam(queryKey);
                
                // If no query param found, don't include this parameter
                if (value === null) {
                    return;
                }
            } else {
                // Manual value
                value = param.value;
                
                // Skip empty manual values
                if (value === '' || value === null || value === undefined) {
                    return;
                }
            }

            // Type conversion based on expected type
            value = convertValue(value, param.type);

            // Special handling for custom event name
            if (key === 'custom_event_name' && eventType === 'CustomEvent') {
                customEventName = value;
            } else {
                eventData[key] = value;
            }
        });

        // Determine the actual event name to fire
        var eventName = eventType;
        if (eventType === 'CustomEvent' && customEventName) {
            eventName = customEventName;
        }

        // Fire the event
        try {
            if (Object.keys(eventData).length > 0) {
                fbq('track', eventName, eventData);
                logEvent(eventName, eventData);
            } else {
                fbq('track', eventName);
                logEvent(eventName, null);
            }
        } catch (e) {
            console.error('Error firing Facebook event:', e);
        }
    }

    /**
     * Get a query parameter from the URL
     */
    function getQueryParam(key) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(key);
    }

    /**
     * Convert value to the appropriate type
     */
    function convertValue(value, type) {
        if (value === null || value === undefined) {
            return value;
        }

        switch (type) {
            case 'number':
                var num = parseFloat(value);
                return isNaN(num) ? 0 : num;
            
            case 'array':
                // If it's already an array, return it
                if (Array.isArray(value)) {
                    return value;
                }
                // Try to parse as JSON array
                try {
                    var parsed = JSON.parse(value);
                    if (Array.isArray(parsed)) {
                        return parsed;
                    }
                } catch (e) {
                    // Not valid JSON
                }
                // Split by comma if it contains commas
                if (typeof value === 'string' && value.indexOf(',') !== -1) {
                    return value.split(',').map(function(item) {
                        return item.trim();
                    });
                }
                // Return as single-item array
                return [value];
            
            case 'boolean':
                if (typeof value === 'boolean') {
                    return value;
                }
                return value === 'true' || value === '1' || value === 'yes';
            
            case 'string':
            default:
                return String(value);
        }
    }

    /**
     * Log event to console in development mode
     */
    function logEvent(eventName, eventData) {
        // Only log if we can detect debug mode (check for console and non-production)
        if (typeof console !== 'undefined' && console.log) {
            // Check if we're in a development environment
            var isDebug = window.location.hostname === 'localhost' || 
                          window.location.hostname.indexOf('.local') !== -1 ||
                          window.location.hostname.indexOf('.test') !== -1;
            
            if (isDebug) {
                console.log('📊 Facebook Pixel Event:', eventName, eventData || '(no data)');
            }
        }
    }

})();
