<?php

function beaver_warrior_remove_bb_nav_menus() {
    unregister_nav_menu('bar');
    unregister_nav_menu('header');
    unregister_nav_menu('footer');
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_nav_menus', 11);

function beaver_warrior_remove_bb_settings() {
    global $wp_customize;

    $wp_customize->remove_section("fl-topbar-layout");
    $wp_customize->remove_section("fl-header-layout");
    $wp_customize->remove_section("fl-header-logo");
    $wp_customize->remove_section("fl-nav-layout");

    $wp_customize->get_section("title_tagline")->panel = 'fl-header';
    $wp_customize->get_section("title_tagline")->priority = 0;

    @$wp_customize->get_section("sidebar-widgets-site_header_banner")->panel = 'fl-header';
    @$wp_customize->get_section("sidebar-widgets-site_header_banner")->priority = 4;
    @$wp_customize->get_section("sidebar-widgets-site_header_primary_navigation")->panel = 'fl-header';
    @$wp_customize->get_section("sidebar-widgets-site_header_primary_navigation")->priority = 4;

    $wp_customize->get_panel("nav_menus")->priority = 1;
}
add_action('customize_register', 'beaver_warrior_remove_bb_settings', 11);
