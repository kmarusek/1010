<?php

function skeleton_warrior_register_settings_article($wp_customize) {
    $wp_customize->add_section('skeleton_warrior_social_share', array(
        'title' => __('Social Media Share', 'skeleton_warrior'),
        'priority' => 1000, //hopefully this pushes it down, not up
    ));
    $wp_customize->add_setting('skeleton_warrior_social_twitteruser');
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_social_twitteruser',
        array(
            'label' => __('Twitter Username', 'skeleton_warrior'),
            'section' => 'skeleton_warrior_social_share',
            'settings' => 'skeleton_warrior_social_twitteruser',
            'description' => 'If provided, share links on your blog will link back to your Twitter username.'
        )
    ));
}
add_action('customize_register', 'skeleton_warrior_register_settings_article');