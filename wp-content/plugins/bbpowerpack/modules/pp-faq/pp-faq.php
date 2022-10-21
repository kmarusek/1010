<?php
/**
 * @class PPFAQModule
 */
class PPFAQModule extends FLBuilderModule {

	private $_schema_rendered = false;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'FAQ', 'bb-powerpack' ),
				'description'     => __( 'Display a collapsible FAQ of items.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-faq/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-faq/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);
	}

	public function enqueue_icon_styles() {
		$enqueue = false;
		$settings = $this->settings;

		if ( ! empty( $settings->faq_open_icon ) ) {
			$enqueue = true;
		}
		if ( ! empty( $settings->faq_close_icon ) ) {
			$enqueue = true;
		}

		if ( $enqueue && is_callable( 'parent::enqueue_icon_styles' ) ) {
			parent::enqueue_icon_styles();
		}
	}

	public function filter_settings( $settings, $helper ) {
		if ( isset( $settings->collapse ) ) {
			if ( '0' === $settings->collapse ) {
				$settings->collapse = 'no';
			}
			if ( '1' === $settings->collapse ) {
				$settings->collapse = 'yes';
			}
		}

		if ( isset( $settings->faq_source ) ) {
			$settings->data_source = $settings->faq_source;
			unset( $settings->faq_source );
		}
		if ( isset( $settings->post_slug ) ) {
			$settings->post_type = $settings->post_slug;
			unset( $settings->post_slug );
		}
		if ( isset( $settings->post_count ) ) {
			$settings->posts_per_page = $settings->post_count;
			unset( $settings->post_count );
		}
		if ( isset( $settings->post_order ) ) {
			$settings->order = $settings->post_order;
			unset( $settings->post_order );
		}

		if ( isset( $settings->acf_repeater_question ) ) {
			$settings->acf_repeater_label = $settings->acf_repeater_question;
			unset( $settings->acf_repeater_question );
		}
		if ( isset( $settings->acf_repeater_answer ) ) {
			$settings->acf_repeater_content = $settings->acf_repeater_answer;
			unset( $settings->acf_repeater_answer );
		}

		if ( isset( $settings->acf_options_page_repeater_question ) ) {
			$settings->acf_options_page_repeater_label = $settings->acf_options_page_repeater_question;
			unset( $settings->acf_options_page_repeater_question );
		}
		if ( isset( $settings->acf_options_page_repeater_answer ) ) {
			$settings->acf_options_page_repeater_content = $settings->acf_options_page_repeater_answer;
			unset( $settings->acf_options_page_repeater_answer );
		}		

		return $settings;
	}

	public function get_data_source() {
		if ( ! isset( $this->settings->data_source ) || empty( $this->settings->data_source ) ) {
			return 'manual';
		}

		return $this->settings->data_source;
	}

	public function get_acf_data( $post_id = false ) {
		if ( ! isset( $this->settings->acf_repeater_name ) || empty( $this->settings->acf_repeater_name ) ) {
			return;
		}

		$data    = array();
		$post_id = apply_filters( 'pp_faq_acf_post_id', $post_id );

		$repeater_name = $this->settings->acf_repeater_name;
		$question_name = $this->settings->acf_repeater_label;
		$answer_name   = $this->settings->acf_repeater_content;

		$repeater_rows = get_field( $repeater_name, $post_id );

		if ( ! $repeater_rows ) {
			return;
		}

		foreach ( $repeater_rows as $row ) {
			$item               = new stdClass;
			$item->faq_question = isset( $row[ $question_name ] ) ? $row[ $question_name ] : '';
			$item->answer       = isset( $row[ $answer_name ] ) ? $row[ $answer_name ] : '';

			$data[] = $item;
		}

		return $data;
	}

	public function get_acf_relationship_data() {
		if ( ! isset( $this->settings->acf_relational_key ) || empty( $this->settings->acf_relational_key ) ) {
			return;
		}

		$data = array();
		$settings = new stdClass;

		$settings->data_source = 'acf_relationship';
		$settings->data_source_acf_relational_key = $this->settings->acf_relational_key;
		$settings->data_source_acf_relational_type = $this->settings->acf_relational_type;
		$settings->data_source_acf_order_by = $this->settings->acf_order_by;
		$settings->data_source_acf_order = $this->settings->acf_order;
		$settings->data_source_acf_order_by_meta_key = $this->settings->acf_order_by_meta_key;
		$settings->posts_per_page = '-1';
		$settings->id = $this->settings->id;
		$settings->class = $this->settings->class;

		$settings = apply_filters( 'pp_faq_acf_relationship_data_settings', $settings, $this->settings );

		$query = FLBuilderLoop::query( $settings );

		if ( $query->have_posts() ) {
			$posts = $query->get_posts();
			foreach ( $posts as $post ) {
				$item               = new stdClass;
				$item->faq_question = isset( $post->post_title ) ? $post->post_title : '';
				$item->answer       = isset( $post->post_content ) ? $post->post_content : '';
	
				$data[] = $item;
			}
		}

		return $data;
	}

	public function get_acf_options_page_data( $post_id = false ) {
		if ( ! isset( $this->settings->acf_options_page_repeater_name ) || empty( $this->settings->acf_options_page_repeater_name ) ) {
			return;
		}

		$data    = array();
		$post_id = apply_filters( 'pp_faq_acf_options_page_post_id', $post_id );

		$repeater_name = $this->settings->acf_options_page_repeater_name;
		$question_name = $this->settings->acf_options_page_repeater_label;
		$answer_name   = $this->settings->acf_options_page_repeater_content;

		$repeater_rows = get_field( $repeater_name, 'option' );
		if ( ! $repeater_rows ) {
			return;
		}

		foreach ( $repeater_rows as $row ) {
			$item               = new stdClass;
			$item->faq_question = isset( $row[ $question_name ] ) ? $row[ $question_name ] : '';
			$item->answer       = isset( $row[ $answer_name ] ) ? $row[ $answer_name ] : '';

			$data[] = $item;
		}
		return $data;
	}

	public function get_cpt_data() {
		$data = array();

		if ( ! isset( $this->settings->post_type ) || empty( $this->settings->post_type ) ) {
			return $data;
		}

		$settings = $this->settings;

		$settings->post_type  = ! empty( $this->settings->post_type ) ? $this->settings->post_type : 'post';
		$settings->posts_per_page = ! empty( $this->settings->posts_per_page ) || '-1' !== $this->settings->posts_per_page ? $this->settings->posts_per_page : '-1';
		$settings->order = ! empty( $this->settings->order ) ? $this->settings->order : 'DESC';

		$query = FLBuilderLoop::query( $settings );
		$posts = $query->get_posts();

		foreach ( $posts as $post ) {
			$item               = new stdClass;
			$item->faq_question = isset( $post->post_title ) ? $post->post_title : '';
			$item->answer       = pp_get_post_content( $post );

			$data[] = $item;
		}

		return $data;
	}

	public function get_faq_items() {
		$items = array();
		$source = $this->get_data_source();

		if ( 'manual' === $source ) {
			$items = $this->settings->items;
		} elseif ( 'acf' === $source ) {
			$items = $this->get_acf_data();
		} elseif ( 'acf_relationship' === $source ) {
			$items = $this->get_acf_relationship_data();
		} elseif ( 'acf_options_page' === $source ) {
			$items = $this->get_acf_options_page_data();
		} elseif ( 'post' === $source || 'pods_relationship' === $source ) {
			$items = $this->get_cpt_data();
		}

		return apply_filters( 'pp_faq_items', $items, $this->settings );
	}

	/**
	 * Render content.
	 */
	public function render_content( $item, $embed = true ) {

		if ( ! $embed ) {
			$text = ! empty( $item->answer ) ? json_encode( $item->answer ) : '';
			return $text;
		}

		global $wp_embed;

		$html  = '';
		$html .= wpautop( $wp_embed->autoembed( $item->answer ) );

		return $html;
	}

	/**
	 * Render schema markup.
	 */
	public function render_schema( $return = false ) {
		global $pp_faq_schema_items;

		$settings      = $this->settings;
		$enable_schema = true;

		if ( isset( $settings->enable_schema ) && 'no' === $settings->enable_schema ) {
			$enable_schema = false;
		}

		if ( ! $enable_schema ) {
			return;
		}

		if ( $this->_schema_rendered ) {
			return;
		}

		// @codingStandardsIgnoreStart.
		$schema_data = array(
			"@context" => "https://schema.org",
			"@type" => "FAQPage",
			"mainEntity" => array(),
		);

		$items = $this->get_faq_items();

		if ( empty( $items ) ) {
			return;
		}

		for ( $i = 0; $i < count( $items ); $i++ ) {
			if ( ! is_object( $items[ $i ] ) ) {
				continue;
			}

			$item = (object) array(
				"@type" => "Question",
				"name" => $items[ $i ]->faq_question,
				"acceptedAnswer" => (object) array(
					"@type" => "Answer",
					"text" => $items[ $i ]->answer,
				),
			);

			$schema_data['mainEntity'][] = $item;
		}
		// @codingStandardsIgnoreEnd.

		$schema_data = apply_filters( 'pp_faq_schema_markup', $schema_data, $settings );

		$pp_faq_schema_items[] = $schema_data['mainEntity'];

		if ( $return ) {
			return $schema_data;
		}
		?>
		<script type="application/ld+json">
		<?php echo json_encode( $schema_data ); ?>
		</script>
		<?php

		$this->_schema_rendered = true;
	}

}

/**
 * Register the module and its form settings.
 */
BB_PowerPack::register_module(
	'PPFAQModule',
	array(
		'items'      => array(
			'title'    => __( 'FAQ', 'bb-powerpack' ),
			'sections' => array(
				'schema_markup' => array(
					'title'  => 'Schema Markup',
					'fields' => array(
						'enable_schema' => array(
							'type'        => 'pp-switch',
							'label'       => __( 'Enable Schema Markup', 'bb-powerpack' ),
							'default'     => 'yes',
							'options'     => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'description' => __( '<br>Enable Schema Markup if you are setting up a unique FAQ page on your website. The module adds FAQ Page schema to the page as per Google\'s Structured Data guideline.<br><a target="_blank" rel="noopener" href="https://developers.google.com/search/docs/data-types/faqpage"><b style="color: #2d7ea2;">Click here</b></a> for more details.', 'bb-powerpack' ),
						),
					),
				),
				'faq_general' => array(
					'title' => __( 'FAQ Items', 'bb-powerpack' ),
					'file'  => BB_POWERPACK_DIR . 'includes/ui-setting-fields.php',
				),
				'items' => array(
					'title' => __( 'Items', 'bb-powerpack' ),
					'fields' => array(
						'items'      => array(
							'type'         => 'form',
							'label'        => __( 'FAQ', 'bb-powerpack' ),
							'form'         => 'pp_faq_items_form', // ID from registered form below
							'preview_text' => 'faq_question', // Name of a field to use for the preview text
							'multiple'     => true,
						),
					),
				), 
				'post_content'  => array(
					'title' => __( 'Content', 'bb-powerpack' ),
					'file'  => BB_POWERPACK_DIR . 'includes/ui-loop-settings-simple.php',
				),
				'faq_settings'  => array(
					'title'     => __( 'Settings', 'bb-powerpack' ),
					'fields'    => array(
						'expand_option'       => array(
							'type'    => 'select',
							'label'   => __( 'Expand', 'bb-powerpack' ),
							'default' => 'first',
							'options' => array(
								'first'  => __( 'First Item', 'bb-powerpack' ),
								'custom' => __( 'Custom Item', 'bb-powerpack' ),
								'all'    => __( 'All', 'bb-powerpack' ),
								'none'   => __( 'None', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'open_custom' ),
								),
							),
						),
						'open_custom'         => array(
							'type'    => 'text',
							'label'   => __( 'Expand Custom', 'bb-powerpack' ),
							'default' => '',
							'size'    => 5,
							'help'    => __( 'Add item number to expand by default.', 'bb-powerpack' ),
						),
						'collapse'            => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Collapse Inactive', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'help'    => __( 'Choosing yes will keep only one item open at a time. Choosing no will allow multiple items to be open at the same time.', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'responsive_collapse' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Responsive Collapse All', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'help'    => __( 'Items will not appear as expanded on responsive devices until user clicks on it.', 'bb-powerpack' ),
						),
						'faq_id_prefix'       => array(
							'type'        => 'text',
							'label'       => __( 'Custom ID Prefix', 'bb-powerpack' ),
							'default'     => '',
							'placeholder' => __( 'myfaq', 'bb-powerpack' ),
							'help'        => __( 'A prefix that will be applied to ID attribute of faq items in HTML. For example, prefix "myfaq" will be applied as "myfaq-1", "myfaq-2" in ID attribute of faq item 1 and faq item 2 respectively. It should only contain dashes, underscores, letters or numbers. No spaces.', 'bb-powerpack' ),
						),
					),
				),
			),
		),
		'icon_style' => array(
			'title'    => __( 'Icon', 'bb-powerpack' ),
			'sections' => array(
				'responsive_toggle_icons' => array(
					'title'  => __( 'Toggle Icons', 'bb-powerpack' ),
					'fields' => array(
						'faq_open_icon'               => array(
							'type'        => 'icon',
							'label'       => __( 'Open Icon', 'bb-powerpack' ),
							'show_remove' => true,
						),
						'faq_close_icon'              => array(
							'type'        => 'icon',
							'label'       => __( 'Close Icon', 'bb-powerpack' ),
							'show_remove' => true,
						),
						'faq_toggle_icon_position'    => array(
							'type'    => 'select',
							'label'   => __( 'Icon Position', 'bb-powerpack' ),
							'default' => 'right',
							'options' => array(
								'left'  => __( 'Before Text', 'bb-powerpack' ),
								'right' => __( 'After Text', 'bb-powerpack' ),
							),
						),
						'faq_toggle_icon_spacing'     => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'default'    => '15',
						),
						'faq_toggle_icon_size'        => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'default'    => '14',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button-icon, .pp-faq-item .pp-faq-button-icon:before',
								'property' => 'font-size',
							),
						),
						'faq_toggle_icon_color'       => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button-icon',
								'property' => 'color',
							),
						),
						'faq_toggle_icon_color_hover' => array(
							'type'        => 'color',
							'label'       => __( 'Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'bb-powerpack' ),
			'sections' => array(
				'box_style'       => array(
					'title'  => __( 'Box Style', 'bb-powerpack' ),
					'fields' => array(
						'item_spacing' => array(
							'type'       => 'unit',
							'label'      => __( 'Item Spacing', 'bb-powerpack' ),
							'default'    => '10',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
						),
						'box_border'   => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
						),
					),
				),
				'questions_style' => array(
					'title'     => __( 'Questions', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'qus_bg_color_default'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color - Default', 'bb-powerpack' ),
							'default'     => 'dddddd',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button',
								'property' => 'background-color',
							),
						),
						'qus_bg_color_active'    => array(
							'type'        => 'color',
							'label'       => __( 'Background Color - Active/Hover', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button:hover, .pp-faq-item.pp-faq-item-active .pp-faq-button',
								'property' => 'background-color',
							),
						),
						'qus_text_color_default' => array(
							'type'        => 'color',
							'label'       => __( 'Text Color - Default', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button .pp-faq-button-label',
								'property' => 'color',
							),
						),
						'qus_text_color_active'  => array(
							'type'        => 'color',
							'label'       => __( 'Text Color - Active/Hover', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button:hover .pp-faq-button-label, .pp-faq-item.pp-faq-item-active .pp-faq-button .pp-faq-button-label',
								'property' => 'color',
							),
						),
						'qus_padding'            => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'    => '10',
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
					),
				),
				'answer_style'    => array(
					'title'     => __( 'Answer', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'answer_bg_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => 'eeeeee',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-content',
								'property' => 'background-color',
							),
						),
						'answer_text_color' => array(
							'type'        => 'color',
							'label'       => __( 'Text Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-content',
								'property' => 'color',
							),
						),
						'answer_border'     => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.pp-faq-item .pp-faq-content',
								'important' => false,
							),
						),
						'answer_padding'    => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'default'    => '15',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-content',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'bb-powerpack' ),
			'sections' => array(
				'qus_typography'    => array(
					'title'  => __( 'Questions', 'bb-powerpack' ),
					'fields' => array(
						'qus_tag'        => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'bb-powerpack' ),
							'default' => 'span',
							'options' => array(
								'h1'   => 'H1',
								'h2'   => 'H2',
								'h3'   => 'H3',
								'h4'   => 'H4',
								'h5'   => 'H5',
								'h6'   => 'H6',
								'div'  => 'div',
								'span' => 'span',
								'p'    => 'p',
							),
						),
						'qus_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Questions Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-button .pp-faq-button-label',
							),
						),
					),
				),
				'answer_typography' => array(
					'title'     => __( 'Answer', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'answer_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Answer Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-faq-item .pp-faq-content',
							),
						),
					),
				),
			),
		),
	)
);

/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form(
	'pp_faq_items_form',
	array(
		'title' => __( 'Add FAQ', 'bb-powerpack' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'bb-powerpack' ),
				'sections' => array(
					'general' => array(
						'title'  => '',
						'fields' => array(
							'faq_question' => array(
								'type'        => 'text',
								'label'       => __( 'Question', 'bb-powerpack' ),
								'default'     => __( 'FAQ', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
							'answer'       => array(
								'type'        => 'editor',
								'label'       => __( 'Answer', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
						),
					),
				),
			),
		),
	)
);
