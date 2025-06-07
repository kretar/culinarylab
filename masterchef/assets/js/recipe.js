/**
 * Master Chef - Recipe JavaScript
 */
(function($) {
    'use strict';

    // Initialize the recipe functionality when the DOM is ready
    $(document).ready(function() {
        initServingsAdjustment();
        initPrintButton();
        initTimers();
        initIngredientCheckboxes();
    });

    /**
     * Initialize servings adjustment
     */
    function initServingsAdjustment() {
        const servingsSlider = $('.recipe-servings-slider');
        
        if (servingsSlider.length) {
            servingsSlider.on('input change', function() {
                const newServings = parseInt($(this).val());
                const originalServings = parseInt($(this).data('original-servings'));
                
                if (newServings && originalServings) {
                    // Update display of servings
                    $('.recipe-servings-value').text(newServings);
                    
                    // Calculate ratio
                    const ratio = newServings / originalServings;
                    
                    // Update all ingredient quantities
                    $('.recipe-ingredient-quantity').each(function() {
                        const originalAmount = parseFloat($(this).data('original-amount'));
                        if (!isNaN(originalAmount)) {
                            let newAmount = originalAmount * ratio;
                            
                            // Format to at most 2 decimal places
                            newAmount = Math.round(newAmount * 100) / 100;
                            
                            // If it's a whole number, display as an integer
                            if (newAmount === Math.floor(newAmount)) {
                                $(this).text(newAmount);
                            } else {
                                $(this).text(newAmount);
                            }
                        }
                    });
                }
            });
        }
    }

    /**
     * Initialize print button
     */
    function initPrintButton() {
        $('.recipe-print-button').on('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }

    /**
     * Initialize recipe timers
     */
    function initTimers() {
        $('.recipe-timer-button').on('click', function() {
            const minutes = $(this).data('minutes');
            if (minutes) {
                startTimer(minutes);
            }
        });
    }

    /**
     * Start a countdown timer
     */
    function startTimer(minutes) {
        // Convert minutes to seconds
        let timeLeft = minutes * 60;
        
        // Create timer display
        const timerDisplay = $('<div>', {
            'class': 'recipe-timer-display',
            'text': formatTime(timeLeft)
        });
        
        // Create timer container
        const timerContainer = $('<div>', {
            'class': 'recipe-timer-container'
        }).append(timerDisplay);
        
        // Add stop button
        const stopButton = $('<button>', {
            'class': 'recipe-timer-stop',
            'text': 'Stop Timer'
        }).on('click', function() {
            clearInterval(interval);
            timerContainer.remove();
        });
        
        // Append to timer container
        timerContainer.append(stopButton);
        
        // Add to page
        $('body').append(timerContainer);
        
        // Set interval to update timer
        const interval = setInterval(function() {
            timeLeft--;
            
            if (timeLeft <= 0) {
                clearInterval(interval);
                timerDisplay.text("Time's up!");
                
                // Play notification sound if possible
                playNotificationSound();
                
                // Show notification if permission granted
                showNotification("Recipe Timer", "Your timer is complete!");
            } else {
                timerDisplay.text(formatTime(timeLeft));
            }
        }, 1000);
    }

    /**
     * Format seconds to MM:SS
     */
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');
    }

    /**
     * Play a notification sound
     */
    function playNotificationSound() {
        // Create an audio element
        const audio = new Audio();
        
        // Set source to the notification sound (moved to external file)
        audio.src = NOTIFICATION_SOUND_URL;
        
        // Play the sound
        audio.play();
    }

    /**
     * Show browser notification
     */
    function showNotification(title, message) {
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: message,
                    icon: '/wp-content/themes/masterchef/assets/images/favicon.png'
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(function (permission) {
                    if (permission === 'granted') {
                        new Notification(title, {
                            body: message,
                            icon: '/wp-content/themes/masterchef/assets/images/favicon.png'
                        });
                    }
                });
            }
        }
    }
    
    /**
     * Initialize ingredient checkboxes for shopping list functionality
     */
    function initIngredientCheckboxes() {
        // Toggle checked state when clicking on ingredient items
        $('.recipe-ingredients li').on('click', function() {
            $(this).toggleClass('checked');
        });
    }

})(jQuery);