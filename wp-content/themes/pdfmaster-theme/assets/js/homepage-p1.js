/**
 * PDFMaster Homepage P1 Template JavaScript
 * Handles sticky trust bar, FAQ accordion, and smooth scrolling
 *
 * @package PDFMaster
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Sticky Trust Bar
     * Shows/hides trust bar based on scroll position
     */
    function initStickyTrustBar() {
        const trustBar = $('#trust-bar');

        if (!trustBar.length) {
            return;
        }

        let ticking = false;

        function updateTrustBar() {
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollY > 100) {
                trustBar.addClass('visible');
            } else {
                trustBar.removeClass('visible');
            }

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateTrustBar);
                ticking = true;
            }
        }

        $(window).on('scroll', requestTick);

        // Initial check
        updateTrustBar();
    }

    /**
     * FAQ Accordion
     * Toggle FAQ items on click
     */
    function initFAQAccordion() {
        $('.faq-question').on('click', function() {
            const $item = $(this).closest('.faq-item');
            const isActive = $item.hasClass('active');

            // Close all items
            $('.faq-item').removeClass('active');

            // Open clicked item if it wasn't active
            if (!isActive) {
                $item.addClass('active');
            }
        });
    }

    /**
     * Smooth Scroll
     * Smooth scrolling for anchor links
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');

            // Skip if href is just "#"
            if (href === '#') {
                e.preventDefault();
                return;
            }

            const $target = $(href);

            if ($target.length) {
                e.preventDefault();

                const offsetTop = $target.offset().top - 80; // Account for fixed header

                $('html, body').animate({
                    scrollTop: offsetTop
                }, 500, 'swing');
            }
        });
    }

    /**
     * Initialize all functions on document ready
     */
    $(document).ready(function() {
        initStickyTrustBar();
        initFAQAccordion();
        initSmoothScroll();
    });

})(jQuery);
