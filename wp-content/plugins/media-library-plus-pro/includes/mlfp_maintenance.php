
<div class="media-plus-toolbar"><div class="media-toolbar-secondary">  

  <!--<div id="mgmlp-library-container">-->

    <p><?php _e("If you would like to remove the uncateloged files from your media library, we recommend that you first backup up the files in your uploads folder.","maxgalleria-media-library")?></p>              

    <?php 

      global $wpdb;

      $folder_table = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;                

      $sql = "select count(*) 
      from {$wpdb->prefix}posts 
      LEFT JOIN $folder_table ON({$wpdb->prefix}posts.ID = $folder_table.post_id)
      where post_type = '" . MAXGALLERIA_MEDIA_LIBRARY_POST_TYPE . "'";

      //error_log($sql);

      $folder_count = $wpdb->get_var($sql);

    ?>
    <input type="hidden" id="folder-count" value="<?php echo $folder_count ?>">
    <input type="hidden" id="last-folder" value="">
    <input type="hidden" id="record-count" value="">

    <a id="purge-top"></a>
    <p>&nbsp;</p>
    <div class='purge-button-bar'>              
      <a class="button-primary" id="located-uncateloged-files" help="<?php _e("Scan the media library for files not recorded in the Wordpress database.","maxgalleria-media-library")?>" ><?php _e('Search For Uncataloged Files','maxgalleria-media-library'); ?></a>&nbsp;&nbsp;		
      <a class="button-primary" id="stop-search" disabled="disabled" data="disabled" help="<?php _e("Stop a currently running search.","maxgalleria-media-library")?>"><?php _e('Stop Search','maxgalleria-media-library'); ?></a>&nbsp;&nbsp;		
      <a class="button-primary" id="resume-search" disabled="disabled" data="disabled" help="<?php _e("Resume a stopped search.","maxgalleria-media-library")?>"><?php _e('Resume Search','maxgalleria-media-library'); ?></a>&nbsp;&nbsp;                
      <a class="button-primary" id="mlfp-process-selected-files" disabled="disabled" data="disabled" help="<?php _e("Processes all uncataloged files according to their currently set options.","maxgalleria-media-library")?>"><?php _e('Process Files','maxgalleria-media-library') ?></a>

    </div>
    <div class='purge-button-bar'>              
      <input id="remove-file-size" type="checkbox" checked > <label><?php _e('Automatically remove file size from imported thumbnail images names','maxgalleria-media-library') ?></label>
    </div>


    <div id="alwrap">
      <div style="display:none" id="ajax-purge-loader"></div>
    </div>


    <div id="scan_message" class="alert alert-success" style="display:none;"></div>
    <div id="scan_progress" class="alert alert-info" style="display:none;">
      <table>
        <tbody>
          <tr id="ajax-wheel">
            <td rowspan="2" width="40" valign="top">
              <img src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL; ?>/images/loading.gif" style="margin-top: 11px;">
            </td>
            <td valign="top">
              <h5 id="mlfp_scan_message"><?php _e('Please wait while folders are scanned', 'maxgalleria-media-library'); ?></h5>
              <h5 id="mlfp_process_message" style="display:none"><?php _e('Please wait while files are processed', 'maxgalleria-media-library'); ?></h5>
            </td>
          </tr>
          <tr>
            <td valign="top">
              <div class="progress">
                <div class="bar" style="width: 0%;"></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>							

    <p id="folder-message"> </p>
    <!--<p id="purge-file-status" style="display:none;"><span id="purge-file-count"></span> < ?php _e(' files found.', 'maxgalleria-media-library') ?></p>-->
    <div id="uncateloged-files">

    </div>

    <?php 
    //$this->refresh_purge_table();

    ?>
    <script>
      jQuery(document).ready(function(){

        load_purge_list(0);
        jQuery('.blank-nav').hide();
        jQuery('.active-nav').show();
        

        jQuery(document).on("click", "#mlfp-mm-more", function () {						
          jQuery('#mm-more-instructions').show();                    
          jQuery('#mm-dots').hide();
          jQuery('#mlfp-mm-more').hide();
          jQuery('#mlfp-mm-less').show();                                                           
        });

        jQuery(document).on("click", "#mlfp-mm-less", function () {						
          jQuery('#mm-more-instructions').hide();
          jQuery('#mm-dots').show();
          jQuery('#mlfp-mm-less').hide();                                                           
          jQuery('#mlfp-mm-more').show();
        });                  

        jQuery(document).on("mouseenter", ".purge-button-bar a", function () {						
           jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
        });

        jQuery(document).on("mouseleave", ".purge-button-bar a", function () {						
           jQuery('#folder-message').html('');
        });

        jQuery(document).on("click", "#mlfp-leave-all", function () {	
          
          if(jQuery(this).is(':checked')) {
            
            jQuery("#scan_message").hide();                  

            jQuery('input:radio[class=leave-radio]').prop('checked',true);

            jQuery('.leave-radio').each(function() {  
              var rec_id = jQuery(this).attr("data-id");
              update_purge_action(rec_id, 0);
            });
                                                
          }

        });

        jQuery(document).on("click", "#mlfp-delete-all", function () {						

          if(jQuery(this).is(':checked')) {
            
            jQuery("#scan_message").hide();                  

            jQuery('input:radio[class=delete-radio]').prop('checked',true);

            jQuery('.delete-radio').each(function() {  
              var rec_id = jQuery(this).attr("data-id");
              update_purge_action(rec_id, 1);
            });
                        
          } else {
            
            jQuery("#scan_message").hide();                  

            jQuery('input:radio[class=leave-radio]').prop('checked',true);

            jQuery('.leave-radio').each(function() {  
              var rec_id = jQuery(this).attr("data-id");
              update_purge_action(rec_id, 0);
            });
                        
          }


        });

        jQuery(document).on("click", "#mlfp-import-all", function () {						
          
          if(jQuery(this).is(':checked')) {
            
            jQuery("#scan_message").hide();                  

            jQuery('input:radio[class=import-radio]').prop('checked',true);

            jQuery('.import-radio').each(function() {  
              var rec_id = jQuery(this).attr("data-id");
              update_purge_action(rec_id, 2);
            });            
            
          } else {
            jQuery("#scan_message").hide();                  

            jQuery('input:radio[class=leave-radio]').prop('checked',true);

            jQuery('.leave-radio').each(function() {  
              var rec_id = jQuery(this).attr("data-id");
              update_purge_action(rec_id, 0);
            });
          }
         
        });                                    

        jQuery(document).on("click", "#mlfp-previous, #mlfp-next, #mlfp-first, #mlfp-last", function (e) {
          e.stopImmediatePropagation();
          jQuery("#ajax-purge-loader").show();

          var page_id = jQuery(this).attr("page-id");
          console.log('page_id',page_id);

          load_purge_list(page_id)
          // scroll into view not working well
          //jQuery("#purge-top").scrollIntoView({
          //    behavior: "smooth", 
          //    block: "end"
          //});      

        });

        jQuery(document).on("click", ".leave-radio", function () {						
          var rec_id = jQuery(this).attr("data-id");
          update_purge_action(rec_id, 0);
        });

        jQuery(document).on("click", ".delete-radio", function () {						
          var rec_id = jQuery(this).attr("data-id");
          var dir_found = jQuery(this).attr("data-dir");
          if(dir_found == '1')
            update_purge_action(rec_id, 4);
          else
            update_purge_action(rec_id, 1);
        });

        jQuery(document).on("click", ".import-radio", function () {						
          var rec_id = jQuery(this).attr("data-id");
          var dir_found = jQuery(this).attr("data-dir");
          if(dir_found == '1')
            update_purge_action(rec_id, 3);
          else
            update_purge_action(rec_id, 2);
        });

        jQuery(document).on("click", "#mlfp-process-selected-files", function () {						

          var data = jQuery(this).attr("data");
          if(data == "disabled" ) {
            return false;          
          }       

          if(confirm(mgmlp_ajax.confirm_file_processing)) {

            //jQuery("#folder-message").html('');						
            jQuery("#folder-message").html('Searching for files to process... please wait.');

            jQuery("#uncateloged-files").hide();

            update_purge_count();                      

          }

        });

      });

      function update_purge_count() {

        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mlfp_update_purge_count", nonce: mgmlp_ajax.nonce },
          url : mgmlp_ajax.ajaxurl,
          dataType: "html",
          success: function (data) {
            console.log('update_purge_count',data);
            jQuery("#record-count").val(data);

            disable_button("located-uncateloged-files");
            //disable_button("stop-search");
            //disable_button("resume-search");
            disable_button("mlfp-leave-all");
            disable_button("mlfp-delete-all");
            disable_button("mlfp-import-all");
            disable_button("mlfp-process-selected-files");

            jQuery("#scan_progress .progress .bar").css("width", "0%");

            process_purge_queue(0, parseInt(data));

          },
          error: function (err)
            { 
              jQuery("#ajax-purge-loader").hide();
              alert(err.responseText);
            }
        });                


      }

      function load_purge_list(current_page) {

        jQuery("#ajax-purge-loader").show();
        jQuery("#uncateloged-files").html('');
        jQuery("#uncateloged-files").show();


        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "refresh_purge_table", current_page: current_page, nonce: mgmlp_ajax.nonce },
          url : mgmlp_ajax.ajaxurl,
          dataType: "json",
          success: function (data) {
            jQuery("#ajax-purge-loader").hide();                      
            //jQuery("#purge-file-status").show();
            //jQuery("#purge-file-count").text(data.total);

            if(parseInt(data.total) == 0)
              jQuery("#folder-message").text(mgmlp_ajax.no_files_found);
            else
              jQuery("#folder-message").text(data.total + mgmlp_ajax.files_found);
            jQuery("#record-count").val(data.total);
            jQuery("#uncateloged-files").html(data.output);

            if(parseInt(data.total) > 0) {
              jQuery("#mlfp-leave-all").attr('disabled',false);    
              jQuery("#mlfp-leave-all").attr('data','');                            

              jQuery("#mlfp-delete-all").attr('disabled',false);    
              jQuery("#mlfp-delete-all").attr('data','');                            

              jQuery("#mlfp-import-all").attr('disabled',false);    
              jQuery("#mlfp-import-all").attr('data','');                            

              jQuery("#mlfp-process-selected-files").attr('disabled',false);    
              jQuery("#mlfp-process-selected-files").attr('data','');                            

            } else {
              jQuery("#mlfp-leave-all").attr('data','disabled');
              jQuery("#mlfp-leave-all").attr('disabled','disabled');    

              jQuery("#mlfp-delete-all").attr('data','disabled');
              jQuery("#mlfp-delete-all").attr('disabled','disabled');    

              jQuery("#mlfp-import-all").attr('data','disabled');
              jQuery("#mlfp-import-all").attr('disabled','disabled');    

              jQuery("#mlfp-process-selected-files").attr('data','disabled');
              jQuery("#mlfp-process-selected-files").attr('disabled','disabled');    

            }

          },
          error: function (err)
            { 
              jQuery("#ajax-purge-loader").hide();
              alert(err.responseText);
            }
        });                

      }


      // action types
      // 0 leave
      // 1 delete
      // 2 import
      // 3 create folder
      // 4 delete folder record
      function update_purge_action(rec_id, action_type) {
        console.log('rec_id',rec_id,'action_type',action_type);

        jQuery("#ajax-purge-loader").show();

        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mlfp_update_purge_action", rec_id: rec_id, action_type: action_type, nonce: mgmlp_ajax.nonce },
          url : mgmlp_ajax.ajaxurl,
          dataType: "html",
          success: function (data) {
            jQuery("#ajax-purge-loader").hide();
          },
          error: function (err)
            { 
              jQuery("#ajax-purge-loader").hide();
              alert(err.responseText);
            }
        });                

      }

      function process_purge_queue(last_record, record_count) {

        var remove_file_size = jQuery('#remove-file-size').is(':checked');

        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mlfp_process_purge_file", last_record: last_record, record_count: record_count, remove_file_size: remove_file_size, nonce: mgmlp_ajax.nonce },
          url: mgmlp_ajax.ajaxurl,
          dataType: "json",
          success: function (data) { 
            console.log('data',data);
            if(data != null && data.last_record != null) {                        
              jQuery("#mlfp_scan_message").hide();
              jQuery("#mlfp_process_message").show();
              jQuery("#scan_progress").show();
              jQuery("#scan_message").show();
              console.log(data.percentage);
              jQuery("#scan_progress .progress .bar").css("width", data.percentage + "%");
              jQuery("#scan_message").html(data.message);
              //jQuery("#last-folder").val(data.last_folder);        
              process_purge_queue(data.last_record, record_count);
            } else {        
              //jQuery("#ajax-wheel").hide();
              jQuery("#scan_message").html(data.message); 
              jQuery("#scan_progress").delay(2500).slideUp(500);
              jQuery("#folder-message").html('');


              enable_button("located-uncateloged-files");
              enable_button("mlfp-leave-all");
              enable_button("mlfp-delete-all");
              enable_button("mlfp-import-all");
              enable_button("mlfp-process-selected-files");

              //jQuery("#located-uncateloged-files").click();
              //console.log('click');
              //return false;
              load_purge_list(0);
            }      
          },
          error: function (err){ 
            jQuery("#ajax-wheel").hide();
            alert(err.responseText)
          }    
        });																											                  

      }

      function disable_button(button_id) {
        jQuery("#"+button_id).attr('data','disabled');
        jQuery("#"+button_id).attr('disabled','disabled');                      
      }

      function enable_button(button_id) {
        jQuery("#"+button_id).attr('disabled',false);    
        jQuery("#"+button_id).attr('data','');                                              
      }


    </script>

  <!--</div>mgmlp-library-container-->
  </div>
</div>  

		<?php