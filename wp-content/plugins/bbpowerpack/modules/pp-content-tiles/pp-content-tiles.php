<?php

/**
 * @class PPContentTilesModule
 */
class PPContentTilesModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Content Tiles', 'bb-powerpack'),
			'description'   	=> __('Display posts in various tile layouts.', 'bb-powerpack'),
			'group'         	=> pp_get_modules_group(),
            'category'			=> pp_get_modules_cat( 'content' ),
            'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-content-tiles/',
            'url'           	=> BB_POWERPACK_URL . 'modules/pp-content-tiles/',
			'editor_export' 	=> false,
			'partial_refresh'	=> true,
			'icon'				=> 'layout.svg',
		));

		add_action( 'wp_ajax_ct_get_post_tax', array( $this, 'get_post_taxonomies' ) );
		add_action( 'wp_ajax_nopriv_ct_get_post_tax', array( $this, 'get_post_taxonomies' ) );
	}

	/**
     * Get taxonomies
     */
    public function get_post_taxonomies()
	{
		$slug = isset( $_POST['post_type_slug'] ) ? $_POST['post_type_slug'] : '';
		$taxonomies = FLBuilderLoop::taxonomies($slug);
		$html = '';
		$html .= '<option value="none">'. __('None', 'bb-powerpack') .'</option>';

		foreach ( $taxonomies as $tax_slug => $tax ) {
			$html .= '<option value="'.$tax_slug.'">'.$tax->label.'</option>';
		}

        echo $html; die();
    }

	/**
	 * Renders the schema structured data for the current
	 * post in the loop.
	 *
	 * @return void
	 */
	static public function schema_meta()
	{
		BB_PowerPack_Post_Helper::schema_meta();
	}

	/**
	 * Renders the schema itemtype for the current
	 * post in the loop.
	 *
	 * @return void
	 */
	static public function schema_itemtype()
	{
		BB_PowerPack_Post_Helper::schema_itemtype();
	}

	static public function get_post_class( $count, $layout )
	{
		if ( $count == 2 && $layout == 1 ) {
			return ' pp-post-tile-medium';
		}
		if ( $count > 1 && $layout == 3 ) {
			return ' pp-post-tile-medium';
		}
		if ( $count > 2 && $layout != 3 ) {
			return ' pp-post-tile-small';
		}
		if ( $count > 1 && $layout == 2 ) {
			return ' pp-post-tile-small';
		}
		if ( $count > 1 && $layout == 4 ) {
			return ' pp-post-tile-small';
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPContentTilesModule', array(
	'layout'	=> array(
		'title'		=> __('Layout', 'bb-powerpack'),
		'sections'	=> array(
			'layout'	=> array(
				'title'		=> '',
				'fields'	=> array(
					'layout'	=> array(
						'type'		=> 'layout',
						'default'	=> 1,
						'options'	=> array(
							1			=> BB_POWERPACK_URL . 'modules/pp-content-tiles/images/layout-1.jpg',
							2			=> BB_POWERPACK_URL . 'modules/pp-content-tiles/images/layout-2.jpg',
							3			=> BB_POWERPACK_URL . 'modules/pp-content-tiles/images/layout-3.jpg',
							4			=> BB_POWERPACK_URL . 'modules/pp-content-tiles/images/layout-4.jpg',
						),
						'toggle'	=> array(
							1			=> array(
								'sections'	=> array('small_grid')
							),
							2			=> array(
								'sections'	=> array('small_grid')
							),
							4			=> array(
								'sections'	=> array('small_grid')
							)
						)
					),
				)
			),
			'other_posts'	=> array(
				'title'			=> __('Other Posts', 'bb-powerpack'),
				'fields'		=> array(
					'show_other_posts'	=> array(
						'type'				=> 'pp-switch',
						'label'				=> __('Show Other Posts', 'bb-powerpack'),
						'default'			=> 'no',
						'options'			=> array(
							'yes'				=> __('Yes', 'bb-powerpack'),
							'no'				=> __('No', 'bb-powerpack'),
						),
						'toggle'			=> array(
							'yes'				=> array(
								'fields'			=> array( 'number_of_posts', 'column_width' )
							)
						)
					),
					'number_of_posts'	=> array(
						'type'				=> 'text',
						'label'				=> __('Number of Other Posts', 'bb-powerpack'),
						'default'			=> 4,
						'size'				=> 5,
						'description'		=> __('Leave blank to show all posts.', 'bb-powerpack')
					),
					'column_width'	=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Column Width', 'bb-powerpack'),
						'default'		=> '25',
						'options'		=> array(
							'25'			=> '25%',
							'50'			=> '50%'
						)
					)
				)
			)
		)
	),
	'content'   => array(
		'title'         => __('Content', 'bb-powerpack'),
		'file'          => FL_BUILDER_DIR . 'includes/loop-settings.php',
	),
	'settings'   => array(
		'title'         => __('Settings', 'bb-powerpack'),
		'file'          => BB_POWERPACK_DIR . 'modules/pp-content-tiles/includes/settings-tab.php',
	),
	'style'         => array( // Tab
		'title'         => __('Style', 'bb-powerpack'), // Tab title
		'sections'      => array( // Tab Sections
			'structure'		=> array(
				'title'         => __('Structure', 'bb-powerpack'),
				'fields'        => array(
					'post_height'    => array(
						'type'          => 'text',
						'label'         => __('Height', 'bb-powerpack'),
						'default'       => '470',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
					'post_spacing'  => array(
						'type'          => 'text',
						'label'         => __('Spacing', 'bb-powerpack'),
						'default'       => '3',
						'maxlength'     => '3',
						'size'          => '4',
						'description'   => 'px'
					),
				)
			),
			'text_style'    => array(
				'title'         => __('Colors', 'bb-powerpack'),
				'fields'        => array(
					'text_color'    => array(
						'type'          => 'color',
						'label'         => __('Text Color', 'bb-powerpack'),
						'default'       => 'ffffff',
						'show_reset'    => true
					),
					'tax_bg_color'  => array(
						'type'          => 'color',
						'label'         => __('Taxonomy Background Color', 'bb-powerpack'),
						'default'       => '333333',
						'show_reset'    => true
					),
					'tax_bg_color_h'  	=> array(
						'type'          	=> 'color',
						'label'         	=> __('Taxonomy Hover Background Color', 'bb-powerpack'),
						'default'       	=> '6b6b6b',
						'show_reset'    	=> true
					),
				)
			),
		)
	),
	'typography'	=> array(
		'title'			=> __('Typography', 'bb-powerpack'),
		'sections'		=> array(
			'title_typography'	=> array(
				'title'			=> __('Title', 'bb-powerpack'),
				'fields'		=> array(
					'title_font'	=> array(
						'type'			=> 'font',
						'label'			=> __('Font', 'bb-powerpack'),
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> '400'
						),
					),
					'title_font_size'	=> array(
						'type'				=> 'pp-switch',
						'label'				=> __('Font Size', 'bb-powerpack'),
						'default'			=> 'default',
						'options'			=> array(
							'default'			=> __('Default', 'bb-powerpack'),
							'custom'			=> __('Custom', 'bb-powerpack')
						),
						'toggle'			=> array(
							'custom'			=> array(
								'fields'			=> array('title_custom_font_size')
							)
						)
					),
					'title_custom_font_size'	=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Font Size', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 30,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-post:not(.pp-post-tile-small) .pp-post-tile-title',
									'property'        			=> 'font-size',
									'unit'            			=> 'px'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),

						),
					),
					'title_line_height'	=> array(
						'type'				=> 'pp-switch',
						'label'				=> __('Line Height', 'bb-powerpack'),
						'default'			=> 'default',
						'options'			=> array(
							'default'			=> __('Default', 'bb-powerpack'),
							'custom'			=> __('Custom', 'bb-powerpack')
						),
						'toggle'			=> array(
							'custom'			=> array(
								'fields'			=> array('title_custom_line_height')
							)
						)
					),
					'title_custom_line_height'	=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Line Height', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 1.4,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-post:not(.pp-post-tile-small) .pp-post-tile-title',
									'property'        			=> 'line-height',
									'unit'            			=> 'em'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),

						),
					),
					'title_letter_spacing'	=> array(
						'type'					=> 'pp-switch',
						'label'					=> __('Letter Spacing', 'bb-powerpack'),
						'default'				=> 'default',
						'options'				=> array(
							'default'				=> __('Default', 'bb-powerpack'),
							'custom'				=> __('Custom', 'bb-powerpack')
						),
						'toggle'				=> array(
							'custom'				=> array(
								'fields'				=> array('title_custom_letter_spacing')
							)
						)
					),
					'title_custom_letter_spacing'	=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Letter Spacing', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 0,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-title',
									'property'        			=> 'letter-spacing',
									'unit'            			=> 'px'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),

						),
					),
					'title_text_transform'	=> array(
						'type'					=> 'select',
						'label'					=> __('Text Transform', 'bb-powerpack'),
						'default'				=> 'none',
						'options'				=> array(
							'none'					=> __('None', 'bb-powerpack'),
							'capitalize'			=> __('Capitalize', 'bb-powerpack'),
							'lowercase'				=> __('lowercase', 'bb-powerpack'),
							'uppercase'				=> __('UPPERCASE', 'bb-powerpack'),
						)
					),
					'title_margin'	=> array(
						'type'			=> 'pp-multitext',
						'label'			=> __('Margin', 'bb-powerpack'),
						'default'		=> array(
							'top'			=> '0',
							'bottom'		=> '0',
						),
						'options'	=> array(
							'top'		=> array(
								'icon'			=> 'fa-long-arrow-up',
								'placeholder'	=> __('Top', 'bb-powerpack'),
								'tooltip'		=> __('Top', 'bb-powerpack'),
								'preview'		=> array(
									'selector'		=> '.pp-post-tile-title',
									'property'		=> 'margin-top',
									'unit'			=> 'px'
								)
							),
							'bottom'		=> array(
								'icon'			=> 'fa-long-arrow-down',
								'placeholder'	=> __('Bottom', 'bb-powerpack'),
								'tooltip'		=> __('Bottom', 'bb-powerpack'),
								'preview'		=> array(
									'selector'		=> '.pp-post-tile-title',
									'property'		=> 'margin-bottom',
									'unit'			=> 'px'
								)
							)
						)
					)
				)
			),
			'small_grid'	=> array(
				'title'			=> __('Title - Small Grid', 'bb-powerpack'),
				'fields'		=> array(
					'title_font_size_s'	=> array(
						'type'				=> 'pp-switch',
						'label'				=> __('Font Size', 'bb-powerpack'),
						'default'			=> 'default',
						'options'			=> array(
							'default'			=> __('Default', 'bb-powerpack'),
							'custom'			=> __('Custom', 'bb-powerpack')
						),
						'toggle'			=> array(
							'custom'			=> array(
								'fields'			=> array('title_custom_font_size_s')
							)
						)
					),
					'title_custom_font_size_s'	=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Font Size', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 18,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-small .pp-post-tile-title',
									'property'        			=> 'font-size',
									'unit'            			=> 'px'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),

						),
					),
					'title_line_height_s'=> array(
						'type'				=> 'pp-switch',
						'label'				=> __('Line Height', 'bb-powerpack'),
						'default'			=> 'default',
						'options'			=> array(
							'default'			=> __('Default', 'bb-powerpack'),
							'custom'			=> __('Custom', 'bb-powerpack')
						),
						'toggle'			=> array(
							'custom'			=> array(
								'fields'			=> array('title_custom_line_height_s')
							)
						)
					),
					'title_custom_line_height_s'=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Line Height', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 1.4,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-small .pp-post-tile-title',
									'property'        			=> 'line-height',
									'unit'            			=> 'em'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),

						),
					),
				)
			),
			'meta_typography'	=> array(
			    'title'				=> __('Meta', 'bb-powerpack'),
			    'fields'			=> array(
			        'meta_font'		=> array(
			            'type'			=> 'font',
			            'label'			=> __('Font', 'bb-powerpack'),
			            'default'		=> array(
			                'family'		=> 'Default',
			                'weight'		=> '400'
			            )
			        ),
			        'meta_font_size'	=> array(
			            'type'				=> 'pp-switch',
			            'label'				=> __('Font Size', 'bb-powerpack'),
			            'default'			=> 'custom',
			            'options'			=> array(
			                'default'			=> __('Default', 'bb-powerpack'),
			                'custom'			=> __('Custom', 'bb-powerpack')
			            ),
			            'toggle'			=> array(
			                'custom'			=> array(
			                    'fields'			=> array('meta_custom_font_size')
			                )
			            )
			        ),
					'meta_custom_font_size'		=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Font Size', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 12,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-meta, .pp-post-tile-category',
									'property'        			=> 'font-size',
									'unit'            			=> 'px'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),
						),
					),
			        'meta_letter_spacing'	=> array(
			            'type'					=> 'pp-switch',
			            'label'					=> __('Letter Spacing', 'bb-powerpack'),
			            'default'				=> 'default',
			            'options'				=> array(
			                'default'				=> __('Default', 'bb-powerpack'),
			                'custom'				=> __('Custom', 'bb-powerpack')
			            ),
			            'toggle'				=> array(
			                'custom'				=> array(
			                    'fields'				=> array('meta_custom_letter_spacing')
			                )
			            )
			        ),
					'meta_custom_letter_spacing'=> array(
						'type' 						=> 'pp-multitext',
						'label'						=> __('Custom Letter Spacing', 'bb-powerpack'),
						'default'					=> array(
							'desktop'					=> 0,
							'tablet'					=> '',
							'mobile'					=> '',
						),
						'options' 					=> array(
							'desktop' 					=> array(
								'icon'						=> 'fa-desktop',
								'placeholder'				=> __('Desktop', 'bb-powerpack'),
								'tooltip'					=> __('Desktop', 'bb-powerpack'),
								'preview'       			=> array(
									'selector'        			=> '.pp-post-tile-meta, .pp-post-tile-category',
									'property'        			=> 'letter-spacing',
									'unit'            			=> 'px'
		                        ),
							),
							'tablet' 					=> array(
								'icon'						=> 'fa-tablet',
								'placeholder'				=> __('Tablet', 'bb-powerpack'),
								'tooltip'					=> __('Tablet', 'bb-powerpack'),
							),
							'mobile' 					=> array(
								'icon'						=> 'fa-mobile',
								'placeholder'				=> __('Mobile', 'bb-powerpack'),
								'tooltip'					=> __('Mobile', 'bb-powerpack'),
							),
						),
					),
			        'meta_text_transform'	=> array(
			            'type'					=> 'select',
			            'label'					=> __('Text Transform', 'bb-powerpack'),
			            'default'				=> 'none',
			            'options'				=> array(
			                'none'					=> __('None', 'bb-powerpack'),
			                'capitalize'			=> __('Capitalize', 'bb-powerpack'),
			                'lowercase'				=> __('lowercase', 'bb-powerpack'),
			                'uppercase'				=> __('UPPERCASE', 'bb-powerpack'),
			            )
			        ),
					'meta_margin'	=> array(
						'type'			=> 'pp-multitext',
						'label'			=> __('Margin', 'bb-powerpack'),
						'default'		=> array(
							'top'			=> 10,
							'bottom'		=> 20,
						),
						'options'	=> array(
							'top'		=> array(
								'icon'			=> 'fa-long-arrow-up',
								'placeholder'	=> __('Top', 'bb-powerpack'),
								'tooltip'		=> __('Top', 'bb-powerpack'),
								'preview'		=> array(
									'selector'		=> '.pp-post-tile-meta, .pp-post-tile-category',
									'property'		=> 'margin-top',
									'unit'			=> 'px'
								)
							),
							'bottom'		=> array(
								'icon'			=> 'fa-long-arrow-down',
								'placeholder'	=> __('Bottom', 'bb-powerpack'),
								'tooltip'		=> __('Bottom', 'bb-powerpack'),
								'preview'		=> array(
									'selector'		=> '.pp-post-tile-meta',
									'property'		=> 'margin-bottom',
									'unit'			=> 'px'
								)
							)
						)
					)
			    )
			)
		)
	)
));
