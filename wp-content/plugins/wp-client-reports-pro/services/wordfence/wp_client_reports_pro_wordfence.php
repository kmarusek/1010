<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load Wordfence Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_wordfence_actions', 999 );
function wp_client_reports_pro_load_wordfence_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_wordfence', 63);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_wordfence', 63, 2);
        add_action('wp_ajax_wp_client_reports_pro_wordfence_data', 'wp_client_reports_pro_wordfence_data');

    }

}


/**
 * Ajax request report data for Wordfence
 */
function wp_client_reports_pro_wordfence_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_wordfence_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for Wordfence
 */
function wp_client_reports_get_wordfence_data($start_date, $end_date) {

    $start_date_object = new DateTime($start_date);
    $start_date_timestamp = $start_date_object->format('U');
    
    $end_date_object = new DateTime($end_date);
    $end_date_timestamp = $end_date_object->format('U');

    $data = new stdClass;
    $data->complex_blocks = wp_client_reports_get_wordfence_blockcount($start_date_timestamp, $end_date_timestamp, 'complex');
    $data->brute_force_blocks = wp_client_reports_get_wordfence_blockcount($start_date_timestamp, $end_date_timestamp, 'bruteforce');
    $data->blacklist = wp_client_reports_get_wordfence_blockcount($start_date_timestamp, $end_date_timestamp, 'blacklist');
    $data->wordfence_is_paid = wp_client_reports_get_wordfence_is_paid();

    $data = apply_filters( 'wp_client_reports_pro_wordfence_data', $data, $start_date, $end_date );

    return $data;
}


function wp_client_reports_get_wordfence_is_paid() {
    $wordfence_is_paid = wfConfig::get('isPaid');
    if (!$wordfence_is_paid) {
        return false;
    } else {
        return true;
    }
}


function wp_client_reports_get_wordfence_blockcount($start_date_timestamp, $end_date_timestamp, $type) {

    $start_date_day = floor($start_date_timestamp / 86400);
    $end_date_day = floor($end_date_timestamp / 86400);

    $groupingWHERE = '';
    if ($type == 'complex') {
        $groupingWHERE = ' AND blockType IN ("fakegoogle", "badpost", "country", "advanced", "waf")';
    } else if ($type == 'bruteforce') {
        $groupingWHERE = ' AND blockType IN ("throttle", "brute")';
    } else if ($type == 'blacklist') {
        $groupingWHERE = ' AND blockType IN ("blacklist", "manual")';
    }
    
    $table_wfBlockedIPLog = wfDB::networkTable('wfBlockedIPLog');
    global $wpdb;
    $count = $wpdb->get_var(<<<SQL
SELECT SUM(blockCount) as blockCount
FROM {$table_wfBlockedIPLog}
WHERE unixday >= {$start_date_day} AND unixday <= {$end_date_day}{$groupingWHERE}
SQL
			);

		return number_format(intval($count));
}


/**
 * Report page section for Wordfence
 */
function wp_client_reports_pro_stats_page_wordfence() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-wordfence">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Wordfence','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Complex Attacks %s Blocked', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-wordfence-complex-blocks'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Brute Force %s Attacks Blocked', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-wordfence-brute-force'
                                );
                                if (wp_client_reports_get_wordfence_is_paid()) {
                                    wp_client_reports_render_big_number(
                                        sprintf( __( 'Attacker IP\'s %s Blacklisted', 'wp-client-reports-pro' ), '<br>' ), 
                                        'wp-client-reports-pro-wordfence-blacklist'
                                    );
                                }
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->
                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for Wordfence
 */
function wp_client_reports_pro_stats_email_wordfence($start_date, $end_date) {
    $wordfence_data = wp_client_reports_get_wordfence_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Wordfence', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $wordfence_data->complex_blocks, 
        __( 'Complex Attacks Blocked', 'wp-client-reports-pro' ),
        $wordfence_data->brute_force_blocks, 
        __( 'Brute Force Attacks Blocked', 'wp-client-reports-pro' )
    );

    if (wp_client_reports_get_wordfence_is_paid()) {
        wp_client_reports_render_email_row(
            $wordfence_data->blacklist, 
            __( 'Attacker IP\'s Blacklisted', 'wp-client-reports-pro' ),
            null, 
            null
        );
    }

}