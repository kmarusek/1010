<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
    <head>
        <?php do_action('fl_head_open'); ?>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <?php echo apply_filters( 'fl_theme_viewport', "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n" ); ?>
        <?php echo apply_filters( 'fl_theme_xua_compatible', "<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n" ); ?>

        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="format-detection" content="telephone=no"/>

        <?php FLTheme::title(); ?>
        <?php FLTheme::favicon(); ?>
        <?php FLTheme::fonts(); ?>
        
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

        <?php wp_head(); ?>
        <?php FLTheme::head(); ?>
    </head>
    <body <?php body_class(); ?> itemscope="itemscope" itemtype="http://schema.org/WebPage">
        <?php FLTheme::header_code(); ?>
        <?php do_action('fl_body_open'); ?>
