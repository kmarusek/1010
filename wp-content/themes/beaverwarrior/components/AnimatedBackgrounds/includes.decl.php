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
            ),
            'bw_ab_layer_' . $layer_id . '_bob' => array(
                'type' => 'select',
                'label' => __("Bob Animation", 'skeleton-warrior'),
                "default" => "none",
                'options' => array(
                    'none' => __("Do not apply a bob animation", 'skeleton-warrior'),
                    'vertical' => __("Layer should bob up and down", 'skeleton-warrior'),
                    'horizontal' => __("Layer should bob left and right", 'skeleton-warrior'),
                )
            )
        )
    );
}

FLBuilder::register_settings_form('bw_anim_layer', array(
    'title' => __("Animation Layer", 'skeleton-warrior'),
    'tabs' => array(
        'general' => array(
            'title' => __("General", 'skeleton-warrior'),
            'sections' => array(
                'general' => array(
                    'title' => __("General", 'skeleton-warrior'),
                    'fields' => array(
                        'layer_label' => array(
                            'type' => 'text',
                            'label' => __("Layer Name", 'skeleton-warrior')
                        ),
                        'layer_enable' => array(
                            'type' => 'select',
                            'label' => __("Layer Type"),
                            'options' => array(
                                'image' => __("Static image"),
                                'atlas' => __("Atlas animation"),
                                'no' => __("Nothing"),
                            ),
                            'default' => 'no',
                            'toggle' => array(
                                'image' => array(
                                    'fields' => array(
                                        'layer_image',
                                        'layer_depth',
                                    )
                                ),
                                'atlas' => array(
                                    'fields' => array(
                                        'layer_image',
                                        'layer_animdata',
                                        'layer_depth',
                                        'layer_loop'
                                    )
                                )
                            )
                        ),
                        'layer_image' => array(
                            'type' => 'photo',
                            'label' => __("Background image", 'skeleton-warrior'),
                            'show_remove' => true
                        ),
                        'layer_bgsize' => array(
                            'type' => 'select',
                            'label' => __("Sizing", 'skeleton-warrior'),
                            'default' => 'cover',
                            'options' => array(
                                'cover' => __("Cover the viewport", 'skeleton-warrior'),
                                'contain' => __("Avoid clipping the image", 'skeleton-warrior'),
                                'max-width' => __("Use a maximum width", 'skeleton-warrior')
                            ),
                            'default' => 'cover',
                            'toggle' => array(
                                'max-width' => array(
                                    'fields' => array(
                                        'layer_max_width'
                                    )
                                )
                            )
                        ),
                        'layer_max_width' => array(
                            'type' => 'unit',
                            'label' => __("Maximum width", 'skeleton-warrior'),
                            'responsive' => true,
                            'description' => 'px',
                        ),
                        'layer_animdata' => array(
                            'type' => 'textarea',
                            'label' => __("Animation data", 'skeleton-warrior'),
                            'default' => '',
                            'description' => __('Copy the JSON data you got from the Photoshop script: https://github.com/tonioloewald/Layer-Group-Atlas', 'skeleton-warrior'),
                            'rows' => 6,
                            'sanitize' => "bw_animated_background_fix_json",
                        ),
                        'layer_depth' => array(
                            'type' => 'unit',
                            'label' => __("Parallax depth", 'skeleton-warrior')
                        ),
                        'layer_loop' => array(
                            'type' => 'select',
                            'label' => __("Loop Setting", 'skeleton-warrior'),
                            "default" => "no-override",
                            'options' => array(
                                'force-loop' => __("Force animation to loop", 'skeleton-warrior'),
                                'force-once' => __("Force animation to play once and stop", 'skeleton-warrior'),
                                'no-override' => __("Use loop setting from the animation data", 'skeleton-warrior')
                            )
                        ),
                        'layer_bob' => array(
                            'type' => 'select',
                            'label' => __("Bob Animation", 'skeleton-warrior'),
                            "default" => "none",
                            'options' => array(
                                'none' => __("Do not apply a bob animation", 'skeleton-warrior'),
                                'vertical' => __("Layer should bob up and down", 'skeleton-warrior'),
                                'horizontal' => __("Layer should bob left and right", 'skeleton-warrior'),
                            )
                        )
                    )
                )
            )
        )
    )
));

require_once __DIR__ . "/animated_background_row/settings.decl.php";

function beaverwarrior_load_AnimatedBackgrounds_modules() {
    if (class_exists("FLBuilder")) {
        require_once "bw-animation/bw-animation.php";
    }
}
add_action ('init', "beaverwarrior_load_AnimatedBackgrounds_modules", 15);