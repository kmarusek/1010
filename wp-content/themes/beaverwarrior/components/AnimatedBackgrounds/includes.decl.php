<?php

/**
 * Constructs a setting form section for a single layer of animation or
 * background animation settings
 */
function bw_animated_background_row_settings_layer($layer_id) {
    return array(
        'title' => sprintf(_n('Layer %d', 'Layer %d', $layer_id, 'skeleton-warrior'), $layer_id),
        'fields' => array(
            'bw_ab_layer_' . $layer_id . '_enable' => array(
                'type' => 'select',
                'label' => sprintf(_n('Enable layer %d', 'Enable layer %d', $layer_id, 'skeleton-warrior'), $layer_id),
                'options' => array(
                    'image' => __("Static image"),
                    'atlas' => __("Atlas animation"),
                    'no' => __("Nothing"),
                ),
                'default' => 'no',
                'toggle' => array(
                    'image' => array(
                        'fields' => array(
                            'bw_ab_layer_' . $layer_id . '_image',
                            'bw_ab_layer_' . $layer_id . '_depth',
                        )
                    ),
                    'atlas' => array(
                        'fields' => array(
                            'bw_ab_layer_' . $layer_id . '_image',
                            'bw_ab_layer_' . $layer_id . '_animdata',
                            'bw_ab_layer_' . $layer_id . '_depth',
                            'bw_ab_layer_' . $layer_id . '_loop'
                        )
                    )
                )
            ),
            'bw_ab_layer_' . $layer_id . '_image' => array(
                'type' => 'photo',
                'label' => __("Background image", 'skeleton-warrior'),
                'show_remove' => true
            ),
            'bw_ab_layer_' . $layer_id . '_animdata' => array(
                'type' => 'textarea',
                'label' => __("Animation data", 'skeleton-warrior'),
                'default' => '',
                'description' => __('Copy the JSON data you got from the Photoshop script: https://github.com/tonioloewald/Layer-Group-Atlas', 'skeleton-warrior'),
                'rows' => 6,
                'sanitize' => "bw_animated_background_fix_json",
            ),
            'bw_ab_layer_' . $layer_id . '_depth' => array(
                'type' => 'unit',
                'label' => __("Parallax depth", 'skeleton-warrior')
            ),
            'bw_ab_layer_' . $layer_id . '_loop' => array(
                'type' => 'select',
                'label' => __("Loop Setting", 'skeleton-warrior'),
                "default" => "no-override",
                'options' => array(
                    'force-loop' => __("Force animation to loop", 'skeleton-warrior'),
                    'force-once' => __("Force animation to play once and stop", 'skeleton-warrior'),
                    'no-override' => __("Use loop setting from the animation data", 'skeleton-warrior')
                )
            )
        )
    );
}

require_once __DIR__ . "/animated_background_row/settings.decl.php";

function beaverwarrior_load_AnimatedBackgrounds_modules() {
    if (class_exists("FLBuilder")) {
        require_once "bw-animation/bw-animation.php";
    }
}
add_action ('init', "beaverwarrior_load_AnimatedBackgrounds_modules", 15);