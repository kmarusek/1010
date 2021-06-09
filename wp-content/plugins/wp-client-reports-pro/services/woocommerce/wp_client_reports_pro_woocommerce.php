<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load WooCommerce Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_woocommerce_actions', 999 );
function wp_client_reports_pro_load_woocommerce_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_woocommerce', 60);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_woocommerce', 60, 2);
        add_action('wp_ajax_wp_client_reports_pro_woocommerce_data', 'wp_client_reports_pro_woocommerce_data');

    }

}


/**
 * Ajax request report data for woocommerce
 */
function wp_client_reports_pro_woocommerce_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_woocommerce_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for woocommerce
 */
function wp_client_reports_get_woocommerce_data($start_date, $end_date) {

    $data = new stdClass;
    $data->gross_sales = 0;
    $data->net_sales = 0;
    $data->orders_placed = 0;
    $data->items_purchased = 0;

    if (defined('WC_PLUGIN_FILE')) {

        include_once plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/admin/reports/class-wc-admin-report.php';
        include_once plugin_dir_path( WC_PLUGIN_FILE ) . 'includes/admin/reports/class-wc-report-sales-by-date.php';

    }

    if (class_exists('WC_Admin_Report') && class_exists('WC_Report_Sales_By_Date')) {

        $report = new WC_Report_Sales_By_Date();
        $report->chart_groupby = 'day';
        $_GET['start_date'] = $start_date;
        $_GET['end_date'] = $end_date;
        $report->calculate_current_range('custom');
        $report_data = $report->get_report_data();

        $data->gross_sales = html_entity_decode(strip_tags(wc_price($report_data->total_sales, ['decimals' => 0])));
        $data->net_sales = html_entity_decode(strip_tags(wc_price($report_data->net_sales, ['decimals' => 0])));
        $data->orders_placed = $report_data->total_orders;
        $data->items_purchased = $report_data->total_items;

    }

    $data = apply_filters( 'wp_client_reports_pro_woocommerce_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Report page section for woocommerce
 */
function wp_client_reports_pro_stats_page_woocommerce() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-woocommerce">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('WooCommerce','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Gross %s Sales', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-woocommerce-gross-sales'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Net %s Sales', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-woocommerce-net-sales'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Orders %s Placed', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-woocommerce-orders-placed'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Items %s Purchased', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-woocommerce-items-purchased'
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
 * Report email section for woocommerce
 */
function wp_client_reports_pro_stats_email_woocommerce($start_date, $end_date) {
    $woocommerce_data = wp_client_reports_get_woocommerce_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'WooCommerce', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $woocommerce_data->gross_sales, 
        sprintf( __( 'Gross %s Sales', 'wp-client-reports-pro' ), '<br>' ),
        $woocommerce_data->net_sales, 
        sprintf( __( 'Net %s Sales', 'wp-client-reports-pro' ), '<br>' )
    );

    wp_client_reports_render_email_row(
        $woocommerce_data->orders_placed, 
        sprintf( __( 'Orders %s Placed', 'wp-client-reports-pro' ), '<br>' ),
        $woocommerce_data->items_purchased, 
        sprintf( __( 'Items %s Purchased', 'wp-client-reports-pro' ), '<br>' )
    );

}