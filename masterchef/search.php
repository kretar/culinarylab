<?php
/**
 * The template for displaying search results
 */

get_header();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            $is_dutch = strpos(get_locale(), 'nl') !== false;
            $search_query = get_search_query();
            $is_recipe_search = isset($_GET['post_type']) && $_GET['post_type'] === 'recipe';
            
            if (have_posts()) :
                // Determine if we're searching for recipes
                if ($is_recipe_search) {
                    ?>
                    <header class="recipe-archive-header">
                        <h1 class="recipe-archive-title">
                            <?php 
                            if ($is_dutch) {
                                printf(esc_html__('Recepten zoekresultaten voor: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                            } else {
                                printf(esc_html__('Recipe search results for: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                            }
                            ?>
                        </h1>
                    </header>

                    <div class="recipe-grid">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();
                        
                        // Display recipe card
                        masterchef_recipe_card();
                        
                    endwhile;
                    ?>
                    </div>
                <?php
                } else {
                    ?>
                    <header class="page-header">
                        <h1 class="page-title">
                            <?php 
                            if ($is_dutch) {
                                printf(esc_html__('Zoekresultaten voor: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                            } else {
                                printf(esc_html__('Search results for: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                            }
                            ?>
                        </h1>
                    </header>

                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();
                        
                        /*
                         * Include the Post-Type-specific template for the content.
                         */
                        get_template_part('template-parts/content', 'card');
                        
                    endwhile;
                }

                // Pagination
                masterchef_pagination();
                
                // Add search again form
                ?>
                <div class="search-again">
                    <h3 class="search-again-title">
                        <?php echo $is_dutch ? esc_html__('Opnieuw zoeken', 'masterchef') : esc_html__('Search again', 'masterchef'); ?>
                    </h3>
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text"><?php echo $is_dutch ? esc_html__('Zoeken naar:', 'masterchef') : esc_html__('Search for:', 'masterchef'); ?></label>
                        <input type="search" class="search-field" placeholder="<?php echo $is_dutch ? esc_attr__('Zoek...', 'masterchef') : esc_attr__('Search...', 'masterchef'); ?>" value="<?php echo esc_attr($search_query); ?>" name="s" />
                        <?php if ($is_recipe_search) : ?>
                            <input type="hidden" name="post_type" value="recipe" />
                        <?php endif; ?>
                        <button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo $is_dutch ? esc_html__('Zoeken', 'masterchef') : esc_html__('Search', 'masterchef'); ?></span>🔍</button>
                    </form>
                </div>
                <?php
                
            else :
                ?>
                <header class="page-header">
                    <h1 class="page-title">
                        <?php 
                        if ($is_dutch) {
                            printf(esc_html__('Zoekresultaten voor: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                        } else {
                            printf(esc_html__('Search results for: %s', 'masterchef'), '<span>' . esc_html($search_query) . '</span>');
                        }
                        ?>
                    </h1>
                </header>

                <div class="no-results">
                    <?php if ($is_recipe_search): ?>
                        <p>
                            <?php 
                            echo $is_dutch 
                                ? esc_html__('Er zijn geen recepten gevonden die aan je zoekcriteria voldoen. Probeer andere zoekwoorden.', 'masterchef') 
                                : esc_html__('No recipes found matching your search criteria. Try different keywords.', 'masterchef');
                            ?>
                        </p>
                    <?php else: ?>
                        <?php get_template_part('template-parts/content', 'none'); ?>
                    <?php endif; ?>
                    
                    <div class="search-again">
                        <h3 class="search-again-title">
                            <?php echo $is_dutch ? esc_html__('Opnieuw zoeken', 'masterchef') : esc_html__('Search again', 'masterchef'); ?>
                        </h3>
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <label class="screen-reader-text"><?php echo $is_dutch ? esc_html__('Zoeken naar:', 'masterchef') : esc_html__('Search for:', 'masterchef'); ?></label>
                            <input type="search" class="search-field" placeholder="<?php echo $is_dutch ? esc_attr__('Zoek...', 'masterchef') : esc_attr__('Search...', 'masterchef'); ?>" value="<?php echo esc_attr($search_query); ?>" name="s" />
                            <?php if ($is_recipe_search) : ?>
                                <input type="hidden" name="post_type" value="recipe" />
                            <?php endif; ?>
                            <button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo $is_dutch ? esc_html__('Zoeken', 'masterchef') : esc_html__('Search', 'masterchef'); ?></span>🔍</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

    <?php if (!$is_recipe_search): ?>
        <?php get_sidebar(); ?>
    <?php endif; ?>
</div><!-- .container -->

<?php
get_footer();