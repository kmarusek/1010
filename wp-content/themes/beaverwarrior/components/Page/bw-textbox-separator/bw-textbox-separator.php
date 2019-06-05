<?php
/**
 * @class BWTextboxSeparator
 *
 */
class BWTextboxSeparator extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Textbox Separator', 'fl-builder'),
                'description'     => __('A simple separator module.', 'fl-builder'),
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
     * Method used to get the text for the textbox.
     *
     * @return string The text for the textbox
     */
    public function getTextboxHeaderText(){
        return $this->settings->textbox_header_text ? $this->settings->textbox_header_text : 'Textbox Header';
    }

    /**
     * Method to get the header type for this instance.
     *
     * @return string The header tag type
     */
    public function getTextboxHeaderType(){
        return $this->settings->textbox_header_type;
    }
}

FLBuilder::register_module( 
    'BWTextboxSeparator', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'title' => __( 'Header', 'fl-builder'),
                    'fields' => array(
                        'textbox_header_text' => array(
                            'type'        => 'text',
                            'label'       => __( 'Text', 'fl-builder'),
                            'placeholder' => 'Textbox text'
                        ),
                        'textbox_header_type' => array(
                            'type'    => 'select',
                            'label'   => __( 'Type', 'fl-builder'),
                            'default' => 'h5',
                            'options' => array(
                                'h1' => 'Header One',
                                'h2' => 'Header Two',
                                'h3' => 'Header Three',
                                'h4' => 'Header Four',
                                'h5' => 'Header Five',
                                'h6' => 'Header Six'
                            )
                        )
                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'section_textbox_style' => array(
                    'fields' => array(
                        'textbox_padding' => array(
                            'type'         => 'dimension',
                            'label'        => __( 'Textbox padding', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default'      => '20',
                            'default_unit' => 'px',
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 100,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.textbox',
                                'property' => 'padding'
                            )
                        ),
                        'textbox_border' => array(
                            'type'  => 'border',
                            'label' => __( 'Textbox border', 'fl-builder')
                        )
                    )
                )
            )
        ),
        'typography' => array(
            'title' => __( 'Typography', 'fl-builder'),
            'sections' => array(
                'section_textbox_typography' => array(
                    'fields' => array(
                        'textbox_typography' => array(
                            'type' => 'typography',
                            'label'       => __( 'Textbox', 'fl-builder'),
                            'preview'     => array(
                                'type'     => 'css',
                                'selector' => '.textbox-header'
                            )
                        ),
                        'textbox_header_color' => array(
                            'type'       => 'color',
                            'label'      => __( 'Header color', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.textbox-header',
                                'property' => 'color'
                            )
                        )
                    )
                )
            )
        )
    )
);