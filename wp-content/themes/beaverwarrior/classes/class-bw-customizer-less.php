<?php

/**
 * Blatantly copypastaed from BW
 *
 * @since 1.2.0
 */
final class BWCustomizerLess {

	/**
	 * An array of data used to render Customizer panels.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var array $_panels
	 */
	static private $_panels = array();

	/**
	 * An array of data for each settings preset.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var array $_presets
	 */
	static private $_presets = array();

	/**
	 * Cache for the get_theme_mods call.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var array $_mods
	 */
	static private $_mods = false;

	/**
	 * A flag for whether or not we're in a Customizer
	 * preview or not.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var bool $_in_customizer_preview
	 */
	static private $_in_customizer_preview = false;

	/**
	 * The prefix for the option that is stored in the
	 * database for the cached CSS file key.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var string $_css_key
	 */
	static private $_css_key = 'bw_theme_css_key';

	/**
	 * Adds Customizer panel data to the $_panels array.
	 *
	 * @since 1.2.0
	 * @param string $key The key for the panel to add. Must be unique.
	 * @param array $data The panel data.
	 * @return void
	 */
	static public function add_panel( $key, $data ) {
		self::$_panels[ $key ] = $data;
	}

	/**
	 * Adds settings preset data to the $_presets array.
	 *
	 * @since 1.2.0
	 * @param string $key The key for the preset to add. Must be unique.
	 * @param array $data An array of settings for the preset.
	 * @return void
	 */
	static public function add_preset( $key, $data ) {
		self::$_presets[ $key ] = $data;
	}

	/**
	 * Removes a preset from the presets array.
	 *
	 * @since 1.3.0
	 * @param string $key The key of the preset to remove.
	 * @return void
	 */
	static public function remove_preset( $key ) {
		if ( empty( $key ) ) {
			return;
		}

		if ( is_array( $key ) ) {
			$keys = $key;
		} elseif ( is_string( $key ) ) {
			$keys = array_map( 'trim', explode( ',', $key ) );
		}

		foreach ( (array) $keys as $k ) {
			if ( isset( self::$_presets[ $k ] ) ) {
				unset( self::$_presets[ $k ] );
			}
		}
	}

	/**
	 * Called by the customize_preview_init action to initialize
	 * a Customizer preview.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function preview_init() {
		self::$_in_customizer_preview = true;

		self::refresh_css();

		wp_enqueue_script( 'fl-stylesheet', FL_THEME_URL . '/js/stylesheet.js', array(), '', true );
		wp_enqueue_script( 'fl-customizer-preview', FL_THEME_URL . '/js/customizer-preview.js', array(), '', true );
	}

	/**
	 * Called by the customize_controls_enqueue_scripts action to enqueue
	 * styles and scripts for the Customizer.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function controls_enqueue_scripts() {
		wp_enqueue_style( 'fl-customizer', FL_THEME_URL . '/css/customizer.css', array(), FL_THEME_VERSION );
		wp_enqueue_script( 'fl-customizer-toggles', FL_THEME_URL . '/js/customizer-toggles.js', array(), FL_THEME_VERSION, true );
		wp_enqueue_script( 'fl-customizer', FL_THEME_URL . '/js/customizer.js', array(), FL_THEME_VERSION, true );
		wp_enqueue_script( 'ace', FL_THEME_URL . '/js/ace/ace.js', array(), FL_THEME_VERSION, true );
		wp_enqueue_script( 'ace-language-tools', FL_THEME_URL . '/js/ace/ext-language_tools.js', array(), FL_THEME_VERSION, true );
	}

	/**
	 * Called by the customize_controls_print_footer_scripts action to
	 * print scripts in the footer for the Customizer.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function controls_print_footer_scripts() {
		// Opening script tag
		echo '<script>';

		// Fonts
		FLFonts::js();

		// Defaults
		echo 'var FLCustomizerPresetDefaults = ' . json_encode( self::_get_default_preset_mods() ) . ';';

		// Presets
		echo 'var FLCustomizerPresets = ' . json_encode( self::$_presets ) . ';';

		// Closing script tag
		echo '</script>';
	}

	/**
	 * Called by the customize_register action to register presets,
	 * panels, sections, settings and controls.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function register( $customizer ) {
		require_once FL_THEME_DIR . '/classes/class-fl-customizer-control.php';

		self::_register_presets( $customizer );
		self::_register_panels( $customizer );
		self::_register_export_import_section( $customizer );
		self::_move_builtin_sections( $customizer );
		self::_remove_sections( $customizer );
	}

	/**
	 * Called by the customize_save_after action to refresh
	 * the cached CSS when Customizer settings are saved.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function save( $customizer ) {
		self::refresh_css();
	}

	/**
	 * Returns an array of all theme mods.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	static public function get_mods() {
		// We don't have mods yet, get them from the database.
		if ( ! self::$_mods ) {

			// Get preset preview mods.
			if ( self::is_preset_preview() ) {
				$mods = self::_get_preset_preview_mods();
			} // End if().
			else {

				// Get the settings.
				$mods = get_theme_mods();

				// Merge default mods.
				$mods = self::_merge_mods( 'default', $mods );
			}

			// No mods! Get defaults.
			if ( ! $mods ) {
				$mods = self::_get_default_mods();
				update_option( 'theme_mods_' . get_option( 'stylesheet' ), $mods );
			}
		} // End if().
		else {
			$mods = self::$_mods;
		}

		// Hack to insure the mod values are the same as the customzier
		// values since get_theme_mods doesn't return the correct values
		// while in the customizer. See https://core.trac.wordpress.org/ticket/24844
		if ( self::is_customizer_preview() ) {
			foreach ( $mods as $key => $val ) {
				$mods[ $key ] = apply_filters( 'theme_mod_' . $key, $mods[ $key ] );
			}
		}

		return apply_filters( 'fl_theme_mods', $mods );
	}

	/**
	 * Returns a URL for the cached CSS file.
	 *
	 * @since 1.2.0
	 * @return string
	 */
	static public function css_url() {
		// Get the cache dir and css key.
		$cache_dir = self::get_cache_dir();
		$css_slug  = self::_css_slug();
		$css_key   = get_option( self::$_css_key . '-' . $css_slug );
		$css_path  = $cache_dir['path'] . $css_slug . '-' . $css_key . '.css';
		$css_url   = $cache_dir['url'] . $css_slug . '-' . $css_key . '.css';

		// No css key, recompile the css.
		if ( ! $css_key ) {
			self::_compile_css();
			return self::css_url();
		}

		// Check to see if the file exists.
		if ( ! file_exists( $css_path ) ) {
			self::_compile_css();
			return self::css_url();
		}
       
       if (self::_css_mtime(self::$_presets[ $preset ]['skin']) > filemtime($css_path)) {
           self::_compile_css();
           return self::css_url();
       }

		// Return the url.
		return $css_url;
	}

	/**
	 * Clears and rebuilds the cached CSS file.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	static public function refresh_css() {
		self::_clear_css_cache();
		self::_compile_css();
	}

	/**
	 * Checks to see if this is a preset preview or not.
	 *
	 * @since 1.2.0
	 * @return bool
	 */
	static public function is_preset_preview() {
		if ( ! isset( $_GET['fl-preview'] ) ) {
			return false;
		}
		if ( ! isset( self::$_presets[ $_GET['fl-preview'] ] ) ) {
			return false;
		} elseif ( current_user_can( 'manage_options' ) || self::_is_demo_server() ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks to see if this is a Customizer preview or not.
	 *
	 * @since 1.2.0
	 * @return bool
	 */
	static public function is_customizer_preview() {
		return self::$_in_customizer_preview;
	}

	/**
	 * Sanitize callback for Customizer number settings.
	 *
	 * @since 1.2.0
	 * @return int
	 */
	static public function sanitize_number( $val ) {
		return is_numeric( $val ) ? $val : 0;
	}

	/**
	 * Sanitize callback for Customizer multiple checkbox settings.
	 *
	 * @since 1.5.3
	 * @return array
	 */
	static public function sanitize_checkbox_multiple( $val ) {
		$multi_values = ! is_array( $val ) ? explode( ',', $val ) : $val;

		return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	}

	/**
	 * Returns an array with the path and url for the cache directory.
	 *
	 * @since 1.2.0
	 * @return array
	 */
	static public function get_cache_dir() {
		$dir_name   = basename( FL_CHILD_THEME_DIR );
		$wp_info    = wp_upload_dir();

		// SSL workaround.
		if ( FLTheme::is_ssl() ) {
			$wp_info['baseurl'] = str_ireplace( 'http://', 'https://', $wp_info['baseurl'] );
		}

		// Build the paths.
		$dir_info   = array(
			'path'      => $wp_info['basedir'] . '/' . $dir_name . '/',
			'url'       => $wp_info['baseurl'] . '/' . $dir_name . '/',
		);

		// Create the cache dir if it doesn't exist.
		if ( ! file_exists( $dir_info['path'] ) ) {
			mkdir( $dir_info['path'] );
		}

		return $dir_info;
	}

	/**
	 * Deletes all cached CSS files.
	 *
	 * @since 1.5.2
	 * @return void
	 */
	static public function clear_all_css_cache() {
		self::_clear_css_cache( true );
	}

	/**
	 * Registers the presets section, control and setting.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param object $customizer An instance of WP_Customize_Manager.
	 * @return void
	 */
	static private function _register_presets( $customizer ) {
		// Presets section
		$customizer->add_section( 'fl-presets', array(
			'title'    => _x( 'Presets', 'Customizer section title. Theme design/style presets.', 'fl-automator' ),
			'priority' => 0,
		) );

		// Presets setting
		$customizer->add_setting( 'fl-preset', array(
			'default' => 'default',
		));

		// Presets choices
		$choices = array();

		foreach ( self::$_presets as $key => $val ) {
			$choices[ $key ] = $val['name'];
		}

		// Presets control
		$customizer->add_control( new WP_Customize_Control( $customizer, 'fl-preset', array(
			'section'       => 'fl-presets',
			'settings'      => 'fl-preset',
			'description'   => __( 'Start by selecting a preset for your theme.', 'fl-automator' ),
			'type'          => 'select',
			'choices'       => $choices,
		)));
	}

    /**
     * Steal parent panels, because FLCustomizer is so @*%(!ing encapsulated
     * that child themes are barred through almost every language mechanism from
     * modifying it.
     */
    static private function _steal_panels() {
        $panel_thief = Closure::bind(static function () {
            return self::$_panels;
        }, null, "FLCustomizer");

        $_parPanels = $panel_thief();

        foreach ($_parPanels as $pkey => $panel_data) {
            if (!isset(self::$_panels[$pkey])) {
                self::$_panels[$pkey] = $panel_data;
            }
        }
    }

	/**
	 * Registers the panels using data in the $_panels array.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param object $customizer An instance of WP_Customize_Manager.
	 * @return void
	 */
	static private function _register_panels( $customizer ) {
		$panel_priority     = 1;
		$section_priority   = 1;
		$option_priority    = 1;

       self::_steal_panels();

		// Loop panels
		foreach ( self::$_panels as $panel_key => $panel_data ) {

			// Add panel
			if ( self::_has_panel_support() ) {
				$customizer->add_panel( $panel_key, array(
					'title'    => $panel_data['title'],
					'priority' => $panel_priority,
				));
			}

			// Increment panel priority
			$panel_priority++;

			// Loop panel sections
			if ( isset( $panel_data['sections'] ) ) {

				foreach ( $panel_data['sections'] as $section_key => $section_data ) {

					// Make sure this section should be registered.
					if ( isset( $section_data['disable'] ) && true === $section_data['disable'] ) {
						continue;
					}

					// Add section
					$customizer->add_section( $section_key, array(
						'panel'    => $panel_key,
						'title'    => $section_data['title'],
						'priority' => $section_priority,
					));

					// Increment section priority
					$section_priority++;

					// Loop section options
					if ( isset( $section_data['options'] ) ) {

						foreach ( $section_data['options'] as $option_key => $option_data ) {

							// Add setting
							if ( ! isset( $option_data['setting'] ) ) {
								$option_data['setting'] = array(
									'default' => '',
								);
							}

							$customizer->add_setting( $option_key, $option_data['setting'] );

							// Add control
							$option_data['control']['section']  = $section_key;
							$option_data['control']['settings'] = $option_key;
							$option_data['control']['priority'] = $option_priority;
							$customizer->add_control(
								new $option_data['control']['class']( $customizer, $option_key, $option_data['control'] )
							);

							// Increment option priority
							$option_priority++;
						}

						// Reset option priority
						$option_priority = 0;
					}
				}// End foreach().

				// Reset section priority on if we have panel support.
				if ( self::_has_panel_support() ) {
					$section_priority = 0;
				}
			}// End if().
		}// End foreach().
	}

	/**
	 * Registers the export/import section, control and setting.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param object $customizer An instance of WP_Customize_Manager.
	 * @return void
	 */
	static private function _register_export_import_section( $customizer ) {
		if ( ! class_exists( 'CEI_Core' ) && current_user_can( 'install_plugins' ) ) {

			$customizer->add_section( 'fl-export-import', array(
				'title'    => _x( 'Export/Import', 'Customizer section title.', 'fl-automator' ),
				'priority' => 10000000,
			) );

			$customizer->add_setting( 'fl-export-import', array(
				'default' => '',
				'type'    => 'none',
			));

			$customizer->add_control( new FLCustomizerControl(
				$customizer,
				'fl-export-import',
				array(
					'section'       => 'fl-export-import',
					'type'          => 'export-import',
					'priority'      => 1,
				)
			));
		}
	}

	/**
	 * Checks to see if Customizer panels are supported.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return bool
	 */
	static private function _has_panel_support() {
		return method_exists( 'WP_Customize_Manager' , 'add_panel' );
	}

	/**
	 * Moves the builtin sections to the Settings panel.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param object $customizer An instance of WP_Customize_Manager.
	 * @return void
	 */
	static private function _move_builtin_sections( $customizer ) {
		$title_tagline      = $customizer->get_section( 'title_tagline' );
		$nav                = $customizer->get_section( 'nav' );
		$static_front_page  = $customizer->get_section( 'static_front_page' );

		// Set new panels or set a low priority.
		if ( self::_has_panel_support() ) {
			if ( is_object( $title_tagline ) ) {
				$title_tagline->panel = 'fl-settings';
			}
			if ( is_object( $nav ) ) {
				$nav->panel = 'fl-settings';
			}
			if ( is_object( $static_front_page ) ) {
				$static_front_page->panel = 'fl-settings';
			}
		} else {
			if ( is_object( $title_tagline ) ) {
				$title_tagline->priority = 10000;
			}
			if ( is_object( $nav ) ) {
				$nav->priority = 10001;
			}
			if ( is_object( $static_front_page ) ) {
				$static_front_page->priority = 10002;
			}
		}
	}

	/**
	 * Remove customizer sections based on version dependencies
	 *
	 * @since 1.6
	 * @access private
	 * @param object $customizer An instance of WP_Customize_Manager.
	 * @return boolean
	 */
	static private function _remove_sections( $customizer ) {
		// Remove CSS Code if WP core 'Additional CSS' is available since 4.7.0
		if ( function_exists( 'wp_get_custom_css_post' ) ) {
			$customizer->remove_section( 'fl-css-code-section' );
		}
	}

	/**
	 * Get an array of defaults for all Customizer settings.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return array
	 */
	static private function _get_default_mods() {
		$mods = array();
       self::_steal_panels();

		// Loop through the panels.
		foreach ( self::$_panels as $panel ) {

			if ( ! isset( $panel['sections'] ) ) {
				continue;
			}

			// Loop through the panel sections.
			foreach ( $panel['sections'] as $section ) {

				if ( ! isset( $section['options'] ) ) {
					continue;
				}

				// Loop through the section options.
				foreach ( $section['options'] as $option_id => $option ) {
					$mods[ $option_id ] = isset( $option['setting']['default'] ) ? $option['setting']['default'] : '';
				}
			}
		}

		return apply_filters( 'fl_default_theme_mods', $mods );
		return apply_filters( 'bw_default_theme_mods', $mods );
	}

	/**
	 * Get an array of defaults for settings that have a preset.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return array
	 */
	static private function _get_default_preset_mods() {
		$keys       = array();
		$defaults   = self::_get_default_mods();
		$mods       = array();

		foreach ( self::$_presets as $preset => $data ) {

			foreach ( $data['settings'] as $key => $val ) {

				if ( ! in_array( $key, $keys ) ) {
					$keys[] = $key;
				}
			}
		}

		foreach ( $keys as $key ) {
			if ( isset( $defaults[ $key ] ) ) {
				$mods[ $key ] = $defaults[ $key ];
			}
		}

		return $mods;
	}

	/**
	 * Get an array of mods for either a Customizer preview
	 * or a preset preview.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return array|bool
	 */
	static private function _get_preset_preview_mods() {
		if ( self::is_preset_preview() ) {

			$preset_slug                       = $_GET['fl-preview'];
			$preset                            = self::$_presets[ $preset_slug ];
			$preset['settings']['fl-preset']   = $_GET['fl-preview'];

			if ( current_user_can( 'manage_options' ) ) {
				return self::_merge_mods( 'saved', $preset['settings'] );
			} elseif ( self::_is_demo_server() ) {
				return self::_merge_mods( 'default', $preset['settings'] );
			}

			return false;
		}
	}

	/**
	 * Checks if this is the Beaver Builder demo server or not.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return bool
	 */
	static private function _is_demo_server() {
		return stristr( $_SERVER['HTTP_HOST'], 'demos.wpbeaverbuilder.com' );
	}

	/**
	 * Merges the provided mods array with the type of mods
	 * specified in the $merge_with param.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param string $merge_with Possible values are default and saved.
	 * @param array $mods The mods array to merge with.
	 * @return array|bool
	 */
	static private function _merge_mods( $merge_with = 'default', $mods = null ) {
		if ( ! $mods ) {
			return false;
		} elseif ( 'default' == $merge_with ) {
			$new_mods = self::_get_default_mods();
		} elseif ( 'saved' == $merge_with ) {
			$new_mods = get_theme_mods();
			$new_mods = self::_merge_mods( 'default', $new_mods );
		}

		foreach ( $mods as $mod_id => $mod ) {
			$new_mods[ $mod_id ] = $mod;
		}

		return $new_mods;
	}

	/**
	 * Deletes cached CSS files based on the current
	 * context (live, preview or customizer) or all if
	 * $all is set to true.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param bool $all
	 * @return void
	 */
	static private function _clear_css_cache( $all = false ) {
		$dir_name   = basename( FL_CHILD_THEME_DIR );
		$cache_dir  = self::get_cache_dir();
		$css_slug   = $all ? '' : self::_css_slug() . '-';

		if ( ! empty( $cache_dir['path'] ) && stristr( $cache_dir['path'], $dir_name ) ) {

			$css = glob( $cache_dir['path'] . $css_slug . '*' );

			foreach ( $css as $file ) {
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
		}
	}

	/**
	 * Returns the prefix slug for the CSS cache file.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return string
	 */
	static private function _css_slug() {
		if ( self::is_preset_preview() ) {
			$slug = 'preview-' . $_GET['fl-preview'];
		} elseif ( self::is_customizer_preview() ) {
			$slug = 'customizer';
		} else {
			$slug = 'skin';
		}

		return $slug;
	}
    
    static private function _css_paths($skin = null) {
        // BB Events Calendar
        $paths[] = file_get_contents( FL_THEME_DIR . '/less/the-events-calendar.less' );
        
        $paths[] = FL_THEME_DIR . '/less/theme.less';

        // BB WooCommerce
        if ( 'disabled' != $mods['fl-woo-css'] ) {
           $paths[] = FL_THEME_DIR . '/less/woocommerce.less';
        }

        // BB Skin
        if ( isset( $skin ) ) {
           if ( stristr( $skin, '.css' ) || stristr( $skin, '.less' ) ) {
              $skin_file = $skin;
           } else {
              $skin_file = FL_THEME_DIR . '/less/skin-' . $skin . '.less';
           }

           if ( file_exists( $skin_file ) ) {
              $paths[] = $skin_file;
           }
        }

        // Phylactery
        $paths[] = FL_CHILD_THEME_DIR . '/stylesheets/main.less';

        // Skin
        if ( isset( $skin ) ) {
           if ( stristr( $skin, '.css' ) || stristr( $skin, '.less' ) ) {
              $skin_file = $skin;
           } else {
              $skin_file = FL_THEME_DIR . '/less/skin-' . $skin . '.less';
           }

           if ( file_exists( $skin_file ) ) {
              $paths[] = $skin_file;
           }
        }

        // Filter the array of paths
        $paths = apply_filters( 'bw_theme_compile_less_paths', $paths );
        
        return $paths;
    }
    
    static private function _css_mtime($skin = null) {
        $paths = self::_css_paths($skin);
        $newest_mtime = 0;
        
        foreach ($paths as $path) {
            if (file_exists($path) && filemtime($path) > $newest_mtime) {
                $newest_mtime = filemtime($path);
            }
        }
        
        return $newest_mtime;
    }

	/**
	 * Compiles the cached CSS file.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return void
	 */
	static private function _compile_css() {
		$theme_info   = wp_get_theme();
		$mods         = self::get_mods();
		$preset       = isset( $mods['fl-preset'] ) ? $mods['fl-preset'] : 'default';
		$cache_dir    = self::get_cache_dir();
		$new_css_key  = uniqid();
		$css_slug     = self::_css_slug();
		$css          = '';
		$filename     = $cache_dir['path'] . $css_slug . '-' . $new_css_key . '.css';
		$paths = self::_css_paths(self::$_presets[ $preset ]['skin']);

		// Loop over paths and get contents
		$css = '';
		foreach ( $paths as $path ) {
			if ( file_exists( $path ) ) {
				$css .= file_get_contents( $path );
			}
		}

		// Filter less before compiling
		$css = apply_filters( 'bw_theme_compile_less', $css );

		// Replace {FL_THEME_URL} placeholder.
		$css = str_replace( '{FL_THEME_URL}', FL_THEME_URL, $css );
		$css = str_replace( '{FL_CHILD_THEME_URL}', FL_CHILD_THEME_URL, $css );

		// Compile LESS
		$css = self::_compile_less( $css );

		// Compress
		if ( ! WP_DEBUG ) {
			$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
			$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		}

		// Save the new css.
		file_put_contents( $filename, $css );

		// Make sure we can read the new file.
		// @codingStandardsIgnoreStart
		@chmod( $filename, 0644 );
		// @codingStandardsIgnoreEnd

		// Save the new css key.
		update_option( self::$_css_key . '-' . $css_slug, $new_css_key );
	}

	/**
	 * Compiles the provided LESS CSS.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param string $css The LESS CSS to compile.
	 * @return string
	 */
	static private function _compile_less( $css ) {
		if ( ! class_exists( 'lessc' ) ) {
			require_once FL_THEME_DIR . '/classes/class-lessc.php';
		}

		$less = new lessc;
		$mods = self::get_mods();

		// Fix issue with IE filters
		$css = preg_replace_callback( '(filter\s?:\s?(.*);)', 'BWCustomizerLess::_preg_replace_less', $css );

		// Mixins
		$mixins = file_get_contents( FL_THEME_DIR . '/less/mixins.less' );

		// Vars
		$less_vars = self::_get_less_vars();

       //Imports
       $less->addImportDir(FL_CHILD_THEME_DIR . '/stylesheets');

		// Compile and return
       try {
           return $less->compile( $mixins . $less_vars . $css );
       } catch (Less_Exception_Compiler $e) {
           trigger_error("LESS compilation failed. Most likely, the settings currently configured for your theme are invalid, which is preventing the theme customizations from being compiled. You won't see a styled website until you resolve this issue. The given error message was: " . $e->getMessage(), E_USER_WARNING);

           return "";
       }
	}

	/**
	 * Builds a string with LESS variables using Customizer settings.
	 *
	 * @since 1.2.0
	 * @access private
	 * @return string
	 */
	static private function _get_less_vars() {
		$mods                                   = self::get_mods();
		$defaults   							= self::_get_default_mods();
		$vars                                   = array();
		$vars_string                            = '';

		// Layout
		$boxed                                  = 'boxed' == $mods['fl-layout-width'];
		$shadow_size                            = $mods['fl-layout-shadow-size'];
		$shadow_color                           = $mods['fl-layout-shadow-color'];
		$vars['body-padding']                   = $boxed ? $mods['fl-layout-spacing'] . 'px 0' : '0';
		$vars['page-shadow']                    = $boxed ? '0 0 ' . $shadow_size . 'px ' . $shadow_color : 'none';

		// Body Background Image
		$vars['body-bg-image']                  = empty( $mods['fl-body-bg-image'] ) ? 'none' : 'url(' . $mods['fl-body-bg-image'] . ')';
		$vars['body-bg-repeat']                 = $mods['fl-body-bg-repeat'];
		$vars['body-bg-position']               = $mods['fl-body-bg-position'];
		$vars['body-bg-attachment']             = $mods['fl-body-bg-attachment'];
		$vars['body-bg-size']                   = $mods['fl-body-bg-size'];

		// Body Colors
		$vars['body-bg-color']                  = FLColor::hex( array( $mods['fl-body-bg-color'], $defaults['fl-body-bg-color'] ) );
		$vars['body-bg-color-2']             	= FLColor::similar( array( 1, 4, 13 ), $vars['body-bg-color'] );
		$vars['body-bg-color-3']             	= FLColor::similar( array( 3, 9, 18 ), $vars['body-bg-color'] );
		$vars['body-border-color']              = FLColor::similar( array( 10, 9, 19 ), $vars['body-bg-color'] );
		$vars['body-border-color-2']            = FLColor::similar( array( 20, 20, 30 ), $vars['body-bg-color'] );
		$vars['body-fg-color']                  = FLColor::foreground( $vars['body-bg-color'] );

		// Accent Color
		$vars['accent-color']                   = FLColor::hex( array( $mods['fl-accent'], $defaults['fl-accent'] ) );
		$vars['accent-hover-color']             = FLColor::hex( array( $mods['fl-accent-hover'], $mods['fl-accent'] ) );
		$vars['accent-fg-color']                = FLColor::foreground( $vars['accent-color'] );
		$vars['accent-fg-hover-color']          = FLColor::foreground( $vars['accent-hover-color'] );

		// Text Colors
		$vars['heading-color']                  = FLColor::hex( $mods['fl-heading-text-color'] );
		$vars['text-color']                     = FLColor::hex( $mods['fl-body-text-color'] );

		// Fonts
		$vars['text-font']                      = self::_get_font_family_string( $mods['fl-body-font-family'] );
		$vars['text-size']                      = $mods['fl-body-font-size'] . 'px';
		$vars['line-height']                    = $mods['fl-body-line-height'];
		$vars['text-weight']                    = self::_sanitize_weight( $mods['fl-body-font-weight'] );
		$vars['heading-font']                   = self::_get_font_family_string( $mods['fl-heading-font-family'] );
		$vars['heading-weight']                 = self::_sanitize_weight( $mods['fl-heading-font-weight'] );
		$vars['heading-transform']              = $mods['fl-heading-font-format'];
       

		$vars['heading-style']      = self::_get_style( $mods['fl-heading-font-weight'] );
		$vars['title-color']				= FLColor::hex( $mods['fl-title-text-color'] );
		$vars['title-font']					= self::_get_font_family_string( $mods['fl-title-font-family'] );
		$vars['title-weight']				= self::_sanitize_weight( $mods['fl-title-font-weight'] );
		$vars['title-transform']			= $mods['fl-title-font-format'];

		$vars['custom-h1-style'] = isset( $mods['fl-heading-style'] ) && 'title' === $mods['fl-heading-style'] ? self::_get_style( $mods['fl-title-font-weight'] ) : self::_get_style( $mods['fl-heading-font-weight'] );
		// Custom title styles
		if ( isset( $mods['fl-heading-style'] ) && 'title' !== $mods['fl-heading-style'] ) {
			$vars['title-color']			= FLColor::hex( $mods['fl-heading-text-color'] );
			$vars['title-font']					= self::_get_font_family_string( $mods['fl-heading-font-family'] );
			$vars['title-weight']				= self::_sanitize_weight( $mods['fl-heading-font-weight'] );
			$vars['title-transform']			= $mods['fl-heading-font-format'];
		}

		// Responsive controls style
		$responsive_mods = array(
			'h1-size' => array(
				'key' => 'fl-h1-font-size',
				'format' => 'px',
			),
			'h1-line-height' => array(
				'key' => 'fl-h1-line-height',
				'format' => '',
			),
			'h1-letter-spacing' => array(
				'key' => 'fl-h1-letter-spacing',
				'format' => 'px',
			),
		);
		foreach ( $responsive_mods as $var => $mod_data ) {
			foreach ( array( 'medium', 'mobile' ) as $device ) {
				$option_key = $mod_data['key'] . '_' . $device;

				if ( isset( $mods[ $option_key ] ) ) {
					$vars[ $device . '-' . $var ] = $mods[ $option_key ] . $mod_data['format'];
				} else {
					$vars[ $device . '-' . $var ] = $defaults[ $option_key ] . $mod_data['format'];
				}
			}
		}
       
		$vars['h1-size']                        = $mods['fl-h1-font-size'] . 'px';
		$vars['h1-line-height']                 = $mods['fl-h1-line-height'];
		$vars['h1-letter-spacing']              = $mods['fl-h1-letter-spacing'] . 'px';
		$vars['h2-size']                        = $mods['fl-h2-font-size'] . 'px';
		$vars['h2-line-height']                 = $mods['fl-h2-line-height'];
		$vars['h2-letter-spacing']              = $mods['fl-h2-letter-spacing'] . 'px';
		$vars['h3-size']                        = $mods['fl-h3-font-size'] . 'px';
		$vars['h3-line-height']                 = $mods['fl-h3-line-height'];
		$vars['h3-letter-spacing']              = $mods['fl-h3-letter-spacing'] . 'px';
		$vars['h4-size']                        = $mods['fl-h4-font-size'] . 'px';
		$vars['h4-line-height']                 = $mods['fl-h4-line-height'];
		$vars['h4-letter-spacing']              = $mods['fl-h4-letter-spacing'] . 'px';
		$vars['h5-size']                        = $mods['fl-h5-font-size'] . 'px';
		$vars['h5-line-height']                 = $mods['fl-h5-line-height'];
		$vars['h5-letter-spacing']              = $mods['fl-h5-letter-spacing'] . 'px';
		$vars['h6-size']                        = $mods['fl-h6-font-size'] . 'px';
		$vars['h6-line-height']                 = $mods['fl-h6-line-height'];
		$vars['h6-letter-spacing']              = $mods['fl-h6-letter-spacing'] . 'px';
		$vars['logo-font']                      = self::_get_font_family_string( $mods['fl-logo-font-family'] );
		$vars['logo-weight']                    = self::_sanitize_weight( $mods['fl-logo-font-weight'] );
		$vars['logo-size']                      = $mods['fl-logo-font-size'] . 'px';

		// Button Styles
		$vars['button-color']					= $mods['fl-button-color'] ? $mods['fl-button-color'] : $defaults['fl-button-color'];
		$vars['button-hover-color']				= $mods['fl-button-hover-color'] ? $mods['fl-button-hover-color'] : $defaults['fl-button-hover-color'];
		$vars['button-bg-color']				= $mods['fl-button-background-color'] ? $mods['fl-button-background-color'] : $defaults['fl-button-background-color'];
		$vars['button-bg-hover-color'] 			= $mods['fl-button-background-hover-color'] ? $mods['fl-button-background-hover-color'] : $defaults['fl-button-background-hover-color'];
		$vars['button-font-family']    			= self::_get_font_family_string( $mods['fl-button-font-family'] );
		$vars['button-font-weight']    			= self::_sanitize_weight( $mods['fl-button-font-weight'] );
		$vars['button-font-size']      			= is_numeric( $mods['fl-button-font-size'] ) ? $mods['fl-button-font-size'] . 'px' : $mods['fl-button-font-size'];
		$vars['button-line-height']    			= $mods['fl-button-line-height'];
		$vars['button-text-transform'] 			= $mods['fl-button-text-transform'];
		$vars['button-border-style']   			= $mods['fl-button-border-style'];
		$vars['button-border-width']   			= $mods['fl-button-border-width'] . 'px';
		$vars['button-border-color']   			= $mods['fl-button-border-color'];
		$vars['button-border-radius']  			= $mods['fl-button-border-radius'] . 'px';

		// Top Bar Background Image
		$vars['topbar-bg-image']                = empty( $mods['fl-topbar-bg-image'] ) ? 'none' : 'url(' . $mods['fl-topbar-bg-image'] . ')';
		$vars['topbar-bg-repeat']               = $mods['fl-topbar-bg-repeat'];
		$vars['topbar-bg-position']             = $mods['fl-topbar-bg-position'];
		$vars['topbar-bg-attachment']           = $mods['fl-topbar-bg-attachment'];
		$vars['topbar-bg-size']                 = $mods['fl-topbar-bg-size'];

		// Top Bar Colors
		$vars['topbar-bg-color']				= FLColor::hex_or_transparent( $mods['fl-topbar-bg-color'] );
		$vars['topbar-bg-opacity']				= FLColor::clean_opa( $mods['fl-topbar-bg-opacity'] );
		$vars['topbar-bg-grad']                	= $mods['fl-topbar-bg-gradient'] ? 10 : 0;
		$vars['topbar-border-color']        	= FLColor::similar( array( 10, 13, 19 ), array( $vars['topbar-bg-color'], $vars['body-bg-color'] ) );
		$vars['topbar-fg-color']               	= FLColor::hex( array( $mods['fl-topbar-text-color'], $vars['text-color'] ) );
		$vars['topbar-fg-link-color']          	= FLColor::hex( array( $mods['fl-topbar-link-color'], $vars['topbar-fg-color'] ) );
		$vars['topbar-fg-hover-color']         	= FLColor::hex( array( $mods['fl-topbar-hover-color'], $vars['topbar-fg-color'] ) );
		$vars['topbar-dropdown-bg-color']      	= FLColor::hex( array( $mods['fl-topbar-bg-color'], $vars['body-bg-color'] ) );

		// Header Background Image
		$vars['header-bg-image']                = empty( $mods['fl-header-bg-image'] ) ? 'none' : 'url(' . $mods['fl-header-bg-image'] . ')';
		$vars['header-bg-repeat']               = $mods['fl-header-bg-repeat'];
		$vars['header-bg-position']             = $mods['fl-header-bg-position'];
		$vars['header-bg-attachment']           = $mods['fl-header-bg-attachment'];
		$vars['header-bg-size']                 = $mods['fl-header-bg-size'];

		// Header Colors
		$vars['header-bg-color']				= FLColor::hex_or_transparent( $mods['fl-header-bg-color'] );
		$vars['header-bg-opacity']				= FLColor::clean_opa( $mods['fl-header-bg-opacity'] );
		$vars['header-bg-grad']                 = $mods['fl-header-bg-gradient'] ? 10 : 0;
		$vars['header-border-color']        	= FLColor::similar( array( 10, 13, 19 ), array( $vars['header-bg-color'], $vars['body-bg-color'] ) );
		$vars['header-fg-color']                = FLColor::hex( array( $mods['fl-header-text-color'], $vars['text-color'] ) );
		$vars['header-fg-link-color']           = FLColor::hex( array( $mods['fl-header-link-color'], $vars['header-fg-color'] ) );
		$vars['header-fg-hover-color']          = FLColor::hex( array( $mods['fl-header-hover-color'], $vars['header-fg-color'] ) );
		$vars['header-padding']                 = $mods['fl-header-padding'] . 'px';

		// Vertical Header
		$vars['vertical-header-width']          = $mods['fl-vertical-header-width'] . 'px';

		// Fixed Header Background Color
		$vars['fixed-header-bg-color']          = FLColor::hex( array( $vars['header-bg-color'], $vars['body-bg-color'] ) );

		// Nav Fonts
		$vars['nav-font-family']                = self::_get_font_family_string( $mods['fl-nav-font-family'] );
		$vars['nav-font-weight']                = self::_sanitize_weight( $mods['fl-nav-font-weight'] );
		$vars['nav-font-format']                = $mods['fl-nav-font-format'];
		$vars['nav-font-size']                  = $mods['fl-nav-font-size'] . 'px';

		// Nav Background Image
		$vars['nav-bg-image']                	= empty( $mods['fl-nav-bg-image'] ) ? 'none' : 'url(' . $mods['fl-nav-bg-image'] . ')';
		$vars['nav-bg-repeat']               	= $mods['fl-nav-bg-repeat'];
		$vars['nav-bg-position']             	= $mods['fl-nav-bg-position'];
		$vars['nav-bg-attachment']           	= $mods['fl-nav-bg-attachment'];
		$vars['nav-bg-size']                 	= $mods['fl-nav-bg-size'];

		// Nav Shadow
		$nav_shadow_size                        = $mods['fl-nav-shadow-size'];
		$nav_shadow_color                       = $mods['fl-nav-shadow-color'];
		$vars['nav-shadow']                     = $nav_shadow_size ? '0 0 ' . $nav_shadow_size . 'px ' . $nav_shadow_color : 'none';

		// Nav Layout
		$vars['nav-item-spacing']               = $mods['fl-nav-item-spacing'] . 'px';
		$vars['nav-menu-top-spacing']           = $mods['fl-nav-menu-top-spacing'] . 'px';
		$vars['header-logo-top-spacing']        = $mods['fl-header-logo-top-spacing'] . 'px';

		// Right Nav, Left Nav, Vertical Nav, Centered Inline Logo Nav Colors
		if ( 'right' == $mods['fl-header-layout'] ||
			 'left' == $mods['fl-header-layout'] ||
			 'vertical-left' == $mods['fl-header-layout'] ||
			 'vertical-right' == $mods['fl-header-layout'] ||
			 'centered-inline-logo' == $mods['fl-header-layout']
		   ) {
			$vars['nav-bg-color']               = $vars['header-bg-color'];
			$vars['nav-bg-grad']                = 0;
			$vars['nav-border-color']           = $vars['header-border-color'];
			$vars['nav-fg-color']               = $vars['header-fg-color'];
			$vars['nav-fg-link-color']          = $vars['header-fg-link-color'];
			$vars['nav-fg-hover-color']         = $vars['header-fg-hover-color'];
			$vars['nav-bg-opacity']			= FLColor::clean_opa( $mods['fl-nav-bg-opacity'] );
		} else {
			$vars['nav-bg-color']				= FLColor::hex_or_transparent( $mods['fl-nav-bg-color'] );
			$vars['nav-bg-opacity']			= FLColor::clean_opa( $mods['fl-nav-bg-opacity'] );
			$vars['nav-bg-grad']                = $mods['fl-nav-bg-gradient'] ? 5 : 0;
			$vars['nav-border-color']        	= FLColor::similar( array( 10, 13, 19 ), array( $vars['nav-bg-color'], $vars['body-bg-color'] ) );
			$vars['nav-fg-color']               = FLColor::hex( array( $mods['fl-nav-link-color'], $vars['text-color'] ) );
			$vars['nav-fg-link-color']          = FLColor::hex( array( $mods['fl-nav-link-color'], $vars['text-color'] ) );
			$vars['nav-fg-hover-color']         = FLColor::hex( array( $mods['fl-nav-hover-color'], $vars['nav-fg-color'] ) );
		}

		// Nav Dropdown Colors
		$vars['nav-dropdown-bg-color']			= FLColor::hex( array( $vars['nav-bg-color'], $vars['body-bg-color'] ) );

		// Mobile Nav Colors
		$vars['mobile-nav-btn-color']       	= FLColor::similar( array( 10, 13, 19 ), array( $vars['header-bg-color'], $vars['body-bg-color'] ) );
		$vars['mobile-nav-fg-color']        	= $vars['header-fg-color'];
		$vars['mobile-nav-fg-link-color']   	= $vars['header-fg-link-color'];
		$vars['mobile-nav-fg-hover-color']  	= $vars['header-fg-hover-color'];

		// Mobile Nav Breakpoint
		$vars['mobile-nav-breakpoint']			= $mods['fl-nav-breakpoint'];

		// Content Width
		$vars['content-width']                  = $mods['fl-content-width'] . 'px';

		// Content Background Image
		$vars['content-bg-image']               = empty( $mods['fl-content-bg-image'] ) ? 'none' : 'url(' . $mods['fl-content-bg-image'] . ')';
		$vars['content-bg-repeat']              = $mods['fl-content-bg-repeat'];
		$vars['content-bg-position']            = $mods['fl-content-bg-position'];
		$vars['content-bg-attachment']          = $mods['fl-content-bg-attachment'];
		$vars['content-bg-size']                = $mods['fl-content-bg-size'];

		// Content Colors
		$vars['content-bg-color']               = FLColor::hex( $mods['fl-content-bg-color'] );
		$vars['content-bg-opacity']				= FLColor::clean_opa( $mods['fl-content-bg-opacity'] );

		if ( ! FLColor::is_hex( $vars['content-bg-color'] ) ) {
			$vars['content-bg-color-2']         = $vars['body-bg-color-2'];
			$vars['content-bg-color-3']         = $vars['body-bg-color-3'];
			$vars['border-color']               = $vars['body-border-color'];
			$vars['border-color-2']             = $vars['body-border-color-2'];
			$vars['content-fg-color']           = $vars['body-fg-color'];
		} else {
			$vars['content-bg-color-2']         = FLColor::similar( array( 1, 4, 13 ), $vars['content-bg-color'] );
			$vars['content-bg-color-3']         = FLColor::similar( array( 3, 9, 18 ), $vars['content-bg-color'] );
			$vars['border-color']               = FLColor::similar( array( 10, 9, 19 ), $vars['content-bg-color'] );
			$vars['border-color-2']             = FLColor::similar( array( 20, 20, 30 ), $vars['content-bg-color'] );
			$vars['content-fg-color']           = FLColor::foreground( $vars['content-bg-color'] );
		}

		// Custom Blog Sidebar Size
		if ( 'custom' == $mods['fl-blog-sidebar-size'] && 'no-sidebar' != $mods['fl-blog-layout'] ) {
			$vars['custom-sidebar-size']        = $mods['fl-blog-custom-sidebar-size'] . '%';
		} else {
          //TODO: Actually make the sidebar classes work
          $vars['custom-sidebar-size']        = $mods['fl-blog-custom-sidebar-size'] . '%';
      }
		$vars['custom-sidebar-size']        = 'custom' == $mods['fl-blog-sidebar-size'] ? $mods['fl-blog-custom-sidebar-size'] . '%' : 0;

		// Custom WooCommerce Sidebar Size
		if ( 'custom' == $mods['fl-woo-sidebar-size'] && 'no-sidebar' != $mods['fl-woo-layout'] ) {
			$vars['custom-woo-sidebar-size']    = $mods['fl-woo-custom-sidebar-size'] . '%';
		} else {
          //TODO: Actually make the sidebar classes work
          $vars['custom-woo-sidebar-size']    = $mods['fl-woo-custom-sidebar-size'] . '%';
      }
		$vars['custom-woo-sidebar-size']    = 'custom' == $mods['fl-woo-sidebar-size'] ? $mods['fl-woo-custom-sidebar-size'] . '%' : 0;

		// Inputs Colors
		$vars['input-bg-color']               	= FLColor::hex( array( $vars['content-bg-color-2'], $vars['body-bg-color-2'], '#fcfcfc' ) );
		$vars['input-bg-focus-color']           = FLColor::hex( array( $vars['content-bg-color'], $vars['body-bg-color'], '#ffffff' ) );
		$vars['input-border-color']             = FLColor::hex( array( $vars['border-color'], $vars['body-border-color'], '#e6e6e6' ) );
		$vars['input-border-focus-color']       = FLColor::hex( array( $vars['border-color-2'], $vars['body-border-color-2'], '#cccccc' ) );

		// Footer Widget Background Image
		$vars['footer-widgets-bg-image']        = empty( $mods['fl-footer-widgets-bg-image'] ) ? 'none' : 'url(' . $mods['fl-footer-widgets-bg-image'] . ')';
		$vars['footer-widgets-bg-repeat']       = $mods['fl-footer-widgets-bg-repeat'];
		$vars['footer-widgets-bg-position']     = $mods['fl-footer-widgets-bg-position'];
		$vars['footer-widgets-bg-attachment']   = $mods['fl-footer-widgets-bg-attachment'];
		$vars['footer-widgets-bg-size']         = $mods['fl-footer-widgets-bg-size'];

		// Footer Widget Colors
		$vars['footer-widgets-bg-color']		= FLColor::hex_or_transparent( $mods['fl-footer-widgets-bg-color'] );
		$vars['footer-widgets-bg-opacity']		= FLColor::clean_opa( $mods['fl-footer-widgets-bg-opacity'] );
		$vars['footer-widgets-bg-grad']         = $mods['fl-footer-widgets-bg-gradient'] ? 15 : 0;
		$vars['footer-widgets-border-color']    = FLColor::similar( array( 10, 13, 19 ), array( $vars['footer-widgets-bg-color'], $vars['body-bg-color'] ) );
		$vars['footer-widgets-fg-color']        = FLColor::hex( array( $mods['fl-footer-widgets-text-color'], $vars['text-color'] ) );
		$vars['footer-widgets-fg-link-color']   = FLColor::hex( array( $mods['fl-footer-widgets-link-color'], $vars['footer-widgets-fg-color'] ) );
		$vars['footer-widgets-fg-hover-color']  = FLColor::hex( array( $mods['fl-footer-widgets-hover-color'], $vars['footer-widgets-fg-color'] ) );

		// Footer Background Image
		$vars['footer-bg-image']        		= empty( $mods['fl-footer-bg-image'] ) ? 'none' : 'url(' . $mods['fl-footer-bg-image'] . ')';
		$vars['footer-bg-repeat']       		= $mods['fl-footer-bg-repeat'];
		$vars['footer-bg-position']     		= $mods['fl-footer-bg-position'];
		$vars['footer-bg-attachment']   		= $mods['fl-footer-bg-attachment'];
		$vars['footer-bg-size']         		= $mods['fl-footer-bg-size'];

		// Footer Colors
		$vars['footer-bg-color']				= FLColor::hex_or_transparent( $mods['fl-footer-bg-color'] );
		$vars['footer-bg-opacity']				= FLColor::clean_opa( $mods['fl-footer-bg-opacity'] );
		$vars['footer-bg-grad']         		= $mods['fl-footer-bg-gradient'] ? 8 : 0;
		$vars['footer-border-color']    		= FLColor::similar( array( 10, 13, 19 ), array( $vars['footer-bg-color'], $vars['body-bg-color'] ) );
		$vars['footer-fg-color']        		= FLColor::hex( array( $mods['fl-footer-text-color'], $vars['text-color'] ) );
		$vars['footer-fg-link-color']           = FLColor::hex( array( $mods['fl-footer-link-color'], $vars['footer-fg-color'] ) );
		$vars['footer-fg-hover-color']          = FLColor::hex( array( $mods['fl-footer-hover-color'], $vars['footer-fg-color'] ) );

       //Critical variables that might not be set...
       if (!isset($vars["nav-bg-opacity"])) $vars["nav-bg-opacity"] = FLColor::clean_opa( $mods['fl-nav-bg-opacity'] );
       if (!isset($vars["nav-bg-opacity"])) $vars["nav-bg-opacity"] = "100%";
       if (!isset($vars["woo-cats-add-button"])) $vars["woo-cats-add-button"] = "none";

		// WooCommerce
		if ( FLTheme::is_plugin_active( 'woocommerce' ) ) {
			$vars['woo-cats-add-button']        = 'hidden' == $mods['fl-woo-cart-button'] ? 'none' : 'inline-block';
		}

		if ( true == apply_filters( 'fl_enable_fa5_pro', false ) ) {
			$vars['font-awesome-family'] = "'Font Awesome 5 Pro'";
		} else {
			$vars['font-awesome-family'] = "'Font Awesome 5 Free'";
		}

		// Let developers add their own vars.
		$vars = apply_filters( 'fl_less_vars', $vars );
		$vars = apply_filters( 'bw_less_vars', $vars, $mods );

		// Build the vars string
		foreach ( $vars as $key => $value ) {
			$vars_string .= '@' . $key . ':' . $value . ';';
		}

		// Return the vars string
		return $vars_string;
	}


	/**
	 * Sanitize the weight string.
	 * @since 1.7
	 */
	static private function _sanitize_weight( $weight ) {

		$weight = str_replace( 'italic', '', $weight );
		return empty( $weight ) ? 400 : $weight;
	}

	/**
	 * Get font style.
	 * @since 1.7.1
	 */
	static private function _get_style( $weight ) {

		if ( false !== strpos( $weight, 'italic' ) ) {
			return 'italic';
		}
		return 'normal';
	}

	/**
	 * Builds a font family string using the provided font key.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param string $font The font key.
	 * @return string
	 */
	static private function _get_font_family_string( $font ) {
		$string = '';
		$system = FLFontFamilies::get_system();
		if ( isset( $system[ $font ] ) ) {
			$string = $font . ', ' . $system[ $font ]['fallback'];
		} else {
			$string = '"' . $font . '", sans-serif';
		}

		return $string;
	}

	/**
	 * Regx replace callback for LESS to fix issues with IE filters.
	 *
	 * @since 1.2.0
	 * @access private
	 * @param array $matches
	 * @return string
	 */
	static private function _preg_replace_less( $matches ) {
		if ( ! empty( $matches[1] ) ) {
			return 'filter: ~"' . $matches[1] . '";';
		}

		return $matches[0];
	}
}
