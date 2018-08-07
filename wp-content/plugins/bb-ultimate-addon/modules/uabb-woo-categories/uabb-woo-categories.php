<?php

/**
 * @class UABBWooCategoriesModule
 */
class UABBWooCategoriesModule extends FLBuilderModule {

	/**
	 * Products Query
	 *
	 * @var query
	 */
	private $query = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __('Woo - Categories', 'uabb'),
			'description'   	=> __('Display WooCommerce Categories.', 'uabb'),
			'category'          => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$woo_modules ),
            'group'             => UABB_CAT,
			'dir'           	=> BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-categories/',
            'url'           	=> BB_ULTIMATE_ADDON_URL . 'modules/uabb-woo-categories/',
            'partial_refresh'	=> true,
			'icon'				=> 'text.svg',
		));

		$this->add_css( 'font-awesome' );
		$this->add_js('imagesloaded-uabb', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/imagesloaded.min.js', array('jquery'), '', true);
		$this->add_js( 'carousel', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/jquery-carousel.js', array('jquery'), '', true );
	}

	public function get_inner_classes() {
		
		$settings 	= $this->settings;
		$classes 	= array();

		$classes = array(
			'uabb-woo--align-' . $settings->content_alignment,
		);
		
		if ( 'grid' === $settings->layout ) {

			$classes[]  = 'uabb-woo-cat__column-' . $settings->grid_columns_new;
			$classes[]  = 'uabb-woo-cat__column-tablet-' . $settings->grid_columns_new_medium;
			$classes[]  = 'uabb-woo-cat__column-mobile-' . $settings->grid_columns_new_responsive;
		} elseif ( 'carousel' === $settings->layout ) {
			$classes[] = 'uabb-woo-cat__column-' . $settings->slider_columns_new;
			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_position;
			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_style;
			
			if ( 'yes' === $settings->enable_dots ) {
				$classes[] = 'uabb-slick-dotted';
			}
		}

		
		return implode( ' ', $classes );
	}

	/**
	 * List all product categories.
	 *
	 * @return string
	 */
	public function query_product_categories() {

		if ( ! isset( $this->settings->filter_rule ) ) {
			$this->settings->filter_rule = 'all';
		}

		if ( ! isset( $this->settings->display_empty_cat ) ) {
			$this->settings->display_empty_cat = 'no';
		}

		if ( ! isset( $this->settings->display_cat_desc ) ) {
			$this->settings->display_cat_desc = 'no';
		}

		if ( ! isset( $this->settings->order_by ) ) {
			$this->settings->order_by = 'name';
		}

		if ( ! isset( $this->settings->order ) ) {
			$this->settings->order = 'DESC';
		}


		$settings    = $this->settings;
		$include_ids = array();
		$exclude_ids = array();


		$atts = array(
			'limit'   => '8',
			'columns' => '4',
			'parent'  => '',
		);

		if ( 'grid' === $settings->layout  ) {
			
			$atts['limit'] = ( $settings->grid_categories ) ? $settings->grid_categories : '-1';
			$atts['columns'] = ( $settings->grid_columns_new ) ? $settings->grid_columns_new : '4';
		}elseif ( 'carousel' === $settings->layout ) {
			$atts['limit'] = ( $settings->slider_categories ) ? $settings->slider_categories : '-1';
			$atts['columns'] = ( $settings->slider_columns_new ) ? $settings->slider_columns_new : '4';
		}

		if ( 'top' === $settings->filter_rule ) {
			$atts['parent'] = 0;
		} elseif ( 'include' === $settings->filter_rule && '' !== $settings->tax_product_product_cat ) {
			$cat_ids = explode( ',', $settings->tax_product_product_cat );
			$include_ids = array_filter( array_map( 'trim', $cat_ids ) );
		} elseif ( 'exclude' === $settings->filter_rule && '' !== $settings->tax_product_product_cat ) {
			$cat_ids = explode( ',', $settings->tax_product_product_cat );
			$exclude_ids = array_filter( array_map( 'trim', $cat_ids ) );
		}

		$hide_empty = ( 'yes' === $settings->display_empty_cat ) ? 0 : 1;

		// Get terms and workaround WP bug with parents/pad counts.
		$args = array(
			'orderby'    => ( $settings->order_by ) ? $settings->order_by : 'name',
			'order'      => ( $settings->order ) ? $settings->order : 'ASC',
			'hide_empty' => $hide_empty,
			'pad_counts' => true,
			'child_of'   => $atts['parent'],
			'include'    => $include_ids,
			'exclude'    => $exclude_ids,
		);

		$product_categories = get_terms( 'product_cat', $args );

		if ( '' !== $atts['parent'] ) {
			$product_categories = wp_list_filter(
				$product_categories, array(
					'parent' => $atts['parent'],
				)
			);
		}

		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( 0 === $category->count ) {
					unset( $product_categories[ $key ] );
				}
			}
		}

		$atts['limit'] = intval( $atts['limit'] );

		if ( $atts['limit'] > 0 ) {
			$product_categories = array_slice( $product_categories, 0, $atts['limit'] );
		}

		$columns = absint( $atts['columns'] );

		wc_set_loop_prop( 'columns', $columns );

		/* Category Link */
		remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		add_action( 'woocommerce_before_subcategory', array( $this, 'template_loop_category_link_open' ), 10 );

		/* Category Wrapper */
		add_action( 'woocommerce_before_subcategory', array( $this, 'category_wrap_start' ), 15 );
		add_action( 'woocommerce_after_subcategory', array( $this, 'category_wrap_end' ), 8 );

		if ( 'yes' === $settings->display_cat_desc ) {
			add_action( 'woocommerce_after_subcategory', array( $this, 'category_description' ), 8 );
		}

		/* Category Title */
		remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', array( $this, 'template_loop_category_title' ), 10 );

		ob_start();

		if ( $product_categories ) {
			woocommerce_product_loop_start();

			foreach ( $product_categories as $category ) {

				include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-categories/templates/content-product-cat.php';
			}

			woocommerce_product_loop_end();
		}

		woocommerce_reset_loop();


		$inner_content = ob_get_clean();

		/* Category Link */
		add_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		remove_action( 'woocommerce_before_subcategory', array( $this, 'template_loop_category_link_open' ), 10 );

		/* Category Wrapper */
		remove_action( 'woocommerce_before_subcategory', array( $this, 'category_wrap_start' ), 15 );
		remove_action( 'woocommerce_after_subcategory', array( $this, 'category_wrap_end' ), 8 );

		if ( 'yes' === $settings->display_cat_desc ) {
			remove_action( 'woocommerce_after_subcategory', array( $this, 'category_description' ), 8 );
		}

		/* Category Title */
		remove_action( 'woocommerce_shop_loop_subcategory_title', array( $this, 'template_loop_category_title' ), 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

		echo $inner_content;
	}

	/**
	 * Wrapper Start.
	 *
	 * @param object $category Category object.
	 */
	function template_loop_category_link_open( $category ) {
		$link = apply_filters( 'uabb_woo_category_link', esc_url( get_term_link( $category, 'product_cat' ) ) );

		echo '<a href="' . $link . '">';
	}

	/**
	 * Wrapper Start.
	 *
	 * @param object $category Category object.
	 */
	public function category_wrap_start( $category ) {
		echo '<div class="uabb-product-cat-inner">';
	}


	/**
	 * Wrapper End.
	 *
	 * @param object $category Category object.
	 */
	public function category_wrap_end( $category ) {
		echo '</div>';
	}

	/**
	 * Category Description.
	 *
	 * @param object $category Category object.
	 */
	public function category_description( $category ) {

		if ( $category && ! empty( $category->description ) ) {

			echo '<div class="uabb-product-cat-desc">';
				echo '<div class="uabb-term-description">' . wc_format_content( $category->description ) . '</div>'; // WPCS: XSS ok.
			echo '</div>';
		}
	}

	/**
	 * Show the subcategory title in the product loop.
	 *
	 * @param object $category Category object.
	 */
	public function template_loop_category_title( $category ) {
		$output          = '<div class="uabb-category__title-wrap">';
			$output     .= '<h2 class="woocommerce-loop-category__title">';
				$output .= esc_html( $category->name );
			$output     .= '</h2>';

		if ( $category->count > 0 ) {
				$output .= sprintf( // WPCS: XSS OK.
					/* translators: 1: number of products */
					_nx( '<mark class="uabb-count">%1$s Product</mark>', '<mark class="uabb-count">%1$s Products</mark>', $category->count, 'product categories', 'uabb' ),
					number_format_i18n( $category->count )
				);
		}
		$output .= '</div>';

		echo $output;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('UABBWooCategoriesModule', array(
	'general'       => array(
		'title'         => __('General', 'uabb'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'layout'   => array(
						'type'          => 'select',
						'label'         => __('Layout', 'uabb'),
						'default'       => 'grid',
						'options'       => array(
							'grid'			=> __('Grid', 'uabb'),
							'carousel'		=> __('Carousel', 'uabb')
						),
						'toggle'	=> array(
							'grid'	=> array(
								'sections'	=> array( 'grid_options' ),
								'fields'	=> array( 'rows_gap' )
							),
							'carousel'	=> array(
								'sections'	=> array( 'slider_options' ),
								/*'fields'	=> array( 'slider_columns' )*/
							),
						)
					),
				)
			),
            'grid_options'	=> array( 
				'title'  		=> __( 'Grid Options', 'uabb' ),
				'fields' 		=> array(
					'grid_categories'     => array(
		                'type'          => 'unit',
		                'label'         => __('Categories Count', 'uabb'),
		                'placeholder'   => '-1',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '8',
		            ),
		            'grid_columns_new'  => array(
						'type'          => 'unit',
						'label'         => __( 'Columns', 'uabb' ),
						'help' => __( 'Choose number of categories to be displayed at a time.', 'uabb' ),
						'responsive'  => array(
							'default' => array(
								'default'    => '4',
								'medium'     => '2',
								'responsive' => '1',
							),
						),
					),
				),
			),
			'slider_options'	=> array( 
				'title'  		=> __( 'Slider Options', 'uabb' ),
				'fields' 		=> array(
					'slider_categories'     => array(
		                'type'          => 'unit',
		                'label'         => __('Categories Count', 'uabb'),
		                'placeholder'   => '-1',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '8',
		            ),
		            'slider_columns_new'  => array(
						'type'          => 'unit',
						'label'         => __( 'Columns', 'uabb' ),
						'help' => __( 'Choose number of categories to be displayed at a time.', 'uabb' ),
						'responsive'  => array(
							'default' => array(
								'default'    => '4',
								'medium'     => '2',
								'responsive' => '1',
							),
						),
					),
					'slides_to_scroll'  => array(
                        'type'          => 'unit',
                        'label'         => __('Categories to Scroll', 'uabb'),
                        'help'          => __( 'Choose number of categories you want to scroll at a time.', 'uabb' ),
                        'placeholder'   => '1',
                        'size'          => '8',
                    ),
                    'autoplay'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Autoplay Categories Scroll', 'uabb' ),
                        'help'          => __( 'Enables auto play of posts.', 'uabb' ),
                        'default'       => 'no',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle' => array(
                            'yes' => array(
                                'fields' => array( 'animation_speed' )
                            )
                        )
                    ),
                    'animation_speed' => array(
                        'type'          => 'unit',
                        'label'         => __('Autoplay Speed', 'uabb'),
                        'help'          => __( 'Enter the time interval to scroll post automatically.', 'uabb' ),
                        'placeholder'   => '1000',
                        'size'          => '8',
                        'description'   => __( 'ms', 'uabb' )
                    ),
                    'infinite_loop'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Infinite Loop', 'uabb' ),
                        'help'          => __( 'Enable this to scroll posts in infinite loop.', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                    ),
                    'enable_dots' => array(
                        'type'          => 'select',
                        'label'         => __( 'Enable Dots', 'uabb' ),
                        'help'          => __( 'Enable Dots navigation below to your carousel slider.', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                    ),
                    'enable_arrow' => array(
                        'type'          => 'select',
                        'label'         => __( 'Enable Arrows', 'uabb' ),
                        'help'          => __( 'Enable Next/Prev arrows to your carousel slider.', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'	=> array(
							'yes'	=> array(
								'fields'	=> array( 'arrow_position', 'arrow_style', 'arrow_color', 'arrow_background_color','arrow_color_border', 'arrow_border_size' )
							),
							'no'	=> array(
								'fields'	=> array( '' )
							),
						)
                    ),
                    'arrow_position' => array(
                        'type'          => 'select',
                        'label'         => __( 'Arrow Position', 'uabb' ),
                        'default'       => 'outside',
                        'options'       => array(
                            'outside'   => __( 'Outside', 'uabb' ),
                            'inside'    => __( 'Inside', 'uabb' ),
                        ),
                    ),
                    'arrow_style'       => array(
                        'type'          => 'select',
                        'label'         => __('Arrow Style', 'uabb'),
                        'default'       => 'circle',
                        'options'       => array(
                            'square'             => __('Square Background', 'uabb'),
                            'circle'             => __('Circle Background', 'uabb'),
                            'square-border'      => __('Square Border', 'uabb'),
                            'circle-border'      => __('Circle Border', 'uabb')
                        ),
                        'toggle'        => array(
                            'square-border' => array(
                                'fields'        => array( 'arrow_color', 'arrow_color_border', 'arrow_border_size' )
                            ),
                            'circle-border' => array(
                                'fields'        => array( 'arrow_color', 'arrow_color_border', 'arrow_border_size' )
                            ),
                            'square' => array(
                                'fields'        => array( 'arrow_color', 'arrow_background_color' )
                            ),
                            'circle' => array(
                                'fields'        => array( 'arrow_color', 'arrow_background_color' )
                            ),
                        )
                    ),
                    'arrow_color' => array( 
                        'type'       => 'color',
                        'label'         => __('Arrow Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
						'show_alpha' => true,
                    ),
                    'arrow_background_color' => array( 
                        'type'       => 'color',
                        'label'         => __('Arrow Background Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
						'show_alpha' => true,
                    ),
                    'arrow_color_border' => array( 
                        'type'       => 'color',
                        'label'         => __('Arrow Border Color', 'uabb'),
                        'default'    => '',
                        'show_reset' => true,
						'show_alpha' => true,
                    ),
                    'arrow_border_size'       => array(
                        'type'          => 'unit',
                        'label'         => __('Border Size', 'uabb'),
                        'default'       => '1',
                        'description'   => 'px',
                        'size'          => '8',
                        'max_length'    => '3'
                    ),
                )
            ),
		)
	),
	'product_query' => array(
		'title'         => __('Query', 'uabb'),
		'file'          => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-categories/includes/loop-settings.php',
	),
	'style'         => array(
		'title'         => __('Style', 'uabb'),
		'sections'      => array(
			'layout_style_sec' 	=> 	array( // Section
		        'title'         => __('Layout','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'columns_gap' => array(
						'type' => 'unit',
						'label' => __('Columns Gap', 'uabb'),
						'description' => 'px',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'rows_gap' => array(
						'type' => 'unit',
						'label' => __('Rows Gap', 'uabb'),
						'description' => 'px',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
		        )
		    ),
		    'content_style_sec' 	=> 	array( // Section
		        'title'         => __('Category Content','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'content_alignment' => array(
                        'type'          => 'select',
                        'label' 		=> __('Alignment', 'uabb'),
                        'default'       => 'center',
                        'options'       => array(
                            'center' => __( 'Center', 'uabb' ),
                            'left' 	 => __( 'Left', 'uabb' ),
                            'right'  => __( 'Right', 'uabb' ),
                        ),
                    ),
                    'mobile_align'         => array(
						'type'          => 'select',
						'label'         => __('Mobile Alignment', 'uabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'uabb'),
							'left'          => __('Left', 'uabb'),
							'right'         => __('Right', 'uabb'),
						),
						'help'          => __('This alignment will apply on Mobile', 'uabb'),
					),
                    'content_around_spacing' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Spacing Around Content', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories li.product .uabb-category__title-wrap',
                            'property'      => 'padding',
                            'unit'          => 'px',
                        ),
                        'responsive' => array(
                            'placeholder' => array(
                                'default' 	 => '',
                                'medium' 	 => '',
                                'responsive' => '',
                            ),
                        ),
                    ),
                    'content_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count'
						)
					),
					'content_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woo-categories li.product .uabb-category__title-wrap'
						)
					),
					'content_hover_color'    => array( 
						'type'       => 'color',
						'label'         => __('Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woo-categories li.product-category > a:hover .woocommerce-loop-category__title, .uabb-woo-categories li.product-category > a:hover .uabb-category__title-wrap .uabb-count'
						)
					),
					'content_hover_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woo-categories li.product-category > a:hover .uabb-category__title-wrap'
						)
					),
		        )
		    ),
		    'desc_style_sec' 	=> 	array( // Section
		        'title'         => __('Category Description','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'desc_alignment' => array(
                        'type'          => 'select',
                        'label' 		=> __('Alignment', 'uabb'),
                        'default'       => 'left',
                        'options'       => array(
                            'center' => __( 'Center', 'uabb' ),
                            'left' 	 => __( 'Left', 'uabb' ),
                            'right'  => __( 'Right', 'uabb' ),
                        ),
                    ),
                    'desc_around_spacing' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Spacing Around Content', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories .uabb-product-cat-desc',
                            'property'      => 'padding',
                            'unit'          => 'px',
                        ),
                        'responsive' => array(
                            'placeholder' => array(
                                'default' 	 => '',
                                'medium' 	 => '',
                                'responsive' => '',
                            ),
                        ),
                    ),
                    'desc_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woo-categories .uabb-term-description'
						)
					),
					'desc_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woo-categories .uabb-product-cat-desc'
						)
					),
					'desc_hover_color'    => array( 
						'type'       => 'color',
						'label'         => __('Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woo-categories li.product-category > a:hover .uabb-term-description'
						)
					),
					'desc_hover_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woo-categories li.product-category > a:hover .uabb-product-cat-desc'
						)
					),
		        )
		    ),
		)
	),
	'typography'    => array(
		'title'         => __('Typography', 'uabb'),
		'sections'      => array(
			'content_typo'     => array(
				'title'         => __('Content', 'uabb'),
				'fields'        => array(
					'content_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count'
							
						)
					),
					'content_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count',
							'property'	=> 'font-size',
							'unit' 		=> 'px'
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'content_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count',
							'property'	=> 'line-height',
							'unit' 		=> 'em'
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
                    'content_transform'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Transform', 'uabb' ),
                        'default'       => 'none',
                        'options'       => array(
                            'none'           =>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'content_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories li.product .woocommerce-loop-category__title, .uabb-woo-categories li.product .uabb-category__title-wrap .uabb-count',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'desc_typo'     => array(
				'title'         => __('Description', 'uabb'),
				'fields'        => array(
					'desc_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woo-categories .uabb-term-description'
							
						)
					),
					'desc_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woo-categories .uabb-term-description',
							'property'	=> 'font-size',
							'unit' 		=> 'px'
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'desc_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woo-categories .uabb-term-description',
							'property'	=> 'line-height',
							'unit' 		=> 'em'
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
                    'desc_transform'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Transform', 'uabb' ),
                        'default'       => 'none',
                        'options'       => array(
                            'none'           =>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories .uabb-term-description',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'desc_letter_spacing'       => array(
                        'type'          => 'text',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-categories .uabb-term-description',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
		)
	)
));
