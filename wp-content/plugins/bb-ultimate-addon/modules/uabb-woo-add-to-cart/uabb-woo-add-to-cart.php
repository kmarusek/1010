<?php

/**
 * @class UABBWooAddToCartModule
 */
class UABBWooAddToCartModule extends FLBuilderModule {

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
			'name'          	=> __('Woo - Add To Cart', 'uabb'),
			'description'   	=> __('Display WooCommerce Add to Cart button.', 'uabb'),
			'category'          => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$woo_modules ),
            'group'             => UABB_CAT,
			'dir'           	=> BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-add-to-cart/',
            'url'           	=> BB_ULTIMATE_ADDON_URL . 'modules/uabb-woo-add-to-cart/',
            'partial_refresh'	=> true,
			'icon'				=> 'uabb-woo-add-to-cart.svg',
		));

		$this->add_css( 'font-awesome' );
	}

	/**
	 * Function that renders icons for the Woo - Add To Cart
	 *
	 * @param object $icon get an object for the icon.
	 */
	public function get_icon( $icon = '' ) {

        // check if $icon is referencing an included icon.
        if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-add-to-cart/icon/' . $icon ) ) {
            $path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-add-to-cart/icon/' . $icon;
        }

        if ( file_exists( $path ) ) {
            return file_get_contents( $path );
        } else {
            return '';
        }
    }

	public function enqueue_scripts() {
		
		$localize = array(
			'is_cart'	=> is_cart(),
			'view_cart'	=> esc_attr__( 'View cart', 'uabb' ),
			'cart_url'	=> apply_filters( 'uabb_woocommerce_add_to_cart_redirect', wc_get_cart_url() )
		);

		wp_localize_script( 'jquery', 'uabb_cart', $localize );
	}

	public function get_inner_classes() {
		
		$settings 	= $this->settings;
		$classes 	= array();

		$classes = array(
			'uabb-woo--align-' . $settings->content_alignment,
		);

		if ( 'grid' === $settings->layout ) {

			$classes[]  = 'uabb-woo-cat__column-' . $settings->grid_columns_desktop;
			$classes[]  = 'uabb-woo-cat__column-tablet-' . $settings->grid_columns_medium;
			$classes[]  = 'uabb-woo-cat__column-mobile-' . $settings->grid_columns_small;
		} elseif ( 'carousel' === $settings->layout ) {
			$classes[] = 'uabb-woo-cat__column-' . $settings->slider_columns_desktop;
			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_position;
			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_style;
			
			if ( 'yes' === $settings->enable_dots ) {
				$classes[] = 'uabb-slick-dotted';
			}
		}

		
		return implode( ' ', $classes );
	}

	/**
	 * Render Cart Button.
	 *
	 * @return string
	 */
	public function render_cart_button() {

		$settings	= $this->settings;
		$node_id	= $this->node;
		$atc_html 	= '';
		$product  	= false;
		$new_class  = '';


		if ( ! empty( $settings->product_id ) ) {
			$product_data = get_post( $settings->product_id );
		}

		$product = ! empty( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ) ) ? wc_setup_product_data( $product_data ) : false;

		if ( $product ) {

			$product_id   = $product->get_id();
			$product_type = $product->get_type();

			$class = array(
				'button',
				'uabb-button',
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			);

			if( $settings->style == 'transparent' ) {
				$class[] = 'uabb-creative-button uabb-creative-transparent-btn';
				if( isset( $settings->transparent_button_options ) && !empty( $settings->transparent_button_options ) ) {
					$class[] .= ' uabb-' . $settings->transparent_button_options .'-btn';
				}
			} else {
				$class[] = 'uabb-adc-normal-btn';
			}

			if ( 'yes' === $settings->auto_redirect ) {
				$class[] = 'uabb-redirect';
			}

			$attr = array(
				'rel'             => 'nofollow',
				'href'            => $product->add_to_cart_url(),
				'data-quantity'   => ( isset( $settings->quantity ) ? $settings->quantity : 1 ),
				'data-product_id' => $product_id,
			);

			$attr_string = '';

			foreach ( $attr as $key => $value) {
				$attr_string .= ' '. $key . '="' . $value . '"';
			}

			$atc_html .= '<a class="' . implode( ' ', $class )  . '"' . $attr_string . '>';
				$atc_html .= '<span class="uabb-atc-content-wrapper">';

				if ( ! empty( $settings->btn_icon ) ) :
					$atc_html     .= '<span class="uabb-atc-icon-align uabb-atc-align-icon-' . $settings->btn_icon_position . '">';
						$atc_html .= '<i class="' . $settings->btn_icon . '" aria-hidden="true"></i>';
					$atc_html     .= '</span>';
				endif;

					$atc_html .= '<span class="uabb-atc-btn-text">' . $settings->btn_text . '</span>';
				$atc_html .= '</span>';
			$atc_html .= '</a>';

			echo $atc_html;
		} elseif ( current_user_can( 'manage_options' ) ) {

			if( $settings->style == 'transparent' ) {
				$new_class = ' uabb-creative-button uabb-creative-transparent-btn';
				if( isset( $settings->transparent_button_options ) && !empty( $settings->transparent_button_options ) ) {
					$new_class .= 'uabb-' . $settings->transparent_button_options .'-btn';
				}
			} else {
				$new_class = 'uabb-adc-normal-btn';
			}
			$atc_html .= '<a class="button uabb-button ' . $new_class . '">';
				$atc_html .= '<span class="uabb-atc-select-text">';
					$atc_html .= __( 'Please select the product', 'uabb' );
				$atc_html .= '</span>';
			$atc_html .= '</a>';

			echo $atc_html;
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('UABBWooAddToCartModule', array(
	'general'       => array(
		'title'         => __('General', 'uabb'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					// Posts
					'product_id' => array(
						'type'          => 'suggest',
						'action'        => 'fl_as_posts',
						'data'          => 'product',
						'label'         => __( 'Product', 'uabb' ),
						'help'          => __( 'Enter the Product.', 'uabb' ),
						'limit'			=> 1,
					),
					'quantity'     => array(
		                'type'          => 'unit',
		                'label'         => __('Quantity', 'uabb'),
		                'placeholder'   => '1',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '1',
		            ),
					'auto_redirect'   => array(
						'type'          => 'select',
						'label'         => __('Auto Redirect to Cart Page', 'uabb'),
						'default'       => 'no',
						'options'       => array(
							'yes'	=> __('Yes', 'uabb'),
							'no'	=> __('No', 'uabb')
						),
						'help'          => __( 'Select Yes to redirect to cart page after the product gets added to cart.', 'uabb' ),
						
					),
				)
			),
			'button'       => array(
				'title'         => __('Button', 'uabb'),
				'fields'        => array(
					'btn_text'          => array(
						'type'          => 'text',
						'label'         => __('Text', 'uabb'),
						'default'       => __('Add to Cart', 'uabb'),
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.uabb-atc-btn-text'
						),
						'connections' => array( 'string', 'html' ),
					),
					'btn_align'         => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'uabb'),
						'default'       => 'center',
						'options'       => array(
							'center'        => __('Center', 'uabb'),
							'left'          => __('Left', 'uabb'),
							'right'         => __('Right', 'uabb'),
							'justify'         => __('Justify', 'uabb'),
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
							'justify'       => __('Justify', 'uabb'),
						),
						'help'          => __('This alignment will apply on Mobile', 'uabb'),
					),
					'btn_padding' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Padding', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .button',
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
					'border_radius' => array(
						'type'          => 'unit',
						'label'         => __('Round Corners', 'uabb'),
						'size'          => '4',
						'description'   => 'px',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart a',
                            'property'      => 'border-radius',
                            'unit'			=> 'px'
                        ),
					),
                    'btn_icon'          => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'uabb'),
						'show_remove'   => true
					),
					'btn_icon_position' => array(
						'type'          => 'select',
						'label'         => __('Icon Position', 'uabb'),
						'default'       => 'before',
						'options'       => array(
							'before'        => __('Before Text', 'uabb'),
							'after'         => __('After Text', 'uabb')
						)
					),
					'btn_icon_spacing' => array(
                        'type'          => 'unit',
                        'label'         => __('Icon Spacing', 'uabb'),
                        'placeholder'   => '8',
                        'default'		=> '',
                        'description'   => 'px',
                        'size'          => '8',
                    ),
				)
			),
		)
	),
	'style'         => array(
		'title'         => __('Style', 'uabb'),
		'sections'      => array(
			'style'         => array(
				'title'         => __('Style', 'uabb'),
				'fields'        => array(
					'style'         => array(
						'type'          => 'select',
						'label'         => __('Style', 'uabb'),
						'default'       => 'flat',
						'class'			=> 'creative_button_styles',
						'options'       => array(
							''          => __('Flat', 'uabb'),
							'transparent'   => __('Transparent', 'uabb'),
						),
						'toggle'        => array(
							'transparent'   => array(
								'fields'        => array( 'border_size', 'transparent_button_options' )
							),
						)
					),		
					'border_size'   => array(
						'type'          => 'unit',
						'label'         => __('Border Size', 'uabb'),
						'description'   => 'px',
						'maxlength'     => '3',
						'size'          => '5',
						'default' 		=> '2',
						'placeholder'   => '2'
					),
					'transparent_button_options' => array(
						'type'          => 'select',
						'label'         => __('Hover Styles', 'uabb'),
						'default'       => 'transparent-fade',
						'options'       => array(
							'none'          => __('None', 'uabb'),
							'transparent-fade'          => __('Fade Background', 'uabb'),
							'transparent-fill-top'      => __('Fill Background From Top', 'uabb'),
							'transparent-fill-bottom'      => __('Fill Background From Bottom', 'uabb'),
							'transparent-fill-left'     => __('Fill Background From Left', 'uabb'),
							'transparent-fill-right'     => __('Fill Background From Right', 'uabb'),
							'transparent-fill-center'   	=> __('Fill Background Vertical', 'uabb'),
							'transparent-fill-diagonal'   	=> __('Fill Background Diagonal', 'uabb'),
							'transparent-fill-horizontal'  => __('Fill Background Horizontal', 'uabb'),
						),
					),
				)
			),
			'btn_colors'        => array(
				'title'         => __('Button Colors', 'uabb'),
				'fields'        => array(
					'text_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Text Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .button',
                            'property'      => 'color',
                        )
					),
					'text_hover_color'   => array( 
						'type'       => 'color',
                        'label'         => __('Text Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
					'bg_color'        => array( 
						'type'       => 'color',
                        'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.uabb-woo-add-to-cart .uabb-adc-normal-btn',
									'property' => 'background',
								),
								array(
									'selector' => '.uabb-woo-add-to-cart .uabb-creative-transparent-btn',
									'property' => 'border-color',
								),
							),
						),
					),
					'bg_hover_color'        => array(
						'type'       => 'color',
                        'label'      => __('Background Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
                        'preview'       => array(
							'type'          => 'none'
						)
					),
					'view_cart_color'        => array( 
						'type'       => 'color',
                        'label'         => __('View Cart Text', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .added_to_cart',
                            'property'      => 'color',
                        )
					),

					'view_cart_hover_color'        => array( 
						'type'       => 'color',
                        'label'         => __('View Cart Hover Text', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .added_to_cart:hover',
                            'property'      => 'color',
                        )
					),
				)
			),
		)
	),
	'typography'    => array(
		'title'         => __('Typography', 'uabb'),
		'sections'      => array(
			'btn_typography'    =>  array(
                'title'     => __('Button', 'uabb' ) ,
		        'fields'    => array(
		            'font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'uabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
                        'preview'         => array(
                            'type'            => 'font',
                            'selector'        => '.uabb-woo-add-to-cart .button'
                        )
		            ),
		            'font_size'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'uabb' ),
		                'description'   => 'px',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.uabb-woo-add-to-cart .button',
                            'property'		=>	'font-size',
                            'unit'			=> 'px'
                        )
		            ),
		            'line_height'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'uabb' ),
		                'description'   => 'em',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'            => 'css',
                            'selector'        => '.uabb-woo-add-to-cart .button',
                            'property'		=>	'line-height',
                            'unit'			=> 'em'
                        )
		            ),
                    'transform'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Transform', 'uabb' ),
                        'default'       => '',
                        'options'       => array(
                            ''           		=>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .button',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woo-add-to-cart .button',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
		        )
		    ),
		)
	)
));
