<?php

/**
 * Constructs a setting form section for a single layer of the animated background settings.
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
                'rows' => 6
            ),
            'bw_ab_layer_' . $layer_id . '_depth' => array(
                'type' => 'unit',
                'label' => __("Parallax depth", 'skeleton-warrior')
            )
        )
    );
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
                        )
                    )
                ),
                'bw_ab_layer_1' => bw_animated_background_row_settings_layer(1),
                'bw_ab_layer_2' => bw_animated_background_row_settings_layer(2),
                'bw_ab_layer_3' => bw_animated_background_row_settings_layer(3),
                'bw_ab_layer_4' => bw_animated_background_row_settings_layer(4),
                'bw_ab_layer_5' => bw_animated_background_row_settings_layer(5),
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