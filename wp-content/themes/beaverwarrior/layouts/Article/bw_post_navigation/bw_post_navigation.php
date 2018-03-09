<?php

/**
 * @since 1.0
 * @class BWPostNavigationModule
 */
class BWPostNavigationModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Advanced Post Navigation', 'fl-theme-builder' ),
			'description'   	=> __( 'Displays the next / previous post navigation links.', 'fl-theme-builder' ),
         'category' => __("Space Station", 'skeleton-warrior'),
			'partial_refresh'	=> true,
			'dir'               => get_stylesheet_directory() . 'layouts/Article/bw_post_navigation/',
			'url'               => get_stylesheet_directory_uri() . 'layouts/Article/bw_post_navigation/',
			//'enabled'           => FLThemeBuilderLayoutData::current_post_is( 'singular' ),
		));
	}
}

FLBuilder::register_module( 'BWPostNavigationModule', array(
		'general'       => array(
			'title'         => __( 'Settings', 'fl-theme-builder' ),
			'sections'      => array(
				'nextprev'       => array(
					'title'         => __('Next / Previous', "skeleton-warrior"),
					'fields'        => array(
						'in_same_term' => array(
							'type'          => 'select',
							'label'         => __( 'Navigate in same category', 'fl-theme-builder' ),
							'default'       => '0',
							'options'       => array(
								'1'             => __( 'Enable', 'fl-theme-builder' ),
								'0'             => __( 'Disable', 'fl-theme-builder' ),
							),
						),
					),
				),
             'related' => array(
                "title" => __("Related Posts", "skeleton-warrior"),
                 "fields" => array(
                    'post_bg_color'        => array(
                        'type'       => 'color',
                        'label'      => __('Background Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post',
                            'property'        => 'background-color',
                        )
                    ),
                    'post_title_color'        => array(
                        'type'       => 'color',
                        'label'      => __('Title Text Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_title',
                            'property'        => 'color',
                        )
                    ),
                     "post_title_font_size" => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Title Font Size', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_title',
                            'property'        => 'font-size',
                            'unit'            => 'px'
                        )
                    ),
                    'post_title_line_height'    => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Title Line Height', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_title',
                            'property'        => 'line-height',
                            'unit'            => 'px'
                        )
                    ),
                 )
             )
			),
		),
) );
