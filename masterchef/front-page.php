<?php
/**
 * The template for displaying the front page
 */

get_header();
?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <?php
            $is_dutch = strpos(get_locale(), 'nl') !== false;
            $title = $is_dutch ? __('CulinaryLab Recepten', 'culinarylab') : __('CulinaryLab Recipes', 'culinarylab');
            $subtitle = $is_dutch ? __('Ontdek culinaire experimenten met een wetenschappelijke benadering', 'culinarylab') : __('Discover culinary experiments with a scientific approach', 'culinarylab');
            ?>
            <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html($subtitle); ?></p>
            
            <!-- Recipe Search Area -->
            <div class="recipe-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label class="screen-reader-text"><?php echo $is_dutch ? __('Zoeken naar:', 'masterchef') : __('Search for:', 'masterchef'); ?></label>
                    <input type="search" class="search-field" placeholder="<?php echo $is_dutch ? esc_attr__('Zoek recepten...', 'masterchef') : esc_attr__('Search recipes...', 'masterchef'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <input type="hidden" name="post_type" value="recipe" />
                    <button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo $is_dutch ? __('Zoeken', 'masterchef') : __('Search', 'masterchef'); ?></span>🔍</button>
                </form>
                
                <div class="recipe-search-filters">
                    <?php
                    // Get recipe categories for quick filters
                    $categories = get_terms(array(
                        'taxonomy' => 'recipe_category',
                        'hide_empty' => true,
                        'number' => 5,
                    ));
                    
                    if (!empty($categories) && !is_wp_error($categories)) {
                        echo '<div class="recipe-quick-filters">';
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_term_link($category)) . '" class="recipe-filter-tag">' . esc_html($category->name) . '</a>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="featured-section">
        <div class="section-header">
            <h2 class="section-title"><?php echo $is_dutch ? __('Uitgelichte Recepten', 'masterchef') : __('Featured Recipes', 'masterchef'); ?></h2>
            <a href="<?php echo esc_url(get_post_type_archive_link('recipe')); ?>" class="view-all"><?php echo $is_dutch ? __('Alle Recepten', 'masterchef') : __('All Recipes', 'masterchef'); ?> →</a>
        </div>

        <div class="recipe-featured-grid">
            <?php
            // Query for featured recipes
            $featured_recipes = new WP_Query(array(
                'post_type' => 'recipe',
                'posts_per_page' => 3,
                'meta_query' => array(
                    array(
                        'key' => '_recipe_featured',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));

            if ($featured_recipes->have_posts()) :
                while ($featured_recipes->have_posts()) : $featured_recipes->the_post();
                    masterchef_recipe_card();
                endwhile;
                wp_reset_postdata();
            else :
                // If no featured recipes, get latest recipes
                $latest_recipes = new WP_Query(array(
                    'post_type' => 'recipe',
                    'posts_per_page' => 3
                ));
                
                if ($latest_recipes->have_posts()) :
                    while ($latest_recipes->have_posts()) : $latest_recipes->the_post();
                        masterchef_recipe_card();
                    endwhile;
                    wp_reset_postdata();
                endif;
            endif;
            ?>
        </div>
    </div>

    <div class="main-content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <div class="section-header">
                    <h2 class="section-title"><?php echo $is_dutch ? __('Laatste artikelen', 'masterchef') : __('Latest Articles', 'masterchef'); ?></h2>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="view-all"><?php echo $is_dutch ? __('Alle Artikelen', 'masterchef') : __('All Articles', 'masterchef'); ?> →</a>
                </div>

                <div class="posts-grid">
                    <?php
                    $latest_posts = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => 6
                    ));

                    if ($latest_posts->have_posts()) :
                        while ($latest_posts->have_posts()) : $latest_posts->the_post();
                            get_template_part('template-parts/content', 'card');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </main><!-- #main -->
        </div><!-- #primary -->

        <!-- Categories Section -->
        <div class="recipe-categories-section">
            <div class="section-header">
                <h2 class="section-title"><?php echo $is_dutch ? __('Recepten Categorieën', 'masterchef') : __('Recipe Categories', 'masterchef'); ?></h2>
            </div>
            
            <div class="recipe-categories-grid">
                <?php
                $recipe_cats = get_terms(array(
                    'taxonomy' => 'recipe_category',
                    'hide_empty' => true,
                    'number' => 6,
                ));
                
                if (!empty($recipe_cats) && !is_wp_error($recipe_cats)) :
                    foreach ($recipe_cats as $cat) :
                        // Get an image for the category if available
                        $cat_image = get_term_meta($cat->term_id, 'category_image', true);
                        ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="recipe-category-card">
                            <div class="recipe-category-image">
                                <?php if ($cat_image) : ?>
                                    <img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($cat->name); ?>">
                                <?php else : ?>
                                    <div class="recipe-category-icon"><?php echo esc_html(substr($cat->name, 0, 1)); ?></div>
                                <?php endif; ?>
                            </div>
                            <h3 class="recipe-category-name"><?php echo esc_html($cat->name); ?></h3>
                            <span class="recipe-category-count"><?php echo esc_html($cat->count); ?> <?php echo $is_dutch ? __('recepten', 'masterchef') : __('recipes', 'masterchef'); ?></span>
                        </a>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();