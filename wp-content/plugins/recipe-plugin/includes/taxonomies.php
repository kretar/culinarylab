<?php
/**
 * Register custom taxonomies for the Recipe Plugin
 *
 * @package Recipe_Plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the recipe taxonomies
 */
function recipe_plugin_register_taxonomies() {
    // Get current locale to decide which labels to use
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    
    // Recipe Category taxonomy
    $category_labels = $is_dutch ? 
        array(
            'name'                       => _x('Recept Categorieën', 'Taxonomy General Name', 'recipe-plugin'),
            'singular_name'              => _x('Recept Categorie', 'Taxonomy Singular Name', 'recipe-plugin'),
            'menu_name'                  => __('Categorieën', 'recipe-plugin'),
            'all_items'                  => __('Alle Categorieën', 'recipe-plugin'),
            'parent_item'                => __('Hoofdcategorie', 'recipe-plugin'),
            'parent_item_colon'          => __('Hoofdcategorie:', 'recipe-plugin'),
            'new_item_name'              => __('Nieuwe Categorie Naam', 'recipe-plugin'),
            'add_new_item'               => __('Nieuwe Categorie Toevoegen', 'recipe-plugin'),
            'edit_item'                  => __('Categorie Bewerken', 'recipe-plugin'),
            'update_item'                => __('Categorie Bijwerken', 'recipe-plugin'),
            'view_item'                  => __('Categorie Bekijken', 'recipe-plugin'),
            'separate_items_with_commas' => __('Categorieën scheiden met komma\'s', 'recipe-plugin'),
            'add_or_remove_items'        => __('Categorieën toevoegen of verwijderen', 'recipe-plugin'),
            'choose_from_most_used'      => __('Kies uit meest gebruikte categorieën', 'recipe-plugin'),
            'popular_items'              => __('Populaire Categorieën', 'recipe-plugin'),
            'search_items'               => __('Categorieën zoeken', 'recipe-plugin'),
            'not_found'                  => __('Niet gevonden', 'recipe-plugin'),
            'no_terms'                   => __('Geen categorieën', 'recipe-plugin'),
            'items_list'                 => __('Categorieën lijst', 'recipe-plugin'),
            'items_list_navigation'      => __('Categorieën lijst navigatie', 'recipe-plugin'),
        ) :
        array(
            'name'                       => _x('Recipe Categories', 'Taxonomy General Name', 'recipe-plugin'),
            'singular_name'              => _x('Recipe Category', 'Taxonomy Singular Name', 'recipe-plugin'),
            'menu_name'                  => __('Categories', 'recipe-plugin'),
            'all_items'                  => __('All Categories', 'recipe-plugin'),
            'parent_item'                => __('Parent Category', 'recipe-plugin'),
            'parent_item_colon'          => __('Parent Category:', 'recipe-plugin'),
            'new_item_name'              => __('New Category Name', 'recipe-plugin'),
            'add_new_item'               => __('Add New Category', 'recipe-plugin'),
            'edit_item'                  => __('Edit Category', 'recipe-plugin'),
            'update_item'                => __('Update Category', 'recipe-plugin'),
            'view_item'                  => __('View Category', 'recipe-plugin'),
            'separate_items_with_commas' => __('Separate categories with commas', 'recipe-plugin'),
            'add_or_remove_items'        => __('Add or remove categories', 'recipe-plugin'),
            'choose_from_most_used'      => __('Choose from the most used categories', 'recipe-plugin'),
            'popular_items'              => __('Popular Categories', 'recipe-plugin'),
            'search_items'               => __('Search Categories', 'recipe-plugin'),
            'not_found'                  => __('Not Found', 'recipe-plugin'),
            'no_terms'                   => __('No categories', 'recipe-plugin'),
            'items_list'                 => __('Categories list', 'recipe-plugin'),
            'items_list_navigation'      => __('Categories list navigation', 'recipe-plugin'),
        );

    $category_args = array(
        'labels'            => $category_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true, // Enable in Gutenberg
        'query_var'         => true,
        'rewrite'           => array('slug' => $is_dutch ? 'recept-categorie' : 'recipe-category'),
    );

    register_taxonomy('recipe_category', array('recipe'), $category_args);

    // Recipe Tag taxonomy
    $tag_labels = $is_dutch ? 
        array(
            'name'                       => _x('Recept Tags', 'Taxonomy General Name', 'recipe-plugin'),
            'singular_name'              => _x('Recept Tag', 'Taxonomy Singular Name', 'recipe-plugin'),
            'menu_name'                  => __('Tags', 'recipe-plugin'),
            'all_items'                  => __('Alle Tags', 'recipe-plugin'),
            'parent_item'                => __('Hoofdtag', 'recipe-plugin'),
            'parent_item_colon'          => __('Hoofdtag:', 'recipe-plugin'),
            'new_item_name'              => __('Nieuwe Tag Naam', 'recipe-plugin'),
            'add_new_item'               => __('Nieuwe Tag Toevoegen', 'recipe-plugin'),
            'edit_item'                  => __('Tag Bewerken', 'recipe-plugin'),
            'update_item'                => __('Tag Bijwerken', 'recipe-plugin'),
            'view_item'                  => __('Tag Bekijken', 'recipe-plugin'),
            'separate_items_with_commas' => __('Tags scheiden met komma\'s', 'recipe-plugin'),
            'add_or_remove_items'        => __('Tags toevoegen of verwijderen', 'recipe-plugin'),
            'choose_from_most_used'      => __('Kies uit meest gebruikte tags', 'recipe-plugin'),
            'popular_items'              => __('Populaire Tags', 'recipe-plugin'),
            'search_items'               => __('Tags zoeken', 'recipe-plugin'),
            'not_found'                  => __('Niet gevonden', 'recipe-plugin'),
            'no_terms'                   => __('Geen tags', 'recipe-plugin'),
            'items_list'                 => __('Tags lijst', 'recipe-plugin'),
            'items_list_navigation'      => __('Tags lijst navigatie', 'recipe-plugin'),
        ) :
        array(
            'name'                       => _x('Recipe Tags', 'Taxonomy General Name', 'recipe-plugin'),
            'singular_name'              => _x('Recipe Tag', 'Taxonomy Singular Name', 'recipe-plugin'),
            'menu_name'                  => __('Tags', 'recipe-plugin'),
            'all_items'                  => __('All Tags', 'recipe-plugin'),
            'parent_item'                => __('Parent Tag', 'recipe-plugin'),
            'parent_item_colon'          => __('Parent Tag:', 'recipe-plugin'),
            'new_item_name'              => __('New Tag Name', 'recipe-plugin'),
            'add_new_item'               => __('Add New Tag', 'recipe-plugin'),
            'edit_item'                  => __('Edit Tag', 'recipe-plugin'),
            'update_item'                => __('Update Tag', 'recipe-plugin'),
            'view_item'                  => __('View Tag', 'recipe-plugin'),
            'separate_items_with_commas' => __('Separate tags with commas', 'recipe-plugin'),
            'add_or_remove_items'        => __('Add or remove tags', 'recipe-plugin'),
            'choose_from_most_used'      => __('Choose from the most used tags', 'recipe-plugin'),
            'popular_items'              => __('Popular Tags', 'recipe-plugin'),
            'search_items'               => __('Search Tags', 'recipe-plugin'),
            'not_found'                  => __('Not Found', 'recipe-plugin'),
            'no_terms'                   => __('No tags', 'recipe-plugin'),
            'items_list'                 => __('Tags list', 'recipe-plugin'),
            'items_list_navigation'      => __('Tags list navigation', 'recipe-plugin'),
        );

    $tag_args = array(
        'labels'            => $tag_labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true, // Enable in Gutenberg
        'query_var'         => true,
        'rewrite'           => array('slug' => $is_dutch ? 'recept-tag' : 'recipe-tag'),
    );

    register_taxonomy('recipe_tag', array('recipe'), $tag_args);
}
add_action('init', 'recipe_plugin_register_taxonomies');