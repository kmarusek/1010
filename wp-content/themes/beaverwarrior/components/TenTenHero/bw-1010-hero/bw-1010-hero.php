<?php

/**
 * @class BWWorkHero
 *
 */
class BW1010Hero extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Hero Header', 'skeleton-warrior'),
                'description'     => __('Hero header with lens animation and overlayed image carousel.', 'fl-builder'),
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

FLBuilder::register_module('BW1010Hero', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array (
                'images' => array (
                    'title' => __("Hero Image(s)", 'skeleton-warrior'),
                    'fields' => array (
                        'home_page_check' => array(
                            'type'          => 'select',
                            'label'         => __( 'Is this the home page?', 'skeleton-warrior' ),
                            'default'       => 'not-homepage',
                            'options'       => array(
                              'not-homepage'      => __( 'No', 'skeleton-warrior' ),
                              'is-homepage'      => __( 'Yes', 'skeleton-warrior' )
                            ),
                        ),
                        'heros' => array(
                            'type' => 'form',
                            'label' => __('Hero Image', 'skeleton-warrior'),
                            'form' => 'bw_1010_hero',
                            'multiple' => true
                        ),
                    )
                ),
                'content' => array (
                    'title' => __("Content Side", 'skeleton-warrior'),
                    'fields' => array(
                        'subpage_subtitle' => array(
                            'type' => 'text',
                            'label' => __( 'Subpage Subtitle', 'skeleton-warrior'),
                        ),
                        'content_title' => array(
                            'type' => 'text',
                            'label' => __( 'Content Title', 'skeleton-warrior'),
                        ),
                        'content' => array(
                            'type' => 'textarea',
                            'label' => __( 'Content', 'skeleton-warrior'),
                        ),
                        'button_text' => array(
                            'type' => 'text',
                            'label' => __( 'Button Text', 'skeleton-warrior'),
                            'maxlength' => '15',
                        ),
                        'button_icon' => array(
                            'type'          => 'icon',
                            'label'         => __( 'Icon Field', 'fl-builder' ),
                            'show_remove'   => true
                        ),
                        'button_url' => array(
                            'type' => 'link',
                            'label' => __( 'Button URL', 'skeleton-warrior'),
                            'show_target' => true,
                            'show_nofollow' => true,
                        ),
                    )
                ),
            )
        ),
        'style' => array(
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
                        )
                    )
                ),
            ),
        ),
        'animation' => array(
            'title' => __( 'Animations', 'skeleton-warrior'),
            'sections' => array(
                'animation' => array(
                    'fields' => array(
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
                        'mask_image' => array (
                            'type' => 'photo',
                            'label' => __('Mask For Lens Animation', 'skeleton-warrior'),
                            'show_remove' => true,
                        ),
                    )
                ),
            ),
        ),
        
    )
);
FLBuilder::register_settings_form( 'bw_1010_hero', array(
    'title' => __('Individual Hero Image', 'skeleton-warrior'),
    'tabs' => array(
        'content' => array(
            'title' => __('Single Image','skeleton-warrior'),
            'sections' => array(
                'content' => array(
                    'title' => __("Images", 'skeleton-warrior'),
                    'fields' => array(
                        'hero_image' => array(
                            'type' => 'photo',
                            'label' => __('Hero Image','skeleton-warrior'),
                            'show_remove'   => true,
                        ),
                    )
                )
            )
        )
    )
)
);