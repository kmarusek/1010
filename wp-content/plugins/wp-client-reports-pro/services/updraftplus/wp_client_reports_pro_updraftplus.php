<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load UpdraftPlus Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_updraftplus_actions', 999 );
function wp_client_reports_pro_load_updraftplus_actions(){

    if (is_admin() || wp_doing_cron()) {
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_updraftplus', 70);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_updraftplus', 70, 2);
        add_action('wp_ajax_wp_client_reports_pro_updraftplus_data', 'wp_client_reports_pro_updraftplus_data');

    }

}


/**
 * Ajax request report data for updraftplus
 */
function wp_client_reports_pro_updraftplus_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_get_updraftplus_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get data for updraftplus
 */
function wp_client_reports_get_updraftplus_data($start_date, $end_date) {

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

    $updraft_backup_history = get_option('updraft_backup_history');

    if ($updraft_backup_history && is_array($updraft_backup_history)) {

        foreach($updraft_backup_history as $timestamp => $updraft_backup) {

            if (intval($timestamp) < intval($end_date_timestamp) && intval($timestamp) > intval($start_date_timestamp)) {

                $data->backups++;

                $size = 0;
                if (isset($updraft_backup['plugins-size'])) {
                    $size = $size + $updraft_backup['plugins-size'];
                }
                if (isset($updraft_backup['themes-size'])) {
                    $size = $size + $updraft_backup['themes-size'];
                }
                if (isset($updraft_backup['uploads-size'])) {
                    $size = $size + $updraft_backup['uploads-size'];
                }
                if (isset($updraft_backup['others-size'])) {
                    $size = $size + $updraft_backup['others-size'];
                }
                if (isset($updraft_backup['db-size'])) {
                    $size = $size + $updraft_backup['db-size'];
                }
                $data->data = $data->data + $size;

            }

        }

    }

    if ($data->data > 0) {
        //$data->data = formatUpdraftPlusBytes($data->data, 0);
        if ($data->data > 1073741824) {
			$data->data = round($data->data / 1073741824).' GB';
		} elseif ($data->data > 1048576) {
			$data->data = round($data->data / 1048576).' MB';
		} elseif ($data->data > 1024) {
			$data->data = round($data->data / 1024).' KB';
		} else {
			$data->data = round($data->data).' B';
		}
    }

    $data = apply_filters( 'wp_client_reports_pro_updraftplus_data', $data, $start_date, $end_date );

    return $data;
}


/**
 * Format bytes to other unit
 */
function formatUpdraftPlusBytes($bytes, $precision = 2) { 
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
function wp_client_reports_pro_stats_page_updraftplus() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-updraftplus">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Backups','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">
                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-updraftplus-backups'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' ), 
                                    'wp-client-reports-pro-updraftplus-data'
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
function wp_client_reports_pro_stats_email_updraftplus($start_date, $end_date) {
    $updraftplus_data = wp_client_reports_get_updraftplus_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Backups', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $updraftplus_data->backups, 
        sprintf( __( 'Backups', 'wp-client-reports-pro' ), '<br>' ),
        $updraftplus_data->data, 
        sprintf( __( 'Data', 'wp-client-reports-pro' ), '<br>' )
    );

}