<?php

/**
 * @class BWWorkHero
 *
 */
class BWContentSlider extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Advanced Content Slider', 'skeleton-warrior'),
                'description'     => __('A slider module that allows arbitrary contents.', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            ]
        );
    }
    
    public function getSavedRows() {
        $return_array = array();

        $args = array(
            'post_type' => 'fl-builder-template',
            'post_per_page' => -1,
            'orderby' => 'title',
            'order' => 'asc'
        );

        $query = new WP_Query( $args );

        $pages = $query->posts;

        for ( $i=0;$i<count($pages); $i++ ){
            $current_saved_module = $pages[$i];
            $return_array[$current_saved_module->ID] = $current_saved_module->post_title;
        }

        return $return_array;
    }
};

FLBuilder::register_module(
    'BWContentSlider', [
        'content' => [
            'title' => __('Content', 'fl-builder'),
            'sections' => [
                'section_general' => [
                    'fields' => [
                        'slider_label' => [
                            'type' => 'text',
                            'label' => __('Slider Label','fl-builder')
                        ],
                        'slides' => [
                            'type' => 'form',
                            'label' => __('Slide', 'fl-builder'),
                            'form' => 'bw_contentslider_slide',
                            'preview_text' => 'slide_title',
                            'multiple' => true
                        ]
                    ]
                ]
            ]
        ],
        'navigation' => [
            'title' => __('Navigation','skeleton-warrior'),
            'sections' => [ 
                'arrows' => [
                    'title' => __('Arrow Navigation','skeleton-warrior'), 
                    'fields' => [
                        'left_arrow_icon' => [
                            'type' => 'icon',
                            'label' => __('Left Arrow','skeleton-warrior'),
                            'show_remove' => true,
                        ],
                        'left_arrow_bg_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Left Arrow Color', 'skeleton-warrior' ),
                            'default'       => '#222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'left_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Left Arrow Hover Color','skeleton-warrior'),
                            'default' => '#EC4067', 
                            'show_reset' => true,
                            'show_alpha' => true
                        ],
                        'right_arrow_icon' => [
                            'type' => 'icon',
                            'label' => __('Right Arrow','skeleton-warrior'),
                            'show_remove' => true,
                        ],
                        'right_arrow_bg_color' => [
                            'type'          => 'color',
                            'label'         => __('Right Arrow Color', 'skeleton-warrior'),
                            'default'       => '#222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'right_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Right Arrow Hover Color','skeleton-warrior'),
                            'default' => '#EC4067',
                            'show_reset' => true,
                            'show_alpha' => true
                        ]
                    ]
                ],
                'dots' => [
                    'title' => __("Dot Slide Navigation", 'skeleton-warrior'),
                    'fields' => [
                        'dots_style' => [
                            'type' => 'select',
                            'label' => __("Navigation style", 'skeleton-warrior'),
                            'default' => 'none',
                            'options' => [
                                'none' => __("No slide navigation", 'skeleton-warrior'),
                                'dots' => __("Dots", 'skeleton-warrior'),
                                'icon' => __("Icon", 'skeleton-warrior'),
                                'line' => __("Line", 'skeleton-warrior'),
                            ],
                            'toggle' => [
                                'icon' => [
                                    'fields' => ['dots_color', 'dots_color_active', 'dots_color_hover', 'dots_orientation', 'dots_edge_distance', 'dots_spacing', 'dots_icon', 'dots_size']
                                ],
                                'dots' => [
                                    'fields' => ['dots_color', 'dots_color_active', 'dots_color_hover', 'dots_orientation', 'dots_edge_distance', 'dots_spacing', 'dots_size']
                                ],
                                'line' => [
                                    'fields' => ['dots_color', 'dots_color_active', 'dots_color_hover', 'dots_orientation', 'dots_edge_distance', 'dots_spacing', 'dots_width', 'dots_height']
                                ]
                            ],
                        ],
                        'dots_icon' => [
                            'type' => 'icon',
                            'label' => __('Icon','skeleton-warrior'),
                            'show_remove' => true,
                        ],
                        'dots_size' => [
                            'type' => 'unit',
                            'label' => __('Size','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 10,
                        ],
                        'dots_width' => [
                            'type' => 'unit',
                            'label' => __('Width','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 10,
                        ],
                        'dots_height' => [
                            'type' => 'unit',
                            'label' => __('Height','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 10,
                        ],
                        'dots_spacing' => [
                            'type' => 'unit',
                            'label' => __('Spacing','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 2,
                        ],
                        'dots_orientation' => [
                            'type' => 'select',
                            'label' => __("Orientation", 'skeleton-warrior'),
                            'default' => 'bottom',
                            'options' => [
                                'top' => __("Top of the slider, horizontal"),
                                'bottom' => __("Bottom of the slider, horizontal"),
                                'left' => __("Left of the slider, vertical"),
                                'right' => __("Right of the slider, vertical"),
                            ]
                        ],
                        'dots_edge_distance' => [
                            'type' => 'unit',
                            'label' => __('Distance from edge','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 0,
                        ],
                        'dots_color' => [
                            'type' => 'color',
                            'label' => __("Dot Color", 'skeleton-warrior'),
                            'default' => '#000000',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ],
                        'dots_color_active' => [
                            'type' => 'color',
                            'label' => __("Dot Color (Active)", 'skeleton-warrior'),
                            'default' => '#3b68d0',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ],
                        'dots_color_hover' => [
                            'type' => 'color',
                            'label' => __("Dot Color (Hover/Focus)", 'skeleton-warrior'),
                            'default' => '#447af7',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ]
                    ]
                ]
            ]
        ]
    ]
);
/* This field for saved row selection is not showing up 2/21/20 */
// These fields still are't showing up 2/24/20 
FLBuilder::register_settings_form(
    'bw_contentslider_slide', 
    [
        'title' => __('Slider Content', 'skeleton-warrior'),
        'tabs' => [
            'general' => [
                'title' => __('General','skeleton-warrior'),
                'sections' => [
                    'general' => [
                        'title' => __("General", 'skeleton-warrior'),
                        'fields' => [
                            'slide_title' => [
                                'type' => 'text',
                                'label' => __('Slide Title','skeleton-warrior')
                            ],
                            'saved_content_row' => [
                                'type' => 'select',
                                'label' => __('Saved Row','skeleton-warrior'),
                                'options' => BWContentSlider::getSavedRows()
                            ],
                            'saved_row' => [
                                'type' => 'select',
                                'label' => __('Use same row on mobile?','skeleton-warrior'),
                                'options' => [
                                    true => __('Yes','skeleton-warrior'),
                                    false => __('No','skeleton-warrior')
                                ],
                                'default' => true,
                                'toggle' => [
                                    false => [
                                        'fields' => [
                                            'mobile_saved_row'
                                        ]
                                    ]
                                ]
                            ],
                            'mobile_saved_row' => [
                                'type' => 'select',
                                'label' => __('Mobile Saved Row','skeleton-warrior'),
                                'options' => BWContentSlider::getSavedRows()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
);