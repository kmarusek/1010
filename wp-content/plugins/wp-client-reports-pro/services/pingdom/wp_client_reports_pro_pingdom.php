<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Pingdom Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_pingdom_actions', 999 );
function wp_client_reports_pro_load_pingdom_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        $pingdom_key = get_option( 'wp_client_reports_pro_pingdom_key' );
        $pingdom_check_id = get_option( 'wp_client_reports_pro_pingdom_check_id' );
        if ($pingdom_key && $pingdom_check_id) {
            add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_pingdom', 41);
            add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_pingdom', 41, 2);
            add_action('wp_ajax_wp_client_reports_pro_pingdom_data', 'wp_client_reports_pro_pingdom_data');
        }

    }

}


/**
 * Register the options that will be available on the options page
 */
add_action( 'admin_init', 'wp_client_reports_pro_pingdom_options_init', 13 );
function wp_client_reports_pro_pingdom_options_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_pingdom_key', 'wp_client_reports_pro_pingdom_key_save' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_pingdom_check_id' );
    
    add_settings_field(
		'wp_client_reports_pro_pingdom_key',
		__( 'Pingdom API Key', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_pingdom_key_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_pingdom_section'
    );

}


/**
 * Save the pingdom api key field
 */
function wp_client_reports_pro_pingdom_key_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_pingdom_key' );
	if( $old && $old != $new ) {
		delete_option('wp_client_reports_pro_pingdom_check_id');
        wp_client_reports_delete_transients('wp_client_reports_pingdom');
	}
	return $new;
}


/**
 * Add Pingdom API key field to the options page
 */
function wp_client_reports_pro_pingdom_key_render(  ) {
    $pingdom_key = get_option( 'wp_client_reports_pro_pingdom_key' );
    $pingdom_check_id = get_option( 'wp_client_reports_pro_pingdom_check_id' );
	?>
	<input type='text' name='wp_client_reports_pro_pingdom_key' value='<?php echo $pingdom_key; ?>'class="regular-text">
	<?php
    if ($pingdom_key) { 
        $pingdom_checks = wp_client_reports_pro_get_pingdom_checks();
        ?>
            <tr>
                <th scope="row">Pingdom Checks</th>
                <td>
                <?php if ($pingdom_checks && is_array($pingdom_checks)) { ?>
                    <select name="wp_client_reports_pro_pingdom_check_id">
                        <?php foreach($pingdom_checks as $check): ?>
                            <option value="<?php echo $check->id; ?>" <?php if ($check->id == $pingdom_check_id) { echo 'selected'; } ?>><?php echo $check->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php } else { ?>
                        No checks found in your pingdom account
                    <?php } ?>
                </td>
            </tr>
        <?php
    }
}


/**
 * Ajax request report data for Pingdom
 */
function wp_client_reports_pro_pingdom_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_pingdom_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get report data for Pingdom
 */
function wp_client_reports_pro_get_pingdom_data($start_date, $end_date) {

    $date_format = get_option('date_format');
    
    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $pingdom_data = get_transient('wp_client_reports_pingdom_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($pingdom_data === false) {

        $pingdom_data = new stdClass;
        $pingdom_data->uptime = 0;
        $pingdom_data->down_events_count = 0;
        $pingdom_data->down_events = [];
        $pingdom_data->daysup = "-";

        $pingdom_api_key = get_option( 'wp_client_reports_pro_pingdom_key' );

        $query_data = [
            'from' => $start_date_timestamp,
            'to' => $end_date_timestamp,
        ];
    
        $query = http_build_query($query_data);

        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $pingdom_api_key 
            )
        );

        $events_response = wp_remote_get( 'https://api.pingdom.com/api/3.1/summary.outage/5730958?' . $query, $args );
        
        if ( is_wp_error( $events_response ) ) {
           $error_message = $events_response->get_error_message();
           echo "Something went wrong: $error_message";
        } else {
            $pingdom_events = json_decode($events_response['body']);

            $down_events_count = 0;
            $down_events = [];
            if ($pingdom_events && isset($pingdom_events->summary->states)) {
                foreach($pingdom_events->summary->states as $state) {
                    if ($state->status == 'down') {
                        $down_events_count++;

                        $down_event = new stdClass;
                        $down_event->type = 'Down';
                        $down_event->date = date($date_format, $state->timefrom);
                        $duration = $state->timeto - $state->timefrom;
                        if ($duration > 86400) {
                            $downtime = round($duration / 60 / 60 / 24);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Days';
                        } else if ($duration > 3600) {
                            $downtime = round($duration / 60 / 60);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Hours';
                        } else {
                            $downtime = round($duration / 60);
                            $down_event->downtime = $downtime;
                            $down_event->downtime_pretty = $downtime . ' Minutes';
                        }

                        $down_events[] = $down_event;
                    }
                }
            }

            if (isset($pingdom_events->summary->states[0]->timeto)) {
                $last_up_event = $pingdom_events->summary->states[0]->timeto;
                $now = time();
                $datediff = $now - $last_up_event;
                $days_since_last_up_event = round($datediff / (60 * 60 * 24));

                $pingdom_data->down_events_count = $down_events_count;
                $pingdom_data->down_events = $down_events;
                $pingdom_data->daysup = $days_since_last_up_event;
            }

        }

        $query_data['includeuptime'] = 'true';

        $average_query = http_build_query($query_data);
        
        $average_response = wp_remote_get( 'https://api.pingdom.com/api/3.1/summary.average/5730958?' . $average_query, $args );

        if ( is_wp_error( $average_response ) ) {
            $error_message = $average_response->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            $pingdom_average = json_decode($average_response['body']);

            $totalup = $pingdom_average->summary->status->totalup;
            $totaldown = $pingdom_average->summary->status->totaldown;
            $totalunknown = $pingdom_average->summary->status->totalunknown;
            $total_up_and_down = $totalup + $totaldown;

            if ($total_up_and_down > 0) {
                $uptime_percent = abs((($totaldown * 100) / $total_up_and_down) - 100);
                $pingdom_data->uptime = round($uptime_percent, 2);
            }

        }

        set_transient('wp_client_reports_data_pingdom_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $pingdom_data, 3600 * 24);
    }

    $pingdom_data = apply_filters( 'wp_client_reports_pro_pingdom_data', $pingdom_data, $start_date, $end_date );

    return $pingdom_data;

}


/**
 * Get list of checks from Pingdom account
 */
function wp_client_reports_pro_get_pingdom_checks() {

    $pingdom_checks = get_transient('wp_client_reports_pingdom_checks');

    if ($pingdom_checks === false) {

        $pingdom_api_key = get_option( 'wp_client_reports_pro_pingdom_key' );

        if ($pingdom_api_key) {

            $args = array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $pingdom_api_key 
                )
            );

            $response = wp_remote_get( 'https://api.pingdom.com/api/3.1/checks', $args );

            if ( is_wp_error( $response ) ) {
                return false;
            } else {

                $pingdom_checks = json_decode($response['body']);

                if (isset($pingdom_checks->checks)) {
                    $pingdom_checks = $pingdom_checks->checks;
                    set_transient('wp_client_reports_pingdom_checks', $pingdom_checks, 3600 * 24);
                } else {
                    return false;
                }

            }

        } else {

            return false;

        }

    }

    return $pingdom_checks;

}


/**
 * Report page section for Pingdom
 */
function wp_client_reports_pro_stats_page_pingdom() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-pingdom">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Uptime','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">

                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __('Uptime', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-pingdom-uptime'
                                );
                                wp_client_reports_render_big_number(
                                    __('Events', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-pingdom-events'
                                );
                                wp_client_reports_render_big_number(
                                    __('Days Without Issue', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-pingdom-daysup'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->

                        <div class="wp-client-report-section wp-client-report-border-top">

                            <h3><?php _e('Down Events','wp-client-reports-pro'); ?></h3>
                            <ul id="wp-client-reports-pro-pingdom-events-list" class="wp-client-reports-list"></ul>

                        </div>

                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for Pingdom
 */
function wp_client_reports_pro_stats_email_pingdom($start_date, $end_date) {
    $pingdom_data = wp_client_reports_pro_get_pingdom_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Uptime', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $pingdom_data->uptime . "%", 
        __( 'Uptime', 'wp-client-reports-pro' ),
        $pingdom_data->down_events_count, 
        __( 'Events', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $pingdom_data->daysup, 
        __( 'Days Without Issue', 'wp-client-reports-pro' ),
        null,
        null
    );

    ?>
        <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 20px 40px 40px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 14px; line-height: 20px;">
                <h3 style="font-size:14px;margin:0px 0px 4px 0px;"><?php _e('Down Events','wp-client-reports'); ?></h3>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top:solid 1px #dddddd;margin-bottom:30px;">
                <?php
                if (is_array($pingdom_data->down_events) && !empty($pingdom_data->down_events)) : 
                    foreach($pingdom_data->down_events as $down_event) :
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
 * When force refresh is called, clear all transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_pingdom_update', 13 );
function wp_client_reports_force_pingdom_update() {
    wp_client_reports_delete_transients('wp_client_reports_pingdom');
}