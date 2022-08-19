<?php
/**
 * @class BWFeaturedEvent
 *
 */
class BWFeaturedEvent extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Featured Event', 'skeleton-warrior'),
                'description'     => __('Featured event module.', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true
            ]
        );
        
        // register_custom_image_size( 'testimonial_quotes', 34, 40, true );
    }
    
    
};

FLBuilder::register_module('BWFeaturedEvent', array(
    'general' => array (
        'title' => __('Featured Event General', 'fl-builder'),
        'sections' => array (
            'general' => array (
                'title' => __("Featured Event Main Settings", 'skeleton-warrior'),
            ),
            'content' => array (
                'title' => __("Content", 'skeleton-warrior'),
                'fields' => array (
                    'featured_events' => array(
                        'type' => 'form',
                        'label' => __('Featured Events', 'skeleton-warrior'),
                        'form' => 'bw_featured_event',
                        'preview_text' => 'slide_title',
                        'multiple' => true
                    ),
                )
            ),
            
        )
    ),
    'featured_event-style' => array (
        'title' => __('Style','skeleton-warrior'),
        'sections' => array (
            'style' => array (
                // 'title' => __("Style", 'skeleton-warrior'),
                'fields' => array (
                    'featured_event_main_title_typography' =>  array(
                        'type'       => 'typography',
                        'label'      => 'Featured Event Title Typography',
                        'responsive' => true,
                    ),
                    'featured_event_type' => array(
                        'type'          => 'typography',
                        'label'         => 'Event Type Typography',
                        'responsive'    => true,
                    ),
                    'featured_event_date_from' => array(
                        'type'          => 'typography',
                        'label'         => 'Event From Date Typography',
                        'responsive'    => true,
                    ),
                    'featured_event_date_to' => array(
                        'type'          => 'typography',
                        'label'         => 'Event To Date Typography',
                        'responsive'    => true,
                    ),
                    'featured_event_description' => array(
                        'type'          => 'typography',
                        'label'         => 'Event Description Typography',
                        'responsive'    => true,
                    ),
                    'featured_event_learn_more' => array(
                        'type'          => 'typography',
                        'label'         => 'Learn More Typography',
                        'responsive'    => true,
                    ),

                    'featured_event_main_title_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Title Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'featured_event_type_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Type Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ),
                    'featured_event_date_from_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Date From Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'featured_event_date_to_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Date To Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'featured_event_description_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Description Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ),    
                    'featured_event_link_button_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Learn More Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ),               
                )
            ),
        ),
    ),
    
)
);

FLBuilder::register_settings_form( 'bw_featured_event', array(
    'title' => __('Single Featured Event', 'skeleton-warrior'),
    'tabs' => array(
        'content' => array(
            'title' => __('Featured Event Content','skeleton-warrior'),
            'sections' => array(
                'content' => array(
                    'title' => __("Content", 'skeleton-warrior'),
                    'fields' => array(

                        'featured_event_image' => array(
                            'type' => 'photo',
                            'label' => __('Event Image','skeleton-warrior'),
                            'show_remove'   => true,
                        ),

                        'featured_event_type' => array(
                            'type' => 'text',
                            'label' => __('Event Type', 'skeleton-warrior'),
                        ),
                        'featured_event_date_from' => array(
                            'type' => 'date',
                            'label' => __('Event From Date','skeleton-warrior')
                        ),
                        'featured_event_date_to' => array(
                            'type' => 'date',
                            'label' => __('Event To Date','skeleton-warrior')
                        ),
                        'featured_event_main_title' => array(
                                'type' => 'text',
                                'label' => __('Event Title', 'skeleton-warrior'),
                        ),

                        'featured_event_description' => array(
                            'type' => 'text',
                            'label' => __('Event Description', 'skeleton-warrior')
                        ),
                        'featured_event_link_button' => array(
                            'type' => 'text',
                            'label' => __('Learn More Text', 'skeleton-warrior')
                        ),
                        'featured_event_link' => array(
                            'type'          => 'link',
                            'label'         => 'Link',
                            'show_target'   => true,
                            'show_nofollow' => true,
                        ),
                    )
                )
            )
        )
    )
)
);
