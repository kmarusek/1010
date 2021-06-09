<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Formidable Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_formidable_actions', 999 );
function wp_client_reports_pro_load_formidable_actions(){

    if (is_admin() || wp_doing_cron()) {
        
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_formidable', 54);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_formidable', 54, 2);
        add_action('wp_ajax_wp_client_reports_pro_formidable_data', 'wp_client_reports_pro_formidable_data');

    }

}


/**
 * Enqueue frontend scripts and styles for formidable
 */
function wp_client_reports_scripts_pro_formidable_frontend_scripts() {

    wp_enqueue_script( 'wp-client-reports-pro-formidable-js', plugin_dir_url( __FILE__ ) . '/wp-client-reports-pro-formidable.js', array('jquery'), WP_CLIENT_REPORTS_PRO_VERSION, true );
    wp_localize_script( 'wp-client-reports-pro-formidable-js', 'wp_client_reports_pro', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'wp_client_reports_scripts_pro_formidable_frontend_scripts', 9999 );


/**
 * Ajax request report data for formidable
 */
function wp_client_reports_pro_formidable_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_formidable_data($dates->start_date, $dates->end_date);
    
    print json_encode($data);
    wp_die();

}


/**
 * Get report data for formidable
 */
function wp_client_reports_pro_get_formidable_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_gmt = $start_date_object->format('Y-m-d H:i:s');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_gmt = $end_date_object->format('Y-m-d H:i:s');

    global $wpdb;

    $formidable_entries_table = $wpdb->prefix . 'frm_items';

    $entry_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $formidable_entries_table WHERE `is_draft` = 0 AND `created_at` >= %s AND `created_at` <= %s", array($start_date_gmt, $end_date_gmt) ) );

    if (!$entry_count) {
        $entry_count = 0;
    }

    //$counts = FrmEntry::getRecordCount();
    //FrmAppHelper::pro_is_installed()
    
    $form_daily_views_table_name = $wpdb->prefix . 'form_views_daily';

    $form_view_results = $wpdb->get_var( $wpdb->prepare( "SELECT sum(count) FROM $form_daily_views_table_name WHERE `plugin` = 'formidable' AND `date` >= %s AND `date` <= %s", array($start_date, $end_date) ) );

    if (!$form_view_results) {
        $form_view_results = 0;
    }

    $data = new \stdClass;
    $data->views = intval($form_view_results);
    $data->entries = intval($entry_count);
    if ($entry_count > 0 && $form_view_results > 0) {
        $data->conversion =  round( ($entry_count / $form_view_results) * 100 );
    } else {
        $data->conversion =  0;
    }

    $data = apply_filters( 'wp_client_reports_pro_formidable_data', $data, $start_date, $end_date );

    return $data;
}



/**
 * Report page section for formidable
 */
function wp_client_reports_pro_stats_page_formidable() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-formidable">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Forms','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Form %s Views', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-formidable-views-count'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Forms %s Submitted', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-formidable-entries-count'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Conversion %s Rate', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-formidable-conversion'
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
 * Report email section for formidable
 */
function wp_client_reports_pro_stats_email_formidable($start_date, $end_date) {
    $gform_data = wp_client_reports_pro_get_formidable_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Forms', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $gform_data->views, 
        sprintf( __( 'Form %s Views', 'wp-client-reports-pro' ), '<br>' ),
        $gform_data->entries, 
        sprintf( __( 'New %s Entries', 'wp-client-reports-pro' ), '<br>' )
    );

    wp_client_reports_render_email_row(
        $gform_data->conversion . '%', 
        sprintf( __( 'Conversion %s Rate', 'wp-client-reports-pro' ), '<br>' ),
        null,
        null
    );

}