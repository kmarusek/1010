if ( typeof wpActiveEditor != 'undefined') {
  var wpActiveEditor = 'undefined';
}    
(function ($) {
  
  $(document).ready(function(){
                
    set_uploads_folder();
    $('div.media-frame-content div.media-toolbar').append('<div style="float:left;">' + mlfpmedia.upload_message +'</div><div id="mlfp-tool-bar"><input type="hidden" id="folder_id" value="' + mlfpmedia.uploads_folder_id + '"><ul id="folder-tree"></ul></div>');
    
    $.ajax({
      type: "POST",
      async: true,
      data: { action: "mlp_get_folder_data", current_folder_id: mlfpmedia.uploads_folder_id, nonce: mlfpmedia.nonce },
      url : mlfpmedia.ajaxurl,
      dataType: "json",
      success: function (data) {
        
        $('#folder-tree').jstree({ 'core' : {
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
            //'file' : { 'valid_children' : [], 'icon' : 'file' }
          },
          'sort' : function(a, b) {
				    return this.get_type(a).toLowerCase() === this.get_type(b).toLowerCase() ? (this.get_text(a).toLowerCase() > this.get_text(b).toLowerCase() ? 1 : -1) : (this.get_type(a).toLowerCase() >= this.get_type(b).toLowerCase() ? 1 : -1);
          },			
          'plugins' : [ 'sort','types' ],
        });
        
        if(!$("ul#folder-tree.jstree").hasClass("bound")) {
          $("#folder-tree").addClass("bound").on("select_node.jstree", mlfp_filter_files);		
        }	
        
      },
      error: function (err)
        { alert(err.responseText);}
    });
       
    jQuery(document).on("click", "#mflp-media-refresh", function () {
      wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});
    });    
    
	});
  
  function mlfp_filter_files (e, data) {
    
    if ( typeof wpActiveEditor != 'undefined') {
      var wpActiveEditor = 'undefined';
    }    
        
    if(!window.mlfp_busy) {
      window.mlfp_busy = true;
      
			jQuery("#folder-tree").jstree("toggle_node", data.node.id);
      
			var folder_id = data.node.id;

			jQuery.ajax({
				type: "POST",
				async: true,
				data: { action: "mlfp_update_folder_id", folder_id: folder_id, nonce: mlfpmedia.nonce },
				url : mlfpmedia.ajaxurl,
				dataType: "html",
				success: function (data) {
          console.log('successful ' + folder_id);
          wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});  
					jQuery("#folder_id").val(folder_id);
					sessionStorage.setItem('folder_id', folder_id);
																				
				},
				error: function (err) { 
						console.log('12');
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
        console.log(data);
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
    
  }
      
}(jQuery));
