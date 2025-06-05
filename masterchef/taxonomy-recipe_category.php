<?php
/**
 * The template for displaying recipe category archives
 */

get_header();

// Get the current taxonomy term
$term = get_queried_object();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

                <header class="recipe-archive-header">
                    <h1 class="recipe-archive-title"><?php echo esc_html($term->name); ?></h1>
                    
                    <?php if (!empty($term->description)) : ?>
                        <div class="recipe-archive-description">
                            <?php echo wp_kses_post($term->description); ?>
                        </div>
                    <?php endif; ?>
                </header><!-- .page-header -->

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
                // Pagination
                masterchef_pagination();
                
                else :
                    
                    $is_dutch = strpos(get_locale(), 'nl') !== false;
                    if ($is_dutch) {
                        echo '<p>' . esc_html__('Geen recepten gevonden in deze categorie.', 'masterchef') . '</p>';
                    } else {
                        echo '<p>' . esc_html__('No recipes found in this category.', 'masterchef') . '</p>';
                    }

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();