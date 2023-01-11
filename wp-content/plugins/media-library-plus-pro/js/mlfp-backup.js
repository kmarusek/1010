(function( $, wpmdb ) {

	var reader = {};
	var file = {};
	var slice_size = 1000 * 1024;

	function start_upload( event ) {
    
    //jQuery( "#mlfp_import" ).dialog( "close" );
    $('#mlfp-upload-export-popup').fadeOut(300);
    
		event.preventDefault();
		
		reader = new FileReader();
		file = document.querySelector('#fileToUpload').files[0];
    
    if(file.name == 'mlfp-data.zip') {
      $('#exim-message').html(mgmlp_ajax.incorrect_backup);
      return false;
    }

		upload_file( 0 );
	}
	$('#exim-upload-submit').on('click', start_upload);
  
	function upload_file( start ) {
		var next_slice = start + slice_size + 1;
		var blob = file.slice( start, next_slice );

		reader.onloadend = function( event ) {
			if ( event.target.readyState !== FileReader.DONE ) {
				return;
			}
			
			$.ajax( {
				url: mlfpb_ajax.ajaxurl,
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: {
					action: 'exim_upload_file',
					file_data: event.target.result,
					file: file.name,
					file_type: file.type,
					nonce: mlfpb_ajax.nonce
				},
				error: function( jqXHR, textStatus, errorThrown ) {
          console.log('textStatus',textStatus);
          console.log('errorThrown',errorThrown);
				},
				success: function( data ) {
					var size_done = start + slice_size;
					var percent_done = Math.floor( ( size_done / file.size ) * 100 );
					
					if ( next_slice < file.size ) {						// Update upload progress
						$( '#exim-message' ).html( 'Uploading File - ' + percent_done + '%' );
						upload_file( next_slice );
					} else {
            unzip_ml_backup(file.name);            
					}
				}
			} );
		};

		reader.readAsDataURL( blob );
	}
  
  function unzip_ml_backup(file_name) {
      console.log('unzip_ml_backup');
        
			$.ajax( {
				url: mlfpb_ajax.ajaxurl,
				type: 'POST',
				dataType: 'html',
				cache: false,
				data: {
					action: 'exim_unzip_file',
          zip_file: file_name,
					nonce: mlfpb_ajax.nonce
				},
				success: function( data ) {
          $( '#exim-message' ).html( 'Upload Complete!' );
          refreshBackups();          
				}
			});
		}
        
})( jQuery );
