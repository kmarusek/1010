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
        /* Not Showing any fields 2/21/20 */
        // This should have corrected the issue -- Update 2/24/20 It didn't resolve the problem
        // Should have fixed the above issue 2/24/20 -- Resolved 2/24/20
        // Color the background color of the arrow should change and not the arrow itself 
        'slider_arrows' => [
            'title' => __('Slider Arrows','fl-builder'),
            'sections' => [ 
                'title_text' => [
                    'title' => __('Arrow Styles','fl-builder'), 
                    'fields' => [
                        'left_arrow_icon' => [
                            'type' => 'icon',
                            'label' => __('Left Arrow','fl-builder'),
                            'show_remove' => true,
                        ],
                        'left_arrow_bg_color' => [
                            'type'          => 'color',
                            'label'         => __( 'Left Arrow Color', 'fl-builder' ),
                            'default'       => '#222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'left_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Left Arrow Hover Color','fl-builder'),
                            'default' => '#EC4067', 
                            'show_reset' => true,
                            'show_alpha' => true
                        ],
                        'right_arrow_icon' => [
                            'type' => 'icon',
                            'label' => __('Right Arrow','fl-builder'),
                            'show_remove' => true,
                        ],
                        'right_arrow_bg_color' => [
                            'type'          => 'color',
                            'label'         => __('Right Arrow Color', 'fl-builder'),
                            'default'       => '#222222',
                            'show_reset'    => true,
                            'show_alpha'    => true
                        ],
                        'right_arrow_bg_color_hover' => [
                            'type' => 'color',
                            'label' => __('Right Arrow Hover Color','fl-builder'),
                            'default' => '#EC4067',
                            'show_reset' => true,
                            'show_alpha' => true
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
        'title' => __('Slider Content', 'fl-builder'),
        'tabs' => [
            'general' => [
                'title' => __('General','fl-builder'),
                'sections' => [
                    'general' => [
                        'title' => __("General", 'fl-builder'),
                        'fields' => [
                            'slide_title' => [
                                'type' => 'text',
                                'label' => __('Slide Title','fl-builder')
                            ],
                            'saved_content_row' => [
                                'type' => 'select',
                                'label' => __('Saved Row','fl-builder'),
                                'options' => BWContentSlider::getSavedRows()
                            ],
                            'saved_row' => [
                                'type' => 'select',
                                'label' => __('Use same row on mobile?','fl-builder'),
                                'options' => [
                                    true => __('Yes','fl-builder'),
                                    false => __('No','fl-builder')
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
                                'label' => __('Mobile Saved Row','fl-builder'),
                                'options' => BWContentSlider::getSavedRows()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
);