<?php
/**
 * Recipe-specific functions for Master Chef theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display recipe meta information (prep time, cook time, servings, difficulty)
 */
function masterchef_recipe_meta() {
    // Get recipe meta values
    $prep_time = get_post_meta(get_the_ID(), '_recipe_prep_time', true);
    $cook_time = get_post_meta(get_the_ID(), '_recipe_cook_time', true);
    $servings = get_post_meta(get_the_ID(), '_recipe_servings', true);
    $difficulty = get_post_meta(get_the_ID(), '_recipe_difficulty', true);
    
    // Set default icons
    $icons = array(
        'prep_time' => '⏱️',
        'cook_time' => '🍳',
        'servings' => '👥',
        'difficulty' => '📊',
    );
    
    // Set labels based on language
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $labels = $is_dutch ? array(
        'prep_time' => __('Voorbereidingstijd', 'masterchef'),
        'cook_time' => __('Kooktijd', 'masterchef'),
        'servings' => __('Porties', 'masterchef'),
        'difficulty' => __('Moeilijkheidsgraad', 'masterchef'),
        'minutes' => __('minuten', 'masterchef'),
        'easy' => __('Makkelijk', 'masterchef'),
        'medium' => __('Gemiddeld', 'masterchef'),
        'hard' => __('Moeilijk', 'masterchef'),
    ) : array(
        'prep_time' => __('Prep Time', 'masterchef'),
        'cook_time' => __('Cook Time', 'masterchef'),
        'servings' => __('Servings', 'masterchef'),
        'difficulty' => __('Difficulty', 'masterchef'),
        'minutes' => __('minutes', 'masterchef'),
        'easy' => __('Easy', 'masterchef'),
        'medium' => __('Medium', 'masterchef'),
        'hard' => __('Hard', 'masterchef'),
    );
    
    // Translate difficulty
    $difficulty_value = '';
    switch ($difficulty) {
        case 'easy':
            $difficulty_value = $labels['easy'];
            break;
        case 'medium':
            $difficulty_value = $labels['medium'];
            break;
        case 'hard':
            $difficulty_value = $labels['hard'];
            break;
        default:
            $difficulty_value = $labels['medium'];
    }
    
    // Only display meta section if we have some value
    if ($prep_time || $cook_time || $servings || $difficulty) {
        ?>
        <div class="recipe-meta">
            <?php if ($prep_time) : ?>
                <div class="recipe-meta-item recipe-prep-time">
                    <div class="recipe-meta-item-icon"><?php echo $icons['prep_time']; ?></div>
                    <span class="recipe-meta-item-label"><?php echo $labels['prep_time']; ?></span>
                    <span class="recipe-meta-item-value"><?php echo esc_html($prep_time); ?> <?php echo $labels['minutes']; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($cook_time) : ?>
                <div class="recipe-meta-item recipe-cook-time">
                    <div class="recipe-meta-item-icon"><?php echo $icons['cook_time']; ?></div>
                    <span class="recipe-meta-item-label"><?php echo $labels['cook_time']; ?></span>
                    <span class="recipe-meta-item-value"><?php echo esc_html($cook_time); ?> <?php echo $labels['minutes']; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($servings) : ?>
                <div class="recipe-meta-item recipe-servings">
                    <div class="recipe-meta-item-icon"><?php echo $icons['servings']; ?></div>
                    <span class="recipe-meta-item-label"><?php echo $labels['servings']; ?></span>
                    <span class="recipe-meta-item-value recipe-servings-value"><?php echo esc_html($servings); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($difficulty) : ?>
                <div class="recipe-meta-item recipe-difficulty">
                    <div class="recipe-meta-item-icon"><?php echo $icons['difficulty']; ?></div>
                    <span class="recipe-meta-item-label"><?php echo $labels['difficulty']; ?></span>
                    <span class="recipe-meta-item-value"><?php echo esc_html($difficulty_value); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display recipe servings adjustment control
 */
function masterchef_recipe_servings_control() {
    $servings = get_post_meta(get_the_ID(), '_recipe_servings', true);
    
    if ($servings) {
        $is_dutch = strpos(get_locale(), 'nl') !== false;
        $label = $is_dutch ? __('Pas aantal porties aan:', 'masterchef') : __('Adjust servings:', 'masterchef');
        
        ?>
        <div class="recipe-servings-control">
            <label class="recipe-servings-label"><?php echo $label; ?></label>
            <input type="range" 
                   class="recipe-servings-slider" 
                   min="1" 
                   max="<?php echo esc_attr($servings * 3); ?>" 
                   value="<?php echo esc_attr($servings); ?>" 
                   data-original-servings="<?php echo esc_attr($servings); ?>" />
            <div class="recipe-servings-display">
                <span class="recipe-servings-value"><?php echo esc_html($servings); ?></span>
            </div>
        </div>
        <?php
    }
}

/**
 * Display recipe print button
 */
function masterchef_recipe_print_button() {
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $button_text = $is_dutch ? __('Print recept', 'masterchef') : __('Print recipe', 'masterchef');
    
    ?>
    <a href="#" class="recipe-print-button">
        <span class="print-icon">🖨️</span>
        <span class="print-text"><?php echo $button_text; ?></span>
    </a>
    <?php
}

/**
 * Display recipe ingredients
 */
function masterchef_recipe_ingredients() {
    $ingredients = get_post_meta(get_the_ID(), '_recipe_ingredients', true);
    
    if (!empty($ingredients)) {
        $is_dutch = strpos(get_locale(), 'nl') !== false;
        $title = $is_dutch ? __('Ingrediënten', 'masterchef') : __('Ingredients', 'masterchef');
        
        ?>
        <div class="recipe-ingredients">
            <h3 class="recipe-ingredients-title"><?php echo $title; ?></h3>
            <ul>
                <?php
                // Split ingredients by line
                $ingredients_array = explode("\n", $ingredients);
                
                foreach ($ingredients_array as $ingredient) {
                    $ingredient = trim($ingredient);
                    
                    if (!empty($ingredient)) {
                        // Try to extract amount if formatted as "Amount - Unit - Ingredient"
                        $parts = explode('-', $ingredient);
                        
                        if (count($parts) >= 3) {
                            $amount = trim($parts[0]);
                            $unit = trim($parts[1]);
                            $name = trim(implode('-', array_slice($parts, 2)));
                            
                            echo '<li><span class="recipe-ingredient-quantity" data-original-amount="' . esc_attr($amount) . '">' . esc_html($amount) . '</span> ' . esc_html($unit) . ' ' . esc_html($name) . '</li>';
                        } else {
                            echo '<li>' . esc_html($ingredient) . '</li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }
}

/**
 * Display recipe instructions
 */
function masterchef_recipe_instructions() {
    $instructions = get_post_meta(get_the_ID(), '_recipe_instructions', true);
    
    if (!empty($instructions)) {
        $is_dutch = strpos(get_locale(), 'nl') !== false;
        $title = $is_dutch ? __('Bereidingswijze', 'masterchef') : __('Instructions', 'masterchef');
        
        ?>
        <div class="recipe-instructions">
            <h3 class="recipe-instructions-title"><?php echo $title; ?></h3>
            <ol>
                <?php
                // Split instructions by line
                $instructions_array = explode("\n", $instructions);
                
                foreach ($instructions_array as $instruction) {
                    $instruction = trim($instruction);
                    
                    if (!empty($instruction)) {
                        echo '<li>' . esc_html($instruction) . '</li>';
                    }
                }
                ?>
            </ol>
        </div>
        <?php
    }
}

/**
 * Display recipe taxonomies (categories and tags)
 */
function masterchef_recipe_taxonomies() {
    $categories = get_the_terms(get_the_ID(), 'recipe_category');
    $tags = get_the_terms(get_the_ID(), 'recipe_tag');
    
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $cat_title = $is_dutch ? __('Categorieën:', 'masterchef') : __('Categories:', 'masterchef');
    $tags_title = $is_dutch ? __('Tags:', 'masterchef') : __('Tags:', 'masterchef');
    
    if ($categories || $tags) {
        ?>
        <div class="recipe-tags">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <div class="recipe-categories">
                    <span class="recipe-taxonomies-title"><?php echo $cat_title; ?></span>
                    <?php
                    foreach ($categories as $category) {
                        echo '<span class="recipe-category"><a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></span>';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if ($tags && !is_wp_error($tags)) : ?>
                <div class="recipe-tags-list">
                    <span class="recipe-taxonomies-title"><?php echo $tags_title; ?></span>
                    <?php
                    foreach ($tags as $tag) {
                        echo '<span class="recipe-tag"><a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a></span>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display a recipe card for archives and listings
 * 
 * @param int|WP_Post $post Post ID or post object.
 */
function masterchef_recipe_card($post = null) {
    $post = get_post($post);
    if (!$post) {
        return;
    }
    
    // Get recipe meta values
    $prep_time = get_post_meta($post->ID, '_recipe_prep_time', true);
    $cook_time = get_post_meta($post->ID, '_recipe_cook_time', true);
    $difficulty = get_post_meta($post->ID, '_recipe_difficulty', true);
    
    // Calculate total time
    $total_time = 0;
    if ($prep_time) {
        $total_time += intval($prep_time);
    }
    if ($cook_time) {
        $total_time += intval($cook_time);
    }
    
    // Set labels based on language
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $labels = $is_dutch ? array(
        'time' => __('Tijd', 'masterchef'),
        'minutes' => __('min', 'masterchef'),
        'easy' => __('Makkelijk', 'masterchef'),
        'medium' => __('Gemiddeld', 'masterchef'),
        'hard' => __('Moeilijk', 'masterchef'),
        'read_more' => __('Bekijk recept', 'masterchef'),
    ) : array(
        'time' => __('Time', 'masterchef'),
        'minutes' => __('min', 'masterchef'),
        'easy' => __('Easy', 'masterchef'),
        'medium' => __('Medium', 'masterchef'),
        'hard' => __('Hard', 'masterchef'),
        'read_more' => __('View recipe', 'masterchef'),
    );
    
    // Translate difficulty
    $difficulty_value = '';
    switch ($difficulty) {
        case 'easy':
            $difficulty_value = $labels['easy'];
            break;
        case 'medium':
            $difficulty_value = $labels['medium'];
            break;
        case 'hard':
            $difficulty_value = $labels['hard'];
            break;
        default:
            $difficulty_value = $labels['medium'];
    }
    ?>
    <article id="post-<?php echo esc_attr($post->ID); ?>" <?php post_class('recipe-card'); ?>>
        <?php if (has_post_thumbnail($post)) : ?>
            <div class="recipe-card-image">
                <a href="<?php echo esc_url(get_permalink($post)); ?>">
                    <?php echo get_the_post_thumbnail($post, 'medium_large'); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="recipe-card-content">
            <h2 class="recipe-card-title">
                <a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a>
            </h2>
            
            <div class="recipe-card-meta">
                <?php if ($total_time) : ?>
                    <div class="recipe-card-time">
                        <span class="icon">⏱️</span>
                        <?php echo $labels['time'] . ': ' . esc_html($total_time) . ' ' . $labels['minutes']; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($difficulty) : ?>
                    <div class="recipe-card-difficulty">
                        <span class="icon">📊</span>
                        <?php echo esc_html($difficulty_value); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="recipe-card-excerpt">
                <?php echo wp_trim_words(get_the_excerpt($post), 20); ?>
            </div>
            
            <div class="recipe-card-footer">
                <div class="recipe-card-category">
                    <?php
                    $categories = get_the_terms($post->ID, 'recipe_category');
                    if ($categories && !is_wp_error($categories)) {
                        echo '<a href="' . esc_url(get_term_link($categories[0])) . '">' . esc_html($categories[0]->name) . '</a>';
                    }
                    ?>
                </div>
                
                <a href="<?php echo esc_url(get_permalink($post)); ?>" class="recipe-card-readmore">
                    <?php echo $labels['read_more']; ?>
                </a>
            </div>
        </div>
    </article>
    <?php
}