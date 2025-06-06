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
                            <div class="recipe-header-scientific">
                                <span class="recipe-protocol-number">PROTOCOL #<?php echo esc_html(get_the_ID()); ?></span>
                                <span class="recipe-protocol-ref"><?php echo esc_html(substr(md5(get_the_title()), 0, 6)); ?></span>
                            </div>
                            <h1 class="recipe-title"><?php the_title(); ?></h1>
                            
                            <?php masterchef_recipe_print_button(); ?>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="recipe-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php masterchef_recipe_meta(); ?>
                        
                        <div class="recipe-controls">
                            <?php if (get_post_meta(get_the_ID(), '_recipe_servings', true)) : ?>
                                <?php masterchef_recipe_servings_control(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="recipe-description">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        // Check if we have recipe sections
                        $recipe_sections = get_post_meta(get_the_ID(), '_recipe_sections', true);
                        
                        if (is_array($recipe_sections) && !empty($recipe_sections)) {
                            // Display recipe sections (ingredients and instructions by section)
                            masterchef_recipe_sections();
                        } else {
                            // Display traditional recipe format
                            ?>
                            <div class="recipe-components">
                                <div class="recipe-component recipe-ingredients-component">
                                    <?php masterchef_recipe_ingredients(); ?>
                                </div>

                                <div class="recipe-component recipe-instructions-component">
                                    <?php masterchef_recipe_instructions(); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <?php masterchef_recipe_source(); ?>
                        
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