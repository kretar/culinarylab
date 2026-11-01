<?php
/**
 * Plugin Name: Recipe Plugin
 * Plugin URI: 
 * Description: A custom plugin to manage recipes for the Master Chef WordPress site
 * Version: 1.2.1
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
define('RECIPE_PLUGIN_VERSION', '1.2.1');
define('RECIPE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RECIPE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RECIPE_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once RECIPE_PLUGIN_DIR . 'includes/post-types.php';
require_once RECIPE_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once RECIPE_PLUGIN_DIR . 'includes/taxonomies.php';
require_once RECIPE_PLUGIN_DIR . 'includes/category-icons.php';

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
    // The 'init' hook has already run when a plugin is activated, so the post type and
    // taxonomies are not registered yet. Register them first, otherwise the flushed
    // rewrite rules lack the recipe URLs and every recipe returns a 404.
    recipe_plugin_register_post_types();
    recipe_plugin_register_taxonomies();

    // Flush rewrite rules on activation
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'recipe_plugin_activate');

/**
 * Flush the rewrite rules once whenever the plugin version changes.
 *
 * Installing or replacing the plugin (even in a differently named folder) leaves WordPress with
 * rewrite rules that do not know the recipe URLs, and every recipe returns a 404 until
 * permalinks are saved. Running late on 'init' makes sure the post type is registered first.
 */
function recipe_plugin_maybe_flush_rewrite_rules() {
    if (get_option('recipe_plugin_version') !== RECIPE_PLUGIN_VERSION) {
        flush_rewrite_rules();
        update_option('recipe_plugin_version', RECIPE_PLUGIN_VERSION);
    }
}
add_action('init', 'recipe_plugin_maybe_flush_rewrite_rules', 99);

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