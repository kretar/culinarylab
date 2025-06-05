<?php
/**
 * Template part for displaying posts
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <?php masterchef_post_meta(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php masterchef_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        if (is_singular()) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'masterchef'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'masterchef'),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
            ?>
            <a href="<?php echo esc_url(get_permalink()); ?>" class="read-more">
                <?php 
                $is_dutch = strpos(get_locale(), 'nl') !== false;
                echo $is_dutch ? esc_html__('Lees meer', 'masterchef') : esc_html__('Read More', 'masterchef');
                ?>
            </a>
        <?php
        endif;
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        if (is_singular() && 'post' === get_post_type()) :
            // Display tags if this is a post
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'masterchef'));
            if ($tags_list) {
                $is_dutch = strpos(get_locale(), 'nl') !== false;
                $tags_text = $is_dutch ? esc_html__('Tags:', 'masterchef') : esc_html__('Tags:', 'masterchef');
                printf('<div class="post-tags">%1$s %2$s</div>', $tags_text, $tags_list);
            }
        endif;
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->