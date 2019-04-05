<?php

class BWAnimation extends FLBuilderModule {
    public function __construct() {
        parent::__construct(array(
            "name" => __("BW Animation", "skeleton-warrior"),
            "description" => __("Stand-alone animation block", "skeleton-warrior"),
            "category" => __("Space Station", "skeleton-warrior"),
            "dir" => get_stylesheet_directory() . "components/AnimatedBackgrounds/bw-animation/",
            "url" => get_stylesheet_directory_uri() . "components/AnimatedBackgrounds/bw-animation/",
            "editor_export" => true,
            "enabled" => true,
            "icon" => "button.svg"
        ));
    }
}

FLBuilder::register_module("BWAnimation", array(
    "general" => array(
        'title' => __("General", 'skeleton-warrior'),
        'sections' => array(
            'general' => array(
                'fields' => array(
                    'aspect_ratio' => array(
                        'type' => 'unit',
                        'default' => 1.77,
                        'label' => __("Aspect ratio", "skeleton-warrior"),
                        'description' => __("The shape of the image", "skeleton-warrior"),
                        'slider' => array(
                            'min' => 0.5,
                            'max' => 2,
                            'step' => 0.01,
                        )
                    )
                )
            )
        )
    ),
    "layers" => array(
        'title' => __('Layers', 'skeleton-warrior'),
        'sections' => array(
            'bw_ab_layer_1' => bw_animated_background_row_settings_layer(1),
            'bw_ab_layer_2' => bw_animated_background_row_settings_layer(2),
            'bw_ab_layer_3' => bw_animated_background_row_settings_layer(3),
            'bw_ab_layer_4' => bw_animated_background_row_settings_layer(4),
            'bw_ab_layer_5' => bw_animated_background_row_settings_layer(5),
            'bw_ab_layer_6' => bw_animated_background_row_settings_layer(6),
            'bw_ab_layer_7' => bw_animated_background_row_settings_layer(7),
            'bw_ab_layer_8' => bw_animated_background_row_settings_layer(8),
        )
    )
));