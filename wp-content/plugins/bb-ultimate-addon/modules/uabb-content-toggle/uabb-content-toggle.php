<?php
/**
 *  UABB Content Toggle Module file
 *
 *  @package UABB Content Toggle Module
 */

/**
 * Function that initializes UABB Content Toggle Module
 *
 * @class UABBContentToggleModule
 */
class UABBContentToggleModule extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Content Toggle Module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Content Toggle', 'uabb' ),
				'description'     => __( 'An animated content toggle.', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-content-toggle/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-content-toggle/',
				'partial_refresh' => true,
				'icon'            => 'content-toggle.svg',
			)
		);
	}

	/**
	 * Function that gets the Icon for the module
	 *
	 * @method get_icons
	 * @param object $icon get an object for the module.
	 */
	public function get_icon( $icon = '' ) {

		// check if $icon is referencing an included icon.
		if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-content-toggle/icon/' . $icon ) ) {
			$path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-content-toggle/icon/' . $icon;
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
	 * Function that gets the Toggle Content 1 for the module
	 *
	 * @method get_toggle_content2
	 * @param settings $settings get the settings for the module.
	 */
	function get_toggle_content1( $settings ) {

		$content_type = $settings->cont1_section;

		switch ( $content_type ) {

			case 'content':
				global $wp_embed;
				return wpautop( $wp_embed->autoembed( $settings->content_editor ) );
			break;
			case 'saved_rows':
				echo '[fl_builder_insert_layout id="' . $settings->cont1_saved_rows . '" type="fl-builder-template"]';
				break;
			case 'saved_modules':
				echo '[fl_builder_insert_layout id="' . $settings->cont1_saved_modules . '" type="fl-builder-template"]';
				break;
			case 'saved_page_templates':
				echo '[fl_builder_insert_layout id="' . $settings->cont1_page_templates . '" type="fl-builder-template"]';
				break;
			default:
				return;
			break;
		}
	}
	/**
	 * Function that gets the toggle content 2 for the module
	 *
	 * @method get_toggle_content2
	 * @param settings $settings get the settings for the module.
	 */
	function get_toggle_content2( $settings ) {

		$content_type = $settings->cont2_section;

		switch ( $content_type ) {

			case 'content_head2':
				global $wp_embed;
				return wpautop( $wp_embed->autoembed( $settings->content2_editor ) );
			break;
			case 'saved_rows_head2':
				echo '[fl_builder_insert_layout id="' . $settings->cont2_saved_rows . '" type="fl-builder-template"]';
				break;
			case 'saved_modules_head2':
				echo '[fl_builder_insert_layout id="' . $settings->cont2_saved_modules . '" type="fl-builder-template"]';
				break;
			case 'saved_page_templates_head2':
				echo '[fl_builder_insert_layout id="' . $settings->cont2_page_templates . '" type="fl-builder-template"]';
				break;
			default:
				return;
			break;
		}
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

			// Handle old border settings for Content.
			if ( ! isset( $settings->border ) || empty( $settings->border ) ) {

				$settings->border = array();

				// Border style, color, and width.
				if ( isset( $settings->border_width_sec ) && isset( $settings->border_type_sec ) && 'none' !== $settings->border_type_sec ) {
					$settings->border['style'] = $settings->border_type_sec;
					$settings->border['color'] = $settings->border_color_sec;
					$settings->border['width'] = array(
						'top'    => $settings->border_width_sec,
						'right'  => $settings->border_width_sec,
						'bottom' => $settings->border_width_sec,
						'left'   => $settings->border_width_sec,
					);
					unset( $settings->border_width_sec );
				}

				// Border radius.
				if ( isset( $settings->border_radius_sec ) ) {
					$settings->border['radius'] = array(
						'top_left'     => $settings->border_radius_sec,
						'top_right'    => $settings->border_radius_sec,
						'bottom_left'  => $settings->border_radius_sec,
						'bottom_right' => $settings->border_radius_sec,
					);
					unset( $settings->border_radius_sec );
				}
			}

			// Handle old border settings for Heading.
			if ( ! isset( $settings->head_border ) || empty( $settings->head_border ) ) {

				$settings->head_border = array();

				// Border style, color, and width.
				if ( isset( $settings->border_width_head ) && isset( $settings->border_type ) && 'none' !== $settings->border_type ) {
					$settings->head_border['style'] = $settings->border_type;
					$settings->head_border['color'] = $settings->border_color_head;
					$settings->head_border['width'] = array(
						'top'    => $settings->border_width_head,
						'right'  => $settings->border_width_head,
						'bottom' => $settings->border_width_head,
						'left'   => $settings->border_width_head,
					);
					unset( $settings->border_width_head );
				}

				// Border radius.
				if ( isset( $settings->border_radius ) ) {
					$settings->head_border['radius'] = array(
						'top_left'     => $settings->border_radius,
						'top_right'    => $settings->border_radius,
						'bottom_left'  => $settings->border_radius,
						'bottom_right' => $settings->border_radius,
					);
					unset( $settings->border_radius );
				}
			}

			// compatibility for Heading-1.
			if ( ! isset( $settings->head1_font_typo ) || ! is_array( $settings->head1_font_typo ) ) {

				$settings->head1_font_typo            = array();
				$settings->head1_font_typo_medium     = array();
				$settings->head1_font_typo_responsive = array();
			}
			if ( isset( $settings->head1_font_family ) ) {

				if ( isset( $settings->head1_font_family['family'] ) ) {

					$settings->head1_font_typo['font_family'] = $settings->head1_font_family['family'];
				}
				if ( isset( $settings->head1_font_family['weight'] ) ) {

					if ( 'regular' == $settings->head1_font_family['weight'] ) {
						$settings->head1_font_typo['font_weight'] = 'normal';
					} else {
						$settings->head1_font_typo['font_weight'] = $settings->head1_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->head1_size ) ) {
				$settings->head1_font_typo['font_size'] = array(
					'length' => $settings->head1_size,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head1_size_medium ) ) {
				$settings->head1_font_typo_medium['font_size'] = array(
					'length' => $settings->head1_size_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head1_size_responsive ) ) {
				$settings->head1_font_typo_responsive['font_size'] = array(
					'length' => $settings->head1_size_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head1_line_height ) ) {

				$settings->head1_font_typo['line_height'] = array(
					'length' => $settings->head1_line_height,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head1_line_height_medium ) ) {
				$settings->head1_font_typo_medium['line_height'] = array(
					'length' => $settings->head1_line_height_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head1_line_height_responsive ) ) {
				$settings->head1_font_typo_responsive['line_height'] = array(
					'length' => $settings->head1_line_height_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head1_transform ) ) {
				$settings->head1_font_typo['text_transform'] = $settings->head1_transform;
			}
			if ( isset( $settings->head1_letter_spacing ) ) {
				$settings->head1_font_typo['letter_spacing'] = array(
					'length' => $settings->head1_letter_spacing,
					'unit'   => 'px',
				);
			}

			// compatibility for Heading-2.
			if ( ! isset( $settings->head2_font_typo ) || ! is_array( $settings->head2_font_typo ) ) {

				$settings->head2_font_typo            = array();
				$settings->head2_font_typo_medium     = array();
				$settings->head2_font_typo_responsive = array();
			}
			if ( isset( $settings->head2_font_family ) ) {

				if ( isset( $settings->head2_font_family['family'] ) ) {

					$settings->head2_font_typo['font_family'] = $settings->head2_font_family['family'];
				}
				if ( isset( $settings->head2_font_family['weight'] ) ) {

					if ( 'regular' == $settings->head2_font_family['weight'] ) {
						$settings->head2_font_typo['font_weight'] = 'normal';
					} else {
						$settings->head2_font_typo['font_weight'] = $settings->head2_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->head2_size ) ) {

				$settings->head2_font_typo['font_size'] = array(
					'length' => $settings->head2_size,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head2_size_medium ) ) {
				$settings->head2_font_typo_medium['font_size'] = array(
					'length' => $settings->head2_size_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head2_size_responsive ) ) {

				$settings->head2_font_typo_responsive['font_size'] = array(
					'length' => $settings->head2_size_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->head2_line_height ) ) {

				$settings->head2_font_typo['line_height'] = array(
					'length' => $settings->head2_line_height,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head2_line_height_medium ) ) {
				$settings->head2_font_typo_medium['line_height'] = array(
					'length' => $settings->head2_line_height_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head2_line_height_responsive ) ) {
				$settings->head2_font_typo_responsive['line_height'] = array(
					'length' => $settings->head2_line_height_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->head2_transform ) ) {

				$settings->head2_font_typo['text_transform'] = $settings->head2_transform;
			}
			if ( isset( $settings->head2_letter_spacing ) ) {

				$settings->head2_font_typo['letter_spacing'] = array(
					'length' => $settings->head2_letter_spacing,
					'unit'   => 'px',
				);
			}

			// compatibility for Description-1.
			if ( ! isset( $settings->desc1_font_typo ) || ! is_array( $settings->desc1_font_typo ) ) {

				$settings->desc1_font_typo            = array();
				$settings->desc1_font_typo_medium     = array();
				$settings->desc1_font_typo_responsive = array();
			}
			if ( isset( $settings->section1_font_family ) ) {

				if ( isset( $settings->section1_font_family['family'] ) ) {

					$settings->desc1_font_typo['font_family'] = $settings->section1_font_family['family'];
				}
				if ( isset( $settings->section1_font_family['weight'] ) ) {

					if ( 'regular' == $settings->section1_font_family['weight'] ) {
						$settings->desc1_font_typo['font_weight'] = 'normal';
					} else {
						$settings->desc1_font_typo['font_weight'] = $settings->section1_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->section1_size ) ) {

				$settings->desc1_font_typo['font_size'] = array(
					'length' => $settings->section1_size,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section1_size_medium ) ) {
				$settings->desc1_font_typo_medium['font_size'] = array(
					'length' => $settings->section1_size_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section1_size_responsive ) ) {
				$settings->desc1_font_typo_responsive['font_size'] = array(
					'length' => $settings->section1_size_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section1_line_height ) ) {

				$settings->desc1_font_typo['line_height'] = array(
					'length' => $settings->section1_line_height,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section1_line_height_medium ) ) {
				$settings->desc1_font_typo_medium['line_height'] = array(
					'length' => $settings->section1_line_height_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section1_line_height_responsive ) ) {
				$settings->desc1_font_typo_responsive['line_height'] = array(
					'length' => $settings->section1_line_height_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section1_transform ) ) {
				$settings->desc1_font_typo['text_transform'] = $settings->section1_transform;
			}
			if ( isset( $settings->section1_letter_spacing ) ) {

				$settings->desc1_font_typo['letter_spacing'] = array(
					'length' => $settings->section1_letter_spacing,
					'unit'   => 'px',
				);
			}

			// compatibility for Description-2.
			if ( ! isset( $settings->desc2_font_typo ) || ! is_array( $settings->desc2_font_typo ) ) {

				$settings->desc2_font_typo            = array();
				$settings->desc2_font_typo_medium     = array();
				$settings->desc2_font_typo_responsive = array();
			}
			if ( isset( $settings->section2_font_family ) ) {

				if ( isset( $settings->section2_font_family['family'] ) ) {

					$settings->desc2_font_typo['font_family'] = $settings->section2_font_family['family'];
				}
				if ( isset( $settings->section2_font_family['weight'] ) ) {

					if ( 'regular' == $settings->section2_font_family['weight'] ) {
						$settings->desc2_font_typo['font_weight'] = 'normal';
					} else {
						$settings->desc2_font_typo['font_weight'] = $settings->section2_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->section2_size ) ) {

				$settings->desc2_font_typo['font_size'] = array(
					'length' => $settings->section2_size,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section2_size_medium ) ) {
				$settings->desc2_font_typo_medium['font_size'] = array(
					'length' => $settings->section2_size_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section2_size_responsive ) ) {
				$settings->desc2_font_typo_responsive['font_size'] = array(
					'length' => $settings->section2_size_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->section2_line_height ) ) {

				$settings->desc2_font_typo['line_height'] = array(
					'length' => $settings->section2_line_height,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section2_line_height_medium ) ) {
				$settings->desc2_font_typo_medium['line_height'] = array(
					'length' => $settings->section2_line_height_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section2_line_height_responsive ) ) {
				$settings->desc2_font_typo_responsive['line_height'] = array(
					'length' => $settings->section2_line_height_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->section2_transform ) ) {
				$settings->desc2_font_typo['text_transform'] = $settings->section2_transform;
			}
			if ( isset( $settings->section2_letter_spacing ) ) {

				$settings->desc2_font_typo['letter_spacing'] = array(
					'length' => $settings->section2_letter_spacing,
					'unit'   => 'px',
				);
			}

			if ( isset( $settings->head1_font_family ) ) {
				unset( $settings->head1_font_family );
				unset( $settings->head1_size );
				unset( $settings->head1_size_medium );
				unset( $settings->head1_size_responsive );
				unset( $settings->head1_line_height );
				unset( $settings->head1_line_height_medium );
				unset( $settings->head1_line_height_responsive );
				unset( $settings->head1_transform );
				unset( $settings->head1_letter_spacing );
			}
			if ( isset( $settings->head2_font_family ) ) {
				unset( $settings->head2_font_family );
				unset( $settings->head2_size );
				unset( $settings->head2_size_medium );
				unset( $settings->head2_size_responsive );
				unset( $settings->head2_line_height );
				unset( $settings->head2_line_height_medium );
				unset( $settings->head2_line_height_responsive );
				unset( $settings->head2_transform );
				unset( $settings->head2_letter_spacing );
			}
			if ( isset( $settings->section1_font_family ) ) {
				unset( $settings->section1_font_family );
				unset( $settings->section1_size );
				unset( $settings->section1_size_medium );
				unset( $settings->section1_size_responsive );
				unset( $settings->section1_line_height );
				unset( $settings->section1_line_height_medium );
				unset( $settings->section1_line_height_responsive );
				unset( $settings->section1_transform );
				unset( $settings->section1_letter_spacing );
			}
			if ( isset( $settings->section2_font_family ) ) {
				unset( $settings->section2_font_family );
				unset( $settings->section2_size );
				unset( $settings->section2_size_medium );
				unset( $settings->section2_size_responsive );
				unset( $settings->section2_line_height );
				unset( $settings->section2_line_height_medium );
				unset( $settings->section2_line_height_responsive );
				unset( $settings->section2_transform );
				unset( $settings->section2_letter_spacing );
			}
		}

		return $settings;
	}
}

/*
 * Condition to verify Beaver Builder version.
 * And accordingly render the required form settings file.
 */
if ( UABB_Compatibility::check_bb_version() ) {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-content-toggle/uabb-content-toggle-bb-2-2-compatibility.php';
} else {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-content-toggle/uabb-content-toggle-bb-less-than-2-2-compatibility.php';
}
