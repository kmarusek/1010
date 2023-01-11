jQuery(document).ready(function(){
	
//	var categories_visible = false;
//  var search_progress = true;
//  var bulk_move_status = false;
//  var allow_bulk_move = true;
//  var stop_bulk_move = false;
  //var click_to_edit_image = true;
  //window.click_to_edit_image = true;
	        			
		jQuery(document).on("click", "#mgmlp-new-category", function () {						
			if(jQuery("#category-box").is(":visible")) {
        jQuery("#category-box").slideUp(600);				
			} else {
        jQuery("#category-box").slideDown(200);				
			}
			
    });
				
		jQuery(document).on("click", "#mgmlp-add-category", function () {						
      var new_category = jQuery('#new-category-name').val();
			
			if(new_category.length < 1) {
        alert(mgmlp_ajax.blank_category);
        return false;								
			}
			
		  jQuery("#ajaxloader").show();
			
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mgmlp_add_new_category", new_category: new_category, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#folder-message").html(data);
          jQuery("#category-box").slideUp(600);													  
			    jQuery("#new-category-name").val('');
					jQuery("#ajaxloader").hide(); 
        },
        error: function (err)
          { alert(err.responseText);}
      });
			
		  jQuery("#ajaxloader").show();

			setTimeout(function(){	
				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "mlf_get_categories", image_id: '', nonce: mgmlp_ajax.nonce },
					url : mgmlp_ajax.ajaxurl,
					dataType: "html",
					success: function (data) {									
						jQuery("#category-list").html(data);
						jQuery("#ajaxloader").hide(); 
					},
					error: function (err) { 
						alert(err.responseText);
						jQuery("#ajaxloader").hide();          
					}
				});
			}, 3000);
			
						
    });
    
    jQuery("#filter_text").on("keyup", function(e) {
      
      console.log('keyCode',e.keyCode);
      
      if(e.keyCode != 12 && 
         e.keyCode != 32 &&
         e.keyCode != 37 &&
         e.keyCode != 38 &&
         e.keyCode != 39 &&
         e.keyCode != 40) {
   
        if(jQuery("#current-folder-id").val() === undefined) 
          var parent_folder = sessionStorage.getItem('folder_id');
        else
          var parent_folder = jQuery('#current-folder-id').val();

        var filter = jQuery("#filter_text").val();
        filter = filter.toLowerCase();
        console.log(filter);
        
        if(filter.length < 1) {
          mlf_refresh(parent_folder);
          return false;
        }
        var display_type = jQuery("#display_type").val();
        
        //var grid_list_switch = jQuery('input[type=checkbox]#grid-list-switch-view:checked').length > 0;        
        var grid_list_switch = jQuery('#grid-list-switch-view').val();
        grid_list_switch = (grid_list_switch == 'on')? true : false;        
        
        jQuery("#ajaxloader").show();
        
        jQuery.ajax({
          type: "POST",
          async: true,
          data: { action: "mgmlp_filter_images", folder_id: parent_folder, filter: filter, display_type: display_type, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
          url : mgmlp_ajax.ajaxurl,
          dataType: "html",
          success: function (data) {
            console.log(data);
            jQuery("#mgmlp-file-container").html(data); 
            jQuery("#ajaxloader").hide();
          },
          error: function (err){
              jQuery("#ajaxloader").hide();
              alert(err.responseText);            
            }
        });

   
      }
            
    });
    
    
    
    //jQuery("#new-folder-name").on("keyup", function(e) {
    //jQuery(document).on("keyup", "#new-folder-name", function (e) {
//    jQuery("#new-folder-name").keypress(function(e) {
//  
//      
//      console.log(e.keyCode)
//      if (e.keyCode == 13) {
//        console.log('Enter');
//        mlf_create_folder();
//      }
//    });
    
//    jQuery(document).on("click", "#mgmlp-create-new-folder", function () {
//                
//			jQuery("#folder-message").html('');			
//			
//			if(jQuery("#current-folder-id").val() === undefined) 
//	      var parent_folder = sessionStorage.getItem('folder_id');
//			else
//        var parent_folder = jQuery('#current-folder-id').val();
//
//      var new_folder_name = jQuery('#new-folder-name').val();
//      
//      new_folder_name = new_folder_name.trim();      
//      
//      if(new_folder_name.indexOf(' ') >= 0) {
//        alert(mgmlp_ajax.no_spaces);
//        return false;
//      }       
//			
//      if(new_folder_name.indexOf('"') >= 0) {
//        alert(mgmlp_ajax.no_quotes);
//        return false;
//      } 
//			
//      if(new_folder_name.indexOf("'") >= 0) {
//        alert(mgmlp_ajax.no_quotes);
//        return false;
//      } 
//      
//      if(new_folder_name == "") {
//        alert(mgmlp_ajax.no_blank);
//        return false;
//      } 
//      						
//      jQuery("#ajaxloader").show();
//      
//      jQuery.ajax({
//        type: "POST",
//        async: true,
//        data: { action: "create_new_folder", parent_folder: parent_folder, new_folder_name: new_folder_name,   nonce: mgmlp_ajax.nonce },
//        url : mgmlp_ajax.ajaxurl,
//        dataType: "json",
//        success: function (data) {
//				  jQuery('#new-folder-name').val('');	
//          jQuery("#ajaxloader").hide();          
//          jQuery("#folder-message").html(data.message);
//					jQuery("#new-folder-area").slideUp(600);
//					if(data.refresh) {
//						jQuery('#folder-tree').jstree(true).settings.core.data = data.folders;
//						jQuery('#folder-tree').jstree(true).refresh();			
//						jQuery('#folder-tree').jstree('select_node', '#' + parent_folder, true);
//						jQuery('#folder-tree').jstree('toggle_expand', '#' + parent_folder, true );
//				  }
//										
//        },
//        error: function (err)
//          { alert(err.responseText);}
//      });
//            
//    });
				
		//jQuery("#mlpp-add-to-ng-gallery").click(function(){
//    jQuery(document).on("click", "#mlpp-add-to-ng-gallery", function () {
//			
//			jQuery("#folder-message").html('');						
//      var gallery_image_ids = new Array();
//      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
//        gallery_image_ids[gallery_image_ids.length] = jQuery(this).attr("id");
//      });
//			if(gallery_image_ids.length > 0) {
//            
//				var serial_gallery_image_ids = JSON.stringify(gallery_image_ids.join());
//				var gallery_id = jQuery('#ng-gallery-select').val();
//
//				jQuery("#ajaxloader").show();
//
//				jQuery.ajax({
//					type: "POST",
//					async: true,
//					data: { action: "mg_add_to_ng_gallery", gallery_id: gallery_id, serial_gallery_image_ids: serial_gallery_image_ids, nonce: mgmlp_ajax.nonce },
//					url : mgmlp_ajax.ajaxurl,
//					dataType: "html",
//					success: function (data) {
//						jQuery("#ajaxloader").hide();
//						jQuery("#folder-message").html(data);
//						jQuery(".mgmlp-media").prop('checked', false);
//						jQuery(".mgmlp-folder").prop('checked', false);
//					},
//					error: function (err) { 
//						jQuery("#ajaxloader").hide();
//						alert(err.responseText);
//					}
//				});  
//			} else {
//				alert(mgmlp_ajax.no_images_selected);
//			}
//
//    });
    
    //jQuery("#select-media").click(function(){
//    jQuery(document).on("click", "#select-media", function () {
//      jQuery(".media-attachment, .mgmlp-media").prop("checked", !jQuery(".media-attachment").prop("checked"));
//    });
				            
    //jQuery("#mgmlp_ajax_upload").click(function(){
    jQuery(document).on("click", "#mgmlp_ajax_upload", function () {
        		
			jQuery("#folder-message").html('');			
			if(jQuery("#current-folder-id").val() === undefined) 
	      var folder_id = sessionStorage.getItem('folder_id');
			else
        var folder_id = jQuery('#current-folder-id').val();
												
			var mlp_title_text = jQuery('#mlp_title_text').val();      
			var mlp_alt_text = jQuery('#mlp_alt_text').val();      
																								
      //var folder_id = jQuery('#folder_id').val();      
      var file_data = jQuery('#fileToUpload').prop('files')[0];   
      var form_data = new FormData();                  
      
      form_data.append('file', file_data);
      form_data.append('action', 'upload_attachment');
      form_data.append('folder_id', folder_id);
			form_data.append('title_text', mlp_title_text);
			form_data.append('alt_text', mlp_alt_text);
      form_data.append('nonce', mgmlp_ajax.nonce);
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
          url : mgmlp_ajax.ajaxurl,
          dataType: 'html',  
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,                         
          type: 'post',
          success: function (data) {
            jQuery("#ajaxloader").hide();
            jQuery("#mgmlp-file-container").html(data);
            jQuery('#fileToUpload').val("");
          }
       });
            
    });
		
    //jQuery("#mlf-refresh").click(function(e){
    jQuery(document).on("click", "#mlf-refresh", function (e) {
      e.stopImmediatePropagation();                  
			jQuery("#folder-message").html('');			
				if(jQuery("#current-folder-id").val() === undefined) 
					var current_folder = sessionStorage.getItem('folder_id');
				else
					var current_folder = jQuery('#current-folder-id').val();
				
				
				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "mlp_get_folder_data", current_folder_id: current_folder, nonce: mgmlp_ajax.nonce },
					url: mgmlp_ajax.ajaxurl,
					dataType: "json",
					success: function (data) { 
						jQuery('#folder-tree').jstree(true).settings.core.data = data;
						jQuery('#folder-tree').jstree(true).refresh();			
						//jQuery('#folder-tree').jstree(true).redraw(true);


						jQuery("#folder-message").html('');
					},
					error: function (err){ 
						alert(err.responseText)
					}
				});
												
    });
		
        
    //jQuery("#delete-media").click(function(){
    jQuery(document).on("click", "#delete-media", function (e) {
        
			jQuery("#folder-message").html('');			

				if(jQuery("#current-folder-id").val() === undefined) 
					var current_folder = sessionStorage.getItem('folder_id');
				else
					var current_folder = jQuery('#current-folder-id').val();
				
        jQuery(".mgmlp-folder").prop('disabled', false);
        
        //jQuery('.input-area').each(function(index) {
        //  jQuery(this).slideUp(600);
        //});        
        
        var delete_ids = new Array();
        jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
          delete_ids[delete_ids.length] = jQuery(this).attr("id");
        });
								        
        if(delete_ids.length === 0) {
          alert(mgmlp_ajax.nothing_selected);
          return false;
        }
        if(confirm(mgmlp_ajax.confirm_file_delete)) {
          var serial_delete_ids = JSON.stringify(delete_ids.join());
          jQuery("#ajaxloader").show();
          jQuery.ajax({
            type: "POST",
            async: true,
            data: { action: "delete_maxgalleria_media", serial_delete_ids: serial_delete_ids, parent_id: current_folder, nonce: mgmlp_ajax.nonce },
            //var delete_data = jQuery.serialize(data);
            url : mgmlp_ajax.ajaxurl,
            dataType: "json",
            success: function (data) {
              jQuery("#ajaxloader").hide();            
              jQuery("#filter_text").val('');   
							jQuery("#folder-message").html(data.message);
							if(data.refresh)
								jQuery("#mgmlp-file-container").html(data.files);						
																																					
            },
            error: function (err)
              { alert(err.responseText);}
          });
      } 
    });	
        
    //jQuery("#copy-media").click(function(){
    jQuery(document).on("click", "#copy-media", function (e) {
      var copy_ids = new Array();
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
        copy_ids[copy_ids.length] = jQuery(this).attr("id");
      });
            
			jQuery("#folder-message").html('');			
      var serial_copy_ids = JSON.stringify(copy_ids.join());
      var folder_id = jQuery('#copy-select').val();
      var destination = jQuery("#copy-select option:selected").text();
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "copy_media", folder_id: folder_id, destination: destination, serial_copy_ids: serial_copy_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "json",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#filter_text").val('');   
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery(".mgmlp-folder").prop('checked', false);
          jQuery("#folder-message").html(data.message);
					
        },
        error: function (err)
          { 
            jQuery("#ajaxloader").hide();
            alert(err.responseText);
          }
      });                
    });	
    
    //jQuery("#move-media").click(function(){
    jQuery(document).on("click", "#move-media", function (e) {
      var move_ids = new Array();
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
        move_ids[move_ids.length] = jQuery(this).attr("id");
      });
            
      var serial_copy_ids = JSON.stringify(move_ids.join());
      var folder_id = jQuery('#move-select').val();
      var destination = jQuery("#move-select option:selected").text();
      //var current_folder = jQuery("#current-folder-id").val();      
			
			if(jQuery("#current-folder-id").val() === undefined) 
				var current_folder = sessionStorage.getItem('folder_id');
			else
				var current_folder = jQuery('#current-folder-id').val();
			      
			jQuery("#folder-message").html('');			
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "move_media", current_folder: current_folder, folder_id: folder_id, destination: destination, serial_copy_ids: serial_copy_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "json",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#filter_text").val('');   
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery(".mgmlp-folder").prop('checked', false);
          jQuery("#folder-message").html(data.message);
					if(data.refresh)
					  jQuery("#mgmlp-file-container").html(data.files);						
					
        },
        error: function (err)
          { 
            jQuery("#ajaxloader").hide();
            alert(err.responseText);
          }
      });                
    });	
        
	
    //jQuery("#add-to-gallery").click(function(){
    jQuery(document).on("click", "#add-to-gallery", function (e) {
			
			jQuery("#folder-message").html('');			
			
      var gallery_image_ids = new Array();
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
        gallery_image_ids[gallery_image_ids.length] = jQuery(this).attr("id");
      });
			
			if(gallery_image_ids.length > 0) {
            
				var serial_gallery_image_ids = JSON.stringify(gallery_image_ids.join());
				var gallery_id = jQuery('#gallery-select').val();

				jQuery("#ajaxloader").show();

				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "add_to_max_gallery", gallery_id: gallery_id, serial_gallery_image_ids: serial_gallery_image_ids, nonce: mgmlp_ajax.nonce },
					url : mgmlp_ajax.ajaxurl,
					dataType: "html",
					success: function (data) {
						jQuery("#ajaxloader").hide();
						jQuery("#folder-message").html(data);
						jQuery(".mgmlp-media").prop('checked', false);
						jQuery(".mgmlp-folder").prop('checked', false);
					},
					error: function (err) { 
						jQuery("#ajaxloader").hide();
						alert(err.responseText);
					}
				});  
			} else {
				alert(mgmlp_ajax.no_images_selected);
			}
    });	
    
    //jQuery("#mgmlp-rename-file").click(function(){
    jQuery(document).on("click", "#mgmlp-rename-file", function (e) {
      			
			jQuery("#folder-message").html('');			
			
			if(jQuery("#current-folder-id").val() === undefined) 
				var current_folder = sessionStorage.getItem('folder_id');
			else
				var current_folder = jQuery('#current-folder-id').val();
			
    
      //var current_folder = jQuery("#current-folder-id").val();      
      var image_id = 0;
      var new_file_name = jQuery('#new-file-name').val();
      
      new_file_name = new_file_name.trim();
      
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
        // only get the first one
        //if(image_id === 0)
          image_id = jQuery(this).attr("id");
      });
      
      if(new_file_name.indexOf(' ') >= 0 || new_file_name === '' ) {
        alert(mgmlp_ajax.valid_file_name);
        return false;
      }       
      
      if(new_file_name == "") {
        alert(mgmlp_ajax.no_blank_filename);
        return false;
      }                 
            
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "maxgalleria_rename_image", image_id: image_id, new_file_name: new_file_name, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#folder-message").html(data);
					jQuery('#new-file-name').val('');
          jQuery("#filter_text").val('');   
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery(".mgmlp-folder").prop('checked', false);
          jQuery('#rename-area').slideUp(600);
					mlf_refresh(current_folder);
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });                
      
    });	
    
	  jQuery(document).on("change", "#mgmlp-sort-order", function () {						
      var sort_order = jQuery('#mgmlp-sort-order').val();
			
			if(jQuery("#current-folder-id").val() === undefined) 
				var current_folder = sessionStorage.getItem('folder_id');
			else
				var current_folder = jQuery('#current-folder-id').val();
			      
      jQuery("#ajaxloader").show();
      
      jQuery("#filter_text").val('');   
            
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "sort_contents", sort_order: sort_order, folder: current_folder, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();
				  jQuery("#mgmlp-file-container").html(data); 
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });                
      
    });
		
	  jQuery(document).on("change", "#mgmlp-cat-sort-order", function () {						
      
      console.log('mgmlp-cat-sort-order');
						
      var sort_order = jQuery('#mgmlp-cat-sort-order').val();
      
      var grid_list_switch = jQuery('#grid-list-switch-view').val();
      grid_list_switch = (grid_list_switch == 'on')? true : false;        
      			
			var cat_ids = new Array();
			var cat_id = 0;
			jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
				cat_ids[cat_ids.length] = jQuery(this).attr("id");
			});			
			
			if(cat_ids.length === 0) {
				alert(mgmlp_ajax.no_categories_selected);
				return false;
			}

			jQuery("#ajaxloader").show(); 
			
		  var serial_cat_ids = JSON.stringify(cat_ids.join());
			      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "sort_categories", sort_order: sort_order, serial_cat_ids: serial_cat_ids, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
				  jQuery("#mgmlp-file-container").html(data); 
          jQuery("#ajaxloader").hide();
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });                
      
    });
				
//	  jQuery(document).on("change", "#move-copy-switch", function () {						
//			
//      var move_copy_switch = jQuery('input[type=checkbox]#move-copy-switch:checked').length > 0;
//			
//			if(move_copy_switch)
//				move_copy_switch = 'on'
//			else
//				move_copy_switch = 'off'
//            
//      jQuery.ajax({
//        type: "POST",
//        async: true,
//        data: { action: "mgmlp_move_copy", move_copy_switch: move_copy_switch, nonce: mgmlp_ajax.nonce },
//        url : mgmlp_ajax.ajaxurl,
//        dataType: "html",
//        success: function (data) {
//        },
//        error: function (err) { 
//          alert(err.responseText);
//        }
//      });                
//    });
    
//	  jQuery(document).on("change", "#grid-list-switch-view", function () {						
//      
//      //var grid_list_switch = jQuery('input[type=checkbox]#grid-list-switch-view:checked').length > 0;
//      var grid_list_switch = jQuery('#grid-list-switch-view').val();
//      //grid_list_switch = (grid_list_switch == '1')? true : false;        
//			
//			if(grid_list_switch == "1") {
//				grid_list_switch = 'on' // grid
//        jQuery("table.mgmlp-list").hide();        
//      } else {
//				grid_list_switch = 'off' // list
//        jQuery("table.mgmlp-list").show();        
//      }  
//      console.log('grid_list_switch 648', grid_list_switch);
//      
//			if(jQuery("#current-folder-id").val() === undefined) 
//				var current_folder = sessionStorage.getItem('folder_id');
//			else
//				var current_folder = jQuery('#current-folder-id').val();
//            
//      jQuery("#ajaxloader").show();
//                  
//      jQuery.ajax({
//        type: "POST",
//        async: true,
//        data: { action: "mgmlp_grid_list", grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
//        url : mgmlp_ajax.ajaxurl,
//        dataType: "html",
//        success: function (data) {
//          jQuery("#filter_text").val('');   
//          mlf_refresh(current_folder);
//        },
//        error: function (err) { 
//          jQuery("#ajaxloader").hide();
//          alert(err.responseText);
//        }
//      });                
//      
//      
//    });
        
	  jQuery(document).on("mouseenter", "#above-toolbar a", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#above-toolbar a", function () {						
       jQuery('#folder-message').html('');
    });
            
    //jQuery('#mgmlp-toolbar a').hover(function() {
    //   jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    //}, function() {
    //   jQuery('#folder-message').html('');
    //});
    
	  jQuery(document).on("mouseenter", "#mgmlp-toolbar a", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#mgmlp-toolbar a", function () {						
       jQuery('#folder-message').html('');
    });
    		    
	  jQuery(document).on("mouseenter", "#wp-gallery-area a", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#wp-gallery-area a", function () {						
       jQuery('#folder-message').html('');
    });
    		    
	  jQuery(document).on("mouseenter", "#category-area a", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#category-area a", function () {						
       jQuery('#folder-message').html('');
    });
        				    
	  jQuery(document).on("mouseenter", "#mgmlp-toolbar .onoffswitch", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#mgmlp-toolbar .onoffswitch", function () {						
       jQuery('#folder-message').html('');
    });    
    
	  jQuery(document).on("mouseenter", "#mgmlp-toolbar .onoffswitch-view", function () {						
       jQuery('#folder-message').html(jQuery(this).attr('help')).fadeIn(200);
    });

	  jQuery(document).on("mouseleave", "#mgmlp-toolbar .onoffswitch-view", function () {						
       jQuery('#folder-message').html('');
    });    
        		
    //jQuery("#sync-media").click(function(){      
    jQuery(document).on("click", "#sync-media", function (e) {
      
			if(jQuery("#current-folder-id").val() === undefined) 
				var parent_folder = sessionStorage.getItem('folder_id');
			else
				var parent_folder = jQuery('#current-folder-id').val();
			
			var mlp_title_text = jQuery('#mlp_title_text').val();
			
			var mlp_alt_text = jQuery('#mlp_alt_text').val();      
      						
			//jQuery("#folder-message").html('Scanning for new files and folders...please wait.');						
			
      //jQuery("#ajaxloader").show();
      
		  run_sync_process('1', parent_folder, mlp_title_text, mlp_alt_text);
                 						
    });
    
    jQuery(document).on("click", "#located-uncateloged-files", function (e) {
                              
      var data = jQuery(this).attr("data");
      if(data == "disabled" ) {
        return false;          
      }        
            			      						
			jQuery("#folder-message").html('');						
      jQuery("#scan_progress .progress .bar").css("width", "0%");
      
      var folder_count = jQuery("#folder-count").val();
      
      console.log('folder_count',folder_count);
      
      jQuery("#located-uncateloged-files").attr('data','disabled');
      jQuery("#located-uncateloged-files").attr('disabled','disabled');    
      
      jQuery("#stop-search").attr('disabled',false);    
      jQuery("#stop-search").attr('data','');    
            			
      jQuery("#ajaxloader").show();
      
      empty_purge_table();
      
      jQuery("#scan_progress").show();
                  
		  run_file_detect_process(1, folder_count);
                 						
    });
    
    jQuery(document).on("click", "#stop-search", function (e) {
      
      var data = jQuery(this).attr("data");
      if(data == "disabled" ) {
        return false;          
      }
            
      jQuery("#resume-search").attr('disabled',false);    
      jQuery("#resume-search").attr('data','');    
      
      jQuery("#stop-search").attr('disabled','disabled');    
      jQuery("#stop-search").attr('data','disabled');    
      jQuery("#scan_progress").hide();
      
      window.search_progress = false;        
      
      jQuery("#scan_progress").hide();      
      
    });
    
    jQuery(document).on("click", "#resume-search", function (e) {
      
      var data = jQuery(this).attr("data");
      if(data == "disabled" ) {
        return false;          
      }
                 
      jQuery("#stop-search").attr('disabled',false);    
      jQuery("#stop-search").attr('data','');    
      
      jQuery("#resume-search").attr('disabled','disabled');    
      jQuery("#resume-search").attr('data','disabled');    
            
      window.search_progress = true;        
      var folder_count = jQuery("#folder-count").val();
      var last_folder = jQuery("#last-folder").val();
            
      jQuery("#scan_message").show();
      jQuery("#upload_message").html('Scanning folders... Plese wait.')					
            
      jQuery("#folder-message").text("");      
      jQuery("#uncateloged-files").html('');
      
      jQuery("#mlfp_process_message").hide();
      jQuery("#scan_progress").show();      
            
		  run_file_detect_process(last_folder, folder_count);    
    });
        									
    //jQuery("#seo-images").click(function(){			
    jQuery(document).on("click", "#seo-images", function (e) {
			jQuery("#folder-message").text("");
    });    
				
    //jQuery("#mgmlp-regen-thumbnails").click(function(){
    jQuery(document).on("click", "#mgmlp-regen-thumbnails", function (e) {
      var image_ids = new Array();
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {   
        image_ids[image_ids.length] = jQuery(this).attr("id");
      });
			
			if(image_ids.length < 1) {
        jQuery("#folder-message").html("No files were selected.");
				return false;
			}	
			            
      var serial_image_ids = JSON.stringify(image_ids.join());
      
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "regen_mlp_thumbnails", serial_image_ids: serial_image_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery("#folder-message").html(data);
        },
        error: function (err)
          { 
            jQuery("#ajaxloader").hide();
            alert(err.responseText);
          }
      });                
    });
				
		//jQuery("#mlp-update-seo-settings").click(function(){
    jQuery(document).on("click", "#mlp-update-seo-settings", function (e) {
						
			var checked = "off";
			if(jQuery("#seo-images").is(":checked")) {
				checked = 'on';
			}
			var default_alt = jQuery("#default-alt").val();
			var default_title = jQuery("#default-title").val();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlp_image_seo_change", checked: checked, default_alt: default_alt, default_title: default_title, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#folder-message").html(data);
        },
        error: function (err)
          { 
            alert(err.responseText);
          }
      });                
			 			 
		});
						
		jQuery("#mgmlp-file-container").on("click", "#display_mlpp_images", function(e){
      e.stopImmediatePropagation();
      
      console.log('mgmlp display_mlpp_images');
			var folder_id = jQuery(this).attr('folder_id');
			var image_link = jQuery(this).attr('image_link');
      jQuery("#display_type").val('1');
      jQuery("#ajaxloader").show();
      
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlp_display_folder_contents_ajax", current_folder_id: folder_id, image_link: image_link, display_type: 1, nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) 
					{ 
            jQuery("#ajaxloader").hide();
						jQuery("#mgmlp-file-container").html(data); 
					},
				error: function (err){ 
          alert(err.responseText)}
				});
    });
		
		jQuery("#mgmlp-file-container").on("click", "#display_mlpp_titles", function(e){
      e.stopImmediatePropagation();
			var folder_id = jQuery(this).attr('folder_id');
			var image_link = jQuery(this).attr('image_link');
      jQuery("#display_type").val('2');
						
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlp_display_folder_contents_images_ajax", current_folder_id: folder_id, image_link: image_link, display_type: 2, nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) 
					{ 
						jQuery("#mgmlp-file-container").html(data); 						
					},
						error: function (err)
					{ alert(err.responseText)}
					});
    });
		
		jQuery("#mgmlp-file-container").on("click", "#mlfp_display_category_images", function(){
			
			var cat_id = jQuery(this).attr('cat_id');
			
		  var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;
						
      jQuery("#ajaxloader").show();
						
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlp_load_categories_ajax", cat_id: cat_id, display_type: 1, mif_visible: mif_visible, nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) 
					{ 
						jQuery("#mgmlp-file-container").html(data); 
            jQuery("#ajaxloader").hide();
					},
						error: function (err)
					{ alert(err.responseText)}
					});
    });
		
		jQuery("#mgmlp-file-container").on("click", "#mlfp_display_category_titles", function(){
			
			var cat_id = jQuery(this).attr('cat_id');
			
      jQuery("#ajaxloader").show();
						
			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlp_load_categories_ajax", cat_id: cat_id, display_type: 2, nonce: mgmlp_ajax.nonce },
				url: mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) 
					{ 
						jQuery("#mgmlp-file-container").html(data); 
            jQuery("#ajaxloader").hide();
						
					},
						error: function (err)
					{ alert(err.responseText)}
					});
    });
			
    //jQuery("#mgmlp-create-new-gallery").click(function(){
    jQuery(document).on("click", "#mgmlp-create-new-gallery", function (e) {
      
			jQuery("#folder-message").html('');			
			
      var new_gallery_name = jQuery('#new-gallery-name').val();
      //var parent_folder = jQuery('#current-folder-id').val();
			
			if(jQuery("#current-folder-id").val() === undefined) 
				var parent_folder = sessionStorage.getItem('folder_id');
			else
				var parent_folder = jQuery('#current-folder-id').val();
			
      
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlpp_create_new_ng_gallery", new_gallery_name: new_gallery_name, parent_folder: parent_folder, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();          
          jQuery("#folder-message").html(data);
        },
        error: function (err)
          { alert(err.responseText);}
      });
           	
  });  
		
	//jQuery("#mgmlp-select-category").click(function(){
  jQuery(document).on("click", "#mgmlp-select-category", function (e) {
	  
			jQuery("#folder-message").html('');			
		
      var image_ids = new Array();
			var image_id;
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
        image_ids[image_ids.length] = jQuery(this).attr("id");
        image_id = jQuery(this).attr("id");				
      });
									
			if(image_ids.length < 1 && window.categories_visible) {
				jQuery("#category-area").slideUp(600);
			  window.categories_visible = false;
				return false;
			}	
			
			window.categories_visible = true;
							
		  jQuery("#ajaxloader").show();
			
				jQuery.ajax({
					type: "POST",
					async: true,
					data: { action: "mlf_get_categories", image_id: image_id, nonce: mgmlp_ajax.nonce },
					url : mgmlp_ajax.ajaxurl,
					dataType: "html",
					success: function (data) {									
            jQuery("#category-list").html(data);
						jQuery("#ajaxloader").hide(); 
					},
					error: function (err) { 
						alert(err.responseText);
						jQuery("#ajaxloader").hide();          
					}
				});
	
  });    
	
	//jQuery("#mgmlp-get_categories").click(function(){
  jQuery(document).on("click", "#mgmlp-get_categories", function (e) {
		
		var image_ids = new Array();
		var image_id = 0;
		jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
			image_ids[image_ids.length] = jQuery(this).attr("id");
			image_id = jQuery(this).attr("id");				
		});
		
		if(image_ids.length < 1) {
			alert(mgmlp_ajax.nothing_selected);				
		} else if(image_ids.length > 1) {
			jQuery(".media-attachment, .mgmlp-media").prop("checked", false);
			alert(mgmlp_ajax.select_only_one);				
			jQuery("#category-area").slideUp(600);
		} else {
			
			jQuery("#ajaxloader").show();          			

			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlf_get_categories", image_id: image_id, nonce: mgmlp_ajax.nonce },
				url : mgmlp_ajax.ajaxurl,
				dataType: "html",
				success: function (data) {									
					jQuery("#category-list").html(data);
					jQuery("#ajaxloader").hide(); 
				},
				error: function (err) { 
					alert(err.responseText);
					jQuery("#ajaxloader").hide();          
				}
			});
					
		}
		
  });    
	
	//jQuery("#mgmlp-set_categories").click(function(){
  jQuery(document).on("click", "#mgmlp-set_categories", function (e) {

		var image_ids = new Array();
		jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
			image_ids[image_ids.length] = jQuery(this).attr("id");
		});

		if(image_ids.length === 0) {
			alert(mgmlp_ajax.nothing_selected);
			return false;
		}

		var cat_ids = new Array();
		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
			cat_ids[cat_ids.length] = jQuery(this).attr("id");
		});
		
//		if(cat_ids.length === 0) {
//			alert(mgmlp_ajax.no_categories_selected);
//			return false;
//		}
		
		jQuery("#ajaxloader").show(); 
				
		var serial_image_ids = JSON.stringify(image_ids.join());
		var serial_cat_ids = JSON.stringify(cat_ids.join());
		
		jQuery.ajax({
			type: "POST",
			async: true,
			data: { action: "mlf_set_new_categories", serial_image_ids: serial_image_ids, serial_cat_ids: serial_cat_ids, nonce: mgmlp_ajax.nonce },
			url : mgmlp_ajax.ajaxurl,
			dataType: "html",
			success: function (data) {									
			  jQuery("#folder-message").html(data);			
				jQuery("#ajaxloader").hide(); 
			},
			error: function (err) { 
				alert(err.responseText);
				jQuery("#ajaxloader").hide();          
			}
		});
		
  });  
	
	
  jQuery(document).on("click", "#mgmlp-view-categories", function (e) {
	
		var cat_ids = new Array();
		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
			cat_ids[cat_ids.length] = jQuery(this).attr("id");
		});
		
		if(cat_ids.length === 0) {
			alert(mgmlp_ajax.no_categories_selected);
			return false;
		}
		
		jQuery("#ajaxloader").show(); 
		
		var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;
						
		var serial_cat_ids = JSON.stringify(cat_ids.join());
    
    //var grid_list_switch = jQuery("input[type=checkbox]#grid-list-switch-view:checked").length > 0;
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on')? true : false;        
    
    console.log('grid_list_switch',grid_list_switch);
    if(!grid_list_switch) {
      
      var page_id = 0;
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlp_load_categories_list", serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: page_id, nonce: mgmlp_ajax.nonce },
        url: mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) 
        { 
          jQuery("#mgmlp-file-container").html(data); 
          jQuery("#folder-message").html(''); 				
          jQuery("#ajaxloader").hide(); 
        },
          error: function (err)
        { alert(err.responseText)}
      });
                 
    } else {
		
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlp_load_categories", serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: page_id, nonce: mgmlp_ajax.nonce },
        url: mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) 
        { 
          jQuery("#mgmlp-file-container").html(data); 
          jQuery("#folder-message").html(''); 				
          jQuery("#ajaxloader").hide(); 
        },
          error: function (err)
        { alert(err.responseText)}
      });

    }

	});  
  
//  jQuery(document).on("click", "#mlfp-previous-cats, #mlfp-next-cats", function (e) {
//    
//    console.log('mlfp-next-cats');
//    
//		jQuery("#ajaxloader").show(); 
//    
//		var cat_ids = new Array();
//		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
//			cat_ids[cat_ids.length] = jQuery(this).attr("id");
//		});
//		
//		if(cat_ids.length === 0) {
//			alert(mgmlp_ajax.no_categories_selected);
//			return false;
//		}
//    		
//    var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;						
//    var serial_cat_ids = JSON.stringify(cat_ids.join());
//    var page_id = jQuery(this).attr("data-page-id");    
//    var image_link = jQuery(this).attr("image_link");
//           
//    jQuery.ajax({
//      type: "POST",
//      async: true,
//      data: { action: "mlp_load_categories_list", serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: page_id , nonce: mgmlp_ajax.nonce },
//      url: mgmlp_ajax.ajaxurl,
//      dataType: "html",
//      success: function (data) 
//      { 
//        jQuery("#mgmlp-file-container").html(data); 
//        jQuery("#folder-message").html(''); 				
//        jQuery("#ajaxloader").hide(); 
//      },
//        error: function (err)
//      { alert(err.responseText)}
//    });
//       
//  
//	});  
    
  jQuery(document).on("click", ".mgmlp-media", function (e) {
    var current_element = jQuery(this);
    var check_next = false;
    var search_for_next_checked_item = false;
    var shift_after = false;
    var shiftHeld = e.shiftKey;    
    if(shiftHeld) {
      jQuery('input[type=checkbox].mgmlp-media').each(function() {          
        if(!search_for_next_checked_item && !shift_after && jQuery(this).is(':checked') && current_element.is(this)) {
          search_for_next_checked_item = true;
          return; // continue the each loop
        } else if(search_for_next_checked_item) {  
            if(jQuery(this).is(':checked'))
              return false;
            jQuery(this).prop('checked', true);
        } else {                    
          if(!check_next && jQuery(this).is(':checked')) {
            check_next = true;
            shift_after = true;
          } else if(current_element.is(this)) {
            return false;
          } else if(check_next) {
            jQuery(this).prop('checked', true);
          }
        }
      });
    }
  }); 
  
  //jQuery("#mlfp_clear").click(function(){
  jQuery(document).on("click", "#mlfp_clear", function (e) {
    clear_filter_and_refresh_page();    
  }); 
  
  //jQuery("#mlfp-display-filter-area").click(function(){
  jQuery(document).on("click", "#mlfp-display-filter-area", function (e) {
		if(jQuery("#filter-area").is(":visible")) {
      jQuery("#filter_text").val('');   
      clear_filter_and_refresh_page();    
    }  
  }); 
                
  //jQuery("#generate-shortcode").click(function(){
  jQuery(document).on("click", "#generate-shortcode", function (e) {

    var shortcode = '';

    var embed_file_type = jQuery("#embed-file-type").val();
    
    var allowed_types = ['pdf','mpeg','mp3','oga','wav','mp4','webm','ogg','ogv'];
    //console.log('allowed_types', allowed_types, embed_file_type);
    if(jQuery.inArray(embed_file_type, allowed_types) == -1) {
      //console.log('not in the array');
      alert(embed_file_type + mgmlp_ajax.filetype_not_allowed);
      return false;
    }  
    
    var embed_file_url = jQuery("#embed-file-url").val();
    
    var file_type = jQuery("#embed-file-type").val();
    var embed_type = jQuery("#embed-type").val(); // only for PDF
    //if(embed_type.length < 1)
    //  embed_type = jQuery("select#embed-type option").filter(":selected").val();
    var embed_ogg_type = jQuery('input[name="ogg-type"]:checked').val();
    console.log('embed_ogg_type',embed_ogg_type);
    if(file_type == 'ogg') {
      if(embed_ogg_type == 'audio')
        file_type = 'ogg-audio';
    }
    
    var embed_file_width = jQuery("#embed-file-width").val();
    var embed_file_height = jQuery("#embed-file-height").val();
    var embed_align = jQuery("select#embed-align option").filter(":selected").val();
	  var embed_autoplay = jQuery('#embed-autoplay:checkbox:checked').length > 0;
	  var embed_controls = jQuery('#embed-controls:checkbox:checked').length > 0;
	  var embed_loop = jQuery('#embed-loop:checkbox:checked').length > 0;
	  var embed_muted = jQuery('#embed-muted:checkbox:checked').length > 0;
    var embed_preload = jQuery("select#embed-preload option").filter(":selected").val();
    var embed_poster  = jQuery("#embed-poster").val();

    shortcode = '[mlfp-embed-file filetype="'+file_type+'" url="'+embed_file_url+'" ';
    
    if(file_type == 'pdf') {
      shortcode += 'embed-type="'+embed_type+'" ';      
    }  

    if(file_type == 'pdf' || file_type == 'mp4' || file_type == 'ogg' || file_type == 'ogv' || file_type == 'webm') {
      if(embed_file_width.length > 0) {
        if(!embed_file_width.includes('px') && !  embed_file_width.includes('%') )
          shortcode += 'width="'+embed_file_width+'px'+'" ';
        else  
          shortcode += 'width="'+embed_file_width+'" ';
      }

      if(embed_file_height.length > 0) {
        if(!embed_file_height.includes('px') && !embed_file_height.includes('%') )
          shortcode += 'height="'+embed_file_height+'px'+'" ';
        else  
          shortcode += 'height="'+embed_file_height+'" ';
      }
    }  

    if(file_type == 'pdf') {
      if(embed_align.length > 0) {
        if(embed_align != 'none') {
          shortcode += 'align="'+embed_align+'" ';
        }
      }
    }
    
    if(jQuery("#embed-autoplay").is(":visible") && embed_autoplay) {
      shortcode += 'autoplay="true" ';
    }
    
    if(jQuery("#embed-controls").is(":visible") && embed_controls) {
      shortcode += 'controls="true" ';
    }
    
    if(jQuery("#embed-loop").is(":visible") && embed_loop) {
      shortcode += 'loop="true" ';      
    }
    
    if(jQuery("#embed-muted").is(":visible") && embed_muted) {
      shortcode += 'muted="true" ';          
    }
    
    if(jQuery("#embed-preload").is(":visible") && embed_preload) {
      shortcode += 'preload="'+embed_preload+'" ';
    }
    
    if(jQuery("#embed-poster").is(":visible") && embed_poster) {
      shortcode += 'poster="'+embed_poster+'" ';                      
    }
    
    shortcode += ']';
    //console.log(shortcode);
    jQuery("#embed-shortcode-container").val(shortcode);
    
    jQuery("#copy-shortcode").removeAttr('disabled');    
    jQuery("#copy-shortcode").removeClass('disabled-button');    

  });
  
  //jQuery("#copy-shortcode").click(function(){
  jQuery(document).on("click", "#copy-shortcode", function (e) {
    
    var copy_text = document.getElementById("embed-shortcode-container");

    copy_text.select();
    copy_text.setSelectionRange(0, 99999);

    document.execCommand("copy");    
    
    jQuery("#copy-message").html(mgmlp_ajax.copy_message);
    
  });
  
  
  //jQuery("#ogg-audio").click(function(){
  jQuery(document).on("click", "#ogg-audio", function (e) {
    jQuery(".mlfp-video-options").hide();   
    jQuery(".mlfp-audio-options").show();   
  });
  
  //jQuery("#ogg-video").click(function(){
  jQuery(document).on("click", "#ogg-video", function (e) {
    jQuery(".mlfp-audio-options").hide();   
    jQuery(".mlfp-video-options").show();   
  });
  
  jQuery(document).on("click", "#copy-jp-shortcode", function (e) {
    
    var copy_text = document.getElementById("jetpack-shortcode-container");

    copy_text.select();
    copy_text.setSelectionRange(0, 99999);

    document.execCommand("copy");    
    
    jQuery("#jp-copy-message").html(mgmlp_ajax.copy_message);
    
  });
    
  jQuery(document).on("click", "#mgmlp-wp-galleries", function (e) {
        
    // close when visible
    if(jQuery("#wp-gallery-area").is(":visible")) {      
      //jQuery("#wp-gallery-area").slideUp(600);
      jQuery("#copy-jp-shortcode").attr("disabled","disabled");
      jQuery("#copy-jp-shortcode").addClass("disabled-button");    
      jQuery("ul#mgmlp-gallery-list").empty();      
      jQuery("#jetpack-shortcode-container").val('');
    } else {
      //jQuery("#wp-gallery-area").slideDown(200);				      
    }
    
  });
            
  //jQuery("#mlfp-display-embed-area").click(function(){
  jQuery(document).on("click", "#mlfp-display-embed-area", function (e) {
    
    //console.log('mlfp-display-embed-area');
    
    // close when visible
    if(jQuery('#embed-area').is(":visible")) {      
      jQuery('#embed-area').slideUp(600);
      jQuery("#copy-shortcode").attr('disabled','disabled');
      jQuery("#copy-shortcode").addClass('disabled-button');    
      jQuery("#embed-shortcode-container").val('');      
      jQuery("#copy-message").val('');
      jQuery("#embed-poster").val('');
      jQuery('#embed-autoplay').prop('checked', false);
      jQuery('#embed-controls').prop('checked', false);
      jQuery('#embed-loop').prop('checked', false);
      jQuery('#embed-muted').prop('checked', false);
      jQuery('select#embed-preload>option:eq(0)').prop('selected', true);
      jQuery(".media-attachment, .mgmlp-media").prop("checked", false);
      return false;
    }
      
    var image_id = 0;
    jQuery("#embed-type-row").hide();   
    jQuery(".mlfp-video-options").hide();   
    jQuery(".mlfp-audio-options").hide();   
    jQuery(".mlfp-pdf-options").hide();   
    
    
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      // only get the first one
        image_id = jQuery(this).attr("id");
    });

    //console.log(image_id);

    if(image_id > 0) {

      jQuery("#ajaxloader").show();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "maxgalleria_get_file_url", image_id: image_id, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "json",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          //console.log(data); 
          jQuery("#embed-file-url").val(data.url);
          jQuery("#embed-file-type").val(data.app_type);
          //if(data.app_type == 'pdf') {
          //  jQuery("#embed-type-row").show();            
          //}
          
          if(data.app_type == 'ogg') {
            jQuery("#ogg-options").show();
          } else {
            jQuery("#ogg-options").hide();              
          }           
          
          switch(data.app_type) {
            case 'pdf':
              jQuery("#embed-type-row").show();            
              jQuery(".mlfp-pdf-options").show();
              break;
              
            case 'mpeg':
            case 'mp3':
            case 'oga':
            case 'wav':  
              jQuery(".mlfp-audio-options").show();            
              break;
            
            case 'mp4':  
            case 'webm':  
            case 'ogg':  
            case 'ogv':  
              jQuery(".mlfp-video-options").show();            
              break;
              
            default:   
              jQuery("#folder-message").html(data.app_type + mgmlp_ajax.filetype_not_allowed);
              break;
          }
          slideonlyone('embed-area');
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });                

    } else {
      alert(mgmlp_ajax.select_to_embed);
    } 
         
  });
  
  jQuery(document).on("click", "#mlfp-playlist-area", function (e) {
    
    if(jQuery('#playlist-area').is(":visible")) {      
      jQuery('#playlist-area').slideUp(600);
      window.click_to_edit_image = true;
      jQuery('#pl_attachment_ids').val('');
      jQuery('#playlist-shortcode-container').val('');
      jQuery('#folder-message').val('');
      jQuery('#pl-copy-message').val('');
      jQuery("#audio-playlist").prop("checked", true);
      jQuery("#copy-pl-shortcode").attr('disabled','disabled');
      jQuery("#copy-pl-shortcode").addClass('disabled-button');    
      return false;
    }
    slideonlyone('playlist-area');
    window.click_to_edit_image = false;
  
  });
  
  jQuery(document).on("click", "#audio-playlist, #video-playlist", function (e) {    
    console.log('playlist type');
    //jQuery('#pl_attachment_ids').text('');
    jQuery('#pl_attachment_ids').val('');
  });

  jQuery(document).on("click", ".edit-link", function (e) {
    console.log('click_to_edit_image',window.click_to_edit_image);
    
    var attachment_id = jQuery(this).attr("id");
    if(window.click_to_edit_image) {
      var new_tab = mgmlp_ajax.site_url + "/wp-admin/post.php?post=" + attachment_id + "&action=edit";
      console.log('new_tab',new_tab);
      newTab(new_tab);
    } else {
      console.log('attachment_id',attachment_id);
      
      var playlist_type = jQuery('input[name="list-type"]:checked').val();
            
      mime_type_test(attachment_id, playlist_type);
               
    }  
    
  });
  
  jQuery(document).on("click", "#generate-pl-shortcode", function (e) {
    var attachment_ids = jQuery("#pl_attachment_ids").val();
    
    if(attachment_ids.length < 1) {
      alert(mgmlp_ajax.no_ids_selected);
      return false;
    }
    
    var playlist_type = jQuery('input[name="list-type"]:checked').val();
    
    if(playlist_type == 'video')    
      var shortcode = '[playlist type="video" ids="' + attachment_ids + '"]';    
    else
      var shortcode = '[playlist ids="' + attachment_ids + '"]';    
    
    jQuery("#playlist-shortcode-container").val(shortcode);
    jQuery("#copy-pl-shortcode").removeAttr('disabled');    
    jQuery("#copy-pl-shortcode").removeClass('disabled-button');    
    
  });
  
  jQuery(document).on("click", "#copy-pl-shortcode", function (e) {
    
    var copy_text = document.getElementById("playlist-shortcode-container");

    copy_text.select();
    copy_text.setSelectionRange(0, 99999);

    document.execCommand("copy");    
    
    jQuery("#pl-copy-message").html(mgmlp_ajax.copy_message);
    
  });
  
  jQuery(document).on("click", "#mlfp-file-replace-area", function (e) {

    window.click_to_edit_image = true;
    if(jQuery('#file-replace-area').is(":visible")) {      
      jQuery('#file-replace-area').slideUp(600);      
      jQuery("#replacment_to_upload").val('');
      return false;
    }
    
    jQuery("#replacment_to_upload").val('');
    var image_id = 0;
    
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      // only get the first one
        image_id = jQuery(this).attr("id");
    });
    
    if(image_id > 0) {

      jQuery("#replace-file-id").val(image_id);
      jQuery("#ajaxloader").show();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "maxgalleria_get_file_url", image_id: image_id, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "json",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          console.log(data); 
          //jQuery("#replace-file-url").val(data.url);
          jQuery("#replace-mine-type").val(data.mine_type);
          jQuery("#replace-ext").val(data.app_type);
          
          //console.log('filename',filename);
          jQuery('#mlfp-rpl-selected-file').html('Seleted File: ' + data.basefile);
    
          slideonlyone('file-replace-area');
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });                
    
    } else {
      alert(mgmlp_ajax.select_to_replace);      
    }
    
  });
  
	jQuery(document).on("change", "#replacment_to_upload", function () {						
    
    jQuery("#ajaxloader").show();
    
    var filename = jQuery(this).val();
    var mine_type = jQuery('#replace-mine-type').val();        
    var replace_type = jQuery('input[name="replace-type"]:checked').val();
    var replace_ext = jQuery('#replace-ext').val();        
    var upload_ext = get_upload_extension(filename);

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "determine_mime_type", filename: filename, replace_type: replace_type, replace_ext: replace_ext, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {
        jQuery("#ajaxloader").hide();
        //console.log(data);                 
        if(mine_type != data.file_type) {
          jQuery("#replace-file-upload").attr('disabled','disabled');
          jQuery("#replace-file-upload").addClass('disabled-button');    
          alert(mgmlp_ajax.mime_mismatch);
          return false;
        } else if(replace_ext != upload_ext) {  
          alert(mgmlp_ajax.mime_mismatch);
          return false;
        } else {
          jQuery("#replace-file-upload").removeAttr('disabled');    
          jQuery("#replace-file-upload").removeClass('disabled-button');              
                          
        }

      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });                
      
  });
    
  jQuery(document).on("click", "#replace-file-upload", function () {

    jQuery("#folder-message").html('');			
    if(jQuery("#current-folder-id").val() === undefined) 
      var folder_id = sessionStorage.getItem('folder_id');
    else
      var folder_id = jQuery('#current-folder-id').val();

    var replace_file_id = jQuery('#replace-file-id').val();

    var replace_type = jQuery('input[name="replace-type"]:checked').val();
    //var replace_type = 'replace-only';
    var date_options = jQuery('input[name="date-options"]:checked').val();      												
    var mlp_title_text = jQuery('#replace-seo-file-title').val();      
    var mlp_alt_text = jQuery('#replace-seo-alt-text').val();      
    var custom_date = jQuery('#mlfp-custom-date').val();
    //var replace_file_url = jQuery('#replace-file-url').val();
    var replace_mine_type = jQuery('#replace-mine-type').val();

    var file_data = jQuery('#replacment_to_upload').prop('files')[0];   
    var form_data = new FormData();                  

    form_data.append('file', file_data);
    form_data.append('action', 'mlfp_replace_attachment');
    form_data.append('folder_id', folder_id);      
    form_data.append('replace_file_id', replace_file_id);
    form_data.append('replace_type', replace_type);
    form_data.append('date_options', date_options);      
    form_data.append('custom_date', custom_date);
    //form_data.append('replace_file_url', replace_file_url);
    form_data.append('replace_mine_type', replace_mine_type);
    form_data.append('title_text', mlp_title_text);
    form_data.append('alt_text', mlp_alt_text);      
    form_data.append('nonce', mgmlp_ajax.nonce);
    jQuery("#ajaxloader").show();

    jQuery.ajax({
        url : mgmlp_ajax.ajaxurl,
        dataType: 'html',  
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#mgmlp-file-container").html(data);
          jQuery('#replacment_to_upload').val("");
          jQuery('#file-replace-area').slideUp(600);
          jQuery("#ir-instructions").slideUp(200);
          jQuery("#display-ir-instructions").show();
          jQuery("#display-ir-instructions-close").hide();                        
          jQuery('#replace-only').prop('checked',true);
          jQuery('#mlfp-keep-date').prop('checked',true);
          jQuery('#mlfp-custom-date').val("");
          jQuery('#replace-only').prop('checked',true);
          jQuery('#mlfp-keep-date').prop('checked',true);
        }
     });

  });
      
  jQuery(document).on("click", "#mlfp-keep-date, #mlfp-update-date", function () {
    jQuery("#mlfp-custom-date").prop('disabled', true);   
    jQuery("#mlfp-custom-date").val('');
  });


  jQuery(document).on("click", "#mlfp-use-custom-date", function () {

    if(jQuery(this).is(':checked')) {
      jQuery("#mlfp-custom-date").prop('disabled', false);        
    } else {
      jQuery("#mlfp-custom-date").prop('disabled', true);                
    }

  });
  
//  jQuery(document).on("click", "#mlfp-reselect-folder", function () {
//    window.bulk_move_status = true;    
//    jQuery("#bulkmove-destination-folder").val(mgmlp_ajax.select_folder);      
//    jQuery("#mlfp-bulk-move-files").addClass("disabled-button");   
//    jQuery("#mlfp-bulk-move-files").attr('disabled','disabled');        
//  });
    
//  jQuery(document).on("click", "#mlfp-stop-file-move", function () {
//    window.allow_bulk_move = false;
//  });
//  
//  jQuery(document).on("click", "#mlfp-bulk-move-files", function (e) {
//    
//    e.stopImmediatePropagation();   
//    
//		jQuery("#folder-message").html(mgmlp_ajax.moving_files);    
//    
//    // promise array/counter will call refresh when done
//    var promisesArray = [];
//    var successCounter = 0;
//    var promise;			
//    
//    console.log("mlfp-bulk-move-files click")
//    
//    jQuery("#mlfp-bulk-move-files").hide();
//    jQuery("#mlfp-stop-file-move").show();    
//    
//    window.stop_bulk_move = false;
//    
//    jQuery("#ajaxloader").show();
//    
//    if(jQuery("#current-folder-id").val() === undefined) 
//      var current_folder = parseInt(sessionStorage.getItem('folder_id'));
//    else
//      var current_folder = parseInt(jQuery('#current-folder-id').val());
//    
//    var grid_list_switch = jQuery('input[type=checkbox]#grid-list-switch-view:checked').length > 0;
//    grid_list_switch = (grid_list_switch) ? "on" : "off";
//            
//    var file_count = 0;
//
//    var destination_folder_id = parseInt(jQuery("#bulkmove-destination-folder-id").val());
//    
//    var destination_folder_path = jQuery("#bulkmove-destination-folder-path").val();
//    
//    var file_count = 0;
//    
//    console.log('folder ids: ', destination_folder_id, current_folder);
//    if(destination_folder_id == current_folder) {
//      window.bulk_move_status = true;
//      window.allow_bulk_move = false;
//      jQuery("#mlfp-bulk-move-files").show();
//      jQuery("#mlfp-stop-file-move").hide();          
//      jQuery("#ajaxloader").hide();      
//      jQuery('#bulkmove-destination-folder').val(mgmlp_ajax.select_folder);      
//      alert(mgmlp_ajax.source_destination_error);
//      return false;
//    }
//    
//    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
//      
//      file_count++;
//      
//      if(window.stop_bulk_move == true) {
//        //refresh_file_contents(current_folder);
//        jQuery('#folder-tree').jstree('select_node', '#' + current_folder, true);        
//        return false;
//      }
//                  
//      var file_id = jQuery(this).attr("id");
//      
//      //mlfp_move_single_file(file_id, destination_folder_id, current_folder, destination_folder_path);
//      
//			promise = 
//      jQuery.ajax({
//        type: "POST",
//        async: true,
//        data: { action: "mlfp_move_single_file", file_id: file_id, folder_id: destination_folder_id, current_folder: current_folder, destination_folder_path: destination_folder_path, nonce: mgmlp_ajax.nonce },
//        url: mgmlp_ajax.ajaxurl,
//        dataType: "html",
//        success: function (data) { 
//          jQuery("#folder-message").html(data);      
//        },
//        error: function (err){ 
//          alert(err.responseText)
//        }
//      });  
//  
//      promise.done(function(msg) {
//        successCounter++;
//      });
//
//      promise.fail(function(jqXHR) { /* error out... */ });
//
//      promisesArray.push(promise);
//      
//    });
//    
//    jQuery.when.apply($, promisesArray).done(function() {
//      jQuery("#mlfp-bulk-move-files").show();
//      jQuery("#mlfp-stop-file-move").hide();          
//      if(file_count < 1) {
//        alert(mgmlp_ajax.nothing_selected);
//        jQuery("#ajaxloader").hide();
//        return false;      
//      } else {
//         jQuery("#bulk-move-area").slideUp(600);        
//        //refresh_file_contents(current_folder, grid_list_switch);
//        jQuery('#folder-tree').jstree(true).deselect_all(true);
//        jQuery('#folder-tree').jstree('select_node', '#' + current_folder, true);
//        
//      }      
//    });
//    
//  });
//    
//  jQuery(document).on("click", "#mlfp-stop-file-move", function () {    
//    window.stop_bulk_move = true;    
//    jQuery("#mlfp-stop-file-move").hide();
//    jQuery("#mlfp-bulk-move-files").show();    
//		jQuery("#folder-message").html(mgmlp_ajax.copying_stopped);
//		jQuery("#ajaxloader").hide();    
//  });
//  
//  jQuery(document).on("click", "#mgmlp-bulk-move", function () {
//    
//    window.allow_bulk_move = true;
//    
//    if(jQuery("#bulk-move-area").is(":visible")) {  
//      window.bulk_move_status = false;
//      jQuery("#bulkmove-destination-folder").val('');
//      jQuery("#bulkmove-destination-folder-id").val('');
//      jQuery("#bulkmove-destination-folder-path").val('');   
//      jQuery("#mlfp-stop-file-move").hide();
//      jQuery("#mlfp-bulk-move-files").show();      
//    } else {
//      jQuery('#bulkmove-destination-folder').val(mgmlp_ajax.select_folder);
//      window.bulk_move_status = true;
//    }    
//  
//  });
  
}); // document ready

function refresh_file_contents(current_folder, grid_list_switch) {
  
  console.log('mgmlp refresh_file_contents');  
  
  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlp_display_folder_contents_ajax", current_folder_id: current_folder, image_link: 1, display_type: 1, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "html",
    success: function (data) 
    { 
      jQuery("#ajaxloader").hide();
      jQuery("#mgmlp-file-container").html(data); 
    },
      error: function (err)
    { alert(err.responseText)}
  });
}          


function newTab(link) {
  var form = document.createElement("form");
  form.method = "POST";
  form.action = link;
  form.target = "_blank";
  document.body.appendChild(form);
  form.submit();
}
                			       
function slideonlyone(thechosenone) {
  jQuery('.input-area').each(function(index) {
    if (jQuery(this).attr("id") == thechosenone) {
			 if(jQuery(this).is(":visible")) {
         jQuery(this).slideUp(600);
       } else {
         jQuery(this).slideDown(200);
       }  
			 if(thechosenone == 'new-folder-area')
				 jQuery("#new-folder-name").focus();
			 if(thechosenone == 'rename-area')
				 jQuery("#new-file-name").focus();			 			 
			 if(thechosenone == 'filter-area') 
          jQuery("#filter_text").focus();	
    }
    else {
       jQuery(this).slideUp(600);
    }
  });
}

var obj = jQuery("#dragandrophandler");
obj.on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
    jQuery(this).css('border', '2px solid #0B85A1');
});
obj.on('dragover', function (e) 
{
     e.stopPropagation();
     e.preventDefault();
});
obj.on('drop', function (e) 
{
     jQuery(this).css('border', '2px solid #0B85A1');
     e.preventDefault();
     var files = e.originalEvent.dataTransfer.files;
     
     //We need to send dropped files to Server
     handleFileUpload(files,obj);
});

var replaceobj = jQuery("#replace-dragandrop-handler");
replaceobj.on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
    jQuery(this).css('border', '2px solid #0B85A1');
});
replaceobj.on('dragover', function (e) 
{
     e.stopPropagation();
     e.preventDefault();
});
replaceobj.on('drop', function (e) 
{
     jQuery(this).css('border', '2px solid #0B85A1');
     e.preventDefault();
     var files = e.originalEvent.dataTransfer.files;
     
     //We need to send dropped files to Server
     handleFileReplace(files,replaceobj);
});

jQuery(document).on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
jQuery(document).on('dragover', function (e) 
{
  e.stopPropagation();
  e.preventDefault();
  obj.css('border', '2px solid #0B85A1');
});
jQuery(document).on('drop', function (e, ui) 
{
    e.stopPropagation();
    e.preventDefault();
});

function handleFileUpload(files,obj)
{
  var folder_id = jQuery('#folder_id').val();      

  var mlp_title_text = jQuery('#mlp_title_text').val();      
  var mlp_alt_text = jQuery('#mlp_alt_text').val();      

  for (var i = 0; i < files.length; i++) 
  {
    var fd = new FormData();
    fd.append('file', files[i]);
    fd.append('action', 'upload_attachment');
    fd.append('folder_id', folder_id);
    fd.append('title_text', mlp_title_text);
    fd.append('alt_text', mlp_alt_text);
    fd.append('nonce', mgmlp_ajax.nonce);

    var status = new createStatusbar(obj); //Using this we can set progress.
    status.setFileNameSize(files[i].name,files[i].size);
    sendFileToServer(fd,status);

  }
}

function get_upload_extension(filename) {
  return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
}

function handleFileReplace(files,obj) {
  
  var folder_id = jQuery('#folder_id').val();
  var replace_file_id = jQuery('#replace-file-id').val();
  //var replace_file_url = jQuery('#replace-file-url').val();
  var replace_mine_type = jQuery('#replace-mine-type').val();  
  var replace_type = jQuery('input[name="replace-type"]:checked').val();
  //var replace_type = 'replace-only';
  var date_options = jQuery('input[name="date-options"]:checked').val();      												
  var mlp_title_text = jQuery('#replace-seo-file-title').val();      
  var mlp_alt_text = jQuery('#replace-seo-alt-text').val();      
  var custom_date = jQuery('#mlfp-custom-date').val();
  var replace_ext = jQuery('#replace-ext').val();        
  
  var upload_ext = get_upload_extension(files[0].name);
  
  if(upload_ext != replace_ext) {
    alert(mgmlp_ajax.mime_mismatch);    
    return false;
  }
  
  jQuery("#ajaxloader").show();
   
  //for (var i = 0; i < files.length; i++) {
    var fd = new FormData();
    fd.append('file', files[0]);
    fd.append('action', 'mlfp_replace_attachment');
    fd.append('folder_id', folder_id);      
    fd.append('replace_file_id', replace_file_id);
    fd.append('replace_type', replace_type);
    fd.append('date_options', date_options);      
    fd.append('custom_date', custom_date);
    //fd.append('replace_file_url', replace_file_url);
    fd.append('replace_mine_type', replace_mine_type);
    fd.append('title_text', mlp_title_text);
    fd.append('alt_text', mlp_alt_text);      
    fd.append('nonce', mgmlp_ajax.nonce);

    var status = new createStatusbar(obj); //Using this we can set progress.
    status.setFileNameSize(files[0].name,files[0].size);
    jQuery('#file-replace-area').slideUp(600);
    jQuery("#ir-instructions").slideUp(200);
    jQuery("#display-ir-instructions-close").hide();    
    jQuery('#mlfp-custom-date').val("");
    jQuery('#replace-only').prop('checked',true);
    jQuery('#mlfp-keep-date').prop('checked',true);
    sendFileToServer(fd,status);
    //break; // only allow one file upload

  //}
}

function sendFileToServer(formData,status)
{
    jQuery("#ajaxloader").show();
    var extraData ={}; //Extra Data.
    var jqXHR=jQuery.ajax({
            xhr: function() {
            var xhrobj = jQuery.ajaxSettings.xhr();
            if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        status.setProgress(percent);
                    }, false);
                }
            return xhrobj;
        },
        url : mgmlp_ajax.ajaxurl,
        type: "POST",
        contentType:false,
        processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);
            jQuery("#ajaxloader").hide();
            jQuery("#mgmlp-file-container").html(data);

		        jQuery('li a.media-attachment').draggable({
							cursor: 'move',
							//helper: 'clone'
			        helper: function() {
								// allows the checkboxes to be used in multi select drag and drop
								var selected = jQuery('.mg-media-list input:checked').parents('li');
								if (selected.length === 0) {
									selected = jQuery(this);
								}
								var container = jQuery('<div/>').attr('id', 'draggingContainer');
								container.append(selected.clone());
								return container;
							}										
						});
//						jQuery('.media-link').droppable( {
//								accept: 'li a.media-attachment',
//								hoverClass: 'droppable-hover',
//								drop: handleDropEvent
//						});
						
        },
        error: function (err){ 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }        
    }); 
  
    status.setAbort(jqXHR);
}

var rowCount=0;
function createStatusbar(obj)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
     this.statusbar = jQuery("<div class='statusbar "+row+"'></div>");
     this.filename = jQuery("<div class='filename'></div>").appendTo(this.statusbar);
     this.size = jQuery("<div class='filesize'></div>").appendTo(this.statusbar);
     this.progressBar = jQuery("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
     this.abort = jQuery("<div class='abort'>Abort</div>").appendTo(this.statusbar);
     obj.after(this.statusbar);
 
    this.setFileNameSize = function(name,size)
    {
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
 
        this.filename.html(name);
        this.size.html(sizeStr);
    }
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {            
            this.abort.hide();            
            //jQuery(".statusbar").remove();
            this.statusbar.remove();
        }
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
            jQuery("#ajaxloader").hide();
        });
    }
}

function handleDropEvent(event, ui ) {
	
	console.log('drop event');
	
	var move_ids = new Array();
	var items = ui.helper.children();
	items.each(function() {  
		move_ids[move_ids.length] = jQuery(this).find( "a.media-attachment" ).attr("id");
	});
	
	if(move_ids.length < 2) {
	  move_ids = new Array();
		move_ids[move_ids.length] =  ui.draggable.attr("id");
	}	
		
  var droppableId = jQuery(this).attr("folder");	
	var serial_copy_ids = JSON.stringify(move_ids.join());
	var folder_id = droppableId;
	var destination = '';
	//var current_folder = jQuery("#current-folder-id").val();      
	
	if(jQuery("#current-folder-id").val() === undefined) 
		var current_folder = sessionStorage.getItem('folder_id');
	else
		var current_folder = jQuery('#current-folder-id').val();
		    
  var move_or_copy_status = jQuery('#move-or-copy-status').val();
  
	jQuery("#ajaxloader").show();

	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "move_media", current_folder: current_folder, folder_id: folder_id, destination: destination, serial_copy_ids: serial_copy_ids, nonce: mgmlp_ajax.nonce },
		url : mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) {
			//jQuery("#ajaxloader").hide();
			jQuery(".mgmlp-media").prop('checked', false);
			jQuery(".mgmlp-folder").prop('checked', false);
			jQuery("#folder-message").html(data.message);
		},
		error: function (err)
			{ 
				jQuery("#ajaxloader").hide();
				alert(err.responseText);
			}
	});                	
}

function mlf_refresh(folder_id) {
	var image_link = '1';
  jQuery("#folder-message").html('Refreshing...');
	
	jQuery.ajax({
		type: "POST",
		async: true,
		//data: { action: "mlp_display_folder_contents_ajax", current_folder_id: folder_id, image_link: image_link, display_type: 1, nonce: mgmlp_ajax.nonce },
		data: { action: "mlp_load_folder", folder: folder_id, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "html",
		success: function (data) 
			{ 
				jQuery("#mgmlp-file-container").html(data); 
				jQuery("#folder-message").html(''); 				
        jQuery("#ajaxloadernav").hide();
        jQuery("#ajaxloader").hide();
			},
				error: function (err) {
          jQuery("#ajaxloader").show();
          alert(err.responseText)
        }
			});
	
}

function mlf_refresh_folders(folder_id, show) {
  //jQuery("#folder-message").html('Refreshing folders...');
	
	if(jQuery("#current-folder-id").val() === undefined) 
		var folder_id = sessionStorage.getItem('folder_id');
	else
		var folder_id = jQuery('#current-folder-id').val();
	
	jQuery.ajax({
		type: "POST",
		async: true,
		//data: { action: "display_folder_nav_ajax", folder: folder_id, nonce: mgmlp_ajax.nonce },
		data: { action: "mlp_get_folder_data", current_folder_id: folder_id, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			jQuery('#folder-tree').jstree(true).settings.core.data = data;
			jQuery('#folder-tree').jstree(true).refresh();			
			//jQuery('#folder-tree').jstree(true).redraw(true);
			
      //jQuery("#folder-message").html('');
		},
		error: function (err){ 
			alert(err.responseText)
		}
	});
	
}

function capitalize(str) {
	strVal = '';
	str = str.split(' ');
	for (var chr = 0; chr < str.length; chr++) {
			strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
	}
	return strVal
}	

function mlp_format_link(insert_html, mlp_image_id, mlp_link, mlp_image_src, mlp_custom_link, mlp_title ) {

	var html = '';
	switch(mlp_link) {
		case 'file':
			var extension = mlp_image_src.split('.').pop();
			if(jQuery.inArray(extension, ['jpg','jpeg','jpe','png','gif','bmp','tiff','ico']) === -1)
				html = '<a href="' + mlp_image_src + '" title="'+ mlp_title +'">'+ mlp_title +'</a>';
			else
				html = '<a href="' + mlp_image_src + '" title="'+ mlp_title +'">'+ insert_html +'</a>';
			break;
		case 'post':
			html = '<a href="<?php echo home_url(); ?>/?attachment_id='+ mlp_image_id +'" rel="attachment wp-att-'+ mlp_image_id + '" title="'+mlp_title+'">' + insert_html + '</a>';							
			break;
		case 'custom':
			html = '<a href="' + mlp_custom_link + '" title="'+mlp_title+'">'+insert_html+'</a>';
			break;										
	}	
	return html;
}

function check_for_new_folders(parent_folder) {
	
	var data;
	
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "max_sync_contents", parent_folder: parent_folder, nonce: mgmlp_ajax.nonce },
		url : mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (folder_info) {
			data = folder_info;
    },
		error: function (err){ 
			jQuery("#ajaxloader").hide();
			alert(err.responseText);
		},
	});
	
	return data;
	
}

function run_sync_process(phase, parent_folder, mlp_title_text, mlp_alt_text) {
	
  jQuery("#ajaxloader").hide();
  
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_run_sync_process", phase: phase, parent_folder: parent_folder, mlp_title_text: mlp_title_text, mlp_alt_text: mlp_alt_text, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			if(data != null && data.phase != null) {
			  jQuery("#folder-message").html(data.message);
        run_sync_process(data.phase, parent_folder, mlp_title_text, mlp_alt_text);
      } else {        
			  jQuery("#folder-message").html(data.message);        
				mlf_refresh_folders(parent_folder);
		    jQuery("#ajaxloader").hide();
				return false;
      }      
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText)
		}    
	});																											
	
}

function empty_purge_table() {
  
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "clear_purge_table", nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "html",
		success: function (data) { 
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText)
		}    
	});																											
    
}

function run_file_detect_process(last_folder, folder_count) {
  
  //jQuery("#ajaxloader").hide();
  
  if(window.search_progress == false)
    return false;
   
  var folder_count = jQuery("#folder-count").val();
    
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "run_file_detect_process", last_folder: last_folder, folder_count: folder_count, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
      console.log('data',data);
			if(data != null && data.last_folder != null) {
        jQuery("#mlfp_process_message").hide();
        //jQuery("#scan_progress").show();
        jQuery("#mlfp_scan_message").show();
        jQuery("#scan_message").show();
        console.log(data.percentage);
        jQuery("#scan_progress .progress .bar").css("width", data.percentage + "%");
        jQuery("#scan_message").html(data.message);
        jQuery("#last-folder").val(data.last_folder);        
        run_file_detect_process(data.last_folder, folder_count);
      } else {        
		    //jQuery("#ajax-wheel").hide();
        jQuery("#scan_message").html(data.message); 
        jQuery("#scan_progress").delay(2500).slideUp(500);
        
        jQuery("#stop-search").attr('data','disabled');
        jQuery("#stop-search").attr('disabled','disabled');    

        jQuery("#located-uncateloged-files").attr('disabled',false);    
        jQuery("#located-uncateloged-files").attr('data','');    
        //window.location.reload(true);
        load_purge_list(0);

                
				return false;
      }      
		},
		error: function (err){ 
		  jQuery("#ajax-wheel").hide();
			alert(err.responseText)
		}    
	});																											
  
  
}

function clear_filter_and_refresh_page() {
  
  jQuery("#filter_text").val('');   

  if(jQuery("#current-folder-id").val() === undefined) 
    var current_folder = sessionStorage.getItem('folder_id');
  else
    var current_folder = jQuery('#current-folder-id').val();


  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlp_get_folder_data", current_folder_id: current_folder, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "json",
    success: function (data) { 
      jQuery('#folder-tree').jstree(true).settings.core.data = data;
      jQuery('#folder-tree').jstree(true).refresh();			
      //jQuery('#folder-tree').jstree(true).redraw(true);


      jQuery("#folder-message").html('');
    },
    error: function (err){ 
      alert(err.responseText)
    }
  });  
}

function mime_type_test(attachment_id, playlist_type) {
  
  //jQuery("#folder-message").html('');
  jQuery("#ajaxloader").show();
    
  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlfp_mime_type_test", attachment_id: attachment_id, playlist_type: playlist_type, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "json",
    success: function (data) { 
      jQuery("#ajaxloader").hide();
      console.log(data);
      
      retval = data.type_status;
      if(data.type_status == false)
        alert(data.message);
      else {
        
        var attachment_ids = jQuery("#pl_attachment_ids").val();
        console.log('attachment_ids 1',attachment_ids);
        if(attachment_ids.trim().length == 0)
          attachment_ids = attachment_id;
        else
          attachment_ids = attachment_ids + ',' + attachment_id;
            
        jQuery("#pl_attachment_ids").val(attachment_ids);
        console.log('attachment_ids 2',attachment_ids);
        jQuery("#folder-message").html(data.message);

        
        
      }
    },
    error: function (err){ 
      jQuery("#ajaxloader").hide();
      alert(err.responseText)
    }
  });  
  
  
  
}

function mlfp_move_single_file(file_id, folder_id, current_folder, destination_folder_path) {
  
  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlfp_move_single_file", file_id: file_id, folder_id: folder_id, current_folder: current_folder, destination_folder_path: destination_folder_path, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "html",
    success: function (data) { 
      jQuery("#folder-message").html(data);      
    },
    error: function (err){ 
      alert(err.responseText)
    }
  });  
  
}

