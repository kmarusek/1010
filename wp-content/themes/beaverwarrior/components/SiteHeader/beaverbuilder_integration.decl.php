<?php

function beaver_warrior_remove_bb_nav_menus() {
    unregister_nav_menu('bar');
    unregister_nav_menu('header');
    unregister_nav_menu('footer');
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_nav_menus', 11);

function beaver_warrior_reorganize_bb_header_controls() {
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

    //fl-topbar-style
    $wp_customize->add_setting("bw-topbar-height-bp", array(
        "default" => 38
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-topbar-height-bp", array(
        "section" => "fl-topbar-style",
        "settings" => "bw-topbar-height-bp",
        "label" => __("Header Bar Height (Desktop)", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => -1,
        'choices' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 1
        )
    )));

    //fl-header-style
    $wp_customize->add_setting("bw-header-breakpoint", array(
        "default" => 768
    ));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, "bw-header-breakpoint", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-breakpoint",
        "label" => __("Header Breakpoint", 'skeleton_warrior'),
        "type" => "select",
        "priority" => -1,
        'choices' => array(
            320 => __("Handheld (320px)", "fl-automator"),
            480 => __("X-Small (480px)", "fl-automator"),
            768 => __("Small (768px)", "fl-automator"),
            992 => __("Medium (992px)", "fl-automator"),
            1200 => __("Large (1200px)", "fl-automator"),
            1600 => __("X-Large (1600px)", "fl-automator"),
        )
    )));

    $wp_customize->add_setting("bw-header-height", array(
        "default" => 50
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-height", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-height",
        "label" => __("Header Height (Mobile)", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => -1,
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
        "priority" => -1,
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
        "priority" => -1,
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
        "priority" => -1,
        'choices' => array(
            'min'  => 30,
            'max'  => 100,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-header-border-width", array(
        "default" => 1
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-header-border-width", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-border-width",
        "label" => __("Border Width", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => 10,
        'choices' => array(
            'min'  => 0,
            'max'  => 5,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-header-border-color", array(
        "default" => "#f2f2f2"
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-header-border-color", array(
        "section" => "fl-header-style",
        "settings" => "bw-header-border-color",
        "label" => __("Border Color", 'skeleton_warrior'),
        "priority" => 10,
    )));
}
add_action('customize_register', 'beaver_warrior_reorganize_bb_header_controls', 11);

function beaver_warrior_expose_header_settings($vars, $mods) {
    $vars["bw-topbar-height-bp"] = get_theme_mod("bw-topbar-height-bp", 38) . "px";
    $vars["bw-header-height"] = get_theme_mod("bw-header-height", 50) . "px";
    $vars["bw-header-logo-height"] = get_theme_mod("bw-header-logo-height", 45) . "px";
    $vars["bw-header-height-bp"] = get_theme_mod("bw-header-height-bp", 100) . "px";
    $vars["bw-header-logo-height-bp"] = get_theme_mod("bw-header-logo-height-bp", 65) . "px";
    $vars["bw-header-breakpoint"] = get_theme_mod("bw-header-breakpoint", 768) . "px";
    $vars["bw-header-border-width"] = get_theme_mod("bw-header-border-width", 1) . "px";
    $vars["bw-header-border-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-header-border-color"));

    return $vars;
}
add_action('bw_less_vars', 'beaver_warrior_expose_header_settings', 10, 2);
