<?php
/*
Plugin Name: Media Library Folders Pro For WordPress
Plugin URI: http://maxgalleria.com
Description: Gives you the ability to adds folders and move files in the WordPress Media Library.
Version: 8.0.4
Author: Max Foundry
Author URI: http://maxfoundry.com

Copyright 2015-2021 Max Foundry, LLC (http://maxfoundry.com)
*/

if(defined('MAXGALLERIA_MEDIA_LIBRARY_VERSION_KEY')) {
   wp_die(__('You must deactivate Media Library Folders before activating Media Library Folders Pro', 'maxgalleria-media-library'));
}

include_once(__DIR__ . '/includes/attachments.php');

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {	
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

if(!defined("S3_VALID"))
	define("S3_VALID", "valid");
if(!defined("S3_FILE_COUNT_WARNING"))
	define("S3_FILE_COUNT_WARNING", "limit_warning");

require_once 'includes/maxgalleria-video-sc.php';
require_once(ABSPATH . 'wp-admin/includes/screen.php');

class MGMediaLibraryFoldersPro {
    
  public $upload_dir;
  public $wp_version;
  public $theme_mods;
	public $uploads_folder_name;
	public $uploads_folder_name_length;
	public $uploads_folder_ID;
	public $image_sizes;
	public $shortcode_video;
	public $blog_id;
	public $base_url_length;
	public $maxgalleria_media_library_pro_s3;
	public $s3_addon;
  public $version;
  public $folder_id;
  public $disable_media_ft;
  public $folders_to_hide = array();
  public $license_expiration;
  public $license_valid;
  public $front_end;
  public $current_user_can_upload;
  public $enable_user_role;
  public $disable_scaling;
  public $disable_list_mode;
  public $disable_non_admins;
  //public $mlfp_export;
  public $wpmf_integration;
  public $rollback_scaling;
  public $current_user_manage_options;
  public $mlfp_exim_folder;
  public $mlfp_exim_url;
  public $seo_file_title;
  public $seo_alt_text;
  
  public function __construct() {
    
		$this->blog_id = 0;
		$this->set_global_constants();
		$this->set_activation_hooks();        
    $this->wpmf_integration = get_option(MAXGALLERIA_WPMF, 'off');    
    $this->setup_hooks();        
    //$this->api_setup();    
		$this->upload_dir = wp_upload_dir();  
    $this->wp_version = get_bloginfo('version'); 
	  $this->base_url_length = strlen($this->upload_dir['baseurl']) + 1;
    $this->version = MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_NUM;
        
    //convert theme mods into an array
    $theme_mods = get_theme_mods();
    $this->theme_mods = json_decode(json_encode($theme_mods), true);
		
		$this->image_sizes = get_intermediate_image_sizes();
	  $this->image_sizes[] = 'full';
		
    if(class_exists('MaxGalleria') || class_exists('MaxGalleriaPro'))
		  $this->shortcode_video = new MaxGalleriaVideoShortcode();
        
    add_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER, '0' );    
    add_option( MAXGALLERIA_MLF_SORT_TYPE, 'ASC' );        
    add_option( MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY, 'on' );    
    add_user_meta(get_current_user_id(), MAXGALLERIA_MEDIA_LIBRARY_GRID_OR_LIST, 'on', true);
		
	  //add_action( 'plugins_loaded', array($this, 'mlfp_plugin_init'));	
		
	  $this->uploads_folder_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");
	  $this->uploads_folder_name_length = strlen($this->uploads_folder_name);
    $this->folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
    $this->uploads_folder_ID = $this->folder_id;
    $this->disable_media_ft = get_option(MAXGALLERIA_REMOVE_FT, 'off');
    $this->front_end = get_option( MAXGALLERIA_FE_SCRIPTS, 'off');
    $this->license_valid = $this->is_valid_license();
    $this->enable_user_role = get_option( MAXGALLERIA_RESTRICT_USER_ROLE, 'off');
    $this->disable_non_admins = get_option( MAXGALLERIA_DISABLE_NON_ADMINS, 'off');
    $this->license_expiration = get_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES, '');
    $this->rollback_scaling = get_option( MAXGALLERIA_ROLLBACK_SCALLING, 'off');
    $this->seo_file_title = get_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT);
    $this->seo_alt_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);
    
		$use_set_locale = get_option(MAXGALLERIA_USE_SET_LOCALE, 'no' );
    if($use_set_locale == 'on') {
		  $locale = get_option(MAXGALLERIA_LOCALE, '' );
      if(!empty($locale))
        setlocale(LC_ALL, $locale);
    }
    
    $this->alter_folder_table();
    
    $this->mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
    $this->mlfp_exim_url = $this->upload_dir['url'] . '/' . MLFP_EXIM_FOLDER;    
        
  }
  
	public function set_global_constants() {	
		//define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
    //if(!defined('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR'))
		//  define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME);
		//define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL', plugin_dir_url('') . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME);
		//if(!defined('MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE'))
    //  define("MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE", "mgmlp_nonce");
        
		define('MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_KEY', 'maxgalleria_media_library_version');
		define('MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_NUM', '8.0.4');
		define('MAXGALLERIA_MEDIA_LIBRARY_PRO_IGNORE_NOTICE', 'maxgalleria_media_library_ignore_notice');
		define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR'))
		  define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME);
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL'))
		  define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL', plugin_dir_url('') . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_NAME);
		if(!defined('MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE'))
      define("MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE", "mgmlp_nonce");
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE'))
      define("MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE", "mgmlp_media_folder");
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME'))
      define("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME", "mgmlp_upload_folder_name");
    if(!defined("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID"))
      define("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID", "mgmlp_upload_folder_id");
		if(!defined('MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE'))
      define("MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE", "mgmlp_folders");    
    define("MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE", "mgmlp_userrole_permissions");    
    define("MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE", "mgmlp_thumbnails");        
    define("MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE", "mgmlp_file_purge");    
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER'))
      define("MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER", "mgmlp_sort_order");
    define("MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER", "mgmlp_cat_sort_order");
    if(!defined('NEW_MEDIA_LIBRARY_VERSION'))  
      define("NEW_MEDIA_LIBRARY_VERSION", "4.0.0");
    if(!defined('MAXGALLERIA_MLP_REVIEW_NOTICE'))
      define("MAXGALLERIA_MLP_REVIEW_NOTICE", "maxgalleria_mlp_review_notice");
    if(!defined('MAXGALLERIA_MLP_FEATURE_NOTICE'))
      define("MAXGALLERIA_MLP_FEATURE_NOTICE", "maxgalleria_mlp_feature_notice");
    
		define('MLPP_EDD_SHOP_URL', 'https://maxgalleria.com/');		
	  define('EDD_MLPP_NAME', 'Media Library Plus Pro' ); 
	  define('EDD_MLPP_ID', '10509' ); 
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY'))
      define("MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY", "mgmlp_move_or_copy");
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO'))
      define("MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO", "mgmlp_image_seo");
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT'))
      define("MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT", "mgmlp_default_alt");
    if(!defined('MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT'))
      define("MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT", "mgmlp_default_title");
    //define("MAXGALLERIA_MEDIA_LIBRARY_BACKUP_TABLE", "mgmlp_old_posts");
		//define("MAXGALLERIA_MEDIA_LIBRARY_POSTMETA_UPDATED", "mgmlp_postmeta_updated");
    define("MAXGALLERIA_MEDIA_LIBRARY_GRID_OR_LIST", "mgmlp_grid_or_list");
		
		define("MLFP_TS_URL", "https://maxgalleria.com/forums/topic/troubleshooting-media-library-folders-pro/");
		define("MAXGALLERIA_MEDIA_LIBRARY_CATEGORY", "media_category");
    if(!defined('MAXGALLERIA_MLP_DISPLAY_INFO'))
		  define("MAXGALLERIA_MLP_DISPLAY_INFO", "mlf_display_info");
    if(!defined('MAXGALLERIA_MLP_DISABLE_FT'))
		  define("MAXGALLERIA_MLP_DISABLE_FT", "mlf_disable_ft");		
		define("MAXGALLERIA_MLP_PAGINATION", "mlf_enable_pagnation");		
		define("MAXGALLERIA_MLP_UPLOAD", "mlf_enable_upload");		
    if(!defined('MAXGALLERIA_MLP_ITEMS_PRE_PAGE'))
		  define("MAXGALLERIA_MLP_ITEMS_PRE_PAGE", "mlf_items_per_page");		
		define("MAXGALLERIA_MLP_FOLDER_TO_LOAD", "mlfp_folder_to_load");	
    if(!defined('MAXGALLERIA_REMOVE_FT'))
		  define("MAXGALLERIA_REMOVE_FT", "mlf_remove_ft");		
    if(!defined('MAXG_SYNC_FOLDER_PATH'))
		  define("MAXG_SYNC_FOLDER_PATH", "mlfp_sync_folder_path");		
    if(!defined('MAXG_SYNC_FOLDER_PATH_ID'))
		  define("MAXG_SYNC_FOLDER_PATH_ID", "mlfp_sync_folder_path_id");		
    if(!defined('MAXG_SYNC_FILES'))
		  define("MAXG_SYNC_FILES", "mlfp_sync_files");		
    if(!defined('MAXG_SYNC_FOLDERS'))
      define("MAXG_SYNC_FOLDERS", "mlfp_sync_folders");
    if(!defined('MAXG_MC_FILES'))
      define("MAXG_MC_FILES", "mlfp_move_file_ids");
    if(!defined('MAXG_MC_DESTINATION_FOLDER'))
      define("MAXG_MC_DESTINATION_FOLDER", "mlfp_move_file_destination");
		define("MAXGALLERIA_MEDIA_LIBRARY_EXPIRES", "mlfp_expires_date");
		define("MAXGALLERIA_MEDIA_LIBRARY_UNLIMITED", "mlfp_unlimited");    
		define("MAXGALLERIA_MEDIA_LIBRARY_NETWORK_ACTIVATED", "mlfp_net_activted");    
    define("MAXG_NEW_LICENSE","mlfp-new-license");
		define("MAXGALLERIA_FE_SCRIPTS", "mlfp_fe_scripts");		
		define("MAXGALLERIA_RESTRICT_USER_ROLE", "mlfp_use_user_roles");		
		if(!defined('MAXGALLERIA_DISABLE_SCALLING'))
      define("MAXGALLERIA_DISABLE_SCALLING", "mlfp_disable_scaling");
    define("MAXGALLERIA_ROLLBACK_SCALLING", "mlfp_rollback_scaling");
    define("MAXGALLERIA_DEBUG_QUERIES", "mlfp_debug_queries");
    define("MAXGALLERIA_DISABLE_LIST_MODE", "mlfp_disable_listmode");    
    define("MAXGALLERIA_DISABLE_NON_ADMINS", "mlfp_disable_non_admins");
    define("MAXGALLERIA_USE_SET_LOCALE", "mlfp_use_set_locale");
    define("MAXGALLERIA_LOCALE", "mlfp_locale");
    if(!defined('WPMF_TAXO'))
      define('WPMF_TAXO', 'wpmf-category');
    if(!defined('MAXGALLERIA_WPMF'))
      define("MAXGALLERIA_WPMF", "mlfp-wpmf-integration");
    define("MLFP_MAX_PURGE_FILES", 20);
    define("MLFP_PREVENT_CAPTION_IMPORT", "mlfp_no_captions");    		
		if(!defined('MAXGALLERIA_MLF_SORT_TYPE'))
		  define("MAXGALLERIA_MLF_SORT_TYPE", "mlf_sort_order_type");		
    define('MAXGALLERIA_MEDIA_LIBRARY_SEARCH_MODE','mlfp-search-mode');
    
    define('MLFP_EXIM_FOLDER', 'mlfp_exim_files');
    if(!defined('MLFP_EXIM_FOLDER_LOCATION'))    
      define('MLFP_EXIM_FOLDER_LOCATION', 'mlfp_exim_folder');
    define('MAXGALLERIA_MEDIA_LIBRARY_CSV_DATA_TABLE', 'mgmlp_csv_data');
    define('MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE', 'mgmlp_import_folders');
    define('MAXGALLERIA_POSTMETA_INDEX', 'mgmlp-index');
        
		// Bring in all the actions and filters
		require_once 'includes/maxgalleria-media-library-hooks.php';
    
  }
  
 	public function set_activation_hooks() {
		register_activation_hook(__FILE__, array($this, 'do_activation'));
		register_deactivation_hook(__FILE__, array($this, 'do_deactivation'));
	}
  	
  public function do_activation($network_wide) {
		if ($network_wide) {
			$correct_mulisite = $this->check_for_old_multisite();			
			if($correct_mulisite === false )
				wp_die ( _e("This multisite was create with a version earlier than Wordpress 3.5 an is not compatible with Media Library Folders Pro. To continue, click the Back Arrow.", 'maxgalleria-media-library' ));
			$this->call_function_for_each_site(array($this, 'activate'));
		}
		else {
			$this->activate();
		}
	}
  
	public function do_deactivation($network_wide) {	
		if ($network_wide) {
			$this->call_function_for_each_site(array($this, 'deactivate'));
		}
		else {
			$this->deactivate();
		}
	}
  
	public function activate() {
		
		if(class_exists('MaxGalleriaMediaLib')) {
			add_action( 'admin_notices', array($this, 'runing_mlp_error_notice'));
			exit();
		}
		
    update_option(MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_KEY, MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_NUM);
    update_option('uploads_use_yearmonth_folders', 0);    
    $this->add_folder_table();
    $this->add_userrole_table();
    $this->add_thumbnail_table();
    $this->alter_folder_table();
    $this->add_purge_table();
    if ( 'impossible_default_value_1234' === get_option( MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, 'impossible_default_value_1234' ) ) {
      $this->scan_attachments();
      $this->admin_check_for_new_folders(true);
		  update_option(MAXGALLERIA_MEDIA_LIBRARY_SRC_FIX, true);
    } 
		    
    if(!defined('SKIP_AUTO_FOLDER_CHECK')) {
      if ( ! wp_next_scheduled( 'new_folder_check' ) )
        wp_schedule_event( time(), 'daily', 'new_folder_check' );
    }
         
	}
            	  	
	public function check_for_old_multisite() {
		
		$content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
		$upload_sites = ABSPATH . $content_folder . DIRECTORY_SEPARATOR . "uploads";
    		
		if(!file_exists($upload_sites)) 
			return false;
		else
			return true;
	}
  
  public function setup_mg_media_plus() {
		add_menu_page(__('Media Library Folders Pro','maxgalleria-media-library'), __('Media Library Folders Pro','maxgalleria-media-library'), 'upload_files', 'mlfp-folders', array($this, 'mlfp_folders'), 'dashicons-admin-media', 11 );				
    add_submenu_page('mlfp-folders', __('Folders & Files','maxgalleria-media-library'), __('Folders & Files','maxgalleria-media-library'), 'upload_files', 'mlfp-folders' );
    if($this->license_valid) {
      add_submenu_page('mlfp-folders', __('Thumbnails','maxgalleria-media-library'), __('Thumbnails','maxgalleria-media-library'), 'manage_options', 'mlfp-thumbnails', array($this, 'mlfp_thumbnails'));
    }
    add_submenu_page('mlfp-folders', __('Image SEO','maxgalleria-media-library'), __('Image SEO','maxgalleria-media-library'), 'upload_files', 'mlfp-image-seo', array($this, 'mlfp_image_seo'));
    add_submenu_page('mlfp-folders', __('Settings','maxgalleria-media-library'), __('Settings','maxgalleria-media-library'), 'manage_options', 'mlfp-settings8', array($this, 'mlfp_settings8'));
		if(class_exists('MGMediaLibraryFoldersProS3')) {
      global $maxgalleria_media_library_pro_s3;
      add_submenu_page('mlfp-folders', __('Cloud Storage','maxgalleria-media-library'), __('Cloud Storage','maxgalleria-media-library'), 'manage_options', 'mlfp-cloud', array($maxgalleria_media_library_pro_s3, 'mlfps3_settings'));
      add_submenu_page(null, __('View Sync Log','maxgalleria-media-library'), __('View Sync Log','maxgalleria-media-library'), 'manage_options', 'view-sync-log', array($this->s3_addon, 'mlfps3_sync_log'));			
    }  
    add_submenu_page('mlfp-folders', __('Support','maxgalleria-media-library'), __('Support','maxgalleria-media-library'), 'manage_options', 'mlfp-support', array($this, 'mlfp_support'));
    add_submenu_page(null, __('Search Library','maxgalleria-media-library'), __('Search Library','maxgalleria-media-library'), 'upload_files', 'mlfp-search-library', array($this, 'search_library'));
		add_submenu_page(null, '', '', 'manage_options', 'mlp-review-later', array($this, 'mlp_set_review_later'));
		add_submenu_page(null, '', '', 'manage_options', 'mlp-review-notice', array($this, 'mlp_set_review_notice_true'));    		
		add_submenu_page(null, '', '', 'manage_options', 'mlp-feature-notice', array($this, 'mlp_set_feature_notice_true'));    		
  }  
  
  public function get_maxgalleria_galleries() {
    
    global $wpdb;
    
    $sql = "select ID, post_title 
	from {$wpdb->prefix}posts 
  LEFT JOIN {$wpdb->prefix}postmeta ON({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id)
	where post_type = 'maxgallery' and post_status = 'publish'
  and {$wpdb->prefix}postmeta.meta_key = 'maxgallery_type'
	and {$wpdb->prefix}postmeta.meta_value = 'image'
	order by LOWER(post_name)";
  
    //error_log($sql);
  
    $gallery_list = "";
    $rows = $wpdb->get_results($sql);

    if($rows) {
      foreach ($rows as $row) {
        $gallery_list .='<option value="' . esc_attr($row->ID) . '">' . esc_html($row->post_title) . '</option>' . PHP_EOL;
      }
    }
    return $gallery_list;
  }    
    
  public function mlfp_folders() {
	  require_once 'includes/media-folders.php';	 		
	}  
  
  public function mlfp_folder_access() {
	  require_once 'includes/mlfp-folder-access.php';	 		
  }

  public function mlfp_thumbnails() {
	  require_once 'includes/mlfp-thumbnails.php';	 		    
  }
  
  public function mlfp_image_seo() {
	  require_once 'includes/mlfp-image-seo.php';	 		    
  }
  
  public function mlfp_settings8() {
	  require_once 'includes/mlfp-settings.php';	 		    
  }
          
  public function mlfp_cloud() {
	  require_once 'includes/mlfp-cloud.php';	 		    
  }
  
  public function mlfp_support() {
	  require_once 'includes/mlfp-support.php';	 		    
  }
  
  public function media_library() {
	  require_once 'includes/media-library.php';	 		        
  }
  
  public function support_tips() {
	  require_once 'includes/mlfp-support-tips.php';	 		        
  }
  
  public function support_articles() {
	  require_once 'includes/mlfp-support-articles.php';	 		        
  }
  
  public function support_sys_info() {
	  require_once 'includes/mlfp-support-sys-info.php';	 		        
  }
  
  public function license(){
    require_once 'includes/settings.php';
  }
    
  public function display_mlfp_header() {
    
    $html = '';
    
		$html .= '<div class="mgmlp-header">' . PHP_EOL;
                      
    $html .= '  <div id="mlfp-logo-container">' . PHP_EOL;
    $html .= '    <img id="mlpf-logo" src="' . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/images/mlfp-logo.png" alt="media library folders pro logo" width="100" height="100">' . PHP_EOL;
    $html .= '  </div>' . PHP_EOL;
          
    $html .= '  <div id="mlfp-link-container">' . PHP_EOL;
    $html .= '    <div id="mlfp-links">' . PHP_EOL;
    $html .= '      <div>' . __('Brought to you by ', 'maxgalleria-media-library') .' <a target="_blank" href="http://maxfoundry.com">MaxFoundry</a></div>' . PHP_EOL;
    $html .= '      <div>' . __('Makers of', 'maxgalleria-media-library') . ' <a target="_blank"  href="http://maxbuttons.com/">MaxButtons</a>, <a target="_blank" href="http://maxbuttons.com/product-category/button-packs/">WordPress Buttons</a> ' . __('and', 'maxgalleria-media-library') . ' <a target="_blank" href="http://maxgalleria.com/">MaxGalleria</a></div>' . PHP_EOL;
    $html .= '    </div>' . PHP_EOL;
          
    $html .= '    <div id="mlfp-support">' . PHP_EOL;
    $html .= '      <div><strong>' . __('Quick Support', 'maxgalleria-media-library') . '</strong></div>' . PHP_EOL;
    $html .= '      <div>' . __('Click here to', 'maxgalleria-media-library') . '&nbsp;<a href="' . MLFP_TS_URL . '" target="_blank">' . __('Fix Common Problems', 'maxgalleria-media-library') . '</a></div>' . PHP_EOL;
    $html .= '      <div>' . __('Need more help? Check out our ', 'maxgalleria-media-library') . ' <a href="https://maxgalleria.com/forums/forum/media-library-plus-pro/" target="_blank">' . __('Support Forums', 'maxgalleria-media-library') . '</a></div>' . PHP_EOL;
    $html .= '      <div>' . __('Or Email Us at', 'maxgalleria-media-library' ) . ' <a href="mailto:support@maxfoundry.com">support@maxfoundry.com</a></div>' . PHP_EOL;
    $html .= '    </div>' . PHP_EOL;
    
    $html .= '  </div>' . PHP_EOL;
    
                   
    $html .= '</div>' . PHP_EOL;
    
    return $html;
    
  }
  
  public function get_upload_status() {
    $data = get_userdata(get_current_user_id());
    if (!is_object($data) || !isset($data->allcaps['upload_files']))
      $this->current_user_can_upload = false;
    else
      $this->current_user_can_upload = $data->allcaps['upload_files'];
    
    if (!is_object($data) || !isset($data->allcaps['manage_options']))
      $this->current_user_manage_options = false;
    else
      $this->current_user_manage_options = $data->allcaps['manage_options'];
  }  
  		
	public function update_achachment_data() {
		
    global $wpdb;

		// get all the attachment IDs
    $sql = "select ID from $wpdb->prefix" . "posts where post_type = 'attachment' order by ID";

    $rows = $wpdb->get_results($sql);
		if($rows) {
			foreach($rows as $row) {

				// get the file location and meta data location
        $uploads_location = get_post_meta( $row->ID, '_wp_attached_file', true );
        $attachment_data = get_post_meta( $row->ID, '_wp_attachment_metadata', true );
				
				if(isset($attachment_data[0])) {
					if(isset($attachment_data[0]['file'])) {
						$meta_file = $uploads_location;
						$meta_location = $attachment_data[0]['file'];
						
						// update the meta data location if it does not match
						if($meta_location !== $meta_file) {
						  $attachment_data[0]['file'] = $meta_file;
						  update_post_meta( $row->ID, '_wp_attachment_metadata', $attachment_data );												
						}
				  }					
				}	else {
					if(isset($attachment_data['file'])) {
						$meta_file = $uploads_location;
						$meta_location = $attachment_data['file'];
						
						// update the meta data location if it does not match
						if($meta_location !== $meta_file) {
						  $attachment_data['file'] = $meta_file;
						  update_post_meta( $row->ID, '_wp_attachment_metadata', $attachment_data );												
						}
					}										
				}	
			}			
		}
		// never repeat this process
		update_option(MAXGALLERIA_MEDIA_LIBRARY_SRC_FIX, true);

	}
		  
  public function deactivate() {
    if(!defined('SKIP_AUTO_FOLDER_CHECK'))
      wp_clear_scheduled_hook('new_folder_check');
	}
  
  public function call_function_for_each_site($function) {
		global $wpdb;
		
		// Hold this so we can switch back to it
		$current_blog = $wpdb->blogid;
		
		// Get all the blogs/sites in the network and invoke the function for each one
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blog_ids as $blog_id) {
			switch_to_blog($blog_id);
		  $this->blog_id = $blog_id;
			call_user_func($function);
		}
		$this->blog_id = 0;
		
		// Now switch back to the root blog
		switch_to_blog($current_blog);
	}
  
    public function enqueue_admin_print_styles() {		
		
	?>
		<style>
		#setting-error-tgmpa {
			display: none;
		}
		</style>
		<script>
			// deterime what browser we are using
			var doc = document.documentElement;
			doc.setAttribute('data-useragent', navigator.userAgent);
		</script>
  <?php
		
    global $pagenow, $current_screen;

    if(isset($_REQUEST['page'])) {
	        
      // on these pages load our styles and scripts
      if($_REQUEST['page'] == 'mlfp-folders'   
				|| $_REQUEST['page'] == 'mlfp-thumbnails'
				|| $_REQUEST['page'] == 'mlfp-image-seo'
				|| $_REQUEST['page'] == 'mlfp-settings8'
				|| $_REQUEST['page'] == 'mlfp-cloud'
        || $_REQUEST['page'] == 'mlfp-search-library'
				|| $_REQUEST['page'] == 'mlfp-support') {
                        
        wp_enqueue_style('foundation', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/libs/foundation/foundation.min.css');    
        wp_enqueue_style('mlfp8', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css');				
        wp_enqueue_style('mlf-jstree-style', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/themes/default/style.min.css');    		

        wp_register_script('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/jstree.min.js', array('jquery'));
        wp_enqueue_script('jstree');
        
        wp_enqueue_style('mlfp-fontawesome', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/libs/fontawesome-free-6.0.0-web/css/all.min.css');
        wp_enqueue_style('wp-jquery-ui-dialog');    
                
      }
									
    }
    		
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-progressbar');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-ui-position');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-sortable');
      wp_enqueue_script('jquery-ui-dialog');    
										
    wp_enqueue_style('mlp-notice', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlp-notice.css');
    
		if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php')) && 
        $this->license_valid &&
        $this->disable_media_ft != 'on') {
      
			  //wp_enqueue_style('maxgalleria-media-library', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/maxgalleria-media-library.css');				
        wp_enqueue_style('mlfp8', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css');				

      // do not load for any of these pages
      if (isset( $current_screen ) && 
         ($current_screen->base != 'all-import_page_pmxi-admin-import' &&             
          $current_screen->base != 'all-import_page_pmxi-admin-manage' &&
          $current_screen->post_type != 'acf-field-group' &&
          $current_screen->base != 'pmxi-admin-manage' &&
          $current_screen->base != 'pmxi-admin-import')) {
        
        wp_enqueue_style('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/themes/default/style.min.css');    		
        //wp_enqueue_style('maxgalleria-media-library', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/maxgalleria-media-library.css', array('jstree'));				
        wp_enqueue_style('mlfp8', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css', array('jstree'));				

        wp_register_script('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/jstree.min.js', array('jquery'));
        wp_enqueue_script('jstree');
        
        if(class_exists('Vc_Manager')) {
          wp_register_script('mlfp-vc', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/mlfp-vc.js', array('jquery'));
          wp_enqueue_script('mlfp-vc');
        }
        
      }	
    }
  }
      
  public function enqueue_admin_print_scripts() {
    global $pagenow, $current_screen, $wp_version;
    
    //error_log("enqueue_admin_print_scripts");
    //error_log("pagenow $pagenow");

		if(!in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    
      if($this->current_user_can_upload || defined('MLFP_SKIP_UPLOAD_STATUS_CHECK') ) {

        if (isset( $current_screen ) && 
          $current_screen->base != 'product_page_advanced_bulk_edit' &&
          $current_screen->base != 'all-import_page_pmxi-admin-import' && 
          $current_screen->base != 'all-import_page_pmxi-admin-manage' &&
          $current_screen->post_type != 'acf-field-group' &&
          $current_screen->base != 'pmxi-admin-manage' &&
          $current_screen->base != 'pmxi-admin-import') {
                    
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-migrate', ABSPATH . WPINC . '/js/jquery/jquery-migrate.min.js', array('jquery'));            

          wp_register_script( 'loader-folders', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/mgmlp-loader.js', array( 'jquery' ), '', true );

          wp_localize_script( 'loader-folders', 'mgmlp_ajax', 
                array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                       'confirm_file_delete' => __('Are you sure you want to delete the selected files?', 'maxgalleria-media-library' ),
                       'confirm_file_processing' => __('Are you sure you want to process the selected items?', 'maxgalleria-media-library' ),
                       'nothing_selected' => __('No items were selected.', 'maxgalleria-media-library' ),
                       'no_images_selected' => __('No images were selected.', 'maxgalleria-media-library' ),
                       'no_quotes' => __('Folder names cannot contain single or double quotes.', 'maxgalleria-media-library' ),
                       'no_blank' => __('The folder name cannot be blank.' ),
                       'no_blank_filename' => __('The new file name cannot be blank.' ),                  
                       'no_spaces' => __('Folder names cannot contain spaces.', 'maxgalleria-media-library' ),
                       'valid_file_name' => __('Please enter a valid file name with no spaces.', 'maxgalleria-media-library' ),
                       'blank_category' => __('A category name cannot be blank.', 'maxgalleria-media-library' ),
                       'no_categories_selected' => __('No categories were selected.', 'maxgalleria-media-library' ),
                       'select_only_one' => __("Select only one file to view its categories and press 'Get Categories'", 'maxgalleria-media-library' ),
                       'copy_message' => __('The shortcode was copied to the clipboard.', 'maxgalleria-media-library' ),
                       'select_to_embed' => __('Please select a file before clicking the Embed Shortcode button.', 'maxgalleria-media-library' ),
                       'select_to_replace' => __('Please select a file before clicking the Replace File button.', 'maxgalleria-media-library' ),
                       'mime_mismatch' => __('The replacment file type does not match to selected file type. A file needs to be replaced with the same type (.jpg for .jpg, .png for .png or .pdf for .pdf). Please choose another file.', 'maxgalleria-media-library' ),
                       'files_found' => __(' files found', 'maxgalleria-media-library' ),
                       'no_files_found' => __('No uncataloged files found', 'maxgalleria-media-library' ),
                       'no_ids_selected' => __('No file IDs have been selected.', 'maxgalleria-media-library' ),
                       'site_url' => site_url(),
                       'select_folder' => __('Select a destination folder in the folder tree', 'maxgalleria-media-library'),
                       'copying_stopped' => __('File moving stopped.', 'maxgalleria-media-library'),
                       'source_destination_error' => __('The source and destination folders are the same. Please select a different destination folder.', 'maxgalleria-media-library'),
                       'destination_folder' => __('Destination Folder: ', 'maxgalleria-media-library'),
                       'moving_files' => __('Please wait while files are moved...', 'maxgalleria-media-library'),
                       'filetype_not_allowed' => __(' is not an allowed embed file type. Only pdf, mpeg, mp3, oga, wav, mp4, webm, ogg and ogv can be embedded.', 'maxgalleria-media-library' ),
                       'move_mode' => esc_html__('Drag and drop is set for moving files', 'maxgalleria-media-library' ),
                       'copy_mode' => esc_html__('Drag and drop is set for copying files', 'maxgalleria-media-library' ),
                       'incorrect_backup' => esc_html__('Incorrect backup file. Please provide an export file downloaded from Media Library Folders Pro.', 'maxgalleria-media-library' ),
                       'nonce'=> wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE))
                     ); 

          wp_enqueue_script('loader-folders');
          
          wp_register_script( 'mlfp-backup', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/mlfp-backup.js', array( 'jquery' ), '', true );

          wp_localize_script( 'mlfp-backup', 'mlfpb_ajax', 
                array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                       'nonce'=> wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE))
                     ); 

          wp_enqueue_script('mlfp-backup');
          
        }

        if (isset( $current_screen ) && 'upload' === $current_screen->base && $this->license_valid) {

          if($this->disable_media_ft != 'on') {
            
            if($wp_version >= '5.8' || $wp_version == '5.8-RC2' || $wp_version == '5.8-RC3') 
              wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media58.css');
            else
              wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media.css');

            wp_register_script( 'uploads-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/uploads-media.js', array( 'jquery', 'media-models', ), '', true );

            wp_localize_script( 'uploads-media', 'mlfpmedia', $this->media_localize());

            wp_enqueue_script('uploads-media');
          }
        }
      }    
    }
  }
  
  public function mlfp_enqueue_media() {
    global $current_screen, $wp_version;
    //$license_valid = $this->is_valid_license();

    if($this->disable_media_ft != 'on' && $this->license_valid && ($this->current_user_can_upload || defined('MLFP_SKIP_UPLOAD_STATUS_CHECK'))) {
            
      if (isset( $current_screen ) && 
              
        $current_screen->base != 'all-import_page_pmxi-admin-import' && 
        $current_screen->base != 'all-import_page_pmxi-admin-manage' &&
        $current_screen->post_type != 'acf-field-group' &&
        $current_screen->base != 'pmxi-admin-manage' &&
        $current_screen->base != 'pmxi-admin-import') {
          
        $this->reset_mlfp_folder_id();

        wp_enqueue_style('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/themes/default/style.min.css');    		    
        
        if($wp_version >= '5.8' || $wp_version == '5.8-RC2' || $wp_version == '5.8-RC3') 
          wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media58.css');
        else
          wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media.css');
        
        wp_enqueue_style('maxgalleria-media-library', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css', array('jstree'));				

        if($this->disable_media_ft != 'on') {
         
          wp_register_script( 'mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/mlfp-media.js', array( 'jquery', 'media-models', ), '', true );

          wp_localize_script( 'mlfp-media', 'mlfpmedia', $this->media_localize());

          wp_enqueue_script('mlfp-media');
        }

        wp_register_script('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/jstree.min.js', array('jquery'));
        wp_enqueue_script('jstree');
      
      }
    }
  }
        
  public function media_localize() {
    
    $upload_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        
    $theme = get_option('template');
    $user = wp_get_current_user();    
    //error_log(print_r($user,true));
        
    return array( 
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'nonce'=> wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE),
      'upload_message' => __('Select the folder where you wish to view or upload files.', 'maxgalleria-media-library'),
      'uploads_folder_id' => $upload_id,
      'new_folder_id' => get_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, true),
      //'new_folder_id' =>  get_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD),
      'theme' => get_option('template'),
		  'gutenberg' => $this->gutenberg_active(),        
      'uploads_path' => $this->get_folder_location($upload_id) 
    );   
    
  }
  
  public function media_localize1() {
    
    $upload_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        
    $theme = get_option('template');
        
    return array( 
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'nonce'=> wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE),
      'upload_message' => __('Select the folder where you wish to view or upload files.', 'maxgalleria-media-library'),
      'uploads_folder_id' => get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID),
      'new_folder_id' => get_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD),
      'theme' => get_option('template'),
		  'gutenberg' => $this->gutenberg_active(),        
      'uploads_path' => $this->get_folder_location($upload_id) 
    );   
    
  }
 	
  public function setup_hooks() {
    
    global $pagenow;
    //$license_valid = $this->is_valid_license();
    
    $this->license_valid = $this->is_valid_license();
		
	  add_action( 'plugins_loaded', array($this, 'mlfps3_plugin_init'));	
    
    // to remove list mode in the media library
    $this->disable_list_mode = get_option( MAXGALLERIA_DISABLE_LIST_MODE, 'off');
    if($this->disable_list_mode == 'on') {
      add_action('admin_init', function() {$_GET['mode'] = 'grid';}, 100);
      add_action('admin_head', array($this, 'remove_listview_icon'));      
    } 
    
		add_action('init', array($this, 'load_textdomain'));
	  add_action('init', array($this, 'register_mgmlp_post_type'));
		add_action('init', array($this, 'show_mlp_admin_notice'));
    add_action('init', array($this, 'get_upload_status'),1);
    if(!class_exists('eml') && !function_exists( 'wpuxss_get_eml_slug' ) )     
		  add_action( 'init', array($this, 'media_category_taxonomy'));
	  add_action('admin_init', array($this, 'ignore_notice'));
		if(class_exists('MGMediaLibraryFoldersProS3')) {			    
			global $maxgalleria_media_library_pro_s3;
	    add_action('admin_init', array($maxgalleria_media_library_pro_s3, 'edd_mlpp_register_option_s3'));      
    }
    
		add_action('admin_print_styles', array($this, 'enqueue_admin_print_styles'),10);
		add_action('admin_print_scripts', array($this, 'enqueue_admin_print_scripts'),10);
    add_action('admin_menu', array($this, 'setup_mg_media_plus'));
				
		add_action('admin_footer', array($this, 'mlp_button_admin_footer'));		
		//add_action('media_buttons_context', array($this, 'mlp_gallery_button'));					
    
    $this->disable_scaling = get_option( MAXGALLERIA_DISABLE_SCALLING, 'off');
    if($this->disable_scaling == 'on') {
      add_filter( 'big_image_size_threshold', '__return_false' );
    } 
    
    $enable_upload = get_option(MAXGALLERIA_MLP_UPLOAD, 'off');
    if($enable_upload == 'on') {
      //add_action('print_styles', array($this, 'enqueue_print_styles'),10);
      add_action('print_scripts', array($this, 'enqueue_print_scripts'),10);      
    }
         
    add_action('wp_ajax_nopriv_create_new_folder', array($this, 'create_new_folder'));
    add_action('wp_ajax_create_new_folder', array($this, 'create_new_folder'));
    
    add_action('wp_ajax_nopriv_delete_maxgalleria_media', array($this, 'delete_maxgalleria_media'));
    add_action('wp_ajax_delete_maxgalleria_media', array($this, 'delete_maxgalleria_media'));
    
    add_action('wp_ajax_nopriv_upload_attachment', array($this, 'upload_attachment'));
    add_action('wp_ajax_upload_attachment', array($this, 'upload_attachment'));
        
    add_action('wp_ajax_nopriv_frontend_upload_attachment', array($this, 'frontend_upload_attachment'));
    add_action('wp_ajax_frontend_upload_attachment', array($this, 'frontend_upload_attachment'));
        
    add_action('wp_ajax_nopriv_add_to_max_gallery', array($this, 'add_to_max_gallery'));
    add_action('wp_ajax_add_to_max_gallery', array($this, 'add_to_max_gallery'));
    
    add_action('wp_ajax_nopriv_maxgalleria_rename_image', array($this, 'maxgalleria_rename_image'));
    add_action('wp_ajax_maxgalleria_rename_image', array($this, 'maxgalleria_rename_image'));
        
    add_action('wp_ajax_nopriv_sort_contents', array($this, 'sort_contents'));
    add_action('wp_ajax_sort_contents', array($this, 'sort_contents'));
				
    add_action('wp_ajax_nopriv_sort_categories', array($this, 'sort_categories'));
    add_action('wp_ajax_sort_categories', array($this, 'sort_categories'));
    
    add_action('wp_ajax_nopriv_mlf_change_sort_type', array($this, 'mlf_change_sort_type'));
    add_action('wp_ajax_mlf_change_sort_type', array($this, 'mlf_change_sort_type'));				
    		
    add_action('wp_ajax_nopriv_mgmlp_move_copy', array($this, 'mgmlp_move_copy'));
    add_action('wp_ajax_mgmlp_move_copy', array($this, 'mgmlp_move_copy'));		
    
    add_action('wp_ajax_nopriv_mgmlp_grid_list', array($this, 'mgmlp_grid_list'));
    add_action('wp_ajax_mgmlp_grid_list', array($this, 'mgmlp_grid_list'));		
                    
    add_action( 'new_folder_check', array($this,'admin_check_for_new_folders'));
    
    add_action('wp_ajax_nopriv_mlf_check_for_new_folders', array($this, 'mlf_check_for_new_folders'));
    add_action('wp_ajax_mlf_check_for_new_folders', array($this, 'mlf_check_for_new_folders'));		    
        
    //add_action( 'added_post_meta', array($this,'add_attachment_to_folder'), 10, 4);
    
    add_filter( 'wp_generate_attachment_metadata', array($this, 'add_attachment_to_folder2'), 10, 4);    
    
    add_action( 'delete_attachment', array($this,'delete_folder_attachment'));
			
    //remove ?
    add_action('wp_ajax_nopriv_mlp_tb_load_folder', array($this, 'mlp_tb_load_folder'));
    add_action('wp_ajax_mlp_tb_load_folder', array($this, 'mlp_tb_load_folder'));				

    add_action('wp_ajax_nopriv_mlp_load_folder', array($this, 'mlp_load_folder'));
    add_action('wp_ajax_mlp_load_folder', array($this, 'mlp_load_folder'));		
				
    add_action('wp_ajax_nopriv_mlp_get_image_info', array($this, 'mlp_get_image_info'));
    add_action('wp_ajax_mlp_get_image_info', array($this, 'mlp_get_image_info'));		
						
    add_action('wp_ajax_nopriv_mlp_image_add_caption', array($this, 'mlp_image_add_caption'));
    add_action('wp_ajax_mlp_image_add_caption', array($this, 'mlp_image_add_caption'));		
		
    add_action('wp_ajax_nopriv_mlp_update_description', array($this, 'mlp_update_description'));
    add_action('wp_ajax_mlp_update_description', array($this, 'mlp_update_description'));		
		
    // for custom thickbox with folder tree
		//add_filter( 'admin_post_thumbnail_html', array( $this, 'mlp_admin_post_thumbnail'), 10, 2 );
		
    //add_action('wp_ajax_nopriv_mlp_add_featured_image', array($this, 'mlp_add_featured_image'));
    //add_action('wp_ajax_mlp_add_featured_image', array($this, 'mlp_add_featured_image'));		
		
		//add_action('wp_ajax_nopriv_mlp_display_folder_ajax', array($this, 'mlp_display_folder_contents_ajax'));
		add_action('wp_ajax_nopriv_mlp_display_folder_contents_ajax', array($this, 'mlp_display_folder_contents_ajax'));
    add_action('wp_ajax_mlp_display_folder_contents_ajax', array($this, 'mlp_display_folder_contents_ajax'));		
    
		//add_action('wp_ajax_nopriv_mlp_display_folder_list_ajax', array($this, 'mlp_display_folder_list_ajax'));
    //add_action('wp_ajax_mlp_display_folder_list_ajax', array($this, 'mlp_display_folder_list_ajax'));		
        
		add_action('wp_ajax_nopriv_mlfp_search_list_page', array($this, 'mlfp_search_list_page'));
    add_action('wp_ajax_mlfp_search_list_page', array($this, 'mlfp_search_list_page'));		
    		
    add_action('wp_ajax_nopriv_mlp_display_folder_contents_images_ajax', array($this, 'mlp_display_folder_contents_images_ajax'));
    add_action('wp_ajax_mlp_display_folder_contents_images_ajax', array($this, 'mlp_display_folder_contents_images_ajax'));		

    add_action('wp_ajax_nopriv_mlpp_hide_template_ad', array($this, 'mlpp_hide_template_ad'));
    add_action('wp_ajax_mlpp_hide_template_ad', array($this, 'mlpp_hide_template_ad'));				
		
    add_action('wp_ajax_nopriv_mlpp_create_new_ng_gallery', array($this, 'mlpp_create_new_ng_gallery'));
    add_action('wp_ajax_mlpp_create_new_ng_gallery', array($this, 'mlpp_create_new_ng_gallery'));				
			
    add_action('wp_ajax_nopriv_mg_add_to_ng_gallery', array($this, 'mg_add_to_ng_gallery'));
    add_action('wp_ajax_mg_add_to_ng_gallery', array($this, 'mg_add_to_ng_gallery'));				
		
    add_action('wp_ajax_nopriv_mgmlp_add_to_gallery', array($this, 'mgmlp_add_to_gallery'));
    add_action('wp_ajax_mgmlp_add_to_gallery', array($this, 'mgmlp_add_to_gallery'));				
		
    add_action('wp_ajax_nopriv_display_folder_nav_ajax', array($this, 'display_folder_nav_ajax'));
    add_action('wp_ajax_mgmlp_display_folder_nav_ajax', array($this, 'display_folder_nav_ajax'));				
		
    add_action('wp_ajax_nopriv_mlp_get_folder_data', array($this, 'mlp_get_folder_data'));
    add_action('wp_ajax_mlp_get_folder_data', array($this, 'mlp_get_folder_data'));		
		
    add_action('wp_ajax_nopriv_mlf_set_new_categories', array($this, 'mlf_set_new_categories'));
    add_action('wp_ajax_mlf_set_new_categories', array($this, 'mlf_set_new_categories'));		
		
    add_action('wp_ajax_nopriv_mlp_load_categories', array($this, 'mlp_load_categories'));
    add_action('wp_ajax_mlp_load_categories', array($this, 'mlp_load_categories'));				
        
    add_action('wp_ajax_nopriv_mlp_load_categories_list', array($this, 'mlp_load_categories_list'));
    add_action('wp_ajax_mlp_load_categories_list', array($this, 'mlp_load_categories_list'));				
		
    add_action('wp_ajax_nopriv_mgmlp_add_new_category', array($this, 'mgmlp_add_new_category'));
    add_action('wp_ajax_mgmlp_add_new_category', array($this, 'mgmlp_add_new_category'));				
				
    add_action('wp_ajax_nopriv_mlp_load_categories_ajax', array($this, 'mlp_load_categories_ajax'));
    add_action('wp_ajax_mlp_load_categories_ajax', array($this, 'mlp_load_categories_ajax'));				
        
    add_action('wp_ajax_nopriv_mlfp_new_folder_check', array($this, 'mlfp_new_folder_check'));
    add_action('wp_ajax_mlfp_new_folder_check', array($this, 'mlfp_new_folder_check'));				
    
    add_action('wp_ajax_nopriv_mlfp_save_role_access', array($this, 'mlfp_save_role_access'));
    add_action('wp_ajax_mlfp_save_role_access', array($this, 'mlfp_save_role_access'));				
        
    add_action('wp_ajax_nopriv_mlfp_get_role_data', array($this, 'mlfp_get_role_data'));
    add_action('wp_ajax_mlfp_get_role_data', array($this, 'mlfp_get_role_data'));				
        
		//add_action('save_post', array($this, 'mlp_save_featured_image_id'), 20, 2);
		
		add_action('admin_init', array($this, 'edd_mlpp_plugin_updater'), 0 );
		
    add_action('admin_init', array($this, 'edd_mlpp_activate_license'));
		
    add_action('admin_init', array($this, 'edd_mlpp_deactivate_license'));
		
	  add_action('admin_init', array($this, 'edd_mlpp_register_option'));		
		
    add_action('wp_ajax_nopriv_regen_mlp_thumbnails', array($this, 'regen_mlp_thumbnails'));
    add_action('wp_ajax_regen_mlp_thumbnails', array($this, 'regen_mlp_thumbnails'));				
		
		add_action( 'wp_ajax_regeneratethumbnail', array( $this, 'ajax_process_image' ) );
		$this->capability = apply_filters( 'regenerate_thumbs_cap', 'manage_options' );

    add_action('wp_ajax_nopriv_mlp_image_seo_change', array($this, 'mlp_image_seo_change'));
    add_action('wp_ajax_mlp_image_seo_change', array($this, 'mlp_image_seo_change'));				

    add_action('wp_ajax_nopriv_mlp_get_attachment_image_src', array($this, 'mlp_get_attachment_image_src'));
    add_action('wp_ajax_mlp_get_attachment_image_src', array($this, 'mlp_get_attachment_image_src'));				
		
    add_action('wp_ajax_nopriv_hide_maxgalleria_media', array($this, 'hide_maxgalleria_media'));
    add_action('wp_ajax_hide_maxgalleria_media', array($this, 'hide_maxgalleria_media'));						
		
    add_action('wp_ajax_nopriv_mlf_get_categories', array($this, 'mlf_get_categories'));
    add_action('wp_ajax_mlf_get_categories', array($this, 'mlf_get_categories'));						
				
    add_action('wp_ajax_nopriv_mlf_hide_info', array($this, 'mlf_hide_info'));
    add_action('wp_ajax_mlf_hide_info', array($this, 'mlf_hide_info'));						
				
    add_action('wp_ajax_nopriv_update_mlfp_settings', array($this, 'update_mlfp_settings'));
    add_action('wp_ajax_update_mlfp_settings', array($this, 'update_mlfp_settings'));						
				
    //add_action('wp_ajax_nopriv_mlfp_update_meta_info', array($this, 'mlfp_update_meta_info'));
    //add_action('wp_ajax_mlfp_update_meta_info', array($this, 'mlfp_update_meta_info'));								
    				
    add_action('wp_ajax_nopriv_mlfp_get_file_info', array($this, 'mlfp_get_file_info'));
    add_action('wp_ajax_mlfp_get_file_info', array($this, 'mlfp_get_file_info'));
		
    add_action('wp_ajax_nopriv_mlfp_get_next_attachments', array($this, 'mlfp_get_next_attachments'));
    add_action('wp_ajax_mlfp_get_next_attachments', array($this, 'mlfp_get_next_attachments'));
    
    add_action('wp_ajax_nopriv_mlfp_update_folder_id', array($this, 'mlfp_update_folder_id'));
    add_action('wp_ajax_mlfp_update_folder_id', array($this, 'mlfp_update_folder_id'));
    
    add_action('wp_ajax_nopriv_mlfp_run_sync_process', array($this, 'mlfp_run_sync_process'));
    add_action('wp_ajax_mlfp_run_sync_process', array($this, 'mlfp_run_sync_process'));
        
    add_action('wp_ajax_nopriv_mlfp_process_mc_data', array($this, 'mlfp_process_mc_data'));
    add_action('wp_ajax_mlfp_process_mc_data', array($this, 'mlfp_process_mc_data'));				
				
    add_action('wp_ajax_nopriv_mlfp_get_folder_path', array($this, 'mlfp_get_folder_path'));
    add_action('wp_ajax_mlfp_get_folder_path', array($this, 'mlfp_get_folder_path'));				
    
    add_action('wp_ajax_nopriv_mlfp_get_destination_folder_path', array($this, 'mlfp_get_destination_folder_path'));
    add_action('wp_ajax_mlfp_get_destination_folder_path', array($this, 'mlfp_get_destination_folder_path'));	
        
    add_action('wp_ajax_nopriv_mlfp_move_single_file', array($this, 'mlfp_move_single_file'));
    add_action('wp_ajax_mlfp_move_single_file', array($this, 'mlfp_move_single_file'));			
        
    add_action('wp_ajax_nopriv_ajax_mlfp_save_mc_data', array($this, 'ajax_mlfp_save_mc_data'));
    add_action('wp_ajax_ajax_mlfp_save_mc_data', array($this, 'ajax_mlfp_save_mc_data'));			
    
    add_action('wp_ajax_nopriv_mlfp_update_tn_settings', array($this, 'mlfp_update_tn_settings'));
    add_action('wp_ajax_mlfp_update_tn_settings', array($this, 'mlfp_update_tn_settings'));				    
        
    add_action('wp_ajax_nopriv_mlfp_remove_thumbnails', array($this, 'mlfp_remove_thumbnails'));
    add_action('wp_ajax_mlfp_remove_thumbnails', array($this, 'mlfp_remove_thumbnails'));				    
        
    add_action('wp_ajax_nopriv_mlfp_thumbnail_reset', array($this, 'mlfp_thumbnail_reset'));
    add_action('wp_ajax_mlfp_thumbnail_reset', array($this, 'mlfp_thumbnail_reset'));				    
    
    add_action('wp_ajax_nopriv_mlfp_license_network_activate', array($this, 'mlfp_license_network_activate'));
    add_action('wp_ajax_mlfp_license_network_activate', array($this, 'mlfp_license_network_activate'));				        
    
    add_action('wp_ajax_nopriv_mlfp_license_network_deactivate', array($this, 'mlfp_license_network_deactivate'));
    add_action('wp_ajax_mlfp_license_network_deactivate', array($this, 'mlfp_license_network_deactivate'));				        
    
    add_action('wp_ajax_nopriv_mgmlp_filter_images', array($this, 'mgmlp_filter_images'));
    add_action('wp_ajax_mgmlp_filter_images', array($this, 'mgmlp_filter_images'));    
    
    add_action('wp_ajax_nopriv_mlfp_add_wmf_folder', array($this, 'mlfp_add_wmf_folder'));
    add_action('wp_ajax_mlfp_add_wmf_folder', array($this, 'mlfp_add_wmf_folder'));    
        
    add_action('wp_ajax_nopriv_mlfp_add_wmf_attachment', array($this, 'mlfp_add_wmf_attachment'));
    add_action('wp_ajax_mlfp_add_wmf_attachment', array($this, 'mlfp_add_wmf_attachment'));    
    
    add_action('wp_ajax_nopriv_mlfp_import_into_wpmf', array($this, 'mlfp_import_into_wpmf'));
    add_action('wp_ajax_mlfp_import_into_wpmf', array($this, 'mlfp_import_into_wpmf'));    
        
    //add_action('wp_ajax_nopriv_mlfp_delete_terms', array($this, 'mlfp_delete_terms'));
    //add_action('wp_ajax_mlfp_delete_terms', array($this, 'mlfp_delete_terms'));    
    
    add_action('wp_ajax_nopriv_mlfp_get_folder_count', array($this, 'mlfp_get_folder_count'));
    add_action('wp_ajax_mlfp_get_folder_count', array($this, 'mlfp_get_folder_count'));    
    
    add_action('wp_ajax_nopriv_mlfp_import_next_folder', array($this, 'mlfp_import_next_folder'));
    add_action('wp_ajax_mlfp_import_next_folder', array($this, 'mlfp_import_next_folder'));    
           
    add_action('wp_ajax_nopriv_mlfp_import_next_file', array($this, 'mlfp_import_next_file'));
    add_action('wp_ajax_mlfp_import_next_file', array($this, 'mlfp_import_next_file'));        
            
    add_action('wp_ajax_nopriv_mlfp_wpmf_folder_count', array($this, 'mlfp_wpmf_folder_count'));
    add_action('wp_ajax_mlfp_wpmf_folder_count', array($this, 'mlfp_wpmf_folder_count'));        
        
    add_action('wp_ajax_nopriv_mlfp_import_next_wpmf_folder', array($this, 'mlfp_import_next_wpmf_folder'));
    add_action('wp_ajax_mlfp_import_next_wpmf_folder', array($this, 'mlfp_import_next_wpmf_folder'));        
            
    add_action('wp_ajax_nopriv_maxgalleria_get_file_url', array($this, 'maxgalleria_get_file_url'));
    add_action('wp_ajax_maxgalleria_get_file_url', array($this, 'maxgalleria_get_file_url'));        
        
    add_action('wp_ajax_nopriv_mlfp_mime_type_test', array($this, 'mlfp_mime_type_test'));
    add_action('wp_ajax_mlfp_mime_type_test', array($this, 'mlfp_mime_type_test'));        
    
    add_action('wp_ajax_nopriv_determine_mime_type', array($this, 'determine_mime_type'));
    add_action('wp_ajax_determine_mime_type', array($this, 'determine_mime_type'));        
        
    add_action('wp_ajax_nopriv_mlfp_replace_attachment', array($this, 'mlfp_replace_attachment'));
    add_action('wp_ajax_mlfp_replace_attachment', array($this, 'mlfp_replace_attachment'));        
    
    add_action('wp_ajax_nopriv_run_file_detect_process', array($this, 'run_file_detect_process'));
    add_action('wp_ajax_run_file_detect_process', array($this, 'run_file_detect_process'));								
        
    add_action('wp_ajax_nopriv_clear_purge_table', array($this, 'clear_purge_table'));
    add_action('wp_ajax_clear_purge_table', array($this, 'clear_purge_table'));								
        
    add_action('wp_ajax_nopriv_refresh_purge_table', array($this, 'refresh_purge_table'));
    add_action('wp_ajax_refresh_purge_table', array($this, 'refresh_purge_table'));								
            
    add_action('wp_ajax_nopriv_mlfp_update_purge_action', array($this, 'mlfp_update_purge_action'));
    add_action('wp_ajax_mlfp_update_purge_action', array($this, 'mlfp_update_purge_action'));
        
    add_action('wp_ajax_nopriv_mlfp_process_purge_file', array($this, 'mlfp_process_purge_file'));
    add_action('wp_ajax_mlfp_process_purge_file', array($this, 'mlfp_process_purge_file'));
        
    add_action('wp_ajax_nopriv_mlfp_get_next_ml_file', array($this, 'mlfp_get_next_ml_file'));
    add_action('wp_ajax_mlfp_get_next_ml_file', array($this, 'mlfp_get_next_ml_file'));
        
    add_action('wp_ajax_nopriv_mlfp_update_purge_count', array($this, 'mlfp_update_purge_count'));
    add_action('wp_ajax_mlfp_update_purge_count', array($this, 'mlfp_update_purge_count'));
        
    add_action('wp_ajax_nopriv_mlfp_save_search_type', array($this, 'mlfp_save_search_type'));
    add_action('wp_ajax_mlfp_save_search_type', array($this, 'mlfp_save_search_type'));
    
    add_action('wp_ajax_mlfp_refresh_backups', array($this, 'mlfp_refresh_backups'));

    add_action('wp_ajax_mlfp_create_backup_folder', array($this, 'mlfp_create_backup_folder'));

    add_action('wp_ajax_new_backup_folder', array($this, 'new_backup_folder'));
    
    add_action('wp_ajax_mlfp_save_bk_data', array($this, 'mlfp_save_bk_data'));
        
    add_action('wp_ajax_mlfp_create_zip_archive', array($this, 'mlfp_create_zip_archive'));
            
    add_action('wp_ajax_mlfp_close_zip_archive', array($this, 'mlfp_close_zip_archive'));
        
    add_action('wp_ajax_mlfp_add_file_to_archive', array($this, 'mlfp_add_file_to_archive'));
    
    //add_action('wp_ajax_nopriv_mlfp_exim_download', array($this, 'mlfp_exim_download'));
    //add_action('wp_ajax_mlfp_exim_download', array($this, 'mlfp_exim_download'));    
    
    add_action('wp_ajax_mlfp_exim_delete_backup', array($this, 'mlfp_exim_delete_backup'));    
    
    //add_action('wp_ajax_mlfp_upload_backup', array($this, 'mlfp_upload_backup'));    
    
    add_action('wp_upload_file_chuncks_chuncks', array( $this, 'upload_file_chuncks'));    
    
    add_action('wp_ajax_exim_upload_file', array($this, 'exim_upload_file'));    
        
    add_action('wp_ajax_exim_unzip_file', array($this, 'exim_unzip_file'));    
    
    add_action('wp_ajax_mlfp_exim_load_import_data', array($this, 'mlfp_exim_load_import_data'));    
    
    add_action('wp_ajax_mlfp_exim_next_folder', array($this, 'mlfp_exim_next_folder'));    
    
    add_action('wp_ajax_mlfp_exim_next_file', array($this, 'mlfp_exim_next_file'));                
            
    if($this->wpmf_integration == 'on') {
      add_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10, 4);        
      add_action('wpmf_delete_folder', array($this, 'delete_wpmf_folder'), 10, 1);    
    }
    
    add_action('wp_loaded', array($this, 'get_sizes_to_deactivate'));    
				
    add_action('wp_enqueue_media', array($this, 'mlfp_enqueue_media'), 99, 1);    

    //add_action('pre-plupload-upload-ui', array($this, 'set_initial_folder_id')); 
    
    if(class_exists('PLL_CRUD_Posts')) {
      add_action('pll_translate_media', array($this, 'add_new_laguage_attachment_to_folder'), 10, 3);
    }  
            								
		add_filter( 'body_class', array($this, 'mlf_body_classes'));
		add_filter( 'admin_body_class', array($this, 'mlf_body_classes'));
		
		//if(function_exists( 'et_epanel_admin_js' ))
		  add_filter( 'et_pb_add_upload_button', array($this, 'add_mlfp_button'));
            
				// Only run in post/page creation and edit screens
		//if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    $this->disable_media_ft = get_option(MAXGALLERIA_REMOVE_FT, 'off');
    $this->front_end = get_option( MAXGALLERIA_FE_SCRIPTS, 'off');
    if($this->disable_media_ft != 'on' && $this->license_valid) {      
    //if($this->disable_media_ft != 'on') {      
      if( isset( $_POST['action'] ) && ( $_POST['action'] == 'query-attachments' ) ){      
        add_filter( 'posts_where', array($this, 'filter_by_folder_id'), 8, 2 );
        add_filter('posts_join', array($this, 'query_add_folder_table'), 9, 1 );
      }

      // moved to run_plugins_loaded
      //add_filter('upload_dir', array(&$this,'save_to_mlfp_dir'));
      add_action( 'plugins_loaded', array($this, 'run_plugins_loaded'));

      add_action( 'wp', array($this, 'reset_mlfp_folder_id'));
    }
    
    if(class_exists('Themify_Builder') && $this->license_valid) {
      add_action ('wp_enqueue_scripts', array($this, 'front_end_scripts'), 999);      
    }
        
    // check for beaver builder theme
    $current_theme = wp_get_theme();
    $template = $current_theme->get('Template');
    $name = $current_theme->get('Name');
    //error_log("checking for front end scripts " . $this->front_end);
    if(($template == 'bb-theme' ||
       $this->front_end == 'on' ||
       $name == 'Divi' ||
       $name == 'Extra' ||
       class_exists('FLBuilderLoader') || // beaver builder plugin
       $name == 'Beaver Builder Theme') && $this->license_valid) {
         //error_log("front_end_scripts");
         add_action ('wp_enqueue_scripts', array($this, 'front_end_scripts'), 999);
    }  
    
  }
  
  public function run_plugins_loaded() {
    
    add_filter('upload_dir', array(&$this,'save_to_mlfp_dir'));

  }
    
  public function api_setup() {
    
    
    // http://localhost/folders3/wp-json/mlfp-api/v1/get-uploads-dir
    add_action( 'rest_api_init', function () {
      register_rest_route( 'mlfp-api/v1/', '/get-uploads-dir', array(
        'methods' => 'GET',
        'callback' => array($this, 'api_get_uploads_dir'),
      ) );
    } );
    
    // http://localhost/folders3/wp-json/mlfapi/tracks
    add_action( 'rest_api_init', function () {
      register_rest_route( 'mlfp-api/v1/', '/create-folder', array(
        'methods' => 'GET',
        'callback' => array($this, 'api_create_folder'),
      ) );
    } );
    
    
  }
  
  public function api_get_uploads_dir($request) {
    
    $clientNonce = $request->get_header( 'x_wp_nonce' );

//    if(!wp_verify_nonce($clientNonce, 'wp_rest')) {
//      error_log('missing nonce!');
//      die();  
//    }  
    
    $uplaods_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "none");
    $uplaods_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
    
    $data = array(
      'uploads_name' => $uplaods_name,
      'uplaods_id' => $uplaods_id
    );
    
    return $data;
    die();    
    
  }
  
  public function api_create_folder($request) {
    
    $clientNonce = $request->get_header( 'x_wp_nonce' );

    if(!wp_verify_nonce($clientNonce, 'wp_rest')) {
      error_log('missing nonce!');
      die();  
    }  
    
    
    $folder_name = $request->get_param('folder_name');
    
    $folder_path = $request->get_param('folder_path');
    
    $data = array(
      $folder = $folder_path . DIRECTORY_SEPARATOR . $folder_name
    );
    
    
    return $data;
    die();    
    
  }
  
    
  public function remove_listview_icon() {
    ?>
    <style type="text/css">
        .view-switch .view-list {
            display: none;
        }
    </style>
    <?php        
  }
  
  public function front_end_scripts() {
    global $wp_version;    
    //error_log("front_end_scripts");
    wp_enqueue_media();
    //$license_valid = $this->is_valid_license();
    
    if($this->disable_media_ft != 'on' && $this->license_valid && $this->current_user_can_upload) {
                 
        $this->reset_mlfp_folder_id();

        wp_enqueue_style('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/themes/default/style.min.css');    		    
        
        if($wp_version >= '5.8' || $wp_version == '5.8-RC2' || $wp_version == '5.8-RC3') 
          wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media58.css');
        else
          wp_enqueue_style('mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp-media.css');
        
        //wp_enqueue_style('maxgalleria-media-library', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/maxgalleria-media-library.css', array('jstree'));
        wp_enqueue_style('mlfp8', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css', array('jstree'));				
        

        wp_register_script( 'mlfp-media', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/mlfp-media.js', array( 'jquery', 'media-models', ), '', true );

        wp_localize_script( 'mlfp-media', 'mlfpmedia', $this->media_localize());

        wp_enqueue_script('mlfp-media');

        wp_register_script('jstree', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/js/jstree/jstree.min.js', array('jquery'));
        wp_enqueue_script('jstree');
           
    }       
  }
  
  public function enqueue_print_scripts() {

    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');  
    wp_register_script('mlfp-upload', MICSA_PP_URL . '/js/upload.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('mlfp-upload');
  
  }
  
  public function reset_mlfp_folder_id() {
    //error_log("reset_mlfp_folder_id");
    //$folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
    $user = wp_get_current_user();    
    //update_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);    
    update_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
  }
  
  public function save_to_mlfp_dir( $param ){
    
    // no need to load pluggable.php, using action 'plugins_loaded'
    
    $user = wp_get_current_user();    
    
    $folder_id = get_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, true);
    
	  $mlfp_dir = trim(get_post_meta( $folder_id, '_wp_attached_file', true ));

    if($mlfp_dir != "") {
      $param['path'] = $param['path'] . "/" . $mlfp_dir;
      $param['url'] = $param['url'] . "/" . $mlfp_dir;
    }

    return $param;
  }  
      
  public function modify_attachments( $query ) {
        
    add_filter('posts_join', array($this, 'query_add_folder_table'), 8, 2 );
    add_filter( 'posts_where', array($this, 'filter_by_folder_id'), 9, 1 );
        
    return $query;
  }
  
  public function query_add_folder_table($join) {
    global $wp_query, $wpdb;
    
    if( isset( $_POST['action'] ) && ( ( $_POST['action'] == 'query-attachments' ) || ( $_POST['action'] == 'mla-query-attachments' ) ) ){ 
      $join .= " LEFT JOIN $wpdb->prefix" . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE . " ON $wpdb->posts.ID = $wpdb->prefix" . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE . ".post_id ";
    }
    
    return $join;
  }  
  
  //public function filter_by_folder_id( $where, &$wp_query ) {
  public function filter_by_folder_id( $where, $wp_query ) {
    global $wpdb;
    
    //$folder_id = get_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
    
    //if(!function_exists('wp_get_current_user')) {
    //  include(ABSPATH . "wp-includes/pluggable.php"); 
    //}    
    
    $user = wp_get_current_user();    
    
    $folder_id = get_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, true);
    
    //error_log("folder_id $folder_id");
    
    $where .= " AND $wpdb->prefix" . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE . ".folder_id = $folder_id ";

    return $where;
  }      
  		
	public function mlfps3_plugin_init() {
		
		if(class_exists('MGMediaLibraryFoldersProS3')) {			
			global $maxgalleria_media_library_pro_s3;
			$this->s3_addon = &$maxgalleria_media_library_pro_s3;						
						
			//add_filter( 'wp_get_attachment_url', array( $this, 'mlfp_get_attachment_url' ), 99, 2 );

			//add_filter( 'wp_get_attachment_image_src', array( $this, 'mlfp_get_attachment_image_src'), 99, 4 );
			
		}
				
	}
		
	function mlf_body_classes( $classes ) {
    
		$locale = "locale-" . str_replace('_','-', strtolower(get_locale()));
		if(is_array($classes))
		  $classes[] = $locale;
		else
			$classes .= " " . $locale;
    
    if($this->enable_user_role == 'on') {
      // get the current user role
      $user = wp_get_current_user();
      $role = $user->roles ? $user->roles[0] : '';
      
      // add a role class
      if(!empty($role)) {
        $role .= '-role';
        if(is_array($classes))
          $classes[] = $role;
        else
          $classes .= " " . $role;
      }
    }
    
    
    
		return $classes;
	}	
			
	function runing_mlp_error_notice() {
    ?>
    <div class="error notice">
        <p><?php _e( 'Please deactivate Media Library Folders. It should not running when Media Library Folders Pro is activated', 'maxgalleria-media-library' ); ?></p>
    </div>
    <?php
  }
	
	function edd_mlpp_plugin_updater() {
    
		// retrieve our license key from the DB
		$license_key = trim( get_option( 'mg_edd_mlpp_license_key' ) );

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( MLPP_EDD_SHOP_URL, __FILE__, array(
				'version' 	=> MAXGALLERIA_MEDIA_LIBRARY_PRO_VERSION_NUM, 				// current version number
				'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
				'item_name' => EDD_MLPP_NAME, 	// name of this plugin
				'item_id'   => EDD_MLPP_ID,
				'author' 	=> 'MaxFoundry INC'  // author of this plugin
			)
		);
	}
		
	public function edd_mlpp_activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_mlpp_license_activate'] ) ) {

			// run a quick security check
			if( ! check_admin_referer( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'mg_edd_mlpp_license_key' ) );

			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( EDD_MLPP_NAME ), // the name of our product in EDD
				'item_id'   => EDD_MLPP_ID,
				'url'       => site_url()
			);

			// Call the custom API.
			$response = wp_remote_post( MLPP_EDD_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
			$output = print_r($response, true);
			//$this->write_log("license activate response $output");

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'mg_edd_mlpp_license_status', $license_data->license );
      $this->license_valid = $license_data->license;
			//$info = print_r($license_data, true);
			//update_option( 'mg_edd_mlpp_license_response', $info );
    }
		if(class_exists('MGMediaLibraryFoldersProS3')) {			
			global $maxgalleria_media_library_pro_s3;
        if( isset( $_POST['edd_s3_license_activate'] ) ) {

        // run a quick security check
        if( ! check_admin_referer( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ) )
          return; // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim( $maxgalleria_media_library_pro_s3->get_mlfp_option( 'mg_edd_s3_license_key' ) );

        // data to send in our API request
        $api_params = array(
          'edd_action'=> 'activate_license',
          'license' 	=> $license,
          'item_name' => urlencode( EDD_S3_NAME ), // the name of our product in EDD
          'item_id'   => EDD_S3_ID,
          'url'       => site_url()
        );

        // Call the custom API.
        $response = wp_remote_post( S3_EDD_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
        //error_log(print_r($response, true));

        // make sure the response came back okay
        if ( is_wp_error( $response ) )
          return false;

        // decode the license data
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

        if(isset($response->image_limit))
          $maxgalleria_media_library_pro_s3->license_limit = $response->image_limit;


        $maxgalleria_media_library_pro_s3->update_mlfp_option('mlfp-license-limit', $maxgalleria_media_library_pro_s3->license_limit, true);

        // $license_data->license will be either "valid" or "invalid"
        //error_log("updating license status 827" . $license_data->license );
        $maxgalleria_media_library_pro_s3->update_mlfp_option( 'mg_edd_s3_license_status', $license_data->license );
        //error_log(print_r($license_data, true));

        $maxgalleria_media_library_pro_s3->license_status = $license_data->license;
        $maxgalleria_media_library_pro_s3->update_mlfp_option('mlfp-license-status', $maxgalleria_media_library_pro_s3->license_status, true);

      }
    }

	}
	
	public function edd_mlpp_deactivate_license() {
    
		// listen for our activate button to be clicked
		if( isset($_POST['edd_mlpp_license_deactivate']) || isset($_POST['edd_mlpp_license_deactivate2'])) {
      
      if(isset($_POST['edd_mlpp_license_deactivate2']))
        update_option(MAXG_NEW_LICENSE, 'on');

			// run a quick security check
			if( ! check_admin_referer( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'mg_edd_mlpp_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'deactivate_license',
				'license' 	=> $license,
				'item_name' => urlencode( EDD_MLPP_NAME ), // the name of our product in EDD
				'item_id'   => EDD_MLPP_ID,
				'url'       => site_url()
			);

			// Call the custom API.
			$response = wp_remote_post( MLPP_EDD_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
						
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			//$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			//$this->write_log(print_r($response, true));			

			// $license_data->license will be either "deactivated" or "failed"
			
			//if( $license_data->license == 'deactivated' )
			//if($response->code == 200 && $response['message'] = 'OK')
			if($response['response']['code'] == 200 && $response['response']['message'] == 'OK')
				delete_option( 'mg_edd_mlpp_license_status' );
		}
    
		if(class_exists('MGMediaLibraryFoldersProS3')) {			
			global $maxgalleria_media_library_pro_s3;
      
		// listen for our activate button to be clicked
    if( isset($_POST['edd_s3_license_deactivate']) || isset($_POST['edd_s3_license_deactivate2']) ) {

        if(isset($_POST['edd_s3_license_deactivate2']))
          update_option(MG_S3_ADDON_NEW_LICENSE, 'on');

        // run a quick security check
        if( ! check_admin_referer( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ) )
          return; // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim( $maxgalleria_media_library_pro_s3->get_mlfp_option( 'mg_edd_s3_license_key' ) );

        // data to send in our API request
        $api_params = array(
          'edd_action'=> 'deactivate_license',
          'license' 	=> $license,
          'item_name' => urlencode( EDD_S3_NAME ), // the name of our product in EDD 
          'item_id'   => EDD_S3_ID,
          'url'       => site_url()
        );

        // Call the custom API.
        $response = wp_remote_post( S3_EDD_SHOP_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

        // make sure the response came back okay
        if ( is_wp_error( $response ) )
          return false;

        // $license_data->license will be either "deactivated" or "failed"			
        if($response['response']['code'] == 200 && $response['response']['message'] == 'OK') {
          //$maxgalleria_media_library_pro_s3->license_status = S3_DEACTIVATED;
          $maxgalleria_media_library_pro_s3->license_status = S3_NO_LICENSE;
          $maxgalleria_media_library_pro_s3->license_limit = 500;
          //$maxgalleria_media_library_pro_s3->update_mlfp_option('mg_edd_s3_license_status', S3_DEACTIVATED, true);        
          $maxgalleria_media_library_pro_s3->update_mlfp_option('mg_edd_s3_license_status', S3_NO_LICENSE, true);        
          $maxgalleria_media_library_pro_s3->update_mlfp_option('mlfp-license-limit', 500, true);
        }  
      }      
    }
	}
	
	public function edd_mlpp_register_option() {
		// creates our settings in the options table
		register_setting('edd_mlpp_license', 'mg_edd_mlpp_license_key', array($this, 'edd_sanitize_mlpp_license' ));
	}
	
	public function edd_sanitize_mlpp_license( $new ) {
    $new = sanitize_text_field($new);
		$old = get_option( 'mg_edd_mlpp_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'mg_edd_mlpp_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}	
			
	public function mlp_button_admin_footer() {
    if($this->license_valid && $this->disable_media_ft != 'on') {    
      require_once 'includes/mlp-media-button.php';
    }  
	}

  public function delete_folder_attachment ($postid) {    
    global $wpdb;
    
    if(!empty($postid)) {
    
      $sql = "select post_title, post_type, pm.meta_value as attached_file 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where ID = $postid
AND pm.meta_key = '_wp_attached_file'";

      $row = $wpdb->get_row($sql);

      if($row) {

        if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
          $file_url = $this->build_location_url($row->attached_file);
          $location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);

          //if(defined('MLFP_CLOUD_SYNC')) {
          //  if($this->s3_addon->sync_cloud_changes == 'on') {    
          //    $basename = wp_basename($row->attached_file);
          //    $request_filename = $this->s3_addon->get_base_file($basename);
          //    $this->s3_addon->insert_task_log("delete", $request_filename);    
          //  }
          //}

          $this->s3_addon->remove_from_s3($row->post_type, $location);

          $metadata = wp_get_attachment_metadata($postid);

          if(isset($metadata['sizes'])) {
            foreach($metadata['sizes'] as $thumbnail) {
              $thumbnail_location = $this->s3_addon->get_thumbnail_location($thumbnail['file'], $location);
              $this->s3_addon->remove_from_s3($row->post_type, $thumbnail_location);
            }
          }
        }
      }

      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
      $where = array( 'post_id' => $postid );
      $wpdb->delete( $table, $where );    
    }
  }
    
  public function delete_folder_attachment1 ($postid) {    
    global $wpdb;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $where = array( 'post_id' => $postid );
    $wpdb->delete( $table, $where );    
  }
  
  public function add_attachment_to_folder($meta_id, $attachment_id, $meta_key = '', $metadata = '') {
    
    if ($meta_key != '_wp_attachment_metadata') {
      return false;
    }    
    
    
    $folder_id = $this->get_default_folder($attachment_id); //for non pro version
    //error_log("folder_id $folder_id");
    //$folder_id = get_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
    
    if($folder_id !== false) {
      $this->add_new_folder_parent($attachment_id, $folder_id);
      
      // no adding after file count exceded
      if(class_exists('MGMediaLibraryFoldersProS3') && 
        ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
        if($this->s3_addon->s3_active) {        
          $attached_file = get_post_meta( $attachment_id, '_wp_attached_file', true );        
          //$file = get_attached_file( $attachment_id );
          $file_url = $this->build_location_url($attached_file);

          $location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
          $destination_location = $this->s3_addon->get_destination_location($location);
          $destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);
          $filename = $this->get_absolute_path($file_url);

          do_action(MLFP_BEFORE_UPLOAD_TO_CLOUD, $location, $filename, $attachment_id);   

          $upload_result = $this->s3_addon->upload_to_s3("attachment", $location, $filename, $attachment_id);

          if(isset($metadata['sizes'])) {
            foreach($metadata['sizes'] as $thumbnail) {
              $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
              $this->s3_addon->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
            }
            do_action(MLFP_AFTER_UPLOAD_TO_CLOUD, $location, $filename, $attachment_id, $metadata['sizes']);                                

            if($this->s3_addon->remove_from_local) {
              if($upload_result['statusCode'] == '200')							
                $this->remove_media_file($filename);										

              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $thumbnail) {
                  $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                  $this->s3_addon->remove_media_file($source_file);										
                }
              }
            }              
          }                        
        }          
      }
      
//      if($this->wpmf_integration == 'on') {
//        
//        $wpmf_folder_id = $this->mlfp_export->get_new_folder_id($folder_id);
//
//        $folder = array($wpmf_folder_id);
//
//        error_log("attachment_id $attachment_id, wpmf_folder_id $wpmf_folder_id");
//        
//        wp_set_post_terms($attachment_id, $folder, WPMF_TAXO );      
//        
//      }      
    }
  }  
  
  public function add_new_laguage_attachment_to_folder($post_id, $tr_id, $slug) {
    
    //error_log("new attachemnt ID". $tr_id);
    
    $metadata = wp_get_attachment_metadata($tr_id);  
    
    return $this->add_attachment_to_folder2($metadata, $tr_id);
        
  }
  
  public function add_attachment_to_folder2( $metadata, $attachment_id ) {
  
    $folder_id = $this->get_default_folder($attachment_id);
    //error_log("add_attachment_to_folder2 folder_id $folder_id");
    if($folder_id !== false) {
      $this->add_new_folder_parent($attachment_id, $folder_id);
      
      if(class_exists('MGMediaLibraryFoldersProS3') && 
        ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
        if($this->s3_addon->s3_active) {        
          $attached_file = get_post_meta( $attachment_id, '_wp_attached_file', true );        
          //$file = get_attached_file( $attachment_id );
          $file_url = $this->build_location_url($attached_file);

          $location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
          $destination_location = $this->s3_addon->get_destination_location($location);
          $destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);
          $filename = $this->get_absolute_path($file_url);

          do_action(MLFP_BEFORE_UPLOAD_TO_CLOUD, $location, $filename, $attachment_id);   

          $upload_result = $this->s3_addon->upload_to_s3("attachment", $location, $filename, $attachment_id);

          if(isset($metadata['sizes'])) {
            foreach($metadata['sizes'] as $thumbnail) {
              $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
              $this->s3_addon->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
            }
            do_action(MLFP_AFTER_UPLOAD_TO_CLOUD, $location, $filename, $attachment_id, $metadata['sizes']);                                

            if($this->s3_addon->remove_from_local) {
              if($upload_result['statusCode'] == '200')							
                $this->remove_media_file($filename);										

              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $thumbnail) {
                  $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                  $this->s3_addon->remove_media_file($source_file);										
                }
              }
            }              
          }                        
        }
      }
      
//      if($this->wpmf_integration == 'on') {
//        
//        $wpmf_folder_id = $this->mlfp_export->get_new_folder_id($folder_id);
//
//        $folder = array($wpmf_folder_id);
//        
//        error_log("attachment_id $attachment_id, wpmf_folder_id $wpmf_folder_id");
//
//        wp_set_post_terms($attachment_id, $folder, WPMF_TAXO );      
//        
//      }      
      
    }
    return $metadata;
  }
        
  // used on an image path returns the image ID
  public function get_parent_by_name($sub_folder) {
    
    global $wpdb;
    
    $sql = "SELECT post_id FROM {$wpdb->prefix}postmeta where meta_key = '_wp_attached_file' and `meta_value` = '$sub_folder'";
    
    return $wpdb->get_var($sql);
  }
    
  public function get_default_folder($post_id) {
    
    $attached_file = get_post_meta($post_id, '_wp_attached_file', true);
    
    $folder_path = dirname($attached_file);
    $upload_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID);
    //$basepath = $this->upload_dir['baseurl'];
    
    if($folder_path == '.') {
      $folder_id = $upload_folder_id;
      //$folder_path =  $basepath;
    } else {
      $folder_id = $this->get_parent_by_name($folder_path);      
    }
    return $folder_id;
  }

  public function register_mgmlp_post_type() {
    
		$args = apply_filters(MGMLP_FILTER_POST_TYPE_ARGS, array(
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false,
      'show_in_nav_menus' => false,
      'show_in_admin_bar' => false,
			'show_in_menu' => false,
			'query_var' => true,
			'hierarchical' => true,
			'supports' => false,
			'exclude_from_search' => true
		));
		
		register_post_type(MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE, $args);
    
  }
  
  public function add_folder_table () {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( 
  `post_id` bigint(20) NOT NULL,
  `folder_id` bigint(20) NOT NULL,
  `term_id` bigint(20),
  PRIMARY KEY (`post_id`)
) DEFAULT CHARSET=utf8;";	
 
    dbDelta($sql);
    
  }
  
  public function alter_folder_table () {
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        
    $sql = "select count(*) from information_schema.columns
 where table_schema = '" . DB_NAME . "' and table_name = '$table'";
    
    //error_log($sql);
        
    $fields = $wpdb->get_var($sql);
    
    if($fields > 1 && $fields < 3) {
          
      $sql = "alter table $table add column term_id bigint(20)";

      $wpdb->query($sql);
    }
  
  }
    
  public function add_userrole_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( 
  `user_role` varchar(50) NOT NULL,
  `folders` longtext NOT NULL,
  `parents` longtext,  
  `permissions` bigint(20),
   PRIMARY KEY (`user_role`)
) DEFAULT CHARSET=utf8;";
     
    dbDelta($sql);
    
  }
  
  public function add_thumbnail_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` ( 
  `thumbnail_id` tinyint NOT NULL AUTO_INCREMENT,
  `size` varchar(80) NOT NULL,
  `width` int NOT NULL,
  `height` int NOT NULL,
  `generate` tinyint NOT NULL,  
   PRIMARY KEY (`thumbnail_id`)
) DEFAULT CHARSET=utf8;";
    
    dbDelta($sql);
    
  }
  
  public function add_purge_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` ( 
  `rec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `folder_id` bigint(20) NOT NULL,
  `basefile` varchar(100) NOT NULL,
  `folder_path` text NOT NULL,
  `action` tinyint(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`rec_id`)
) DEFAULT CHARSET=utf8;";
    
    dbDelta($sql);
    
  }
  
  
  public function frontend_upload_attachment () {
    global $is_IIS;
    
    //error_log("frontend_upload_attachment");
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    //error_log('nonce ok');
    
    $uploads_path = wp_upload_dir();
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = 0;
    
    if ((isset($_POST['title_text'])) && (strlen(trim($_POST['title_text'])) > 0))
      $seo_title_text = trim(stripslashes(strip_tags($_POST['title_text'])));
    else
      $seo_title_text = "";
		
    if ((isset($_POST['alt_text'])) && (strlen(trim($_POST['alt_text'])) > 0))
      $alt_text = trim(stripslashes(strip_tags($_POST['alt_text'])));
    else
      $alt_text = "";
                
    if ((isset($_POST['display_files'])) && (strlen(trim($_POST['display_files'])) > 0)) {
      $display_files = trim(stripslashes(strip_tags($_POST['display_files'])));
      //error_log("display_files 1 $display_files");
      if($display_files == '0')
        $display_files = false;      
      else
        $display_files = true;      
    } else
      $display_files = true;
    
    //error_log("display_files 2 $display_files");
    	
    $destination = $this->get_folder_path($folder_id);
    
    //error_log("destination $destination");
				    
    if(isset($_FILES['file'])){
      if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
      } else {

        if(!defined('ALLOW_UNFILTERED_UPLOADS')) {  
          $wp_filetype = wp_check_filetype_and_ext($_FILES['file']['tmp_name'], $_FILES['file']['name'] );

          if ($wp_filetype['ext'] === false) {
            echo '<script>' , PHP_EOL;
            echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
            echo $_FILES['file']['name'] . __(' file\'s type is invalid.', 'maxgalleria-media-library');
            echo '</span>");';
            echo '</script>' , PHP_EOL;
            exit;
          }
        }

        // insure it has a unique name
        $title_text = $_FILES['file']['name'];    
        $new_filename = wp_unique_filename( $destination, $_FILES['file']['name'], null );

        $folder_id = apply_filters(MLFP_FILTER_UPLOAD_DESTINATION_FOLDER, $folder_id, $new_filename );

        do_action(MLFP_BEFORE_ADD_FILE, $new_filename, $folder_id);              

        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
          $destination = rtrim($destination, '\\');

        $filename = $destination . DIRECTORY_SEPARATOR . $new_filename;
        
        if(file_exists($destination)) {
          //error_log("file exists");
          
          if( move_uploaded_file($_FILES['file']['tmp_name'], $filename) ) {

            // Set correct file permissions.
            $stat = stat( dirname( $filename ));
            $perms = $stat['mode'] & 0000664;
            @ chmod( $filename, $perms );

            do_action(MLFP_PROCESS_NEW_FILE, $filename, $folder_id);        

            $attach_id = $this->add_new_attachment($filename, $folder_id, $title_text, $alt_text, $seo_title_text);

            // no adding after limit exceded
            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {

              $file_url = $this->get_file_url($filename);
              $upload_folder_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
              $upload_length = strlen($upload_folder_name);
              $post_type = 'attachment';

              if($this->s3_addon->s3_active) 
                $this->s3_addon->upload_attachment_files_to_s3($post_type, $file_url, $filename, $attach_id);
              
            } 

            do_action(MLFP_AFTER_ADD_FILE, $attach_id, $filename, $folder_id );        

            // if called by the shortcode, don't display the files and return the file URL
            if($display_files)
              $this->display_folder_contents ($folder_id);
            else {
              if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3 && ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
                $file_url = $this->s3_addon->get_attachment_s3_url($attach_id);
              } else {                
                $file_url = $this->get_file_url($filename);
              }
              $mine_type = mime_content_type($filename);  
              if(strpos($mine_type,'image') !== false)
                $image = true;
              else
                $image = false;
              
              //if(strlen($notify) > 0) {                
              //  $notify = str_replace(' ', '', $notify);
              //  $addresses = explode(',', $notify);
              //  foreach($addresses as $address) {
              //    $this->mlfp_send_notification($address, $file_url);
              //  }                
              //}
              
              $data = array ('file_url' => $file_url, 'mine_type' => $mine_type, 'image' => $image);
              echo json_encode($data);
              
            }

          }
        } else {
          
          //error_log("file does not exists");

          echo '<script>' , PHP_EOL;
          echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
          echo __(' Unable to move the file to the destination folder; the folder may not exist.', 'maxgalleria-media-library');
          echo '</span>");';
          echo '</script>' , PHP_EOL;

        }                
      }
    }    
    die();
  }
  
  public function mlfp_send_notification($address, $filename) {
    
    global $current_user;
        
    $site_name = get_bloginfo('name');
    
    $current_user = wp_get_current_user();
        
    $subject = __('New file submitted at ','maxgalleria-media-library') . $site_name;
    
    $sender = get_bloginfo('admin_email');
        
    $message = __('A new file has been uploaded at ','maxgalleria-media-library') . $site_name . __(' by ','maxgalleria-media-library') . $current_user->display_name . "\r\n\r\n";
    
    $message .= $filename .  "\r\n\r\n";
      
    $headers = array();
    $headers[] = 'Content-type: text/html;charset=utf-8' . "\r\n";
    $headers[] = 'From: '.$site_name.' <'.$sender.'>';
    $headers[] = 'Reply-To: Admin <'.$sender.'>';
    //error_log("new_user_email $address, subject $subject, message $message");
    //if(!wp _mail($address, $subject, $message, $headers)) 
    //  error_log("not email sent");
    //wp _mail($address, $subject, $message, $headers);
        
  }
        
  public function upload_attachment () {
    global $is_IIS;
                  
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $uploads_path = wp_upload_dir();
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = 0;
    
    if ((isset($_POST['title_text'])) && (strlen(trim($_POST['title_text'])) > 0))
      $seo_title_text = trim(stripslashes(strip_tags($_POST['title_text'])));
    else
      $seo_title_text = "";
		
    if ((isset($_POST['alt_text'])) && (strlen(trim($_POST['alt_text'])) > 0))
      $alt_text = trim(stripslashes(strip_tags($_POST['alt_text'])));
    else
      $alt_text = "";
		
    $destination = $this->get_folder_path($folder_id);
				    
    if(isset($_FILES['file'])){
      if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
      } else {

        if(!defined('ALLOW_UNFILTERED_UPLOADS')) {  
          $wp_filetype = wp_check_filetype_and_ext($_FILES['file']['tmp_name'], $_FILES['file']['name'] );

          if ($wp_filetype['ext'] === false) {
            echo '<script>' , PHP_EOL;
            echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
            echo $_FILES['file']['name'] . __(' file\'s type is invalid.', 'maxgalleria-media-library');
            echo '</span>");';
            echo '</script>' , PHP_EOL;
            exit;
          }
        }

        // insure it has a unique name
        $title_text = $_FILES['file']['name'];    
        $new_filename = wp_unique_filename( $destination, $_FILES['file']['name'], null );

        $folder_id = apply_filters(MLFP_FILTER_UPLOAD_DESTINATION_FOLDER, $folder_id, $new_filename );

        do_action(MLFP_BEFORE_ADD_FILE, $new_filename, $folder_id);              

        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
          $destination = rtrim($destination, '\\');

        $filename = $destination . DIRECTORY_SEPARATOR . $new_filename;
        
        if(file_exists($destination)) {
          
          if( move_uploaded_file($_FILES['file']['tmp_name'], $filename) ) {

            // Set correct file permissions.
            $stat = stat( dirname( $filename ));
            $perms = $stat['mode'] & 0000664;
            @ chmod( $filename, $perms );

            do_action(MLFP_PROCESS_NEW_FILE, $filename, $folder_id);        

            $attach_id = $this->add_new_attachment($filename, $folder_id, $title_text, $alt_text, $seo_title_text);

            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {

              $file_url = $this->get_file_url($filename);
              $upload_folder_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
              $upload_length = strlen($upload_folder_name);
              $post_type = 'attachment';

              if($this->s3_addon->s3_active) 
                $this->s3_addon->upload_attachment_files_to_s3($post_type, $file_url, $filename, $attach_id);

            } 

            do_action(MLFP_AFTER_ADD_FILE, $attach_id, $filename, $folder_id );        

            $this->display_folder_contents ($folder_id);

          }
        } else {

           echo '<script>' , PHP_EOL;
           echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
           echo __(' Unable to move the file to the destination folder; the folder may not exist.', 'maxgalleria-media-library');
           echo '</span>");';
           echo '</script>' , PHP_EOL;

        }                
      }
    }    
    die();
  }
      
  public function mlfp_replace_attachment () {
    global $wpdb, $is_IIS;
		$default_title = "";
    $default_alt = "";    
    $exif_data = array();
    $ImageDescription = "";
        
    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                      
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $uploads_path = wp_upload_dir();
    $caption_import = get_option(MLFP_PREVENT_CAPTION_IMPORT, 'off');

    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = 0;
    
    if ((isset($_POST['replace_file_id'])) && (strlen(trim($_POST['replace_file_id'])) > 0))
      $replace_file_id = trim(stripslashes(strip_tags($_POST['replace_file_id'])));
    else
      $replace_file_id = 0;
       
    if ((isset($_POST['replace_type'])) && (strlen(trim($_POST['replace_type'])) > 0))
      $replace_type = trim(stripslashes(strip_tags($_POST['replace_type'])));
    else
      $replace_type = '';
    
    if ((isset($_POST['date_options'])) && (strlen(trim($_POST['date_options'])) > 0))
      $date_options = trim(stripslashes(strip_tags($_POST['date_options'])));
    else
      $date_options = '';
    
    if ((isset($_POST['custom_date'])) && (strlen(trim($_POST['custom_date'])) > 0))
      $custom_date = trim(stripslashes(strip_tags($_POST['custom_date'])));
    else
      $custom_date = '';
            
    if ((isset($_POST['replace_mine_type'])) && (strlen(trim($_POST['replace_mine_type'])) > 0))
      $replace_mine_type = trim(stripslashes(strip_tags($_POST['replace_mine_type'])));
    else
      $replace_mine_type = '';
          
    if ((isset($_POST['title_text'])) && (strlen(trim($_POST['title_text'])) > 0))
      $seo_title_text = trim(stripslashes(strip_tags($_POST['title_text'])));
    else
      $seo_title_text = "";
		
    if ((isset($_POST['alt_text'])) && (strlen(trim($_POST['alt_text'])) > 0))
      $alt_text = trim(stripslashes(strip_tags($_POST['alt_text'])));
    else
      $alt_text = "";
    
    //error_log("replace_file_id $replace_file_id");
    //error_log("replace_type $replace_type");
    //error_log("replace_mine_type $replace_mine_type");
    //error_log("seo_title_text $seo_title_text");
    //error_log("alt_text $alt_text");
    
    $destination = $this->get_folder_path($folder_id);
    
    if($replace_file_id != 0) {
      
      $replace_file_path = get_attached_file($replace_file_id);
      //error_log("replace_file_path $replace_file_path");
      $old_file_url = $this->get_file_url($replace_file_path);
      
      if($replace_type == 'replace-update') {
        //error_log("replace-update 1");
        if(file_exists($replace_file_path))
          unlink($replace_file_path);
      }
            
      if(isset($_FILES['file'])) {
        if ( 0 < $_FILES['file']['error'] ) {
          echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        } else {

          if(!defined('ALLOW_UNFILTERED_UPLOADS')) {  
            $wp_filetype = wp_check_filetype_and_ext($_FILES['file']['tmp_name'], $_FILES['file']['name'] );

            if ($wp_filetype['ext'] === false) {
              echo '<script>' , PHP_EOL;
              echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
              echo $_FILES['file']['name'] . __(' file\'s type is invalid.', 'maxgalleria-media-library');
              echo '</span>");';
              echo '</script>' , PHP_EOL;
              exit;
            }
          }
                    
          if($replace_type == 'replace-update') {
            //error_log("replace-update 2");
            
            //insure it has a unique name
            $title_text = $_FILES['file']['name'];    
            $new_filename = wp_unique_filename( $destination, $_FILES['file']['name'], null );

            $folder_id = apply_filters(MLFP_FILTER_UPLOAD_DESTINATION_FOLDER, $folder_id, $new_filename );

            do_action(MLFP_BEFORE_ADD_FILE, $new_filename, $folder_id);              

            if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
              $destination = rtrim($destination, '\\');

            $filename = $destination . DIRECTORY_SEPARATOR . $new_filename;
            
            $replace_file_path = $filename;
            //error_log("filename $filename");
            //error_log("replace_file_path 1 $replace_file_path");
                                                
          }

          if(file_exists($destination)) {

            if( move_uploaded_file($_FILES['file']['tmp_name'], $replace_file_path) ) {

              // Set correct file permissions.
              $stat = stat( dirname( $replace_file_path ));
              $perms = $stat['mode'] & 0000664;
              @ chmod( $replace_file_path, $perms );

              do_action(MLFP_PROCESS_NEW_FILE, $replace_file_path, $folder_id);        
              
              $new_file_url = $this->get_file_url($replace_file_path);              
              
              // delete thumbnails
              $metadata = wp_get_attachment_metadata($replace_file_id);                               
              //$path_to_thumbnails = pathinfo($old_file_path, PATHINFO_DIRNAME);

              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $source_path) {
                  $thumbnail_file = $destination . DIRECTORY_SEPARATOR . $source_path['file'];

                  if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                    $thumbnail_file = str_replace('/', '\\', $thumbnail_file);

                  if(file_exists($thumbnail_file))
                    unlink($thumbnail_file);
                }  
              }
              
//              // generate new thumbnails
//              do_action(MLFP_BEFORE_THUMBNAIL_REGEN, $replace_file_id, $replace_file_path);
//
//              // set the time limit to five minutes
//              @set_time_limit( 300 ); 
//
//              // regenerate the thumbnails
//              if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
//                $metadata = wp_generate_attachment_metadata( $replace_file_id, addslashes($replace_file_path));
//              else
//                $metadata = wp_generate_attachment_metadata( $replace_file_id, $replace_file_path );
              
              // update the attacment record
              $attachment_record = array('ID' => $replace_file_id);
              $attachment_record['post_author'] = get_current_user_id();
              
              // update the date
              if($date_options != 'mlfp-keep-date') {
                if(($date_options == 'mlfp-custom-date') && ($custom_date != '')) {
                  $new_date = date($custom_date);
                  $gmt_date = gmdate($custom_date);
                } else {
                  $new_date = date("Y-m-d H:i");
                  $gmt_date = gmdate("Y-m-d H:i");
                }  
                
                $attachment_record['post_date'] = $new_date;
                $attachment_record['post_date_gmt'] = $gmt_date;
                                               
              }
              
              //error_log("new_file_url $new_file_url, replace_type $replace_type");
              
              if($replace_type == 'replace-update') {
                
                $image_seo = get_option(MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO, 'off');
                
                if(isset($filetype['type'])) {
                  if($filetype['type'] == 'image/jpeg') {
                    if(extension_loaded("exif")) {
                      $exif_data = exif_read_data($replace_file_path);
                      
                      //error_log(print_r($exif_data,true));
                      
                      if(isset($exif_data['FileName'])) {
                        $title_text = $exif_data['FileName']; 
                      }  
                      if(isset($exif_data['ImageDescription'])) {
                        $ImageDescription = $exif_data['ImageDescription'];
                      }                        
                    }
                  }
                }
                                
            		// remove the extention from the file name
            		$position = strpos($title_text, '.');
            		if($position)
            			$title_text	= substr ($title_text, 0, $position);
                
                //error_log("title_text $title_text");

                if($image_seo === 'on') {

                  $folder_name = $this->get_folder_name($folder_id);

                  $file_name = $this->remove_extension(basename($replace_file_path));
                  
                  $file_name = str_replace('-', ' ', $file_name);

                  $new_file_title = $seo_title_text;

                  $new_file_title = str_replace('%foldername', $folder_name, $new_file_title );			

                  $new_file_title = str_replace('%filename', $file_name, $new_file_title );			

                  $default_alt = $alt_text;

                  $default_alt = str_replace('%foldername', $folder_name, $default_alt );			

                  $default_alt = str_replace('%filename', $file_name, $default_alt );			

                  if($caption_import == 'off') 
                    $ImageDescription = apply_filters(MGMLP_FILTER_SET_POST_EXCERPT, $ImageDescription, $folder_name, $file_name );
                  else
                    $ImageDescription = '';

                } else {
                  //$new_file_title	= preg_replace( '/\.[^.]+$/', '', basename( $filename ) );
                  $new_file_title	= $title_text;
                  $ImageDescription = "";
                }
                
                $attachment_record['guid'] = $new_file_url;
                $attachment_record['post_mime_type'] = $replace_mine_type;
                $attachment_record['post_title'] = $new_file_title;
                $attachment_record['post_name'] = $title_text;
                $attachment_record['post_excerpt'] = $ImageDescription;                                
                
                // meta and update file links
                //$table = $wpdb->prefix . "postmeta";
                //$where = array('post_id' => $replace_file_id);
                //$wpdb->delete($table, $where);

                // get the uploads dir name
                $basedir = $this->upload_dir['baseurl'];
                $uploads_dir_name_pos = strrpos($basedir, '/');
                $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);

                //find the name and cut off the part with the uploads path
                $string_position = strpos($new_file_url, $uploads_dir_name);
                $uploads_dir_length = strlen($uploads_dir_name) + 1;
                $uploads_location = substr($new_file_url, $string_position+$uploads_dir_length);
                if($this->is_windows()) 
                  $uploads_location = str_replace('\\','/', $uploads_location);      

                $uploads_location = ltrim($uploads_location, '/');
                update_post_meta( $replace_file_id, '_wp_attached_file', $uploads_location );
                //error_log("uploads_location $uploads_location");
                                
                if($image_seo == 'on') { 
                  if(!is_array($default_alt))
                    update_post_meta($replace_file_id, '_wp_attachment_image_alt', $default_alt);
                  else  
                    update_post_meta($replace_file_id, '_wp_attachment_image_alt', $default_alt[0]);
                }  
                                
                // generate new thumbnails
                do_action(MLFP_BEFORE_THUMBNAIL_REGEN, $replace_file_id, $replace_file_path);

                // set the time limit to five minutes
                @set_time_limit( 300 ); 

                // regenerate the thumbnails
                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                  $attach_data = wp_generate_attachment_metadata( $replace_file_id, addslashes($replace_file_path));
                else
                  $attach_data = wp_generate_attachment_metadata( $replace_file_id, $replace_file_path );

                //error_log("adding metadata");
                
                wp_update_attachment_metadata( $replace_file_id, $attach_data );
                
                //$rename_image_location = $old_file_url;
                //$rename_destination = $new_file_url;
                
                $rename_image_location = $this->get_base_file($old_file_url);
                $scaled_pos = strpos($rename_image_location, '-scaled');
                if($scaled_pos !== false) {
                  $rename_image_location = substr($rename_image_location, 0, $scaled_pos);
                }
				        $rename_destination = $this->get_base_file($new_file_url);			
                
                //error_log("updating embedded links $rename_image_location, $rename_destination");

                if(class_exists( 'SiteOrigin_Panels')) {                  
                  $this->update_serial_postmeta_records($rename_image_location, $rename_destination);                  
                }

                // update postmeta records for beaver builder
                if(class_exists( 'FLBuilderLoader')) {

                  $sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%$rename_image_location%'";
                  //error_log($sql);

                  $records = $wpdb->get_results($sql);
                  foreach($records as $record) {

                    $this->update_bb_postmeta($record->ID, $rename_image_location, $rename_destination);

                  }
                  // clearing BB caches
                  if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_asset_cache_for_all_posts' ) ) {
                    FLBuilderModel::delete_asset_cache_for_all_posts();
                  }

                  if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_all_asset_cache' ) ) {
                    FLBuilderModel::delete_all_asset_cache( $record->ID );
                  }  

                  if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
                    FLCustomizer::clear_all_css_cache();
                  }
                  wp_cache_flush();

                }

                //$replace_sql = "UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE (`post_content`, '$rename_image_location', '$rename_destination');";          
                //$result = $wpdb->query($replace_sql);

                //$replace_sql = str_replace ( '/', '\\/', $replace_sql);
                //$result = $wpdb->query($replace_sql);
                
                $this->update_links($rename_image_location, $rename_destination);                

                // for updating wp pagebuilder
                if(defined('WPPB_LICENSE')) {
                  $this->update_wppb_data($image_location_no_extension, $new_file_url);          
                }

                // for updating themify images
                if(function_exists('themify_builder_activate')) {
                  $this->update_themify_data($image_location_no_extension, $new_file_url);
                }

                // for updating elementor background images
                if(is_plugin_active("elementor/elementor.php")) {
                  $this->update_elementor_data($replace_file_id, $image_location_no_extension, $new_file_url);          
                }
                                            
              } else {
                //error_log("updating meta data: file replace");
                
                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                  $attach_data = wp_generate_attachment_metadata( $replace_file_id, addslashes($replace_file_path));
                else
                  $attach_data = wp_generate_attachment_metadata( $replace_file_id, $replace_file_path );                
                
                wp_update_attachment_metadata( $replace_file_id, $attach_data );
              }
                            
              wp_update_post($attachment_record, true);
              
              
              if(class_exists('MGMediaLibraryFoldersProS3') && 
                ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {

                $file_url = $this->get_file_url($replace_file_path);
                $upload_folder_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
                $upload_length = strlen($upload_folder_name);
                $post_type = 'attachment';

                if($this->s3_addon->s3_active) 
                  $this->s3_addon->upload_attachment_files_to_s3($post_type, $file_url, $filename, $attach_id);

              } 

              do_action(MLFP_AFTER_ADD_FILE, $replace_file_id, $replace_file_path, $folder_id );        

              $this->display_folder_contents ($folder_id);

            }
          } else {

             echo '<script>' , PHP_EOL;
             echo '  jQuery("#folder-message").html("<span class=\"mlp-warning\">';
             echo __(' Unable to move the file to the destination folder; the folder may not exist.', 'maxgalleria-media-library');
             echo '</span>");';
             echo '</script>' , PHP_EOL;

          }                
        }
      }                
    }
				    
    die();
  }
        
  public function update_links($rename_image_location, $rename_destination) {
    
    global $wpdb;
    
    $table_list = apply_filters( MGMLP_FILTER_SET_UPDATE_TABLE_LINKS, '');
    $field_list = apply_filters( MGMLP_FILTER_SET_UPDATE_TABLE_FIELDS, '');
    
    $table_list = str_replace(' ', '', $table_list);
    $field_list = str_replace(' ', '', $field_list);
        
    if(!empty($table_list)) {
      $table_list = "$wpdb->posts," . $table_list;
      $tables = explode(',', $table_list);     
    } else {
      $tables = array("$wpdb->posts"); 
    }
    
    if(!empty($field_list)) {
      $field_list = "post_content," . $field_list;
      $fields = explode(',', $field_list);     
    } else {
      $fields = array("post_content"); 
    }
    
    if(is_array($table_list) || is_array($field_list)) {
      if(count($table_list) != count($field_list)) {
        error_log(__('An unequal number of items were sent to update_links function.','maxgalleria-media-library'));
        return;
      }  
    }
    
    $pairs = array_combine($tables, $fields);
    
    foreach($pairs as $key => $value) {    
      $replace_sql = "UPDATE $key SET `$value` = REPLACE (`$value`, '$rename_image_location', '$rename_destination');";          
      $result = $wpdb->query($replace_sql);
      //error_log("replace_sql $replace_sql");

      $replace_sql = str_replace ( '/', '\\/', $replace_sql);
      $result = $wpdb->query($replace_sql);
    }
    
  }
            
  public function add_new_attachment($filename, $folder_id, $title_text="", $alt_text="", $seo_title_text="", $download = true) {
    
    global $is_IIS;
    $parent_post_id = 0;
		$default_title = "";
    $default_alt = "";    
    $exif_data = array();
    $ImageDescription = "";
    $caption_import = get_option(MLFP_PREVENT_CAPTION_IMPORT, 'off');

    //error_log("filename $filename");
    
    //remove_action( 'added_post_meta', array($this,'add_attachment_to_folder'));
    remove_filter( 'wp_generate_attachment_metadata', array($this, 'add_attachment_to_folder2'));    

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );
    
    if(isset($filetype['type'])) {
      if($filetype['type'] == 'image/jpeg') {
        if(extension_loaded("exif")) {
          $exif_data = exif_read_data($filename);
        }
      }
    }
            
    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();
    
    $file_url = $this->get_file_url_for_copy($filename);
		
		$image_seo = get_option(MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO, 'off');
    
    if(isset($filetype['type']) && $filetype['type'] == 'image/jpeg') {
      if(isset($exif_data['FileName'])) {
        $title_text = $exif_data['FileName']; 
      }  
      if(isset($exif_data['ImageDescription'])) {
        $ImageDescription = $exif_data['ImageDescription'];
      }  
    }
            
		// remove the extention from the file name
		$position = strpos($title_text, '.');
		if($position)
			$title_text	= substr ($title_text, 0, $position);
		
		if($image_seo === 'on') {
			
			$folder_name = $this->get_folder_name($folder_id);
			
			$file_name = $this->remove_extension(basename($filename));
      
      // remove dashes from file name
      $file_name = str_replace('-', ' ', $file_name);
			
			$new_file_title = $seo_title_text;
			
			$new_file_title = str_replace('%foldername', $folder_name, $new_file_title );			
			
			$new_file_title = str_replace('%filename', $file_name, $new_file_title );			
									
		  //$default_alt = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);
			$default_alt = $alt_text;
			
			$default_alt = str_replace('%foldername', $folder_name, $default_alt );			
			
			$default_alt = str_replace('%filename', $file_name, $default_alt );			
						
      if($caption_import == 'off') 
			  $ImageDescription = apply_filters(MGMLP_FILTER_SET_POST_EXCERPT, $ImageDescription, $folder_name, $file_name );
      else
        $ImageDescription = '';
			
		} else {
      //$new_file_title	= preg_replace( '/\.[^.]+$/', '', basename( $filename ) );
			$new_file_title	= $title_text;
      $ImageDescription = "";
		}
    
    //error_log("adding new attachment $filename");
		
    // Prepare an array of post data for the attachment.
    $attachment = array(
      'guid'           => $file_url, 
      'post_mime_type' => $filetype['type'],
      'post_title'     => $new_file_title,
  		'post_parent'    => 0,
      'post_content'   => '',
      'post_status'    => 'inherit',
      'post_excerpt'  => $ImageDescription
    );
    
    // Insert the attachment.
    //$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );    
    if (! ($attach_id = get_file_attachment_id($filename))) {
      $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
    }

    if($image_seo == 'on') { 
      if(!is_array($default_alt))
        update_post_meta($attach_id, '_wp_attachment_image_alt', $default_alt);
      else  
        update_post_meta($attach_id, '_wp_attachment_image_alt', $default_alt[0]);
    }  
		
    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    if($download) {
      // Generate the metadata for the attachment, and update the database record.
      //if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      //  $attach_data = wp_generate_attachment_metadata( $attach_id, addslashes($filename));
      //else
      //  $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

      // Generate the metadata for the attachment (if it doesn't already exist), and update the database record.
 	    if (! wp_get_attachment_metadata($attach_id, TRUE)) {
		    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
			    $attach_data = wp_generate_attachment_metadata( $attach_id, addslashes($filename));
		    else
			    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        
		    wp_update_attachment_metadata( $attach_id, $attach_data );
        
        $update_post_info = array(
          'ID'=> $attach_id,
        );
        
		    if($image_seo !== 'on') {
          if(isset($attach_data['image_meta']['title']) && empty(!$attach_data['image_meta']['title']))
            $update_post_info['post_title'] = $attach_data['image_meta']['title'];
        }
         
        if($caption_import == 'off') {
          if(isset($attach_data['image_meta']['caption']) && empty(!$attach_data['image_meta']['caption']))
            $update_post_info['post_excerpt'] = $attach_data['image_meta']['caption'];
        }
        
        if(count($update_post_info) > 1)
          wp_update_post($update_post_info);                
                
      }

    }
    
    if($this->is_windows()) {
      
      // get the uploads dir name
      $basedir = $this->upload_dir['baseurl'];
      $uploads_dir_name_pos = strrpos($basedir, '/');
      $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);
    
      //find the name and cut off the part with the uploads path
      $string_position = strpos($filename, $uploads_dir_name);
      $uploads_dir_length = strlen($uploads_dir_name) + 1;
      $uploads_location = substr($filename, $string_position+$uploads_dir_length);
      $uploads_location = str_replace('\\','/', $uploads_location);   
			$uploads_location = ltrim($uploads_location, '/');
      
      // put the short path into postmeta
	    $media_file = get_post_meta( $attach_id, '_wp_attached_file', true );
    
      if($media_file !== $uploads_location )
        update_post_meta( $attach_id, '_wp_attached_file', $uploads_location );
    }

    $this->add_new_folder_parent($attach_id, $folder_id );
    //add_action( 'added_post_meta', array($this,'add_attachment_to_folder'), 10, 4);
    add_filter( 'wp_generate_attachment_metadata', array($this, 'add_attachment_to_folder2'), 10, 4);    
    
    return $attach_id;
    
  }
  
  public function remove_extension($file_name) {
    $position = strrpos($file_name, '.');
    if($position === false)
      return $file_name;
    else
      return substr($file_name, 0, $position);
  }
	  
  public function scan_attachments () {
    
    global $wpdb;
            
    $uploads_path = wp_upload_dir();
    
    if(!$uploads_path['error']) {
			
      //find the uploads folder
      $base_url = $uploads_path['baseurl'];
      $last_slash = strrpos($base_url, '/');
      $uploads_dir = substr($base_url, $last_slash+1);
			$this->uploads_folder_name = $uploads_dir;
			$this->uploads_folder_name_length = strlen($uploads_dir);
      
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, $uploads_dir);
                              
      //create uploads parent media folder      
      $uploads_parent_id = $this->add_media_folder($uploads_dir, 0, $base_url);
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID, $uploads_parent_id);
      
      $baseurl = $this->upload_dir['baseurl'];
      // use for comparisons 
      $uploads_base_url = rtrim($baseurl, '/');
      $baseurl = rtrim($baseurl, '/') . '/';      
      
      $sql = "SELECT ID, pm.meta_value as attached_file 
FROM {$wpdb->prefix}posts
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
WHERE post_type = 'attachment' 
AND pm.meta_key = '_wp_attached_file'
ORDER by ID";
			
      $rows = $wpdb->get_results($sql);
      
      $current_folder = "";
            
      $parent_id = $uploads_parent_id;
            
      if($rows) {
        foreach($rows as $row) {
					
				if( strpos($row->attached_file, "http:") !== false || 
						strpos($row->attached_file, "https:") !== false || 
						strpos($row->attached_file, "'") !== false)  {
				} else {
																									        										                    
						$image_location = $baseurl . ltrim($row->attached_file, '/');
            
            // check for and add files in the uploads or root media library folder
            $uploads_location = $this->strip_base_file($image_location);
            if($uploads_base_url == $uploads_location) {
              $this->add_new_folder_parent($row->ID, $uploads_parent_id);
              continue;
            }  
																          
            $sub_folders = $this->get_folders($image_location);
            $attachment_file = array_pop($sub_folders);  

            $uploads_length = strlen($uploads_dir);
            $new_folder_pos = strpos($image_location, $uploads_dir ); 
            $folder_path = substr($image_location, 0, $new_folder_pos+$uploads_length );

            foreach($sub_folders as $sub_folder) {
              
              // check for URL path in database
              $folder_path = $folder_path . '/' . $sub_folder;

              $new_parent_id = $this->folder_exist($folder_path);														
              if($new_parent_id === false) {
                if($this->is_new_top_level_folder($uploads_dir, $sub_folder, $folder_path)) {
                  $parent_id = $this->add_media_folder($sub_folder, $uploads_parent_id, $folder_path); 
                }  
                else {
                  $parent_id = $this->add_media_folder($sub_folder, $parent_id, $folder_path); 
                }  
              }  
              else
                $parent_id = $new_parent_id;
            }          

            $this->add_new_folder_parent($row->ID, $parent_id );
				  } // test for http
        } //foreach         
        
      } //rows  
			//if ( ! wp_next_scheduled( 'new_folder_check' ) )
			//	wp_schedule_event( time(), 'daily', 'new_folder_check' );
            
    }
		
//		echo "done";
//		die();
        
  }
  
  public function strip_base_file($url){
    $parts = explode("/", $url);
    if(count($parts) < 4) return $url . "/";
    if(strpos(end($parts), ".") !== false){ 
        array_pop($parts); 
    }else if(end($parts) !== ""){ 
      $parts[] = ""; 
    }
    
    return implode("/", $parts);
  }    
       
  private function is_new_top_level_folder($uploads_dir, $folder_name, $folder_path) {
    
    $needle = $uploads_dir . '/' . $folder_name;
    //if(strpos($folder_path, $needle))
    if(strpos($folder_path . '/' , $needle . '/'))        
      return true;
    else
      return false;   
  }

  public function get_folders($path) {
    
    if($path != '') {       
      $sub_folders = explode('/', $path);    
        while($sub_folders[0] != $this->uploads_folder_name ) 
          array_shift($sub_folders);

        if($sub_folders[0] == $this->uploads_folder_name) 
          array_shift($sub_folders);      
    }  
      
    return $sub_folders;
  }
  
  public function folder_exist($folder_path) {
    
    global $wpdb;    
		
		$relative_path = substr($folder_path, $this->base_url_length);
		$relative_path = ltrim($relative_path, '/');
    
		$sql = "SELECT ID FROM {$wpdb->prefix}posts
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = ID
WHERE pm.meta_value = '$relative_path' 
and pm.meta_key = '_wp_attached_file'";

    $row = $wpdb->get_row($sql);
    if($row === null) {
      return false;
    } else {
      return $row->ID;
    }         
  }
  
  public function add_media_folder($folder_name, $parent_folder, $base_path, $termID = null ) {
        
    global $wpdb;    
    $table = $wpdb->prefix . "posts";	    
    
    if($this->wpmf_integration == 'on' && $termID == null) {
      
      remove_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10);    
            
      $parent_term_id = $this->mlfp_get_term_id($parent_folder);

      //error_log("folder_id $parent_id, new_folder_id $new_folder_id");

      $inserted = wp_insert_term($folder_name, WPMF_TAXO, array('parent' => $parent_term_id));    

      if(!is_wp_error($inserted)) {
        
        $id_author = get_current_user_id();
              
        $updateted = wp_update_term($inserted['term_id'], WPMF_TAXO, array('term_group' => $id_author));

        $term_info = get_term($updateted['term_id'], WPMF_TAXO);

        do_action('wpmf_create_folder', $inserted['term_id'], $folder_name, $parent_term_id, array('trigger' => 'media_library_action'));                  
        
        $termID = $inserted['term_id'];
                  
      }
      
      add_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10, 4);        
      
    }
    		
    $new_folder_id = $this->mpmlp_insert_post(MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE, $folder_name, $base_path, 'publish' );

		$attachment_location = substr($base_path, $this->base_url_length);
		$attachment_location = ltrim($attachment_location, '/');
				
		update_post_meta($new_folder_id, '_wp_attached_file', $attachment_location);
        		
    //error_log("adding $folder_name, termID $termID");
    $this->add_new_folder_parent($new_folder_id, $parent_folder, $termID);
    
    if($this->wpmf_integration == 'on') {
      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
      $data = array('term_id' => $termID);
      $where = array('post_id' => $new_folder_id);           
      $wpdb->update($table, $data, $where);                   
    }
        
    return $new_folder_id;
        
  }
  
  private function add_new_folder_parent($record_id, $parent_folder, $termID = null) {
    
    global $wpdb;    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;

    // check for existing record  
    $sql = "select post_id from $table where post_id = $record_id";
    
    //error_log($sql); 
    
    if($wpdb->get_var($sql) == NULL) {
      
      //error_log("inserting $record_id, $parent_folder, $termID");
    
      $new_record = array( 
			  'post_id'   => $record_id, 
			  'folder_id' => $parent_folder,
        'term_id'   => $termID
			);
      
      $wpdb->insert( $table, $new_record );
    }
    
    if($this->wpmf_integration == 'on' && $termID == null) {

      //$wpmf_folder_id = $this->mlfp_export->get_new_folder_id($parent_folder);
      $wpmf_folder_id = $this->mlfp_get_term_id($parent_folder);

      //error_log("folder_id $wpmf_folder_id, new_folder_id $new_folder_id");

      //$mlfp_parent = $this->get_mlfp_parent($parent_folder);
      $mlfp_parent = $this->get_mlfp_parent2($parent_folder);

      //error_log("mlfp_parent $mlfp_parent, mlfp_parent2 $mlfp_parent2");

      $folder = array($wpmf_folder_id);

      //error_log("wp_set_post_terms record_id $record_id, parent_folder $parent_folder, wpmf_folder_id $wpmf_folder_id. mlfp_parent $mlfp_parent");

      $results = wp_set_post_terms($record_id, $folder, WPMF_TAXO );      

      if(!is_wp_error($results) && isset($results[0])) {
        //error_log("term id " . $results[0] . ", $record_id");
        $data = array('term_id' => $results[0]);
        $where = array('post_id' => $record_id);           
        $wpdb->update($table, $data, $where);                             
      }

    }      
    
      
  }
      
	public function load_textdomain() {
		load_plugin_textdomain('maxgalleria-media-library', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
  
	public function ignore_notice() {
		if (current_user_can('install_plugins')) {
			global $current_user;
			
			if (isset($_GET['maxgalleria-media-library-ignore-notice']) && $_GET['maxgalleria-media-library-ignore-notice'] == 1) {
				add_user_meta($current_user->ID, MAXGALLERIA_MEDIA_LIBRARY_IGNORE_NOTICE, true, true);
			}
		}
	}
  
//	public function show_mlp_admin_notice() {
//    global $current_user;  
//    
//    if(isset($_REQUEST['page'])) {
//    
//      if($_REQUEST['page'] == 'media-library-folders' 
//          || $_REQUEST['page'] === 'mlp-support' 
//          || $_REQUEST['page'] === 'mlfp-settings' 
//          || $_REQUEST['page'] === 'image-seo' 
//          || $_REQUEST['page'] === 'mlp-regenerate-thumbnails' 
//          || $_REQUEST['page'] === 'mflp-search-library' ) {
//
//        $review = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_FEATURE_NOTICE, true );
//        
//        if( $review !== 'off') {
//          if($review === '') {
//            //show review notice after one days
//            $review_date = date('Y-m-d', strtotime("+1 days"));        
//            update_user_meta( $current_user->ID, MAXGALLERIA_MLP_FEATURE_NOTICE, $review_date );
//
//            //show notice if not found
//            //add_action( 'admin_notices', array($this, 'mlp_review_notice' ));            
//          } else {
//            $now = date("Y-m-d"); 
//            $review_time = strtotime($review);
//            $now_time = strtotime($now);
//            if($now_time > $review_time)
//              add_action( 'admin_notices', array($this, 'mlp_review_notice' ));
//          }
//        }
//      }
//    }
//	}
  
//	public function show_mlp_admin_notice3() {
//    global $current_user;  
//    
//    if(isset($_REQUEST['page'])) {
//    
//      if($_REQUEST['page'] == 'media-library-folders') {
//
//        $review = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_FEATURE_NOTICE, true );
//        if( $review !== 'off') {
//          add_action( 'admin_notices', array($this, 'mlp_review_notice' ));            
//        }
//      }
//    }
//	}
  
	public function show_mlp_admin_notice() {
    global $current_user;  
    
    if(isset($_REQUEST['page'])) {
    
      if($_REQUEST['page'] == 'mlfp-folders' 
          || $_REQUEST['page'] === 'mlfp-thumbnails' 
          || $_REQUEST['page'] === 'mlfp-image-seo'
          || $_REQUEST['page'] === 'mlfp-settings8' 
          || $_REQUEST['page'] === 'mlfp-cloud' 
          || $_REQUEST['page'] === 'mlfp-support' 
          || $_REQUEST['page'] === 'mflp-search-library' ) {

        
        $features = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_FEATURE_NOTICE, true );
        //error_log("features $features");
        $review = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_REVIEW_NOTICE, true );
        //error_log("review $review");
        if( $review != 'off' || $features !== 'off') {
          if($features == '') {
            $features_date = date('Y-m-d', strtotime("+30 days"));        
            update_user_meta( $current_user->ID, MAXGALLERIA_MLP_FEATURE_NOTICE, $features_date );
          }
          if($review == '') {
            //show review notice after three days
            $review_date = date('Y-m-d', strtotime("+3 days"));        
            update_user_meta( $current_user->ID, MAXGALLERIA_MLP_REVIEW_NOTICE, $review_date );

            //show notice if not found
            //disable for now
            //add_action( 'admin_notices', array($this, 'mlp_review_notice' ));            
          } else if( $review != 'off') {
            $now = date("Y-m-d"); 
            $review_time = strtotime($review);
            $features_time = strtotime($features);
            $now_time = strtotime($now);
            
            //if($now_time > $features_time && $features != 'off')
            //  add_action( 'admin_notices', array($this, 'mlp_features_notice' ));            
            //else if($now_time > $review_time)
            if($now_time > $review_time)
              add_action( 'admin_notices', array($this, 'mlp_review_notice' ));
          } else if( $features != 'off') {
            $now = date("Y-m-d"); 
            $features_time = strtotime($features);
            $now_time = strtotime($now);
            //if($now_time > $features_time && $features != 'off')
            //  add_action( 'admin_notices', array($this, 'mlp_features_notice' ));                        
          }
        }
      }
    }
	}
  
  /* if no upload fold id, check the folder table */
  private function fetch_uploads_folder_id() {
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}mgmlp_folders order by folder_id limit 1";
    $row = $wpdb->get_row($sql);
    if($row) {
      return $row->post_id;
    } else {
      return false;
    }
  }
          
  private function lookup_uploads_folder_name($current_folder_id) {
    global $wpdb;
    $sql = "SELECT post_title FROM {$wpdb->prefix}posts where ID = $current_folder_id";
    $folder_name = $wpdb->get_var($sql);
    return $folder_name;
  }  
      
//  public function media_library2() {
//	  require_once 'includes/media_library.php';	 		
//	}    
  	
  public function display_folder_contents ($current_folder_id, $image_link = true, $folders_path = '', $echo = true) {
    
    //error_log("display_folder_contents");
				
    $folders_found = false;
    $images_found = false;
		$output = "";
    
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER);
    $sort_type = trim(get_option( MAXGALLERIA_MLF_SORT_TYPE ));        
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = "post_date $sort_type";
        break;
      
      case '1': //order by name
        $order_by = "LOWER(post_title) $sort_type";
        break;      
    }
		
		// not used at this time
		//if($image_link)
		//	$image_link = "1";
		//else				
		//	$image_link = "0";
    								
		$output .= '<script type="text/javascript">' . PHP_EOL;
    $output .= '	jQuery(document).ready(function() {' . PHP_EOL;		    
		$output .= '	var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;' . PHP_EOL;		
		$output .= '	var tb_visible = (jQuery("#mlp_tb_title").is(":visible")) ? 0 : 1;' . PHP_EOL;	
		$output .= '	var image_link = (jQuery("#TB_window").is(":visible")) ? 0 : 1;' . PHP_EOL;
    // $output .= '	var grid_list_switch = jQuery("input[type=checkbox]#grid-list-switch-view:checked").length > 0;' . PHP_EOL;
    // $output .= '	grid_list_switch = (grid_list_switch) ? "on" : "off";' . PHP_EOL;    
    $output .= '	var grid_list_switch = jQuery("#grid-list-switch-view").val();' . PHP_EOL;
    //$output .= '	grid_list_switch = (grid_list_switch == "1")? "on" : "off";' . PHP_EOL;    
    $output .= '	console.log("grid_list_switch 3194",grid_list_switch);' . PHP_EOL;    
    
		$output .= '	window.hide_checkboxes = false;' . PHP_EOL;		
		$output .= '    jQuery.ajax({' . PHP_EOL;
		$output .= '      type: "POST",' . PHP_EOL;
		$output .= '      async: true,' . PHP_EOL;
		$output .= '      data: { action: "mlp_display_folder_contents_ajax", current_folder_id: "' . $current_folder_id . '", image_link: image_link, mif_visible: mif_visible, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },' . PHP_EOL;
		//$output .= '      data: { action: "mlp_display_folder_contents_ajax", current_folder_id: "' . $current_folder_id . '", image_link: "' . $image_link . '", mif_visible: mif_visible, nonce: mgmlp_ajax.nonce },' . PHP_EOL;
    $output .= '      url: mgmlp_ajax.ajaxurl,' . PHP_EOL;
		$output .= '      dataType: "html",' . PHP_EOL;
		$output .= '      success: function (data) ' . PHP_EOL;
		$output .= '        {' . PHP_EOL;
		//$output .= '				  console.log("2238 " + window.hide_checkboxes);' . PHP_EOL;
		//$output .= '				  if(window.hide_checkboxes) {' . PHP_EOL;
		//$output .= '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").hide();' . PHP_EOL;
		//$output .= '	          jQuery("a.tb-media-attachment").css("cursor", "pointer");' . PHP_EOL;
		//$output .= '				  } else {' . PHP_EOL;
		//$output .= '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").show();' . PHP_EOL;
		//$output .= '	          jQuery("a.tb-media-attachment").css("cursor", "default");' . PHP_EOL;
		//$output .= '				  }' . PHP_EOL;
		$output .= '          jQuery("#mgmlp-file-container").html(data);' . PHP_EOL;		
		$output .= '          jQuery("li a.media-attachment").draggable({' . PHP_EOL;
		$output .= '          	cursor: "move",' . PHP_EOL;
    $output .= '            cursorAt: { left: 25, top: 25 },' . PHP_EOL;
		$output .= '          helper: function() {' . PHP_EOL;
		$output .= '          	var selected = jQuery(".mg-media-list input:checked").parents("li");' . PHP_EOL;
		$output .= '          	if (selected.length === 0) {' . PHP_EOL;
		$output .= '          		selected = jQuery(this);' . PHP_EOL;
		$output .= '          	}' . PHP_EOL;
		$output .= '          	var container = jQuery("<div/>").attr("id", "draggingContainer");' . PHP_EOL;
		$output .= '          	container.append(selected.clone());' . PHP_EOL;
    $output .= '          	console.log("container",container);' . PHP_EOL;
		$output .= '          	return container;' . PHP_EOL;
		$output .= '          }' . PHP_EOL;
		
		$output .= '          });' . PHP_EOL;
		
//		$output .= '          jQuery(".media-link").droppable( {' . PHP_EOL;
//		$output .= '          	  accept: "li a.media-attachment",' . PHP_EOL;
//		$output .= '          		hoverClass: "droppable-hover",' . PHP_EOL;
//		$output .= '          		drop: handleDropEvent' . PHP_EOL;
//		$output .= '          });' . PHP_EOL;
		
    $output .= '          jQuery(".blank-nav").hide();' . PHP_EOL;
    $output .= '          jQuery(".active-nav").show();' . PHP_EOL;
    $output .= '        },' . PHP_EOL;
		$output .= '          error: function (err)' . PHP_EOL;
		$output .= '	      { alert(err.responseText)}' . PHP_EOL;
		$output .= '	   });' . PHP_EOL;
		
		if($folders_path !== '') {
		  $output .= '   jQuery("#mgmlp-breadcrumbs").html("'. __('Location:','maxgalleria-media-library') . " " . addslashes($folders_path) .'");' . PHP_EOL;
		}
				
    $output .= '	});' . PHP_EOL;
    $output .= '</script>' . PHP_EOL;
		
		if($echo)
			echo $output;
		else
			return $output;
				
	}
  	
	public function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
    return false;
  }


	public function mlp_display_folder_contents_ajax() {
    
    //error_log("mlp_display_folder_contents_ajax");
    		
    global $wpdb;
		    
    //$folders_found = false;
    
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER);
    $sort_type = trim(get_option( MAXGALLERIA_MLF_SORT_TYPE ));    
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = "post_date $sort_type";
        break;
      
      case '1': //order by name
        $order_by = "LOWER(attached_file) $sort_type";
        break;      
    }
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['current_folder_id'])) && !is_array($_POST['current_folder_id']) && (strlen(trim($_POST['current_folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
			$current_folder_id = 0;
		
    if ((isset($_POST['image_link'])) && !is_array($_POST['image_link']) && (strlen(trim($_POST['image_link'])) > 0))
      $image_link = trim(stripslashes(strip_tags($_POST['image_link'])));
		else
			$image_link = "0";
    
    if ((isset($_POST['display_type'])) && !is_array($_POST['display_type']) && (strlen(trim($_POST['display_type'])) > 0))
      $display_type = trim(stripslashes(strip_tags($_POST['display_type'])));
		else
			$display_type = 0;
		
    if ((isset($_POST['mif_visible'])) && !is_array($_POST['mif_visible']) && (strlen(trim($_POST['mif_visible'])) > 0))
      $mif_visible = trim(stripslashes(strip_tags($_POST['mif_visible'])));
		else
			$mif_visible = false;
		
		if($mif_visible === 'true')
			$mif_visible = true;
		else
			$mif_visible = false;
    
    if ((isset($_POST['grid_list_switch'])) && !is_array($_POST['grid_list_switch']) && (strlen(trim($_POST['grid_list_switch'])) > 0))
      $grid_list_switch = trim(stripslashes(strip_tags($_POST['grid_list_switch'])));
		else
			$grid_list_switch = 'off';
    				
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
				
		$this->display_folder_nav($current_folder_id, $folder_table);
    		
    $this->display_files($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible, 0, false, $grid_list_switch );
        
		die();
		
	}
	
	public function mlp_display_folder_contents_images_ajax() {
    
    //error_log("mlp_display_folder_contents_images_ajax");
    	
    global $wpdb;
		        
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER);
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date DESC';
        break;
      
      case '1': //order by name
        $order_by = 'LOWER(post_title)';
        break;      
    }
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
			$current_folder_id = 0;
		
    if ((isset($_POST['image_link'])) && (strlen(trim($_POST['image_link'])) > 0))
      $image_link = trim(stripslashes(strip_tags($_POST['image_link'])));
		else
			$image_link = "0";
		
    if ((isset($_POST['display_type'])) && (strlen(trim($_POST['display_type'])) > 0))
      $display_type = trim(stripslashes(strip_tags($_POST['display_type'])));
		else
			$display_type = 0;
        		
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
			
		$this->display_files($image_link, $current_folder_id, $folder_table, $display_type, $order_by );
		
		die();
		
	}
	
	public function display_folder_nav_ajax () {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
			$current_folder_id = 0;
				
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
				
		$this->display_folder_nav($current_folder_id, $folder_table);
		
		die();
						
	}
	
	public function mlfp_get_next_attachments() {
    
    //error_log("mlfp_get_next_attachments");
		
    global $wpdb;
		
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER);
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date DESC';
        break;
      
      case '1': //order by name
        $order_by = 'LOWER(post_title)';
        break;      
    }
    		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    } 
				
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
			$current_folder_id = 0;
		
    if ((isset($_POST['page_id'])) && (strlen(trim($_POST['page_id'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['page_id']))));
		else
			$page_id = 0;
				
    if ((isset($_POST['image_link'])) && (strlen(trim($_POST['image_link'])) > 0))
      $image_link = trim(stripslashes(strip_tags($_POST['image_link'])));
		else
			$image_link = 0;
    
		if ((isset($_POST['grid_list_switch'])) && (strlen(trim($_POST['grid_list_switch'])) > 0))
      $grid_list_switch = trim(stripslashes(strip_tags($_POST['grid_list_switch'])));
    else
      $grid_list_switch = "";
    		
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
		
		$display_type = 1;
    
    // filter?
    			
		$this->display_files($image_link, $current_folder_id, $folder_table, $display_type, $order_by, false, $page_id, false, $grid_list_switch);

		die();		
		
	}
  
  public function mlfp_update_folder_id() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
				
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
		else
			$folder_id = 0;
    
    //if(!function_exists('wp_get_current_user')) {
    //  include(ABSPATH . "wp-includes/pluggable.php"); 
    //}    
        
    //update_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $folder_id);
    $user = wp_get_current_user();    
    //update_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);    
    update_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, $folder_id);

    echo "updated to $folder_id";
		die();
    
  }
  
  public function set_initial_folder_id() {    
    //$current_user_id = get_current_user_id();
    //update_user_meta( $current_user_id, MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
    //update_option(MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
    
    //if(!function_exists('wp_get_current_user')) {
    //  include(ABSPATH . "wp-includes/pluggable.php"); 
    //}    
    
    $user = wp_get_current_user();    
    update_user_meta($user->ID, MAXGALLERIA_MLP_FOLDER_TO_LOAD, $this->folder_id);
        
  }
		
	public function mlp_get_folder_data() {
				
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
				
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0)) 
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
		  $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        								
				
		$folders = array();
		$folders = $this->get_folder_data($current_folder_id);
					
		echo json_encode($folders);
		
		die();
			
	}
	
	public function get_folder_data($current_folder_id, $selected = true, $filter = true) {
		
    global $wpdb;
    
    if($filter && $this->enable_user_role == 'on') {
      $allowed_folders = $this->get_allowed_folders();
      if($allowed_folders == null)
        $filter = false;
    }  
		
// we used to use this to display the folders		
//    $sql = "select ID, guid, post_title, $folder_table.folder_id
//from $wpdb->prefix" . "posts
//LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
//where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
//and folder_id = $current_folder_id 
//order by $order_by";		
//            $rows = $wpdb->get_results($sql);
		
		$folder_parents = $this->get_parents($current_folder_id);
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
		
			$sql = "select ID, post_title, $folder_table.folder_id
from {$wpdb->prefix}posts
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
order by folder_id";

      //error_log($sql);
						
			$add_child = array();
			$folders = array();
			$first = true;
			$rows = $wpdb->get_results($sql);            
			if($rows) {
				foreach($rows as $row) {
           
          if($filter && $this->enable_user_role == 'on' && !in_array($row->ID, $allowed_folders))
            continue;                  

						//$max_id = -1;

						//if($row->ID > $max_id)
						//	$max_id = $row->ID;
						$folder = array();
						$folder['id'] = $row->ID;
						if($row->folder_id === '0') {
							$folder['parent'] = '#';
							//$folder['children'] = true;
						} else {
              if(!$row->folder_id)
						    continue;
						  // check if parent folder even exists
						  $sql = "select ID from {$wpdb->prefix}posts
						    where ID = {$row->folder_id} and post_type = '".MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE."'";
						  if (count($wpdb->get_results($sql)) == 0)
						    continue;
						  $folder['parent'] = $row->folder_id;
						}

						//$folder['text'] = $row->post_title . " ($row->file_count)";
						$folder['text'] = $row->post_title;
						$state = array();
					if($row->folder_id === '0') {
						$state['opened'] = true;
						$state['disabled'] = false;
						$state['selected'] = $selected;
					} else if($this->in_array_r($row->ID, $folder_parents))	{
						$state['opened'] = true;
					} else if($this->uploads_folder_ID === $row->ID) {	
						$state['opened'] = true;
					}	else {
						$state['opened'] = false;
					}	
					if($row->ID === $current_folder_id) {
						$state['opened'] = true;
						$state['selected'] = false;
					} else
						$state['selected'] = false;
					$state['disabled'] = false;
					$folder['state'] = $state;
					
					$a_attr  = array();
					$a_attr['href'] = "#" . $row->ID;
					$a_attr['target'] = '_self';

					$folder['a_attr'] = $a_attr;
					

//					$a_attr  = array();
//					$a_attr['href'] = site_url() . "/wp-admin/admin.php?page=mlfp-folders&media-folder=" . $row->ID;
//					$a_attr['target'] = '_self';
//
//					$folder['a_attr'] = $a_attr;

					$add_child[] = $row->ID;
					$child_index = array_search($row->folder_id, $add_child);
					if($child_index !== false)
						unset($add_child[$child_index]);

					$folders[] = $folder;
				}

//				$max_id += 99999;
//				foreach($add_child as $child) {
//					$max_id++;
//					$folder = array();
//					$folder['id'] = $max_id;
//					$folder['parent'] = $child;
//					$folder['text'] = "empty node";
//					$state = array();
//					$state['opened'] = false;
//					$state['disabled'] = true;
//					$state['selected'] = false;
//					$folder['state'] = $state;
//					$folders[] = $folder;							
//				}
			}

			return $folders;
		
	}
  
  public function mlfp_new_folder_check() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE))
      exit(__('missing nonce!','maxgalleria-media-library'));
    
    if(!defined('SKIP_AUTO_FOLDER_CHECK'))
      $this->new_folder_check();
    
    echo "folders checked";
    
    die();
      
  }
  
  public function new_folder_check() {
    
    $currnet_date_time = date('Y-m-d H:i:s');
    
    $currnet_date_time_seconds = strtotime($currnet_date_time);
    
    $folder_check = get_option('mlfp-folder-check', $currnet_date_time);
    if($currnet_date_time == $folder_check) {
			update_option('mlfp-folder-check', $currnet_date_time, true);
      return;
    }  
    
    $folder_check_seconds = strtotime($folder_check . ' +1 hour');
    
    //error_log("Last check: " . $folder_check_seconds . " : " . "Current time: " .  $currnet_date_time_seconds);
    
    if($folder_check_seconds < $currnet_date_time_seconds) {
      //error_log("checking folders $currnet_date_time");
      $this->admin_check_for_new_folders(true);
			update_option('mlfp-folder-check', $currnet_date_time, true);
    }		
    
  }
	
	public function display_folder_nav($current_folder_id, $folder_table ) {
	
    global $wpdb;
		
// we used to use this to display the folders		
//    $sql = "select ID, guid, post_title, $folder_table.folder_id
//from $wpdb->prefix" . "posts
//LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
//where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
//and folder_id = $current_folder_id 
//order by $order_by";		
//            $rows = $wpdb->get_results($sql);
		
    if(!defined('SKIP_AUTO_FOLDER_CHECK'))
      $this->new_folder_check();
   
    $folder_parents = $this->get_parents($current_folder_id);
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
						
    $sql = "select ID, post_title, $folder_table.folder_id
from {$wpdb->prefix}posts
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
order by folder_id";
						
					$folders = array();
					$folders = $this->get_folder_data($current_folder_id);
					
					?>
			
<script>
	var mlp_busy = false;
  var folders = <?php echo json_encode($folders); ?>;
	jQuery(document).ready(function(){		
		jQuery("#scanning-message").hide();		
		jQuery("#ajaxloadernav").show();		
    jQuery('#folder-tree').jstree({ 'core' : {
				'data' : folders,
				'check_callback' : true
			},
			'force_text' : true,
			'themes' : {
				'responsive' : false,
				'variant' : 'small',
				'stripes' : true
			},		
			'types' : {
				'default' : { 'icon' : 'folder' },
        'file' : { 'icon' :'folder'},
				'valid_children' : {'icon' :'folder'}	 
 				//'file' : { 'valid_children' : [], 'icon' : 'file' }
			},
			'sort' : function(a, b) {
				return this.get_type(a).toLowerCase() === this.get_type(b).toLowerCase() ? (this.get_text(a).toLowerCase() > this.get_text(b).toLowerCase() ? 1 : -1) : (this.get_type(a).toLowerCase() >= this.get_type(b).toLowerCase() ? 1 : -1);
			},			
        <?php if(current_user_can('administrator') || (!current_user_can('administrator') && $this->disable_non_admins == 'off')) { ?>
				"contextmenu":{
				  "select_node":false,
					"items": function($node) {
						 var tree = jQuery("#tree").jstree(true);
						 return {
							 "Remove": {
								 "separator_before": false,
								 "separator_after": false,
								 "label": "<?php _e('Delete this folder?','maxgalleria-media-library'); ?>",
								 "action": function (obj) { 
										var delete_ids = new Array();
										delete_ids[delete_ids.length] = jQuery($node).attr('id');
										
										var folder_id = jQuery('#folder_id').val();      
										var to_delete = jQuery($node).attr('id');
										var parent_id = jQuery($node).attr('parent');
										
//										if(folder_id === to_delete ) {
//											alert("<?php //_e('You cannot delete the currently open folder.','maxgalleria-media-library'); ?>")
//											return true;
//										}	

										if(confirm("<?php _e('Are you sure you want to delete the selected folder?','maxgalleria-media-library'); ?>")) {
											var serial_delete_ids = JSON.stringify(delete_ids.join());
											jQuery("#ajaxloader").show();
											jQuery.ajax({
												type: "POST",
												async: true,
												data: { action: "delete_maxgalleria_media", serial_delete_ids: serial_delete_ids, parent: parent_id, nonce: mgmlp_ajax.nonce },
												url : mgmlp_ajax.ajaxurl,
												dataType: "json",
												success: function (data) {
													jQuery("#ajaxloader").hide();            
													
													jQuery("#folder-message").html(data.message);
													if(data.refresh) {
														jQuery('#folder-tree').jstree(true).settings.core.data = data.folders;
														jQuery('#folder-tree').jstree(true).refresh();			
														jQuery('#folder-tree').jstree('select_node', '#' + parent_id, true);
														jQuery('#folder-tree').jstree('toggle_expand', '#' + parent_id, true );
														jQuery("#folder-message").html('');
														jQuery("#current-folder-id").val(parent_id);
													}																																																															
												},
												error: function (err) {
													console.log('10');
													alert(err.responseText);
												}
											});
									} 
								}
							},
              <?php if($this->license_valid) { ?>     
							 "Hide": {
								 "separator_before": false,
								 "separator_after": false,
								 "label": "<?php _e('Hide this folder? This will remove the folder contents from the media library database.','maxgalleria-media-library'); ?>",                 
								 "action": function (obj) { 
										//var hide_id = jQuery($node).attr('id');										
										var folder_id = jQuery('#folder_id').val();      
										var to_hide = jQuery($node).attr('id');
										//if(folder_id === to_hide ) {
										//	alert("<?php _e('You cannot hide the currently open folder.','maxgalleria-media-library'); ?>");
										//	return true;
										//}	

								    if(confirm("<?php _e('Are you sure you want to hide the selected folder and all its sub folders and files?','maxgalleria-media-library'); ?>")) {
											//var serial_delete_ids = JSON.stringify(delete_ids.join());
											jQuery("#ajaxloader").show();
											jQuery.ajax({
												type: "POST",
												async: true,
												data: { action: "hide_maxgalleria_media", folder_id: to_hide, nonce: mgmlp_ajax.nonce },
												url : mgmlp_ajax.ajaxurl,
												dataType: "html",
												success: function (data) {
													jQuery("#ajaxloader").hide();            
													jQuery("#folder-message").html(data);
												},
												error: function (err) {
													console.log('11');
													alert(err.responseText);
												}
											});
									} 
								}
							}
              <?php } ?>
						}; // end context menu
					}					
			},						
			'plugins' : [ 'sort','types','contextmenu' ],
      <?php } else {  ?>      
			'plugins' : [ 'sort','types' ],
      <?php }  ?>      
			//'plugins' : [ 'sort','types', 'state', 'contextmenu' ],
		});
		
		// for changing folders
		if(!jQuery("ul#folder-tree.jstree").hasClass("bound")) {
      jQuery("#folder-tree").addClass("bound").on("select_node.jstree", show_mlp_node);		
		}	
				
		jQuery('#folder-tree').droppable( {
				accept: 'li a.media-attachment',
				hoverClass: 'jstree-anchor',
				//hoverClass: 'droppable-hover',
				drop: handleTreeDropEvent
		});
	
		jQuery('#folder-tree').on('copy_node.jstree', function (e, data) {
			 //console.log(data.node.data.more); 
		});
				
		jQuery("#ajaxloadernav").hide();		

	});  
	
	
function show_mlp_node (e, data) {

  if(window.bulk_move_status) {
    console.log(data.node) // id and text
    get_bulk_move_destination(data.node.id);
    window.bulk_move_status = false;
    return false;
  }  

  jQuery("#display_type").val('1');
  jQuery("#folder-message").html('');
  //var thickbox_shown = (jQuery('#TB_window').is(':visible')) ? true : false;
	if(!window.mlp_busy) {
		window.mlp_busy = true;
		//if(thickbox_shown) {
		
    //console.log('show_mlp_node');
    if(jQuery('div#embed-area.input-area').is(":visible")) {
      jQuery('div#embed-area.input-area').slideUp(600);
      jQuery("#embed-file-url").val('');      
      jQuery("#embed-shortcode-container").val('');      
      jQuery("#copy-message").val('');
      jQuery("#embed-poster").val('');
      jQuery('#embed-autoplay').prop('checked', false);
      jQuery('#embed-controls').prop('checked', false);
      jQuery('#embed-loop').prop('checked', false);
      jQuery('#embed-muted').prop('checked', false);
      jQuery('select#embed-preload>option:eq(0)').prop('selected', true);
      
    }  
    	
			// opens the closed node
			jQuery("#folder-tree").jstree("toggle_node", data.node.id);
      
      // clear the filtered text
      if(jQuery("#filter-area").is(":visible")) {
        jQuery("#filter_text").val('');
        //jQuery("#filter-area").hide();
        //javascript:slideonlyone('filter-area');
      }
			
			var folder = data.node.id;

			jQuery("#ajaxloader").show();

			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlp_load_folder", folder: folder, nonce: mgmlp_ajax.nonce },
				url : mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) {
					jQuery("#ajaxloader").hide();          
					jQuery("#mgmlp-file-container").html(data);						
					jQuery("#current-folder-id").val(folder);
					jQuery("#folder_id").val(folder);
					sessionStorage.setItem('folder_id', folder);
					
					jQuery("li a.media-attachment").draggable({
						cursor: "move",
            cursorAt: { left: 25, top: 25 },
						helper: function() {
							var selected = jQuery(".mg-media-list input:checked").parents("li");
							if (selected.length === 0) {
								selected = jQuery(this);
							}
							var container = jQuery("<div/>").attr("id", "draggingContainer");
							//var container = jQuery("<div style='width:40px;height:40px' />").attr("id", "draggingContainer");
							container.append(selected.clone());
              jQuery("#draggable").css("height", '200px');
							return container;
						}		
					});
					
//					jQuery(".media-link").droppable( {
//						accept: "li a.media-attachment",
//						hoverClass: "droppable-hover",
//						drop: handleDropEvent
//					});					
															
//					if(window.hide_checkboxes) {
//						jQuery("div#mgmlp-tb-container input.mgmlp-media").hide();
//						jQuery("a.tb-media-attachment").css("cursor", "pointer");
//					} else {
//						jQuery("div#mgmlp-tb-container input.mgmlp-media").show();
//						jQuery("a.tb-media-attachment").css("cursor", "default");
//					}	
				},
				error: function (err) { 
						console.log('12');
						alert(err.responseText);
					}
			});

//		} else {	
//			window.location.href = data.node.a_attr.href;
//		}
		window.mlp_busy = false;
	}	
}

function get_bulk_move_destination(folder_id) {
  
  
  //jQuery("#mlfp-stop-file-move").show();
  
  jQuery("#ajaxloader").show();  
  
  jQuery('#bulkmove-destination-folder-id').val(folder_id);
  
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_get_destination_folder_path", folder_id: folder_id, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
      jQuery("#bulkmove-destination-folder").val(mgmlp_ajax.destination_folder + data.folder_path);
      jQuery("#bulkmove-destination-folder-path").val(data.absolute_path);
      jQuery("#mlfp-bulk-move-files").removeClass("disabled-button");
      jQuery("#mlfp-bulk-move-files").attr('disabled',false);          
		  jQuery("#ajaxloader").hide();
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText);
		}    
	});																											
  
  
  
}

function handleTreeDropEvent(event, ui ) {
		
	var target=event.target || event.srcElement;
	//console.log(target);
	
	var move_ids = new Array();
	var items = ui.helper.children();
	items.each(function() {  
		move_ids[move_ids.length] = jQuery(this).find( "a.media-attachment" ).attr("id");
	});
	
	if(move_ids.length < 2) {
	  move_ids = new Array();
		move_ids[move_ids.length] =  ui.draggable.attr("id");
	}	
		
	//var serial_copy_ids = JSON.stringify(move_ids.join());
	var folder_id = jQuery(target).attr("aria-activedescendant");	
	var current_folder = jQuery("#current-folder-id").val();      
	
	var action_name = 'move_media';
  var move_or_copy_status = jQuery('#move-or-copy-status').val();  
	if(move_or_copy_status == 'on')
		action_name = 'move_media';
	else
		action_name = 'copy_media';

	jQuery("#ajaxloader").show();
			
  var serial_copy_ids = JSON.stringify(move_ids.join());

  process_mc_data('1', folder_id, action_name, current_folder, serial_copy_ids);
      						
} 

function delete_current_folder(node) {
	var folder_id = jQuery(target).attr("aria-activedescendant");	
	//console.log(folder_id);	
}

function process_mc_data(phase, folder_id, action_name, parent_folder, serial_copy_ids) {
  
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_process_mc_data", phase: phase, folder_id: folder_id, action_name: action_name, current_folder: parent_folder, serial_copy_ids: serial_copy_ids, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			if(data != null && data.phase != null) {
			  jQuery("#folder-message").html(data.message);
        process_mc_data(data.phase, folder_id, action_name, parent_folder, null);
      } else {        
			  jQuery("#folder-message").html(data.message);
        if(action_name == 'move_media')
				  mlf_refresh_folders(parent_folder);
		    jQuery("#ajaxloader").hide();
				return false;
      }      
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText);
		}    
	});																											
  
}
</script>
  <?php
							
	}
  
  public function mlfp_get_destination_folder_path() {
    
    global $wpdb;
    global $is_IIS;    
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0)) 
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
		else
		  $folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );    
    
        global $wpdb;    
    
    $sql = "select meta_value as attached_file from {$wpdb->prefix}postmeta where post_id = $folder_id AND meta_key = '_wp_attached_file'";

    //error_log($sql);
				
    $row = $wpdb->get_row($sql);
		
    //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;		
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($row->attached_file, '/');
    $absolute_path = $this->get_absolute_path($image_location);
    
    //error_log("image_location $image_location");
    //error_log("absolute_path $absolute_path");
        
    $data = array ('folder_path' => $image_location, 'absolute_path' => $absolute_path );
    echo json_encode($data);
    
    die();
        
  }
	
	public function display_files($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible = false, $page_id = 0, $filter = false, $grid_list_switch = 'off') {
    
    if($grid_list_switch == 'off' || $grid_list_switch == 'false') {
      //echo "<p>displaying list</p>";
      $this->display_files_list($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible, $page_id, $filter);
    } else {
      $this->display_files_grid($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible, $page_id, $filter);
    }    
  }
  	
  public function display_files_grid($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible = false, $page_id = 0, $filter = false) {
    
    global $wpdb;
    $images_found = false;
		$offset = 0;
		
		if($image_link === "1")
			$image_link = true;
		else
			$image_link = false;
    
    echo "<style>

    .media-folder, .media-attachment, .media-attachment img, a.tb-media-attachment img, a.media-attachment img {
      height: 135px !important;
      width: 135px !important;    
    }

    ul.mg-media-list li a {
      width: 135px;
    }

    </style>";
		if($this->license_valid) {				
		  $items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '40'));
		  $enable_pagination = get_option(MAXGALLERIA_MLP_PAGINATION, 'off');
    } else {
      $items_per_page = 500;
      $enable_pagination = 'off';
    }  
    
		if($enable_pagination == 'off') {
			$sql = "select COUNT(*)  
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where post_type = 'attachment' 
and folder_id = '$current_folder_id'";
							
			$row_count = $wpdb->get_var($sql);
		  if($this->license_valid) {				
        if($row_count > 40 && $display_type === 0) {
           ?>
            <p class="center-text"><?php echo $row_count; ?><?php _e(' files were found. Choose to display the images or just the file names?', 'maxgalleria-media-library' ); ?></p>
            <div class="center-text">
              <a id="display_mlpp_images" folder_id="<?php echo $current_folder_id; ?>" image_link="<?php echo $image_link; ?>" class="gray-blue-link"><?php _e('Display images', 'maxgalleria-media-library' ); ?></a>
              <a id="display_mlpp_titles" folder_id="<?php echo $current_folder_id; ?>" image_link="<?php echo $image_link; ?>" class="gray-blue-link"><?php _e('Display image file names only', 'maxgalleria-media-library' ); ?></a>				
              <p style="text-align: center"><?php _e( 'Or you can turn on pagination in Settings.', 'maxgalleria-media-library' ); ?></p>
            </div>	
          <?php
          die();		
        }
      }
		}
		
				
            //echo $this->display_secondary_toolbar($total_images, 1, $total_number_pages);    
            //echo '<ul class="mg-media-list">' . PHP_EOL;              
            
//            $sql = "select ID, guid, post_title, $folder_table.folder_id 
//from $wpdb->prefix" . "posts 
//LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
//where post_type = 'attachment' 
//and folder_id = '$current_folder_id'
//order by $order_by";
						
    $offset = $page_id * $items_per_page;

    if($enable_pagination == 'on')
      $limit = "limit $offset, $items_per_page";
    else
      $limit = "";
						
						
    $sql = "select SQL_CALC_FOUND_ROWS ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file 
from {$wpdb->prefix}posts 
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' 
and folder_id = '$current_folder_id'
AND pm.meta_key = '_wp_attached_file' 
order by $order_by $limit";

//AND post_title like '%%$filter%%'
						//error_log($sql);

    $rows = $wpdb->get_results($sql);            

    $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
    $total_images = $count['FOUND_ROWS()'];
    if($items_per_page != 0)
      $total_number_pages = ceil($total_images / $items_per_page);
    else
      $total_number_pages = 0;

    echo "<input type='hidden' id='mlfp-file-count' value='$total_images'>" . PHP_EOL;
    echo "<input type='hidden' id='mlfp-last-page' value='$total_number_pages'>" . PHP_EOL;						            
    echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'on');    
    echo '<ul class="mg-media-list">' . PHP_EOL;              
    if($rows) {
      $images_found = true;
      $counter = 1;
      foreach($rows as $row) {
        $thumbnail_html = "";
        $image_file_type = true;
        if($display_type == 1 || $display_type == 0) {
          $new_attachment_id = $row->ID;
          //if(is_array($new_attachment_id))
            //error_log("wp_get_attachment_image id");
          $thumbnail_html = wp_get_attachment_image( $new_attachment_id, 'thumbnail', false, '');
          if(!$thumbnail_html){
            $thumbnail = wp_get_attachment_thumb_url($new_attachment_id);                
            //if(is_array($thumbnail))
            //  error_log("wp_get_attachment_image thumbnail");
            if($thumbnail === false || $display_type == 2) {									
              $ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
              //if(is_array($ext))
              //  error_log("wp_get_attachment_image ext");
              //$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
              $thumbnail = $this->get_file_thumbnail($ext);
              $image_file_type = false;
            }
            $thumbnail_html = "<img alt='' src='$thumbnail' />";
          }  
        } else {
          $thumbnail_html = "";
        }

        $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
        if($image_link && $mif_visible)
          $class = "media-attachment no-pointer"; 
//								else if($image_link)
//                  $class = "media-attachment"; 
//								else
//                  $class = "tb-media-attachment"; 
        else
          $class = "media-attachment"; 

        if($display_type == 2) // from bcmc version
          $class .= " mlfp-list-images"; 

        $s3_class = "";
        if($display_type == 1 || $display_type == 0) {
          if(class_exists('MGMediaLibraryFoldersProS3')) {			
            if($this->s3_addon->s3_active) {
              if($this->s3_addon->serve_from_s3) {
                //error:log("thumbnail $thumbnail");
                if($image_file_type) {
                  if(!strpos($thumbnail_html, $this->s3_addon->bucket))
                    $s3_class = "on-local";
                }
              }					
            }
          }
        }

        // for WP 4.6 use /wp-admin/post.php?post=
        if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
          $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
        else
          $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;

        //$image_location = $this->check_for_attachment_id($row->guid, $row->ID);
        //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
        $baseurl = $this->upload_dir['baseurl'];
        $baseurl = rtrim($baseurl, '/') . '/';
        $image_location = $baseurl . ltrim($row->attached_file, '/');

        $filename = pathinfo($image_location, PATHINFO_BASENAME);
        //error_log("image_link $image_link, mif_visible $mif_visible, display_type $display_type");

        if($display_type == 2)
          echo "<li id='$row->ID' class='mlpf-file-list'>" . PHP_EOL;
        else
          echo "<li id='$row->ID'>" . PHP_EOL;

        if($display_type == 1 || $display_type == 0) {
          if($mif_visible)
            echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;
          else if($image_link && !$mif_visible)
            echo "   <a id='$row->ID' class='$class edit-link' title='$filename'>$thumbnail_html</a>" . PHP_EOL;
            //echo "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'>$thumbnail_html</a>" . PHP_EOL;
          else
            echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;

          if(defined('MLFP_SHOW_TITLES')) {               
              echo "   <div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span><br>$filename</div>" . PHP_EOL;
            //echo "</li>" . PHP_EOL;        
          } else {
            echo "   <div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span><span class='mlf-filename'>$filename</span></div>" . PHP_EOL;
          }
        } else {

          if(defined('MLFP_SHOW_TITLES')) {               
            if($counter % 2)
              $thumbnail_html = "<div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span> - $filename</div>";
            else
              $thumbnail_html = "<div class='attachment-name transparent $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span> - $filename</div>";   
          } else {
            $thumbnail_html = "<div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span>$filename</div>";
          }

          if($mif_visible) {
            echo "   <a id='$row->ID' class='$class' title='$filename'></a>" . $thumbnail_html . PHP_EOL;
          } else if($image_link && !$mif_visible) {
            echo "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'></a>" . $thumbnail_html . PHP_EOL;
          } else {
            echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;                  
          }  
        }
        echo "</li>" . PHP_EOL;                          
        $counter++;
      }      
    }
    echo '</ul>' . PHP_EOL;
    echo '<div style="clear:both"></div>' . PHP_EOL;

    $this->insert_mlfp_js();

    if(!$images_found)
      echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
    else {
      if($this->license_valid) {                    
        echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, false);
      }  
    }



	}
  
  public function display_files_list($image_link, $current_folder_id, $folder_table, $display_type, $order_by, $mif_visible = false, $page_id = 0, $filter = false) {
    
    global $wpdb;
    $images_found = false;
		$offset = 0;
		
		if($image_link === "1")
			$image_link = true;
		else
			$image_link = false;
						
		$items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '20'));
        
    $offset = $page_id * $items_per_page;

    $limit = "limit $offset, $items_per_page";
												
    $sql = "select SQL_CALC_FOUND_ROWS posts.ID, posts.post_title, $folder_table.folder_id, pm.meta_value as attached_file, us.display_name, posts.post_date 
from {$wpdb->prefix}posts as posts
LEFT JOIN $folder_table ON(posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = posts.ID) 
LEFT JOIN {$wpdb->users} AS us ON (posts.post_author = us.ID) 
where post_type = 'attachment' 
and folder_id = '$current_folder_id'
AND pm.meta_key = '_wp_attached_file' 
order by $order_by $limit";

//AND post_title like '%%$filter%%'
    //error_log($sql);

    $rows = $wpdb->get_results($sql);            

    $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
    $total_images = $count['FOUND_ROWS()'];
    if($items_per_page != 0)
      $total_number_pages = ceil($total_images / $items_per_page);
    else
      $total_number_pages = 0;
    
    echo "<style>
          
    ul.mg-media-list li {
      display: table-row;
      float: none;
      /*height: 40px;*/
      list-style: outside none none;
      margin: 0;
      max-width: none;
      overflow: visible;
      width: 100%;
    }
    
    ul.mg-media-list li {
      height: auto;
    }
   
    </style>";
    
    echo "<input type='hidden' id='mlfp-file-count' value='$total_images'>" . PHP_EOL;						        
    echo "<input type='hidden' id='mlfp-last-page' value='$total_number_pages'>" . PHP_EOL;						            
    echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'off');    
    if($rows) {
      $images_found = true;
      $counter = 1;
      echo '<ul class="mg-media-list">' . PHP_EOL;
      foreach($rows as $row) {
        $thumbnail_html = "";
        $image_file_type = true;
        if($display_type == 1 || $display_type == 0) {
          $new_attachment_id = $row->ID;
          $thumbnail_html = wp_get_attachment_image( $new_attachment_id, 'thumbnail', false, '');
          if(!$thumbnail_html){
            $thumbnail = wp_get_attachment_thumb_url($new_attachment_id);                
            if($thumbnail === false || $display_type == 2) {									
              $ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
              $thumbnail = $this->get_file_thumbnail($ext);
              $image_file_type = false;
            }
            $thumbnail_html = "<img alt='' src='$thumbnail' />";
          }  
        } else {
          $thumbnail_html = "";
        }

        $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
        
        $s3_class = "";
        if($display_type == 1 || $display_type == 0) {
          if(class_exists('MGMediaLibraryFoldersProS3')) {			
            if($this->s3_addon->s3_active) {
              if($this->s3_addon->serve_from_s3) {
                //error:log("thumbnail $thumbnail");
                if($image_file_type) {
                  if(!strpos($thumbnail_html, $this->s3_addon->bucket))
                    $s3_class = "on-local";
                }
              }					
            }
          }
        }

        // for WP 4.6 use /wp-admin/post.php?post=
        if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
          $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
        else
          $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;
        
        
        $baseurl = $this->upload_dir['baseurl'];
        $baseurl = rtrim($baseurl, '/') . '/';
        $image_location = $baseurl . ltrim($row->attached_file, '/');
        $filename = pathinfo($image_location, PATHINFO_BASENAME);
        
        if($counter % 2)
          echo '<li class="row-item gray-row">';
        else
          echo '<li class="row-item">';
        echo '  <span class="mlfp-list-cb">'.$checkbox.'</span>';
        echo '  <span class="mlfp-list-image"><a id="'.$row->ID.'" class="media-attachment list edit-link" >'.$thumbnail_html.'</a></span>';
        echo '  <span class="mlfp-list-title">'.$row->post_title.'</span>';
        echo '  <span class="mlfp-list-file '.$s3_class.'">'.$filename.'</span>';
        echo '  <span class="mlfp-list-author">'.$row->display_name.'</span>';
        echo '  <span class="mlfp-list-cat">'. $this->get_media_categories($row->ID) .'</span>';
        echo '  <span class="mlfp-list-date">'. date("Y-m-d", strtotime($row->post_date)) .'</span>';
        echo '</li>';
        
        $counter++;
      }      
      echo "</ul>" . PHP_EOL;      
    }
    echo '<div style="clear:both"></div>' . PHP_EOL;
    
    $this->insert_mlfp_js();

    if(!$images_found)
      echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
    else {
      if($this->license_valid) {      
        echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, false);
      }  
    }
    
  }
  
  function get_media_categories($attachment_id) {
    
    global $wpdb;
    $categories = '';
    
    $media_category = apply_filters(MGMLP_FILTER_SET_CUSTOM_MEDIA_CATEGORY, 'media_category');
        
    $sql = "SELECT t.* 
FROM $wpdb->terms t
JOIN $wpdb->term_taxonomy tt ON(t.`term_id` = tt.`term_id`)
JOIN $wpdb->term_relationships ttr ON(ttr.term_taxonomy_id = tt.term_taxonomy_id)
WHERE tt.taxonomy = '$media_category'  
AND ttr.`object_id` = $attachment_id";
    
    //error_log($sql);
    
    $rows = $wpdb->get_results($sql);
    
    if($rows) {
      $first = true;
      foreach($rows as $row) {
        if($first)
          $categories .= $row->name;
        else
          $categories .= ', ' . $row->name;
        $first = false;
      }
    } 
      
    return $categories;
        
  }
      
  public function insert_mlfp_js() {
    
    echo '      <script>' . PHP_EOL;
    echo '				jQuery(document).ready(function(){' . PHP_EOL;
    echo '			    jQuery("#folder-message").html("");' . PHP_EOL;
//						echo '				  console.log(window.hide_checkboxes);' . PHP_EOL;
    echo '				  if(window.hide_checkboxes) {' . PHP_EOL;
    echo '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").hide();' . PHP_EOL;
    echo '	          jQuery("a.tb-media-attachment").css("cursor", "pointer");' . PHP_EOL;
    echo '				  } else {' . PHP_EOL;
    echo '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").show();' . PHP_EOL;
    echo '	          jQuery("a.tb-media-attachment").css("cursor", "default");' . PHP_EOL;
    echo '				  }' . PHP_EOL;
    echo '          jQuery("li a.media-attachment").draggable({' . PHP_EOL;
    echo '          	cursor: "move",' . PHP_EOL;
    echo '            cursorAt: { left: 25, top: 25 },' . PHP_EOL;
    echo '            helper: function() {' . PHP_EOL;
    echo '          	  var selected = jQuery(".mg-media-list input:checked").parents("li");' . PHP_EOL;
    echo '          	  if (selected.length === 0) {' . PHP_EOL;
    echo '          		  selected = jQuery(this);' . PHP_EOL;
    echo '          	  }' . PHP_EOL;
    echo '          	  var container = jQuery("<div/>").attr("id", "draggingContainer");' . PHP_EOL;
    echo '          	  container.append(selected.clone());' . PHP_EOL;
    echo '          	  return container;' . PHP_EOL;
    echo '            }' . PHP_EOL;
    echo '          });' . PHP_EOL;

    echo '          jQuery(document).on("click", "#mlfp-previous, #mlfp-next", function (e) {' . PHP_EOL;
    echo '            console.log("mlfp-next mlfp-previous")' . PHP_EOL;
    echo '            e.stopImmediatePropagation();' . PHP_EOL;

    echo '        	  jQuery("#ajaxloader").show();' . PHP_EOL;

    echo '        	  jQuery("#filter_text").val("");' . PHP_EOL;            

    echo '        	  if(jQuery("#current-folder-id").val() === undefined) ' . PHP_EOL;
    echo '        		  var current_folder_id = sessionStorage.getItem("folder_id");' . PHP_EOL;
    echo '        	  else' . PHP_EOL;
    echo '        		  var current_folder_id = jQuery("#current-folder-id").val();' . PHP_EOL;

    echo '        	  var page_id = jQuery(this).attr("page-id");' . PHP_EOL;
    echo '        	  var image_link = jQuery(this).attr("image_link");' . PHP_EOL;
    
    echo '            var grid_list_switch = jQuery("#grid-list-switch-view").val();' . PHP_EOL;
    echo '            grid_list_switch = (grid_list_switch == "on") ? "true" : "false";' . PHP_EOL;

    echo '        	  jQuery.ajax({' . PHP_EOL;
    echo '        		  type: "POST",' . PHP_EOL;
    echo '          		async: true,' . PHP_EOL;
    echo '        	  	data: { action: "mlfp_get_next_attachments", current_folder_id: current_folder_id, page_id: page_id, image_link: image_link, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },' . PHP_EOL;
    echo '        		  url: mgmlp_ajax.ajaxurl,' . PHP_EOL;
    echo '        		  dataType: "html",' . PHP_EOL;
    echo '        		  success: function (data) {' . PHP_EOL;
    echo '        			  jQuery("#ajaxloader").hide();' . PHP_EOL;    
    echo '        			  jQuery("#mgmlp-file-container").html(data);' . PHP_EOL;
    echo '        		  },' . PHP_EOL;
    echo '        		  error: function (err){' . PHP_EOL;
    echo '        			  jQuery("#ajaxloader").hide();' . PHP_EOL;
    echo '        			  alert(err.responseText);' . PHP_EOL;
    echo '        		  }' . PHP_EOL;
    echo '        	  });' . PHP_EOL;
    echo '          });' . PHP_EOL;

    echo '        });' . PHP_EOL;
    echo '      </script>' . PHP_EOL;

  }
  
  
  public function display_list_container($visible) {
    $buffer = "";
    if($visible == 'off')
      $buffer .= '<table class="mgmlp-list">' . PHP_EOL;
    else
      $buffer .=  '<table class="mgmlp-list" style="display:none">' . PHP_EOL;
    $buffer .=  '  <thead>' . PHP_EOL;
    $buffer .=  '    <tr>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-cb">&nbsp;</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-image">&nbsp;</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-title">' . __('Title','maxgalleria-media-library').'</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-file">' . __('File','maxgalleria-media-library').'</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-author">' . __('Author','maxgalleria-media-library').'</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-cat">' . __('Categories','maxgalleria-media-library').'</td>' . PHP_EOL;
    $buffer .=  '      <td class="mlfp-list-date">' . __('Date','maxgalleria-media-library').'</td>' . PHP_EOL;
    $buffer .=  '    </tr>' . PHP_EOL;
    $buffer .=  '  </thead>' . PHP_EOL;
    $buffer .=  '</table>' . PHP_EOL;
    return $buffer;
  }
    
  public function mlfp_get_folder_path() {
    
    global $wpdb;
    global $is_IIS;    
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0)) 
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
		else
		  $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        								
    
    $folder_path = $this->get_folder_path($current_folder_id);
    
    $postion = strpos($folder_path, $this->uploads_folder_name);
    $uploads_path = substr($folder_path, $postion);    
    
		$content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
    $wpc_postion = strpos($folder_path, $content_folder);
    $wpc_path = substr($folder_path, $wpc_postion);    
    
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      $wpc_path = str_replace('\\', '/', $wpc_path);
        
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}mlfp_files_to_sync WHERE file_name LIKE '%$wpc_path%'";
    
		$count = $wpdb->get_var($sql);
    
    $data = array ('uploads_path' => $uploads_path, 'wpc_path' => $wpc_path, 'count' => $count);
    echo json_encode($data);
    
    die();
    
  }
	  
  private function get_folder_path($folder_id) {
    
    //error_log("get_folder_path, $folder_id");
      
    global $wpdb;    
    
   $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $folder_id
AND meta_key = '_wp_attached_file'";
				
    $row = $wpdb->get_row($sql);
		
    //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;		
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($row->attached_file, '/');
    $absolute_path = $this->get_absolute_path($image_location);
		
    return $absolute_path;
      
  }
  
  private function get_subfolder_path($folder_id) {
      
    global $wpdb;    
    
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $folder_id    
AND meta_key = '_wp_attached_file'";
		
    $row = $wpdb->get_row($sql);
		
	  //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($row->attached_file, '/');
			
    $postion = strpos($image_location, $this->uploads_folder_name);
    $path = substr($image_location, $postion+$this->uploads_folder_name_length );
    return $path;
      
  }
  
  private function get_folder_name($folder_id) {
    global $wpdb;    
    $sql = "select post_title from $wpdb->prefix" . "posts where ID = $folder_id";    
    $row = $wpdb->get_row($sql);
    return $row->post_title;
  }
    
  public function get_parents($current_folder_id) {

    global $wpdb;    
    $folder_id = $current_folder_id;    
    $parents = array();
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
		$not_found = false;
    
    while($folder_id !== '0' || !$not_found ) {    
      
    //error_log("gp folder id $folder_id");
      
      $sql = "select post_title, ID, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
where ID = $folder_id";    
      
      $row = $wpdb->get_row($sql);
			
			if($row) {      
				$folder_id = $row->folder_id;
				$new_folder = array();
				$new_folder['name'] = $row->post_title;
				$new_folder['id'] = $row->ID;
				$parents[] = $new_folder;      
			} else {
				$not_found = true;
			}              
    }
    
    $parents = array_reverse($parents);
        
    return $parents;
    
  }  

  private function get_parent($folder_id) {
    
    global $wpdb;    
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    
    $sql = "select folder_id from $folder_table where post_id = $folder_id";    
    
    $row = $wpdb->get_row($sql);
		if($row)        
      return $row->folder_id;
    else
			return $this->uploads_folder_ID;
  }
  
  public function create_new_folder() {
    
    //error_log("create_new_folder");
            
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 

    if ((isset($_POST['parent_folder'])) && (strlen(trim($_POST['parent_folder'])) > 0))
      $parent_folder_id = trim(stripslashes(strip_tags($_POST['parent_folder'])));
    
    
    if ((isset($_POST['new_folder_name'])) && (strlen(trim($_POST['new_folder_name'])) > 0))
      $new_folder_name = trim(stripslashes(strip_tags($_POST['new_folder_name'])));
  
    $this->create_new_mlfp_folder($parent_folder_id, $new_folder_name, true);
  }
  
  public function create_new_mlfp_folder($parent_folder_id, $new_folder_name, $echo, $termID = null) {
    
    //error_log("create_new_mlfp_folder, parent_folder_id $parent_folder_id, termID $termID");
    
    global $wpdb, $is_IIS;
        
    if($this->enable_user_role == 'on') 
      $user_role = $this->get_compatible_parent_user_role($parent_folder_id);
      //$user_role = $this->get_user_role();
    
    if(empty($parent_folder_id)) {
      if($echo) {
        $message = __('Cannot create folder, missing parent folder ID.','maxgalleria-media-library');
        $data = array ('message' => $message,  'refresh' => false );
        die();
      }      
      return;
    }
            
      $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $parent_folder_id    
AND meta_key = '_wp_attached_file'";

    //error_log($sql);
		
    $row = $wpdb->get_row($sql);
		
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($row->attached_file, '/');
				        
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {          
      $absolute_path = $this->get_absolute_path($image_location);
      $absolute_path = rtrim($absolute_path, '\\') . '\\';
    } else {
      $absolute_path = $this->get_absolute_path($image_location);
      $absolute_path = rtrim($absolute_path, '/') . '/';
    }
		//$this->write_log("absolute_path $absolute_path");
        
    $new_folder_path = $absolute_path . $new_folder_name ;
		//$this->write_log("new_folder_path $new_folder_path");
    
    $new_folder_url = $this->get_file_url_for_copy($new_folder_path);
		//$this->write_log("new_folder_url $new_folder_url");
		
		//$this->write_log("Trying to create directory at $new_folder_path, $parent_folder_id, $new_folder_url");
    
		do_action(MLFP_BEFORE_FOLDER_CREATION, $new_folder_path);   
    
    //error_log("new_folder_path $new_folder_path");
        
    if(!file_exists($new_folder_path)) {
      if(mkdir($new_folder_path)) {
        if(defined('FS_CHMOD_DIR'))
			    @chmod($new_folder_path, FS_CHMOD_DIR);
        else  
			    @chmod($new_folder_path, 0755);
        $new_folder_id = $this->add_media_folder($new_folder_name, $parent_folder_id, $new_folder_url, $termID);
        if($new_folder_id){
          //$location = 'window.location.href = "' . home_url() . '/wp-admin/admin.php?page=mlfp-folders&media-folder=' . $parent_folder_id .'";';
          //echo __('The folder was created.','maxgalleria-media-library');
          //echo "<script>$location</script>";
          //$this->display_folder_contents ($parent_folder_id);
          //$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
					
				  //$this->display_folder_nav($parent_folder_id, $folder_table);
					
				  do_action(MLFP_AFTER_FOLDER_CREATION, $new_folder_path, $parent_folder_id, $new_folder_url);              
          
          if($this->enable_user_role == 'on' && $user_role != false)
            $this->append_folder_id($user_role, $new_folder_id);
			
          $message = __('The folder was created.','maxgalleria-media-library');
					$folders = $this->get_folder_data($parent_folder_id);
          if($echo) {
            $data = array ('message' =>$message, 'folders' => $folders, 'refresh' => true );
            echo json_encode($data);
          }
					
        } else {					
          if($echo) {
            $message = __('There was a problem creating the folder.','maxgalleria-media-library');
            $data = array ('message' => $message,  'refresh' => false );
            echo json_encode($data);
          }
				}	
      }
    } else {
      if($echo) {
        $message = __('The folder already exists.','maxgalleria-media-library');
        $data = array ('message' => $message,  'refresh' => false );
        echo json_encode($data);
      }  
		}	
    if($echo) {
      die();
    }  
  }

  public function get_absolute_path($url) {
		
		global $blog_id, $is_IIS;
		
		$baseurl = $this->upload_dir['baseurl'];
				
		if(is_multisite()) {
			$url_slug = "site" . $blog_id . "/";
			$baseurl = str_replace($url_slug, "", $baseurl);
			$content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
			if(strpos($url, $content_folder) === false)
			  $url = str_replace($url_slug, "$content_folder/uploads/sites/" . $blog_id . "/" , $url);
			else
			  $url = str_replace($url_slug, "", $url);
		}
		
    //$file_path = str_replace( $this->upload_dir['baseurl'], $this->upload_dir['basedir'], $url ); 
    $file_path = str_replace( $baseurl, $this->upload_dir['basedir'], $url ); 
    
    // fix the slashes
    if(strpos($this->upload_dir['basedir'], '\\') !== false)
      $file_path = str_replace('/', '\\', $file_path);
    		
		//$this->write_log("url $url");
		//$this->write_log("baseurl "  . $this->upload_dir['baseurl']);
		//$this->write_log("basedir " . $this->upload_dir['basedir']);
		//$this->write_log("file_path $file_path");
				
		//first attempt failed; try again
		if((strpos($file_path, "http:") !== false) || (strpos($file_path, "https:") !== false)) {	
			$this->write_log("absolute path, second attempt $file_path");
			$baseurl = $this->upload_dir['baseurl'];
			$base_length = strlen($baseurl);
			//compare the two urls
			$url_stub = substr($url, 0, $base_length);
			if(strcmp($url_stub, $baseurl) === 0) {			
				$non_base_file = substr($url, $base_length);
				$file_path = $this->upload_dir['basedir'] . DIRECTORY_SEPARATOR . $non_base_file;			
			} else {
				$this->write_log("url_stub $url_stub");
				$this->write_log("baseurl $baseurl");
				$new_msg = "The URL to the folder or image is not correct: $url";
				$this->write_log($new_msg);
				echo $new_msg;
			}
		}
		    
    // are we on windows?
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
      $file_path = str_replace('/', '\\', $file_path);
    }
		
		//$this->write_log("file_path 2 $file_path");
				
    return $file_path;
  }
  
  public function is_windows() {
		global $is_IIS;
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      return true;
    else
      return false;      
  }
  
  public function get_file_url($path) {
    global $is_IIS;
    
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
      
      $base_url = $this->upload_dir['baseurl'];
      
      $position = strpos($path, $this->uploads_folder_name);
      $relative_path = substr($path, $position+$this->uploads_folder_name_length+1);
      $file_url = $base_url . '/' . $relative_path;
      $file_url = str_replace('\\', '/', $file_url);      
              
      // replace any slashes in the dir path when running windows
      //$base_upload_dir1 = $this->upload_dir['basedir'];
      //$base_upload_dir2 = str_replace('\\', '/', $base_upload_dir1);      
      //$file_url = str_replace( $base_upload_dir2, $base_url, $path ); 
    }
    else {
      $file_url = str_replace( $this->upload_dir['basedir'], $this->upload_dir['baseurl'], $path );          
    }
    return $file_url;    
  }
  
  public function get_file_url_for_copy($path) {
    global $is_IIS;
    
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
      
      $base_url = $this->upload_dir['baseurl'];
      
      // replace any slashes in the dir path when running windows
      $base_upload_dir1 = $this->upload_dir['basedir'];
      $base_upload_dir2 = str_replace('/','\\', $base_upload_dir1);      
      $file_url = str_replace( $base_upload_dir2, $base_url, $path ); 
      $file_url = str_replace('\\',   '/', $file_url);      
      
    }
    else {
      $file_url = str_replace( $this->upload_dir['basedir'], $this->upload_dir['baseurl'], $path );          
    }
    return $file_url;    
  
  }
  
  public function is_folder($id) {
    global $wpdb;
    
    $sql = "select post_type from {$wpdb->prefix}posts where ID = $id";

    $type = $wpdb->get_var($sql);
    
    if($type == MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE) {
      return true;
    } else {
      return false;
    } 
  }
  
  public function delete_maxgalleria_media() {
    global $wpdb, $is_IIS;
    $delete_ids = array();
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $folder_deleted = true;
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['serial_delete_ids'])) && (strlen(trim($_POST['serial_delete_ids'])) > 0)) {
      $delete_ids = trim(stripslashes(strip_tags($_POST['serial_delete_ids'])));
      $delete_ids = str_replace('"', '', $delete_ids);
		  //$this->write_log("delete_ids $delete_ids");
      $delete_ids = explode(",",$delete_ids);
    }  
    else
      $delete_ids = '';
		
    if ((isset($_POST['parent_id'])) && (strlen(trim($_POST['parent_id'])) > 0))
      $parent_folder = trim(stripslashes(strip_tags($_POST['parent_id'])));
		else
			$parent_folder = "0";
		    
		if(class_exists('MGMediaLibraryFoldersProS3') && 
			($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || 
       $this->s3_addon->license_status == S3_FILE_COUNT_WARNING || $this->s3_addon->license_status == S3_FILE_COUNT_EXCEDED)) {
			if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
			    $query_type = '2'; 
      } else {
			  $query_type = '1';
      }  
		} else {
			$query_type = '1';				
		}
    	
		if($query_type == '1')
        $formatted_query = "select post_title, post_type, pm.meta_value as attached_file 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where ID = %s 
AND pm.meta_key = '_wp_attached_file'";
      else  
			$formatted_query = "select post_title, post_type, pm.meta_value as attached_file, pm2.meta_value as file_path 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
LEFT JOIN {$wpdb->prefix}postmeta AS pm2 ON (pm2.post_id = {$wpdb->prefix}posts.ID)
where ID = %s 
AND pm.meta_key = '_wp_attached_file'
AND pm2.meta_key = '" . MLFP3_META . "'";
		            
    foreach( $delete_ids as $delete_id) {
      
      // prevent uploads folder from being deleted
      if(intval($delete_id) == intval($this->folder_id)) {
				$message = __('The uploads folder cannot be deleted.','maxgalleria-media-library');
				$data = array ('message' =>$message, 'refresh' => false );
        echo json_encode($data);
        die();
      }
			
			if(is_numeric($delete_id) && !empty($delete_id)) {
        
        if($this->is_folder($delete_id))
          $formatted_query = "select post_title, post_type, pm.meta_value as attached_file 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where ID = %s 
AND pm.meta_key = '_wp_attached_file'";
				
				$sql = $wpdb->prepare($formatted_query, $delete_id);

				$row = $wpdb->get_row($sql);
        
        if($row) {
        
          $baseurl = $this->upload_dir['baseurl'];
          $baseurl = rtrim($baseurl, '/') . '/';
          $image_location = $baseurl . ltrim($row->attached_file, '/');

          $folder_path = $this->get_absolute_path($image_location);
          $del_post = array('post_id' => $delete_id);                        

          do_action(MLFP_BEFORE_FILE_OR_FOLDER_DELETE, $delete_id, $folder_path, $row->post_type);        

          if($row->post_type === MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE) { //folder
            $this->mlfp_delete_single_folder($delete_id, $folder_path, $del_post, $parent_folder, $row, $image_location);            
          } else {
            
            $metadata = wp_get_attachment_metadata($delete_id);            

            if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
              $location = $this->s3_addon->get_location($row->file_path, $this->uploads_folder_name);

//              if($this->enable_cloud_sync) {
//                if($this->s3_addon->sync_cloud_changes == 'on') {    
//                  $basename = wp_basename($row->file_path);
//                  $request_filename = $this->s3_addon->get_base_file($basename);
//                  $this->s3_addon->insert_task_log("delete", $request_filename);    
//                }
//              }

              $this->s3_addon->remove_from_s3($row->post_type, $location);

              //$metadata = wp_get_attachment_metadata($delete_id);

              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $thumbnail) {
                  $thumbnail_location = $this->s3_addon->get_thumbnail_location($thumbnail['file'], $location);
                  $this->s3_addon->remove_from_s3($row->post_type, $thumbnail_location);
                }
              }

            }

            if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3)
              remove_action( 'delete_attachment', array($this,'delete_folder_attachment'));
            
            $attached_file = get_post_meta($delete_id, '_wp_attached_file', true);
            //$metadata = wp_get_attachment_metadata($delete_id);                               
            $baseurl = $this->upload_dir['baseurl'];
            $baseurl = rtrim($baseurl, '/') . '/';
            $image_location = $baseurl . ltrim($row->attached_file, '/');
            $image_path = $this->get_absolute_path($image_location);
            $path_to_thumbnails = pathinfo($image_path, PATHINFO_DIRNAME);                      
            
            if( wp_delete_attachment( $delete_id, true ) !== false ) {
              $wpdb->delete( $table, $del_post );						
              $message = __('The file(s) were deleted','maxgalleria-media-library') . PHP_EOL;						
              
              //error_log("image_path $image_path");            
              if(file_exists($image_path))
                unlink($image_path);
              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $source_path) {
                  $thumbnail_file = $path_to_thumbnails . DIRECTORY_SEPARATOR . $source_path['file'];
                  //error_log($thumbnail_file);
                  if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                    $thumbnail_file = str_replace('/', '\\', $thumbnail_file);

                  if(file_exists($thumbnail_file))
                    unlink($thumbnail_file);
                }  
              }  
                        
              do_action(MLFP_AFTER_FILE_OR_FOLDER_DELETE, $delete_id, $folder_path, $row->post_type);        

            } else {
              $message = __('The file(s) were not deleted','maxgalleria-media-library') . PHP_EOL;
            } 
            
            if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3)
	            add_action('delete_attachment', array($this, 'delete_folder_attachment'));		
          }
        } else {
          // record not found, delete the attachment
          $del_post = array('post_id' => $delete_id);                        
          
          if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3)
            remove_action( 'delete_attachment', array($this,'mlfps3_delete_attachment'));
          
          if( wp_delete_attachment( $delete_id, true ) !== false ) {
            $wpdb->delete( $table, $del_post );						
            $message = __('The file(s) were deleted','maxgalleria-media-library') . PHP_EOL;						
          } else {
            $message = __('The file(s) were not deleted','maxgalleria-media-library') . PHP_EOL;
          }           
          
          if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3)
            add_action('delete_attachment', array($this, 'mlfps3_delete_attachment'));		
        }
			}
    }

		$files = $this->display_folder_contents ($parent_folder, true, "", false);
		$refresh = true;
		$data = array ('message' => $message, 'files' => $files, 'refresh' => $refresh );
		echo json_encode($data);						
    die();
  }
  
  public function mlfp_delete_single_folder($delete_id, $folder_path, $del_post, $parent_folder, $row, $image_location, $check_contents = true) {
    
    global $wpdb, $is_IIS;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $folder_deleted = true;
    $message = "";

    if($check_contents) {
      $sql = "SELECT COUNT(*) FROM $wpdb->prefix" . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE . " where folder_id = $delete_id";
      $row_count = $wpdb->get_var($sql);
      //error_log($sql);

      if($row_count > 0) {
        $message = __('The folder, ','maxgalleria-media-library'). $row->post_title . __(', is not empty. Please delete or move files from the folder','maxgalleria-media-library') . PHP_EOL;      

        $data = array ('message' =>$message, 'refresh' => false );
        echo json_encode($data);

        die();
      }  
    }  

      //$parent_folder =  $this->get_parent($delete_id);					

      if(file_exists($folder_path)) {
        if(is_dir($folder_path)) {  //folder
          @chmod($folder_path, 0777);
          $this->remove_hidden_files($folder_path);
          if($this->is_dir_empty($folder_path)) {
            if(!rmdir($folder_path)) {
              $message = __('The folder could not be deleted.','maxgalleria-media-library');
            }  
          } else {
            $message = __('The folder is not empty and could not be deleted.','maxgalleria-media-library');
            $folder_deleted = false;                                  
          }         
        }          
      }                          
      
      //if($this->wpmf_integration == 'on') {
      //  $this->mlfp_remove_lookup_table($delete_id);
      //}

      if(class_exists('MGMediaLibraryFoldersProS3') && 
        ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || 
         $this->s3_addon->license_status == S3_FILE_COUNT_WARNING || $this->s3_addon->license_status == S3_FILE_COUNT_EXCEDED)) {
        if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
          $location = $this->s3_addon->get_location($image_location, $this->uploads_folder_name);
          //error_log("delete location: $location");
          $this->s3_addon->remove_from_s3("", $location);
        }
      }  

      if($folder_deleted) {
        if($this->wpmf_integration == 'on') {
          $term_id = $this->mlfp_get_term_id($delete_id);
          wp_delete_term($term_id, WPMF_TAXO);
        }
                
        wp_delete_post($delete_id, true);
        $wpdb->delete( $table, $del_post );
                
        $message = __('The folder was deleted.','maxgalleria-media-library');
        

        do_action(MLFP_AFTER_FILE_OR_FOLDER_DELETE, $delete_id, $folder_path, $row->post_type);        
      }  

      //$this->display_folder_contents ($parent_folder);
      //$this->display_folder_nav($parent_folder, $folder_table);

      //$folders = $this->get_folder_data($parent_folder); redundant!

      $folders = $this->get_folder_data($parent_folder);
      $data = array ('message' =>$message, 'folders' => $folders, 'refresh' => $folder_deleted );
      if($check_contents)
        echo json_encode($data);

      die();
        
  }
  
  public function remove_hidden_files($directory) {
    $files = array_diff(scandir($directory), array('.','..'));
    foreach ($files as $file) {
      unlink("$directory/$file");
    }    
  }
        
  public function is_dir_empty($directory) {
    $filehandle = opendir($directory);
    while (false !== ($entry = readdir($filehandle))) {
      if ($entry != "." && $entry != ".." && $entry != ".DS_Store") {
        closedir($filehandle);
        return false;
      }
    }
    closedir($filehandle);
    return true;
  }  
      
  public function generate_s3_url($bucket_address, $local_url) {
    $position = strpos($local_url, "wp-content");
    $s3_address = $bucket_address . substr($local_url, $position);
    return $s3_address;
  }
	  
  public function get_image_sizes() {
    global $_wp_additional_image_sizes;
    $sizes = array();
    $rSizes = array();
    foreach (get_intermediate_image_sizes() as $s) {
      $sizes[$s] = array(0, 0);
      if (in_array($s, array('thumbnail', 'medium', 'large'))) {
        $sizes[$s][0] = get_option($s . '_size_w');
        $sizes[$s][1] = get_option($s . '_size_h');
      } else {
        if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s]))
          $sizes[$s] = array($_wp_additional_image_sizes[$s]['width'], $_wp_additional_image_sizes[$s]['height'],);
      }
    }
		
		foreach ($sizes as $size => $atts) {
			$rSizes[] = implode('x', $atts);
		}

    return $rSizes;
  }  
    
  public function add_to_max_gallery () {
    
    global $wpdb, $maxgalleria;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['serial_gallery_image_ids'])) && (strlen(trim($_POST['serial_gallery_image_ids'])) > 0))
      $serial_gallery_image_ids = trim(stripslashes(strip_tags($_POST['serial_gallery_image_ids'])));
    else
      $serial_gallery_image_ids = "";
    
    $serial_gallery_image_ids = str_replace('"', '', $serial_gallery_image_ids);    
    
    $serial_gallery_image_ids = explode(',', $serial_gallery_image_ids);
        
    if ((isset($_POST['gallery_id'])) && (strlen(trim($_POST['gallery_id'])) > 0))
      $gallery_id = trim(stripslashes(strip_tags($_POST['gallery_id'])));
    else
      $gallery_id = 0;
    
    foreach( $serial_gallery_image_ids as $attachment_id) {
      
      $result = $this->add_image_to_mg_gallery($attachment_id, $gallery_id);
                     
    }// foreach
        
    echo __('The images were added.','maxgalleria-media-library') . PHP_EOL;              
        
    die();
    
  }
  
  public function add_image_to_mg_gallery($attachment_id, $gallery_id) {
    
    global $wpdb, $maxgalleria;

    $result = $attachment_id;

    // check for image already in the gallery
    $sql = "SELECT ID FROM $wpdb->prefix" . "posts where post_parent = $gallery_id and post_type = 'attachment' and ID = $attachment_id";

    $row = $wpdb->get_row($sql);

    if($row === null) {

      $menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);      

      $attachment = get_post( $attachment_id, ARRAY_A );

      // assign a new value for menu_order
      //$menu_order = $maxgalleria->common->get_next_menu_order($gallery_id);
      $attachment[ 'menu_order' ] = $menu_order;

      do_action(MLFP_BEFORE_ADD_TO_MAXGALLERIA, $attachment_id, $attachment, $gallery_id, $menu_order);

      //If the attachment doesn't have a post parent, simply change it to the attachment we're working with and be done with it      
      // assign a new value for menu_order
      if( empty( $attachment[ 'post_parent' ] ) ) {
        wp_update_post(
          array(
            'ID' => $attachment[ 'ID' ],
            'post_parent' => $gallery_id,
            'menu_order' => $menu_order
          )
        );
        $result = $attachment[ 'ID' ];
      } else {
        //Else, unset the attachment ID, change the post parent and insert a new attachment
        unset( $attachment[ 'ID' ] );
        $attachment[ 'post_parent' ] = $gallery_id;
        $new_attachment_id = wp_insert_post( $attachment );
        //$new_attachment_id = $this->mpmlp_insert_post( $attachment );

        //Now, duplicate all the custom fields. (There's probably a better way to do this)
        $custom_fields = get_post_custom( $attachment_id );

        foreach( $custom_fields as $key => $value ) {
          //The attachment metadata wasn't duplicating correctly so we do that below instead
          if( $key != '_wp_attachment_metadata' )
            update_post_meta( $new_attachment_id, $key, $value[0] );
        }

        //Carry over the attachment metadata
        $data = wp_get_attachment_metadata( $attachment_id );
        wp_update_attachment_metadata( $new_attachment_id, $data );

        $result = $new_attachment_id;

        do_action(MLFP_AFTER_ADD_TO_MAXGALLERIA, $result, $attachment, $gallery_id, $menu_order);
      }
    }
          
    return $result;
          
  }

  public function search_media () {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['search_value'])) && (strlen(trim($_POST['search_value'])) > 0))
      $search_value = trim(stripslashes(strip_tags($_POST['search_value'])));
    else
      $search_value = "";
    
	$sql = $wpdb->prepare("select ID, post_title, post_name, pm.meta_value as attached_file from {$wpdb->prefix}posts 
			LEFT JOIN {$wpdb->prefix}mgmlp_folders ON( {$wpdb->prefix}posts.ID = {$wpdb->prefix}mgmlp_folders.post_id) 
      LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID)
      where post_type= 'attachment' and pm.meta_key = '_wp_attached_file' and post_title like '%%%s%%'", $search_value);
    
    $rows = $wpdb->get_results($sql);
    
    if($rows) {
        foreach($rows as $row) {
          $thumbnail = wp_get_attachment_thumb_url($row->ID);
          if($thumbnail !== false)
            $ext = pathinfo($thumbnail, PATHINFO_EXTENSION);
          else {
						
            //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
						$baseurl = $this->upload_dir['baseurl'];
						$baseurl = rtrim($baseurl, '/') . '/';
						$image_location = $baseurl . ltrim($row->attached_file, '/');
												
            $ext_pos = strrpos($image_location, '.');
            $ext = substr($image_location, $ext_pos+1);
            $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file.jpg";
          }

          $class = "media-attachment"; 
          echo "<li>" . PHP_EOL;
          echo "   <a class='$class' href='" . site_url() . "/wp-admin/upload.php?item=" . $row->ID . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          echo "   <div class='attachment-name'>$row->post_title.$ext</div>" . PHP_EOL;
          echo "</li>" . PHP_EOL;              
        }      
      
    }
    else {
      echo __('No files were found matching that name.','maxgalleria-media-library') . PHP_EOL;                      
    }
    
    die();    
  }
  
  public function search_library() {
    
    global $wp_query;
    
    if ((isset($_GET['display'])) && (strlen(trim($_GET['display'])) > 0)) {
        $display_type = trim(stripslashes(strip_tags($_GET['display'])));
      if($display_type == 'grid') {
        $this->search_library_grid();
      } else {
        $this->search_library_list();
      }
    }
    
    
  }  
  
  public function search_library_grid() {
    
    global $wpdb;
    
    if ((isset($_GET['s'])) && (strlen(trim($_GET['s'])) > 0))
      $search_string = trim(sanitize_text_field($_GET['s']));
    else
      $search_string = '';
        
    echo '<div id="wp-media-grid" class="wrap">' . PHP_EOL;
    //empty h2 for where WP notices will appear
    echo '  <h2></h2>' . PHP_EOL;
//    echo '  <div class="media-plus-toolbar wp-filter"><div class="media-toolbar-secondary">' . PHP_EOL;
    echo '  <div class="media-plus-toolbar wp-filter">' . PHP_EOL;
    echo '<div id="mgmlp-title-area">' . PHP_EOL;
		echo '  <h2 class="mgmlp-title">'. __('Media Library Folders Pro Search Results','maxgalleria-media-library') . '</h2>' . PHP_EOL;
    echo '  <div>' . PHP_EOL;
    echo '    <p><a href="' . site_url() . '/wp-admin/admin.php?page=mlfp-folders">Back to Media Library Folders Pro</a></p>' . PHP_EOL;
    echo '    <p><input type="search" placeholder="Search" id="mgmlp-media-search-input" class="search" value="'.$search_string.'"> <a id="mlfp-media-search-2" class="gray-blue-link" >' .  __('Search','maxgalleria-media-library') . '</a></p>' . PHP_EOL;            
    echo '  </div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
		echo '<div style="clear:both;"></div>' . PHP_EOL;
    echo "<div id='search-instructions'>". __('Click on an image to go to its folder or a on folder to view its contents.','maxgalleria-media-library')."</div>";		
    if ((isset($_GET['s'])) && (strlen(trim($_GET['s'])) > 0)) {
      echo "<h4>" . __('Search results for: ','maxgalleria-media-library') . $search_string ."</h4>" . PHP_EOL;			
      
      echo '<ul class="mg-media-list search-results">' . PHP_EOL;
            
      $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
      //$sql = $wpdb->prepare("select ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file 
      //  from $wpdb->prefix" . "posts
      //  LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
      //  LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID)
      //  where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' and pm.meta_key = '_wp_attached_file'  and post_title like '%%%s%%'", $search_string);
        
      $sql = $wpdb->prepare("(select $wpdb->posts.ID, post_title, {$wpdb->prefix}mgmlp_folders.folder_id, pm.meta_value as attached_file, 'a' as item_type 
from $wpdb->posts
LEFT JOIN {$wpdb->prefix}mgmlp_folders ON($wpdb->posts.ID = {$wpdb->prefix}mgmlp_folders.post_id)
LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = $wpdb->posts.ID)
LEFT JOIN $wpdb->users AS us ON ($wpdb->posts.post_author = us.ID) 
where post_type = 'mgmlp_media_folder' and pm.meta_key = '_wp_attached_file' and SUBSTRING_INDEX(pm.meta_value, '/', -1) like '%%%s%%')
union all
(select $wpdb->posts.ID, post_title, {$wpdb->prefix}mgmlp_folders.folder_id, pm.meta_value as attached_file, 'b' as item_type
from $wpdb->posts 
LEFT JOIN {$wpdb->prefix}mgmlp_folders ON( $wpdb->posts.ID = {$wpdb->prefix}mgmlp_folders.post_id) 
LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = $wpdb->posts.ID) 
LEFT JOIN $wpdb->users AS us ON ($wpdb->posts.post_author = us.ID) 
where post_type = 'attachment' and pm.meta_key = '_wp_attached_file' and SUBSTRING_INDEX(pm.meta_value, '/', -1) like '%%%s%%') order by attached_file", $search_string, $search_string);
                      
      //error_log("grid " . $sql);  
        
      $rows = $wpdb->get_results($sql);

      if($rows) {
        foreach($rows as $row) {
          
          if($row->item_type == 'a')
            $class = "media-folder"; 
          else
            $class = "media-attachment";
          
          $thumbnail = wp_get_attachment_thumb_url($row->ID);
          if($thumbnail !== false)
            $ext = pathinfo($thumbnail, PATHINFO_EXTENSION);
          else {
						
            //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
						$baseurl = $this->upload_dir['baseurl'];
						$baseurl = rtrim($baseurl, '/') . '/';
						$image_location = $baseurl . ltrim($row->attached_file, '/');
												
            $ext_pos = strrpos($image_location, '.');
            $ext = substr($image_location, $ext_pos+1);
            if($row->item_type == 'b')
              $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file.jpg";
            else
              $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/folder.jpg";
          }
          
          echo "<li>" . PHP_EOL;
          if($row->item_type == 'a')
            echo "   <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=mlfp-folders&media-folder=" . $row->ID . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          else
            echo "   <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=mlfp-folders&media-folder=" . $row->folder_id . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
          echo "   <div class='attachment-name'>$row->post_title</div>" . PHP_EOL;
          echo "</li>" . PHP_EOL;              
          
        }
      }

      echo "</ul>" . PHP_EOL;
    }
    //echo '  </div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;    
    
    ?>
        
      <script>                        
      jQuery('#mgmlp-media-search-input').keydown(function (e){
        if(e.keyCode == 13){

          var search_value = jQuery('#mgmlp-media-search-input').val();
          
          var grid_list_switch = jQuery('#grid-list-switch-view').val();
          grid_list_switch = (grid_list_switch == 'on')? true : false;        
          

          var home_url = "<?php echo site_url(); ?>"; 

          window.location.href = home_url + '/wp-admin/admin.php?page=mlfp-search-library&display=grid&' + 's=' + search_value;

        }  
      })
            
      jQuery(document).on("click", "#mlfp-media-search-2", function () {
      
        var search_value = jQuery('#mgmlp-media-search-input').val();
        
        var grid_list_switch = jQuery('#grid-list-switch-view').val();
        grid_list_switch = (grid_list_switch == 'on')? true : false;        

        var home_url = "<?php echo site_url(); ?>"; 
        
        window.location.href = home_url + '/wp-admin/admin.php?page=mlfp-search-library&display=grid&' + 's=' + search_value;
      })    
      
      </script>          
    <?php
  }
  
  public function search_library_list() {
    
    global $wpdb;
    
    $found = false;
    
    $page_id = 0;
        
    echo '<div id="wp-media-grid" class="wrap">' . PHP_EOL;
    //empty h2 for where WP notices will appear
    echo '  <h2></h2>' . PHP_EOL;
//    echo '  <div class="media-plus-toolbar wp-filter"><div class="media-toolbar-secondary">' . PHP_EOL;
    echo '  <div class="media-plus-toolbar wp-filter">' . PHP_EOL;
    echo '<div id="mgmlp-title-area">' . PHP_EOL;
		echo '  <h2 class="mgmlp-title">'. __('Media Library Folders Pro Search Results','maxgalleria-media-library') . '</h2>' . PHP_EOL;
    echo '  <div>' . PHP_EOL;
    echo '    <p><a href="' . site_url() . '/wp-admin/admin.php?page=mlfp-folders">Back to Media Library Folders Pro</a></p>' . PHP_EOL;
    echo '    <p><input type="search" placeholder="Search" id="mgmlp-media-search-input" class="search"> <a id="mlfp-media-search-2" class="gray-blue-link" >' .  __('Search','maxgalleria-media-library') . '</a></p>' . PHP_EOL;            
    echo '  </div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
		echo '<div style="clear:both;"></div>' . PHP_EOL;
    
    echo "<style>
          
    ul.mg-media-list li {
      display: table-row;
      float: none;
      height: 80px;
      list-style: outside none none;
      margin: 0;
      max-width: none;
      overflow: visible;
      width: 100%;
    }
    
    .mg-media-list.search-results a.media-attachment img {
      height: 80px !important;
      width: 80px;
    }
    
    ul.mg-media-list li {
      height: auto;
    }
    
    ul.mg-media-list li a {
      background-color: #f0f0f0;
      display: block;
      width: 80px;
    }
    
    table#search-header tbody tr.row-item td.mlfp-list-image a.media-attachment {
      height: 72px;
    }
    
    li.row-item span.mlfp-list-image a.media-attachment.list.edit-link img {
      height: 80px !important;    
      width: 80px !important;
    }
           
    </style>";
    
    echo "<div id='search-instructions'>". __('Click on an image to go to its folder or a on folder to view its contents.','maxgalleria-media-library')."</div>";		
    if ((isset($_GET['s'])) && (strlen(trim($_GET['s'])) > 0)) {
      $search_string = trim(stripslashes(strip_tags($_GET['s'])));
      echo "<h4>" . __('Search results for: ','maxgalleria-media-library') . $search_string ."</h4>" . PHP_EOL;			
      
      $page_id = 0;
      
      $search_results = $this->get_search_results($search_string, $page_id);
                      
      if($search_results['found']) {
        
        echo '<table id="search-header">' . PHP_EOL;
        echo '  <thead>' . PHP_EOL;
        echo '    <tr>' . PHP_EOL;
        echo '      <td class="mlfp-list-image">&nbsp;</td>' . PHP_EOL;
        echo '      <td class="mlfp-list-title">'.__( 'Title', 'maxgalleria-media-library' ).'</td>' . PHP_EOL;
        echo '      <td class="mlfp-list-file">'.__( 'File', 'maxgalleria-media-library' ).'</td>' . PHP_EOL;
        echo '      <td class="mlfp-list-author">'.__( 'Author', 'maxgalleria-media-library' ).'</td>' . PHP_EOL;
        echo '      <td class="mlfp-list-cat">'.__( 'Categories', 'maxgalleria-media-library' ).'</td>' . PHP_EOL;
        echo '      <td class="mlfp-list-date">'.__( 'Date', 'maxgalleria-media-library' ).'</td>' . PHP_EOL;
        echo '    </tr>' . PHP_EOL;
        echo '  </thead>' . PHP_EOL;
        echo '  <tbody id="search-results">' . PHP_EOL;
        echo $search_results['html'];
        echo '  </tbody>' . PHP_EOL;
        echo '</table>' . PHP_EOL;      
              
        $previous_page = $page_id - 1;
        $next_page = $page_id + 1;
        echo "<div class='mlfp-page-nav'>" . PHP_EOL;
        //if($page_id > 0)	
        //  echo "<a id='mlfp-previous-list' page-id='$previous_page' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
        //else
        echo "<a id='mlfp-previous-list' page-id='$previous_page' data-search='$search_string' style='float:left;cursor:pointer;display:none'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
        if($page_id < $search_results['total_number_pages']-1 && $search_results['total_images'] > $search_results['items_per_page'])
          echo "<a id='mlfp-next-list' page-id='$next_page' data-search='$search_string' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
        echo "</div>" . PHP_EOL;
      }
      
      
    }
    //echo '  </div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;    
    
    ?>
        
    <script>                                
    jQuery(document).ready(function(){
      
      jQuery(document).on("click", "#mlfp-previous-list, #mlfp-next-list", function (e) {
        e.stopImmediatePropagation();
        jQuery("#ajaxloader").show(); 
        
        var page_id = jQuery(this).attr("page-id");    
        var search_string = jQuery(this).attr("data-search");
        console.log('page_id', page_id);
        
        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mlfp_search_list_page", page_id: page_id, search_string: search_string, nonce: mgmlp_ajax.nonce },
          url: mgmlp_ajax.ajaxurl,
          dataType: "json",
          success: function (data){ 
            jQuery("#search-results").html(data.html); 
            jQuery("#mlfp-previous-list").attr("page-id", data.page_id-1);
            jQuery("#mlfp-next-list").attr("page-id", data.page_id + 1);
            
            if((parseInt(data.page_id)) < 1)
              jQuery("#mlfp-previous-list").hide();
            else
              jQuery("#mlfp-previous-list").show(); 
            
            console.log('data.page_id',data.page_id,'data.total_number_pages',data.total_number_pages);
            console.log('data.total_images',data.total_images,'data.items_per_page',data.items_per_page);
            if((parseInt(data.page_id) < parseInt(data.total_number_pages) - 1) && (parseInt(data.total_images) > parseInt(data.items_per_page)))            
              jQuery("#mlfp-next-list").show();
            else
              jQuery("#mlfp-next-list").hide();                        
          },
            error: function (err)
          { alert(err.responseText)}
        });

      });  
              
      jQuery('#mgmlp-media-search-input').keydown(function (e){
        if(e.keyCode == 13){

          var search_value = jQuery('#mgmlp-media-search-input').val();
          
          var grid_list_switch = jQuery('#grid-list-switch-view').val();
          grid_list_switch = (grid_list_switch == 'on')? true : false;        

          var home_url = "<?php echo site_url(); ?>"; 

          //window.location.href = home_url + '/wp-admin/admin.php?page=mlfp-search-library&' + 's=' + search_value;
          window.location.href = home_url + '/wp-admin/admin.php?page=mlfp-search-library&display=list&' + 's=' + search_value;

        }  
      });
            
      jQuery(document).on("click", "#mlfp-media-search-2", function () {
      
        var search_value = jQuery('#mgmlp-media-search-input').val();
        
        var grid_list_switch = jQuery('#grid-list-switch-view').val();
        grid_list_switch = (grid_list_switch == 'on')? true : false;        

        var home_url = "<?php echo site_url(); ?>"; 
        
        window.location.href = home_url + '/wp-admin/admin.php?page=mlfp-search-library&display=list&' + 's=' + search_value;
      });
      
    });
    </script>          
    <?php
  }
    
  public function mlfp_search_list_page() {
     
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['search_string'])) && (strlen(trim($_POST['search_string'])) > 0))
      $search_string = trim(stripslashes(strip_tags($_POST['search_string'])));
    else
      $search_string = "";
    
    if ((isset($_POST['page_id'])) && (strlen(trim($_POST['page_id'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['page_id']))));
    else
      $page_id = "";

		echo json_encode($this->get_search_results($search_string, $page_id));
        
    die();
  }
  
  public function get_search_results($search_string, $page_id) {
    
    global $wpdb;
    
    $buffer = '';
    
    $pagination = '';
    
    $found = false;
    
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;

    $items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '20'));
    
    $offset = $page_id * $items_per_page;    

    $sql = $wpdb->prepare("(select SQL_CALC_FOUND_ROWS $wpdb->posts.ID, post_title, {$wpdb->prefix}mgmlp_folders.folder_id, pm.meta_value as attached_file, us.display_name, $wpdb->posts.post_date, 'a' as item_type 
from $wpdb->posts
LEFT JOIN {$wpdb->prefix}mgmlp_folders ON($wpdb->posts.ID = {$wpdb->prefix}mgmlp_folders.post_id)
LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = $wpdb->posts.ID)
LEFT JOIN $wpdb->users AS us ON ($wpdb->posts.post_author = us.ID) 
where post_type = 'mgmlp_media_folder' and pm.meta_key = '_wp_attached_file' and SUBSTRING_INDEX(pm.meta_value, '/', -1) like '%%%s%%')
union all
(select $wpdb->posts.ID, post_title, {$wpdb->prefix}mgmlp_folders.folder_id, pm.meta_value as attached_file, us.display_name, $wpdb->posts.post_date, 'b' as item_type
from $wpdb->posts 
LEFT JOIN {$wpdb->prefix}mgmlp_folders ON( $wpdb->posts.ID = {$wpdb->prefix}mgmlp_folders.post_id) 
LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = $wpdb->posts.ID) 
LEFT JOIN $wpdb->users AS us ON ($wpdb->posts.post_author = us.ID) 
where post_type = 'attachment' and pm.meta_key = '_wp_attached_file' and SUBSTRING_INDEX(pm.meta_value, '/', -1) like '%%%s%%') order by item_type, attached_file limit %d, %d", $search_string, $search_string, $offset, $items_per_page);
        
    //error_log("list " . $sql);
    
    $rows = $wpdb->get_results($sql);

    $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
    $total_images = $count['FOUND_ROWS()'];
    if($items_per_page != 0)
      $total_number_pages = ceil($total_images / $items_per_page);
    else
      $total_number_pages = 0;

    $counter = 0;
    if($rows) {

      $found = true;
      foreach($rows as $row) {

        if($row->item_type == 'a') {
          $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/folder.jpg";
          $filename = '';
        } else {
          $baseurl = $this->upload_dir['baseurl'];
          $baseurl = rtrim($baseurl, '/') . '/';
          $image_location = $baseurl . ltrim($row->attached_file, '/');

          $thumbnail = wp_get_attachment_thumb_url($row->ID);
          if($thumbnail !== false)
            $ext = pathinfo($thumbnail, PATHINFO_EXTENSION);
          else {												
            $ext_pos = strrpos($image_location, '.');
            $ext = substr($image_location, $ext_pos+1);
            $thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file.jpg";
          }

          $filename =  pathinfo($image_location, PATHINFO_BASENAME);

        }

        $class = "media-attachment"; 

        $baseurl = $this->upload_dir['baseurl'];
        $baseurl = rtrim($baseurl, '/') . '/';
        $image_location = $baseurl . ltrim($row->attached_file, '/');

        $filename =  pathinfo($image_location, PATHINFO_BASENAME);

        if($counter % 2)
          $buffer .= "<tr class='row-item gray-row'>" . PHP_EOL;
        else
          $buffer .= "<tr class='row-item' >" . PHP_EOL;
        $buffer .= "  <td class='mlfp-list-image'>" . PHP_EOL;
        if($row->item_type == 'a') 
          $buffer .= "    <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=mlfp-folders&media-folder=" . $row->ID . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
        else  
          $buffer .= "    <a class='$class' href='" . site_url() . "/wp-admin/admin.php?page=mlfp-folders&media-folder=" . $row->folder_id . "'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
        $buffer .= "  </td>" . PHP_EOL;
        $buffer .= "  <td class='mlfp-list-title'>$row->post_title</td>" . PHP_EOL;
        $buffer .= "  <td class='mlfp-list-file'>$filename</td>" . PHP_EOL;
        $buffer .= "  <td class='mlfp-list-author'>$row->display_name</td>" . PHP_EOL;
        if($row->item_type == 'a') 
          $buffer .= "  <td class='mlfp-list-cat'></td>" . PHP_EOL;
        else  
          $buffer .= "  <td class='mlfp-list-cat'>".$this->get_media_categories($row->ID)."</td>" . PHP_EOL;
        $buffer .= "  <td class='mlfp-list-date'>$row->post_date</td>" . PHP_EOL;
        $buffer .= "</tr>" . PHP_EOL;              
        $counter++;
      }      

    }
    else {
      $buffer .= __('No files were found matching that name.','maxgalleria-media-library') . PHP_EOL;                      
    }
    
    if($found) {
      $previous_page = $page_id - 1;
      $next_page = $page_id + 1;
      $pagination .= "<div class='mlfp-page-nav'>" . PHP_EOL;
      if($page_id > 0)	
        $pagination .= "<a id='mlfp-previous-list' page-id='$previous_page' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
      if($page_id < $total_number_pages-1 && $total_images > $items_per_page)
        $pagination .= "<a id='mlfp-next-list' page-id='$next_page' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
      $pagination .= "</div>" . PHP_EOL;
    }
    
		return array('html' => $buffer, 'pagination' => $pagination, 'found' => $found, 'page_id' => $page_id, 'total_number_pages' => $total_number_pages, 'total_images' => $total_images, 'items_per_page' => $items_per_page );
    
        
  }    
    	
  public function maxgalleria_rename_image() {
    
    global $wpdb, $blog_id, $is_IIS;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $file_id = trim(stripslashes(strip_tags($_POST['image_id'])));
    else
      $file_id = "";
    
    if ((isset($_POST['new_file_name'])) && (strlen(trim($_POST['new_file_name'])) > 0))
      $new_file_name = trim(stripslashes(strip_tags($_POST['new_file_name'])));
    else
      $new_file_name = "";
    
    if($new_file_name === '') {
      echo "Invalid file name.";
      die();
    }
    
    //$new_file_name = strtolower($new_file_name);
    if(preg_match('^[\w,\s\-_]+\.[A-Za-z]{3}$^', $new_file_name)) {
      echo "Invalid file name.";
      die();      
    }
          
    if (preg_match("/\\s/", $new_file_name)) {
      echo "The file name cannot contain spaces or tabs.";
      die();            
    }
          
		$new_file_name = sanitize_file_name($new_file_name);
    
		if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
      
			// get the file info

      $sql = $wpdb->prepare("select p.ID, p.post_title, p.post_name, pm.meta_value as file_path, pm2.meta_value as metadata, pm3.meta_value as attached_file
from {$wpdb->prefix}posts as p
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = p.ID and pm.meta_key = '" . MLFP3_META . "' )
LEFT JOIN {$wpdb->prefix}postmeta AS pm2 ON (pm2.post_id = p.ID and pm2.meta_key = '_wp_attachment_metadata' )
LEFT JOIN {$wpdb->prefix}postmeta AS pm3 ON (pm3.post_id = p.ID and pm3.meta_key = '_wp_attached_file' )
WHERE p.ID = %s", $file_id);

			//error_log($sql);
									
			$row = $wpdb->get_row($sql);
			if($row) {
								
				// get its url and path
				$image_location = $this->build_location_url($row->attached_file);
				$image_location_path = $this->get_absolute_path($image_location);
        
        $alt_text = get_post_meta($file_id, '_wp_attachment_image_alt', true);
							
				if(is_multisite()) {
					$url_slug = "site" . $blog_id . "/";
					$location= $this->s3_addon->s3_uploads_folder_location . str_replace($url_slug, "", $row->attached_file);
					//$location = $this->uploads_folder_name . "/" . str_replace($url_slug, "", $row->attached_file);
 				} else {
					$location = $this->s3_addon->s3_uploads_folder_location . $row->attached_file;								
					//$location = $this->uploads_folder_name . "/" . $row->attached_file;								
				}
				
				// verify the new file name
				$folder_id =  $this->get_parent($file_id);
				$full_new_file_name = $new_file_name . '.' . pathinfo($image_location, PATHINFO_EXTENSION);
								
				$new_file_name = $this->s3_addon->mlfp_unique_filename( $folder_id, $full_new_file_name );
				
				// get the file's folder and path
        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
          $location = str_replace('\\', '/', $location);
				$position = strrpos($location, "/");
        
				$location_folder = substr($location, 0, $position+1);
								
				//the file destination
				$destination_file = $location_folder . $new_file_name;
								
				// remove uploads folder to get the relative path
				$position = strpos($destination_file, $this->uploads_folder_name);
				$relative_path = substr($destination_file, $position + $this->uploads_folder_name_length+1);
								
				$position = strpos($this->s3_addon->uploadsurl, $this->uploads_folder_name);
				$uploads_url = substr($this->s3_addon->uploadsurl, 0, $position + $this->uploads_folder_name_length+1);
				
				// this is the new file's URL
				$destination_url = $uploads_url . $relative_path;
				$destination_path = $this->get_absolute_path($destination_url);
				
				// where to upload the files
				$destination_location = $this->s3_addon->get_destination_location($location);
                
        $destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);        
        
				//get the file to rename
			  $download_result = $this->s3_addon->download_file_from_s3($location, $destination_path);
								
				// remove files with the old file name
				if($download_result['statusCode'] == '200')	{
				  $result = $this->s3_addon->remove_from_s3("attachment", $location);
								
					$metadata = unserialize($row->metadata);				

					if(isset($metadata['sizes'])){
						foreach($metadata['sizes'] as $thumbnail) {
							$this->s3_addon->remove_from_s3("attachment", $destination_location . "/" . $thumbnail['file']);
						}
					}
				}		
												
				// update posts table
				$table = $wpdb->prefix . "posts";
				$data = array('guid' => $destination_url );
				$where = array('ID' => $file_id);
				$wpdb->update( $table, $data, $where);
				
				update_post_meta($file_id, '_wp_attached_file', $relative_path);
        if(strlen(trim($alt_text)) > 0) {
          if(!is_array($default_alt))
            update_post_meta( $file_id, '_wp_attachment_image_alt', $alt_text );
          else
            update_post_meta( $file_id, '_wp_attachment_image_alt', $alt_text[0] );
        }  
        
				//generate the new meta data, upload all the files, remove files from local server
        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
				  $attach_data = wp_generate_attachment_metadata( $file_id, addslashes($destination_path));
        else
				  $attach_data = wp_generate_attachment_metadata( $file_id, $destination_path );
				wp_update_attachment_metadata( $file_id,  $attach_data );
        
				$upload_result= $this->s3_addon->upload_to_s3("attachment", $destination_file, $destination_path, $file_id);
        
        if(isset($attach_data['sizes'])) {
          foreach($attach_data['sizes'] as $thumbnail) {
            $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
            $upload_result = $this->s3_addon->upload_to_s3("attachment", $destination_location . "/" . $thumbnail['file'], $source_file, 0);
          }

          if($this->s3_addon->remove_from_local) {
            $this->s3_addon->remove_media_file($destination_path);										
            foreach($attach_data['sizes'] as $thumbnail) {
              $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
              $this->s3_addon->remove_media_file($source_file);
            }
          }
        }
			}
		} else {
			
    $sql = $wpdb->prepare("select ID, pm.meta_value as attached_file, post_title, post_name 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where ID = %s
AND pm.meta_key = '_wp_attached_file'", $file_id);
			
			$row = $wpdb->get_row($sql);
			if($row) {

			  $image_location = $this->build_location_url($row->attached_file);
        
        $alt_text = get_post_meta($file_id, '_wp_attachment_image_alt', true);        

				$full_new_file_name = $new_file_name . '.' . pathinfo($image_location, PATHINFO_EXTENSION);
				$destination_path = $this->get_absolute_path(pathinfo($image_location, PATHINFO_DIRNAME));

				$new_file_name = wp_unique_filename( $destination_path, $full_new_file_name, null );
        
        $new_file_title = $this->remove_extension($new_file_name);

				$old_file_path = $this->get_absolute_path($image_location);

				$new_file_url = pathinfo($image_location, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $new_file_name;

				if(is_multisite()) {
					$url_slug = "site" . $blog_id . "/";
					$new_file_url = str_replace($url_slug, "", $new_file_url);
				}

				$new_file_path = $this->get_absolute_path($new_file_url);

				if($this->is_windows()) {
					$old_file_path = str_replace('\\', '/', $old_file_path);      
					$new_file_path = str_replace('\\', '/', $new_file_path);      
				}

				$rename_image_location = $this->get_base_file($image_location);
				$rename_destination = $this->get_base_file($new_file_url);			
                
        do_action(MLFP_BEFORE_FILE_RENAME, $old_file_path, $new_file_path);        
        
        $position = strrpos($image_location, '.');

        $image_location_no_extension = substr($image_location, 0, $position);
        
				if(rename($old_file_path, $new_file_path )) {

          // not sure if this line is needed
					//$old_file_path = str_replace('.', '*.', $old_file_path );          
          
          $metadata = wp_get_attachment_metadata($file_id);                               
          $path_to_thumbnails = pathinfo($old_file_path, PATHINFO_DIRNAME);

          foreach($metadata['sizes'] as $source_path) {
            $thumbnail_file = $path_to_thumbnails . DIRECTORY_SEPARATOR . $source_path['file'];
            
            if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
              $thumbnail_file = str_replace('/', '\\', $thumbnail_file);
            
		        if(file_exists($thumbnail_file))
              unlink($thumbnail_file);
          }  

					$table = $wpdb->prefix . "posts";
					$data = array('guid' => $new_file_url, 
												'post_title' => $new_file_title,
												'post_name' => $new_file_name                
									);
					$where = array('ID' => $file_id);
					$wpdb->update( $table, $data, $where);

					$table = $wpdb->prefix . "postmeta";
					$where = array('post_id' => $file_id);
					$wpdb->delete($table, $where);

					// get the uploads dir name
					$basedir = $this->upload_dir['baseurl'];
					$uploads_dir_name_pos = strrpos($basedir, '/');
					$uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);

					//find the name and cut off the part with the uploads path
					$string_position = strpos($new_file_url, $uploads_dir_name);
					$uploads_dir_length = strlen($uploads_dir_name) + 1;
					$uploads_location = substr($new_file_url, $string_position+$uploads_dir_length);
					if($this->is_windows()) 
						$uploads_location = str_replace('\\','/', $uploads_location);      

					$uploads_location = ltrim($uploads_location, '/');
					update_post_meta( $file_id, '_wp_attached_file', $uploads_location );
          
          if(strlen(trim($alt_text)) > 0) {
            if(!is_array($alt_text))
              update_post_meta( $file_id, '_wp_attachment_image_alt', $alt_text );
            else
              update_post_meta( $file_id, '_wp_attachment_image_alt', $alt_text[0] );
          }
          
					$attach_data = wp_generate_attachment_metadata( $file_id, $new_file_path );
					wp_update_attachment_metadata( $file_id, $attach_data );
          
          if(class_exists( 'SiteOrigin_Panels')) {                  
            $this->update_serial_postmeta_records($rename_image_location, $rename_destination);                  
          }
          
          // update postmeta records for beaver builder
          if(class_exists( 'FLBuilderLoader')) {

            $sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%$rename_image_location%'";
            //error_log($sql);

            $records = $wpdb->get_results($sql);
            foreach($records as $record) {

              $this->update_bb_postmeta($record->ID, $rename_image_location, $rename_destination);

            }
            // clearing BB caches
            if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_asset_cache_for_all_posts' ) ) {
              FLBuilderModel::delete_asset_cache_for_all_posts();
            }
            
            if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_all_asset_cache' ) ) {
              FLBuilderModel::delete_all_asset_cache( $record->ID );
            }  
            
            if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
              FLCustomizer::clear_all_css_cache();
            }
            wp_cache_flush();

          }

					//$replace_sql = "UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE (`post_content`, '$rename_image_location', '$rename_destination');";          
					//$result = $wpdb->query($replace_sql);
          
          //$replace_sql = str_replace ( '/', '\\/', $replace_sql);
          //$result = $wpdb->query($replace_sql);
           
          $this->update_links($rename_image_location, $rename_destination);
                    
          // for updating wp pagebuilder
          if(defined('WPPB_LICENSE')) {
            $this->update_wppb_data($image_location_no_extension, $new_file_url);          
          }
                  
          // for updating themify images
          if(function_exists('themify_builder_activate')) {
            $this->update_themify_data($image_location_no_extension, $new_file_url);
          }
                    
          // for updating elementor background images
          if(is_plugin_active("elementor/elementor.php")) {
            $this->update_elementor_data($file_id, $image_location_no_extension, $new_file_url);          
          }
                              
          do_action(MLFP_AFTER_FILE_RENAME, $old_file_path, $new_file_path);        
          
					//echo "<script>window.location.reload(true);</script>";
					echo __('Updating attachment links, please wait...The file was renamed','maxgalleria-media-library');
				}
			}	
    }
    
    die();
  }
	
	public function build_location_url($attached_file) {					
		return rtrim($this->upload_dir['baseurl'], '/') . '/' . ltrim($attached_file, '/');
	}					
  
  // saves the sort selection
  public function sort_contents() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['sort_order'])) && (strlen(trim($_POST['sort_order'])) > 0))
      $sort_order = trim(stripslashes(strip_tags($_POST['sort_order'])));
    else
      $sort_order = "0";
		
    if ((isset($_POST['folder'])) && (strlen(trim($_POST['folder'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['folder'])));
    else
      $current_folder_id = "";
		    
    update_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER, $sort_order );  
    
    switch ($sort_order) {
      case '0':
      $msg = __('Sorting by date.','maxgalleria-media-library');
      break;  
    
      case '1':
      $msg = __('Sorting by name.','maxgalleria-media-library');
      break;        
    }
    
    //echo $msg;
		
		if($current_folder_id != "") {		
		  $this->display_folder_contents ($current_folder_id, true);
		}
		            
    die();
  }
	
  public function mlf_change_sort_type() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['sort_type'])) && (strlen(trim($_POST['sort_type'])) > 0))
      $sort_type = trim(sanitize_text_field($_POST['sort_type']));
    else
      $sort_type = "ASC";
    
    if ((isset($_POST['folder'])) && (strlen(trim($_POST['folder'])) > 0))
      $current_folder_id = trim(sanitize_text_field($_POST['folder']));
    else
      $current_folder_id = "";
		        
    update_option( MAXGALLERIA_MLF_SORT_TYPE, $sort_type );  
        
		if($current_folder_id != "") {		
		  $this->display_folder_contents ($current_folder_id, true);
		}
                    
    die();
  }
	
	public function sort_categories() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['sort_order'])) && (strlen(trim($_POST['sort_order'])) > 0))
      $sort_order = trim(stripslashes(strip_tags($_POST['sort_order'])));
    else
      $sort_order = "0";
    
		if ((isset($_POST['grid_list_switch'])) && (strlen(trim($_POST['grid_list_switch'])) > 0))
      $grid_list_switch = trim(stripslashes(strip_tags($_POST['grid_list_switch'])));
    else
      $grid_list_switch = "";
        				
    update_option( MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER, $sort_order );  
    
    if($grid_list_switch == 'true')				
      $this->mlp_load_categories();
    else
      $this->mlp_load_categories_list();
		            
    die();
  }

	
	public function mgmlp_move_copy(){

    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['move_copy_switch'])) && (strlen(trim($_POST['move_copy_switch'])) > 0))
      $move_copy_switch = trim(stripslashes(strip_tags($_POST['move_copy_switch'])));
    else
      $move_copy_switch = 'on';
				    
    update_option( MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY, $move_copy_switch );  
		
		die();
		
	}
  
//  public function grid_list_switch() {
//    
//    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
//      exit(__('missing nonce!','maxgalleria-media-library'));
//    } 
//    
//    if ((isset($_POST['move_copy_switch'])) && (strlen(trim($_POST['move_copy_switch'])) > 0))
//      $move_copy_switch = trim(stripslashes(strip_tags($_POST['move_copy_switch'])));
//    else
//      $move_copy_switch = 'on';
//				    
//    update_option( MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY, $move_copy_switch );  
//		
//		die();
//
//  }
  
  public function mgmlp_grid_list() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['grid_list_switch'])) && (strlen(trim($_POST['grid_list_switch'])) > 0))
      $grid_list_switch = trim(stripslashes(strip_tags($_POST['grid_list_switch'])));
    else
      $grid_list_switch = 'off';
				    
    update_user_meta(get_current_user_id(), MAXGALLERIA_MEDIA_LIBRARY_GRID_OR_LIST, $grid_list_switch);
    
		die();
    
  }
    
  public function run_on_deactivate() {
    wp_clear_scheduled_hook('new_folder_check');
  }
  
  public function mlf_check_for_new_folders(){
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['parent_folder'])) && (strlen(trim($_POST['parent_folder'])) > 0))
      $parent_folder_id = trim(sanitize_text_field($_POST['parent_folder']));
    
    //error_log("parent_folder_id $parent_folder_id");
            
    $message = $this->admin_check_for_new_folders(true);
    
    $folders = $this->get_folder_data($parent_folder_id);
    $data = array ('message' => esc_html($message), 'folders' => $folders, 'refresh' => true );
    echo json_encode($data);
        
    die();
    
  }
  
  public function admin_check_for_new_folders($noecho = null) {
        
		global $blog_id, $is_IIS;
		$skip_path = "";
    //$uploads_path = wp_upload_dir();
    
    if(!$this->upload_dir['error']) {
      
      $uploads_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
      $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
      $uploads_length = strlen($uploads_folder);
						
			$folders_to_hide = explode("\n", file_get_contents( MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR .'/folders_to_hide.txt'));
      
      //find the uploads folder
      $uploads_url = $this->upload_dir['baseurl'];
      //$upload_path = $this->get_absolute_path($uploads_url);
			$upload_path = $this->upload_dir['basedir'];
      $folder_found = false;
			
			//not sure if this is still needed
			//$this->mlp_remove_slashes();
      
      if(!$noecho)
        echo __('Scanning for new folders in ','maxgalleria-media-library') . " $upload_path<br>";      
      $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_path), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
      foreach($objects as $name => $object){
					if(is_dir($name)) {            
						$dir_name = pathinfo($name, PATHINFO_BASENAME);
						if ($dir_name[0] !== '.' && strpos($dir_name, "'") === false ) { 
							if( empty($skip_path) || (strpos($name, $skip_path) === false)) {

								// no match, set it back to empty
								$skip_path = "";
							//$url = $this->get_file_url($name);
							//error_log("skip_path $skip_path, name $name");

							if(!is_multisite()) {
								$upload_pos = strpos($name, $uploads_folder);
								$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));
                
								// fix slashes if running windows
                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
									$url = str_replace('\\', '/', $url);      
								}

								if($this->folder_exist($url) === false) {                  
									if(!in_array($dir_name, $folders_to_hide)) {
										if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){
											$folder_found = true;
											if(!$noecho)
												echo __('Adding','maxgalleria-media-library') . " $url<br>";
											$parent_id = $this->find_parent_id($url);
											if($parent_id)
											  $this->add_media_folder($dir_name, $parent_id, $url);
										} else {
											$skip_path = $name;
										}
									} else {
										$skip_path = $name;									
									}
								}
							} else {
								if($blog_id === '1') {
									if(strpos($name,"uploads/sites") !== false)
										continue;

									$upload_pos = strpos($name, $uploads_folder);
									$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));

									// fix slashes if running windows
                  if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
										$url = str_replace('\\', '/', $url);      
									}

									if($this->folder_exist($url) === false) {
										if(!in_array($dir_name, $folders_to_hide)) {
											if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){
												$folder_found = true;
												if(!$noecho)
													echo __('Adding','maxgalleria-media-library') . " $url<br>";
												$parent_id = $this->find_parent_id($url);
											  if($parent_id)
												  $this->add_media_folder($dir_name, $parent_id, $url);
											} else {
												$skip_path = $name;									
												//error_log("skip_path $skip_path");
											}
										} else {
											$skip_path = $name;									
												//error_log("skip_path $skip_path");
										}
									}																
								} else {
									if(strpos($name,"uploads/sites/$blog_id") !== false) {
										$upload_pos = strpos($name, $uploads_folder);
										$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));
										
										// fix slashes if running windows
                    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
											$url = str_replace('\\', '/', $url);      
										}

										if($this->folder_exist($url) === false) {											
											if(!in_array($dir_name, $folders_to_hide)) {
												if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){																						
													$folder_found = true;
													if(!$noecho)
														echo __('Adding','maxgalleria-media-library') . " $url<br>";
													$parent_id = $this->find_parent_id($url);
											    if($parent_id)
													  $this->add_media_folder($dir_name, $parent_id, $url);              
												}
											} else {
												$skip_path = $name;									
											}
										}																
									}
								}
							}
						}  
					}
        }  
      }      
      if(!$folder_found) {
        if(!$noecho)
          echo __('No new folders were found.','maxgalleria-media-library') . "<br>";
      }  
    } 
    else {
      if(!$noecho)
        echo "error: " . $this->upload_dir['error'];
    }
  }
		
	public function new_folder_search($name, $uploads_folder, $uploads_length, $dir_name, $noecho) {
    
    global $is_IIS;
		$folder_found = false;
		$upload_pos = strpos($name, $uploads_folder);
		$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));

		// fix slashes if running windows
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
			$url = str_replace('\\', '/', $url);      
		}

		if($this->folder_exist($url) === false) {
			$folder_found = true;
			if(!$noecho) {
				echo __('Adding','maxgalleria-media-library') . " $url<br>";
			}	
			$parent_id = $this->find_parent_id($url);
			if($parent_id)
			  $this->add_media_folder($dir_name, $parent_id, $url);              
		}
		return $folder_found;
	}
  
  private function find_parent_id($base_url) {
    
    global $wpdb;    
    $last_slash = strrpos($base_url, '/');
    $parent_dir = substr($base_url, 0, $last_slash);
		
		// get the relative path
		$parent_dir = substr($parent_dir, $this->base_url_length);		
		
    //$sql = "select ID from $wpdb->prefix" . "posts where guid = '$parent_dir'";
    $sql = "SELECT ID FROM {$wpdb->prefix}posts
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = ID
WHERE pm.meta_value = '$parent_dir' 
and pm.meta_key = '_wp_attached_file'";
		
    $row = $wpdb->get_row($sql);
    if($row) {
      $parent_id = $row->ID;
    }
    else
      $parent_id = $this->uploads_folder_ID; //-1;

    return $parent_id;
  }
    
  private function mpmlp_insert_post( $post_type, $post_title, $guid, $post_status ) {
    global $wpdb;
    
    $user_id = get_current_user_id();
    $post_date = current_time('mysql');
    
    $post = array(
      'post_content'   => '',
      'post_name'      => $post_title, 
      'post_title'     => $post_title,
      'post_status'    => $post_status,
      'post_type'      => $post_type,
      'post_author'    => $user_id,
      'ping_status'    => 'closed',
      'post_parent'    => 0,
      'menu_order'     => 0,
      'to_ping'        => '',
      'pinged'         => '',
      'post_password'  => '',
      'guid'           => $guid,
      'post_content_filtered' => '',
      'post_excerpt'   => '',
      'post_date'      => $post_date,
      'post_date_gmt'  => $post_date,
      'comment_status' => 'closed'
    );      
        
    
    $table = $wpdb->prefix . "posts";	    
    $wpdb->insert( $table, $post );
        
    return $wpdb->insert_id;  
  }
  
  public function mlp_set_review_notice_true() {
    
    $current_user_id = get_current_user_id(); 
    
    update_user_meta( $current_user_id, MAXGALLERIA_MLP_REVIEW_NOTICE, "off" );
    
    $request = $_SERVER["HTTP_REFERER"];
    
    echo "<script>window.location.href = '" . $request . "'</script>";             
    
	}
  
  
  public function mlp_set_feature_notice_true() {
    
    $current_user_id = get_current_user_id(); 
    
    update_user_meta( $current_user_id, MAXGALLERIA_MLP_FEATURE_NOTICE, "off" );
    
    $request = $_SERVER["HTTP_REFERER"];
    
    echo "<script>window.location.href = '" . $request . "'</script>";             
    
	}
  
	public function mlp_set_review_later() {
    
    $current_user_id = get_current_user_id(); 
    
    $review_date = date('Y-m-d', strtotime("+14 days"));
        
    update_user_meta( $current_user_id, MAXGALLERIA_MLP_REVIEW_NOTICE, $review_date );
    
    $request = $_SERVER["HTTP_REFERER"];
    
    echo "<script>window.location.href = '" . $request . "'</script>";             
    
	}
		
  public function mlp_features_notice() {
    if( current_user_can( 'manage_options' ) ) {  ?>
      <div class="updated notice maxgalleria-mlp-notice">         
        <div id='maxgalleria-mlp-notice-3'><p id='mlp-notice-title'><?php _e( 'Is there a feature you would like for us to add to Media Library Folders Pro? Let us know.', 'maxgalleria-media-library' ); ?></p>
        <p><?php _e( 'Send your suggestions to <a href="mailto:support@maxfoundry.com">support@maxfoundry.com</a>.', 'maxgalleria-media-library' ); ?></p>

        </div>
        <a class="dashicons dashicons-dismiss close-mlp-notice" href="<?php echo admin_url(); ?>admin.php?page=mlp-feature-notice"></a>          
      </div>
    <?php     
    }
  }
  
  public function mlp_review_notice() {
    if( current_user_can( 'manage_options' ) ) {  ?>
      <div class="updated notice maxgalleria-mlp-notice">         
        <div id='maxgalleria-mlp-notice-3'><p id='mlp-notice-title'><strong><?php _e( 'Rate us Please!', 'maxgalleria-media-library' ); ?></strong>
        <?php _e( 'Your rating is the simplest way to support Media Library Folders Pro. We really appreciate it!', 'maxgalleria-media-library' ); ?></p>

        <ul id="mlp-review-notice-links">
          <li><a href="<?php echo admin_url(); ?>admin.php?page=mlp-review-notice"><?php _e( "I've already left a review", "maxgalleria-media-library" ); ?></a></li>
          <li><a href="<?php echo admin_url(); ?>admin.php?page=mlp-review-later"><?php _e( "Maybe Later", "maxgalleria-media-library" ); ?></a></li>
          <li><a target="_blank" href="https://wordpress.org/support/plugin/media-library-plus/reviews/?filter=5"><?php _e( "Sure! I'd love to!", "maxgalleria-media-library" ); ?></a></li>
        </ul>
        </div>
        <a class="dashicons dashicons-dismiss close-mlp-notice" href="<?php echo admin_url(); ?>admin.php?page=mlp-review-notice"></a>          
      </div>
    <?php     
    }
  }
  	
  public function check_for_attachment_id($guid, $post_id) {	
		global $blog_id;
		$default_alt = "";
		
		$attach_id_found = strpos($guid, 'attachment_id=');
		if($attach_id_found !== false)
			$location = wp_get_attachment_url($post_id);
		else
			$location = $guid;
						
//		if(is_multisite()) {
//			$url_slug = 'site' . $blog_id . '/';
//			$location = str_replace($location, $url_slug, "");			
//			return $location;
//		} else
			return $location;
	}
	
	private function get_existing_folder_attachments($parent_folder) {
		
		global $wpdb;
		
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
      
    $sql = "select ID, pm.meta_value as attached_file, post_title, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' 
and folder_id = '$parent_folder' 
and pm.meta_key = '_wp_attached_file'	
order by post_title";
    
    return $wpdb->get_results($sql);
	}
	
	public function max_sync_contents($parent_folder) {
    
    global $wpdb;
		global $blog_id;
		global $is_IIS;
		$skip_path = "";
		$last_new_folder_id = 0;
		
    $files_added = 0;
		$alt_text = "";
		$default_title = "";
		$default_alt = "";
		$folders_found = false;
    $existing_folders = false;
				    				    
    if(!is_numeric($parent_folder))
      die();
    
		$uploads_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
		$uploads_length = strlen($uploads_folder);		
		$uploads_url = $this->upload_dir['baseurl'];
		$upload_path = $this->upload_dir['basedir'];

		$folders_to_hide = explode("\n", file_get_contents( MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_DIR .'/folders_to_hide.txt'));
        						
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta
where post_id = $parent_folder    
and meta_key = '_wp_attached_file'";	

    $current_row = $wpdb->get_row($sql);

		$baseurl = rtrim($uploads_url, '/') . '/';
		
		if(!is_multisite()) {
			//error_log("baseurl $baseurl");
			$image_location = $baseurl . ltrim($current_row->attached_file, '/');
		  //error_log("image_location $image_location");
      $folder_path = $this->get_absolute_path($image_location);
		} else {
      $folder_path = $this->get_absolute_path($baseurl);		
		}	
		
		//not sure if this is still needed
		//$this->mlp_remove_slashes();
		
		$folders_array = array();
		$folders_array[] = $parent_folder;
    
    //error_log(print_r($folders_to_hide,true));

		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder_path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
				
    // check for new folders		
		foreach($objects as $name => $object){
			if(is_dir($name)) {
				$dir_name = pathinfo($name, PATHINFO_BASENAME);
				if ($dir_name[0] != '.' && strpos($dir_name, "'") == false ) { 
					if( empty($skip_path) || (strpos($name, $skip_path) === false)) {

						// no match, set it back to empty
						$skip_path = "";

						if(!is_multisite()) {

							$upload_pos = strpos($name, $uploads_folder);
							$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));

							// fix slashes if running windows
              if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
								$url = str_replace('\\', '/', $url);      
							}

							$existing_folder_id = $this->folder_exist($url);
							if($existing_folder_id == false) {
								if(!in_array($dir_name, $folders_to_hide)) {
									if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){
									$folders_found = true;
									$parent_id = $this->find_parent_id($url);
									$last_new_folder_id = $this->add_media_folder($dir_name, $parent_id, $url);
									$folders_array[] = $last_new_folder_id;
									//$last_new_folder_id++;
									//error_log("folder added: $name");
									$files_added++;								
									} else {
										$skip_path = $name;
									}
								} else {
									$skip_path = $name;			
								}
							} else {
                $existing_folders = true;
							  $folders_array[] = $existing_folder_id;
							}
						} else {
							if($blog_id === '1') {
								if(strpos($name,"uploads/sites") !== false)
									continue;

								$upload_pos = strpos($name, $uploads_folder);
								$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));

								// fix slashes if running windows
                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
									$url = str_replace('\\', '/', $url);      
								}

							  $existing_folder_id = $this->folder_exist($url);
								if($existing_folder_id === false) {
									//error_log("folder id: $existing_folder_id");
									if(!in_array($dir_name, $folders_to_hide)) {
										if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){
											$folders_found = true;
											$parent_id = $this->find_parent_id($url);
											$last_new_folder_id = $this->add_media_folder($dir_name, $parent_id, $url);
									    $folders_array[] = $last_new_folder_id;
											//error_log("folder added: $name");
											$files_added++;								
										} else {
											$skip_path = $name;
										}
									} else {
                    //error_log("folder found in list: $dir_name");
										$skip_path = $name;									
									}
								}	else {
                  $existing_folders = true;
							    $folders_array[] = $existing_folder_id;									
								}					
							} else {
								if(strpos($name,"uploads/sites/$blog_id") !== false) {
									
									//error_log("");
									//error_log("name $name");
									
									$upload_pos = strpos($name, $uploads_folder);
									//error_log("$uploads_folder, upload_pos $upload_pos");
																		
									$url = $uploads_url . substr($name, ($upload_pos+$uploads_length));
									//error_log("uploads_url $uploads_url");

									// fix slashes if running windows
                  if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
										$url = str_replace('\\', '/', $url);      
									}
									$existing_folder_id = $this->folder_exist($url);
									if($existing_folder_id === false) {
                    if(!in_array($dir_name, $folders_to_hide)) {
                      if(!file_exists($name . DIRECTORY_SEPARATOR . 'mlpp-hidden' )){
                        $folders_found = true;
                        
                        $parent_id = $this->find_parent_id($url);
                        $last_new_folder_id = $this->add_media_folder($dir_name, $parent_id, $url);              
                        $folders_array[] = $last_new_folder_id;
                        $files_added++;								
                      } else {
                        $existing_folders = true;
                        $folders_array[] = $existing_folder_id;										
                      }
                    }
                  }  
								}
							}
						}
					}  
				}				
			}
		} // end foreach		
    
		$user_id = get_current_user_id();
    $folders_array = array_reverse($folders_array);
  	update_user_meta($user_id, MAXG_SYNC_FOLDERS, $folders_array);
				
    if($folders_found || $existing_folders) {
      return true;
    } else {
      return false;
    }  
    
	}
	
	public function get_base_file($file_path) {
		
		$dot_position = strrpos($file_path, '.' );		
    if($dot_position === false)
      return $file_path;
    else
		  return substr($file_path, 0, $dot_position);
	}
				
	private function is_base_file($file_path, $file_array) {
		
		$dash_position = strrpos($file_path, '-' );
		$x_position = strrpos($file_path, 'x', $dash_position);
		$dot_position = strrpos($file_path, '.' );
		
		if(($dash_position) && ($x_position)) {
			$base_file = substr($file_path, 0, $dash_position) . substr($file_path, $dot_position );
			if(in_array($base_file, $file_array))
				return false;
			else 
				return true;
		} else 
			return true;
				
	}
  
	private function get_ml_base_file($file_path) {
		
		$dash_position = strrpos($file_path, '-' );
		$x_position = strrpos($file_path, 'x', $dash_position);
		$dot_position = strrpos($file_path, '.' );
		
		if(($dash_position) && ($x_position)) {
			$base_file = substr($file_path, 0, $dash_position) . substr($file_path, $dot_position );
      return $base_file;
    } else { 
			return false;
    }  
				
	}
  	
	private function search_folder_attachments($file_path, $attachments){

		$found = false;
    if($attachments) {
      foreach($attachments as $row) {
        $current_file_path = pathinfo(get_attached_file($row->ID), PATHINFO_BASENAME);
        if(strpos($current_file_path, '-scaled.') !== false)
          $current_file_path = str_replace ('-scaled', '', $current_file_path);
        //error_log("$current_file_path $file_path");
				if($current_file_path === $file_path) {
					$found = true;
          //error_log("found");
					break;
				} else {
          //error_log("not found");          
        }
      }			
    }
		return $found; 
	}
	
	public function write_log ( $log )  {
		if(!defined('HIDE_WRITELOG_MESSAGES')) {
			if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
				} else {
					error_log( $log );
				}
			}
		}
  }
				
	public function mlp_load_folder() {
		
    global $wpdb;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['folder'])) && (strlen(trim($_POST['folder'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['folder'])));
    else
      $current_folder_id = "";
    
    if(!is_numeric($current_folder_id))
      die();
        
		$folder_location = $this->get_folder_path($current_folder_id);

		$folders_path = "";
		$parents = $this->get_parents($current_folder_id);

		$folder_count = count($parents);
		$folder_counter = 0;        
		$content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
		$current_folder_string = site_url() . "/$content_folder";
		foreach( $parents as $key => $obj) { 
			$folder_counter++;
			if($folder_counter === $folder_count)
				$folders_path .= $obj['name'];      
			else
				$folders_path .= '<a folder="' . $obj['id'] . '" class="media-link">' . $obj['name'] . '</a>/';      
			$current_folder_string .= '/' . $obj['name'];
		}
		
		$this->display_folder_contents ($current_folder_id, true, $folders_path); // changes media link
				
	  die();
		
	}
		
	public function wp_get_attachment( $attachment_id ) {

		$attachment = get_post( $attachment_id );

		$base_url = $this->upload_dir['baseurl'];
    $attached_file = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$base_url = rtrim($base_url, '/') . '/';
		$image_location = $base_url . ltrim($attached_file, '/');
		
		$available_sizes = array();
		
		if (wp_attachment_is_image($attachment_id)) {
			foreach ( $this->image_sizes as $size ) {
				$image = wp_get_attachment_image_src( $attachment_id, $size );
								
				if(!empty( $image ) && ( true == $image[3] || 'full' == $size )) {
					$available_sizes[$size] = $image[1] . " x " . $image[2];
				}	
			}
		} else {
			$available_sizes["full"] = "full";
		}
	
		
		$image_data = array(
				'id' => $attachment_id,
				'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
				'caption' => $attachment->post_excerpt,
				'description' => $attachment->post_content,
				'href' => get_permalink( $attachment->ID ),
				'src' => $image_location,
				'title' => $attachment->post_title,
				'available_sizes'	=> $available_sizes
		);
		
		return $image_data;
	}
	
	public function mlp_get_image_info() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $image_id = trim(stripslashes(strip_tags($_POST['image_id'])));
    else
      $image_id = "";
    
    if(!is_numeric($image_id))
      die();
		
		$image_info = $this->wp_get_attachment($image_id);
				
		echo json_encode($image_info);
						
		die();
		
	}
	
	public function mlp_image_add_caption() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['html'])) && (strlen(trim($_POST['html'])) > 0))
      $html = stripslashes($_POST['html']);
    else
      $html = "";
		
    if ((isset($_POST['id'])) && (strlen(trim($_POST['id'])) > 0))
      $id = intval(trim(stripslashes(strip_tags($_POST['id']))));
    else
      $id = 0;
		
    if ((isset($_POST['caption'])) && (strlen(trim($_POST['caption'])) > 0))
      $caption = trim(stripslashes(strip_tags($_POST['caption'])));
    else
      $caption = "";
		
    if ((isset($_POST['title'])) && (strlen(trim($_POST['title'])) > 0))
      $title = trim(stripslashes(strip_tags($_POST['title'])));
    else
      $title = "";
		
    if ((isset($_POST['align'])) && (strlen(trim($_POST['align'])) > 0))
      $align = trim(stripslashes(strip_tags($_POST['align'])));
    else
      $align = "";
		
    if ((isset($_POST['url'])) && (strlen(trim($_POST['url'])) > 0))
      $url = trim(stripslashes(strip_tags($_POST['url'])));
    else
      $url = "";
				
    if ((isset($_POST['size'])) && (strlen(trim($_POST['size'])) > 0))
      $size = trim(stripslashes(strip_tags($_POST['size'])));
    else
      $size = "";
		
    if ((isset($_POST['alt'])) && (strlen(trim($_POST['alt'])) > 0))
      $alt = trim(stripslashes(strip_tags($_POST['alt'])));
    else
      $alt = "";	
				   
    $caption_html = image_add_caption( $html, $id, $caption, $title, $align, $url, $size, $alt );		
		
		echo json_encode($caption_html);
						
		die();
		
	}
	
	function mlp_update_description () {
		
		global $wpdb;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['id'])) && (strlen(trim($_POST['id'])) > 0))
      $id = intval(trim(stripslashes(strip_tags($_POST['id']))));
    else
      $id = 0;
		
    if ((isset($_POST['desc'])) && (strlen(trim($_POST['desc'])) > 0))
      $desc = trim(stripslashes(strip_tags($_POST['desc'])));
    else
      $desc = "";
		
		if($id !== 0) {
			$table = $wpdb->prefix . "posts";
			$data = array('post_content' => $desc );
			$where = array('ID' => $id);
			$wpdb->update( $table, $data, $where);
		}
	
		die();
	}
	
	function mlp_admin_post_thumbnail( $content, $post_id = null )  {
		
    if ($post_id == null) {
			global $post;

		  if ( !is_object($post) )
		    return $content;
       
      $post_id = $post->ID;
    }
		
		$thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
		
		global $wp_version;

		if (version_compare($wp_version, '3.5', '>=') && $thumbnail_id <= 0) {

			$screen = get_current_screen();			
			if($screen->post_type === 'product') {
			  $set_thumbnail_link = '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set Woo Product Image &amp; Product Gallery Images', 'maxgalleria-media-library' ) . '" href="#TB_inline?height=450&amp;width=753&amp;inlineId=select-mlp-container" id="set-mlp-post-thumbnail" class="thickbox">%s</a></p>';
			  $content .= sprintf($set_thumbnail_link, esc_html__( 'Set Woo Product Image &amp; Product Gallery Images', 'maxgalleria-media-library' )) . PHP_EOL;
			} else {
			  $set_thumbnail_link = '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set MLF featured image', 'maxgalleria-media-library' ) . '" href="#TB_inline?height=450&amp;width=753&amp;inlineId=select-mlp-container" id="set-mlp-post-thumbnail" class="thickbox">%s</a></p>';
			  $content .= sprintf($set_thumbnail_link, esc_html__( 'Set MLF featured image', 'maxgalleria-media-library' )) . PHP_EOL;
			}	
			$content .= '<script>' . PHP_EOL;
			$content .= '	jQuery(document).ready(function(){' . PHP_EOL;
			
			//$content .= '	  jQuery("#set-mlp-post-thumbnail, .set-mlp-post-thumbnail-gallery").click(function(){' . PHP_EOL;
      $content .= '	  jQuery(document).on("click","#set-mlp-post-thumbnail, .set-mlp-post-thumbnail-gallery",function(){' . PHP_EOL;
			
			$content .= '	    jQuery("#acf_info").hide();' . PHP_EOL;
			$content .= '     if(jQuery("#product_images_container").length) {' . PHP_EOL;
			
			$content .= '	      jQuery("#product_info").show();' . PHP_EOL;
			$content .= '	      jQuery("#featured_info").hide();' . PHP_EOL;
			$content .= '	      jQuery("#insert_mlp_media").hide();' . PHP_EOL;
			$content .= '     } else {' . PHP_EOL;
			
			$content .= '	      jQuery("#product_info").hide();' . PHP_EOL;
			$content .= '	      jQuery("#featured_info").show();' . PHP_EOL;
			$content .= '	      jQuery("#insert_mlp_media").show();' . PHP_EOL;
			$content .= '     }' . PHP_EOL;

			$content .= '	    jQuery("#mlp_featured").val("featured");' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_custom_link_label").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_custom_link").hide();' . PHP_EOL;			
			$content .= '	    jQuery("#mlp_tb_align_label").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_alignment").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_size_label").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_size").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_link_to_label").hide();' . PHP_EOL;
			$content .= '	    jQuery("#mlp_tb_link_select").hide();' . PHP_EOL;
			$content .= '	    jQuery("#insert_mlp_media").val("Set Featured Image");' . PHP_EOL;			
		  $content .= '	  });' . PHP_EOL;
			$content .= '	});' . PHP_EOL;
			$content .= '</script>' . PHP_EOL;
		}
			
		return $content;
						
	}
	
//	public function mlp_add_featured_image () {
//		
//    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
//      exit(__('missing nonce!','maxgalleria-media-library'));
//    } 
//				
//		if ((isset($_POST['id'])) && (strlen(trim($_POST['id'])) > 0))
//			$post_ID = intval(trim(stripslashes(strip_tags($_POST['id']))));
//		else
//			$post_ID = 0;
//
//		if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
//			$thumbnail_id = intval(trim(stripslashes(strip_tags($_POST['image_id']))));
//		else
//			$thumbnail_id = 0;
//				
//		if($post_ID !== 0 ) {
//			if ( set_post_thumbnail( $post_ID, $thumbnail_id ) ) {
//				$return = _wp_post_thumbnail_html( $thumbnail_id, $post_ID );
//				echo $return;
//			}
//		}
//		else {
//			$return = _wp_post_thumbnail_html( $thumbnail_id, 0 );
//			echo $return;			
//		}
//		
//		die();
//		
//	}
	
	public function mlp_get_attachment_image_src () {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
								
    if ((isset($_POST['serial_image_ids'])) && (strlen(trim($_POST['serial_image_ids'])) > 0))
      $image_ids = trim(stripslashes(strip_tags($_POST['serial_image_ids'])));
    else
      $image_ids = "";
		
						        
    $image_ids = str_replace('"', '', $image_ids);    
    
    $image_ids = explode(',', $image_ids);
						
//		if ((isset($_POST['size'])) && (strlen(trim($_POST['size'])) > 0))
//			$size = intval(trim(stripslashes(strip_tags($_POST['size']))));
//		else
//			$size = "";
		
		if ((isset($_POST['id'])) && (strlen(trim($_POST['id'])) > 0))
			$post_id = intval(trim(stripslashes(strip_tags($_POST['id']))));
		else
			$post_id = 0;		
		
		$image_array = array();
		
		foreach( $image_ids as $image_id) {

			$image = array();
			$image['image_id'] = $image_id;
			//$image_path_sizes = wp_get_attachment_image_src( $image_id, $size );
			$image_path_sizes = wp_get_attachment_image_src($image_id, array('150','150'));
			$image_srcset = wp_get_attachment_image_srcset($image_id);
			if(isset($image_path_sizes[0])) { 
				$image['src'] = '<img class="attachment-thumbnail size-thumbnail" src="' . $image_path_sizes[0] . '" alt="" srcset="' . $image_srcset . '" width="150" height="150" >';
			}	else 
				$image['src'] = "";
			
			$image_array[] = $image; 
		}
			
		echo json_encode($image_array);
		die();
	}				

//	public function mlp_save_featured_image_id( $post_ID ) {
//		
//		if( !current_user_can( 'edit_pages' ) ) return;
//
//		if( isset( $_REQUEST['mlp_featured_image'] )) {		
//			$thumbnail_id = $_REQUEST['mlp_featured_image'];
//			set_post_thumbnail( $post_ID, $thumbnail_id );
//		}		
//
//	}
	
	public function view_nextgen (){ 

	  global $wpdb;
		$folders_found = false;
		$images_found = false;
		
    ?>      
<!--      <div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=636262096435499";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>-->
    <?php		
		
	  $site_url = site_url();
		$ngg_options =	get_option('ngg_options');
		$ng_folder = $site_url . '/' . $ngg_options['gallerypath'];
		$folders_path = "NextGen Galleries"
						
			?>
      <div id="wp-media-grid" class="wrap">                
        <!--empty h2 for where WP notices will appear--> 
				<h1></h1>
        <div class="media-plus-toolbar"><div class="media-toolbar-secondary">  
            
        <div id='mgmlp-title-area'>
          <h2 class='mgmlp-title'><?php _e('NextGen Gallery Viewer', 'maxgalleria-media-library' ); ?> </h2>  
          <div class="mgmlp-title" id='mg-prono-top'>
            <div><?php _e('Brought to you by', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxfoundry.com"> <img alt="Max Foundry" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/max-foundry-new.png"></a> <?php _e('Makers of', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxbuttons.com/?ref=mbpro">MaxButtons</a>, <a target="_blank" href="http://maxbuttons.com/product-category/button-packs/">WordPress Buttons</a> <?php _e('and', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxgalleria.com/">MaxGalleria</a></div>
            <!--<div><?php _e('Brought to you by', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxfoundry.com"> <img alt="Max Foundry" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/max-foundry-new.png"></a> <?php _e('Makers of', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxbuttons.com/?ref=mbpro">MaxButtons</a> <?php _e('and', 'maxgalleria-media-library' ); ?> <a target="_blank" href="http://maxinbound.com/?ref=mbpro">MaxInbound</a></div>-->
            <div class="fb-like" data-href="https://www.facebook.com/maxfoundry" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>        </div>      
        </div>    
        <div class="clearfix"></div>  
        <!--<p id='mlp-more-info'><a href='http://maxgalleria.com/media-library-plus/' target="_blank"><?php //_e('Click here to learn about the Media Library Folders Pro', 'maxgalleria-media-library' ); ?> </a></p>-->
                                      
        <div class="clearfix"></div>
				
          <!--<a id="mgmlp-scan_folders">Scan Folders</a>-->  
          
          <div id="mgmlp-library-container">
            <div id="alwrap">
              <div style="display:none" id="ajaxloader"></div>
            </div>
            <?php 
						
							if ((isset($_GET['media-folder'])) && (strlen(trim($_GET['media-folder'])) > 0)) {
								$current_folder_id = trim(stripslashes(strip_tags($_GET['media-folder'])));
								if(is_numeric($current_folder_id)) {
									$gallery_name = $this->get_gallery_name($current_folder_id);
									$folders_path .= "/" . $gallery_name;
									echo "media folder: $current_folder_id, folders_path: $folders_path<br>";
								}
							} else {
								$current_folder_id = 0;
							}
						
	            echo "<h3 id='mgmlp-breadcrumbs'>" . __('Location:','maxgalleria-media-library') . " $folders_path</h3>";
														
							echo '<div id="mgmlp-toolbar">' . PHP_EOL;
							echo '</div><!-- mgmlp-toolbar -->' . PHP_EOL;
							echo '<div class="clearfix"></div>' . PHP_EOL;


							if($current_folder_id === 0)
							  $sql = "SELECT gid, name FROM {$wpdb->prefix}ngg_gallery ORDER BY name";
							else	
							  $sql = "SELECT pid, filename FROM {$wpdb->prefix}ngg_pictures WHERE galleryid = $current_folder_id ORDER BY filename";
							//echo $sql;
							$folder_list = "";
							$rows = $wpdb->get_results($sql);

              echo '<div id="mgmlp-file-container">' . PHP_EOL;
						  //echo '<div id="folder-tree-container"><ul id="folder-tree" class="ztree"></ul></div>' . PHP_EOL;
							

							echo '<ul class="mg-media-list">' . PHP_EOL;              
							if($rows) {
								$folders_found = true;
								foreach($rows as $row) {

									if($current_folder_id === 0) {
										//$checkbox = sprintf("<input type='checkbox' class='mgmlp-folder' id='%s' value='%s' />", $row->gid, $row->gid );
										
										echo "<li>" . PHP_EOL;
										echo "  <a id='$row->ID' class='media-folder media-link' folder='$row->gid'></a>" . PHP_EOL;
										echo "  <div class='attachment-name'><span class='image_select'></span><a href='$gallery_link' class='media-link' folder='$row->gid'>$row->name</a></div>" . PHP_EOL;
										//echo "  <div class='attachment-name'><span class='image_select'>$checkbox</span><a href='$gallery_link' class='media-link' folder='$row->gid'>$row->name</a></div>" . PHP_EOL;
										echo "</li>" . PHP_EOL;       
									} else {
										$images_found = true;
										//$checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->pid, $row->pid );
										if($image_link)
											$class = "media-attachment"; 
										else
											$class = "tb-media-attachment"; 

										$media_edit_link = "";

										$filename = $ng_folder . "/" . $gallery_name . "/" . $row->filename;
										
										$thumbnail = $ng_folder . "/" . $gallery_name . "/thumbs/thumbs_" . $row->filename;

										echo "<li>" . PHP_EOL;
										if($image_link)
											echo "   <a class='$class' href='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
										else
											echo "   <a id='$row->pid' class='$class'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
										echo "   <div class='attachment-name'><span class='image_select'></span>$row->filename</div>" . PHP_EOL;
										//echo "   <div class='attachment-name'><span class='image_select'>$checkbox</span>$row->filename</div>" . PHP_EOL;
										echo "</li>" . PHP_EOL;              
									
									}
								}
							}

							echo '</ul>' . PHP_EOL;
							echo '</div><!-- mgmlp-file-container -->' . PHP_EOL;

							if(!$images_found && !$folders_found)
								echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
																
						?>
                        
          </div> <!-- mgmlp-library-container -->
				</div> <!-- media-toolbar-secondary -->
				</div> <!-- media-plus-toolbar -->
			</div> <!-- wp-media-grid -->	
		<script>

	jQuery(document).on("click", ".media-link", function () {

    jQuery("#folder-message").html('');
		var folder = jQuery(this).attr('folder');

		var home_url = "<?php echo site_url(); ?>"; 

		window.location.href = home_url + '/wp-admin/admin.php?page=view-nextgen&' + 'media-folder=' + folder;

	});

	</script>  

			<?php
		
	}
	
	private function get_gallery_name($current_folder_id) {
    global $wpdb;    
    
	  $sql = "select name from {$wpdb->prefix}ngg_gallery where gid = $current_folder_id";
    
    $row = $wpdb->get_row($sql);
    if($row === null)
      return false;
    else
      return $row->name;
		
	}
	
	public function mlpp_hide_template_ad() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
    update_option('mlpp_show_template_ad', "off");
		
		die();
	}
	
	public function mlpp_create_new_ng_gallery() {
		
    global $wpdb;
		$retval = false;
		
		$ngg_options =	get_option('ngg_options');
    
		$ng_folder_path = rtrim(get_home_path() . DIRECTORY_SEPARATOR . $ngg_options['gallerypath'], '/');
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
    if ((isset($_POST['parent_folder'])) && (strlen(trim($_POST['parent_folder'])) > 0))
      $parent_folder_id = trim(stripslashes(strip_tags($_POST['parent_folder'])));
		else
			$parent_folder_id = 0;
    
    if ((isset($_POST['new_gallery_name'])) && (strlen(trim($_POST['new_gallery_name'])) > 0)) {
      $new_gallery_name = trim(stripslashes(strip_tags($_POST['new_gallery_name'])));
			
			$gallery_slug =  strtolower(str_replace(' ', '-', $new_gallery_name));
			
			if(class_exists('C_Gallery_Mapper')) {
				$mapper = C_Gallery_Mapper::get_instance();
				if (($gallery = $mapper->create(array('title'	=>	$new_gallery_name))) && $gallery->save()) {
					$retval = $gallery->id();
				}
			}
			
			if($retval !== false) {
			
				$new_gallery_path = $ng_folder_path . DIRECTORY_SEPARATOR . $gallery_slug;
				$thumbs_path = $ng_folder_path . DIRECTORY_SEPARATOR . $gallery_slug . DIRECTORY_SEPARATOR .  "thumbs";
        //error_log("new_gallery_path $new_gallery_path");
				if(!file_exists($new_gallery_path)) {          
          if(defined('FS_CHMOD_DIR')) {
            $retval = mkdir($new_gallery_path, FS_CHMOD_DIR);
            //error_log("thumbs_path 1 $thumbs_path");
            mkdir($thumbs_path, FS_CHMOD_DIR);
          } else {  
            //error_log("thumbs_path 2 $thumbs_path");
            $retval = mkdir($new_gallery_path, 0755);
            mkdir($thumbs_path, 0755);
          }  
				//} else {
	      //  echo __('The gallery already exists.','maxgalleria-media-library');
		    //  die();
				}
			}
						
			if($retval !== false) {
	      echo __('The gallery was created.','maxgalleria-media-library');				
        $location = 'window.location.href = "' . site_url() . '/wp-admin/admin.php?page=mlfp-folders&media-folder=' . $parent_folder_id .'";';
        echo "<script>$location</script>";				
			} else
	      echo __('The gallery could not be created.','maxgalleria-media-library');		
						
		}
		
		die();
		
	}
	
	public function mg_add_to_ng_gallery() {
	
    global $wpdb;
		$image_count = 0;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['serial_gallery_image_ids'])) && (strlen(trim($_POST['serial_gallery_image_ids'])) > 0))
      $serial_gallery_image_ids = trim(stripslashes(strip_tags($_POST['serial_gallery_image_ids'])));
    else
      $serial_gallery_image_ids = "";
    
    $serial_gallery_image_ids = str_replace('"', '', $serial_gallery_image_ids);    
    
    $serial_gallery_image_ids = explode(',', $serial_gallery_image_ids);
        
    if ((isset($_POST['gallery_id'])) && (strlen(trim($_POST['gallery_id'])) > 0))
      $gallery_id = trim(stripslashes(strip_tags($_POST['gallery_id'])));
    else
      $gallery_id = 0;

		if(class_exists('nggdb')) {
			
		  $content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
      require_once ABSPATH . "/$content_folder/plugins/nextgen-gallery/products/photocrati_nextgen/modules/ngglegacy/admin/functions.php";	

			$home_path = get_home_path();
			if(substr($home_path, -1) == '/') 
				$home_path = substr($home_path, 0, -1);
			$gallery_location = $home_path . $this->get_ng_gallery_folder($gallery_id);

			foreach( $serial_gallery_image_ids as $attachment_id) {

				//$sql = "select guid, post_excerpt from {$wpdb->prefix}posts where post_type = 'attachment' and ID = $attachment_id";
				$sql = "select pm.meta_value as attached_file, post_excerpt 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' and ID = $attachment_id";
				

				$row = $wpdb->get_row($sql);
				if($row) {
					//$image_location = $this->check_for_attachment_id($row->guid, $attachment_id);
          //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
					$baseurl = $this->upload_dir['baseurl'];
					$baseurl = rtrim($baseurl, '/') . '/';
					$image_location = $baseurl . ltrim($row->attached_file, '/');
					
					$image_path = $this->get_absolute_path($image_location);
					
			    if(class_exists('MGMediaLibraryFoldersProS3') && 
							($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING))
				  $this->s3_addon->check_and_fetch_file($image_path, $attachment_id);
										
					$alttext = get_post_meta ( $attachment_id, '_wp_attachment_image_alt', true );
					
					$filename = pathinfo($image_location, PATHINFO_BASENAME);					
					
					if($alttext === "")
						$alttext = $filename; 

					$date = date('Y-m-d H:i:s');
          
          do_action(MLFP_BEFORE_ADD_TO_NEXTGEN, $attachment_id, $image_location, $image_path, $gallery_id, $gallery_location); 
					
					$new_nggdb = new nggdb();
					
					$retval = $new_nggdb->add_image( $gallery_id, $filename, $row->post_excerpt, $alttext, false, 0, $date );
					$image_id = $wpdb->insert_id;
					
					if($retval) {
						$destination_name = $gallery_location . DIRECTORY_SEPARATOR . $filename;
						copy($image_path, $destination_name );
						copy($image_path, $destination_name . '_backup' );
            nggAdmin::create_thumbnail($image_id);
	          nggAdmin::import_MetaData($image_id);
					$image_count++;
										
					}
			    if(class_exists('MGMediaLibraryFoldersProS3') && 
						($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
						if($this->s3_addon->remove_from_local) {
							$this->s3_addon->remove_media_file($image_path);
						}	
					}
          
          do_action(MLFP_AFTER_ADD_TO_NEXTGEN, $attachment_id, $image_location, $image_path, $gallery_id, $gallery_location, $image_id); 
          
				}
			}
		}
	  echo  $image_count . __(' image(s) were added.','maxgalleria-media-library');		
		die();
	}
		
	private function get_ng_gallery_folder($gallery_id) {
		
		global $wpdb;
		
		$sql = "select path from {$wpdb->prefix}ngg_gallery where gid = $gallery_id";
		
		$row = $wpdb->get_row($sql);
		if($row) {
			return $row->path;
		}	
		else
			return "";
	}
	
	public function mgmlp_add_to_gallery() {
		
		global $wpdb;
		    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['serial_add_ids'])) && (strlen(trim($_POST['serial_add_ids'])) > 0)) {
      $add_ids = trim(stripslashes(strip_tags($_POST['serial_add_ids'])));
      $add_ids = str_replace('"', '', $add_ids);
      $add_ids = explode(",",$add_ids);
      //$output = print_r($delete_ids, true);
    }  
    else
      $add_ids = '';
		
		$output = "";
            
//    foreach( $add_ids as $add_id) {
//		
//      $sql = "select guid, post_title from {$wpdb->prefix}posts where ID = $add_id";    
//      
//      $row = $wpdb->get_row($sql); 
//			
//			if($row) {
//			
//		    $image_location = $this->check_for_attachment_id($row->guid, $add_id);
//				$output .= '<li id="' . $add_id . '">' . PHP_EOL;				
//        $output .= '  <img src="" alt="">' . PHP_EOL;
//        $output .= '  <div class="attachment-name"><span class="image_select"><input type="checkbox" value="206" id="206" class="mgmlp-media"></span>black-business-woman3.jpg</div>' . PHP_EOL;
//				$output .= '</li>' . PHP_EOL;
//				
//			}
//	  }
		
		echo $output;
		
		die();
	}
		
	public function mlpp_settings() {
    
	  require_once 'includes/mlfp-options.php';	 		
    
	}
  
  public function mlfp_license_network_activate() {
    
    global $wpdb;
        
    $license_key = trim( get_option('mg_edd_mlpp_license_key'));
    $license_status = trim( get_option('mg_edd_mlpp_license_status'));
    $expiration_date = get_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES, '');

    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        		
		// Hold this so we can switch back to it
		$current_blog = $wpdb->blogid;
		
		// Get all the blogs/sites in the network and invoke the function for each one
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blog_ids as $blog_id) {
      if($blog_id != 1) {
        switch_to_blog($blog_id);      
        update_option('mg_edd_mlpp_license_key', $license_key);
        update_option('mg_edd_mlpp_license_status', $license_status );      
        update_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES, $expiration_date );      
      }
		}
		
		// Now switch back to the root blog
		switch_to_blog($current_blog);    
		update_option(MAXGALLERIA_MEDIA_LIBRARY_NETWORK_ACTIVATED, 'yes');
    echo __('Network site licenses were activated;','maxgalleria-media-library');
    die();
  }
  
  public function mlfp_license_network_deactivate() {
    
    global $wpdb;
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        		
		// Hold this so we can switch back to it
		$current_blog = $wpdb->blogid;
		
		// Get all the blogs/sites in the network and invoke the function for each one
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blog_ids as $blog_id) {
      if($blog_id != 1) {
        switch_to_blog($blog_id);      
        delete_option( 'mg_edd_mlpp_license_status' );
        delete_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES);
      }
		}
		
		// Now switch back to the root blog
		switch_to_blog($current_blog);    
		update_option(MAXGALLERIA_MEDIA_LIBRARY_NETWORK_ACTIVATED, 'no');
    echo __('Network site licenses were deactivated;','maxgalleria-media-library');
    die();
  }
   
	public function regen_mlp_thumbnails() {
		
    global $wpdb, $is_IIS;
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		    
    if ((isset($_POST['serial_image_ids'])) && (strlen(trim($_POST['serial_image_ids'])) > 0))
      $image_ids = trim(stripslashes(strip_tags($_POST['serial_image_ids'])));
    else
      $image_ids = "";
				        
    $image_ids = str_replace('"', '', $image_ids);    
    
    $image_ids = explode(',', $image_ids);
		
		$counter = 0;
		
		foreach( $image_ids as $image_id) {
			
			// check if the file is an image
			if(wp_attachment_is_image($image_id)) {
			
				// get the image path
				$image_path = get_attached_file( $image_id );
        
        $scaled_position = strpos($image_path, '-scaled');
        
        if($scaled_position != false) {
          $temp_path = substr($image_path, 0, $scaled_position);
          $temp_path .= substr($image_path, $scaled_position+7);
          $image_path = $temp_path;
        }
        				
		    if(class_exists('MGMediaLibraryFoldersProS3') && 
							($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING))
				  $this->s3_addon->check_and_fetch_file($image_path, $image_id);

				// get the name of the file
				$base_name = wp_basename( $image_path );
        
        //$this->get_sizes_to_deactivate();
        
        do_action(MLFP_BEFORE_THUMBNAIL_REGEN, $image_id, $image_path);
        
				// set the time limit o five minutes
				@set_time_limit( 300 ); 
        
				// regenerate the thumbnails
        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
          $this->remove_existing_thumbnails($image_id, addslashes($image_path));
				  $metadata = wp_generate_attachment_metadata( $image_id, addslashes($image_path));
        } else {
          $this->remove_existing_thumbnails($image_id, $image_path);
				  $metadata = wp_generate_attachment_metadata( $image_id, $image_path );
        }  
        
        //error_log(print_r($metadata, true));
				
				// update the meta data
				wp_update_attachment_metadata( $image_id, $metadata );
        
        do_action(MLFP_AFTER_THUMBNAIL_REGEN, $image_id, $image_path, $metadata);                          

				// check for errors
				if (is_wp_error($metadata)) {
					echo "Error: $base_name, " . $metadata->get_error_message();
					continue;
				}	
				if(empty($metadata)) {
					echo "Unknown error with $base_name";
					continue;
				}	
								
		    //if(class_exists('MGMediaLibraryFoldersProS3') && 
				//	($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
		    if(class_exists('MGMediaLibraryFoldersProS3') && ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE)) {			
				
					
					if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {

						$file_url = wp_get_attachment_url($image_id);

						$location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
						$destination_location = $this->s3_addon->get_destination_location($location);
						$destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);

						//$metadata = wp_get_attachment_metadata($image_id);
            if(isset($metadata['sizes'])) {
              foreach($metadata['sizes'] as $thumbnail) {
                $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                $upload_result = $this->s3_addon->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
              }
            }

						if($this->s3_addon->remove_from_local) {
							$this->s3_addon->remove_media_file($image_path);										
              if(isset($metadata['sizes'])) {
                foreach($metadata['sizes'] as $thumbnail) {
                  $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                  $this->s3_addon->remove_media_file($source_file);
                }
              }
						}
					}
				}

				//wp_update_attachment_metadata( $image_id, $metadata );
				$counter++;

			}		
		}
				
    $regen_msg = sprintf(__('Thumbnails have been regenerated for %d image(s)', 'maxgalleria-media-library'), $counter);
    echo $regen_msg;
    
		
		die();
	}
  
  public function remove_existing_thumbnails($image_id, $image_path) {
    
    global $is_IIS;
    
    $metadata = wp_get_attachment_metadata($image_id);
        
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      $seprator_position = strrpos($image_path, '\\');
    else
      $seprator_position = strrpos($image_path, '/');
    
    $image_path = substr($image_path, 0, $seprator_position);

    if(isset($metadata['sizes'])) {
      foreach($metadata['sizes'] as $source_path) {
        $thumbnail_file = $image_path . DIRECTORY_SEPARATOR . $source_path['file'];

        if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
          $thumbnail_file = str_replace('/', '\\', $thumbnail_file);

        if(file_exists($thumbnail_file))
          unlink($thumbnail_file);
        
        if(class_exists('MGMediaLibraryFoldersProS3') && $this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
          
          //$file_url = $this->build_location_url($row->attached_file);
          //$image_path = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
          $thumbnail_location = $this->s3_addon->get_thumbnail_location($thumbnail['file'], $image_path);
          $this->s3_addon->remove_from_s3($row->post_type, $thumbnail_location);
                    
        }        
      }  
    }    
  }
		
	public function regenerate_interface() {
		global $wpdb;

		?>

      <div id="message" class="updated fade" style="display:none"></div>

      <div id="wp-media-grid" class="wrap">                
        <!--empty h2 for where WP notices will appear--> 
				<h1></h1>
        <div class="media-plus-toolbar"><div class="media-toolbar-secondary">  
            

    <?php

		// If the button was clicked
		if ( ! empty( $_POST['regenerate-thumbnails'] ) || ! empty( $_REQUEST['ids'] ) ) {
			// Capability check
			if ( ! current_user_can( $this->capability ) )
				wp_die( __( 'Cheatin&#8217; uh?' ) );

			// Form nonce check
			check_admin_referer(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE);

			// Create the list of image IDs
			if ( ! empty( $_REQUEST['ids'] ) ) {
				$images = array_map( 'intval', explode( ',', trim( $_REQUEST['ids'], ',' ) ) );
				$ids = implode( ',', $images );
			} else {
				if ( ! $images = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' ORDER BY ID DESC" ) ) {
					echo '	<p>' . sprintf( __( "Unable to find any images. Are you sure <a href='%s'>some exist</a>?", 'maxgalleria-media-library' ), admin_url( 'upload.php?post_mime_type=image' ) ) . "</p></div>";
					return;
				}

				// Generate the list of IDs
				$ids = array();
				foreach ( $images as $image )
					$ids[] = $image->ID;
				$ids = implode( ',', $ids );
			}

			echo '	<p id="wait-message">' . __( "Please wait while the thumbnails are regenerated. This may take a while.", 'maxgalleria-media-library' ) . '</p>';

			$count = count( $images );

			$text_goback = ( ! empty( $_GET['goback'] ) ) ? sprintf( __( 'To go back to the previous page, <a href="%s">click here</a>.', 'maxgalleria-media-library' ), 'javascript:history.go(-1)' ) : '';
			$text_failures = sprintf( __( 'All done! %1$s image(s) were successfully resized in %2$s seconds and there were %3$s failure(s). To try regenerating the failed images again, <a href="%4$s">click here</a>. %5$s', 'maxgalleria-media-library' ), "' + rt_successes + '", "' + rt_totaltime + '", "' + rt_errors + '", esc_url( wp_nonce_url( admin_url( 'tools.php?page=mlp-regenerate-thumbnails&goback=1' ), 'mlp-regenerate-thumbnails' ) . '&ids=' ) . "' + rt_failedlist + '", $text_goback );
			$text_nofailures = sprintf( __( 'All done! %1$s image(s) were successfully resized in %2$s seconds and there were 0 failures. %3$s', 'maxgalleria-media-library' ), "' + rt_successes + '", "' + rt_totaltime + '", $text_goback );
?>


	<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'maxgalleria-media-library' ) ?></em></p></noscript>

	<div id="regenthumbs-bar" style="position:relative;height:25px;">
		<div id="regenthumbs-bar-percent" style="position:absolute;left:50%;top:50%;width:300px;margin-left:-150px;height:25px;margin-top:-9px;font-weight:bold;text-align:center;"></div>
	</div>

	<p><input type="button" class="button hide-if-no-js" name="regenthumbs-stop" id="regenthumbs-stop" value="<?php _e( 'Abort Resizing Images', 'maxgalleria-media-library' ) ?>" /></p>

	<h3 class="title"><?php _e( 'Debugging Information', 'maxgalleria-media-library' ) ?></h3>

	<p>
		<?php printf( __( 'Total Images: %s', 'maxgalleria-media-library' ), $count ); ?><br />
		<?php printf( __( 'Images Resized: %s', 'maxgalleria-media-library' ), '<span id="regenthumbs-debug-successcount">0</span>' ); ?><br />
		<?php printf( __( 'Resize Failures: %s', 'maxgalleria-media-library' ), '<span id="regenthumbs-debug-failurecount">0</span>' ); ?>
	</p>

	<ol id="regenthumbs-debuglist">
		<li style="display:none"></li>
	</ol>

	<script type="text/javascript">
	// <![CDATA[
		jQuery(document).ready(function($){
			var i;
			var rt_images = [<?php echo $ids; ?>];
			var rt_total = rt_images.length;
			var rt_count = 1;
			var rt_percent = 0;
			var rt_successes = 0;
			var rt_errors = 0;
			var rt_failedlist = '';
			var rt_resulttext = '';
			var rt_timestart = new Date().getTime();
			var rt_timeend = 0;
			var rt_totaltime = 0;
			var rt_continue = true;

			// Create the progress bar
			$("#regenthumbs-bar").progressbar();
			$("#regenthumbs-bar-percent").html( "0%" );

			// Stop button
			//$("#regenthumbs-stop").click(function() {
      $(document).on("click","#regenthumbs-stop",function(){
				rt_continue = false;
				$('#regenthumbs-stop').val("<?php echo $this->esc_quotes( __( 'Stopping...', 'maxgalleria-media-library' ) ); ?>");
			});

			// Clear out the empty list element that's there for HTML validation purposes
			$("#regenthumbs-debuglist li").remove();

			// Called after each resize. Updates debug information and the progress bar.
			function RegenThumbsUpdateStatus( id, success, response ) {
				$("#regenthumbs-bar").progressbar( "value", ( rt_count / rt_total ) * 100 );
				$("#regenthumbs-bar-percent").html( Math.round( ( rt_count / rt_total ) * 1000 ) / 10 + "%" );
				rt_count = rt_count + 1;

				if ( success ) {
					rt_successes = rt_successes + 1;
					$("#regenthumbs-debug-successcount").html(rt_successes);
					$("#regenthumbs-debuglist").append("<li>" + response.success + "</li>");
				}
				else {
					rt_errors = rt_errors + 1;
					rt_failedlist = rt_failedlist + ',' + id;
					$("#regenthumbs-debug-failurecount").html(rt_errors);
					$("#regenthumbs-debuglist").append("<li>" + response.error + "</li>");
				}
			}

			// Called when all images have been processed. Shows the results and cleans up.
			function RegenThumbsFinishUp() {
				rt_timeend = new Date().getTime();
				rt_totaltime = Math.round( ( rt_timeend - rt_timestart ) / 1000 );

				$('#regenthumbs-stop').hide();

				if ( rt_errors > 0 ) {
					rt_resulttext = '<?php echo $text_failures; ?>';
				} else {
					rt_resulttext = '<?php echo $text_nofailures; ?>';
				}

				$("#wait-message").html("");
				$("#message").html("<p><strong>" + rt_resulttext + "</strong></p>");
				$("#message").show();
			}

			// Regenerate a specified image via AJAX
			function RegenThumbs( id ) {
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: { action: "regeneratethumbnail", id: id },
					success: function( response ) {
						if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
							response = new Object;
							response.success = false;
							response.error = "<?php printf( esc_js( __( 'The resize request was abnormally terminated (ID %s). This is likely due to the image exceeding available memory or some other type of fatal error.', 'maxgalleria-media-library' ) ), '" + id + "' ); ?>";
						}

						if ( response.success ) {
							RegenThumbsUpdateStatus( id, true, response );
						}
						else {
							RegenThumbsUpdateStatus( id, false, response );
						}

						if ( rt_images.length && rt_continue ) {
							RegenThumbs( rt_images.shift() );
						}
						else {
							RegenThumbsFinishUp();
						}
					},
					error: function( response ) {
						RegenThumbsUpdateStatus( id, false, response );

						if ( rt_images.length && rt_continue ) {
							RegenThumbs( rt_images.shift() );
						}
						else {
							RegenThumbsFinishUp();
						}
					}
				});
			}

			RegenThumbs( rt_images.shift() );
		});
	// ]]>
	</script>
<?php
		}

		// No button click? Display the form.
		else {
?>
	<form method="post" action="">
<?php wp_nonce_field(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE) ?>

	<!--<p><input type="submit" class="button hide-if-no-js" name="regenerate-thumbnails" id="regenerate-thumbnails" value="< ?php _e( 'Regenerate All Thumbnails', 'maxgalleria-media-library' ) ?>" /></p>-->
	<!--<p><button type="submit" class="button hide-if-no-js" name="regenerate-thumbnails" id="regenerate-thumbnails" value="< ?php _e( 'Regenerate All Thumbnails', 'maxgalleria-media-library' ) ?>" ><?php _e( 'Regenerate All Thumbnails', 'maxgalleria-media-library' ) ?></button></p>-->
<ul id="tn-row" class="all-tn">
  <li>    
    <button type="submit" class="button hide-if-no-js" name="regenerate-thumbnails" id="regenerate-thumbnails" value="<?php _e( 'Regenerate All Thumbnails', 'maxgalleria-media-library' ) ?>">
      <i class="fa-solid fa-images fa-3x"></i>
      <p><?php _e( 'Regenerate All Thumbnails', 'maxgalleria-media-library' ) ?></p>      
    </button>
  </li>  
</ul>

	<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'maxgalleria-media-library' ) ?></em></p></noscript>

	</form>
<?php
		} // End if button
?>
			</div>
		</div>
	</div>

<?php
	}
	
	public function ajax_process_image() {
    
    global $is_IIS;
    
		@error_reporting( 0 ); // Don't break the JSON result

		header( 'Content-type: application/json' );
		
		$id = (int) $_REQUEST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' != $image->post_type || 'image/' != substr( $image->post_mime_type, 0, 6 ) )
			die( json_encode( array( 'error' => sprintf( __( 'Failed resize: %s is an invalid image ID.', 'maxgalleria-media-library' ), esc_html( $_REQUEST['id'] ) ) ) ) );

		if ( ! current_user_can( $this->capability ) )
			$this->die_json_error_msg( $image->ID, __( "Your user account doesn't have permission to resize images", 'maxgalleria-media-library' ) );
		
		$fullsizepath = get_attached_file($image->ID);
    
    $scaled_position = strpos($fullsizepath, '-scaled');

    if($scaled_position != false) {
      $temp_path = substr($fullsizepath, 0, $scaled_position);
      $temp_path .= substr($fullsizepath, $scaled_position+7);
      //error_log("temp_path $temp_path");
      $fullsizepath = $temp_path;
    }
        
    do_action(MLFP_BEFORE_THUMBNAIL_REGEN, $id, $fullsizepath);                  
		
		//if(class_exists('MGMediaLibraryFoldersProS3'))
		if(class_exists('MGMediaLibraryFoldersProS3') && 
			($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING))
		  $original_found = $this->s3_addon->check_and_fetch_file($fullsizepath, $image->ID);		
		
		if ( false === $fullsizepath || ! file_exists( $fullsizepath ) )
			$this->die_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', 'maxgalleria-media-library' ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );

		@set_time_limit( 900 ); // 5 minutes per image should be PLENTY
		
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
      $this->remove_existing_thumbnails($image->ID, addslashes($fullsizepath));
		  $metadata = wp_generate_attachment_metadata( $image->ID, addslashes($fullsizepath));
    } else {
      $this->remove_existing_thumbnails($image->ID, $fullsizepath);
		  $metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );
    }  
    
    //error_log(print_r($metadata, true));

		if ( is_wp_error( $metadata ) )
			$this->die_json_error_msg( $image->ID, $metadata->get_error_message() );
		if ( empty( $metadata ) )
			$this->die_json_error_msg( $image->ID, __( 'Unknown failure reason.', 'maxgalleria-media-library' ) );

		// If this fails, then it just means that nothing was changed (old value == new value)
		wp_update_attachment_metadata( $image->ID, $metadata );
    
    do_action(MLFP_AFTER_THUMBNAIL_REGEN, $id, $fullsizepath, $metadata);                  
		
		if(class_exists('MGMediaLibraryFoldersProS3') && ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE)) {					
		//if(class_exists('MGMediaLibraryFoldersProS3')) {			
			
			if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {

				$file_url = wp_get_attachment_url($image->ID);

				$location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
				$destination_location = $this->s3_addon->get_destination_location($location);
				$destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);

				//$metadata = wp_get_attachment_metadata($image_id);
				if($original_found)
					$upload_result= $this->s3_addon->upload_to_s3("attachment", $location, $fullsizepath, $image->ID);
        
        if(isset($metadata['sizes'])) {
          foreach($metadata['sizes'] as $thumbnail) {
            $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
            $upload_result = $this->s3_addon->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
          }
        }

				if($this->s3_addon->remove_from_local) {
					$this->s3_addon->remove_media_file($fullsizepath);										
          if(isset($metadata['sizes'])) {
            foreach($metadata['sizes'] as $thumbnail) {
              $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
              $this->s3_addon->remove_media_file($source_file);
            }
          }
				}
			}
		}
		
		die( json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) was successfully resized in %3$s seconds.', 'maxgalleria-media-library' ), esc_html( get_the_title( $image->ID ) ), $image->ID, timer_stop() ) ) ) );
	}

	// Helper to make a JSON error message
	public function die_json_error_msg( $id, $message ) {
		die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) failed to resize. The error message was: %3$s', 'maxgalleria-media-library' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
	}


	// Helper function to escape quotes in strings for use in Javascript
	public function esc_quotes( $string ) {
		return str_replace( '"', '\"', $string );
	}
	
	public function image_seo() {
		
		?>
	
						<div id="mlp-left-column">
						
							<?php 
              
							$defatul_alt = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);
							$default_title = get_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT);
              
							if($defatul_alt === '')
								$defatul_alt = '%foldername - %filename';
							if($default_title === '')
								$default_title = '%foldername photo';

							$checked = get_option(MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO, 'off');						

							?>
							<table id="mlp-image-seo">
								<thead>
									<tr>
										<td colspan="3"><?php _e('Settings','maxgalleria-media-library'); ?></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php _e('Turn on Image SEO:','maxgalleria-media-library'); ?></td>
										<td><input name="seo-images" id="seo-images" type="checkbox" <?php checked($checked, 'on', true ); ?> </td>
										<td></td>
									</tr>
									<tr>
										<td><?php _e('Image ALT attribute:','maxgalleria-media-library'); ?></td>
										<td><input type="text" value="<?php echo $defatul_alt; ?>" name="default-alt" id="default-alt"></td>
										<td><em><?php _e('example','maxgalleria-media-library'); ?> %foldername - %filename</em></td>									
									</tr>
									<tr>
										<td><?php _e('Image Title attribute:','maxgalleria-media-library'); ?></td>
										<td><input type="text" value="<?php echo $default_title; ?>" name="default-title" id="default-title"></td>
										<td><em><?php _e('example','maxgalleria-media-library'); ?> %filename photo</em></td>									
									</tr>								
									<tr>
										<td colspan="3"><a class="button" id="mlp-update-seo-settings"><?php _e('Update Settings','maxgalleria-media-library'); ?></a></td>									
									</tr>
								</tbody>							
							</table>
							<div id="folder-message"></div>
						</div>    
												
					</div>    

		<?php
		
	}
	
	public function mlp_image_seo_change() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		    
    if ((isset($_POST['checked'])) && (strlen(trim($_POST['checked'])) > 0))
      $checked = trim(stripslashes(strip_tags($_POST['checked'])));
    else
      $checked = "off";
		
    if ((isset($_POST['default_alt'])) && (strlen(trim($_POST['default_alt'])) > 0))
      $default_alt = trim(stripslashes(strip_tags($_POST['default_alt'])));
    else
      $default_alt = "";
		
    if ((isset($_POST['default_title'])) && (strlen(trim($_POST['default_title'])) > 0))
      $default_title = trim(stripslashes(strip_tags($_POST['default_title'])));
    else
      $default_title = "";
    
    //error_log("SEO $default_alt, $default_title");
		
    update_option(MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO, $checked );		
		
    update_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT, $default_alt );		
		
    update_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT, $default_title );		
		
		echo __('The Image SEO settings have been updated ','maxgalleria-media-library');
				
		die();
		
		
	}
		
	public function get_browser() {
		// http://www.php.net/manual/en/function.get-browser.php#101125.
		// Cleaned up a bit, but overall it's the same.

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browser_name = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		// First get the platform
		if (preg_match('/linux/i', $user_agent)) {
			$platform = 'Linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
			$platform = 'Mac';
		}
		elseif (preg_match('/windows|win32/i', $user_agent)) {
			$platform = 'Windows';
		}
		
		// Next get the name of the user agent yes seperately and for good reason
		if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
			$browser_name = 'Internet Explorer';
			$browser_name_short = "MSIE";
		}
		elseif (preg_match('/Firefox/i', $user_agent)) {
			$browser_name = 'Mozilla Firefox';
			$browser_name_short = "Firefox";
		}
		elseif (preg_match('/Chrome/i', $user_agent)) {
			$browser_name = 'Google Chrome';
			$browser_name_short = "Chrome";
		}
		elseif (preg_match('/Safari/i', $user_agent)) {
			$browser_name = 'Apple Safari';
			$browser_name_short = "Safari";
		}
		elseif (preg_match('/Opera/i', $user_agent)) {
			$browser_name = 'Opera';
			$browser_name_short = "Opera";
		}
		elseif (preg_match('/Netscape/i', $user_agent)) {
			$browser_name = 'Netscape';
			$browser_name_short = "Netscape";
		}
		
		// Finally get the correct version number
		$known = array('Version', $browser_name_short, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $user_agent, $matches)) {
			// We have no matching number just continue
		}
		
		// See how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			// We will have two since we are not using 'other' argument yet
			// See if version is before or after the name
			if (strripos($user_agent, "Version") < strripos($user_agent, $browser_name_short)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// Check if we have a number
		if ($version == null || $version == "") { $version = "?"; }
		
		return array(
			'user_agent' => $user_agent,
			'name' => $browser_name,
			'version' => $version,
			'platform' => $platform,
			'pattern' => $pattern
		);
	}
	
	public function mlp_support() {
	  require_once 'includes/mlf_support.php';	 		
	}
  
  public function set_mlfp_user_access() {
	  require_once 'includes/user_access.php';	 		    
  }
  
  public function mlfp_maintenance() {
    $phpversion = phpversion();		
    if($phpversion >= '7.4')	{
      require_once 'includes/mlfp_maintenance.php';	 		        
    } else {
      $message_text = __('Media Library Maintenance requires PHP version 7.4 or greater. This site uses version %s.','maxgalleria-media-library');
      $message = sprintf($message_text, $phpversion);
      echo "<h3>" . $message . "</h3>";
    }
  }
	
	public  function mlp_remove_slashes() {

		global $wpdb;
			
    $sql = "select ID, pm.meta_value, pm.meta_id
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
where post_type = 'attachment' 
or post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE . "'
and pm.meta_key = '_wp_attached_file'
group by ID
order by meta_id";


		//echo $sql;

		$rows = $wpdb->get_results($sql);

		if($rows) {
			foreach($rows as $row) {
				if($row->meta_value !== '') {
					if( $row->meta_value[0] == "/") {
						$new_meta = $row->meta_value;
						$new_meta = ltrim($new_meta, '/');
						update_post_meta($row->ID, '_wp_attached_file', $new_meta);							
					}	
				}
			}
		}	
	}
	
	public function hide_maxgalleria_media() {
		
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
		if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = "";
    
    // prevent hiding of the uploads folder and sub folders  
    if(intval($folder_id) == intval($this->folder_id)) {
      echo __('The uploads folder cannot be hidden.','maxgalleria-media-library');
      die();
    }
    			
		if($folder_id !== '') {
			
			$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;			
			$parent_folder =  $this->get_parent($folder_id);
			
		  $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta
where post_id = $folder_id
and meta_key = '_wp_attached_file';";
	
			$row = $wpdb->get_row($sql);
			if($row) {
				
				$basedir = $this->upload_dir['basedir'];
				$basedir = rtrim($basedir, '/') . '/';
				$skip_folder_file = $basedir . ltrim($row->attached_file, '/') . DIRECTORY_SEPARATOR . "mlpp-hidden";
        
        do_action(MLFP_BEFORE_FOLDER_HIDE, $folder_id, $row->attached_file);        
        
				file_put_contents($skip_folder_file, '');
				
				$this->remove_children($folder_id);
        
        if($this->wpmf_integration == 'on') {
          $term_id = $this->mlfp_get_term_id($folder_id);
          wp_delete_term($term_id, WPMF_TAXO);
        }
                
				$del_post = array('post_id' => $folder_id);                        
				$this->mlf_delete_post($folder_id, true); //delete the post record
				$wpdb->delete( $folder_table, $del_post ); // delete the folder table record
								
        do_action(MLFP_AFTER_FOLDER_HIDE, $folder_id, $row->attached_file);        
			}
			
			$location = 'window.location.href = "' . site_url() . '/wp-admin/admin.php?page=mlfp-folders&media-folder=' . $parent_folder .'";';
			echo __('The selected folder, subfolders and thier files have been hidden.','maxgalleria-media-library');
			echo "<script>$location</script>";
					
		}	
		
		die();
	}
		
	private function remove_children($folder_id) {
		
    global $wpdb;
		
		if($folder_id !== 0) {
			
			$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
							
//		  $sql = "select post_id
//from $folder_table 
//where folder_id = $folder_id";
      
      $sql = "select post_id, post_type from $folder_table 
LEFT JOIN {$wpdb->prefix}posts ON($folder_table.post_id = {$wpdb->prefix}posts.ID)
where folder_id = $folder_id";
		
			$rows = $wpdb->get_results($sql);
			if($rows) {
				foreach($rows as $row) {

					$this->remove_children($row->post_id);
          
          if($this->wpmf_integration == 'on') {
            $term_id = $this->mlfp_get_term_id($row->post_id);
            if($row->post_type == MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE) {
              wp_delete_term($term_id, WPMF_TAXO);
            }                           
          }
                    
				  $del_post = array('post_id' => $row->post_id);                        
					$this->mlf_delete_post($row->post_id, false); //delete the post record
					$wpdb->delete( $folder_table, $del_post ); // delete the folder table record
								
				}
			}	
		}	
	}
	
	// modifed version of wp_delete_post
	private function mlf_delete_post( $postid = 0, $force_delete = false ) {
		global $wpdb;

		if ( !$post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", $postid)) )
			return $post;
    
		if ( !$force_delete && ( $post->post_type == 'post' || $post->post_type == 'page') && get_post_status( $postid ) != 'trash' && EMPTY_TRASH_DAYS )
			return wp_trash_post( $postid );

		delete_post_meta($postid,'_wp_trash_meta_status');
		delete_post_meta($postid,'_wp_trash_meta_time');

		wp_delete_object_term_relationships($postid, get_object_taxonomies($post->post_type));

		$parent_data = array( 'post_parent' => $post->post_parent );
		$parent_where = array( 'post_parent' => $postid );

		if ( is_post_type_hierarchical( $post->post_type ) ) {
			// Point children of this page to its parent, also clean the cache of affected children.
			$children_query = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_parent = %d AND post_type = %s", $postid, $post->post_type );
			$children = $wpdb->get_results( $children_query );
			if ( $children ) {
				$wpdb->update( $wpdb->posts, $parent_data, $parent_where + array( 'post_type' => $post->post_type ) );
			}
		}

		// Do raw query. wp_get_post_revisions() is filtered.
		$revision_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'revision'", $postid ) );
		// Use wp_delete_post (via wp_delete_post_revision) again. Ensures any meta/misplaced data gets cleaned up.
		foreach ( $revision_ids as $revision_id )
			wp_delete_post_revision( $revision_id );

		// Point all attachments to this post up one level.
		$wpdb->update( $wpdb->posts, $parent_data, $parent_where + array( 'post_type' => 'attachment' ) );

		wp_defer_comment_counting( true );

		$comment_ids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = %d", $postid ));
		foreach ( $comment_ids as $comment_id ) {
			wp_delete_comment( $comment_id, true );
		}

		wp_defer_comment_counting( false );

		$post_meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE post_id = %d ", $postid ));
		foreach ( $post_meta_ids as $mid )
			delete_metadata_by_mid( 'post', $mid );

		$result = $wpdb->delete( $wpdb->posts, array( 'ID' => $postid ) );
		if ( ! $result ) {
			return false;
		}

		if ( is_post_type_hierarchical( $post->post_type ) && $children ) {
			foreach ( $children as $child )
				clean_post_cache( $child );
		}

		wp_clear_scheduled_hook('publish_future_post', array( $postid ) );

		return $post;
	}
		
	public function get_file_thumbnail($ext) {
		switch ($ext) {

			case 'psd':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/psd.png";
				break;			
			
			// spread sheet
			case 'xlsx':
			case 'xlsm':
			case 'xlsb':
			case 'xltx':
			case 'xltm':
			case 'xlam':
			case 'ods':
			case 'numbers':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/xls.png";
				break;
			
			// video formats
			case 'asf':
			case 'asx':
			case 'wmv':
			case 'wmx':
			case 'wm':
			case 'avi':
			case 'divx':
			case 'flv':
			case 'mov':
			case 'qt':
			case 'mpeg':
			case 'mpg':
			case 'mpe':
			case 'mp4':
			case 'm4v':
			case 'ogv':
			case 'webm':
			case 'mkv':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/video.png";
				break;
			
			// text formats
			case 'txt':
			case 'asc':
			case 'c':
			case 'cc':
			case 'h':
			case 'js':
			case 'cpp':
			case 'csv':
			case 'tsv':
			case 'ics':
			case 'rtx':
			case 'css':
			case 'htm':
			case 'html':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/txt.png";
				break;

			case 'mp3':
			case 'm4a':
			case 'm4b':
			case 'ra':
			case 'ram':
			case 'wav':
			case 'ogg':
			case 'oga':
			case 'mid':
			case 'midi':
			case 'wma':
			case 'wax':
			case 'mka':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/audio.png";
				break;
			
			// archive formats
			case '7z':
			case 'rar':
			case 'gz':
			case 'gzip':
			case 'zip':
			case 'tar':
			case 'swf':
			case 'class':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/arch.png";
				break;

			// doc files
			case 'doc':
			case 'odt':
			case 'rtf':
			case 'wri':
			case 'mdb':
			case 'mpp':
			case 'docx':
			case 'docm':
			case 'dotx':
			case 'dotm':
			case 'wp':
			case 'wpd':
			case 'pages':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/doc.png";
				break;
			
			case 'pdf':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/pdf.png";
				break;
						
			// power point
			case 'pptx':
			case 'pptm':
			case 'ppsx':
			case 'ppsm':
			case 'potx':
			case 'potm':
			case 'ppam':
			case 'sldx':
			case 'sldm':
			case 'odp':
			case 'key':
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/ppt.png";
				break;
						
			default:
				$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
				break;
				
		}
		return $thumbnail;
	}
		
  public function media_category_taxonomy() {
	  require_once 'includes/mlfp-categories.php';	 
  }
	
	public function mlf_get_categories() {
		
    global $wpdb;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $image_id = trim(stripslashes(strip_tags($_POST['image_id'])));
    else
      $image_id = "";
		
		$category_terms = get_terms( MAXGALLERIA_MEDIA_LIBRARY_CATEGORY, array('fields' => 'all', 'hide_empty' => false));
		
		$terms = wp_get_post_terms( $image_id, MAXGALLERIA_MEDIA_LIBRARY_CATEGORY, array("fields" => "ids") );
		
		foreach($category_terms as $category_term) {
			if(in_array($category_term->term_id, $terms))
			  echo '<span class="mlf-cat-span"><span><input type="checkbox" class="mlf-cats" id="' . $category_term->term_id . '" value="" checked /></span>' . $category_term->name . '</span>&nbsp;&nbsp;';
			else
			  echo '<span class="mlf-cat-span"><span><input type="checkbox" class="mlf-cats" id="' . $category_term->term_id . '" value="" /></span>' . $category_term->name . '</span>&nbsp;&nbsp;';
		}
		
		die();
	}
	
	public function mlf_set_new_categories() {
		
    global $wpdb;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
				
		if ((isset($_POST['serial_image_ids'])) && (strlen(trim($_POST['serial_image_ids'])) > 0))
      $image_ids = trim(stripslashes(strip_tags($_POST['serial_image_ids'])));
    else
      $image_ids = "";
				        
    $image_ids = str_replace('"', '', $image_ids);    
    
    $image_ids = explode(',', $image_ids);
		
		if ((isset($_POST['serial_cat_ids'])) && (strlen(trim($_POST['serial_cat_ids'])) > 0))
      $cat_ids = trim(stripslashes(strip_tags($_POST['serial_cat_ids'])));
    else
      $cat_ids = "";
				    		
    $cat_ids = str_replace('"', '', $cat_ids);    
    
		$cat_ids = array_map('intval', explode(',', $cat_ids));
    
		foreach($image_ids as $image_id) {
			wp_set_object_terms( $image_id, $cat_ids, MAXGALLERIA_MEDIA_LIBRARY_CATEGORY, false );
		}
		
		echo  __('The media categories were updated.', 'maxgalleria-media-library');
			
		die();
		
	}
  
  	public function mlp_load_categories() {
      
    //error_log('mlp_load_categories');
	
    global $wpdb;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
		if ((isset($_POST['serial_cat_ids'])) && (strlen(trim($_POST['serial_cat_ids'])) > 0))
      $cat_ids = trim(stripslashes(strip_tags($_POST['serial_cat_ids'])));
    else
      $cat_ids = "";
		
		if ((isset($_POST['mif_visible'])) && (strlen(trim($_POST['mif_visible'])) > 0))
      $mif_visible = trim(stripslashes(strip_tags($_POST['mif_visible'])));
    else
      $mif_visible = "";
    
		if ((isset($_POST['page_id'])) && (strlen(trim($_POST['page_id'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['page_id']))));
    else
      $page_id = 0;
    						    		
    $cat_ids = str_replace('"', '', $cat_ids);    
    
		//$cat_ids = array_map('intval', explode(',', $cat_ids));
		
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER);
    $sort_type = trim(get_option( MAXGALLERIA_MLF_SORT_TYPE ));                
		$items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '40'));
    
    $offset = $page_id * $items_per_page;    
    
    $limit = "limit $offset, $items_per_page";
		
		$display_type = 0;
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date ' . $sort_type;
        break;
      
      case '1': //order by name
        $order_by = 'post_title ' . $sort_type;
        break;      
    }
											
												
    $sql = "SELECT SQL_CALC_FOUND_ROWS ID, post_title, cat_terms.name, meta.meta_value as attached_file
FROM {$wpdb->prefix}term_taxonomy AS cat_term_taxonomy
INNER JOIN {$wpdb->prefix}terms AS cat_terms ON cat_term_taxonomy.term_id = cat_terms.term_id
INNER JOIN {$wpdb->prefix}term_relationships AS cat_term_relationships ON cat_term_taxonomy.term_taxonomy_id = cat_term_relationships.term_taxonomy_id
INNER JOIN {$wpdb->prefix}posts AS cat_posts ON cat_term_relationships.object_id = cat_posts.ID
INNER JOIN {$wpdb->prefix}postmeta AS meta ON cat_posts.ID = meta.post_id
WHERE cat_term_taxonomy.taxonomy =  'media_category'
AND cat_terms.term_id IN ($cat_ids) 
AND meta.meta_key = '_wp_attached_file' 
GROUP BY ID
ORDER BY $order_by $limit";
						
    //error_log($sql);

    echo "<style>
          
      ul.mg-media-list li a img {
          width: 135px !important;
      }
   
    </style>";            

		$rows = $wpdb->get_results($sql);            
    $row_count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
		$num_records = $row_count['FOUND_ROWS()'];
    
    $total_images = $num_records;
    if($items_per_page != 0)
      $total_number_pages = ceil($total_images / $items_per_page);
    else
      $total_number_pages = 0;
        
    echo "<input type='hidden' id='mlfp-file-count' value='$total_images'>" . PHP_EOL;
    echo "<input type='hidden' id='mlfp-last-page' value='$total_number_pages'>" . PHP_EOL;						            
    echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'on', true);    
    echo '<ul class="mg-media-list">' . PHP_EOL;              
		
		if($num_records > 40 && $display_type === 0) {
			 ?>
				<p class="center-text"><?php echo $num_records; ?> files were found. Choose to display the images or just the file names?</p>
				<div class="center-text">
		      <a id="mlfp_display_category_images" cat_id="<?php echo $cat_ids; ?>" class="gray-blue-link">Display images</a>
			    <a id="mlfp_display_category_titles" cat_id="<?php echo $cat_ids; ?>" class="gray-blue-link">Display image file names only</a>				
				</div>	
			<?php
      die();		
		}
		
		if($rows) {
			$images_found = true;
			foreach($rows as $row) {
				$thumbnail = wp_get_attachment_thumb_url($row->ID);                
				if($thumbnail === false || $display_type == 2) {									
					$ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
					//$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
					$thumbnail = $this->get_file_thumbnail($ext);
				}  

				$checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
				$class = "media-attachment"; 
				$image_link = 1;

				// for WP 4.6 use /wp-admin/post.php?post=
				if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
					$media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
				else
					$media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;

				$baseurl = $this->upload_dir['baseurl'];
				$baseurl = rtrim($baseurl, '/') . '/';
				$image_location = $baseurl . ltrim($row->attached_file, '/');

				$filename = pathinfo($image_location, PATHINFO_BASENAME);

				echo "<li>" . PHP_EOL;
				if($mif_visible)
					echo "   <a id='$row->ID' class='$class' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				else if($image_link && !$mif_visible)
					echo "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				else
					echo "   <a id='$row->ID' class='$class' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				echo "   <div class='attachment-name'><span class='image_select'>$checkbox</span>$filename</div>" . PHP_EOL;
				echo "</li>" . PHP_EOL;              
			}      
		}
		echo '</ul>' . PHP_EOL;
		echo '      <script>' . PHP_EOL;
		echo '				jQuery(document).ready(function(){' . PHP_EOL;
		echo '					jQuery("div#mgmlp-tb-container input.mgmlp-media").show();' . PHP_EOL;
		echo '	        jQuery("a.tb-media-attachment").css("cursor", "default");' . PHP_EOL;
		echo '          jQuery("li a.media-attachment").draggable({' . PHP_EOL;
		echo '          	cursor: "move",' . PHP_EOL;
		echo '            helper: function() {' . PHP_EOL;
		echo '          	  var selected = jQuery(".mg-media-list input:checked").parents("li");' . PHP_EOL;
		echo '          	  if (selected.length === 0) {' . PHP_EOL;
		echo '          		  selected = jQuery(this);' . PHP_EOL;
		echo '          	  }' . PHP_EOL;
		echo '          	  var container = jQuery("<div/>").attr("id", "draggingContainer");' . PHP_EOL;
		echo '          	  container.append(selected.clone());' . PHP_EOL;
		echo '          	  return container;' . PHP_EOL;
		echo '            }' . PHP_EOL;
		echo '          });' . PHP_EOL;
		echo '        });' . PHP_EOL;
		echo '      </script>' . PHP_EOL;


		if(!$images_found) {
			echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
    } else {
      if($this->license_valid) {                    
        echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, true);
      }  
    }
      
						    
		die();
		
	}
	  
	public function mlp_load_categories_list() {
    
    //error_log('mlp_load_categories_list');    
    
    global $wpdb;
    $image_link = 1;
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
		if ((isset($_POST['serial_cat_ids'])) && (strlen(trim($_POST['serial_cat_ids'])) > 0))
      $cat_ids = trim(stripslashes(strip_tags($_POST['serial_cat_ids'])));
    else
      $cat_ids = "";
		
		if ((isset($_POST['mif_visible'])) && (strlen(trim($_POST['mif_visible'])) > 0))
      $mif_visible = trim(stripslashes(strip_tags($_POST['mif_visible'])));
    else
      $mif_visible = "";
    
		if ((isset($_POST['page_id'])) && (strlen(trim($_POST['page_id'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['page_id']))));
    else
      $page_id = 0;
    						    		
    $cat_ids = str_replace('"', '', $cat_ids);    
    
		//$cat_ids = array_map('intval', explode(',', $cat_ids));
		
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER);
    
    $sort_type = trim(get_option( MAXGALLERIA_MLF_SORT_TYPE ));            
    
    $items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '20'));
    
    $offset = $page_id * $items_per_page;

    $limit = "limit $offset, $items_per_page";
        
		$display_type = 0;
    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date ' . $sort_type;
        break;
      
      case '1': //order by name
        $order_by = 'post_title ' . $sort_type;
        break;      
    }
																							
    $sql = "SELECT SQL_CALC_FOUND_ROWS cat_posts.ID, post_title, post_date, us.display_name, cat_terms.name, meta.meta_value as attached_file
FROM {$wpdb->prefix}term_taxonomy AS cat_term_taxonomy
INNER JOIN {$wpdb->prefix}terms AS cat_terms ON cat_term_taxonomy.term_id = cat_terms.term_id
INNER JOIN {$wpdb->prefix}term_relationships AS cat_term_relationships ON cat_term_taxonomy.term_taxonomy_id = cat_term_relationships.term_taxonomy_id
INNER JOIN {$wpdb->prefix}posts AS cat_posts ON cat_term_relationships.object_id = cat_posts.ID
INNER JOIN {$wpdb->prefix}postmeta AS meta ON cat_posts.ID = meta.post_id
LEFT JOIN {$wpdb->prefix}users AS us ON (cat_posts.post_author = us.ID) 
WHERE cat_term_taxonomy.taxonomy =  'media_category'
AND cat_terms.term_id IN ($cat_ids) 
AND meta.meta_key = '_wp_attached_file' 
ORDER BY $order_by $limit";

//GROUP BY ID
						
    //error_log($sql);

		$rows = $wpdb->get_results($sql);            
    
    $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
    $total_images = $count['FOUND_ROWS()'];
    if($items_per_page != 0)
      $total_number_pages = ceil($total_images / $items_per_page);
    else
      $total_number_pages = 0;
    
    echo "<style>
          
    ul.mg-media-list li {
      display: table-row;
      float: none;
      /*height: 40px;*/
      list-style: outside none none;
      margin: 0;
      max-width: none;
      overflow: visible;
      width: 100%;
    }
    
    ul.mg-media-list li {
      height: auto;
    }
   
    </style>";
            
    echo "<input type='hidden' id='mlfp-file-count' value='$total_images'>" . PHP_EOL;
    echo "<input type='hidden' id='mlfp-last-page' value='$total_number_pages'>" . PHP_EOL;						            
    echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'on', true);    
    echo '<ul class="mg-media-list">' . PHP_EOL;              
    
    if($rows) {
      $images_found = true;
      $counter = 1;
      //echo '<ul class="mg-media-list">' . PHP_EOL;
      foreach($rows as $row) {
        $thumbnail_html = "";
        $image_file_type = true;
        if($display_type == 1 || $display_type == 0) {
          $new_attachment_id = $row->ID;
          //if(is_array($new_attachment_id))
            //error_log("wp_get_attachment_image id");
          $thumbnail_html = wp_get_attachment_image( $new_attachment_id, 'thumbnail', false, '');
          if(!$thumbnail_html){
            $thumbnail = wp_get_attachment_thumb_url($new_attachment_id);                
            //if(is_array($thumbnail))
            //  error_log("wp_get_attachment_image thumbnail");
            if($thumbnail === false || $display_type == 2) {									
              $ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
              //if(is_array($ext))
              //  error_log("wp_get_attachment_image ext");
              //$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
              $thumbnail = $this->get_file_thumbnail($ext);
              $image_file_type = false;
            }
            $thumbnail_html = "<img alt='' src='$thumbnail' />";
          }  
        } else {
          $thumbnail_html = "";
        }

        $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
        
        $s3_class = "";
        if($display_type == 1 || $display_type == 0) {
          if(class_exists('MGMediaLibraryFoldersProS3')) {			
            if($this->s3_addon->s3_active) {
              if($this->s3_addon->serve_from_s3) {
                //error:log("thumbnail $thumbnail");
                if($image_file_type) {
                  if(!strpos($thumbnail_html, $this->s3_addon->bucket))
                    $s3_class = "on-local";
                }
              }					
            }
          }
        }

        // for WP 4.6 use /wp-admin/post.php?post=
        if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
          $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
        else
          $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;
        
        
        $baseurl = $this->upload_dir['baseurl'];
        $baseurl = rtrim($baseurl, '/') . '/';
        $image_location = $baseurl . ltrim($row->attached_file, '/');
        $filename = pathinfo($image_location, PATHINFO_BASENAME);
        
        if($counter % 2)
          echo '<li class="row-item gray-row">';
        else
          echo '<li class="row-item">';
        echo '  <span class="mlfp-list-cb">'.$checkbox.'</span>';
        echo '  <span class="mlfp-list-image"><a id="'.$row->ID.'" class="media-attachment list edit-link" >'.$thumbnail_html.'</a></span>';
        echo '  <span class="mlfp-list-title">'.$row->post_title.'</span>';
        echo '  <span class="mlfp-list-file '.$s3_class.'">'.$filename.'</span>';
        echo '  <span class="mlfp-list-author">'.$row->display_name.'</span>';
        echo '  <span class="mlfp-list-cat">'. $this->get_media_categories($row->ID) .'</span>';
        echo '  <span class="mlfp-list-date">'. date("Y-m-d", strtotime($row->post_date)) .'</span>';
        echo '</li>';
        
        $counter++;
      }      
      echo "</ul>" . PHP_EOL;      
    }
    echo '<div style="clear:both"></div>' . PHP_EOL;
    
    $this->insert_mlfp_js();

    if(!$images_found) {
      echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
    } else {
      if($this->license_valid) {                    
        echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, true);
      }  
    }
    
    
//    else {
//      $previous_page = $page_id - 1;
//      $next_page = $page_id + 1;
//      echo "<div class='mlfp-page-nav'>" . PHP_EOL;
//      if($page_id > 0)	
//        echo "<a id='mlfp-previous-cats' page-id='$previous_page' image_link='$image_link' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
//      if($page_id < $total_number_pages-1 && $total_images > $items_per_page)
//        echo "<a id='mlfp-next-cats' page-id='$next_page' image_link='$image_link' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
//      echo "</div>" . PHP_EOL;
//    }
						    
		die();
		
	}
  
  public function mlfp_get_next_attachments_categories() {

		die();
  }
	
	public function mgmlp_add_new_category() {
		
    global $wpdb;
		
		$output = __('The category was not added.','maxgalleria-media-library');
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
				
		if ((isset($_POST['new_category'])) && (strlen(trim($_POST['new_category'])) > 0))
      $new_category = trim(stripslashes(strip_tags($_POST['new_category'])));
    else
      $new_category = "";
		
		if($new_category !== '') {
      
      do_action(MLFP_BEFORE_ADD_CATEGORY, $new_category);      
			
			$result = wp_insert_term( $new_category, MAXGALLERIA_MEDIA_LIBRARY_CATEGORY);
			
			if(!is_wp_error( $result)) {
        do_action(MLFP_AFTER_ADD_CATEGORY, $new_category, $result['term_id'], $result['term_taxonomy_id']);      
		    $output = __("The category $new_category was added.",'maxgalleria-media-library');			
      }  
		}
		
		echo $output;
		
	  die();
	}
	
	public function mlp_load_categories_ajax() {
		
    global $wpdb;
		$output = "";
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
		if ((isset($_POST['cat_id'])) && (strlen(trim($_POST['cat_id'])) > 0))
      $cat_id = trim(stripslashes(strip_tags($_POST['cat_id'])));
    else
      $cat_id = "";
						
		if ((isset($_POST['display_type'])) && (strlen(trim($_POST['display_type'])) > 0))
      $display_type = trim(stripslashes(strip_tags($_POST['display_type'])));
    else
      $display_type = "0";
		
		if ((isset($_POST['mif_visible'])) && (strlen(trim($_POST['mif_visible'])) > 0))
      $mif_visible = trim(stripslashes(strip_tags($_POST['mif_visible'])));
    else
      $mif_visible = "";
								
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER);
		    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date DESC';
        break;
      
      case '1': //order by name
        $order_by = 'post_title';
        break;      
    }
		
    $output .= '<ul class="mg-media-list">' . PHP_EOL;              
		
    $sql = "SELECT ID, post_title, cat_terms.name, meta.meta_value as attached_file
FROM {$wpdb->prefix}term_taxonomy AS cat_term_taxonomy
INNER JOIN {$wpdb->prefix}terms AS cat_terms ON cat_term_taxonomy.term_id = cat_terms.term_id
INNER JOIN {$wpdb->prefix}term_relationships AS cat_term_relationships ON cat_term_taxonomy.term_taxonomy_id = cat_term_relationships.term_taxonomy_id
INNER JOIN {$wpdb->prefix}posts AS cat_posts ON cat_term_relationships.object_id = cat_posts.ID
INNER JOIN {$wpdb->prefix}postmeta AS meta ON cat_posts.ID = meta.post_id
WHERE cat_term_taxonomy.taxonomy =  'media_category'
AND cat_terms.term_id IN ($cat_id) 
AND meta.meta_key = '_wp_attached_file' 
GROUP BY ID
ORDER BY $order_by";

    //error_log($sql);
				
		$rows = $wpdb->get_results($sql);            
		if($rows) {
			$images_found = true;
			foreach($rows as $row) {
				$thumbnail = wp_get_attachment_thumb_url($row->ID);                
				if($thumbnail === false || $display_type == 2) {									
					$ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
					//$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
					$thumbnail = $this->get_file_thumbnail($ext);
				}  

				$checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
				$class = "media-attachment"; 
				$image_link = 1;

				// for WP 4.6 use /wp-admin/post.php?post=
				if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
					$media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
				else
					$media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;

				$baseurl = $this->upload_dir['baseurl'];
				$baseurl = rtrim($baseurl, '/') . '/';
				$image_location = $baseurl . ltrim($row->attached_file, '/');

				$filename = pathinfo($image_location, PATHINFO_BASENAME);

				$output .= "<li>" . PHP_EOL;
				if($mif_visible)
					$output .= "   <a id='$row->ID' class='$class' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				else if($image_link && !$mif_visible)
					$output .= "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				else
					$output .= "   <a id='$row->ID' class='$class' title='$filename'><img alt='' src='$thumbnail' /></a>" . PHP_EOL;
				$output .= "   <div class='attachment-name'><span class='image_select'>$checkbox</span>$filename</div>" . PHP_EOL;
				$output .= "</li>" . PHP_EOL;              
			}      
		}
		$output .= '</ul>' . PHP_EOL;

		$output .= '      <script>' . PHP_EOL;
		$output .= '				jQuery(document).ready(function(){' . PHP_EOL;
		$output .= '					jQuery("div#mgmlp-tb-container input.mgmlp-media").show();' . PHP_EOL;
		$output .= '	        jQuery("a.tb-media-attachment").css("cursor", "default");' . PHP_EOL;
		$output .= '          jQuery("li a.media-attachment").draggable({' . PHP_EOL;
		$output .= '          	cursor: "move",' . PHP_EOL;
    $output .= '            cursorAt: { left: 70, top: 70 },' . PHP_EOL;
		$output .= '            helper: function() {' . PHP_EOL;
		$output .= '          	  var selected = jQuery(".mg-media-list input:checked").parents("li");' . PHP_EOL;
		$output .= '          	  if (selected.length === 0) {' . PHP_EOL;
		$output .= '          		  selected = jQuery(this);' . PHP_EOL;
		$output .= '          	  }' . PHP_EOL;
		$output .= '          	  var container = jQuery("<div/>").attr("id", "draggingContainer");' . PHP_EOL;
		$output .= '          	  container.append(selected.clone());' . PHP_EOL;
		$output .= '          	  return container;' . PHP_EOL;
		$output .= '            }' . PHP_EOL;
		$output .= '          });' . PHP_EOL;
		$output .= '        });' . PHP_EOL;
		$output .= '      </script>' . PHP_EOL;

		if(!$images_found)
			$output .= "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
		
    echo $output;
		die();
		
	}
			
	public function add_mlfp_button( $integration_button ) {
		
		//$integration_button .= '<input type="button" class="button button-upload et-pb-upload-button" value="%3$s" data-choose="%4$s" data-update="%5$s" data-type="%6$s" />%7$s <input type="button" value="old Button">';
		$integration_button .= '<input class="button button-upload et-pb-upload-button" value="Media Library Folders Pro" data-choose="Choose an Image" data-update="Set As Image" data-type="image" type="button" value="New Button">>';
		
	}
	
	public function mlf_hide_info() {
				
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 
		
    $current_user_id = get_current_user_id(); 
            
    update_user_meta( $current_user_id, MAXGALLERIA_MLP_DISPLAY_INFO, 'off' );
				
	}
	
	public function update_mlfp_settings() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
		if ((isset($_POST['enable_fe_scripts'])) && (strlen(trim($_POST['enable_fe_scripts'])) > 0))
      $enable_fe_scripts = trim(stripslashes(strip_tags($_POST['enable_fe_scripts'])));
    else
      $enable_fe_scripts = "";

		//if ((isset($_POST['floating_ft_disabled'])) && (strlen(trim($_POST['floating_ft_disabled'])) > 0))
    //  $floating_ft_disabled = trim(stripslashes(strip_tags($_POST['floating_ft_disabled'])));
    //else
    //  $floating_ft_disabled = "";
		
		if ((isset($_POST['pagnation_enabled'])) && (strlen(trim($_POST['pagnation_enabled'])) > 0))
      $pagnation_enabled = trim(stripslashes(strip_tags($_POST['pagnation_enabled'])));
    else
      $pagnation_enabled = "";
		
		if ((isset($_POST['images_per_page'])) && (strlen(trim($_POST['images_per_page'])) > 0))
      $images_per_page = trim(sanitize_text_field($_POST['images_per_page']));
    else
      $images_per_page = "40";
    
		if ((isset($_POST['enable_upload'])) && (strlen(trim($_POST['enable_upload'])) > 0))
      $enable_upload = trim(stripslashes(strip_tags($_POST['enable_upload'])));
    else
      $enable_upload = "";
		        
		if ((isset($_POST['disable_popup_ft'])) && (strlen(trim($_POST['disable_popup_ft'])) > 0))
      $disable_popup_ft = trim(stripslashes(strip_tags($_POST['disable_popup_ft'])));
    else
      $disable_popup_ft = "";
        
		if ((isset($_POST['enable_user_role'])) && (strlen(trim($_POST['enable_user_role'])) > 0))
      $enable_user_role = trim(stripslashes(strip_tags($_POST['enable_user_role'])));
    else
      $enable_user_role = "";
        
		if ((isset($_POST['disable_scaling'])) && (strlen(trim($_POST['disable_scaling'])) > 0))
      $disable_scaling = trim(stripslashes(strip_tags($_POST['disable_scaling'])));
    else
      $disable_scaling = "";
    
		if ((isset($_POST['rollback_scaling'])) && (strlen(trim($_POST['rollback_scaling'])) > 0))
      $rollback_scaling = trim(stripslashes(strip_tags($_POST['rollback_scaling'])));
    else
      $rollback_scaling = "";
            
		if ((isset($_POST['disable_list_mode'])) && (strlen(trim($_POST['disable_list_mode'])) > 0))
      $disable_list_mode = trim(stripslashes(strip_tags($_POST['disable_list_mode'])));
    else
      $disable_list_mode = "";
        
		if ((isset($_POST['disable_non_admins'])) && (strlen(trim($_POST['disable_non_admins'])) > 0))
      $disable_non_admins = trim(stripslashes(strip_tags($_POST['disable_non_admins'])));
    else
      $disable_non_admins = "";
    
		if ((isset($_POST['caption_import'])) && (strlen(trim($_POST['caption_import'])) > 0))
      $caption_import = trim(stripslashes(strip_tags($_POST['caption_import'])));
    else
      $caption_import = "";    
    
		if ((isset($_POST['use_locale'])) && (strlen(trim($_POST['use_locale'])) > 0))
      $use_locale = trim(sanitize_text_field($_POST['use_locale']));
    else
      $use_locale = "";
    
		if ((isset($_POST['locale'])) && (strlen(trim($_POST['locale'])) > 0))
      $locale = trim(stripslashes(strip_tags($_POST['locale'])));
    else
      $locale = "";
        
		if ((isset($_POST['meta_index'])) && (strlen(trim($_POST['meta_index'])) > 0))
      $meta_index = trim(stripslashes(strip_tags($_POST['meta_index'])));
    else
      $meta_index = "";
    
		//$use_set_locale = get_option(MAXGALLERIA_USE_SET_LOCALE, 'no' );
		//$locale = get_option(MAXGALLERIA_LOCALE, '' );
    
    		
		$current_user_id = get_current_user_id(); 
		// if checked, disable
		//if($floating_ft_disabled == 'true')
		//	update_user_meta( $current_user_id, MAXGALLERIA_MLP_DISABLE_FT, 'on' );
		//else
		//	update_user_meta( $current_user_id, MAXGALLERIA_MLP_DISABLE_FT, 'off' );						
		
		if($pagnation_enabled == 'true')
		  update_option(MAXGALLERIA_MLP_PAGINATION, 'on', true);
		else
		  update_option(MAXGALLERIA_MLP_PAGINATION, 'off', true);
    
		if($enable_upload == 'true')
		  update_option(MAXGALLERIA_MLP_UPLOAD, 'on', true);
		else
		  update_option(MAXGALLERIA_MLP_UPLOAD, 'off', true);
        
    //error_log("disable_popup_ft $disable_popup_ft");
		if($disable_popup_ft == 'true')
		  update_option(MAXGALLERIA_REMOVE_FT, 'on', true);
		else
		  update_option(MAXGALLERIA_REMOVE_FT, 'off', true);
    
		if($enable_fe_scripts == 'true')
		  update_option(MAXGALLERIA_FE_SCRIPTS, 'on', true);
		else
		  update_option(MAXGALLERIA_FE_SCRIPTS, 'off', true);
    
		if($enable_user_role == 'true')
		  update_option(MAXGALLERIA_RESTRICT_USER_ROLE, 'on', true);
		else
		  update_option(MAXGALLERIA_RESTRICT_USER_ROLE, 'off', true);
    
		if($disable_scaling == 'true')
		  update_option(MAXGALLERIA_DISABLE_SCALLING, 'on', true);
		else
		  update_option(MAXGALLERIA_DISABLE_SCALLING, 'off', true);
        
		if($rollback_scaling == 'true')
		  update_option(MAXGALLERIA_ROLLBACK_SCALLING, 'on', true);
		else
		  update_option(MAXGALLERIA_ROLLBACK_SCALLING, 'off', true);
        
		if($disable_list_mode == 'true')
		  update_option(MAXGALLERIA_DISABLE_LIST_MODE, 'on', true);
		else
		  update_option(MAXGALLERIA_DISABLE_LIST_MODE, 'off', true);
        
		if($disable_non_admins == 'true')
		  update_option(MAXGALLERIA_DISABLE_NON_ADMINS, 'on', true);
		else
		  update_option(MAXGALLERIA_DISABLE_NON_ADMINS, 'off', true);
        
		if($caption_import == 'true')
		  update_option(MLFP_PREVENT_CAPTION_IMPORT, 'on', true);
		else
		  update_option(MLFP_PREVENT_CAPTION_IMPORT, 'off', true);
    
		if($use_locale == 'true')
		  update_option(MAXGALLERIA_USE_SET_LOCALE, 'on', true);
		else
		  update_option(MAXGALLERIA_USE_SET_LOCALE, 'off', true);
    
		update_option(MAXGALLERIA_LOCALE, $locale, true);
        
		update_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, $images_per_page, true);
    
    if($meta_index == 'true') {
      $this->add_postmeta_index();
      update_option(MAXGALLERIA_POSTMETA_INDEX, 'on', true);
    } else {
      $this->remove_postmeta_index();
      update_option(MAXGALLERIA_POSTMETA_INDEX, 'off', true);
    }
				
		echo __( 'The settings were updated.', 'maxgalleria-media-library' );
		
		die();
	}
  
  public function add_postmeta_index() {
    
    global $wpdb;
    
    $sql = "ALTER TABLE $wpdb->postmeta ADD INDEX mg_meta_value (meta_key ASC, meta_value(255) ASC);";
    
    //error_log($sql);
    
    $wpdb->get_results($sql);
    
  }
  
  public function remove_postmeta_index() {
    
    global $wpdb;    
    
    $sql = "DROP INDEX mg_meta_value ON $wpdb->postmeta";
    
    //error_log($sql);    
    
    $wpdb->get_results($sql);    
    
  }
	
//	public function mlfp_update_meta_info() {
//		
//    global $wpdb;
//				
//    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
//      exit(__('missing nonce!','maxgalleria-media-library'));
//    }
//		
//		if ((isset($_POST['mlp_image_id'])) && (strlen(trim($_POST['mlp_image_id'])) > 0))
//      $mlp_image_id = trim(stripslashes(strip_tags($_POST['mlp_image_id'])));
//    else
//      $mlp_image_id = "";
//				
//		if ((isset($_POST['mlp_title'])) && (strlen(trim($_POST['mlp_title'])) > 0))
//      $mlp_title = trim(stripslashes(strip_tags($_POST['mlp_title'])));
//    else
//      $mlp_title = "";
//		
//		if ((isset($_POST['mlp_caption'])) && (strlen(trim($_POST['mlp_caption'])) > 0))
//      $mlp_caption = trim(stripslashes(strip_tags($_POST['mlp_caption'])));
//    else
//      $mlp_caption = "";
//		
//		if ((isset($_POST['mlp_desc'])) && (strlen(trim($_POST['mlp_desc'])) > 0))
//      $mlp_desc = trim(stripslashes(strip_tags($_POST['mlp_desc'])));
//    else
//      $mlp_desc = "";
//		
//		if ((isset($_POST['mlp_alt'])) && (strlen(trim($_POST['mlp_alt'])) > 0))
//      $mlp_alt = trim(stripslashes(strip_tags($_POST['mlp_alt'])));
//    else
//      $mlp_alt = "";
//		
//		if($mlp_image_id != "") {
//			
//			update_post_meta($mlp_image_id, '_wp_attachment_image_alt', $mlp_alt);
//					
//			$table = $wpdb->prefix . "posts";
//			
//			$data = array(
//					'post_title' => $mlp_title,
//					'post_excerpt' => $mlp_caption,
//					'post_content' => $mlp_desc
//			);
//			
//			$where = array('ID' => $mlp_image_id);
//			
//			$wpdb->update($table, $data, $where);
//			
//		}
//		
//		echo "ok";
//		die();
//		
//		
//	}
	
	public function mlfp_get_file_info() {
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    } 

		if ((isset($_POST['file_id'])) && (strlen(trim($_POST['file_id'])) > 0))
      $file_id = trim(stripslashes(strip_tags($_POST['file_id'])));
    else
      $file_id = "";
		
		if($file_id != "") {
			
			$baseurl = $this->upload_dir['baseurl'];
			$baseurl = rtrim($baseurl, '/') . '/';
						
			$file_size = get_post_meta($file_id, 'wpmf_size', true);
			$filetype = get_post_meta($file_id, 'wpmf_filetype', true);
			$file_location = get_post_meta($file_id, '_wp_attached_file', true);
			$file_path = $baseurl . ltrim($file_location, '/');
			
			$include_path = site_url() . "/wp-includes/images/media/";
			
 			switch($filetype) {
							
			// spread sheet
			case 'xlsx':
			case 'xlsm':
			case 'xlsb':
			case 'xltx':
			case 'xltm':
			case 'xlam':
			case 'ods':
			case 'numbers':
				$icon_file = $include_path . "spreadsheet.png";
				break;
			
			// video formats
			case 'asf':
			case 'asx':
			case 'wmv':
			case 'wmx':
			case 'wm':
			case 'avi':
			case 'divx':
			case 'flv':
			case 'mov':
			case 'qt':
			case 'mpeg':
			case 'mpg':
			case 'mpe':
			case 'mp4':
			case 'm4v':
			case 'ogv':
			case 'webm':
			case 'mkv':
				$icon_file = $include_path . "video.png";
				break;
			
			// text formats
			case 'txt':
			case 'asc':
			case 'c':
			case 'cc':
			case 'h':
			case 'js':
			case 'php':
			case 'cpp':
			case 'csv':
			case 'tsv':
			case 'ics':
			case 'rtx':
			case 'css':
			case 'htm':
			case 'html':
				$icon_file = $include_path . "text.png";
				break;

			case 'mp3':
			case 'm4a':
			case 'm4b':
			case 'ra':
			case 'ram':
			case 'wav':
			case 'ogg':
			case 'oga':
			case 'mid':
			case 'midi':
			case 'wma':
			case 'wax':
			case 'mka':
				$icon_file = $include_path . "audio.png";
				break;
			
			// archive formats
			case '7z':
			case 'rar':
			case 'gz':
			case 'gzip':
			case 'zip':
			case 'tar':
			case 'swf':
			case 'class':
				$icon_file = $include_path . "archive.png";
				break;

			// doc files
			case 'pdf':
			case 'doc':
			case 'odt':
			case 'rtf':
			case 'wri':
			case 'mdb':
			case 'mpp':
			case 'docx':
			case 'docm':
			case 'dotx':
			case 'dotm':
			case 'wp':
			case 'wpd':
			case 'pages':
				$icon_file = $include_path . "document.png";
				break;
						
			default:
				$icon_file = $include_path . "default.png";
				break;
			       				 			 
			}
			
			$data = array('filetype' => $filetype, 'filesize' => $file_size, 'iconfile' => $icon_file, 'filepath' => $file_path );
				
			echo json_encode($data);
				
		}
				
		die();
		
	}
	
	public function remove_media_file($source_file) {
		if(file_exists($source_file))
			unlink($source_file);		
	}
		
	private function update_post_links($image_location, $destination_url ) {
		global $wpdb;
		
		$replace_image_location = $this->get_base_file($image_location);
    //error_log("get_base_file $replace_image_location");
		$replace_destination_url = $this->get_base_file($destination_url);
		$replace_sql = "UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE (`post_content`, '$replace_image_location', '$replace_destination_url');";
		$result = $wpdb->query($replace_sql);
    
    $replace_sql = str_replace ( '/', '\\/', $replace_sql);
    //error_log($replace_sql);
    $result = $wpdb->query($replace_sql);
    
		
	}
  
  public function get_folder_location($upload_id) {
    
    $folder = trim(get_post_meta( $upload_id, '_wp_attached_file', true ));
    // if not empty, add slash
    if($folder)
      $folder = '/' . $folder;
    return $this->uploads_folder_name . $folder;
    
  }
  
  public function max_discover_files($parent_folder) {
    
    global $wpdb, $is_IIS;
    $user_id = get_current_user_id();
    $files_to_add = array();
    $files_count = 0;
        
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
      
    $sql = "select ID, pm.meta_value as attached_file, post_title, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' 
and folder_id = '$parent_folder' 
and pm.meta_key = '_wp_attached_file'	
order by post_title";

    //error_log($sql);
    
    $attachments = $wpdb->get_results($sql);
		      
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta
where post_id = $parent_folder    
and meta_key = '_wp_attached_file'";	

    //error_log($sql);

    $current_row = $wpdb->get_row($sql);
		
    //$image_location = $this->upload_dir['baseurl'] . '/' . $current_row->attached_file;
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($current_row->attached_file, '/');
		
    $folder_path = $this->get_absolute_path($image_location);
    
    update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH_ID, $parent_folder);
        
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, str_replace('\\', '\\\\', $folder_path));
    else
      update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, $folder_path);
    //error_log("folder_path $folder_path");
    $folder_contents = array_diff(scandir($folder_path), array('..', '.'));
						
    foreach ($folder_contents as $file_path) {
      
      //error_log($file_path);
			
			if($file_path !== '.DS_Store' && $file_path !== '.htaccess') {
				$new_attachment = $folder_path . DIRECTORY_SEPARATOR . $file_path;
				if(!strpos($new_attachment, '-uai-')) {  // skip thumbnails created by the Uncode theme
				  if(!strpos($new_attachment, '-scaled')) {  // skip thumbnails created by the Uncode theme
            if(!strpos($new_attachment, '-pdf.jpg')) {  // skip pdf thumbnails
              if(!is_dir($new_attachment)) {
                if($this->is_base_file($file_path, $folder_contents)) {				
                  if(!$this->search_folder_attachments($file_path, $attachments)) {

                    $old_attachment_name = $new_attachment;
                    //$new_attachment = pathinfo($new_attachment, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($new_attachment, PATHINFO_FILENAME) . "." . strtolower(pathinfo($new_attachment, PATHINFO_EXTENSION));
                    $new_attachment = pathinfo($new_attachment, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . sanitize_file_name(pathinfo($new_attachment, PATHINFO_FILENAME) . "." . strtolower(pathinfo($new_attachment, PATHINFO_EXTENSION)));

                    if(rename($old_attachment_name, $new_attachment)) {	
                      $files_to_add[] = basename($new_attachment);
                      $files_count++;
                    } else {
                      $files_to_add[] = basename($old_attachment_name);
                      $files_count++;
                    }
                  }	
                }
              } 
            }
          }
				}
			}		
		}
    
    if(is_array($files_to_add)) {
      update_user_meta($user_id, MAXG_SYNC_FILES, $files_to_add);
    }
    if($files_count > 0)
      return '3'; // add the files
    else
      return '2'; // check next folder
   		
  }
  
  public function mlfp_run_sync_process() {
    
    global $wpdb;
		$user_id = get_current_user_id();
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
		if ((isset($_POST['phase'])) && (strlen(trim($_POST['phase'])) > 0))
      $phase = trim(stripslashes(strip_tags($_POST['phase'])));
    else
      $phase = "";
    
		if ((isset($_POST['parent_folder'])) && (strlen(trim($_POST['parent_folder'])) > 0))
      $parent_folder = trim(stripslashes(strip_tags($_POST['parent_folder'])));
    else
      $parent_folder = "";
        
    if ((isset($_POST['mlp_title_text'])) && (strlen(trim($_POST['mlp_title_text'])) > 0))
      $mlp_title_text = trim(stripslashes(strip_tags($_POST['mlp_title_text'])));
    else
      $mlp_title_text = "";

    if ((isset($_POST['mlp_alt_text'])) && (strlen(trim($_POST['mlp_alt_text'])) > 0))
      $mlp_alt_text = trim(stripslashes(strip_tags($_POST['mlp_alt_text'])));
    else
      $mlp_alt_text = "";
    
    $next_phase = '1';
    
    switch($phase) {
      // find folders
      case '1':
        $next_phase = '2';
        $this->max_sync_contents($parent_folder);
        break;
      
      // for each folder. get the folder ids
      case '2':
        
		    $folders_array = get_user_meta($user_id, MAXG_SYNC_FOLDERS, true);
                
        if(is_array($folders_array)) {
          $next_folder = array_pop($folders_array);				
        } else {
          $next_folder = $folders_array;
        }  
        //error_log("next_folder $next_folder");
        
        if($next_folder != "") {
          $message = __("Scanning for new files and folders...please wait.",'maxgalleria-media-library');        
          $this->max_discover_files($next_folder);
          update_user_meta($user_id, MAXG_SYNC_FOLDERS, $folders_array);
          $next_phase = '3';          
        } else {
          $message = __("Syncing finished.",'maxgalleria-media-library');        
          delete_user_meta($user_id, MAXG_SYNC_FOLDERS);
          delete_user_meta($user_id, MAXG_SYNC_FILES);          
          delete_user_meta($user_id, MAXG_SYNC_FOLDER_PATH_ID);          
          delete_user_meta($user_id, MAXG_SYNC_FOLDER_PATH);          
          $next_phase = null;          
        }                
        break;
                      
      // add each file
      case '3':
        $files_to_add = get_user_meta($user_id, MAXG_SYNC_FILES, true);        
        
        if(is_array($files_to_add)) {
          $next_file = array_pop($files_to_add);
        } else {
          $next_file = $files_to_add;
        }
        
        if($next_file != "") {
          $next_phase = '3';          
          
          $wp_filetype = wp_check_filetype_and_ext($next_file, $next_file );

          if ($wp_filetype['ext'] !== false) {      
            $message = __("Adding ",'maxgalleria-media-library') . $next_file;
            $this->mlfp_process_sync_file($next_file, $mlp_title_text, $mlp_alt_text);
          } else {
            $message = $next_file . __(" is not an allowed file type. It was not added.",'maxgalleria-media-library');            
          }
          update_user_meta($user_id, MAXG_SYNC_FILES, $files_to_add);            
          
        } else {
          $next_phase = '2';          
          delete_user_meta($user_id, MAXG_SYNC_FILES);          
        }        
        break;
    }  
    $phase = $next_phase;
    
	  $data = array('phase' => $phase, 'message' => $message);								
		echo json_encode($data);						
    die();
  }
  
  public function mlfp_process_sync_file($next_file, $mlp_title_text, $mlp_alt_text, $parent_folder = 0, $generate_folde_path = false) {
    
    global $wpdb;
		$user_id = get_current_user_id();
    
		if($next_file != "") {
  
      if($parent_folder == 0)
        $parent_folder = get_user_meta($user_id, MAXG_SYNC_FOLDER_PATH_ID, true);

      //if($folder_path = '')
      if(!$generate_folde_path) { 
        $folder_path = get_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, true);
      } else {
        $folder_path = $this->get_folder_path($parent_folder);
      }

      $new_attachment = $folder_path . DIRECTORY_SEPARATOR . $next_file;
             
			$new_file_title = preg_replace( '/\.[^.]+$/', '', $next_file);
      
      do_action(MLFP_BEFORE_SYNC_FILE, $new_attachment, $parent_folder, $new_file_title, $mlp_alt_text, $mlp_title_text); 
      
      
      $attach_id = $this->add_new_attachment($new_attachment, $parent_folder, $new_file_title, $mlp_alt_text, $mlp_title_text);
      //error_log("new_attachment $new_attachment, attach_id $attach_id");
      if($attach_id) {
        $absolute_path = $new_attachment;
        $file_url = $this->get_file_url($new_attachment);
        do_action(MLFP_AFTER_SYNC_FILE, $new_attachment, $parent_folder, $new_file_title, $mlp_alt_text, $mlp_title_text, $attach_id, $absolute_path, $file_url);                  
      }	
      
      if(class_exists('MGMediaLibraryFoldersProS3')) {
        if($this->s3_addon->s3_active) {
          $location = $this->s3_addon->get_location($file_url, $this->uploads_folder_name);
          $destination_location = $this->s3_addon->get_destination_location($location);
          $destination_folder  = $this->s3_addon->get_destination_folder($destination_location, $this->uploads_folder_name_length);
          $upload_result= $this->s3_addon->upload_to_s3("attachment", $location, $absolute_path, $attach_id);

          if($this->s3_addon->remove_from_local) {
            if($upload_result['statusCode'] == '200')							
              $this->s3_addon->remove_media_file($absolute_path);										
          }	

          $metadata = wp_get_attachment_metadata($attach_id);

          if(isset($metadata['sizes'])) {
            foreach($metadata['sizes'] as $thumbnail) {
              $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
              $upload_result = $this->s3_addon->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
              if($this->s3_addon->remove_from_local) {
                if($upload_result['statusCode'] == '200')							
                  $this->remove_media_file($source_file);										
              }	
            }

            if($this->s3_addon->remove_from_local) {
              foreach($metadata['sizes'] as $thumbnail) {
                $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                $this->s3_addon->remove_media_file($source_file);										
              }
            }									
          }
        }
      }
    }       
  }
  
  public function ajax_mlfp_save_mc_data() {
    
		$user_id = get_current_user_id();
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = "";
    
    $this->mlfp_save_mc_data('0', $folder_id, $user_id);
    
    echo 'ok';
    
    die();
    
  }
  
  public function mlfp_save_mc_data($serial_copy_ids, $folder_id, $user_id) {
                    
    global $is_IIS; 
                
  	update_user_meta($user_id, MAXG_MC_FILES, $serial_copy_ids);
    
    $destination_folder = $this->get_folder_path($folder_id);
        
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      update_user_meta($user_id, MAXG_MC_DESTINATION_FOLDER, str_replace('\\', '\\\\', $destination_folder));
    else
      update_user_meta($user_id, MAXG_MC_DESTINATION_FOLDER, $destination_folder);

  }
  
  public function mlfp_process_mc_data() {
        
		$user_id = get_current_user_id();
    $message = "";
    $next_phase = '2';
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
		if ((isset($_POST['phase'])) && (strlen(trim($_POST['phase'])) > 0))
      $phase = trim(stripslashes(strip_tags($_POST['phase'])));
    else
      $phase = "";
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = "";
    
    if ((isset($_POST['current_folder'])) && (strlen(trim($_POST['current_folder'])) > 0))
      $current_folder = trim(stripslashes(strip_tags($_POST['current_folder'])));
    else
      $current_folder = "";
    
    if ((isset($_POST['action_name'])) && (strlen(trim($_POST['action_name'])) > 0))
      $action_name = trim(stripslashes(strip_tags($_POST['action_name'])));
    else
      $action_name = "";    
    
    if ((isset($_POST['serial_copy_ids'])) && (strlen(trim($_POST['serial_copy_ids'])) > 0))
      $serial_copy_ids = trim(stripslashes(strip_tags($_POST['serial_copy_ids'])));
    else
      $serial_copy_ids = "";
		
          
    switch($phase) {
      
      case '1':
        
        $serial_copy_ids = str_replace('"', '', $serial_copy_ids);    

        $serial_copy_ids = explode(',', $serial_copy_ids);
    
        $this->mlfp_save_mc_data($serial_copy_ids, $folder_id, $user_id);
        
        $next_phase = '2';
        
        break;
      
      case '2':
        
        $files_to_move = get_user_meta($user_id, MAXG_MC_FILES, true);        

        if(is_array($files_to_move)) {
          $next_id = array_pop($files_to_move);
        } else {
          $next_id = $files_to_move;
          $files_to_move = "";
        }

        if($next_id != "") {
          if($action_name == 'copy_media') {
            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) 
              $message = $this->move_copy_file_s3(true, $next_id, $folder_id, $current_folder, $user_id);
            else  
              $message = $this->move_copy_file(true, $next_id, $folder_id, $current_folder, $user_id);
          } else {
            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) 
              $message = $this->move_copy_file_s3(false, $next_id, $folder_id, $current_folder, $user_id);
            else  
              $message = $this->move_copy_file(false, $next_id, $folder_id, $current_folder, $user_id);
          }  
          update_user_meta($user_id, MAXG_MC_FILES, $files_to_move);                     
        } else {
          $next_phase = null;
          delete_user_meta($user_id, MAXG_MC_FILES);          
          if($action_name == 'copy_media')          		
            $message = __("Finished copying files. ",'maxgalleria-media-library');
          else
            $message = __("Finished moving files. ",'maxgalleria-media-library');
        }  
        break;
    }
    $phase = $next_phase;
       
	  $data = array('phase' => $phase, 'message' => $message);								
    
		echo json_encode($data);						
    
    die();
  }
  
  public function move_copy_file($copy, $copy_id, $folder_id, $current_folder, $user_id, $destination_folder_path = '') {
    
    global $wpdb, $is_IIS;
		$message = "";
		$files = "";
		$refresh = false;
    $scaled = false;
        
    if($destination_folder_path == '') {
      $destination = get_user_meta($user_id, MAXG_MC_DESTINATION_FOLDER, true);
      $destination_path = $this->get_absolute_path($destination);
    } else {
      $destination_path = $destination_folder_path;
    }  
        
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $copy_id    
AND meta_key = '_wp_attached_file'";

    $row = $wpdb->get_row($sql);

    $baseurl = $this->upload_dir['baseurl'];
    $baseurl = rtrim($baseurl, '/') . '/';
    $image_location = $baseurl . ltrim($row->attached_file, '/');
    
    //error_log("image_location $image_location");
    if(strpos($image_location, '-scaled.' ) !== false) {
      $scaled = true;
      //error_log("scaled true");
    }  else {
      //error_log("scaled false");      
    }

    $image_path = $this->get_absolute_path($image_location);

    //$destination_path = $this->get_absolute_path($destination);

    $folder_basename = basename($destination_path);
    
    $basename = pathinfo($image_path, PATHINFO_BASENAME);

    $destination_name = $destination_path . DIRECTORY_SEPARATOR . $basename;

    $copy_status = true;

    if(file_exists($image_path)) {
      if(!is_dir($image_path)) {
        if(file_exists($destination_path)) {
          if(is_dir($destination_path)) {

            if($copy) {
              
              do_action(MLFP_BEFORE_FILE_COPY, $image_path, $destination_name);
              
              if($scaled) {
                $full_scaled_image_path = str_replace('-scaled*', '', $image_path);
                //error_log("full_scaled_image_path $full_scaled_image_path");
                if(file_exists($full_scaled_image_path)) {
                  //error_log("file_exists");
                  $image_path = $full_scaled_image_path;
                  $full_scaled_image = substr($full_scaled_image_path, strrpos($full_scaled_image_path, '/')+1);
                  $destination_name = $destination_path . DIRECTORY_SEPARATOR . $full_scaled_image;                  
                  //error_log("destination_name $destination_name");
                }
              }
                            
              if(copy($image_path, $destination_name )) {                                          

                $destination_url = $this->get_file_url($destination_name);
                $title_text = get_the_title($copy_id);
                $alt_text = get_post_meta($copy_id, '_wp_attachment_image_alt');										
                $attach_id = $this->add_new_attachment($destination_name, $folder_id, $title_text, $alt_text);
                
                do_action(MLFP_AFTER_FILE_COPY, $image_path, $destination_name, $destination_url, $folder_id);
                
                if($attach_id === false){
                  $copy_status = false; 
                }  
              }
              else {
                echo __('Unable to copy the file; please check the folder and file permissions.','maxgalleria-media-library') . PHP_EOL;
                $copy_status = false; 
              }
              //move
            } else {
              
              do_action(MLFP_BEFORE_FILE_MOVE, $image_path, $destination_name);

              if(rename($image_path, $destination_name )) {
                // check current theme customizer settings for the file
                // and update if found
                $update_theme_mods = false;
                $move_image_url = $this->get_file_url_for_copy($image_path);
                $move_destination_url = $this->get_file_url_for_copy($destination_name);
                $key = array_search ($move_image_url, $this->theme_mods, true);
                if($key !== false ) {
                  set_theme_mod( $key, $move_destination_url);
                  $update_theme_mods = true;                      
                }
                if($update_theme_mods) {
                  $theme_mods = get_theme_mods();
                  $this->theme_mods = json_decode(json_encode($theme_mods), true);
                  $update_theme_mods = false;
                }

                $image_path = str_replace('.', '*.', $image_path );
                $metadata = wp_get_attachment_metadata($copy_id);                               
                $path_to_thumbnails = pathinfo($image_path, PATHINFO_DIRNAME);
                
                if($scaled) {
                  $full_scaled_image_path = str_replace('-scaled*', '', $image_path);
                  //error_log("full_scaled_image_path $full_scaled_image_path");
                  $full_scaled_image = substr($full_scaled_image_path, strrpos($full_scaled_image_path, '/')+1);
                  //error_log("full_scaled_image $full_scaled_image");
                  $scaled_image_destination = $destination_path . DIRECTORY_SEPARATOR . $full_scaled_image;
                  //error_log("scaled_image_destination $scaled_image_destination");
                  if(file_exists($full_scaled_image_path))
                    rename($full_scaled_image_path, $scaled_image_destination);  
                }
                
                if(isset($metadata['sizes'])) {
                  
                  foreach($metadata['sizes'] as $source_path) {
                    $thumbnail_file = $path_to_thumbnails . DIRECTORY_SEPARATOR . $source_path['file'];
                    $thumbnail_destination = $destination_path . DIRECTORY_SEPARATOR . $source_path['file'];
                    //error_log("thumbnail_file $thumbnail_file");
		                if(file_exists($thumbnail_file)) {
                      //error_log("moving $thumbnail_file");
                      rename($thumbnail_file, $thumbnail_destination);

                      // if the source thubmnail is in the folder, delete it
		                  if(file_exists($thumbnail_destination)) {
		                    if(file_exists($thumbnail_file)) 
                          unlink($thumbnail_file);
                      }
                      
                      // check current theme customizer settings for the fileg
                      // and update if found
                      $update_theme_mods = false;
                      $move_source_url = $this->get_file_url_for_copy($source_path);
                      $move_thumbnail_url = $this->get_file_url_for_copy($thumbnail_destination);
                      $key = array_search ($move_source_url, $this->theme_mods, true);
                      if($key !== false ) {
                        set_theme_mod( $key, $move_thumbnail_url);
                        $update_theme_mods = true;                      
                      }
                      if($update_theme_mods) {
                        $theme_mods = get_theme_mods();
                        $this->theme_mods = json_decode(json_encode($theme_mods), true);
                        $update_theme_mods = false;
                      }
                    }
                  }
                }
              
                $destination_url = $this->get_file_url($destination_name);
                
                do_action(MLFP_AFTER_FILE_MOVE, $image_path, $destination_name, $destination_url);

                // update posts table
                $table = $wpdb->prefix . "posts";
                $data = array('guid' => $destination_url );
                $where = array('ID' => $copy_id);
                $wpdb->update( $table, $data, $where);

                // update folder table
                $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
                $data = array('folder_id' => $folder_id );
                $where = array('post_id' => $copy_id);
                $wpdb->update( $table, $data, $where);

                // get the uploads dir name
                $basedir = $this->upload_dir['baseurl'];
                $uploads_dir_name_pos = strrpos($basedir, '/');
                $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);

                //find the name and cut off the part with the uploads path
                $string_position = strpos($destination_name, $uploads_dir_name);
                $uploads_dir_length = strlen($uploads_dir_name) + 1;
                $uploads_location = substr($destination_name, $string_position+$uploads_dir_length);
                if($this->is_windows()) 
                  $uploads_location = str_replace('\\','/', $uploads_location);      

                // update _wp_attached_file

                $uploads_location = ltrim($uploads_location, '/');
                update_post_meta( $copy_id, '_wp_attached_file', $uploads_location );

                // update _wp_attachment_metadata
                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                  $attach_data = wp_generate_attachment_metadata( $copy_id, addslashes($destination_name));										
                else
                  $attach_data = wp_generate_attachment_metadata( $copy_id, $destination_name );										
                wp_update_attachment_metadata( $copy_id,  $attach_data );

                // update posts and pages
                $replace_image_location = $this->get_base_file($image_location);
                $replace_destination_url = $this->get_base_file($destination_url);
                                
                if(class_exists( 'SiteOrigin_Panels')) {                  
                  $this->update_serial_postmeta_records($replace_image_location, $replace_destination_url);                  
                }
                
                // update postmeta records for beaver builder
                if(class_exists( 'FLBuilderLoader')) {
                  $sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%$replace_image_location%'";
                  //error_log($sql);
                  
                  $records = $wpdb->get_results($sql);
                  foreach($records as $record) {
                    
                    $this->update_bb_postmeta($record->ID, $replace_image_location, $replace_destination_url);
                                        
                  }
                  // clearing BB caches
                  //error_log("check for cache");
                  if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_asset_cache_for_all_posts' ) ) {
                    FLBuilderModel::delete_asset_cache_for_all_posts();
                    //error_log("delete_asset_cache_for_all_posts");
                  }
                  
                  if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_all_asset_cache' ) ) {
                    FLBuilderModel::delete_all_asset_cache( $record->ID );
                    //error_log("delete_all_asset_cache");
                  }  
                  
                  if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
                    FLCustomizer::clear_all_css_cache();
                    //error_log("clear_all_css_cache");
                  }
                  wp_cache_flush();
                                    
                }
                                
                //$replace_sql = "UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE (`post_content`, '$replace_image_location', '$replace_destination_url');";
                //$result = $wpdb->query($replace_sql);
                
                //$replace_sql = str_replace ( '/', '\\/', $replace_sql);
                //$result = $wpdb->query($replace_sql);
                $this->update_links($replace_image_location, $replace_destination_url);                
                
                // for updating wp pagebuilder
                if(defined('WPPB_LICENSE')) {
                  $this->update_wppb_data($replace_image_location, $destination_url);
                }
                                                      
                // for updating themify images
                if(function_exists('themify_builder_activate')) {
                  $this->update_themify_data($replace_image_location, $destination_url);
                }
                
                // for updating elementor background images
                if(is_plugin_active("elementor/elementor.php")) {
                  $this->update_elementor_data($copy_id, $replace_image_location, $destination_url);
                }
                                
                $message .= __('Updating attachment links, please wait...','maxgalleria-media-library') . PHP_EOL;
                $files = $this->display_folder_contents ($current_folder, true, "", false);
                $refresh = true;
              }                                   
              else {
                $message .= __('Unable to move ','maxgalleria-media-library') . $basename . __('; please check the folder and file permissions.','maxgalleria-media-library') . PHP_EOL;
                $copy_status = false; 
              }
            } 
          }
          else {
            $message .= __('The destination is not a folder: ','maxgalleria-media-library') . $destination_path . PHP_EOL;
            $copy_status = false; 
          }
        }
        else {
          $message .= __('Cannot find destination folder: ','maxgalleria-media-library') . $destination_path . PHP_EOL;
          $copy_status = false; 
        }
      }   
      else {
        $message .= __('Copying or moving a folder is not allowed.','maxgalleria-media-library') . PHP_EOL;
        $copy_status = false; 
      }
    }
    else {
      $message .= __('Cannot find the file: ','maxgalleria-media-library') . $image_path . ". " . PHP_EOL;
      $this->write_log("Cannot find the file: $image_path");
      $copy_status = false; 
    }        
  
    if($copy) {
      if($copy_status)
        $message .= $basename . __(' was copied to ','maxgalleria-media-library') . $folder_basename . PHP_EOL;      
      else
        $message .= $basename . __(' was not copied.','maxgalleria-media-library') . PHP_EOL;      
    }
    else {
      if($copy_status)
        $message .= $basename . __(' was moved to ','maxgalleria-media-library') . $folder_basename . PHP_EOL;      
      else
        $message .= $basename . __(' was not moved.','maxgalleria-media-library') . PHP_EOL;              
    }

    return $message;
    
  }
  
  public function mlfp_move_single_file() {
            
		$user_id = get_current_user_id();
    $action_name = 'move_media';
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
		if ((isset($_POST['file_id'])) && (strlen(trim($_POST['file_id'])) > 0))
      $next_id = trim(stripslashes(strip_tags($_POST['file_id'])));
    else
      $next_id = "";
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $folder_id = "";
    
    if ((isset($_POST['current_folder'])) && (strlen(trim($_POST['current_folder'])) > 0))
      $current_folder = trim(stripslashes(strip_tags($_POST['current_folder'])));
    else
      $current_folder = "";
        
    if ((isset($_POST['destination_folder_path'])) && (strlen(trim($_POST['destination_folder_path'])) > 0))
      $destination_folder_path = trim(stripslashes(strip_tags($_POST['destination_folder_path'])));
    else
      $destination_folder_path = "";
        
    if($next_id != "") {
      if($action_name == 'copy_media') {
        if(class_exists('MGMediaLibraryFoldersProS3') && 
          ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) 
          $message = $this->move_copy_file_s3(true, $next_id, $folder_id, $current_folder, $user_id);
        else  
          $message = $this->move_copy_file(true, $next_id, $folder_id, $current_folder, $user_id, $destination_folder_path);
      } else {
        if(class_exists('MGMediaLibraryFoldersProS3') && 
          ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) 
          $message = $this->move_copy_file_s3(false, $next_id, $folder_id, $current_folder, $user_id);
        else  
          $message = $this->move_copy_file(false, $next_id, $folder_id, $current_folder, $user_id, $destination_folder_path);
      }  
    } else {
      $next_phase = null;
      delete_user_meta($user_id, MAXG_MC_FILES);          
      if($action_name == 'copy_media')          		
        $message = __("Finished copying files. ",'maxgalleria-media-library');
      else
        $message = __("Finished moving files. ",'maxgalleria-media-library');
    }  
    
    //error_log($message);
    
    echo $message;
    
    die();

  }
  
  public function update_wppb_data($replace_image_location, $destination_url) {
    
    //error_log("update_wppb_data");

    global $wpdb;
    $save = false;
    $table = $wpdb->prefix . "postmeta";
    
    $position = strrpos($destination_url, '.');    
    $url_without_extension = substr($destination_url, 0, $position);    
        
    $base_file_name = basename($replace_image_location);
    
    $sql = "select post_id, meta_id, meta_value from wp_postmeta where meta_key = '_wppb_content' and meta_value like '%{$base_file_name}%'";
    //error_log($sql);
    
    $rows = $wpdb->get_results($sql);
    if($rows) {
      foreach($rows as $row) {        
        $jarrays = json_decode($row->meta_value, true);
        $this->wppb_recursive_find_and_update($jarrays, $replace_image_location, $destination_url, $url_without_extension);
        //error_log(print_r($jarrays, true));
        
        $jarrays = json_encode($jarrays);
        $data = array('meta_value' => $jarrays);
        $where = array('meta_id' => $row->meta_id);
        $wpdb->update($table, $data, $where);
      }
    }  
  }
  
  public function wppb_recursive_find_and_update(&$jarrays, $replace_image_location, $destination_url ) {
    
    foreach($jarrays as $key => &$value) {
      if(is_array($value)) {
        $this->wppb_recursive_find_and_update($value, $replace_image_location, $destination_url);
      } else {
        if($key == 'url' && strpos($value, $replace_image_location) !== false) {            
          $value = $destination_url;
        }          
      }
    }
  }
    
  public function update_themify_data($replace_image_location, $destination_url) {
    
    global $wpdb;
    $save = false;
    $table = $wpdb->prefix . "postmeta";
    
    $position = strrpos($destination_url, '.');    
    $url_without_extension = substr($destination_url, 0, $position);    
        
    $base_file_name = basename($replace_image_location);
    
    $sql = "select post_id, meta_id, meta_value from {$table} where meta_key = '_themify_builder_settings_json' and meta_value like '%$base_file_name%'";
    
    $rows = $wpdb->get_results($sql);
    if($rows) {
      foreach($rows as $row) {        
        $jarrays = json_decode($row->meta_value, true);
        $this->recursive_find_and_update($jarrays, $replace_image_location, $destination_url, $url_without_extension);
        
        $jarrays = json_encode($jarrays);
        $data = array('meta_value' => $jarrays);
        $where = array('meta_id' => $row->meta_id);
        $wpdb->update($table, $data, $where);
      }
    }      
  }
  
  public function recursive_find_and_update(&$jarrays, $replace_image_location, $destination_url, $url_without_extension) {
            
      foreach($jarrays as $key => &$value) {
        if(is_array($value)) {
          $this->recursive_find_and_update($value, $replace_image_location, $destination_url, $url_without_extension);
        } else {
          if($key == 'url_image' && strpos($value, $replace_image_location) !== false) {            
            $value = $destination_url;
          } else if($key == 'img_url_slider' && strpos($value, $replace_image_location) !== false) {            
            $value = $destination_url;            
          } else if($key == 'content_text' && strpos($value, $replace_image_location) !== false ) {
            $content_text = $value;
            $value = str_replace($replace_image_location, $url_without_extension, $content_text);      
          }          
        }
      }
  }
    
  public function update_elementor_data($image_id, $replace_image_location, $replace_destination_url) {
    
    global $wpdb;
    $save = false;
    
    $base_file_name = basename($replace_image_location);
    
    $sql = "select post_id, meta_id, meta_value from {$wpdb->prefix}postmeta where meta_key = '_elementor_data' and meta_value like '%$base_file_name%'";
    
    $rows = $wpdb->get_results($sql);
    if($rows) {
      foreach($rows as $row) {
        
        // check for serialized data
        $data = @unserialize($row->meta_value);
        if($data === false)
          $jarrays = json_decode($row->meta_value, true);
        else {
          $jarrays = $data; 
        }
        
        if(is_array($jarrays)) {          
          foreach($jarrays as &$jarray) {
            //error_log("is an array");
            if($this->search_elementor_array($image_id, $jarray, $replace_image_location, $replace_destination_url, $row->post_id))
              $save = true;
          }
        } else {
            //error_log("is not an array");
        }
        if($save) {
          update_post_meta($row->post_id, '_elementor_data', $jarrays);
        }
        $this->update_elemenator_css_file($row->post_id, $replace_image_location, $replace_destination_url);
      }
    }
  }
  
  public function search_elementor_array($image_id, &$jarray, $replace_image_location, $replace_destination_url, $post_id) {
    
    $save = false;
    if(array_key_exists('settings', $jarray)) {
      if(array_key_exists('background_background', $jarray['settings'])) {
        if($jarray['settings']['background_background'] == 'classic') {
          if(array_key_exists('id', $jarray['settings']['background_image'])) {
            if($jarray['settings']['background_image']['id'] == $image_id) {
              $jarray['settings']['background_image']['url'] = $replace_destination_url;
              $save = true;              
            }              
          }          
        }        
      }
    }    
  }
  
  public function update_elemenator_css_file($post_id, $replace_image_location, $replace_destination_url) {
    
    $css_file_path = trailingslashit($this->upload_dir['basedir']) . "elementor/css/post-{$post_id}.css";
    
    $position = strrpos($replace_destination_url, '.');
    
    $url_without_extension = substr($replace_destination_url, 0, $position);
    
    if(file_exists($css_file_path)) {
        
      $css = file_get_contents($css_file_path);

      $css = str_replace($replace_image_location, $url_without_extension, $css);

      file_put_contents($css_file_path, $css);
    }
        
  }
      
  public function update_bb_postmeta($post_id, $replace_image_location, $replace_destination_url) {
      
    $this->update_bb_postmeta_item('_fl_builder_draft', $post_id, $replace_image_location, $replace_destination_url);
    $this->update_bb_postmeta_item('_fl_builder_data', $post_id, $replace_image_location, $replace_destination_url);
    
  }
  
  public function update_bb_postmeta_item($metakey, $post_id, $replace_image_location, $replace_destination_url) {
    //error_log("$replace_image_location, $replace_destination_url");
    
    $save = false;
    $builder_info = json_decode(json_encode(get_post_meta($post_id, $metakey, true)));
    $builder_info = $this->objectToArray($builder_info);
    
    if(is_array($builder_info)){
      foreach ($builder_info as $key => &$info_head) {
        foreach ($info_head as $info_key => &$info_value) {
          if(is_array($info_value)) {
            foreach ($info_value as $data_key => &$data_value) {
              if(!is_array($data_value)) {
                if($data_key == 'photo_src' || $data_key == 'text') {
                  $save = true;
                  $data_value = str_replace($replace_image_location, $replace_destination_url, $data_value);
                }
              } else {  
                foreach ($data_value as $next_key => &$next_value) {
                  if(!is_array($next_value)) {
                    if($next_key == 'url') {
                      $save = true;
                      $next_value = str_replace($replace_image_location, $replace_destination_url, $next_value);
                    }                  
                  } else {
                    foreach ($next_value as $sizes_key => &$sizes_value) {
                      if(is_array($sizes_value)) {
                        foreach ($sizes_value as $final_key => &$final_value) {
                          if(!is_array($final_value)) {
                            if($final_key == 'url') {
                              $save = true;
                              $final_value = str_replace($replace_image_location, $replace_destination_url, $final_value);
                            }                            
                          }
                        }
                      }
                    }
                  }  
                }  
              }
            }  
          }
        }
      }
    }
        
    if($save) {
      $builder_info = $this->arrayToObject($builder_info);
      $builder_info = serialize($builder_info);
      update_post_meta($post_id, $metakey, $builder_info);
    }
    
  }
        
  function objectToArray( $object ) {
    if( !is_object( $object ) && !is_array( $object )){
        return $object;
    }
    if( is_object( $object ) ){
        $object = get_object_vars( $object );
    }
    return array_map( array($this, 'objectToArray'), $object );
  }  
  
  public function arrayToObject($d){
    if (is_array($d)){
      return (object) array_map(array($this, 'arrayToObject'), $d);
    } else {
      return $d;
    }
  }  
          
  public function move_copy_file_s3($copy, $copy_id, $folder_id, $current_folder, $user_id) {
    
    global $wpdb, $is_IIS;
		$message = "";
		$files = "";
		$refresh = false;
    
    $destination = get_user_meta($user_id, MAXG_MC_DESTINATION_FOLDER, true);
        		
		if($destination === '' && $folder_id !== '' ) {
      $destination = $this->get_folder_path($folder_id);			
		}
				           
    if($destination !== "" || $folder_id !== 0 ) {
      				
      if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
					
        $sql = "select p.ID, pm.meta_value as file_path, pm2.meta_value as metadata, pm3.meta_value as attached_file
from {$wpdb->prefix}posts as p
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = p.ID and pm.meta_key = '" . MLFP3_META . "' )
LEFT JOIN {$wpdb->prefix}postmeta AS pm2 ON (pm2.post_id = p.ID and pm2.meta_key = '_wp_attachment_metadata' )
LEFT JOIN {$wpdb->prefix}postmeta AS pm3 ON (pm3.post_id = p.ID and pm3.meta_key = '_wp_attached_file' )
WHERE p.ID = $copy_id";
					
      } else {
        
      //if(empty($copy_id))
      //  error_log('8683');              
        
        $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $copy_id    
AND meta_key = '_wp_attached_file'";

      }

      //error_log($sql);

      $row = $wpdb->get_row($sql);

      //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
      $baseurl = $this->upload_dir['baseurl'];
      $baseurl = rtrim($baseurl, '/') . '/';
      $image_location = $baseurl . ltrim($row->attached_file, '/');

      $image_path = $this->get_absolute_path($image_location);

      $destination_path = $this->get_absolute_path($destination);

      $folder_basename = basename($destination_path);

      $basename = pathinfo($image_path, PATHINFO_BASENAME);

      $destination_name = $destination_path . DIRECTORY_SEPARATOR . pathinfo($image_path, PATHINFO_BASENAME);

      $copy_status = true;

      if(($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) || file_exists($image_path)) {
        if(($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) || !is_dir($image_path)) {
          if(file_exists($destination_path)) {
            if(is_dir($destination_path)) {

              if($copy) {
                //download the file
                if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {

                  $location = $this->s3_addon->get_location($row->file_path, $this->uploads_folder_name);
                  $this->s3_addon->download_file_from_s3($location, $image_path);
                }

                if(copy($image_path, $destination_name )) { 

                  $destination_url = $this->get_file_url($destination_name);
                  $title_text = get_the_title($copy_id);
                  $alt_text = get_post_meta($copy_id, '_wp_attachment_image_alt');										
                  $attach_id = $this->add_new_attachment($destination_name, $folder_id, $title_text, $alt_text);
                  if($attach_id === false){
                    $copy_status = false; 
                  } 
                  // remove the file
                  if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3 && $attach_id) {
                    $this->s3_addon->remove_media_file($image_path);											
                    $destination_file_url = $this->get_file_url($destination_name);
                    $this->s3_addon->upload_attachment_files_to_s3("attachment", $destination_file_url, $destination_name, $attach_id);											
                  }	
                }
                else {
                  echo __('Unable to copy the file; please check the folder and file permissions.','maxgalleria-media-library') . PHP_EOL;
                  $copy_status = false; 
                }
                //move
              } else {

                if(($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) || rename($image_path, $destination_name )) {

                  if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
                    $s3_file_info = $this->s3_addon->get_move_info($row->file_path, $destination);
                    $upload_result = $this->s3_addon->mlfp_s3_move_file($s3_file_info['location'], $s3_file_info['destination_location'], $copy);	

                    if($upload_result['statusCode'] == '200')
                      $this->s3_addon->save_aws_path($upload_result['url'], $copy_id);													
                      $this->s3_addon->download_file_from_s3($s3_file_info['destination_location'], $destination_name);												
                  }

                  // check current theme customizer settings for the file
                  // and update if found
                  $update_theme_mods = false;
                  $move_image_url = $this->get_file_url_for_copy($image_path);
                  //$move_destination_url = $this->get_file_url_for_copy($destination_name);
                  $key = array_search ($move_image_url, $this->theme_mods, true);
                  if($key !== false ) {
                    if($this->s3_active && $this->serve_from_s3) {												
                      set_theme_mod( $key, $upload_result['url']);
                    } else {
                      $move_destination_url = $this->get_file_url_for_copy($destination_name);
                      set_theme_mod( $key, $move_destination_url);
                    }	
                    $update_theme_mods = true;                      
                  }
                  if($update_theme_mods) {
                    $theme_mods = get_theme_mods();
                    $this->theme_mods = json_decode(json_encode($theme_mods), true);
                    $update_theme_mods = false;
                  }

                  $image_path = str_replace('.', '*.', $image_path );

                  $metadata = wp_get_attachment_metadata($copy_id);

                  if(!$this->s3_addon->s3_active || !$this->s3_addon->serve_from_s3) {
                    if(isset($metadata['sizes'])) {
                      $destination_folder  = $this->get_destination_folder($s3_file_info['destination_location'], $this->uploads_folder_name_length);
                      foreach($metadata['sizes'] as $thumbnail) {
                        $source_file = $this->get_absolute_path($destination .'/' . $thumbnail['file']);
                        $thumbnail_destination = $destination_path . DIRECTORY_SEPARATOR . $thumbnail['file'];
                        rename($source_path, $thumbnail_destination);
                      }
                    }
                  }

                  //foreach (glob($image_path) as $source_path) {
                  foreach($metadata['sizes'] as $thumbnail) {
                    //$thumbnail_file = pathinfo($source_path, PATHINFO_BASENAME);
                    $thumbnail_destination = $destination_path . DIRECTORY_SEPARATOR . $thumbnail['file'];
                    if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
                      $s3_thumb_info = $this->s3_addon->get_thumbnail_move_info($thumbnail['file'], $s3_file_info['location'], $s3_file_info['destination_folder']);
                      $source_path = $s3_thumb_info['location'];
                      $upload_result = $this->s3_addon->mlfp_s3_move_file($s3_thumb_info['location'], $s3_thumb_info['destination_location'], $copy);				
                    } else {
                      $position = strrpos($image_path, '/');
                      $source_path = substr($image_path, 0, $position ) . $thumbnail;
		                  if(file_exists($source_path))                      
                        rename($source_path, $thumbnail_destination);
                    }	

                    // check current theme customizer settings for the fileg
                    // and update if found
                    $update_theme_mods = false;
                    $move_source_url = $this->get_file_url_for_copy($source_path);
                    $move_thumbnail_url = $this->get_file_url_for_copy($thumbnail_destination);
                    $key = array_search ($move_source_url, $this->theme_mods, true);
                    if($key !== false ) {
                      set_theme_mod( $key, $move_thumbnail_url);
                      $update_theme_mods = true;                      
                    }
                    if($update_theme_mods) {
                      $theme_mods = get_theme_mods();
                      $this->theme_mods = json_decode(json_encode($theme_mods), true);
                      $update_theme_mods = false;
                    }

                  }  

                  // remove the files in the old location
                  if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
                    $post_type = "attachment";
                    $this->s3_addon->remove_from_s3($post_type, $s3_file_info['location']);

                    foreach($metadata['sizes'] as $thumbnail) {
                      $s3_thumb_info = $this->s3_addon->get_thumbnail_move_info($thumbnail['file'], $s3_file_info['location'], $s3_file_info['destination_folder']);
                      $this->s3_addon->remove_from_s3($post_type, $s3_thumb_info['location']);

                    }
                  }

                  $destination_url = $this->get_file_url($destination_name);

                  $s3_url = $this->s3_addon->get_attachment_s3_url($copy_id);

                  // update the metadata
                  if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                    $attach_data = wp_generate_attachment_metadata( $copy_id, addslashes($destination_name));										
                  else
                    $attach_data = wp_generate_attachment_metadata( $copy_id, $destination_name );										
                  wp_update_attachment_metadata( $copy_id,  $attach_data );											

                  // update posts table
                  $table = $wpdb->prefix . "posts";
                  $data = array('guid' => $destination_url );
                  $where = array('ID' => $copy_id);
                  $wpdb->update( $table, $data, $where);

                  // update folder table
                  $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
                  $data = array('folder_id' => $folder_id );
                  $where = array('post_id' => $copy_id);
                  $wpdb->update( $table, $data, $where);

                  if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {

                    $upload_position = strpos($s3_file_info['destination_location'], $this->uploads_folder_name);
                    $destination_location = substr($s3_file_info['destination_location'], $upload_position + $this->uploads_folder_name_length + 1);

                    update_post_meta($copy_id, '_wp_attached_file', $destination_location);
                    $this->s3_addon->remove_media_file($destination_name);

                    $metadata = wp_get_attachment_metadata($copy_id);
                    foreach($metadata['sizes'] as $thumbnail) {
                      $source_file = $this->get_absolute_path($destination .'/' . $thumbnail['file']);
                      $this->s3_addon->remove_media_file($source_file);

                    }


                  } else {
                    // get the uploads dir name
                    $basedir = $this->upload_dir['baseurl'];
                    $uploads_dir_name_pos = strrpos($basedir, '/');
                    $uploads_dir_name = substr($basedir, $uploads_dir_name_pos+1);

                    //find the name and cut off the part with the uploads path
                    $string_position = strpos($destination_name, $uploads_dir_name);
                    $uploads_dir_length = strlen($uploads_dir_name) + 1;
                    $uploads_url = substr($destination_name, $string_position+$uploads_dir_length);
                    if($this->is_windows()) 
                      $uploads_location = str_replace('\\','/', $uploads_location);      

                    // update _wp_attached_file

                    $uploads_location = ltrim($uploads_location, '/');
                    update_post_meta( $copy_id, '_wp_attached_file', $uploads_location );

                    // update _wp_attachment_metadata
                    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                      $attach_data = wp_generate_attachment_metadata( $copy_id, addslashes($destination_name));										
                    else
                      $attach_data = wp_generate_attachment_metadata( $copy_id, $destination_name );										
                    wp_update_attachment_metadata( $copy_id,  $attach_data );
                  }

                  // update posts and pages
                  if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {

                    $s3_url = $this->s3_addon->get_attachment_s3_url($copy_id);
                    $content_position = strpos($s3_url, "wp-content");
                    $bucket_address = substr($s3_url, 0, $content_position);

                    $replace_image_location = $this->generate_s3_url($bucket_address, $this->get_base_file($image_location));
                    $replace_destination_url = $this->generate_s3_url($bucket_address, $this->get_base_file($destination_url));
                  } else {
                    $replace_image_location = $this->get_base_file($image_location);
                    $replace_destination_url = $this->get_base_file($destination_url);                      
                  }

                  //$replace_sql = "UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE (`post_content`, '$replace_image_location', '$replace_destination_url');";
                  //$result = $wpdb->query($replace_sql);
                  
                  $this->update_links($replace_image_location, $replace_destination_url);                
                  
                  // for updating wp pagebuilder
                  if(defined('WPPB_LICENSE')) {
                    $this->update_wppb_data($replace_image_location, $destination_url);
                  }
                                                                        
                  // for updating themify images
                  if(function_exists('themify_builder_activate')) {
                    $this->update_themify_data($replace_image_location, $destination_url);
                  }
                                    
                  // for updating elementor background images
                  if(is_plugin_active("elementor/elementor.php")) {
                    $this->update_elementor_data($copy_id, $replace_image_location, $destination_url);
                  }
                  
                  $message .= __('Updating attachment links, please wait...','maxgalleria-media-library') . PHP_EOL;
                  $files = $this->display_folder_contents ($current_folder, true, "", false);
                  $refresh = true;
                }                                   
                else {
                  $message .= __('Unable to move the file(s); please check the folder and file permissions.','maxgalleria-media-library') . PHP_EOL;
                  $copy_status = false; 
                }
              } // end of move section
            }
            else {
              $message .= __('The destination is not a folder: ','maxgalleria-media-library') . $destination_path . PHP_EOL;
              $copy_status = false; 
            }
          }
          else {
            $message .= __('Cannot find destination folder: ','maxgalleria-media-library') . $destination_path . PHP_EOL;
            $copy_status = false; 
          }
        }   
        else {
          $message .= __('Coping or moving a folder is not allowed.','maxgalleria-media-library') . PHP_EOL;
          $copy_status = false; 
        }
      }
      else {
        $message .= __('Cannot find the file: ','maxgalleria-media-library') . $image_path . ". " . PHP_EOL;					
        $this->write_log("Cannot find the file: $image_path");
        $copy_status = false; 
      }        
    
      if($copy) {
        if($copy_status)
          $message .= $basename . __(' was copied to ','maxgalleria-media-library') . $folder_basename . PHP_EOL;      
        else
          $message .= $basename . __(' was not copied.','maxgalleria-media-library') . PHP_EOL;      
      }
      else {
        if($copy_status)
          $message .= $basename . __(' was moved to ','maxgalleria-media-library') . $folder_basename . PHP_EOL;      
        else
          $message .= $basename . __(' was not moved.','maxgalleria-media-library') . PHP_EOL;              
      }

    }
    
    return $message;
    
  }
  
	public function check_license() {
    
    $valid_license = true;

		$license = trim( get_option( 'mg_edd_mlpp_license_key' ) );
	
		if($license != "") {
		
			$args = array(
				'edd_action' => 'check_license',
				'license' => $license,
				'item_name' => urlencode( EDD_MLPP_NAME ), // the name of our product in EDD
				'url'       => site_url()          
			);

			$request = wp_remote_post(MLPP_EDD_SHOP_URL,  array( 'body' => $args, 'timeout' => 15, 'sslverify' => false ) );
      
      if(is_wp_error($request)){
        $error_message = $request->get_error_message();
        error_log("Something went wrong: $error_message");
      } else {

        $response = json_decode($request['body']);

        //error_log(print_r($response, true));

        update_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES, $response->expires);

        $this->license_expiration = $response->expires;

        if($this->license_expiration != 'lifetime') {
          
          $expire_time = strtotime($response->expires);

          $currnet_date_time = date('Y-m-d H:i:s');
          $today_time = strtotime($currnet_date_time);
          if($expire_time < $today_time)
            $valid_license = false;            
          else {
            if($response->activations_left == 'unlimited') {
              update_option(MAXGALLERIA_MEDIA_LIBRARY_UNLIMITED, 'yes');
            } else { 
              delete_option(MAXGALLERIA_MEDIA_LIBRARY_UNLIMITED);
            }  
          }
        }
      }       
    }
    return $valid_license;
        
  }
  
  public function display_experation_notice() {
    
    $expriation_date = get_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES);
    //$new_license = get_option(MAXG_NEW_LICENSE, 'off');
    
    $new_license = get_option('mg_edd_mlpp_license_status', 'inactive');
    //error_log("expriation_date $expriation_date");
    $valid = false;
    
    $expire_time = strtotime($expriation_date);
    $expiration = date_i18n( 'F d, Y', $expire_time);

    $currnet_date_time = date('Y-m-d H:i:s');
    $today_time = strtotime($currnet_date_time);
    
    //error_log("expriation_date $expriation_date, $new_license");
    if($expriation_date == 'lifetime' && $new_license != 'inactive') {
      $valid = true;      
    } else if($expriation_date == '' || $new_license == 'inactive') {
      echo "<div class='license_warning'>" . PHP_EOL;
      echo "<h3>" . __('No License Found! ', 'maxgalleria-media-library') . "</h3>" . PHP_EOL;
      echo "<p>" . __('Media Library Folders PRO requires a license. Activate or renew your license to access pro features, get updates and new features plus support!', 'maxgalleria-media-library') . "</p>" . PHP_EOL;
      echo "<p class='enter_license'><a href='" . site_url() . "/wp-admin/admin.php?page=mlfp-settings8&tab=license'>" . __('Click to enter license.', 'maxgalleria-media-library') . "</h3></a></p>" . PHP_EOL;
      echo "<p>" . __('Your license is in your purchase email or in the ', 'maxgalleria-media-library') . "<a href='https://maxgalleria.com/my-account/' target='_blank'> " . __('Account', 'maxgalleria-media-library') . " </a> " . __('section of our website.', 'maxgalleria-media-library') . "</p>" . PHP_EOL;
      echo "</div>";      
      echo "<div class='clearfix'></div>";          
    } else if($expire_time < $today_time) {
      echo "<div class='license_warning expired'>" . PHP_EOL;
      echo  "<h3>" . __('License Expired', 'maxgalleria-media-library') . "</h3>" . PHP_EOL;
      echo  "<p>" . sprintf(__('Your license expired on %s. Renew your license to access pro features, get updates and new features plus support!','maxgalleria-media-library'), $expiration) . "</p>" . PHP_EOL;
      echo  "<p>" . sprintf(__('Renew your license for a discount via  %s Your Account %s on our website.</p>', 'maxgalleria-media-library'), "<a href='https://maxgalleria.com/my-account/' target='_blank'>", "</a>" ) . "</p>" . PHP_EOL;
      $settings_url = site_url() . "/wp-admin/admin.php?page=mlfp-settings8&tab=license";
      echo  "<p>" . sprintf(__('Already renewed your license, <a href="%s">click here to update your license information.</a></p>', 'maxgalleria-media-library'), $settings_url ) . "</p>" . PHP_EOL;
      echo "</div>";      
      echo "<div class='clearfix'></div>";      
    } else {
      $valid = true;
    }
    return $valid;
  }
  
  public function is_valid_license() {
    
    $expriation_date = get_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES);
    if($expriation_date != 'lifetime') {
      $license_status = get_option('mg_edd_mlpp_license_status', 'inactive');

      $expire_time = strtotime($expriation_date);

      $currnet_date_time = date('Y-m-d H:i:s');
      $today_time = strtotime($currnet_date_time);
      if($expire_time < $today_time || $license_status != 'valid') {
        $valid = false;
      } else {
        $valid = true;
      }
    } else {
      $valid = true;
    }
    return $valid;
  }
  
  public function gutenberg_active() {
      // Gutenberg plugin is installed and activated.
      $gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

      // Block editor since 5.0.
      $block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

      if ( ! $gutenberg && ! $block_editor ) {
        return false;
      }

      if ( $this->classic_editor_plugin_active() ) {
        $editor_option = get_option( 'classic-editor-replace' );
        $block_editor_active = array( 'no-replace', 'block' );

        return in_array( $editor_option, $block_editor_active, true );
      }

      return true;
  }

  public function classic_editor_plugin_active() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
      include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
      return true;
    }

    return false;
  }
    
  public function update_serial_postmeta_records($replace_image_location, $replace_destination_url) {
    
    global $wpdb;
    
    // = instead oflike?   
    $sql = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'panels_data' and meta_value like '%$replace_image_location%'";
    
    $widgets = array('text','content','url','mp4','m4v','webm','ogv','flv');

    $records = $wpdb->get_results($sql);
    foreach($records as $record) {
      
      //error_log($record->post_id);
            
      $data = unserialize($record->meta_value);
      
      if (isset($data['widgets']) && is_array($data['widgets'])) {
        
        for ($index = 0; $index < count($data['widgets']); $index++) {  
          
          foreach($widgets as $widget) {
            
            if(isset($data['widgets'][$index][$widget])) {
              
              if(is_string($data['widgets'][$index][$widget])) {
                $text = $data['widgets'][$index][$widget];
                //error_log("$widget: $text");
                $data['widgets'][$index][$widget] = str_replace($replace_image_location, $replace_destination_url, $text);
                //error_log($data['widgets'][$index][$widget]);
              }
            }
            
          }
          
        }
        
      }
      
      //$data = serialize($data);
      //error_log(print_r($data, true));
      
		  update_post_meta($record->post_id, $record->meta_key, $data);												      
    }        
  }
  
  public function mlfp_save_role_access() {
    
    global $wpdb;
    $retval = false;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE))
      exit(__('missing nonce!','maxgalleria-media-library'));
    
    if ((isset($_POST['role_id'])) && (strlen(trim($_POST['role_id'])) > 0))
      $role_id = trim(stripslashes(strip_tags($_POST['role_id'])));
    else
      $role_id = "";
    
    if ((isset($_POST['serial_folders_allowed'])) && (strlen(trim($_POST['serial_folders_allowed'])) > 0))
      $serial_folders_allowed = trim(stripslashes(strip_tags($_POST['serial_folders_allowed'])));
    else
      $serial_folders_allowed = "";
    
    $serial_folders_allowed = str_replace('"', '', $serial_folders_allowed);    
    
    //$serial_folders_allowed = explode(',', $serial_folders_allowed);            
        
    if ((isset($_POST['serial_parents'])) && (strlen(trim($_POST['serial_parents'])) > 0))
      $serial_parents = trim(stripslashes(strip_tags($_POST['serial_parents'])));
    else
      $serial_parents = "";
    
    $serial_parents = str_replace('"', '', $serial_parents);    
    
    
    if ((isset($_POST['new_role'])) && (strlen(trim($_POST['new_role'])) > 0))
      $new_role = trim(stripslashes(strip_tags($_POST['new_role'])));
    else
      $new_role = false;
    
    if($new_role == 'true')
      $new_role = true;
    else
      $new_role = false;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;
        
    if($new_role) {
      
      $data = array(
        'user_role' => $role_id,
        'folders' => $serial_folders_allowed,
        'parents' => $serial_parents,  
        'permissions' => ''  
      );
      
      $retval = $wpdb->insert($table, $data);
      
    } else {
      
      $data = array(
        'folders' => $serial_folders_allowed,
        'parents' => $serial_parents,  
        'permissions' => ''  
      );
      
      $where = array(
        'user_role' => $role_id,          
      );
      
      if(empty($serial_folders_allowed)) {
        $retval = $wpdb->delete($table, $where);
      } else {             
        $retval = $wpdb->update($table, $data, $where);        
      }  
            
    }
        
    $message = __('The role setting were saved.','maxgalleria-media-library');
        
    echo $message;
    
    die();
  }
  
  public function mlfp_get_role_data() {
    
    global $wpdb;
    $new_role = true;							
    $folders = "";
    $permissions = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE))
      exit(__('missing nonce!','maxgalleria-media-library'));
    
    if ((isset($_POST['role_id'])) && (strlen(trim($_POST['role_id'])) > 0))
      $role_id = trim(stripslashes(strip_tags($_POST['role_id'])));
    else
      $role_id = "";    
    
    if($role_id != "") {
      
      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;
      
      $sql = "select folders, permissions from $table where user_role = '$role_id'"; 
      
      $role = $wpdb->get_row($sql);
      //error_log(print_r($role, true));
      
      if($role) {
        $new_role = false;        
        $folders = explode(',', $role->folders);    
        $permissions = $role->permissions;        
      } else {        
        $new_role = true;								        
      }
              
    }
    
	  $data = array('new_role' => $new_role, 'folders' => $folders, 'permissions' => $permissions);								
    
		echo json_encode($data);						
        
    die();
    
  }
  
  public function get_allowed_folders() {
    
    global $wpdb;
    
    $allowed_folders = array();
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;
    
    $where = "";
    
    $first = true;
        
    $user_roles = $this->get_user_role();
    
    foreach($user_roles as $user_role) {
      if($first)
        $first = false;
      else
        $where .= " or ";
      
      $where .= " user_role = '$user_role' ";
    }
        
    $sql = "select folders, parents from $table where $where";       
    
    $roles = $wpdb->get_results($sql);
    
    //$role = $wpdb->get_row($sql);
//    if($role) {
//      $folders = explode(',', $role->folders);    
//      $parents = explode(',', $role->parents);    
//      $allowed_folders = array_merge($parents, $folders);
//    }
    
    $allowed_folders = array();
    
    foreach($roles as $role) {
      $folders = explode(',', $role->folders);    
      $parents = explode(',', $role->parents);    
      $temp_folders = array_merge($parents, $folders);
      $allowed_folders = array_merge($allowed_folders, $temp_folders);
    }
                  
    return $allowed_folders;
    
  }
  
  public function get_user_role() {
    $user = wp_get_current_user();
    //return $user->roles ? $user->roles[0] : false;
    return $user->roles;
  }
  
  public function get_compatible_parent_user_role($parent_id) {
    
    global $wpdb;
    
    $user = wp_get_current_user();
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;
    
    $parent_id = strval($parent_id);
    
    foreach($user->roles as $role) {
      
      $sql = "select folders from $table where user_role = '$role'";       
      
      $parents = $wpdb->get_var($sql);
      
      if($parents) {
        $parents = explode(',', $parents);    
        if(in_array($parent_id, $parents)) {
          return($role);
        }  
      }
    }
    return false;
  }

  public function append_folder_id($user_role, $new_folder_id) {
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_USERROLE_TABLE;

    $sql = "select folders from $table where user_role = '$user_role'"; 

    $role = $wpdb->get_row($sql);
    
    if($role)
      $folders = $role->folders;
    else
      $folders = "";
    
    if(strlen($folders) > 0) {   
      $folders .= ',';
      $folders .= $new_folder_id;
    } else {
      $folders = $new_folder_id;      
    }  
    
    $data = array(
      'folders' => $folders
    );

    $where = array(
      'user_role' => $user_role,          
    );

    $retval = $wpdb->update($table, $data, $where);
        
    return $retval;
        
  }
    
  public function thumbnail_management() {
	  require_once 'includes/thumbnail_management.php';	 		    
  }
  
  public function refresh_thumbnail_table() {
    
    global $wpdb, $_wp_additional_image_sizes; 
    
    //error_log(print_r($_wp_additional_image_sizes, true));
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
  
    foreach($_wp_additional_image_sizes as $key => $value) {
      
      //echo "$key: " . $value['width'] . " " . $value['height'] . PHP_EOL;
      if($key != 'post-thumbnail') {
        
        $sql = "select thumbnail_id from $table where size = '$key'";
        $row = $wpdb->get_row($sql);
        if($row == false) {
          //error_log("$key not found");
          if($value['height'] > 0) {
            $data = array(
              'size' => $key,
              'width' => $value['width'],
              'height' => $value['height'],
              'generate' => true
            );

            $wpdb->insert($table, $data);
            //error_log("$key added");
          }

        }                      
      }      
    }
  }  
    
  public function display_thumbnail_table() {
    
    global $wpdb; 
    
    $file_count = $this->get_media_file_count();
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
    
    $sql = "select * from $table order by thumbnail_id";
    
    $rows = $wpdb->get_results($sql);
    if($rows) {
      ?>
        <input type="hidden" id="total-files" value="<?php echo $file_count; ?>">
        <table>
          <tr>
            <th>Generate</th>
            <th>Thumbnail Size</th>
            <th>Width</th>
            <th>Height</th>
          </tr>
      <?php
      foreach($rows as $row) {

      ?>
          <tr>
            <td><input type="checkbox" name="<?php echo $row->size; ?>" id="<?php $row->size; ?>" class="thumbnail-size" value="" <?php checked($row->generate , 1) ?> data="<?php echo $row->thumbnail_id; ?>" ></td>
            <td><?php echo $row->size; ?></td>
            <td><?php echo $row->width; ?></td>
            <td><?php echo $row->height; ?></td>
          </tr>
      <?php
      }    
      ?>
        </table>
        <div style="clear:both"></div>
        
        <div id="upload_message" class="alert alert-success" style="display: none;"></div>
        <div id="upload_progress" class="alert alert-info" style="">
          <table>
            <tbody><tr>
              <td rowspan="2" width="40" valign="top">
                <img src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL; ?>/images/loading.gif" style="margin-top: 3px;">
              </td>
              <td valign="top">
                <h5 id="mlfp_upload_message" style="font-size:12px;"><?php _e('Please wait while thumbnail files are removed from the site. This may take a few minutes, depending on the number of files.', 'maxgalleria-media-library'); ?></h5>
              </td>
            </tr>
            <tr>
              <td valign="top">
                <div class="progress">
                  <div class="bar" style="width: 0%;"></div>
                </div>
              </td>
            </tr>
          </tbody></table>
        </div>	
        <div style="clear:both"></div>
        
        <ul id="tn-row">
          <li>
            <a id="mlfp-save-thumbnails" class="tm-link">
              <i class="fa-solid fa-file-arrow-down fa-3x"></i>
              <p><?php _e('Save Thumbnail Settings', 'maxgalleria-media-library'); ?></p>
            </a>  
          </li>
          <li>
            <a id="mlfp-remove-thumbnails" class="tm-link">
              <i class="fa-solid fa-eraser fa-3x"></i>
              <p><?php _e('Remove Unselected Thumbnail Sizes', 'maxgalleria-media-library'); ?></p>
            </a>
          </li>
          <li>
            <a id="mlfp-thumbnail-reset" class="tm-link">
              <i class="fa-solid fa-rotate-right fa-3x"></i>
              <p><?php _e('Reset Defaults', 'maxgalleria-media-library'); ?></p>
            </a>
          </li>
        </ul>          
        
        <script>
        jQuery(document).ready(function(){
          
          jQuery(document).on("click","#mlfp-save-thumbnails",function(){
            
            // promise array/counter will call refresh when done
            var promisesArray = [];
            var successCounter = 0;
            var promise;			
                        
            jQuery("#ajaxloader-tb").show();            
            jQuery('input[type=checkbox].thumbnail-size').each(function() {  
              var thumbnail_id = jQuery(this).attr("data");
              var checked = false;
              if(jQuery(this).is(":checked")) {
                checked = true;                
              }  
              
					    promise = 
              jQuery.ajax({
                type: "POST",
                async: true,
                data: { action: "mlfp_update_tn_settings", thumbnail_id: thumbnail_id, checked: checked, nonce: mgmlp_ajax.nonce },
                url : mgmlp_ajax.ajaxurl,
                dataType: "html",
                success: function (data) {
                  console.log(thumbnail_id + ' saved');
                },
                error: function (err) { 
                  //jQuery("#ajaxloader").hide();
                  alert(err.responseText);
                }
              });  
                                          
            });
            
            promise.done(function(msg) {
                //console.log("successfully updated"); 
                successCounter++;
            });

            promise.fail(function(jqXHR) { /* error out... */ });

            promisesArray.push(promise);
            
            jQuery.when.apply($, promisesArray).done(function() {
              jQuery("#upload_message").show();
              jQuery("#upload_message").html("<?php _e('Thumbnail settings saved.','maxgalleria-media-library'); ?>");
              jQuery("#ajaxloader-tb").hide();
            });
                        
          });  
          
		      //jQuery("#mlfp-remove-thumbnails").click(function () {
          jQuery(document).on("click","#mlfp-remove-thumbnails",function(){
          
            var total_files = jQuery('#total-files').val();
            var tn_sizes = new Array();
            
            jQuery('input[type=checkbox].thumbnail-size').each(function() {  
              if(!jQuery(this).is(":checked")) {
                tn_sizes[tn_sizes.length] = jQuery(this).attr("name");
              }  
            });

			      if(tn_sizes.length > 0) {
				      var serial_tn_sizes = JSON.stringify(tn_sizes.join());
              //console.log('total_files ' + total_files);
              //console.log(serial_tn_sizes);
                         
              //console.log('remove_selected_thumbnails');
              
              //jQuery("#ajaxloader-tb").show();
              jQuery("#upload_message").show();
              jQuery("#upload_message").html('<?php _e('Removing selected thumbnails. Please wait.', 'maxgalleria-media-library'); ?>')
              jQuery("#upload_progress").show();
              
          	  remove_selected_thumbnails(0, total_files, serial_tn_sizes);
            }

          });
          
		      //jQuery("#mlfp-thumbnail-reset").click(function () {
          jQuery(document).on("click","#mlfp-thumbnail-reset",function(){
          
            if(confirm("<?php _e('Are you sure you want reset thumbnail data?','maxgalleria-media-library'); ?>")) {
              
              jQuery("#ajaxloader-tb").show();
              jQuery.ajax({
                type: "POST",
                async: true,
                data: { action: "mlfp_thumbnail_reset", nonce: mgmlp_ajax.nonce },
                url : mgmlp_ajax.ajaxurl,
                dataType: "html",
                success: function (data) {
                  window.location.reload(true);                  
                },
                error: function (err) { 
                  //jQuery("#ajaxloader").hide();
                  alert(err.responseText);
                }
              });  
              
            }
            
          });
          
        }); 
                
        function remove_selected_thumbnails(last_file, file_count, serial_tn_sizes) {
          //console.log(last_folder);

          //if(progress == false)
          //  return false;

          jQuery.ajax({
            type: "POST",
            async: true,
            data: { action: "mlfp_remove_thumbnails", last_file: last_file, file_count: file_count, serial_tn_sizes: serial_tn_sizes, nonce: mgmlp_ajax.nonce },
            url: mgmlp_ajax.ajaxurl,
            dataType: "json",
            success: function (data) { 
              console.log('data ' + data);
              if(data != null && data.last_file != null) {
                console.log(data.percentage);
                jQuery("#upload_progress").show();
                jQuery("#upload_progress .progress .bar").css("width", data.percentage + "%");
                jQuery("#upload_message").html(data.message);
                remove_selected_thumbnails(data.last_file, file_count, serial_tn_sizes);
              } else {
                jQuery("#upload_message").html('<?php _e('Thumbnail removal complete.', 'maxgalleria-media-library'); ?>');
                jQuery("#upload_progress").delay(2500).slideUp(500);
                //jQuery("#ajaxloader-tb").hide();
                return false;
              }	
            },
            error: function (err){ 
              alert(err.responseText)
            }
          });																											
        }
        
        
        </script>  
        
      <?php
    }
  }  
    
  public function mlfp_remove_thumbnails() {
    
    global $wpdb, $is_IIS;
    $file_path = "";
    $location = "";
    $update_metadata = false;
    
    //error_log("mlfp_remove_thumbnails");
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['last_file'])) && (strlen(trim($_POST['last_file'])) > 0))
      $last_file = trim(stripslashes(strip_tags($_POST['last_file'])));
    else
      $last_file = 0;
    
    if ((isset($_POST['file_count'])) && (strlen(trim($_POST['file_count'])) > 0))
      $file_count = trim(stripslashes(strip_tags($_POST['file_count'])));
    else
      $file_count = 0;
    
    if ((isset($_POST['serial_tn_sizes'])) && (strlen(trim($_POST['serial_tn_sizes'])) > 0)) {
      $tn_sizes = trim(stripslashes(strip_tags($_POST['serial_tn_sizes'])));
      $tn_sizes = str_replace('"', '', $tn_sizes);
      $tn_sizes = explode(",",$tn_sizes);
    }  
    else
      $tn_sizes = '';
    
    //error_log("last_file $last_file");
            
		$percentage = (($last_file+1) / $file_count) * 100;
						
			$sql = "select mf.post_id, mf.folder_id, p.post_type, p.post_mime_type, pm.meta_value as file_path, pm2.meta_value as metadata
from {$wpdb->prefix}mgmlp_folders as mf
LEFT JOIN {$wpdb->prefix}posts as p ON p.ID = mf.post_id
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = mf.post_id and pm.meta_key = '_wp_attached_file' )
LEFT JOIN {$wpdb->prefix}postmeta AS pm2 ON (pm2.post_id = mf.post_id and pm2.meta_key = '_wp_attachment_metadata' )
where p.post_type = 'attachment'
order by mf.post_id limit $last_file, 1";

    $row = $wpdb->get_row($sql);

    if($row) {
      
      //error_log("post_id " . $row->post_id);      

      // get the folder path
      $location = $this->build_location_url($row->file_path);
      $position = strrpos($location, "/");        
			$location_folder = substr($location, 0, $position+1);
      //error_log("file: " . $row->file_path);
            
        //if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
        //  $location = str_replace('\\', '/', $location);      
                  
      $metadata = unserialize($row->metadata);				
      
      //error_log(print_r($tn_sizes, true));
      //error_log("metadata");
      //error_log(print_r($metadata['sizes'], true));
     
      if(isset($metadata['sizes'])) {
        foreach($metadata['sizes'] as $key => $value ) {
          if(in_array($key, $tn_sizes)) {
            //error_log($key);
            //error_log(print_r($value['file'], true));

            // get the path to the thumbnail
            $file_path = $location_folder . $value['file'];
            $source_file = $this->get_absolute_path($file_path);

            // convert if windows is running
            if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
              $source_file = str_replace('\\', '/', $source_file);      

            // delete the thumbnail
            //error_log("deleting $source_file");        
            if(file_exists($source_file))
              unlink($source_file);

            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || 
               $this->s3_addon->license_status == S3_FILE_COUNT_WARNING || $this->s3_addon->license_status == S3_FILE_COUNT_EXCEDED)) {
              if($this->s3_addon->s3_active && $this->s3_addon->serve_from_s3) {
                $thumbnail_location = $this->s3_addon->get_location($source_file, $this->uploads_folder_name);
                //error_log("deleting: $thumbnail_location");
                $this->s3_addon->remove_from_s3("", $thumbnail_location);
              }
            }  

          }
        }
      }
              
      // remove the thumbnail from metadata
      //error_log(print_r($metadata['sizes'], true));
//      foreach($tn_sizes as $size) {
//        if(isset($metadata['sizes'][$size])) {
//          unset($metadata['sizes'][$size]);
//          $update_metadata = true;
//        }  
//      }
      //error_log(print_r($metadata['sizes'], true));
      if($update_metadata)
        update_post_meta( $row->post_id, '_wp_attachment_metadata', $metadata );
            
      $last_file++;
      // $row->post_id
     
      //error_log("$location, $last_file, $percentage");
      $data = array('message' => __('Removeing unselected thumbnail sizes for ','maxgalleria-media-library') . $row->file_path, 'last_file' => $last_file, 'percentage' => $percentage );				
    } else {
      $data = array('message' => __('The unselected thumbnails sizes were removed.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );								
    }			
			
	  echo json_encode($data);
      
    die();    
  }
  
  public function mlfp_update_tn_settings() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['thumbnail_id'])) && (strlen(trim($_POST['thumbnail_id'])) > 0))
      $thumbnail_id = trim(stripslashes(strip_tags($_POST['thumbnail_id'])));
    else
      $thumbnail_id = 0;
    
    if ((isset($_POST['checked'])) && (strlen(trim($_POST['checked'])) > 0))
      $checked = trim(stripslashes(strip_tags($_POST['checked'])));
    else
      $checked = 0;
    
    if($thumbnail_id != 0) {
            
      $checked = ($checked == 'true') ? 1 : 0;
      
      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
      
      $data = array('generate' => $checked );
      
      $format = array('%s', '%d');
  
      $where = array('thumbnail_id' => $thumbnail_id);
      
      $where_format = array('%d');
      
      $wpdb->update($table, $data, $where, $format, $where_format);
          
    }
    
    die();
  }
  
  public function get_sizes_to_deactivate() {
    
    global $wpdb;
    global  $_wp_additional_image_sizes;     
                
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
        
    $sql = "select * from $table where generate = 0";
        
    try
    {
      $rows = $wpdb->get_results($sql);

      if($rows) {
        foreach($rows as $row) {
          $command = "remove_image_size('" . $row->size . "');";
          //error_log($command);
          eval($command);
        }
      }

    }	
    catch(PDOException $e)
    {
        error_log("$table not found. Please deactivate and reactivate the plugin to add the table.");
    }
        
  }
    	
	public function get_media_file_count() {
		
		global $wpdb;
    
		$sql = "SELECT COUNT(DISTINCT p.ID) FROM {$wpdb->prefix}posts as p WHERE p.post_type = 'attachment'";
	
		$file_count = $wpdb->get_var($sql);
    
    if($file_count)						
		  return $file_count;
    else
		  return 0;
		
	}

  public function mlfp_thumbnail_reset() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_THUMBNAIL_TABLE;
    $sql = "TRUNCATE TABLE $table";
    $wpdb->query($sql);

    //error_log($wpdb->last_query);
    //error_log(print_r($wpdb->last_result, true));
    //error_log($wpdb->last_error);			
    
    echo "ok";
    
    die();
  }
  
  public function mgmlp_filter_images() {
    
    global $wpdb;
    $images_found = true;
    //$display_type = 1;
    $mif_visible = 1;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['folder_id'])));
    else
      $current_folder_id = 0;
    
    if ((isset($_POST['filter'])) && (strlen(trim($_POST['filter'])) > 0))
      $filter = trim(stripslashes(strip_tags($_POST['filter'])));
    else
      $filter = 0;
    
    if ((isset($_POST['display_type'])) && (strlen(trim($_POST['display_type'])) > 0))
      $display_type = intval(trim(stripslashes(strip_tags($_POST['display_type']))));
    else
      $display_type = 1;
    
    if ((isset($_POST['grid_list_switch'])) && (strlen(trim($_POST['grid_list_switch'])) > 0))
      $grid_list_switch = trim(stripslashes(strip_tags($_POST['grid_list_switch'])));
    else
      $grid_list_switch = 'false';
    
		if ((isset($_POST['page_id'])) && (strlen(trim($_POST['page_id'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['page_id']))));
    else
      $page_id = 0;
    
    //error_log(print_r($_POST,true));
    
    $sort_order = get_option(MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER);
		    
    switch($sort_order) {
      default:
      case '0': //order by date
        $order_by = 'post_date DESC';
        break;
      
      case '1': //order by name
        $order_by = 'post_title';
        break;      
    }
    
		//if($image_link === "1")
			$image_link = true;
		//else
		//	$image_link = false;
        
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
    //$page_id = 0;
    
		$items_per_page = intval(get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '40'));
		$enable_pagination = get_option(MAXGALLERIA_MLP_PAGINATION, 'off');
        
    $offset = $page_id * $items_per_page;

    if($enable_pagination == 'on')
      $limit = "limit $offset, $items_per_page";
    else
      $limit = "";
      
    //error_log("filter display_files filter $filter");
    //$this->display_files('1', $folder_id, $folder_table, 1, $order_by, $filter );
    
    //echo "filtering files";
    
    if(empty($filter)) {
      $sql = "select SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.ID, post_title, post_date, $folder_table.folder_id, pm.meta_value as attached_file, us.display_name, {$wpdb->prefix}posts.post_date  
from {$wpdb->prefix}posts 
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
LEFT JOIN {$wpdb->prefix}users AS us ON ({$wpdb->prefix}posts.post_author = us.ID) 
where post_type = 'attachment' 
and folder_id = '$current_folder_id'
AND pm.meta_key = '_wp_attached_file' 
order by $order_by $limit";
  
} else       {
            $sql = "select SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.ID, post_title, post_date, $folder_table.folder_id, pm.meta_value as attached_file, us.display_name, {$wpdb->prefix}posts.post_date  
from {$wpdb->prefix}posts 
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
LEFT JOIN {$wpdb->prefix}users AS us ON ({$wpdb->prefix}posts.post_author = us.ID) 
where post_type = 'attachment' 
and folder_id = '$current_folder_id'
AND pm.meta_key = '_wp_attached_file' 
AND pm.meta_value like '%%$filter%%'
order by $order_by $limit";
}

      //error_log($sql);

      $rows = $wpdb->get_results($sql);            

      $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
      $total_images = $count['FOUND_ROWS()'];
      if($items_per_page != 0)
        $total_number_pages = ceil($total_images / $items_per_page);
      else
        $total_number_pages = 0;

      //error_log("grid_list_switch $grid_list_switch");
      //error_log("total_images $total_images, total_number_pages $total_number_pages");

      if($grid_list_switch == 'true' || $grid_list_switch == 'on') {

        echo "<style>

        .media-folder, .media-attachment, .media-attachment img, a.tb-media-attachment img, a.media-attachment img {
          height: 135px !important;
          width: 135px !important;    
        }

        ul.mg-media-list li a {
          width: 135px;
        }

        </style>";
        
        echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'on', false, false);
        
        echo '<ul class="mg-media-list">' . PHP_EOL;

        if($rows) {
          $images_found = true;
          $counter = 1;
          foreach($rows as $row) {
            $thumbnail_html = "";
            $image_file_type = true;
            if($display_type == 1 || $display_type == 0) {
              $new_attachment_id = $row->ID;
              //if(is_array($new_attachment_id))
              //  error_log("wp_get_attachment_image id");
              $thumbnail_html = wp_get_attachment_image( $new_attachment_id, 'thumbnail', false, '');
              if(!$thumbnail_html){
                $thumbnail = wp_get_attachment_thumb_url($new_attachment_id);                
                //if(is_array($thumbnail))
                //  error_log("wp_get_attachment_image thumbnail");
                if($thumbnail === false || $display_type == 2) {									
                  $ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
                  //if(is_array($ext))
                  //  error_log("wp_get_attachment_image ext");
                  //$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-types/default.png";
                  $thumbnail = $this->get_file_thumbnail($ext);
                  $image_file_type = false;
                }
                $thumbnail_html = "<img alt='' src='$thumbnail' />";
              }  
            } else {
              $thumbnail_html = "";
            }

            $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );
            if($image_link && $mif_visible)
              $class = "media-attachment no-pointer"; 
            else
              $class = "media-attachment"; 

            if($display_type == 2) // from bcmc version
              $class .= " mlfp-list-images"; 

            $s3_class = "";
            if($display_type == 1 || $display_type == 0) {
              if(class_exists('MGMediaLibraryFoldersProS3')) {			
                if($this->s3_addon->s3_active) {
                  if($this->s3_addon->serve_from_s3) {
                    //error:log("thumbnail $thumbnail");
                    if($image_file_type) {
                      if(!strpos($thumbnail_html, $this->s3_addon->bucket))
                        $s3_class = "on-local";
                    }
                  }					
                }
              }
            }

            // for WP 4.6 use /wp-admin/post.php?post=
            if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
              $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
            else
              $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;

            //$image_location = $this->check_for_attachment_id($row->guid, $row->ID);
            //$image_location = $this->upload_dir['baseurl'] . '/' . $row->attached_file;
            $baseurl = $this->upload_dir['baseurl'];
            $baseurl = rtrim($baseurl, '/') . '/';
            $image_location = $baseurl . ltrim($row->attached_file, '/');

            $filename = pathinfo($image_location, PATHINFO_BASENAME);
            //error_log("image_link $image_link, mif_visible $mif_visible, display_type $display_type");

            if($display_type == 2)
              echo "<li id='$row->ID' class='mlpf-file-list'>" . PHP_EOL;
            else
              echo "<li id='$row->ID'>" . PHP_EOL;

            if($display_type == 1 || $display_type == 0) {
              if($mif_visible)
                echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;
              else if($image_link && !$mif_visible)
                echo "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'>$thumbnail_html</a>" . PHP_EOL;
              else
                echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;

              if(defined('MLFP_SHOW_TITLES')) {               
                  echo "   <div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span><br>$filename</div>" . PHP_EOL;
                //echo "</li>" . PHP_EOL;        
              } else {
                echo "   <div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span>$filename</div>" . PHP_EOL;
              }
            } else {

              if(defined('MLFP_SHOW_TITLES')) {               
                if($counter % 2)
                  $thumbnail_html = "<div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span> - $filename</div>";
                else
                  $thumbnail_html = "<div class='attachment-name transparent $s3_class'><span class='image_select'>$checkbox</span><span class='attachment-title'>$row->post_title</span> - $filename</div>";   
              } else {
                $thumbnail_html = "<div class='attachment-name $s3_class'><span class='image_select'>$checkbox</span>$filename</div>";
              }

              if($mif_visible) {
                echo "   <a id='$row->ID' class='$class' title='$filename'></a>" . $thumbnail_html . PHP_EOL;
              } else if($image_link && !$mif_visible) {
                echo "   <a id='$row->ID' class='$class' href='" . site_url() . $media_edit_link . "' target='_blank' title='$filename'></a>" . $thumbnail_html . PHP_EOL;
              } else {
                echo "   <a id='$row->ID' class='$class' title='$filename'>$thumbnail_html</a>" . PHP_EOL;                  
              }  
            }
            echo "</li>" . PHP_EOL;                          
            $counter++;
          }                                       
        }
        
      if(!$images_found)
        echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
      else {
//        if($enable_pagination == 'on') {							
//          $previous_page = $page_id - 1;
//          $next_page = $page_id + 1;
//          echo "<div class='mlfp-page-nav'>" . PHP_EOL;
//          if($page_id > 0)	
//            echo "<a id='mlfp-previous' page-id='$previous_page' image_link='$image_link' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
//          if($page_id < $total_number_pages-1 && $total_images > $items_per_page)
//            echo "<a id='mlfp-next' page-id='$next_page' image_link='$image_link' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
//          echo "</div>" . PHP_EOL;
//        }
        if($this->license_valid) {                    
          //echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, true);
        }
      }  
        
        
      } else {

        $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
        $total_images = $count['FOUND_ROWS()'];
        $total_number_pages = ceil($total_images / $items_per_page);

        echo "<style>

        ul.mg-media-list li {
          display: table-row;
          float: none;
          /*height: 40px;*/
          list-style: outside none none;
          margin: 0;
          max-width: none;
          overflow: visible;
          width: 100%;
        }

        ul.mg-media-list li {
          height: auto;
        }

        </style>";
        
        echo $this->display_secondary_toolbar($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, 'on', false, false);    
        
        if($rows) {
          $images_found = true;
          $counter = 1;
          echo '<ul class="mg-media-list">' . PHP_EOL;
          foreach($rows as $row) {
            $thumbnail_html = "";
            $image_file_type = true;
            if($display_type == 1 || $display_type == 0) {
              $new_attachment_id = $row->ID;
              $thumbnail_html = wp_get_attachment_image( $new_attachment_id, 'thumbnail', false, '');
              if(!$thumbnail_html){
                $thumbnail = wp_get_attachment_thumb_url($new_attachment_id);                
                if($thumbnail === false || $display_type == 2) {									
                  $ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
                  $thumbnail = $this->get_file_thumbnail($ext);
                  $image_file_type = false;
                }
                $thumbnail_html = "<img alt='' src='$thumbnail' />";
              }  
            } else {
              $thumbnail_html = "";
            }

            $checkbox = sprintf("<input type='checkbox' class='mgmlp-media' id='%s' value='%s' />", $row->ID, $row->ID );

            $s3_class = "";
            if($display_type == 1 || $display_type == 0) {
              if(class_exists('MGMediaLibraryFoldersProS3')) {			
                if($this->s3_addon->s3_active) {
                  if($this->s3_addon->serve_from_s3) {
                    //error:log("thumbnail $thumbnail");
                    if($image_file_type) {
                      if(!strpos($thumbnail_html, $this->s3_addon->bucket))
                        $s3_class = "on-local";
                    }
                  }					
                }
              }
            }

            // for WP 4.6 use /wp-admin/post.php?post=
            if( version_compare($this->wp_version, NEW_MEDIA_LIBRARY_VERSION, ">") )
              $media_edit_link = "/wp-admin/post.php?post=" . $row->ID . "&action=edit";
            else
              $media_edit_link = "/wp-admin/upload.php?item=" . $row->ID;


            $baseurl = $this->upload_dir['baseurl'];
            $baseurl = rtrim($baseurl, '/') . '/';
            $image_location = $baseurl . ltrim($row->attached_file, '/');
            $filename = pathinfo($image_location, PATHINFO_BASENAME);

            if($counter % 2)
              echo '<li class="row-item gray-row">';
            else
              echo '<li class="row-item">';
            echo '  <span class="mlfp-list-cb">'.$checkbox.'</span>';
            echo '  <span class="mlfp-list-image"><a id="'.$row->ID.'" class="media-attachment list edit-link" >'.$thumbnail_html.'</a></span>';
            echo '  <span class="mlfp-list-title">'.$row->post_title.'</span>';
            echo '  <span class="mlfp-list-file '.$s3_class.'">'.$filename.'</span>';
            echo '  <span class="mlfp-list-author">'.$row->display_name.'</span>';
            echo '  <span class="mlfp-list-cat">'. $this->get_media_categories($row->ID) .'</span>';
            echo '  <span class="mlfp-list-date">'. date("Y-m-d", strtotime($row->post_date)) .'</span>';
            echo '</li>';

            $counter++;
          }      
        }


      }  

      echo '</ul>' . PHP_EOL;
      echo '<div style="clear:both"></div>' . PHP_EOL;


      echo '      <script>' . PHP_EOL;
      echo '				jQuery(document).ready(function(){' . PHP_EOL;
      echo '			    jQuery("#folder-message").html("");' . PHP_EOL;
      //echo '				  console.log(window.hide_checkboxes);' . PHP_EOL;
      echo '				  if(window.hide_checkboxes) {' . PHP_EOL;
      echo '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").hide();' . PHP_EOL;
      echo '	          jQuery("a.tb-media-attachment").css("cursor", "pointer");' . PHP_EOL;
      echo '				  } else {' . PHP_EOL;
      echo '					  jQuery("div#mgmlp-tb-container input.mgmlp-media").show();' . PHP_EOL;
      echo '	          jQuery("a.tb-media-attachment").css("cursor", "default");' . PHP_EOL;
      echo '				  }' . PHP_EOL;
      echo '          jQuery("li a.media-attachment").draggable({' . PHP_EOL;
      echo '          	cursor: "move",' . PHP_EOL;
      echo '            helper: function() {' . PHP_EOL;
      echo '          	  var selected = jQuery(".mg-media-list input:checked").parents("li");' . PHP_EOL;
      echo '          	  if (selected.length === 0) {' . PHP_EOL;
      echo '          		  selected = jQuery(this);' . PHP_EOL;
      echo '          	  }' . PHP_EOL;
      echo '          	  var container = jQuery("<div/>").attr("id", "draggingContainer");' . PHP_EOL;
      echo '          	  container.append(selected.clone());' . PHP_EOL;
      echo '          	  return container;' . PHP_EOL;
      echo '            }' . PHP_EOL;
      echo '          });' . PHP_EOL;

      echo '          jQuery(document).on("click", "#mlfp-previous, #mlfp-next", function (e) {' . PHP_EOL;
      echo '            e.stopImmediatePropagation();' . PHP_EOL;
      echo '        	  jQuery("#ajaxloader").show();' . PHP_EOL;

      echo '        	  if(jQuery("#current-folder-id").val() === undefined) ' . PHP_EOL;
      echo '        		  var current_folder_id = sessionStorage.getItem("folder_id");' . PHP_EOL;
      echo '        	  else' . PHP_EOL;
      echo '        		  var current_folder_id = jQuery("#current-folder-id").val();' . PHP_EOL;

      echo '        	  var page_id = jQuery(this).attr("page-id");' . PHP_EOL;
      echo '        	  var image_link = jQuery(this).attr("image_link");' . PHP_EOL;

      echo '        	  jQuery.ajax({' . PHP_EOL;
      echo '        		  type: "POST",' . PHP_EOL;
      echo '          		async: true,' . PHP_EOL;
      echo '        	  	data: { action: "mlfp_get_next_attachments", current_folder_id: current_folder_id, page_id: page_id, image_link: image_link, nonce: mgmlp_ajax.nonce },' . PHP_EOL;
      echo '        		  url: mgmlp_ajax.ajaxurl,' . PHP_EOL;
      echo '        		  dataType: "html",' . PHP_EOL;
      echo '        		  success: function (data) {' . PHP_EOL;
      echo '        			  jQuery("#ajaxloader").hide();' . PHP_EOL;
      echo '        			  jQuery("#mgmlp-file-container").html(data);' . PHP_EOL;
      echo '        		  },' . PHP_EOL;
      echo '        		  error: function (err){' . PHP_EOL;
      echo '        			  jQuery("#ajaxloader").hide();' . PHP_EOL;
      echo '        			  alert(err.responseText);' . PHP_EOL;
      echo '        		  }' . PHP_EOL;
      echo '        	  });' . PHP_EOL;
      echo '          });' . PHP_EOL;

      echo '        });' . PHP_EOL;
      echo '      </script>' . PHP_EOL;

      //error_log("enable_pagination $enable_pagination, images_found $images_found");						

      if(!$images_found)
        echo "<p style='text-align:center'>" . __('No files were found.','maxgalleria-media-library')  . "</p>";
      else {
//        if($enable_pagination == 'on') {							
//          $previous_page = $page_id - 1;
//          $next_page = $page_id + 1;
//          echo "<div class='mlfp-page-nav'>" . PHP_EOL;
//          if($page_id > 0)	
//            echo "<a id='mlfp-previous' page-id='$previous_page' image_link='$image_link' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
//          if($page_id < $total_number_pages-1 && $total_images > $items_per_page)
//            echo "<a id='mlfp-next' page-id='$next_page' image_link='$image_link' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
//          echo "</div>" . PHP_EOL;
//        }
        //if($this->license_valid) {                    
        //  echo $this->bottom_pagination($total_images, $page_id, $total_number_pages, $image_link, $items_per_page, true, false);
        //}  
        
      }

    die();
  }
        
  public function mlfp_add_wmf_attachment() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['folder_id'])) && (strlen(trim($_POST['folder_id'])) > 0))
      $folder_id = intval(trim(stripslashes(strip_tags($_POST['folder_id']))));
    else
      $folder_id = 0;
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $image_id = intval(trim(stripslashes(strip_tags($_POST['image_id']))));
    else
      $image_id = 0;
    
    
    if($folder_id != 0 && $image_id != 0 ) {
      
      $folder = array($folder_id);
      
      //error_log("mlfp_add_wmf_attachment image_id  $image_id, folder_id $folder_id");
      
      $results = wp_set_post_terms($image_id, $folder, WPMF_TAXO );      
      
      if(!is_wp_error($results) && isset($results[0])) {
        $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        $data = array('term_id' => $results[0]);
        $where = array('post_id' => $image_id);           
        $wpdb->update($table, $data, $where);                             
      }
            
    }
    
    die();
  }
    
  public function mlfp_delete_terms() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $terms = get_terms( WPMF_TAXO, array( 'fields' => 'ids', 'hide_empty' => false ) );
    foreach ( $terms as $value ) {
      wp_delete_term( $value, WPMF_TAXO );
    }

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $sql = "UPDATE $table SET term_id = NULL WHERE term_id is not null";
    $wpdb->query($sql);
    
    echo "Cleared";
    die();
  }
  
  function mlfp_get_folder_count() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $this->alter_folder_table();
    
    update_option(MAXGALLERIA_WPMF, 'on');
    $this->wpmf_integration = 'on';
    
		update_option(MAXGALLERIA_REMOVE_FT, 'on', true);
    $this->disable_media_ft = 'on';    
    
    //$this->mlfp_export->add_folder_import_table();
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
    
    $sql = "TRUNCATE TABLE " . $table  ;
    
    $wpdb->query($sql);
    
    
    $sql = "SELECT count(*)  
FROM {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
WHERE post_type = 'attachment' AND pm.meta_key = '_wp_attached_file'";

    //error_log($sql);

    $total_files = $wpdb->get_var($sql);
    
    $wpmf_folder_root_id = get_option('wpmf_folder_root_id');
        
    //$folders = $this->mlfp_export->get_export_folder_data();
    
    $total_folders = count($folders);
            
    foreach($folders as $folder) {
      
      if($folder['parent'] != '#') {
                  
        $wpdb->insert($table, (array)$folder);
          
      } else {
        $folder['new_id'] = 0;
        $wpdb->insert($table, (array)$folder);
      }
      
    }  
      
    wp_send_json(array('total_folders' => $total_folders, 'total_files' => $total_files));
  
    die();
  }
  
  public function mlfp_import_next_folder() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['last_folder'])) && (strlen(trim($_POST['last_folder'])) > 0))
      $last_folder = intval(trim(stripslashes(strip_tags($_POST['last_folder']))));
    else
      $last_folder = 0;
    
    if ((isset($_POST['folder_count'])) && (strlen(trim($_POST['folder_count'])) > 0))
      $folder_count = intval(trim(stripslashes(strip_tags($_POST['folder_count']))));
    else
      $folder_count = 0;
        
    if ((isset($_POST['id_author'])) && (strlen(trim($_POST['id_author'])) > 0))
      $id_author = intval(trim(stripslashes(strip_tags($_POST['id_author']))));
    else
      $id_author = 0;
          
    remove_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10);    
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
				
		$percentage = (($last_folder+1) / $folder_count) * 100;
							
    $sql = "select * from $table order by id limit $last_folder, 1";

    //error_log($sql);

    $row = $wpdb->get_row($sql);
    
    if($row) {
      
      if($row->parent != 0) {
                
        $parent_id = $this->mlfp_get_term_id($row->parent);
                
        $inserted = wp_insert_term($row->text, WPMF_TAXO, array('parent' => $parent_id));    
                        
        if (is_wp_error($inserted)) {
          
          $last_folder++;

          $data = array('message' => $row->path . __(' already exists','maxgalleria-media-library'), 'last_folder' => $last_folder, 'percentage' => $percentage );				
          
          add_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10, 4);    
          echo json_encode($data);    
          die();
          
          
        } else {
                    
          $updateted = wp_update_term($inserted['term_id'], WPMF_TAXO, array('term_group' => $id_author));
          
          $term_info = get_term($updateted['term_id'], WPMF_TAXO);
          
          do_action('wpmf_create_folder', $inserted['term_id'], $row->text, $parent_id, array('trigger' => 'media_library_action'));                  
          
          //error_log("id " . $row->id . " term id " .  $inserted['term_id']);
          
          $data = array('new_id' => $inserted['term_id']);          
          $where = array('id' => $row->id);           
          $wpdb->update($table, $data, $where);
                    
          $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
          $data = array('term_id' => $inserted['term_id']);
          $where = array('post_id' => $row->id);           
          $wpdb->update($table, $data, $where);                   
        } 
                
      } else {
        $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        $data = array('term_id' => 0);
        $where = array('post_id' => $row->id);           
        $wpdb->update($table, $data, $where);                           
      }
      
      $last_folder++;

      $data = array('message' => $row->path, 'last_folder' => $last_folder, 'percentage' => $percentage );				
      
    } else {       
      $data = array('message' => __('Folders added.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );								      
    }	
    
    add_action('wpmf_create_folder', array($this, 'create_wpmf_folder'), 10, 4);    
        
	  echo json_encode($data);    
    die();
  }
  
  public function mlfp_import_next_file() {
    
    global $wpdb;
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['last_file'])) && (strlen(trim($_POST['last_file'])) > 0))
      $last_file = intval(trim(stripslashes(strip_tags($_POST['last_file']))));
    else
      $last_file = 0;
    
    if ((isset($_POST['file_count'])) && (strlen(trim($_POST['file_count'])) > 0))
      $file_count = intval(trim(stripslashes(strip_tags($_POST['file_count']))));
    else
      $file_count = 0;
        
    if ((isset($_POST['id_author'])) && (strlen(trim($_POST['id_author'])) > 0))
      $id_author = intval(trim(stripslashes(strip_tags($_POST['id_author']))));
    else
      $id_author = 0;
    
		$percentage = (($last_file+1) / $file_count) * 100;
        
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
    
    $sql = "SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title, pm.meta_value as attached_file, $folder_table.folder_id as parent_id  
FROM {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
WHERE post_type = 'attachment' 
AND pm.meta_key = '_wp_attached_file'
group by ID
ORDER by parent_id limit $last_file, 1";

    //error_log($sql);

    $row = $wpdb->get_row($sql);
    
    if($row) {
      
      $folder_id = $this->mlfp_get_term_id($row->parent_id);
                        
      $folder = array($folder_id);            
      
      $results = wp_set_post_terms($row->ID, $folder, WPMF_TAXO );      
            
      if(!is_wp_error($results) && isset($results[0])) {
        //$this->get_wpmf_term_id($name, $row->parent_id);
        $data = array('term_id' => $results[0]);
        $where = array('post_id' => $row->ID);           
        $wpdb->update($folder_table, $data, $where);                             
      }
            
      $last_file++;

      $data = array('message' => $row->attached_file, 'last_file' => $last_file, 'percentage' => $percentage );				
      
    } else {
      
      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;    
      $sql = "TRUNCATE TABLE " . $table;    
      $wpdb->query($sql);
            
      $data = array('message' => __('Files added.','maxgalleria-media-library'), 'last_file' => null, 'percentage' => 100 );								
    }	

	  echo json_encode($data);    
    
    die();   
  }
  
  public function create_wpmf_folder($termID, $folder_name, $folder_parent, $trigger) {
    
    $parent_id = $this->get_mlfp_parent2($folder_parent);

    $this->create_new_mlfp_folder($parent_id, $folder_name, false, $termID);
    
  }
  
  public function delete_wpmf_folder($termID) {
      
    global $wpdb;
        
    $delete_id = $this->get_mlfp_parent2($termID->term_id);
    
    if(!empty($delete_id)) {
    
      $parent_folder = $this->get_parent($delete_id);

      $formatted_query = "select post_title, post_type, pm.meta_value as attached_file 
from {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where ID = %d 
AND pm.meta_key = '_wp_attached_file'";

      $sql = $wpdb->prepare($formatted_query, $delete_id);

      //error_log($sql);

      $row = $wpdb->get_row($sql);

      if($row) {

        $baseurl = $this->upload_dir['baseurl'];
        $baseurl = rtrim($baseurl, '/') . '/';
        $image_location = $baseurl . ltrim($row->attached_file, '/');

        $folder_path = $this->get_absolute_path($image_location);
        $del_post = array('post_id' => $delete_id);                        

        $this->mlfp_delete_single_folder($delete_id, $folder_path, $del_post, $parent_folder, $row, $image_location, false);    
      }

    }
          
  }
  
  public function mlfp_get_term_id($folder_parent) {
   
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        
    $sql = "select term_id from $table where post_id = $folder_parent";
    
		$term_id = $wpdb->get_var($sql);
    
    return $term_id;
        
  }
  
  public function get_mlfp_parent($folder_parent) {
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
        
    $sql = "select parent from $table where new_id = $folder_parent";
    
		$parent_id = $wpdb->get_var($sql);
    
    return $parent_id;
        
  }
  
  public function get_mlfp_parent2($folder_parent) {
        
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        
    $sql = "select post_id from $table where term_id = $folder_parent";
    
    //error_log($sql);
    
		$parent_id = $wpdb->get_var($sql);
    
    return $parent_id;
    
  }
  
  public function mlfp_remove_lookup_table($delete_id) {
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
    
    $where = array( 'id' => $delete_id );
    
    $wpdb->delete( $table, $where );    
    
  }
  
  public function get_wpmf_term_id($name, $parent) {
    
    global $wpdb;
    
    $sql = "select tt.term_id from {$wpdb->prefix}term_taxonomy as tt
left join {$wpdb->prefix}terms as t ON (t.term_id = tt.term_id) 
where tt.taxonomy = '" . WPMF_TAXO . "' and tt.parent = $parent and t.name = '$name'";

    //error_log($sql);

    $term_id = $wpdb->get_var($sql);
    
    return $term_id;
    
  }
    
  public function mlfp_wpmf_folder_count() {
    
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $sql = "SELECT count(*)
FROM {$wpdb->prefix}terms AS t 
INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON (t.term_id = tt.term_id) 
WHERE tt.taxonomy IN ('" . WPMF_TAXO . "') ";

    //error_log($sql);
    
    $wpmf_folder_count = $wpdb->get_var($sql);
    
    echo $wpmf_folder_count;
     
    die();    
  }
    
  public function mlfp_lookup_folder_id_from_term($parent_term_id) {
        
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        
    $sql = "select post_id from $table where term_id = $parent_term_id";
    
    //error_log($sql);
    
		$folder_id = $wpdb->get_var($sql);
    
    return $folder_id;
        
  }
  
  public function mlfp_import_next_wpmf_folder() {
    
    global $wpdb;
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['last_folder'])) && (strlen(trim($_POST['last_folder'])) > 0))
      $last_folder = intval(trim(stripslashes(strip_tags($_POST['last_folder']))));
    else
      $last_folder = 0;
    
    if ((isset($_POST['folder_count'])) && (strlen(trim($_POST['folder_count'])) > 0))
      $folder_count = intval(trim(stripslashes(strip_tags($_POST['folder_count']))));
    else
      $folder_count = 0;
    
		$percentage = (($last_folder+1) / $folder_count) * 100;
    
    $sql = "SELECT t.term_id, t.name, t.slug, tt.parent 
FROM {$wpdb->prefix}terms AS t 
INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON (t.term_id = tt.term_id) 
WHERE tt.taxonomy IN ('" . WPMF_TAXO . "') 
ORDER BY t.term_id ASC LIMIT $last_folder, 1";
        
    //error_log($sql);

    $row = $wpdb->get_row($sql);
    
    if($row) {
            
      if( $row->slug != 'wp-media-folder-root') {
                        
        $relative_path = $this->get_wpmf_parent($row->parent, $row->name);

        //$folder_exists = $this->mlfp_export->epim_folder_exist($relative_path);
        
        if(!$folder_exists) {
                                        
          if($row->parent == 0)            
            $parent_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
          else
            $parent_folder_id = $this->mlfp_lookup_folder_id_from_term($row->parent);
                                  
          $this->create_new_mlfp_folder($parent_folder_id, $row->name, false, $row->term_id);      

          $message = $relative_path;

        } else {
          $message = $relative_path . __(' already exists','maxgalleria-media-library');                    
        }
                      
      } else {
        $message = "";
      }
                  
      $last_folder++;

      $data = array('message' => $message, 'last_folder' => $last_folder, 'percentage' => $percentage );				
      
    } else {       
      $data = array('message' => __('Folders added.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );								      
    }	
            
	  echo json_encode($data);    

    die();
  }
  
  public function get_wpmf_parent($parent_id, $name) {
    
    if($parent_id == 0)
      return $name;
    
    $path_array = array($name);
    
    $row = $this->get_wpmf_folder_name($parent_id);
    array_unshift($path_array, $row->name);
    while($row->parent != 0) {
      $row = $this->get_wpmf_folder_name($row->parent);
      array_unshift($path_array, $row->name);      
    }
    
    $relative_path = implode('/', $path_array);
    return $relative_path;
    
  }
  
  public function get_wpmf_folder_name($term_id) {
    
    global $wpdb;
    
    $sql = "SELECT t.name, tt.parent
FROM {$wpdb->prefix}terms AS t 
INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON (t.term_id = tt.term_id) 
WHERE tt.taxonomy IN ('wpmf-category') 
and t.term_id = $term_id";

    $row = $wpdb->get_row($sql);
    
    return $row;
    
  }
    
  public function maxgalleria_get_file_url() {
    
    global $wpdb;
    
    $url = '';
    $ext = '';
    $mine_type = '';
        
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
      $file_id = intval(trim(stripslashes(strip_tags($_POST['image_id']))));
    else
      $file_id = 0;
    
    if($file_id != 0) {
      
      $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $file_id    
AND meta_key = '_wp_attached_file'";

      $row = $wpdb->get_row($sql);

      $baseurl = $this->upload_dir['baseurl'];
      $baseurl = rtrim($baseurl, '/') . '/';
      $url = $baseurl . ltrim($row->attached_file, '/');
      
      $filepath = $this->get_absolute_path($url);
      
      $basefile = wp_basename($filepath);
           
      $ext = $this->mlfp_get_extention($row->attached_file);
      
      $mine_type = get_post_mime_type($file_id);
                                
      $data = array ('url' => $url, 'app_type' => $ext, 'mine_type' => $mine_type, 'basefile' => $basefile);
      
      echo json_encode($data);
    } 
    
    die();       
  }
  
  public function mlfp_get_extention($file_name) {
    
    $position = strrpos($file_name, '.');
    if($position !== false){
      if($position > 0)
        $ext = substr($file_name, $position+1); 
      else if($position == 0)
        $ext = $file_name;     
    } else {
       $ext = "";      
    } 
    return $ext;
  }

  public function mlfp_mime_type_test() {
    
    $mime_message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['attachment_id'])) && (strlen(trim($_POST['attachment_id'])) > 0))
      $attachment_id = intval(trim(stripslashes(strip_tags($_POST['attachment_id']))));
    else
      $attachment_id = 0;
    
    if ((isset($_POST['playlist_type'])) && (strlen(trim($_POST['playlist_type'])) > 0))
      $playlist_type = trim(stripslashes(strip_tags($_POST['playlist_type'])));
    else
      $playlist_type = '';
    
    if($attachment_id != 0) {
      $mine_type = get_post_mime_type($attachment_id);
    }  
    
    //error_log("playlist_type $playlist_type, mine_type $mine_type");
    
    $position = strpos($mine_type, $playlist_type);
    //error_log("position $position");
    
    
    if($position === 0 ) {
      $data = array ('message' => "File added.", 'type_status' => true);
    } else {
      if($playlist_type == 'audio')
        $mime_message = __('The selected file is not an audio file','maxgalleria-media-library');
      else        
        $mime_message = __('The selected file is not a video file','maxgalleria-media-library');
      $data = array ('message' => $mime_message, 'type_status' => false);
    }  
    echo json_encode($data);
    
    die();
  }
  
  // https://stackoverflow.com/questions/35299457/getting-mime-type-from-file-name-in-php
  public function determine_mime_type() {
    
    $same_ext = true;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['filename'])) && (strlen(trim($_POST['filename'])) > 0))
      $filename = trim(stripslashes(strip_tags($_POST['filename'])));
    else
      $filename = '';        
    
    if ((isset($_POST['replace_type'])) && (strlen(trim($_POST['replace_type'])) > 0))
      $replace_type = trim(stripslashes(strip_tags($_POST['replace_type'])));
    else
      $replace_type = '';        
    
    if ((isset($_POST['replace_ext'])) && (strlen(trim($_POST['replace_ext'])) > 0))
      $replace_ext = trim(stripslashes(strip_tags($_POST['replace_ext'])));
    else
      $replace_ext = '';        
        
    $file_type = '';
    
    if($filename != '') {
      
      $index = explode( '.', $filename );
      $count_explode = count($index);
      $index = strtolower($index[$count_explode - 1]);

      $mimet = array( 
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
      );
            
      if (isset( $mimet[$index] )) {
        $file_type = $mimet[$index];
      } else {
        $file_type = 'application/octet-stream';
      }
            
    }
    
    $data = array ('file_type' => $file_type);
    echo json_encode($data);
    die();
}

public function clear_purge_table() {
  
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    // first, check for new folders
    $this->admin_check_for_new_folders(true);
        
    $sql = "TRUNCATE TABLE {$wpdb->prefix}mgmlp_file_purge";
    //error_log($sql);
    $wpdb->query($sql);
    
    //error_log("last result " . print_r($wpdb->last_result, true));
    //error_log("last error " .$wpdb->last_error);			
    //echo "ok";
    
    die();
     
}

 
public function run_file_detect_process() {
   
    global $wpdb;
		//$user_id = get_current_user_id();
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
		if ((isset($_POST['last_folder'])) && (strlen(trim($_POST['last_folder'])) > 0))
      $last_folder = intval(trim(stripslashes(strip_tags($_POST['last_folder']))));
    else
      $last_folder = "";
    
    if ((isset($_POST['folder_count'])) && (strlen(trim($_POST['folder_count'])) > 0))
      $folder_count = intval(trim(stripslashes(strip_tags($_POST['folder_count']))));
    else
      $folder_count = 0;
    
		$percentage = (($last_folder+1) / $folder_count) * 100;
        
    $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
        
    //$sql = "select * from $table ORDER by folder_id limit $last_folder, 1";
    
    $sql = "select ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file 
	from {$wpdb->prefix}posts 
	LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
	LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
	where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
	AND pm.meta_key = '_wp_attached_file' 
	order by folder_id limit $last_folder, 1";
  
    //error_log($sql);

    $row = $wpdb->get_row($sql);
    
    if($row) {
      
      //error_log($row->ID . " " . $row->attached_file);
      $message = "Scanning folder " . $row->attached_file;
      $this->check_folder_contents($row->ID);
         
      $last_folder++;
      //error_log("last_folder $last_folder");
      
      //if($last_folder == 10) {
      //  $data = array('message' => __('Folders scanned.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );
	    //  echo json_encode($data);    
      //  die();        
      //}

      $data = array('message' => $message, 'last_folder' => $last_folder, 'percentage' => $percentage );				
      
    } else {       
      $data = array('message' => __('Folders scanned.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );
    }	
            
	  echo json_encode($data);    

    die();
      
 }
 
  public function check_folder_contents($parent_folder) {
    
    global $wpdb, $is_IIS;
    $user_id = get_current_user_id();
    $files_to_add = array();
    $files_count = 0;
        
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
      
    $sql = "select ID, pm.meta_value as attached_file, post_title, $folder_table.folder_id 
from $wpdb->prefix" . "posts 
LEFT JOIN $folder_table ON($wpdb->prefix" . "posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' 
and folder_id = '$parent_folder' 
and pm.meta_key = '_wp_attached_file'	
order by post_title";

    //error_log($sql);
    
    $attachments = $wpdb->get_results($sql);
		      
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta
where post_id = $parent_folder    
and meta_key = '_wp_attached_file'";	

    //error_log($sql);

    $current_row = $wpdb->get_row($sql);
		
    //$image_location = $this->upload_dir['baseurl'] . '/' . $current_row->attached_file;
		$baseurl = $this->upload_dir['baseurl'];
		$baseurl = rtrim($baseurl, '/') . '/';
		$image_location = $baseurl . ltrim($current_row->attached_file, '/');
		
    $folder_path = $this->get_absolute_path($image_location);
    
    update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH_ID, $parent_folder);
        
    if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
      update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, str_replace('\\', '\\\\', $folder_path));
    else
      update_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, $folder_path);
    
    //error_log("folder_path $folder_path");
    
    if(file_exists($folder_path)) {
      if(is_dir($folder_path)) {  //folder
    
        $folder_contents = array_diff(scandir($folder_path), array('..', '.'));

        foreach ($folder_contents as $file_path) {

          //error_log($file_path);

          if($file_path !== '.DS_Store' && $file_path !== '.htaccess') {
            $new_attachment = $folder_path . DIRECTORY_SEPARATOR . $file_path;
            if(!strpos($new_attachment, '-uai-')) {  // skip thumbnails created by the Uncode theme
              if(!strpos($new_attachment, '-scaled')) {  // skip thumbnails scaled images
                if(!strpos($new_attachment, '-pdf.jpg')) {  // skip pdf thumbnails
                  if(!is_dir($new_attachment)) {
                    if($this->is_base_file($file_path, $folder_contents)) {				
                      //error_log('$file_path is base file');
                      if(!$this->search_folder_attachments($file_path, $attachments)) {                    
                        $this->add_file_to_purge($new_attachment, $parent_folder);                    
                      }	
                    } else {
                      $image_base_file = $this->get_ml_base_file($file_path);
                      if(!$this->search_folder_attachments($image_base_file, $attachments)) {                    
                        $this->add_file_to_purge($new_attachment, $parent_folder);
                      }                    
                    }
                  } 
                }
              }
            }
          }		
        }      		
      }
    } else { // folder does not exist
      //error_log("missing folder " . $folder_path);
      $this->add_file_to_purge($folder_path, $parent_folder, true);      
    }
  }
  
  public function add_file_to_purge($file_path, $folder_id, $folder = false) {
    
    global $wpdb, $is_IIS;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    
    $base_file = basename($file_path);
    //error_log("base_file $base_file");
    
    if($folder) {
      if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )      
        $position = strrpos($file_path, '\\');
      else
        $position = strrpos($file_path, '/');
      $folder_path = substr($file_path, 0, $position+1);      
    } else {
      $position = strpos($file_path, $base_file);
      $folder_path = substr($file_path, 0, $position);
    }
    
    //error_log("add_file_to_purge folder $folder_id $base_file $folder_path");
    
//    if(is_multisite() && $folder) {
//      $data = array(
//        'folder_id' => $folder_id,
//        'basefile' => $base_file,
//        'folder_path' => $file_path
//      );      
//    } else {
//      $data = array(
//        'folder_id' => $folder_id,
//        'basefile' => $base_file,
//        'folder_path' => $folder_path
//      );            
//    }
    
    $data = array(
      'folder_id' => $folder_id,
      'basefile' => $base_file,
      'folder_path' => $folder_path
    );            
            
    $wpdb->insert($table, $data);
        
  }
 
  public function refresh_purge_table() {
   
    global $wpdb;
    $output = "";
    $images_found = false;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
    if ((isset($_POST['current_page'])) && (strlen(trim($_POST['current_page'])) > 0))
      $page_id = intval(trim(stripslashes(strip_tags($_POST['current_page']))));
    else
      $page_id = 0;
        
    $offset = $page_id * MLFP_MAX_PURGE_FILES;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
        
    $sql = "select SQL_CALC_FOUND_ROWS * from $table order by folder_path limit $offset, " . MLFP_MAX_PURGE_FILES;
    
    //error_log($sql); 
    
    $rows = $wpdb->get_results($sql);            

    $count = $wpdb->get_row("select FOUND_ROWS()", ARRAY_A);
    $total_images = $count['FOUND_ROWS()'];
    $total_number_pages = ceil($total_images / MLFP_MAX_PURGE_FILES);
    //error_log("page_id $page_id, total_images $total_images, total_number_pages $total_number_pages");
    
    if($rows) {
      $images_found = true;
    
    $last_page = $total_number_pages-1;
    $previous_page = $page_id - 1;
    $next_page = $page_id + 1;
    $output .= "<div>" . __( 'Page', 'maxgalleria-media-library' ) ." $next_page " . __( 'of', 'maxgalleria-media-library' ) ." $total_number_pages</div>" . PHP_EOL;
    $output .= "<div class='mlfp-page-nav'>" . PHP_EOL;
    if($page_id > 0) {
      $output .=  "<a id='mlfp-first' page-id='0' style='float:left;cursor:pointer'>" . __( 'First Page', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
      $output .=  "<a id='mlfp-previous' page-id='$previous_page' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
    }  
    if($page_id < $total_number_pages-1 && $total_images > MLFP_MAX_PURGE_FILES) { //MLFP_MAX_PURGE_FILES) 
      $output .=  "<a id='mlfp-last' page-id='$last_page' style='float:right;cursor:pointer'>" . __( 'Last', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
      $output .=  "<a id='mlfp-next' page-id='$next_page' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
    } 
    $output .=  "</div>" . PHP_EOL;
      
      
      $output .= '<table id="purge-table">' . PHP_EOL;
      $output .= '  <thead>' . PHP_EOL;
      $output .= '    <tr>' . PHP_EOL;
      $output .= '      <td>' . __( 'Image', 'maxgalleria-media-library' ) . '</td>' . PHP_EOL;
      $output .= '      <td>' . __( 'File Name', 'maxgalleria-media-library' ) . '</td>' . PHP_EOL;
      $output .= '      <td>' . __( 'Folder', 'maxgalleria-media-library' ) . '</td>' . PHP_EOL;
      $output .= '      <td><input type="radio" id="mlfp-leave-all" class="maint-ckb" name="mlfpm-all"><label for="mlfp-leave-all">'.__('Leave', 'maxgalleria-media-library').'</label> <input type="radio" id="mlfp-delete-all" class="maint-ckb" name="mlfpm-all"><label for="mlfp-delete-all">'.__('Delete', 'maxgalleria-media-library').'</label> <input type="radio" id="mlfp-import-all" class="maint-ckb" name="mlfpm-all"><label for="mlfp-import-all">'.__( 'Import', 'maxgalleria-media-library' ).'</label></td>' . PHP_EOL;
      $output .= '    </tr>' . PHP_EOL;
      $output .= '  </thead>' . PHP_EOL;
      
      $output .= '  <tbody>' . PHP_EOL;
      
      foreach($rows as $row) {
        
        $is_folder = false;
        
        switch($row->action) {
          case 1:
            $leave = '';
            $delete = 'checked="checked"';
            $import = '';
            break;
          
          case 2:
            $leave = '';
            $delete = '';
            $import = 'checked="checked"';
            break;
          
          case 5: // file type not allowed
            break;          
          
          default:
          case 0:
            $leave = 'checked="checked"';
            $delete = '';
            $import = '';
            break;
                    
        }
        
        // format the URL
        $file_path = $row->folder_path . "/" . $row->basefile;
        $file_url = $this->get_file_url($file_path);
        

        //$ext = pathinfo($file_url, PATHINFO_EXTENSION);										
        
        if(file_exists($file_path)) {
          if(!is_dir($file_path) && $this->check_image_extention($file_url)) {
            $display_url = $file_url;
          } else {
            if(strrpos($file_path, '.') === false ) {
              $is_folder = true;
              $display_url = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/folder.jpg";
            } else {
              $ext = pathinfo($file_url, PATHINFO_EXTENSION);
              $display_url = $this->get_file_thumbnail($ext);
            }  
          }  
        } else {
          $ext = pathinfo($file_url, PATHINFO_EXTENSION);
          $display_url = $this->get_file_thumbnail($ext);          
        }
        //error_log("display_url $display_url");
        
        // get the folder path
        $position = strpos($row->folder_path, $this->uploads_folder_name);        
        $file_folder = rtrim(substr($row->folder_path, $position),'/');
                
        $output .= '    <tr>' . PHP_EOL;
        $output .= '      <td><img class="purge-file-image" src="' . $display_url . '" alt="file '.$row->basefile.'"></td>' . PHP_EOL;
        if($row->action == 5) {
          $output .= '      <td>' . $row->basefile . '<span class="mlp-warning"> ' . __('File type not allowed', 'maxgalleria-media-library' ) . '</td>' . PHP_EOL;
        } else {          
          $output .= '      <td>' . $row->basefile . '</td>' . PHP_EOL; 
        }
        $output .= '      <td>' . $file_folder . '</td>' . PHP_EOL; 
        $output .= '      <td>' . PHP_EOL;
        
          $output .= '        <input type="radio" class="leave-radio"  data-id="'.$row->rec_id.'"  name="purge-action-'.$row->rec_id.'" value="leave" '.$leave.'><label for="leave-'.$row->rec_id.'">' . __( 'Leave', 'maxgalleria-media-library' ) . '</label>&nbsp;' . PHP_EOL;

          if($is_folder)
            $output .= '        <input type="radio" class="delete-radio" data-id="'.$row->rec_id.'" data-dir="1" name="purge-action-'.$row->rec_id.'" value="delete" '.$delete.'><label for="delete-'.$row->rec_id.'">' . __( 'Delete', 'maxgalleria-media-library' ) . '</label>&nbsp;' . PHP_EOL;
          else
            $output .= '        <input type="radio" class="delete-radio" data-id="'.$row->rec_id.'" data-dir="0" name="purge-action-'.$row->rec_id.'" value="delete" '.$delete.'><label for="delete-'.$row->rec_id.'">' . __( 'Delete', 'maxgalleria-media-library' ) . '</label>&nbsp;' . PHP_EOL;

          if($is_folder)
            $output .= '        <input type="radio" class="import-radio" data-id="'.$row->rec_id.'" data-dir="1" name="purge-action-'.$row->rec_id.'" value="create" '.$import.'><label for="import-'.$row->rec_id.'">' . __( 'Create', 'maxgalleria-media-library' ) . '</label>' . PHP_EOL;
          else {
            if($row->action != 5) {
              $output .= '        <input type="radio" class="import-radio" data-id="'.$row->rec_id.'" data-dir="0" name="purge-action-'.$row->rec_id.'" value="import" '.$import.'><label for="import-'.$row->rec_id.'">' . __( 'Import', 'maxgalleria-media-library' ) . '</label>' . PHP_EOL;
            }
          }  
        
        $output .= '      <td>' . PHP_EOL;
        $output .= '    </tr>' . PHP_EOL;
      }
      
      $output .= '  </tbody>' . PHP_EOL;
      
      $output .= '</table>' . PHP_EOL;
    }
    
    //if(!$images_found)
    //   $output .= "<p style='text-align:center'>" . __('No uncataloged files were found.','maxgalleria-media-library')  . "</p>";
    //else {
    if($images_found) {
      $output .= "<div class='mlfp-page-nav'>" . PHP_EOL;
    if($page_id > 0) {
      $output .=  "<a id='mlfp-first' page-id='0' style='float:left;cursor:pointer'>" . __( 'First Page', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
      $output .=  "<a id='mlfp-previous' page-id='$previous_page' style='float:left;cursor:pointer'>< " . __( 'Previous', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
    }  
    if($page_id < $total_number_pages-1 && $total_images > MLFP_MAX_PURGE_FILES) { //MLFP_MAX_PURGE_FILES) 
      $output .=  "<a id='mlfp-last' page-id='$last_page' style='float:right;cursor:pointer'>" . __( 'Last', 'maxgalleria-media-library' ) ."</a>" . PHP_EOL;
      $output .=  "<a id='mlfp-next' page-id='$next_page' style='float:right;cursor:pointer'>" . __( 'Next', 'maxgalleria-media-library' ) ." ></a>" . PHP_EOL;
    } 
      $output .=  "</div>" . PHP_EOL;
    }
      $output .=  "<p class='center-text'><a href='#purge-top'>".__( 'Go to page top', 'maxgalleria-media-library' )."</a></p>" . PHP_EOL;
    
    $data = array('output' => $output, 'total' => $total_images); 
            
	  echo json_encode($data);    

    die();
    
  }
  
  public function check_image_extention($file_url) {
    
    $is_image = false;
    
    $position = strrpos($file_url, '.');
    if($position !== false) {
      $ext = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));      
      if(in_array($ext, ['jpg', 'jpeg', 'ico', 'gif', 'png', 'svg'])) {
        $is_image = true;
        //error_log("type $ext true");
      } else {
        //error_log("type $ext false");        
      }  
    }
    //error_log("check type $ext");
        
    return $is_image;
    
  }
  
  public function mlfp_update_purge_action() {
    
    global $wpdb;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
        
    if ((isset($_POST['rec_id'])) && (strlen(trim($_POST['rec_id'])) > 0))
      $rec_id = intval(trim(stripslashes(strip_tags($_POST['rec_id']))));
    else
      $rec_id = 0;
    
    if ((isset($_POST['action_type'])) && (strlen(trim($_POST['action_type'])) > 0))
      $action_type = intval(trim(stripslashes(strip_tags($_POST['action_type']))));
    else
      $action_type = -1;
    
    if($action_type != -1) {
    
      $data = array('action' => $action_type);
      
      $where = array('rec_id' => $rec_id);
      
      $wpdb->update($table, $data, $where);
    
    }
      
    die();
  }
  
  public function update_purge_action($rec_id, $action){
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    
    $data = array('action' => $action);

    $where = array('rec_id' => $rec_id);

    $wpdb->update($table, $data, $where);
    
  }
  
  public function mlfp_process_purge_file() {
    
    global $wpdb, $is_IIS;
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
          
		if ((isset($_POST['last_record'])) && (strlen(trim($_POST['last_record'])) > 0))
      $last_record = intval(trim(stripslashes(strip_tags($_POST['last_record']))));
    else
      $last_record = "";
    
    if ((isset($_POST['record_count'])) && (strlen(trim($_POST['record_count'])) > 0))
      $record_count = intval(trim(stripslashes(strip_tags($_POST['record_count']))));
    else
      $record_count = 0;
    
    
    if ((isset($_POST['remove_file_size'])) && (strlen(trim($_POST['remove_file_size'])) > 0))
      $remove_file_size = trim(stripslashes(strip_tags($_POST['remove_file_size'])));
    else
      $remove_file_size = 'true';
    
    if($remove_file_size == 'true')
      $remove_file_size = true;
    else
      $remove_file_size = false;
          
    $mlp_title_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT);
    $mlp_alt_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);    

    if($record_count == 0)
      $percentage = 0;
    else
		  $percentage = (($last_record+1) / $record_count) * 100;
    
    $sql = "select * from $table where action != 0 order by rec_id limit $last_record, 1";    
    
    //error_log($sql);
    
    $row = $wpdb->get_row($sql);
    
    if($row) {
      
      $file_path = $row->folder_path . $row->basefile;
      //error_log("file_path $file_path");
      //error_log("$row->basefile " . $row->action);
            
      switch($row->action) {
        
        case 4: // delete folder
          //error_log("deleting folder $row->basefile, " . $row->folder_id);
          $mlfp_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
          
          if($this->wpmf_integration == 'on') {
            $term_id = $this->mlfp_get_term_id($row->folder_id);
            //error_log("term_id $term_id");
            wp_delete_term($term_id, WPMF_TAXO);
          }

          wp_delete_post($row->folder_id, true);
          $del_post = array('post_id' => $row->folder_id);                                  
          $wpdb->delete( $mlfp_table, $del_post );

          $message = __('Folder ','maxgalleria-media-library') . $row->basefile . __(' was deleted','maxgalleria-media-library');
                    
          break;
        
        
        case 3: // create folder
          
          if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
            $file_path = str_replace('/', '\\', $file_path);
          
          //error_log("adding folder $file_path");
          
          if(!file_exists($file_path)) {
            if(mkdir($file_path))
              $message = __('Folder ','maxgalleria-media-library') . $row->basefile . __(' was added','maxgalleria-media-library');
            else
              $message = __('Folder ','maxgalleria-media-library') . $row->basefile . __(' could not be added','maxgalleria-media-library');
          }  
                    
          break;
                
        case 2:
          
          $wp_filetype = wp_check_filetype_and_ext($file_path, $row->basefile );

          if ($wp_filetype['ext'] !== false) {      
            
            if($remove_file_size) {
              
              $file_to_add = $this->remove_thumbnail_size($row->basefile);

              //error_log("file_to_add 1 $file_to_add");

              $new_file_path = $row->folder_path . $file_to_add;
              //error_log("new_file_path $new_file_path");

              if($file_to_add != $row->basefile) {
                if(file_exists($file_path)) {
                  //error_log("renaming " . $row->basefile);
                  rename($file_path, $new_file_path );                
                } else {
                  $file_to_add = $row->basefile;
                }
              }  
            } else {
              $file_to_add = $row->basefile;              
            }
            
            //error_log("file_to_add 2 $file_to_add");
            
            $message = $row->basefile . __(' added','maxgalleria-media-library');
            $this->mlfp_process_sync_file($file_to_add, $mlp_title_text, $mlp_alt_text, $row->folder_id, true);
          } else {
            //error_log("rec id " . $row->rec_id);
            $this->update_purge_action($row->rec_id, 5);
            $message = $row->basefile . __(" is not an allowed file type. It was not added.",'maxgalleria-media-library');            
          }
          
          break;
                
        case 1:
          
          if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
            $file_path = str_replace('/', '\\', $file_path);
                    
          if(file_exists($file_path))
            unlink($file_path);          
          $message = $row->basefile . __(' deleted','maxgalleria-media-library');
          break;
        
        case 0:
        default:
          $message = $row->basefile . __(' skipped.','maxgalleria-media-library');
          break;
      }
      //error_log($message);
      
      
      $last_record++;
      
      //if($last_record > 10) {
      //  $data = array('message' => __('Processing complete.','maxgalleria-media-library'), 'last_record' => null, 'percentage' => 100 );        
      //}
      //else
      $data = array('message' => $message, 'last_record' => $last_record, 'percentage' => $percentage );				
      
    } else {       
      $this->remove_purge_records();
      $data = array('message' => __('Processing complete.','maxgalleria-media-library'), 'last_record' => null, 'percentage' => 100 );
    }	
    
	  echo json_encode($data);    
    
    die();
    
  }
  
  public function mlfp_update_purge_count() {
    global $wpdb;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce!','maxgalleria-media-library'));
    }
    
    $sql = "select count(*) from {$wpdb->prefix}mgmlp_file_purge where action != 0";
    
    $count = $wpdb->get_var($sql);
    //error_log("purge count $count");
    if($count)
      echo $count;
    else
      echo '0';
    
    die();
  }
  
  public function remove_purge_records() {
    
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_PURGE_TABLE;
        
    $sql = "delete from $table where action = 1 or action = 2 or action = 3 or action = 4";
    
    $wpdb->query($sql);
    
  }
  
  public function remove_thumbnail_size($basefile) {
    
    $dash_position = strrpos($basefile, '-' );
		$x_position = strrpos($basefile, 'x', $dash_position);
		$dot_position = strrpos($basefile, '.' );
		
		if(($dash_position) && ($x_position)) {
			$new_file = substr($basefile, 0, $dash_position) . substr($basefile, $dot_position );
      return $new_file;
    }  else {
      return $basefile;
    }
    
  }
  
  public function mlfp_get_next_ml_file() {
    
    global $wpdb, $is_IIS;
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    }
          
		if ((isset($_POST['last_file'])) && (strlen(trim($_POST['last_file'])) > 0))
      $last_file = intval(trim(stripslashes(strip_tags($_POST['last_file']))));
    else
      $last_file = "";
    
    if ((isset($_POST['file_count'])) && (strlen(trim($_POST['file_count'])) > 0))
      $file_count = intval(trim(stripslashes(strip_tags($_POST['file_count']))));
    else
      $file_count = 0;
    
    if ((isset($_POST['current_folder_id'])) && (strlen(trim($_POST['current_folder_id'])) > 0))
      $current_folder_id = trim(stripslashes(strip_tags($_POST['current_folder_id'])));
    else
      $current_folder_id = 0;    
    
    if($file_count == 0)
      $percentage = 0;
    else
		  $percentage = (($last_file+1) / $file_count) * 100;
    
    $folder_table = $wpdb->prefix . "mgmlp_folders";
    
		$upload_path = $this->upload_dir['basedir'];    

    $sql = "select ID as attach_id, $folder_table.folder_id, pm.meta_value as attached_file, pm.meta_id
from {$wpdb->prefix}posts 
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = 'attachment' 
and folder_id = '$current_folder_id'
AND pm.meta_key = '_wp_attached_file' 
order by ID limit $last_file, 1";   

    //error_log($sql);
    
    $row = $wpdb->get_row($sql);
    
    if($row) {
            
      if(strpos( $row->attached_file, '-scaled') !== false) {
        
        remove_filter( 'wp_generate_attachment_metadata', array($this, 'add_attachment_to_folder2'));            
        
        $message = __('Updating ','maxgalleria-media-library') . $row->attached_file . " - " . number_format($percentage)  . __('% complete','maxgalleria-media-library');
        
        $non_scaled = str_replace('-scaled', '', $row->attached_file);
        
        $non_scaled_path = $upload_path . DIRECTORY_SEPARATOR . $non_scaled;
        
        $scaled_file_path = $upload_path . DIRECTORY_SEPARATOR . $row->attached_file;
                
        if(file_exists($non_scaled_path)) {
          //error_log("meta id " . $row->meta_id);
          if($this->update_attached_image($row->meta_id, $non_scaled) !== false) {
            //error_log("updated post meta");
            
            if(file_exists($scaled_file_path)) {
              unlink($scaled_file_path);
            }
            
            // delete old thumbnails files
            $metadata = wp_get_attachment_metadata($row->attach_id);                               
            
            if(isset($metadata['sizes'])) {
              foreach($metadata['sizes'] as $source_path) {
                $thumbnail_file = $upload_path . DIRECTORY_SEPARATOR . $source_path['file'];

                if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
                  $thumbnail_file = str_replace('/', '\\', $thumbnail_file);

                if(file_exists($thumbnail_file))
                  unlink($thumbnail_file);
              }  
            }
            
            // generate new thumbnails
            if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' )
              $attach_data = wp_generate_attachment_metadata( $row->attach_id, addslashes($non_scaled_path));
            else
              $attach_data = wp_generate_attachment_metadata( $row->attach_id, $non_scaled_path );

            wp_update_attachment_metadata( $row->attach_id, $attach_data );
                        
          }
          
        }
        
        add_filter( 'wp_generate_attachment_metadata', array($this, 'add_attachment_to_folder2'), 10, 4);            
        
      } else {
        $message = __('Checking ','maxgalleria-media-library') . $row->attached_file . " - " . number_format($percentage)  . __('% complete','maxgalleria-media-library');        
      }
      
         
      $last_file++;

      $data = array('message' => $message, 'last_file' => $last_file, 'percentage' => $percentage );				
      
    } else {       
      $data = array('message' => __('Done scanning files.','maxgalleria-media-library'), 'last_file' => null, 'percentage' => 100 );
    }	
            
	  echo json_encode($data);    
    
    die();
  }
  
  public function update_attached_image($meta_id, $file) {
    global $wpdb;
    
    if(!empty($file)) {
    
      $table = $wpdb->prefix . "postmeta";
      $data = array('meta_value' => $file);
      $where = array(
        'meta_id' => $meta_id
      );

      return $wpdb->update($table, $data, $where);
    } else 
      return false;
  }
  
  public function mlfp_save_search_type() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    }
          
		if ((isset($_POST['search_type'])) && (strlen(trim($_POST['search_type'])) > 0))
      $search_type = trim(stripslashes(strip_tags($_POST['search_type'])));
    else
      $search_type = "";
    
    if($search_type != '') {      
      update_option(MAXGALLERIA_MEDIA_LIBRARY_SEARCH_MODE, $search_type);          
    }
    
    die();
  }
  
  public function display_secondary_toolbar($items, $page, $last, $image_link, $items_per_page, $headings_visible = 'on', $categories = false, $pagintation = true ) {
    
    $text_result = ($pagintation) ? 'true' : 'false';
    //error_log("display_secondary_toolbar pagintation $text_result");
    
    $buffer = '';
    
    $buffer .= '<div class="mg-folders-secondary-tool-bar">' . PHP_EOL;
      $buffer .= '  <div id="mg-stb-left">' . PHP_EOL;
    if($this->license_valid) {
      $buffer .= '    <select id="mlf-bulk-select" class="gray-blue-link">' . PHP_EOL;
      $buffer .= '      <option>' . __('Bulk Actions', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      //$buffer .= '      <option>' . __('Copy', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      //$buffer .= '      <option>' . __('Regenerate Thumbnails', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      $buffer .= '      <option value="mlf-bulk-move">' . __('Bulk Move', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      $buffer .= '      <option value="mlfp-playlist">' . __('Playlist Shortcode', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      $buffer .= '      <option value="mlfp-jp-gallery">' . __('Jetpack Gallery', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      $buffer .= '      <option value="mlfp-embed">' . __('Embed PDF/Audio/Video', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      $buffer .= '      <option value="mlfp-add-to-ng">' . __('Add Images to NextGen Galleries', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;
      if(class_exists('MGMediaLibraryFoldersProS3') && 
        ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING)) {
          $buffer .= '      <option value="mlfp-s3-upload">' . __('Upload selected files to S3', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;      
          $buffer .= '      <option value="mlfp-s3-download">' . __('Downloads selected files from S3', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;      
      }
      if($this->rollback_scaling == 'on') {
        $buffer .= '      <option value="mlfp-rollback-scaled">' . __('Roll Back Scaled Images', 'maxgalleria-media-library' ) . '</option>' . PHP_EOL;            
      }  
      $buffer .= '    </select>' . PHP_EOL;
      $buffer .= '    <a id="mlp-bulk-apply" class="gray-blue-link">' . __('Apply', 'maxgalleria-media-library' ) . '</a>' . PHP_EOL;    
      $buffer .= '    <div id="select-all-container">' . PHP_EOL;
      $buffer .= '      <input type="checkbox" id="mlf-select-all">' . PHP_EOL;
      $buffer .= '      <label>Select All Files</label>' . PHP_EOL;
      $buffer .= '    </div>' . PHP_EOL;
      $buffer .= '  </div>' . PHP_EOL;
    //}  
    
    $buffer .= '  <div id="mg-stb-right">' . PHP_EOL;
    if($pagintation)
      $buffer .=  $this->mflp_pagination($items, $page, $last, $image_link, $items_per_page, false, $categories);
    $buffer .= '  </div>' . PHP_EOL;
    }
    $buffer .= '</div>' . PHP_EOL;
    $buffer .= '<div style="clear:both"></div>' . PHP_EOL;
    $buffer .= '<div id="alwrap">' . PHP_EOL;
    $buffer .= '  <div id="ajaxloader" style="display:none"></div>' . PHP_EOL;
    $buffer .= '</div>' . PHP_EOL;
    $buffer .= '<div style="clear:both"></div>' . PHP_EOL;
    $buffer .= '<div id="folder-message" class="folder-message-backgound"></div>' . PHP_EOL;
    $buffer .= $this->display_list_container($headings_visible);    
    
    return $buffer;
 
  }
  
  public function mflp_pagination($items, $page_id, $last, $image_link, $items_per_page, $top_link = false, $categories = false)  {
    
    $previous_page_class = '';
    $next_page_class = '';
    
    
    $previous_page = $page_id - 1;
    $next_page = $page_id + 1;
    
    if($previous_page < 0)
        $previous_page_class = 'disabled';
    
    
    //if($page_id <= $last-1 && $items > $items_per_page)
    if($page_id == $last-1) {
        $next_page_class = 'disabled';
    }    
    
    if($last == 0)
      $last = 1;
          
    $buffer = '';
    
    $buffer .= '    <ul class="mlf-pagination">' . PHP_EOL;
    if($top_link)
      $buffer .= '      <li class="top"><a href="#mlfp-top" id="mflp-top-of-files" title="' . __('Back to top of file contents', 'maxgalleria-media-library' ) . '"><i class="fas fa-arrow-up fa-2x"></i></a></li>' . PHP_EOL;
    $buffer .= '      <li class="items"><div class="mlp-folder-count-row"><span id="mlp-folder-count">'.$items.'</span> ' . __('items', 'maxgalleria-media-library' ) . '</div></li>' . PHP_EOL;
    if($categories)
      $buffer .= '      <li class="links"><a class="mlfp-first-cats gray-blue-link '.$previous_page_class.'" title="' . __('First page', 'maxgalleria-media-library' ) . '" data-page-id="1">«</a></li>' . PHP_EOL;
    else
      $buffer .= '      <li class="links"><a class="mlf-first-page gray-blue-link '.$previous_page_class.'" title="' . __('First page', 'maxgalleria-media-library' ) . '" data-page-id="1">«</a></li>' . PHP_EOL;
    if($categories)
      $buffer .= '      <li class="links"><a class="mlfp-previous-cats gray-blue-link '.$previous_page_class.'" title="' . __('Previous page', 'maxgalleria-media-library' ) . '" data-page-id="'.$previous_page.'" data-type="prev">&lt;</a></li>' . PHP_EOL;
    else
      $buffer .= '      <li class="links"><a class="mlf-previous-page gray-blue-link '.$previous_page_class.'" title="' . __('Previous page', 'maxgalleria-media-library' ) . '" data-page-id="'.$previous_page.'" data-type="prev">&lt;</a></li>' . PHP_EOL;
    if($categories)
      $buffer .= '      <li><input type="text" class="mlf-cat-page" value="'.($page_id + 1).'" data-last="'.$last.'"></li>' . PHP_EOL;
    else
      $buffer .= '      <li><input type="text" class="mlf-page" value="'.($page_id + 1).'"></li>' . PHP_EOL;
    $buffer .= '      <li class="total"><div class="mlf-total-pages">' . __('of ', 'maxgalleria-media-library' ) . ' '.$last.'</div></li>' . PHP_EOL;
    if($categories)
      $buffer .= '      <li class="links"><a class="mlfp-next-cats gray-blue-link '.$next_page_class.'" title="' . __('Next page', 'maxgalleria-media-library' ) . '" data-page-id="'.$next_page.'" data-type="next">&gt;</a></li>' . PHP_EOL;
    else
      $buffer .= '      <li class="links"><a class="mlf-next-page gray-blue-link '.$next_page_class.'" title="' . __('Next page', 'maxgalleria-media-library' ) . '" data-page-id="'.$next_page.'" data-type="next">&gt;</a></li>' . PHP_EOL;
    if($categories)
      $buffer .= '      <li class="links"><a class="mlfp-last-cats gray-blue-link '.$next_page_class.'" title="' . __('Last page', 'maxgalleria-media-library' ) . '" data-last="'.$last.'" >»</a></li>' . PHP_EOL;
    else
      $buffer .= '      <li class="links"><a class="mlf-last-page gray-blue-link '.$next_page_class.'" title="' . __('Last page', 'maxgalleria-media-library' ) . '" data-last="'.$last.'" >»</a></li>' . PHP_EOL;
    $buffer .= '    </ul>' . PHP_EOL;
    
    return $buffer;
  }
  
  public function bottom_pagination($items, $page, $last, $image_link, $items_per_page, $top_link, $categories) {
    
    $buffer = '';
    $buffer .= '<div style="clear:both"></div>' . PHP_EOL;
    $buffer .= '<div id="mlfp-bottom">' . PHP_EOL;
    $buffer .= $this->mflp_pagination($items, $page, $last, $image_link, $items_per_page, $top_link, $categories);
    $buffer .= '</div>' . PHP_EOL;
    
    return $buffer;
  }
  
  public function license_help() {
    ?>
      <p><strong><?php _e("License Key Help","maxgalleria-media-library")?></strong></p>
      <p><?php _e("The license key must be activated to access all the pro features of Media Library Folders Pro.","maxgalleria-media-library")?></p>
      <p><?php _e("To activate, paste in the license key you received after purchasing the plugin. Click the Save Changes button to save the license key and then click the Activate License button to activate the license.","maxgalleria-media-library")?></p>
      <p><?php _e("If the license key expires, Media Library Folders Pro will display a notice that the license needs to be renewed. After renewing the license, it is necessary to visit the Media Library Folders Pro Settings Page, License tab, which will cause the plugin to update its license information.","maxgalleria-media-library")?></p>
    <?php    
  }
  
  public function library_help() {
    ?>
      <div id="library-help">

        <p><strong><?php _e("Library Help","maxgalleria-media-library")?></strong></p>

        <p><?php _e("Media Library Folders provides additional functionality to the standard Wordpress media library. The media library is basically a database of files that have been imported into Wordpress. It is not a live picture of files on your server. Before files can be viewed in the media library they have to be process and added to the database. New folders created manually on the server also have to be added to the database before that can be used by Media Library Folders.","maxgalleria-media-library") ?></p>

        <p><strong><?php _e("Basic Operations","maxgalleria-media-library") ?></strong></p>

        <p><i class="fa-solid fa-folder-plus mlf-help-icon"></i> <?php _e("Add Folder, Creates a new folder. Folder names cannot contain spaces.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-upload mlf-help-icon"></i> <?php _e("Upload Files, Opens the file upload area. Here you can upload individual files using the browse button to find and select a file on your computer. Multiple files can be uploaded using drag and drop; open your file manger, select highlight multiple files, drag them to the Drag and Drop box on this page and release them. The files will be uploaded to the currently selected folder. To close the file upload area, click the Upload Files icon.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-arrows-rotate mlf-help-icon"></i> <?php _e("Refresh Folders, Media Library Folders periodically checks for new folders. Clicking this icon will immediate check and add new folders to the folder tree.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-file-import mlf-help-icon"></i> <?php _e("Move Files, By default, files can be moved from one folder to another by dragging a file to a folder. Note that the mouse pointer is slightly offset so be sure to place the mouse, not the image, over the destination folder before releasing. Media files that are embedded in standard posts and pages will be updated when a file is moved. However this some themes and page builders may store embedded links in a non standard format. Media Library Folders has attentional code for handling such non standard formats but it is a good practices on a new install of Media Library Folders to test the updating of embedded links by move a file and checking the page where the files is embedded to see that it was correctly update to the new link.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-clone mlf-help-icon"></i> <?php _e("Copy Files, It possible to switch the drag and drop operation from moving files to copying files but clicking on the copy icon. To switch back to moving files, click the move icon.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-calendar-days mlf-help-icon"></i> <?php _e("Order by Date, Files can be sorted by the date they were added to Wordpress or by their title. Clicking on the Calendar icon will order the files by date.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-file-image mlf-help-icon"></i> <?php _e("Sort by Title, Order files by their title. The title of a file can be changed by clicking on an image which will open the file's edit media page in a new tab.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-arrows-up-down mlf-help-icon"></i> <?php _e("Reverse Order, Reverses the order of files displayed.","maxgalleria-media-library") ?></p>
        <p><i class="fas fa-bolt mlf-help-icon"></i> <?php _e("Sync, The sync function scans the current folder and adds any new files or folders it finds. Files uploaded to a folder by FTP can be added to the media library using this feature.","maxgalleria-media-library") ?></p>        
        <p><i class="fa-solid fa-pen mlf-help-icon"></i> <?php _e("Rename a File, To rename a file, check the check box of the file to be renamed and click the Rename icon. This action will display the Rename The Selected File box where you can enter a new name for the file. After entering the new file name, click the Rename File button. Note the the file's extension cannot be changes and if multiple files are selected, only the name of the first file selected can be changed. Folders cannot be renamed as that would break embedded links in posts and page; instead renaming a folder, create a new folder with the desired name and move the files to the new folder which will update any embedded links to the new location.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-images mlf-help-icon"></i> <?php _e("Add Images to MaxGalleria Gallery, The icon for adding images to a MaxGalleria gallery is only visible when MaxGalleria or MaxGalleria Pro is installed and activated. This feature allows one to select one or more images in a folder and add them to an existing MaxGalleria gallery. To add images, select the images to add by checking their checkboxes Then click the image gallery icon. In the Add images to MaxGalleria Gallery box the appears, select the name of the gallery where to add the images and click the Add Images button. This function only works for images and not videos.","maxgalleria-media-library") ?></p>
        <p><i class="far fa-object-group mlf-help-icon"></i> <?php _e("Regenerate Thumbnails, To regenerate thumbnails for one or more files, check the check boxes of the images to regenerate and then click the regenerate thumbnails icon.","maxgalleria-media-library") ?></p>
        <p><i class="fas fa-trash mlf-help-icon"></i> <?php _e("Delete Files, To delete one or more files, check the check boxes of the files to delete and then click the delete files icon.","maxgalleria-media-library") ?></p>      
        <p><i class="fa-regular fa-image mlf-help-icon"></i> <?php _e("Create a NextGen Gallery, This option is available for sites where the NextGen plugin is installed and allows one to create a new NextGen gallery. Once a new gallery has been created, media library images can be added using the Add Images to NextGen Gallery feature.","maxgalleria-media-library") ?></p>
        <p><i class="fa-solid fa-file-arrow-up mlf-help-icon"></i> <?php _e("Replace a file, An existing file can be replaced using this feature.","maxgalleria-media-library") ?></p>
        <p><?php _e("Search/Find, To find a particular file or folder by typing in a file or folder name and pressing Enter, or by clicking the Find button. The search results page will display files that match the search text and clicking on an item on the search results page will open the related folder and display its contents.","maxgalleria-media-library") ?></p>
        <p><?php _e("Edit a file, To edit a media file's data, such as it title, alt text, caption or description, click on an image and the file's edit media page will open in a new browser tab. ","maxgalleria-media-library") ?></p>
        <p><strong><?php _e("Folder Operations","maxgalleria-media-library") ?></strong></p>                        
        <p><?php _e("Hide a Folder,  To hide a folder, that is to remove it from the folder tree and the database but not from the server, right click on a folder you want to hide (CTRL-click on a Mac). Choose the option “Hide this folder?”. Clicking on this option will remove the folder, its sub folders and files from the Media Library but not from your server. This action will also write a file to the folder with the name “mlpp-hidden”. As long as that file is present, Media Library Plus will skip over this folder when checking for folders and files to add to the Media Library.","maxgalleria-media-library") ?></p>
        <p><?php _e("Delete a Folder, Right click on a folder you want to delete (CTRL-click on a Mac). This action will display a popup menu. Click “Delete this Folder?” and if the folder is empty, then it will be removed. If you get a message that the folder is not empty, view that folder and click the Sync button to add any files on the server that are not in the media library. Then you can remove the files and delete the folder as explained above.","maxgalleria-media-library") ?></p> 
        <p><?php _e("Select/Deselect All Files, to select or deselect at the files currently displayed, check the Select All Files checkbox.","maxgalleria-media-library") ?></p>
        <p><?php _e("Select a Group of Files, To select a group of adjacent files, check the checkbox of the first file in the group and the while holding down the Shift key, click the last file in the group.","maxgalleria-media-library") ?></p>
        
        <p><strong><?php _e("Media Categories","maxgalleria-media-library") ?></strong></p>
        <p><?php _e("Media Library Folder Pro adds media categories to your Wordpress site. ","maxgalleria-media-library") ?></p>
        <p><?php _e("To assign or view categories, click the category icon","maxgalleria-media-library") ?> <i class="fa-solid fa-boxes-stacked mlf-help-icon"></i> <?php _e("to reveal the category area. From this area you can:","maxgalleria-media-library") ?></p>
        <ul>
          <li><?php _e("Add a new category","maxgalleria-media-library") ?></li>
          <li><?php _e("Set the category of items in the media library","maxgalleria-media-library") ?></li>
          <li><?php _e("View the categories of a single item","maxgalleria-media-library") ?></li>
          <li><?php _e("Display items by categories","maxgalleria-media-library") ?></li>
          <li><?php _e("Change the sort order of items in categories","maxgalleria-media-library") ?></li>
        </ul>
        <p><?php _e("Add a new category by clicking the Add New Category button. Once added, the new category is automatically displayed the category list.","maxgalleria-media-library") ?></p>
        <p><?php _e("To set the category of one or more images in Media Library Folders Pro, click the checkboxes of the desired images, click the check box of a single category and press the Set Categories button.","maxgalleria-media-library") ?></p>
        <p><?php _e("If you want to see what categories have been assigned to a particular image, check an image you and then click the Get Categories button.","maxgalleria-media-library") ?></p>
        <p><?php _e("One can do the same even when the Categories Area is not displayed. Just click on an image and click the Categories button. When it loads the categories, the category associated with the check image will be checked","maxgalleria-media-library") ?></p>
        <p><?php _e("To view all the images in one or more categories, check the checkboxes of the categories you want to view and press the View Categories button. If there are more than 40 items to display (in grid view) you will be prompted to view either the images of the titles of the images or files.","maxgalleria-media-library") ?></p>
        <p><?php _e("When viewing items by category you can specify the sorting order, either order by title or order by date.","maxgalleria-media-library") ?></p>
        <p><?php _e("With the items displayed by categories, you can select items from the list to perform move, copy, regenerate thumbnails or add to a MaxGalleria gallery operations.","maxgalleria-media-library") ?></p>                
        <p><strong><?php _e("Bulk Actions","maxgalleria-media-library") ?></strong></p>
        <p><?php _e("To use a bulk action, select an action from the dropdown list and click the Apply button.","maxgalleria-media-library") ?></p>
        <p><?php _e("Bulk Move, The bulk move function allows one to move a larger number of files than can be moved by the drag and drop method. Detailed instructions for using bulk move are available when the bulk move box is visible.","maxgalleria-media-library") ?></p>
        <p><?php _e("Playlist Shortcode - This shortcode generator lets one create an audio or video playlist that can be displayed in a post or page.","maxgalleria-media-library") ?></p>
        <p><?php _e("Jetpack Gallery, Allows the creation of image gallery shortcodes. Additional gallery features are available when the Jetpack plugin is installed.","maxgalleria-media-library") ?></p>
        <p><?php _e("Embed PDF/Audio/video, Enables the creation of shortcodes to embed PDFs, audio or video files into a page or post.","maxgalleria-media-library") ?></p>
        <p><?php _e("Add Images to NextGen Galleries, For sites where the NextGen plugin is installed, Media Library Folders can add images NextGen galleries.","maxgalleria-media-library") ?></p>
        <p><?php _e("Rollback Scaled Images - Since Wordpress 5.3, the media library scales very large images. For those you want to undo the image scaling, this can be undone by first enabling image scaling rollback in Media Library Folders Pro Settings. Then, this option will be available in the Bulk Actions dropdown. To use, visit a folder containing scaled images, select Roll back scaled images from the bulk actions dropdown list and click the apply button. The plugin will search the current folder for scaled images and attempt to replace them in the media library database with the full site image if it is still available on the server.","maxgalleria-media-library") ?></p>
      </div>

    <?php        
  }
  
  public function folder_access_help() {
    ?>
      <p><strong><?php _e("Folder Access Instructions","maxgalleria-media-library") ?></p></strong></p>
      <p><?php _e("Only roles that are allowed to upload files appear in the Role dropdown list. Note that each of the roles have full access until any folder access is set for a role.","maxgalleria-media-library")?></p>
      <p><?php _e("When viewing the access folder permissions for a role, no checked check boxes means full access to all folders. Once one or more folder boxes are checked and saved, users of the current role are restricted to only access the checked folders and their parent folders. In this case, the check boxes of parent folders do not need to be checked, access to these parent folders is automatically granted by the plugin.","maxgalleria-media-library")?></p>
      <p><?php _e("Note that checking the check box of a parent folder will add checkmarks to all its sub folders. And unchecking a checkbox for a parent folder will uncheck all it sub folders.","maxgalleria-media-library")?></p>
      <p><?php _e("To remove all restrictions on a role, check and uncheck the top most folder to remove all checkbox checks and click Save Access.","maxgalleria-media-library")?></p>
      <p><?php _e("Note that folder access restrictions only affect logged in user from adding, modifying or removing files from the restricted folder; it does not prevent files from being viewed or downloaded.","maxgalleria-media-library")?></p>
      <p><?php _e("On the Wordpress media page, users can still access files through the list view. Access to the list view can be turn of in Media Library Folders Pro Settings","maxgalleria-media-library")?></p>
            
    <?php  
  }  
  
  public function bulk_move_help() {
    ?>
      <div id="bulk-move-help" style="display:none">
        <p><strong><?php _e("Bulk Move Instructions", "maxgalleria-media-library") ?></strong></p>
        <p><?php _e("1. After opening the bulk move window, select a destination folder from the folder tree.", "maxgalleria-media-library") ?></p>
        <p><?php _e("2. Select the files to be moved by checking their check boxes. Use the Select All Files checkbox to select all the displayed files.", "maxgalleria-media-library") ?></p>
        <p><?php _e("3. Click the Move Selected Files to begin the moving files. The button will change to a Stop Moving Files button which may allow one to interrupt the file moving process, but because the process goes very quickly, it cannot halt processing immediately.", "maxgalleria-media-library") ?></p>
        <p><?php _e("4. To change to a different destination folder, click the Reselect Destination Folder and then click the new destination folder in the folder tree.", "maxgalleria-media-library") ?></p>
        <p><?php _e("5. To close the Bulk Move window click the 'x' in the right corner of the bulk move area.</p>", "maxgalleria-media-library") ?></p>
      </div>                  
    <?php  
  }
  
  public function playlist_help() {
    
    ?>    
      <div id="playlist-help" style="display:none">
        <p><strong><?php _e("Playlist Shortcode Instructions", "maxgalleria-media-library") ?></strong></p>
        <p><?php _e('To generate a audio or video playlist shortcode:','maxgalleria-media-library') ?></p>            
        <p><?php _e('1. Select the type of playlist to create, audio or video.','maxgalleria-media-library') ?></p>
        <p><?php _e('2. Navigate to the folders containing the files that you want to include in the playlist and click on the files to be included in the playlist. Before the new file\'s ID is added to the list of IDs, it type will be checked. An error message will be displayed if the file does not match the selected playlist type.','maxgalleria-media-library') ?></p>
        <p><?php _e('3. After adding files click on the Generate Shortcode button to create the shortcode.','maxgalleria-media-library') ?></p>
        <p><?php _e('4. Click on Copy to Clipboard button to copy the shortcode and then insert it into a page or post.','maxgalleria-media-library') ?></p>
      </div>
    <?php     
  }
  
  public function embed_help() {
    ?>      
      <div id="embed-file-help" style="display:none">        
        <p><strong><?php _e("Embed Shortcode Instructions", "maxgalleria-media-library") ?></strong></p>
        <p><?php _e('To generate an embed shortcode:','maxgalleria-media-library') ?></p>
        <p><?php _e('1. Select one file from the current folder and click the Embed Shortcode button.','maxgalleria-media-library') ?></p>
        <p><?php _e('2. For PDF or Video files, enter a width in either pixels ("px") or percent ("%") and a height in pixels (‘px’).','maxgalleria-media-library') ?></p>
        <p><?php _e('3. Select other options as needed.','maxgalleria-media-library') ?></p>
        <p><?php _e('4. Click the Generate Shortcode button to create the shortcode.','maxgalleria-media-library') ?></p>
        <p><?php _e('5. Click the Copy to Clipboard button to copy the shortcode to paste into a post or page.','maxgalleria-media-library') ?></p>
        <p><?php _e('6. To generate another shortcode, click the Embed Shortcode button to close the embed shortcode generator, select a new file and click the Embed Shortcode button again.','maxgalleria-media-library') ?></p>
      </div>      
    <?php  
  }
  
  public function jp_gallery_help() {
    ?>
      <div id="jp-gallery-help" style="display:none">        
        <p><strong><?php _e("Gallery Shortcode Generator Instructions", "maxgalleria-media-library") ?></strong></p>
        <p><?php _e('To generate a gallery shortcode:','maxgalleria-media-library') ?></p>            
        <p><?php echo stripslashes( __('1. Select images by clicking their checkboxes and then clicking the Add Selected Images button. The selected images will appear in the selected image list directly below the gallery options. Images can be added to the selected image list from multiple folders. Or instead of selecting individual images enter a post or page ID number to display all embedded images in a post or page. To find the post or page ID, edit a post or page and looked at the link in the browser address bar. The number the comes after \'post=\' is the post or page ID. Note if a post or page ID is used, this will override displaying any selected images.','maxgalleria-media-library')) ?></p>
        <p><?php _e('2. Individual images can be removed from the selected images list by checking their checkboxes and clicking the Remove Selected Images button. All the images in the selected image list can be removed by clicking the Remove All Images button.','maxgalleria-media-library') ?></p>
        <p><?php echo stripslashes( __('3. Select the options for displaying the images. Note the shortcode that is generated by Media Library Folders Pro is one of Wordpress\' builtin shortcodes and not all available options may be supported by the current version of Wordpress installed.','maxgalleria-media-library'))?></p>
        <p><?php _e('4. After selecting the desired options click the Generate Shortcode button to create the shortcode which will appear in the text area below the selected image list. Click the Copy to Clipboard button to copy the shortcode to insert into a post or Gutenberg shortcode widget.','maxgalleria-media-library') ?></p>
      </div>              
    <?php  
  }
  
  public function maintenance_help() {
    ?>
      <div class="tn-box purge-margin-top">
        <p><strong><?php _e("Media LIbrary Maintenance Instructions","maxgalleria-media-library")?></strong></p>
        <p><?php _e("1. Before searching for files, check your folder tree for any folders that do not contain media files which may have been added by other plugins. Special use folders such as ones for backup files, cache files etc, can be hidden and then Media Library Maintenance will not scan those folders when searching for uncateloged files.","maxgalleria-media-library")?></p>
        <p><?php _e("2. When the Media Library Maintenance page opens, it will display the results of any previous search for files. Click the Search for Uncataloged Files button to start a new search of your media library files.","maxgalleria-media-library")?><span id="mm-dots">..</span></p>
        <p><?php _e("3. Once the search is complete, choose which files to delete or import into the media library.","maxgalleria-media-library")?></p>
        <p><?php _e("4. Any uncataloged thumbnail images that are selected for import will have the file dimensions part of the file name (width x height) removed when imported. To prevent the removal of the file dimensions, uncheck the 'Automatically remove file size from imported thumbnail images names' option.","maxgalleria-media-library")?></p>
        <p><?php _e("5. If there is a full size image to be imported and it also has thumbnail images, select import for the full size image and mark all the thumbnails for deletion as they will be automatically regenerated for the full size image.","maxgalleria-media-library")?></p>
        <p><?php _e("6. One does not have to review all the files in the uncateloged file list to process that ones that have been selected for deletion or import. The plugin will only process that one that have been marked for deletion or import.","maxgalleria-media-library")?></p>
        <p><?php _e("7. Folders that are recorded in the database but which do not exist on the server will also be displayed in the list and can be selected for removal from the database or to be recreated on the server.","maxgalleria-media-library")?></p>
        <p><?php _e("8. Files that are not allowed by Wordpress will not be imported and will be denoted as 'File type now allowed'.","maxgalleria-media-library")?></p>
        <p><?php _e("<strong>Media Library Maintenance can also be used for the bulk importing of files to the media library.</strong>","maxgalleria-media-library")?></p>
        <p><?php _e("1. Upload your files to the the site and place them in new or existing folders under the site's uploads folder.","maxgalleria-media-library")?></p>
        <p><?php _e("2. Go to the Media Library Folders Pro Media Library Maintenance page and click the Search for Uncataloged Files. Media Library Folder Pro will check for new files and folders and will list all the files it finds.","maxgalleria-media-library")?></p>
        <p><?php _e("3. Once searching is finished, select the files to import into the media library and then click the Process Files button.","maxgalleria-media-library")?></p>
      </div>    
    <?php
  }
  
  public function export_help() {
    ?>
      <div class="tn-box purge-margin-top">
        <p><strong><?php _e("Export/Import Instructions","maxgalleria-media-library")?></strong></p>
        <p><?php _e("The Import/Export feature allows an administrator to export a site's media library from one site to another. With this feature: ","maxgalleria-media-library")?></p>
        <ul id="mlfp-export-features">
          <li><?php _e("You can export and download the contents of your media library from one WordPress site and then upload and import it into the media library of another WordPress site.","maxgalleria-media-library")?></li>
          <li><?php _e("The feature will also work for media libraries on multi-sites, either multi-site library to single site library or single site library to multi-site library.","maxgalleria-media-library")?></li>
          <li><?php _e("You can also import files to an AWS S3 bucket when connected to a site through Media Library Folders Pro S3.","maxgalleria-media-library")?></li>
          <li><?php _e("It can even be used to simply create a back-up of your media library files.","maxgalleria-media-library")?></li>
        </ul>  
        <p><?php _e("Please note: Backups do not include thumbnail images. These will be generated when the images are imported into the destination site. The only files and folders included in the backup will be those that are listed in the media library database.","maxgalleria-media-library")?></p>
        <p><?php _e("If media files are stored on a cloud server, such as AWS Spaces, it will be necessary to move the files from the cloud server to the local media library in order to create an export file.","maxgalleria-media-library")?></p>
        <p><?php _e("To create a new export file, click on the Create a New Media Export File button, enter a name for the export and press the OK button.  Media Library Folders will generate a new export file and add it the export file list.","maxgalleria-media-library")?></p>
        <p><?php _e("Once created, an export file can be download by clicking on the file's download icon","maxgalleria-media-library")?> <i class="fa-solid fa-download mlf-help-icon"></i>. <?php _e("The zip file downloaded can then be uploaded to a different site where Media Library Folders Pro is installed. There is also a delete icon","maxgalleria-media-library")?> <i class="fa-solid fa-trash-can mlf-help-icon"></i>. <?php _e(" if you don’t need to or just prefer not to keep a copy on your site.","maxgalleria-media-library")?></p>
        <p><?php _e("To upload an export file, click on the Upload a Media Export File button to display the Import Media Library Export File window. Click the browse button and choose the export file to upload and then the Upload Export File. Once the file is finished uploading it will appear in the list of the export files and you can then click the import icon", "maxgalleria-media-library")?> <i class="fa-solid fa-upload mlf-help-icon"></i>. <?php _e(" to import the files and folders into the site’s media library.","maxgalleria-media-library")?></p>
        <p><?php _e("This will begin a two-part process. First up, folders are added to the site. If one of the imported folder names already exist, Media Library Folders Pro won’t create a duplicate, instead it will use the existing folder during the file import.","maxgalleria-media-library")?></p>
        <p><?php _e("Once the folders are created, then second part of the import process will begin, adding files to the appropriate folders. If a folder already contains a file with the same name as one being imported, it won’t replace it, but instead will simply skip that file and continue importing.","maxgalleria-media-library")?></p>
        <p><?php _e("If for whatever reason, you need to abort the import, simply click Stop Import button and the import process will be halted. You can restart the process at any time and any folders and files not already copied across will then be imported. The import will attempt a full import but since files are not overwritten, it will just skip past them.","maxgalleria-media-library")?></p>
      </div>    
    <?php
  }
  
  public function regen_thumbnails_help() {    
    ?>
    <p><strong>Regenerate Thumbnail Instructions</strong></p>  
    <p><?php printf( __( "Click the Regenerate All Thumbnails button to regenerate thumbnails for all images in the Media Library. This is helpful if you have added new thumbnail sizes to your site. Existing thumbnails will not be removed to prevent breaking any links.", 'maxgalleria-media-library' ), admin_url( 'options-media.php' ) ); ?></p>
    <p>The regeneration process can be stopped by clicking the Abort Resizing Images button.</p>
    <p><?php printf( __( "You can regenerate thumbnails for individual images from the Media Library Folders Pro page by checking the box below one or more images and clicking the Regenerate Thumbnails button. The regenerate operation is not reversible but you can always generate the sizes you need by adding additional thumbnail sizes to your theme.", 'maxgalleria-media-library'), admin_url( 'upload.php' ) ); ?></p>
    <?php    
  }
  
  public function thumbnails_management_help() {
    ?>
    <p><strong><?php _e("Instructions","maxgalleria-media-library")?></strong></p>
    <p><?php _e("To start, uncheck the boxes of the thumbnail sizes you wish
      to not generate and click Save Thumbnail Settings. Default Wordpress
      thumbnails sizes, ‘thumbnail’, ‘medium’, ‘large’, cannot be removed as
      Wordpress requires that these sizes be present. After saving the thumbnail
      settings click the Remove Unselected Thumbnail Sizes button to start the 
      removal process.","maxgalleria-media-library")?></p>

    <p><?php _e("To add thumbnail sizes or undo the removal 
      of thumbnail sizes, check the boxes of the thumbnails 
      desired and save the thumbnail settings. Then go to the","maxgalleria-media-library")?> 
      <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=mlfp-thumbnails&tab=regenerate">
      <?php _e("Media Library Folders Pro Regenerate Thumbnails page","maxgalleria-media-library")?></a> 
      <?php _e("and regenerate thumbnails images for the whole site.","maxgalleria-media-library")?></p>

    <p><?php _e("Some thumbnail sizes may no longer be active if the theme or plugin 
      that added the sizes has been removed from the site. To delete the inactive sizes 
      from the thumbnail management list, click the ‘Reset Defaults’ button to reset the 
      list of sizes registered on your site. Then uncheck the thumbnail sizes that should 
      not be generated and resave the thumbnail settings.","maxgalleria-media-library")?></p>
    
    <?php
  }
  
  public function image_seo_help() {
    ?>
    <p><strong><?php _e("Image SEO Instruction","maxgalleria-media-library")?></strong></p>
    <p><?php _e("When Image SEO is enabled Media Library Folders Pro automatically adds ALT and Title attributes with the default settings defined below to all your images as they are uploaded.","maxgalleria-media-library")?></p>
    <p><?php _e("You can easily override the Image SEO default settings when you are uploading new images. When Image SEO is enabled you will see two fields under the Upload Box when you add a file - Image Title Text and Image ALT Text. Whatever you type into these fields overrides the default settings for the current upload or sync operations.","maxgalleria-media-library")?></p>
    <p><?php _e("To change the settings on an individual image simply click on the image and change the settings on the far right. Save and then back click to return to Media Library Folders or MLFP.","maxgalleria-media-library")?></p>
    <p><?php _e("Image SEO supports two special tags:<br>
%filename - replaces image file name ( without extension )</br>
%foldername - replaces image folder name","maxgalleria-media-library")?></p>
    <?php
  }
  
  public function options_help() {
    ?>
    <p><strong><?php _e("Media Library Folders Pro Options Instructions","maxgalleria-media-library")?></strong></p>
    <p><strong><?php _e("Remove folder tree from Media page & popups","maxgalleria-media-library")?></strong>, <?php _e("removes the folder tree from the standard Wordpress media library. This is useful if you are having an issue with displaying the folder tree.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable loading folder tree on the front end media popups","maxgalleria-media-library")?></strong>, <?php _e("by default, the folder tree will not appear in media popups on the front end of the site. If you are using a front end theme editor, you would need to check this option to allow the folder tree to be displayed.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable Pagination","maxgalleria-media-library")?></strong>, <?php _e("checking this option turns on pagination on the Library tab of the Folders and Files page. To set the number of files to display, enter the number in Number of images to display per page option.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable Front End Upload","maxgalleria-media-library")?></strong>, <?php _e("Media Library Folders Pro includes a shortcode for upload files to a selected folder. To use this feature, check this option.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Disable file delete, folder hide, folder add, folder delete and sync for non Administrators","maxgalleria-media-library")?></strong>, <?php _e("this option remove these functions for non administrators.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable folder access by user role","maxgalleria-media-library")?></strong>, <?php _e("check this option to turn on the user role folder access limit feature for non administrators. You can then set with folders different user roles can access in the Folder Access tab on the Folders and Files page. Note, limiting folder access by user roles does not prevent internet access to your media files.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable scaled image rollback","maxgalleria-media-library")?></strong>, <?php _e("this option was added to undo the Wordpress images scaling. With this option is checked, a Roll Back Scaled Images option is added to the Bulk Actions dropdown list on the Library tab of the Folders and Files page, which when selected, will search the current folder for previously scaled images and covert them to non scaled.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Disable media library list view","maxgalleria-media-library")?></strong>, <?php _e("when checked, this option prevents users from switching the the media library from a grid to list view of the media files.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Enable MLFP Query Display","maxgalleria-media-library")?></strong>, <?php _e("This option is only for debugging purposes and should not normally be checked. It will display the SQL query for displaying folder contents on the Library page.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Disable import of captions from images","maxgalleria-media-library")?></strong>, <?php _e("this option disables caption text importing from JEPG images when they are added to the media library.","maxgalleria-media-library")?></p>
    <p><strong><?php _e("Add an index to the postmeta table","maxgalleria-media-library")?></strong>, <?php _e("For sites with a large number of media files, check this option to create a new index fro the postmeta table to speed by the loading of the Media Library Folders Pro page.","maxgalleria-media-library")?></p>
    <p><?php _e("For language local issues, use this option:","maxgalleria-media-library")?> <strong><?php _e("For users with non Latin character sets, check this option and enter the proper locale code below to fix issues with moving files.","maxgalleria-media-library")?></strong></p>
    <?php
  }
  
  public function mlfp_export() {
    require_once "includes/mlfp-import-export.php";  
  }
  
  public function setup_mlfp_exim() {
            
    $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
    
    if(empty($mlfp_exim_folder) || !file_exists($mlfp_exim_folder)) {
      
      if(defined('UPLOADS'))
        $uploads_url = home_url(UPLOADS);
      else
        $uploads_url = content_url() .'/uploads';
      
      $upload_path = $this->get_absolute_path($uploads_url);
      
      $mlfp_exim_folder = $upload_path . DIRECTORY_SEPARATOR . MLFP_EXIM_FOLDER;
    
      update_option(MLFP_EXIM_FOLDER_LOCATION, $mlfp_exim_folder);
      
      $this->mlfp_exim_folder = $mlfp_exim_folder;
            
		  if(!file_exists($mlfp_exim_folder)) {        
        if(mkdir($mlfp_exim_folder)) {
          if(defined('FS_CHMOD_DIR'))
            @chmod($mlfp_exim_folder, FS_CHMOD_DIR);
          else  
            @chmod($mlfp_exim_folder, 0755);
                    
        }  
      }   
    }
    
    $skip_folder_file = $mlfp_exim_folder . DIRECTORY_SEPARATOR . "mlpp-hidden";
    
    if(!file_exists($skip_folder_file)) {   
      file_put_contents($skip_folder_file, '');
    }
        
    $this->add_csv_data_table();
    $this->add_folder_import_table();
    
  }
  
  public function add_csv_data_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_CSV_DATA_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( 
`id` bigint(20) NOT NULL,
`post_title` text NOT NULL,
`attached_file` longtext NOT NULL,
`parent_id` bigint(20) NOT NULL
) DEFAULT CHARSET=utf8;";	    
 
    dbDelta($sql);
    
  }
  
  public function add_folder_import_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( 
`id` bigint(20) NOT NULL,
`parent` bigint(20) NOT NULL,
`text` text NOT NULL,
`path` longtext NOT NULL,
`new_id` bigint(20) NULL
) DEFAULT CHARSET=utf8;";	    
 
    dbDelta($sql);
    
  }
    
  // this function is in MLFP
//  private function is_dir_empty($directory) {
//    $filehandle = opendir($directory);
//    while (false !== ($entry = readdir($filehandle))) {
//      if ($entry != "." && $entry != "..") {
//        closedir($filehandle);
//        return false;
//      }
//    }
//    closedir($filehandle);
//    return true;
//  }
  
  public function mlfp_create_backup_folder() {
    
    global $is_IIS;    
    
    if(class_exists('MGMediaLibraryFoldersProS3')) {
      global $maxgalleria_media_library_pro_s3;
    }
        
    $ml_zip_file = "";
    
    $message = "";
    
    $new_backup_folder = null;
       
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['backup_name'])) && (strlen(trim($_POST['backup_name'])) > 0))
      $backup_name = trim(stripslashes(strip_tags($_POST['backup_name'])));
    else
      $backup_name = "";
    
    if(!empty($backup_name)) {
            
      $backup_name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $backup_name); // simple file name validation
      $backup_name = filter_var($backup_name, FILTER_SANITIZE_URL); // Remove (more) invalid characters
      
      if(class_exists('MGMediaLibraryFoldersProS3') && $maxgalleria_media_library_pro_s3->remove_from_local) {
        $message = __('Currently Media Library Folders Pro S3 is set to store media library files only on the Cloud Server. Please go to Cloud Storage, Cloud Files tab and click the <strong>Download Media Files from Cloud Storage</strong> button to move all your files to the local media library before creating an export file.','maxgalleria-media-library');
        $new_backup_folder = null;
        $ml_zip_file = null;
        
        $data = array('message' => $message, 'new_backup_folder' => $new_backup_folder, 'ml_zip_file' => $ml_zip_file );								
        echo json_encode($data);
        die();        
      }
      
      if(!$this->backup_exists($backup_name))  {
        
        $message = __('Creating backup ','maxgalleria-media-library') . $backup_name . __('. This may take a few minutes. The list of backups will refresh once the process is complete. ','maxgalleria-media-library');

        $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );

        $new_backup_folder = $this->createNewBackupFolder($backup_name);
        
        if($new_backup_folder !== false ) {

          $ml_zip_file = $new_backup_folder . DIRECTORY_SEPARATOR . 'mlfp-data.zip';

          // are we on windows?
          if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
            $ml_zip_file = str_replace('/', '\\', $ml_zip_file);
          }
        
        } else {
          
          $message = $backup_name . __(' could not be created. Try another backup name.','maxgalleria-media-library');

          $new_backup_folder = null;

          $ml_zip_file = null;
          
        }

      } else {
        
        $message = $backup_name . __(' already exists.','maxgalleria-media-library');

        $new_backup_folder = null;

        $ml_zip_file = null;
        
      }

    }
    
		$data = array('message' => $message, 'new_backup_folder' => $new_backup_folder, 'ml_zip_file' => $ml_zip_file );								
    			
	  echo json_encode($data);
            
    die();
  }
  
  public function backup_exists($backup_name) {
    
    $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');

    $new_backup_folder = $mlfp_exim_folder . DIRECTORY_SEPARATOR . $backup_name;

    return file_exists($new_backup_folder);
    
  }
  
  public function mlfp_save_bk_data() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['backup_name'])) && (strlen(trim($_POST['backup_name'])) > 0))
      $backup_name = trim(stripslashes(strip_tags($_POST['backup_name'])));
    else
      $backup_name = "";
        
    if ((isset($_POST['new_backup_folder'])) && (strlen(trim($_POST['new_backup_folder'])) > 0))
      $new_backup_folder = trim(stripslashes(strip_tags($_POST['new_backup_folder'])));
    else
      $new_backup_folder = "";
    
    if(!empty($backup_name) && !empty($new_backup_folder)) {
    
      $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
        
      $new_backup_folder = $this->createNewBackupFolder($backup_name);
                        
      $folders = $this->get_export_folder_data();

      $folders_file = $new_backup_folder . DIRECTORY_SEPARATOR . 'folders.json';
      file_put_contents($folders_file, json_encode($folders, JSON_UNESCAPED_UNICODE));

      $this->createAttachmentsFile($new_backup_folder);

      $this->createAttachmentsZipFile($new_backup_folder);
        
    }
    
  }
  
  private function createNewBackupFolder($backup_name) {
    
    $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
    
    if(!file_exists($mlfp_exim_folder)) {        
      if(mkdir($mlfp_exim_folder)) {
        if(defined('FS_CHMOD_DIR'))
          @chmod($mlfp_exim_folder, FS_CHMOD_DIR);
        else  
          @chmod($mlfp_exim_folder, 0755);
        
        $skip_folder_file = $mlfp_exim_folder . DIRECTORY_SEPARATOR . "mlpp-hidden";

        file_put_contents($skip_folder_file, '');        
      } 
    }  
    
    $new_backup_folder = $mlfp_exim_folder . DIRECTORY_SEPARATOR . $backup_name;

    if(!file_exists($new_backup_folder)) {        
      if(mkdir($new_backup_folder)) {
        if(defined('FS_CHMOD_DIR'))
          @chmod($new_backup_folder, FS_CHMOD_DIR);
        else  
          @chmod($new_backup_folder, 0755);
      } else {
        return false;
      }
    }
    return $new_backup_folder;
  }
  
  public function mlfp_refresh_backups() {
    
    $backups = "";
    $count = 0;
    $even_odd = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
    
    if(!file_exists($this->mlfp_exim_folder)) {
      if(mkdir($this->mlfp_exim_folder)) {
        if(defined('FS_CHMOD_DIR'))
          @chmod($this->mlfp_exim_folder, FS_CHMOD_DIR);
        else  
          @chmod($this->mlfp_exim_folder, 0755);

        $skip_folder_file = $this->mlfp_exim_folder . DIRECTORY_SEPARATOR . "mlpp-hidden";

        file_put_contents($skip_folder_file, '');
      }  
    }
        
    $folder_names = array_diff(scandir($this->mlfp_exim_folder), array('..', '.','mlpp-hidden','.DS_Store'));
    
    foreach ($folder_names as $folder_name) {
      
      $even_odd = ($count % 2) ? 'white-bg' : 'gainsboro-bg';

      $folder_path = $this->mlfp_exim_folder . DIRECTORY_SEPARATOR . $folder_name;

      if(is_dir($folder_path)) {

        $backups .=  "    <tr class='$even_odd'>"  . PHP_EOL;
        $backups .= "       <td>$folder_name</td>" . PHP_EOL; //$this->mlfp_exim_folder &nonce=
        $backups .= "       <td>" . date ("F d Y H:i", filemtime($folder_path)) ."</td>" . PHP_EOL; 
        $download_path = "location.href='" . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/download.php?folder={$folder_name}&path={$this->mlfp_exim_folder}&uploads={$this->uploads_folder_name}'";
        $backups .= '       <td><a onclick="'. $download_path .'" class="mlfp-download-backup exim-button" title="'.__('Download Export File', 'maxgalleria-media-library' ).'"><i class="fa-solid fa-download fa-2x"></i></a></td>' . PHP_EOL;
        $backups .= "       <td><a class='mlfp-import-backup exim-button' folder-id='$folder_name' title='".__('Import Export File', 'maxgalleria-media-library' )."'><i class='fa-solid fa-upload fa-2x'></i></a></td>" . PHP_EOL;
        $backups .= "       <td><a class='mlfp-delete-backup exim-button' folder-id='$folder_name' title='" . __('Delete Export File', 'maxgalleria-media-library' ) . "'><i class='fa-solid fa-trash-can fa-2x'></i></a></td>" . PHP_EOL;
        $backups .= "     </tr>"  . PHP_EOL;
        $count++;
          //<i class="fa-solid fa-download"></i>
          //<i class="fa-solid fa-upload"></i>
          //<i class="fa-solid fa-trash-can"></i>
      }
    }
       
    echo $backups;
    die();
  
  }
  
  public function createAttachmentsFile($new_backup_folder) {
  
    global $wpdb;
    
    $mldata_file = $new_backup_folder . DIRECTORY_SEPARATOR . 'mlfp-data.csv';
    
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
    
    $sql = "SELECT {$wpdb->prefix}posts.id, {$wpdb->prefix}posts.post_title, pm.meta_value as attached_file, $folder_table.folder_id as parent_id  
FROM {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
WHERE post_type = 'attachment' 
AND pm.meta_key = '_wp_attached_file'
group by ID
ORDER by parent_id";
			
      //error_log($sql);

      $rows = $wpdb->get_results($sql);
      
      if($rows) {
        $file = fopen($mldata_file, 'w');

        foreach ($rows as $row) {
          fputcsv($file, (array)$row);
        }

        fclose($file); 
      }
    
  }
  
  public function createAttachmentsZipFile($new_backup_folder) {
    
    global $wpdb, $is_IIS;
    
    $ml_zip_file = $new_backup_folder . DIRECTORY_SEPARATOR . 'mlfp-data.zip';
    
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;    
    
		$basedir = $this->upload_dir['basedir'];
    
    
		$basedir = rtrim($basedir, '/') . '/';
            
    $sql = "SELECT {$wpdb->prefix}posts.*, pm.meta_value as attached_file
FROM {$wpdb->prefix}posts 
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
WHERE post_type = 'attachment' 
AND pm.meta_key = '_wp_attached_file'
group by ID
ORDER by ID";
			
    //error_log($sql);

    $rows = $wpdb->get_results($sql);

    if($rows) {

      $zip = new ZipArchive;
      if ($zip->open($ml_zip_file, ZipArchive::CREATE) === TRUE) {        

        foreach($rows as $row) {

          $file_to_zip = $basedir . ltrim($row->attached_file, '/');

          // are we on windows?
          if ($is_IIS || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
            $file_to_zip = str_replace('/', '\\', $file_to_zip);
          }
          
          $zip->addFile($file_to_zip, $row->attached_file);

        }
        $zip->close();
      }

    }
  
  }
    
  public function get_total_media_bk_file_counts() {
    
		global $wpdb;
    
    if(is_multisite()) {
      $all_files = 0;
      
      $current_blog = $wpdb->blogid;

      $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        $this->blog_id = $blog_id;
        $all_files +=  $this->get_media_file_bk_count();
      }
      $this->blog_id = 0;

      // Now switch back to the root blog
      switch_to_blog($current_blog);
      
      return $all_files;
            
    } else {
      return $this->get_media_file_bk_count();
    }
    
  }
  	
	public function get_media_file_bk_count() {
		
		global $wpdb;
    
		$sql = "SELECT COUNT(DISTINCT p.ID) FROM {$wpdb->prefix}posts as p WHERE p.post_type = 'attachment'";
	
		$all_files = $wpdb->get_var($sql);
				
		return $all_files;
		
	}
    
  public function getRelativePath($filepath) {
		$upload_position = strpos($filepath, $this->uploads_folder_name);
		$file_location = substr($filepath, $upload_position + $this->uploads_folder_name_length);
    return $file_location;
  }
    
  public function mlfp_exim_delete_backup() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['backup_folder'])) && (strlen(trim($_POST['backup_folder'])) > 0))
      $backup_folder = trim(stripslashes(strip_tags($_POST['backup_folder'])));
    else
      $backup_folder = "";
    
    if($backup_folder != '') {
      
      $folder_path = $this->mlfp_exim_folder . DIRECTORY_SEPARATOR . $backup_folder;
      
      if(file_exists($folder_path)) {
        if(is_dir($folder_path)) {
          
          $folder_names = array_diff(scandir($folder_path), array('..', '.','mlpp-hidden','.DS_Store'));

          foreach ($folder_names as $folder_name) {
            $backup_file = $folder_path . DIRECTORY_SEPARATOR . $folder_name;
            unlink($backup_file);
          }
          rmdir($folder_path);        
        }
      }
    }
    
    echo "ok";
    die();
    
  }
  
  
	public function exim_upload_file() {
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    }
    
	  $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
	  //$wp_upload_dir = wp_upload_dir();
		$file_path = trailingslashit($mlfp_exim_folder) . $_POST['file'];
		$file_data = $this->decode_chunk($_POST['file_data']);

		if ( false === $file_data ) {
			wp_send_json_error();
		}

		file_put_contents( $file_path, $file_data, FILE_APPEND );

		wp_send_json_success();
	}

	public function decode_chunk( $data ) {
		$data = explode( ';base64,', $data );

		if ( ! is_array( $data ) || ! isset( $data[1] ) ) {
			return false;
		}

		$data = base64_decode( $data[1] );
		if ( ! $data ) {
			return false;
		}

		return $data;
	}
  
  public function exim_unzip_file() {
    
    $destination_counter = 0;
    
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('Missing nonce! Please refresh this page.','maxgalleria-media-library'));
    }
    
    if ((isset($_POST['zip_file'])) && (strlen(trim($_POST['zip_file'])) > 0))
      $zip_file = trim(stripslashes(strip_tags($_POST['zip_file'])));
    else
      $zip_file = "";
    
    if($zip_file != '' && $this->current_user_can_upload) {
      
      $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
      $file_path = trailingslashit($mlfp_exim_folder) . $zip_file;
      $destination_folder = substr($file_path, 0, strrpos($file_path, '.zip'));
      
      // check if backup already exists
      $backup_destination = $destination_folder;      
      while(file_exists($destination_folder)) {
        
        // rename if found
        if($destination_counter > 20) {
          $message = __('The backup already exists.','maxgalleria-media-library');                
          echo $message;
          die();                  
        } else {
          $destination_counter++;
          $destination_folder = $backup_destination . '-' . $destination_counter;
        }
      }  
      
      if(file_exists($file_path)) {
        
        $zip = new ZipArchive;
        $res = $zip->open($file_path);
        if ($res === TRUE) {
          $zip->extractTo($destination_folder);
          $zip->close();
          unlink($file_path);
          $message = __('The backup uploaded successfully.','maxgalleria-media-library');
        } else {
          $message = __('The backup file could not be opened after uploading.','maxgalleria-media-library');        
        }    
        
      } else {
        $message = __('The upload failed.','maxgalleria-media-library');                
      }
            
      echo $message;
      die();
    }
    
  }
  
  public function mlfp_exim_import_backup() {
    
    $folder_count = 0;
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
    
    if ((isset($_POST['backup_folder'])) && (strlen(trim($_POST['backup_folder'])) > 0))
      $backup_folder = trim(stripslashes(strip_tags($_POST['backup_folder'])));
    else
      $backup_folder = "";
    
    if($backup_folder != '') {
      
      $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
      $json_file = trailingslashit($mlfp_exim_folder) . $backup_folder . "/folders.json";
      $csv_file = trailingslashit($mlfp_exim_folder) . $backup_folder . "/mlfp-data.csv";
            
      $folder_data = file_get_contents($json_file);
      $folders = json_decode($folder_data);
      
      $folder_count = $this->load_folder_data($folders);
                  
    }
    
    echo $folder_count;
    
    die();
            
  }
  
  public function load_folder_data($folders) {
    
    global $wpdb;
    
    $folder_count = 0;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
    
    $sql = "TRUNCATE TABLE " . $table  ;
    $wpdb->query($sql);
        
    foreach ($folders as $folder) {
      $wpdb->insert($table, (array)$folder);
      $folder_count++;
    }
    
    return $folder_count;
  }


  public function load_csv_data($csv_file) {
    
    global $wpdb;
    $file_count = 0;
    
    // id, post_title, attached_file, parent_id
    $csv_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_CSV_DATA_TABLE;
    
    $sql = "TRUNCATE TABLE " . $csv_table;
    $wpdb->query($sql);
        
    $handle = fopen($csv_file, 'r');
    while (!feof($handle) ) {
      $line = fgetcsv($handle, 1024);
      if(is_array($line)) {  
        $data = array(
          'id' => $line[0],
          'post_title' => $line[1],
          'attached_file' => $line[2],
          'parent_id' => $line[3]
        );
        
        $wpdb->insert($csv_table,$data);
        $file_count++;
      }
      
    }
    fclose($handle);    
        
    return $file_count;
          
  }
    
  public function base_folder($url) {
    
    $sub_folder = rtrim($url, '/');
    
    $sub_folder = substr($sub_folder, strrpos($sub_folder, '/')+1);
    
    return $sub_folder;
    
  }
  
	public function get_export_folder_data() {
		
    global $wpdb;
    
		$current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID ); 
		$folder_parents = $this->get_parents($current_folder_id);
		$folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
		
			$sql = "select ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file
from {$wpdb->prefix}posts
LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE ."' 
AND pm.meta_key = '_wp_attached_file' 
order by folder_id";

      //error_log($sql);
						
			$folders = array();
			$first = true;
			$rows = $wpdb->get_results($sql);            
			if($rows) {
				foreach($rows as $row) {
           
						$folder = array();
						$folder['id'] = $row->ID;
						if($row->folder_id === '0') {
							$folder['parent'] = '#';
						} else {
              if(!$row->folder_id)
						    continue;
						  // check if parent folder even exists
						  $sql = "select ID from {$wpdb->prefix}posts
						    where ID = {$row->folder_id} and post_type = '".MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE."'";
						  if (count($wpdb->get_results($sql)) == 0)
						    continue;
						  $folder['parent'] = $row->folder_id;
						}

						$folder['text'] = $row->post_title;
						$folder['path'] = $row->attached_file;
					
					$folders[] = $folder;
				}

			}

			return $folders;
		
	}
  
  public function mlfp_exim_load_import_data() {
    
    $folder_count = 0;
    
    if ((isset($_POST['backup_folder'])) && (strlen(trim($_POST['backup_folder'])) > 0))
      $backup_folder = trim(stripslashes(strip_tags($_POST['backup_folder'])));
    else
      $backup_folder = "";
    
    if($backup_folder != '') {
      
      $this->add_folder_import_table();
      $this->add_csv_data_table();
      
      $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
      $json_file = trailingslashit($mlfp_exim_folder) . $backup_folder . "/folders.json";
      $csv_file = trailingslashit($mlfp_exim_folder) . $backup_folder . "/mlfp-data.csv";
            
      $folder_data = file_get_contents($json_file);
      $folders = json_decode($folder_data);
      
      $folder_count = $this->load_folder_data($folders);
            
      $file_count = $this->load_csv_data($csv_file);
      
    }
    
    $data = array('folder_count' => $folder_count, 'file_count' => $file_count);
    
	  echo json_encode($data);
       
    die();     
  }
  
  public function save_new_folder_id($old_id, $new_id) {
    global $wpdb;
        
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
    
    $data = array('new_id' => $new_id);
    
    $where = array('id' => $old_id);
    
    $wpdb->update($table, $data, $where);
    
  }
  
  public function get_new_folder_id($old_id) {
    global $wpdb;
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
        
    $sql = "select new_id from $table where id = $old_id";
    
		$new_id = $wpdb->get_var($sql);
    
    return $new_id;
    
  }  
  
  public function epim_folder_exist($relative_path) {
    
    //error_log("epim_folder_exist, $relative_path");
    
    global $wpdb;    
		    
		$sql = "SELECT ID FROM {$wpdb->prefix}posts
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = ID
WHERE pm.meta_value = '$relative_path' 
and pm.meta_key = '_wp_attached_file'";

    //error_log($sql);

    $row = $wpdb->get_row($sql);
    if($row === null) {
      return false;
    } else {
      return $row->ID;
    }         
  }
  
  
  public function mlfp_exim_next_folder() {
        
		global $wpdb;
    $message = "";
    
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    } 
		
		if ((isset($_POST['last_folder'])) && (strlen(trim($_POST['last_folder'])) > 0))
      $last_folder = intval(trim(stripslashes(strip_tags($_POST['last_folder']))));
    else
      $last_folder = "";
		
		if ((isset($_POST['folder_count'])) && (strlen(trim($_POST['folder_count'])) > 0))
      $folder_count = intval(trim(stripslashes(strip_tags($_POST['folder_count']))));
    else
      $folder_count = 0;
    
    //$new_uploads_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
    $baseurl = $this->upload_dir['baseurl'];
    
    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_IMPORT_FOLDERS_TABLE;
				
		$percentage = (($last_folder+1) / $folder_count) * 100;
							
    $sql = "select * from $table order by id limit $last_folder, 1";

    //error_log($sql);

    $row = $wpdb->get_row($sql);

    if($row) {
      
      if($row->parent != 0) {

        $new_folder_url = $baseurl . '/' . $row->path;

        $existing_folder_id = $this->epim_folder_exist($row->path);          
          
        if($existing_folder_id == false) {
          $new_folder_path = $this->get_absolute_path($new_folder_url);

          if(!defined('MLFPEI_DEBUG')) {
            if(!file_exists($new_folder_path)) {
              if(mkdir($new_folder_path)) {
                if(defined('FS_CHMOD_DIR'))
                  @chmod($new_folder_path, FS_CHMOD_DIR);
                else  
                  @chmod($new_folder_path, 0755);
              }
              
              $parent_id = $this->get_new_folder_id($row->parent);
              if(!is_null($parent_id)) {
                $new_folder_id = $this->add_media_folder($row->text, $parent_id, $new_folder_url); 
                $message = __('Adding folder: ','maxgalleria-media-library') . $row->path;
                if($new_folder_id != false) {
                  $this->save_new_folder_id($row->id, $new_folder_id);
                }                              
              } else {
                $message = __('Could not import ','maxgalleria-media-library') . $row->path;                            
              }

            }  
          }

        } else {
          if(!defined('MLFPEI_DEBUG')) {
            $this->save_new_folder_id($row->id, $existing_folder_id);
            $message = $row->path . __(' already exists','maxgalleria-media-library');            
          }
        }  

      } else { // uploads folder
        if(!defined('MLFPEI_DEBUG')) {
          $new_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
          $this->save_new_folder_id($row->id, $new_folder_id);
          $message = __('Uploads folder found','maxgalleria-media-library');            
        }
      }  

      $last_folder++;

      //if($last_folder <10)
        $data = array('message' => $message, 'last_folder' => $last_folder, 'percentage' => $percentage );				
//      else {
//        $data = array('message' => __('Folders added.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );								
//      }  

    } else {       
      $data = array('message' => __('Folders added.','maxgalleria-media-library'), 'last_folder' => null, 'percentage' => 100 );								
    }	
        
	  echo json_encode($data);
    die();
  }
  
  public function mlfp_exim_next_file() {
    
    
		global $wpdb;
    
    if(class_exists('MGMediaLibraryFoldersProS3')) {
      global $maxgalleria_media_library_pro_s3;
    }
    $percentage = "";
    $message = "";
		
    if ( !wp_verify_nonce( $_POST['nonce'], MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE)) {
      exit(__('missing nonce! Please refresh the page.','maxgalleria-media-library'));
    }
      
    if ((isset($_POST['backup_folder'])) && (strlen(trim($_POST['backup_folder'])) > 0))
      $backup_folder = trim(stripslashes(strip_tags($_POST['backup_folder'])));
    else
      $backup_folder = "";
    		
		if ((isset($_POST['last_file'])) && (strlen(trim($_POST['last_file'])) > 0))
      $last_file = intval(trim(stripslashes(strip_tags($_POST['last_file']))));
    else
      $last_file = "";
		
		if ((isset($_POST['file_count'])) && (strlen(trim($_POST['file_count'])) > 0))
      $file_count = intval(trim(stripslashes(strip_tags($_POST['file_count']))));
    else
      $file_count = 0;
    
    if($backup_folder != '') {
      
      //$new_uploads_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
      $basedir = $this->upload_dir['basedir'];

      $ml_zip_file = $this->mlfp_exim_folder . DIRECTORY_SEPARATOR . $backup_folder . DIRECTORY_SEPARATOR . 'mlfp-data.zip';

      $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_CSV_DATA_TABLE;

      $percentage = (($last_file+1) / $file_count) * 100;

      $sql = "select * from $table order by id limit $last_file, 1";

      $row = $wpdb->get_row($sql);

      if($row) {
        
        $destination_file = $basedir . DIRECTORY_SEPARATOR . $row->attached_file;
                        
        if(!file_exists($destination_file)) {
          
          $zip = new ZipArchive;
          $res = $zip->open($ml_zip_file);
          if ($res === TRUE) {
            $zip->extractTo($basedir, array($row->attached_file));
            $zip->close();

            //$folder_path = get_user_meta($user_id, MAXG_SYNC_FOLDER_PATH, true);
            $new_attachment = $basedir . DIRECTORY_SEPARATOR . $row->attached_file;
            $folder_path = substr($new_attachment, 0, strrpos($new_attachment, '/'));        

            $new_file_title = preg_replace( '/\.[^.]+$/', '', basename($row->attached_file));

            $parent_folder = $this->get_new_folder_id($row->parent_id);

            $attach_id = $this->add_new_attachment($new_attachment, $parent_folder, $new_file_title, $this->seo_alt_text, $this->seo_file_title);
            $message = __('Importing: ','maxgalleria-media-library') . $row->attached_file;
                                    
            if(class_exists('MGMediaLibraryFoldersProS3') && 
              ($maxgalleria_media_library_pro_s3->license_status == S3_VALID || $maxgalleria_media_library_pro_s3->license_status == S3_FILE_COUNT_WARNING)) {
              
              $absolute_path = $new_attachment;
              $file_url = $this->get_file_url($new_attachment);
              $upload_folder_name = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
              $upload_length = strlen($upload_folder_name);
              $post_type = 'attachment';
                            
              if($maxgalleria_media_library_pro_s3->s3_active) {
                $location = $maxgalleria_media_library_pro_s3->get_location($file_url, $this->uploads_folder_name);
                $destination_location = $maxgalleria_media_library_pro_s3->get_destination_location($location);
                $destination_folder  = $maxgalleria_media_library_pro_s3->get_destination_folder($destination_location, $this->uploads_folder_name_length);
                $upload_result= $maxgalleria_media_library_pro_s3->upload_to_s3("attachment", $location, $absolute_path, $attach_id);

                if($maxgalleria_media_library_pro_s3->remove_from_local) {
                  if($upload_result['statusCode'] == '200')							
                    $maxgalleria_media_library_pro_s3->remove_media_file($absolute_path);										
                }	

                $metadata = wp_get_attachment_metadata($attach_id);

                if(isset($metadata['sizes'])) {
                  foreach($metadata['sizes'] as $thumbnail) {
                    $source_file = $this->get_absolute_path($this->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                    $upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3("attachment", $destination_location . '/' . $thumbnail['file'], $source_file, 0);
                    if($maxgalleria_media_library_pro_s3->remove_from_local) {
                      if($upload_result['statusCode'] == '200')							
                        $this->remove_media_file($source_file);										
                    }	
                  }
                  
                  if($maxgalleria_media_library_pro_s3->remove_from_local) {
                    foreach($metadata['sizes'] as $thumbnail) {
                      $source_file = $this->get_absolute_path($maxgalleria_media_library_pro->s3_addon->uploadsurl . $destination_folder . $thumbnail['file']);
                      $maxgalleria_media_library_pro_s3->remove_media_file($source_file);										
                    }
                  }									
                }
              }
            }                    
          }  
        } else {
          $message = $row->attached_file . __(' already exists','maxgalleria-media-library');          
        }

        $last_file++;

        $data = array('message' => $message, 'last_file' => $last_file, 'percentage' => $percentage );				
              
      } else {
        $data = array('message' => __('Files imported.','maxgalleria-media-library'), 'last_file' => null, 'percentage' => 100 );								
      }	
      //$last_file++;
      
      
    }
    
    //$data = array('message' => '', 'last_file' => $last_file, 'percentage' => $percentage );				
   
	  echo json_encode($data);
    die();
  }
       
    
}

$maxgalleria_media_library_pro = new MGMediaLibraryFoldersPro();

	function mlpp_display_files( $atts ) {

		global $wpdb, $maxgalleria_media_library_pro;

		extract(shortcode_atts(array(
			'folder_id' => '',
		), $atts));

		$upload_dir = wp_upload_dir(); 
		$output = "";
		$folder_table = $wpdb->prefix . "mgmlp_folders";

							$sql = "select ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file 
	from {$wpdb->prefix}posts 
	LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
	LEFT JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = {$wpdb->prefix}posts.ID) 
	where post_type = 'attachment' 
	and folder_id = '$folder_id'
	AND pm.meta_key = '_wp_attached_file' 
	order by post_title";
  
    //error_log($sql);

		$output .= "<style>" . PHP_EOL;
		$output .= "  ul.mlpp-file-list li {" . PHP_EOL;
		$output .= "    display: inline-block;" . PHP_EOL;
		$output .= "    float: left;" . PHP_EOL;
		$output .= "    list-style: outside none none;" . PHP_EOL;
		$output .= "    height: 222px;" . PHP_EOL;
		$output .= "  }" . PHP_EOL;	
		$output .= "  ul.mlpp-file-list li img {" . PHP_EOL;
		$output .= "		height: 135px;" . PHP_EOL;
		$output .= "		width: 135px;" . PHP_EOL;
		$output .= "		margin: 10px;" . PHP_EOL;	
		$output .= "	}" . PHP_EOL;
		$output .= "	ul.mlpp-file-list li p.title {" . PHP_EOL;
		$output .= "		text-align: center;" . PHP_EOL;
		$output .= "		word-wrap: break-word;" . PHP_EOL;
		$output .= "		width: 154px;" . PHP_EOL;
		$output .= "	}" . PHP_EOL;
		$output .= "</style>" . PHP_EOL;

		$output .= '<ul class="mlpp-file-list">' . PHP_EOL;
		$rows = $wpdb->get_results($sql);            
		if($rows) {
			foreach($rows as $row) {
				$thumbnail = wp_get_attachment_thumb_url($row->ID);
        //error_log($thumbnail);
				if($thumbnail == false) {
					//$thumbnail = MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file.jpg";
					$ext = pathinfo($row->attached_file, PATHINFO_EXTENSION);										
					$thumbnail = $maxgalleria_media_library_pro->get_file_thumbnail($ext);
				}  

				$file_ulr = $upload_dir['baseurl'] . "/" . $row->attached_file;

				$output .=  "<li><a href='$file_ulr' target='_blank'><img alt='$row->post_title' src='$thumbnail' /><p class='title'>$row->post_title</p></a></li>" . PHP_EOL;
			}      
		}
		$output .= '</ul>' . PHP_EOL;

    //error_log($output);
		return $output;

	} 

  add_shortcode('mlpp-display-files', 'mlpp_display_files');
  
  function mlfp_embed_file( $atts ) {
    
		extract(shortcode_atts(array(
      'filetype' => '',
			'url' => '',
			'type' => '',
			'width' => '',
			'height' => '',
      'align'  => '',
      'autoplay'  => '',
      'controls' => '',
      'loop' => '',
      'muted' => '',  
      'poster' => ''  
		), $atts));
    
    $output = '';
    $source = '';
    $default_text = '';
    $end_tag = '';
    $audio_or_video = false;
    
    //error_log("filetype $filetype");
        
    switch($filetype) {
     
      case 'ogg-audio':
      case 'wav':
      case 'oga':
      case 'mpeg':
      case 'mp3': //audio
        $output .= "<audio ";
        if($filetype == 'ogg-audio')
          $source .= "<source src=\"$url\" type=\"audio/ogg\">";
        else
          $source .= "<source src=\"$url\" type=\"audio/$filetype\">";
        $audio_or_video = true;
        $default_text = __('Your browser does not support the audio element.','maxgalleria-media-library');
        $end_tag = "</audio>";
        break;
        
      case 'ogv':
      case 'ogg':
      case 'webm':
      case 'mp4': //video
        $output .= "<video "; 
        $source .= "<source src=\"$url\" type=\"video/$filetype\">";
        $audio_or_video = true;
        $default_text = __('Your browser does not support the video element.','maxgalleria-media-library');
        $end_tag = "</video>";
        break;
              
      case 'pdf':
      default:  
        
        $output .= "<embed src=\"$url\" type=\"$type\" ";

        if(!empty($width))
          $output .= "width=\"$width\" ";

        if(!empty($height))
          $output .= "height=\"$height\" ";

        if(!empty($align))
          $output .= "align=\"$align\" ";
        
        break;
      
    }
    
    if($audio_or_video) {
      
      if(!empty($autoplay))
        $output .= "autoplay ";
      
      if(!empty($controls))
        $output .= "controls ";
      
      if(!empty($loop))
        $output .= "loop ";
      
      if(!empty($muted))
        $output .= "muted ";
      
      if(!empty($preload)) 
        $output .= "preload=\"$preload\" ";
            
      if(!empty($poster)) 
        $output .= "poster=\"$poster\" ";
      
      $output .= ">\r\n  " . $source . "\r\n  " . $default_text . "\r\n" . $end_tag;
    } else {
      $output .= ">";
    }  
        
    //error_log($output);
		return $output;
    
  }

  add_shortcode('mlfp-embed-file', 'mlfp_embed_file');
  
  function disable_mlfpr_plugin_updates( $value ) {
    if(isset($value->response)) {
      unset( $value->response['media-library-plus-pro/mlf-pro-reset.php'] );
    }  
    return $value;
  }
  add_filter( 'site_transient_update_plugins', 'disable_mlfpr_plugin_updates' );
  
  $enable_upload = get_option(MAXGALLERIA_MLP_UPLOAD, 'off');
  if($enable_upload == 'on') {
    add_action('print_scripts', 'enqueue_sc_print_scripts', 10);      
  }
  
  function enqueue_sc_print_scripts() {
    
    //error_log("enqueue_sc_print_scripts");
    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');  
    wp_register_script('mlfp-upload', MICSA_PP_URL . '/js/upload.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('mlfp-upload');
    
  }
  
  function mlfp_check_folder_id($folder_id) {
    
    global $wpdb;
    $retval = true;
    
    $sql = "select meta_value as attached_file
from {$wpdb->prefix}postmeta 
where post_id = $folder_id
AND meta_key = '_wp_attached_file'";
				
    $row = $wpdb->get_row($sql);
    
    if($row == null) {
      $retval = false;      
    } 
    return $retval;
  }
       
  function mlfp_image_file_upload( $atts ) {
    
    $output = '';
    
		extract(shortcode_atts(array(
			'folder_id' => '',
			'display_image' => '0',
			'file_types' => '',
		), $atts));
    
    if(is_user_logged_in()) {

      $title_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT);
      $alt_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);
      $enable_upload = get_option(MAXGALLERIA_MLP_UPLOAD, 'off');
      
      if(mlfp_check_folder_id($folder_id) == false) {
        $output .= "<p>" . __('The destination folder does not exist. Please verify the correct folder ID.','maxgalleria-media-library') . "</p>" .PHP_EOL;
        return $output;        
      }
      
      if($enable_upload == 'on') {

        $output .= "<style>" . PHP_EOL;
        $output .= "#front-end-upload-spinner {" . PHP_EOL;
        $output .= "  display: none;" . PHP_EOL;
        $output .= "}" . PHP_EOL;    
        $output .= ".upload-image-container {" . PHP_EOL;
        $output .= "  margin-top: 20px;" . PHP_EOL;
        $output .= "}" . PHP_EOL;    
        $output .= "#file_to_upload, #front-end-upload {" . PHP_EOL;
        $output .= "  cursor: pointer;" . PHP_EOL;
        $output .= "}" . PHP_EOL;    
        $output .= "</style>" . PHP_EOL;


        $output .= "<div class='mlfp-upload-file-container'>" . PHP_EOL;
        $output .= "  <input type='hidden' name='mlfp-fe-upload-image' id='mlfp-fe-upload-image' value='' maxlength='240'>" . PHP_EOL;    
        $output .= "  <input type='hidden' id='mlfp-title-text' value='$title_text'>" . PHP_EOL;    
        $output .= "  <input type='hidden' id='mlfp-alt-text' value='$alt_text'>" . PHP_EOL;    
        $output .= "  <input type='hidden' id='mlfp-folder-id' value='$folder_id'>" . PHP_EOL;        
        $output .= "  <input type='hidden' id='display-image' value='$display_image'>" . PHP_EOL;        

        $output .= "  <div>" . PHP_EOL;  
        //error_log("file_types $file_types");
        if(strlen($file_types) > 0)
          $output .= "    <input type='file' name='file_to_upload' id='file_to_upload' accept='$file_types'>" . PHP_EOL;  
        else
          $output .= "    <input type='file' name='file_to_upload' id='file_to_upload'>" . PHP_EOL;  
        $output .= "    <input type='button' value='Upload' id='front-end-upload' name='front-end-upload'>" . PHP_EOL;
        $output .= "    <img class='sf-spiner' id='front-end-upload-spinner' alt='loading spinner' src='" . MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . "/images/file-loading.gif' height='16' width='16' />" . PHP_EOL;   
        $output .= "  </div>" . PHP_EOL;      
        $output .= "  <div class='upload-image-container'>" . PHP_EOL;
        $output .= "    <img id='front-end-upload-image' src='' />" . PHP_EOL;
        $output .= "  </div>" . PHP_EOL;
        $output .= "  <p id='upload-message'></p>" . PHP_EOL;    
        $output .= "</div>" . PHP_EOL;

        $output .= "<script>" . PHP_EOL;
        $output .= "jQuery(document).ready(function(){" . PHP_EOL;

        $output .= "  jQuery(document).on('click', '#front-end-upload', function (e) {" . PHP_EOL;
        $output .= "    e.stopPropagation();" . PHP_EOL;

        //$output .= "    console.log('front-end-upload');" . PHP_EOL;

        $output .= "    var file_data = jQuery('#file_to_upload').prop('files')[0];" . PHP_EOL;
        $output .= "    console.log('file_data',file_data);" . PHP_EOL;
        $output .= "    if(file_data == 'undefined' || file_data == undefined || file_data == '') {" . PHP_EOL;
        $output .= "      alert('" .  __('No file was selected.','maxgalleria-media-library') . "');" . PHP_EOL;
        $output .= "      return false;" . PHP_EOL;
        $output .= "    }" . PHP_EOL;

        $output .= "    var title_text = jQuery('#mlfp-title-text').val();" . PHP_EOL;
        $output .= "    var alt_text = jQuery('#mlfp-alt-text').val();" . PHP_EOL;
        $output .= "    var folder_id = jQuery('#mlfp-folder-id').val();" . PHP_EOL;
        $output .= "    var display_image = parseInt(jQuery('#display-image').val());" . PHP_EOL;
        $output .= "    var notify = jQuery('#notify').val();" . PHP_EOL;

        $output .= "    if(folder_id == 'undefined' || folder_id == undefined || folder_id == '' || folder_id == '0') {" . PHP_EOL;
        $output .= "      alert('" .  __('This shortcde requires a folder ID.','maxgalleria-media-library') . "');" . PHP_EOL;
        $output .= "      return false;" . PHP_EOL;
        $output .= "    }" . PHP_EOL;

        $output .= "    var form_data = new FormData();" . PHP_EOL;

        $output .= "    form_data.append('file', file_data);" . PHP_EOL;
        $output .= "    form_data.append('action', 'frontend_upload_attachment');" . PHP_EOL;
        $output .= "    form_data.append('title_text', title_text);" . PHP_EOL;
        $output .= "    form_data.append('alt_text', alt_text);" . PHP_EOL;
        $output .= "    form_data.append('folder_id', folder_id);" . PHP_EOL;        
        $output .= "    form_data.append('notify', notify);" . PHP_EOL;
        $output .= "    form_data.append('display_files', '0');" . PHP_EOL;
        $output .= "    form_data.append('nonce', '" . wp_create_nonce(MAXGALLERIA_MEDIA_LIBRARY_PRO_NONCE) . "');" . PHP_EOL;
        $output .= "    jQuery('#front-end-upload-spinner').css('display','inline-block');" . PHP_EOL;

        $output .= "    jQuery.ajax({" . PHP_EOL;
        $output .= "        url: '" . admin_url( 'admin-ajax.php' ) . "'," . PHP_EOL;
        $output .= "        dataType: 'json'," . PHP_EOL;  
        $output .= "        cache: false," . PHP_EOL;
        $output .= "        contentType: false," . PHP_EOL;
        $output .= "        processData: false," . PHP_EOL;
        $output .= "        data: form_data," . PHP_EOL;            
        $output .= "        type: 'post'," . PHP_EOL;
        
        $output .= "        success: function (data) {" . PHP_EOL;               
        $output .= "          jQuery('#front-end-upload-spinner').hide();" . PHP_EOL;
        $output .= "          jQuery('#file_to_upload').val('');" . PHP_EOL;
        $output .= "          console.log(data);" . PHP_EOL;
        $output .= "          jQuery('#mlfp-fe-upload-image').val(data.file_url);" . PHP_EOL;
        $output .= "          if(display_image == 1) {" . PHP_EOL;
        $output .= "            if(data.file_url.length > 0 && data.image == true) {" . PHP_EOL;
        $output .= "              jQuery('#front-end-upload-image').attr('src', data.file_url);" . PHP_EOL;
        $output .= "              jQuery('#front-end-upload-image').show();" . PHP_EOL;
        $output .= "            }" . PHP_EOL;
        $output .= "          } else {" . PHP_EOL;    
        $output .= "            const uploaded_file = basename(data.file_url);" . PHP_EOL;
        $output .= "            const upload_msg = '" . __(' was uploaded successfully.','maxgalleria-media-library') . "'" . PHP_EOL;    
        $output .= "            jQuery('#upload-message').html(uploaded_file + upload_msg);" . PHP_EOL;
        $output .= "          }" . PHP_EOL;
        $output .= "        }," . PHP_EOL;
        
        $output .= "        error: function (err) {" . PHP_EOL;
        $output .= "          jQuery('#front-end-upload-spinner').hide();" . PHP_EOL;
        $output .= "          alert('" .  __('The file could not be uploaded.','maxgalleria-media-library') . "');" . PHP_EOL;
        $output .= "        }" . PHP_EOL;
        $output .= "      });" . PHP_EOL;
        $output .= "    });" . PHP_EOL;
        $output .= "});" . PHP_EOL;
        $output .= "function basename(path) {" . PHP_EOL;
        $output .= "  return path.split(/[\\/]/).pop();" . PHP_EOL;
        $output .= "}" . PHP_EOL;    
        $output .= "</script>" . PHP_EOL;
      } else {
        $output .= "<p style='text-align:center'>" . __('Please enable front end uploading in Media Library Folders Pro Settings to use the front end upload shortcode.','maxgalleria-media-library') . "</p>"  . PHP_EOL;
      }
            
    }
            
    return $output;
  }  
  add_shortcode('mlfp-image-file-upload', 'mlfp_image_file_upload');
  
  
  function mlfp_search_query_vars($qvars) {
    $qvars[] = 'display';
    return $qvars;    
  }

  add_filter('query_vars', 'mlfp_search_query_vars');
        
?>