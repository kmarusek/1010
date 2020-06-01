<?php

if( !defined( 'ABSPATH' ) )
    exit;


/**
 * Load Google Analytics Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_ga_actions', 999 );
function wp_client_reports_pro_load_ga_actions(){

    if (is_admin() || wp_doing_cron()) {
        
        $google_analytics_key = get_option( 'wp_client_reports_pro_google_analytics_key' );
        $google_analytics_view_id = get_option( 'wp_client_reports_pro_google_analytics_view_id' );
        if ($google_analytics_key && $google_analytics_view_id) {
            add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_google_analytics', 20);
            add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_google_analytics', 20, 2);
            add_action('wp_ajax_wp_client_reports_pro_ga_data', 'wp_client_reports_pro_ga_data');
        }

    }

}


/**
 * Register the options that will be available on the options page
 */
add_action( 'admin_init', 'wp_client_reports_pro_google_analytics_options_init', 12 );
function wp_client_reports_pro_google_analytics_options_init(  ) {

    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_google_analytics_key', 'wp_client_reports_pro_google_analytics_key_upload' );
    register_setting( 'wp_client_reports_options_page', 'wp_client_reports_pro_google_analytics_view_id');
    
	add_settings_field(
		'wp_client_reports_pro_google_analytics_key',
		__( 'Google Analytics API Key', 'wp-client-reports-pro' ),
		'wp_client_reports_pro_google_analytics_key_render',
		'wp_client_reports_options_page',
		'wp_client_reports_pro_ga_section'
    );

}


/**
 * Upload google analytics key from the options page
 */
function wp_client_reports_pro_google_analytics_key_upload($option) {
    if (isset($_POST['wp_client_reports_pro_google_analytics_key']) && $_POST['wp_client_reports_pro_google_analytics_key'] == 'uploaded') {
        return get_option('wp_client_reports_pro_google_analytics_key');
    } else if(!empty($_FILES["wp_client_reports_pro_google_analytics_key"]['tmp_name'])) {
        $json = file_get_contents($_FILES["wp_client_reports_pro_google_analytics_key"]['tmp_name']);
        delete_option('wp_client_reports_pro_google_analytics_view_id');
        wp_client_reports_delete_transients('wp_client_reports_google_analytics');
        require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client/vendor/autoload.php';
        $config = json_decode($json, true);
        $client = new Google_Client();
        $client->setAuthConfig($config);
        $client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        $analytics = new Google_Service_Analytics($client);
        $views_array = getViews($analytics);
        if (isset($views_array[0]->id)) {
            update_option('wp_client_reports_pro_google_analytics_view_id', $views_array[0]->id);
            $_POST['wp_client_reports_pro_google_analytics_view_id'] = $views_array[0]->id;
        }
        return $json;
    } else {
        return $option;
    }
}


/**
 * Add google analytics key upload field to the options page
 */
function wp_client_reports_pro_google_analytics_key_render(  ) {

    $json = get_option( 'wp_client_reports_pro_google_analytics_key' );
    $analytics_config = json_decode($json, true);
    $view_id = get_option( 'wp_client_reports_pro_google_analytics_view_id' );

    if ($analytics_config) {
        require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client/vendor/autoload.php';
        $client = new Google_Client();

        $client->setAuthConfig($analytics_config);
        $client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
       
        $analytics = new Google_Service_Analytics($client);

        $views_array = getViews($analytics);

    }
    
    if ($analytics_config) : ?>
        <select name="wp_client_reports_pro_google_analytics_view_id">
            <?php foreach($views_array as $view): ?>
                <option value="<?php echo $view->id; ?>" <?php if ($view->id == $view_id) { echo 'selected'; } ?>><?php echo $view->websiteUrl; ?> <?php echo $view->name; ?></option>
            <?php endforeach; ?>
        </select>
        &nbsp;&nbsp;<a href="#" style="color:#dc3232;" id="wp-client-reports-pro-ga-remove-config"><?php _e('Remove Config File','wp-client-reports-pro'); ?></a>
        <input type="hidden" name="wp_client_reports_pro_google_analytics_key" value="uploaded">
    <?php else: ?>
        <input type="file" name="wp_client_reports_pro_google_analytics_key" />
    <?php endif; ?>
    <?php
 }


 /**
 * Remove google analytics key
 */
add_action('wp_ajax_wp_client_reports_pro_ga_remove_config', 'wp_client_reports_pro_ga_remove_config');
function wp_client_reports_pro_ga_remove_config() {

    delete_option('wp_client_reports_pro_google_analytics_view_id');
    delete_option('wp_client_reports_pro_google_analytics_key');

    print json_encode(['status'=>'success']);
    wp_die();

}


/**
 * Ajax request report data for google analytics
 */
function wp_client_reports_pro_ga_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_ga_data($dates->start_date, $dates->end_date);
    
    print json_encode($data);
    wp_die();

}


/**
 * Get report data for google analytics
 */
function wp_client_reports_pro_get_ga_data($start_date, $end_date) {

    $json = get_option( 'wp_client_reports_pro_google_analytics_key' );
    $analytics_config = json_decode($json, true);
    $view_id = get_option( 'wp_client_reports_pro_google_analytics_view_id' );

    $google_analytics_data = get_transient('wp_client_reports_google_analytics_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($google_analytics_data === false) {

        $google_analytics_data = new \stdClass;
        $google_analytics_data->users = 0;
        $google_analytics_data->new_users = 0; 
        $google_analytics_data->sessions = 0;
        $google_analytics_data->sessions_per_user = 0;
        $google_analytics_data->hits = 0;
        $google_analytics_data->pageviews_per_session = 0;
        $google_analytics_data->avg_session_duration = 0;
        $google_analytics_data->bounce_rate = 0;

        if ($analytics_config && $view_id) {

            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client/vendor/autoload.php';

            $client = new Google_Client();

            $client->setAuthConfig($analytics_config);
            $client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
            
            $analytics = new Google_Service_Analytics($client);

            $general_stats = $analytics->data_ga->get(
                'ga:'. $view_id,
                $start_date,
                $end_date,
                'ga:users,ga:newusers,ga:sessions,ga:sessionsPerUser,ga:hits,ga:pageviewsPerSession,ga:avgSessionDuration,ga:bounceRate'
            ); //,ga:goalCompletionsAll,ga:goalConversionRateAll

            $general_stats_rows = $general_stats->getRows();

            if (isset($general_stats_rows[0])) {
                $google_analytics_data->users = $general_stats_rows[0][0];
                $google_analytics_data->new_users = $general_stats_rows[0][1]; 
                $google_analytics_data->sessions = $general_stats_rows[0][2];
                $google_analytics_data->sessions_per_user = round($general_stats_rows[0][3], 2);
                $google_analytics_data->hits = $general_stats_rows[0][4];
                $google_analytics_data->pageviews_per_session = round($general_stats_rows[0][5], 2);
                $google_analytics_data->avg_session_duration = round((($general_stats_rows[0][6]) / 60), 2);
                $google_analytics_data->bounce_rate = round($general_stats_rows[0][7], 1) . "%";
                //$google_analytics_data->goal_completionsAll = $general_stats[0][6];
                //$google_analytics_data->goalConversionRateAll = $general_stats[0][7];
            }

        }

        set_transient('wp_client_reports_google_analytics_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $google_analytics_data, 3600 * 24);
    }

    $google_analytics_data = apply_filters( 'wp_client_reports_pro_ga_data', $google_analytics_data, $start_date, $end_date );

    return $google_analytics_data;

}


/**
 * Get available google analytics views based on service key
 */
function getViews($analytics) {

    $google_analytics_views = get_transient('wp_client_reports_google_analytics_views');

    if ($google_analytics_views === false) {
  
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        $google_analytics_views = array();
        
        $account_items = $accounts->getItems();
        if (count($account_items) > 0) {
            foreach($account_items as $account_item) {
                $account_id = $account_item->getId();
                $properties = $analytics->management_webproperties->listManagementWebproperties($account_id);
                $property_items = $properties->getItems();
                if (count($property_items) > 0) {
                    foreach($property_items as $property_item) {
                        $property_id = $property_item->getId();
                        $profiles = $analytics->management_profiles->listManagementProfiles($account_id, $property_id);
                        $profile_items = $profiles->getItems();
                        if (count($profile_items) > 0) {
                            foreach($profile_items as $profile_item) {
                                $profile_item_id = $profile_item->getId();
                                $google_analytics_views[] = $profile_item;
                            }
                            
                        } else {
                            throw new Exception('No views (profiles) found for this user.');
                        }
                    }
                    
                } else {
                    throw new Exception('No properties found for this user.');
                }
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }

        set_transient('wp_client_reports_google_analytics_views', $google_analytics_views, 3600 * 24);
    }

    return $google_analytics_views;

}


/**
 * Report page section for google analytics
 */
function wp_client_reports_pro_stats_page_google_analytics() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-google-analytics">
                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"><?php _e('Toggle panel','wp-client-reports-pro'); ?>: <?php _e('Site Analytics','wp-client-reports-pro'); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span><?php _e('Site Analytics','wp-client-reports-pro'); ?></span></h2>
                <div class="inside">
                    <div class="main">

                        <div class="wp-client-reports-big-numbers">
                            <?php 
                                wp_client_reports_render_big_number(
                                    __( 'Users', 'wp-client-reports' ), 
                                    'wp-client-reports-pro-ga-users'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'New %s Users', 'wp-client-reports' ), '<br>' ), 
                                    'wp-client-reports-pro-ga-new-users'
                                );
                                wp_client_reports_render_big_number(
                                    __( 'Sessions', 'wp-client-reports' ), 
                                    'wp-client-reports-pro-ga-sessions'
                                );
                                wp_client_reports_render_big_number(
                                    sprintf( __( 'Sessions Per %s User', 'wp-client-reports' ), '<br>' ), 
                                    'wp-client-reports-pro-ga-sessions-per-user'
                                );
                            ?>
                        </div><!-- .wp-client-reports-big-numbers -->

                        <div class="wp-client-report-section wp-client-report-border-top">

                            <div class="wp-client-reports-big-numbers">
                                <?php 
                                    wp_client_reports_render_big_number(
                                        __( 'Pageviews', 'wp-client-reports' ), 
                                        'wp-client-reports-pro-ga-pageviews'
                                    );
                                    wp_client_reports_render_big_number(
                                        sprintf( __( 'Pages Per %s Session', 'wp-client-reports' ), '<br>' ), 
                                        'wp-client-reports-pro-ga-pages-per-session'
                                    );
                                    wp_client_reports_render_big_number(
                                        sprintf( __( 'Avg Session %s Duration', 'wp-client-reports-pro' ), '<br>' ),
                                        'wp-client-reports-pro-ga-avg-session-duration'
                                    );
                                    wp_client_reports_render_big_number(
                                        sprintf( __( 'Bounce %s Rate', 'wp-client-reports' ), '<br>' ), 
                                        'wp-client-reports-pro-ga-bounce-rate'
                                    );
                                ?>
                            </div><!-- .wp-client-reports-big-numbers -->

                        </div>

                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
}

/**
 * Report email section for google analytics
 */
function wp_client_reports_pro_stats_email_google_analytics($start_date, $end_date) {
    $ga_data = wp_client_reports_pro_get_ga_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Analytics', 'wp-client-reports-pro' ));

    wp_client_reports_render_email_row(
        $ga_data->users, 
        __( 'Users', 'wp-client-reports-pro' ), 
        $ga_data->new_users, 
        __( 'New Users', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $ga_data->sessions, 
        __( 'Sessions', 'wp-client-reports-pro' ), 
        $ga_data->sessions_per_user, 
        __( 'Sessions/User', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $ga_data->hits, 
        __( 'Pageviews', 'wp-client-reports-pro' ), 
        $ga_data->pageviews_per_session, 
        __( 'Pages/Session', 'wp-client-reports-pro' )
    );

    wp_client_reports_render_email_row(
        $ga_data->avg_session_duration, 
        __( 'Avg Session Duration', 'wp-client-reports-pro' ), 
        $ga_data->bounce_rate, 
        __( 'Bounce Rate', 'wp-client-reports-pro' )
    );
    
}


/**
 * When force refresh is called, clear all transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_google_analytics_update', 13 );
function wp_client_reports_force_google_analytics_update() {
    wp_client_reports_delete_transients('wp_client_reports_google_analytics');
}