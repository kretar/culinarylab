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
define('MASTERCHEF_VERSION', '1.1.4');
define('MASTERCHEF_DIR', get_template_directory());
define('MASTERCHEF_URI', get_template_directory_uri());

/**
 * Disable Gutenberg block editor global inline styles
 */
function masterchef_disable_block_editor_styles() {
    // Only run on frontend, not in admin
    if (!is_admin()) {
        // Disable global styles for the block editor
        wp_deregister_style('wp-block-library'); 
        wp_deregister_style('wp-block-library-theme');
        wp_deregister_style('classic-theme-styles'); // Remove WordPress classic theme styles
        wp_deregister_style('wc-block-style');
    }
}
add_action('wp_print_styles', 'masterchef_disable_block_editor_styles', 100);

/**
 * Force all WordPress styles and scripts to be loaded from files (no inline)
 */
function masterchef_force_file_includes() {
    // Only enforce on frontend
    if (!is_admin()) {
        // Create a filter to force scripts to be loaded from files, not inline
        add_filter('script_loader_tag', function($tag, $handle) {
            // Look for scripts with inline data attached
            if (strpos($tag, 'data-') !== false || strpos($tag, 'type="text/template"') !== false) {
                // Extract script URL
                preg_match('/src=["\']([^"\']+)["\']/', $tag, $matches);
                if (isset($matches[1])) {
                    // Rewrite tag to only include src attribute
                    $tag = '<script src="' . esc_url($matches[1]) . '"></script>';
                }
            }
            return $tag;
        }, 999, 2);
        
        // Create a filter to prevent inline styles
        add_filter('style_loader_tag', function($tag, $handle) {
            // Remove inline CSS
            if (strpos($tag, 'data-') !== false || strpos($tag, '<style') !== false) {
                // Extract style URL
                preg_match('/href=["\']([^"\']+)["\']/', $tag, $matches);
                if (isset($matches[1])) {
                    // Rewrite tag to only include href attribute
                    $tag = '<link rel="stylesheet" href="' . esc_url($matches[1]) . '" />';
                }
            }
            return $tag;
        }, 999, 2);
        
        // Filter out speculationrules completely
        add_filter('wp_resource_hints', function($hints, $relation_type) {
            if ($relation_type === 'speculationrules') {
                return array(); // Return empty array to prevent speculative loading
            }
            return $hints;
        }, 10, 2);

        // Disable WordPress emoji scripts (these insert inline content)
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        
        // Remove speculation rules script
        remove_action('wp_head', 'wp_add_speculation_rules_tag');
        
        // Remove inline scripts added by WordPress
        remove_action('wp_head', 'wp_print_scripts');
        remove_action('wp_head', 'wp_print_head_scripts', 9);
        add_action('wp_footer', 'wp_print_scripts', 5);
        add_action('wp_footer', 'wp_print_head_scripts', 5);
    }
}
add_action('wp_loaded', 'masterchef_force_file_includes');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function masterchef_setup() {
    // Disable WordPress 5.9+ global styles and SVGs
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    
    // Remove speculationrules added in WordPress 6.4+
    remove_action('wp_head', 'wp_add_speculation_rules_tag');
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

    // Remove support for core block styles
    remove_theme_support('wp-block-styles');

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
 * Add a function to convert inline styles to external files
 */
function masterchef_inline_to_files() {
    global $wp_styles;
    
    // Only run on frontend
    if (is_admin() || !is_object($wp_styles)) {
        return;
    }
    
    // Process all registered styles
    foreach ($wp_styles->registered as $handle => $style) {
        // Check for inline styles
        if (!empty($wp_styles->registered[$handle]->extra['after'])) {
            $inline_css = implode("\n", $wp_styles->registered[$handle]->extra['after']);
            
            // Create a directory for extracted styles if it doesn't exist
            $css_dir = MASTERCHEF_DIR . '/assets/css/extracted';
            if (!file_exists($css_dir)) {
                wp_mkdir_p($css_dir);
            }
            
            // Generate a filename based on the handle
            $filename = sanitize_file_name('inline-' . $handle . '-' . substr(md5($inline_css), 0, 8) . '.css');
            $filepath = $css_dir . '/' . $filename;
            
            // Write the CSS to file
            file_put_contents($filepath, $inline_css);
            
            // Enqueue the extracted file and clear inline CSS
            wp_enqueue_style($handle . '-extracted', MASTERCHEF_URI . '/assets/css/extracted/' . $filename, array($handle), filemtime($filepath));
            $wp_styles->registered[$handle]->extra['after'] = array();
        }
    }
}
add_action('wp_head', 'masterchef_inline_to_files', 1);

/**
 * Directly remove speculationrules script
 */
function masterchef_remove_speculationrules() {
    // Remove directly from the output buffer
    ob_start(function($output) {
        // Remove speculationrules JSON scripts from head
        return preg_replace('/<script type="speculationrules">.*?<\/script>/s', '', $output);
    });
}
add_action('wp_head', 'masterchef_remove_speculationrules', 0);

/**
 * Add Content Security Policy
 */
function masterchef_add_csp_headers() {
    // Only apply CSP to frontend, not admin pages
    if (is_admin()) {
        return;
    }
    
    // Define CSP policy - IMPORTANT: removed 'unsafe-inline' since we're now enforcing file includes
    $csp = "default-src 'self'; " .
           "script-src 'self' https://fonts.googleapis.com https://ajax.googleapis.com; " .
           "style-src 'self' https://fonts.googleapis.com; " .
           "font-src 'self' data: https://fonts.gstatic.com; " .
           "img-src 'self' data: https:; " .
           "connect-src 'self'; " .
           "frame-src 'self'; " .
           "object-src 'none'; " .
           "base-uri 'self'; " .
           "frame-ancestors 'none'; " .
           "form-action 'self';";
    
    // Apply CSP in enforcement mode
    header("Content-Security-Policy: " . $csp);
    
    // Report-only mode (doesn't block anything, just reports violations)
    // header("Content-Security-Policy-Report-Only: " . $csp . " report-uri https://culinarylab.kretar.com/csp-report/");
}
add_action('send_headers', 'masterchef_add_csp_headers');

/**
 * Enables the HTTP Strict Transport Security (HSTS) header
 */
function masterchef_add_secure_header() {
    header( 'Strict-Transport-Security: max-age=31536000' );
    header( 'Referrer-Policy: same-origin' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
}
add_action( 'send_headers', 'masterchef_add_secure_header' );

/**
 * Remove global styles and SVG filters
 */
function masterchef_remove_global_styles() {
    // Only run on frontend, not in admin
    if (!is_admin()) {
        // Remove global styles
        wp_dequeue_style('global-styles');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('classic-theme-styles'); // Remove WordPress classic theme styles
        
        // Remove the SVG and global styles filter from wp_head
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    }
}
add_action('wp_enqueue_scripts', 'masterchef_remove_global_styles', 100);

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
    // Removed integrity attribute to comply with no-inline policy

    // JavaScript files
    wp_enqueue_script('masterchef-navigation', MASTERCHEF_URI . '/assets/js/navigation.js', array(), MASTERCHEF_VERSION, true);
    
    // Sound resources
    wp_enqueue_script('masterchef-sounds', MASTERCHEF_URI . '/assets/js/sounds.js', array(), MASTERCHEF_VERSION, true);
    
    // Recipe specific script
    if (is_singular('recipe')) {
        wp_enqueue_script('masterchef-recipe', MASTERCHEF_URI . '/assets/js/recipe.js', array('jquery'), MASTERCHEF_VERSION, true);
    }
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Add nonce for frontend AJAX requests - save to a custom JS file instead of inline
    $nonce_data = 'var masterchef_vars = ' . json_encode(array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('masterchef_nonce')
    )) . ';';
    
    // Create the directory if it doesn't exist
    $nonce_dir = MASTERCHEF_DIR . '/assets/js/generated';
    if (!file_exists($nonce_dir)) {
        wp_mkdir_p($nonce_dir);
    }
    
    // Write to a file (will be regenerated on page load)
    $nonce_file = $nonce_dir . '/nonce.js';
    file_put_contents($nonce_file, $nonce_data);
    
    // Enqueue the generated file
    wp_enqueue_script('masterchef-vars', MASTERCHEF_URI . '/assets/js/generated/nonce.js', array(), time(), true);
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

/**
 * Scientific comment callback function
 */
if (!function_exists('masterchef_scientific_comment')) :
    function masterchef_scientific_comment($comment, $args, $depth) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        $is_dutch = strpos(get_locale(), 'nl') !== false;
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('scientific-comment'); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-meta scientific-metadata">
                    <div class="comment-author">
                        <?php
                        if (0 != $args['avatar_size']) {
                            echo '<div class="scientific-avatar">';
                            echo get_avatar($comment, $args['avatar_size']);
                            echo '</div>';
                        }
                        ?>
                        <div class="scientific-author-info">
                            <span class="scientific-author-name"><?php echo get_comment_author_link(); ?></span>
                            <span class="scientific-timestamp">
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        printf(
                                            $is_dutch ? '%1$s om %2$s' : '%1$s at %2$s',
                                            get_comment_date(),
                                            get_comment_time()
                                        );
                                        ?>
                                    </time>
                                </a>
                                <span class="scientific-id">#<?php echo esc_html(substr(md5($comment->comment_ID . $comment->comment_author), 0, 8)); ?></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="comment-content scientific-data">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-metadata scientific-actions">
                    <?php
                    edit_comment_link(
                        $is_dutch ? 'Wijzig' : 'Edit',
                        '<span class="scientific-edit">',
                        '</span>'
                    );
                    ?>
                    <?php
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                                'before'    => '<span class="scientific-reply">',
                                'after'     => '</span>',
                            )
                        )
                    );
                    ?>
                </div>
            </article>
        </<?php echo $tag; ?>>
        <?php
    }
endif;