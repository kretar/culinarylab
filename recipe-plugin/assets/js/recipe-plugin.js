/**
 * Recipe Plugin JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize any dynamic functionality for recipes
        initRecipeFunctionality();
    });

    /**
     * Initialize recipe functionality
     */
    function initRecipeFunctionality() {
        // Printing functionality
        $('.recipe-print-button').on('click', function(e) {
            e.preventDefault();
            window.print();
        });

        // Ingredient quantity adjustment
        $('.recipe-serving-adjuster').on('change', function() {
            let newServings = parseInt($(this).val());
            let originalServings = parseInt($(this).data('original-servings'));
            
            if (newServings && originalServings) {
                let ratio = newServings / originalServings;
                
                $('.recipe-ingredient-amount').each(function() {
                    let originalAmount = parseFloat($(this).data('original-amount'));
                    if (!isNaN(originalAmount)) {
                        let newAmount = originalAmount * ratio;
                        // Format the number nicely (round to 2 decimal places if needed)
                        newAmount = Math.round(newAmount * 100) / 100;
                        
                        // If it's a whole number, don't show decimal places
                        if (newAmount === Math.floor(newAmount)) {
                            $(this).text(newAmount);
                        } else {
                            $(this).text(newAmount);
                        }
                    }
                });
            }
        });

        // Timer functionality
        $('.recipe-timer-button').on('click', function() {
            const minutes = $(this).data('minutes');
            if (minutes) {
                startTimer(minutes);
            }
        });
    }

    /**
     * Start a recipe timer
     */
    function startTimer(minutes) {
        // Basic implementation - could be enhanced with a proper UI
        const seconds = minutes * 60;
        const timerElement = $('<div>', {
            'class': 'recipe-timer-display',
            'html': formatTime(seconds)
        });
        
        // Add timer to page
        const timerContainer = $('<div>', {
            'class': 'recipe-timer-container'
        }).append(timerElement);
        
        const stopButton = $('<button>', {
            'class': 'recipe-timer-stop',
            'html': 'Stop Timer'
        }).on('click', function() {
            clearInterval(interval);
            timerContainer.remove();
        });
        
        timerContainer.append(stopButton);
        $('body').append(timerContainer);
        
        // Update every second
        let timeLeft = seconds;
        const interval = setInterval(function() {
            timeLeft--;
            
            if (timeLeft <= 0) {
                clearInterval(interval);
                timerElement.html('Time\'s up!');
                
                // Play sound or notification if available
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Recipe Timer', {
                        body: 'Your recipe timer is complete!'
                    });
                }
                
                // Fallback alert
                alert('Recipe timer complete!');
            } else {
                timerElement.html(formatTime(timeLeft));
            }
        }, 1000);
    }

    /**
     * Format seconds into MM:SS
     */
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');
    }

})(jQuery);