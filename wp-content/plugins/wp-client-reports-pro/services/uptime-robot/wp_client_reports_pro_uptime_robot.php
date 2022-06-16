<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Pingdom Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_uptime_robot_actions', 999 );
function wp_client_reports_pro_load_uptime_robot_actions(){
    
    if (is_admin() || wp_doing_cron()) {

        $uptime_robot_key = get_option( 'wp_client_reports_pro_uptime_robot_key' );
        if ($uptime_robot_key) {
            add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_uptime_robot', 40);
            add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_uptime_robot', 40, 2);
            add_action('wp_ajax_wp_client_reports_pro_uptime_robot_data', 'wp_client_reports_pro_uptime_robot_data');
        }

    }

}


/**
 * Register the options that will be available on the options page
 */
add_action( 'admin_init', 'wp_client_reports_pro_uptime_robot_options_init', 13 );
function wp_client_reports_pro_uptime_robot_options_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_uptime_robot_key', 'wp_client_reports_pro_uptime_robot_key_save' );
    
    add_settings_field(
		'wp_client_reports_pro_uptime_robot_key',
		__( 'Uptime Robot Site API Key', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_uptime_robot_key_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_uptime_robot_section'
    );

}


/**
 * Save the uptime robot api key field
 */
function wp_client_reports_pro_uptime_robot_key_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_uptime_robot_key' );
	if( $old && $old != $new ) {
        wp_client_reports_delete_transients('wp_client_reports_uptime_robot');
	}
	return $new;
}


/**
 * Add default email field to the options page
 */
function wp_client_reports_pro_uptime_robot_key_render(  ) {
    $option = get_option( 'wp_client_reports_pro_uptime_robot_key' );
	?>
	<input type='text' name='wp_client_reports_pro_uptime_robot_key' value='<?php echo $option; ?>'class="regular-text">
	<?php
}


/**
 * Ajax request report data for uptime robot
 */
function wp_client_reports_pro_uptime_robot_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_uptime_robot_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get report data for uptime robot
 */
function wp_client_reports_pro_get_uptime_robot_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');
    
    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $uptime_robot_data = get_transient('wp_client_reports_uptime_robot_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($uptime_robot_data === false) {

        $date_format = get_option('date_format');

        $uptime_robot_data = new stdClass;
        $uptime_robot_data->uptime = 100;
        $uptime_robot_data->down_events_count = 0;
        $uptime_robot_data->down_events = 0;
        $uptime_robot_data->downtime = 0;
        //$uptime_robot_data->daysup = 0;

        $option = get_option( 'wp_client_reports_pro_uptime_robot_key' );

        $args = array(
            'api_key' => $option,
            'format' => 'json',
            'logs' => '1',
            'custom_uptime_ranges' => $start_date_timestamp . '_' . $end_date_timestamp,
            'logs_start_date' => $start_date_timestamp,
            'logs_end_date' => $end_date_timestamp,
        );
        
        $response = wp_remote_post( 'https://api.uptimerobot.com/v2/getMonitors', array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $args,
            'cookies' => array()
            )
        );
        
        if ( is_wp_error( $response ) ) {
           $error_message = $response->get_error_message();
           echo "Something went wrong: $error_message";
        } else {
            $uptime_robot_api_data = json_decode($response['body']);

            $down_events_count = 0;
            $downtime_seconds = 0;
            $down_events = [];
            if ($uptime_robot_api_data && isset($uptime_robot_api_data->monitors[0]->logs)) {
                foreach($uptime_robot_api_data->monitors[0]->logs as $log_entry) {
                    if ($log_entry->type == '1') {
                        $down_events_count++;

                        if (isset($log_entry->duration) && $log_entry->duration > 0) {
                            $downtime_seconds = $downtime_seconds + $log_entry->duration;
                        }
                        
                        $down_event = new stdClass;
                        $down_event->type = $log_entry->reason->detail;
                        $down_event->date = date($date_format, $log_entry->datetime);
                        if ($log_entry->duration > 86400) {
                            $downtime = round($log_entry->duration / 60 / 60 / 24);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Days';
                        } else if ($log_entry->duration > 3600) {
                            $downtime = round($log_entry->duration / 60 / 60);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Hours';
                        } else {
                            $downtime = round($log_entry->duration / 60);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Minutes';
                        }

                        $down_events[] = $down_event;

                    }
                }
            }

            if ($uptime_robot_api_data && isset($uptime_robot_api_data->monitors[0]->custom_uptime_ranges)) {
                $uptime_robot_data->uptime = round($uptime_robot_api_data->monitors[0]->custom_uptime_ranges,2);
            }

            // $days_since_last_up_event = "-";
            // if (isset($uptime_robot_api_data->monitors[0]->logs[0]->datetime)) {
            //     $last_up_event = $uptime_robot_api_data->monitors[0]->logs[0]->datetime;
            //     $now = time();
            //     $datediff = $now - $last_up_event;
            //     $days_since_last_up_event = round($datediff / (60 * 60 * 24));
            // } else {
            //     $longer_args = array(
            //         'api_key' => $option,
            //         'format' => 'json',
            //         'logs' => '1',
            //         'limit' => '1',
            //         'logs_limit' => '1',
            //     );
                
            //     $longer_response = wp_remote_post( 'https://api.uptimerobot.com/v2/getMonitors', array(
            //         'method' => 'POST',
            //         'timeout' => 45,
            //         'redirection' => 5,
            //         'httpversion' => '1.0',
            //         'blocking' => true,
            //         'headers' => array(),
            //         'body' => $longer_args,
            //         'cookies' => array()
            //         )
            //     );

            //     if ( !is_wp_error( $longer_response ) ) {
            //         $uptime_robot_longer_api_data = json_decode($longer_response['body']);
            //         if (isset($uptime_robot_longer_api_data->monitors[0]->logs[0]->datetime)) {
            //             $last_up_event = $uptime_robot_longer_api_data->monitors[0]->logs[0]->datetime;
            //             $now = time();
            //             $datediff = $now - $last_up_event;
            //             $days_since_last_up_event = round($datediff / (60 * 60 * 24));
            //         }
            //     }
            // }

            $uptime_robot_data->down_events_count = $down_events_count;
            $uptime_robot_data->down_events = $down_events;
            $uptime_robot_data->downtime = round($downtime_seconds / 60);
            //$uptime_robot_data->daysup = $days_since_last_up_event;

        }

        set_transient('wp_client_reports_uptime_robot_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $uptime_robot_data, 3600 * 24);
    }

    $uptime_robot_data = apply_filters( 'wp_client_reports_pro_uptime_robot_data', $uptime_robot_data, $start_date, $end_date );

    return $uptime_robot_data;

}


/**
 * Report page section for uptime robot
 */
function wp_client_reports_pro_stats_page_uptime_robot() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-uptime-robot">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Uptime','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">

                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __('Uptime', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-uptime-robot-uptime'
                                );
                                wp_client_reports_render_big_number(
                                    __('Events', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-uptime-robot-events'
                                );
                                wp_client_reports_render_big_number(
                                    __('Downtime Minutes', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-uptime-robot-downtime'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->

                        <div class="wp-client-report-section wp-client-report-border-top">

                            <h3><?php _e('Down Events','wp-client-reports-pro'); ?></h3>
                            <ul id="wp-client-reports-pro-uptime-robot-events-list" class="wp-client-reports-list"></ul>

                        </div>

                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for uptime robot
 */
function wp_client_reports_pro_stats_email_uptime_robot($start_date, $end_date) {
    $uptime_robot_data = wp_client_reports_pro_get_uptime_robot_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Uptime', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $uptime_robot_data->uptime . "%", 
        __( 'Uptime', 'wp-client-reports-pro' ),
        $uptime_robot_data->down_events_count, 
        __( 'Events', 'wp-client-reports-pro' )
    );

    $downtime = $uptime_robot_data->downtime;
    $downtime_label = __( 'Downtime Minutes', 'wp-client-reports-pro' );
    if ($uptime_robot_data->downtime > 1440) {
        $downtime = round($uptime_robot_data->downtime / 1440, 1);
        $downtime_label = __( 'Downtime Days', 'wp-client-reports-pro' );
    } else if ($uptime_robot_data->downtime > 60) {
        $downtime = round($uptime_robot_data->downtime / 60, 1);
        $downtime_label = __( 'Downtime Hours', 'wp-client-reports-pro' );
    }

    wp_client_reports_render_email_row(
        $downtime, 
        $downtime_label,
        null,
        null
    );

    ?>
        <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 20px 40px 40px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 14px; line-height: 20px;">
                <h3 style="font-size:14px;margin:0px 0px 4px 0px;"><?php _e('Down Events','wp-client-reports'); ?></h3>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top:solid 1px #dddddd;margin-bottom:30px;">
                <?php
                if (is_array($uptime_robot_data->down_events) && !empty($uptime_robot_data->down_events)) : 
                    foreach($uptime_robot_data->down_events as $down_event) :
                        echo '<tr><td style="width:40%;padding:8px 8px 8px 0px;border-bottom:solid 1px #dddddd;">' . esc_html($down_event->type) . '</td><td style="text-align:center;width:30%;padding:8px;border-bottom:solid 1px #dddddd;"">' . esc_html($down_event->downtime_pretty) . '</td><td style="text-align:right;width:30%;padding:8px 0px 8px 8px;border-bottom:solid 1px #dddddd;"">' . esc_html($down_event->date) . '</td>';
                    endforeach;
                else:
                    echo '<tr><td style="padding:8px 0px 8px 0px;border-bottom:solid 1px #dddddd;">' . __('No Downtime Events','wp-client-reports-pro') . '</td>';
                endif;
                ?>
                </table>
            </td>
        </tr>
    <?php
    
}


/**
 * When force refresh is called, clear all uptime robot transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_uptime_robot_update', 13 );
function wp_client_reports_force_uptime_robot_update() {
    wp_client_reports_delete_transients('wp_client_reports_uptime_robot');
}