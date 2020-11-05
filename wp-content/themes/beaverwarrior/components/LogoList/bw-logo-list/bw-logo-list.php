
<?php
/**
 * @class BWLogoList
 *
 */
class BWLogoList extends BeaverWarriorFLModule {
    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Logo List', 'fl-builder'),
                'description'     => __('A Logo List module', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            ]
        );
    }
}

FLBuilder::register_module( 'BWLogoList', [
    'team' => array(
        'title'    => __( 'Logo List', 'fl-builder' ),
        'sections' => array(
            'general' => array(
                'title'  => '',
                'fields' => array(
                    'the_logo' => array(
                        'type'         => 'form',
                        'label'        => __( 'Logo', 'fl-builder' ),
                        'form'         => 'logo_form', // ID from registered form below
                        'preview_text' => 'title', // Name of a field to use for the preview text
                        'multiple'     => true,
                    ),
                    'col_desktop' => [
                        'type' => 'unit',
                        'label' => __('Number of Columns - Desktop'),
                        'description' => 'Columns',
                        'slider' => array(
                            'min'   => 0,
                            'max'   => 10,
                            'step'  => 1,
                          ),
                    ],
                    'col_tablet' => [
                        'type' => 'unit',
                        'label' => __('Number of Columns - Tablet'),
                        'description' => 'Columns',
                        'slider' => array(
                            'min'   => 0,
                            'max'   => 10,
                            'step'  => 1,
                          ),
                    ],
                    'col_mobile' => [
                        'type' => 'unit',
                        'label' => __('Number of Columns - Mobile'),
                        'description' => 'Columns',
                        'slider' => array(
                            'min'   => 0,
                            'max'   => 10,
                            'step'  => 1,
                          ),
                    ],
                    'margin' => array(
                        'type'        => 'dimension',
                        'label'       => 'Logo Margins',
                        'description' => 'px',
                        'responsive' => true
                    ),
                    'padding' => array(
                        'type'        => 'dimension',
                        'label'       => 'Logo Padding',
                        'description' => 'px',
                        'responsive' => true
                    ),
                    'marquee' => array(
                        'type'          => 'select',
                        'label'         => __( 'Marquee', 'fl-builder' ),
                        'default'       => 'option-2',
                        'options'       => array(
                          'option-1'      => __( 'Enable', 'fl-builder' ),
                          'option-2'      => __( 'Disable', 'fl-builder' )
                        )
                    ),
                    'overlay_gradient_left' => array(
                        'type'    => 'gradient',
                        'label'   => 'Left Gradient',
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.overlay-gradient',
                            'property' => 'background-image',
                        ),
                    ),
                    'overlay_gradient_right' => array(
                        'type'    => 'gradient',
                        'label'   => 'Right Gradient',
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.overlay-gradient',
                            'property' => 'background-image',
                        ),
                    ),
                ),
            ),
        ),
    ),
]   
);

FLBuilder::register_settings_form('logo_form', array(
	'title' => __( 'Add Logo', 'fl-builder' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'fl-builder' ), // Tab title
			'sections' => array( // Tab Sections
				'general' => array( // Section
					'title'  => '', // Section Title
                    'fields' => array( // Section Fields
                        'title' => array(
							'type'  => 'text',
							'label' => __( 'Logo Title', 'fl-builder' ),
                        ),
						'image' => array(
							'type'  => 'photo',
							'label' => __( 'Logo Image', 'fl-builder' ),
                        ),
                        'logo_sizes_field' => array(
                            'type'          => 'photo-sizes',
                            'label'         => __('Logo Size', 'fl-builder'),
                            'default'       => 'medium'
                          ),
                        'bg_color' => array(
                            'type'  => 'color',
                            'label' => __( 'Logo Background Color', 'fl-builder' ),
                            'default'       => 'ffffff',
                            'show_reset'    => true,
                            'show_alpha'    => true,
                        ),
                        'url_option' => array(
                            'type'          => 'select',
                            'label'         => __( 'Select Field', 'fl-builder' ),
                            'default'       => 'option-1',
                            'options'       => array(
                              'option-1'      => __( 'Link Enabled', 'fl-builder' ),
                              'option-2'      => __( 'Link Disabled', 'fl-builder' )
                            ),
                            'toggle'        => array(
                              'option-1'      => array(
                                'fields'        => array( 'url' ),
                              ),
                              'option-2'      => array()
                            )
                          ),
                        'url' => [
                            'type' => 'link',
                            'label' => __('Link', 'fl-builder'),
                            'show_target'   => true,
                            'show_nofollow' => true,
                        ],
                        // 'url_typography' => [
                        //     'type' => 'typography',
                        //     'label' => __('Link Styles', 'fl-builder'),
                        //     'responsive' => true
                        // ],
					),
				),
			),
		),
	),
)	); 