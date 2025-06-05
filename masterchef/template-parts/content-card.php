<?php
/**
 * Template part for displaying posts in a card layout
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-card-content">
        <header class="post-card-header">
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
                echo '<div class="post-card-category">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                echo '</div>';
            endif;
            ?>

            <h3 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </header>

        <div class="post-card-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
        </div>

        <footer class="post-card-footer">
            <div class="post-card-meta">
                <span class="post-card-date"><?php echo get_the_date(); ?></span>
            </div>
            <?php
            $is_dutch = strpos(get_locale(), 'nl') !== false;
            $read_more = $is_dutch ? __('Lees Meer', 'masterchef') : __('Read More', 'masterchef');
            ?>
            <a href="<?php the_permalink(); ?>" class="post-card-readmore"><?php echo esc_html($read_more); ?></a>
        </footer>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->