<?php

function skeleton_warrior_register_menus_site_footer() {
    register_nav_menu('primary_navigation_footer', __( 'Footer Primary Navigation'));
    register_nav_menu('secondary_navigation_footer', __( 'Footer Secondary Navigation'));
    register_nav_menu('tertiary_navigation_footer', __( 'Footer Tertiary Navigation'));
    register_nav_menu('quaternary_navigation_footer', __( 'Footer Quaternary Navigation'));
    register_nav_menu('site_footer_icons_menu', __( 'Footer Icon Menu'));
}
add_action( 'init', 'skeleton_warrior_register_menus_site_footer' );

function skeleton_warrior_register_legaldecl($wp_customize) {
    $wp_customize->add_section('skeleton_warrior_footer', array(
        'title' => __('Site Footer', 'skeleton_warrior'),
        'priority' => 1000, //hopefully this pushes it down, not up
    ));
    $wp_customize->add_setting('skeleton_warrior_legaldecl', array(
        'default' => "©"
    ));
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_legaldecl',
        array(
            'label' => __('Legal declaration', 'skeleton_warrior'),
            'section' => 'skeleton_warrior_footer',
            'settings' => 'skeleton_warrior_legaldecl',
            'priority' => 1
        )
    ));
}
add_action('customize_register', 'skeleton_warrior_register_legaldecl');

function skeleton_warrior_register_sidebars_site_footer() {
    register_sidebar(array(
        'name' => __('Site Footer Content Area'),
        'id' => 'site_footer',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2 class="SiteFooter-menu_title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => __('Site Footer Icon Area'),
        'id' => 'site_footer_icons',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Hidden Modal Area'),
        'id' => 'hidden_modals',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
}
add_action('widgets_init', 'skeleton_warrior_register_sidebars_site_footer');