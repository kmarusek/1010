<?php

if( !defined( 'ABSPATH' ) )
	exit;


// use MailPoet\DI\ContainerWrapper;
// use MailPoetVendor\Doctrine\ORM\EntityManager;
// use MailPoet\Entities\SubscriberEntity;

/**
 * Load MailPoet Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_mailpoet_actions', 999 );
function wp_client_reports_pro_load_mailpoet_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_mailpoet', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_mailpoet', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_mailpoet_data', 'wp_client_reports_pro_mailpoet_data');

    }

}


/**
 * Ajax request report data for MailPoet
 */
function wp_client_reports_pro_mailpoet_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_mailpoet_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}



/**
 * Get report data for MailPoet
 */
function wp_client_reports_pro_get_mailpoet_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_gmt = $start_date_object->format('Y-m-d H:i:s');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_gmt = $end_date_object->format('Y-m-d H:i:s');

    $mailpoet_data = get_transient('wp_client_reports_mailpoet_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($mailpoet_data === false) {
        
        $mailpoet_data = new \stdClass;
        $mailpoet_data->subscribes = 0;
        $mailpoet_data->unsubscribes = 0;
        $mailpoet_data->total = 0;
        
        //use MailPoetVendor\Doctrine\ORM\EntityManager;
        $entityManager = MailPoet\DI\ContainerWrapper::getInstance()->get(MailPoetVendor\Doctrine\ORM\EntityManager::class);
        //$definition = new MailPoet\Listing\ListingDefinition;
        $subscribersTable = $entityManager->getClassMetadata(MailPoet\Entities\SubscriberEntity::class)->getTableName();

        global $wpdb;

        $mailpoet_data->subscribes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $subscribersTable WHERE `status` = 'subscribed' AND `last_subscribed_at` >= %s AND `last_subscribed_at` <= %s", array($start_date_gmt, $end_date_gmt) ) ); //

        $mailpoet_data->unsubscribes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $subscribersTable WHERE `status` = 'unsubscribed' AND `updated_at` >= %s AND `updated_at` <= %s", array($start_date_gmt, $end_date_gmt) ) );

        $mailpoet_data->total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $subscribersTable WHERE `status` = 'subscribed'" ) );

        set_transient('wp_client_reports_mailpoet_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $mailpoet_data, 3600 * 24);
        
    }

    $mailpoet_data = apply_filters( 'wp_client_reports_pro_mailpoet_data', $mailpoet_data, $start_date, $end_date );

    return $mailpoet_data;

}


/**
 * Report page section for MailPoet
 */
function wp_client_reports_pro_stats_page_mailpoet() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-mailpoet">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Mailing List','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __('Subscribes', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailpoet-subscribes'
                                );
                                wp_client_reports_render_big_number(
                                    __('Unsubscribes', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailpoet-unsubscribes'
                                );
                                wp_client_reports_render_big_number(
                                    __('Current List Size', 'wp-client-reports-pro' ), 
                                    'wp-client-reports-pro-mailpoet-total'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->
                        <div class="error notice" id="wp-client-reports-pro-mailpoet-warning" style="display:none;"><p></p></div>
                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for MailPoet
 */
function wp_client_reports_pro_stats_email_mailpoet($start_date, $end_date) {
    $mailpoet_data = wp_client_reports_pro_get_mailpoet_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Mailing List', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $mailpoet_data->subscribes, 
        __( 'Subscribes', 'wp-client-reports-pro' ),
        $mailpoet_data->unsubscribes, 
        __( 'Unsubscribes', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $mailpoet_data->total, 
        __( 'Current List Size', 'wp-client-reports-pro' ),
        null,
        null
    );

    if ($mailpoet_data->warning) {
    ?>
        <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 20px 40px 40px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 14px; line-height: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:30px;">
                    <tr><td style="padding:8px;border:solid 1px #dddddd; border-left:solid 4px #dc3232;"><?php echo esc_html($mailpoet_data->warning); ?></td></tr>
                </table>
            </td>
        </tr>
    <?php
    }
    
}


/**
 * When force refresh is called, clear all transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_mailpoet_update', 13 );
function wp_client_reports_force_mailpoet_update() {
    wp_client_reports_delete_transients('wp_client_reports_mailpoet');
}