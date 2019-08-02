<?php

class BWAnimatedBackgroundsSettingsCompat extends FLBuilderSettingsCompatRow {
    public function filter_settings($settings) {
        $settings = parent::filter_settings($settings);
        
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

FLBuilderSettingsCompat::register_helper('row', 'BWAnimatedBackgroundsSettingsCompat');

function bw_animated_background_fix_json($json) {
    if (!is_string($json)) $json = json_encode($json);
    
    return $json;
}

function bw_animated_background_row_settings($form, $id) {
    if ($id === "row") {
        $form['tabs']['bw_animated_background'] = array(
            'title' => __('Animated Background', 'skeleton-warrior'),
            'sections' => array(
                'bw_ab_enable' => array(
                    'fields' => array(
                        'bw_ab_enable' => array(
                            'type' => 'select',
                            'label' => __("Animated Background", 'skeleton-warrior'),
                            'options' => array(
                                'yes' => __("Yes"),
                                'no' => __("No"),
                            ),
                            'default' => 'no',
                        ),
                        'bw_ab_loadanim' => array(
                            'type' => 'select',
                            'label' => __("Load animation present", 'skeleton-warrior'),
                            'description' => __("Indicates that a load animation has been applied in CSS. If so, the load animation will remain active until it has completed. Not to be used on a looping animation.", 'skeleton-warrior'),
                            'options' => array(
                                'yes' => __("Yes"),
                                'no' => __("No"),
                            ),
                            'default' => 'no',
                        )
                    )
                ),
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
        );
    }
    
    return $form;
}
add_filter('fl_builder_register_settings_form', 'bw_animated_background_row_settings', 10, 2);

function bw_animated_background_before_row_bg($rows) {
    if ($rows->settings->bw_ab_enable == 'yes') {
        include "before_row_bg.php";
    }
}
add_action('fl_builder_before_render_row_bg', 'bw_animated_background_before_row_bg', 10, 1);