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

        $message = __( "WP Client Reports Config File Successfully Added.", "wp-client-reports" );
        $type = 'updated';

        $json = file_get_contents($_FILES["wp_client_reports_pro_google_analytics_key"]['tmp_name']);
        delete_option('wp_client_reports_pro_google_analytics_view_id');
        wp_client_reports_delete_transients('wp_client_reports_google_analytics');
        delete_transient('wp_client_reports_google_analytics_views');
        
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php5.6/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '7.0.0', '>=') && version_compare(PHP_VERSION, '7.4.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.0/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '7.4.0', '>=') && version_compare(PHP_VERSION, '8.0.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.4/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php8/vendor/autoload.php';
        }

        $config = json_decode($json, true);
        $client = new Google_Client();
        try {
            $client->setAuthConfig($config);
            //$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
            $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
            $analytics = new Google_Service_Analytics($client);
            $httpClient = $client->authorize();
            $views_array = getViews($analytics, $client, $httpClient);
        } catch (Exception $e) {
            //return new WP_Error( 'wp_client_reports_ga_error', __( "<strong>Google Analytics Error:</strong> This does not seem to be a valid Google Analytics Service Account key.", "wp-client-reports" ) );
            // $message = __( "<strong>Google Analytics Error:</strong> This does not seem to be a valid Google Analytics Service Account key.", "wp-client-reports" );
            // $type = 'error';
            $message = __( "This does not seem to be a valid Google Analytics Service Account key.", "wp-client-reports" );
            $type = 'error';
        }

        

        if( is_wp_error( $views_array ) ) {
            //return new WP_Error( 'wp_client_reports_ga_error', __( "<strong>Google Analytics Error:</strong> This does not seem to be a valid Google Analytics Service Account key.", "wp-client-reports" ) );
            $message = __( "Google Analytics Error: Your key file is invalid or no google analytics properties were found", "wp-client-reports" );
            $type = 'error';
        } else {
            if (isset($views_array[0]->id)) {
                update_option('wp_client_reports_pro_google_analytics_view_id', $views_array[0]->id);
                $_POST['wp_client_reports_pro_google_analytics_view_id'] = $views_array[0]->id;
            }
        }

        add_settings_error(
            'wp-client-reports-pro-errors',
            esc_attr( 'settings_updated' ),
            $message,
            $type
        );

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

        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php5.6/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '7.0.0', '>=') && version_compare(PHP_VERSION, '7.4.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.0/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '7.4.0', '>=') && version_compare(PHP_VERSION, '8.0.0', '<')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.4/vendor/autoload.php';
        } else if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
            require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php8/vendor/autoload.php';
        }
        
        $client = new Google_Client();

        $client->setAuthConfig($analytics_config);
        //$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
       
        $analytics = new Google_Service_Analytics($client);

        $httpClient = $client->authorize();

        $views_array = getViews($analytics, $client, $httpClient);

    }

    settings_errors('wp_client_reports_pro_ga_config_error');
    
    if ($analytics_config) :
        if (is_array($views_array) && !empty($views_array) && !is_wp_error( $views_array )) : ?>
            <select name="wp_client_reports_pro_google_analytics_view_id">
                <?php foreach($views_array as $view): ?>
                    <?php 
                        $the_view_id = $view->id;
                        if (isset($view->type) && $view->type == 'ga4') {
                            $the_view_id = $view->id . "|ga4";
                        }
                    ?>
                    <option value="<?php echo $the_view_id; ?>" <?php if ($the_view_id == $view_id) { echo 'selected'; } ?>><?php echo $view->name; ?></option>
                <?php endforeach; ?>
            </select>
            &nbsp;&nbsp;
        <?php endif; ?>
        <a href="#" style="color:#dc3232;" id="wp-client-reports-pro-ga-remove-config"><?php _e('Remove Config File','wp-client-reports-pro'); ?></a> &nbsp;&nbsp;<a href="#" style="color:#dc3232;" id="wp-client-reports-pro-ga-reset-list"><?php _e('Reset List','wp-client-reports-pro'); ?></a>
        <input type="hidden" name="wp_client_reports_pro_google_analytics_key" value="uploaded">
    <?php else: ?>
        <input type="file" name="wp_client_reports_pro_google_analytics_key" />
        <?php if( $analytics_config && is_wp_error( $views_array ) ) : ?>
            <div class="notice notice-error">
                <p><?php echo $views_array->get_error_message(); ?></p>
            </div>
        <?php endif; ?>
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
 * Reset google analytics property/views list
 */
add_action('wp_ajax_wp_client_reports_pro_ga_reset_list', 'wp_client_reports_pro_ga_reset_list');
function wp_client_reports_pro_ga_reset_list() {

    delete_option('wp_client_reports_pro_google_analytics_view_id');
    delete_transient('wp_client_reports_google_analytics_views');

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

            if (version_compare(PHP_VERSION, '7.0.0', '<')) {
                require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php5.6/vendor/autoload.php';
            } else if (version_compare(PHP_VERSION, '7.0.0', '>=') && version_compare(PHP_VERSION, '7.4.0', '<')) {
                require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.0/vendor/autoload.php';
            } else if (version_compare(PHP_VERSION, '7.4.0', '>=') && version_compare(PHP_VERSION, '8.0.0', '<')) {
                require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php7.4/vendor/autoload.php';
            } else if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
                require_once plugin_dir_path( __FILE__ ) . '../../vendor/google-api-php-client-php8/vendor/autoload.php';
            }

            $client = new Google_Client();
            $client->setAuthConfig($analytics_config);

            //$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

            $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

            $google_analytics_type = 'ga3';
            $last_four_of_id = substr($view_id, -4);
            if ($last_four_of_id == '|ga4') {
                $google_analytics_type = 'ga4';
                $view_id = str_replace("|ga4", "", $view_id);
            }

            if ($google_analytics_type == 'ga4') {

                $httpClient = $client->authorize();

                $payload = [
                    'dateRanges' => [
                        'startDate' => $start_date,
                        'endDate' => $end_date,
                    ],
                    'metrics' => [
                        [ 'name' => 'totalUsers' ],
                        [ 'name' => 'newUsers' ],
                        [ 'name' => 'sessions' ],
                        [ 'name' => 'sessionsPerUser' ],
                        [ 'name' => 'screenPageViews' ],
                        [ 'name' => 'screenPageViewsPerSession' ],
                        [ 'name' => 'averageSessionDuration' ],
                        [ 'name' => 'engagementRate' ]
                    ]
                ];

                $response = $httpClient->post('https://analyticsdata.googleapis.com/v1beta/properties/' . $view_id . ':runReport', [
                    'json' => $payload
                ]);

                $data = json_decode($response->getBody()->getContents());

                if (isset($data->rows[0]->metricValues[0]->value)) {
                    $google_analytics_data->users = $data->rows[0]->metricValues[0]->value;
                    $google_analytics_data->new_users = $data->rows[0]->metricValues[1]->value; 
                    $google_analytics_data->sessions = $data->rows[0]->metricValues[2]->value;
                    $google_analytics_data->sessions_per_user = round($data->rows[0]->metricValues[3]->value, 2);
                    $google_analytics_data->hits = $data->rows[0]->metricValues[4]->value;
                    $google_analytics_data->pageviews_per_session = round($data->rows[0]->metricValues[5]->value, 2);
                    $google_analytics_data->avg_session_duration = round((($data->rows[0]->metricValues[6]->value) / 60), 2);
                    $google_analytics_data->bounce_rate = round(abs($data->rows[0]->metricValues[7]->value - 1) * 100, 1) . "%";
                }

            } else {
            
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

        }

        set_transient('wp_client_reports_google_analytics_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $google_analytics_data, 3600 * 24);
    }

    $google_analytics_data = apply_filters( 'wp_client_reports_pro_ga_data', $google_analytics_data, $start_date, $end_date );

    return $google_analytics_data;

}


/**
 * Get available google analytics views based on service key
 */
function getViews($analytics, $client, $httpClient) {

    $google_analytics_views = get_transient('wp_client_reports_google_analytics_views');

    if ($google_analytics_views === false) {

        $google_analytics_views = array();

        try {

            // Get the list of (V3 Universal) properties for the authorized user.
            $response = $httpClient->get('https://analyticsadmin.googleapis.com/v1alpha/accountSummaries');
            $data = json_decode($response->getBody()->getContents());

            if (isset($data->accountSummaries) && !empty($data->accountSummaries)) {
                foreach($data->accountSummaries as $account) {
                    if (isset($account->propertySummaries) && !empty($account->propertySummaries)) {
                        foreach($account->propertySummaries as $property) {
                            $view = new \stdClass;
                            $view->id = str_replace("properties/", "", $property->property);
                            $view->name = $property->displayName . " (Analytics V4)";
                            $view->type = 'ga4';
                            $google_analytics_views[] = $view;
                        }
                        
                    }
                }
            }
            
            // Get the list of (V3 Universal) views for the authorized user.
            $accounts = $analytics->management_accounts->listManagementAccounts();

            $account_items = $accounts->getItems();

            if (count($account_items) > 0) {
                foreach($account_items as $account_item) {
                    $account_id = $account_item->getId();
                    $account_name = $account_item->getName();
                    $properties = $analytics->management_webproperties->listManagementWebproperties($account_id);
                    $property_items = $properties->getItems();

                    if (count($property_items) > 0) {
                        foreach($property_items as $property_item) {
                            $property_id = $property_item->getId();
                            $property_name = $property_item->getName();
                            $profiles = $analytics->management_profiles->listManagementProfiles($account_id, $property_id);

                            
                            $profile_items = $profiles->getItems();

                            

                            if (count($profile_items) > 0) {
                                foreach($profile_items as $profile_item) {
                                    $view = new \stdClass;
                                    $view->id = $profile_item->getId();
                                    $view->name = $property_name . " (" . $profile_item->getName() . ")";
                                    $view->type = 'ga3';
                                    $google_analytics_views[] = $view;
                                }
                                
                            }
                        }
                        
                    }
                } 
            } 

        } catch (Exception $e) {
            return new WP_Error( 'wp_client_reports_ga_error', __( "Google Analytics Error: Your key file is invalid or no google analytics properties were found", "wp-client-reports" ) );
        }
        
        if (empty($google_analytics_views)) {
            return new WP_Error( 'wp_client_reports_ga_error', __( "Google Analytics Error: Your key file is invalid or no google analytics properties were found", "wp-client-reports" ) );
            //wp_safe_redirect( add_query_arg( array( 'page' => 'wp_client_reports', 'error' => 'ga-no-accounts' ), admin_url( 'options-general.php' ) ) );
            //throw new Exception('No accounts found for this user.');
        }  else {
            set_transient('wp_client_reports_google_analytics_views', $google_analytics_views, 3600 * 24);
        }

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
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Analytics','wp-client-reports-pro'); ?></h2>
                </div>
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