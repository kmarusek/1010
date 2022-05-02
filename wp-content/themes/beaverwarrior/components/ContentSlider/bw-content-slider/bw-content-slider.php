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
                'enabled'         => true
            ]
        );
    }
    
    public static function getSavedRows() {
        $return_array = array();

        $args = array(
            'post_type' => 'fl-builder-template',
            'posts_per_page' => -1,
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
                'slides' => [
                    'title' => __("Slides", 'skeleton-warrior'),
                    'fields' => [
                        'slider_label' => [
                            'type' => 'text',
                            'label' => __('Slider Label','fl-builder')
                        ],
                        'items_per_view' => [
                            'type' => 'unit',
                            'label' => __('Items Per Slide','fl-builder'),
                            'default' => '1',
                            'responsive' => true,
                        ],
                        'slides' => [
                            'type' => 'form',
                            'label' => __('Slide', 'fl-builder'),
                            'form' => 'bw_contentslider_slide',
                            'preview_text' => 'slide_title',
                            'multiple' => true
                        ]
                    ]
                ],
                'playback' => [
                    'title' => __('Playback settings', 'skeleton-warrior'),
                    'fields' => [
                        'play_loop' => [
                            'type' => 'select',
                            'label' => __("Loop slides", 'skeleton-warrior'),
                            'default' => 'none',
                            'options' => [
                                'none' => __("Stop at the last slide", 'skeleton-warrior'),
                                'loop' => __("Loop back to the beginning after the last slide", 'skeleton-warrior'),
                            ],
                        ],
                        'play_auto' => [
                            'type' => 'select',
                            'label' => __("Autoplay", 'skeleton-warrior'),
                            'default' => 'none',
                            'options' => [
                                'none' => __("Do not autoplay", 'skeleton-warrior'),
                                'autoplay' => __("Automatically move through slides", 'skeleton-warrior'),
                            ],
                            'toggle' => [
                                'autoplay' => [
                                    'fields' => ['play_delay', 'play_hoverpause']
                                ]
                            ],
                        ],
                        'play_delay' => [
                            'type' => 'unit',
                            'label' => __('Time between slides','skeleton-warrior'),
                            'units' => array('s', 'ms'),
                            'default_unit' => 's',
                            'default' => 5,
                        ],
                        'play_hoverpause' => [
                            'type' => 'select',
                            'label' => __("Pause autoplay on hover", 'skeleton-warrior'),
                            'default' => 'none',
                            'options' => [
                                'none' => __("Ignore hover", 'skeleton-warrior'),
                                'hoverpause' => __("Pause autoplay on hover", 'skeleton-warrior'),
                            ],
                        ],
                    ]
                ]
            ]
        ],
        'styles' => [
            'title' => __('Styles','skeleton-warrior'),
            'sections' => [ 
                'general' => [
                    'title' => __('General','skeleton-warrior'), 
                    'fields' => [
                        'slide_margin' => [
                            'type' => 'unit',
                            'label' => __('Slide Margin','skeleton-warrior'),
                            'placeholder' => '0',
                            'description' => 'px',
                        ],
                        'slide_stage_padding' => [
                            'type' => 'unit',
                            'label' => __('Slide Padding','skeleton-warrior'),
                            'placeholder' => '0',
                            'description' => 'px',
                            'help' => 'This may cause the slides to overflow, showing slides from the next page.',
                            'responsive' => true,
                        ],
                        'slide_background_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide Background Color', 'fl-builder' ),
                            'default'       => '',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'image_height' => [
                            'type' => 'unit',
                            'label' => __('Image Height','skeleton-warrior'),
                            'description' => 'px',
                            'responsive' => true
                        ],
                        'image_position'     => array(
                            'type'    => 'select',
                            'label'   => __( 'Image Position', 'fl-builder' ),
                            'default' => 'contain',
                            'options' => array(
                                'contain' => 'Contain',
                                'cover' => 'Cover',
                            ),
                        ),
                        
                    ]
                ],
                'title' => [
                    'title' => __('Title','skeleton-warrior'), 
                    'fields' => [
                        'slide_title_typography' => [
                            'type'       => 'typography',
                            'label'      => 'Slide Title Typography',
                            'responsive' => true,
                        ],
                        'slide_title_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Title Margin',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_title_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Title Padding',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_title_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide Title Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_title_tag'     => array(
                            'type'    => 'select',
                            'label'   => __( 'Slide Title HTML Tag', 'fl-builder' ),
                            'default' => 'h2',
                            'options' => array(
                                'h1' => 'h1',
                                'h2' => 'h2',
                                'h3' => 'h3',
                                'h4' => 'h4',
                                'h5' => 'h5',
                                'h6' => 'h6',
                                'p' => 'p',
                            ),
                        ),
                    ]
                ],
                'title_2' => [
                    'title' => __('Title Two','skeleton-warrior'), 
                    'fields' => [
                        'slide_title_two_typography' => [
                            'type'       => 'typography',
                            'label'      => 'Slide Title Two Typography',
                            'responsive' => true,
                        ],
                        'slide_title_two_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Title Two Margin',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_title_two_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Title Two Padding',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_title_two_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide Title Two Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_title_two_tag'     => array(
                            'type'    => 'select',
                            'label'   => __( 'Slide Title Two HTML Tag', 'fl-builder' ),
                            'default' => 'h3',
                            'options' => array(
                                'h1' => 'h1',
                                'h2' => 'h2',
                                'h3' => 'h3',
                                'h4' => 'h4',
                                'h5' => 'h5',
                                'h6' => 'h6',
                                'p' => 'p',
                            ),
                        ),
                    ]
                ],
                'description' => [
                    'title' => __('Description','skeleton-warrior'), 
                    'fields' => [
                        'slide_description_typography' => [
                            'type'       => 'typography',
                            'label'      => 'Slide Description Typography',
                            'responsive' => true,
                        ],
                        'slide_description_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Description Margin',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_description_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide Description Padding',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_description_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide Description Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_description_tag'     => array(
                            'type'    => 'select',
                            'label'   => __( 'Slide Description HTML Tag', 'fl-builder' ),
                            'default' => 'p',
                            'options' => array(
                                'h1' => 'h1',
                                'h2' => 'h2',
                                'h3' => 'h3',
                                'h4' => 'h4',
                                'h5' => 'h5',
                                'h6' => 'h6',
                                'p' => 'p',
                            ),
                        ),
                    ]
                ],
                'cta' => [
                    'title' => __('CTA','skeleton-warrior'), 
                    'fields' => [
                        'slide_cta_typography' => [
                            'type'       => 'typography',
                            'label'      => 'Slide CTA Typography Typography',
                            'responsive' => true,
                        ],
                        'slide_cta_margin' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide CTA Margin',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_cta_padding' => array(
                            'type'        => 'dimension',
                            'label'       => 'Slide CTA Padding',
                            'description' => 'px',
                            'responsive' => true,
                        ),
                        'slide_cta_width'     => array(
                            'type'    => 'select',
                            'label'   => __( 'Slide CTA Width', 'fl-builder' ),
                            'default' => 'full',
                            'options' => array(
                                'full' => 'Full',
                                'auto' => 'Auto',
                            ),
                        ),
                        'stretched_link' => [
                            'type' => 'select',
                            'label' => __("Slide CTA Link Style", 'skeleton-warrior'),
                            'default' => 'Stretched',
                            'options' => [
                                'stretched' => __("Stretched", 'skeleton-warrior'),
                                'normal'    => __("Normal", 'skeleton-warrior'),
                            ],
                        ],
                        'slide_cta_border' => array(
                            'type'       => 'border',
                            'label'      => __( 'Slide CTA Border Styles', 'fl-builder' ),
                            'responsive' => true,
                        ),
                        'slide_cta_border_hover' => array(
                            'type'       => 'border',
                            'label'      => __( 'Slide CTA Border Hover Styles', 'fl-builder' ),
                            'responsive' => true,
                        ),
                        'slide_cta_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide CTA Text Color', 'fl-builder' ),
                            'default'       => 'ffffff',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_cta_color_hover' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide CTA Text Hover Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_cta_color_background' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide CTA Background Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'slide_cta_color_background_hover' => [
                            'type'          => 'color',
                            'label'         => __( 'Slide CTA Background Hover Color', 'fl-builder' ),
                            'default'       => 'ffffff',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                    ]
                ],
            ]
        ],
        'navigation' => [
            'title' => __('Navigation','skeleton-warrior'),
            'sections' => [ 
                'arrows' => [
                    'title' => __('Arrow Navigation','skeleton-warrior'), 
                    'fields' => [
                        'arrows_size' => [
                            'type' => 'unit',
                            'label' => __('Size','skeleton-warrior'),
                            'units' => array('px', 'em', 'vw', '%'),
                            'default_unit' => 'px',
                            'default' => 10,
                        ],
                        'arrows_offset' => [
                            'type' => 'unit',
                            'label' => __('Arrow Offset','skeleton-warrior'),
                            'description' => 'px',
                            'responsive' => true
                        ],
                        'left_arrow_icon' => [
                            'type' => 'icon',
                            'label' => __('Left Arrow','skeleton-warrior'),
                            'show_remove' => true,
                        ],
                        'left_arrow_bg_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Left Arrow Color', 'skeleton-warrior' ),
                            'default'       => '222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'left_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Left Arrow Hover Color','skeleton-warrior'),
                            'default' => 'EC4067', 
                            'show_reset' => true,
                            'show_alpha' => true
                        ],
                        'left_arrow_bg_color_bg' => [
                            'type'          => 'color',
                            'label'         => __( 'Left Arrow Background Color', 'skeleton-warrior' ),
                            'default'       => '',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'left_arrow_bg_color_bg_hover' => [
                            'type' => 'color',
                            'label' => __('Left Arrow Background Hover Color','skeleton-warrior'),
                            'default' => '', 
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
                            'default'       => '222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'right_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Right Arrow Hover Color','skeleton-warrior'),
                            'default' => 'EC4067',
                            'show_reset' => true,
                            'show_alpha' => true
                        ],
                        'right_arrow_bg_color_bg' => [
                            'type'          => 'color',
                            'label'         => __('Right Arrow Background Color', 'skeleton-warrior'),
                            'default'       => '',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'right_arrow_bg_color_bg_hover' => [
                            'type' => 'color',
                            'label' => __('Right Arrow Background Hover Color','skeleton-warrior'),
                            'default' => '',
                            'show_reset' => true,
                            'show_alpha' => true
                        ],
                        'arrow_border' => array(
                            'type'       => 'border',
                            'label'      => __( 'Arrow Border', 'fl-builder' ),
                            'responsive' => true,
                        ),
                        'arrow_border_hover' => array(
                            'type'       => 'border',
                            'label'      => __( 'Arrow Border Hover', 'fl-builder' ),
                            'responsive' => true,
                        ),
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
                            'default' => '000000',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ],
                        'dots_color_active' => [
                            'type' => 'color',
                            'label' => __("Dot Color (Active)", 'skeleton-warrior'),
                            'default' => '3b68d0',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ],
                        'dots_color_hover' => [
                            'type' => 'color',
                            'label' => __("Dot Color (Hover/Focus)", 'skeleton-warrior'),
                            'default' => '447af7',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ],
                        'dots_border' => [
                            'type' => 'border',
                            'label'      => __( 'Dots Border ', 'fl-builder' ),
                            'responsive' => true,
                        ],
                        'dots_border_hover' => [
                            'type' => 'border',
                            'label'      => __( 'Dot Border Hover', 'fl-builder' ),
                            'responsive' => true,
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
                            'content_type' => [
                                'type' => 'select',
                                'label' => __('Content Type','skeleton-warrior'),
                                'options' => [
                                    'saved_row' => __('Saved Row','skeleton-warrior'),
                                    'standard' => __('Standard Content','skeleton-warrior'),
                                ],
                                'default' => 'saved_row',
                                'toggle' => [
                                    'saved_row' => [
                                        'fields' => array(
                                            'saved_content_row', 'same_row'
                                        )
                                    ],
                                    'standard' => [
                                        'fields' => array(
                                            'slide_title_two', 'slide_description', 'slide_cta','slide_cta_link', 'slide_image'
                                        )
                                    ]
                                ]
                            ],
                            'saved_content_row' => [
                                'type' => 'select',
                                'label' => __('Saved Row','skeleton-warrior'),
                                'options' => BWContentSlider::getSavedRows()
                            ],
                            'same_row' => [
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
                            ],
                            
                            'slide_title' => [
                                'type' => 'text',
                                'label' => __('Slide Title','skeleton-warrior')
                            ],
                            'slide_title_two' => [
                                'type' => 'text',
                                'label' => __('Slide Title 2','skeleton-warrior')
                            ],
                            'slide_description' => [
                                'type' => 'textarea',
                                'label' => __('Slide Description','skeleton-warrior'),
                                'placeholder' => __('Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis molestiae doloribus iure.','skeleton-warrior'),
                                'rows'  => '3'
                            ],
                            'slide_cta' => [
                                'type' => 'text',
                                'label' => __('Slide CTA Text','skeleton-warrior')
                            ],
                            'slide_cta_link' => [
                                'type' => 'link',
                                'label' => __('Slide CTA Link','skeleton-warrior'),
                                'show_target'   => true,
						        'show_nofollow' => true,
                            ],
                            'slide_image' => [
                                'type' => 'photo',
                                'label' => __('Slide Image','skeleton-warrior'),
                                'show_remove'   => true,
                            ],
                        ]
                    ]
                ]
            ]
        ]
    ]
);