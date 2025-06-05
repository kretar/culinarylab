<?php
/**
 * Master Chef theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define constants
 */
define('MASTERCHEF_VERSION', '1.0.0');
define('MASTERCHEF_DIR', get_template_directory());
define('MASTERCHEF_URI', get_template_directory_uri());

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function masterchef_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for wide and full-width blocks
    add_theme_support('align-wide');

    // Add support for core block styles
    add_theme_support('wp-block-styles');

    // Register menu locations
    register_nav_menus(
        array(
            'main-menu' => esc_html__('Main Menu', 'masterchef'),
            'footer-menu' => esc_html__('Footer Menu', 'masterchef'),
        )
    );

    // Add theme support for Custom Logo
    add_theme_support(
        'custom-logo',
        array(
            'height' => 100,
            'width' => 300,
            'flex-width' => true,
            'flex-height' => true,
        )
    );

    // Dutch language support
    load_theme_textdomain('masterchef', MASTERCHEF_DIR . '/languages');
}
add_action('after_setup_theme', 'masterchef_setup');

/**
 * Enqueue scripts and styles.
 */
function masterchef_scripts() {
    // Main stylesheet
    wp_enqueue_style('masterchef-style', get_stylesheet_uri(), array(), MASTERCHEF_VERSION);
    
    // Recipe specific styles
    wp_enqueue_style('masterchef-recipe', MASTERCHEF_URI . '/assets/css/recipe.css', array(), MASTERCHEF_VERSION);
    
    // Front page specific styles
    if (is_front_page()) {
        wp_enqueue_style('masterchef-front-page', MASTERCHEF_URI . '/assets/css/front-page.css', array(), MASTERCHEF_VERSION);
    }
    
    // Search results page styles
    if (is_search()) {
        wp_enqueue_style('masterchef-search', MASTERCHEF_URI . '/assets/css/search.css', array(), MASTERCHEF_VERSION);
    }
    
    // Google Fonts for scientific theme
    wp_enqueue_style('masterchef-fonts', 'https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600&family=Space+Mono:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap', array(), null);
    
    // JavaScript files
    wp_enqueue_script('masterchef-navigation', MASTERCHEF_URI . '/assets/js/navigation.js', array(), MASTERCHEF_VERSION, true);
    
    // Recipe specific script
    if (is_singular('recipe')) {
        wp_enqueue_script('masterchef-recipe', MASTERCHEF_URI . '/assets/js/recipe.js', array('jquery'), MASTERCHEF_VERSION, true);
    }
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'masterchef_scripts');

/**
 * Modify search query for recipes
 */
function masterchef_recipe_search_query($query) {
    // Don't modify queries in the admin
    if (is_admin()) {
        return $query;
    }

    // Check if this is a search query and if we're searching for recipes
    if ($query->is_main_query() && $query->is_search()) {
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
        
        if ($post_type === 'recipe' || empty($post_type)) {
            // If specifically searching for recipes or it's a general search
            if ($post_type === 'recipe') {
                $query->set('post_type', 'recipe');
            }
            
            $search_term = $query->get('s');
            if (!empty($search_term)) {
                // Create a custom search configuration
                global $wpdb;
                
                // Clear the standard 's' parameter to prevent default WordPress search
                $query->set('s', '');
                
                // Define meta keys to search in
                $meta_keys = array(
                    '_recipe_ingredients',
                    '_recipe_instructions',
                );
                
                // Start building our custom WHERE clause
                $meta_search_sql = '';
                foreach ($meta_keys as $key) {
                    $meta_search_sql .= $wpdb->prepare(
                        " OR EXISTS (
                            SELECT * FROM $wpdb->postmeta 
                            WHERE $wpdb->postmeta.post_id = $wpdb->posts.ID 
                            AND $wpdb->postmeta.meta_key = %s 
                            AND $wpdb->postmeta.meta_value LIKE %s
                        )",
                        $key,
                        '%' . $wpdb->esc_like($search_term) . '%'
                    );
                }
                
                // Join the custom WHERE clause with the title and content search
                $search_sql = "
                    AND (
                        ($wpdb->posts.post_title LIKE %s)
                        OR ($wpdb->posts.post_content LIKE %s)
                        $meta_search_sql
                    )
                ";
                
                // Add our custom SQL to the query
                add_filter('posts_where', function($where) use ($wpdb, $search_term, $search_sql) {
                    return $where . $wpdb->prepare(
                        $search_sql,
                        '%' . $wpdb->esc_like($search_term) . '%',
                        '%' . $wpdb->esc_like($search_term) . '%'
                    );
                });
                
                // We need to do a GROUP BY to avoid duplicate results
                add_filter('posts_groupby', function($groupby) use ($wpdb) {
                    if (!$groupby) {
                        return "$wpdb->posts.ID";
                    }
                    return $groupby;
                });
            }
        }
    }
    
    return $query;
}
add_action('pre_get_posts', 'masterchef_recipe_search_query');

/**
 * Add custom classes to recipe posts while removing the post-type class
 */
function masterchef_custom_post_classes($classes, $class, $post_id) {
    // If this is a recipe post
    if (get_post_type($post_id) == 'recipe') {
        // Add experiment class
        $classes[] = 'experiment';
        
        // Remove the 'recipe' class (which is added because it's the post type)
        $recipe_class_key = array_search('recipe', $classes);
        if ($recipe_class_key !== false) {
            unset($classes[$recipe_class_key]);
        }
        
        // Remove the post-type-recipe class
        $post_type_recipe_key = array_search('post-type-recipe', $classes);
        if ($post_type_recipe_key !== false) {
            unset($classes[$post_type_recipe_key]);
        }
    }
    
    return $classes;
}
add_filter('post_class', 'masterchef_custom_post_classes', 10, 3);

/**
 * Require users to be logged in to comment
 */
function culinarylab_restrict_comments_to_members() {
    // If the user is not logged in, disable comments
    if (!is_user_logged_in()) {
        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        
        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);
    }
}
add_action('init', 'culinarylab_restrict_comments_to_members');


/**
 * Register widget areas
 */
function masterchef_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'masterchef'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'masterchef'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => esc_html__('Footer 1', 'masterchef'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'masterchef'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => esc_html__('Footer 2', 'masterchef'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'masterchef'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => esc_html__('Footer 3', 'masterchef'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'masterchef'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action('widgets_init', 'masterchef_widgets_init');

/**
 * Custom template tags for this theme.
 */
require MASTERCHEF_DIR . '/inc/template-tags.php';

/**
 * Recipe template functions
 */
require MASTERCHEF_DIR . '/inc/recipe-functions.php';