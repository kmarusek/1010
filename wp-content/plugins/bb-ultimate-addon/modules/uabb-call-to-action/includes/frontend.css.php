<?php 

	global $post;
	$converted = get_post_meta( $post->ID,'_uabb_converted', true );

    $settings->bg_color = UABB_Helper::uabb_colorpicker( $settings, 'bg_color', true );
    $settings->title_color = UABB_Helper::uabb_colorpicker( $settings, 'title_color' );
    $settings->subhead_color = UABB_Helper::uabb_colorpicker( $settings, 'subhead_color' );
    $settings->spacing = ( $settings->spacing != '' ) ? $settings->spacing : '0';
?>
<?php if(!empty($settings->bg_color)) : ?>
.fl-node-<?php echo $id; ?> .fl-module-content {
	background-color: <?php echo $settings->bg_color; ?>;
}
<?php endif; ?>
<?php if(is_numeric($settings->spacing)) : ?>
.fl-node-<?php echo $id; ?> .fl-module-content { 
	padding: <?php echo $settings->spacing; ?>px;
}
<?php endif; ?>
<?php

FLBuilder::render_module_css('uabb-button', $id, array(
	
	/* General Section */
        'text'              => $settings->btn_text,

        /* Link Section */
        'link'              => $settings->btn_link,
        'link_target'       => $settings->btn_link_target,
        'link_nofollow'		=> $settings->btn_link_nofollow,

        /* Style Section */
        'style'             => $settings->btn_style,
        'border_size'       => $settings->btn_border_size,
        'transparent_button_options' => $settings->btn_transparent_button_options,
        'threed_button_options'      => $settings->btn_threed_button_options,
        'flat_button_options'        => $settings->btn_flat_button_options,

        /* Colors */
        'bg_color'          => $settings->btn_bg_color,
        'bg_hover_color'    => $settings->btn_bg_hover_color,
        'bg_color_opc'          => $settings->btn_bg_color_opc,
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
        'align'              => 'right',
		'mob_align'			 => 'center',

        /* Typography */
        'font_size'         				=> ( isset( $settings->btn_font_size ) ) ? $settings->btn_font_size : '',
        'line_height'       				=> ( isset( $settings->btn_line_height ) ) ? $settings->btn_line_height : '',
        'font_size_unit'         			=> $settings->btn_font_size_unit,
        'line_height_unit'       			=> $settings->btn_line_height_unit,
        'font_size_unit_medium'         	=> $settings->btn_font_size_unit_medium,
        'line_height_unit_medium'       	=> $settings->btn_line_height_unit_medium,
        'font_size_unit_responsive'         => $settings->btn_font_size_unit_responsive,
        'line_height_unit_responsive'       => $settings->btn_line_height_unit_responsive,
        'font_family'       				=> $settings->btn_font_family,
        'transform'                         => $settings->btn_transform,
        'letter_spacing'                    => $settings->btn_letter_spacing,
));
?>

<?php if ( $settings->layout == 'inline' ) { ?> 
	@media ( min-width: <?php echo ( $global_settings->responsive_breakpoint + 1 ); ?>px ) {
		<?php if ( $settings->btn_width == 'auto' || $settings->btn_width == 'full' ) : ?>
		.fl-node-<?php echo $id; ?> .uabb-cta-inline .uabb-cta-text {
			width: 70%;
		}
		.fl-node-<?php echo $id; ?> .uabb-cta-inline .uabb-cta-button {
			width: 30%;
		}
		<?php endif; ?>
		.fl-node-<?php echo $id; ?> .uabb-creative-button-wrap {
			text-align: right;
		}
	}
<?php } ?>

<?php if ( $settings->btn_width == 'auto' ) { ?>
	<?php if ( $settings->layout == 'inline' ) { ?> 
	@media ( min-width: <?php echo ( $global_settings->responsive_breakpoint + 1 ); ?>px ) {
		.fl-node-<?php echo $id; ?> .uabb-creative-button-wrap {
			text-align: right;
		}
	}
	<?php } ?>
<?php } ?>
<?php if ( $settings->btn_width == 'custom' ) { ?>
	@media ( min-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
		.fl-node-<?php echo $id; ?> .fl-module-content .uabb-button { 
			margin-right: 0;
			margin-left: auto;
		}
	}
<?php } ?>

<?php
if( $settings->title_color != '' ) {
?>
	.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-cta-title {
		color: <?php echo $settings->title_color; ?>;
	}
<?php
}
?>

<?php
if( $settings->subhead_color != '' ) {
?>
	.fl-node-<?php echo $id; ?> .uabb-text-editor {
		color: <?php echo uabb_theme_text_color( $settings->subhead_color ); ?>;
	}
<?php
}
?>

/* Typography Options for Title */
<?php if( $settings->title_font_family['family'] != "Default" || isset( $settings->title_font_size['desktop'] ) || isset( $settings->title_line_height['desktop'] ) || isset( $settings->title_font_size_unit ) || isset( $settings->title_line_height_unit ) || isset( $settings->title_transform ) || isset( $settings->title_letter_spacing ) ) { ?>
	.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-cta-title {
	
		<?php if( $settings->title_font_family['family'] != "Default") : ?>
			<?php UABB_Helper::uabb_font_css( $settings->title_font_family ); ?>
		<?php endif; ?>

		<?php if( $converted === 'yes' || isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit != '' ) { ?>
	     	font-size: <?php echo $settings->title_font_size_unit; ?>px;
			<?php if( $settings->title_line_height_unit == '' &&  $settings->title_font_size_unit != '' ){ ?>
				line-height: <?php echo $settings->title_font_size_unit + 2 ; ?>px;
		    <?php } ?>		
	    <?php } else if( isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit == '' && isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] != '' ) { ?>
		    font-size: <?php echo $settings->title_font_size['desktop']; ?>px;
		    line-height: <?php echo $settings->title_font_size['desktop'] + 2 ; ?>px;
		<?php } ?>

		<?php if( isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '' && $settings->title_line_height_unit == '' ) { ?>
		    line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
		<?php } ?>

		 <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit != '' ) { ?>
	    	line-height: <?php echo $settings->title_line_height_unit; ?>em;	
	    <?php }  else if( isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '' ) { ?>
		    line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
		<?php } ?>

		<?php if($settings->title_transform!= "")?>
	   		text-transform: <?php echo $settings->title_transform; ?>;

		<?php if($settings->title_letter_spacing!= "")?>
	   		letter-spacing: <?php echo $settings->title_letter_spacing; ?>px;	
	}

<?php } ?>
/* Typography Options for Description */



<?php 
if( $settings->subhead_font_family['family'] != "Default" || isset( $settings->subhead_font_size['desktop'] ) && $settings->subhead_font_size['desktop'] != '' || isset( $settings->subhead_line_height['desktop'] ) && $settings->subhead_line_height['desktop'] != '' || isset( $settings->subhead_font_size_unit ) || isset( $settings->subhead_line_height_unit ) || isset( $settings->subhead_transform ) || isset( $settings->subhead_letter_spacing ) ) { ?>
	.fl-node-<?php echo $id; ?> .uabb-text-editor {
		<?php if( $settings->subhead_font_family['family'] != "Default") : ?>
			<?php UABB_Helper::uabb_font_css( $settings->subhead_font_family ); ?>
		<?php endif; ?>

		<?php if( $converted === 'yes' || isset( $settings->subhead_font_size_unit ) && $settings->subhead_font_size_unit != '' ) { ?>
	     	font-size: <?php echo $settings->subhead_font_size_unit; ?>px;
			<?php if( $settings->subhead_line_height_unit == '' &&  $settings->subhead_font_size_unit != '' ) { ?>
				line-height: <?php echo $settings->subhead_font_size_unit + 2 ; ?>px;
		    <?php } ?>		
	    <?php } else if( isset( $settings->subhead_font_size_unit ) && $settings->subhead_font_size_unit == '' && isset( $settings->subhead_font_size['desktop'] ) && $settings->subhead_font_size['desktop'] != '' ) { ?>
		    font-size: <?php echo $settings->subhead_font_size['desktop']; ?>px;
		    line-height: <?php echo $settings->subhead_font_size['desktop'] + 2 ; ?>px;
		<?php } ?>

		<?php if( isset( $settings->subhead_font_size['desktop'] ) && $settings->subhead_font_size['desktop'] == '' && isset( $settings->subhead_line_height['desktop'] ) && $settings->subhead_line_height['desktop'] != '' && $settings->subhead_line_height_unit == '' ) { ?>
		    line-height: <?php echo $settings->subhead_line_height['desktop']; ?>px;
		<?php } ?>

		<?php if( $converted === 'yes' || isset( $settings->subhead_line_height_unit ) && $settings->subhead_line_height_unit != '' ) { ?>
	    	line-height: <?php echo $settings->subhead_line_height_unit; ?>em;	
	    <?php }  else if( isset( $settings->subhead_line_height_unit ) && $settings->subhead_line_height_unit == '' && isset( $settings->subhead_line_height['desktop'] ) && $settings->subhead_line_height['desktop'] != '' ) { ?>
		    line-height: <?php echo $settings->subhead_line_height['desktop']; ?>px;
		<?php } ?>

		<?php if($settings->subhead_transform != "" )?>
		   text-transform: <?php echo $settings->subhead_transform; ?>;

	    <?php if($settings->subhead_letter_spacing != "" )?>
		   letter-spacing: <?php echo $settings->subhead_letter_spacing; ?>px;
	}
<?php } ?>

	 
		
/* Typography responsive css */
<?php if($global_settings->responsive_enabled) { // Global Setting If started 
	/*if( $settings->title_font_size['medium'] != "" || $settings->title_line_height['medium'] != "" || $settings->subhead_font_size['medium'] != "" || $settings->subhead_line_height['medium'] != "" )
	{*/
	?>
		@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
			<?php if( isset( $settings->title_font_size_unit_medium ) || isset( $settings->title_line_height_unit_medium ) || isset( $settings->title_font_size['medium'] ) || isset( $settings->title_line_height['medium'] ) ) { ?>
				.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-cta-title {

					<?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium != '' ) { ?>
						font-size: <?php echo $settings->title_font_size_unit_medium; ?>px;
						<?php if( $settings->title_line_height_unit_medium == '' && $settings->title_font_size_unit_medium != '' ) { ?>
							line-height: <?php $settings->title_font_size_unit_medium + 2?>px;
						<?php } ?>	
				    <?php } else if(  isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium == '' && isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] != '' ) {?>
				    	font-size: <?php echo $settings->title_font_size['medium']; ?>px;
					    line-height: <?php $settings->title_font_size['medium'] + 2?>px;
				    <?php } ?>
					
					<?php if( isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) { ?>
					    line-height: <?php echo $settings->title_line_height['medium']; ?>px;
					<?php } ?>

		            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_medium ) && $settings->title_line_height_unit_medium != '' ){ ?>
		            	line-height: <?php echo $settings->title_line_height_unit_medium; ?>em;	
		            <?php } else if( isset( $settings->title_line_height_unit_medium )&& $settings->title_line_height_unit_medium == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' ) {?>
		            	line-height: <?php echo $settings->title_line_height['medium']; ?>px;
		            <?php } ?>

				}
			<?php } ?>

			.fl-node-<?php echo $id; ?> .uabb-button-wrap .uabb-button {
				<?php
				if( $settings->btn_width == 'custom' ) {
				?>
				margin: 0 auto;
				<?php
				}
				?>
			}

			<?php if( isset( $settings->subhead_font_size_unit_medium ) || isset( $settings->subhead_line_height_unit_medium ) || isset( $settings->subhead_font_size['medium'] ) || isset( $settings->subhead_line_height['medium'] ) ) { ?>
				.fl-node-<?php echo $id; ?> .uabb-text-editor {

					<?php if( $converted === 'yes' || isset( $settings->subhead_font_size_unit_medium ) && $settings->subhead_font_size_unit_medium != '' ) { ?>
						font-size: <?php echo $settings->subhead_font_size_unit_medium; ?>px;
						<?php if( $settings->subhead_line_height_unit_medium == '' && $settings->subhead_font_size_unit_medium != '' ) { ?>
							line-height: <?php $settings->subhead_font_size_unit_medium + 2?>px;
						<?php } ?>	
				    <?php } else if(  isset( $settings->subhead_font_size_unit_medium ) && $settings->subhead_font_size_unit_medium == '' && isset( $settings->subhead_font_size['medium'] ) && $settings->subhead_font_size['medium'] != '' ) {?>
				    	font-size: <?php echo $settings->subhead_font_size['medium']; ?>px;
					    line-height: <?php $settings->subhead_font_size['medium'] + 2?>px;
				    <?php } ?>

					<?php if( isset( $settings->subhead_font_size['medium'] ) && $settings->subhead_font_size['medium'] == '' && isset( $settings->subhead_line_height['medium'] ) && $settings->subhead_line_height['medium'] != '' && $settings->subhead_line_height_unit_medium == '' &&  $settings->subhead_line_height_unit == '') { ?>
					    line-height: <?php echo $settings->subhead_line_height['medium']; ?>px;
					<?php } ?>

					<?php if( $converted === 'yes' || isset( $settings->subhead_line_height_unit_medium ) && $settings->subhead_line_height_unit_medium != '' ){ ?>
		            	line-height: <?php echo $settings->subhead_line_height_unit_medium; ?>em;	
		            <?php } else if( isset( $settings->subhead_line_height_unit_medium )&& $settings->subhead_line_height_unit_medium == '' && isset( $settings->subhead_line_height['medium'] ) && $settings->subhead_line_height['medium'] != '' ) {?>
		            	line-height: <?php echo $settings->subhead_line_height['medium']; ?>px;
		            <?php } ?>

				}
			<?php } ?>
		}
	<?php
	/*}
	if( $settings->title_font_size['small'] != "" || $settings->title_line_height['small'] != "" || $settings->subhead_font_size['small'] != "" || $settings->subhead_line_height['small'] != "" )
	{*/
	?>
		@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
			<?php if( isset( $settings->title_font_size_unit_responsive ) || isset( $settings->title_line_height_unit_responsive ) || isset( $settings->title_font_size['small'] ) || isset( $settings->title_line_height['small'] ) ) { ?>
				.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-cta-title {

					<?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive != '' ) { ?>
						font-size: <?php echo $settings->title_font_size_unit_responsive; ?>px;
						<?php if( $settings->title_line_height_unit_responsive == '' && $settings->title_font_size_unit_responsive != '' ) {?>
							line-height: <?php echo $settings->title_font_size_unit_responsive + 2 ?>px;
					    <?php } ?>		
					<?php } else if(isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive == '' && isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] != '') { ?>
						font-size: <?php echo $settings->title_font_size['small']; ?>px;
					    line-height: <?php echo $settings->title_font_size['small'] + 2 ?>px;
					<?php } ?>

					<?php if( isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' && $settings->title_line_height_unit_responsive == '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) { ?>
					    line-height: <?php echo $settings->title_line_height['small']; ?>px;
					<?php } ?>

					<?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_responsive ) && $settings->title_line_height_unit_responsive != '' ){ ?>
		            	line-height: <?php echo $settings->title_line_height_unit_responsive; ?>em;	
		            <?php } else if( isset( $settings->title_line_height_unit_responsive )&& $settings->title_line_height_unit_responsive == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' ) {?>
		            	line-height: <?php echo $settings->title_line_height['small']; ?>px;
		            <?php } ?>

				}
			<?php } ?>

			.fl-node-<?php echo $id; ?> .uabb-button-wrap .uabb-button {
				<?php
				if( $settings->btn_width == 'custom' ) {
				?>
				margin: 0 auto;
				<?php
				}
				?>
			}

			<?php if( isset( $settings->subhead_font_size_unit_responsive ) || isset( $settings->subhead_line_height_unit_responsive ) || isset( $settings->subhead_font_size['small'] ) || isset( $settings->subhead_line_height['small'] ) ) { ?>
				.fl-node-<?php echo $id; ?> .uabb-text-editor {

					<?php if( $converted === 'yes' || isset( $settings->subhead_font_size_unit_responsive ) && $settings->subhead_font_size_unit_responsive != '' ) { ?>
						font-size: <?php echo $settings->subhead_font_size_unit_responsive; ?>px;
						<?php if( $settings->subhead_line_height_unit_responsive == '' && $settings->subhead_font_size_unit_responsive != '' ) {?>
							line-height: <?php echo $settings->subhead_font_size_unit_responsive + 2 ?>px;
					    <?php } ?>		
					<?php } else if(isset( $settings->subhead_font_size_unit_responsive ) && $settings->subhead_font_size_unit_responsive == '' && isset( $settings->subhead_font_size['small'] ) && $settings->subhead_font_size['small'] != '') { ?>
						font-size: <?php echo $settings->subhead_font_size['small']; ?>px;
					    line-height: <?php echo $settings->subhead_font_size['small'] + 2 ?>px;
					<?php } ?>

					<?php if( isset( $settings->subhead_font_size['small'] ) && $settings->subhead_font_size['small'] == '' && isset( $settings->subhead_line_height['small'] ) && $settings->subhead_line_height['small'] != '' && $settings->subhead_line_height_unit_responsive == '' ) { ?>
					    line-height: <?php echo $settings->subhead_line_height['small']; ?>px;
					<?php } ?>

					<?php if( $converted === 'yes' || isset( $settings->subhead_line_height_unit_responsive ) && $settings->subhead_line_height_unit_responsive != '' ){ ?>
		            	line-height: <?php echo $settings->subhead_line_height_unit_responsive; ?>em;	
		            <?php } else if( isset( $settings->subhead_line_height_unit_responsive )&& $settings->subhead_line_height_unit_responsive == '' && isset( $settings->subhead_line_height['small'] ) && $settings->subhead_line_height['small'] != '' ) {?>
		            	line-height: <?php echo $settings->subhead_line_height['small']; ?>px;
		            <?php } ?>

				}
			<?php } ?>
		}
	<?php
	/*}*/
}
?>
