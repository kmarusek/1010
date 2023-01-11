  jQuery(document).ready(function(){
    jQuery.extend( wp.Uploader.prototype, {
      init : function(){
        mlfp_display_folder_tree();
      },
      success : function(){
        console.log('wp.Uploader.prototype');
        wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});  
      },
      refresh : function(){
        new_folder_check();
        mlfp_display_folder_tree();
      }      
    });
    
  });                  

function mlfp_display_folder_tree() {
    
    var hide_menu = false;
    var current_too_bar;
    var parent_id;
                        
      jQuery('.media-frame.mode-select.wp-core-ui div.media-frame-content div.attachments-browser').each(function() {
        if(jQuery(this).is(':visible')) {
          parent_id = jQuery(this).closest('.media-frame.mode-select.wp-core-ui').attr('id');
          if(jQuery('div#' + parent_id + '.media-frame.mode-select.wp-core-ui div.media-frame-content div.attachments-browser div.media-toolbar div#mlfp-tool-bar').length == 0) {
            set_uploads_folder();
            jQuery('div#' + parent_id + '.media-frame.mode-select.wp-core-ui div.media-frame-content div.attachments-browser div.media-toolbar').append('<div id="mlfp-tool-bar"><input type="hidden" id="mlfp_folder_id" value="' + mlfpmedia.new_folder_id + '"><ul id="folder-tree"></ul></div>');
            jQuery('.attachments').css('top','275px');
            jQuery('.media-toolbar').css('overflow','visible');
            jQuery('div.media-frame-tab-panel div.media-frame-content').addClass('mlfp-view');
            //jQuery('.media-frame .media-frame-toolbar .media-toolbar-primary').css('bottom','61px');
                        
            return false;
          }
        }  
      });      
      
			var current_folder_id	=	jQuery("#" + parent_id + " #mlfp_folder_id").val();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlp_get_folder_data", current_folder_id: current_folder_id, nonce: mlfpmedia.nonce },
        url : mlfpmedia.ajaxurl,
        dataType: "json",
        success: function (data) {

          var tree_selector = 'div#' + parent_id + ' #folder-tree';

          jQuery(tree_selector).jstree({ 'core' : {
              'data' : data,
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
            },
            'sort' : function(a, b) {
				       return this.get_type(a).toLowerCase() === this.get_type(b).toLowerCase() ? (this.get_text(a).toLowerCase() > this.get_text(b).toLowerCase() ? 1 : -1) : (this.get_type(a).toLowerCase() >= this.get_type(b).toLowerCase() ? 1 : -1);
            },			
            'plugins' : [ 'sort','types' ],
          });

          if(!jQuery(tree_selector + ".jstree").hasClass("bound")) {
            jQuery(tree_selector).addClass("bound").on("select_node.jstree", mlfp_filter_files);		
          }	

        },
        error: function (err)
          { alert(err.responseText);}
      });          
  }
  
  function mlfp_filter_files (e, data) {
    //console.log('mlfp_filter_files');
    var ret_val = '';
      
    if(!window.mlfp_busy) {
      window.mlfp_busy = true;
      
      var parent_id = jQuery(this).closest('.media-frame.mode-select.wp-core-ui').attr('id');
			jQuery("#folder-tree").jstree("toggle_node", data.node.id);
      
			var folder_id = data.node.id;

			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlfp_update_folder_id", folder_id: folder_id, nonce: mlfpmedia.nonce },
				url : mlfpmedia.ajaxurl,
				dataType: "html",
				success: function (data) {
          //console.log('gutenberg ' + mlfpmedia.gutenberg);
          //console.log('theme ' + mlfpmedia.theme);
          if(mlfpmedia.theme == 'Divi'|| mlfpmedia.theme == 'Extra' || mlfpmedia.theme == 'divi-child') {
            if(typeof wp.media.frames.et_pb_file_frame !== 'undefined') {
              //console.log('case 1');
              wp.media.frames.et_pb_file_frame.content.get().collection.props.set({ignore: (+ new Date())});
            } else if(typeof wp.media.frame != 'undefined' && wp.media.frame != null) {
              //console.log("case 3");
              if(wp.media.frame.content.get() != null)
                wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});  
              else
                window.top.wp.media.frames.file_frame.content.get().collection.props.set({ignore: (+ new Date())});
            } else if ((typeof window.top.wp.media.frames.file_frame !== undefined) &&
                (typeof window.top.wp.media.frames.file_frame != null) && 
                (typeof window.top.wp.media.frames.file_frame != "undefined")) {                       
              //console.log("case 2, top defined");
              window.top.wp.media.frames.file_frame.content.get().collection.props.set({ignore: (+ new Date())});
            } else {
              //console.log("case 4");
              wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});  
            }  
          } else {
            if( typeof wp.media.frames.modula !== 'undefined' ){
              wp.media.frames.modula.content.get().collection.props.set({ignore: (+ new Date())});
            } else if( typeof window.vc !== 'undefined') {
              console.log('vc');
              if(window.mlfp_vc_gallery == 1)
                wp.media.vc_editor.add('visual-composer').states.first().frame.content.get('collection').collection.props.set({ignore: (+ new Date())})
//              else if(window.mlfp_vc_single == 1) // currently not working, using default instead
//                wp.media.VcSingleImage.frame().content.get('collection').collection.props.set({ignore: (+ new Date())});
              else
                wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});                
            } else {
              wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});                
            }
          }  
                       
					jQuery("#" + parent_id + " #mlfp_folder_id").val(folder_id);
					sessionStorage.setItem('folder_id', folder_id);
          jQuery('#' + parent_id + ' #folder-tree').jstree('select_node', '#' + parent_id + ' #'+folder_id, true);
          mlfpmedia.new_folder_id = folder_id;
					
															
				},
				error: function (err) { 
						alert(err.responseText);
					}
			});

      window.mlfp_busy = false;
            
    }
  }
    
  function set_uploads_folder() {
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_update_folder_id", folder_id: mlfpmedia.uploads_folder_id, nonce: mlfpmedia.nonce },
      url : mlfpmedia.ajaxurl,
      dataType: "html",
      success: function (data) {
        mlfpmedia.new_folder_id = mlfpmedia.uploads_folder_id;
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
    
  }
  
  function new_folder_check() {
    
    var parent_search = jQuery(this).parent('_wpb_vc_single_image');
    console.log('parent_search',parent_search);
    

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_new_folder_check", nonce: mlfpmedia.nonce },
      url : mlfpmedia.ajaxurl,
      dataType: "html",
      success: function (data) {
        console.log(data);
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });

  }
