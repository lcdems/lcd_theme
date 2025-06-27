/**
 * Universal Modal System for LCD Theme
 * Handles all modal interactions across theme and plugins
 */

(function($) {
    'use strict';

    // Modal system namespace
    window.LCDModal = {
        // Configuration
        config: {
            backdropClass: 'lcd-modal-backdrop',
            modalClass: 'lcd-modal',
            activeClass: 'lcd-modal-active',
            animation: {
                duration: 300,
                easing: 'ease-in-out'
            },
            zIndexBase: 10000
        },

        // Active modals stack
        activeModals: [],

        // Initialize the modal system
        init: function() {
            this.createModalContainer();
            this.bindGlobalEvents();
            this.initializeExistingModals();
        },

        // Create the modal container if it doesn't exist
        createModalContainer: function() {
            if (!document.getElementById('lcd-modal-container')) {
                $('body').append('<div id="lcd-modal-container"></div>');
            }
        },

        // Bind global events
        bindGlobalEvents: function() {
            const self = this;

            // Escape key to close modals
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && self.activeModals.length > 0) {
                    self.close();
                }
            });

            // Click outside to close (if enabled)
            $(document).on('click', '.' + this.config.backdropClass, function(e) {
                if (e.target === this) {
                    const modal = $(this).find('.' + self.config.modalClass);
                    if (modal.data('close-on-backdrop') !== false) {
                        self.close();
                    }
                }
            });

            // Handle data-modal attributes
            $(document).on('click', '[data-modal]', function(e) {
                e.preventDefault();
                const options = $(this).data();
                self.open(options);
            });
        },

        // Main modal opening method
        open: function(options) {
            const settings = $.extend({
                type: 'content', // 'confirm', 'alert', 'form', 'content', 'iframe'
                title: '',
                content: '',
                size: 'medium', // 'small', 'medium', 'large', 'full'
                closable: true,
                closeOnBackdrop: true,
                buttons: [],
                ajax: null,
                beforeOpen: null,
                afterOpen: null,
                beforeClose: null,
                afterClose: null,
                className: '',
                data: {}
            }, options);

            // Execute beforeOpen callback
            if (typeof settings.beforeOpen === 'function') {
                const result = settings.beforeOpen.call(this, settings);
                if (result === false) return false;
            }

            // Create modal HTML
            const modalHtml = this.buildModalHtml(settings);
            const $backdrop = $(modalHtml);
            
            // Add to container
            $('#lcd-modal-container').append($backdrop);

            // Store settings for later use
            $backdrop.data('modal-settings', settings);

            // Add to active modals stack
            this.activeModals.push($backdrop[0]);

            // Handle AJAX content loading
            if (settings.ajax) {
                this.loadAjaxContent($backdrop, settings);
            }

            // Show modal with animation
            this.showModal($backdrop, settings);

            // Return modal element for chaining
            return $backdrop;
        },

        // Build modal HTML structure
        buildModalHtml: function(settings) {
            const modalId = 'lcd-modal-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            const sizeClass = 'lcd-modal-' + settings.size;
            const customClass = settings.className ? ' ' + settings.className : '';
            
            let html = `
                <div class="${this.config.backdropClass}" data-close-on-backdrop="${settings.closeOnBackdrop}">
                    <div class="${this.config.modalClass} ${sizeClass}${customClass}" id="${modalId}" role="dialog" aria-labelledby="${modalId}-title" aria-modal="true">
                        <div class="lcd-modal-content">
            `;

            // Header
            if (settings.title || settings.closable) {
                html += '<div class="lcd-modal-header">';
                if (settings.title) {
                    html += `<h2 class="lcd-modal-title" id="${modalId}-title">${settings.title}</h2>`;
                }
                if (settings.closable) {
                    html += '<button type="button" class="lcd-modal-close" aria-label="Close modal">&times;</button>';
                }
                html += '</div>';
            }

            // Body
            html += '<div class="lcd-modal-body">';
            if (settings.content) {
                html += settings.content;
            } else if (settings.ajax) {
                html += '<div class="lcd-modal-loading">Loading...</div>';
            }
            html += '</div>';

            // Footer with buttons
            if (settings.buttons && settings.buttons.length > 0) {
                html += '<div class="lcd-modal-footer">';
                settings.buttons.forEach(button => {
                    const btnClass = 'lcd-modal-btn ' + (button.className || 'lcd-btn-secondary');
                    const btnAttrs = button.attributes ? ' ' + button.attributes : '';
                    html += `<button type="button" class="${btnClass}" data-action="${button.action || ''}"${btnAttrs}>${button.text}</button>`;
                });
                html += '</div>';
            }

            html += `
                        </div>
                    </div>
                </div>
            `;

            return html;
        },

        // Show modal with animation
        showModal: function($backdrop, settings) {
            const self = this;
            const $modal = $backdrop.find('.' + this.config.modalClass);

            // Set z-index based on modal stack
            const zIndex = this.config.zIndexBase + (this.activeModals.length * 10);
            $backdrop.css('z-index', zIndex);

            // Add body class to prevent scrolling
            $('body').addClass('lcd-modal-open');

            // Animate in
            $backdrop.hide().fadeIn(this.config.animation.duration);
            $modal.css({
                transform: 'scale(0.9) translateY(-50px)',
                opacity: 0
            }).animate({
                opacity: 1
            }, {
                duration: this.config.animation.duration,
                step: function(now, fx) {
                    if (fx.prop === 'opacity') {
                        const scale = 0.9 + (now * 0.1);
                        const translateY = -50 + (now * 50);
                        $(this).css('transform', `scale(${scale}) translateY(${translateY}px)`);
                    }
                },
                complete: function() {
                    $(this).css('transform', '');
                    // Focus management
                    self.manageFocus($modal);
                    
                    // Execute afterOpen callback
                    if (typeof settings.afterOpen === 'function') {
                        settings.afterOpen.call(self, $backdrop, settings);
                    }
                }
            });

            // Bind close events
            this.bindCloseEvents($backdrop, settings);
        },

        // Load AJAX content
        loadAjaxContent: function($backdrop, settings) {
            const $body = $backdrop.find('.lcd-modal-body');
            const ajaxSettings = $.extend({
                type: 'POST',
                dataType: 'html',
                error: function() {
                    $body.html('<p class="lcd-modal-error">Failed to load content.</p>');
                },
                success: function(data) {
                    $body.html(data);
                }
            }, settings.ajax);

            $.ajax(ajaxSettings);
        },

        // Manage focus for accessibility
        manageFocus: function($modal) {
            // Store previously focused element
            $modal.data('previous-focus', document.activeElement);

            // Focus first focusable element in modal
            const focusableElements = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements.first().focus();
            }

            // Trap focus within modal
            $modal.on('keydown', function(e) {
                if (e.key === 'Tab') {
                    const firstFocusable = focusableElements.first()[0];
                    const lastFocusable = focusableElements.last()[0];

                    if (e.shiftKey) {
                        if (document.activeElement === firstFocusable) {
                            e.preventDefault();
                            lastFocusable.focus();
                        }
                    } else {
                        if (document.activeElement === lastFocusable) {
                            e.preventDefault();
                            firstFocusable.focus();
                        }
                    }
                }
            });
        },

        // Bind close events
        bindCloseEvents: function($backdrop, settings) {
            const self = this;

            // Close button
            $backdrop.find('.lcd-modal-close').on('click', function(e) {
                e.preventDefault();
                self.close();
            });

            // Action buttons
            $backdrop.find('.lcd-modal-btn').on('click', function(e) {
                const action = $(this).data('action');
                const buttonConfig = settings.buttons.find(btn => btn.action === action);
                
                if (buttonConfig && typeof buttonConfig.callback === 'function') {
                    const result = buttonConfig.callback.call(this, e, $backdrop, settings);
                    if (result !== false && buttonConfig.closeModal !== false) {
                        self.close();
                    }
                } else if (action === 'close' || action === 'cancel') {
                    self.close();
                }
            });
        },

        // Close modal
        close: function($backdrop = null) {
            const self = this;
            
            // If no specific modal provided, close the topmost one
            if (!$backdrop && this.activeModals.length > 0) {
                $backdrop = $(this.activeModals[this.activeModals.length - 1]);
            }

            if (!$backdrop || $backdrop.length === 0) return;

            const settings = $backdrop.data('modal-settings') || {};

            // Execute beforeClose callback
            if (typeof settings.beforeClose === 'function') {
                const result = settings.beforeClose.call(this, $backdrop, settings);
                if (result === false) return false;
            }

            const $modal = $backdrop.find('.' + this.config.modalClass);

            // Animate out
            $modal.animate({
                opacity: 0
            }, {
                duration: this.config.animation.duration,
                step: function(now, fx) {
                    if (fx.prop === 'opacity') {
                        const scale = 0.9 + (now * 0.1);
                        const translateY = -50 + (now * 50);
                        $(this).css('transform', `scale(${scale}) translateY(${translateY}px)`);
                    }
                },
                complete: function() {
                    $backdrop.fadeOut(self.config.animation.duration, function() {
                        // Restore focus
                        const previousFocus = $modal.data('previous-focus');
                        if (previousFocus) {
                            $(previousFocus).focus();
                        }

                        // Remove from DOM
                        $backdrop.remove();

                        // Remove from active modals stack
                        const index = self.activeModals.indexOf($backdrop[0]);
                        if (index > -1) {
                            self.activeModals.splice(index, 1);
                        }

                        // Remove body class if no more modals
                        if (self.activeModals.length === 0) {
                            $('body').removeClass('lcd-modal-open');
                        }

                        // Execute afterClose callback
                        if (typeof settings.afterClose === 'function') {
                            settings.afterClose.call(self, settings);
                        }
                    });
                }
            });
        },

        // Helper methods for common modal types
        confirm: function(options) {
            const settings = $.extend({
                type: 'confirm',
                title: 'Confirm Action',
                size: 'small',
                buttons: [
                    {
                        text: 'Cancel',
                        action: 'cancel',
                        className: 'lcd-btn-secondary'
                    },
                    {
                        text: 'Confirm',
                        action: 'confirm',
                        className: 'lcd-btn-primary'
                    }
                ]
            }, options);

            return new Promise((resolve) => {
                settings.buttons.forEach(button => {
                    if (button.action === 'confirm') {
                        button.callback = function() {
                            resolve(true);
                        };
                    } else if (button.action === 'cancel') {
                        button.callback = function() {
                            resolve(false);
                        };
                    }
                });

                settings.afterClose = function() {
                    resolve(false);
                };

                this.open(settings);
            });
        },

        alert: function(options) {
            const settings = $.extend({
                type: 'alert',
                title: 'Alert',
                size: 'small',
                buttons: [
                    {
                        text: 'OK',
                        action: 'close',
                        className: 'lcd-btn-primary'
                    }
                ]
            }, options);

            return this.open(settings);
        },

        // Initialize existing modals (for backward compatibility)
        initializeExistingModals: function() {
            // Convert existing jQuery UI dialogs
            $('.ui-dialog').each(function() {
                // Migration code for existing jQuery UI dialogs
            });
        },

        // Utility method to check if any modal is open
        hasOpenModal: function() {
            return this.activeModals.length > 0;
        },

        // Close all modals
        closeAll: function() {
            while (this.activeModals.length > 0) {
                this.close();
            }
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        LCDModal.init();
    });

    // Add jQuery plugin for convenience
    $.fn.lcdModal = function(options) {
        return this.each(function() {
            const $this = $(this);
            const data = $.extend({}, $this.data(), options);
            LCDModal.open(data);
        });
    };

})(jQuery); 