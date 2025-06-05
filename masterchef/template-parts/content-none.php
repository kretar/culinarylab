<?php
/**
 * Template part for displaying a message that posts cannot be found
 */

$is_dutch = strpos(get_locale(), 'nl') !== false;
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php echo $is_dutch ? esc_html__('Niets gevonden', 'masterchef') : esc_html__('Nothing Found', 'masterchef'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_search()) :
            if ($is_dutch) :
                ?>
                <p><?php esc_html_e('Sorry, maar er zijn geen resultaten gevonden voor je zoekopdracht. Probeer het opnieuw met andere zoekwoorden.', 'masterchef'); ?></p>
                <?php
            else :
                ?>
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'masterchef'); ?></p>
                <?php
            endif;

            get_search_form();

        else :
            if ($is_dutch) :
                ?>
                <p><?php esc_html_e('Het lijkt erop dat we niet kunnen vinden waar je naar op zoek bent. Misschien kan zoeken helpen.', 'masterchef'); ?></p>
                <?php
            else :
                ?>
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'masterchef'); ?></p>
                <?php
            endif;

            get_search_form();

        endif;
        ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->