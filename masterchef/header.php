<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon-16x16.png" sizes="16x16">
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/apple-touch-icon.png">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'masterchef'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-inner">
                <div class="site-branding">
                    <?php masterchef_site_logo(); ?>
                </div>

                <?php masterchef_main_navigation(); ?>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">