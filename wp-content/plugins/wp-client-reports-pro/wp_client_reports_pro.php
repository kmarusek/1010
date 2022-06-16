<?php
/*
Plugin Name: WP Client Reports Pro
Plugin URI: https://switchwp.com/wp-client-reports/
Description: Send beautiful client maintenance reports with integrations from many popular plugins and services
Version: 1.0.11
Author: SwitchWP
Author URI: https://switchwp.com/
Text Domain: wp-client-reports-pro
Domain Path: /languages/
*/

if( !defined( 'ABSPATH' ) )
	exit;


define( 'WP_CLIENT_REPORTS_PRO_VERSION', '1.0.11' );
define( 'WP_CLIENT_REPORTS_PRO_STORE_URL', 'https://switchwp.com' );
define( 'WP_CLIENT_REPORTS_PRO_ITEM_ID', 39 );
define( 'WP_CLIENT_REPORTS_PRO_ITEM_NAME', 'WP Client Reports Pro' );

$pro_plugin_url = plugin_dir_url(__FILE__);
define('WP_CLIENT_REPORTS_PRO_PLUGIN_URL',$pro_plugin_url);

/**
 * Add notice to admin if base plugin is not installed
 */
add_action( 'admin_notices', 'wp_client_reports_pro_no_base_plugin_error' );
function wp_client_reports_pro_no_base_plugin_error() {
    if (!defined('WP_CLIENT_REPORTS_VERSION')) {
        echo '<div class="notice notice-error"><p><strong>';
        printf( __( 'WP Client Reports Pro requires the free base plugin %1$sWP Client Reports%2$s to be installed and active.', 'wp-client-reports-pro' ), '<a href="https://wordpress.org/plugins/wp-client-reports/" target="_blank">', '</a>' );
        echo '</strong></p></div>';
    }
}


/**
 * Add scripts and styles into the admin as needed
 */
add_action( 'admin_notices', 'wp_client_reports_pro_settings_errors' );
function wp_client_reports_pro_settings_errors() {
    settings_errors( 'wp-client-reports-pro-errors' );
}


/**
 * Add scripts and styles into the admin as needed
 */
function wp_client_reports_scripts_pro() {

    $screen = get_current_screen();
    if($screen && $screen->id == 'dashboard_page_wp_client_reports') {
        wp_enqueue_style( 'wp-client-reports-pro-css', plugin_dir_url( __FILE__ ) . '/css/wp-client-reports-pro.css', array(), '1.0' );
        wp_register_script( 'wp-client-reports-pro-js', plugin_dir_url( __FILE__ ) . 'js/wp-client-reports-pro.js', array('jquery','jquery-ui-datepicker'), WP_CLIENT_REPORTS_PRO_VERSION, true );
        $date_format = get_option('date_format');
        $js_data = array(
            'moment_date_format' => wp_client_reports_convert_date_format($date_format),
            'nodowntimeevents' => __('No Downtime Events','wp-client-reports-pro'),
            'nonotes' => __('No site maintenance notes during this period','wp-client-reports-pro'),
            'downtime_days_label' => __( 'Downtime Days', 'wp-client-reports-pro' ),
            'downtime_hours_label' => __( 'Downtime Hours', 'wp-client-reports-pro' ),
            'user_is_admin' => current_user_can('administrator'),
        );
        wp_localize_script( 'wp-client-reports-pro-js', 'wp_client_reports_pro_data', $js_data );
        wp_enqueue_script( 'wp-client-reports-pro-js' );
    } else if ($screen && $screen->id == 'settings_page_wp_client_reports') {
        //wp_enqueue_style( 'wp-client-reports-pro-css', plugin_dir_url( __FILE__ ) . '/css/wp-client-reports-pro.css', array(), '1.0' );
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_register_script( 'wp-client-reports-pro-options-js', plugin_dir_url( __FILE__ ) . 'js/wp-client-reports-pro-options.js', array('jquery','jquery-ui-datepicker','wp-color-picker'), WP_CLIENT_REPORTS_PRO_VERSION, true );
        $date_format = get_option('date_format');
        $js_data = array(
            'moment_date_format' => wp_client_reports_convert_date_format($date_format),
        );
        wp_localize_script( 'wp-client-reports-pro-options-js', 'wp_client_reports_pro_data', $js_data );
        wp_enqueue_script( 'wp-client-reports-pro-options-js' );
        
    }

}
add_action( 'admin_print_scripts', 'wp_client_reports_scripts_pro', 11 );



/**
 * On plugin deactivation remove the scheduled events
 */
register_deactivation_hook( __FILE__, 'wp_client_reports_pro_auto_send_schedule_clear' );
function wp_client_reports_pro_auto_send_schedule_clear() {
     wp_clear_scheduled_hook( 'wp_client_reports_pro_auto_send' );
}


/**
 * Load additional files in admin if options are enabled
 */
add_action( 'init', 'wp_client_reports_pro_admin_init' );
function wp_client_reports_pro_admin_init() {

    if (is_admin() || wp_doing_cron()) {

        $notes_enabled = get_option('wp_client_reports_pro_enable_notes');
        if ($notes_enabled == 'on') {
            require_once plugin_dir_path( __FILE__ ) . 'services/notes/wp_client_reports_pro_notes.php';
        }

        $google_analytics_enabled = get_option('wp_client_reports_pro_enable_ga');
        if ($google_analytics_enabled == 'on') {
            require_once plugin_dir_path( __FILE__ ) . 'services/google-analytics/wp_client_reports_pro_google_analytics.php';
        }

        $uptime_robot_enabled = get_option('wp_client_reports_pro_enable_uptime_robot');
        if ($uptime_robot_enabled == 'on') {
            require_once plugin_dir_path( __FILE__ ) . 'services/uptime-robot/wp_client_reports_pro_uptime_robot.php';
        }

        $pingdom_enabled = get_option('wp_client_reports_pro_enable_pingdom');
        if ($pingdom_enabled == 'on') {
            require_once plugin_dir_path( __FILE__ ) . 'services/pingdom/wp_client_reports_pro_pingdom.php';
        }

        $mailchimp_enabled = get_option('wp_client_reports_pro_enable_mailchimp');
        if ($mailchimp_enabled == 'on') {
            require_once plugin_dir_path( __FILE__ ) . 'services/mailchimp/wp_client_reports_pro_mailchimp.php';
        }

        $gravity_forms_enabled = get_option('wp_client_reports_pro_enable_gravity_forms');
        if ( class_exists( 'GFCommon' ) && $gravity_forms_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/gravity-forms/wp_client_reports_pro_gravity_forms.php';
        }

        $ninja_forms_enabled = get_option('wp_client_reports_pro_enable_ninja_forms');
        if ( class_exists( 'Ninja_Forms' ) && $ninja_forms_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/ninja-forms/wp_client_reports_pro_ninja_forms.php';
        }

        $wpforms_enabled = get_option('wp_client_reports_pro_enable_wpforms');
        if ( class_exists( 'WPForms' ) && $wpforms_enabled == 'on' ) {
            if ( \wpforms()->pro ) {
                require_once plugin_dir_path( __FILE__ ) . 'services/wpforms/wp_client_reports_pro_wpforms.php';
            }
        }

        $formidable_enabled = get_option('wp_client_reports_pro_enable_formidable');
        if ( class_exists( 'FrmAppHelper' ) && $formidable_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/formidable/wp_client_reports_pro_formidable.php';
        }

        $wpcf7_enabled = get_option('wp_client_reports_pro_enable_wpcf7');
        if ( class_exists( 'WPCF7' ) && (function_exists('cfdb7_init') || function_exists('flamingo_init')) && $wpcf7_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/wpcf7/wp_client_reports_pro_wpcf7.php';
        }

        $caldera_forms_enabled = get_option('wp_client_reports_pro_enable_caldera_forms');
        if ( class_exists( 'Caldera_Forms' ) && $caldera_forms_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/caldera-forms/wp_client_reports_pro_caldera_forms.php';
        }
        
        $woocommerce_enabled = get_option('wp_client_reports_pro_enable_woocommerce');
        if ( class_exists( 'WooCommerce' ) && $woocommerce_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/woocommerce/wp_client_reports_pro_woocommerce.php';
        }

        $edd_enabled = get_option('wp_client_reports_pro_enable_edd');
        if ( class_exists( 'Easy_Digital_Downloads' ) && $edd_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/easy-digital-downloads/wp_client_reports_pro_edd.php';
        }

        $givewp_enabled = get_option('wp_client_reports_pro_enable_givewp');
        if ( class_exists( 'Give' ) && $givewp_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/givewp/wp_client_reports_pro_givewp.php';
        }

        $stripe_enabled = get_option('wp_client_reports_pro_enable_stripe');
        if ($stripe_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/stripe/wp_client_reports_pro_stripe.php';
        }

        $updraftplus_enabled = get_option('wp_client_reports_pro_enable_updraftplus');
        if ( class_exists( 'UpdraftPlus' ) && $updraftplus_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/updraftplus/wp_client_reports_pro_updraftplus.php';
        }

        $backwpup_enabled = get_option('wp_client_reports_pro_enable_backwpup');
        if ( class_exists( 'BackWPup' ) && $backwpup_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/backwpup/wp_client_reports_pro_backwpup.php';
        }

        $backupbuddy_enabled = get_option('wp_client_reports_pro_enable_backupbuddy');
        if ( class_exists( 'pb_backupbuddy' ) && $backupbuddy_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/backupbuddy/wp_client_reports_pro_backupbuddy.php';
        }

        $wpengine_backups_enabled = get_option('wp_client_reports_pro_enable_wpengine_backups');
        if ( defined( 'WPE_APIKEY' ) && $wpengine_backups_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/wpengine-backups/wp_client_reports_pro_wpengine_backups.php';
        }

        $searchwp_enabled = get_option('wp_client_reports_pro_enable_searchwp');
        if ( class_exists( 'SearchWP' ) && $searchwp_enabled == 'on' ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/searchwp/wp_client_reports_pro_searchwp.php';
        }

        if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'vendor/edd-sl-plugin-updater/EDD_SL_Plugin_Updater.php';
        }
        
        $license_key = trim( get_option( 'wp_client_reports_pro_license' ) );
        if ( false !== $license_key ) {
            $edd_updater = new EDD_SL_Plugin_Updater( WP_CLIENT_REPORTS_PRO_STORE_URL, __FILE__,
                array(
                    'version' => WP_CLIENT_REPORTS_PRO_VERSION,
                    'license' => $license_key,
                    'item_id' => WP_CLIENT_REPORTS_PRO_ITEM_ID,
                    'author'  => 'SwitchWP',
                    'beta'    => false,
                    'url'     => home_url(),
                )
            );
        }

    }

}


/**
 * Load additional files on frontend if options are enabled
 */
add_action( 'init', 'wp_client_reports_pro_frontend_init' );
function wp_client_reports_pro_frontend_init() {

    // exit function if not on front-end
    if ( is_admin() ) {
        return;
    }

    $gravity_forms_enabled = get_option('wp_client_reports_pro_enable_gravity_forms');
    if ( class_exists( 'GFCommon' ) && $gravity_forms_enabled == 'on' ) {
        require_once plugin_dir_path( __FILE__ ) . 'services/gravity-forms/wp_client_reports_pro_gravity_forms.php';
    }

    $ninja_forms_enabled = get_option('wp_client_reports_pro_enable_ninja_forms');
    if ( class_exists( 'Ninja_Forms' ) && $ninja_forms_enabled == 'on' ) {
        require_once plugin_dir_path( __FILE__ ) . 'services/ninja-forms/wp_client_reports_pro_ninja_forms.php';
    }

    $wpforms_enabled = get_option('wp_client_reports_pro_enable_wpforms');
    if ( class_exists( 'WPForms' ) && $wpforms_enabled == 'on' ) {
        if ( \wpforms()->pro ) {
            require_once plugin_dir_path( __FILE__ ) . 'services/wpforms/wp_client_reports_pro_wpforms.php';
        }
    }

    $formidable_enabled = get_option('wp_client_reports_pro_enable_formidable');
    if ( class_exists( 'FrmAppHelper' ) && $formidable_enabled == 'on' ) {
        require_once plugin_dir_path( __FILE__ ) . 'services/formidable/wp_client_reports_pro_formidable.php';
    }

    $wpcf7_enabled = get_option('wp_client_reports_pro_enable_wpcf7');
    if ( class_exists( 'WPCF7' ) && (function_exists('cfdb7_init') || function_exists('flamingo_init')) && $wpcf7_enabled == 'on' ) {
        require_once plugin_dir_path( __FILE__ ) . 'services/wpcf7/wp_client_reports_pro_wpcf7.php';
    }

    $caldera_forms_enabled = get_option('wp_client_reports_pro_enable_caldera_forms');
    if ( class_exists( 'Caldera_Forms' ) && $caldera_forms_enabled == 'on' ) {
        require_once plugin_dir_path( __FILE__ ) . 'services/caldera-forms/wp_client_reports_pro_caldera_forms.php';
    }

}


/**
 * On plugin activation create the database tables needed to store form views
 */
register_activation_hook( __FILE__, 'wp_client_reports_pro_data_install' );
function wp_client_reports_pro_data_install() {
	global $wpdb;
	global $wp_client_reports_pro_version;

	$form_daily_views_table_name = $wpdb->prefix . 'form_views_daily';

	$charset_collate = $wpdb->get_charset_collate();

	$form_daily_views_sql = "CREATE TABLE $form_daily_views_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		date date DEFAULT '0000-00-00' NOT NULL,
        plugin varchar(191),
        form_id mediumint(9),
        count mediumint(9),
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $form_daily_views_sql );

	add_option( 'wp_client_reports_pro_version', WP_CLIENT_REPORTS_PRO_VERSION );
}


/**
 * Register the pro license settings that will be added to the settings page
 */
add_action( 'admin_init', 'wp_client_reports_pro_license_init', 999 );
function wp_client_reports_pro_license_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_license', 'wp_client_reports_pro_license_save' );
    
    add_settings_section(
		'wp_client_reports_pro_plugin_settings_section',
		__( 'Plugin Settings', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_license',
		__( 'WP Client Reports Pro License', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_license_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_plugin_settings_section'
    );

}


/**
 * Add license field to the options page
 */
function wp_client_reports_pro_license_render(  ) {
    $license = get_option( 'wp_client_reports_pro_license' );
    $status  = get_option( 'wp_client_reports_pro_license_status' );
	?>
	<input type='text' name='wp_client_reports_pro_license' value='<?php echo $license; ?>'class="regular-text">
    <?php if( $license !== false && !empty($license) ) { ?>
        <div style="margin-top:10px;">
        <?php if( $status !== false && $status == 'valid' ) { ?>
            <span class="wp-client-reports-pro-active-license"><?php _e('active'); ?></span>
            <?php wp_nonce_field( 'wp_client_reports_pro_nonce', 'wp_client_reports_pro_nonce' ); ?>
            <input type="submit" class="button-secondary" name="wp_client_reports_pro_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
        <?php } else {
            wp_nonce_field( 'wp_client_reports_pro_nonce', 'wp_client_reports_pro_nonce' ); ?>
            <input type="submit" class="button-secondary" name="wp_client_reports_pro_license_activate" value="<?php _e('Activate License'); ?>"/>
        <?php } ?>
        </div>
    <?php
    }
}


/**
 * Save the license field
 */
function wp_client_reports_pro_license_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_license' );
	if( $old && $old != $new ) {
		delete_option( 'wp_client_reports_pro_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}


/**
 * Activate license key
 */
add_action('admin_init', 'wp_client_reports_pro_activate_license');
function wp_client_reports_pro_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wp_client_reports_pro_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wp_client_reports_pro_nonce', 'wp_client_reports_pro_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wp_client_reports_pro_license' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( WP_CLIENT_REPORTS_PRO_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( WP_CLIENT_REPORTS_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled' :
					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = __( 'This appears to be an invalid license key for WP Client Reports Pro.' );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'options-general.php?page=wp_client_reports' );
			$redirect = add_query_arg( array( 'wp_client_reports_pro_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'wp_client_reports_pro_license_status', $license_data->license );
		wp_redirect( admin_url( 'options-general.php?page=wp_client_reports' ) );
		exit();
	}
}


/**
 * Deactivate license key
 */
add_action('admin_init', 'wp_client_reports_pro_deactivate_license');
function wp_client_reports_pro_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wp_client_reports_pro_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wp_client_reports_pro_nonce', 'wp_client_reports_pro_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wp_client_reports_pro_license' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( WP_CLIENT_REPORTS_PRO_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( WP_CLIENT_REPORTS_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

			$base_url = admin_url( 'options-general.php?page=wp_client_reports' );
			$redirect = add_query_arg( array( 'wp_client_reports_pro_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'wp_client_reports_pro_license_status' );
		}

		wp_redirect( admin_url( 'options-general.php?page=wp_client_reports' ) );
		exit();

	}
}


/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
add_action( 'admin_notices', 'wp_client_reports_pro_admin_notices' );
function wp_client_reports_pro_admin_notices() {

	if ( isset( $_GET['wp_client_reports_pro_activation'] ) && ! empty( $_GET['message'] ) ) {

        $message = sanitize_text_field(urldecode( $_GET['message']));

		switch( $_GET['wp_client_reports_pro_activation'] ) {

			case 'false':
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}


/**
 * Register the pro settings that will be added to the options page
 */
add_action( 'admin_init', 'wp_client_reports_pro_options_init', 12 );
function wp_client_reports_pro_options_init(  ) {

    //Advanced Email Settings
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_auto_send_period', 'wp_client_reports_pro_auto_send_period_save' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_auto_send_day', 'wp_client_reports_pro_auto_send_day_save' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_auto_send_time', 'wp_client_reports_pro_auto_send_time_save' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_auto_send_type', 'wp_client_reports_pro_auto_send_time_type' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_logo' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_color' );

    add_settings_field(
		'wp_client_reports_pro_auto_send_period',
		__( 'Site Report Automatic Send', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_auto_send_period_render',
		'wp_client_reports_options_page',
		'wp_client_reports_email_section'
    );

    add_settings_field(
		'wp_client_reports_pro_logo',
		__( 'Site Report Logo', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_logo_render',
		'wp_client_reports_options_page',
		'wp_client_reports_email_section'
    );

    add_settings_field(
		'wp_client_reports_pro_color',
		__( 'Site Report Primary Color', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_color_render',
		'wp_client_reports_options_page',
		'wp_client_reports_email_section'
    );

    // Notes

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_notes' );

    add_settings_section(
		'wp_client_reports_pro_notes_section',
		__( 'Site Maintenance Notes', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_enable_ga',
		__( 'Enable Site Maintenance Notes', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_enable_notes_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_notes_section'
    );

    // Google Analytics

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_ga' );

    add_settings_section(
		'wp_client_reports_pro_ga_section',
		__( 'Google Analytics', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_enable_ga',
		__( 'Enable Google Analytics', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_enable_ga_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_ga_section'
    );
    
    //Uptime Robot

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_uptime_robot' );

    add_settings_section(
		'wp_client_reports_pro_uptime_robot_section',
		__( 'Uptime Robot', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_enable_uptime_robot',
		__( 'Enable Uptime Robot', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_enable_uptime_robot_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_uptime_robot_section'
    );

    //Pingdom

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_pingdom' );

    add_settings_section(
		'wp_client_reports_pro_pingdom_section',
		__( 'Pingdom', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_enable_pingdom',
		__( 'Enable Pingdom', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_enable_pingdom_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_pingdom_section'
    );

    //Mailchimp

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_mailchimp' );

    add_settings_section(
		'wp_client_reports_pro_mailchimp_section',
		__( 'Mailchimp', 'wp-client-reports-pro' ),
		'wp_client_reports_settings_section_callback',
		'wp_client_reports_options_page'
    );
    
    add_settings_field(
		'wp_client_reports_pro_enable_mailchimp',
		__( 'Enable Mailchimp', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_enable_mailchimp_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_mailchimp_section'
    );

    //Gravity Forms

    if ( class_exists( 'GFCommon' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_gravity_forms' );

        add_settings_section(
            'wp_client_reports_pro_gravity_forms_section',
            __( 'Gravity Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_gravity_forms',
            __( 'Enable Gravity Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_gravity_forms_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_gravity_forms_section'
        );

    }

    //Ninja Forms

    if ( class_exists( 'Ninja_Forms' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_ninja_forms' );

        add_settings_section(
            'wp_client_reports_pro_ninja_forms_section',
            __( 'Ninja Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_ninja_forms',
            __( 'Enable Ninja Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_ninja_forms_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_ninja_forms_section'
        );

    }

    //WPForms

    if ( class_exists( 'WPForms' ) && \wpforms()->pro ) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_wpforms' );

        add_settings_section(
            'wp_client_reports_pro_wpforms_section',
            __( 'WPForms', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_wpforms',
            __( 'Enable WPForms', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_wpforms_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_wpforms_section'
        );

    }

    //Formidable

    if ( class_exists( 'FrmAppHelper' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_formidable' );

        add_settings_section(
            'wp_client_reports_pro_formidable_section',
            __( 'Formidable', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_formidable',
            __( 'Enable Formidable', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_formidable_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_formidable_section'
        );

    }

    //Contact Form 7

    if ( class_exists( 'WPCF7' ) && (function_exists('cfdb7_init') || function_exists('flamingo_init')) ) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_wpcf7' );

        add_settings_section(
            'wp_client_reports_pro_wpcf7_section',
            __( 'Contact Form 7', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_wpcf7',
            __( 'Enable Contact Form 7', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_wpcf7_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_wpcf7_section'
        );

    }

    //Caldera Forms

    if ( class_exists( 'Caldera_Forms' ) ) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_caldera_forms' );

        add_settings_section(
            'wp_client_reports_pro_caldera_forms_section',
            __( 'Caldera Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );
        
        add_settings_field(
            'wp_client_reports_pro_enable_caldera_forms',
            __( 'Enable Caldera Forms', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_caldera_forms_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_caldera_forms_section'
        );

    }

    //WooCommerce

    if ( class_exists( 'WooCommerce' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_woocommerce' );

        add_settings_section(
            'wp_client_reports_pro_woocommerce_section',
            __( 'WooCommerce', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_woocommerce',
            __( 'Enable WooCommerce', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_woocommerce_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_woocommerce_section'
        );

    }

    //Easy Digital Downloads

    if ( class_exists( 'Easy_Digital_Downloads' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_edd' );

        add_settings_section(
            'wp_client_reports_pro_edd_section',
            __( 'Easy Digital Downloads', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_edd',
            __( 'Enable Easy Digital Downloads', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_edd_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_edd_section'
        );

    }


    //GiveWP

    if ( class_exists( 'Give' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_givewp' );

        add_settings_section(
            'wp_client_reports_pro_givewp_section',
            __( 'GiveWP', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_givewp',
            __( 'Enable GiveWP', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_givewp_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_givewp_section'
        );

    }


    //Stripe

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_stripe' );

    add_settings_section(
        'wp_client_reports_pro_stripe_section',
        __( 'Stripe', 'wp-client-reports-pro' ),
        'wp_client_reports_settings_section_callback',
        'wp_client_reports_options_page'
    );

    add_settings_field(
        'wp_client_reports_pro_enable_stripe',
        __( 'Enable Stripe', 'wp-client-reports-pro' ),
        'wp_client_reports_pro_enable_stripe_render',
        'wp_client_reports_options_page',
        'wp_client_reports_pro_stripe_section'
    );
    

    //UpdraftPlus

    if ( class_exists( 'UpdraftPlus' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_updraftplus' );

        add_settings_section(
            'wp_client_reports_pro_updraftplus_section',
            __( 'UpdraftPlus', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_updraftplus',
            __( 'Enable UpdraftPlus', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_updraftplus_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_updraftplus_section'
        );

    }

    //BackWPup

    if ( class_exists( 'BackWPup' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_backwpup' );

        add_settings_section(
            'wp_client_reports_pro_backwpup_section',
            __( 'BackWPup', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_backwpup',
            __( 'Enable BackWPup', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_backwpup_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_backwpup_section'
        );

    }

    //BackupBuddy

    if ( class_exists( 'pb_backupbuddy' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_backupbuddy' );

        add_settings_section(
            'wp_client_reports_pro_backupbuddy_section',
            __( 'BackupBuddy', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_backupbuddy',
            __( 'Enable BackupBuddy', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_backupbuddy_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_backupbuddy_section'
        );

    }

    //WP Engine Backups

    if ( defined( 'WPE_APIKEY' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_wpengine_backups' );

        add_settings_section(
            'wp_client_reports_pro_wpengine_backups_section',
            __( 'WPEngine Backups', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_wpengine_backups',
            __( 'Enable WPEngine Backups', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_wpengine_backups_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_wpengine_backups_section'
        );

    }

    //SearchWP

    if ( class_exists( 'SearchWP' )) {

        register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_enable_searchwp' );

        add_settings_section(
            'wp_client_reports_pro_searchwp_section',
            __( 'SearchWP', 'wp-client-reports-pro' ),
            'wp_client_reports_settings_section_callback',
            'wp_client_reports_options_page'
        );

        add_settings_field(
            'wp_client_reports_pro_enable_searchwp',
            __( 'Enable SearchWP', 'wp-client-reports-pro' ),
            'wp_client_reports_pro_enable_searchwp_render',
            'wp_client_reports_options_page',
            'wp_client_reports_pro_searchwp_section'
        );

    }

}


/**
 * Add color field to the options page
 */
function wp_client_reports_pro_auto_send_period_render() { 
    $send_period = get_option( 'wp_client_reports_pro_auto_send_period', 'never' );
    $send_day = get_option( 'wp_client_reports_pro_auto_send_day', null );
    $send_time = get_option( 'wp_client_reports_pro_auto_send_time', null );
    $send_type = get_option( 'wp_client_reports_pro_auto_send_type', null );
    $time_format = get_option('time_format', 'g:i a');
    $military_time = false;
    if(strpos($time_format, 'H') !== false || strpos($time_format, 'h') !== false) {
        $military_time = true;
    }
	?>
    <select name="wp_client_reports_pro_auto_send_period" id="wp_client_reports_pro_auto_send_period">
        <option value="never" <?php if ($send_period == 'never') { echo "selected"; } ?>><?php _e( 'Never', 'wp-client-reports-pro' ); ?></option>
        <option value="weekly" <?php if ($send_period == 'weekly') { echo "selected"; } ?>><?php _e( 'Weekly', 'wp-client-reports-pro' ); ?></option>
        <option value="monthly" <?php if ($send_period == 'monthly') { echo "selected"; } ?>><?php _e( 'Monthly', 'wp-client-reports-pro' ); ?></option>
    </select>
    <?php $send_weekly_display = ''; $send_weekly_name = 'name="wp_client_reports_pro_auto_send_day"'; if ($send_period !== 'weekly') { $send_weekly_display = 'display:none;'; $send_weekly_name = ''; } ?>
    <select <?php echo $send_weekly_name; ?> id="wp_client_reports_pro_auto_send_day_weekly" style="<?php echo $send_weekly_display; ?>">
        <option value="sunday" <?php if ($send_day == 'sunday') { echo "selected"; } ?>><?php _e( 'Sunday', 'wp-client-reports-pro' ); ?></option>
        <option value="monday" <?php if ($send_day == 'monday') { echo "selected"; } ?>><?php _e( 'Monday', 'wp-client-reports-pro' ); ?></option>
        <option value="tuesday" <?php if ($send_day == 'tuesday') { echo "selected"; } ?>><?php _e( 'Tuesday', 'wp-client-reports-pro' ); ?></option>
        <option value="wednesday" <?php if ($send_day == 'wednesday') { echo "selected"; } ?>><?php _e( 'Wednesday', 'wp-client-reports-pro' ); ?></option>
        <option value="thursday" <?php if ($send_day == 'thursday') { echo "selected"; } ?>><?php _e( 'Thursday', 'wp-client-reports-pro' ); ?></option>
        <option value="friday" <?php if ($send_day == 'friday') { echo "selected"; } ?>><?php _e( 'Friday', 'wp-client-reports-pro' ); ?></option>
        <option value="saturday" <?php if ($send_day == 'saturday') { echo "selected"; } ?>><?php _e( 'Saturday', 'wp-client-reports-pro' ); ?></option>
    </select>
    <?php $send_monthly_display = ''; $send_monthly_name = 'name="wp_client_reports_pro_auto_send_day"'; if ($send_period !== 'monthly') { $send_monthly_display = 'display:none;'; $send_monthly_name = ''; } ?>
    <select <?php echo $send_monthly_name; ?> id="wp_client_reports_pro_auto_send_day_monthly" style="<?php echo $send_monthly_display; ?>">
        <option value="01" <?php if ($send_day == '01') { echo "selected"; } ?>>1</option>
        <option value="02" <?php if ($send_day == '02') { echo "selected"; } ?>>2</option>
        <option value="03" <?php if ($send_day == '03') { echo "selected"; } ?>>3</option>
        <option value="04" <?php if ($send_day == '04') { echo "selected"; } ?>>4</option>
        <option value="05" <?php if ($send_day == '05') { echo "selected"; } ?>>5</option>
        <option value="06" <?php if ($send_day == '06') { echo "selected"; } ?>>6</option>
        <option value="07" <?php if ($send_day == '07') { echo "selected"; } ?>>7</option>
        <option value="08" <?php if ($send_day == '08') { echo "selected"; } ?>>8</option>
        <option value="09" <?php if ($send_day == '09') { echo "selected"; } ?>>9</option>
        <option value="10" <?php if ($send_day == '10') { echo "selected"; } ?>>10</option>
        <option value="11" <?php if ($send_day == '11') { echo "selected"; } ?>>11</option>
        <option value="12" <?php if ($send_day == '12') { echo "selected"; } ?>>12</option>
        <option value="13" <?php if ($send_day == '13') { echo "selected"; } ?>>13</option>
        <option value="14" <?php if ($send_day == '14') { echo "selected"; } ?>>14</option>
        <option value="15" <?php if ($send_day == '15') { echo "selected"; } ?>>15</option>
        <option value="16" <?php if ($send_day == '16') { echo "selected"; } ?>>16</option>
        <option value="17" <?php if ($send_day == '17') { echo "selected"; } ?>>17</option>
        <option value="18" <?php if ($send_day == '18') { echo "selected"; } ?>>18</option>
        <option value="19" <?php if ($send_day == '19') { echo "selected"; } ?>>19</option>
        <option value="20" <?php if ($send_day == '20') { echo "selected"; } ?>>20</option>
        <option value="21" <?php if ($send_day == '21') { echo "selected"; } ?>>21</option>
        <option value="22" <?php if ($send_day == '22') { echo "selected"; } ?>>22</option>
        <option value="23" <?php if ($send_day == '23') { echo "selected"; } ?>>23</option>
        <option value="24" <?php if ($send_day == '24') { echo "selected"; } ?>>24</option>
        <option value="25" <?php if ($send_day == '25') { echo "selected"; } ?>>25</option>
        <option value="26" <?php if ($send_day == '26') { echo "selected"; } ?>>26</option>
        <option value="27" <?php if ($send_day == '27') { echo "selected"; } ?>>27</option>
        <option value="last" <?php if ($send_day == 'last') { echo "selected"; } ?>><?php _e( 'Last Day', 'wp-client-reports-pro' ); ?></option>
    </select>
    <?php $send_time_display = ''; $send_time_name = 'name="wp_client_reports_pro_auto_send_time"'; if ($send_period == 'never') { $send_time_display = 'display:none;'; $send_time_name = ''; } ?>
    <select <?php echo $send_time_name; ?> id="wp_client_reports_pro_auto_send_time" style="<?php echo $send_time_display; ?>">
        <option value="0" <?php if ($send_time == '0') { echo "selected"; } ?>><?php echo date($time_format, strtotime('12:00 AM')); ?></option>
        <option value="1" <?php if ($send_time == '1') { echo "selected"; } ?>><?php echo date($time_format, strtotime('1:00 AM')); ?></option>
        <option value="2" <?php if ($send_time == '2') { echo "selected"; } ?>><?php echo date($time_format, strtotime('2:00 AM')); ?></option>
        <option value="3" <?php if ($send_time == '3') { echo "selected"; } ?>><?php echo date($time_format, strtotime('3:00 AM')); ?></option>
        <option value="4" <?php if ($send_time == '4') { echo "selected"; } ?>><?php echo date($time_format, strtotime('4:00 AM')); ?></option>
        <option value="5" <?php if ($send_time == '5') { echo "selected"; } ?>><?php echo date($time_format, strtotime('5:00 AM')); ?></option>
        <option value="6" <?php if ($send_time == '6') { echo "selected"; } ?>><?php echo date($time_format, strtotime('6:00 AM')); ?></option>
        <option value="7" <?php if ($send_time == '7') { echo "selected"; } ?>><?php echo date($time_format, strtotime('7:00 AM')); ?></option>
        <option value="8" <?php if ($send_time == '8') { echo "selected"; } ?>><?php echo date($time_format, strtotime('8:00 AM')); ?></option>
        <option value="9" <?php if ($send_time == '9') { echo "selected"; } ?>><?php echo date($time_format, strtotime('9:00 AM')); ?></option>
        <option value="10" <?php if ($send_time == '10') { echo "selected"; } ?>><?php echo date($time_format, strtotime('10:00 AM')); ?></option>
        <option value="11" <?php if ($send_time == '11') { echo "selected"; } ?>><?php echo date($time_format, strtotime('11:00 AM')); ?></option>
        <option value="12" <?php if ($send_time == '12') { echo "selected"; } ?>><?php echo date($time_format, strtotime('12:00 PM')); ?></option>
        <option value="13" <?php if ($send_time == '13') { echo "selected"; } ?>><?php echo date($time_format, strtotime('1:00 PM')); ?></option>
        <option value="14" <?php if ($send_time == '14') { echo "selected"; } ?>><?php echo date($time_format, strtotime('2:00 PM')); ?></option>
        <option value="15" <?php if ($send_time == '15') { echo "selected"; } ?>><?php echo date($time_format, strtotime('3:00 PM')); ?></option>
        <option value="16" <?php if ($send_time == '16') { echo "selected"; } ?>><?php echo date($time_format, strtotime('4:00 PM')); ?></option>
        <option value="17" <?php if ($send_time == '17') { echo "selected"; } ?>><?php echo date($time_format, strtotime('5:00 PM')); ?></option>
        <option value="18" <?php if ($send_time == '18') { echo "selected"; } ?>><?php echo date($time_format, strtotime('6:00 PM')); ?></option>
        <option value="19" <?php if ($send_time == '19') { echo "selected"; } ?>><?php echo date($time_format, strtotime('7:00 PM')); ?></option>
        <option value="20" <?php if ($send_time == '20') { echo "selected"; } ?>><?php echo date($time_format, strtotime('8:00 PM')); ?></option>
        <option value="21" <?php if ($send_time == '21') { echo "selected"; } ?>><?php echo date($time_format, strtotime('9:00 PM')); ?></option>
        <option value="22" <?php if ($send_time == '22') { echo "selected"; } ?>><?php echo date($time_format, strtotime('10:00 PM')); ?></option>
        <option value="23" <?php if ($send_time == '23') { echo "selected"; } ?>><?php echo date($time_format, strtotime('11:00 PM')); ?></option>
    </select>
    <?php $send_type_display = ''; $send_type_name = 'name="wp_client_reports_pro_auto_send_type"'; if ($send_period == 'never') { $send_type_display = 'display:none;'; $send_type_name = ''; } ?>
    <select <?php echo $send_type_name; ?> id="wp_client_reports_pro_auto_send_type" style="<?php echo $send_type_display; ?>">
        <option value="endthisday" <?php if ($send_type == 'endthisday') { echo "selected"; } ?>><?php _e( 'Report End This Day', 'wp-client-reports-pro' ); ?></option>
        <option value="enddaybefore" <?php if ($send_type == 'enddaybefore') { echo "selected"; } ?>><?php _e( 'Report End Day Before', 'wp-client-reports-pro' ); ?></option>
    </select>
    <span id="wp-client-reports-pro-example-report-period">Example: <span id="wp-client-reports-pro-example-report-start"></span> - <span id="wp-client-reports-pro-example-report-end"></span></span>
    <p class="description"><?php printf( __( 'Timing may not be exact unless you do additional configuration for your site/server. %sRead more%s.', 'wp-client-reports-pro' ), '<a href="https://switchwp.com/docs/automatic-sending-issues/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank">', '</a>' ); ?></p>
    <?php
}


/**
 * Save the license field
 */
function wp_client_reports_pro_auto_send_period_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_auto_send_period' );
    if( $old !== $new ) {
		wp_client_reports_pro_schedule_auto_send();
	}
	return $new;
}

/**
 * Save the license field
 */
function wp_client_reports_pro_auto_send_day_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_auto_send_day' );
	if( $old !== $new ) {
		wp_client_reports_pro_schedule_auto_send();
	}
	return $new;
}

/**
 * Save the license field
 */
function wp_client_reports_pro_auto_send_time_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_auto_send_time' );
	if( $old !== $new ) {
		wp_client_reports_pro_schedule_auto_send();
	}
	return $new;
}


function wp_client_reports_pro_schedule_auto_send() {

    if (!isset($_POST['wp_client_reports_pro_auto_send_ran_once'])) {

        $_POST['wp_client_reports_pro_auto_send_ran_once'] = true;

        if( isset( $_POST['wp_client_reports_pro_auto_send_period'] ) ) {
            $send_period = sanitize_text_field($_POST['wp_client_reports_pro_auto_send_period']);
        } else {
            $send_period = get_option( 'wp_client_reports_pro_auto_send_period', 'never' );
        }
        if( isset( $_POST['wp_client_reports_pro_auto_send_day'] ) ) {
            $send_day = sanitize_text_field($_POST['wp_client_reports_pro_auto_send_day']);
        } else {
            $send_day = get_option( 'wp_client_reports_pro_auto_send_day', null );
        }
        if( isset( $_POST['wp_client_reports_pro_auto_send_time'] ) ) {
            $send_time = sanitize_text_field($_POST['wp_client_reports_pro_auto_send_time']);
        } else {
            $send_time = get_option( 'wp_client_reports_pro_auto_send_time', null );
        }

        if ($send_period == 'never') {

            wp_unschedule_hook( 'wp_client_reports_pro_auto_send' );

        } else if (($send_period == 'weekly' || $send_period == 'monthly') && $send_day !== null && $send_time !== null) {
            
            $timezone = wp_timezone();
            $now = new DateTime("now", $timezone);
            $next_send_date_object = null;

            //Only future dates for wp_schedule_event!!
            if ($send_period == 'weekly') {

                $next_send_date_object = new DateTime($send_day, $timezone);
                $next_send_date_object->setTime(intval($send_time), 0, 0);
                if ($next_send_date_object->format('U') < $now->format('U')) {
                    $next_send_date_object = new DateTime('next ' . $send_day, $timezone);
                    $next_send_date_object->setTime(intval($send_time), 0, 0);
                }

            } else if ($send_period == 'monthly') {

                $next_send_date_object = new DateTime('now', $timezone);

                //Only time is important because it will run every day
                $next_send_date_object->setTime(intval($send_time), 0, 0);

                if ($next_send_date_object->format('U') < $now->format('U')) {
                    $next_send_date_object->modify('+1 day');
                }

            }
            
            $next_send_date_object->setTimezone(new DateTimeZone('UTC'));
            $next_send_date_timestamp = $next_send_date_object->format('U');

            $schedule_period = $send_period;
            if ($send_period == 'monthly') {
                $schedule_period = 'daily';
            }

            wp_unschedule_hook( 'wp_client_reports_pro_auto_send' );
            wp_schedule_event( $next_send_date_timestamp, $schedule_period, 'wp_client_reports_pro_auto_send' );

        }

    }
}


/**
 * Loop through each type of update and determine if there is now a newer version
 */
add_action( 'wp_client_reports_pro_auto_send', 'wp_client_reports_pro_auto_send' );
function wp_client_reports_pro_auto_send() {

    wp_client_reports_check_for_updates();

    $send_today = false;
    $timezone = wp_timezone();

    $now = new DateTime('now', $timezone);

    //NOT USED ANY MORE
    //$send_timestamp = wp_next_scheduled( 'wp_client_reports_pro_auto_send' );
    // $send_time_object = new DateTime('@' . $send_timestamp);
    // $send_time_object->setTimezone($timezone);

    $send_period = get_option( 'wp_client_reports_pro_auto_send_period', 'never' );
    $send_day = get_option( 'wp_client_reports_pro_auto_send_day', null );
    $send_day_orig_value = $send_day;
    $send_time = get_option( 'wp_client_reports_pro_auto_send_time', null );
    $send_type = get_option( 'wp_client_reports_pro_auto_send_type', null );

    $end_date_object = null;
    $start_date_object = null;

    if ($send_period == 'weekly') {

        $send_today = true;

        $end_date_object = new DateTime('now', $timezone);

        // if ($send_time_object->format('l') !== $end_date_object->format('l')) {
        //     $end_date_object = new DateTime('last ' . $send_day, $timezone);
        // }
        
        if ($send_type == 'enddaybefore') {
            $end_date_object->modify('-1 day');
        }

        $start_date_object = clone $end_date_object;
        $start_date_object->modify('-7 days');

    } else if ($send_period == 'monthly') {

        if ($send_day == 'last') {
            $send_day = $now->format('t');
        }

        //Decide if today is the right day to send the report
        if ($now->format('j') == $send_day) {

            $send_today = true;

            $end_date_object = new DateTime('now', $timezone);

            if ($send_type == 'enddaybefore') {
                $end_date_object->modify('-1 day');
            }

            $month = $now->format('m');
            $year = $now->format('Y');

            if ($send_day_orig_value == 'last') {
                $start_send_date = 1;
            } else {
                $month = $month - 1;
                if ($month == 0) {
                    $month = 12;
                    $year = $year - 1;
                }
                $start_send_date = $send_day + 1;
            }

            $start_date_object = new DateTime($year . "-" . $month . "-" . $start_send_date, $timezone);

        }
        
    }

    if ($send_today && $end_date_object !== null && $start_date_object !== null) {
        wp_client_reports_send_email_report($start_date_object->format('Y-m-d'), $end_date_object->format('Y-m-d'));
    }

}


/**
 * Add the logo before the start of the email
 */
add_action('wp_client_reports_stats_email_before', 'wp_client_reports_pro_stats_email_add_logo');
function wp_client_reports_pro_stats_email_add_logo() {

    $image_id = get_option( 'wp_client_reports_pro_logo' );
    $image = '';
    if( intval( $image_id ) > 0 ) {
        $image = wp_get_attachment_image( $image_id, 'medium', false );
    }
    if($image) { ?>
            <!-- start copy -->
            <tr>
                <td bgcolor="#ffffff" align="left" style="padding: 10px 40px 20px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 16px; line-height: 24px;text-align:center;">
                    <?php echo $image; ?>
                </td>
            </tr>
            <!-- end copy -->
    <?php }
}


/**
 * Add logo upload field to the options page
 */
function wp_client_reports_pro_logo_render(  ) {
    $image_id = get_option( 'wp_client_reports_pro_logo' );
    $image = '';
    if( intval( $image_id ) > 0 ) {
        // Change with the image size you want to use
        $image = wp_get_attachment_image( $image_id, 'medium', false, array( 'id' => 'wp-client-reports-pro-logo-preview' ) );
    } else {
        // Some default image
        $image = '<img id="wp-client-reports-pro-logo-preview" src="" style="display:none" />';
    }
    echo $image; ?>
    <div style="margin-top:5px;">
        <input type="hidden" name="wp_client_reports_pro_logo" id="wp_client_reports_pro_logo" value="<?php echo esc_attr( $image_id ); ?>" class="regular-text" />
        <input type='button' class="button-primary" value="<?php esc_attr_e( 'Select image', 'wp-client-reports-pro' ); ?>" id="wp_client_reports_pro_logo_media_manager"/>
    </div>
    <?php
}


/**
 * Add color field to the options page
 */
function wp_client_reports_pro_color_render() { 
    $option = get_option( 'wp_client_reports_pro_color', '#007cba' );
	?>
    <input type="text" name="wp_client_reports_pro_color" value="<?php echo $option; ?>" class="wp-client-reports-pro-color-picker" >
    <?php
}


// Ajax action to refresh the user image
add_action( 'wp_ajax_wp_client_reports_pro_get_image', 'wp_client_reports_pro_get_image'   );
function wp_client_reports_pro_get_image() {
    if(isset($_GET['id']) ){
        $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'wp-client-reports-pro-logo-preview' ) );
        $data = array(
            'image'    => $image,
        );
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }
}


/**
 * Enable Notes Toggle Switch
 */
function wp_client_reports_pro_enable_notes_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_notes' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_notes" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/site-maintenance-notes/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Google Analytics Toggle Switch
 */
function wp_client_reports_pro_enable_ga_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_ga' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_ga" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/google-analytics/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
        <div><a href="https://switchwp.com/docs/set-up-google-analytics/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Setup Instructions', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Uptime Robot Toggle Switch
 */
function wp_client_reports_pro_enable_uptime_robot_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_uptime_robot' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_uptime_robot" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/uptime-robot/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
        <div><a href="https://switchwp.com/docs/set-up-uptime-robot/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Setup Instructions', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Pingdom Toggle Switch
 */
function wp_client_reports_pro_enable_pingdom_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_pingdom' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_pingdom" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/pingdom/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
        <div><a href="https://switchwp.com/docs/set-up-pingdom/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Setup Instructions', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Mailchimp Toggle Switch
 */
function wp_client_reports_pro_enable_mailchimp_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_mailchimp' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_mailchimp" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/mailchimp/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
        <div><a href="https://switchwp.com/docs/set-up-mailchimp/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Setup Instructions', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Gravity Forms Toggle Switch
 */
function wp_client_reports_pro_enable_gravity_forms_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_gravity_forms' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_gravity_forms" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/gravity-forms/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Ninja Forms Toggle Switch
 */
function wp_client_reports_pro_enable_ninja_forms_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_ninja_forms' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_ninja_forms" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/ninja-forms/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable WPForms Toggle Switch
 */
function wp_client_reports_pro_enable_wpforms_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_wpforms' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_wpforms" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/wpforms/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Formidable Toggle Switch
 */
function wp_client_reports_pro_enable_formidable_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_formidable' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_formidable" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/formidable-forms/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Contact Form 7 Toggle Switch
 */
function wp_client_reports_pro_enable_wpcf7_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_wpcf7' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_wpcf7" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/contact-form-7/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Caledera Forms Toggle Switch
 */
function wp_client_reports_pro_enable_caldera_forms_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_caldera_forms' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_caldera_forms" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/caldera-forms/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable WooCommerce Toggle Switch
 */
function wp_client_reports_pro_enable_woocommerce_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_woocommerce' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_woocommerce" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/woocommerce/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Easy Digital Downloads Toggle Switch
 */
function wp_client_reports_pro_enable_edd_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_edd' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_edd" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/easy-digital-downloads/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable GiveWP Toggle Switch
 */
function wp_client_reports_pro_enable_givewp_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_givewp' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_givewp" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/givewp/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable Stripe Toggle Switch
 */
function wp_client_reports_pro_enable_stripe_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_stripe' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_stripe" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/stripe/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable UpdraftPlus Toggle Switch
 */
function wp_client_reports_pro_enable_updraftplus_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_updraftplus' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_updraftplus" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/updraftplus/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable BackWPup Toggle Switch
 */
function wp_client_reports_pro_enable_backwpup_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_backwpup' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_backwpup" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/backwpup/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable BackupBuddy Toggle Switch
 */
function wp_client_reports_pro_enable_backupbuddy_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_backupbuddy' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_backupbuddy" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/backupbuddy/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable WPEngine Backups Toggle Switch
 */
function wp_client_reports_pro_enable_wpengine_backups_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_wpengine_backups' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_wpengine_backups" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/wpengine-backups/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Enable SearchWP Toggle Switch
 */
function wp_client_reports_pro_enable_searchwp_render(  ) {
	$option = get_option( 'wp_client_reports_pro_enable_searchwp' );
	?>
    <label class="wp-client-reports-switch">
        <input type="checkbox" name="wp_client_reports_pro_enable_searchwp" <?php if ($option == 'on') { echo "checked"; } ?>>
        <span class="wp-client-reports-slider"></span>
    </label>
    <div class="wp-client-reports-instructions">
        <div><a href="https://switchwp.com/plugins/wp-client-reports/searchwp/?utm_source=wordpress&utm_medium=plugin_settings&utm_campaign=wpclientreports" target="_blank"><?php _e( 'Learn More', 'wp-client-reports-pro' ); ?></a></div>
    </div>
	<?php
}


/**
 * Ajax call for tracking form views
 */
add_action('wp_ajax_wp_client_reports_pro_form_view', 'wp_client_reports_pro_form_view');
add_action('wp_ajax_nopriv_wp_client_reports_pro_form_view', 'wp_client_reports_pro_form_view');
function wp_client_reports_pro_form_view() {

    $form_id = sanitize_text_field($_POST['form_id']);
    $plugin = sanitize_key($_POST['plugin']);

    if (!$form_id || !$plugin) {
        return;
    }

    $form_id = apply_filters( 'wp_client_reports_pro_form_id_pre_save', $form_id );

    global $wpdb;
    $form_daily_views_table_name = $wpdb->prefix . 'form_views_daily';

    $timezone = wp_timezone();
    $now = new DateTime("now", $timezone);
    $mysqldate = $now->format('Y-m-d');

    $today_form_view = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $form_daily_views_table_name WHERE `plugin` = %s AND `form_id` = %d AND date = %s LIMIT 1", array($plugin, $form_id, $mysqldate) ) );

    $update_id = null;
    $count = 1;
    if ($today_form_view) {
        $update_id = $today_form_view->id;
        $existing_count = intval($today_form_view->count);
        if ($existing_count > 0) {
            $count = $existing_count + 1;
        }
    }

    $form_track = array(
        'id' => $update_id,
        'date' => $mysqldate,
        'plugin' => $plugin,
        'form_id' => $form_id,
        'count' => $count,
    );

    $wpdb->replace(
        $form_daily_views_table_name,
        $form_track,
        array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
        )
    );

    print json_encode(['status'=>'success']);
    wp_die();

}

/**
 * Filter the brand color for the pro color setting
 */
add_filter( 'wp_client_reports_brand_color', 'wp_client_reports_pro_brand_color', 10, 1 );
function wp_client_reports_pro_brand_color( $color ){
	return get_option( 'wp_client_reports_pro_color', $color );
}