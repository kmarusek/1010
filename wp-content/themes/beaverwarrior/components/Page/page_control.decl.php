<?php

function skeleton_warrior_register_page_border($wp_customize) {
    $wp_customize->add_setting('skeleton_warrior_pagecolor', array());
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_pagecolor',
        array(
            'label' => __('Background Contrast Class', 'skeleton_warrior'),
            'description' => __('Select which contrast option fits your selected background image and color the best.', 'skeleton_warrior'),
            'section' => 'fl-content-bg',
            'settings' => 'skeleton_warrior_pagecolor',
            'type' => 'radio',
            'choices' => array(
                'light' => __('Light Page', 'skeleton_warrior'),
                'dark' => __('Dark Page', 'skeleton_warrior'),
            )
        )
    ));
    
    $wp_customize->add_setting('skeleton_warrior_pageborder', array());
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_pageborder',
        array(
            'label' => __('Page Border', 'skeleton_warrior'),
            'section' => 'fl-content-bg',
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
