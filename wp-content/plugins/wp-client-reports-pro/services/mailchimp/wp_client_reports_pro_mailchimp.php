<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Mailchimp Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_mailchimp_actions', 999 );
function wp_client_reports_pro_load_mailchimp_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        $mailchimp_key = get_option( 'wp_client_reports_pro_mailchimp_key' );
        $mailchimp_list_id = get_option( 'wp_client_reports_pro_mailchimp_list_id' );
        if ($mailchimp_key && $mailchimp_list_id) {
            add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_mailchimp', 70);
            add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_mailchimp', 70, 2);
            add_action('wp_ajax_wp_client_reports_pro_mailchimp_data', 'wp_client_reports_pro_mailchimp_data');
        }

    }

}


/**
 * Register the Mailchimp settings that will be available on the settings page
 */
add_action( 'admin_init', 'wp_client_reports_pro_mailchimp_options_init', 13 );
function wp_client_reports_pro_mailchimp_options_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_mailchimp_key', 'wp_client_reports_pro_mailchimp_key_save' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_mailchimp_list_id' );
    
    add_settings_field(
		'wp_client_reports_pro_mailchimp_key',
		__( 'Mailchimp Site API Key', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_mailchimp_key_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_mailchimp_section'
    );

}



/**
 * Save the mailchimp api key field
 */
function wp_client_reports_pro_mailchimp_key_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_mailchimp_key' );
	if( $old && $old != $new ) {
		delete_option('wp_client_reports_pro_mailchimp_list_id');
        wp_client_reports_delete_transients('wp_client_reports_mailchimp');
	}
	return $new;
}



/**
 * Add mailchimp key field to the options page
 */
function wp_client_reports_pro_mailchimp_key_render(  ) {
    $mailchimp_key = get_option( 'wp_client_reports_pro_mailchimp_key' );
    $mailchimp_list_id = get_option( 'wp_client_reports_pro_mailchimp_list_id' );
	?>
	<input type='text' name='wp_client_reports_pro_mailchimp_key' value='<?php echo $mailchimp_key; ?>'class="regular-text">
	<?php
    if ($mailchimp_key) { 
        $mailchimp_lists = wp_client_reports_pro_get_mailchimp_lists();
        ?>
            <tr>
                <th scope="row"><?php _e('Mailchimp Audience/List'); ?></th>
                <td>
                <?php if ($mailchimp_lists && is_array($mailchimp_lists)) { ?>
                    <select name="wp_client_reports_pro_mailchimp_list_id">
                        <?php foreach($mailchimp_lists as $list): ?>
                            <option value="<?php echo $list->id; ?>" <?php if ($list->id == $mailchimp_list_id) { echo 'selected'; } ?>><?php echo $list->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php } else { ?>
                        <?php _e('No audience/list found in your mailchimp account'); ?>
                    <?php } ?>
                </td>
            </tr>
        <?php
    }
}



/**
 * Ajax request report data for Mailchimp
 */
function wp_client_reports_pro_mailchimp_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_mailchimp_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get report data for Mailchimp
 */
function wp_client_reports_pro_get_mailchimp_lists() {

    $mailchimp_lists = get_transient('wp_client_reports_mailchimp_lists');

    if ($mailchimp_lists === false) {

        $mailchimp_api_key = get_option( 'wp_client_reports_pro_mailchimp_key' );

        if ($mailchimp_api_key) {

            $data_center = wp_client_reports_pro_mailchimp_get_datacenter($mailchimp_api_key); 

            $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/';

            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( 'user:'. $mailchimp_api_key )
                )
            );

            $response = wp_remote_get( $url, $args );

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo __( "Something went wrong: ") . $error_message;
            } else {
                $json = json_decode($response['body']);
            }

            if (isset($json->lists)) {
                $mailchimp_lists = $json->lists;
                set_transient('wp_client_reports_mailchimp_lists', $mailchimp_lists, 3600 * 24);
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    return $mailchimp_lists;

}


/**
 * Get report data for Mailchimp
 */
function wp_client_reports_pro_get_mailchimp_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');
    
    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $mailchimp_data = get_transient('wp_client_reports_mailchimp_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($mailchimp_data === false) {

        $mailchimp_api_key = get_option( 'wp_client_reports_pro_mailchimp_key' );
        $mailchimp_list_id = get_option( 'wp_client_reports_pro_mailchimp_list_id' );

        if ($mailchimp_api_key && $mailchimp_list_id) {

            $data_center = wp_client_reports_pro_mailchimp_get_datacenter($mailchimp_api_key); 

            $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $mailchimp_list_id . '/activity';

            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( 'user:'. $mailchimp_api_key )
                )
            );

            $response = wp_remote_get( $url, $args );
            try {
                $mailchimp_activity = json_decode( $response['body'] );
            } catch ( Exception $ex ) {
                $mailchimp_activity = null;
            }

            $mailchimp_data = new \stdClass;
            $mailchimp_data->subscribes = 0;
            $mailchimp_data->unsubscribes = 0;
            $mailchimp_data->total = 0;
            $mailchimp_data->warning = null;

            if ($mailchimp_activity && is_array($mailchimp_activity->activity)) {

                $mailchimp_data->total = $mailchimp_activity->total_items;

                foreach($mailchimp_activity->activity as $item) {
                    $item_date = strtotime($item->day);
                    if ($item_date >= $start_date_timestamp && $item_date <= $end_date_timestamp) {
                        $mailchimp_data->subscribes = $mailchimp_data->subscribes + $item->subs;
                        $mailchimp_data->unsubscribes = $mailchimp_data->unsubscribes + $item->unsubs;
                    }
                }
            }

            //The Mailchimp activity API only gets 180 days worth of data
            $days_difference = (time() - $start_date_timestamp) / 86400;
            if ( $days_difference > 180) {
                $mailchimp_data->warning = "The Mailchimp activity API only gets 180 days worth of data";
            }

        } else {

            return false;

        }

        set_transient('wp_client_reports_mailchimp_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $mailchimp_data, 3600 * 24);
    }

    $mailchimp_data = apply_filters( 'wp_client_reports_pro_mailchimp_data', $mailchimp_data, $start_date, $end_date );

    return $mailchimp_data;

}


/**
 * Retrieve the datacenter from within the api key string
 */
function wp_client_reports_pro_mailchimp_get_datacenter($mailchimp_api_key) {
    return substr($mailchimp_api_key,strpos($mailchimp_api_key,'-')+1);
}


/**
 * Report page section for Mailchimp
 */
function wp_client_reports_pro_stats_page_mailchimp() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-mailchimp">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Mailing List','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __('Subscribes', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailchimp-subscribes'
                                );
                                wp_client_reports_render_big_number(
                                    __('Unsubscribes', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailchimp-unsubscribes'
                                );
                                wp_client_reports_render_big_number(
                                    __('Current List Size', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailchimp-total'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->
                        <div class="error notice" id="wp-client-reports-pro-mailchimp-warning" style="display:none;"><p></p></div>
                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for Mailchimp
 */
function wp_client_reports_pro_stats_email_mailchimp($start_date, $end_date) {
    $mailchimp_data = wp_client_reports_pro_get_mailchimp_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Mailing List', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $mailchimp_data->subscribes, 
        __( 'Subscribes', 'wp-client-reports-pro' ),
        $mailchimp_data->unsubscribes, 
        __( 'Unsubscribes', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $mailchimp_data->total, 
        __( 'Current List Size', 'wp-client-reports-pro' ),
        null,
        null
    );

    if ($mailchimp_data->warning) {
    ?>
        <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 20px 40px 40px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 14px; line-height: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:30px;">
                    <tr><td style="padding:8px;border:solid 1px #dddddd; border-left:solid 4px #dc3232;"><?php echo esc_html($mailchimp_data->warning); ?></td></tr>
                </table>
            </td>
        </tr>
    <?php
    }
    
}


/**
 * When force refresh is called, clear all transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_mailchimp_update', 13 );
function wp_client_reports_force_mailchimp_update() {
    wp_client_reports_delete_transients('wp_client_reports_mailchimp');
}