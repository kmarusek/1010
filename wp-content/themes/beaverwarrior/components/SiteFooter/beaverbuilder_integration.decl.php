<?php

function beaver_warrior_remove_bb_footer_menus() {
    if (get_theme_mod("bw-footer-phylactery") == "phylactery") {
        unregister_nav_menu('footer');
    } else if (get_theme_mod("bw-footer-phylactery") == "phylactery") {
        unregister_nav_menu('primary_navigation_footer');
        unregister_nav_menu('secondary_navigation_footer');
        unregister_nav_menu('tertiary_navigation_footer');
        unregister_nav_menu('quaternary_navigation_footer');
        unregister_nav_menu('site_footer_icons_menu');
    }
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_footer_menus', 11);

function beaver_warrior_reorganize_bb_footer_controls() {
    global $wp_customize;

    $wp_customize->add_setting("bw-footer-phylactery", array(
        "default" => "beaver"
    ));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, "bw-footer-phylactery", array(
        "section" => "fl-layout",
        "settings" => "bw-footer-phylactery",
        "label" => __("Site Footer System", 'skeleton_warrior'),
        "type" => "select",
        "priority" => -1,
        'choices' => array(
            "beaver" => __("Beaver Builder", "fl-automator"),
            "phylactery" => __("Skeleton Warrior", "fl-automator"),
        )
    )));

    if (get_theme_mod("bw-footer-phylactery") == "phylactery") {
        //TODO: Add something here
    }
}
add_action('customize_register', 'beaver_warrior_reorganize_bb_footer_controls', 11);
