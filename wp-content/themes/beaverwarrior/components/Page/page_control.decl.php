<?php

function skeleton_warrior_register_page_border($wp_customize) {
    $wp_customize->add_section('beaver_warrior_pageborder', array(
        "title" => __("Page Border", "skeleton_warrior"),
        "panel" => "fl-general"
    ));

    $wp_customize->add_setting("bw-pageborder-color", array(
        "default" => "#000000"
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-pageborder-color", array(
        "section" => "beaver_warrior_pageborder",
        "settings" => "bw-pageborder-color",
        "label" => __("Border Color", 'skeleton_warrior'),
        "priority" => 10,
    )));

    $wp_customize->add_setting('skeleton_warrior_pageborder', array());

    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_pageborder',
        array(
            'label' => __('Page Border', 'skeleton_warrior'),
            'section' => 'beaver_warrior_pageborder',
            'settings' => 'skeleton_warrior_pageborder',
            'type' => 'radio',
            'choices' => array(
                'border' => __('Add a page border', "skeleton_warrior"),
                'no_border' => __('Omit page border', "skeleton_warrior"),
            )
        )
    ));
}
add_action('customize_register', 'skeleton_warrior_register_page_border');

function beaver_warrior_expose_page_border_settings($vars) {
    $vars["bw-pageborder-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-pageborder-color"));

    return $vars;
}
add_filter('fl_less_vars', 'beaver_warrior_expose_page_border_settings');
