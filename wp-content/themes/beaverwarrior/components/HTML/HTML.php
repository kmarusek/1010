<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
    <head>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no"/>

        <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

        <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
