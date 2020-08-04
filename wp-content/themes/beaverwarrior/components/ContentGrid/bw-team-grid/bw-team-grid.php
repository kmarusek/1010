<?php

/**
 * @class BWTeamGrid
 *
 */
class BWTeamGrid extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Content Grid', 'fl-builder'),
                'description'     => __('A Content Grid Module', 'fl-builder'),
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

FLBuilder::register_module( 'BWTeamGrid', [
        'team' => array(
            'title'    => __( 'Team Members', 'fl-builder' ),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'the_team_member' => array(
                            'type'         => 'form',
                            'label'        => __( 'Grid Items', 'fl-builder' ),
                            'form'         => 'team_group_form', // ID from registered form below
                            'preview_text' => 'name', // Name of a field to use for the preview text
                            'multiple'     => true,
                        ),
                        'col_desktop' => [
                            'type' => 'unit',
                            'label' => __('Number of Columns - Desktop'),
                            'description' => 'Columns'
                        ],
                        'col_tablet' => [
                            'type' => 'unit',
                            'label' => __('Number of Columns - Tabletp'),
                            'description' => 'Columns'
                        ],
                        'col_mobile' => [
                            'type' => 'unit',
                            'label' => __('Number of Columns - Mobile'),
                            'description' => 'Columns'
                        ],
                        'space_between' => [
                            'type' => 'unit',
                            'label' => __('Space Between Columns'),
                            'description' => 'px'
                        ]
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
                        'position_typography' => [
                            'type' => 'typography',
                            'label' => __('Subtitle Typography', 'fl-builder'),
                            'responsive' => true,
                        ],
                        'titles_color' => array(
                            'type'  => 'color',
                            'label' => __( 'Subtitle Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'names_typography' => [
                            'type' => 'typography',
                            'label' => __('Title Typography'),
                            'responsive' => true,
                        ],
                        'name_color' => array(
                            'type'  => 'color',
                            'label' => __( 'Title Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'titles_hover_color' => array(
                            'type'  => 'color',
                            'label' => __( 'Title/Subtitle Hover Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'cta_typography' => [
                            'type' => 'typography',
                            'label' => __('CTA Typography'),
                            'responsive' => true,
                        ],
                        'cta_color' => array(
                            'type'  => 'color',
                            'label' => __( 'CTA Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'cta_hover_color' => array(
                            'type'  => 'color',
                            'label' => __( 'CTA Hover Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                    ]
                ],
                'modal' => [
                    'title' => __('Modal', 'fl-builder'),
                    'fields' => [
                        'modal_position_typography' => [
                            'type' => 'typography',
                            'label' => __('Modal Subtitle Typography'),
                            'responsive' => true,
                        ],
                        'modal_position_color' => array(
                            'type'  => 'color',
                            'label' => __( 'Modal Subtitle Color', 'fl-builder' ),
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
                            'label' => __( 'Modal Title Color', 'fl-builder' ),
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
                            'label' => __( 'Modal Text Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'modal_close_color' => [
                            'type'  => 'color',
                            'label' => __( 'Modal Close Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                        'modal_close_hover_color' => [
                            'type'  => 'color',
                            'label' => __( 'Modal Close Hover Color', 'fl-builder' ),
                            'default'       => '000000',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ],
                    ]
                ]

            ]
        ]
    ]   
);

FLBuilder::register_settings_form('team_group_form', array(
	'title' => __( 'Add Member', 'fl-builder' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'fl-builder' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'image' => array(
							'type'  => 'photo',
							'label' => __( 'List Image', 'fl-builder' ),
						),
						'name' => array(
							'type'  => 'text',
							'label' => __( 'Title', 'fl-builder' ),
						),
						'position' => array(
							'type'  => 'text',
							'label' => __( 'Subtitle', 'fl-builder' ),
                        ),
                        'cta' => [
                            'type' => 'text',
                            'label' => __('CTA Text', 'fl-builder')
                        ],
                        'url' => [
                            'type' => 'link',
                            'label' => __('Link', 'fl-builder')
                        ],
                        'include_modal' => [
                            'type' => 'select',
                            'label' => __('Include Modal Popup', 'fl-builder'),
                            'options' => [
                                'yes' => 'Yes',
                                'no' => 'No'
                            ],
                            'default' => 'yes',
                            'toggle' => [
                                'yes' => [
                                    'fields' => [
                                        'modal_text',
                                        'modal_image'
                                    ]
                                ]
                            ]
                        ],
						'modal_text' => array(
							'type'          => 'editor',
							'label'         => __( 'Modal text', 'fl-builder' ),
							'show_target'   => true,
							'show_nofollow' => false,
						),
						'modal_image' => array(
							'type'  => 'photo',
							'label' => __( 'Modal Image', 'fl-builder' ),
						),
					),
				),
			),
		),
	),
)	);