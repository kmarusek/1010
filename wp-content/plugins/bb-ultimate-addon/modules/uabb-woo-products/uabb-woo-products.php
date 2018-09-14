<?php

/**
 * @class UABBWooProductsModule
 */
class UABBWooProductsModule extends FLBuilderModule {

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
			'name'          	=> __('Woo - Products', 'uabb'),
			'description'   	=> __('Display WooCommerce Products.', 'uabb'),
			'category'          => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$woo_modules ),
            'group'             => UABB_CAT,
			'dir'           	=> BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/',
            'url'           	=> BB_ULTIMATE_ADDON_URL . 'modules/uabb-woo-products/',
            'partial_refresh'	=> true,
			'icon'				=> 'uabb-woo-products.svg',
		));

		$this->add_css( 'font-awesome' );
		$this->add_js('imagesloaded-uabb', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/imagesloaded.min.js', array('jquery'), '', true);
		$this->add_js( 'carousel', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/jquery-carousel.js', array('jquery'), '', true );

		add_filter( 'fl_builder_loop_query_args', array( $this, 'woo_filter_args' ) );

		// quick view ajax.
		add_action( 'wp_ajax_uabb_woo_quick_view', array( $this, 'load_quick_view_product' ) );
		add_action( 'wp_ajax_nopriv_uabb_woo_quick_view', array( $this, 'load_quick_view_product' ) );

		/* Add tO cart */
		add_action( 'wp_ajax_uabb_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );
		add_action( 'wp_ajax_nopriv_uabb_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );

		add_action( 'wp_ajax_uabb_get_products', array( $this, 'uabb_get_products' ) );
		add_action( 'wp_ajax_nopriv_uabb_get_products', array( $this, 'uabb_get_products' ) );
	}

	/**
	 * Function that renders icons for the Woo - Products
	 *
	 * @param object $icon get an object for the icon.
	 */
	public function get_icon( $icon = '' ) {

        // check if $icon is referencing an included icon.
        if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/icon/' . $icon ) ) {
            $path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/icon/' . $icon;
        }

        if ( file_exists( $path ) ) {
            return file_get_contents( $path );
        } else {
            return '';
        }
    }

	/**
	 * Get Woo Data via AJAX call.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function uabb_get_products() {

		$data = array(
			'message'    => __( 'Saved', 'uabb' ),
			'html'       => '',
			'pagination' => '',
		);

		ob_start();

		$this->settings = (object)$_POST['settings'];

		add_filter( 'fl_builder_loop_query_args', function( $args ) {

			if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
				$args['paged'] = $_POST['page_number'];
				$args['offset'] = ( ( $_POST['page_number'] - 1 ) * $this->settings->posts_per_page );
			}
			return $args;

		});

		$this->render_query();
		$this->render_loop_args();
		$this->render_woo_loop_start();
			$this->render_woo_loop();
		$this->render_woo_loop_end();

		$data['html'] = ob_get_clean();

		ob_start();
		$this->render_pagination_structure();
		$data['pagination'] = ob_get_clean();

		wp_send_json_success( $data );
	}

	/**
	 * Filter the args.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	public function woo_filter_args( $args ) {
		
		if ( 'uabb-woo-products' === $args['settings']->type ) {

			if ( isset( $args['settings']->filter_by ) ) {
				if ( 'sale' === $args['settings']->filter_by ) {

					$sale_ids 	= wc_get_product_ids_on_sale();

					if ( isset( $args['post__in'] ) ) {
						
						$post_in  = $args['post__in'];
						$sale_ids = array_intersect( $post_in, $sale_ids );
					}

					$args['post__in'] = $sale_ids;
				} elseif ( 'featured' === $args['settings']->filter_by ) {

					$product_visibility_term_ids = wc_get_product_visibility_term_ids();

					$args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['featured'],
					);
				}
			}
		}

		return $args;
	}

	public function get_inner_classes() {
		
		$settings 	= $this->settings;
		$classes 	= array();

		$classes = array(
			'uabb-woo--align-' . $settings->content_alignment,
			'uabb-woo--align-mobile-' . $settings->mobile_align,
			'uabb-woo-product__hover-' . $settings->image_hover_style,
			'uabb-woo-product__hover-' . $settings->image_hover_style,
			'uabb-sale-flash-' . $settings->sale_flash_style,
			'uabb-featured-flash-' . $settings->featured_flash_style
		);
		
		if ( 'grid' === $settings->layout ) {
			$classes[] = 'columns-' . $settings->grid_columns_new;
			$classes[] = 'uabb-woo-product__column-' . $settings->grid_columns_new;
			$classes[] = 'uabb-woo-product__column-tablet-' . $settings->grid_columns_new_medium;
			$classes[] = 'uabb-woo-product__column-mobile-' . $settings->grid_columns_new_responsive;
		} elseif ( 'carousel' === $settings->layout ) {
			/*$classes[] = 'columns-' . $settings->slider_columns_new;
			$classes[] = 'uabb-woo-product__column-' . $settings->slider_columns_new;
			$classes[] = 'uabb-woo-product__column-tablet-' . $settings->slider_columns_new_medium;
			$classes[] = 'uabb-woo-product__column-mobile-' . $settings->slider_columns_new_responsive;*/

			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_position;
			$classes[] = 'uabb-woo-slider-arrow-' . $settings->arrow_style;

			if ( 'yes' === $settings->enable_dots ) {
				$classes[] = 'uabb-slick-dotted';
			}
		}

		
		return implode( ' ', $classes );
	}
	/**
	 * Register Get Query.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Get query products based on settings.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.8.0
	 * @access public
	 */
	public function render_query() {

		global $post;

		$this->settings->post_type = 'product';

		$settings = $this->settings;
		
		if ( ! isset( $settings->order_by ) ) {
			$this->settings->order_by = 'date';
		}

		if ( ! isset( $settings->order ) ) {
			$this->settings->order = 'DESC';
		}

		$query_args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'paged'          => 1,
			'post__not_in'   => array(),
		);

		if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
			$query_args['paged'] = $_POST['page_number'];
		}


		if ( 'grid' === $settings->layout ) {

			if ( $settings->grid_products > 0 ) {
				
				$query_args['posts_per_page'] = $settings->grid_products;

				$settings->posts_per_page = $settings->grid_products;
			}

			/*if ( '' !== $settings['pagination_type'] ) {

				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '1';

				$query_args['paged'] = $paged;
			}*/
		} else {

			if ( $settings->slider_products > 0 ) {
				$query_args['posts_per_page'] = $settings->slider_products;
				$settings->posts_per_page = $settings->slider_products;
			}
		}

		$this->query = FLBuilderLoop::query( $settings );
		
		// Default ordering args.
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings->order_by, $settings->order );
		

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order']   = $ordering_args['order'];

		// Order by meta value arg.
        if ( strstr( $query_args['orderby'], 'meta_value' ) ) {
            
            if( isset( $settings->order_by_meta_key ) ) {
                $query_args['meta_key'] = $settings->order_by_meta_key;
            }
        }
	}

	/**
	 * Render loop required arguments.
	 *
	 * @since 1.1.0
	 */
	public function render_loop_args() {

		global $woocommerce_loop;

		$query = $this->get_query();

		$settings = $this->settings;

		if ( 'grid' === $settings->layout ) {
			
			$woocommerce_loop['columns'] = (int) $settings->grid_columns_new;
			
			// Pagination
			if ( 0 < $settings->grid_products && '' !== $settings->pagination_type ) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
					$paged = $_POST['page_number'];
				}
				$woocommerce_loop['paged']        = $paged;
				$woocommerce_loop['total']        = $query->found_posts;
				$woocommerce_loop['post_count']   = $query->post_count;
				$woocommerce_loop['per_page']     = $settings->grid_products;
				$woocommerce_loop['total_pages']  = ceil( $query->found_posts / $settings->grid_products );
				$woocommerce_loop['current_page'] = $paged;
			}
		}elseif( 'carousel' === $settings->layout ) {
			
			$woocommerce_loop['columns'] = (int) $settings->slider_columns_new;
		}
	}

	/**
	 * Render woo default template.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_template() {

		$settings = $this->settings;

		if ( 'classic' === $settings->skin ) {
			include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/content-product-default.php';
			
		} elseif ( 'modern' === $settings->skin ) {
			include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/content-product-modern.php';
		}
	}

	/**
	 * Render woo loop start.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_start() {
		woocommerce_product_loop_start();
	}

	/**
	 * Render woo loop.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop() {

		$query = $this->get_query();

		while ( $query->have_posts() ) :
			$query->the_post();
			$this->render_woo_loop_template();
		endwhile;
	}

	/**
	 * Render woo loop end.
	 *
	 * @since 1.1.0
	 */
	public function render_woo_loop_end() {
		woocommerce_product_loop_end();
	}

	/**
	 * Render reset loop.
	 *
	 * @since 1.1.0
	 */
	public function render_reset_loop() {

		woocommerce_reset_loop();

		wp_reset_postdata();
	}

	/**
	 * Short Description.
	 *
	 * @since 0.0.1
	 */
	public function woo_shop_short_desc() {
		if ( has_excerpt() ) {
			echo '<div class="uabb-woo-products-description">';
				echo the_excerpt();
			echo '</div>';
		}
	}

	/**
	 * Parent Category.
	 *
	 * @since 1.1.0
	 */
	public function woo_shop_parent_category() {
		if ( apply_filters( 'uabb_woo_shop_parent_category', true ) ) : ?>
			<span class="uabb-woo-product-category">
				<?php
				global $product;
				$product_categories = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( get_the_ID(), ',', '', '' ) : $product->get_categories( ',', '', '' );

				$product_categories = strip_tags( $product_categories );
				if ( $product_categories ) {
					list( $parent_cat ) = explode( ',', $product_categories );
					echo esc_html( $parent_cat );
				}
				?>
			</span> 
			<?php
		endif;
	}

	/**
	 * Product Flip Image.
	 *
	 * @since 0.0.1
	 */
	public function woo_shop_product_flip_image() {

		global $product;

		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids ) {

			$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );

			echo apply_filters( 'uabb_woocommerce_product_flip_image', wp_get_attachment_image( reset( $attachment_ids ), $image_size, false, array( 'class' => 'uabb-show-on-hover' ) ) );
		}
	}

	/**
	 * Pagination Structure.
	 *
	 * @since 1.1.0
	 */
	public function render_pagination_structure() {

		$settings = $this->settings;
		
		if ( '' !== $settings->pagination_type ) {
			
			$query = $this->get_query();

			add_filter( 'wc_get_template', array( $this, 'woo_pagination_template' ), 10, 5 );
			add_filter( 'uabb_woocommerce_pagination_args', array( $this, 'woo_pagination_options' ) );

			$total_pages = $query->max_num_pages;
			$permalink_structure = get_option( 'permalink_structure' );
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '1';

			if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
				$paged = $_POST['page_number'];
			}
			$base = html_entity_decode( get_pagenum_link() );

			if ( $total_pages > 1 ) {

				if ( ! $current_page = $paged ) { // @codingStandardsIgnoreLine
					$current_page = 1;
				}

				// Refer this function if any issue woocommerce_pagination();
				$base = FLBuilderLoop::build_base_url( $permalink_structure, $base );
				$format = FLBuilderLoop::paged_format( $permalink_structure, $base );

				$args = array(
					'base'	   => $base . '%_%',
					'format'   => $format,
					'current'  => $current_page,
					'total'	   => $total_pages,
				);
				
				wc_get_template( 'loop/pagination.php', $args );
			}

			remove_filter( 'uabb_woocommerce_pagination_args', array( $this, 'woo_pagination_options' ) );
			remove_filter( 'wc_get_template', array( $this, 'woo_pagination_template' ), 10, 5 );
		}
	}

	/**
	 * Change pagination arguments based on settings.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @param string $located location.
	 * @param string $template_name template name.
	 * @param array  $args arguments.
	 * @param string $template_path path.
	 * @param string $default_path default path.
	 * @return string template location
	 */
	public function woo_pagination_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( 'loop/pagination.php' === $template_name ) {
			$located = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/loop/pagination.php';
		}

		return $located;
	}

	/**
	 * Change pagination arguments based on settings.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @param array $args pagination args.
	 * @return array
	 */
	public function woo_pagination_options( $args ) {

		$settings = $this->settings;

		$pagination_arrow = false;

		if ( 'numbers_arrow' === $settings->pagination_type ) {
			$pagination_arrow = true;
		}

		$args['prev_next'] = $pagination_arrow;

		/*if ( '' !== $settings['pagination_prev_label'] ) {
			$args['prev_text'] = $settings['pagination_prev_label'];
		}

		if ( '' !== $settings['pagination_next_label'] ) {
			$args['next_text'] = $settings['pagination_next_label'];
		}*/

		return $args;
	}

	/**
	 * Quick View.
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function quick_view_modal() {

		$settings = $this->settings;
		$quick_view_type = $settings->quick_view;

		if ( '' !== $quick_view_type ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'flexslider' );

			$widget_id = $this->node;

			include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/quick-view-modal.php';
		}
	}

	/**
	 * Load Quick View Product.
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function load_quick_view_product() {

		if ( ! isset( $_REQUEST['product_id'] ) ) {
			die();
		}

		$this->quick_view_content_actions();

		$product_id = intval( $_REQUEST['product_id'] );

		// echo $product_id;
		// die();
		// set the main wp query for the product.
		wp( 'p=' . $product_id . '&post_type=product' );

		ob_start();

		// load content template.
		include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/quick-view-product.php';

		echo ob_get_clean();

		die();
	}

	/**
	 * Quick view actions
	 */
	public function quick_view_content_actions() {

		add_action( 'uabb_woo_quick_view_product_image', 'woocommerce_show_product_sale_flash', 10 );
		// Image.
		add_action( 'uabb_woo_quick_view_product_image', array( $this, 'quick_view_product_images_markup' ), 20 );

		// Summary.
		add_action( 'uabb_woo_quick_view_product_summary', array( $this, 'quick_view_product_content_structure' ), 10 );
	}

	/**
	 * Quick view product images markup.
	 */
	public function quick_view_product_images_markup() {

		include BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/templates/quick-view-product-image.php';
	}

	/**
	 * Quick view product content structure.
	 */
	public function quick_view_product_content_structure() {

		global $product;

		$post_id = $product->get_id();

		$single_structure = apply_filters(
			'uabb_quick_view_product_structure',
			array(
				'title',
				'ratings',
				'price',
				'short_desc',
				'meta',
				'add_cart',
			)
		);

		if ( is_array( $single_structure ) && ! empty( $single_structure ) ) {

			foreach ( $single_structure as $value ) {

				switch ( $value ) {
					case 'title':
						/**
						 * Add Product Title on single product page for all products.
						 */
						do_action( 'uabb_quick_view_title_before', $post_id );
						woocommerce_template_single_title();
						do_action( 'uabb_quick_view_title_after', $post_id );
						break;
					case 'price':
						/**
						 * Add Product Price on single product page for all products.
						 */
						do_action( 'uabb_quick_view_price_before', $post_id );
						woocommerce_template_single_price();
						do_action( 'uabb_quick_view_price_after', $post_id );
						break;
					case 'ratings':
						/**
						 * Add rating on single product page for all products.
						 */
						do_action( 'uabb_quick_view_rating_before', $post_id );
						woocommerce_template_single_rating();
						do_action( 'uabb_quick_view_rating_after', $post_id );
						break;
					case 'short_desc':
						do_action( 'uabb_quick_view_short_description_before', $post_id );
						woocommerce_template_single_excerpt();
						do_action( 'uabb_quick_view_short_description_after', $post_id );
						break;
					case 'add_cart':
						do_action( 'uabb_quick_view_add_to_cart_before', $post_id );
						woocommerce_template_single_add_to_cart();
						do_action( 'uabb_quick_view_add_to_cart_after', $post_id );
						break;
					case 'meta':
						do_action( 'uabb_quick_view_category_before', $post_id );
						woocommerce_template_single_meta();
						do_action( 'uabb_quick_view_category_after', $post_id );
						break;
					default:
						break;
				}
			}
		}

	}

	/**
	 * Single Product add to cart ajax request
	 *
	 * @since 1.1.0
	 *
	 * @return void.
	 */
	function add_cart_single_product_ajax() {
		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? sanitize_text_field( $_POST['quantity'] ) : 0;

		if ( $variation_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}
		die();
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('UABBWooProductsModule', array(
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
								'sections'	=> array( 'grid_options', 'pagination_style_sec' ),
								'fields'	=> array( 'rows_gap' )
							),
							'carousel'	=> array(
								'sections'	=> array( 'slider_options' ),
								/*'fields'	=> array( 'slider_columns' )*/
							),
						)
					),
					'skin'   => array(
						'type'          => 'select',
						'label'         => __('Skin', 'uabb'),
						'default'       => 'classic',
						'options'       => array(
							'classic'		=> __('Classic', 'uabb'),
							'modern'		=> __('Modern', 'uabb')
						),
					),
				)
			),
            'grid_options'	=> array( 
				'title'  		=> __( 'Grid Options', 'uabb' ),
				'fields' 		=> array(
					'grid_products'     => array(
		                'type'          => 'unit',
		                'label'         => __('Products Per Page', 'uabb'),
		                'placeholder'   => '-1',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '8',
		            ),
		            'grid_columns_new'  => array(
						'type'          => 'unit',
						'label'         => __( 'Columns', 'uabb' ),
						'help' => __( 'Choose number of products to be displayed at a time.', 'uabb' ),
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
					'slider_products'     => array(
		                'type'          => 'unit',
		                'label'         => __('Total Products', 'uabb'),
		                'placeholder'   => '-1',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '8',
		            ),
		            'slider_columns_new'  => array(
						'type'          => 'unit',
						'label'         => __( 'Columns', 'uabb' ),
						'help' => __( 'Choose number of products to be displayed at a time.', 'uabb' ),
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
                        'label'         => __( 'Posts to Scroll', 'uabb' ),
                        'help'          => __( 'Choose number of posts you want to scroll at a time.', 'uabb' ),
                        'placeholder'   => '1',
                        'size'          => '8',
                    ),
                    'autoplay'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Autoplay Post Scroll', 'uabb' ),
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
                        'type'          => 'text',
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
                    /*'icon_left'          => array(
                        'type'          => 'icon',
                        'label'         => __('Left Arrow Icon', 'uabb'),
                        'show_remove' => true
                    ),
                    'icon_right'          => array(
                        'type'          => 'icon',
                        'label'         => __('Right Arrow Icon', 'uabb'),
                        'show_remove' => true
                    ),*/
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
		'file'          => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-woo-products/includes/loop-settings.php',
	),
	'layout'        => array(
		'title'         => __('Layout', 'uabb'),
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
						/*'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-heading .uabb-heading-text',
							'property'	=> 'font-size',
							'unit' 		=> 'px'
						),*/
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
						/*'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-heading .uabb-heading-text',
							'property'	=> 'font-size',
							'unit' 		=> 'px'
						),*/
					),
		        )
		    ),
		    'image_style_sec' 	=> 	array( // Section
		        'title'         => __('Image','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'image_hover_style'   => array(
						'type'          => 'select',
						'label'         => __('Image Hover Style', 'uabb'),
						'default'       => '',
						'options'       => array(
							'' 		=> 'None',
							'swap' 	=> 'Swap Images',
							'zoom' 	=> 'Zoom Image',
						),
					),
		        )
		    ),
			'content'          => array(
				'title'         => __('Content', 'uabb'),
				'fields'        => array(
					'show_category' => array(
                        'type'          => 'select',
                        'label'         => __( 'Category', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'cat_typo' )
		                    )
		                )
                    ),
                    'show_title' => array(
                        'type'          => 'select',
                        'label'         => __( 'Title', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'title_typo' )
		                    )
		                )
                    ),
                    'show_ratings' => array(
                        'type'          => 'select',
                        'label'         => __( 'Ratings', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'rating_typo' )
		                    )
		                )
                    ),
                    'show_price' => array(
                        'type'          => 'select',
                        'label'         => __( 'Price', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'price_typo' )
		                    )
		                )
                    ),
                    'show_short_desc' => array(
                        'type'          => 'select',
                        'label'         => __( 'Short Description', 'uabb' ),
                        'default'       => 'no',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'desc_typo' )
		                    )
		                )
                    ),
                    'show_add_to_cart' => array(
                        'type'          => 'select',
                        'label'         => __( 'Add to Cart', 'uabb' ),
                        'default'       => 'yes',
                        'options'       => array(
                            'yes'       => __( 'Yes', 'uabb' ),
                            'no'        => __( 'No', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'yes'       => array(
		                        'sections' => array( 'add_cart_typo' )
		                    )
		                )
                    ),
				)
			),
			'quick_view'          => array(
				'title'         => __('Quick View', 'uabb'),
				'fields'        => array(
					'quick_view' => array(
                        'type'          => 'select',
                        'label'         => __( 'Quick View', 'uabb' ),
                        'default'       => 'hide',
                        'options'       => array(
                            'hide' => __( 'Hide', 'uabb' ),
                            'show' => __( 'Show', 'uabb' ),
                        ),
                    ),
				)
			),
		)
	),
	'style'         => array(
		'title'         => __('Style', 'uabb'),
		'sections'      => array(
			
		    'content_style_sec' 	=> 	array( // Section
		        'title'         => __('Content','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'content_alignment' => array(
                        'type'          => 'select',
                        'label' 		=> __('Alignment', 'uabb'),
                        'default'       => 'left',
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
                            'selector'      => '.uabb-woo-products-summary-wrap',
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
		        )
		    ),
		    'sale_flash_style_sec' 	=> 	array( // Section
		        'title'         => __('Sale Flash','uabb'), // Section Title
		        'fields'        => array( // Section Fields
		        	'sale_flash' => array(
                        'type'          => 'select',
                        'label'         => __( 'Flash', 'uabb' ),
                        'default'       => 'default',
                        'options'       => array(
                            'none'    => __( 'None', 'uabb' ),
                            'default' => __( 'Default', 'uabb' ),
                            'custom'  => __( 'Custom', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'default'       => array(
		                    	'sections'	=> array('sale_flash_typo'),
		                        'fields'        => array('sale_flash_style','sale_flash_size','sale_flash_padding','sale_flash_margin','sale_flash_color','sale_flash_bg_color')
		                    ),
		                    'custom'       => array(
		                    	'sections'	=> array('sale_flash_typo'),
		                        'fields'        => array('sale_flash_content','sale_flash_style','sale_flash_size','sale_flash_padding','sale_flash_margin','sale_flash_color','sale_flash_bg_color')
		                    )
		                )
                    ),
                    'sale_flash_content'     => array(
		                'type'          => 'text',
		                'label'         => __('Flash Content', 'uabb'),
						'placeholder' 	=> '-[value]%',
						'default'		=> '-[value]%',
						'connections'   => array( 'string', 'html' ),
		            ),
		        	'sale_flash_style' => array(
                        'type'          => 'select',
                        'label' 		=> __('Flash Style', 'uabb'),
                        'default'       => 'circle',
                        'options'       => array(
                            'circle' => __( 'Circle', 'uabb' ),
                            'square' 	 => __( 'Square', 'uabb' ),
                        ),
                    ),
                    'sale_flash_size'     => array(
		                'type'          => 'unit',
		                'label'         => __('Flash Size', 'uabb'),
		                'placeholder'   => '3',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '3',
		            ),
		            'sale_flash_padding' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Padding', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-sale-flash-wrap .uabb-onsale',
                            'property'      => 'padding',
                            'unit'          => 'px',
                        ),
                    ),
                    'sale_flash_margin' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Margin', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-sale-flash-wrap .uabb-onsale',
                            'property'      => 'margin',
                            'unit'          => 'px',
                        ),
                    ),
		            'sale_flash_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-sale-flash-wrap .uabb-onsale'
						)
					),
					'sale_flash_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-sale-flash-wrap .uabb-onsale'
						)
					),
		        )
		    ),
		    'featured_flash_style_sec' 	=> 	array( // Section
		        'title'         => __('Featured Flash','uabb'), // Section Title
		        'fields'        => array( // Section Fields
					'featured_flash' => array(
                        'type'          => 'select',
                        'label'         => __( 'Flash', 'uabb' ),
                        'default'       => 'none',
                        'options'       => array(
                            'none'    => __( 'None', 'uabb' ),
                            'default' => __( 'Default', 'uabb' ),
                            'custom'  => __( 'Custom', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'default'       => array(
		                    	'sections'	=> array('featured_flash_typo'),
		                        'fields'        => array( 'featured_flash_style', 'featured_flash_size', 'featured_flash_padding', 'featured_flash_margin', 'featured_flash_color', 'featured_flash_bg_color' )
		                    ),
		                    'custom'       => array(
		                    	'sections'	=> array('featured_flash_typo'),
		                        'fields'        => array('featured_flash_content', 'featured_flash_style', 'featured_flash_size', 'featured_flash_padding', 'featured_flash_margin', 'featured_flash_color', 'featured_flash_bg_color')
		                    )
		                )
                    ),
                    'featured_flash_content'     => array(
		                'type'          => 'text',
		                'label'         => __('Flash Content', 'uabb'),
						'placeholder' 	=> __( 'New', 'uabb' ),
						'connections'   => array( 'string', 'html' ),
		            ),
		        	'featured_flash_style' => array(
                        'type'          => 'select',
                        'label' 		=> __('Flash Style', 'uabb'),
                        'default'       => 'circle',
                        'options'       => array(
                            'circle' => __( 'Circle', 'uabb' ),
                            'square' 	 => __( 'Square', 'uabb' ),
                        ),
                    ),
                    'featured_flash_size'     => array(
		                'type'          => 'unit',
		                'label'         => __('Flash Size', 'uabb'),
		                'placeholder'   => '3',
		                'maxlength'     => '5',
		                'size'          => '6',
						'default'       => '3',
		            ),
		            'featured_flash_padding' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Padding', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-featured',
                            'property'      => 'padding',
                            'unit'          => 'px',
                        ),
                    ),
                    'featured_flash_margin' => array(
                        'type'      => 'dimension',
                        'label'     => __( 'Margin', 'uabb' ),
                        'description'  => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-featured',
                            'property'      => 'margin',
                            'unit'          => 'px',
                        ),
                    ),
		            'featured_flash_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-featured'
						)
					),
					'featured_flash_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woocommerce .uabb-featured'
						)
					),
		        )
		    ),
			'add_cart_style_sec'     => array(
				'title'         => __('Add to Cart', 'uabb'),
				'fields'        => array(
                    'add_cart_padding_top_bottom'       => array(
                        'type'          => 'unit',
                        'label'         => __('Padding Top/Bottom', 'uabb'),
                        'placeholder'   => '',
                        'maxlength'     => '3',
                        'size'          => '4',
                        'description'   => 'px'
                    ),
                    'add_cart_padding_left_right'       => array(
                        'type'          => 'unit',
                        'label'         => __('Padding Left/Right', 'uabb'),
                        'placeholder'   => '',
                        'maxlength'     => '3',
                        'size'          => '4',
                        'description'   => 'px'
                    ),
					'add_cart_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-summary-wrap .button',
							'unit'		=> 'px',
						)
					),
				)
			),
			'pagination_style_sec' 	=> 	array( // Section
		        'title'         => __('Pagination','uabb'), // Section Title
		        'fields'        => array( // Section Fields
					'pagination_type' => array(
                        'type'          => 'select',
                        'label'         => __( 'Type', 'uabb' ),
                        'default'       => '',
                        'options'       => array(
                            '' 			 => __( 'None', 'uabb' ),
                            'numbers' 		 => __( 'Numbers', 'uabb' ),
                            'numbers_arrow'  => __( 'Numbers + Pre/Next Arrow', 'uabb' ),
                        ),
                        'toggle'        => array(
		                    'numbers'       => array(
		                        'fields'        => array('pg_alignment','pg_color','pg_hover_color','pg_bg_color','pg_bg_hover_color','pg_border_color','pg_border_hover_color')
		                    ),
		                    'numbers_arrow'       => array(
		                        'fields'        => array('pg_alignment','pg_color','pg_hover_color','pg_bg_color','pg_bg_hover_color','pg_border_color','pg_border_hover_color')
		                    )
		                )
                    ),
		        	'pg_alignment' => array(
                        'type'          => 'select',
                        'label' 		=> __('Pagination Alignment', 'uabb'),
                        'default'       => 'center',
                        'options'       => array(
                            'center' => __( 'Center', 'uabb' ),
                            'left' 	=> __( 'Left', 'uabb' ),
                            'right' => __( 'Right', 'uabb' ),
                        ),
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => 'nav.uabb-woocommerce-pagination',
                            'property'      => 'text-align',
                        )
                    ),
                    'pg_color' => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li > .page-numbers',
                            'property'  => 'color',
                        ),
					),
					'pg_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Active / Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li .page-numbers:focus, nav.uabb-woocommerce-pagination ul li .page-numbers:hover, nav.uabb-woocommerce-pagination ul li span.current',
                            'property'  => 'color',
                        ),
					),
					'pg_bg_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li > .page-numbers',
                            'property'  => 'background',
                        ),
					),
					'pg_bg_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Background Active / Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li .page-numbers:focus, nav.uabb-woocommerce-pagination ul li .page-numbers:hover, nav.uabb-woocommerce-pagination ul li span.current',
                            'property'  => 'background',
                        ),
					),
					'pg_border_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li > .page-numbers',
                            'property'  => 'border-color',
                        ),
					),
					'pg_border_hover_color' => array( 
						'type'       => 'color',
						'label'         => __('Border Active / Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => 'nav.uabb-woocommerce-pagination ul li .page-numbers:focus, nav.uabb-woocommerce-pagination ul li .page-numbers:hover, nav.uabb-woocommerce-pagination ul li span.current',
                            'property'  => 'border-color',
                        ),
					),
		        )
		    ),
			'spacing_style_sec'     => array(
				'title'         => __('Spacing', 'uabb'),
				'fields'        => array(
					'cat_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Category Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce .uabb-woo-product-category',
							'unit'		=> 'px',
						)
					),
					'title_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Title Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce .woocommerce-loop-product__title',
							'unit'		=> 'px',
						)
					),
					'rating_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Rating Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce .star-rating',
							'unit'		=> 'px',
						)
					),
					'price_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Price Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce li.product .price',
							'unit'		=> 'px',
						)
					),
					'desc_margin_bottom'       => array(
						'type'          => 'unit',
						'label'         => __('Desc Margin Bottom', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-description',
							'unit'		=> 'px',
						)
					),
				)
			),
		)
	),
	'typography'    => array(
		'title'         => __('Typography', 'uabb'),
		'sections'      => array(
			'cat_typo'     => array(
				'title'         => __('Category', 'uabb'),
				'fields'        => array(
					'cat_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woocommerce .uabb-woo-product-category'
							
						)
					),
					'cat_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-woo-product-category',
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
					'cat_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-woo-product-category',
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
					'cat_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-woo-product-category'
						)
					),
                    'cat_transform'     => array(
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
                            'selector'      => '.uabb-woocommerce .uabb-woo-product-category',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'cat_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-woo-product-category',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'title_typo'     => array(
				'title'         => __('Title', 'uabb'),
				'fields'        => array(
					'title_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woocommerce .uabb-loop-product__link, .uabb-woocommerce .woocommerce-loop-product__title'
							
						)
					),
					'title_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .woocommerce-loop-product__title',
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
					'title_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .woocommerce-loop-product__title',
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
					'title_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .woocommerce-loop-product__title'
						)
					),
					'title_hover_color'    => array( 
						'type'       => 'color',
						'label'         => __('Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-loop-product__link:hover .woocommerce-loop-product__title'
						)
					),
                    'title_transform'     => array(
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
                            'selector'      => '.uabb-woocommerce .woocommerce-loop-product__title',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'title_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .woocommerce-loop-product__title',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'rating_typo'     => array(
				'title'         => __('Rating', 'uabb'),
				'fields'        => array(
					'rating_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .star-rating, .uabb-woocommerce .star-rating::before'
						)
					),
				)
			),
			'price_typo'     => array(
				'title'         => __('Price', 'uabb'),
				'fields'        => array(
					'price_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woocommerce li.product .price'
						)
					),
					'price_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce li.product .price',
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
					'price_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce li.product .price',
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
					'price_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce li.product .price'
						)
					),
                    'price_transform'     => array(
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
                            'selector'      => '.uabb-woocommerce li.product .price',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'price_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce li.product .price',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'desc_typo'     => array(
				'title'         => __('Short Description', 'uabb'),
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
							'selector'        => '.uabb-woocommerce .uabb-woo-products-description'
						)
					),
					'desc_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-description',
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
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-description',
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
					'desc_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-woo-products-description'
						)
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
                            'selector'      => '.uabb-woocommerce .uabb-woo-products-description',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'desc_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-woo-products-description',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'add_cart_typo'     => array(
				'title'         => __('Add to Cart', 'uabb'),
				'fields'        => array(
					'add_cart_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button'
							
						)
					),
					'add_cart_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-summary-wrap .button',
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
					'add_cart_line_height' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-woo-products-summary-wrap .button',
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
					'add_cart_color'    => array( 
						'type'       => 'color',
						'label'         => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button'
						)
					),
					'add_cart_hover_color'    => array( 
						'type'       => 'color',
						'label'         => __('Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button:hover'
						)
					),
					'add_cart_bg_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button'
						)
					),
					'add_cart_bg_hover_color'    => array( 
						'type'       => 'color',
						'label'         => __('Background Hover Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'background',
							'selector' => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button:hover'
						)
					),
                    'add_cart_transform'     => array(
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
                            'selector'      => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'add_cart_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-woo-products-summary-wrap .button',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'sale_flash_typo'     => array(
				'title'         => __('Sale Flash', 'uabb'),
				'fields'        => array(
					'sale_flash_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-sale-flash-wrap .uabb-onsale'
						)
					),
					'sale_flash_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-sale-flash-wrap .uabb-onsale',
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
                    'sale_flash_transform'     => array(
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
                            'selector'      => '.uabb-sale-flash-wrap .uabb-onsale',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'sale_flash_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-sale-flash-wrap .uabb-onsale',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			'featured_flash_typo'     => array(
				'title'         => __('Featured Flash', 'uabb'),
				'fields'        => array(
					'featured_flash_font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'uabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-woocommerce .uabb-featured'
						)
					),
					'featured_flash_font_size' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-woocommerce .uabb-featured',
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
                    'featured_flash_transform'     => array(
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
                            'selector'      => '.uabb-woocommerce .uabb-featured',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'featured_flash_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-woocommerce .uabb-featured',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
				)
			),
			/*'description_typo'    =>  array(
		        'title'		=> __('Description', 'uabb'),
		        'fields'    => array(
		            'desc_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'uabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.uabb-subheading, .uabb-subheading *'
						)
		            ),
					'desc_font_size_unit' => array(
						'type' => 'unit',
						'label' => __('Font Size', 'uabb'),
						'description' => 'px',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-subheading, .uabb-subheading *',
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
					'desc_line_height_unit' => array(
						'type' => 'unit',
						'label' => __('Line height', 'uabb'),
						'description' => 'em',
						'preview' => array(
							'type' 		=> 'css',
							'selector'	=> '.uabb-subheading, .uabb-subheading *',
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
		            'desc_color'        => array( 
						'type'       => 'color',
						'label'      => __('Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .uabb-subheading, .fl-module-content.fl-node-content .uabb-subheading *'
						)
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
                        ),                        'default'       => 'none',
                        'options'       => array(
                            'none'           =>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .uabb-subheading, .fl-module-content.fl-node-content .uabb-subheading *',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'desc_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .uabb-subheading, .fl-module-content.fl-node-content .uabb-subheading *',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    ),
					'desc_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'uabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.uabb-subheading',
							'unit'	=> 'px',
						)
					),
					'desc_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'uabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.uabb-subheading',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
			'separator_text_typography' => array(
		        'title'     => __('Separator Text Typography', 'uabb'),
		        'fields'    => array(
		            'separator_text_tag_selection'   => array(
		                'type'          => 'select',
		                'label'         => __('Text Tag', 'uabb'),
		                'default'       => 'h3',
		                'options'       => array(
		                    'h1'      => __('H1', 'uabb'),
		                    'h2'      => __('H2', 'uabb'),
		                    'h3'      => __('H3', 'uabb'),
		                    'h4'      => __('H4', 'uabb'),
		                    'h5'      => __('H5', 'uabb'),
		                    'h6'      => __('H6', 'uabb'),
		                    'div'     => __('Div', 'uabb'),
		                    'p'       => __('p', 'uabb'),
		                    'span'    => __('span', 'uabb'),
		                )
		            ),
		            'separator_text_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'uabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
                        'preview'         => array(
                            'type'            => 'font',
                            'selector'        => '.uabb-divider-text'
                        )
		            ),
		            'separator_text_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'uabb' ),
						'description' => 'px',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
	                  	'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.uabb-divider-text',
							'unit'		=> 'px',
						)
		            ),
		            'separator_text_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'uabb' ),
						'description' => 'em',
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.uabb-divider-text',
							'unit'		=> 'em',
						)
		            ),
		            'separator_text_color' => array( 
						'type'       => 'color',
						'label'      => __('Text Color', 'uabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'color',
							'selector'  => '.uabb-divider-text',
						)
					),
                    'separator_transform'     => array(
                        'type'          => 'select',
                        'label'         => __( 'Transform', 'uabb' ),
                        'default'       => 'none',
                        'options'       => array(
                            'none'           =>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),                        'default'       => 'none',
                        'options'       => array(
                            'none'           =>  'Default',
                            'uppercase'         =>  'UPPERCASE',
                            'lowercase'         =>  'lowercase',
                            'capitalize'        =>  'Capitalize'                 
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-divider-text',
                            'property'      => 'text-transform'
                        ),
                    ),
                    'separator_letter_spacing'       => array(
                        'type'          => 'unit',
                        'label'         => __('Letter Spacing', 'uabb'),
                        'placeholder'   => '0',
                        'size'          => '5',
                        'description'   => 'px',
                        'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.uabb-divider-text',
                            'property'      => 'letter-spacing',
                            'unit'          => 'px'
                        )
                    )
		        )
		    ),*/
		)
	)
));
