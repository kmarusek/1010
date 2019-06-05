<?php
/**
 * @class BWDescriptiveList
 *
 */
class BWDescriptiveList extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Descriptive List', 'fl-builder'),
                'description'     => __('A descriptive list module.', 'fl-builder'),
                'category'        => __('Content', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }   

    /**
     * Method to get the list items for this module.
     *
     * @return array An array of items
     */
    public function getListItems(){
        return is_array( $this->settings->descriptive_list_items ) ? $this->settings->descriptive_list_items : array();
    }
}

FLBuilder::register_module( 
    'BWDescriptiveList', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'descriptive_list_items' => array(
                            'label'        => __('Terms', 'fl-builder'),
                            'type'         => 'form',
                            'form'         => 'bw_descriptive_list_items', 
                            'preview_text' => 'dt_text',
                            'multiple'     => true
                        )
                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'style_general' => array(
                    'fields' => array(
                        'icon_margin_right' => array(
                            'label'         => __('Icon margin-right', 'fl-builder'),
                            'type'          => 'unit',
                            'units'         => array( 'px' ),
                            'default_units' => 'px',
                            'default'       => 10,
                            'slider'        => true,
                            'preview'       => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container li .icon-container',
                                'property' => 'margin-right'
                            )
                        ),
                        'dt_margin_top' => array(
                            'label'         => __('Term margin-top', 'fl-builder'),
                            'type'          => 'unit',
                            'units'         => array( 'px' ),
                            'default_units' => 'px',
                            'default'       => 10,
                            'slider'        => true,
                            'preview'       => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container li',
                                'property' => 'margin-top'
                            )
                        ),
                        'dd_margin_top' => array(
                            'label'         => __('Definition margin-top', 'fl-builder'),
                            'type'          => 'unit',
                            'units'         => array( 'px' ),
                            'default_units' => 'px',
                            'default'       => 7,
                            'slider'        => true,
                            'preview'       => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container dd',
                                'property' => 'margin-top'
                            )
                        )
                    )
                )
            )
        ),
        'typography' => array(
            'title' => __( 'Typography', 'fl-builder'),
            'sections' => array(
                'typography_term' => array(
                    'title' => __( 'Term', 'fl-builder'),
                    'fields' => array(
                        'dt_typography' => array(
                            'label'      => __('Typography', 'fl-builder'),
                            'type'       => 'typography',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container dt'
                            )
                        ),
                        'dt_color' => array(
                            'label'      => __('Color', 'fl-builder'),
                            'type'       => 'color',
                            'show_alpha' => true,
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container dt',
                                'property' => 'color'
                            )
                        )
                    )
                ),
                'typography_definition' => array(
                    'title' => __( 'Definition', 'fl-builder'),
                    'fields' => array(
                        'dd_typography' => array(
                            'label'      => __('Typography', 'fl-builder'),
                            'type'       => 'typography',
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container dd'
                            )
                        ),
                        'dd_color' => array(
                            'label'      => __('Color', 'fl-builder'),
                            'type'       => 'color',
                            'show_alpha' => true,
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.descriptive-list-container dd',
                                'property' => 'color'
                            )
                        )
                    )
                )
            )
        )
    )
);

/**
 * Register the settings for each of the slides in the slider
 */
FLBuilder::register_settings_form('bw_descriptive_list_items', 
    array(
        'title' => __( 'Add list item', 'fl-builder' ),
        'tabs'  => array(
            'general'      => array(
                'title' => __( 'General', 'fl-builder' ),
                'sections'      => array(
                    'general' => array(
                        'fields' => array(
                            'dt_text'=> array(
                                'label' => __('Term', 'fl-builder'),
                                'type'  => 'text'
                            ),
                            'dd_text'=> array(
                                'label' => __('Definition', 'fl-builder'),
                                'type'  => 'text'
                            )
                        )
                    ),
                    'icon' => array(
                        'title' => __( 'Icon', 'fl-builder' ),
                        'fields' => array(
                            'icon_enabled'=> array(
                                'label'   => __('Icon', 'fl-builder'),
                                'type'    => 'select',
                                'default' => 'enabled',
                                'options' => array(
                                    'enabled'  => 'Enabled',
                                    'disabled' => 'Disabled'
                                ),
                                'toggle' => array(
                                    'enabled' => array(
                                        'fields' => array(
                                            'icon',
                                            'icon_size',
                                            'icon_color'
                                        )
                                    )
                                )
                            ),
                            'icon'=> array(
                                'label' => __('Icon', 'fl-builder'),
                                'type'  => 'icon'
                            ),
                            'icon_size'=> array(
                                'label'        => __('Icon Size', 'fl-builder'),
                                'type'         => 'unit',
                                'units'        => array( 'px' ),
                                'default_unit' =>'px',
                                'default'      => 20
                            ),
                            'icon_color'=> array(
                                'label'      => __('Icon Color', 'fl-builder'),
                                'type'       => 'color',
                            )
                        )
                    )
                )
            )
        ) 
    ) 
);