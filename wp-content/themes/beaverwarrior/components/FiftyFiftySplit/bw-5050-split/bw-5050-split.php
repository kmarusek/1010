<?php

/**
 * @class BWWorkHero
 *
 */
class BW5050Split extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('50/50 Split', 'skeleton-warrior'),
                'description'     => __('Left & Right Image and Content Split', 'fl-builder'),
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

FLBuilder::register_module('BW5050split', array(
        'general' => array(
            'title' => __( '50/50 Split General', 'fl-builder'),
            'sections' => array (
                'image-tab' => array (
                    'title' => __( "Image Side", 'skeleton-warrior'),
                    'fields' => array (
                        'image_side' => array(
                            'type'          => 'select',
                            'label'         => __( 'Image Display', 'skeleton-warrior' ),
                            'default'       => 'option-1',
                            'options'       => array(
                              'option-1'      => __( 'Leftside', 'skeleton-warrior' ),
                              'option-2'      => __( 'Rightside', 'skeleton-warrior' )
                            ),
                        ),
                        'primary_image' => array (
                            'type' => 'photo',
                            'label' => __('Primary Image', 'skeleton-warrior'),
                            'show_remove' => true,
                        ),
                        'background_image' => array (
                            'type' => 'photo',
                            'label' => __('Background Image', 'skeleton-warrior'),
                            'show_remove' => true,
                        ),
                        'lens_image' => array (
                            'type' => 'photo',
                            'label' => __('Animated Lens Image', 'skeleton-warrior'),
                            'show_remove' => true,
                        ),
                    )
                ),
                'content-tab' => array (
                    'title' => __("Content Side", 'skeleton-warrior'),
                    'fields' => array(
                        'content_title' => array(
                            'type' => 'text',
                            'label' => __( 'Content Title', 'skeleton-warrior'),
                        ),
                        'content' => array(
                            'type' => 'text',
                            'label' => __( 'Content', 'skeleton-warrior'),
                        ),
                        'button_text' => array(
                            'type' => 'text',
                            'label' => __( 'Button Text', 'skeleton-warrior'),
                            'maxlength' => '15',
                        ),
                        'button_url' => array(
                            'type' => 'link',
                            'label' => __( 'Button URL', 'skeleton-warrior'),
                            'show_target' => true,
                            'show_nofollow' => true,
                        ),
                    )
                ),
                'mobile-tab' => array (
                    'title' => __( "Mobile Accordion", 'skeleton-warrior'),
                    'fields' => array (
                        'accordion_title' => array(
                            'type' => 'text',
                            'label' => __( 'Mobile Accordion Title', 'skeleton-warrior'),
                        ),
                    )
                ),
            )
        ),
        'style-5050-tab' => array(
            'title' => __( 'Style', 'skeleton-warrior'),
            'sections' => array(
                'style' => array(

                    'fields' => array(
                        'background_color' => array(
                            'type' => 'color',
                            'label' => __('Background Color Picker', 'skeleton-warrior'),
                            'default' => '',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ),
                        'title_color' => array(
                            'type' => 'color',
                            'label' => __('Title Color Picker', 'skeleton-warrior'),
                            'default' => '',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ),
                        'content_color' => array(
                            'type' => 'color',
                            'label' => __('Content Color Picker', 'skeleton-warrior'),
                            'default' => '',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ),
                        'button_color' => array(
                            'type' => 'color',
                            'label' => __('Button Color Picker', 'skeleton-warrior'),
                            'default' => '',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ),
                        'button_hover' => array(
                            'type' => 'color',
                            'label' => __('Button:Hover Color Picker', 'skeleton-warrior'),
                            'default' => '#eee',
                            'show_reset' => true,
                            'show_alpha' => true,
                        ),
                    )
                ),
            ),
        ),

    )
);


