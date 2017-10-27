<?php

function skeleton_warrior_register_settings_gmaps($wp_customize) {
    $wp_customize->add_section('skeleton_warrior_integration', array(
        'title' => __('API Integration', 'skeleton_warrior'),
        'priority' => 1000, //hopefully this pushes it down, not up
    ));
    $wp_customize->add_setting('skeleton_warrior_integration_gmapskey');
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_integration_gmapskey',
        array(
            'label' => __('Google Maps Key', 'skeleton_warrior'),
            'section' => 'skeleton_warrior_integration',
            'settings' => 'skeleton_warrior_integration_gmapskey',
            'description' => 'Required for integration with Google Maps related functionality.'
        )
    ));
}
add_action('customize_register', 'skeleton_warrior_register_settings_gmaps');

function skeleton_warrior_register_gmapsapi() {
    $gmaps_apikey = get_theme_mod('skeleton_warrior_integration_gmapskey');
    if ($gmaps_apikey === NULL || $gmaps_apikey === "") {
        return;
    }
    
    wp_register_script('google_maps_v3', 'https://maps.googleapis.com/maps/api/js?key=' . $gmaps_apikey . '&ver=3', array(), null, true);
}
add_action('wp_enqueue_scripts', 'skeleton_warrior_register_gmapsapi');