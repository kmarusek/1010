<?php
/**
 *  UABB Flip Box Module file
 *
 *  @package UABB Flip Box Module
 */

/**
 * Function that initializes UABB Flip Box Module
 *
 * @class FlipBoxModule
 */
class FlipBoxModule extends FLBuilderModule {
	/**
	 * Constructor function that constructs default values for the Flip Box Module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Flip Box', 'uabb' ),
				'description'     => __( 'Flip Box', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$creative_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/flip-box/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/flip-box/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'flip-box.svg',
			)
		);
		$this->add_css( 'font-awesome' );

	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *
	 * @since 1.14.0
	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		$version_bb_check        = UABB_Compatibility::check_bb_version();
		$page_migrated           = UABB_Compatibility::check_old_page_migration();
		$stable_version_new_page = UABB_Compatibility::check_stable_version_new_page();

		if ( $version_bb_check && ( 'yes' == $page_migrated || 'yes' == $stable_version_new_page ) ) {

			// Handle opacity fields.
			$helper->handle_opacity_inputs( $settings, 'back_background_color_opc', 'back_background_color' );
			$helper->handle_opacity_inputs( $settings, 'front_background_color_opc', 'front_background_color' );

			// Handle old front border settings.
			if ( isset( $settings->front_border_color ) ) {
				$settings->front_border = array();

				// Border style, color, and width.
				if ( isset( $settings->front_box_border_style ) ) {
					$settings->front_border['style'] = $settings->front_box_border_style;
				}
				if ( isset( $settings->front_border_color ) ) {
					$settings->front_border['color'] = $settings->front_border_color;
				}
				if ( isset( $settings->front_border_size ) ) {
					$settings->front_border['width'] = array(
						'top'    => $settings->front_border_size,
						'right'  => $settings->front_border_size,
						'bottom' => $settings->front_border_size,
						'left'   => $settings->front_border_size,
					);
				}
			}
			unset( $settings->front_border_size );
			unset( $settings->front_border_color );
			unset( $settings->front_box_border_style );
			// Handle old back border settings.
			if ( isset( $settings->back_border_color ) ) {
				$settings->back_border = array();

				// Border style, color, and width.
				if ( isset( $settings->back_border_size ) ) {
					$settings->back_border['width'] = array(
						'top'    => $settings->back_border_size,
						'right'  => $settings->back_border_size,
						'bottom' => $settings->back_border_size,
						'left'   => $settings->back_border_size,
					);
				}
				if ( isset( $settings->back_box_border_style ) ) {
					$settings->back_border['style'] = $settings->back_box_border_style;
				}
				if ( isset( $settings->back_border_color ) ) {
					$settings->back_border['color'] = $settings->back_border_color;
				}
			}
			unset( $settings->back_border_size );
			unset( $settings->back_box_border_style );
			unset( $settings->back_border_color );
			// compatibility for front title.
			if ( ! isset( $settings->front_title_font_typo ) || ! is_array( $settings->front_title_font_typo ) ) {

				$settings->front_title_font_typo            = array();
				$settings->front_title_font_typo_medium     = array();
				$settings->front_title_font_typo_responsive = array();
			}
			if ( isset( $settings->front_title_typography_font_family ) ) {
				if ( isset( $settings->front_title_typography_font_family['weight'] ) ) {

					if ( 'regular' == $settings->front_title_typography_font_family['weight'] ) {
						$settings->front_title_font_typo['font_weight'] = 'normal';
					} else {
						$settings->front_title_font_typo['font_weight'] = $settings->front_title_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->front_title_typography_font_family['family'] ) ) {
					$settings->front_title_font_typo['font_family'] = $settings->front_title_typography_font_family['family'];
				}
			}
			if ( isset( $settings->front_title_typography_font_size_unit ) ) {

				$settings->front_title_font_typo['font_size'] = array(
					'length' => $settings->front_title_typography_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_font_size_unit_medium ) ) {

				$settings->front_title_font_typo_medium['font_size'] = array(
					'length' => $settings->front_title_typography_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_font_size_unit_responsive ) ) {

				$settings->front_title_font_typo_responsive['font_size'] = array(
					'length' => $settings->front_title_typography_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_line_height_unit ) ) {

				$settings->front_title_font_typo['line_height'] = array(
					'length' => $settings->front_title_typography_line_height_unit,
					'unit'   => 'em',
				);

			}
			if ( isset( $settings->front_title_typography_line_height_unit_medium ) ) {
				$settings->front_title_font_typo_medium['line_height'] = array(
					'length' => $settings->front_title_typography_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_typography_line_height_unit_responsive ) ) {
					$settings->front_title_font_typo_responsive['line_height'] = array(
						'length' => $settings->front_title_typography_line_height_unit_responsive,
						'unit'   => 'em',
					);
			}
			if ( isset( $settings->front_title_typography_transform ) ) {

				$settings->front_title_font_typo['text_transform'] = $settings->front_title_typography_transform;

			}
			if ( isset( $settings->front_title_typography_letter_spacing ) ) {

				$settings->front_title_font_typo['letter_spacing'] = array(
					'length' => $settings->front_title_typography_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Unset the old values.
			if ( isset( $settings->front_title_typography_font_family ) ) {
				unset( $settings->front_title_typography_font_family );
				unset( $settings->front_title_typography_font_size_unit );
				unset( $settings->front_title_typography_line_height_unit );
				unset( $settings->front_title_typography_transform );
				unset( $settings->front_title_typography_letter_spacing );
			}

			// compatibility for Front Description.
			if ( ! isset( $settings->front_desk_font_typo ) || ! is_array( $settings->front_desk_font_typo ) ) {

				$settings->front_desk_font_typo            = array();
				$settings->front_desk_font_typo_medium     = array();
				$settings->front_desk_font_typo_responsive = array();
			}
			if ( isset( $settings->front_desc_typography_font_family ) ) {
				if ( isset( $settings->front_desc_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->front_desc_typography_font_family['weight'] ) {
						$settings->front_desk_font_typo['font_weight'] = 'normal';
					} else {
						$settings->front_desk_font_typo['font_weight'] = $settings->front_desc_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->front_desc_typography_font_family['family'] ) ) {
					$settings->front_desk_font_typo['font_family'] = $settings->front_desc_typography_font_family['family'];
				}
			}
			if ( isset( $settings->front_desc_typography_font_size_unit ) ) {

				$settings->front_desk_font_typo['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_font_size_unit_medium ) ) {

				$settings->front_desk_font_typo_medium['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_font_size_unit_responsive ) ) {

				$settings->front_desk_font_typo_responsive['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height_unit ) ) {

				$settings->front_desk_font_typo['line_height'] = array(
					'length' => $settings->front_desc_typography_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height_unit_medium ) ) {
				$settings->front_desk_font_typo_medium['line_height'] = array(
					'length' => $settings->front_desc_typography_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height_unit_responsive ) ) {

				$settings->front_desk_font_typo_responsive['line_height'] = array(
					'length' => $settings->front_desc_typography_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_transform ) ) {

				$settings->front_desk_font_typo['text_transform'] = $settings->front_desc_transform;

			}
			if ( isset( $settings->front_desc_letter_spacing ) ) {

				$settings->front_desk_font_typo['letter_spacing'] = array(
					'length' => $settings->front_desc_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Unset the old values.
			if ( isset( $settings->front_desc_typography_font_family ) ) {
				unset( $settings->front_desc_typography_font_family );
				unset( $settings->front_desc_typography_font_size_unit );
				unset( $settings->front_desc_typography_line_height_unit );
				unset( $settings->front_desc_transform );
				unset( $settings->front_desc_letter_spacing );
			}

			// compatibility for Back title.
			if ( ! isset( $settings->back_title_font_typo ) || ! is_array( $settings->back_title_font_typo ) ) {

				$settings->back_title_font_typo            = array();
				$settings->back_title_font_typo_medium     = array();
				$settings->back_title_font_typo_responsive = array();
			}
			if ( isset( $settings->back_title_typography_font_family ) ) {
				if ( isset( $settings->back_title_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->back_title_typography_font_family['weight'] ) {
						$settings->back_title_font_typo['font_weight'] = 'normal';
					} else {

						$settings->back_title_font_typo['font_weight'] = $settings->back_title_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->back_title_typography_font_family['family'] ) ) {
					$settings->back_title_font_typo['font_family'] = $settings->back_title_typography_font_family['family'];
				}
			}
			if ( isset( $settings->back_title_typography_font_size_unit ) ) {

				$settings->back_title_font_typo['font_size'] = array(
					'length' => $settings->back_title_typography_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_font_size_unit_medium ) ) {

				$settings->back_title_font_typo_medium['font_size'] = array(
					'length' => $settings->back_title_typography_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_font_size_unit_responsive ) ) {

				$settings->back_title_font_typo_responsive['font_size'] = array(
					'length' => $settings->back_title_typography_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_line_height_unit ) ) {

				$settings->back_title_font_typo['line_height'] = array(
					'length' => $settings->back_title_typography_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_typography_line_height_unit_medium ) ) {

				$settings->back_title_font_typo_medium['line_height'] = array(
					'length' => $settings->back_title_typography_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_typography_line_height_unit_responsive ) ) {

				$settings->back_title_font_typo_responsive['line_height'] = array(
					'length' => $settings->back_title_typography_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_transform ) ) {

				$settings->back_title_font_typo['text_transform'] = $settings->back_title_transform;

			}
			if ( isset( $settings->back_title_letter_spacing ) ) {

				$settings->back_title_font_typo['letter_spacing'] = array(
					'length' => $settings->back_title_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Unset the old values.
			if ( isset( $settings->back_title_typography_font_family ) ) {
				unset( $settings->back_title_typography_font_family );
				unset( $settings->back_title_typography_font_size_unit );
				unset( $settings->back_title_typography_line_height_unit );
				unset( $settings->back_title_transform );
				unset( $settings->back_title_letter_spacing );
			}

			// compatibility for Back description.
			if ( ! isset( $settings->back_desc_font_typo ) || ! is_array( $settings->back_desc_font_typo ) ) {

				$settings->back_desc_font_typo            = array();
				$settings->back_desc_font_typo_medium     = array();
				$settings->back_desc_font_typo_responsive = array();
			}
			if ( isset( $settings->back_desc_typography_font_family ) ) {
				if ( isset( $settings->back_desc_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->back_desc_typography_font_family['weight'] ) {
						$settings->back_desc_font_typo['font_weight'] = 'normal';
					} else {

						$settings->back_desc_font_typo['font_weight'] = $settings->back_desc_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->back_desc_typography_font_family['family'] ) ) {
					$settings->back_desc_font_typo['font_family'] = $settings->back_desc_typography_font_family['family'];
				}
			}
			if ( isset( $settings->back_desc_typography_font_size_unit ) ) {

				$settings->back_desc_font_typo['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_font_size_unit_medium ) ) {

				$settings->back_desc_font_typo_medium['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_font_size_unit_responsive ) ) {
				$settings->back_desc_font_typo_responsive['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height_unit ) ) {

				$settings->back_desc_font_typo['line_height'] = array(
					'length' => $settings->back_desc_typography_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height_unit_medium ) ) {

				$settings->back_desc_font_typo_medium['line_height'] = array(
					'length' => $settings->back_desc_typography_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height_unit_responsive ) ) {

				$settings->back_desc_font_typo_responsive['line_height'] = array(
					'length' => $settings->back_desc_typography_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_transform ) ) {

				$settings->back_desc_font_typo['text_transform'] = $settings->back_desc_transform;

			}
			if ( isset( $settings->back_desc_letter_spacing ) ) {

				$settings->back_desc_font_typo['letter_spacing'] = array(
					'length' => $settings->back_desc_letter_spacing,
					'unit'   => 'px',
				);
			}
			if ( ! isset( $settings->button->button_typo ) || ! is_object( $settings->button->button_typo ) ) {
				$settings->button->button_typo            = new stdClass();
				$settings->button->button_typo_medium     = new stdClass();
				$settings->button->button_typo_responsive = new stdClass;
			}
			if ( isset( $settings->button->font_family ) ) {
				if ( isset( $settings->button->font_family->weight ) ) {
					if ( 'regular' == $settings->button->font_family->weight ) {
						$settings->button->button_typo->font_weight = 'normal';
					} else {

						$settings->button->button_typo->font_weight = $settings->button->font_family->weight;
					}
				}
				if ( isset( $settings->button->font_family->family ) ) {
					$settings->button->button_typo->font_family = $settings->button->font_family->family;
				}
			}
			if ( isset( $settings->button->font_size_unit ) ) {
				$settings->button->button_typo->font_size = (object) array(
					'length' => $settings->button->font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->font_size_unit_medium ) ) {
				$settings->button->button_typo_medium->font_size = (object) array(
					'length' => $settings->button->font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->font_size_unit_responsive ) ) {

				$settings->button->button_typo_responsive->font_size = (object) array(
					'length' => $settings->button->font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->line_height_unit ) ) {

				$settings->button->button_typo->line_height = (object) array(
					'length' => $settings->button->line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->line_height_unit_medium ) ) {
				$settings->button->button_typo_medium->line_height = (object) array(
					'length' => $settings->button->line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->line_height_unit_responsive ) ) {

				$settings->button->button_typo_responsive->line_height = (object) array(
					'length' => $settings->button->line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->transform ) ) {
				$settings->button->button_typo->text_transform = $settings->button->transform;
			}
			if ( isset( $settings->button->letter_spacing ) ) {
				$settings->button->button_typo->letter_spacing = (object) array(
					'length' => $settings->button->letter_spacing,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->font_family ) ) {
				unset( $settings->button->font_family );
				unset( $settings->button->font_size_unit );
				unset( $settings->button->line_height_unit );
				unset( $settings->button->transform );
				unset( $settings->button->letter_spacing );
			}
			// Unset the old values.
			if ( isset( $settings->back_desc_typography_font_family ) ) {
				unset( $settings->back_desc_typography_font_family );
				unset( $settings->back_desc_typography_font_size_unit );
				unset( $settings->back_desc_typography_line_height_unit );
				unset( $settings->back_desc_transform );
				unset( $settings->back_desc_letter_spacing );
			}
		} elseif ( $version_bb_check && 'yes' != $page_migrated ) {

			// Handle opacity fields.
			$helper->handle_opacity_inputs( $settings, 'back_background_color_opc', 'back_background_color' );
			$helper->handle_opacity_inputs( $settings, 'front_background_color_opc', 'front_background_color' );

			// Handle old front border settings.
			if ( isset( $settings->back_border_color ) ) {
				$settings->back_border = array();

				// Border style, color, and width.
				if ( isset( $settings->back_border_size ) ) {
					$settings->back_border['width'] = array(
						'top'    => $settings->back_border_size,
						'right'  => $settings->back_border_size,
						'bottom' => $settings->back_border_size,
						'left'   => $settings->back_border_size,
					);
				}
				if ( isset( $settings->back_box_border_style ) ) {
					$settings->back_border['style'] = $settings->back_box_border_style;
				}
				if ( isset( $settings->back_border_color ) ) {
					$settings->back_border['color'] = $settings->back_border_color;
				}
			}
			unset( $settings->back_border_size );
			unset( $settings->back_box_border_style );
			unset( $settings->back_border_color );

			// Handle old back border settings.
			if ( isset( $settings->back_border_color ) ) {
				$settings->back_border = array();

				// Border style, color, and width.
				if ( isset( $settings->back_border_size ) ) {
					$settings->back_border['width'] = array(
						'top'    => $settings->back_border_size,
						'right'  => $settings->back_border_size,
						'bottom' => $settings->back_border_size,
						'left'   => $settings->back_border_size,
					);
				}
				if ( isset( $settings->back_box_border_style ) ) {
					$settings->back_border['style'] = $settings->back_box_border_style;
				}
				if ( isset( $settings->back_border_color ) ) {
					$settings->back_border['color'] = $settings->back_border_color;
				}
			}
			unset( $settings->back_border_size );
			unset( $settings->back_box_border_style );
			unset( $settings->back_border_color );

			// Handle padding dimension field.
			if ( isset( $settings->inner_padding ) ) {

				$value = '';
				$value = str_replace( 'px', '', $settings->inner_padding );

				$output       = array();
				$uabb_default = array_filter( preg_split( '/\s*;\s*/', $value ) );

				$settings->inner_padding_dimension_top    = '';
				$settings->inner_padding_dimension_bottom = '';
				$settings->inner_padding_dimension_left   = '';
				$settings->inner_padding_dimension_right  = '';

				foreach ( $uabb_default as $val ) {
					$new      = explode( ':', $val );
					$output[] = $new;
				}
				for ( $i = 0; $i < count( $output ); $i++ ) {
					switch ( $output[ $i ][0] ) {
						case 'padding-top':
							$settings->inner_padding_dimension_top = (int) $output[ $i ][1];
							break;
						case 'padding-bottom':
							$settings->inner_padding_dimension_bottom = (int) $output[ $i ][1];
							break;
						case 'padding-right':
							$settings->inner_padding_dimension_right = (int) $output[ $i ][1];
							break;
						case 'padding-left':
							$settings->inner_padding_dimension_left = (int) $output[ $i ][1];
							break;
						case 'padding':
							$settings->inner_padding_dimension_top    = (int) $output[ $i ][1];
							$settings->inner_padding_dimension_bottom = (int) $output[ $i ][1];
							$settings->inner_padding_dimension_left   = (int) $output[ $i ][1];
							$settings->inner_padding_dimension_right  = (int) $output[ $i ][1];
							break;
					}
				}
			}
			// For front title typography settings.
			if ( ! isset( $settings->front_title_font_typo ) || ! is_array( $settings->front_title_font_typo ) ) {

				$settings->front_title_font_typo            = array();
				$settings->front_title_font_typo_medium     = array();
				$settings->front_title_font_typo_responsive = array();
			}
			if ( isset( $settings->front_title_typography_font_family ) && '' !== $settings->front_title_typography_font_family ) {
				if ( isset( $settings->front_title_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->front_title_typography_font_family['weight'] ) {
						$settings->front_title_font_typo['font_weight'] = 'normal';
					} else {
						$settings->front_title_font_typo['font_weight'] = $settings->front_title_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->front_title_typography_font_family['family'] ) ) {
					$settings->front_title_font_typo['font_family'] = $settings->front_title_typography_font_family['family'];
				}
			}
			if ( isset( $settings->front_title_typography_font_size['desktop'] ) && ! isset( $settings->front_title_font_typo['font_size'] ) ) {

				$settings->front_title_font_typo['font_size'] = array(
					'length' => $settings->front_title_typography_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_font_size['medium'] ) && ! isset( $settings->front_title_font_typo_medium['font_size'] ) ) {
				$settings->front_title_font_typo_medium['font_size'] = array(
					'length' => $settings->front_title_typography_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_font_size['small'] ) && ! isset( $settings->front_title_font_typo_responsive['font_size'] ) ) {
				$settings->front_title_font_typo_responsive['font_size'] = array(
					'length' => $settings->front_title_typography_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_typography_line_height['desktop'] ) && isset( $settings->front_title_typography_font_size['desktop'] ) && 0 != $settings->front_title_typography_font_size['desktop'] && ! isset( $settings->front_title_typography_line_height_unit ) ) {

				$settings->front_title_font_typo['line_height'] = array(
					'length' => round( $settings->front_title_typography_line_height['desktop'] / $settings->front_title_typography_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_typography_line_height['medium'] ) && isset( $settings->front_title_typography_font_size['medium'] ) && 0 != $settings->front_title_typography_font_size['medium'] && ! isset( $settings->front_title_typography_line_height_unit_medium ) ) {
				$settings->front_title_font_typo_medium['line_height'] = array(
					'length' => round( $settings->front_title_typography_line_height['medium'] / $settings->front_title_typography_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_typography_line_height['small'] ) && isset( $settings->front_title_typography_font_size['small'] ) && 0 != $settings->front_title_typography_font_size['small'] && ! isset( $settings->front_title_typography_line_height_unit_responsive ) ) {
				$settings->front_title_font_typo_responsive['line_height'] = array(
					'length' => round( $settings->front_title_typography_line_height['small'] / $settings->front_title_typography_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// Unset the previous values.
			if ( isset( $settings->front_title_typography_font_family ) ) {
				unset( $settings->front_title_typography_font_family );
				unset( $settings->front_title_typography_font_size );
				unset( $settings->front_title_typography_line_height );
			}

			// For front description typography settings.
			if ( ! isset( $settings->front_desk_font_typo ) || ! is_array( $settings->front_desk_font_typo ) ) {

				$settings->front_desk_font_typo            = array();
				$settings->front_desk_font_typo_medium     = array();
				$settings->front_desk_font_typo_responsive = array();
			}
			if ( isset( $settings->front_desc_typography_font_family ) && '' !== $settings->front_desc_typography_font_family ) {
				if ( isset( $settings->front_desc_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->front_desc_typography_font_family['weight'] ) {
						$settings->front_desk_font_typo['font_weight'] = 'normal';
					} else {
						$settings->front_desk_font_typo['font_weight'] = $settings->front_desc_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->front_desc_typography_font_family['family'] ) ) {
					$settings->front_desk_font_typo['font_family'] = $settings->front_desc_typography_font_family['family'];
				}
			}
			if ( isset( $settings->front_desc_typography_font_size['desktop'] ) && ! isset( $settings->front_desk_font_typo['font_size'] ) ) {

				$settings->front_desk_font_typo['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_font_size['medium'] ) && ! isset( $settings->front_desk_font_typo_medium['font_size'] ) ) {
				$settings->front_desk_font_typo_medium['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_font_size['small'] ) && ! isset( $settings->front_desk_font_typo_responsive['font_size'] ) ) {
				$settings->front_desk_font_typo_responsive['font_size'] = array(
					'length' => $settings->front_desc_typography_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height['desktop'] ) && isset( $settings->front_desc_typography_font_size['desktop'] ) && 0 != $settings->front_desc_typography_font_size['desktop'] && ! isset( $settings->front_desc_typography_line_height_unit ) ) {

				$settings->front_desk_font_typo['line_height'] = array(
					'length' => round( $settings->front_desc_typography_line_height['desktop'] / $settings->front_desc_typography_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height['medium'] ) && isset( $settings->front_desc_typography_font_size['medium'] ) && 0 != $settings->front_desc_typography_font_size['medium'] && ! isset( $settings->front_desc_typography_line_height_unit_medium ) ) {
				$settings->front_desk_font_typo_medium['line_height'] = array(
					'length' => round( $settings->front_desc_typography_line_height['medium'] / $settings->front_desc_typography_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_typography_line_height['small'] ) && isset( $settings->front_desc_typography_font_size['small'] ) && 0 != $settings->front_desc_typography_font_size['small'] && ! isset( $settings->front_desc_typography_line_height_unit_responsive ) ) {
				$settings->front_desk_font_typo_responsive['line_height'] = array(
					'length' => round( $settings->front_desc_typography_line_height['small'] / $settings->front_desc_typography_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// Unset the previous values.
			if ( isset( $settings->front_desc_typography_font_family ) ) {
				unset( $settings->front_desc_typography_font_family );
				unset( $settings->front_desc_typography_font_size );
				unset( $settings->front_desc_typography_line_height );
			}

			// For back title typography settings.
			if ( ! isset( $settings->back_title_font_typo ) || ! is_array( $settings->back_title_font_typo ) ) {

				$settings->back_title_font_typo            = array();
				$settings->back_title_font_typo_medium     = array();
				$settings->back_title_font_typo_responsive = array();
			}
			if ( isset( $settings->back_title_typography_font_family ) && '' !== $settings->back_title_typography_font_family ) {
				if ( isset( $settings->back_title_typography_font_family['weight'] ) ) {
					if ( 'regular' == $settings->back_title_typography_font_family['weight'] ) {
						$settings->back_title_font_typo['font_weight'] = 'normal';
					} else {

						$settings->back_title_font_typo['font_weight'] = $settings->back_title_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->back_title_typography_font_family['family'] ) ) {

					$settings->back_title_font_typo['font_family'] = $settings->back_title_typography_font_family['family'];
				}
			}
			if ( isset( $settings->back_title_typography_font_size['desktop'] ) && ! isset( $settings->back_title_font_typo['font_size'] ) ) {

				$settings->back_title_font_typo['font_size'] = array(
					'length' => $settings->back_title_typography_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_font_size['medium'] ) && ! isset( $settings->back_title_font_typo_medium['font_size'] ) ) {
				$settings->back_title_font_typo_medium['font_size'] = array(
					'length' => $settings->back_title_typography_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_font_size['small'] ) && ! isset( $settings->back_title_font_typo_responsive['font_size'] ) ) {
				$settings->back_title_font_typo_responsive['font_size'] = array(
					'length' => $settings->back_title_typography_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_typography_line_height['desktop'] ) && isset( $settings->back_title_typography_font_size['desktop'] ) && 0 != $settings->back_title_typography_font_size['desktop'] && ! isset( $settings->back_title_typography_line_height_unit ) ) {

				$settings->back_title_font_typo['line_height'] = array(
					'length' => round( $settings->back_title_typography_line_height['desktop'] / $settings->back_title_typography_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_typography_line_height['medium'] ) && isset( $settings->back_title_typography_font_size['medium'] ) && 0 != $settings->back_title_typography_font_size['medium'] && ! isset( $settings->back_title_typography_line_height_unit_medium ) ) {
				$settings->back_title_font_typo_medium['line_height'] = array(
					'length' => round( $settings->back_title_typography_line_height['medium'] / $settings->back_title_typography_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_typography_line_height['small'] ) && isset( $settings->back_title_typography_font_size['small'] ) && 0 != $settings->back_title_typography_font_size['small'] && ! isset( $settings->back_title_typography_line_height_unit_responsive ) ) {
				$settings->back_title_font_typo_responsive['line_height'] = array(
					'length' => round( $settings->back_title_typography_line_height['small'] / $settings->back_title_typography_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// Unset the previous values.
			if ( isset( $settings->back_title_typography_font_family ) ) {
				unset( $settings->back_title_typography_font_family );
				unset( $settings->back_title_typography_font_size );
				unset( $settings->back_title_typography_line_height );
			}

			// For back description typography settings.
			if ( ! isset( $settings->back_desc_font_typo ) || ! is_array( $settings->back_desc_font_typo ) ) {

				$settings->back_desc_font_typo            = array();
				$settings->back_desc_font_typo_medium     = array();
				$settings->back_desc_font_typo_responsive = array();
			}
			if ( isset( $settings->back_desc_typography_font_family ) && '' !== $settings->back_desc_typography_font_family ) {
				if ( isset( $settings->back_desc_typography_font_family['weight'] ) ) {

					if ( 'regular' == $settings->back_desc_typography_font_family['weight'] ) {
						$settings->back_desc_font_typo['font_weight'] = 'normal';
					} else {

						$settings->back_desc_font_typo['font_weight'] = $settings->back_desc_typography_font_family['weight'];
					}
				}
				if ( isset( $settings->back_desc_typography_font_family['family'] ) ) {
					$settings->back_desc_font_typo['font_family'] = $settings->back_desc_typography_font_family['family'];
				}
			}
			if ( isset( $settings->back_desc_typography_font_size['desktop'] ) && ! isset( $settings->back_desc_font_typo['font_size'] ) ) {

				$settings->back_desc_font_typo['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_font_size['medium'] ) && ! isset( $settings->back_desc_font_typo_medium['font_size'] ) ) {
				$settings->back_desc_font_typo_medium['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_font_size['small'] ) && ! isset( $settings->back_desc_font_typo_responsive['font_size'] ) ) {
				$settings->back_desc_font_typo_responsive['font_size'] = array(
					'length' => $settings->back_desc_typography_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height['desktop'] ) && isset( $settings->back_desc_typography_font_size['desktop'] ) && 0 != $settings->back_desc_typography_font_size['desktop'] && ! isset( $settings->back_desc_typography_line_height_unit ) ) {

				$settings->back_desc_font_typo['line_height'] = array(
					'length' => round( $settings->back_desc_typography_line_height['desktop'] / $settings->back_desc_typography_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height['medium'] ) && isset( $settings->back_desc_typography_font_size['medium'] ) && 0 != $settings->back_desc_typography_font_size['medium'] && ! isset( $settings->back_desc_typography_line_height_unit_medium ) ) {
				$settings->back_desc_font_typo_medium['line_height'] = array(
					'length' => round( $settings->back_desc_typography_line_height['medium'] / $settings->back_desc_typography_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_typography_line_height['small'] ) && isset( $settings->back_desc_typography_font_size['small'] ) && 0 != $settings->back_desc_typography_font_size['small'] && ! isset( $settings->back_desc_typography_line_height_unit_responsive ) ) {
				$settings->back_desc_font_typo_responsive['line_height'] = array(
					'length' => round( $settings->back_desc_typography_line_height['small'] / $settings->back_desc_typography_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( ! isset( $settings->button->button_typo ) || ! is_object( $settings->button->button_typo ) ) {
				$settings->button->button_typo            = new stdClass();
				$settings->button->button_typo_medium     = new stdClass();
				$settings->button->button_typo_responsive = new stdClass();
			}
			if ( isset( $settings->button->font_family ) ) {
				if ( isset( $settings->button->font_family->weight ) ) {
					if ( 'regular' == $settings->button->font_family->weight ) {
						$settings->button->button_typo->font_weight = 'normal';
					} else {

						$settings->button->button_typo->font_weight = $settings->button->font_family->weight;
					}
				}
				if ( isset( $settings->button->font_family->family ) ) {
					$settings->button->button_typo->font_family = $settings->button->font_family->family;
				}
			}
			if ( isset( $settings->button->font_size->desktop ) ) {
				$settings->button->button_typo->font_size = (object) array(
					'length' => $settings->button->font_size->desktop,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->font_size->medium ) ) {
				$settings->button->button_typo_medium->font_size = (object) array(
					'length' => $settings->button->font_size->medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->font_size->small ) ) {
				$settings->button->button_typo_responsive->font_size = (object) array(
					'length' => $settings->button->font_size->small,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->button->line_height->desktop ) && isset( $settings->button->font_size->desktop ) && 0 != $settings->button->font_size->desktop ) {
				$settings->button->button_typo->line_height = (object) array(
					'length' => round( $settings->button->line_height->desktop / $settings->button->font_size->desktop, 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->line_height->medium ) && isset( $settings->button->font_size->medium ) && 0 != $settings->button->font_size->medium ) {
				$settings->button->button_typo_medium->line_height = (object) array(
					'length' => round( $settings->button->line_height->medium / $settings->button->font_size->medium, 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->line_height->small ) && isset( $settings->button->font_size->small ) && 0 != $settings->button->font_size->small ) {
				$settings->button->button_typo_responsive->line_height = (object) array(
					'length' => round( $settings->button->line_height->small / $settings->button->font_size->small, 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->button->font_family ) ) {
				unset( $settings->button->font_family );
				unset( $settings->button->font_size );
				unset( $settings->button->line_height );
			}
			// Unset the previous values.
			if ( isset( $settings->back_desc_typography_font_family ) ) {
				unset( $settings->back_desc_typography_font_family );
				unset( $settings->back_desc_typography_font_size );
				unset( $settings->back_desc_typography_line_height );
			}
		}

		return $settings;
	}

	/**
	 * Function that gets the Icons for the Flip Box module
	 *
	 * @method get_icons
	 * @param string $icon gets an string to check if $icon is referencing an included icon.
	 */
	public function get_icon( $icon = '' ) {

		// check if $icon is referencing an included icon.
		if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/flip-box/icon/' . $icon ) ) {
			$path = BB_ULTIMATE_ADDON_DIR . 'modules/flip-box/icon/' . $icon;
		}

		if ( file_exists( $path ) ) {
			$remove_icon = apply_filters( 'uabb_remove_svg_icon', false, 10, 1 );
			if ( true === $remove_icon ) {
				return;
			} else {
				return file_get_contents( $path );
			}
		} else {
			return '';
		}
	}

	/**
	 * Function that renders the button for the button
	 *
	 * @method render_button
	 */
	public function render_button() {
		if ( 'yes' == $this->settings->show_button ) {
			if ( '' != $this->settings->button ) {
				FLBuilder::render_module_html( 'uabb-button', $this->settings->button );
			}
		}
	}

	/**
	 * Function that renders the Icon or Photo for the Flip Box
	 *
	 * @method render_icon
	 */
	public function render_icon() {
		if ( 'icon' == $this->settings->image_types ) {
			if ( '' != $this->settings->smile_icon && '' != $this->settings->smile_icon->icon ) {
				$this->settings->smile_icon->image_type = 'icon';
				FLBuilder::render_module_html( 'image-icon', $this->settings->smile_icon );
			}
		}
		if ( 'photo' == $this->settings->image_types ) {
			if ( '' != $this->settings->smile_photo && '' != $this->settings->smile_photo->photo ) {
					$this->settings->smile_photo->image_type = 'photo';
					FLBuilder::render_module_html( 'image-icon', $this->settings->smile_photo );
			}
		}
	}
}

/*
 * Condition to verify Beaver Builder version.
 * And accordingly render the required form settings file.
 *
 */

if ( UABB_Compatibility::check_bb_version() ) {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/flip-box/flip-box-bb-2-2-compatibility.php';
} else {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/flip-box/flip-box-bb-less-than-2-2-compatibility.php';
}
