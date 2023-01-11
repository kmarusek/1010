
    <div class="media-plus-toolbar"><div class="media-toolbar-secondary">  

      <div>
        <div id="alwrap">
          <div style="display:none" id="ajaxloader"></div>
        </div>

        <?php 

        global $wp_roles;
        $roles = $wp_roles->get_names();

        //print_r($wp_roles);            

        ?>
        <p>
          Role:
          <select id="user-role-list">
            <option selected disabled>Select a role</option>
        <?php foreach($roles as $key => $role) { 
            $role_data = get_role($key);
            if($role_data->has_cap('upload_files')) {
              echo "<option value='$key'>$role</option>" .PHP_EOL;
            }
         } ?>
          </select>

          <a class="button-primary" disabled id="mlfp-update-access"><?php _e('Save Access','maxgalleria-media-library'); ?></a>			              
        </p>
        <p id="user-role-message"></p>

        <div id="folder-tree-container" class="user-access">
          <div id="alwrapnav">
            <div id="ajaxloadernav"></div>
          </div>

          <div id="above-toolbar"></div>

          <div id="ft-panel" style="position:static;top:0;">
            <ul id="folder-tree">

            </ul>
          </div>				
        </div>				

        <?php    
          $uploads_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
          $folders = array();
          $folders = $this->get_folder_data($uploads_folder_id, false, false);
          //error_log(print_r($folders, true));

        ?> 

      </div><!--mgmlp-library-container-->
    </div>    
  </div>    
	<script>
  var new_role = false;
  var folders = <?php echo json_encode($folders); ?>;
	jQuery(document).ready(function(){
    
      jQuery('.blank-nav').hide();
      jQuery('.active-nav').show();
    
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
//          'types': {
//            'types' : {
//              'file' : {
//                'icon' : {
//                  'image' : '/wp-content/plugins/media-library-plus-pro/images/closedimage.png'
//                }
//              },
//              'default' : {
//                'icon' : {
//                  'image' : '/wp-content/plugins/media-library-plus-pro/images/closedimage.png'
//                },
//                'valid_children' : 'default'
//              }
//            }
//          
//          },        
        'types' : {
          'default' : { 'icon' : 'folder' },
          'file' : { 'icon' :'folder'},
          'valid_children' : {'icon' :'folder'}	 
        },
        'sort' : function(a, b) {
          return this.get_type(a).toLowerCase() === this.get_type(b).toLowerCase() ? (this.get_text(a).toLowerCase() > this.get_text(b).toLowerCase() ? 1 : -1) : (this.get_type(a).toLowerCase() >= this.get_type(b).toLowerCase() ? 1 : -1);
        },			
        'checkbox': {
        tie_selection: false
//            three_state: false,
//            cascade: 'down'
        },
        'plugins' : [ 'sort','types','checkbox','ui']
        
      });
                  
      jQuery("#sync-status").text('Ready');
      jQuery("#cloud-loading-gif").hide();
      jQuery("#ajaxloadernav").hide();
                
      jQuery(document).on("click","#mlfp-update-access",function(){
        
        var folders_allowed = jQuery('#folder-tree').jstree("get_checked");
        var parents = jQuery('#folder-tree').jstree().get_undetermined();
        
        var role_id = jQuery('#user-role-list').val();
				jQuery("#user-role-message").html('');
        
        //if(folders_allowed.length < 1) {
        //  alert("<?php _e('No folders selected.', 'maxgalleria-media-library'); ?>");
        //  return false;          
        //}
        
        if(role_id == null) {
          alert("<?php _e('No user role selected.', 'maxgalleria-media-library'); ?>");
          return false;
        }
          
        console.log(role_id);
        jQuery("#ajaxloader").show();
				var serial_folders_allowed = JSON.stringify(folders_allowed.join());
				var serial_parents = JSON.stringify(parents.join());
        
        // get_path could not be used
        //jQuery('#folder-tree').jstree().get_path(2937, false, true);
        //jQuery('#folder-tree').jstree().get_path(jQuery('#folder-tree').jstree("get_selected", true)[0], ' > ')
        //console.log(folders_allowed);
        
				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "mlfp_save_role_access", new_role: new_role, role_id: role_id, serial_folders_allowed: serial_folders_allowed, serial_parents: serial_parents, nonce: mgmlp_ajax.nonce },
					url : mgmlp_ajax.ajaxurl,
					dataType: "html",
					success: function (data) {
						jQuery("#ajaxloader").hide();
						jQuery("#user-role-message").html(data);
					},
					error: function (err) { 
						jQuery("#ajaxloader").hide();
						alert(err.responseText);
					}
				});  
  
      });
      
      //jQuery("#user-role-list").change(function() {
      jQuery(document).on("change", "#user-role-list", function () {						
      
        jQuery("#user-role-message").html('');
        jQuery("#ajaxloader").show();
        jQuery('#folder-tree').jstree(true).uncheck_all();
        var role_id = jQuery('#user-role-list').val();
        
				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "mlfp_get_role_data", role_id: role_id, nonce: mgmlp_ajax.nonce },
					url : mgmlp_ajax.ajaxurl,
					dataType: "json",
					success: function (data) {
						jQuery("#ajaxloader").hide();
            //jQuery("#mlfp-update-access").prop('disabled', false);
            jQuery("#mlfp-update-access").attr("disabled", false);
            new_role = data.new_role;
            
            for (var i=0, total=data.folders.length; i < total; i++) {
              jQuery('#folder-tree').jstree(true).check_node(data.folders[i]);
            }            
					},
					error: function (err) { 
						jQuery("#ajaxloader").hide();
						alert(err.responseText);
					}
				});  
        
        
        
      });
      
      

      
      
//		  jQuery('#folder-tree').on('changed.jstree', function (e, data) {
//        var path = data.instance.get_path(data.node,'/');
//        console.log('Selected: ' + path); 
//      });            
      
	});  
  </script>  

		<?php