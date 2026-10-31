<?php
/**
 * Custom template tags for Master Chef theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Displays the site logo, either default or custom.
 */
function masterchef_site_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title">' . get_bloginfo('name') . '</a>';
        
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
            echo '<p class="site-description">' . $description . '</p>';
        }
    }
}

/**
 * Displays the main navigation menu.
 */
function masterchef_main_navigation() {
    if (has_nav_menu('main-menu')) {
        wp_nav_menu(
            array(
                'theme_location' => 'main-menu',
                'menu_id'        => 'primary-menu',
                'container'      => 'nav',
                'container_class' => 'main-navigation',
                'menu_class'     => 'menu',
                'depth'          => 2,
            )
        );
    }
}

/**
 * Returns an inline SVG icon.
 *
 * Icons are inline SVG because the CSP does not allow a CDN icon font. They use
 * currentColor, so they follow the text colour, and carry no style attributes.
 *
 * @param string $name  Icon name, one of the keys below.
 * @param string $class Extra CSS class for the <svg> element.
 * @return string SVG markup, or an empty string for an unknown name.
 */
function masterchef_icon($name, $class = '') {
    $icons = array(
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'folder'   => '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
        'comment'  => '<path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
    );

    if (!isset($icons[$name])) {
        return '';
    }

    $class = trim('icon icon-' . $name . ' ' . $class);

    return '<svg class="' . esc_attr($class) . '" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[$name] . '</svg>';
}

/**
 * Displays post meta information
 */
function masterchef_post_meta() {
    echo '<div class="post-meta">';
    
    // Author
    echo '<span class="post-author">';
    echo '<span class="meta-icon">' . masterchef_icon('user') . '</span>';
    echo '<span class="meta-text">' . esc_html__('By', 'masterchef') . ' ';
    the_author_posts_link();
    echo '</span></span>';
    
    // Date
    echo '<span class="post-date">';
    echo '<span class="meta-icon">' . masterchef_icon('calendar') . '</span>';
    echo '<span class="meta-text">' . get_the_date() . '</span>';
    echo '</span>';
    
    // Categories
    if (has_category()) {
        echo '<span class="post-categories">';
        echo '<span class="meta-icon">' . masterchef_icon('folder') . '</span>';
        echo '<span class="meta-text">' . get_the_category_list(', ') . '</span>';
        echo '</span>';
    }
    
    // Comments
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="post-comments">';
        echo '<span class="meta-icon">' . masterchef_icon('comment') . '</span>';
        echo '<span class="meta-text">';
        comments_popup_link(
            __('No Comments', 'masterchef'),
            __('1 Comment', 'masterchef'),
            __('% Comments', 'masterchef')
        );
        echo '</span></span>';
    }
    
    echo '</div>';
}

/**
 * Displays the footer navigation menu.
 */
function masterchef_footer_navigation() {
    if (has_nav_menu('footer-menu')) {
        wp_nav_menu(
            array(
                'theme_location' => 'footer-menu',
                'menu_id'        => 'footer-menu',
                'container'      => 'nav',
                'container_class' => 'footer-navigation',
                'menu_class'     => 'menu',
                'depth'          => 1,
            )
        );
    }
}

/**
 * Displays the post thumbnail
 */
function masterchef_post_thumbnail($size = 'post-thumbnail') {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }

    echo '<div class="post-thumbnail">';
    the_post_thumbnail($size);
    echo '</div>';
}

/**
 * Displays pagination for archive pages
 */
function masterchef_pagination() {
    // Set direction labels based on language
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $previous_label = $is_dutch ? __('Vorige', 'masterchef') : __('Previous', 'masterchef');
    $next_label = $is_dutch ? __('Volgende', 'masterchef') : __('Next', 'masterchef');
    
    $args = array(
        'prev_text' => '<span class="nav-prev-text">' . $previous_label . '</span>',
        'next_text' => '<span class="nav-next-text">' . $next_label . '</span>',
    );

    the_posts_pagination($args);
}

/**
 * Displays post navigation
 */
function masterchef_post_navigation() {
    $previous = get_previous_post();
    $next = get_next_post();

    if (!$next && !$previous) {
        return;
    }

    // Set direction labels based on language
    $is_dutch = strpos(get_locale(), 'nl') !== false;
    $previous_label = $is_dutch ? __('Vorige', 'masterchef') : __('Previous', 'masterchef');
    $next_label = $is_dutch ? __('Volgende', 'masterchef') : __('Next', 'masterchef');
    $post_navigation_label = $is_dutch ? __('Receptnavigatie', 'masterchef') : __('Recipe navigation', 'masterchef');

    ?>
    <nav class="post-navigation">
        <h2 class="screen-reader-text"><?php echo esc_html($post_navigation_label); ?></h2>
        <div class="post-nav-links">
            <?php if ($previous) : ?>
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($previous)); ?>">
                        <?php if (has_post_thumbnail($previous)) : ?>
                            <div class="nav-thumb"><?php echo get_the_post_thumbnail($previous, 'thumbnail'); ?></div>
                        <?php endif; ?>
                        <div class="nav-text">
                            <span class="nav-title"><?php echo esc_html(get_the_title($previous)); ?></span>
                            <span class="nav-direction"><?php echo esc_html($previous_label); ?></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($next) : ?>
                <div class="nav-next">
                    <a href="<?php echo esc_url(get_permalink($next)); ?>">
                        <?php if (has_post_thumbnail($next)) : ?>
                            <div class="nav-thumb"><?php echo get_the_post_thumbnail($next, 'thumbnail'); ?></div>
                        <?php endif; ?>
                        <div class="nav-text">
                            <span class="nav-title"><?php echo esc_html(get_the_title($next)); ?></span>
                            <span class="nav-direction"><?php echo esc_html($next_label); ?></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}