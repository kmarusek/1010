<?php

/**
 * @class BWLegal
 *
 */
class BWLegal extends BeaverWarriorFLModule
{

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct()
    {
        FLBuilderModule::__construct(
            array(
                'name'            => __('Legal', 'fl-builder'),
                'description'     => __('Legal', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory(__DIR__),
                'url'             => $this->getModuleDirectoryURI(__DIR__),
                'editor_export'   => true,
                'enabled'         => true,
                'partial_refresh' => true
            )
        );
    }
}

FLBuilder::register_module(
    'BWLegal',
    [
        'team' => array(
            'title'    => __('Hidden Legal', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'legal_option' => [
                            'type' => 'text',
                            'label' => __('Title', 'fl-builder'),
                            'responsive' => true,
                        ],
                        'legal_item' => array(
                            'type'         => 'form',
                            'label'        => __('Legal item', 'fl-builder'),
                            'form'         => 'team_group_form', // ID from registered form below
                            'preview_text' => 'name', // Name of a field to use for the preview text
                            'multiple'     => true,
                        ),
                    ),
                ),
            ),
        ),
        'typography' => [
            'title' => __('Typography', 'fl-builder'),
            'sections' => [
                'general' => [
                    'title' => __('General', 'fl-builder'),
                    'fields' => [
                        'legal_option_typography' => [
                            'type' => 'typography',
                            'label' => __('Title Typography', 'fl-builder'),
                            'responsive' => true,
                        ],
                        'legal_option_color' => array(
                            'type'  => 'color',
                            'label' => __('Title Color', 'fl-builder'),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'modal_name_typography' => [
                            'type' => 'typography',
                            'label' => __('Modal Title Typography'),
                            'responsive' => true,
                        ],
                        'modal_name_color' => array(
                            'type'  => 'color',
                            'label' => __('Modal Title Color', 'fl-builder'),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'modal_text_typography' => [
                            'type' => 'typography',
                            'label' => __('Modal Text Typography'),
                            'responsive' => true,
                        ],
                        'modal_text_color' => array(
                            'type'  => 'color',
                            'label' => __('Modal Text Color', 'fl-builder'),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                    ]
                ],
            ]
        ]
    ]
);

FLBuilder::register_settings_form('team_group_form', array(
    'title' => __('Add item', 'fl-builder'),
    'tabs'  => array(
        'general' => array( // Tab
            'title'    => __('General', 'fl-builder'), // Tab title
            'sections' => array( // Tab Sections
                'general' => array( // Section
                    'title'  => '', // Section Title
                    'fields' => array( // Section Fields
                        'name' => array(
                            'type'  => 'text',
                            'label' => __('Title', 'fl-builder'),
                        ),
                        'modal_text' => array(
                            'type'          => 'editor',
                            'label'         => __('Modal text', 'fl-builder'),
                            'show_target'   => true,
                            'show_nofollow' => false,
                        ),
                    ),
                ),
            ),
        ),
    ),
));
