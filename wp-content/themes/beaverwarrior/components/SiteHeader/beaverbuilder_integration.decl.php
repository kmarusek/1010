<?php

function beaver_warrior_remove_bb_nav_menus() {
    unregister_nav_menu('bar');
    unregister_nav_menu('header');
    unregister_nav_menu('footer');
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_nav_menus', 11);

function beaver_warrior_reorganize_bb_controls() {
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

    //Add some new variables for SiteHeader
    $wp_customize->add_setting("bw-header-height", array(
        "default" => 50
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-height", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-height",
        "label" => __("Header Height (Mobile)", 'skeleton_warrior'),
        "type" => "slider",
        'choices' => array(
            'min'  => 30,
            'max'  => 60,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-header-logo-height", array(
        "default" => 45
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-logo-height", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-logo-height",
        "label" => __("Max Logo Height (Mobile)", 'skeleton_warrior'),
        "type" => "slider",
        'choices' => array(
            'min'  => 30,
            'max'  => 60,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-header-height-bp", array(
        "default" => 65
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-height-bp", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-height-bp",
        "label" => __("Header Height (Desktop)", 'skeleton_warrior'),
        "type" => "slider",
        'choices' => array(
            'min'  => 30,
            'max'  => 100,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-header-logo-height-bp", array(
        "default" => 25
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-logo-height-bp", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-logo-height-bp",
        "label" => __("Max Logo Height (Desktop)", 'skeleton_warrior'),
        "type" => "slider",
        'choices' => array(
            'min'  => 30,
            'max'  => 100,
            'step' => 1
        )
    )));
}
add_action('customize_register', 'beaver_warrior_reorganize_bb_controls', 11);

function beaver_warrior_expose_settings($vars, $mods) {
    $vars["bw-header-height"] = get_theme_mod("bw-header-height", 50) . "px";
    $vars["bw-header-logo-height"] = get_theme_mod("bw-header-logo-height", 45) . "px";
    $vars["bw-header-height-bp"] = get_theme_mod("bw-header-height-bp", 100) . "px";
    $vars["bw-header-logo-height-bp"] = get_theme_mod("bw-header-logo-height-bp", 65) . "px";

    return $vars;
}
add_action('bw_less_vars', 'beaver_warrior_expose_settings', 10, 2);
