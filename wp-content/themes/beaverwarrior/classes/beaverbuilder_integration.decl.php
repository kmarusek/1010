<?php

function beaver_warrior_reorganize_bb_type_controls() {
    global $wp_customize;

    $wp_customize->get_control('fl-h6-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H6' );
    $wp_customize->get_control('fl-h6-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H6' );

    $wp_customize->add_setting("bw-p-lg-font-size", array(
        "default" => "14"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-p-lg-font-size", array(
        "section" => "fl-body-font",
        "settings" => "bw-p-lg-font-size",
        'label' => __( 'Font Size (Large/Desktop)', 'fl-automator' ),
        "type" => "slider",
        "priority" => 7,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-p-lg-line-height", array(
        "default" => "1.45"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-p-lg-line-height", array(
        "section" => "fl-body-font",
        "settings" => "bw-p-lg-line-height",
        'label' => __( 'Line Height (Large/Desktop)', 'fl-automator' ),
        "type" => "slider",
        "priority" => 8,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->add_setting("bw-p-margin", array(
        "default" => "0"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-p-margin", array(
        "section" => "fl-body-font",
        "settings" => "bw-p-margin",
        'label' => __( 'Paragraph Margin', 'fl-automator' ),
        "type" => "slider",
        "priority" => 9,
        'choices' => array(
            'min'  => 0,
            'max'  => 45,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-p-letter-spacing", array(
        "default" => "0"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-p-letter-spacing", array(
        "section" => "fl-body-font",
        "settings" => "bw-p-letter-spacing",
        'label' => __( 'Letter Spacing', 'fl-automator' ),
        "type" => "slider",
        "priority" => 10,
        'choices' => array(
            'min'  => 0,
            'max'  => 4,
            'step' => 0.1
        )
    )));

    $wp_customize->add_setting("bw-p-separator", array());

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-p-separator", array(
        "section" => "fl-body-font",
        "settings" => "bw-p-separator",
        "type" => "line",
        "priority" => 11,
    )));

    $wp_customize->get_control('fl-body-font-size')->priority = 12;
    $wp_customize->get_control('fl-body-font-size')->label = __( 'Font Size (Small/Mobile)', 'fl-automator' );

    $wp_customize->get_control('fl-body-line-height')->priority = 13;
    $wp_customize->get_control('fl-body-line-height')->label = __( 'Line Height (Small/Mobile)', 'fl-automator' );

    $wp_customize->add_setting("bw-h1-lg-font-size", array(
        "default" => "36"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h1-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h1-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H1' ),
        "type" => "slider",
        "priority" => 6,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h1-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h1-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h1-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H1' ),
        "type" => "slider",
        "priority" => 6,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->get_control('fl-h1-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H1' );
    $wp_customize->get_control('fl-h1-font-size')->priority = 8;

    $wp_customize->get_control('fl-h1-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H1' );
    $wp_customize->get_control('fl-h1-line-height')->priority = 8;

    $wp_customize->add_setting("bw-h2-lg-font-size", array(
        "default" => "30"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h2-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h2-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H2' ),
        "type" => "slider",
        "priority" => 9,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h2-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h2-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h2-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H2' ),
        "type" => "slider",
        "priority" => 9,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->get_control('fl-h2-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H2' );
    $wp_customize->get_control('fl-h2-font-size')->priority = 12;

    $wp_customize->get_control('fl-h2-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H2' );
    $wp_customize->get_control('fl-h2-line-height')->priority = 12;

    $wp_customize->add_setting("bw-h3-lg-font-size", array(
        "default" => "24"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h3-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h3-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H3' ),
        "type" => "slider",
        "priority" => 13,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h3-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h3-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h3-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H3' ),
        "type" => "slider",
        "priority" => 13,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->get_control('fl-h3-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H3' );
    $wp_customize->get_control('fl-h3-font-size')->priority = 16;

    $wp_customize->get_control('fl-h3-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H3' );
    $wp_customize->get_control('fl-h3-line-height')->priority = 16;

    $wp_customize->add_setting("bw-h4-lg-font-size", array(
        "default" => "18"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h4-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h4-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H4' ),
        "type" => "slider",
        "priority" => 17,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h4-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h4-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h4-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H4' ),
        "type" => "slider",
        "priority" => 17,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->get_control('fl-h4-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H4' );
    $wp_customize->get_control('fl-h4-font-size')->priority = 20;

    $wp_customize->get_control('fl-h4-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H4' );
    $wp_customize->get_control('fl-h4-line-height')->priority = 20;

    $wp_customize->add_setting("bw-h5-lg-font-size", array(
        "default" => "14"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h5-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h5-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H5' ),
        "type" => "slider",
        "priority" => 21,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h5-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h5-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h5-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H5' ),
        "type" => "slider",
        "priority" => 21,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));

    $wp_customize->get_control('fl-h5-font-size')->label = sprintf( _x( '%s Font Size (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H5' );
    $wp_customize->get_control('fl-h5-font-size')->priority = 24;

    $wp_customize->get_control('fl-h5-line-height')->label = sprintf( _x( '%s Line Height (Small/Mobile)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H5' );
    $wp_customize->get_control('fl-h5-line-height')->priority = 24;

    $wp_customize->add_setting("bw-h6-lg-font-size", array(
        "default" => "12"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h6-lg-font-size", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h6-lg-font-size",
        'label' => sprintf( _x( '%s Font Size (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H6' ),
        "type" => "slider",
        "priority" => 28,
        'choices' => array(
            'min'  => 10,
            'max'  => 72,
            'step' => 1
        )
    )));

    $wp_customize->add_setting("bw-h6-lg-line-height", array(
        "default" => "1.4"
    ));

    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-h6-lg-line-height", array(
        "section" => "fl-heading-font",
        "settings" => "bw-h6-lg-line-height",
        'label' => sprintf( _x( '%s Line Height (Large/Desktop)', '%s stands for HTML heading tag.', 'fl-automator' ), 'H6' ),
        "type" => "slider",
        "priority" => 28,
        'choices' => array(
            'min'  => 1,
            'max'  => 2.5,
            'step' => 0.05
        )
    )));
}
add_action('customize_register', 'beaver_warrior_reorganize_bb_type_controls', 11);

function beaver_warrior_expose_type_settings($vars, $mods) {
    $vars["bw-p-lg-font-size"] = get_theme_mod("bw-p-lg-font-size", 14) . "px";
    $vars["bw-p-lg-line-height"] = get_theme_mod("bw-p-lg-line-height", 1.45);
    $vars["bw-p-margin"] = get_theme_mod("bw-p-margin", 0) . "px";
    $vars["bw-p-letter-spacing"] = get_theme_mod("bw-p-letter-spacing", 2) . "px";
    $vars["bw-h1-lg-font-size"] = get_theme_mod("bw-h1-lg-font-size", 36) . "px";
    $vars["bw-h1-lg-line-height"] = get_theme_mod("bw-h1-lg-line-height", 1.4);
    $vars["bw-h2-lg-font-size"] = get_theme_mod("bw-h2-lg-font-size", 30) . "px";
    $vars["bw-h2-lg-line-height"] = get_theme_mod("bw-h2-lg-line-height", 1.4);
    $vars["bw-h3-lg-font-size"] = get_theme_mod("bw-h3-lg-font-size", 24) . "px";
    $vars["bw-h3-lg-line-height"] = get_theme_mod("bw-h3-lg-line-height", 1.4);
    $vars["bw-h4-lg-font-size"] = get_theme_mod("bw-h4-lg-font-size", 18) . "px";
    $vars["bw-h4-lg-line-height"] = get_theme_mod("bw-h4-lg-line-height", 1.4);
    $vars["bw-h5-lg-font-size"] = get_theme_mod("bw-h5-lg-font-size", 14) . "px";
    $vars["bw-h5-lg-line-height"] = get_theme_mod("bw-h5-lg-line-height", 1.4);
    $vars["bw-h6-lg-font-size"] = get_theme_mod("bw-h6-lg-font-size", 12) . "px";
    $vars["bw-h6-lg-line-height"] = get_theme_mod("bw-h6-lg-line-height", 1.4);

    return $vars;
}
add_action('bw_less_vars', 'beaver_warrior_expose_type_settings', 10, 2);
