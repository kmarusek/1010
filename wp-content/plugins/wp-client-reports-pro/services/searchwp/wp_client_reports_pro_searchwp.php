<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load SearchWP Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_searchwp_actions', 999 );
function wp_client_reports_pro_load_searchwp_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_searchwp', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_searchwp', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_searchwp_data', 'wp_client_reports_pro_searchwp_data');

    }

}


/**
 * Ajax request report data for searchwp
 */
function wp_client_reports_pro_searchwp_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_searchwp_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for searchwp
 */
function wp_client_reports_get_searchwp_data($start_date, $end_date) {

    $start_date_object = new DateTime($start_date . " 00:00:00");
    $start_date_mysqltime = $start_date_object->format('Y-m-d H:i:s');
    
    $end_date_object = new DateTime($end_date . " 23:59:59");
    $end_date_mysqltime = $end_date_object->format('Y-m-d H:i:s');

    $data = new stdClass;
    $data->total_searches = 0;
    $data->unique_searches = 0;
    $data->empty_searches = 0;

    global $wpdb;

    $swp_log_table_name = $wpdb->prefix . 'swp_log';

    $total_searches = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $swp_log_table_name WHERE `tstamp` >= %s AND `tstamp` <= %s", array($start_date_mysqltime, $end_date_mysqltime) ) );

    if (!$total_searches) {
        $total_searches = 0;
    }

    $unique_searches = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT `query`) FROM $swp_log_table_name WHERE `tstamp` >= %s AND `tstamp` <= %s", array($start_date_mysqltime, $end_date_mysqltime) ) );

    if (!$unique_searches) {
        $unique_searches = 0;
    }

    $empty_searches = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $swp_log_table_name WHERE `hits` = 0 AND `tstamp` >= %s AND `tstamp` <= %s", array($start_date_mysqltime, $end_date_mysqltime) ) );

    if (!$empty_searches) {
        $empty_searches = 0;
    }

    $data->total_searches = $total_searches;
    $data->unique_searches = $unique_searches;
    $data->empty_searches = $empty_searches;

    $data = apply_filters( 'wp_client_reports_pro_searchwp_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Report page section for searchwp
 */
function wp_client_reports_pro_stats_page_searchwp() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-searchwp">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Searches','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Total %s Searches', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-searchwp-total-searches'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Unique %s Searches', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-searchwp-unique-searches'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Empty %s Searches', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-searchwp-empty-searches'
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
 * Report email section for searchwp
 */
function wp_client_reports_pro_stats_email_searchwp($start_date, $end_date) {
    $searchwp_data = wp_client_reports_get_searchwp_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Searches', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $searchwp_data->total_searches, 
        sprintf( __( 'Total %s Searches', 'wp-client-reports-pro' ), '<br>' ),
        $searchwp_data->unique_searches, 
        sprintf( __( 'Unique %s Searches', 'wp-client-reports-pro' ), '<br>' )
    );

    wp_client_reports_render_email_row(
        $searchwp_data->empty_searches, 
        sprintf( __( 'Empty %s Searches', 'wp-client-reports-pro' ), '<br>' ),
        null, 
        null
    );

}