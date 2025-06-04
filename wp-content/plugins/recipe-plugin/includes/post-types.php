<?php
/**
 * Register custom post types for the Recipe Plugin
 *
 * @package Recipe_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the 'recipe' custom post type
 */
function recipe_plugin_register_post_types() {
    $labels = array(
        'name'                  => _x('Recipes', 'Post type general name', 'recipe-plugin'),
        'singular_name'         => _x('Recipe', 'Post type singular name', 'recipe-plugin'),
        'menu_name'             => _x('Recipes', 'Admin Menu text', 'recipe-plugin'),
        'name_admin_bar'        => _x('Recipe', 'Add New on Toolbar', 'recipe-plugin'),
        'add_new'               => __('Add New', 'recipe-plugin'),
        'add_new_item'          => __('Add New Recipe', 'recipe-plugin'),
        'new_item'              => __('New Recipe', 'recipe-plugin'),
        'edit_item'             => __('Edit Recipe', 'recipe-plugin'),
        'view_item'             => __('View Recipe', 'recipe-plugin'),
        'all_items'             => __('All Recipes', 'recipe-plugin'),
        'search_items'          => __('Search Recipes', 'recipe-plugin'),
        'parent_item_colon'     => __('Parent Recipes:', 'recipe-plugin'),
        'not_found'             => __('No recipes found.', 'recipe-plugin'),
        'not_found_in_trash'    => __('No recipes found in Trash.', 'recipe-plugin'),
        'featured_image'        => _x('Recipe Cover Image', 'Overrides the "Featured Image" phrase', 'recipe-plugin'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'recipe-plugin'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'recipe-plugin'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'recipe-plugin'),
        'archives'              => _x('Recipe archives', 'The post type archive label used in nav menus', 'recipe-plugin'),
        'insert_into_item'      => _x('Insert into recipe', 'Overrides the "Insert into post" phrase', 'recipe-plugin'),
        'uploaded_to_this_item' => _x('Uploaded to this recipe', 'Overrides the "Uploaded to this post" phrase', 'recipe-plugin'),
        'filter_items_list'     => _x('Filter recipes list', 'Screen reader text for the filter links', 'recipe-plugin'),
        'items_list_navigation' => _x('Recipes list navigation', 'Screen reader text for the pagination', 'recipe-plugin'),
        'items_list'            => _x('Recipes list', 'Screen reader text for the items list', 'recipe-plugin'),
    );

    // Dutch labels
    $nl_labels = array(
        'name'                  => _x('Recepten', 'Post type general name', 'recipe-plugin'),
        'singular_name'         => _x('Recept', 'Post type singular name', 'recipe-plugin'),
        'menu_name'             => _x('Recepten', 'Admin Menu text', 'recipe-plugin'),
        'name_admin_bar'        => _x('Recept', 'Add New on Toolbar', 'recipe-plugin'),
        'add_new'               => __('Nieuwe toevoegen', 'recipe-plugin'),
        'add_new_item'          => __('Nieuw recept toevoegen', 'recipe-plugin'),
        'new_item'              => __('Nieuw recept', 'recipe-plugin'),
        'edit_item'             => __('Recept bewerken', 'recipe-plugin'),
        'view_item'             => __('Recept bekijken', 'recipe-plugin'),
        'all_items'             => __('Alle recepten', 'recipe-plugin'),
        'search_items'          => __('Recepten zoeken', 'recipe-plugin'),
        'parent_item_colon'     => __('Bovenliggende recepten:', 'recipe-plugin'),
        'not_found'             => __('Geen recepten gevonden.', 'recipe-plugin'),
        'not_found_in_trash'    => __('Geen recepten gevonden in de prullenbak.', 'recipe-plugin'),
        'featured_image'        => _x('Recept omslagafbeelding', 'Overrides the "Featured Image" phrase', 'recipe-plugin'),
        'set_featured_image'    => _x('Omslagafbeelding instellen', 'Overrides the "Set featured image" phrase', 'recipe-plugin'),
        'remove_featured_image' => _x('Omslagafbeelding verwijderen', 'Overrides the "Remove featured image" phrase', 'recipe-plugin'),
        'use_featured_image'    => _x('Gebruik als omslagafbeelding', 'Overrides the "Use as featured image" phrase', 'recipe-plugin'),
        'archives'              => _x('Recept archieven', 'The post type archive label used in nav menus', 'recipe-plugin'),
        'insert_into_item'      => _x('Invoegen in recept', 'Overrides the "Insert into post" phrase', 'recipe-plugin'),
        'uploaded_to_this_item' => _x('Geüpload naar dit recept', 'Overrides the "Uploaded to this post" phrase', 'recipe-plugin'),
        'filter_items_list'     => _x('Receptenlijst filteren', 'Screen reader text for the filter links', 'recipe-plugin'),
        'items_list_navigation' => _x('Receptenlijst navigatie', 'Screen reader text for the pagination', 'recipe-plugin'),
        'items_list'            => _x('Receptenlijst', 'Screen reader text for the items list', 'recipe-plugin'),
    );
    
    // Use Dutch labels if Dutch is the current locale
    if (strpos(get_locale(), 'nl') !== false) {
        $labels = $nl_labels;
    }

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'recipe'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-food',
        'supports'           => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'comments',
            'revisions',
            'custom-fields',
        ),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('recipe', $args);
}
add_action('init', 'recipe_plugin_register_post_types');