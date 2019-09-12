<?php

/**
 * @class PPFAQ
 */
class PPFAQ extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('FAQ', 'bb-powerpack'),
			'description'   	=> __('Display a collapsible FAQ of items.', 'bb-powerpack'),
			'group'         	=> pp_get_modules_group(),
            'category'			=> pp_get_modules_cat( 'content' ),
            'dir'           	=> BB_POWERPACK_DIR . 'modules/pp-faq/',
            'url'           	=> BB_POWERPACK_URL . 'modules/pp-faq/',
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
		global $wp_embed;
		$html = '<div itemprop="text">';
		$html .= wpautop( $wp_embed->autoembed( $settings->answer ) );
		$html .= '</div>';

		return $html;
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPFAQ', array(
	'items'         => array(
		'title'         => __('FAQ', 'bb-powerpack'),
		'sections'      => array(
			'faq_general'	=> array(
				'title'         => '',
				'fields'        => array(
					'items'         => array(
						'type'          => 'form',
						'label'         => __('FAQ', 'bb-powerpack'),
						'form'          => 'pp_faq_items_form', // ID from registered form below
						'preview_text'  => 'faq_question', // Name of a field to use for the preview text
						'multiple'      => true
					)
				)
			),
			'general'		=> array(
				'title'         => 'Settings',
				'fields'        => array(
					'expand_option'			=> array(
						'type'          		=> 'select',
						'label'         		=> __('Expand', 'bb-powerpack'),
						'default'       		=> 'first',
						'options'       		=> array(
							'first'            		=> __('First Item', 'bb-powerpack'),
							'custom'           		=> __('Custom Item', 'bb-powerpack'),
							'all'             		=> __('All', 'bb-powerpack'),
						),
						'toggle'				=> array(
							'first'				=> array(
								'fields'			=> array('collapse')
							),
							'custom'				=> array(
								'fields'			=> array('open_custom','collapse')
							)
						)
					),
					'open_custom'			=> array(
						'type'					=> 'text',
						'label'					=> __('Expand Custom', 'bb-powerpack'),
						'default'				=> '',
						'size'					=> 5,
						'help'					=> __('Add item number to expand by default.', 'bb-powerpack')
					),
					'collapse'				=> array(
						'type'          		=> 'pp-switch',
						'label'         		=> __('Collapse Inactive', 'bb-powerpack'),
						'default'       		=> '1',
						'options'       		=> array(
							'1'             		=> __('Yes', 'bb-powerpack'),
							'0'             		=> __('No', 'bb-powerpack')
						),
						'help'          		=> __('Choosing yes will keep only one item open at a time. Choosing no will allow multiple items to be open at the same time.', 'bb-powerpack'),
						'preview'       		=> array(
							'type'          		=> 'none'
						)
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
					'faq_id_prefix'			=> array(
						'type'					=> 'text',
						'label'					=> __('Custom ID Prefix', 'bb-powerpack'),
						'default'				=> '',
						'placeholder'			=> __('myfaq', 'bb-powerpack'),
						'help'					=> __('A prefix that will be applied to ID attribute of faq items in HTML. For example, prefix "myfaq" will be applied as "myfaq-1", "myfaq-2" in ID attribute of faq item 1 and faq item 2 respectively. It should only contain dashes, underscores, letters or numbers. No spaces.', 'bb-powerpack')
					),
				)
			),
		)
	),
	'icon_style'	=> array(
		'title'			=> __('Icon', 'bb-powerpack'),
		'sections'		=> array(
			'responsive_toggle_icons'	=> array(
				'title'						=> __('Toggle Icons', 'bb-powerpack'),
				'fields'					=> array(
					'faq_open_icon' 			=> array(
						'type'          			=> 'icon',
						'label'         			=> __('Open Icon', 'bb-powerpack'),
						'show_remove'   			=> true
					),
					'faq_close_icon' 			=> array(
						'type'          			=> 'icon',
						'label'         			=> __('Close Icon', 'bb-powerpack'),
						'show_remove'   			=> true
					),
					'faq_toggle_icon_size'   	=> array(
                        'type'						=> 'unit',
                        'label'						=> __('Size', 'bb-powerpack'),
						'units'						=> array('px'),
						'slider'					=> true,
						'responsive'				=> true,
                        'default'					=> '14',
                    ),
					'faq_toggle_icon_color'		=> array(
						'type'						=> 'color',
						'label'						=> __('Color', 'bb-powerpack'),
						'default'					=> '666666',
						'connections'				=> array('color'),
						'preview'					=> array(
							'type'						=> 'css',
							'selector'					=> '.pp-faq-item .pp-faq-button-icon',
							'property'					=> 'color'
						)
					),
					'faq_toggle_icon_color_hover'=> array(
						'type'						=> 'color',
						'label'						=> __('Hover Color', 'bb-powerpack'),
						'connections'				=> array('color'),
						'preview'					=> array(
							'type'						=> 'none'
						)
					),
				)
			)
		)
	),
	'style'			=> array(
		'title'			=> __('Style', 'bb-powerpack'),
		'sections'		=> array(
			'box_style'		=> array(
				'title'         => __('Box Style', 'bb-powerpack'),
				'fields'        => array(
					'item_spacing'			=> array(
						'type'          		=> 'unit',
						'label'         		=> __('Item Spacing', 'bb-powerpack'),
						'default'       		=> '10',
						'units'					=> array('px'),
						'slider'				=> true,
						'responsive'			=> true,
					),
					'box_border'				=> array(
						'type'						=> 'border',
						'label'         			=> __( 'Border', 'bb-powerpack' ),
						'responsive'				=> true,
					),
				),
			),
			'questions_style'	=> array(
				'title'         => __('Questions', 'bb-powerpack'),
				'fields'        => array(
					'qus_bg_color_default'	=> array(
						'type'						=> 'color',
						'label'						=> __('Background Color - Default', 'bb-powerpack'),
						'default'					=> 'dddddd',
						'show_reset'				=> true,
						'show_alpha'				=> true,
						'connections'				=> array('color'),
						'preview'					=> array(
							'type'						=> 'css',
							'selector'					=> '.pp-faq-item .pp-faq-button',
							'property'					=> 'background-color'
						)
					),
					'qus_bg_color_active'		=> array(
						'type'						=> 'color',
						'label'						=> __('Background Color - Active/Hover', 'bb-powerpack'),
						'default'					=> '',
						'show_reset'				=> true,
						'show_alpha'				=> true,
						'connections'				=> array('color'),
					),
					'qus_text_color_default'	=> array(
						'type'						=> 'color',
						'label'						=> __('Text Color - Default', 'bb-powerpack'),
						'default'					=> '666666',
						'show_reset'				=> true,
						'connections'				=> array('color'),
					),
					'qus_text_color_active'		=> array(
						'type'						=> 'color',
						'label'						=> __('Text Color - Active/Hover', 'bb-powerpack'),
						'default'					=> '777777',
						'connections'				=> array('color'),
						'show_reset'				=> true,
					),
					'qus_padding'				=> array(
						'type'						=> 'dimension',
						'label'						=> __('Padding', 'bb-powerpack'),
						'units'						=> array('px'),
						'default'					=> '10',
						'slider'					=> true,
						'responsive'				=> true,
						'preview'					=> array(
							'type'						=> 'css',
							'selector'					=> '.pp-faq-item .pp-faq-button',
							'property'					=> 'padding',
							'unit'						=> 'px'
						)
					),
				)
			),
			'answer_style'	=> array(
				'title'         => __('Answer', 'bb-powerpack'),
				'fields'        => array(
					'answer_bg_color'		=> array(
						'type'					=> 'color',
						'label'					=> __('Background Color', 'bb-powerpack'),
						'default'				=> 'eeeeee',
						'show_reset'			=> true,
						'show_alpha'			=> true,
						'connections'			=> array('color'),
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-content',
							'property'				=> 'background-color'
						)
					),
					'answer_text_color'		=> array(
						'type'          		=> 'color',
						'label'         		=> __('Text Color', 'bb-powerpack'),
						'default'       		=> '333333',
						'connections'			=> array('color'),
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-content',
							'property'				=> 'color'
						)
					),
					'answer_border'		=> array(
						'type'					=> 'border',
						'label'					=> __('Border', 'bb-powerpack'),
						'responsive'			=> true,
						'preview'       		=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-content',
							'important'				=> false,
						),
					),
					'answer_padding'		=> array(
						'type'					=> 'dimension',
						'label'					=> __('Padding', 'bb-powerpack'),
						'default'				=> '15',
						'units'					=> array('px'),
						'slider'				=> true,
						'responsive'			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-content',
							'property'				=> 'padding',
							'unit'					=> 'px'
						)
					),
				)
			)
		)
	),
	'typography'        => array(
		'title'				=> __('Typography', 'bb-powerpack'),
		'sections'			=> array(
			'qus_typography'		=> array(
				'title'					=> __('Questions', 'bb-powerpack'),
				'fields'				=> array(
					'qus_typography'		=> array(
						'type'					=> 'typography',
						'label'					=> __('Questions Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-button .pp-faq-button-label'
						)
					),
				)
			),
			'answer_typography'	=> array(
				'title'					=> __('Answer', 'bb-powerpack'),
				'fields'				=> array(
					'answer_typography'		=> array(
						'type'					=> 'typography',
						'label'					=> __('Answer Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-faq-item .pp-faq-content'
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
FLBuilder::register_settings_form('pp_faq_items_form', array(
	'title'	=> __('Add FAQ', 'bb-powerpack'),
	'tabs'	=> array(
		'general'      => array(
			'title'         => __('General', 'bb-powerpack'),
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'faq_question'	=> array(
							'type'          => 'text',
							'label'         => __('Question', 'bb-powerpack'),
							'default'		=> __('FAQ', 'bb-powerpack'),
							'connections'   => array( 'string', 'html', 'url' ),
						),
						'answer'       	=> array(
							'type'          => 'editor',
							'label'         => 'Answer',
							'connections'   => array( 'string', 'html', 'url' ),
						),
					)
				),
			)
		)
	)
));
