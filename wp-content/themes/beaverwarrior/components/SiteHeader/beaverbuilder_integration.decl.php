<?php

function beaver_warrior_remove_bb_nav_menus() {
    unregister_nav_menu('bar');
    unregister_nav_menu('header');
    unregister_nav_menu('footer');
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_nav_menus', 11);

function beaver_warrior_remove_bb_settings($wp_customize) {
    $wp_customize->remove_section("fl-header");
    $wp_customize->remove_section("fl-footer");
}
add_action('customize_register', 'beaver_warrior_remove_bb_settings', 110);
