    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer-bottom">
                <div class="site-info">
                    <?php
                    $is_dutch = strpos(get_locale(), 'nl') !== false;
                    $copyright = $is_dutch ? '© %s Master Chef. Alle rechten voorbehouden.' : '© %s Master Chef. All rights reserved.';
                    printf(esc_html($copyright), date('Y'));
                    ?>
                </div><!-- .site-info -->

                <?php masterchef_footer_navigation(); ?>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>