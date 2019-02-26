<?php

/**
 * @class PPAccordionModule
 */
class PPAccordionModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Advanced Accordion', 'bb-powerpack'),
			'description'   	=> __('Display a collapsible accordion of items.', 'bb-powerpack'),
			'group'         	=> pp_get_modules_group(),
            'category'			=> pp_get_modules_cat( 'content' ),
            'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-advanced-accordion/',
            'url'           	=> BB_POWERPACK_URL . 'modules/pp-advanced-accordion/',
            'editor_export' 	=> true, // Defaults to true and can be omitted.
            'enabled'       	=> true, // Defaults to true and can be omitted.
			'partial_refresh'	=> true,
		));

		$this->add_css(BB_POWERPACK()->fa_css);
	}

	/**
	 * Render content.
	 *
	 * @since 1.4
	 */
	public function render_content( $settings )
	{
		$html = '';

		switch ( $settings->content_type ) {
			case 'content':
				$html = '<div itemprop="text">';
				$html .= $settings->content;
				$html .= '</div>';
				break;
			case 'photo':
				$html = '<div itemprop="image">';
				$html .= '<img src="'.$settings->content_photo_src.'" alt="" style="max-width: 100%;" />';
				$html .= '</div>';
				break;
			case 'video':
                global $wp_embed;
                $html = $wp_embed->autoembed($settings->content_video);
            	break;
			case 'module':
				$html = '[fl_builder_insert_layout id="'.$settings->content_module.'"]';
				break;
			case 'row':
				$html = '[fl_builder_insert_layout id="'.$settings->content_row.'"]';
				break;
			case 'layout':
				$html = '[fl_builder_insert_layout id="'.$settings->content_layout.'"]';
				break;
			default:
				break;
		}

		return $html;
	}

	public function filter_settings( $settings, $helper )
	{
		// Handle old label background dual color field.
		$settings = PP_Module_Fields::handle_dual_color_field( $settings, 'label_background_color', array(
			'primary'					=> 'label_bg_color_default',
			'secondary'					=> 'label_bg_color_active',
			'opacity'					=> 'label_background_opacity'
		) );

		// Handle old label text dual color field.
		$settings = PP_Module_Fields::handle_dual_color_field( $settings, 'label_text_color', array(
			'primary'					=> 'label_text_color_default',
			'secondary'					=> 'label_text_color_active',
		) );

		// Handle old label padding field.
		$settings = PP_Module_Fields::handle_multitext_field( $settings, 'label_padding', 'padding', 'label_padding' );

		// Handle old label border field.
		$settings = PP_Module_Fields::handle_border_field( $settings, array(
			'label_border_style'	=> array(
				'type'					=> 'style'
			),
			'label_border_width'	=> array(
				'type'					=> 'width'
			),
			'label_border_color'	=> array(
				'type'					=> 'color'
			),
			'label_border_radius'	=> array(
				'type'					=> 'radius'
			)
		), 'label_border' );

		// Merge content bg opacity to content bg color.
		if ( isset( $settings->content_bg_opacity ) ) {
			$opacity = 1;
			if ( $settings->content_bg_opacity === '0' ) {
				$opacity = 0;
			} else {
				$opacity = ( $settings->content_bg_opacity / 100 );
			}
			$content_bg_color = $settings->content_bg_color;
			if ( ! empty( $content_bg_color ) ) {
				$settings->content_bg_color = pp_hex2rgba( $content_bg_color, $opacity );
			}

			unset( $settings->content_bg_opacity );
		}

		// Handle old content padding field.
		$settings = PP_Module_Fields::handle_multitext_field( $settings, 'content_padding', 'padding', 'content_padding' );

		// Handle old content border field.
		$settings = PP_Module_Fields::handle_border_field( $settings, array(
			'content_border_style'	=> array(
				'type'					=> 'style'
			),
			'content_border_width'	=> array(
				'type'					=> 'width'
			),
			'content_border_color'	=> array(
				'type'					=> 'color'
			),
			'content_border_radius'	=> array(
				'type'					=> 'radius'
			)
		), 'content_border' );

		// Handle old label typography fields.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'label_font'	=> array(
				'type'			=> 'font'
			),
			'label_custom_font_size'	=> array(
				'type'				=> 'font_size',
				'condition'			=> ( isset( $settings->label_font_size ) && 'custom' == $settings->label_font_size )
			),
			'label_line_height'	=> array(
				'type'				=> 'line_height'
			)
		), 'label_typography' );

		// Handle old content typography fields.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'content_font'	=> array(
				'type'			=> 'font'
			),
			'content_custom_font_size'	=> array(
				'type'				=> 'font_size',
				'condition'			=> ( isset( $settings->content_font_size ) && 'custom' == $settings->content_font_size )
			),
			'content_line_height'	=> array(
				'type'				=> 'line_height'
			),
			'content_alignment'		=> array(
				'type'					=> 'text_align'
			)
		), 'content_typography' );
		
		return $settings;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPAccordionModule', array(
	'items'         => array(
		'title'         => __('Items', 'bb-powerpack'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'items'         => array(
						'type'          => 'form',
						'label'         => __('Item', 'bb-powerpack'),
						'form'          => 'pp_accordion_items_form', // ID from registered form below
						'preview_text'  => 'label', // Name of a field to use for the preview text
						'multiple'      => true
					)
				)
			)
		)
	),
	'icon_style'	=> array(
		'title'	=> __('Icon', 'bb-powerpack'),
		'sections'	=> array(
			'accordion_icon_style'	=> array(
				'title'	=> '',
				'fields'	=> array(
					'accordion_icon_size'   => array(
                        'type'          => 'unit',
                        'label'         => __('Size', 'bb-powerpack'),
						'units'			=> array('px'),
						'slider'		=> true,
                        'default'       => '15',
                        'preview'       => array(
                            'type'      => 'css',
							'selector'  => '.pp-accordion-item .pp-accordion-icon, .pp-accordion-item .pp-accordion-icon:before',
							'property'  => 'font-size',
							'unit'      => 'px'
                        )
                    ),
				)
			),
			'responsive_toggle_icons'	=> array(
				'title'	=> __('Toggle Icons', 'bb-powerpack'),
				'fields'	=> array(
					'accordion_open_icon' => array(
						'type'          => 'icon',
						'label'         => __('Open Icon', 'bb-powerpack'),
						'show_remove'   => true
					),
					'accordion_close_icon' => array(
						'type'          => 'icon',
						'label'         => __('Close Icon', 'bb-powerpack'),
						'show_remove'   => true
					),
					'accordion_toggle_icon_size'   => array(
                        'type'          => 'unit',
                        'label'         => __('Size', 'bb-powerpack'),
						'units'			=> array('px'),
						'slider'		=> true,
                        'default'       => '14',
                        'preview'       => array(
                            'type'      => 'css',
							'selector'  => '.pp-accordion-item .pp-accordion-button-icon, .pp-accordion-item .pp-accordion-button-icon:before',
							'property'  => 'font-size',
							'unit'      => 'px'
                        )
                    ),
					'accordion_toggle_icon_color'  => array(
						'type'          => 'color',
						'label'         => __('Color', 'bb-powerpack'),
						'default'       => '666666',
						'preview'	=> array(
							'type'	=> 'css',
							'selector'	=> '.pp-accordion-item .pp-accordion-button-icon',
							'property'	=> 'color'
						)
					),
				)
			)
		)
	),
	'style'        => array(
		'title'         => __('Style', 'bb-powerpack'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'item_spacing'     => array(
						'type'          => 'unit',
						'label'         => __('Item Spacing', 'bb-powerpack'),
						'default'       => '10',
						'units'			=> array('px'),
						'slider'		=> true,
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-accordion-item',
							'property'      => 'margin-bottom',
							'unit'			=> 'px'
						)
					),
					'collapse'   => array(
						'type'          => 'pp-switch',
						'label'         => __('Collapse Inactive', 'bb-powerpack'),
						'default'       => '1',
						'options'       => array(
							'1'             => __('Yes', 'bb-powerpack'),
							'0'             => __('No', 'bb-powerpack')
						),
						'help'          => __('Choosing yes will keep only one item open at a time. Choosing no will allow multiple items to be open at the same time.', 'bb-powerpack'),
						'preview'       => array(
							'type'          => 'none'
						)
					),
					'open_first'       => array(
						'type'          => 'pp-switch',
						'label'         => __('Expand First Item', 'bb-powerpack'),
						'default'       => '0',
						'options'       => array(
							'1'             => __('Yes', 'bb-powerpack'),
							'0'             => __('No', 'bb-powerpack'),
						),
						'help' 			=> __('Choosing yes will expand the first item by default.', 'bb-powerpack'),
						'toggle'		=> array(
							'0'				=> array(
								'fields'		=> array('open_custom')
							)
						)
					),
					'open_custom'	=> array(
						'type'			=> 'text',
						'label'			=> __('Expand Custom', 'bb-powerpack'),
						'default'		=> '',
						'size'			=> 5,
						'help'			=> __('Add item number to expand by default.', 'bb-powerpack')
					),
					'responsive_collapse'	=> array(
						'type'					=> 'pp-switch',
						'label'					=> __('Responsive Collapse All', 'bb-powerpack'),
						'default'				=> 'no',
						'options'				=> array(
							'yes'					=> __('Yes', 'bb-powerpack'),
							'no'					=> __('No', 'bb-powerpack'),
						),
						'help'					=> __('Items will not appear as expanded on responsive devices until user clicks on it.', 'bb-powerpack')
					),
					'accordion_id_prefix'	=> array(
						'type'			=> 'text',
						'label'			=> __('Custom ID Prefix', 'bb-powerpack'),
						'default'		=> '',
						'placeholder'	=> __('myaccordion', 'bb-powerpack'),
						'help'			=> __('A prefix that will be applied to ID attribute of accordion items in HTML. For example, prefix "myaccordion" will be applied as "myaccordion-1", "myaccordion-2" in ID attribute of accordion item 1 and accordion item 2 respectively. It should only contain dashes, underscores, letters or numbers. No spaces.', 'bb-powerpack')
					),
				)
			),
			'label_style'       => array(
				'title'         => __('Label', 'bb-powerpack'),
				'fields'        => array(
					'label_bg_color_default'	=> array(
						'type'			=> 'color',
						'label'			=> __('Background Color - Default', 'bb-powerpack'),
						'default'		=> 'dddddd',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-accordion-item .pp-accordion-button',
							'property'		=> 'background-color'
						)
					),
					'label_bg_color_active'	=> array(
						'type'			=> 'color',
						'label'			=> __('Background Color - Active', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'show_alpha'	=> true,
					),
					'label_text_color_default'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Color - Default', 'bb-powerpack'),
						'default'		=> '666666',
						'show_reset'	=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-accordion-item .pp-accordion-button',
							'property'		=> 'color'
						)
					),
					'label_text_color_active'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Color - Active', 'bb-powerpack'),
						'default'		=> '777777',
						'show_reset'	=> true
					),
					'label_border'		=> array(
						'type'				=> 'border',
						'label'         	=> __( 'Border', 'bb-powerpack' ),
						'responsive'		=> true,
						'preview'       	=> array(
							'type'          	=> 'css',
							'selector'			=> '.pp-accordion-item .pp-accordion-button',
							'important'			=> false,
						),
					),
					'label_padding'	=> array(
						'type'			=> 'dimension',
						'label'			=> __('Padding', 'bb-powerpack'),
						'units'			=> array('px'),
						'default'		=> '10',
						'slider'		=> true,
						'responsive'	=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-accordion-item .pp-accordion-button',
							'property'		=> 'padding',
							'unit'			=> 'px'
						)
					),
				)
			),
			'content_style'       => array(
				'title'         => __('Content', 'bb-powerpack'),
				'fields'        => array(
					'content_bg_color'  => array(
						'type'          => 'color',
						'label'         => __('Background Color', 'bb-powerpack'),
						'default'       => 'eeeeee',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'preview'	=> array(
							'type'	=> 'css',
							'selector'	=> '.pp-accordion-item .pp-accordion-content',
							'property'	=> 'background-color'
						)
					),
					'content_text_color'  => array(
						'type'          => 'color',
						'label'         => __('Text Color', 'bb-powerpack'),
						'default'       => '333333',
						'preview'	=> array(
							'type'	=> 'css',
							'selector'	=> '.pp-accordion-item .pp-accordion-content',
							'property'	=> 'color'
						)
					),
					'content_border'	=> array(
						'type'				=> 'border',
						'label'				=> __('Border', 'bb-powerpack'),
						'responsive'		=> true,
						'preview'       	=> array(
							'type'          	=> 'css',
							'selector'			=> '.pp-accordion-item .pp-accordion-content',
							'important'			=> false,
						),
					),
					'content_padding'	=> array(
						'type'				=> 'dimension',
						'label'				=> __('Padding', 'bb-powerpack'),
						'default'			=> '15',
						'units'				=> array('px'),
						'slider'			=> true,
						'responsive'		=> true,

					),
				)
			)
		)
	),
	'typography'        => array(
		'title'         => __('Typography', 'bb-powerpack'),
		'sections'      => array(
			'label_typography'	=> array(
				'title'				=> __('Label', 'bb-powerpack'),
				'fields'			=> array(
					'label_typography'	=> array(
						'type'				=> 'typography',
						'label'				=> __('Label Typography', 'bb-powerpack'),
						'responsive'  		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-accordion-item .pp-accordion-button .pp-accordion-button-label'
						)
					),
				)
			),
			'content_typography'	=> array(
				'title'	=> __('Content', 'bb-powerpack'),
				'fields'	=> array(
					'content_typography'	=> array(
						'type'					=> 'typography',
						'label'					=> __('Content Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-accordion-item .pp-accordion-content'
						)
					),
				)
			),
		)
	),
));

/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('pp_accordion_items_form', array(
	'title' => __('Add Item', 'bb-powerpack'),
	'tabs'  => array(
		'general'      => array(
			'title'         => __('General', 'bb-powerpack'),
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'accordion_font_icon' => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'bb-powerpack'),
							'show_remove'   => true
						),
						'label'         => array(
							'type'          => 'text',
							'label'         => __('Label', 'bb-powerpack'),
							'connections'   => array( 'string', 'html', 'url' ),
						)
					)
				),
				'content'       => array(
					'title'         => __('Content', 'bb-powerpack'),
					'fields'        => array(
						'content_type'	=> array(
							'type'			=> 'select',
							'label'			=> __('Type', 'bb-powerpack'),
							'default'		=> 'content',
							'options'		=> array(
								'content'		=> __('Content', 'bb-powerpack'),
								'photo'			=> __('Photo', 'bb-powerpack'),
								'video'			=> __('Video', 'bb-powerpack'),
								'module'		=> __('Saved Module', 'bb-powerpack'),
								'row'			=> __('Saved Row', 'bb-powerpack'),
								'layout'		=> __('Saved Layout', 'bb-powerpack'),
							),
							'toggle'		=> array(
								'content'		=> array(
									'fields'		=> array('content')
								),
								'photo'		=> array(
									'fields'	=> array('content_photo')
								),
								'video'		=> array(
									'fields'	=> array('content_video')
								),
								'module'	=> array(
									'fields'	=> array('content_module')
								),
								'row'		=> array(
									'fields'	=> array('content_row')
								),
								'layout'	=> array(
									'fields'	=> array('content_layout')
								)
							)
						),
						'content'       => array(
							'type'          => 'editor',
							'label'         => '',
							'connections'   => array( 'string', 'html', 'url' ),
						),
						'content_photo'	=> array(
							'type'			=> 'photo',
							'label'			=> __('Photo', 'bb-powerpack'),
							'connections'   => array( 'photo' ),
						),
						'content_video'     => array(
	                        'type'              => 'textarea',
	                        'label'             => __('Embed Code / URL', 'bb-powerpack'),
	                        'rows'              => 6,
							'connections'   	=> array( 'string', 'html', 'url' ),
	                    ),
						'content_module'	=> array(
							'type'				=> 'select',
							'label'				=> __('Saved Module', 'bb-powerpack'),
							'options'			=> array()
						),
						'content_row'		=> array(
							'type'				=> 'select',
							'label'				=> __('Saved Row', 'bb-powerpack'),
							'options'			=> array()
						),
						'content_layout'	=> array(
							'type'				=> 'select',
							'label'				=> __('Saved Layout', 'bb-powerpack'),
							'options'			=> array()
						),
					)
				)
			)
		)
	)
));
