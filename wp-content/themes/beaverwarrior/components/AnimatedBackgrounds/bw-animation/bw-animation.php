<?php

class BWAnimation extends FLBuilderModule {
    public function __construct() {
        parent::__construct(array(
            "name" => __("Sprite Animation", "skeleton-warrior"),
            "description" => __("Stand-alone animation block", "skeleton-warrior"),
            "category" => __("Space Station", "skeleton-warrior"),
            "dir" => get_stylesheet_directory() . "components/AnimatedBackgrounds/bw-animation/",
            "url" => get_stylesheet_directory_uri() . "components/AnimatedBackgrounds/bw-animation/",
            "editor_export" => true,
            "enabled" => true,
            "icon" => "button.svg"
        ));
    }
    
    public function filter_settings($settings, $helper) {
        $new_layer_id = 0;
        
        for ($i = 1; $i <= 8; $i += 1) {
            $legacy_layer_setting = 'bw_ab_layer_' . $i . '_enable';
            $legacy_layer_image = 'bw_ab_layer_' . $i . '_image';
            $legacy_layer_image_src = 'bw_ab_layer_' . $i . '_image_src';
            $legacy_layer_animdata = 'bw_ab_layer_' . $i . '_animdata';
            $legacy_layer_depth = 'bw_ab_layer_' . $i . '_depth';
            $legacy_layer_loop = 'bw_ab_layer_' . $i . '_loop';
            $legacy_layer_bob = 'bw_ab_layer_' . $i . '_bob';
            
            if ($settings->$legacy_layer_setting !== 'no') {
                if (!isset($settings->bw_anim_layers)) {
                    $settings->bw_anim_layers = array();
                }
                
                $settings->bw_anim_layers[$new_layer_id++] = array(
                    'layer_label' => sprintf(__('Layer %d', 'skeleton-warrior'), $i),
                    'layer_enable' => $settings->$legacy_layer_setting,
                    'layer_image' => $settings->$legacy_layer_image,
                    'layer_image_src' => $settings->$legacy_layer_image_src,
                    'layer_animdata' => $settings->$legacy_layer_animdata,
                    'layer_depth' => $settings->$legacy_layer_depth,
                    'layer_loop' => $settings->$legacy_layer_loop,
                    'layer_bob' => $settings->$legacy_layer_bob
                );
                
                $settings->$legacy_layer_setting = 'no';
            }
        }
        
        return $settings;
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
                    ),
                ),
            ),
            'preload' => array(
                'title' => __("Preload appearance", 'skeleton-warrior'),
                'fields' => array(
                    'anim_load' => array(
                        'type' => 'select',
                        'label' => __("Load behavior", 'skeleton-warrior'),
                        'description' => __("Select an option for how the animation should look when loading", 'skeleton-warrior'),
                        'options' => array(
                            'color' => __("Show a solid color when loading"),
                            'image' => __("Show a loading image"),
                            'none' => __("Do not apply a load behavior (not recommended)")
                        ),
                        'default' => 'none',
                        'toggle' => array(
                            'color' => array(
                                'fields' => array('anim_load_color')
                            ),
                            'image' => array(
                                'fields' => array('anim_load_color', 'anim_load_image', 'anim_load_bgsize')
                            ),
                            'none' => array()
                        )
                    ),
                    'anim_load_color' => array(
                        'type' => 'color',
                        'label' => __("Load color", 'skeleton-warrior'),
                        'description' => __("Select a color to hide the animation with until it loads.", 'skeleton-warrior'),
                        'default' => 'ffffff'
                    ),
                    'anim_load_image' => array(
                        'type' => 'photo',
                        'label' => __("Load image", 'skeleton-warrior'),
                        'description' => __("Select an image to hide the animation with until it loads.", 'skeleton-warrior')
                    ),
                    'anim_load_bgsize' => array(
                        'type' => 'select',
                        'label' => __("Load image sizing", 'skeleton-warrior'),
                        'options' => array(
                            'cover' => __("Cover the whole animation", 'skeleton-warrior'),
                            'contain' => __("Fit the size of the animation without cropping", 'skeleton-warrior'),
                        ),
                        'default' => 'cover'
                    ),
                    'ab_loadanim' => array(
                        'type' => 'select',
                        'label' => __("Custom load animation present", 'skeleton-warrior'),
                        'description' => __("Indicates that a custom load animation has been applied in CSS. When indicated, backgrounds will not start to animate until indicated pre-load animations have completed. Should not be enabled alongisde a load behavior.", 'skeleton-warrior'),
                        'options' => array(
                            'yes' => __("Load animation with fade out present, wait for it to completely fade out"),
                            'no' => __("No load animation present, animate backgrounds in sync"),
                        ),
                        'default' => 'no',
                    )
                )
            )
        )
    ),
    "layers" => array(
        'title' => __('Layers', 'skeleton-warrior'),
        'sections' => array(
            'bw_anim_layers' => array(
                'title' => __("Animation Layers", 'skeleton-warrior'),
                'fields' => array(
                    'bw_anim_layers' => array(
                        'type' => 'form',
                        'label' => __("Animation Layers", 'skeleton-warrior'),
                        'form' => 'bw_anim_layer',
                        'preview_text' => 'layer_label',
                        'multiple' => true
                    ),
                )
            )
        )
    )
));