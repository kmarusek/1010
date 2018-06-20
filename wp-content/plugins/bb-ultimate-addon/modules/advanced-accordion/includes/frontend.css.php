<?php 

	global $post;
	$converted = get_post_meta( $post->ID,'_uabb_converted', true );

	$settings->title_color = UABB_Helper::uabb_colorpicker( $settings, 'title_color' );
	$settings->title_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'title_hover_color' );
	
	$settings->title_bg_color = UABB_Helper::uabb_colorpicker( $settings, 'title_bg_color', true );
	$settings->title_bg_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'title_bg_hover_color', true );

	$settings->title_border_color = UABB_Helper::uabb_colorpicker( $settings, 'title_border_color' );

	$settings->icon_color = UABB_Helper::uabb_colorpicker( $settings, 'icon_color' );
	$settings->icon_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'icon_hover_color' );

	$settings->content_color = UABB_Helper::uabb_colorpicker( $settings, 'content_color' );
	$settings->content_bg_color = UABB_Helper::uabb_colorpicker( $settings, 'content_bg_color', true );

	$settings->content_border_color = UABB_Helper::uabb_colorpicker( $settings, 'content_border_color' );
	
	$settings->title_margin = ( $settings->title_margin != '' ) ? $settings->title_margin : '10';
	$settings->icon_size = ( $settings->icon_size != '' ) ? $settings->icon_size : '16';
	$settings->title_border_top = ( $settings->title_border_top != '' ) ? $settings->title_border_top : '1';
	$settings->title_border_bottom = ( $settings->title_border_bottom != '' ) ? $settings->title_border_bottom : '1';
	$settings->title_border_radius = ( $settings->title_border_radius != '' ) ? $settings->title_border_radius : '0';
	$settings->content_border_radius = ( $settings->content_border_radius != '' ) ? $settings->content_border_radius : '0';
?>

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item {
	<?php if ( is_numeric( $settings->title_margin ) ) { ?>
		margin-bottom: <?php echo $settings->title_margin; ?>px;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> {
	
	<?php 
	if( $converted === 'yes' ||  isset($settings->title_spacing_dimension_top) && isset( $settings->title_spacing_dimension_bottom ) && isset( $settings->title_spacing_dimension_left )  && isset( $settings->title_spacing_dimension_right ) ) {
		if(isset($settings->title_spacing_dimension_top) ){
            echo ( $settings->title_spacing_dimension_top != '' ) ? 'padding-top:'.$settings->title_spacing_dimension_top.'px;' : 'padding-top: 15px;'; 
        }
        if(isset($settings->title_spacing_dimension_bottom) ){
            echo ( $settings->title_spacing_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->title_spacing_dimension_bottom.'px;' : 'padding-bottom: 15px;';
        }
        if(isset($settings->title_spacing_dimension_left) ){
            echo ( $settings->title_spacing_dimension_left != '' ) ? 'padding-left:'.$settings->title_spacing_dimension_left.'px;' : 'padding-left: 15px;';
        }
        if(isset($settings->title_spacing_dimension_right) ){
            echo ( $settings->title_spacing_dimension_right != '' ) ? 'padding-right:'.$settings->title_spacing_dimension_right.'px;' : 'padding-right: 15px;';
        } 
	}
	else if( isset( $settings->title_spacing ) && $settings->title_spacing != '' && isset( $settings->title_spacing_dimension_top ) && $settings->title_spacing_dimension_top == '' && isset( $settings->title_spacing_dimension_bottom ) && $settings->title_spacing_dimension_bottom == ''  && isset( $settings->title_spacing_dimension_left ) && $settings->title_spacing_dimension_left == ''  && isset( $settings->title_spacing_dimension_right ) && $settings->title_spacing_dimension_right == '' ) {
		echo $settings->title_spacing; ?>;
	<?php } ?>

	background: <?php echo $settings->title_bg_color; ?>;
<?php if ( $settings->title_border_type != 'none' ) {// var_dump( $settings->title_border_top); die(); ?>
	border: <?php echo $settings->title_border_type; ?> <?php echo $settings->title_border_color; ?>;
	border-top-width: <?php echo $settings->title_border_top; ?>px;
	border-bottom-width: <?php echo $settings->title_border_bottom; ?>px;
	border-left-width: <?php echo ( $settings->title_border_left != '' ) ? $settings->title_border_left : '1'; ?>px;
	border-right-width: <?php echo ( $settings->title_border_right != '' ) ? $settings->title_border_right : '1'; ?>px;
	<?php if( ( $settings->title_margin == 0 ) && ( $settings->title_border_top != 0 ) ) { ?>
	border-bottom-width: 0;
	<?php } ?>
    -webkit-transition: all 15ms linear;
       -moz-transition: all 15ms linear;
         -o-transition: all 15ms linear;
			transition: all 15ms linear;
<?php } ?>
	border-radius: <?php echo $settings->title_border_radius; ?>px;	

	<?php if( $settings->open_icon == '' && $settings->close_icon == '' ) : ?>
	width: 100%;
	<?php endif; ?>
}

<?php if( ( $settings->title_margin == 0 ) && ( $settings->title_border_top != 0 ) ) : ?>

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item:last-child .uabb-adv-accordion-button<?php echo $id; ?>,
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item-active > .uabb-adv-accordion-button<?php echo $id; ?> {
	<?php if ( $settings->title_border_type != 'none' ) { ?>
		border-bottom-width: <?php echo $settings->title_border_bottom; ?>px;
	<?php } ?>
}

<?php endif; ?>

<?php if( $settings->open_icon == '' && $settings->close_icon == '' ) : ?>
.fl-node-<?php echo $id; ?> .uabb-adv-before-text .uabb-adv-accordion-button-label {
	padding-left: 0;
}
.fl-node-<?php echo $id; ?> .uabb-adv-after-text .uabb-adv-accordion-button-label {
	padding-right: 0;
}
<?php endif; ?>

/* Color */
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-label {
	color: <?php echo $settings->title_color; ?>;
	text-align: <?php echo $settings->title_align; ?>;
    -webkit-transition: all 15ms linear;
       -moz-transition: all 15ms linear;
         -o-transition: all 15ms linear;
			transition: all 15ms linear;
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-icon {
	color: <?php echo $settings->icon_color; ?>;
    -webkit-transition: color 15ms linear, transform 60ms linear;
       -moz-transition: color 15ms linear, transform 60ms linear;
         -o-transition: color 15ms linear, transform 60ms linear;
			transition: color 15ms linear, transform 60ms linear;
}


/* Content css */

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?> {
	background: <?php echo $settings->content_bg_color; ?>;
	<?php 
	if( $converted === 'yes' || isset($settings->content_spacing_dimension_top) && isset( $settings->content_spacing_dimension_bottom )  && isset( $settings->content_spacing_dimension_left ) && isset( $settings->content_spacing_dimension_right ) ) { 
        if(isset($settings->content_spacing_dimension_top) ){
            echo ( $settings->content_spacing_dimension_top != '' ) ? 'padding-top:'.$settings->content_spacing_dimension_top.'px;' : 'padding-top: 20px;'; 
        }
        if(isset($settings->content_spacing_dimension_bottom) ){
            echo ( $settings->content_spacing_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->content_spacing_dimension_bottom.'px;' : 'padding-bottom: 20px;';
        }
        if(isset($settings->content_spacing_dimension_left) ){
            echo ( $settings->content_spacing_dimension_left != '' ) ? 'padding-left:'.$settings->content_spacing_dimension_left.'px;' : 'padding-left: 20px;';
        }
        if(isset($settings->content_spacing_dimension_right) ){
            echo ( $settings->content_spacing_dimension_right != '' ) ? 'padding-right:'.$settings->content_spacing_dimension_right.'px;' : 'padding-right: 20px;';
        }
	} else if( isset( $settings->content_spacing ) && $settings->content_spacing != '' && isset( $settings->content_spacing_dimension_top ) && $settings->content_spacing_dimension_top == '' && isset( $settings->content_spacing_dimension_bottom ) && $settings->content_spacing_dimension_bottom == '' && isset( $settings->content_spacing_dimension_left ) && $settings->content_spacing_dimension_left == '' && isset( $settings->content_spacing_dimension_right ) && $settings->content_spacing_dimension_right == '' ) { ?>
			<?php echo $settings->content_spacing; ?>;
	 <?php } ?>

	text-align: <?php echo $settings->content_align; ?>;
	<?php if ( $settings->content_border_type != 'none' ) { ?>
		border: <?php echo $settings->content_border_type ?> <?php echo $settings->content_border_color; ?>;
		border-top-width: <?php echo ( $settings->content_border_top != '')  ? $settings->content_border_top : '1'; ?>px;
		border-bottom-width: <?php echo ( $settings->content_border_bottom != '')  ? $settings->content_border_bottom : '1'; ?>px;
		border-left-width: <?php echo ( $settings->content_border_left != '')  ? $settings->content_border_left : '1'; ?>px;
		border-right-width: <?php echo ( $settings->content_border_right != '')  ? $settings->content_border_right : '1'; ?>px;
	<?php } ?>
	border-radius: <?php echo $settings->content_border_radius; ?>px;
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?>.uabb-accordion-desc {
	color: <?php echo $settings->content_color; ?>;
}

/* Hover State */
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?>:hover .uabb-adv-accordion-button-label,
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item-active > .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-label {
	color: <?php echo $settings->title_hover_color; ?>;
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?>:hover,
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item-active > .uabb-adv-accordion-button<?php echo $id; ?> {
	background: <?php echo $settings->title_bg_hover_color; ?>;
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?>:hover .uabb-adv-accordion-button-icon,
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-item-active > .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-icon {
	color: <?php echo $settings->icon_hover_color; ?>;
}


/* Typography */
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-label {
	<?php if( $settings->font_family['family'] != "Default") : ?>
		<?php UABB_Helper::uabb_font_css( $settings->font_family ); ?>
	<?php endif; ?>
        
    <?php if( $converted === 'yes' || isset( $settings->font_size_unit ) && $settings->font_size_unit != '' ) { ?>
     	font-size: <?php echo $settings->font_size_unit; ?>px;
		<?php if( $settings->line_height_unit == '' &&  $settings->font_size_unit != '' ){ ?>
			line-height: <?php echo $settings->font_size_unit + 2 ; ?>px;
	    <?php } ?>		
    <?php } else if( isset( $settings->font_size_unit ) && $settings->font_size_unit == '' && isset( $settings->font_size['desktop'] ) && $settings->font_size['desktop'] != '' ) { ?>
	    font-size: <?php echo $settings->font_size['desktop']; ?>px;
	    line-height: <?php echo $settings->font_size['desktop'] + 2 ; ?>px;
	<?php } ?>

	<?php if( isset( $settings->font_size['desktop'] ) && $settings->font_size['desktop'] == '' && isset( $settings->line_height['desktop'] ) && $settings->line_height['desktop'] != '' && $settings->line_height_unit == '' ) { ?>
		line-height: <?php echo $settings->line_height['desktop']; ?>px;
	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->line_height_unit ) && $settings->line_height_unit != '' ) { ?>
    	line-height: <?php echo $settings->line_height_unit; ?>em;	
    <?php }  else if( isset( $settings->line_height_unit ) && $settings->line_height_unit == '' && isset( $settings->line_height['desktop'] ) && $settings->line_height['desktop'] != '' ) { ?>
	    line-height: <?php echo $settings->line_height['desktop']; ?>px;
	<?php } ?>

		<?php if( $settings->transform != '' )?>
		   text-transform: <?php echo $settings->transform; ?>;

        <?php if( $settings->letter_spacing != '' )?>
		   letter-spacing: <?php echo $settings->letter_spacing; ?>px;

}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-icon,
.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-icon.dashicons:before {
	<?php if( $settings->icon_size != '' ) : ?>
		font-size: <?php echo $settings->icon_size; ?>px;
		line-height: <?php echo $settings->icon_size + 2; ?>px;
		height: <?php echo $settings->icon_size + 2; ?>px;
		width: <?php echo $settings->icon_size + 2; ?>px;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?>.uabb-accordion-desc {
	<?php if( $settings->content_font_family['family'] != "Default") : ?>
		<?php UABB_Helper::uabb_font_css( $settings->content_font_family ); ?>
	<?php endif; ?>
	
    <?php if( $converted === 'yes' || isset( $settings->content_font_size_unit ) &&  $settings->content_font_size_unit != '' ){ ?>
    	font-size: <?php echo $settings->content_font_size_unit; ?>px;
		<?php if( $settings->content_line_height_unit == '' && $settings->content_font_size_unit != '' ){ ?>
			line-height: <?php echo $settings->content_font_size_unit + 2 ; ?>px;
		<?php } ?>	
    <?php } else if( isset( $settings->content_font_size_unit ) && $settings->content_font_size_unit == '' && isset( $settings->content_font_size['desktop'] ) && $settings->content_font_size['desktop'] != '' ) {?> 
    	font-size: <?php echo $settings->content_font_size['desktop']; ?>px;
		line-height: <?php echo $settings->content_font_size['desktop'] + 2 ; ?>px;
    <?php } ?> 

    <?php if( isset( $settings->content_font_size['desktop'] ) && $settings->content_font_size['desktop'] == '' && isset( $settings->content_line_height['desktop'] ) && $settings->content_line_height['desktop'] != '' && $settings->content_line_height_unit == ''  ) { ?>
	    line-height: <?php echo $settings->content_line_height['desktop']; ?>px;
	<?php } ?>
	
	<?php if( $converted === 'yes' || isset( $settings->content_line_height_unit ) &&  $settings->content_line_height_unit != ''){ ?>
		line-height: <?php echo $settings->content_line_height_unit; ?>em;
	<?php } else if( isset( $settings->content_line_height_unit )&& $settings->content_line_height_unit == '' && isset( $settings->content_line_height['desktop'] ) && $settings->content_line_height['desktop'] != '' ) { ?>
		line-height: <?php echo $settings->content_line_height['desktop']; ?>px;
	 <?php } ?>    

	<?php if( $settings->content_transform != '' )?>
	   text-transform: <?php echo $settings->content_transform; ?>;

    <?php if( $settings->content_letter_spacing != '' )?>
	   letter-spacing: <?php echo $settings->content_letter_spacing; ?>px;
}


<?php
if($global_settings->responsive_enabled) { // Global Setting If started  ?>
	@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {

	    .fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?> {
			<?php 
		        if(isset($settings->content_spacing_dimension_top_medium) ){
		            echo ( $settings->content_spacing_dimension_top_medium != '' ) ? 'padding-top:'.$settings->content_spacing_dimension_top_medium.'px;' : ''; 
		        }
		        if(isset($settings->content_spacing_dimension_bottom_medium) ){
		            echo ( $settings->content_spacing_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->content_spacing_dimension_bottom_medium.'px;' : '';
		        }
		        if(isset($settings->content_spacing_dimension_left_medium) ){
		            echo ( $settings->content_spacing_dimension_left_medium != '' ) ? 'padding-left:'.$settings->content_spacing_dimension_left_medium.'px;' : '';
		        }
		        if(isset($settings->content_spacing_dimension_right_medium) ){
		            echo ( $settings->content_spacing_dimension_right_medium != '' ) ? 'padding-right:'.$settings->content_spacing_dimension_right_medium.'px;' : '';
		        } 
		    ?>
		}

		.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> {
			<?php 
		        if(isset($settings->title_spacing_dimension_top_medium) ){
		            echo ( $settings->title_spacing_dimension_top_medium != '' ) ? 'padding-top:'.$settings->title_spacing_dimension_top_medium.'px;' : ''; 
		        }
		        if(isset($settings->title_spacing_dimension_bottom_medium) ){
		            echo ( $settings->title_spacing_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->title_spacing_dimension_bottom_medium.'px;' : '';
		        }
		        if(isset($settings->title_spacing_dimension_left_medium) ){
		            echo ( $settings->title_spacing_dimension_left_medium != '' ) ? 'padding-left:'.$settings->title_spacing_dimension_left_medium.'px;' : '';
		        }
		        if(isset($settings->title_spacing_dimension_right_medium) ){
		            echo ( $settings->title_spacing_dimension_right_medium != '' ) ? 'padding-right:'.$settings->title_spacing_dimension_right_medium.'px;' : '';
		        } 
		    ?>
        }

		.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?>.uabb-accordion-desc {
		    
		    <?php if( $converted === 'yes' || isset( $settings->content_font_size_unit_medium ) && $settings->content_font_size_unit_medium != '' ){ ?>
		    	font-size: <?php echo $settings->content_font_size_unit_medium; ?>px;
				<?php if( $settings->content_line_height_unit_medium == '' && $settings->content_font_size_unit_medium != '' ) { ?>
					line-height: <?php $settings->content_font_size_unit_medium + 2?>px;
				<?php } ?>	
		    <?php } else if(  isset( $settings->content_font_size_unit_medium ) && $settings->content_font_size_unit_medium == '' && isset( $settings->content_font_size['medium'] ) && $settings->content_font_size['medium'] != '' ) {?>
		    	font-size: <?php echo $settings->content_font_size['medium']; ?>px;
			    line-height: <?php $settings->content_font_size['medium'] + 2?>px;
		    <?php } ?> 

		    <?php if( isset( $settings->content_font_size['medium'] ) && $settings->content_font_size['medium'] == '' && isset( $settings->content_line_height['medium'] ) && $settings->content_line_height['medium'] != '' && $settings->content_line_height_unit_medium == '' && $settings->content_line_height_unit == '' ) : ?>
		    	line-height: <?php echo $settings->content_line_height['medium']; ?>px;
			<?php endif; ?>
            
            <?php if( $converted === 'yes' || isset( $settings->content_line_height_unit_medium ) && $settings->content_line_height_unit_medium != '' ){ ?>
            	line-height: <?php echo $settings->content_line_height_unit_medium; ?>em;	
            <?php } else if( isset( $settings->content_line_height_unit_medium )&& $settings->content_line_height_unit_medium == '' && isset( $settings->content_line_height['medium'] ) && $settings->content_line_height['medium'] != '' ) {?>
            	line-height: <?php echo $settings->content_line_height['medium']; ?>px;
            <?php } ?>

		}
	}		
	<?php  /* Small Breakpoint media query */ ?>
		@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-label {

				<?php if( $converted === 'yes' || isset( $settings->font_size_unit_medium ) && $settings->font_size_unit_medium != '' ){ ?>
					font-size: <?php echo $settings->font_size_unit_medium; ?>px;
					<?php if( $settings->line_height_unit_medium == '' && $settings->font_size_unit_medium != '' ) { ?>
						line-height: <?php echo $settings->font_size_unit_medium + 2 ; ?>px;
					<?php } ?>	
				<?php } else if( isset( $settings->font_size_unit_medium ) && $settings->font_size_unit_medium == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '') { ?>
					font-size: <?php echo $settings->font_size['medium']; ?>px;
				    line-height: <?php echo $settings->font_size['medium'] + 2 ; ?>px;
				<?php } ?>
			    
			    <?php if( isset( $settings->font_size['medium'] ) && $settings->font_size['medium'] == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '' && $settings->line_height_unit_medium == '' && $settings->line_height_unit == '' ) :?>
				    	line-height: <?php echo $settings->line_height['medium']; ?>px;
				<?php endif; ?> 
                
                <?php if( $converted === 'yes' || isset( $settings->line_height_unit_medium ) && $settings->line_height_unit_medium != '' ){ ?>
                	line-height: <?php echo $settings->line_height_unit_medium; ?>em;
                <?php } else if( isset( $settings->line_height_unit_medium )&& $settings->line_height_unit_medium == '' && isset( $settings->line_height['medium'] ) && $settings->line_height['medium'] != '' ){?>
                	line-height: <?php echo $settings->line_height['medium']; ?>px;
            	<?php } ?>
			}

			.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?> {
				<?php 
			        if(isset($settings->content_spacing_dimension_top_responsive) ){
			            echo ( $settings->content_spacing_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->content_spacing_dimension_top_responsive.'px;' : ''; 
			        }
			        if(isset($settings->content_spacing_dimension_bottom_responsive) ){
			            echo ( $settings->content_spacing_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->content_spacing_dimension_bottom_responsive.'px;' : '';
			        }
			        if(isset($settings->content_spacing_dimension_left_responsive) ){
			            echo ( $settings->content_spacing_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->content_spacing_dimension_left_responsive.'px;' : '';
			        }
			        if(isset($settings->content_spacing_dimension_right_responsive) ){
			            echo ( $settings->content_spacing_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->content_spacing_dimension_right_responsive.'px;' : '';
			        } 
			    ?>
			}

			.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> {
				<?php 
			        if(isset($settings->title_spacing_dimension_top_responsive) ){
			            echo ( $settings->title_spacing_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->title_spacing_dimension_top_responsive.'px;' : ''; 
			        }
			        if(isset($settings->title_spacing_dimension_bottom_responsive) ){
			            echo ( $settings->title_spacing_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->title_spacing_dimension_bottom_responsive.'px;' : '';
			        }
			        if(isset($settings->title_spacing_dimension_left_responsive) ){
			            echo ( $settings->title_spacing_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->title_spacing_dimension_left_responsive.'px;' : '';
			        }
			        if(isset($settings->title_spacing_dimension_right_responsive) ){
			            echo ( $settings->title_spacing_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->title_spacing_dimension_right_responsive.'px;' : '';
			        } 
			    ?>
        	}
		}		
	<?php
	/* Content Responsive */
    ?>
	@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
		.fl-node-<?php echo $id; ?> .uabb-adv-accordion-button<?php echo $id; ?> .uabb-adv-accordion-button-label {
			<?php if( $converted === 'yes' || isset( $settings->font_size_unit_responsive ) && $settings->font_size_unit_responsive != '' ) { ?>
				font-size: <?php echo $settings->font_size_unit_responsive; ?>px;
				<?php if( $settings->line_height_unit_responsive == '' && $settings->font_size_unit_responsive != '' ) {?>
					line-height: <?php echo $settings->font_size_unit_responsive + 2 ?>px;
			    <?php } ?>		
			<?php } else if(isset( $settings->font_size_unit_responsive ) && $settings->font_size_unit_responsive == '' && isset( $settings->font_size['small'] ) && $settings->font_size['small'] != '') { ?>
				font-size: <?php echo $settings->font_size['small']; ?>px;
			    line-height: <?php echo $settings->font_size['small'] + 2 ?>px;
			<?php } ?>
		    
		    <?php if( isset( $settings->font_size['small'] ) && $settings->font_size['small'] == '' && isset( $settings->line_height['small'] ) && $settings->line_height['small'] != '' && $settings->line_height_unit_responsive == '' && $settings->line_height_unit == '' ) { ?>
			    line-height: <?php echo $settings->line_height['small']; ?>px;
			<?php } ?>
            
            <?php if( $converted === 'yes' || isset( $settings->line_height_unit_responsive ) && $settings->line_height_unit_responsive != '' ) { ?>
            	line-height: <?php echo $settings->line_height_unit_responsive; ?>em;
            <?php } else if( isset( $settings->line_height_unit_responsive )&& $settings->line_height_unit_responsive == '' && isset( $settings->line_height['small'] ) && $settings->line_height['small'] != '' ) {?>
            	line-height: <?php echo $settings->line_height['small']; ?>px;
        	<?php } ?>
		}
	}			
	<?php if( isset($settings->content_font_size['small']) &&  $settings->content_font_size['small']!= "" || isset($settings->content_line_height['small']) && $settings->content_line_height['small']!= "" || isset($settings->content_font_size_unit_responsive) || isset($settings->content_line_height_unit_responsive) || isset($settings->content_line_height_unit_medium) || isset($settings->content_line_height_unit) ) {
		/* Small Breakpoint media query */	
	?>

		@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
			.fl-node-<?php echo $id; ?> .uabb-adv-accordion-content<?php echo $id; ?>.uabb-accordion-desc {
            
	            <?php if( $converted === 'yes' || isset( $settings->content_font_size_unit_responsive ) && $settings->content_font_size_unit_responsive != '' ) { ?>
	            	font-size: <?php echo $settings->content_font_size_unit_responsive; ?>px;
					<?php if( $settings->content_line_height_unit_responsive == '' && $settings->content_font_size_unit_responsive != '' ) {?>
						line-height: <?php $settings->content_font_size_unit_responsive + 2 ?>px;
					<?php } ?>	
	            <?php } else if( isset( $settings->content_font_size_unit_responsive ) && $settings->content_font_size_unit_responsive == '' && isset( $settings->content_font_size['small'] ) && $settings->content_font_size['small'] != '' ) {?> 
	            	font-size: <?php echo $settings->content_font_size['small']; ?>px;
				    line-height: <?php $settings->content_font_size['small'] + 2?>px;
	            <?php } ?>

			    <?php if( isset( $settings->content_font_size['small'] ) && $settings->content_font_size['small'] == '' && isset( $settings->content_line_height['small'] ) && $settings->content_line_height['small'] != '' && $settings->content_line_height_unit_responsive == '' && $settings->content_line_height_unit_medium == '' && $settings->content_line_height_unit == '' ) : ?>
			    	line-height: <?php echo $settings->content_line_height['small']; ?>px;
				<?php endif; ?>
	            
	            <?php if( $converted === 'yes' || isset( $settings->content_line_height_unit_responsive ) && $settings->content_line_height_unit_responsive != '' ) { ?>
	            	line-height: <?php echo $settings->content_line_height_unit_responsive; ?>em;
	            <?php } else if( isset( $settings->content_line_height_unit_responsive )&& $settings->content_line_height_unit_responsive == '' && isset( $settings->content_line_height['small'] ) && $settings->content_line_height['small'] != '') { ?> 
	            	line-height: <?php echo $settings->content_line_height['small']; ?>px;
	            <?php } ?>

			}
		}		
	<?php
	}
} ?>