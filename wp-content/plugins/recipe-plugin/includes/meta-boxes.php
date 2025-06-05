<?php
/**
 * Meta boxes for the Recipe Plugin
 *
 * @package Recipe_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register meta boxes for the recipe post type
 */
function recipe_plugin_add_meta_boxes() {
    add_meta_box(
        'recipe_details',
        __('Recipe Details', 'recipe-plugin'),
        'recipe_plugin_recipe_details_callback',
        'recipe',
        'normal',
        'high'
    );

    add_meta_box(
        'recipe_source',
        __('Recipe Source', 'recipe-plugin'),
        'recipe_plugin_recipe_source_callback',
        'recipe',
        'normal',
        'high'
    );

    add_meta_box(
        'recipe_ingredients',
        __('Recipe Ingredients', 'recipe-plugin'),
        'recipe_plugin_recipe_ingredients_callback',
        'recipe',
        'normal',
        'high'
    );

    add_meta_box(
        'recipe_instructions',
        __('Recipe Instructions', 'recipe-plugin'),
        'recipe_plugin_recipe_instructions_callback',
        'recipe',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'recipe_plugin_add_meta_boxes');

/**
 * Render Recipe Details meta box
 */
function recipe_plugin_recipe_details_callback($post) {
    wp_nonce_field('recipe_plugin_save_meta', 'recipe_plugin_meta_nonce');

    // Get saved values
    $prep_time = get_post_meta($post->ID, '_recipe_prep_time', true);
    $cook_time = get_post_meta($post->ID, '_recipe_cook_time', true);
    $servings = get_post_meta($post->ID, '_recipe_servings', true);
    $difficulty = get_post_meta($post->ID, '_recipe_difficulty', true);
    
    // Dutch labels based on locale
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $labels = array(
        'prep_time' => $is_dutch ? 'Voorbereidingstijd (minuten)' : 'Preparation Time (minutes)',
        'cook_time' => $is_dutch ? 'Kooktijd (minuten)' : 'Cooking Time (minutes)',
        'servings' => $is_dutch ? 'Aantal porties' : 'Number of Servings',
        'difficulty' => $is_dutch ? 'Moeilijkheidsgraad' : 'Difficulty Level',
        'easy' => $is_dutch ? 'Makkelijk' : 'Easy',
        'medium' => $is_dutch ? 'Gemiddeld' : 'Medium',
        'hard' => $is_dutch ? 'Moeilijk' : 'Hard'
    );
    ?>
    <div class="recipe-details-meta">
        <p>
            <label for="recipe-prep-time"><?php echo esc_html($labels['prep_time']); ?>:</label><br>
            <input type="number" id="recipe-prep-time" name="recipe_prep_time" value="<?php echo esc_attr($prep_time); ?>" min="0">
        </p>
        
        <p>
            <label for="recipe-cook-time"><?php echo esc_html($labels['cook_time']); ?>:</label><br>
            <input type="number" id="recipe-cook-time" name="recipe_cook_time" value="<?php echo esc_attr($cook_time); ?>" min="0">
        </p>
        
        <p>
            <label for="recipe-servings"><?php echo esc_html($labels['servings']); ?>:</label><br>
            <input type="number" id="recipe-servings" name="recipe_servings" value="<?php echo esc_attr($servings); ?>" min="1">
        </p>
        
        <p>
            <label for="recipe-difficulty"><?php echo esc_html($labels['difficulty']); ?>:</label><br>
            <select id="recipe-difficulty" name="recipe_difficulty">
                <option value="easy" <?php selected($difficulty, 'easy'); ?>><?php echo esc_html($labels['easy']); ?></option>
                <option value="medium" <?php selected($difficulty, 'medium'); ?>><?php echo esc_html($labels['medium']); ?></option>
                <option value="hard" <?php selected($difficulty, 'hard'); ?>><?php echo esc_html($labels['hard']); ?></option>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Render Recipe Source meta box
 */
function recipe_plugin_recipe_source_callback($post) {
    $source = get_post_meta($post->ID, '_recipe_source', true);
    $source_url = get_post_meta($post->ID, '_recipe_source_url', true);
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $title = $is_dutch ? 'Bron' : 'Source';
    $source_label = $is_dutch ? 'Naam van de bron (boek, website, etc.)' : 'Source name (book, website, etc.)';
    $url_label = $is_dutch ? 'URL van de bron (indien van toepassing)' : 'Source URL (if applicable)';
    ?>
    <div class="recipe-source-meta">
        <p>
            <label for="recipe-source"><?php echo esc_html($source_label); ?>:</label><br>
            <input type="text" id="recipe-source" name="recipe_source" value="<?php echo esc_attr($source); ?>" style="width: 100%;">
        </p>
        
        <p>
            <label for="recipe-source-url"><?php echo esc_html($url_label); ?>:</label><br>
            <input type="url" id="recipe-source-url" name="recipe_source_url" value="<?php echo esc_attr($source_url); ?>" style="width: 100%;">
        </p>
    </div>
    <?php
}

/**
 * Render Recipe Ingredients meta box
 */
function recipe_plugin_recipe_ingredients_callback($post) {
    $ingredients = get_post_meta($post->ID, '_recipe_ingredients', true);
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $label = $is_dutch ? 'Ingrediënten' : 'Ingredients';
    $description = $is_dutch ? 'Voeg één ingrediënt per regel toe. Voor een gestructureerd formaat, gebruik: Hoeveelheid - Maateenheid - Ingrediënt (bijv. 2 - eetlepels - olijfolie)' : 
                              'Add one ingredient per line. For structured format, use: Amount - Unit - Ingredient (e.g. 2 - tablespoons - olive oil)';
    ?>
    <div class="recipe-ingredients-meta">
        <p>
            <label for="recipe-ingredients"><?php echo esc_html($label); ?>:</label><br>
            <small><?php echo esc_html($description); ?></small><br>
            <textarea id="recipe-ingredients" name="recipe_ingredients" rows="10" style="width: 100%;"><?php echo esc_textarea($ingredients); ?></textarea>
        </p>
    </div>
    <?php
}

/**
 * Render Recipe Instructions meta box
 */
function recipe_plugin_recipe_instructions_callback($post) {
    $instructions = get_post_meta($post->ID, '_recipe_instructions', true);
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    $label = $is_dutch ? 'Bereidingswijze' : 'Instructions';
    $description = $is_dutch ? 'Voeg één stap per regel toe.' : 'Add one step per line.';
    ?>
    <div class="recipe-instructions-meta">
        <p>
            <label for="recipe-instructions"><?php echo esc_html($label); ?>:</label><br>
            <small><?php echo esc_html($description); ?></small><br>
            <textarea id="recipe-instructions" name="recipe_instructions" rows="10" style="width: 100%;"><?php echo esc_textarea($instructions); ?></textarea>
        </p>
    </div>
    <?php
}

/**
 * Save recipe meta box data
 */
function recipe_plugin_save_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['recipe_plugin_meta_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['recipe_plugin_meta_nonce'], 'recipe_plugin_save_meta')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'recipe' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Update recipe details
    if (isset($_POST['recipe_prep_time'])) {
        update_post_meta($post_id, '_recipe_prep_time', sanitize_text_field($_POST['recipe_prep_time']));
    }
    
    if (isset($_POST['recipe_cook_time'])) {
        update_post_meta($post_id, '_recipe_cook_time', sanitize_text_field($_POST['recipe_cook_time']));
    }
    
    if (isset($_POST['recipe_servings'])) {
        update_post_meta($post_id, '_recipe_servings', sanitize_text_field($_POST['recipe_servings']));
    }
    
    if (isset($_POST['recipe_difficulty'])) {
        update_post_meta($post_id, '_recipe_difficulty', sanitize_text_field($_POST['recipe_difficulty']));
    }
    
    // Update recipe source information
    if (isset($_POST['recipe_source'])) {
        update_post_meta($post_id, '_recipe_source', sanitize_text_field($_POST['recipe_source']));
    }
    
    if (isset($_POST['recipe_source_url'])) {
        update_post_meta($post_id, '_recipe_source_url', esc_url_raw($_POST['recipe_source_url']));
    }
    
    // Update ingredients
    if (isset($_POST['recipe_ingredients'])) {
        update_post_meta($post_id, '_recipe_ingredients', sanitize_textarea_field($_POST['recipe_ingredients']));
    }
    
    // Update instructions
    if (isset($_POST['recipe_instructions'])) {
        update_post_meta($post_id, '_recipe_instructions', sanitize_textarea_field($_POST['recipe_instructions']));
    }
}
add_action('save_post', 'recipe_plugin_save_meta');