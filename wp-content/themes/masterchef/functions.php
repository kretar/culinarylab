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
    
    // Google Fonts
    wp_enqueue_style('masterchef-fonts', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap', array(), null);
    
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