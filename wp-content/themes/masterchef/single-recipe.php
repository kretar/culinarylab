<?php
/**
 * The template for displaying single recipes
 */

get_header();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            while (have_posts()) :
                the_post();
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="recipe">
                        <header class="recipe-header">
                            <h1 class="recipe-title"><?php the_title(); ?></h1>
                            
                            <?php masterchef_recipe_print_button(); ?>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="recipe-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php masterchef_recipe_meta(); ?>
                        
                        <?php if (get_post_meta(get_the_ID(), '_recipe_servings', true)) : ?>
                            <?php masterchef_recipe_servings_control(); ?>
                        <?php endif; ?>

                        <div class="recipe-description">
                            <?php the_content(); ?>
                        </div>

                        <div class="recipe-sections">
                            <div class="recipe-section recipe-ingredients-section">
                                <?php masterchef_recipe_ingredients(); ?>
                            </div>

                            <div class="recipe-section recipe-instructions-section">
                                <?php masterchef_recipe_instructions(); ?>
                            </div>
                        </div>

                        <?php masterchef_recipe_taxonomies(); ?>
                        
                    </div><!-- .recipe -->

                    <?php 
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                    <?php masterchef_post_navigation(); ?>
                    
                </article><!-- #post-<?php the_ID(); ?> -->

            <?php endwhile; ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();