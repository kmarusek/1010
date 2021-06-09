<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load GiveWP Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_givewp_actions', 999 );
function wp_client_reports_pro_load_givewp_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_givewp', 62);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_givewp', 62, 2);
        add_action('wp_ajax_wp_client_reports_pro_givewp_data', 'wp_client_reports_pro_givewp_data');

    }

}


/**
 * Ajax request report data for GiveWP
 */
function wp_client_reports_pro_givewp_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_givewp_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for GiveWP
 */
function wp_client_reports_get_givewp_data($start_date, $end_date) {

    $data = new stdClass;
    $data->total = 0;
    $data->average = 0;
    $data->donors = 0;
    $data->refunds = 0;

    if (class_exists('Give')) {

        $donations = new Give_Payments_Query(
			array(
				'number'     => - 1,
				'status'     => array( 'publish', 'refunded' ),
                'mode'      => 'live',
                'start_date' => $start_date,
                'end_date'   => $end_date,
			)
		);

		$donations = $donations->get_payments();


        if ( ! empty( $donations ) ) {

            $total_num_donations = 0;
            $total = 0;
            $refunds = 0;
            $average = 0;
            $donors = 0;

            $unique_donors = [];

            foreach($donations as $donation) {

                if ($donation->status == 'publish') {

                    $total_num_donations++;

                    $total = $total + $donation->total;

                    if (!in_array ( $donation->donor_id, $unique_donors)) {
                        $unique_donors[] = $donation->donor_id;
                        $donors++;
                    }

                } else if ($donation->status == 'refunded') {

                    $refunds++;

                }
                 
            }

            $data->total = wp_client_reports_pro_givewp_formatcurrency($total);
            $data->refunds = $refunds;
            $data->average = wp_client_reports_pro_givewp_formatcurrency($total / $total_num_donations);
            $data->donors = $donors;

        }


    }

    $data = apply_filters( 'wp_client_reports_pro_givewp_data', $data, $start_date, $end_date );

    return $data;
}


function wp_client_reports_pro_givewp_formatcurrency($number) {
    $formatted = give_currency_filter(
        give_format_amount(
            round($number, 2), [ 'sanitize' => false ]
        )
    );
    return str_replace(".00", "", $formatted);
}


/**
 * Report page section for GiveWP
 */
function wp_client_reports_pro_stats_page_givewp() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-givewp">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('GiveWP Donations','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Total %s Donations', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-givewp-total-donations'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Average %s Donation', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-givewp-average-donation'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Total %s Donors', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-givewp-total-donors'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Total %s Refunds', 'wp-client-reports-pro' ), '<br>   ' ), 
                                    'wp-client-reports-pro-givewp-total-refunds'
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
 * Report email section for GiveWP
 */
function wp_client_reports_pro_stats_email_givewp($start_date, $end_date) {
    $givewp_data = wp_client_reports_get_givewp_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'GiveWP', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $givewp_data->total, 
        __( 'Total Donations', 'wp-client-reports-pro' ),
        $givewp_data->average, 
        __( 'Average Donation', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $givewp_data->donors, 
        __( 'Total Donors', 'wp-client-reports-pro' ),
        $givewp_data->refunds, 
        __( 'Total Refunds', 'wp-client-reports-pro' )
    );

}