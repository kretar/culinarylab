<?php
/**
 * The main template file
 */

get_header();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (have_posts()) :

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     */
                    get_template_part('template-parts/content', get_post_type());

                endwhile;

                // Pagination
                masterchef_pagination();

            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

    <?php get_sidebar(); ?>
</div><!-- .container -->

<?php
get_footer();