<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load BackupBuddy Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_backupbuddy_actions', 999 );
function wp_client_reports_pro_load_backupbuddy_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_backupbuddy', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_backupbuddy', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_backupbuddy_data', 'wp_client_reports_pro_backupbuddy_data');

    }

}


/**
 * Ajax request report data for backupbuddy
 */
function wp_client_reports_pro_backupbuddy_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_backupbuddy_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for backupbuddy
 */
function wp_client_reports_get_backupbuddy_data($start_date, $end_date) {

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

    $args = [];

    if (function_exists('backupbuddy_backups')) {

        if ( ! class_exists( 'backupbuddy_core' ) ) {
            require_once pb_backupbuddy::plugin_path() . '/classes/core.php';
        }

        $backupbuddy_backups = backupbuddy_backups()->get_backups();

        if ($backupbuddy_backups && is_array($backupbuddy_backups)) {

            foreach($backupbuddy_backups as $backupbuddy_backup) {

                $timestamp = '';
                if ( ! is_array( $backupbuddy_backup ) ) {
                    if ( is_int( $backupbuddy_backup ) ) {
                        $timestamp = $backupbuddy_backup;
                    }
                } elseif ( ! empty( $backupbuddy_backup[0][1] ) && is_int( $backupbuddy_backup[0][1] ) ) {
                    $timestamp = $backupbuddy_backup[0][1];
                } elseif ( ! empty( $backupbuddy_backup[0] ) && is_int( $backupbuddy_backup[0] ) ) {
                    $timestamp = $backupbuddy_backup[0];
                }

                //$timestamp = pb_backupbuddy::$ui->get_timestamp($backupbuddy_backup);

                if (intval($timestamp) < intval($end_date_timestamp) && intval($timestamp) > intval($start_date_timestamp)) {

                    $data->backups++;

                    if (isset($backupbuddy_backup[2])) {
                        $data->data = $data->data + unFormatBackupBuddySize($backupbuddy_backup[2]);
                    }

                }

            }

        }

    }

    if ($data->data > 0) {
        $data->data = formatBackupBuddyBytes($data->data, 0);
    }

    $data = apply_filters( 'wp_client_reports_pro_backupbuddy_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Format unknown unit to bytes
 */
function unFormatBackupBuddySize($size) { 
    
    $multiplier = 0;
    $unit = '';
    if (strpos($size, 'TB') !== false) {
        $multiplier = 1099511627776;
        $unit = 'TB';
    } else if (strpos($size, 'GB') !== false) {
        $multiplier = 1073741824;
        $unit = 'TB';
    } else if (strpos($size, 'MB') !== false) {
        $multiplier = 1048576;
        $unit = 'TB';
    } else if (strpos($size, 'KB') !== false) {
        $multiplier = 1024;
        $unit = 'KB';
    } else if (strpos($size, 'B') !== false) {
        $multiplier = 1;
        $unit = 'B';
    }

    $number = intval(trim(str_replace($unit,"",$size)));
    return $number * $multiplier;

} 


/**
 * Format bytes to other unit
 */
function formatBackupBuddyBytes($bytes, $precision = 2) { 
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
 * Report page section for backupbuddy
 */
function wp_client_reports_pro_stats_page_backupbuddy() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-backupbuddy">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Backups','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-backupbuddy-backups'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-backupbuddy-data'
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
 * Report email section for backupbuddy
 */
function wp_client_reports_pro_stats_email_backupbuddy($start_date, $end_date) {
    $backupbuddy_data = wp_client_reports_get_backupbuddy_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Backups', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $backupbuddy_data->backups, 
        sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ),
        $backupbuddy_data->data, 
        sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' )
    );

}