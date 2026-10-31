<?php
/**
 * Icons for recipe categories: the icon set, the picker on the category screens and saving
 *
 * The chosen icon is stored as term meta (category_icon) holding the icon name. The theme
 * renders it with recipe_plugin_category_icon_svg() and falls back to the first letter of the
 * category when no icon is chosen.
 *
 * @package Recipe_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The available category icons: name => label and inline SVG shapes (24x24, stroke based).
 *
 * @return array
 */
function recipe_plugin_category_icons() {
    $is_dutch = strpos(get_locale(), 'nl') !== false;

    return array(
        'bbq' => array(
            'label' => $is_dutch ? 'Barbecue' : 'Barbecue',
            'svg'   => '<path d="M4 10h16a8 8 0 0 1-16 0z"/><path d="M9 18.5L7.5 22M15 18.5l1.5 3.5"/><path d="M9 6.5c0-1.2 1-1.7 1-3M14 6.5c0-1.2 1-1.7 1-3"/>',
        ),
        'salad' => array(
            'label' => $is_dutch ? 'Salade' : 'Salad',
            'svg'   => '<path d="M3 12h18a9 8 0 0 1-18 0z"/><path d="M7 12c-.5-3 1.5-5.5 4.5-5.5.5 3-1.5 5.5-4.5 5.5z"/><path d="M13 12c0-2.5 1.8-4.5 5-4.5 0 2.7-1.8 4.5-5 4.5z"/>',
        ),
        'dessert' => array(
            'label' => $is_dutch ? 'Dessert' : 'Dessert',
            'svg'   => '<path d="M6 13h12l-1.5 8h-9z"/><path d="M5 13a3.5 3.5 0 0 1 2-3.2 4 4 0 0 1 7.8-1A3.5 3.5 0 0 1 19 13z"/><path d="M12 5.5V3"/>',
        ),
        'cake' => array(
            'label' => $is_dutch ? 'Taart' : 'Cake',
            'svg'   => '<path d="M3 21h18"/><path d="M4 21v-7h16v7"/><path d="M4 14c0-2 1.5-3 3-3h10c1.5 0 3 1 3 3"/><path d="M12 11V8"/><path d="M12 8c-1 0-1.5-.8-1.5-1.5S12 4 12 4s1.5 1.8 1.5 2.5S13 8 12 8z"/>',
        ),
        'soup' => array(
            'label' => $is_dutch ? 'Soep' : 'Soup',
            'svg'   => '<path d="M3 11h18a9 8 0 0 1-18 0z"/><path d="M9 7c0-1.2 1-1.7 1-3M14 7c0-1.2 1-1.7 1-3"/><path d="M8 21h8"/>',
        ),
        'bread' => array(
            'label' => $is_dutch ? 'Brood' : 'Bread',
            'svg'   => '<path d="M4 10a8 5 0 0 1 16 0v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M9 8l1 3M13 7.5l1 3M17 8.5l.5 2"/>',
        ),
        'fish' => array(
            'label' => $is_dutch ? 'Vis' : 'Fish',
            'svg'   => '<path d="M2 12c2.5-4 6-6 10-6 2.5 0 4.5 1.2 6 3l4-3v12l-4-3c-1.5 1.8-3.5 3-6 3-4 0-7.5-2-10-6z"/><circle cx="7" cy="11" r=".8"/>',
        ),
        'meat' => array(
            'label' => $is_dutch ? 'Vlees' : 'Meat',
            'svg'   => '<path d="M14.5 3.5a6 6 0 0 1 5.5 8.500 6 6 0 0 1-7.500 3.200l-4 4a2 2 0 0 1-3-.5 2 2 0 0 1-.5-3l4-4a6 6 0 0 1 5.500-8.200z"/><path d="M15 8a2.500 2.500 0 0 1 2.500 2.500"/>',
        ),
        'drink' => array(
            'label' => $is_dutch ? 'Drank' : 'Drink',
            'svg'   => '<path d="M6 4h12l-1.5 15a2 2 0 0 1-2 1.8h-5a2 2 0 0 1-2-1.8z"/><path d="M6.8 10h10.4"/><path d="M14 4l2-3"/>',
        ),
        'fruit' => array(
            'label' => $is_dutch ? 'Fruit' : 'Fruit',
            'svg'   => '<path d="M12 8c-1.5-1.5-6-1.5-6 3.5 0 4.5 3 9 5.2 9 .6 0 .3-.5.8-.5s.2.5.8.5c2.2 0 5.2-4.5 5.2-9 0-5-4.5-5-6-3.5z"/><path d="M12 8c0-2 .5-3.5 2-4.5"/>',
        ),
        'egg' => array(
            'label' => $is_dutch ? 'Ei' : 'Egg',
            'svg'   => '<path d="M12 3c-3.5 0-6.5 6-6.5 11a6.5 6.5 0 0 0 13 0C18.5 9 15.5 3 12 3z"/>',
        ),
        'chef' => array(
            'label' => $is_dutch ? 'Algemeen' : 'General',
            'svg'   => '<path d="M7 14a4 4 0 0 1-1-7.5 4 4 0 0 1 6-2 4 4 0 0 1 6 2 4 4 0 0 1-1 7.5z"/><path d="M7 14v5h10v-5"/><path d="M7 17h10"/>',
        ),
    );
}

/**
 * Returns the inline SVG for a category icon, or an empty string for an unknown name.
 *
 * Inline SVG with currentColor and no style attributes, so it works under a strict CSP
 * and follows the text colour.
 *
 * @param string $name  Icon name, a key of recipe_plugin_category_icons().
 * @param string $class Extra CSS class for the <svg> element.
 * @return string
 */
function recipe_plugin_category_icon_svg($name, $class = '') {
    $icons = recipe_plugin_category_icons();

    if (!$name || !isset($icons[$name])) {
        return '';
    }

    $class = trim('recipe-category-icon-svg ' . $class);

    return '<svg class="' . esc_attr($class) . '" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[$name]['svg'] . '</svg>';
}

/**
 * Renders the icon picker: a "no icon" option and one radio button per icon.
 *
 * @param string $selected Currently selected icon name.
 */
function recipe_plugin_render_category_icon_picker($selected = '') {
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $none     = $is_dutch ? 'Geen icoon (eerste letter)' : 'No icon (first letter)';

    wp_nonce_field('recipe_category_icon', 'recipe_category_icon_nonce');
    ?>
    <div class="recipe-icon-picker">
        <label class="recipe-icon-option">
            <input type="radio" name="recipe_category_icon" value="" <?php checked($selected, ''); ?>>
            <span class="recipe-icon-option-icon">&ndash;</span>
            <span class="recipe-icon-option-name"><?php echo esc_html($none); ?></span>
        </label>
        <?php foreach (recipe_plugin_category_icons() as $name => $icon) : ?>
            <label class="recipe-icon-option">
                <input type="radio" name="recipe_category_icon" value="<?php echo esc_attr($name); ?>" <?php checked($selected, $name); ?>>
                <span class="recipe-icon-option-icon"><?php echo recipe_plugin_category_icon_svg($name); // phpcs:ignore WordPress.Security.EscapeOutput -- built from the fixed icon set above ?></span>
                <span class="recipe-icon-option-name"><?php echo esc_html($icon['label']); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Icon field on the "add category" form.
 */
function recipe_plugin_category_icon_add_field() {
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    ?>
    <div class="form-field">
        <label><?php echo esc_html($is_dutch ? 'Icoon' : 'Icon'); ?></label>
        <?php recipe_plugin_render_category_icon_picker(''); ?>
        <p><?php echo esc_html($is_dutch ? 'Wordt getoond bij de categorie op de startpagina.' : 'Shown with the category on the home page.'); ?></p>
    </div>
    <?php
}
add_action('recipe_category_add_form_fields', 'recipe_plugin_category_icon_add_field');

/**
 * Icon field on the "edit category" form.
 *
 * @param WP_Term $term The category being edited.
 */
function recipe_plugin_category_icon_edit_field($term) {
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $selected = get_term_meta($term->term_id, 'category_icon', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label><?php echo esc_html($is_dutch ? 'Icoon' : 'Icon'); ?></label></th>
        <td>
            <?php recipe_plugin_render_category_icon_picker($selected); ?>
            <p class="description"><?php echo esc_html($is_dutch ? 'Wordt getoond bij de categorie op de startpagina.' : 'Shown with the category on the home page.'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('recipe_category_edit_form_fields', 'recipe_plugin_category_icon_edit_field');

/**
 * Save the chosen icon. Only names from the fixed icon set are stored.
 *
 * @param int $term_id The saved term.
 */
function recipe_plugin_save_category_icon($term_id) {
    // Quick edit, imports and REST requests do not post the picker, so leave the icon alone
    if (!isset($_POST['recipe_category_icon_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['recipe_category_icon_nonce'])), 'recipe_category_icon')) {
        return;
    }

    if (!current_user_can('edit_term', $term_id)) {
        return;
    }

    $icon = isset($_POST['recipe_category_icon']) ? sanitize_key(wp_unslash($_POST['recipe_category_icon'])) : '';

    if ($icon && array_key_exists($icon, recipe_plugin_category_icons())) {
        update_term_meta($term_id, 'category_icon', $icon);
    } else {
        delete_term_meta($term_id, 'category_icon');
    }
}
add_action('created_recipe_category', 'recipe_plugin_save_category_icon');
add_action('edited_recipe_category', 'recipe_plugin_save_category_icon');

/**
 * Show the icon in the category list table.
 */
function recipe_plugin_category_icon_column($columns) {
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $new      = array();

    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ('name' === $key) {
            $new['category_icon'] = $is_dutch ? 'Icoon' : 'Icon';
        }
    }

    return $new;
}
add_filter('manage_edit-recipe_category_columns', 'recipe_plugin_category_icon_column');

function recipe_plugin_category_icon_column_content($content, $column, $term_id) {
    if ('category_icon' === $column) {
        $content = recipe_plugin_category_icon_svg(get_term_meta($term_id, 'category_icon', true));
    }

    return $content;
}
add_filter('manage_recipe_category_custom_column', 'recipe_plugin_category_icon_column_content', 10, 3);

/**
 * Load the picker styles on the recipe category screens only.
 */
function recipe_plugin_category_icon_admin_assets($hook) {
    if (!in_array($hook, array('edit-tags.php', 'term.php'), true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'recipe_category' !== $screen->taxonomy) {
        return;
    }

    wp_enqueue_style(
        'recipe-plugin-admin',
        RECIPE_PLUGIN_URL . 'assets/css/admin.css',
        array(),
        RECIPE_PLUGIN_VERSION
    );
}
add_action('admin_enqueue_scripts', 'recipe_plugin_category_icon_admin_assets');
