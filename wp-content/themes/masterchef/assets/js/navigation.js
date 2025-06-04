/**
 * Master Chef - Navigation JavaScript
 */
(function() {
    'use strict';

    // Initialize navigation when the DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initNavigation();
    });

    /**
     * Initialize navigation functionality
     */
    function initNavigation() {
        // Mobile menu toggle functionality
        const navToggle = document.querySelector('.menu-toggle');
        
        if (navToggle) {
            const mainNav = document.querySelector('.main-navigation');
            
            navToggle.addEventListener('click', function() {
                mainNav.classList.toggle('toggled');
                
                if (mainNav.classList.contains('toggled')) {
                    navToggle.setAttribute('aria-expanded', 'true');
                } else {
                    navToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }
})();