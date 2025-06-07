<?php
/**
 * Plugin Name: Recipe Plugin
 * Plugin URI: 
 * Description: A custom plugin to manage recipes for the Master Chef WordPress site
 * Version: 1.1.4
 * Author: 
 * Author URI: 
 * Text Domain: recipe-plugin
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('RECIPE_PLUGIN_VERSION', '1.1.4');
define('RECIPE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RECIPE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RECIPE_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once RECIPE_PLUGIN_DIR . 'includes/post-types.php';
require_once RECIPE_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once RECIPE_PLUGIN_DIR . 'includes/taxonomies.php';

/**
 * Load plugin text domain for translations
 */
function recipe_plugin_load_textdomain() {
    load_plugin_textdomain(
        'recipe-plugin',
        false,
        dirname(RECIPE_PLUGIN_BASENAME) . '/languages/'
    );
}
add_action('plugins_loaded', 'recipe_plugin_load_textdomain');

/**
 * Activation hook
 */
function recipe_plugin_activate() {
    // Flush rewrite rules on activation
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'recipe_plugin_activate');

/**
 * Deactivation hook
 */
function recipe_plugin_deactivate() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'recipe_plugin_deactivate');

/**
 * Enqueue CSS and JS for the plugin
 */
function recipe_plugin_enqueue_scripts() {
    // Add CSS
    wp_enqueue_style(
        'recipe-plugin-css',
        RECIPE_PLUGIN_URL . 'assets/css/recipe-plugin.css',
        array(),
        RECIPE_PLUGIN_VERSION,
        'all'
    );
    
    // Add JS
    wp_enqueue_script(
        'recipe-plugin-js',
        RECIPE_PLUGIN_URL . 'assets/js/recipe-plugin.js',
        array('jquery'),
        RECIPE_PLUGIN_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'recipe_plugin_enqueue_scripts');