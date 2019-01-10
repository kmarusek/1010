<?php
/**
 *  UABB Countdown Module file
 *
 *  @package UABB Countdown Module
 */

/**
 * Function that initializes UABB Countdown Module
 *
 * @class UABBCountdownModule
 */
class UABBCountdownModule extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Countdown module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Countdown', 'uabb' ),
				'description'     => __( 'An animated countdown area.', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$extra_additions ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-countdown/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-countdown/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'clock.svg',
			)
		);
	}

	/**
	 * Function that enqueue scripts for the module
	 */
	public function enqueue_scripts() {

		$this->add_js( 'countdown-plug', $this->url . 'js/jquery.plugin.js', array( 'jquery' ), '', true );
		$this->add_js( 'countdown-library', $this->url . 'js/jquery.countdown.js', array( 'jquery' ), '', true );
		$this->add_js( 'countdown-cookie', $this->url . 'js/jquery.cookie.js', array( 'jquery' ), '', true );
	}

	/**
	 * Function that renders normal countdown for the module
	 *
	 * @param string $str1 gets an string.
	 * @param string $str2 gets an string.
	 */
	public function render_normal_countdown( $str1, $str2 ) {

		ob_start();

		?><div class="uabb-countdown-holding <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-digit-wrapper <?php echo $this->settings->timer_style; ?>"><<?php echo $this->settings->digit_tag_selection; ?> class="uabb-count-down-digit <?php echo $this->settings->timer_style; ?>"><?php echo $str1; ?></<?php echo $this->settings->digit_tag_selection; ?>></div><div class="uabb-countdown-unit-names"><<?php echo $this->settings->unit_tag_selection; ?> class="uabb-count-down-unit <?php echo $this->settings->timer_style; ?>"><?php echo $str2; ?></<?php echo $this->settings->unit_tag_selection; ?>></div></div>
		<?php

		$html = ob_get_contents();
		$html = trim( $html );
		ob_end_clean();
		return $html;
	}

	/**
	 * Function that renders normal above countdown for the module
	 *
	 * @param string $str1 gets an string.
	 * @param string $str2 gets an string.
	 * @param string $str3 gets an string.
	 */
	public function render_normal_above_countdown( $str1, $str2, $str3 ) {

		ob_start();

		?>
		<div class="uabb-countdown-holding <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-digit-wrapper <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-unit-names"><<?php echo $this->settings->unit_tag_selection; ?> class="uabb-count-down-unit <?php echo $this->settings->timer_style; ?>"><?php echo $str2; ?></<?php echo $this->settings->unit_tag_selection; ?>></div><<?php echo $this->settings->digit_tag_selection; ?> class="uabb-count-down-digit <?php echo $this->settings->timer_style; ?>"><?php echo $str1; ?></<?php echo $this->settings->digit_tag_selection; ?>></div><?php echo $str3; ?></div>

		<?php
		$html = ob_get_contents();
		$html = trim( $html );
		ob_end_clean();
		return $html;
	}

	/**
	 * Function that renders inside below countdown
	 *
	 * @param string $str1 gets an string.
	 * @param string $str2 gets an string.
	 * @param string $str3 gets an string.
	 */
	public function render_inside_below_countdown( $str1, $str2, $str3 ) {

		ob_start();

		?>
		<div class="uabb-countdown-holding <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-digit-wrapper <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-digit-content"><<?php echo $this->settings->digit_tag_selection; ?> class="uabb-count-down-digit <?php echo $this->settings->timer_style; ?>"><?php echo $str1; ?></<?php echo $this->settings->digit_tag_selection; ?>></div><div class="uabb-countdown-unit-names"><<?php echo $this->settings->unit_tag_selection; ?> class="uabb-count-down-unit <?php echo $this->settings->timer_style; ?>"><?php echo $str2; ?></<?php echo $this->settings->unit_tag_selection; ?>></div></div><?php echo $str3; ?></div>
		<?php
		$html = ob_get_contents();
		$html = trim( $html );
		ob_end_clean();
		return $html;
	}

	/**
	 * Function that renders inside above countdown
	 *
	 * @param string $str1 gets an string.
	 * @param string $str2 gets an string.
	 * @param string $str3 gets an string.
	 */
	public function render_inside_above_countdown( $str1, $str2, $str3 ) {

		ob_start();

		?>
		<div class="uabb-countdown-holding <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-digit-wrapper <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-unit-names"><<?php echo $this->settings->unit_tag_selection; ?> class="uabb-count-down-unit <?php echo $this->settings->timer_style; ?>"><?php echo $str2; ?></<?php echo $this->settings->unit_tag_selection; ?>></div><<?php echo $this->settings->digit_tag_selection; ?> class="uabb-count-down-digit <?php echo $this->settings->timer_style; ?>"><?php echo $str1; ?></<?php echo $this->settings->digit_tag_selection; ?>></div><?php echo $str3; ?></div>
		<?php

		$html = ob_get_contents();
		$html = trim( $html );
		ob_end_clean();
		return $html;
	}

	/**
	 * Function that renders outside countdown
	 *
	 * @param string $str1 gets an string.
	 * @param string $str2 gets an string.
	 * @param string $str3 gets an string.
	 */
	public function render_outside_countdown( $str1, $str2, $str3 ) {

		ob_start();

		?>
		<div class="uabb-countdown-holding <?php echo $this->settings->timer_style; ?>"><div class="uabb-countdown-unit-names"><<?php echo $this->settings->unit_tag_selection; ?> class="uabb-count-down-unit <?php echo $this->settings->timer_style; ?>"><?php echo $str2; ?></<?php echo $this->settings->unit_tag_selection; ?>></div><div class="uabb-countdown-digit-wrapper <?php echo $this->settings->timer_style; ?>"><<?php echo $this->settings->digit_tag_selection; ?> class="uabb-count-down-digit <?php echo $this->settings->timer_style; ?>"><?php echo $str1; ?></<?php echo $this->settings->digit_tag_selection; ?>></div><?php echo $str3; ?></div>
		<?php

		$html = ob_get_contents();
		$html = trim( $html );
		ob_end_clean();
		return $html;
	}

	/**
	 * Get time zone GMT offset
	 *
	 * @param object $settings gets the settings for the module.
	 */
	public function get_gmt_difference( $settings ) {

		if ( ! empty( $settings->time_zone ) ) {

			$time_zone_kolkata = new DateTimeZone( 'Asia/Kolkata' );
			$time_zone         = new DateTimeZone( $settings->time_zone );

			$time_kolkata = new DateTime( 'now', $time_zone_kolkata );

			$timeoffset = $time_zone->getOffset( $time_kolkata );

			return $timeoffset / 3600;
		} else {
			return 'NULL';
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

			// For link settings.
			if ( isset( $settings->redirect_link ) ) {
				if ( isset( $settings->redirect_link_target ) ) {
					$settings->redirect_link_target = $settings->redirect_link_target;
				}
			}

			// For overall alignment.
			if ( isset( $settings->counter_alignment ) ) {
				$settings->counter_alignment = $settings->counter_alignment;
			}

			// Handling color opacity.
			$helper->handle_opacity_inputs( $settings, 'timer_background_color_opc', 'timer_background_color' );

			// For message typography settings.
			if ( ! isset( $settings->message_typo ) || ! is_array( $settings->message_typo ) ) {

				$settings->message_typo            = array();
				$settings->message_typo_medium     = array();
				$settings->message_typo_responsive = array();
			}
			if ( isset( $settings->message_font_family ) ) {

				if ( isset( $settings->message_font_family['family'] ) ) {

					$settings->message_typo['font_family'] = $settings->message_font_family['family'];
				}
				if ( isset( $settings->message_font_family['weight'] ) ) {

					if ( 'regular' == $settings->message_font_family['weight'] ) {
						$settings->message_typo['font_weight'] = 'normal';
					} else {
						$settings->message_typo['font_weight'] = $settings->message_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->message_font_size_unit ) ) {
				$settings->message_typo['font_size'] = array(
					'length' => $settings->message_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_font_size_unit_medium ) ) {
				$settings->message_typo_medium['font_size'] = array(
					'length' => $settings->message_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_font_size_unit_responsive ) ) {
				$settings->message_typo_responsive['font_size'] = array(
					'length' => $settings->message_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_line_height_unit ) ) {

				$settings->message_typo['line_height'] = array(
					'length' => $settings->message_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->message_line_height_unit_medium ) ) {
				$settings->message_typo_medium['line_height'] = array(
					'length' => $settings->message_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->message_line_height_unit_responsive ) ) {
				$settings->message_typo_responsive['line_height'] = array(
					'length' => $settings->message_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->message_transform ) ) {
				$settings->message_typo['text_transform'] = $settings->message_transform;
			}
			if ( isset( $settings->message_letter_spacing ) ) {
				$settings->message_typo['letter_spacing'] = array(
					'length' => $settings->message_letter_spacing,
					'unit'   => 'px',
				);
			}

			// For digit typography settings.
			if ( ! isset( $settings->digit_typo ) || ! is_array( $settings->digit_typo ) ) {

				$settings->digit_typo            = array();
				$settings->digit_typo_medium     = array();
				$settings->digit_typo_responsive = array();
			}
			if ( isset( $settings->digit_font_family ) ) {

				if ( isset( $settings->digit_font_family['family'] ) ) {

					$settings->digit_typo['font_family'] = $settings->digit_font_family['family'];
				}
				if ( isset( $settings->digit_font_family['weight'] ) ) {

					if ( 'regular' == $settings->digit_font_family['weight'] ) {
						$settings->digit_typo['font_weight'] = 'normal';
					} else {
						$settings->digit_typo['font_weight'] = $settings->digit_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->digit_font_size_unit ) ) {
				$settings->digit_typo['font_size'] = array(
					'length' => $settings->digit_font_size_unit,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_font_size_unit_medium ) ) {
				$settings->digit_typo_medium['font_size'] = array(
					'length' => $settings->digit_font_size_unit_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_font_size_unit_responsive ) ) {
				$settings->digit_typo_responsive['font_size'] = array(
					'length' => $settings->digit_font_size_unit_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_line_height_unit ) ) {

				$settings->digit_typo['line_height'] = array(
					'length' => $settings->digit_line_height_unit,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->digit_line_height_unit_medium ) ) {
				$settings->digit_typo_medium['line_height'] = array(
					'length' => $settings->digit_line_height_unit_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->digit_line_height_unit_responsive ) ) {
				$settings->digit_typo_responsive['line_height'] = array(
					'length' => $settings->digit_line_height_unit_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->digit_letter_spacing ) ) {
				$settings->digit_typo['letter_spacing'] = array(
					'length' => $settings->digit_letter_spacing,
					'unit'   => 'px',
				);
			}

			// For unit typography settings.
			if ( ! isset( $settings->unit_typo ) || ! is_array( $settings->unit_typo ) ) {

				$settings->unit_typo            = array();
				$settings->unit_typo_medium     = array();
				$settings->unit_typo_responsive = array();
			}
			if ( isset( $settings->unit_font_family ) ) {

				if ( isset( $settings->unit_font_family['family'] ) ) {

					$settings->unit_typo['font_family'] = $settings->unit_font_family['family'];
				}
				if ( isset( $settings->unit_font_family['weight'] ) ) {

					if ( 'regular' == $settings->unit_font_family['weight'] ) {
						$settings->unit_typo['font_weight'] = 'normal';
					} else {
						$settings->unit_typo['font_weight'] = $settings->unit_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->unit_font_size_new ) ) {
				$settings->unit_typo['font_size'] = array(
					'length' => $settings->unit_font_size_new,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_font_size_new_medium ) ) {
				$settings->unit_typo_medium['font_size'] = array(
					'length' => $settings->unit_font_size_new_medium,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_font_size_new_responsive ) ) {
				$settings->unit_typo_responsive['font_size'] = array(
					'length' => $settings->unit_font_size_new_responsive,
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_line_height_new ) ) {

				$settings->unit_typo['line_height'] = array(
					'length' => $settings->unit_line_height_new,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->unit_line_height_new_medium ) ) {
				$settings->unit_typo_medium['line_height'] = array(
					'length' => $settings->unit_line_height_new_medium,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->unit_line_height_new_responsive ) ) {
				$settings->unit_typo_responsive['line_height'] = array(
					'length' => $settings->unit_line_height_new_responsive,
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->unit_transform ) ) {
				$settings->unit_typo['text_transform'] = $settings->unit_transform;
			}
			if ( isset( $settings->unit_letter_spacing ) ) {
				$settings->unit_typo['letter_spacing'] = array(
					'length' => $settings->unit_letter_spacing,
					'unit'   => 'px',
				);
			}

			// Unset the values.
			if ( isset( $settings->message_font_family ) ) {
				unset( $settings->message_font_family );
				unset( $settings->message_font_size_unit );
				unset( $settings->message_font_size_unit_medium );
				unset( $settings->message_font_size_unit_responsive );
				unset( $settings->message_line_height_unit );
				unset( $settings->message_line_height_unit_medium );
				unset( $settings->message_line_height_unit_responsive );
				unset( $settings->message_transform );
				unset( $settings->message_letter_spacing );
			}
			if ( isset( $settings->digit_font_family ) ) {
				unset( $settings->digit_font_family );
				unset( $settings->digit_font_size_unit );
				unset( $settings->digit_font_size_unit_medium );
				unset( $settings->digit_font_size_unit_responsive );
				unset( $settings->digit_line_height_unit );
				unset( $settings->digit_line_height_unit_medium );
				unset( $settings->digit_line_height_unit_responsive );
				unset( $settings->digit_letter_spacing );
			}
			if ( isset( $settings->unit_font_family ) ) {
				unset( $settings->unit_font_family );
				unset( $settings->unit_font_size_new );
				unset( $settings->unit_font_size_new_medium );
				unset( $settings->unit_font_size_new_responsive );
				unset( $settings->unit_line_height_new );
				unset( $settings->unit_line_height_new_medium );
				unset( $settings->unit_line_height_new_responsive );
				unset( $settings->unit_transform );
				unset( $settings->unit_letter_spacing );
			}
		} elseif ( $version_bb_check && 'yes' != $page_migrated ) {

			// For link settings.
			if ( isset( $settings->redirect_link ) ) {
				if ( isset( $settings->redirect_link_target ) ) {
					$settings->redirect_link_target = $settings->redirect_link_target;
				}
			}

			// For overall alignment settings.
			if ( isset( $settings->counter_alignment ) ) {
				$settings->counter_alignment = $settings->counter_alignment;
			}

			// Handling color opacity.
			$helper->handle_opacity_inputs( $settings, 'timer_background_color_opc', 'timer_background_color' );

			// For message settings.
			if ( ! isset( $settings->message_typo ) || ! is_array( $settings->message_typo ) ) {

				$settings->message_typo            = array();
				$settings->message_typo_medium     = array();
				$settings->message_typo_responsive = array();
			}
			if ( isset( $settings->message_font_family ) ) {

				if ( isset( $settings->message_font_family['family'] ) ) {

					$settings->message_typo['font_family'] = $settings->message_font_family['family'];
				}
				if ( isset( $settings->message_font_family['weight'] ) ) {

					if ( 'regular' == $settings->message_font_family['weight'] ) {
						$settings->message_typo['font_weight'] = 'normal';
					} else {
						$settings->message_typo['font_weight'] = $settings->message_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->message_font_size['small'] ) && ! isset( $settings->message_typo_responsive['font_size'] ) ) {

				$settings->message_typo_responsive['font_size'] = array(
					'length' => $settings->message_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_font_size['medium'] ) && ! isset( $settings->message_typo_medium['font_size'] ) ) {

				$settings->message_typo_medium['font_size'] = array(
					'length' => $settings->message_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_font_size['desktop'] ) && ! isset( $settings->message_typo['font_size'] ) ) {

				$settings->message_typo['font_size'] = array(
					'length' => $settings->message_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->message_line_height['desktop'] ) && isset( $settings->message_font_size['desktop'] ) && 0 != $settings->message_font_size['desktop'] && ! isset( $settings->message_typo['line_height'] ) ) {

				$settings->message_typo['line_height'] = array(
					'length' => round( $settings->message_line_height['desktop'] / $settings->message_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->message_line_height['medium'] ) && isset( $settings->message_font_size['medium'] ) && 0 != $settings->message_font_size['medium'] && ! isset( $settings->message_typo_medium['line_height'] ) ) {

				$settings->message_typo_medium['line_height'] = array(
					'length' => round( $settings->message_line_height['medium'] / $settings->message_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->message_line_height['small'] ) && isset( $settings->message_font_size['small'] ) && 0 != $settings->message_font_size['small'] && ! isset( $settings->message_typo_responsive['line_height'] ) ) {

				$settings->message_typo_responsive['line_height'] = array(
					'length' => round( $settings->message_line_height['small'] / $settings->message_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// For digit settings.
			if ( ! isset( $settings->digit_typo ) || ! is_array( $settings->digit_typo ) ) {

				$settings->digit_typo            = array();
				$settings->digit_typo_medium     = array();
				$settings->digit_typo_responsive = array();
			}
			if ( isset( $settings->digit_font_family ) ) {

				if ( isset( $settings->digit_font_family['family'] ) ) {

					$settings->digit_typo['font_family'] = $settings->digit_font_family['family'];
				}
				if ( isset( $settings->digit_font_family['weight'] ) ) {

					if ( 'regular' == $settings->digit_font_family['weight'] ) {
						$settings->digit_typo['font_weight'] = 'normal';
					} else {
						$settings->digit_typo['font_weight'] = $settings->digit_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->digit_font_size['small'] ) && ! isset( $settings->digit_typo_responsive['font_size'] ) ) {

				$settings->digit_typo_responsive['font_size'] = array(
					'length' => $settings->digit_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_font_size['medium'] ) && ! isset( $settings->digit_typo_medium['font_size'] ) ) {

				$settings->digit_typo_medium['font_size'] = array(
					'length' => $settings->digit_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_font_size['desktop'] ) && ! isset( $settings->digit_typo['font_size'] ) ) {

				$settings->digit_typo['font_size'] = array(
					'length' => $settings->digit_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->digit_line_height['desktop'] ) && isset( $settings->digit_font_size['desktop'] ) && 0 != $settings->digit_font_size['desktop'] && ! isset( $settings->digit_typo['line_height'] ) ) {

				$settings->digit_typo['line_height'] = array(
					'length' => round( $settings->digit_line_height['desktop'] / $settings->digit_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->digit_line_height['medium'] ) && isset( $settings->digit_font_size['medium'] ) && 0 != $settings->digit_font_size['medium'] && ! isset( $settings->digit_typo_medium['line_height'] ) ) {

				$settings->digit_typo_medium['line_height'] = array(
					'length' => round( $settings->digit_line_height['medium'] / $settings->digit_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->digit_line_height['small'] ) && isset( $settings->digit_font_size['small'] ) && 0 != $settings->digit_font_size['small'] && ! isset( $settings->digit_typo_responsive['line_height'] ) ) {

				$settings->digit_typo_responsive['line_height'] = array(
					'length' => round( $settings->digit_line_height['small'] / $settings->digit_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// For unit settings.
			if ( ! isset( $settings->unit_typo ) || ! is_array( $settings->unit_typo ) ) {

				$settings->unit_typo            = array();
				$settings->unit_typo_medium     = array();
				$settings->unit_typo_responsive = array();
			}
			if ( isset( $settings->unit_font_family ) ) {

				if ( isset( $settings->unit_font_family['family'] ) ) {

					$settings->unit_typo['font_family'] = $settings->unit_font_family['family'];
				}
				if ( isset( $settings->unit_font_family['weight'] ) ) {

					if ( 'regular' == $settings->unit_font_family['weight'] ) {
						$settings->unit_typo['font_weight'] = 'normal';
					} else {
						$settings->unit_typo['font_weight'] = $settings->unit_font_family['weight'];
					}
				}
			}
			if ( isset( $settings->unit_font_size['small'] ) && ! isset( $settings->unit_typo_responsive['font_size'] ) ) {

				$settings->unit_typo_responsive['font_size'] = array(
					'length' => $settings->unit_font_size['small'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_font_size['medium'] ) && ! isset( $settings->unit_typo_medium['font_size'] ) ) {

				$settings->unit_typo_medium['font_size'] = array(
					'length' => $settings->unit_font_size['medium'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_font_size['desktop'] ) && ! isset( $settings->unit_typo['font_size'] ) ) {

				$settings->unit_typo['font_size'] = array(
					'length' => $settings->unit_font_size['desktop'],
					'unit'   => 'px',
				);
			}
			if ( isset( $settings->unit_line_height['desktop'] ) && isset( $settings->unit_font_size['desktop'] ) && 0 != $settings->unit_font_size['desktop'] && ! isset( $settings->unit_typo['line_height'] ) ) {

				$settings->unit_typo['line_height'] = array(
					'length' => round( $settings->unit_line_height['desktop'] / $settings->unit_font_size['desktop'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->unit_line_height['medium'] ) && isset( $settings->unit_font_size['medium'] ) && 0 != $settings->unit_font_size['medium'] && ! isset( $settings->unit_typo_medium['line_height'] ) ) {

				$settings->unit_typo_medium['line_height'] = array(
					'length' => round( $settings->unit_line_height['medium'] / $settings->unit_font_size['medium'], 2 ),
					'unit'   => 'em',
				);
			}
			if ( isset( $settings->unit_line_height['small'] ) && isset( $settings->unit_font_size['small'] ) && 0 != $settings->unit_font_size['small'] && ! isset( $settings->unit_typo_responsive['line_height'] ) ) {

				$settings->unit_typo_responsive['line_height'] = array(
					'length' => round( $settings->unit_line_height['small'] / $settings->unit_font_size['small'], 2 ),
					'unit'   => 'em',
				);
			}

			// Unset the old values.
			if ( isset( $settings->message_font_family ) ) {
				unset( $settings->message_font_family );
				unset( $settings->message_font_size );
				unset( $settings->message_line_height );
			}
			if ( isset( $settings->digit_font_family ) ) {
				unset( $settings->digit_font_family );
				unset( $settings->digit_font_size );
				unset( $settings->digit_line_height );
			}
			if ( isset( $settings->unit_font_family ) ) {
				unset( $settings->unit_font_family );
				unset( $settings->unit_font_size );
				unset( $settings->unit_line_height );
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
		require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-countdown/uabb-countdown-bb-2-2-compatibility.php';
} else {
		require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-countdown/uabb-countdown-bb-less-than-2-2-compatibility.php';
}
