<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load UpdraftPlus Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_wpengine_backups_actions', 999 );
function wp_client_reports_pro_load_wpengine_backups_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_wpengine_backups', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_wpengine_backups', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_wpengine_backups_data', 'wp_client_reports_pro_wpengine_backups_data');

    }

}


/**
 * Ajax request report data for wpengine-backups
 */
function wp_client_reports_pro_wpengine_backups_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_wpengine_backups_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for wpengine-backups
 */
function wp_client_reports_get_wpengine_backups_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $thirty_days_ago = new DateTime('-30 days', $timezone);

    $daydiff = $start_date_object->diff($end_date_object);
    $daydiff = $daydiff->format('%a');

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start_date_object, $interval, $end_date_object);

    $backups = 0;

    foreach ($period as $dt) {
        if ($dt > $thirty_days_ago) {
            $backups++;
        }
    }

    if ($daydiff !== "30" && $backups > 0) {
        $backups--;
    }

    $data = new stdClass;
    $data->backups = $backups;
    $data->schedule = "Daily";
    $data->stored = "30";

    $data = apply_filters( 'wp_client_reports_pro_wpengine_backups_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Report page section for wpengine-backups
 */
function wp_client_reports_pro_stats_page_wpengine_backups() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-wpengine-backups">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Backups','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-wpengine-backups-backups'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Schedule', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-wpengine-backups-schedule'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Days Stored', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-wpengine-backups-stored'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->
                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for wpengine-backups
 */
function wp_client_reports_pro_stats_email_wpengine_backups($start_date, $end_date) {
    $wpengine_backups_data = wp_client_reports_get_wpengine_backups_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Backups', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $wpengine_backups_data->backups, 
        sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ),
        $wpengine_backups_data->schedule, 
        sprintf( __( 'Schedule', 'wp-client-reports-pro' ), '<br>' )
    );

    wp_client_reports_render_email_row(
        $wpengine_backups_data->stored, 
        sprintf( __( 'Days Stored', 'wp-client-reports-pro' ), '<br>' ),
        null, 
        null
    );

}