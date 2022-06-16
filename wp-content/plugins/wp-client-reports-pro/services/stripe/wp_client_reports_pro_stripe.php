<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load Stripe Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_stripe_actions', 999 );
function wp_client_reports_pro_load_stripe_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_stripe', 62);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_stripe', 62, 2);
        add_action('wp_ajax_wp_client_reports_pro_stripe_data', 'wp_client_reports_pro_stripe_data');

    }

}



/**
 * Register the options that will be available on the options page
 */
add_action( 'admin_init', 'wp_client_reports_pro_stripe_options_init', 13 );
function wp_client_reports_pro_stripe_options_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_stripe_key', 'wp_client_reports_pro_stripe_key_save' );
    
    add_settings_field(
		'wp_client_reports_pro_stripe_key',
		__( 'Stripe Secret API Key', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_stripe_key_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_stripe_section'
    );

}


/**
 * Save the stripe api key field
 */
function wp_client_reports_pro_stripe_key_save( $new ) {
	$old = get_option( 'wp_client_reports_pro_stripe_key' );
	if( $old && $old != $new ) {
        wp_client_reports_delete_transients('wp_client_reports_stripe');
	}
	return $new;
}


/**
 * Add Pingdom API key field to the options page
 */
function wp_client_reports_pro_stripe_key_render(  ) {
    $stripe_key = get_option( 'wp_client_reports_pro_stripe_key' );
	?>
	<input type='text' name='wp_client_reports_pro_stripe_key' value='<?php echo $stripe_key; ?>'class="regular-text">
	<?php
}


/**
 * Ajax request report data for Stripe
 */
function wp_client_reports_pro_stripe_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_stripe_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for Stripe
 */
function wp_client_reports_get_stripe_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $stripe_data = get_transient('wp_client_reports_data_stripe_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));



    $stripe_api_key = get_option( 'wp_client_reports_pro_stripe_key' );

    if ($stripe_api_key) {

        if ($stripe_data === false) {

            $stripe_data = new stdClass;
            $stripe_data->gross = 0;
            //$stripe_data->net = 0;
            $stripe_data->customers = 0;
            $stripe_data->refunds = 0;
            
            //Decided not to use library because of conflicts with other plugins (GiveWP)
            // if (!class_exists('Stripe\Stripe')) {
            //     require_once plugin_dir_path( __FILE__ ) . '../../vendor/stripe-php/init.php';
            // }
            
            // $stripe = new \Stripe\StripeClient(
            //     'sk_live_ZiCuKGvPmLumbg12bNxB2j1O00WdseucwR'
            // );

            // $charges = $stripe->charges->all([
            //     'limit' => 100,
            //     // 'created' => [
            //     //     'gte' => $start_date_timestamp,
            //     //     'lte' => $end_date_timestamp
            //     // ],
            // ]);

            $args = [
                'limit' => 100,
                //'expand' => ['customer','balance_transaction'],
                'created' => [
                    'gte' => $start_date_timestamp,
                    'lte' => $end_date_timestamp
                ],
            ];

            $charges = wp_client_reports_pro_stripe_get_charges($args, $stripe_api_key);

            $unique_customers = [];

            // echo '<pre>';
            // print_r($charges);
            // echo '</pre>';
            // die;

            if (is_array($charges) && !empty($charges)) {

                foreach ($charges as $charge) {
                    if (boolval($charge->refunded)) {
                        $stripe_data->refunds++;
                    } else if (boolval($charge->paid)) {
                        $stripe_data->gross = $stripe_data->gross + ($charge->amount_captured / 100);
                    }
                    if (!in_array ( $charge->customer, $unique_customers)) {
                        $unique_customers[] = $charge->customer;
                        $stripe_data->customers++;
                    }
                }

                $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                //$formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, );

                if ($stripe_data->gross > 1000) {
                    $stripe_data->gross = round($stripe_data->gross);
                    $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
                }

                $stripe_data->gross = $formatter->formatCurrency($stripe_data->gross, strtoupper($charges[0]->currency));

            }

            set_transient('wp_client_reports_data_stripe_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $stripe_data, 3600 * 24);

        }

    } else {

        $stripe_data = new stdClass;
        $stripe_data->gross = 0;
        //$stripe_data->net = 0;
        $stripe_data->customers = 0;
        $stripe_data->refunds = 0;

    }

    $data = apply_filters( 'wp_client_reports_pro_stripe_data', $stripe_data, $start_date, $end_date );

    return $data;
}


function wp_client_reports_pro_stripe_get_charges($args, $stripe_api_key, $count = 0) {
    $count++;
    $request = wp_client_reports_pro_stripe_get_data($args, $stripe_api_key);
    $charges = $request->data;
    if (boolval($request->has_more) && $count < 5) {
        $args['starting_after'] = $charges[array_key_last($charges)]->id;
        $additional_charges = wp_client_reports_pro_stripe_get_charges($args, $stripe_api_key, $count);
        $charges = array_merge($charges, $additional_charges);
    }
    return $charges;
}


function wp_client_reports_pro_stripe_get_data($args, $stripe_api_key) {
    $url = 'https://api.stripe.com/v1/charges';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$stripe_api_key));
    $curl_url = sprintf("%s?%s", $url, http_build_query($args));
    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output);
}


/**
 * Report page section for Stripe
 */
function wp_client_reports_pro_stats_page_stripe() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-stripe">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Stripe Earnings','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Gross %s Sales', 'wp-client-reports-pro' ), '<br>' ),
                                    'wp-client-reports-pro-stripe-gross'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'New %s Customers', 'wp-client-reports-pro' ), '<br>' ),
                                    'wp-client-reports-pro-stripe-customers'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Refunds %s Given', 'wp-client-reports-pro' ), '<br>' ),
                                    'wp-client-reports-pro-stripe-refunds'
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
 * Report email section for Stripe
 */
function wp_client_reports_pro_stats_email_stripe($start_date, $end_date) {
    $stripe_data = wp_client_reports_get_stripe_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Stripe', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $stripe_data->gross, 
        sprintf( __( 'Gross %s Sales', 'wp-client-reports-pro' ), '<br>' ),
        $stripe_data->customers, 
        sprintf( __( 'New %s Customers', 'wp-client-reports-pro' ), '<br>' ),
        $stripe_data->refunds, 
        sprintf( __( 'Refunds %s Given', 'wp-client-reports-pro' ), '<br>' )
    );

}