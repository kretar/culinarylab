<?php
/**
 * The template for displaying recipe archives
 */

get_header();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

                <header class="recipe-archive-header">
                    <h1 class="recipe-archive-title">
                        <?php
                        $is_dutch = strpos(get_locale(), 'nl') !== false;
                        echo $is_dutch ? esc_html__('Alle Recepten', 'masterchef') : esc_html__('All Recipes', 'masterchef');
                        ?>
                    </h1>
                    <div class="recipe-archive-description">
                        <?php
                        if ($is_dutch) {
                            echo esc_html__('Bekijk onze complete verzameling recepten.', 'masterchef');
                        } else {
                            echo esc_html__('Browse our complete collection of recipes.', 'masterchef');
                        }
                        ?>
                    </div>
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
                        echo '<p>' . esc_html__('Geen recepten gevonden.', 'masterchef') . '</p>';
                    } else {
                        echo '<p>' . esc_html__('No recipes found.', 'masterchef') . '</p>';
                    }

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();