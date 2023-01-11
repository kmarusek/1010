<?php

		global $wpdb, $current_user;
		$license 	= get_option('mg_edd_mlpp_license_key' );
		$status 	= get_option('mg_edd_mlpp_license_status' );
		$unlimited 	= get_option(MAXGALLERIA_MEDIA_LIBRARY_UNLIMITED);
		$network_activated = get_option(MAXGALLERIA_MEDIA_LIBRARY_NETWORK_ACTIVATED, 'no');
    $expiration_date = get_option(MAXGALLERIA_MEDIA_LIBRARY_EXPIRES, '');
    
    $id_author = get_current_user_id();
    
    //error_log("expiration_date $expiration_date");
    //error_log("network_activated $network_activated");    
    //error_log("unlimited $unlimited");
    
		//$response = get_option( 'mg_edd_mlpp_license_response' );				
    $new_license = get_option(MAXG_NEW_LICENSE, 'off');
    
    $this->license_valid = $this->check_license();
    //$this->license_valid = $this->display_experation_notice();
    
    $class = "";
    $disabled = "";
    $expired = false;
    $today = strtotime(date("Y-m-d"));
    $expiration_date = strtotime($this->license_expiration);
    if($this->license_expiration != 'lifetime') {
      if($expiration_date <= $today) {
        $class = 'red';
        $expired = true;
        $disabled = 'disabled';
      }
      if($new_license == 'on') {
        $disabled = "";
        $license = "";
        update_option(MAXG_NEW_LICENSE, 'off');
      }
    }
    
		?>	
  
  <div id="license-wrap">
		<form method="post" action="options.php">

			<?php settings_fields('edd_mlpp_license'); ?>

			<table>
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key', 'maxgalleria-media-library'); ?>
						</th>
						<td>
							<input id="edd_mlpp_license_key" name="mg_edd_mlpp_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<label class="description" for="mg_edd_mlpp_license_key"><?php _e('Enter your license key, click Save Changes and then click the Activate button', 'maxgalleria-media-library'); ?></label>
						</td>
					</tr>
          <tr valign="top">
            <th scope="row" valign="top">
              <?php // _e('Activate License', 'maxgalleria-media-library'); ?>
            </th>
            <td>
              <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'maxgalleria-media-library'); ?>">                
              <?php if( $status !== false && $status == 'valid' ) { ?>
                <?php wp_nonce_field( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ); ?>
                <input type="submit" class="button-primary" <?php echo $disabled; ?> name="edd_mlpp_license_deactivate" value="<?php _e('Deactivate License', 'maxgalleria-media-library'); ?>"/>
                 <?php _e('Status:', 'maxgalleria-media-library'); ?> <span style="color:green;"> <?php _e('active', 'maxgalleria-media-library'); ?></span>
              <?php } else {
                wp_nonce_field( 'edd_mlpp_nonce', 'edd_mlpp_nonce' ); ?>
                <input type="submit" class="button-primary" <?php echo $disabled; ?> name="edd_mlpp_license_activate" value="<?php _e('Activate License', 'maxgalleria-media-library'); ?>"/>                  
              <?php } ?>
              <?php
              if($expired) {
                $link = "https://maxgalleria.com/checkout/?edd_license_key=$license";
                echo '<a href="' . $link . '" class="button-secondary" id="mlpp_license_renew" >' . __('Renew License', 'maxgalleria-media-library') . '</a>' . PHP_EOL;                  
                //echo '<a class="button-secondary" id="mlpp_new_license" >' . __('Enter New License Key', 'maxgalleria-media-library') . '</a>' . PHP_EOL;                  
                echo '<input type="submit" class="button-secondary" name="edd_mlpp_license_deactivate2" value="' . __('Enter New License Key', 'maxgalleria-media-library') , '"/>' . PHP_EOL;
              }
              ?>
              <?php      
              if(is_multisite() && $wpdb->blogid == 1) {
                if( $status !== false && $status == 'valid' && $unlimited == 'yes' ) { 
                  if($network_activated == 'no')
                    echo '<a class="button-secondary" id="mlpp_activate_ms_license" >' . __('Activate License on All Network Sites', 'maxgalleria-media-library') . '</a>'; 
                  else
                    echo '<a class="button-secondary" id="mlpp_deactivate_ms_license" >' . __('Dectivate License on All Network Sites', 'maxgalleria-media-library') . '</a>'; 
                }    
              }  
              ?>                  
            </td>
          </tr>
          <?php if($license != "") { ?>
          <tr>
            <th></th>
            <td><?php _e('Expiration Date: ', 'maxgalleria-media-library');
            if($this->license_expiration != 'lifetime') {
              echo "<span class='$class'>" . date("F d, Y", $expiration_date) . "</span>"; 
            } else {  
              echo "<span class='$class'>" . __('None', 'maxgalleria-media-library') . "</span>"; 
            }  
            ?></td>
          </tr>
          <?php } ?>
				</tbody>
			</table>      
			<?php //submit_button(); ?>
      <p id="network-activate-message"></p>

		</form>  
  </div>
            		
<script>
  var page_refresh = false;    
	jQuery(document).ready(function(){
    		        
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
                	        	
	});  
</script>  		
