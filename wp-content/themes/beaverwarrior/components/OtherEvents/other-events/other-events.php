<?php
/**
 * @class BWOtherEvents
 *
 */
class BWOtherEvents extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Other Events', 'skeleton-warrior'),
                'description'     => __('Other event module.', 'fl-builder'),
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

FLBuilder::register_module('BWOtherEvents', array(
    'general' => array (
        'title' => __('Other Events General', 'fl-builder'),
        'sections' => array (
            'general' => array (
                'title' => __("Other Events Main Settings", 'skeleton-warrior'),
            ),
            'content' => array (
                'title' => __("Content", 'skeleton-warrior'),
                'fields' => array (
                    'other_eventse' => array(
                        'type' => 'form',
                        'label' => __('Other Eventss', 'skeleton-warrior'),
                        'form' => 'bw_other_events',
                        'preview_text' => 'slide_title',
                        'multiple' => true
                    ),
                )
            ),
            
        )
    ),
    'other_events-style' => array (
        'title' => __('Style','skeleton-warrior'),
        'sections' => array (
            'style' => array (
                // 'title' => __("Style", 'skeleton-warrior'),
                'fields' => array (
                    'other_events_main_title_typography' =>  array(
                        'type'       => 'typography',
                        'label'      => 'Other Events Title Typography',
                        'responsive' => true,
                    ),
                    'other_events_type' => array(
                        'type'          => 'typography',
                        'label'         => 'Event Type Typography',
                        'responsive'    => true,
                    ),
                    'other_events_date_from' => array(
                        'type'          => 'typography',
                        'label'         => 'Event From Date Typography',
                        'responsive'    => true,
                    ),
                    'other_events_date_to' => array(
                        'type'          => 'typography',
                        'label'         => 'Event To Date Typography',
                        'responsive'    => true,
                    ),
                    'other_events_description' => array(
                        'type'          => 'typography',
                        'label'         => 'Event Description Typography',
                        'responsive'    => true,
                    ),
                    'other_events_learn_more' => array(
                        'type'          => 'typography',
                        'label'         => 'Learn More Typography',
                        'responsive'    => true,
                    ),

                    'other_events_main_title_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Title Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'other_events_type_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Type Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ),
                    'other_events_date_from_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Date From Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'other_events_date_to_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Date To Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ), 
                    'other_events_description_color' =>  array(
                        'type'          => 'color',
                        'label'         => __( 'Event Description Color', 'skeleton-warrior' ),
                        'default'       => '',
                        'show_reset'    => true,
                        'show_alpha'    => true,
                    ),    
                    'other_events_link_button_color' =>  array(
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

FLBuilder::register_settings_form( 'bw_other_events', array(
    'title' => __('Single Other Events', 'skeleton-warrior'),
    'tabs' => array(
        'content' => array(
            'title' => __('Other Events Content','skeleton-warrior'),
            'sections' => array(
                'content' => array(
                    'title' => __("Content", 'skeleton-warrior'),
                    'fields' => array(

                        'other_events_image' => array(
                            'type' => 'photo',
                            'label' => __('Event Image','skeleton-warrior'),
                            'show_remove'   => true,
                        ),

                        'other_events_type' => array(
                            'type' => 'select',
                            'label' => __('Select Event Type', 'skeleton-warrior'),
                            'default'       => 'Team',
                            'options'       => array(
                            'Team'      => __( 'Team', 'fl-builder' ),
                            'SpaceX'      => __( 'SpaceX', 'fl-builder' ),
                            'Party'      => __( 'Party', 'fl-builder' )
                            )
                        ),
                        'other_events_date_from' => array(
                            'type' => 'date',
                            'label' => __('Event From Date','skeleton-warrior')
                        ),
                        'other_events_date_to' => array(
                            'type' => 'date',
                            'label' => __('Event To Date','skeleton-warrior')
                        ),
                        'other_events_main_title' => array(
                                'type' => 'text',
                                'label' => __('Event Title', 'skeleton-warrior'),
                        ),

                        'other_events_description' => array(
                            'type' => 'text',
                            'label' => __('Event Description', 'skeleton-warrior')
                        ),
                        'other_events_link_button' => array(
                            'type' => 'text',
                            'label' => __('Learn More Text', 'skeleton-warrior')
                        ),
                        'other_events_link' => array(
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
