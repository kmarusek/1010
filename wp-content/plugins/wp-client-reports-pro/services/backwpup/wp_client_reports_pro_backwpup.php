<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load BackWPup Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_backwpup_actions', 999 );
function wp_client_reports_pro_load_backwpup_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_backwpup', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_backwpup', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_backwpup_data', 'wp_client_reports_pro_backwpup_data');

    }

}


/**
 * Ajax request report data for updraftplus
 */
function wp_client_reports_pro_backwpup_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_backwpup_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for updraftplus
 */
function wp_client_reports_get_backwpup_data($start_date, $end_date) {

    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_timestamp = $start_date_object->format('U');
    
    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_timestamp = $end_date_object->format('U');

    $data = new stdClass;
    $data->backups = 0;
    $data->data = 0;

    //Need to load the BackWPUp plugin if 
    // if (!class_exists('BackWPup_Page_Backups')) {
    //     $plugins = get_plugins();
    //     foreach($plugins as $path => $plugin) {
    //         if ($plugin['Name'] == 'BackWPup') {
    //             if ( is_file( WP_PLUGIN_DIR . $path ) ) {
    //                 include_once ( WP_PLUGIN_DIR . $path );
    //             }
    //         }
    //     }
    // }

    if (class_exists('BackWPup_Page_Backups')) {

        if ( ! class_exists( 'WP_Screen' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
        }

        if ( ! function_exists( 'convert_to_screen' ) ) {
            require_once ABSPATH . 'wp-admin/includes/template.php';
        }

        $backups = new \BackWPup_Page_Backups;
        $backups->prepare_items();

        $backwpup_backup_history = $backups->items;

        if ($backwpup_backup_history && is_array($backwpup_backup_history)) {

            foreach($backwpup_backup_history as $backwpup_backup) {

                $timestamp = $backwpup_backup['time'];

                if (intval($timestamp) < intval($end_date_timestamp) && intval($timestamp) > intval($start_date_timestamp)) {

                    $data->backups++;
                    $data->data = $data->data + $backwpup_backup['filesize'];

                }

            }

        }

    }

    if ($data->data > 0) {
        $data->data = formatBackWPupBytes($data->data, 0);
    }

    $data = apply_filters( 'wp_client_reports_pro_backwpup_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Format bytes to other unit
 */
function formatBackWPupBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . $units[$pow]; 
} 


/**
 * Report page section for updraftplus
 */
function wp_client_reports_pro_stats_page_backwpup() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-backwpup">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Backups','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-backwpup-backups'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-backwpup-data'
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
 * Report email section for updraftplus
 */
function wp_client_reports_pro_stats_email_backwpup($start_date, $end_date) {
    $updraftplus_data = wp_client_reports_get_backwpup_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Backups', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $updraftplus_data->backups, 
        sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ),
        $updraftplus_data->data, 
        sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' )
    );

}