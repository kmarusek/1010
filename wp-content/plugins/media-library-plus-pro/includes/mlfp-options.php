<?php

		global $wpdb;
		//global $wpdb, $current_user;
    
    $id_author = get_current_user_id();
        
    //$disable_ft = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_DISABLE_FT, true );		
		$enable_pagination = get_option(MAXGALLERIA_MLP_PAGINATION, 'off');
		$images_pre_page = get_option(MAXGALLERIA_MLP_ITEMS_PRE_PAGE, '40');
    $this->disable_media_ft = get_option(MAXGALLERIA_REMOVE_FT, 'off');
		$this->front_end = get_option( MAXGALLERIA_FE_SCRIPTS, 'off');
    $this->enable_user_role = get_option( MAXGALLERIA_RESTRICT_USER_ROLE, 'off');
    $this->disable_scaling = get_option( MAXGALLERIA_DISABLE_SCALLING, 'off');
    $this->disable_list_mode = get_option( MAXGALLERIA_DISABLE_LIST_MODE, 'off');
    $this->disable_non_admins = get_option( MAXGALLERIA_DISABLE_NON_ADMINS, 'off');
		$use_set_locale = get_option(MAXGALLERIA_USE_SET_LOCALE, 'off' );
		$locale = get_option(MAXGALLERIA_LOCALE, '' );
    $this->wpmf_integration = get_option(MAXGALLERIA_WPMF, 'off');
		$enable_upload = get_option(MAXGALLERIA_MLP_UPLOAD, 'off');
    $caption_import = get_option(MLFP_PREVENT_CAPTION_IMPORT, 'off');
    $this->rollback_scaling = get_option( MAXGALLERIA_ROLLBACK_SCALLING, 'off');
    $this->debug_mflp_query = get_option( MAXGALLERIA_DEBUG_QUERIES, 'off');    
    $meta_index = get_option( MAXGALLERIA_POSTMETA_INDEX, 'off');    
    
		?>
		
<!--		<p>
			<input type="checkbox" name="disable_floating_filetree" id="disable_floating_filetree" value="" < ?php checked($disable_ft, 'on') ?>>
			<label>< ?php  _e('Disable floating file tree', 'maxgalleria-media-library'); ?></label>
		</p>-->
    <p>
			<input type="checkbox" name="hide_folder_tree_popup" id="hide_folder_tree_popup" value="" <?php checked($this->disable_media_ft, 'on') ?>>
			<label><?php  _e('Remove folder tree from Media page & popups', 'maxgalleria-media-library'); ?></label>			      
    </p>
    <p>
			<input type="checkbox" name="load_mlfp_front_end" id="load_mlfp_front_end" value="" <?php checked($this->front_end, 'on') ?>>
			<label><?php  _e('Enable loading folder tree on the front end media popups', 'maxgalleria-media-library'); ?></label>			      
    </p>
		<p>
			<input type="checkbox" name="enable_pagination" id="enable_pagination" value="" <?php checked($enable_pagination, 'on') ?>>
			<label><?php  _e('Enable Pagination (allows one to page through files in a folder rather than lot them all at one time.)', 'maxgalleria-media-library'); ?></label>			
		</p>
            
		<p>
			<label><?php  _e('Number of images to display per page:', 'maxgalleria-media-library'); ?></label>
			<input type="text" id="images-pre-page" name="images-pre-page" value="<?php echo $images_pre_page ?>" style="width: 50px">
		</p>
    
		<p>
			<input type="checkbox" name="enable_upload" id="enable_upload" value="" <?php checked($enable_upload, 'on') ?>>
			<label><?php  _e('Enable Front End Upload', 'maxgalleria-media-library'); ?></label>			
		</p>
    
		<p>
			<input type="checkbox" name="disable_non_admins" id="disable_non_admins" value="" <?php checked($this->disable_non_admins, 'on') ?>>
			<label><?php  _e('Disable file delete, folder hide, folder add, folder delete and sync for non Administrators', 'maxgalleria-media-library'); ?></label>			
		</p>    
		<p>
			<input type="checkbox" name="enable_user_role" id="enable_user_role" value="" <?php checked($this->enable_user_role, 'on') ?>>
			<label><?php  _e('Enable folder access by user role', 'maxgalleria-media-library'); ?></label>			
		</p>    
		<p>
			<input type="checkbox" name="disable_scaling" id="disable_scaling" value="" <?php checked($this->disable_scaling, 'on') ?>>
			<label><?php  _e('Disable large image scaling', 'maxgalleria-media-library'); ?></label>			
		</p>      
		<p>
			<input type="checkbox" name="rollback_scaling" id="rollback_scaling" value="" <?php checked($this->rollback_scaling, 'on') ?>>
			<label><?php  _e('Enable scaled image rollback', 'maxgalleria-media-library'); ?></label>			
		</p>      
		<p>
			<input type="checkbox" name="disable_list_mode" id="disable_list_mode" value="" <?php checked($this->disable_list_mode, 'on') ?>>
			<label><?php  _e('Disable media library list view', 'maxgalleria-media-library'); ?></label>			
		</p>  
    
		<p>
			<input type="checkbox" name="debug_mflp_query" id="debug_mflp_query" value="" <?php checked($this->debug_mflp_query, 'on') ?>>
			<label><?php  _e('Enable MLFP Query Display', 'maxgalleria-media-library'); ?></label>			
		</p>                
    
		<p>
			<input type="checkbox" name="caption_import" id="caption_import" value="" <?php checked($caption_import, 'on') ?>>
			<label><?php  _e('Disable import of captions from images', 'maxgalleria-media-library'); ?></label>			
		</p>            
    
		<p>
			<input type="checkbox" name="meta_index" id="meta_index" value="" <?php checked($meta_index, 'on') ?>>
			<label><?php  _e('Add an index to the postmeta table. <em>Recommend for sites with a high number of media files. Uncheck to remove the index.</em>', 'maxgalleria-media-library'); ?></label>			
		</p>            
        
		<p>
			<input type="checkbox" name="use_locale" id="use_locale" value="" <?php checked($use_set_locale, 'on') ?>>
			<label><?php _e('For users with non Latin character sets, check this option and enter the proper locale code below to fix issues with moving files', 'maxgalleria-media-library'); ?></label>			
		</p>    
		<p>    
			<label><?php  _e('Locale', 'maxgalleria-media-library'); ?>: </label>			
      <input type="text" name="locale" id="locale" value="<?php echo $locale; ?>" >
		</p>    
        
		<p>
      <a class="button-primary" id="mlfp-update-settings"><?php _e('Update Settings','maxgalleria-media-library'); ?></a>			
		</p>
		<div id="saving-message"></div>
    
    <?php if(class_exists('WpMediaFolder')) { ?>
    <hr>
		<h5><?php _e('WP Media Folder Integration', 'maxgalleria-media-library'); ?></h5>    
    <table>
      <tbody>
        <tr>
          <td class="wplf-left-column">
              <?php 
                if($this->wpmf_integration == 'on') {
                  $disabled = 'disabled="disabled"';
                  $hide_box_class = '';
                } else {
                  $disabled = '';
                  $hide_box_class = 'mlfp-hide-box';
                }  
              ?>
              <input type="checkbox" <?php echo $disabled; ?> name="mlfp_update_for_wpmf" id="mlfp_update_for_wpmf" value="" <?php checked($this->wpmf_integration, 'on') ?>>
              <label id="mlfp_update_for_wpmf" class="wpmf-compatibility"><?php _e('Enable compatibility with WP Media Folder plugin', 'maxgalleria-media-library'); ?></label>			
              <p><span id="mlfp-in-progress" style="display:none"></span> <span id="integration-status"></label></p>
              <input type="hidden" id="folder-count" value="">
              <input type="hidden" id="file-count" value="">
              <input type="hidden" id="id_author" value="<?php echo $id_author ?>">                                    
          </td>
          <td class="wplf-right-column">
            <label class="wpmf-compatibility"><em><?php _e('This option will enable compatibility with WP Media Folder plugin and will create matching folders in WPMF. <strong>To upload files to their desired folders only use Media Library Folders Pro. Folders in WPMF should not be moved as Media Library Folders Pro does not have a corresponding action as it would break image links in posts and pages.</strong>', 'maxgalleria-media-library'); ?></em></label>
          </td>
        </tr>
        <tr>
          <td class="wplf-left-column">
            <a class="button" id="mlfp_import_from_wpmf"><?php _e('Import folders from WP Media Folder','maxgalleria-media-library'); ?></a>            
            <input type="hidden" id="import-folder-count" value="">
            <p>
              <span id="mlfp-in-progress3" style="display:none"></span> <span id="import-wpmf-status"></span>
            </p>            
          </td>
          <td class="wplf-right-column">
              <span class="wpmf-compatibility"><em><?php _e('This option will create actual folders from the WPMF data in the media library. Files remain in their original folders and are not copied to the imported folders. Use Media Library Folders Pro to manually copy files to their desired folders.', 'maxgalleria-media-library'); ?></em></span>
          </td>
        </tr>
        <tr class="<?php echo $hide_box_class;?>">
          <td class="wplf-left-column">
            <a class="button" id="mlpf-clear-and-update-wpmf"><?php _e('Clear and Update WP Media Folder Data','maxgalleria-media-library'); ?></a>
            <p>
              <span id="mlfp-in-progress2" style="display:none"></span> <span id="integration-status2"></span>        
            </p>
`          </td>
          <td class="wplf-right-column">
            <span class="wpmf-compatibility"><em><?php _e('This option will clear the existing WPMF data and import Media Library Folders Pro folders and files into WPMF.', 'maxgalleria-media-library'); ?></em></span>
          </td>
        </tr>
      </tbody>
    </table>    
    
    <div>
      
    <?php }?>
    
    		
<script>
  var page_refresh = false;    
	jQuery(document).ready(function(){
    		
    jQuery(document).on("click","#mlfp-update-settings",function(){
      
      if(jQuery("#rollback_scaling").is(":checked"))
				jQuery("#disable_scaling").prop('checked', true); //must be checked.      
			
      //var floating_ft_disabled = jQuery("#disable_floating_filetree").is(":checked");
      var pagnation_enabled = jQuery("#enable_pagination").is(":checked");
			var images_per_page = jQuery("#images-pre-page").val();
			var disable_popup_ft = jQuery("#hide_folder_tree_popup").is(":checked");
			var enable_fe_scripts = jQuery("#load_mlfp_front_end").is(":checked");
			var enable_user_role = jQuery("#enable_user_role").is(":checked");
			var disable_scaling = jQuery("#disable_scaling").is(":checked");      
			var rollback_scaling = jQuery("#rollback_scaling").is(":checked");
      var disable_list_mode = jQuery("#disable_list_mode").is(":checked");
      var debug_mflp_query = jQuery("#debug_mflp_query").is(":checked");
			var disable_non_admins = jQuery("#disable_non_admins").is(":checked");
      var caption_import  = jQuery("#caption_import").is(":checked");
      var use_locale = jQuery("#use_locale").is(":checked");
      var enable_upload = jQuery("#enable_upload").is(":checked");
      var locale = jQuery("#locale").val();
      var meta_index = jQuery("#meta_index").is(":checked");
                    			
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "update_mlfp_settings", 
                enable_fe_scripts: enable_fe_scripts, 
                pagnation_enabled: pagnation_enabled, 
                images_per_page: images_per_page, 
                enable_upload: enable_upload,
                disable_popup_ft: disable_popup_ft, 
                enable_user_role: enable_user_role, 
                disable_scaling: disable_scaling, 
                rollback_scaling: rollback_scaling,
                debug_mflp_query: debug_mflp_query,
                disable_list_mode: disable_list_mode,
                disable_non_admins: disable_non_admins,
                caption_import: caption_import,
                use_locale: use_locale, 
                meta_index: meta_index,
                locale: locale,
                nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) {					
					jQuery("#saving-message").html(data);
          window.location.reload();
				},
				error: function (err){ 
					jQuery("#gi-ajax-loader").hide();
					alert(err.responseText)
				}
			});
      						
		});
    
    //jQuery(document).on("click","#enable_user_role",function(){
    //  window.page_refresh = !window.page_refresh;
    //  console.log(window.page_refresh);
    //});
        
    jQuery(document).on("click","#mlpp_activate_ms_license",function(){
            
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlfp_license_network_activate", nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) {					
					jQuery("#network-activate-message").html(data);
          window.location.reload();
				},
				error: function (err){ 
					jQuery("#gi-ajax-loader").hide();
					alert(err.responseText)
				}
			});
      
    });
    
    jQuery(document).on("click","#mlpp_deactivate_ms_license",function(){
            
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlfp_license_network_deactivate", nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) {					
					jQuery("#network-activate-message").html(data);
          window.location.reload();
				},
				error: function (err){ 
					jQuery("#gi-ajax-loader").hide();
					alert(err.responseText)
				}
			});
      
    });
    
        
    jQuery(document).on("click","#mlfp_update_for_wpmf",function(){
      
      jQuery('#wpmf-box').removeClass('mlfp-hide-box');
      jQuery('#mlfp_update_for_wpmf').attr('disabled', true);
      add_folders_and_files_to_wpmf('#integration-status', '#mlfp-in-progress');
      jQuery('#hide_folder_tree_popup').prop('checked', true);                  
    });
    
    jQuery(document).on("click","#mlpf-clear-and-update-wpmf",function(){
      console.log('mlpf-clear-and-update-wpmf');
      jQuery('#mlfp-in-progress').html('');
      mlfp_delete_terms();
    });
    
    jQuery(document).on("click","#mlfp_import_from_wpmf",function(){
      
      jQuery('#mlfp-in-progress3').show();
          
      console.log('mlfp_import_from_wpmf');
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlfp_wpmf_folder_count", nonce: mgmlp_ajax.nonce },
        url: mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) { 
          jQuery("#import-folder-count").val(data);
          console.log('count', data);
          mlfp_import_wpmf_folder(0, data);
        },
        error: function (err){ 
          alert(err.responseText)
        }
      });
                
    });
            	        	
	});
  
  
  function mlfp_migrate_folders(last_folder, folder_count, status_element, progress_element) {
    
    const id_author = jQuery('#id_author').val();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_import_next_folder", last_folder: last_folder, folder_count: folder_count, id_author: id_author, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) { 
        if(data != null && data.last_folder != null) {
          jQuery(status_element).html(data.message + ' - ' + Math.floor(data.percentage) + '%' );
          var folder_count = jQuery('#folder-count').val();          
          mlfp_migrate_folders(data.last_folder, folder_count, status_element);
        } else {
          jQuery(status_element).html(data.message);
          const total_files = jQuery('#file-count').val();                    
          jQuery(status_element).html("<?php _e('Adding files. Please wait.','maxgalleria-media-library'); ?>");
          mlfp_migrate_attachments(0, total_files, status_element, progress_element);
        }	
      },
      error: function (err){ 
        jQuery(progress_element).hide();
        alert(err.responseText)
      }
    });
    
  }  
    
  function mlfp_migrate_attachments(last_file, file_count, status_element, progress_element) {

    console.log('last_file',last_file);
    console.log('file_count',file_count);
    const id_author = jQuery('#id_author').val();
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_import_next_file", last_file: last_file, file_count: file_count, id_author: id_author, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) { 
        if(data != null && data.last_file != null) {
          //console.log('last folder ',data.last_file);
          jQuery(status_element).html(data.message + ' - ' + Math.floor(data.percentage) + '%' );
          var file_count = jQuery('#file-count').val();          
          mlfp_migrate_attachments(data.last_file, file_count, status_element, progress_element);
        } else {
          jQuery(status_element).html(data.message);
          jQuery('#mlfp-in-progress').hide();
          jQuery('#mlfp-in-progress2').hide();
        }	
      },
      error: function (err){ 
        jQuery(progress_element).hide();
        alert(err.responseText)
      }
    });
           
  }
  
  function add_folders_and_files_to_wpmf(status_element, progress_element){
           //'#integration-status'
    jQuery(progress_element).show();
    jQuery(status_element).html("<?php _e('Adding folders. Please wait.','maxgalleria-media-library'); ?>");

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_get_folder_count", nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {          
        jQuery('#folder-count').val(data.total_folders);          
        jQuery('#file-count').val(data.total_files);                    
        mlfp_migrate_folders(0, Number(data.total_folders), status_element, progress_element)
      },
      error: function (err){ 
        jQuery(progress_element).hide();
        alert(err.responseText)
      }
    });
    
  }
  
  function mlfp_delete_terms() {
    
    console.log('mlfp_delete_terms');
    
    jQuery('#mlfp-in-progress2').show();
    jQuery("#integration-status2").html('<?php _e('Clearing WPMF terms. Please wait.','maxgalleria-media-library'); ?>');
        
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_delete_terms", nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        add_folders_and_files_to_wpmf('#integration-status2');
      },
      error: function (err){ 
        jQuery('#mlfp-in-progress2').hide();
        alert(err.responseText);
      },
    });
    
  }
  
  function mlfp_import_wpmf_folder(last_folder, folder_count) {
    
    console.log("mlfp_import_wpmf_folder");
    console.log('folder_count', folder_count);
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_import_next_wpmf_folder", last_folder: last_folder, folder_count: folder_count, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) { 
        //console.log('data',data);
        if(data != null && data.last_folder != null) {
          jQuery('#import-wpmf-status').html(data.message + ' - ' + Math.floor(data.percentage) + '%' );
          var folder_count = jQuery('#import-folder-count').val();          
          mlfp_import_wpmf_folder(data.last_folder, folder_count);
        } else {
          jQuery('#import-wpmf-status').html(data.message);
          jQuery('#mlfp-in-progress3').hide();
        }	
      },
      error: function (err){ 
        jQuery('#mlfp-in-progress3').hide();
        alert(err.responseText)
      }
    });
    
       
  }
  
  
</script>  		
