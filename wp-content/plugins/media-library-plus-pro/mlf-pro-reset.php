<?php
/*
Plugin Name: Media Library Folders Pro for WordPress Reset
Plugin URI: http://maxgalleria.com
Description: Plugin for reseting Media Library Folders Pro. This plugin is a part of Media Library Folders Pro. Please do not delete it as doing so will delete Media Library Folders Pro.
Author: Max Foundry
Author URI: http://maxfoundry.com
Version: 8.0.4
Copyright 2015-2022 Max Foundry, LLC (http://maxfoundry.com)
Text Domain: mlp-reset

*/

if(!defined("MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE"))
  define("MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE", "mgmlp_folders");

if(!defined("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID"))
  define("MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID", "mgmlp_upload_folder_id");

define('MG_MEDIA_LIBRARY_RESET_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
define('MG_MEDIA_LIBRARY_RESET_PLUGIN_URL', plugin_dir_url('') . MG_MEDIA_LIBRARY_RESET_PLUGIN_NAME);
define("MG_RESET_NONCE", "mlfr_nonce");

if(!defined('MLFP_EXIM_FOLDER_LOCATION'))
  define('MLFP_EXIM_FOLDER_LOCATION', 'mlfp_exim_folder');

if(!defined('WPMF_TAXO'))
  define('WPMF_TAXO', 'wpmf-category');

if(!defined('MAXGALLERIA_WPMF'))
  define("MAXGALLERIA_WPMF", "mlfp-wpmf-integration");

if(!defined('MAXGALLERIA_REMOVE_FT'))
  define("MAXGALLERIA_REMOVE_FT", "mlf_remove_ft");		

if(!defined("MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL"))
  define('MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL', rtrim(plugin_dir_url(__FILE__), '/'));

add_action('wp_ajax_nopriv_mlfp_clean_database', 'mlfp_clean_database');
add_action('wp_ajax_mlfp_clean_database', 'mlfp_clean_database');				

add_action('wp_ajax_nopriv_mlfr_remove_tables', 'mlfr_remove_tables');
add_action('wp_ajax_mlfr_remove_tables', 'mlfr_remove_tables');

function mlp_reset_menu() {
  add_menu_page(__('Media Library Folders Pro Reset','mlp-reset'), __('Media Library Folders Pro Reset','mlp-reset'), 'manage_options', 'mlp-reset', 'mlp_reset' );
  add_submenu_page('mlp-reset', __('Display Attachment URLs','mlp-reset'), __('Display Attachment URLs','mlp-reset'), 'manage_options', 'mlpr-show-attachments', 'mlpr_show_attachments');
  add_submenu_page('mlp-reset', __('Display Folder Data','mlp-reset'), __('Display Folder Data','mlp-reset'), 'manage_options', 'mlpr-show-folders', 'mlpr_show_folders');
  add_submenu_page('mlp-reset', __('Check for Folders Without Parent IDs','mlp-reset'), __('Check for Folders Without Parent IDs','mlp-reset'), 'manage_options', 'mlpr-folders-no-ids', 'mlpr_folders_no_ids');
  //add_submenu_page('mlp-reset', 'Remove User Access Data', 'Remove User Access Data', 'manage_options', 'mlpr-remove-ua-data', 'mlpr_remove_ua_data');
  add_submenu_page('mlp-reset', __('Add MLFP Database Tables','mlp-reset'), __('Add MLFP Database Tables','mlp-reset'), 'manage_options', 'mlpr-add-tables', 'mlpr_add_tables');
  add_submenu_page('mlp-reset', __('Remove Other MLFP Database Tables','mlp-reset'), __('Remove Other MLFP Database Tables','mlp-reset'), 'manage_options', 'mlpr-remove-tables', 'mlpr_remove_tables');
  //add_submenu_page('mlp-reset', __('Reset Database','mlp-reset'), __('Reset Database','mlp-reset'), 'manage_options', 'clean_database', 'clean_database');
  add_submenu_page('mlp-reset', __('Reset Media Library Folders Data','mlp-reset'), __('Reset Media Library Folders Data','mlp-reset'), 'manage_options', 'data-reset', 'data_reset');
}
add_action('admin_menu', 'mlp_reset_menu');

function load_mlfr_textdomain() {
  load_plugin_textdomain('mlp-reset', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'load_mlfr_textdomain');

function enqueue_mlfpr_admin_print_styles() {		
  wp_enqueue_style('mlfp8', MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL . '/css/mlfp.css');				
}

add_action('admin_print_styles', 'enqueue_mlfpr_admin_print_styles');

function mlp_reset() {

	echo '<h3>' . __('WordPress Media Library Folders Pro Reset Instructions','mlp-reset') . '</h3>';
  echo '<h4>' . __('If you need to rescan your database, please deactivate the Media Library Folders Pro plugin and then click WordPress Media Library Folders Pro Reset->Reset Database to erase the folder data. Then deactivate WordPress Media Library Folders Pro Reset and reactivate Media Library Folders Pro which will perform a fresh scan of your database.','mlp-reset') . '</h4>';
  
}

function data_reset() {
    
  ?>
  <style>
    #ajaxloader {
      top: 0 !important;
    }

  </style>

  <h2><?php esc_html_e('Media Library Folders Pro Data Reset','mlp-reset') ?></h2>
  
  <p><?php esc_html_e('To reset the folder data used by Media Library Folders Pro, deactivate Media Library Folders Pro and click the Reset Folder Data button. Once completed, reactivate Media Library Folders Pro.','mlp-reset') ?></p>
  
  <a id="mlfp-clean-database" class="button">Reset Folder Data</a>
  <div id="alwrap">
    <div style="display:none" id="ajaxloader"></div>
  </div>

  <p id="reset_message"></p>
  
	<script>
	jQuery(document).ready(function(){
    
    jQuery(document).on("click", "#mlfp-clean-database", function (e) {
			      
			jQuery("#reset_message").html('');			
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: 'mlfp_clean_database', nonce: '<?php echo wp_create_nonce(MG_RESET_NONCE) ?>' },
        url : '<?php echo admin_url('admin-ajax.php') ?>',
        dataType: "html",
        success: function (data) {
					jQuery("#reset_message").html(data);						
          jQuery("#ajaxloader").hide();
					
        },
        error: function (err)
          { 
            jQuery("#ajaxloader").hide();
            alert(err.responseText);
          }
      });                
    });	
    
    
	});  
  </script>   
  

  <?php
  
}

function mlfp_clean_database() {  
    global $wpdb;    
    $message = '';
    
    if(!wp_verify_nonce($_POST['nonce'], MG_RESET_NONCE)) {
      exit(esc_html__('Missing nonce! Please refresh this page.','mlp-reset'));
    }    
        
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_name'";
    $wpdb->query($sql);
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_id'";
    $wpdb->query($sql);
		
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_database_checked'";
    $wpdb->query($sql);
		
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_postmeta_updated'";
    $wpdb->query($sql);
    
    $message .=  __('Deleting mgmlp_folders','mlp-reset') . '<br>';
    
    $sql = "TRUNCATE TABLE $wpdb->prefix" . "mgmlp_folders";
    $wpdb->query($sql);
    
    $sql = "DROP TABLE $wpdb->prefix" . "mgmlp_folders";    
    $wpdb->query($sql);
		
    $sql = "select ID from {$wpdb->prefix}posts where post_type = 'mgmlp_media_folder'";
		
    $rows = $wpdb->get_results($sql);
		if($rows) {
      foreach($rows as $row) {
				delete_post_meta($row->ID, '_wp_attached_file');				
			}
		}
		    
    $message .=  __('Removing mgmlp_media_folder posts','mlp-reset') . '<br>';
    $sql = "delete from $wpdb->prefix" . "posts where post_type = 'mgmlp_media_folder'";
    $wpdb->query($sql);
    
    update_option('uploads_use_yearmonth_folders', 1);    
        
    $message .=  __('Done. You can now reactivate Media Library Folders Pro.','mlp-reset') . '<br>';
  
    echo $message;
    
    die();
}

function mlfpr_table_exist($table) {

  global $wpdb;

  $sql = "SHOW TABLES LIKE '{$table}'";
  
  $rows = $wpdb->get_results($sql);
  
  if($rows) 
    return true;
  else
    return false;
}

function mlpr_show_attachments () {
  global $wpdb;
  
  if(!mlfpr_table_exist($wpdb->prefix . 'mgmlp_folders')) {
    echo __("<p>The mgmlp_folders table does not exists. Please activate Media Library Folders Pro to create the table.</p>",'mlp-reset'); 
    return;
  }
                    
  $sql = "select count(*) from {$wpdb->prefix}posts where post_type = 'attachment' ";
  
  $count = $wpdb->get_var($sql);  
	
  $uploads_path = wp_upload_dir();
  //$sql = "select ID, guid from $wpdb->prefix" . "posts where post_type = 'attachment' order by ID";
	
  $sql = "SELECT ID, pm.meta_value as attached_file, folder_id
FROM {$wpdb->prefix}posts
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
LEFT JOIN {$wpdb->prefix}mgmlp_folders ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}mgmlp_folders.post_id)
WHERE post_type = 'attachment' 
AND pm.meta_key = '_wp_attached_file'
ORDER by folder_id";
	
  //echo $sql;
	
	echo '<h2>' . __('Attachment URLs','mlp-reset') . '</h2>';
  
  echo '<p>' . __('Number of attachments: ','mlp-reset') . $count . '</p>';


  $rows = $wpdb->get_results($sql);
	?>
	<table>
		<tr>
			<th><?php _e('Attachment ID','mlp-reset'); ?></th>
			<th><?php _e('Attachment URL','mlp-reset'); ?></th>
			<th><?php _e('Folder ID','mlp-reset'); ?></th>
		</tr>	
    
  <?php  
  
  foreach($rows as $row) {
		$image_location = $uploads_path['baseurl'] . "/" . $row->attached_file;
	  ?>
		<tr>
			<td><?php echo $row->ID; ?></td>	
			<td><?php echo $image_location; ?></td>	
			<td><?php echo $row->folder_id; ?></td>	
		</tr>
    <?php				
  }    
	?>
	</table>
  <?php
}

function mlpr_show_folders() {
  global $wpdb;
  
  if(!mlfpr_table_exist($wpdb->prefix . 'mgmlp_folders')) {
    echo __("<p>The mgmlp_folders table does not exists. Please activate Media Library Folders Pro to create the table.</p>",'mlp-reset'); 
    return;
  }
    
  $sql = "select count(*) from {$wpdb->prefix}posts where post_type = 'mgmlp_media_folder' ";
  
  $count = $wpdb->get_var($sql);    
	
	echo '<h2>' . __('Folder URLs','mlp-reset') . '</h2>';
  
  $upload_dir = wp_upload_dir();  
  
  $upload_dir1 = $upload_dir['basedir'];
  
  echo __('Uploads folder: ','mlp-reset') . $upload_dir1 . '<br>';
        
  echo __('Uploads URL ','mlp-reset') . $upload_dir['baseurl'] . '<br>';
  
  echo __('Number of folders: ','mlp-reset') . $count . '<br><br>';

  $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
            	
  $sql = "select distinct ID, post_title, $folder_table.folder_id, pm.meta_value as attached_file
from $wpdb->prefix" . "posts
LEFT JOIN $folder_table ON ($wpdb->prefix" . "posts.ID = $folder_table.post_id)
LEFT JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
where post_type = 'mgmlp_media_folder' 
order by ID";
	
  //echo $sql . "<br>";
	  
  $rows = $wpdb->get_results($sql);
	
	?>
	<table>
		<tr>
			<th><?php _e('Folder ID','mlp-reset'); ?></th>
			<th><?php _e('Folder Name','mlp-reset'); ?></th>
			<th><?php _e('Folder URL','mlp-reset'); ?></th>
			<th><?php _e('Parent ID','mlp-reset'); ?></th>
		</tr>	
    
  <?php  
  foreach($rows as $row) {
		$image_location = $upload_dir['baseurl'] . "/" . $row->attached_file;
	  ?>
		<tr>
			<td><?php echo $row->ID; ?></td>	
			<td><?php echo $row->post_title; ?></td>	
			<td><?php echo $image_location; ?></td>	
			<td>
        <?php 
          if($row->folder_id !== null)
            echo $row->folder_id; 
          else 
            echo __('No folder ID found','mlp-reset');        
            //echo "Missing folder ID <a data-id='{$row->ID}' class='button primary-button remove-record' style='line-height: 1.3; min-height:auto'>Remove From Database</a>";            
        ?>
      </td>	
		</tr>
    <?php		
  }	
	?>
	</table>
	<script>
	jQuery(document).ready(function(){
    
		//jQuery(".remove-record").click(function(){
    jQuery(document).on("click",".remove-record",function(){
      var id = jQuery(this).attr('data-id');
      console.log('id',id);
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlfr_remove_null_records", 
          id: id,
          nonce: '<?php echo wp_create_nonce(MG_RESET_NONCE); ?>' },
        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
        dataType: "html",
        success: function (data) {
          location.reload(true);
        },
        error: function (err) { 
          alert(err.responseText);
        }
      });  

      
	  });  
        
	});  
  </script>   

  <br><br>
  <?php
		  	
  echo "<br><br>$folder_table<br><br>";
  
  $sql = "select distinct post_id, folder_id from $folder_table order by post_id";
  
  $rows = $wpdb->get_results($sql);
  
  foreach($rows as $row) {
    echo "$row->post_id $row->folder_id<br>";
  }
  	
}

function mlpr_remove_ua_data() {
  
    global $wpdb;    
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mlfp_use_user_roles'";
    $wpdb->query($sql);
            
    $sql = "DROP TABLE $wpdb->prefix" . "mgmlp_userrole_permissions";    
    $wpdb->query($sql);
  
    echo __('The mgmlp_userrole_permissions table was removed from the database.','mlp-reset');
  
}

function mlpr_remove_tables() {
  
  ?>
  
	<h3><?php _e('Remove Other MLFP Database Tables & Settings','mlp-reset'); ?></h3>
  
  <p><?php _e('To remove the auxiliary tables added by Media Library Folders Pro or Media Library Folders Pro S3, in order to completely uninstall either or both plugins, select the tables to be remove and click the \'Remove Selected Tables\' button. All data stored in the selected tables will be lost. Only delete the tables in the plugins to be removed from the site.','mlp-reset'); ?></p>
  
  <div><input type="checkbox" id="mlfr-userrole" name="userrole" value="" ><label for="userrole"><?php _e('User Role Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-thumbnail-management" name="thumbnail-management" value="" ><label for="thumbnail-management"><?php _e('Thumbnail Management Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-backup-files" name="backup-files" value="" ><label for="backup-files"><?php _e('Export/Import Tables and All Backup Files','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-purge-files" name="purge-files" value="" ><label for="purge-files"><?php _e('Purge Files Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-remove-wpmf" name="mlfr-remove-wpmf" value="" ><label for="mlfr-remove-wpmf"><?php _e('Turn off WPMF integration and clear WPMF folder data','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-update-log" name="update-log" value="" ><label for="update-log"><?php _e('S3 Update Log Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-files-to-sync" name="files-to-sync" value="" ><label for="files-to-sync"><?php _e('S3 Files to Sync Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-problem-files" name="problem-files" value="" ><label for="problem-files"><?php _e('S3 Problem Files Table','mlp-reset'); ?></label></div>
  <div><input type="checkbox" id="mlfr-s3-settings" name="s3_settings" value="" ><label for="s3_settings"><?php _e('S3 Option Settings','mlp-reset'); ?></label></div>
  
  <p>
    <a id="remove-tables" class="button-primary"><?php _e('Remove Selected Tables','mlp-reset'); ?></a>
    <img id="mlfr-ajaxloader" alt="loading GIF" src="<?php echo MG_MEDIA_LIBRARY_RESET_PLUGIN_URL; ?>/images/ajax-loader.gif" style="position: relative;top: 10px;left: 10px; display:none;" width="32" height="32">    
  </p>
  <p id="return-message"></p>
  
  <script>
	jQuery(document).ready(function(){
    
    //jQuery("#remove-tables").click(function () {
    jQuery(document).on("click","#remove-tables",function(){
      
      var userrole = jQuery('#mlfr-userrole:checkbox:checked').length > 0;
      var thumbnail_management = jQuery('#mlfr-thumbnail-management:checkbox:checked').length > 0;
      var update_log = jQuery('#mlfr-update-log:checkbox:checked').length > 0;
      var files_to_sync = jQuery('#mlfr-files-to-sync:checkbox:checked').length > 0;
      var problem_files = jQuery('#mlfr-problem-files:checkbox:checked').length > 0;
      var backup_files = jQuery('#mlfr-backup-files:checkbox:checked').length > 0;
      var mlfr_remove_wpmf = jQuery('#mlfr-remove-wpmf:checkbox:checked').length > 0;
      var mlfr_purge_files = jQuery('#mlfr-purge-files:checkbox:checked').length > 0;
      var mlfr_s3_settings = jQuery('#mlfr-s3-settings:checkbox:checked').length > 0;
      
      if( userrole == false &&
          thumbnail_management == false &&
          update_log == false &&
          files_to_sync == false &&
          backup_files == false &&
          mlfr_purge_files == false &&
          mlfr_remove_wpmf == false &&
          mlfr_s3_settings == false &&
          problem_files == false ) {

          jQuery("#return-message").html("<?php _e('No items were selected.','mlp-reset'); ?>");
          jQuery("#mlfr-ajaxloader").hide();
          return false;
      }
      

      if(confirm("<?php _e('Are you sure you want to remove the selected tables, data and files?','mlp-reset'); ?>")) {
        
        jQuery("#return-message").html("");
        jQuery("#mlfr-ajaxloader").show();
                                
        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mlfr_remove_tables", 
            userrole: userrole, 
            thumbnail_management: thumbnail_management,
            update_log: update_log,
            files_to_sync: files_to_sync,
            problem_files: problem_files,
            backup_files: backup_files,
            mlfr_remove_wpmf: mlfr_remove_wpmf,
            mlfr_purge_files: mlfr_purge_files,
            mlfr_s3_settings: mlfr_s3_settings,
            nonce: '<?php echo wp_create_nonce(MG_RESET_NONCE); ?>' },
          url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
          dataType: "html",
          success: function (data) {
            jQuery("#return-message").html(data);
            
						jQuery("#mlfr-userrole").prop('checked', false);
						jQuery("#mlfr-thumbnail-management").prop('checked', false);
						jQuery("#mlfr-update-log").prop('checked', false);
						jQuery("#mlfr-files-to-sync").prop('checked', false);
						jQuery("#mlfr-problem-files").prop('checked', false);
						jQuery("#mlfr-backup-files").prop('checked', false);
						jQuery("#mlfr-remove-wpmf").prop('checked', false);
						jQuery("#mlfr-purge-files").prop('checked', false);  
						jQuery("#mlfr_s3_settings").prop('checked', false);  
                        
            
            jQuery("#mlfr-ajaxloader").hide();
          },
          error: function (err) { 
            jQuery("#mlfr-ajaxloader").hide();
            alert(err.responseText);
          }
        });  
        
        
      }     
    
	  });  
	
	});  
  </script>  

  <?php
    
}

function mlfr_remove_tables () {
      
  global $wpdb;
    
  if ( !wp_verify_nonce( $_POST['nonce'], MG_RESET_NONCE)) {
    exit(__('missing nonce! Please refresh this page.','mlp-reset'));
  }
  
  if ((isset($_POST['userrole'])) && (strlen(trim($_POST['userrole'])) > 0))
    $userrole = trim(stripslashes(strip_tags($_POST['userrole'])));
  
  if ((isset($_POST['thumbnail_management'])) && (strlen(trim($_POST['thumbnail_management'])) > 0))
    $thumbnail_management = trim(stripslashes(strip_tags($_POST['thumbnail_management'])));
  
  if ((isset($_POST['update_log'])) && (strlen(trim($_POST['update_log'])) > 0))
    $update_log = trim(stripslashes(strip_tags($_POST['update_log'])));
  
  if ((isset($_POST['files_to_sync'])) && (strlen(trim($_POST['files_to_sync'])) > 0))
    $files_to_sync = trim(stripslashes(strip_tags($_POST['files_to_sync'])));
  
  if ((isset($_POST['problem_files'])) && (strlen(trim($_POST['problem_files'])) > 0))
    $problem_files = trim(stripslashes(strip_tags($_POST['problem_files'])));
  
  if ((isset($_POST['backup_files'])) && (strlen(trim($_POST['backup_files'])) > 0))
    $backup_files = trim(stripslashes(strip_tags($_POST['backup_files'])));
    
  if ((isset($_POST['mlfr_purge_files'])) && (strlen(trim($_POST['mlfr_purge_files'])) > 0))
    $mlfr_purge_files = trim(stripslashes(strip_tags($_POST['mlfr_purge_files'])));
  
  if ((isset($_POST['mlfr_remove_wpmf'])) && (strlen(trim($_POST['mlfr_remove_wpmf'])) > 0))
    $mlfr_remove_wpmf = trim(stripslashes(strip_tags($_POST['mlfr_remove_wpmf'])));
  
  if ((isset($_POST['mlfr_s3_settings'])) && (strlen(trim($_POST['mlfr_s3_settings'])) > 0))
    $mlfr_s3_settings = trim(stripslashes(strip_tags($_POST['mlfr_s3_settings'])));
    
  if($userrole == 'true')
    mlfr_remove_db_table("mgmlp_userrole_permissions");
  
  if($thumbnail_management == 'true')
    mlfr_remove_db_table("mgmlp_thumbnails");
  
  if($update_log == 'true')
    mlfr_remove_db_table("mlfp_s3_update_log");
  
  if($files_to_sync == 'true')
    mlfr_remove_db_table("mlfp_files_to_sync");
  
  if($problem_files == 'true')
    mlfr_remove_db_table("mlfp_problem_files");
  
  if($backup_files == 'true') {
    mlfr_remove_db_table("mgmlp_csv_data");
    mlfr_remove_db_table("mgmlp_import_folders");        
    $mlfp_exim_folder = get_option(MLFP_EXIM_FOLDER_LOCATION, '');
    mlfp_delete_backups($mlfp_exim_folder);        
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mlfp_exim_folder'";
    $wpdb->query($sql);
    
  if($mlfr_purge_files  == 'true') 
    mlfr_remove_db_table("mgmlp_file_purge");

  }
  
  if($mlfr_remove_wpmf == 'true') {
    
    delete_option('MAXGALLERIA_WPMF');
		update_option(MAXGALLERIA_REMOVE_FT, 'off', true);
        
    $terms = get_terms( WPMF_TAXO, array( 'fields' => 'ids', 'hide_empty' => false ) );
    foreach ( $terms as $value ) {
      wp_delete_term( $value, WPMF_TAXO );
    }

    $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    $sql = "UPDATE $table SET term_id = NULL WHERE term_id is not null";
    $wpdb->query($sql);
    
  }  
  
  if($mlfr_s3_settings == true) {
    delete_option('mlfp-license-status');
    delete_option('mlfp-cloud-service');
    delete_option('mlfp-license-limit');
    delete_option('mg_edd_s3_license_status');
    delete_option('mlfp-s3-use-s3');
    delete_option('mlfp-s3-bucket');
    delete_option('mlfp-s3-region');
    delete_option('mlfp-s3-files-on-s3');
    delete_option('mlfp-license-check');
    delete_option('mlfp-license-update');
    delete_option('mlfp-s3-remove_from_s3');
    delete_option('mlfp-cloud-sync-index');
    delete_option('mlfp-folder-sync-index');
  }

  echo __('The selected tables, data or settings have been deleted.','mlp-reset');
  
  die();
}

function mlfr_remove_db_table ($table) {
  
  global $wpdb;
  
  $table_name = $wpdb->prefix . $table; 
  
  $sql = "DROP TABLE $table_name";
  //error_log($sql);
  $wpdb->query($sql);

}

function get_parent_by_name($sub_folder) {

  global $wpdb;

  $sql = "SELECT post_id FROM {$wpdb->prefix}postmeta where meta_key = '_wp_attached_file' and `meta_value` = '$sub_folder'";

  return $wpdb->get_var($sql);
}

function add_new_folder_parent($record_id, $parent_folder) {

  global $wpdb;    
  $table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;

  $new_record = array( 
    'post_id'   => $record_id, 
    'folder_id' => $parent_folder 
  );

  $wpdb->insert( $table, $new_record );

}

function mlpr_folders_no_ids() {
  
  global $wpdb;
  
  echo '<p>' . __('The following files with missing folder IDs were found:','mlp-reset') . '</p>' . PHP_EOL;
  
  $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );

  $folders = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
  
  $sql = "SELECT ID, pm.meta_value AS attached_file FROM {$wpdb->prefix}posts
 LEFT JOIN $folders ON {$wpdb->prefix}posts.ID = {$folders}.post_id
 JOIN {$wpdb->prefix}postmeta AS pm ON pm.post_id = {$wpdb->prefix}posts.ID
 WHERE post_type = 'attachment' 
 AND folder_id IS NULL
 AND pm.meta_key = '_wp_attached_file' limit 0, 500";
 
 //error_log($sql);
  
  $rows = $wpdb->get_results($sql);
  if($rows) {
    echo "<p>The following files with missing folder IDs:</p>" . PHP_EOL;
    echo "<ul>" . PHP_EOL;
    foreach($rows as $row) {
      // get the parent ID
      $folder_path = dirname($row->attached_file);
      if($folder_path != "")
        $folder_id = get_parent_by_name($folder_path);
      else
        $folder_id = $uploads_folder_id;
      if($folder_id !== NULL) {
        // if parent ID is found
        add_new_folder_parent($row->ID, $folder_id);
        echo "<li>{$row->attached_file} Fixed</li>" . PHP_EOL;
      } else {
        add_new_folder_parent($row->ID, $uploads_folder_id);
        echo "<li>{$row->attached_file} Fixed</li>" . PHP_EOL;
        //echo "<li>{$row->attached_file} Parent folder not found.</li>" . PHP_EOL;        
      }  
    }
    echo "</ul>" . PHP_EOL;
  } else {
    echo '<p>' . __('No files with missing folder IDs were found.','mlp-reset') . '</p>' . PHP_EOL;
  }  
}

function mlfr_remove_null_records() {
  
  global $wpdb;
  
  if ( !wp_verify_nonce( $_POST['nonce'], MG_RESET_NONCE)) {
    exit(__('missing nonce! Please refresh this page.','mlp-reset'));
  }
    
  if ((isset($_POST['id'])) && (strlen(trim($_POST['id'])) > 0))
    $id = trim(stripslashes(strip_tags($_POST['id'])));
  else
    $id = "0";
  
  //error_log("id $id");
  
  if($id != "0") {
  
    $post_table = $wpdb->prefix . "posts"; 
    $postmeta_table = $wpdb->prefix . "postmeta"; 

    $where = array('post_id' => $id);

    $wpdb->delete($postmeta_table, $where);

    $where = array('ID' => $id);

    $wpdb->delete($post_table, $where);

  }
  
  echo "ok";
  die();
 
}

add_action('wp_ajax_nopriv_mlfr_remove_null_records', 'mlfr_remove_null_records');
add_action('wp_ajax_mlfr_remove_null_records', 'mlfr_remove_null_records');


function mlfp_delete_backups($delete_path) {

  if(file_exists($delete_path)) {

    $directoryIterator = new DirectoryIterator($delete_path);

    foreach($directoryIterator as $fileInfo) {
      $filePath = $fileInfo->getPathname();
      if(!$fileInfo->isDot()) {
        if($fileInfo->isFile()) {
          unlink($filePath);
        } elseif($fileInfo->isDir()) {
          if(mlfp_is_dir_empty($filePath)) {
            rmdir($filePath);
          } else {
            mlfp_delete_backups($filePath);
          }
        }
      }
    }
    rmdir($delete_path);
  }
}  

function mlfp_is_dir_empty($directory) {
  $filehandle = opendir($directory);
  while (false !== ($entry = readdir($filehandle))) {
    if ($entry != "." && $entry != "..") {
      closedir($filehandle);
      return false;
    }
  }
  closedir($filehandle);
  return true;
}  

function mlpr_add_tables() {
  
  ?>
  
  	<h2><?php _e('Add Media Library Folders Tables','mlp-reset') ?></h2>
    
  	<p><?php _e('Normally the database tables used by Media Library Folders Pro are created when the plugin is activated. However, if a table is missing, you can use this feature to create one or more missing tables.','mlp-reset') ?></p>
    
    <p>
      <a id="mlfr-generate--db-tables" class="button-primary"><?php _e('Check For Missing Tables','mlp-reset') ?></a>
    </p>    
    
    <p style="text-align: center;">
      <img id="mlfr-ajaxloader" alt="loading GIF" src="<?php echo MG_MEDIA_LIBRARY_RESET_PLUGIN_URL; ?>/images/ajax-loader.gif" style="position: relative;top: 10px;left: 10px; display:none;" width="32" height="32">    
    </p>
    <p id="return-message"></p>
    
    
  <script>
	jQuery(document).ready(function(){
    
    //jQuery("#remove-tables").click(function () {
    jQuery(document).on("click","#mlfr-generate--db-tables",function(){
      
      jQuery("#return-message").html("");
      jQuery("#mlfr-ajaxloader").show();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlfr_check_for_tables", 
          nonce: '<?php echo wp_create_nonce(MG_RESET_NONCE); ?>' },
        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
        dataType: "html",
        success: function (data) {
          jQuery("#return-message").html(data);
          jQuery("#mlfr-ajaxloader").hide();
        },
        error: function (err) { 
          jQuery("#mlfr-ajaxloader").hide();
          alert(err.responseText);
        }
      });  

      
    });  
    
  });  
   
  </script>
    

  <?php
}

function mlfr_check_for_tables() {
  
  global $wpdb;
  $message = '';
  
  if ( !wp_verify_nonce( $_POST['nonce'], MG_RESET_NONCE)) {
    exit(__('missing nonce! Please refresh this page.','mlp-reset'));
  }
  
  if(class_exists('MaxGalleriaMediaLibPro')) {
    
    global $maxgalleria_media_library_pro;
    
    //mgmlp_userrole_permissions 
    $message .= __('Checking mgmlp_userrole_permissions table<br>','mlp-reset');
    $maxgalleria_media_library_pro->add_userrole_table();
    
    //mgmlp_thumbnails 
    $message .= __('Checking mgmlp_thumbnails table<br>','mlp-reset');
    $maxgalleria_media_library_pro->add_thumbnail_table();
    
    //mgmlp_file_purge 
    $message .= __('Checking mgmlp_file_purge table<br>','mlp-reset');
    $maxgalleria_media_library_pro->add_purge_table();
    
    //mgmlp_csv_data 
    $message .= __('Checking mgmlp_csv_data table<br>','mlp-reset');
    $maxgalleria_media_library_pro->mlfp_export->add_csv_data_table();
      
    //mgmlp_import_folders 
    $message .= __('Checking mgmlp_import_folders table<br>','mlp-reset');
    $maxgalleria_media_library_pro->mlfp_export->add_folder_import_table();        
    $message .= __('Done creating tables.<br>','mlp-reset');
        
  } else {
    $message = __('Please activate Media Library Folders Pro to create missing tables.','mlp-reset');
  } 
    
  echo $message;
  
  die();
    
}

add_action('wp_ajax_nopriv_mlfr_check_for_tables', 'mlfr_check_for_tables');
add_action('wp_ajax_mlfr_check_for_tables', 'mlfr_check_for_tables');
