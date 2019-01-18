<?php

class BB_PowerPack_Ajax {

    /**
     * Initializes actions.
     *
     * @since 1.0.0
     * @return void
     */
    static public function init()
    {
		add_action( 'wp', 										__CLASS__ . '::get_ajax_posts' );
		add_action( 'pp_post_grid_ajax_before_query', 			__CLASS__ . '::loop_fake_actions' );
        add_action( 'wp_ajax_pp_get_taxonomies', 				__CLASS__ . '::get_post_taxonomies' );
		add_action( 'wp_ajax_nopriv_pp_get_taxonomies', 		__CLASS__ . '::get_post_taxonomies' );
		add_action( 'wp_ajax_pp_get_saved_templates', 			__CLASS__ . '::get_saved_templates' );
        add_action( 'wp_ajax_nopriv_pp_get_saved_templates', 	__CLASS__ . '::get_saved_templates' );
	}
	
	static public function loop_fake_actions() {
		add_action( 'loop_start', __CLASS__ . '::fake_loop_true');
		add_action( 'loop_end', __CLASS__ . '::fake_loop_false' );
	}

	static public function fake_loop_true() {
		global $wp_query;
		// Fake being in the loop.
		$wp_query->in_the_loop = true;
	}

	static public function fake_loop_false() {
		global $wp_query;
		// Stop faking being in the loop.
		$wp_query->in_the_loop = false;

		remove_action( 'loop_start', __CLASS__ . '::fake_loop_true' );
		remove_action( 'loop_end', __CLASS__ . '::fake_loop_false' );
	}

    static public function get_ajax_posts()
    {
		$is_error = false;

		if ( ! isset( $_POST['pp_action'] ) || 'get_ajax_posts' != $_POST['pp_action'] ) {
			return;
		}

		if ( ! isset( $_POST['settings'] ) || empty( $_POST['settings'] ) ) {
			return;
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

        $settings = (object)$_POST['settings'];
        $module_dir = pp_get_module_dir('pp-content-grid');
        $module_url = pp_get_module_url('pp-content-grid');

        $response = array(
            'data'  => '',
            'pagination' => false,
        );

        $post_type = $settings->post_type;

		global $wp_query;

        $args = array(
            'post_type'             => $post_type,
            'post_status'           => 'publish',
			'ignore_sticky_posts'   => true,
            'pp_content_grid'       => true,
        );

        // posts filter.
        if ( isset( $settings->{'posts_' . $post_type} ) ) {
            
            $ids = $settings->{'posts_' . $post_type};
            $arg = 'post__in';

            if ( isset( $settings->{'posts_' . $post_type . '_matching'} ) ) {
                if ( ! $settings->{'posts_' . $post_type . '_matching'} ) {
                    $arg = 'post__not_in';
                }
            }

            if ( ! empty( $ids ) ) {
                $args[$arg] = explode( ',', $ids );
            }
        }

        // author filter.
        if ( isset( $settings->users ) ) {
            
            $users = $settings->users;
            $arg = 'author__in';
            
            // Set to NOT IN if matching is present and set to 0.
			if ( isset( $settings->users_matching ) && ! $settings->users_matching ) {
				$arg = 'author__not_in';
            }

            if ( !empty( $users ) ) {
                if ( is_string( $users ) ) {
				    $users = explode( ',', $users );
                }
                
                $args[$arg] = $users;
            }
		}
		
		if ( isset( $_POST['author_id'] ) && ! empty( $_POST['author_id'] ) ) {
			$args['author__in'] = array( absint( $_POST['author_id'] ) );
		}

        if ( isset( $settings->posts_per_page ) ) {
            $args['posts_per_page'] = $settings->posts_per_page;
        }

        if ( isset( $settings->post_grid_filters ) && 'none' != $settings->post_grid_filters && isset( $_POST['term'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $settings->post_grid_filters,
                    'field'    => 'slug',
                    'terms'    => $_POST['term']
                )
            );
        } else if ( isset( $_POST['taxonomy'] ) && isset( $_POST['term'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $_POST['taxonomy'],
                    'field'    => 'slug',
                    'terms'    => $_POST['term']
                )
            );
		}
		
		$taxonomies = FLBuilderLoop::taxonomies( $post_type );

		foreach ( $taxonomies as $tax_slug => $tax ) {

			$tax_value = '';
			$term_ids  = array();
			$operator  = 'IN';

			// Get the value of the suggest field.
			if ( isset( $settings->{'tax_' . $post_type . '_' . $tax_slug} ) ) {
				// New style slug.
				$tax_value = $settings->{'tax_' . $post_type . '_' . $tax_slug};
			} elseif ( isset( $settings->{'tax_' . $tax_slug} ) ) {
				// Old style slug for backwards compat.
				$tax_value = $settings->{'tax_' . $tax_slug};
			}

			// Get the term IDs array.
			if ( ! empty( $tax_value ) ) {
				$term_ids = explode( ',', $tax_value );
			}

			// Handle matching settings.
			if ( isset( $settings->{'tax_' . $post_type . '_' . $tax_slug . '_matching'} ) ) {

				$tax_matching = $settings->{'tax_' . $post_type . '_' . $tax_slug . '_matching'};

				if ( ! $tax_matching ) {
					// Do not match these terms.
					$operator = 'NOT IN';
				} elseif ( 'related' === $tax_matching ) {
					// Match posts by related terms from the global post.
					global $post;
					$terms 	 = wp_get_post_terms( $post->ID, $tax_slug );
					$related = array();

					foreach ( $terms as $term ) {
						if ( ! in_array( $term->term_id, $term_ids ) ) {
							$related[] = $term->term_id;
						}
					}

					if ( empty( $related ) ) {
						// If no related terms, match all except those in the suggest field.
						$operator = 'NOT IN';
					} else {

						// Don't include posts with terms selected in the suggest field.
						$args['tax_query'][] = array(
							'taxonomy'	=> $tax_slug,
							'field'		=> 'id',
							'terms'		=> $term_ids,
							'operator'  => 'NOT IN',
						);

						// Set the term IDs to the related terms.
						$term_ids = $related;
					}
				}
			}// End if().

			if ( ! empty( $term_ids ) ) {

				$args['tax_query'][] = array(
					'taxonomy'	=> $tax_slug,
					'field'		=> 'id',
					'terms'		=> $term_ids,
					'operator'  => $operator,
				);
			}
		}// End foreach().

        if ( 'yes' == get_option( 'woocommerce_hide_out_of_stock_items' ) && 'product' == $post_type ) {
            $args['meta_query'][] = array(
                'key'       => '_stock_status',
                'value'     => 'instock',
                'compare'   => '='
            );
		}
		
		if ( isset( $_POST['page'] ) ) {
            $args['paged'] = absint( $_POST['page'] );
        }
		
		// Order by author
		if ( 'author' == $settings->order_by ) {
			$args['orderby'] = array(
				'author' => $settings->order,
				'date' => $settings->order,
			);
		} else {
			$args['orderby'] = $settings->order_by;

			// Order by meta value arg.
			if ( strstr( $settings->order_by, 'meta_value' ) ) {
				$args['meta_key'] = $settings->order_by_meta_key;
			}

			if ( isset( $_POST['orderby'] ) ) {
				$orderby = esc_attr( $_POST['orderby'] );
				
				$args = self::get_conditional_args( $orderby, $args );
			}
			
			if ( isset( $settings->order ) ) {
				$args['order'] = $settings->order;
			}
		}

		$args = apply_filters( 'pp_post_grid_ajax_query_args', $args );

		do_action( 'pp_post_grid_ajax_before_query', $settings );
		
		$query = new WP_Query( $args );

		do_action( 'pp_post_grid_ajax_after_query', $settings );

        if ( $query->have_posts() ) :

            // create pagination.
            if ( $query->max_num_pages > 1 && 'none' != $settings->pagination ) {
				$style = ( 'scroll' == $settings->pagination ) ? ' style="display: none;"' : '';
                ob_start();
               
				echo '<div class="pp-content-grid-pagination pp-ajax-pagination fl-builder-pagination"' . $style . '>';
				if ( 'scroll' == $settings->pagination && isset( $_POST['term'] ) ) {
					BB_PowerPack_Post_Helper::ajax_pagination( $query, $settings, $_POST['current_page'], $_POST['page'], $_POST['term'], $_POST['node_id'] );
				} else {
					BB_PowerPack_Post_Helper::ajax_pagination( $query, $settings, $_POST['current_page'], $_POST['page'] );
				}
                echo '</div>';

                $response['pagination'] = ob_get_clean();
            }

            // posts query.
            while( $query->have_posts() ) {

				$query->the_post();

				$terms_list = wp_get_post_terms( get_the_ID(), $settings->post_taxonomies );
				$post_id = get_the_ID();
				$permalink = get_permalink();
                
                ob_start();

				if ( 'custom' == $settings->post_grid_style_select ) {
					include BB_POWERPACK_DIR . 'includes/post-module-layout.php';
				} else {
					include apply_filters( 'pp_cg_module_layout_path', $module_dir . 'includes/post-grid.php', $settings->layout, $settings );	
				}

                $response['data'] .= do_shortcode( ob_get_clean() );
            }
            
            wp_reset_postdata();

        else :
            $response['data'] = '<div>' . esc_html__('No posts found.', 'bb-powerpack') . '</div>';
        endif;

        wp_reset_query();

        wp_send_json( $response );
    }

    static public function get_conditional_args( $type, $args )
    {
        switch ( $type ) :
            case 'date':
                $args['orderby'] = 'date ID';
                $args['order'] = 'DESC';
                break;

            case 'price':
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                $args['orderby'] = 'meta_value_num';
                break;

            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
                $args['orderby'] = 'meta_value_num';
                break;

            default:
                break;

        endswitch;

        return $args;
    }

    /**
     * Get taxonomies
     */
    static public function get_post_taxonomies( $post_type = 'post' )
	{
		if ( isset( $_POST['post_type'] ) && ! empty( $_POST['post_type'] ) ) {
			$post_type = sanitize_text_field( $_POST['post_type'] );	
		}
		
		$taxonomies = FLBuilderLoop::taxonomies( $post_type );
		$html = '';

		foreach ( $taxonomies as $tax_slug => $tax ) {
			$html .= '<option value="'.$tax_slug.'">'.$tax->label.'</option>';
		}

        echo $html; die();
	}

	/**
	 * Get saved templates.
	 *
	 * @since 1.4
	 */
	static public function get_saved_templates()
    {
		$response = array(
			'success' => false,
			'data'	=> array()
		);

		$args = array(
			'post_type' 		=> 'fl-builder-template',
			'orderby' 			=> 'title',
			'order' 			=> 'ASC',
			'posts_per_page' 	=> '-1',
		);

		if ( isset( $_POST['type'] ) && ! empty( $_POST['type'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'		=> 'fl-builder-template-type',
					'field'			=> 'slug',
					'terms'			=> $_POST['type']
				)
			);
		}

        $posts = get_posts( $args );

		$options = '';

        if ( count( $posts ) ) {
            foreach ( $posts as $post ) {
				$options .= '<option value="' . $post->ID . '">' . $post->post_title . '</option>';
			}
			
			$response = array(
				'success' => true,
				'data' => $options
			);
        } else {
			$response = array(
				'success' => true,
				'data' => '<option value="" disabled>' . __('No templates found!') . '</option>'
			);
		}

		echo json_encode($response); die;
    }
}

BB_PowerPack_Ajax::init();
