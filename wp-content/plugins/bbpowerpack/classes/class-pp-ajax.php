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
        add_action( 'wp_ajax_pp_grid_get_posts', __CLASS__ . '::get_ajax_posts' );
        add_action( 'wp_ajax_nopriv_pp_grid_get_posts', __CLASS__ . '::get_ajax_posts' );
        add_action( 'wp_ajax_pp_get_taxonomies', __CLASS__ . '::get_post_taxonomies' );
        add_action( 'wp_ajax_nopriv_pp_get_taxonomies', __CLASS__ . '::get_post_taxonomies' );
    }

    static public function get_ajax_posts()
    {
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

        if ( isset( $settings->posts_per_page ) ) {
            $args['posts_per_page'] = $settings->posts_per_page;
        }

        if ( isset( $settings->post_grid_filters ) && isset( $_POST['term'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $settings->post_grid_filters,
                    'field'    => 'slug',
                    'terms'    => $_POST['term']
                )
            );
        } else if ( isset( $settings->offset ) ) {
            //$args['offset'] = $settings->offset;
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

        if ( isset( $settings->order_by ) ) {
            $args['orderby'] = $settings->order_by;
        }

        if ( isset( $_POST['orderby'] ) ) {
            $orderby = esc_attr( $_POST['orderby'] );
            
            $args = self::get_conditional_args( $orderby, $args );
		}
		
		if ( isset( $settings->order ) ) {
            $args['order'] = $settings->order;
        }

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :

            // create pagination.
            if ( $query->max_num_pages > 1 && 'none' != $settings->pagination ) {
				$style = ( 'scroll' == $settings->pagination ) ? ' style="display: none;"' : '';
                ob_start();
               
				echo '<div class="pp-content-grid-pagination pp-ajax-pagination fl-builder-pagination"' . $style . '>';
				if ( 'scroll' == $settings->pagination && isset( $_POST['term'] ) ) {
					BB_PowerPack_Post_Helper::ajax_pagination( $query, $_POST['current_page'], $_POST['page'], $_POST['term'], $_POST['node_id'] );
				} else {
					BB_PowerPack_Post_Helper::ajax_pagination( $query, $_POST['current_page'], $_POST['page'] );
				}
                echo '</div>';

                $response['pagination'] = ob_get_clean();
            }

            // posts query.
            while( $query->have_posts() ) {

                $query->the_post();

                $terms_list = wp_get_post_terms( get_the_id(), $settings->post_taxonomies );
                
                ob_start();

                include apply_filters( 'pp_cg_module_layout_path', $module_dir . 'includes/post-grid.php', $settings->layout, $settings );

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
		$taxonomies = FLBuilderLoop::taxonomies( $post_type );
		$html = '';

		foreach ( $taxonomies as $tax_slug => $tax ) {
			$html .= '<option value="'.$tax_slug.'">'.$tax->label.'</option>';
		}

        echo $html; die();
    }
}

BB_PowerPack_Ajax::init();
