<?php
/**
 * Created by PhpStorm.
 * User: stefanmitrevski
 * Date: 6.7.22
 * Time: 12:52
 */
class BWFeaturedResources extends BeaverWarriorFLModule {

    public function __construct()
    {
        FLBuilderModule::__construct(array(
            'name'            => __( 'Featured Resources', 'skeleton-warrior' ),
            'description'     => __( 'Featured Resources section', 'fl-builder' ),
            'category'        => __( 'Space Station', 'skeleton-warrior' ),
            'dirn'             => $this->getModuleDirectory( __DIR__ ),
            'url'             => $this->getModuleDirectoryURI( __DIR__ ),
        ));
    }

}
FLBuilder::register_module( 'BWFeaturedResources', array(
    'general'      => array(
        'title'         => __( 'General', 'fl-builder' ),
        'sections'      => array(
            'benefits_perks' => [
                'title' => __("Featured Resources", 'skeleton-warrior'),
                'fields' => [
                    'fr_subheading' => [
                        'type' => 'text',
                        'label' => __('Subheading', 'fl-builder'),
                        'responsive' => true,
                    ],
                    'fr_heading' => [
                        'type' => 'text',
                        'label' => __('Heading', 'fl-builder'),
                        'responsive' => true,
                    ],
                    'fr_btn_text' => [
                        'type' => 'text',
                        'label' => __('Button Text', 'fl-builder'),
                        'responsive' => true,
                    ],
                    'fr_btn_link' => [
                        'type' => 'link',
                        'label' => __('Button Link', 'fl-builder'),
                        'responsive' => true,
                    ],

                ]
            ],

        )
    )
) );