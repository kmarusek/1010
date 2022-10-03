<?php

/**
 * @class BWSearchResults
 *
 */
class BWSearchResults extends BeaverWarriorFLModule {

    /**
     * The taxonomy for post categories
     */
    const POST_TAXONOMY_CATEGORY = 'category';

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Search Results', 'fl-builder'),
                'description'     => __('A posts search results grid module.', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }   

}

FLBuilder::register_module( 
    'BWSearchResults', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'btn_title' => array(
                            'type'          => 'text',
                            'label'         => __( 'Button Text', 'fl-builder' ),
                        ),
                        'btn_icon' => array(
                            'type'          => 'icon',
                            'label'         => __( 'Button Icon', 'fl-builder' ),
                            'show_remove'   => true
                        ),
                    )
                ),
            )
        ),
        'style' => array( //
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'posts_margin' => array(
                            'type'         => 'dimension',
                            'label'        => __( 'Post margin', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'default'      => 20,
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'      => 'css',
                                'selector'  => '.SearchResults-container .post',
                                'property'  => 'margin'
                            )
                        )
                    )
                ),
            ) //
        ) //
    ) //
);
