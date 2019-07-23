<?php

function space_station_UTM_config() {
    global $wp_customize;
    
    $wp_customize->add_section('sps-utm-config', array(
        "title" => __("UTM Config", 'skeleton_warrior'),
        "panel" => "fl-settings",
        "priority" => 1
    ));
    
    $wp_customize->add_setting('sps-utm-preserve', array(
        "default" => 'true'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'sps-utm-preserve',
            array(
                'label' => __('UTM parameter preserve', 'skeleton_warrior'),
                'section' => 'sps-utm-config',
                'settings' => 'sps-utm-preserve',
                'type' => 'radio',
                'choices' => array(
                    'true' => __("UTM parameters persist across links within a session (default for Space Station)", 'skeleton_warrior'),
                    'false' => __("UTM parameters are lost after navigating to a new page (standard behavior on other sites)", 'skeleton_warrior')
                )
            )
        )
    );
    
    $wp_customize->add_setting('sps-utm-forminject', array(
        "default" => 'true'
    ));
    
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'sps-utm-forminject',
            array(
                'label' => __('UTM parameter form injection', 'skeleton_warrior'),
                'section' => 'sps-utm-config',
                'settings' => 'sps-utm-forminject',
                'type' => 'radio',
                'choices' => array(
                    'true' => __("Inject UTM parameters into hidden form fields (default for Space Station)", 'skeleton_warrior'),
                    'false' => __("Leave form fields as-is", 'skeleton_warrior')
                ),
                'description' => __("Any form built with Gravity Forms whose hidden fields' default value is 'replace_param[utm_source]' will have it's value replaced with the value of the UTM.", 'skeleton_warrior')
            )
        )
    );
}
add_action("customize_register", "space_station_UTM_config", 11);