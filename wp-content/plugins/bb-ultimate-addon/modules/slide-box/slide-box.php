<?php
/**
 *  UABB Slide Box Module file
 *
 *  @package UABB Slide Box Module
 */

/**
 * Function that initializes UABB Slide Box Module
 *
 * @class SlideBoxModule
 */
class SlideBoxModule extends FLBuilderModule {
	/**
	 * Constructor function that constructs default values for the Slide Box Module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Slide Box', 'uabb' ),
				'description'     => __( 'Slide Box', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$creative_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/slide-box/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/slide-box/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'slides.svg',
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

			// Opacity.
			$helper->handle_opacity_inputs( $settings, 'overlay_color_opc', 'overlay_color' );

			$helper->handle_opacity_inputs( $settings, 'front_icon_border_color_opc', 'front_icon_border_color' );

			$helper->handle_opacity_inputs( $settings, 'front_icon_border_hover_color_opc', 'front_icon_border_hover_color' );

			$helper->handle_opacity_inputs( $settings, 'back_background_color_opc', 'back_background_color' );

			$helper->handle_opacity_inputs( $settings, 'img_bg_color_opc', 'img_bg_color' );

			$helper->handle_opacity_inputs( $settings, 'dropdown_icon_bg_color_opc', 'dropdown_icon_bg_color' );

			// For overall alignment and responsive alignment settings.
			if ( isset( $settings->front_alignment ) ) {
				$settings->front_alignment = $settings->front_alignment;
			}

			if ( isset( $settings->back_alignment ) ) {
				$settings->back_alignment = $settings->back_alignment;
			}

			if ( isset( $settings->dropdown_icon_align ) ) {
				$settings->dropdown_icon_align = $settings->dropdown_icon_align;
			}

			// Front Title Typography.
			if ( ! isset( $settings->front_title_typo ) || ! is_array( $settings->front_title_typo ) ) {

				$settings->front_title_typo            = array();
				$settings->front_title_typo_medium     = array();
				$settings->front_title_typo_responsive = array();
			}
			if ( isset( $settings->front_title_font_family ) ) {

				if ( isset( $settings->front_title_font_family['family'] ) ) {

					$settings->front_title_typo['font_family'] = $settings->front_title_font_family['family'];
				}
				if ( isset( $settings->front_title_font_family['weight'] ) ) {

					if ( 'regular' == $settings->front_title_font_family['weight'] ) {
						$settings->front_title_typo['font_weight'] = 'normal';
					} else {
						$settings->front_title_typo['font_weight'] = $settings->front_title_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->front_title_font_size_unit ) ) {
				$settings->front_title_typo['font_size'] = array(
					'length' => $settings->front_title_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_font_size_unit_medium ) ) {
				$settings->front_title_typo_medium['font_size'] = array(
					'length' => $settings->front_title_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_font_size_unit_responsive ) ) {
				$settings->front_title_typo_responsive['font_size'] = array(
					'length' => $settings->front_title_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_line_height_unit ) ) {

				$settings->front_title_typo['line_height'] = array(
					'length' => $settings->front_title_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_line_height_unit_medium ) ) {
				$settings->front_title_typo_medium['line_height'] = array(
					'length' => $settings->front_title_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_line_height_unit_responsive ) ) {
				$settings->front_title_typo_responsive['line_height'] = array(
					'length' => $settings->front_title_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_transform ) ) {
				$settings->front_title_typo['text_transform'] = $settings->front_title_transform;
			}
			if ( isset( $settings->front_title_letter_spacing ) ) {
				$settings->front_title_typo['letter_spacing'] = array(
					'length' => $settings->front_title_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Front Description Typography.
			if ( ! isset( $settings->front_desc_typo ) || ! is_array( $settings->front_desc_typo ) ) {

				$settings->front_desc_typo            = array();
				$settings->front_desc_typo_medium     = array();
				$settings->front_desc_typo_responsive = array();
			}
			if ( isset( $settings->front_desc_font_family ) ) {

				if ( isset( $settings->front_desc_font_family['family'] ) ) {

					$settings->front_desc_typo['font_family'] = $settings->front_desc_font_family['family'];
				}
				if ( isset( $settings->front_desc_font_family['weight'] ) ) {

					if ( 'regular' == $settings->front_desc_font_family['weight'] ) {
						$settings->front_desc_typo['font_weight'] = 'normal';
					} else {
						$settings->front_desc_typo['font_weight'] = $settings->front_desc_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->front_desc_font_size_unit ) ) {
				$settings->front_desc_typo['font_size'] = array(
					'length' => $settings->front_desc_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_font_size_unit_medium ) ) {
				$settings->front_desc_typo_medium['font_size'] = array(
					'length' => $settings->front_desc_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_font_size_unit_responsive ) ) {
				$settings->front_desc_typo_responsive['font_size'] = array(
					'length' => $settings->front_desc_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->front_desc_line_height_unit ) ) {
				$settings->front_desc_typo['line_height'] = array(
					'length' => $settings->front_desc_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_line_height_unit_medium ) ) {
				$settings->front_desc_typo_medium['line_height'] = array(
					'length' => $settings->front_desc_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_line_height_unit_responsive ) ) {
				$settings->front_desc_typo_responsive['line_height'] = array(
					'length' => $settings->front_desc_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_transform ) ) {
				$settings->front_desc_typo['text_transform'] = $settings->front_desc_transform;
			}
			if ( isset( $settings->front_desc_letter_spacing ) ) {
				$settings->front_desc_typo['letter_spacing'] = array(
					'length' => $settings->front_desc_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Back Title Typography.
			if ( ! isset( $settings->back_title_typo ) || ! is_array( $settings->back_title_typo ) ) {

				$settings->back_title_typo            = array();
				$settings->back_title_typo_medium     = array();
				$settings->back_title_typo_responsive = array();
			}
			if ( isset( $settings->back_title_font_family ) ) {

				if ( isset( $settings->back_title_font_family['family'] ) ) {

					$settings->back_title_typo['font_family'] = $settings->back_title_font_family['family'];
				}
				if ( isset( $settings->back_title_font_family['weight'] ) ) {

					if ( 'regular' == $settings->back_title_font_family['weight'] ) {
						$settings->back_title_typo['font_weight'] = 'normal';
					} else {
						$settings->back_title_typo['font_weight'] = $settings->back_title_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->back_title_font_size_unit ) ) {
				$settings->back_title_typo['font_size'] = array(
					'length' => $settings->back_title_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_font_size_unit_medium ) ) {
				$settings->back_title_typo_medium['font_size'] = array(
					'length' => $settings->back_title_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_font_size_unit_responsive ) ) {
				$settings->back_title_typo_responsive['font_size'] = array(
					'length' => $settings->back_title_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_line_height_unit ) ) {

				$settings->back_title_typo['line_height'] = array(
					'length' => $settings->back_title_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_line_height_unit_medium ) ) {
				$settings->back_title_typo_medium['line_height'] = array(
					'length' => $settings->back_title_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_line_height_unit_responsive ) ) {
				$settings->back_title_typo_responsive['line_height'] = array(
					'length' => $settings->back_title_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_transform ) ) {
				$settings->back_title_typo['text_transform'] = $settings->back_transform;
			}
			if ( isset( $settings->back_letter_spacing ) ) {
				$settings->back_title_typo['letter_spacing'] = array(
					'length' => $settings->back_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Back Description Typography.
			if ( ! isset( $settings->back_desc_typo ) || ! is_array( $settings->back_desc_typo ) ) {

				$settings->back_desc_typo            = array();
				$settings->back_desc_typo_medium     = array();
				$settings->back_desc_typo_responsive = array();
			}
			if ( isset( $settings->back_desc_font_family ) ) {

				if ( isset( $settings->back_desc_font_family['family'] ) ) {

					$settings->back_desc_typo['font_family'] = $settings->back_desc_font_family['family'];
				}
				if ( isset( $settings->back_desc_font_family['weight'] ) ) {

					if ( 'regular' == $settings->back_desc_font_family['weight'] ) {
						$settings->back_desc_typo['font_weight'] = 'normal';
					} else {
						$settings->back_desc_typo['font_weight'] = $settings->back_desc_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->back_desc_font_size_unit ) ) {
				$settings->back_desc_typo['font_size'] = array(
					'length' => $settings->back_desc_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_font_size_unit_medium ) ) {
				$settings->back_desc_typo_medium['font_size'] = array(
					'length' => $settings->back_desc_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_font_size_unit_responsive ) ) {
				$settings->back_desc_typo_responsive['font_size'] = array(
					'length' => $settings->back_desc_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_line_height_unit ) ) {

				$settings->back_desc_typo['line_height'] = array(
					'length' => $settings->back_desc_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_line_height_unit_medium ) ) {
				$settings->back_desc_typo_medium['line_height'] = array(
					'length' => $settings->back_desc_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_line_height_unit_responsive ) ) {
				$settings->back_desc_typo_responsive['line_height'] = array(
					'length' => $settings->back_desc_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_transform ) ) {
				$settings->back_desc_typo['text_transform'] = $settings->back_desc_transform;
			}
			if ( isset( $settings->back_desc_letter_spacing ) ) {
				$settings->back_desc_typo['letter_spacing'] = array(
					'length' => $settings->back_desc_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Link Typography.
			if ( ! isset( $settings->link_typo ) || ! is_array( $settings->link_typo ) ) {

				$settings->link_typo            = array();
				$settings->link_typo_medium     = array();
				$settings->link_typo_responsive = array();
			}
			if ( isset( $settings->link_font_family ) ) {

				if ( isset( $settings->link_font_family['family'] ) ) {

					$settings->link_typo['font_family'] = $settings->link_font_family['family'];
				}
				if ( isset( $settings->link_font_family['weight'] ) ) {

					if ( 'regular' == $settings->link_font_family['weight'] ) {
						$settings->link_typo['font_weight'] = 'normal';
					} else {
						$settings->link_typo['font_weight'] = $settings->link_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->link_font_size_unit ) ) {
				$settings->link_typo['font_size'] = array(
					'length' => $settings->link_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->link_font_size_unit_medium ) ) {
				$settings->link_typo_medium['font_size'] = array(
					'length' => $settings->link_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->link_font_size_unit_responsive ) ) {
				$settings->link_typo_responsive['font_size'] = array(
					'length' => $settings->link_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->link_line_height_unit ) ) {

				$settings->link_typo['line_height'] = array(
					'length' => $settings->link_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->link_line_height_unit_medium ) ) {
				$settings->link_typo_medium['line_height'] = array(
					'length' => $settings->link_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->link_line_height_unit_responsive ) ) {
				$settings->link_typo_responsive['line_height'] = array(
					'length' => $settings->link_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->link_transform ) ) {
				$settings->link_typo['text_transform'] = $settings->link_transform;
			}
			if ( isset( $settings->link_letter_spacing ) ) {
				$settings->link_typo['letter_spacing'] = array(
					'length' => $settings->link_letter_spacing,
					'unit'   => 'px',
				);
			}
			if ( ! isset( $settings->button->button_typo ) || ! is_object( $settings->button->button_typo ) ) {
				$settings->button->button_typo            = new stdClass();
				$settings->button->button_typo_medium     = new stdClass();
				$settings->button->button_typo_responsive = new stdClass;
			}
			if ( isset( $settings->button->font_family ) ) {

				if ( isset( $settings->button->font_family->family ) ) {

					$settings->button->button_typo->font_family = $settings->button->font_family->family;
				}
				if ( isset( $settings->button->font_family->weight ) ) {

					if ( 'regular' == $settings->button->font_family->weight ) {
						$settings->button->button_typo->font_weight = 'normal';
					} else {
						$settings->button->button_typo->font_weight = $settings->button->font_family->weight;
					}
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
				unset( $settings->button->font_size_unit_medium );
				unset( $settings->button->font_size_unit_responsive );
				unset( $settings->button->line_height_unit );
				unset( $settings->button->line_height_unit_medium );
				unset( $settings->button->line_height_unit_responsive );
				unset( $settings->button->transform );
				unset( $settings->button->letter_spacing );
			}
			if ( isset( $settings->front_title_font_family ) ) {
				unset( $settings->front_title_font_family );
				unset( $settings->front_title_font_size_unit );
				unset( $settings->front_title_font_size_unit_medium );
				unset( $settings->front_title_font_size_unit_responsive );
				unset( $settings->front_title_line_height_unit );
				unset( $settings->front_title_line_height_unit_medium );
				unset( $settings->front_title_line_height_unit_responsive );
				unset( $settings->front_title_transform );
				unset( $settings->front_title_letter_spacing );
			}
			if ( isset( $settings->front_desc_font_family ) ) {
				unset( $settings->front_desc_font_family );
				unset( $settings->front_desc_font_size_unit );
				unset( $settings->front_desc_font_size_unit_medium );
				unset( $settings->front_desc_font_size_unit_responsive );
				unset( $settings->front_desc_line_height_unit );
				unset( $settings->front_desc_line_height_unit_medium );
				unset( $settings->front_desc_line_height_unit_responsive );
				unset( $settings->front_desc_transform );
				unset( $settings->front_desc_letter_spacing );
			}

			if ( isset( $settings->back_title_font_family ) ) {
				unset( $settings->back_title_font_family );
				unset( $settings->back_title_font_size_unit );
				unset( $settings->back_title_font_size_unit_medium );
				unset( $settings->back_title_font_size_unit_responsive );
				unset( $settings->back_title_line_height_unit );
				unset( $settings->back_title_line_height_unit_medium );
				unset( $settings->back_title_line_height_unit_responsive );
				unset( $settings->back_transform );
				unset( $settings->back_letter_spacing );
			}
			if ( isset( $settings->back_desc_font_family ) ) {
				unset( $settings->back_desc_font_family );
				unset( $settings->back_desc_font_size_unit );
				unset( $settings->back_desc_font_size_unit_medium );
				unset( $settings->back_desc_font_size_unit_responsive );
				unset( $settings->back_desc_line_height_unit );
				unset( $settings->back_desc_line_height_unit_medium );
				unset( $settings->back_desc_line_height_unit_responsive );
				unset( $settings->back_desc_transform );
				unset( $settings->back_desc_letter_spacing );
			}

			if ( isset( $settings->link_font_family ) ) {
				unset( $settings->link_font_family );
				unset( $settings->link_font_size_unit );
				unset( $settings->link_font_size_unit_medium );
				unset( $settings->link_font_size_unit_responsive );
				unset( $settings->link_line_height_unit );
				unset( $settings->link_line_height_unit_medium );
				unset( $settings->link_line_height_unit_responsive );
				unset( $settings->link_transform );
				unset( $settings->link_letter_spacing );
			}
		} elseif ( $version_bb_check && 'yes' != $page_migrated ) {

			// Opacity.
			$helper->handle_opacity_inputs( $settings, 'overlay_color_opc', 'overlay_color' );

			$helper->handle_opacity_inputs( $settings, 'front_icon_border_color_opc', 'front_icon_border_color' );

			$helper->handle_opacity_inputs(
				$settings, 'front_icon_border_hover_color_opc', '
				front_icon_border_hover_color'
			);

			$helper->handle_opacity_inputs( $settings, 'back_background_color_opc', 'back_background_color' );

			$helper->handle_opacity_inputs( $settings, 'img_bg_color_opc', 'img_bg_color' );

			$helper->handle_opacity_inputs( $settings, 'dropdown_icon_bg_color_opc', 'dropdown_icon_bg_color' );

			// For overall alignment and responsive alignment settings.
			if ( isset( $settings->front_alignment ) ) {
				$settings->front_alignment = $settings->front_alignment;
			}

			if ( isset( $settings->back_alignment ) ) {
				$settings->back_alignment = $settings->back_alignment;
			}

			if ( isset( $settings->dropdown_icon_align ) ) {
				$settings->dropdown_icon_align = $settings->dropdown_icon_align;
			}

			// Front title Typography.
			if ( ! isset( $settings->front_title_typo ) || ! is_array( $settings->front_title_typo ) ) {

				$settings->front_title_typo            = array();
				$settings->front_title_typo_medium     = array();
				$settings->front_title_typo_responsive = array();
			}
			if ( isset( $settings->front_title_font_family ) ) {

				if ( isset( $settings->front_title_font_family['family'] ) ) {

					$settings->front_title_typo['font_family'] = $settings->front_title_font_family['family'];
				}
				if ( isset( $settings->front_title_font_family['weight'] ) ) {

					if ( 'regular' == $settings->front_title_font_family['weight'] ) {
						$settings->front_title_typo['font_weight'] = 'normal';
					} else {
						$settings->front_title_typo['font_weight'] = $settings->front_title_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->front_title_font_size['desktop'] ) ) {
				$settings->front_title_typo['font_size'] = array(
					'length' => $settings->front_title_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_font_size['small'] ) ) {
				$settings->front_title_typo_responsive['font_size'] = array(
					'length' => $settings->front_title_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_title_font_size['medium'] ) ) {
				$settings->front_title_typo_medium['font_size'] = array(
					'length' => $settings->front_title_font_size['medium'],
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->front_title_line_height['small'] ) && isset( $settings->front_title_font_size['small'] ) && 0 != $settings->front_title_font_size['small'] ) {

				$settings->front_title_typo_responsive['line_height'] = array(
					'length' => round( $settings->front_title_line_height['small'] / $settings->front_title_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_line_height['medium'] ) && isset( $settings->front_title_font_size['medium'] ) && 0 != $settings->front_title_font_size['medium'] ) {

				$settings->front_title_typo_medium['line_height'] = array(
					'length' => round( $settings->front_title_line_height['medium'] / $settings->front_title_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_title_line_height['desktop'] ) && isset( $settings->front_title_font_size['desktop'] ) && 0 != $settings->front_title_font_size['desktop'] ) {

				$settings->front_title_typo['line_height'] = array(
					'length' => round( $settings->front_title_line_height['desktop'] / $settings->front_title_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}

			// Front Description Typography.
			if ( ! isset( $settings->front_desc_typo ) || ! is_array( $settings->front_desc_typo ) ) {

				$settings->front_desc_typo            = array();
				$settings->front_desc_typo_medium     = array();
				$settings->front_desc_typo_responsive = array();
			}
			if ( isset( $settings->front_desc_font_family ) ) {

				if ( isset( $settings->front_desc_font_family['family'] ) ) {

					$settings->front_desc_typo['font_family'] = $settings->front_desc_font_family['family'];
				}
				if ( isset( $settings->front_desc_font_family['weight'] ) ) {

					if ( 'regular' == $settings->front_desc_font_family['weight'] ) {
						$settings->front_desc_typo['font_weight'] = 'normal';
					} else {
						$settings->front_desc_typo['font_weight'] = $settings->front_desc_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->front_desc_font_size['small'] ) ) {

				$settings->front_desc_typo_responsive['font_size'] = array(
					'length' => $settings->front_desc_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_font_size['medium'] ) ) {
				$settings->front_desc_typo_medium['font_size'] = array(
					'length' => $settings->front_desc_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->front_desc_font_size['desktop'] ) ) {
				$settings->front_desc_typo['font_size'] = array(
					'length' => $settings->front_desc_font_size['desktop'],
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->front_desc_line_height['small'] ) && isset( $settings->front_desc_font_size['small'] ) && 0 != $settings->front_desc_font_size['small'] ) {

				$settings->front_desc_typo_responsive['line_height'] = array(
					'length' => round( $settings->front_desc_line_height['small'] / $settings->front_desc_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_line_height['medium'] ) && isset( $settings->front_desc_font_size['medium'] ) && 0 != $settings->front_desc_font_size['medium'] ) {
				$settings->front_desc_typo_medium['line_height'] = array(
					'length' => round( $settings->front_desc_line_height['medium'] / $settings->front_desc_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->front_desc_line_height['desktop'] ) && isset( $settings->front_desc_font_size['desktop'] ) && 0 != $settings->front_desc_font_size['desktop'] ) {
				$settings->front_desc_typo['line_height'] = array(
					'length' => round( $settings->front_desc_line_height['desktop'] / $settings->front_desc_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}

			// Back Title Typography.
			if ( ! isset( $settings->back_title_typo ) || ! is_array( $settings->back_title_typo ) ) {

				$settings->back_title_typo            = array();
				$settings->back_title_typo_medium     = array();
				$settings->back_title_typo_responsive = array();
			}
			if ( isset( $settings->back_title_font_family ) ) {

				if ( isset( $settings->back_title_font_family['family'] ) ) {

					$settings->back_title_typo['font_family'] = $settings->back_title_font_family['family'];
				}
				if ( isset( $settings->back_title_font_family['weight'] ) ) {

					if ( 'regular' == $settings->back_title_font_family['weight'] ) {
						$settings->back_title_typo['font_weight'] = 'normal';
					} else {
						$settings->back_title_typo['font_weight'] = $settings->back_title_font_family['weight'];
					}
				}
			}

			if ( isset( $settings->back_title_font_size['small'] ) ) {

				$settings->back_title_typo_responsive['font_size'] = array(
					'length' => $settings->back_title_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_font_size['medium'] ) ) {

				$settings->back_title_typo_medium['font_size'] = array(
					'length' => $settings->back_title_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_title_font_size['desktop'] ) ) {
				$settings->back_title_typo['font_size'] = array(
					'length' => $settings->back_title_font_size['desktop'],
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->back_title_line_height['small'] ) && isset( $settings->back_title_font_size['small'] ) && 0 != $settings->back_title_font_size['small'] ) {

				$settings->back_title_typo_responsive['line_height'] = array(
					'length' => round( $settings->back_title_line_height['small'] / $settings->back_title_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_line_height['medium'] ) && isset( $settings->back_title_font_size['medium'] ) && 0 != $settings->back_title_font_size['medium'] ) {

				$settings->back_title_typo_medium['line_height'] = array(
					'length' => round( $settings->back_title_line_height['medium'] / $settings->back_title_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_title_line_height['desktop'] ) && isset( $settings->back_title_font_size['desktop'] ) && 0 != $settings->back_title_font_size['desktop'] ) {

				$settings->back_title_typo['line_height'] = array(
					'length' => round( $settings->back_title_line_height['desktop'] / $settings->back_title_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}

			// Back Description Typography.
			if ( ! isset( $settings->back_desc_typo ) || ! is_array( $settings->back_desc_typo ) ) {

				$settings->back_desc_typo            = array();
				$settings->back_desc_typo_medium     = array();
				$settings->back_desc_typo_responsive = array();
			}
			if ( isset( $settings->back_desc_font_family ) ) {

				if ( isset( $settings->back_desc_font_family['family'] ) ) {

					$settings->back_desc_typo['font_family'] = $settings->back_desc_font_family['family'];
				}
				if ( isset( $settings->back_desc_font_family['weight'] ) ) {

					if ( 'regular' == $settings->back_desc_font_family['weight'] ) {
						$settings->back_desc_typo['font_weight'] = 'normal';
					} else {
						$settings->back_desc_typo['font_weight'] = $settings->back_desc_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->back_desc_font_size['desktop'] ) ) {

				$settings->back_desc_typo['font_size'] = array(
					'length' => $settings->back_desc_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_font_size['medium'] ) ) {
				$settings->back_desc_typo_medium['font_size'] = array(
					'length' => $settings->back_desc_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_font_size['small'] ) ) {
				$settings->back_desc_typo_responsive['font_size'] = array(
					'length' => $settings->back_desc_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->back_desc_line_height['desktop'] ) && isset( $settings->back_desc_font_size['desktop'] ) && 0 != $settings->back_desc_font_size['desktop'] ) {

				$settings->back_desc_typo['line_height'] = array(
					'length' => round( $settings->back_desc_line_height['desktop'] / $settings->back_desc_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->back_desc_line_height['medium'] ) && isset( $settings->back_desc_font_size['medium'] ) && 0 != $settings->back_desc_font_size['medium'] ) {
					$settings->back_desc_typo_medium['line_height'] = array(
						'length' => round( $settings->back_desc_line_height['medium'] / $settings->back_desc_font_size['medium'], 2 ),
						'unit'   => 'em',
					);
			}
			if ( isset( $settings->back_desc_line_height['small'] ) && isset( $settings->back_desc_font_size['small'] ) && 0 != $settings->back_desc_font_size['small'] ) {
				$settings->back_desc_typo_responsive['line_height'] = array(
					'length' => round( $settings->back_desc_line_height['small'] / $settings->back_desc_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			// Link Typography.
			if ( ! isset( $settings->link_typo ) || ! is_array( $settings->link_typo ) ) {

				$settings->link_typo            = array();
				$settings->link_typo_medium     = array();
				$settings->link_typo_responsive = array();
			}
			if ( isset( $settings->link_font_family ) ) {

				if ( isset( $settings->link_font_family['family'] ) ) {

					$settings->link_typo['font_family'] = $settings->link_font_family['family'];
				}
				if ( isset( $settings->link_font_family['weight'] ) ) {

					if ( 'regular' == $settings->link_font_family['weight'] ) {
						$settings->link_typo['font_weight'] = 'normal';
					} else {
						$settings->link_typo['font_weight'] = $settings->link_font_family['weight'];
					}
				}
			}

			if ( isset( $settings->link_font_size['small'] ) ) {
				$settings->link_typo_responsive['font_size'] = array(
					'length' => $settings->link_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->link_font_size['medium'] ) ) {
				$settings->link_typo_medium['font_size'] = array(
					'length' => $settings->link_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->link_font_size['desktop'] ) ) {
				$settings->link_typo['font_size'] = array(
					'length' => $settings->link_font_size['desktop'],
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->link_line_height['small'] ) && isset( $settings->link_font_size['small'] ) && 0 != $settings->link_font_size['small'] ) {
				$settings->link_typo_responsive['line_height'] = array(
					'length' => round( $settings->link_line_height['small'] / $settings->link_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->link_line_height['medium'] ) && isset( $settings->link_font_size['medium'] ) && 0 != $settings->link_font_size['medium'] ) {
				$settings->link_typo_medium['line_height'] = array(
					'length' => round( $settings->link_line_height['medium'] / $settings->link_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->link_line_height['desktop'] ) && isset( $settings->link_font_size['desktop'] ) && 0 != $settings->link_font_size['desktop'] ) {
				$settings->link_typo['line_height'] = array(
					'length' => round( $settings->link_line_height['desktop'] / $settings->link_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( ! isset( $settings->button->button_typo ) || ! is_object( $settings->button->button_typo ) ) {
				$settings->button->button_typo            = new stdClass();
				$settings->button->button_typo_medium     = new stdClass();
				$settings->button->button_typo_responsive = new stdClass();
			}
			if ( isset( $settings->button->font_family ) ) {
				if ( isset( $settings->button->font_family->family ) ) {

					$settings->button->button_typo->font_family = $settings->button->font_family->family;
				}
				if ( isset( $settings->button->font_family->weight ) ) {

					if ( 'regular' == $settings->button->font_family->weight ) {
						$settings->button->button_typo->font_weight = 'normal';
					} else {
						$settings->button->button_typo->font_weight = $settings->button->font_family->weight;
					}
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
			if ( isset( $settings->front_title_font_family ) ) {
				unset( $settings->front_title_font_family );
				unset( $settings->front_title_font_size );
				unset( $settings->front_title_line_height );
			}
			if ( isset( $settings->front_desc_font_family ) ) {
				unset( $settings->front_desc_font_family );
				unset( $settings->front_desc_font_size );
				unset( $settings->front_desc_line_height );
			}

			if ( isset( $settings->back_title_font_family ) ) {
				unset( $settings->back_title_font_family );
				unset( $settings->back_title_font_size );
				unset( $settings->back_title_line_height );
			}
			if ( isset( $settings->back_desc_font_family ) ) {
				unset( $settings->back_desc_font_family );
				unset( $settings->back_desc_font_size );
				unset( $settings->back_desc_line_height );
			}

			if ( isset( $settings->link_font_family ) ) {
				unset( $settings->link_font_family );
				unset( $settings->link_font_size );
				unset( $settings->link_line_height );
			}
		}

		return $settings;
	}

	/**
	 * Function that renders the link for the Slide Box
	 *
	 * @method render_link
	 */
	public function render_link() {
		if ( 'link' == $this->settings->cta_type ) {
			echo '<a href="' . $this->settings->link . '" target="' . $this->settings->link_target . '" ' . BB_Ultimate_Addon_Helper::get_link_rel( $this->settings->link_target, 0, 0 ) . ' class="uabb-callout-cta-link">' . $this->settings->cta_text . '</a>';
		}
	}

	/**
	 * Function that renders the button for the Slide Box
	 *
	 * @method render_button
	 */
	public function render_button() {
		if ( 'button' == $this->settings->cta_type ) {
			if ( '' != $this->settings->button ) {
				FLBuilder::render_module_html( 'uabb-button', $this->settings->button );
			}
		}
	}

	/**
	 * Function that renders the Image for the Slide Box module.
	 *
	 * @method render_image
	 * @param var $pos gets the position of the image.
	 */
	public function render_image( $pos ) {
		if ( $this->settings->front_img_icon_position == $pos ) {
			$imageicon_array = array(

				/* General Section */
				'image_type'              => $this->settings->image_type,

				/* Icon Basics */
				'icon'                    => $this->settings->icon,
				'icon_size'               => $this->settings->icon_size,
				'icon_align'              => '',

				/* Image Basics */
				'photo_source'            => 'library',
				'photo'                   => $this->settings->photo,
				'photo_url'               => '',
				'img_size'                => $this->settings->img_size,
				'img_align'               => 'inherit',
				'photo_src'               => ( isset( $this->settings->photo_src ) ) ? $this->settings->photo_src : '',

				/* Icon Style */
				'icon_style'              => 'simple',
				'icon_bg_size'            => '',
				'icon_border_style'       => '',
				'icon_border_width'       => '',
				'icon_bg_border_radius'   => '',

				/* Image Style */
				'image_style'             => $this->settings->image_style,
				'img_bg_size'             => '',
				'img_border_style'        => '',
				'img_border_width'        => '',
				'img_bg_border_radius'    => '',

				/* Preset Color variable new */
				'icon_color_preset'       => 'preset1',

				/* Icon Colors */
				'icon_color'              => $this->settings->icon_color,
				'icon_hover_color'        => '',
				'icon_bg_color'           => '',
				'icon_bg_hover_color'     => '',
				'icon_border_color'       => '',
				'icon_border_hover_color' => '',
				'icon_three_d'            => '',

				/* Image Colors */
				'img_bg_color'            => '',
				'img_bg_hover_color'      => '',
				'img_border_color'        => '',
				'img_border_hover_color'  => '',
			);

			/* Render HTML Function */
			FLBuilder::render_module_html( 'image-icon', $imageicon_array );
		}
	}

	/**
	 * Function that renders the overlay icon for the Slide Box
	 *
	 * @method render_overlay_icon
	 */
	public function render_overlay_icon() {
		if ( 'style1' == $this->settings->slide_type && 'yes' == $this->settings->overlay ) {
			/* Render HTML Function */
			echo '<div class="uabb-slide-box-overlay">';
			echo    '<span class="uabb-icon-wrap">
                        <span class="uabb-icon">
                            <i class="' . $this->settings->overlay_icon . '"></i>
                        </span>
                    </span>';
			echo '</div>';
		}
	}

	/**
	 * Function that renders the overlay icon for the Slide Box
	 *
	 * @method render_overlay_icon
	 */
	public function render_dropdown_icon() {

		if ( 'style2' == $this->settings->slide_type ) {

			$icon_settings = array(
				'bg_color' => $this->settings->dropdown_icon_bg_color,
				'color'    => $this->settings->dropdown_icon_color,
				'icon'     => 'fa fa-angle-down',
				'size'     => $this->settings->dropdown_icon_size,
				'text'     => '',
			);

			echo '<div class="uabb-slide-dropdown">';
			echo    '<span class="uabb-icon-wrap">
                        <span class="uabb-icon">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </span>';
			echo '</div>';
		}

		if ( 'style3' == $this->settings->slide_type ) {

			$icon_settings = array(
				'color' => $this->settings->dropdown_icon_color,
				'icon'  => 'fa fa-plus',
				'size'  => $this->settings->dropdown_icon_size,
				'text'  => '',
			);

			echo '<div class="uabb-slide-dropdown">';
			echo    '<span class="uabb-icon-wrap">
                        <span class="uabb-icon">
                            <i class="fa fa-plus"></i>
                        </span>
                    </span>';
			echo '</div>';
		}
	}
}

/**
 * Condition to verify Beaver Builder version.
 * And accordingly render the required form settings file.
 */
if ( UABB_Compatibility::check_bb_version() ) {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/slide-box/slide-box-bb-2-2-compatibility.php';
} else {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/slide-box/slide-box-bb-less-than-2-2-compatibility.php';
}
