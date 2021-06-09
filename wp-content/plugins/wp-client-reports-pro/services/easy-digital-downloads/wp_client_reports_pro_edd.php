<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load Easy Digital Downloads Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_edd_actions', 999 );
function wp_client_reports_pro_load_edd_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_edd', 62);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_edd', 62, 2);
        add_action('wp_ajax_wp_client_reports_pro_edd_data', 'wp_client_reports_pro_edd_data');

    }

}


/**
 * Ajax request report data for Easy Digital Downloads
 */
function wp_client_reports_pro_edd_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_edd_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for Easy Digital Downloads
 */
function wp_client_reports_get_edd_data($start_date, $end_date) {

    $data = new stdClass;
    $data->sales = 0;
    $data->earnings = 0;

    if (class_exists('EDD_Payments_Query')) {

        $stats = new EDD_Payment_Stats;

        $sales = $stats->get_sales(0,$start_date,$end_date);

        $earnings = html_entity_decode( edd_currency_filter(edd_format_amount( $stats->get_earnings(0,$start_date,$end_date), false ) ), ENT_COMPAT, 'UTF-8' );


        // $payments_query = edd_get_payments( array(
        //     'number'  => -1,
        //     'status'   => 'publish',
        //     'start_date' => $start_date,
        //     'end_date'   => $end_date,
        ////     'fields' => 'ids'
        // ) );
        // $payments = $payments_query->get_payments();

        $data->sales = $sales;
        $data->earnings = $earnings;

    }

    $data = apply_filters( 'wp_client_reports_pro_edd_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Report page section for Easy Digital Downloads
 */
function wp_client_reports_pro_stats_page_edd() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-edd">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Easy Digital Downloads','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __( 'Sales', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-edd-sales'
                                );
                                wp_client_reports_render_big_number(
                                    __( 'Earnings', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-edd-earnings'
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
 * Report email section for Easy Digital Downloads
 */
function wp_client_reports_pro_stats_email_edd($start_date, $end_date) {
    $edd_data = wp_client_reports_get_edd_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Easy Digital Downloads', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $edd_data->sales, 
        __( 'Sales', 'wp-client-reports-pro' ),
        $edd_data->earnings, 
        __( 'Earnings', 'wp-client-reports-pro' )
    );

}