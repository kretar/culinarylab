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
        
        // Set source to a notification sound
        audio.src = 'data:audio/wav;base64,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';
        
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

})(jQuery);