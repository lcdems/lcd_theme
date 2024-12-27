/**
 * Main theme JavaScript file
 */

(function() {
    'use strict';

    // DOM elements
    const header = document.querySelector('.site-header');
    const backToTop = document.querySelector('.back-to-top');
    const newsletterForm = document.querySelector('.newsletter-form');

    /**
     * Handle sticky header
     */
    function handleStickyHeader() {
        if (header) {
            let lastScroll = 0;
            const scrollThreshold = 100; // Adjust this value to control when the header changes
            const bufferZone = 50; // Increased buffer zone to prevent flickering
            let isScrolled = false;
            let scrollTimeout;

            // Check initial scroll position
            if (window.scrollY > scrollThreshold + bufferZone) {
                isScrolled = true;
                header.classList.add('scrolled');
            }
         
            window.addEventListener('scroll', function() {
                // Clear the timeout if it exists
                if (scrollTimeout) {
                    window.cancelAnimationFrame(scrollTimeout);
                }

                // Clear switch back timeout if it exists


                // Use requestAnimationFrame for smooth performance
                scrollTimeout = window.requestAnimationFrame(function() {
                    const currentScroll = window.scrollY;

                    // Add hysteresis with buffer zone to prevent flickering
                    if (!isScrolled && currentScroll > scrollThreshold + bufferZone) {
                        isScrolled = true;
                        header.classList.add('scrolled');
                    } else if (isScrolled && currentScroll < scrollThreshold - bufferZone) {
                       
                            // Check the scroll position again before switching back
                            if (window.scrollY < scrollThreshold - bufferZone) {
                                isScrolled = false;
                                header.classList.remove('scrolled');
                            }
                    }

                    // Handle header visibility
                    if (currentScroll <= 0) {
                        header.classList.remove('scrolled-down');
                        header.classList.remove('scroll-up');
                    } else if (currentScroll > lastScroll && !header.classList.contains('scrolled-down')) {
                        // Scroll Down
                        header.classList.remove('scroll-up');
                        header.classList.add('scrolled-down');
                    } else if (currentScroll < lastScroll && header.classList.contains('scrolled-down')) {
                        // Scroll Up
                        header.classList.remove('scrolled-down');
                        header.classList.add('scroll-up');
                    }

                    lastScroll = currentScroll;
                });
            }, { passive: true }); // Add passive flag for better scroll performance
        }
    }

    /**
     * Handle back to top button
     */
    function handleBackToTop() {
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 100) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });

            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    /**
     * Handle newsletter form submission
     */
    function handleNewsletterForm() {
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[type="email"]').value;
                const button = this.querySelector('button[type="submit"]');
                const originalButtonText = button.textContent;

                // Disable form during submission
                button.disabled = true;
                button.textContent = 'Subscribing...';

                // Here you would typically make an AJAX call to your newsletter service
                // For now, we'll just simulate a successful subscription
                setTimeout(function() {
                    button.textContent = 'Subscribed!';
                    button.classList.add('success');
                    
                    setTimeout(function() {
                        button.disabled = false;
                        button.textContent = originalButtonText;
                        button.classList.remove('success');
                    }, 2000);
                }, 1000);
            });
        }
    }

    /**
     * Handle smooth scrolling for anchor links
     */
    function handleSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href !== '#') {
                    e.preventDefault();
                    
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    /**
     * Initialize all functions
     */
    function init() {
        handleStickyHeader();
        handleBackToTop();
        handleNewsletterForm();
        handleSmoothScroll();
    }

    // Run when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(); 