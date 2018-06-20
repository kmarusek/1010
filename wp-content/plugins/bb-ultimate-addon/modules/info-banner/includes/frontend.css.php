<?php

global $post;
$converted = get_post_meta( $post->ID,'_uabb_converted', true );

$settings->background_color = UABB_Helper::uabb_colorpicker( $settings, 'background_color', true );
$settings->overlay_color = UABB_Helper::uabb_colorpicker( $settings, 'overlay_color', true );

$settings->color = UABB_Helper::uabb_colorpicker( $settings, 'color' );
$settings->desc_color = UABB_Helper::uabb_colorpicker( $settings, 'desc_color' );
$settings->link_color = UABB_Helper::uabb_colorpicker( $settings, 'link_color' );

?>

 /* Min height */
<?php
if( $settings->min_height_switch == 'custom' && $settings->min_height != '' ) {
	$vertical_align = 'center';
	$prefix_vertical_align = 'center';
	if (  $settings->vertical_align != 'middle' ) {
		$vertical_align = ( $settings->vertical_align == 'top' ) ? 'flex-start' : 'flex-end' ;
		$prefix_vertical_align = ( $settings->vertical_align == 'top' ) ? 'start' : 'end' ;
	}
?>
.fl-node-<?php echo $id; ?> .uabb-ultb3-box {

	min-height: <?php echo $settings->min_height; ?>px;
	-ms-grid-row-align: <?php echo $prefix_vertical_align; ?>;
	-webkit-box-align: <?php echo $prefix_vertical_align; ?>;
       -ms-flex-align: <?php echo $prefix_vertical_align; ?>;
    	  align-items: <?php echo $vertical_align; ?>;
}

.internet-explorer .fl-node-<?php echo $id; ?> .uabb-ultb3-box {
	height: <?php echo $settings->min_height; ?>px;
}

/*.fl-node-<?php echo $id; ?> .uabb-ultb3-info {
	position: absolute;
	<?php if ( $settings->vertical_align == 'top' ) { ?>
	top: 0;
	<?php } elseif ( $settings->vertical_align == 'middle' ) { ?>
	top: 50%;
    -webkit-transform: translateY(-50%);
       -moz-transform: translateY(-50%);
    	-ms-transform: translateY(-50%);
    	 -o-transform: translateY(-50%);
    		transform: translateY(-50%)
	<?php } elseif ( $settings->vertical_align == 'bottom' ) { ?>
	bottom: 0;
	<?php } ?>
}*/
<?php
}
if( $settings->background_color != '' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-ultb3-box {
	background: <?php echo $settings->background_color; ?>;
}
<?php
}
?>

/* Title Typography and Color */
.fl-node-<?php echo $id; ?> .uabb-ultb3-box .uabb-ultb3-title {
   	<?php if( $settings->font_family['family'] != "Default" ){ ?>
   		<?php UABB_Helper::uabb_font_css( $settings->font_family ); ?>
   	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->font_size_unit ) && $settings->font_size_unit != '' ) { ?>
     	font-size: <?php echo $settings->font_size_unit; ?>px;
    <?php } else if( isset( $settings->font_size_unit ) && $settings->font_size_unit == '' && isset( $settings->font_size['desktop'] ) && $settings->font_size['desktop'] != '' ) { ?>
	    font-size: <?php echo $settings->font_size['desktop']; ?>px;
	<?php } ?>    
     
	<?php if( isset( $settings->font_size['desktop'] ) && $settings->font_size['desktop'] == '' && isset( $settings->line_height['desktop'] ) && $settings->line_height['desktop'] != '' && $settings->line_height_unit == '' ) { ?>
	    line-height: <?php echo $settings->line_height['desktop']; ?>px;
	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->line_height_unit ) && $settings->line_height_unit != '' ) { ?>
    	line-height: <?php echo $settings->line_height_unit; ?>em;	
    <?php }  else if( isset( $settings->line_height_unit ) && $settings->line_height_unit == '' && isset( $settings->line_height['desktop'] ) && $settings->line_height['desktop'] != '' ) { ?>
	    line-height: <?php echo $settings->line_height['desktop']; ?>px;
	<?php } ?>   

   	<?php if( $settings->color != '' ){ ?>
   		color: <?php echo $settings->color; ?>;
   	<?php } ?>

   	<?php if( $settings->title_margin_top != '' ){ ?>
   		margin-top: <?php echo $settings->title_margin_top; ?>px;
   	<?php } ?>

   	<?php if( $settings->title_margin_bottom != '' ){ ?>
   		margin-bottom: <?php echo $settings->title_margin_bottom; ?>px;
   	<?php } ?>

   	<?php if( $settings->transform != '' ) ?>
	   text-transform: <?php echo $settings->transform; ?>;

    <?php if( $settings->letter_spacing != '' ) ?>
	   letter-spacing: <?php echo $settings->letter_spacing; ?>px;
}

/* Description Typography and Color */
.fl-node-<?php echo $id; ?> .uabb-text-editor {
    <?php if( $settings->desc_font_family['family'] != "Default" ){ ?>
   		<?php UABB_Helper::uabb_font_css( $settings->desc_font_family ); ?>
   	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit ) && $settings->desc_font_size_unit != '' ) { ?>
     	font-size: <?php echo $settings->desc_font_size_unit; ?>px;
    <?php } else if( isset( $settings->desc_font_size_unit ) && $settings->desc_font_size_unit == '' && isset( $settings->desc_font_size['desktop'] ) && $settings->desc_font_size['desktop'] != '' ) { ?>
	    font-size: <?php echo $settings->desc_font_size['desktop']; ?>px;
	  <?php } ?>   

	  <?php if( isset( $settings->desc_font_size['desktop'] ) && $settings->desc_font_size['desktop'] == '' && isset( $settings->desc_line_height['desktop'] ) && $settings->desc_line_height['desktop'] != '' && $settings->desc_line_height_unit == '' ) { ?>
	   line-height: <?php echo $settings->desc_line_height['desktop']; ?>px;
	  <?php } ?>
        
    <?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit ) && $settings->desc_line_height_unit != '' ) { ?>
    	line-height: <?php echo $settings->desc_line_height_unit; ?>em;	
    <?php }  else if( isset( $settings->desc_line_height_unit ) && $settings->desc_line_height_unit == '' && isset( $settings->desc_line_height['desktop'] ) && $settings->desc_line_height['desktop'] != '' ) { ?>
	    line-height: <?php echo $settings->desc_line_height['desktop']; ?>px;
	  <?php } ?>   

   	<?php if( $settings->desc_color != '' ){ ?>
   		color: <?php echo $settings->desc_color; ?>;
   	<?php } ?>

   	<?php if( $settings->desc_margin_top != '' ){ ?>
   		margin-top: <?php echo $settings->desc_margin_top; ?>px;
   	<?php } ?>

   	<?php if( $settings->desc_margin_bottom != '' ){ ?>
   		margin-bottom: <?php echo $settings->desc_margin_bottom; ?>px;
   	<?php } ?>

   	<?php if( $settings->desc_transform != '' ) ?>
	   text-transform: <?php echo $settings->desc_transform; ?>;

    <?php if( $settings->desc_letter_spacing != '' ) ?>
	   letter-spacing: <?php echo $settings->desc_letter_spacing; ?>px;
}


<?php
if($settings->cta_type == 'button') {
	/* Button Render Css */
	FLBuilder::render_module_css('uabb-button', $id, array(

    	/* General Section */
        'text'              => $settings->btn_text,
        
        /* Link Section */
        'link'              => $settings->btn_link,
        'link_target'       => $settings->btn_link_target,
        
        /* Style Section */
        'style'             => $settings->btn_style,
        'border_size'       => $settings->btn_border_size,
        'transparent_button_options' => $settings->btn_transparent_button_options,
        'threed_button_options'      => $settings->btn_threed_button_options,
        'flat_button_options'        => $settings->btn_flat_button_options,

        /* Colors */
        'bg_color'          => $settings->btn_bg_color,
        'bg_color_opc'          => $settings->btn_bg_color_opc,
        'bg_hover_color'    => $settings->btn_bg_hover_color,
        'bg_hover_color_opc'    => $settings->btn_bg_hover_color_opc,
        'text_color'        => $settings->btn_text_color,
        'text_hover_color'  => $settings->btn_text_hover_color,
        'hover_attribute'	=> $settings->hover_attribute,

        /* Icon */
        'icon'              => $settings->btn_icon,
        'icon_position'     => $settings->btn_icon_position,
        
        /* Structure */
        'width'              => $settings->btn_width,
        'custom_width'       => $settings->btn_custom_width,
        'custom_height'      => $settings->btn_custom_height,
        'padding_top_bottom' => $settings->btn_padding_top_bottom,
        'padding_left_right' => $settings->btn_padding_left_right,
        'border_radius'      => $settings->btn_border_radius,
        'align'              => $settings->banner_alignemnt,
        'mob_align'          => '',

        /* Typography */

        'font_family'       			=> $settings->tbtn_font_family,
        'font_size'         			=> ( isset( $settings->tbtn_font_size ) ) ? $settings->tbtn_font_size : '',
        'line_height'       			=> ( isset( $settings->tbtn_line_height ) ) ? $settings->tbtn_line_height : '',
        'font_size_unit_responsive'     => $settings->tbtn_font_size_unit_responsive,
        'line_height_unit_responsive'   => $settings->tbtn_line_height_unit_responsive,
        'font_size_unit_medium'         => $settings->tbtn_font_size_unit_medium,
        'line_height_unit_medium'       => $settings->tbtn_line_height_unit_medium,
        'font_size_unit'         		=> $settings->tbtn_font_size_unit,
        'line_height_unit'       		=> $settings->tbtn_line_height_unit,
        'transform'       				=> $settings->tbtn_content_transform,
        'letter_spacing'       			=> $settings->tbtn_content_letter_spacing,

	));
}
?>


.fl-node-<?php echo $id; ?> .uabb-ultb3-box-overlay {
    <?php if ( $settings->overlay_color != '' ) { ?>
    	background-color: <?php echo $settings->overlay_color; ?>;
    <?php } ?> 
}

/* Typography Options for Link Text */
.fl-builder-content .fl-node-<?php echo $id; ?> .uabb-infobanner-cta-link {
	<?php if( $settings->link_font_family['family'] != "Default") : ?>
		<?php UABB_Helper::uabb_font_css( $settings->link_font_family ); ?>
	<?php endif; ?>

    <?php if( $converted === 'yes' || isset( $settings->link_font_size_unit ) && $settings->link_font_size_unit != '' ) { ?>
     	font-size: <?php echo $settings->link_font_size_unit; ?>px;
    <?php } else if( isset( $settings->link_font_size_unit ) && $settings->link_font_size_unit == '' && isset( $settings->link_font_size['desktop'] ) && $settings->link_font_size['desktop'] != '' ) { ?>
	    font-size: <?php echo $settings->link_font_size['desktop']; ?>px;
	<?php } ?>    

	<?php if( isset( $settings->link_font_size['desktop'] ) && $settings->link_font_size['desktop'] == '' && isset( $settings->link_line_height['desktop'] ) && $settings->link_line_height['desktop'] != '' && $settings->link_line_height_unit == '' ) { ?>
	    line-height: <?php echo $settings->link_line_height['desktop']; ?>px;
	<?php } ?>

    <?php if( $converted === 'yes' || isset( $settings->link_line_height_unit ) && $settings->link_line_height_unit != '' ) { ?>
    	line-height: <?php echo $settings->link_line_height_unit; ?>em;	
    <?php }  else if( isset( $settings->link_line_height_unit ) && $settings->link_line_height_unit == '' && isset( $settings->link_line_height['desktop'] ) && $settings->link_line_height['desktop'] != '' ) { ?>
	    line-height: <?php echo $settings->link_line_height['desktop']; ?>px;
	<?php } ?>    

	<?php if( $settings->link_transform != '' ) ?>
	   text-transform: <?php echo $settings->link_transform; ?>;

    <?php if( $settings->link_letter_spacing != '' ) ?>
	   letter-spacing: <?php echo $settings->link_letter_spacing; ?>px;	 

}

/* Link Color */
<?php if( !empty($settings->link_color) ) : ?> 
.fl-builder-content .fl-node-<?php echo $id; ?> a,
.fl-builder-content .fl-node-<?php echo $id; ?> a *,
.fl-builder-content .fl-node-<?php echo $id; ?> a:visited {
	color: <?php echo uabb_theme_text_color( $settings->link_color ); ?>;
}
<?php endif; ?>


<?php if($global_settings->responsive_enabled) { // Global Setting If started 

	if( isset( $settings->desc_font_size['medium'] ) || isset( $settings->desc_line_height['medium'] ) || isset( $settings->font_size['medium'] ) || isset( $settings->line_height['medium'] ) || isset( $settings->desc_font_size_unit_medium ) || isset( $settings->desc_line_height_unit_medium ) || isset( $settings->font_size_unit_medium ) || isset( $settings->line_height_unit_medium ) || isset( $settings->link_font_size_unit_medium ) || isset( $settings->link_line_height_unit_medium ) || isset( $settings->line_height_unit ) ) { ?>

		@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-ultb3-box .uabb-ultb3-title {

				<?php if( $converted === 'yes' || isset( $settings->font_size_unit_medium ) && $settings->font_size_unit_medium != '' ){ ?>
					font-size: <?php echo $settings->font_size_unit_medium; ?>px;
				<?php } else if( isset( $settings->font_size_unit_medium ) && $settings->font_size_unit_medium == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '') { ?>
					font-size: <?php echo $settings->font_size['medium']; ?>px;
				<?php } ?>

				<?php if( isset( $settings->font_size['medium'] ) && $settings->font_size['medium'] == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->line_height['medium']; ?>px;
				<?php } ?>

        <?php if( $converted === 'yes' || isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ){ ?>
        	line-height: <?php echo $settings->line_height_unit_medium; ?>em;
        <?php } else if( isset( $settings->line_height_unit_medium )&& $settings->line_height_unit_medium == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '' ){?>
        	line-height: <?php echo $settings->line_height['medium']; ?>px;
    	  <?php } ?>

			}

			.fl-node-<?php echo $id; ?> .uabb-text-editor {

				<?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit_medium ) && $settings->desc_font_size_unit_medium != '' ){ ?>
					font-size: <?php echo $settings->desc_font_size_unit_medium; ?>px;
				<?php } else if( isset( $settings->desc_font_size_unit_medium ) && $settings->desc_font_size_unit_medium == '' && isset( $settings->desc_font_size['medium'] ) && $settings->desc_font_size['medium'] != '') { ?>
					font-size: <?php echo $settings->desc_font_size['medium']; ?>px;
				<?php } ?>

				<?php if( isset( $settings->desc_font_size['medium'] ) && $settings->desc_font_size['medium'] == '' && isset( $settings->desc_line_height['medium'] ) && $settings->desc_line_height['medium'] != '' && $settings->desc_line_height_unit_medium == '' && $settings->desc_line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->desc_line_height['medium']; ?>px;
				<?php } ?>

        <?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit_medium ) && $settings->desc_line_height_unit_medium != '' ){ ?>
        	line-height: <?php echo $settings->desc_line_height_unit_medium; ?>em;
        <?php } else if( isset( $settings->desc_line_height_unit_medium )&& $settings->desc_line_height_unit_medium == '' && isset( $settings->desc_line_height['medium'] ) && $settings->desc_line_height['medium'] != '' ){?>
        	line-height: <?php echo $settings->desc_line_height['medium']; ?>px;
    	  <?php } ?>
			}

			.fl-builder-content .fl-node-<?php echo $id; ?> .uabb-infobanner-cta-link {

				<?php if( $converted === 'yes' || isset( $settings->link_font_size_unit_medium ) && $settings->link_font_size_unit_medium != '' ){ ?>
					font-size: <?php echo $settings->link_font_size_unit_medium; ?>px;
				<?php } else if( isset( $settings->link_font_size_unit_medium ) && $settings->link_font_size_unit_medium == '' && isset( $settings->link_font_size['medium'] ) && $settings->link_font_size['medium'] != '') { ?>
					font-size: <?php echo $settings->link_font_size['medium']; ?>px;
				<?php } ?>

				<?php if( isset( $settings->link_font_size['medium'] ) && $settings->link_font_size['medium'] == '' && isset( $settings->link_line_height['medium'] ) && $settings->link_line_height['medium'] != '' && $settings->link_line_height_unit_medium == '' && $settings->link_line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->link_line_height['medium']; ?>px;
				<?php } ?>

        <?php if( $converted === 'yes' || isset( $settings->link_line_height_unit_medium ) && $settings->link_line_height_unit_medium != '' ){ ?>
        	line-height: <?php echo $settings->link_line_height_unit_medium; ?>em;
        <?php } else if( isset( $settings->link_line_height_unit_medium )&& $settings->link_line_height_unit_medium == '' && isset( $settings->link_line_height['medium'] ) && $settings->link_line_height['medium'] != '' ){?>
        	line-height: <?php echo $settings->link_line_height['medium']; ?>px;
    	  <?php } ?>
			}
	  }
	<?php
	}

	if( isset( $settings->desc_font_size['small'] ) || isset( $settings->desc_line_height['small'] ) || isset( $settings->font_size['small'] ) || isset( $settings->line_height['small'] ) || isset( $settings->desc_font_size_unit_responsive ) || isset( $settings->desc_line_height_unit_responsive ) || isset( $settings->font_size_unit_responsive ) || isset( $settings->line_height_unit_responsive ) || isset( $settings->link_font_size_unit_responsive ) || isset( $settings->link_line_height_unit_responsive ) || isset( $settings->line_height_unit_medium ) || isset( $settings->line_height_unit ) || $settings->desc_line_height_unit_medium || $settings->desc_line_height_unit || $settings->responsive_min_height_switch == 'custom' )
	{
	?>
		@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-ultb3-box .uabb-ultb3-title {

				<?php if( $converted === 'yes' || isset( $settings->font_size_unit_responsive ) && $settings->font_size_unit_responsive != '' ) { ?>
					font-size: <?php echo $settings->font_size_unit_responsive; ?>px;
				<?php } else if(isset( $settings->font_size_unit_responsive ) && $settings->font_size_unit_responsive == '' && isset( $settings->font_size['small'] ) && $settings->font_size['small'] != '') { ?>
					font-size: <?php echo $settings->font_size['small']; ?>px;
				<?php } ?>

				<?php if( isset( $settings->font_size['small'] ) && $settings->font_size['small'] == '' && isset( $settings->line_height['small'] ) && $settings->line_height['small'] != '' && $settings->line_height_unit_responsive == '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->line_height['small']; ?>px;
				<?php } ?>

	            <?php if( $converted === 'yes' || isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) { ?>
	            	line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
	            <?php } else if( isset( $settings->line_height_unit_responsive )&& $settings->line_height_unit_responsive == '' && isset( $settings->line_height['small'] ) && $settings->line_height['small'] != '' ) {?>
	            	line-height: <?php echo $settings->line_height['small']; ?>px;
	        	<?php } ?>
			}

			.fl-node-<?php echo $id; ?> .uabb-text-editor {              
				
				<?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit_responsive ) && $settings->desc_font_size_unit_responsive != '' ) { ?>
					font-size: <?php echo $settings->desc_font_size_unit_responsive; ?>px;
				<?php } else if(isset( $settings->desc_font_size_unit_responsive ) && $settings->desc_font_size_unit_responsive == '' && isset( $settings->desc_font_size['small'] ) && $settings->desc_font_size['small'] != '') { ?>
					font-size: <?php echo $settings->desc_font_size['small']; ?>px;
				 <?php } ?>      
				
				<?php if( isset( $settings->desc_font_size['small'] ) && $settings->desc_font_size['small'] == '' && isset( $settings->desc_line_height['small'] ) && $settings->desc_line_height['small'] != '' && $settings->desc_line_height_unit_responsive == ''  && $settings->desc_line_height_unit_medium == '' && $settings->desc_line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->desc_line_height['small']; ?>px;
				<?php } ?>

				<?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit_responsive ) && $settings->desc_line_height_unit_responsive != '' ) { ?>
				    line-height: <?php echo $settings->desc_line_height_unit_responsive; ?>em;
				<?php } else if(isset( $settings->desc_line_height_unit_responsive ) && $settings->desc_line_height_unit_responsive == '' && isset( $settings->desc_line_height['small'] ) && $settings->desc_line_height['small'] != '') { ?>
				    line-height: <?php echo $settings->desc_line_height['small']; ?>px;
				 <?php } ?>  
			}

			.fl-builder-content .fl-node-<?php echo $id; ?>	.uabb-infobanner-cta-link {                

				<?php if( $converted === 'yes' || isset( $settings->link_font_size_unit_responsive ) && $settings->link_font_size_unit_responsive != '' ) { ?>
					font-size: <?php echo $settings->link_font_size_unit_responsive; ?>px;
				<?php } else if(isset( $settings->link_font_size_unit_responsive ) && $settings->link_font_size_unit_responsive == '' && isset( $settings->link_font_size['small'] ) && $settings->link_font_size['small'] != '') { ?>
					font-size: <?php echo $settings->link_font_size['small']; ?>px;
				 <?php } ?>   
				
				<?php if( isset( $settings->link_font_size['small'] ) && $settings->link_font_size['small'] == '' && isset( $settings->link_line_height['small'] ) && $settings->link_line_height['small'] != '' && $settings->link_height_unit_responsive == ''  && $settings->link_line_height_unit_medium == '' && $settings->link_line_height_unit == '' ) { ?>
				    line-height: <?php echo $settings->link_line_height['small']; ?>px;
				<?php } ?>

        <?php if( $converted === 'yes' || isset( $settings->link_line_height_unit_responsive ) && $settings->link_line_height_unit_responsive != '' ) { ?>
        	line-height: <?php echo $settings->link_line_height_unit_responsive; ?>em;
        <?php } else if( isset( $settings->link_line_height_unit_responsive )&& $settings->link_line_height_unit_responsive == '' && isset( $settings->link_line_height['small'] ) && $settings->link_line_height['small'] != '' ) {?>
        	line-height: <?php echo $settings->link_line_height['small']; ?>px;
    	  <?php } ?>  

			}

			<?php if( $settings->responsive_min_height_switch == 'custom' ) { 

				$vertical_align = 'center';
				$prefix_vertical_align = 'center';
				if (  $settings->responsive_vertical_align != 'middle' ) {
					$vertical_align = ( $settings->responsive_vertical_align == 'top' ) ? 'flex-start' : 'flex-end' ;
					$prefix_vertical_align = ( $settings->responsive_vertical_align == 'top' ) ? 'start' : 'end' ;
				}
			?>
			.fl-node-<?php echo $id; ?> .uabb-ultb3-box {
				min-height: <?php echo $settings->responsive_min_height; ?>px;
				-ms-grid-row-align: <?php echo $prefix_vertical_align; ?>;
				-webkit-box-align: <?php echo $prefix_vertical_align; ?>;
       			   -ms-flex-align: <?php echo $prefix_vertical_align; ?>;
    	  			  align-items: <?php echo $vertical_align; ?>;
			}

			<?php } ?>

	    }
	<?php
	}

	/* Responsive Nature */
	if( $settings->responsive_nature == 'custom' ) :

		if( $settings->res_medium_width != '' ) : ?>
		@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-ultb3-img {
				width: <?php echo $settings->res_medium_width; ?>px !important;
			}		
		}
		<?php endif;

		if( $settings->res_small_width != '' ) : ?>
		@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-ultb3-img {
				width: <?php echo $settings->res_small_width; ?>px !important;
			}		
		}
		<?php endif;
	endif;
}
?>