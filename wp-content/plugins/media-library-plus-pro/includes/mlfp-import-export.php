    <?php $this->setup_mlfp_exim(); ?>        
    <h2><?php _e('Import', 'maxgalleria-media-library' ); ?></h2>
    <p><?php _e('Upload media that was exported from Media Library Folders Pro', 'maxgalleria-media-library' ); ?></p>
    <p>
      <button id="mlfp-upload-backup" class="gray-blue-bt button-primary exim-button"><?php _e('Upload a Media Export File', 'maxgalleria-media-library' ); ?></button>
      <button id="mlfp-cancel-import" class="gray-blue-bt button-primary" style="display: none;"><?php _e('Stop Import', 'maxgalleria-media-library' ); ?></button>          
    </p>
    <hr>
    
    <h2><?php _e('Export', 'maxgalleria-media-library' ); ?></h2>
    
    <p><?php _e('Generate a new export from the contents of the media library', 'maxgalleria-media-library' ); ?></p>
    
    <p>
      <button id="mlfp-create-backup" class="gray-blue-bt button-primary exim-button"><?php _e('Create a New Media Export File', 'maxgalleria-media-library' ); ?></button>      
    </p>
    <hr>
    <input type="hidden" id="mlfp_exim_folder_count" value="0" >
    <input type="hidden" id="mlfp_exim_file_count" value="0" >
    <input type="hidden" id="mlfp_exim_backup_folder" value="0" >
    
    <div id="alwrap">
      <div style="display: none;" id="ajaxieloader"></div>
    </div>
    <p id="exim-message"></p>


    <table id='ml-backup-list'>
      <thead>
        <tr>
          <td class='mlfp-backup-name'><?php _e('Export Name', 'maxgalleria-media-library' ); ?></td>
          <td class='mlfp-backup-date'><?php _e('Export Date', 'maxgalleria-media-library' ); ?></td>
          <td  colspan="3" class='mlfp-backup-button'><?php _e('Actions', 'maxgalleria-media-library' ); ?></td>
<!--          <td class='mlfp-backup-button'></td>
          <td class='mlfp-backup-button'></td>-->
        </tr>
      </thead>
      <tbody id='backup-list'>
      </tbody>
    </table>
    
<div id="mlfp-export-file-popup">
  <div class="mlf-popup-content">
    <h2><?php esc_html_e('Export File Name','maxgalleria-media-library') ?></h2>
    <a id="close-new-export-popup" title="<?php esc_html_e('Close without saving','maxgalleria-media-library') ?>">x</a> 
    <hr>
    
    <div class="popup-content-bottom">
    <p><?php esc_html_e('Enter the name of the export file. This will be the name of the folder where media files will be stored','maxgalleria-media-library') ?></p>
    <p><input type="text" id="mlfp-backup-name" maxlength="40" value="" /></p>
    <div class="btn-wrap"><a id="mgmlp-create-new-export" class="gray-blue-link" ><?php esc_html_e('Ok','maxgalleria-media-library') ?></a></div>
    </div>
        
  </div>
</div>  
    
<div id="mlfp-upload-export-popup">
  <div class="mlf-popup-content">
    <h2><?php esc_html_e('Import Media Library Export File','maxgalleria-media-library') ?></h2>
    <a id="close-new-upload-popup" title="<?php esc_html_e('Close without uploading','maxgalleria-media-library') ?>">x</a> 
    <hr>
    
    <div class="popup-content-bottom">
    <p><?php esc_html_e('Select a Media Library Export File to Upload','maxgalleria-media-library') ?></p>
    <div>
      <input type="file" name="fileToUpload" id="fileToUpload">
      <button id="exim-upload-submit" class="gray-blue-link" ><?php esc_html_e('Upload Export File','maxgalleria-media-library') ?></button></div>
    </div>        
  </div>
</div>  
          
<script>
var mlfp_cancel_import = false;
var interval_count = 0;
jQuery(document).ready(function(){
    
    var backup_name = '';
    
    refreshBackups();
    jQuery('.blank-nav').hide();
    jQuery('.active-nav').show();
        
    jQuery('#mlfp-create-backup').on('click', function (e) {
      e.stopImmediatePropagation();
      console.log('mlfp-create-backup');
      jQuery("#mlfp-backup-name").val('');
      jQuery("#exim-message").html('');
      jQuery('#mlfp-export-file-popup').fadeIn(300);
      jQuery('#mlfp-backup-name').focus();    
    });

    jQuery('#close-new-export-popup').on('click', function (e) {
      e.stopImmediatePropagation();
      jQuery('#mlfp-export-file-popup').fadeOut(300);
    });
    
    jQuery('#mgmlp-create-new-export').on('click', function (e) {
      e.stopImmediatePropagation();
      generate_new_export();
    });

    jQuery("#mlfp-backup-name").keypress(function(e) {
      e.stopImmediatePropagation();
      if (e.keyCode == 13) {
        generate_new_export();
      }
    });    
    
    jQuery( "#mlfp-upload-backup" ).click(function(e) {
      e.stopImmediatePropagation();
      jQuery("#fileToUpload").val('');
      jQuery("#exim-message").html('');      
      jQuery('#mlfp-upload-export-popup').fadeIn(300);
    });
    
    jQuery('#close-new-upload-popup').on('click', function (e) {
      e.stopImmediatePropagation();
      jQuery("#fileToUpload").val('');
      jQuery('#mlfp-upload-export-popup').fadeOut(300);
    });
                    
        
  jQuery(document).on("click", "#mlfp-cancel-import", function () {
    
    window.mlfp_cancel_import = true;    
    
    jQuery('#mlfp-cancel-import').hide();    
    
    jQuery(".exim-button").removeAttr('disabled');
    
    jQuery(".exim-button").removeClass('disabled');
    
    jQuery("#exim-message").html('<?php _e('Import cancelled.','maxgalleria-media-library') ?>');
           
    jQuery("#ajaxieloader").hide();
  });
  
  jQuery(document).on("click", ".mlfp-download-backup", function () {
    jQuery("#exim-message").html('<?php _e('Creating export zip file. Please wait.', 'maxgalleria-media-library' ); ?>');    
    setTimeout(function() {
      jQuery("#exim-message").html('');
    }, 60000);
  });
      
  jQuery(document).on("click", ".mlfp-import-backup", function () {
  
    window.mlfp_cancel_import = false;    
    
    jQuery("#ajaxieloader").show();
    
    jQuery('#mlfp-cancel-import').show();
        
    jQuery(".exim-button").attr('disabled','disabled');
    
    jQuery(".exim-button").addClass('disabled');    
    
    
    var backup_folder = jQuery(this).attr("folder-id");
    
    load_import_data(backup_folder);        
    
  });
    
  jQuery(document).on("click", ".mlfp-delete-backup", function () {
    
    if(confirm('<?php _e('Delete the selected export file?','maxgalleria-media-library') ?>')) {
      
      jQuery("#ajaxieloader").show();
      var backup_folder = jQuery(this).attr("folder-id");

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlfp_exim_delete_backup", backup_folder: backup_folder, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          refreshBackups();        
          jQuery("#ajaxieloader").hide();
        },
        error: function (err){ alert(err.responseText);},
      });
    
    }
        
  });

});

  function generate_new_export() {
    jQuery('#mlfp-export-file-popup').fadeOut(300);
    jQuery("#exim-message").html('<?php _e('Checking for exisiting exports...','maxgalleria-media-library') ?>');
    var backup_name = jQuery("#mlfp-backup-name").val();
    if(backup_name.length > 0) { 
      //remove .zip extention if included
      var zip_position = backup_name.indexOf('.zip')
      if(zip_position !== -1) {
        backup_name = backup_name.substring(0, zip_position);
      }
      backup_name = backup_name.replace(/\s+/g, '-').toLowerCase();
      createBackupFolder(backup_name);
    } else {
      jQuery("#exim-message").html('<?php _e('The export file name cannot be blank.','maxgalleria-media-library') ?>');
    }
  }

  function load_import_data(backup_folder) {
  
    jQuery("#exim-message").html('<?php _e('Loading export data...','maxgalleria-media-library') ?>');
    jQuery('#mlfp_exim_backup_folder').val(backup_folder);					
        
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_exim_load_import_data", backup_folder: backup_folder, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {
        jQuery('#mlfp_exim_folder_count').val(data.folder_count);					
        jQuery('#mlfp_exim_file_count').val(data.file_count);					
        var total_folders = Number(data.folder_count);
        var total_files = Number(data.file_count);
        
        if(data != null)
          processFolders(0, total_folders);
        
      },
      error: function (err)
        { alert(err.responseText);}
    });
  
  }
      
  function refreshBackups() {
    
    console.log('refreshBackups');
    
    jQuery("#ajaxieloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_refresh_backups", nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#backup-list").html(data);
        jQuery("#exim-message").html('');
        jQuery("#ajaxieloader").hide(); 
      },
      error: function (err)
        { alert(err.responseText);}
    });
    
  }
    
  function createBackupFolder(backup_name) {
        
    jQuery("#ajaxieloader").show();
            
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_create_backup_folder", backup_name: backup_name, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {
        jQuery("#exim-message").html(data.message);

        if(data.new_backup_folder != null) {
          saveFolderAndAttachmentData(backup_name, data.new_backup_folder);                
        } else {
          jQuery("#ajaxieloader").hide();
        }  
      },
      error: function (err) {
        alert(err.responseText);
        jQuery("#ajaxieloader").hide(); 
      }
    });
            
  }
  
  function saveFolderAndAttachmentData(backup_name, new_backup_folder) {
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_save_bk_data", backup_name: backup_name, new_backup_folder: new_backup_folder, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        console.log('done creating backup files');
        refreshBackups();        
        
      },
      error: function (err) {
        alert(err.responseText);
        jQuery("#ajaxieloader").hide(); 
      }
    });
        
  }
      
  function processFolders(last_folder, folder_count){
    console.log(last_folder);

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_exim_next_folder", last_folder: last_folder, folder_count: folder_count, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) { 
        if(window.mlfp_cancel_import == true) {
          jQuery("#ajaxieloader").hide(); 
          return false;
        } else if(data != null && data.last_folder != null) {
          console.log('last folder ',data.last_folder);
          jQuery('#exim-message').html(data.message + ' - ' + Math.floor(data.percentage) + '%' );
          processFolders(data.last_folder, folder_count);
        } else {
          jQuery("#exim-message").html(data.message);
          var file_count = jQuery('#mlfp_exim_file_count').val();
          var backup_folder = jQuery('#mlfp_exim_backup_folder').val();
          processFiles(backup_folder, 0, file_count);
        }	
      },
      error: function (err){ 
        alert(err.responseText)
      }
    });																							
  }
  
  function processFiles(backup_folder, last_file, file_count) {
    
    console.log('processFiles',last_file);

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_exim_next_file", backup_folder: backup_folder, last_file: last_file, file_count: file_count, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) { 
        if(window.mlfp_cancel_import == true) {
          jQuery("#ajaxieloader").hide(); 
          return false;
        } else if(data != null && data.last_file != null) {
        //} else if(data != null && data.last_file < 25) {
          console.log('last file ',data.last_file);
          jQuery('#exim-message').html(data.message + ' - ' + Math.floor(data.percentage) + '%' );
          processFiles(backup_folder, data.last_file, file_count);
        } else {
          jQuery("#exim-message").html(data.message);
          jQuery('#mlfp-cancel-import').hide();              
          jQuery(".exim-button").removeAttr('disabled');    
          jQuery(".exim-button").removeClass('disabled');
          jQuery("#ajaxieloader").hide(); 
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