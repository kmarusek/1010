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