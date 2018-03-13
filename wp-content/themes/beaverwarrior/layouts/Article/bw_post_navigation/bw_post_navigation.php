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
                    'post_limit'        => array(
                        'type'       => 'select',
                        'label'      => __('Posts Count', 'uabb'),
                        'default'    => '3',
                        'options'    => array(
                            '2' => '2',
                            '3' => '3',
                            '4' => '4'
                        )
                    ),
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
                    'layout_sort_order' => array(
                        'type' => 'uabb-sortable',
                        'label' => __('Layout Order', 'skeleton-warrior'),
                        'description' => __('Hidden components will still show here, but won\'t be visible elsewhere.', 'skeleton-warrior'),
                        'default' => 'img,title,meta,content,cta',
                        'options' => array(
                            'img' => __('Featured Image','uabb'),
                            'title' => __('Title', 'uabb'),
                            'meta' => __('Meta', 'uabb'),
                            'content' => __('Content', 'uabb'),
                            'cta' => __('CTA', 'uabb'),
                        ),
                    ),
                    'show_featured_image' => array(
                        'type'          => 'uabb-toggle-switch',
                        'label'         => __( 'Display Featured Image', 'uabb' ),
                        'help'          => __('Enable this to display featured image of posts in a module.', 'uabb'),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                    ),
                    'show_title' => array(
                        'type'          => 'uabb-toggle-switch',
                        'label'         => __( 'Display Title', 'uabb' ),
                        'help'          => __('Enable this to display title of posts in a module.', 'uabb'),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle' => array(
                            'yes' => array(
                                'fields' => array('post_title_color', 'post_title_font_size', 'post_title_line_height')
                            )
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
                    'show_meta' => array(
                        'type'          => 'uabb-toggle-switch',
                        'label'         => __( 'Display Meta Information', 'uabb' ),
                        'help'          => __('Enable this to display post meta information in a module.', 'uabb'),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'    => array(
                            'yes'    => array(
                                'fields' => array('post_meta_color', 'post_meta_font_size', 'post_meta_line_height')
                            )
                        ),
                    ),
                    'post_meta_color'        => array(
                        'type'       => 'color',
                        'label'      => __('Meta Text Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_meta',
                            'property'        => 'color',
                        )
                    ),
                     "post_meta_font_size" => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Meta Font Size', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_meta',
                            'property'        => 'font-size',
                            'unit'            => 'px'
                        )
                    ),
                    'post_meta_line_height'    => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Meta Line Height', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_meta',
                            'property'        => 'line-height',
                            'unit'            => 'px'
                        )
                    ),
                    'show_excerpt' => array(
                        'type'          => 'uabb-toggle-switch',
                        'label'         => __( 'Display Content', 'uabb' ),
                        'help'          => __('Enable this to display content of posts in a module.', 'uabb'),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'    => array(
                            'yes'    => array(
                                'fields' => array('post_excerpt_color', 'post_excerpt_font_size', 'post_excerpt_line_height')
                            )
                        ),
                    ),
                    'post_excerpt_color'        => array(
                        'type'       => 'color',
                        'label'      => __('Excerpt Text Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_excerpt',
                            'property'        => 'color',
                        )
                    ),
                     "post_excerpt_font_size" => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Excerpt Font Size', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_excerpt',
                            'property'        => 'font-size',
                            'unit'            => 'px'
                        )
                    ),
                    'post_excerpt_line_height'    => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Excerpt Line Height', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_excerpt',
                            'property'        => 'line-height',
                            'unit'            => 'px'
                        )
                    ),
                    'show_cta' => array(
                        'type'          => 'uabb-toggle-switch',
                        'label'         => __( 'Display CTA', 'uabb' ),
                        'help'          => __('Enable this to display call to action in a module.', 'uabb'),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'    => array(
                            'yes'    => array(
                                'fields' => array('post_permalink_color', 'post_permalink_font_size', 'post_permalink_line_height')
                            )
                        ),
                    ),
                    'post_permalink_color'        => array(
                        'type'       => 'color',
                        'label'      => __('Permalink Text Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_permalink',
                            'property'        => 'color',
                        )
                    ),
                     "post_permalink_font_size" => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Permalink Font Size', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_permalink',
                            'property'        => 'font-size',
                            'unit'            => 'px'
                        )
                    ),
                    'post_permalink_line_height'    => array(
                        'type'          => 'uabb-simplify',
                        'label'         => __( 'Permalink Line Height', 'uabb' ),
                        'default'       => array(
                            'desktop'       => '',
                            'medium'        => '',
                            'small'         => '',
                        ),
                        'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.Article-related_post_permalink',
                            'property'        => 'line-height',
                            'unit'            => 'px'
                        )
                    ),
                 )
             )
			),
		),
) );
