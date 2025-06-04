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
 * Displays post meta information
 */
function masterchef_post_meta() {
    echo '<div class="post-meta">';
    
    // Author
    echo '<span class="post-author">';
    echo '<span class="meta-icon"><i class="fas fa-user"></i></span>';
    echo '<span class="meta-text">' . esc_html__('By', 'masterchef') . ' ';
    the_author_posts_link();
    echo '</span></span>';
    
    // Date
    echo '<span class="post-date">';
    echo '<span class="meta-icon"><i class="fas fa-calendar"></i></span>';
    echo '<span class="meta-text">' . get_the_date() . '</span>';
    echo '</span>';
    
    // Categories
    if (has_category()) {
        echo '<span class="post-categories">';
        echo '<span class="meta-icon"><i class="fas fa-folder"></i></span>';
        echo '<span class="meta-text">' . get_the_category_list(', ') . '</span>';
        echo '</span>';
    }
    
    // Comments
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="post-comments">';
        echo '<span class="meta-icon"><i class="fas fa-comment"></i></span>';
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
    $args = array(
        'prev_text' => '<span class="nav-prev-text">' . __('Previous', 'masterchef') . '</span>',
        'next_text' => '<span class="nav-next-text">' . __('Next', 'masterchef') . '</span>',
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

    ?>
    <nav class="post-navigation">
        <h2 class="screen-reader-text"><?php _e('Post navigation', 'masterchef'); ?></h2>
        <div class="post-nav-links">
            <?php if ($previous) : ?>
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($previous)); ?>">
                        <?php if (has_post_thumbnail($previous)) : ?>
                            <div class="nav-thumb"><?php echo get_the_post_thumbnail($previous, 'thumbnail'); ?></div>
                        <?php endif; ?>
                        <div class="nav-text">
                            <span class="nav-title"><?php echo esc_html(get_the_title($previous)); ?></span>
                            <span class="nav-direction"><?php _e('Previous', 'masterchef'); ?></span>
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
                            <span class="nav-direction"><?php _e('Next', 'masterchef'); ?></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}