<?php
/**
 * Handles logic for the templates library in admin settings.
 *
 * @since 2.6.0
 */
final class BB_PowerPack_Templates_Lib {
    /**
     * Holds the templates count.
     *
     * @since 2.6.0
     * @var array $templates_count
     */
	static public $templates_count = array();

	/**
     * Holds templates data remote URLs.
     *
     * @since 2.6.0
     * @var array $remote_urls
     */
	static public $remote_urls = array();
	
	/**
     * Holds FileSystem object
     *
     * @since 2.6.0
     * @var object $filesystem
     */
	static public $filesystem;

	/**
     * Holds the upload dir path.
     *
     * @since 1.1.8
     * @var array
     */
	public static $upload_dir;

	/**
     * Initialize the class.
     *
     * @since 2.6.0
	 * @return void
     */
	static public function init()
	{
        self::$templates_count = array(
            'page'  => 0,
            'row'   => 0,
		);

		self::$remote_urls = array(
			'page'	=> 'https://wpbeaveraddons.com/wp-json/powerpack/v2/get/templates/page/',
			'row'	=> 'https://wpbeaveraddons.com/wp-json/powerpack/v2/get/templates/row/',
		);

		self::clear_enabled_templates();
		
		add_action( 'plugins_loaded', 					__CLASS__ . '::init_templates_data' );
		add_action( 'wp_ajax_pp_activate_template', 	__CLASS__ . '::activate_template' );
        add_action( 'wp_ajax_pp_deactivate_template', 	__CLASS__ . '::deactivate_template' );
	}

	/**
     * Get WP Filesystem
     *
     * @since 2.6.0
	 * @return object
     */
	static private function get_filesystem()
	{
		if ( class_exists( 'WP_Filesystem_Direct' ) && ! ( self::$filesystem instanceof WP_Filesystem_Direct ) ) {
			self::$filesystem = new WP_Filesystem_Direct( array() );
		}
		elseif ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
			require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';

			self::$filesystem = new WP_Filesystem_Direct( array() );
		}
	
		return self::$filesystem;
	}

	/**
     * Initialize the templates data.
     *
     * @since 2.6.0
	 * @return object
     */
	static public function init_templates_data()
	{
		self::$upload_dir = BB_PowerPack::$upload_dir;

		if ( is_admin() && isset( $_GET['page'] ) && 'ppbb-settings' == $_GET['page'] ) {
			self::download_templates_data();
			self::refresh_templates_data();
		} else {
			$row_templates 	= self::get_enabled_templates( 'row' );

			if ( is_array( $row_templates ) && method_exists( 'FLBuilder', 'register_templates' ) ) {
				$row_group = BB_PowerPack_Admin_Settings::get_option( 'ppwl_rt_label' );

				foreach ( $row_templates as $template ) {
					if ( file_exists( self::$upload_dir['path'] . $template . '.dat' ) ) {
						// Template filename should be the same as the category name.
						FLBuilder::register_templates( self::$upload_dir['path'] . $template . '.dat', array(
							'group'	=> $row_group
						) );
					}
				}
			}
	
			$page_templates = self::get_enabled_templates( 'page' );
	
			if ( is_array( $page_templates ) && method_exists( 'FLBuilder', 'register_templates' ) ) {
				$page_group = BB_PowerPack_Admin_Settings::get_option( 'ppwl_tmpcat_label' );

				foreach ( $page_templates as $template ) {
	
					if ( file_exists( self::$upload_dir['path'] . $template . '.dat' ) ) {
						// Template filename should be the same as the category name.
						FLBuilder::register_templates( self::$upload_dir['path'] . $template . '.dat', array(
							'group'	=> $page_group
						) );
					}
	
				}
			}
		}
	}

	/**
     * If it is fresh plugin install or user had never access templates
	 * earlier then downloads the templates data once and set count.
     *
     * @since 2.6.0
	 * @return object
     */
	static public function get_templates_data( $type )
	{
		$path = self::$upload_dir['path'];
		$file = $path . $type . '-templates.json';

		if ( ! file_exists( $file ) ) {
			self::download_templates_data();
		}

		$data = @file_get_contents( $file );

		if ( $data ) {
			$data = json_decode( $data, true );
			BB_PowerPack_Admin_Settings::$templates_count[$type] = count( $data );
		}

		return $data;
	}

	/**
     * Refreshes the template data on reload reuest.
     *
     * @since 2.6.0
	 * @return void
     */
	static public function refresh_templates_data()
	{
		if ( isset( $_REQUEST['page'] ) && 'ppbb-settings' == $_REQUEST['page'] ) {
            if ( isset( $_REQUEST['refresh'] ) ) {
                self::download_templates_data( 'new' );
            } else {
                self::download_templates_data();
            }
        }
	}

	/**
     * Downloads the template data.
     *
     * @since 2.6.0
	 * @return void
     */
	static private function download_templates_data( $request = '' )
	{
		if ( 'new' != $request && file_exists( self::$upload_dir['path'] . 'page-templates.json' ) && file_exists( self::$upload_dir['path'] . 'row-templates.json' ) ) {
			return;
		}
	
		$page 	= self::download_templates_json( self::$remote_urls['page'], self::$upload_dir['path'], 'page-templates.json' );
		$row 	= self::download_templates_json( self::$remote_urls['row'], self::$upload_dir['path'], 'row-templates.json' );
	}

	/**
	 * Downloads templates JSON.
	 *
	 * @since 2.6.0
	 * @return bool
	 */
	static private function download_templates_json( $url, $path, $filename = '' )
	{
		// Initialize the flag.
		$downloaded = false;
		
		if ( empty( $filename ) ) {
			return $downloaded;
		}

		$path = $path . $filename;

		// Delete the file if is already exists.
		if ( file_exists( $path ) ) {
			unlink( $path );
		}

		// Retrieve templates data.
		$response = wp_remote_get( $url, array(
			'timeout' => 30,
		) );

		if ( is_wp_error( $response ) ) {
			pp_set_error( 'fetch_error' );
			@error_log( 'bb-powerpack: ' . $response->get_error_message() );
			return $downloaded;
		}

		$templates_json = wp_remote_retrieve_body( $response );
		$templates_data = json_decode( $templates_json, 1 );

		if ( is_array( $templates_data ) && count( $templates_data ) > 0 ) {
			
			// Check if there is proper response or something went unexpected.
			if ( array_key_exists( 'code', $templates_data ) || array_key_exists( 'message', $templates_data ) || array_key_exists( 'data', $templates_data ) ) {
				pp_set_error( 'fetch_error' );
				return $downloaded;
			}

			file_put_contents( $path, $templates_json );
		} else {
			pp_set_error( 'fetch_error' );
			return $downloaded;
		}

		// Set the flag true if the template file is downloaded.
		if ( file_exists( $path ) ) {
			$downloaded = true;
		}

		return $downloaded;
	}

	/**
	 * Downloads template .dat file.
	 *
	 * @since 2.6.0
	 * @return bool
	 */
	static private function download_template( $url, $path )
	{
		// download file to temp dir.
		$tmp_file 	= download_url( $url, 300 );
		$path 		= $path . basename( $url );
		$response 	= array(
			'message'	=> __('Template has downloaded successfully!', 'bb-powerpack'),
			'success' 	=> true
		);

		if ( ! is_wp_error( $tmp_file ) ) {
			// copy template file.
			if ( ! self::get_filesystem()->copy( $tmp_file, $path, true ) ) {
				$response['message'] = __('Could not find the template file.', 'bb-powerpack');
				$response['success'] = false;
			}
		} else {
			$response['message'] = $tmp_file->get_error_message();
			$response['success'] = false;
		}

		// deletes the remote file from temp dir.
		unlink( $tmp_file );

		return $response;
	}

	/**
	 * Activate the page template.
	 *
	 * @since 1.1.6
	 */
    static public function activate_template()
    {
        if ( ! isset( $_REQUEST['pp_template_cat'] ) ) {
            return;
        }

        if ( ! isset( $_REQUEST['pp_template_type'] ) ) {
            return;
        }

        if ( is_multisite() && preg_match( '#^'.network_admin_url().'#i', $_SERVER['HTTP_REFERER'] ) ) {
	        define( 'WP_NETWORK_ADMIN', true );
        }

        // Get the template category.
        $cat = $_REQUEST['pp_template_cat'];

        // Get the template type.
        $type = $_REQUEST['pp_template_type'];

        // Get the template URL.
        $url = pp_templates_src( $type, $cat );

        // Get the upload dir path.
        $path = self::$upload_dir['path'];

        // Downloads the template.
        $response = self::download_template( $url, $path );

        if ( ! $response['success'] ) {
            echo $response['message']; die();
        }

        $enabled_templates = self::get_enabled_templates( $type );

        if ( is_array( $enabled_templates ) ) {
            if ( in_array( 'disabled', $enabled_templates ) ) {
                $enabled_templates = array();
            }
            $enabled_templates[] = $cat;
        }

        if ( ! $enabled_templates || ! is_array( $enabled_templates ) ) {
            $enabled_templates = array( $cat );
        }

        $key = 'bb_powerpack_templates';

        if ( $type == 'page' ) {
            $key = 'bb_powerpack_page_templates';
        }

        BB_PowerPack_Admin_Settings::update_option( $key, $enabled_templates, false );

        if ( !defined( 'WP_NETWORK_ADMIN' ) && is_multisite() ) {
            update_option( 'bb_powerpack_override_ms', 1 );
        }

        echo 'activated'; die();
    }

    /**
     * Deactivate the page template.
     *
     * @since 1.1.6
     */
    static public function deactivate_template()
    {
        if ( ! isset( $_REQUEST['pp_template_cat'] ) ) {
            return;
        }

        if ( is_multisite() && preg_match( '#^'.network_admin_url().'#i', $_SERVER['HTTP_REFERER'] ) ) {
	        define( 'WP_NETWORK_ADMIN', true );
        }

        // Get the template category.
        $cat = $_REQUEST['pp_template_cat'];

        // Get the template type.
        $type = $_REQUEST['pp_template_type'];

        $enabled_templates = self::get_enabled_templates( $type );

        if ( is_array( $enabled_templates ) && in_array( $cat, $enabled_templates ) ) {
            if ( ( $array_key = array_search( $cat, $enabled_templates ) ) !== false ) {
                unset( $enabled_templates[$array_key] );
            }
        }

        if ( count( $enabled_templates ) == 0 ) {
            $enabled_templates = array('disabled');
        }

        $key = 'bb_powerpack_templates';

        if ( $type == 'page' ) {
            $key = 'bb_powerpack_page_templates';
        }

        BB_PowerPack_Admin_Settings::update_option( $key, $enabled_templates, false );

        if ( !defined( 'WP_NETWORK_ADMIN' ) && is_multisite() ) {
            update_option( 'bb_powerpack_override_ms', 1 );
        }

        echo 'deactivated'; die();
	}
	
	/**
	 * Returns an array of all PowerPack templates that are enabled.
	 *
	 * @since 1.1.5
	 * @return array
	 */
    static public function get_enabled_templates( $type = 'row' )
    {
        if ( $type == 'row' ) {
            $enabled_templates = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_templates', true );
        } else {
            $enabled_templates = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_page_templates', true );
        }

        if ( $enabled_templates == false || ! is_array( $enabled_templates ) ) {

            $data = self::get_templates_data( $type );

            foreach ( $data as $cat => $info ) {
                $enabled_templates[] = $cat;
            }
        }

        return $enabled_templates;
    }

	/**
     * Clear all previously enabled templates on special URL parameter.
     *
     * @since 1.1.5
	 * @return void
     */
	static public function clear_enabled_templates()
	{
		if ( isset( $_GET['page'] ) && 'ppbb-settings' == $_GET['page'] && isset( $_GET['clear_enabled_templates'] ) ) {
            pp_clear_enabled_templates();
        }
	}
}

BB_PowerPack_Templates_Lib::init();