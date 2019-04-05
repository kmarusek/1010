<?php

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
                'bw_ab_layer_1' => bw_animated_background_row_settings_layer(1),
                'bw_ab_layer_2' => bw_animated_background_row_settings_layer(2),
                'bw_ab_layer_3' => bw_animated_background_row_settings_layer(3),
                'bw_ab_layer_4' => bw_animated_background_row_settings_layer(4),
                'bw_ab_layer_5' => bw_animated_background_row_settings_layer(5),
                'bw_ab_layer_6' => bw_animated_background_row_settings_layer(6),
                'bw_ab_layer_7' => bw_animated_background_row_settings_layer(7),
                'bw_ab_layer_8' => bw_animated_background_row_settings_layer(8),
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