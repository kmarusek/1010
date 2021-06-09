<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Caldera Forms Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_caldera_forms_actions', 999 );
function wp_client_reports_pro_load_caldera_forms_actions(){

    if (is_admin() || wp_doing_cron()) {

        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_caldera_forms', 56);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_caldera_forms', 56, 2);
        add_action('wp_ajax_wp_client_reports_pro_caldera_forms_data', 'wp_client_reports_pro_caldera_forms_data');

    }

}


/**
 * Enqueue frontend scripts and styles.
 */
function wp_client_reports_scripts_pro_caldera_forms_frontend_scripts() {

    wp_enqueue_script( 'wp-client-reports-pro-caldera-js', plugin_dir_url( __FILE__ ) . '/wp-client-reports-pro-caldera.js', array('jquery'), WP_CLIENT_REPORTS_PRO_VERSION, true );
    wp_localize_script( 'wp-client-reports-pro-caldera-js', 'wp_client_reports_pro', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'wp_client_reports_scripts_pro_caldera_forms_frontend_scripts', 9999 );


/**
 * Ajax request report data for Caldera Forms
 */
function wp_client_reports_pro_caldera_forms_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_caldera_forms_data($dates->start_date, $dates->end_date);
    
    print json_encode($data);
    wp_die();

}


/**
 * A way to translate Caldera form ids (strings) into savable integers
 */
add_filter( 'wp_client_reports_pro_form_id_pre_save', 'wp_client_reports_pro_caldera_form_id_translate', 10, 1 );
function wp_client_reports_pro_caldera_form_id_translate($form_id) {
    $caldera_formid_translations = get_option('caldera_formid_translations', null);
    if (!$caldera_formid_translations) {
        $caldera_formid_translations = [$form_id];
        update_option('caldera_formid_translations', $caldera_formid_translations, false);
        $form_id = 0;
    } else {
        $found_form_id = null;
        foreach($caldera_formid_translations as $key => $caldera_formid_translation) {
            if ($caldera_formid_translation === $form_id) {
                $found_form_id = $key;
            }
        }
        if ($found_form_id !== null) {
            $form_id = $found_form_id;
        } else {
            $caldera_formid_translations[] = $form_id;
            update_option('caldera_formid_translations', $caldera_formid_translations, false);
            $form_id = array_key_last($caldera_formid_translations);
        }
    }
    return $form_id;
}


/**
 * Get report data for Caldera Forms
 */
function wp_client_reports_pro_get_caldera_forms_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_gmt = $start_date_object->format('Y-m-d H:i:s');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_gmt = $end_date_object->format('Y-m-d H:i:s');

    global $wpdb;

    $cf_entries_table_name = $wpdb->prefix . 'cf_form_entries';
    $form_daily_views_table_name = $wpdb->prefix . 'form_views_daily';

    $entry_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $cf_entries_table_name WHERE `datestamp` >= %s AND `datestamp` <= %s", array($start_date_gmt, $end_date_gmt) ) );

    if (!$entry_count) {
        $entry_count = 0;
    }

    $form_view_results = $wpdb->get_var( $wpdb->prepare( "SELECT sum(count) FROM $form_daily_views_table_name WHERE `plugin` = 'caldera_forms' AND `date` >= %s AND `date` <= %s", array($start_date, $end_date) ) );

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

    $data = apply_filters( 'wp_client_reports_pro_caldera_forms_data', $data, $start_date, $end_date );

    return $data;
}



/**
 * Report page section for Caldera Forms
 */
function wp_client_reports_pro_stats_page_caldera_forms() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-caldera-forms">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Forms','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <div class="wp-client-reports-big-number">
                                <h2 id="wp-client-reports-pro-caldera-forms-views-count">0</h2>
                                <h3><?php printf( __( 'Form %s Views', 'wp-client-reports-pro' ), '<br>' ); ?></h3>
                            </div><!-- .wp-client-reports-big-number -->
                            <div class="wp-client-reports-big-number">
                                <h2 id="wp-client-reports-pro-caldera-forms-entries-count">0</h2>
                                <h3><?php printf( __( 'Forms %s Submitted', 'wp-client-reports-pro' ), '<br>' ); ?></h3>
                            </div><!-- .wp-client-reports-big-number -->
                            <div class="wp-client-reports-big-number">
                                <h2 id="wp-client-reports-pro-caldera-forms-conversion">0</h2>
                                <h3><?php printf( __( 'Conversion %s Rate', 'wp-client-reports-pro' ), '<br>' ); ?></h3>
                            </div><!-- .wp-client-reports-big-number -->
                        </div><!-- .wp-client-reports-big-numbers -->
                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}


/**
 * Report email section for Caldera Forms
 */
function wp_client_reports_pro_stats_email_caldera_forms($start_date, $end_date) {
    $caldera_forms_data = wp_client_reports_pro_get_caldera_forms_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Forms', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $caldera_forms_data->views, 
        sprintf( __( 'Form %s Views', 'wp-client-reports-pro' ), '<br>' ),
        $caldera_forms_data->entries, 
        sprintf( __( 'New %s Entries', 'wp-client-reports-pro' ), '<br>' )
    );

    wp_client_reports_render_email_row(
        $caldera_forms_data->conversion . '%', 
        sprintf( __( 'Conversion %s Rate', 'wp-client-reports-pro' ), '<br>' ),
        null,
        null
    );

}