<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Gravity Forms Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_gforms_actions', 999 );
function wp_client_reports_pro_load_gforms_actions(){

    if (is_admin() || wp_doing_cron()) {
        
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_gforms', 50);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_gforms', 50, 2);
        add_action('wp_ajax_wp_client_reports_pro_gform_data', 'wp_client_reports_pro_gform_data');

    }

}


/**
 * Enqueue frontend scripts and styles for gravity forms
 */
function wp_client_reports_scripts_pro_gravity_forms_frontend_scripts() {

    wp_enqueue_script( 'wp-client-reports-pro-gf-js', plugin_dir_url( __FILE__ ) . '/wp-client-reports-pro-gf.js', array('jquery'), WP_CLIENT_REPORTS_PRO_VERSION, true );
    wp_localize_script( 'wp-client-reports-pro-gf-js', 'wp_client_reports_pro', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'wp_client_reports_scripts_pro_gravity_forms_frontend_scripts', 9999 );


/**
 * Ajax request report data for gravity forms
 */
function wp_client_reports_pro_gform_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_gform_data($dates->start_date, $dates->end_date);
    
    print json_encode($data);
    wp_die();

}


/**
 * Get report data for gravity forms
 */
function wp_client_reports_pro_get_gform_data($start_date, $end_date) {

    $search_criteria = array();
    $form_id = 0;
    $search_criteria['status'] = 'active';
    $search_criteria['start_date'] = $start_date;
    $search_criteria['end_date'] = $end_date;
    $entry_count = GFAPI::count_entries( $form_id, $search_criteria );

    global $wpdb;
    $form_daily_views_table_name = $wpdb->prefix . 'form_views_daily';

    $form_view_results = $wpdb->get_var( $wpdb->prepare( "SELECT sum(count) FROM $form_daily_views_table_name WHERE `plugin` = 'gravity_forms' AND `date` >= %s AND `date` <= %s", array($start_date, $end_date) ) );

    if (!$form_view_results) {
        $form_view_results = 0;
    }

    $data = new \stdClass;
    // $forms = GFAPI::get_forms();
    // $data->gform->forms = $forms;
    $data->views = intval($form_view_results);
    $data->entries = intval($entry_count);
    if ($entry_count > 0 && $form_view_results > 0) {
        $data->conversion =  round( ($entry_count / $form_view_results) * 100 );
    } else {
        $data->conversion =  0;
    }

    $data = apply_filters( 'wp_client_reports_pro_gform_data', $data, $start_date, $end_date );

    return $data;
}



/**
 * Report page section for gravity forms
 */
function wp_client_reports_pro_stats_page_gforms() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-gforms">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Forms','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Form %s Views', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-gform-views-count'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Forms %s Submitted', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-gform-entries-count'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Conversion %s Rate', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-gform-conversion'
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
 * Report email section for gravity forms
 */
function wp_client_reports_pro_stats_email_gforms($start_date, $end_date) {
    $gform_data = wp_client_reports_pro_get_gform_data($start_date, $end_date);

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