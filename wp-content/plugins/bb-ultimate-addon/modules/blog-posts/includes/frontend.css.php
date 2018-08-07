<?php

global $post;
$converted = get_post_meta( $post->ID,'_uabb_converted', true );

$settings->title_color = UABB_Helper::uabb_colorpicker( $settings, 'title_color' );
$settings->desc_color = UABB_Helper::uabb_colorpicker( $settings, 'desc_color' );
$settings->content_background_color = UABB_Helper::uabb_colorpicker( $settings, 'content_background_color', true );

$settings->arrow_color = UABB_Helper::uabb_colorpicker( $settings, 'arrow_color' );
$settings->arrow_background_color = UABB_Helper::uabb_colorpicker( $settings, 'arrow_background_color', true);
$settings->arrow_color_border = UABB_Helper::uabb_colorpicker( $settings, 'arrow_color_border' );

$settings->date_color = UABB_Helper::uabb_colorpicker( $settings, 'date_color' );
$settings->date_background_color = UABB_Helper::uabb_colorpicker( $settings, 'date_background_color', true );
$settings->meta_color = UABB_Helper::uabb_colorpicker( $settings, 'meta_color' );
$settings->meta_text_color = UABB_Helper::uabb_colorpicker( $settings, 'meta_text_color' );
$settings->meta_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'meta_hover_color' );

$settings->link_color = UABB_Helper::uabb_colorpicker( $settings, 'link_color' );
$settings->link_more_arrow_color = UABB_Helper::uabb_colorpicker( $settings, 'link_more_arrow_color' );

$settings->masonary_text_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_text_color' );
$settings->taxonomy_filter_select_color = UABB_Helper::uabb_colorpicker( $settings, 'taxonomy_filter_select_color' );
$settings->selfilter_background_color = UABB_Helper::uabb_colorpicker( $settings, 'selfilter_background_color' );
$settings->selfilter_color_border = UABB_Helper::uabb_colorpicker( $settings, 'selfilter_color_border' );

$settings->masonary_background_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_background_color', true );
$settings->masonary_text_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_text_hover_color' );
$settings->masonary_background_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_background_hover_color', true );
$settings->masonary_background_active_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_background_active_color', true );
$settings->masonary_active_color = UABB_Helper::uabb_colorpicker( $settings, 'masonary_active_color' );

$settings->pagination_background_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_background_color', true );
$settings->pagination_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_color' );
$settings->pagination_hover_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_hover_color' );
$settings->pagination_active_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_active_color' );
$settings->pagination_hover_background_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_hover_background_color', true );
$settings->pagination_active_background_color = UABB_Helper::uabb_colorpicker( $settings, 'pagination_active_background_color', true );
$settings->pagination_active_color_border = UABB_Helper::uabb_colorpicker( $settings, 'pagination_active_color_border' );
$settings->pagination_color_border = UABB_Helper::uabb_colorpicker( $settings, 'pagination_color_border' );

$settings->masonary_border_size = ( $settings->masonary_border_size != '' ) ? $settings->masonary_border_size : '2';
$settings->pagination_border_size = ( $settings->pagination_border_size != '' ) ? $settings->pagination_border_size : '2';
$settings->masonary_color_border = UABB_Helper::uabb_colorpicker( $settings, 'masonary_color_border' );
$settings->masonary_active_color_border = UABB_Helper::uabb_colorpicker( $settings, 'masonary_active_color_border' );

$settings->overlay_color = UABB_Helper::uabb_colorpicker( $settings, 'overlay_color', true );

$settings->title_margin_top = ( isset( $settings->title_margin_top ) ) ? $settings->title_margin_top : '';
$settings->title_margin_bottom = ( isset( $settings->title_margin_bottom ) ) ? $settings->title_margin_bottom : '';

$settings->element_space = ( isset( $settings->element_space ) && $settings->element_space != '' ) ? $settings->element_space : '15';


$settings->show_meta = ( isset( $settings->show_meta ) ) ? $settings->show_meta : 'yes';

if( $settings->is_carousel == 'grid' ) {
	if( $settings->equal_height_box == 'yes' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-posts {
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	    flex-wrap: wrap;
}

.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
}
<?php
	}
}

if( $settings->blog_image_position == 'top' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-thumbnail img {
	display: inline-block;
}
.fl-node-<?php echo $id; ?> .uabb-post-wrapper .uabb-post-thumbnail {
	text-align: <?php echo $settings->overall_alignment; ?>;
}
<?php
}

//if( $settings->is_carousel != 'masonary' && $settings->is_carousel != 'feed' ) {
	if( $settings->equal_height_box == 'yes' && $settings->blog_image_position == 'background' ) {
	?>
	.fl-node-<?php echo $id; ?> .uabb-thumbnail-position-background {
		height: 100%;
	}
	<?php
	}

	if( $settings->blog_image_position == 'background' ) {
	?>
	.fl-node-<?php echo $id; ?> .uabb-post-thumbnail:before {
		content: '';
	    <?php echo ( $settings->overlay_color != '' ) ? 'background: ' . $settings->overlay_color . ';' : ''; ?>
	    position: absolute;
	    left: 0;
	    top: 0;
	    width: 100%;
	    height: 100%;
	    z-index: 1;
	}
	<?php
	}
//}

if( $settings->cta_type == 'button' ) {

	FLBuilder::render_module_css('uabb-button', $id , array(
		/* General Section */
        'text'              => $settings->btn_text,
        
        /* Link Section */
        /*'link'              => $settings->btn_link,
        'link_target'       => $settings->btn_link_target,*/
        
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
        'align'              => $settings->overall_alignment,
        'mob_align'          => '',

        /* Typography */
        'font_size'                   => ( isset($settings->btn_font_size) ) ? $settings->btn_font_size : '',
        'line_height'                 => ( isset($settings->btn_line_height) ) ? $settings->btn_line_height : '',
        'line_height_unit'            => $settings->btn_line_height_unit,
        'font_size_unit'              => $settings->btn_font_size_unit,
        'font_size_unit_medium'       => $settings->btn_font_size_unit_medium,
        'line_height_unit_medium'     => $settings->btn_line_height_unit_medium,
        'font_size_unit_responsive'   => $settings->btn_font_size_unit_responsive,
        'line_height_unit_responsive' => $settings->btn_line_height_unit_responsive,

        'font_family'       => $settings->btn_font_family,
	));

}

if( $settings->blog_image_position == 'left' || $settings->blog_image_position == 'right' ) {
	if( $settings->featured_image_size == 'custom' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-post-inner-wrap .uabb-blog-post-content {
	width: calc( 100% - <?php echo $settings->featured_image_size_width; ?>px );
}
.fl-node-<?php echo $id; ?> .uabb-blog-post-inner-wrap .uabb-post-thumbnail {
	width: <?php echo $settings->featured_image_size_width; ?>px;
}
<?php
	}
}

if( $settings->blog_image_position != 'top' && $settings->blog_image_position != 'background' ) {
?>
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
		<?php 
		if( $converted === 'yes' || isset( $settings->overall_padding_dimension_top ) && $settings->overall_padding_dimension_top != '' && isset( $settings->overall_padding_dimension_bottom ) && $settings->overall_padding_dimension_bottom != '' && isset( $settings->overall_padding_dimension_left ) && $settings->overall_padding_dimension_left != '' && isset( $settings->overall_padding_dimension_right ) && $settings->overall_padding_dimension_right != '' ){
			if(isset($settings->overall_padding_dimension_top) ){
		        echo ( $settings->overall_padding_dimension_top != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top.'px;' : 'padding-top: 0;'; 
		    }
		    if(isset($settings->overall_padding_dimension_bottom) ){
		        echo ( $settings->overall_padding_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom.'px;' : 'padding-bottom: 0;';
		    }
		    if(isset($settings->overall_padding_dimension_left) ){
		        echo ( $settings->overall_padding_dimension_left != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left.'px;' : 'padding-left: 0;';
		    }
		    if(isset($settings->overall_padding_dimension_right) ){
		        echo ( $settings->overall_padding_dimension_right != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right.'px;' : 'padding-right: 0;';
		    }
		} else if( isset( $settings->overall_padding ) && $settings->overall_padding != '' && isset( $settings->overall_padding_dimension_top ) && $settings->overall_padding_dimension_top == '' && isset( $settings->overall_padding_dimension_bottom ) && $settings->overall_padding_dimension_bottom == '' && isset( $settings->overall_padding_dimension_left ) && $settings->overall_padding_dimension_left == '' && isset( $settings->overall_padding_dimension_right ) && $settings->overall_padding_dimension_right == '' ){
			echo $settings->overall_padding; ?>;
		<?php } ?>
	}
<?php
} else {
	if( $settings->blog_image_position == 'top' ) {
		if( substr( $settings->layout_sort_order, 0, 3 ) == 'img' || substr( $settings->layout_sort_order, -3 ) == 'img' ) {
?>
			.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
				<?php
				if( $converted === 'yes' ||	isset( $settings->overall_padding_dimension_top ) && $settings->overall_padding_dimension_top != '' && isset( $settings->overall_padding_dimension_bottom ) && $settings->overall_padding_dimension_bottom != '' && isset( $settings->overall_padding_dimension_left ) && $settings->overall_padding_dimension_left != '' && isset( $settings->overall_padding_dimension_right ) && $settings->overall_padding_dimension_right != '' ){
					if(isset($settings->overall_padding_dimension_top) ){
				        echo ( $settings->overall_padding_dimension_top != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top.'px;' : 'padding-top: 0;'; 
				    }
				    if(isset($settings->overall_padding_dimension_bottom) ){
				        echo ( $settings->overall_padding_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom.'px;' : 'padding-bottom: 0;';
				    }
				    if(isset($settings->overall_padding_dimension_left) ){
				        echo ( $settings->overall_padding_dimension_left != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left.'px;' : 'padding-left: 0;';
				    }
				    if(isset($settings->overall_padding_dimension_right) ){
				        echo ( $settings->overall_padding_dimension_right != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right.'px;' : 'padding-right: 0;';
				    } 
				} else if( isset( $settings->overall_padding ) && $settings->overall_padding != '' && isset( $settings->overall_padding_dimension_top ) && $settings->overall_padding_dimension_top == '' && isset( $settings->overall_padding_dimension_bottom ) && $settings->overall_padding_dimension_bottom == '' && isset( $settings->overall_padding_dimension_left ) && $settings->overall_padding_dimension_left == '' && isset( $settings->overall_padding_dimension_right ) && $settings->overall_padding_dimension_right == '' ) {echo $settings->overall_padding; ?>;
				<?php } ?>
			}
	<?php
		}
	}
}

if( $settings->is_carousel == 'feed' ) {
	if( $settings->featured_image_size != 'custom' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-thumbnail img {
    width: 100%;
}
<?php
	} else {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-thumbnail img {
    <?php echo ( $settings->overall_alignment == 'left' ) ? 'margin: 0;margin-right: auto;' : ( ( $settings->overall_alignment == 'right' ) ? 'margin: 0;margin-left: auto;' : '' ); ?>
}
<?php
	}
}

if( $settings->is_carousel == 'grid' || $settings->is_carousel == 'masonary' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-blog-posts-grid,
.fl-node-<?php echo $id; ?> .uabb-blog-posts-masonary {
	<?php $grid_margin = ( $settings->element_space != '' ) ? ( $settings->element_space / 2 ) : 7.5; ?>
	margin: 0 -<?php echo $grid_margin; ?>px;
}
<?php
}

if( $settings->is_carousel == 'masonary' ) {

 if ( isset( $settings->selfilter_border_enable ) && $settings->selfilter_border_enable == 'yes' ) {
		$border_style = $settings->selfilter_border_style;
	} else {
		$border_style = 'none';
	} ?>
.fl-node-<?php echo $id; ?> select.uabb-masonary-filters {

	<?php if( $settings->taxonomy_filter_select_font_family['family'] != 'Default' ) {
		UABB_Helper::uabb_font_css( $settings->taxonomy_filter_select_font_family );
	} ?>

	<?php if( isset( $settings->taxonomy_filter_select_font_size_unit ) && $settings->taxonomy_filter_select_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->taxonomy_filter_select_font_size_unit; ?>px;
	<?php endif; ?>

	width: <?php echo ( $settings->selfilter_width != '' ) ? $settings->selfilter_width : '200'; ?>px;
	<?php echo ( uabb_theme_text_color( $settings->taxonomy_filter_select_color ) != '' ) ? 'color: ' . uabb_theme_text_color( $settings->taxonomy_filter_select_color ) . ';' : '';

	echo ( $settings->selfilter_background_color != '' ) ? 'background: ' . $settings->selfilter_background_color . ';' : 'background: #EFEFEF;'; ?>
	border-radius: <?php echo ( $settings->selfilter_border_radius != '' ) ? $settings->selfilter_border_radius : '0'; ?>px;
	margin-bottom: <?php echo ( $settings->selfilter_bottom_spacing != '' ) ? $settings->selfilter_bottom_spacing : '40'; ?>px;
	border: <?php echo $settings->selfilter_border_size . 'px ' . $border_style . ' ' . uabb_theme_base_color( $settings->selfilter_color_border ) ?>;
}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters {

	<?php if( $settings->taxonomy_filter_select_font_family['family'] != 'Default' ) {
		UABB_Helper::uabb_font_css( $settings->taxonomy_filter_select_font_family );
	} ?>

	<?php if( isset( $settings->taxonomy_filter_select_font_size_unit ) && $settings->taxonomy_filter_select_font_size_unit != '' ) : ?>
		font-size: <?php echo $settings->taxonomy_filter_select_font_size_unit; ?>px;
	<?php endif; ?>

	<?php if( $settings->taxonomy_transform != 'none' ) : ?>
	   text-transform: <?php echo $settings->taxonomy_transform; ?>;
	<?php endif; ?>

    <?php if( $settings->taxonomy_letter_spacing != '' ) : ?>
	   letter-spacing: <?php echo $settings->taxonomy_letter_spacing; ?>px;
	<?php endif; ?>

}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters li {
	<?php echo ( uabb_theme_text_color( $settings->taxonomy_filter_select_color ) != '' ) ? 'color: ' . uabb_theme_text_color( $settings->taxonomy_filter_select_color ) . ';' : ''; ?>
}

.fl-node-<?php echo $id; ?> .uabb-masonary-filters-wrapper {
	text-align: <?php echo $settings->selfilter_overall_alignment; ?>;
}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters > li {
    <?php
    if( $settings->masonary_button_style == 'square' ) {
    	echo ( $settings->masonary_background_color != '' ) ? 'background: ' . $settings->masonary_background_color . ';' : 'background: #EFEFEF;';
    } else {
    	echo ( uabb_theme_base_color( $settings->masonary_color_border ) != '' ) ? 'border: ' . $settings->masonary_border_size . 'px ' . $settings->masonary_border_style . ' ' . uabb_theme_base_color( $settings->masonary_color_border ) . ';' : '';
    }
    

    echo ( uabb_theme_text_color( $settings->masonary_text_color ) != '' ) ? 'color: ' . uabb_theme_text_color( $settings->masonary_text_color ) . ';' : '';
    echo ( $settings->masonary_overall_alignment == 'left' ) ? 'margin-right: 10px;' : ( ( $settings->masonary_overall_alignment == 'right' ) ? 'margin-left: 10px;' : 'margin-right: 5px; margin-left: 5px;' ); ?>

    <?php 
    if( $converted === 'yes' || isset( $settings->masonary_padding_dimension_top ) && isset( $settings->masonary_padding_dimension_bottom ) && isset( $settings->masonary_padding_dimension_left ) && isset( $settings->masonary_padding_dimension_right ) ){
        if(isset($settings->masonary_padding_dimension_top) ){
            echo ( $settings->masonary_padding_dimension_top != '' ) ? 'padding-top:'.$settings->masonary_padding_dimension_top.'px;' : 'padding-top: 12px;'; 
        }
        if(isset($settings->masonary_padding_dimension_bottom) ){
            echo ( $settings->masonary_padding_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->masonary_padding_dimension_bottom.'px;' : 'padding-bottom: 12px;';
        }
        if(isset($settings->masonary_padding_dimension_left) ){
            echo ( $settings->masonary_padding_dimension_left != '' ) ? 'padding-left:'.$settings->masonary_padding_dimension_left.'px;' : 'padding-left: 12px;';
        }
        if(isset($settings->masonary_padding_dimension_right) ){
            echo ( $settings->masonary_padding_dimension_right != '' ) ? 'padding-right:'.$settings->masonary_padding_dimension_right.'px;' : 'padding-right: 12px;';
        }
    } else if( isset( $settings->masonary_padding ) && $settings->masonary_padding != '' && isset( $settings->masonary_padding_dimension_top ) && $settings->masonary_padding_dimension_top == '' && isset( $settings->masonary_padding_dimension_bottom ) && $settings->masonary_padding_dimension_bottom == '' && isset( $settings->masonary_padding_dimension_left ) && $settings->masonary_padding_dimension_left == '' && isset( $settings->masonary_padding_dimension_right ) && $settings->masonary_padding_dimension_right == '' ) {
		echo $settings->masonary_padding; ?>;
     <?php } ?>

    border-radius: <?php echo ( $settings->masonary_border_radius != '' ) ? $settings->masonary_border_radius : '2'; ?>px;
}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters > li:hover {
    <?php
    if( $settings->masonary_button_style == 'square' ) {
    	echo ( $settings->masonary_background_hover_color != '' ) ? 'background: ' . $settings->masonary_background_hover_color . ';' : '';

	    echo ( $settings->masonary_text_hover_color != '' ) ? 'color: ' . $settings->masonary_text_hover_color . ';' : '';    
    
    }
    ?>
}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters > li.uabb-masonary-current {
    <?php
    echo ( uabb_theme_text_color( $settings->masonary_active_color ) != '' ) ? 'color: ' . uabb_theme_text_color( $settings->masonary_active_color ) . ';' : '';
    if( $settings->masonary_button_style == 'square' ) {
    	echo ( uabb_theme_base_color( $settings->masonary_background_active_color ) != '' ) ? 'background: ' . uabb_theme_base_color( $settings->masonary_background_active_color ) . ';' : '';
	    
    } else {
    	echo ( uabb_theme_base_color( $settings->masonary_active_color_border ) != '' ) ? 'border: ' . $settings->masonary_border_size . 'px ' . $settings->masonary_border_style . ' ' . uabb_theme_base_color( $settings->masonary_active_color_border ) . '; !important' : '';
    } 
    ?>
}

.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters {
	text-align: <?php echo $settings->masonary_overall_alignment; ?>;
	margin-bottom: <?php echo ( $settings->masonary_bottom_spacing != '' ) ? $settings->masonary_bottom_spacing : '40'; ?>px;
}
<?php
}
?>

.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
	<?php
	if( $settings->is_carousel == 'feed' ) {
		echo ( $settings->element_space != '' ) ? 'margin-bottom: ' . ( $settings->element_space ) . 'px;' : 'margin-bottom: 15px;';
	} else {
		if( $settings->post_per_grid_desktop == 1 ) {
			echo 'padding: 0;';
		} else {
			echo ( $settings->element_space != '' ) ? 'padding-left: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-left: 7.5px;';
			echo ( $settings->element_space != '' ) ? 'padding-right: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-right: 7.5px;';
		}
	}

	if( $settings->is_carousel == 'grid' || $settings->is_carousel == 'masonary' ) {
	?>
	margin-bottom: <?php echo ( $settings->below_element_space != '' ) ? $settings->below_element_space : '30'; ?>px;
	<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .uabb-post-wrapper .uabb-blog-post-content {
	<?php 
	if( $converted === 'yes' || isset( $settings->content_padding_dimension_top ) && isset( $settings->content_padding_dimension_bottom ) && isset( $settings->content_padding_dimension_left ) && isset( $settings->content_padding_dimension_right ) ){
		if(isset($settings->content_padding_dimension_top) ){
            echo ( $settings->content_padding_dimension_top != '' ) ? 'padding-top:'.$settings->content_padding_dimension_top.'px;' : 'padding-top: 25px;'; 
        }
        if(isset($settings->content_padding_dimension_bottom) ){
            echo ( $settings->content_padding_dimension_bottom != '' ) ? 'padding-bottom:'.$settings->content_padding_dimension_bottom.'px;' : 'padding-bottom: 25px;';
        }
        if(isset($settings->content_padding_dimension_left) ){
            echo ( $settings->content_padding_dimension_left != '' ) ? 'padding-left:'.$settings->content_padding_dimension_left.'px;' : 'padding-left: 25px;';
        }
        if(isset($settings->content_padding_dimension_right) ){
            echo ( $settings->content_padding_dimension_right != '' ) ? 'padding-right:'.$settings->content_padding_dimension_right.'px;' : 'padding-right: 25px;';
        }
	} else if( isset( $settings->content_padding ) && $settings->content_padding != '' && isset( $settings->content_padding_dimension_top ) && $settings->content_padding_dimension_top == '' && isset( $settings->content_padding_dimension_bottom ) && $settings->content_padding_dimension_bottom == '' && isset( $settings->content_padding_dimension_left ) && $settings->content_padding_dimension_left == '' && isset( $settings->content_padding_dimension_right ) && $settings->content_padding_dimension_right == '' ){
		echo $settings->content_padding; ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .uabb-posted-on {
	<?php
	$color = uabb_theme_base_color( $settings->date_background_color );
	$date_background_color = ( $color != '' ) ? $color : '#EFEFEF';
	echo 'color: ' . $settings->date_color . ';' ;?>

    <?php if( $converted === 'yes' || isset( $settings->date_font_size_unit ) && $settings->date_font_size_unit != '' ) { ?>
     	font-size: <?php echo $settings->date_font_size_unit; ?>px;		
    <?php } else if( isset( $settings->date_font_size_unit ) && $settings->date_font_size_unit == '' && isset( $settings->date_font_size['desktop'] ) && $settings->date_font_size['desktop'] != '' ) { ?>
	    font-size: <?php echo $settings->date_font_size['desktop']; ?>px;
	<?php } ?>

	<?php if( $settings->date_font_family['family'] != 'Default' ) {
		UABB_Helper::uabb_font_css( $settings->date_font_family );
	} ?>
	background: <?php echo $date_background_color; ?>;
	left: 0;
	<?php if( $settings->date_transform != 'none' ) : ?>
	   text-transform: <?php echo $settings->date_transform; ?>;
	<?php endif; ?>

    <?php if( $settings->date_letter_spacing != '' ) : ?>
	   letter-spacing: <?php echo $settings->date_letter_spacing; ?>px;
	<?php endif; ?>
	
}

<?php
if( $settings->meta_color != '' || $settings->meta_hover_color != '' ) {
?>
	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta a,
	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta a:hover,
	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta a:focus,
	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta a:active {
		color: <?php echo $settings->meta_color; ?>;
	}

	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta a:hover {
		color: <?php echo $settings->meta_hover_color; ?>;
	}
<?php
}

if( $settings->show_meta == 'yes' ) {
?>
	.fl-node-<?php echo $id; ?> .uabb-post-meta a,
	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta{
        <?php if( $converted === 'yes' || isset( $settings->meta_font_size_unit ) && $settings->meta_font_size_unit != '' ){ ?>
        	font-size: <?php echo $settings->meta_font_size_unit; ?>px; 
        <?php } else if(isset( $settings->meta_font_size_unit ) && $settings->meta_font_size_unit == '' && isset( $settings->meta_font_size['desktop'] ) && $settings->meta_font_size['desktop'] != '') {?>
        	font-size: <?php echo $settings->meta_font_size['desktop']; ?>px;
        <?php } ?>

	    <?php if( isset( $settings->meta_font_size['desktop'] ) && $settings->meta_font_size['desktop'] == '' && isset( $settings->meta_line_height['desktop'] ) && $settings->meta_line_height['desktop'] != '' && $settings->meta_line_height_unit == '' ) { ?>
			line-height: <?php echo $settings->meta_line_height['desktop']; ?>px;
		<?php } ?>
		
		<?php if( $converted === 'yes' || isset( $settings->meta_line_height_unit ) && $settings->meta_line_height_unit != '' ){?> 
			line-height: <?php echo $settings->meta_line_height_unit; ?>em;
		<?php } else if( isset( $settings->meta_line_height_unit ) && $settings->meta_line_height_unit == '' && isset( $settings->meta_line_height['desktop'] ) && $settings->meta_line_height['desktop'] != '' ){ ?> 
		    line-height: <?php echo $settings->meta_line_height['desktop']; ?>px;
		<?php } ?> 
		
		<?php echo ( $settings->meta_text_color != '' ) ? 'color: ' . $settings->meta_text_color . ';' : '';
		
		if( $settings->meta_font_family['family'] != 'Default' ) {
			UABB_Helper::uabb_font_css( $settings->meta_font_family );
		}
		?>

		<?php if( $settings->meta_transform != 'none' ) : ?>
		   text-transform: <?php echo $settings->meta_transform; ?>;
		<?php endif; ?>
 
        <?php if( $settings->meta_letter_spacing != '' ) : ?>
		   letter-spacing: <?php echo $settings->meta_letter_spacing; ?>px;
		<?php endif; ?>
		   
		?>
	}
<?php
}
?>

.fl-node-<?php echo $id; ?> .uabb-blog-posts-shadow {
	<?php if( $settings->show_box_shadow == 'yes' ) { ?>
	box-shadow: 0 4px 1px rgba(197, 197, 197, 0.2);
	<?php } ?>
	<?php echo ( $settings->content_background_color != '' ) ? 'background: ' . $settings->content_background_color : ''; ?>;
	transition: all 0.3s linear;
	width: 100%;
}

<?php
if( $settings->is_carousel == 'grid' ) {
?>
@media all and ( min-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
    .fl-node-<?php echo $id; ?> .uabb-post-wrapper:nth-child(<?php echo $settings->post_per_grid; ?>n+1){
        <!-- clear: left; -->
    }
    .fl-node-<?php echo $id; ?> .uabb-post-wrapper:nth-child(<?php echo $settings->post_per_grid; ?>n+0) {
        clear: right;
    }
    .fl-node-<?php echo $id; ?> .uabb-post-wrapper:nth-child(<?php echo $settings->post_per_grid; ?>n+1) .uabb-posted-on {
        left: 0;
    }
}

<?php
}
?>

.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text span,
.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text:visited * {
	color: <?php echo ( uabb_theme_base_color( $settings->link_more_arrow_color ) == '' ) ? '#f7f7f7' : uabb_theme_base_color( $settings->link_more_arrow_color ); ?>;
}

<?php
if( $settings->is_carousel == 'carousel' ) {
?>
.fl-node-<?php echo $id; ?> .slick-prev i,
.fl-node-<?php echo $id; ?> .slick-next i,
.fl-node-<?php echo $id; ?> .slick-prev i:hover,
.fl-node-<?php echo $id; ?> .slick-next i:hover,
.fl-node-<?php echo $id; ?> .slick-prev i:focus,
.fl-node-<?php echo $id; ?> .slick-next i:focus {
	outline: none;
	<?php
	$color = uabb_theme_base_color( $settings->arrow_color );
	$arrow_color = ( $color != '' ) ? $color : '#fff';
	?>
	color: <?php echo $arrow_color; ?>;
	<?php
	switch ( $settings->arrow_style ) {
		case 'square':
	?>
	background: <?php echo ( $settings->arrow_background_color != '' ) ? $settings->arrow_background_color : '#efefef'; ?>;
	<?php
			break;
		
		case 'circle':
	?>
	border-radius: 50%;
	background: <?php echo ( $settings->arrow_background_color != '' ) ? $settings->arrow_background_color : '#efefef'; ?>;
	<?php
			break;

		case 'square-border':
	?>
	border: <?php echo $settings->arrow_border_size; ?>px solid <?php echo $settings->arrow_color_border ?>;
	<?php
			break;

		case 'circle-border':
	?>
	border: <?php echo $settings->arrow_border_size; ?>px solid <?php echo $settings->arrow_color_border ?>;
	border-radius: 50%;
	<?php
			break;
	}
	?>
}

	<?php
	if( $settings->arrow_position != 'outside' ) {
	?>
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev,
	.fl-node-<?php echo $id; ?> [dir='rtl'] .uabb-blog-posts .slick-next
	{
	    left: -15px;
	}
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next,
	.fl-node-<?php echo $id; ?> [dir='rtl'] .uabb-blog-posts .slick-prev
	{
	    right: -15px;
	}
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i,
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i,
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i:hover,
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i:focus,
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i:focus,
	.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i:hover {
	    width: 30px;
	    height: 30px;
	    line-height: 30px;
	}
<?php
	}
	?>

.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
	<?php
	if( $settings->post_per_grid_desktop == 1 ) {
	?>
	margin: 0;
	<?php
	} else {
	?>
	margin: 0 -<?php echo ( $settings->element_space != '' ) ? ( $settings->element_space / 2 ) : '7.5'; ?>px;
	<?php
	}
	?>
}

<?php
}
?>

.fl-node-<?php echo $id; ?> .uabb-blog-post-content {
	text-align: <?php echo $settings->overall_alignment; ?>;
}

.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text,
.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a,
.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:visited,
.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:hover {
	<?php
		echo 'color: ' . uabb_theme_text_color( $settings->link_color ) . ';' ; 
    ?>
    
    <?php if( $converted === 'yes' || isset( $settings->link_font_size_unit ) && $settings->link_font_size_unit != '' ){ ?> 
    	font-size: <?php echo $settings->link_font_size_unit; ?>px;
    <?php } else if(isset( $settings->link_font_size_unit ) && $settings->link_font_size_unit == '' && isset( $settings->link_font_size['desktop'] ) && $settings->link_font_size['desktop'] != '') { ?>
    	font-size: <?php echo $settings->link_font_size['desktop']; ?>px;
    <?php } ?>

    <?php if( isset( $settings->link_font_size['desktop'] ) && $settings->link_font_size['desktop'] == '' && isset( $settings->link_line_height['desktop'] ) && $settings->link_line_height['desktop'] != '' && $settings->link_line_height_unit == '' ) { ?>
	    line-height: <?php echo $settings->link_line_height['desktop']; ?>px;
	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->link_line_height_unit ) && $settings->link_line_height_unit != '' ){ ?> 
    	line-height: <?php echo $settings->link_line_height_unit; ?>em;
    <?php } else if(isset( $settings->link_line_height_unit ) && $settings->link_line_height_unit == '' && isset( $settings->link_line_height['desktop'] ) && $settings->link_line_height['desktop'] != '') { ?>
    	line-height: <?php echo $settings->link_line_height['desktop']; ?>px;
    <?php } ?>

	<?php if( $settings->link_font_family['family'] != 'Default' ) {
		UABB_Helper::uabb_font_css( $settings->link_font_family );
	}
	?>

	<?php if( $settings->link_transform != 'none' ) : ?>
	   text-transform: <?php echo $settings->link_transform; ?>;
	<?php endif; ?>

    <?php if( $settings->link_letter_spacing != '' ) : ?>
	   letter-spacing: <?php echo $settings->link_letter_spacing; ?>px;
	<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .uabb-text-editor {
	<?php
		echo 'color: ' . uabb_theme_text_color( $settings->desc_color ) . ';' ;
    ?>
    
    <?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit ) && $settings->desc_font_size_unit != '' ){ ?> 
    	font-size: <?php echo $settings->desc_font_size_unit; ?>px;
    <?php } else if(isset( $settings->desc_font_size_unit ) && $settings->desc_font_size_unit == '' && isset( $settings->desc_font_size['desktop'] ) && $settings->desc_font_size['desktop'] != '') { ?>
    	font-size: <?php echo $settings->desc_font_size['desktop']; ?>px;
    <?php } ?>

    <?php if( isset( $settings->desc_font_size['desktop'] ) && $settings->desc_font_size['desktop'] == '' && isset( $settings->desc_line_height['desktop'] ) && $settings->desc_line_height['desktop'] != '' && $settings->desc_line_height_unit == '' ) { ?>
	    line-height: <?php echo $settings->desc_line_height['desktop']; ?>px;
	<?php } ?>
    
    <?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit ) && $settings->desc_line_height_unit != '' ){ ?> 
    	line-height: <?php echo $settings->desc_line_height_unit; ?>em;
    <?php } else if(isset( $settings->desc_line_height_unit ) && $settings->desc_line_height_unit == '' && isset( $settings->desc_line_height['desktop'] ) && $settings->desc_line_height['desktop'] != '') { ?>
    	line-height: <?php echo $settings->desc_line_height['desktop']; ?>px;
    <?php } ?>

	<?php if( $settings->desc_font_family['family'] != 'Default' ) {
		UABB_Helper::uabb_font_css( $settings->desc_font_family );
	}
    ?>

    <?php if( $settings->desc_transform != 'none' ) : ?>
	   text-transform: <?php echo $settings->desc_transform; ?>;
	<?php endif; ?>

    <?php if( $settings->desc_letter_spacing != '' ) : ?>
	   letter-spacing: <?php echo $settings->desc_letter_spacing; ?>px;
	<?php endif; ?>
	?>
}

<?php 
	if( isset( $settings->post_layout ) && $settings->post_layout != 'custom' ) {
	?>
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:hover,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:focus,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:visited {
			<?php
			echo ( $settings->title_color != '' ) ? 'color: ' . $settings->title_color . ';' : '';
			?>
            
            <?php if( $converted === 'yes' || isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit != '' ){ ?> 
		    	font-size: <?php echo $settings->title_font_size_unit; ?>px;
		    <?php } else if(isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit == '' && isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] != '') { ?>
		  		font-size: <?php echo $settings->title_font_size['desktop']; ?>px;
		    <?php } ?>

		    <?php if( isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '' && $settings->title_line_height_unit == '' ) { ?>
		    	line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
			<?php } ?>
            
            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit != '' ){ ?> 
		    	line-height: <?php echo $settings->title_line_height_unit; ?>em;
		    <?php } else if(isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '') { ?>
		  		line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
		    <?php } ?>

            <?php if( $settings->transform != 'none' ) : ?>
			   text-transform: <?php echo $settings->transform; ?>;
			<?php endif; ?>

	        <?php if( $settings->letter_spacing != '' ) : ?>
			   letter-spacing: <?php echo $settings->letter_spacing; ?>px;
			<?php endif; ?>

			<?php  
			if( $settings->title_font_family['family'] != 'Default' ) {
				UABB_Helper::uabb_font_css( $settings->title_font_family );
			}
			echo ( $settings->title_margin_top != '' ) ? 'margin-top: ' . $settings->title_margin_top . 'px;' : '';
			echo ( $settings->title_margin_bottom != '' ) ? 'margin-bottom: ' . $settings->title_margin_bottom . 'px;' : '';
			?>
		}
	<?php } 
	else { ?>

		.fl-node-<?php echo $id; ?> .uabb-post-heading,
		.fl-node-<?php echo $id; ?> .uabb-post-heading a,
		.fl-node-<?php echo $id; ?> .uabb-post-heading a:hover,
		.fl-node-<?php echo $id; ?> .uabb-post-heading a:focus,
		.fl-node-<?php echo $id; ?> .uabb-post-heading a:visited {
			<?php
			 echo ( $settings->title_color != '' ) ? 'color: ' . $settings->title_color . ';' : '';
            ?>

            <?php if( $converted === 'yes' || isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit != '' ){ ?> 
		    	font-size: <?php echo $settings->title_font_size_unit; ?>px;
		    <?php } else if(isset( $settings->title_font_size_unit ) && $settings->title_font_size_unit == '' && isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] != '') { ?>
		  		font-size: <?php echo $settings->title_font_size['desktop']; ?>px;
		    <?php } ?>

            <?php if( isset( $settings->title_font_size['desktop'] ) && $settings->title_font_size['desktop'] == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '' && $settings->title_line_height_unit == '' ) { ?>
	        	line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
	        <?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit != '' ){ ?> 
		    	line-height: <?php echo $settings->title_line_height_unit; ?>em;
		    <?php } else if(isset( $settings->title_line_height_unit ) && $settings->title_line_height_unit == '' && isset( $settings->title_line_height['desktop'] ) && $settings->title_line_height['desktop'] != '') { ?>
		  		line-height: <?php echo $settings->title_line_height['desktop']; ?>px;
		    <?php } ?>

	        <?php if( $settings->transform != 'none' ) : ?>
		       text-transform: <?php echo $settings->transform; ?>;
		    <?php endif; ?>

		    <?php if( $settings->letter_spacing != '' ) : ?>
		       letter-spacing: <?php echo $settings->letter_spacing; ?>px;
		    <?php endif; ?>
			       
			<?php if( $settings->title_font_family['family'] != 'Default' ) {
					UABB_Helper::uabb_font_css( $settings->title_font_family );
				}


				echo ( $settings->title_margin_top != '' ) ? 'margin-top: ' . $settings->title_margin_top . 'px;' : '';
				echo ( $settings->title_margin_bottom != '' ) ? 'margin-bottom: ' . $settings->title_margin_bottom . 'px;' : '';
			?>
		}
<?php } ?>

<?php
$show_pagination = ( isset( $settings->show_pagination ) ) ? $settings->show_pagination : 'no';
$pagination = ( isset( $settings->pagination ) ) ? $settings->pagination : 'numbers';
$show_loader = ( isset( $settings->show_paginate_loader ) ) ? $settings->show_paginate_loader : 'yes';

if( $show_pagination == 'yes' && $pagination == 'scroll' && $show_loader == 'no' ) { ?>
	.fl-node-<?php echo $id; ?> .uabb-blog-posts #infscr-loading {
	    display: none !important;
	}
<?php } ?>

<?php
if( $settings->is_carousel != 'carousel' && $show_pagination == 'yes' && $pagination == 'numbers' ) {
?>

	.fl-node-<?php echo $id; ?> .uabb-blogs-pagination ul  {
		text-align: <?php echo $settings->pagination_alignment; ?>;
	}

	.fl-node-<?php echo $id; ?> .uabb-blogs-pagination li:hover a.page-numbers {
		<?php
		if( $settings->pagination_style == 'square' ) {
			echo ( $settings->pagination_hover_background_color != '' ) ? 'background: ' . $settings->pagination_hover_background_color . ';' : '';
			echo ( $settings->pagination_hover_color != '' ) ? 'color: ' . $settings->pagination_hover_color . ';' : '';
		}
		?>
	}

	.fl-node-<?php echo $id; ?> .uabb-blogs-pagination li a.page-numbers,
	.fl-node-<?php echo $id; ?> .uabb-blogs-pagination li span.page-numbers {
		outline: none;
		color: <?php echo uabb_theme_text_color( $settings->pagination_color ); ?>;
		<?php
		switch ( $settings->pagination_style ) {
			case 'square':
		?>
		background: <?php echo ( $settings->pagination_background_color != '' ) ? $settings->pagination_background_color : '#efefef'; ?>;
		<?php
				break;

			case 'square-border':
		?>
		border: <?php echo $settings->pagination_border_size; ?>px <?php echo $settings->pagination_border_style; ?> <?php echo $settings->pagination_color_border ?>;
		<?php
				break;
		}
		?>
	}
	<?php
	//if( $settings->pagination_active_background_color != '' || $settings->pagination_active_color != '' ) {
	?>
	.fl-node-<?php echo $id; ?> .uabb-blogs-pagination li span.page-numbers.current {
		color: <?php echo uabb_theme_text_color( $settings->pagination_active_color ); ?>;
		<?php
		switch ( $settings->pagination_style ) {
			case 'square':
		?>
		background: <?php echo uabb_theme_base_color( $settings->pagination_active_background_color ); ?>;
		<?php
				break;

			case 'square-border':
				$border_color = uabb_theme_base_color ( $settings->pagination_active_color_border );
		?>
		color: <?php echo uabb_theme_base_color( $settings->pagination_active_color ); ?>;
		border: <?php echo $settings->pagination_border_size; ?>px <?php echo $settings->pagination_border_style; ?> <?php echo $border_color ?>;
		<?php
				break;
		}
		?>
	}

	<?php
	//}
	?>

<?php
}
?>

<?php
if( $global_settings->responsive_enabled ) { // Global Setting If started
?>
    @media ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {

        .fl-node-<?php echo $id; ?> ul.uabb-masonary-filters > li {
			<?php 
		        if(isset($settings->masonary_padding_dimension_top_medium) ){
		            echo ( $settings->masonary_padding_dimension_top_medium != '' ) ? 'padding-top:'.$settings->masonary_padding_dimension_top_medium.'px;' : ''; 
		        }
		        if(isset($settings->masonary_padding_dimension_bottom_medium) ){
		            echo ( $settings->masonary_padding_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->masonary_padding_dimension_bottom_medium.'px;' : '';
		        }
		        if(isset($settings->masonary_padding_dimension_left_medium) ){
		            echo ( $settings->masonary_padding_dimension_left_medium != '' ) ? 'padding-left:'.$settings->masonary_padding_dimension_left_medium.'px;' : '';
		        }
		        if(isset($settings->masonary_padding_dimension_right_medium) ){
		            echo ( $settings->masonary_padding_dimension_right_medium != '' ) ? 'padding-right:'.$settings->masonary_padding_dimension_right_medium.'px;' : '';
		        } 
			?>
		}    
        
        .fl-node-<?php echo $id; ?> .uabb-post-wrapper .uabb-blog-post-content {
		    <?php 
		        if(isset($settings->content_padding_dimension_top_medium) ){
		            echo ( $settings->content_padding_dimension_top_medium != '' ) ? 'padding-top:'.$settings->content_padding_dimension_top_medium.'px;' : ''; 
		        }
		        if(isset($settings->content_padding_dimension_bottom_medium) ){
		            echo ( $settings->content_padding_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->content_padding_dimension_bottom_medium.'px;' : '';
		        }
		        if(isset($settings->content_padding_dimension_left_medium) ){
		            echo ( $settings->content_padding_dimension_left_medium != '' ) ? 'padding-left:'.$settings->content_padding_dimension_left_medium.'px;' : '';
		        }
		        if(isset($settings->content_padding_dimension_right_medium) ){
		            echo ( $settings->content_padding_dimension_right_medium != '' ) ? 'padding-right:'.$settings->content_padding_dimension_right_medium.'px;' : '';
		        } 
		    ?>
		}

    	<?php if( $settings->blog_image_position != 'top' && $settings->blog_image_position != 'background' ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
			<?php 
			    if(isset($settings->overall_padding_dimension_top_medium) ){
			        echo ( $settings->overall_padding_dimension_top_medium != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top_medium.'px;' : ''; 
			    }
			    if(isset($settings->overall_padding_dimension_bottom_medium) ){
			        echo ( $settings->overall_padding_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom_medium.'px;' : '';
			    }
			    if(isset($settings->overall_padding_dimension_left_medium) ){
			        echo ( $settings->overall_padding_dimension_left_medium != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left_medium.'px;' : '';
			    }
			    if(isset($settings->overall_padding_dimension_right_medium) ){
			        echo ( $settings->overall_padding_dimension_right_medium != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right_medium.'px;' : '';
			    } 
			?>
		}
		<?php
		} else {
			if( $settings->blog_image_position == 'top' ) {
				if( substr( $settings->layout_sort_order, 0, 3 ) == 'img' || substr( $settings->layout_sort_order, -3 ) == 'img' ) {
		?>
					.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
						<?php 
						    if(isset($settings->overall_padding_dimension_top_medium) ){
						        echo ( $settings->overall_padding_dimension_top_medium != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top_medium.'px;' : ''; 
						    }
						    if(isset($settings->overall_padding_dimension_bottom_medium) ){
						        echo ( $settings->overall_padding_dimension_bottom_medium != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom_medium.'px;' : '';
						    }
						    if(isset($settings->overall_padding_dimension_left_medium) ){
						        echo ( $settings->overall_padding_dimension_left_medium != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left_medium.'px;' : '';
						    }
						    if(isset($settings->overall_padding_dimension_right_medium) ){
						        echo ( $settings->overall_padding_dimension_right_medium != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right_medium.'px;' : '';
						    } 
						?>
					}
			<?php
				}
			}
		} 
		?>

    	<?php
     	if( $settings->is_carousel == 'masonary' || $settings->is_carousel == 'grid' ) {
     	?>
	    	.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-8,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-7,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-6,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-5,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-4,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-3,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-2,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-1 { 
				width: <?php echo ( 100 / $settings->post_per_grid_medium ); ?>%;
			}

    	<?php
    	}
    	if( isset( $settings->link_line_height['medium'] ) || isset( $settings->link_font_size['medium'] ) || isset( $settings->link_line_height_unit_medium ) || isset( $settings->link_font_size_unit_medium ) || isset( $settings->link_line_height_unit ) ) {
    	?>

	    	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text,
			.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a,
			.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:visited,
			.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:hover {
            	<?php if( $converted === 'yes' || isset( $settings->link_font_size_unit_medium ) && $settings->link_font_size_unit_medium != '' ){ ?> 
            		font-size: <?php echo $settings->link_font_size_unit_medium; ?>px;
            	<?php } else if( isset( $settings->link_font_size_unit_medium ) && $settings->link_font_size_unit_medium == '' && isset( $settings->link_font_size['medium'] ) && $settings->link_font_size['medium'] != '' ){?>
            		font-size: <?php echo $settings->link_font_size['medium']; ?>px;
            	 <?php } ?>    

                <?php if( isset( $settings->link_font_size['medium'] ) && $settings->link_font_size['medium'] == '' && isset( $settings->link_line_height['medium'] ) && $settings->link_line_height['medium'] != '' && $settings->link_line_height_unit_medium == '' && $settings->link_line_height_unit == '' ) { ?>
			    	line-height: <?php echo $settings->link_line_height['medium']; ?>px;
				<?php } ?>

            	<?php if( $converted === 'yes' || isset( $settings->link_line_height_unit_medium ) && $settings->link_line_height_unit_medium != '' ){ ?> 
            		line-height: <?php echo $settings->link_line_height_unit_medium; ?>em;
            	<?php } else if( isset( $settings->link_line_height_unit_medium ) && $settings->link_line_height_unit_medium == '' && isset( $settings->link_line_height['medium'] ) && $settings->link_line_height['medium'] != '' ){?>
            		line-height: <?php echo $settings->link_line_height['medium']; ?>px;
            	 <?php } ?>
			}
		<?php
		}

		if( $settings->show_meta == 'yes' ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta {

			<?php if( $converted === 'yes' || isset( $settings->meta_font_size_unit_medium ) && $settings->meta_font_size_unit_medium != '' ){ ?> 
        		font-size: <?php echo $settings->meta_font_size_unit_medium; ?>px;
        	<?php } else if( isset( $settings->meta_font_size_unit_medium ) && $settings->meta_font_size_unit_medium == '' && isset( $settings->meta_font_size['medium'] ) && $settings->meta_font_size['medium'] != '' ){?>
        		font-size: <?php echo $settings->meta_font_size['medium']; ?>px;
        	 <?php } ?> 

		    <?php if( isset( $settings->meta_font_size['medium'] ) && $settings->meta_font_size['medium'] == '' && isset( $settings->meta_line_height['medium'] ) && $settings->meta_line_height['medium'] != '' && $settings->meta_line_height_unit_medium == '' && $settings->meta_line_height_unit == '' ) { ?>
		    	line-height: <?php echo $settings->meta_line_height['medium']; ?>px;
			<?php } ?>
            
            <?php if( $converted === 'yes' || isset( $settings->meta_line_height_unit_medium ) && $settings->meta_line_height_unit_medium != '' ){ ?> 
            	line-height: <?php echo $settings->meta_line_height_unit_medium; ?>em;  
        	<?php } else if( isset( $settings->meta_line_height_unit_medium ) && $settings->meta_line_height_unit_medium == '' && isset( $settings->meta_line_height['medium'] ) && $settings->meta_line_height['medium'] != '' ){?>
        		line-height: <?php echo $settings->meta_line_height['medium']; ?>px;
        	 <?php } ?>
		}
		<?php
		}

		if( $settings->show_date_box == 'yes' ) {
		?>
			.fl-node-<?php echo $id; ?> .uabb-posted-on {

				<?php if( $converted === 'yes' || isset( $settings->date_font_size_unit_medium ) && $settings->date_font_size_unit_medium != '' ){ ?> 
	        		font-size: <?php echo $settings->date_font_size_unit_medium; ?>px;
	        	<?php } else if( isset( $settings->date_font_size_unit_medium ) && $settings->date_font_size_unit_medium == '' && isset( $settings->date_font_size['medium'] ) && $settings->date_font_size['medium'] != '' ){?>
	        		font-size: <?php echo $settings->date_font_size['medium']; ?>px;
	        	<?php } ?>

			}
		<?php
		}

		if( isset($settings->desc_line_height['medium']) || isset($settings->desc_font_size['medium']) || isset($settings->desc_line_height_unit_medium) || isset($settings->desc_font_size_unit_medium) || isset( $settings->desc_line_height_unit ) ) {
		?>

		.fl-node-<?php echo $id; ?> .uabb-text-editor {
            
            <?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit_medium ) && $settings->desc_font_size_unit_medium != '' ){ ?> 
        		font-size: <?php echo $settings->desc_font_size_unit_medium; ?>px;
	        <?php } else if( isset( $settings->desc_font_size_unit_medium ) && $settings->desc_font_size_unit_medium == '' && isset( $settings->desc_font_size['medium'] ) && $settings->desc_font_size['medium'] != '' ){?>
	        	font-size: <?php echo $settings->desc_font_size['medium']; ?>px;
	        <?php } ?>

		    <?php if( isset( $settings->desc_font_size['medium'] ) && $settings->desc_font_size['medium'] == '' && isset( $settings->desc_line_height['medium'] ) && $settings->desc_line_height['medium'] != '' && $settings->desc_line_height_unit_medium == '' && $settings->desc_line_height_unit == '' ) { ?>
		    	line-height: <?php echo $settings->desc_line_height['medium']; ?>px;
			<?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit_medium ) && $settings->desc_line_height_unit_medium != '' ){ ?> 
        		line-height: <?php echo $settings->desc_line_height_unit_medium; ?>em;
	        <?php } else if( isset( $settings->desc_line_height_unit_medium ) && $settings->desc_line_height_unit_medium == '' && isset( $settings->desc_line_height['medium'] ) && $settings->desc_line_height['medium'] != '' ){?>
	        	line-height: <?php echo $settings->desc_line_height['medium']; ?>px;
	        <?php } ?>
			
		}

		<?php
		}

		if( ( isset( $settings->title_line_height['medium'] ) || isset( $settings->title_font_size['medium'] ) || isset($settings->title_line_height_unit_medium) || isset( $settings->title_font_size_unit_medium ) ) || isset( $settings->title_font_size_unit ) && ( isset( $settings->post_layout ) && $settings->post_layout != 'custom' ) ) {
		?>
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:hover,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:focus,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:visited {
           
            <?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium != '' ){ ?> 
        		font-size: <?php echo $settings->title_font_size_unit_medium; ?>px;
	        <?php } else if( isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium == '' && isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] != '' ){?>
	        	font-size: <?php echo $settings->title_font_size['medium']; ?>px;
	        <?php } ?>    
		    
		    <?php if( isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) { ?>
			    line-height: <?php echo $settings->title_line_height['medium']; ?>px;
			<?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_medium ) && $settings->title_line_height_unit_medium != '' ){ ?> 
        		line-height: <?php echo $settings->title_line_height_unit_medium; ?>em;
	        <?php } else if( isset( $settings->title_line_height_unit_medium ) && $settings->title_line_height_unit_medium == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' ){?>
	        	line-height: <?php echo $settings->title_line_height['medium']; ?>px;
	        <?php } ?>
		}

		<?php
		}
		else {
		?>
			.fl-node-<?php echo $id; ?> .uabb-post-heading,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:hover,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:focus,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:visited {
                
	            <?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium != '' ){ ?> 
	        		font-size: <?php echo $settings->title_font_size_unit_medium; ?>px;
		        <?php } else if( isset( $settings->title_font_size_unit_medium ) && $settings->title_font_size_unit_medium == '' && isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] != '' ){?>
		        	font-size: <?php echo $settings->title_font_size['medium']; ?>px;
		        <?php } ?>  
			    
			    <?php if( isset( $settings->title_font_size['medium'] ) && $settings->title_font_size['medium'] == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) { ?>
			    	line-height: <?php echo $settings->title_line_height['medium']; ?>px;
				<?php } ?>

	            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_medium ) && $settings->title_line_height_unit_medium != '' ){ ?> 
	        		line-height: <?php echo $settings->title_line_height_unit_medium; ?>em;
		        <?php } else if( isset( $settings->title_line_height_unit_medium ) && $settings->title_line_height_unit_medium == '' && isset( $settings->title_line_height['medium'] ) && $settings->title_line_height['medium'] != '' ){?>
		        	line-height: <?php echo $settings->title_line_height['medium']; ?>px;
		        <?php } ?>		
			}
		<?php
		}

		if( $settings->is_carousel == 'carousel' ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev,
		.fl-node-<?php echo $id; ?> [dir='rtl'] .uabb-blog-posts .slick-next
		{
		    left: -15px;
		}
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next,
		.fl-node-<?php echo $id; ?> [dir='rtl'] .uabb-blog-posts .slick-prev
		{
		    right: -15px;
		}
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i:hover,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-prev i:focus,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i:focus,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .slick-next i:hover {
		    width: 25px;
		    height: 25px;
		    line-height: 25px;
		}
		<?php
		}

		if( $settings->post_per_grid_medium == 1 ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
			padding: 0;
		}
		.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
			margin: 0;
		}
		<?php
		} else {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
			<?php
			echo ( $settings->element_space != '' ) ? 'padding-left: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-left: 7.5px;';
			echo ( $settings->element_space != '' ) ? 'padding-right: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-right: 7.5px;';
			?>
		}
		.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
			margin: 0 -<?php echo ( $settings->element_space != '' ) ? ( $settings->element_space / 2 ) : '7.5'; ?>px;
		}
		<?php
		}
		?>
		.fl-node-<?php echo $id; ?> select.uabb-masonary-filters,
    	.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters {
			<?php if( isset( $settings->taxonomy_filter_select_font_size_unit_medium ) && $settings->taxonomy_filter_select_font_size_unit_medium != '' ) : ?>
				font-size: <?php echo $settings->taxonomy_filter_select_font_size_unit_medium; ?>px;
			<?php endif; ?>
		}
    }
 
    @media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {

        .fl-node-<?php echo $id; ?> ul.uabb-masonary-filters > li {
		<?php 
		        if(isset($settings->masonary_padding_dimension_top_responsive) ){
		            echo ( $settings->masonary_padding_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->masonary_padding_dimension_top_responsive.'px;' : ''; 
		        }
		        if(isset($settings->masonary_padding_dimension_bottom_responsive) ){
		            echo ( $settings->masonary_padding_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->masonary_padding_dimension_bottom_responsive.'px;' : '';
		        }
		        if(isset($settings->masonary_padding_dimension_left_responsive) ){
		            echo ( $settings->masonary_padding_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->masonary_padding_dimension_left_responsive.'px;' : '';
		        }
		        if(isset($settings->masonary_padding_dimension_right_responsive) ){
		            echo ( $settings->masonary_padding_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->masonary_padding_dimension_right_responsive.'px;' : '';
		        } 
		    ?>
		}   

        .fl-node-<?php echo $id; ?> .uabb-post-wrapper .uabb-blog-post-content {
		    <?php 
		        if(isset($settings->content_padding_dimension_top_responsive) ){
		            echo ( $settings->content_padding_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->content_padding_dimension_top_responsive.'px;' : ''; 
		        }
		        if(isset($settings->content_padding_dimension_bottom_responsive) ){
		            echo ( $settings->content_padding_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->content_padding_dimension_bottom_responsive.'px;' : '';
		        }
		        if(isset($settings->content_padding_dimension_left_responsive) ){
		            echo ( $settings->content_padding_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->content_padding_dimension_left_responsive.'px;' : '';
		        }
		        if(isset($settings->content_padding_dimension_right_responsive) ){
		            echo ( $settings->content_padding_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->content_padding_dimension_right_responsive.'px;' : '';
		        } 
		    ?>
		}
        
        <?php if( $settings->blog_image_position != 'top' && $settings->blog_image_position != 'background' ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
			<?php 
			    if(isset($settings->overall_padding_dimension_top_responsive) ){
			        echo ( $settings->overall_padding_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top_responsive.'px;' : ''; 
			    }
			    if(isset($settings->overall_padding_dimension_bottom_responsive) ){
			        echo ( $settings->overall_padding_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom_responsive.'px;' : '';
			    }
			    if(isset($settings->overall_padding_dimension_left_responsive) ){
			        echo ( $settings->overall_padding_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left_responsive.'px;' : '';
			    }
			    if(isset($settings->overall_padding_dimension_right_responsive) ){
			        echo ( $settings->overall_padding_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right_responsive.'px;' : '';
			    } 
			?>
		}
		<?php
		} else {
			if( $settings->blog_image_position == 'top' ) {
				if( substr( $settings->layout_sort_order, 0, 3 ) == 'img' || substr( $settings->layout_sort_order, -3 ) == 'img' ) {
		?>
					.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-blog-post-inner-wrap {
						<?php 
						    if(isset($settings->overall_padding_dimension_top_responsive) ){
						        echo ( $settings->overall_padding_dimension_top_responsive != '' ) ? 'padding-top:'.$settings->overall_padding_dimension_top_responsive.'px;' : ''; 
						    }
						    if(isset($settings->overall_padding_dimension_bottom_responsive) ){
						        echo ( $settings->overall_padding_dimension_bottom_responsive != '' ) ? 'padding-bottom:'.$settings->overall_padding_dimension_bottom_responsive.'px;' : '';
						    }
						    if(isset($settings->overall_padding_dimension_left_responsive) ){
						        echo ( $settings->overall_padding_dimension_left_responsive != '' ) ? 'padding-left:'.$settings->overall_padding_dimension_left_responsive.'px;' : '';
						    }
						    if(isset($settings->overall_padding_dimension_right_responsive) ){
						        echo ( $settings->overall_padding_dimension_right_responsive != '' ) ? 'padding-right:'.$settings->overall_padding_dimension_right_responsive.'px;' : '';
						    } 
						?>
					}
			<?php
				}
			}
		} 
		?>

     	<?php
     	if( $settings->blog_image_position == 'left' || $settings->blog_image_position == 'right' ) {
     		if( $settings->mobile_structure == 'stack' ) {
     	?>
     	.fl-node-<?php echo $id; ?> .uabb-thumbnail-position-right .uabb-post-thumbnail,
     	.fl-node-<?php echo $id; ?> .uabb-thumbnail-position-left .uabb-post-thumbnail,
     	.fl-node-<?php echo $id; ?> .uabb-thumbnail-position-right .uabb-blog-post-content,
     	.fl-node-<?php echo $id; ?> .uabb-thumbnail-position-left .uabb-blog-post-content {
			width: 100%;
			float: none;
		}
     	<?php
     		}
     	}

     	if( $settings->is_carousel == 'grid' || $settings->is_carousel == 'masonary' ) {
     		if( $settings->post_per_grid_small == 1 ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-blog-posts-grid,
		.fl-node-<?php echo $id; ?> .uabb-blog-posts-masonary {
			margin: 0;
		}
		.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
			padding-right: 0;
			padding-left: 0;
		}
		<?php
			} else {
			?>
			.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
				<?php
				echo ( $settings->element_space != '' ) ? 'padding-left: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-left: 7.5px;';
				echo ( $settings->element_space != '' ) ? 'padding-right: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-right: 7.5px;';
				?>
			}
			.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
				margin: 0 -<?php echo ( $settings->element_space != '' ) ? ( $settings->element_space / 2 ) : '7.5'; ?>px;
			}
			<?php
			}
		}

     	if( $settings->is_carousel == 'masonary' || $settings->is_carousel == 'grid' ) {
     	?>
     		.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-8,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-7,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-6,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-5,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-4,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-3,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-2,
			.fl-node-<?php echo $id; ?> .uabb-blog-posts-col-1 { 
				width: <?php echo ( 100 / $settings->post_per_grid_small ); ?>%;
			}
     	<?php
     	}
     	
    	if( isset( $settings->link_line_height['small'] ) || isset( $settings->link_font_size['small'] ) || isset( $settings->link_line_height_unit_responsive ) || isset( $settings->link_font_size_unit_responsive ) || isset( $settings->link_line_height_unit_medium ) || isset( $settings->link_line_height_unit ) || isset( $settings->link_font_size_unit_medium ) || isset( $settings->link_font_size_unit ) ) {
    	?>
     	.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text,
		.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a,
		.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:visited,
		.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-read-more-text a:hover {
			
			<?php if( $converted === 'yes' || isset( $settings->link_font_size_unit_responsive ) && $settings->link_font_size_unit_responsive != '' ){ ?> 
				font-size: <?php echo $settings->link_font_size_unit_responsive; ?>px;
			<?php } else if( isset( $settings->link_font_size_unit_responsive ) && $settings->link_font_size_unit_responsive == '' && isset( $settings->link_font_size['small'] ) && $settings->link_font_size['small'] != '' ) { ?>
				font-size: <?php echo $settings->link_font_size['small']; ?>px;
			<?php } ?>
		    
		    <?php if( isset( $settings->link_font_size['small'] ) && $settings->link_font_size['small'] == '' && isset( $settings->link_line_height['small'] ) && $settings->link_line_height['small'] != '' && $settings->link_line_height_unit_responsive == '' && $settings->link_line_height_unit_medium == '' && $settings->link_line_height_unit == '' ) :?>
				    line-height: <?php echo $settings->link_line_height['small']; ?>px;
			<?php endif; ?>
            
            <?php if( $converted === 'yes' || isset( $settings->link_line_height_unit_responsive ) && $settings->link_line_height_unit_responsive != '' ){ ?> 
				line-height: <?php echo $settings->link_line_height_unit_responsive; ?>em;
			<?php } else if( isset( $settings->link_line_height_unit_responsive ) && $settings->link_line_height_unit_responsive == '' && isset( $settings->link_line_height['small'] ) && $settings->link_line_height['small'] != '' ) { ?>
				line-height: <?php echo $settings->link_line_height['small']; ?>px;
			<?php } ?>
			
		}
		<?php
		}

		if( isset( $settings->desc_line_height['small'] ) || isset( $settings->desc_font_size['small'] ) || isset( $settings->desc_line_height_unit_responsive ) || isset( $settings->desc_font_size_unit_responsive ) || isset( $settings->desc_line_height_unit_medium ) || isset( $settings->desc_line_height_unit ) ) {
		?>

			.fl-node-<?php echo $id; ?> .uabb-text-editor {
			
				<?php if( $converted === 'yes' || isset( $settings->desc_font_size_unit_responsive ) && $settings->desc_font_size_unit_responsive != '' ){ ?> 
					font-size: <?php echo $settings->desc_font_size_unit_responsive; ?>px;
				<?php } else if( isset( $settings->desc_font_size_unit_responsive ) && $settings->desc_font_size_unit_responsive == '' && isset( $settings->desc_font_size['small'] ) && $settings->desc_font_size['small'] != '' ) { ?>
					font-size: <?php echo $settings->desc_font_size['small']; ?>px;
				<?php } ?>    
			    
			    <?php if( isset( $settings->desc_font_size['small'] ) && $settings->desc_font_size['small'] == '' && isset( $settings->desc_line_height['small'] ) && $settings->desc_line_height['small'] != '' && $settings->desc_line_height_unit_responsive == '' && $settings->desc_line_height_unit_medium == '' && $settings->desc_line_height_unit == '' ) :?>
				    line-height: <?php echo $settings->desc_line_height['small']; ?>px;
				<?php endif; ?>

	            <?php if( $converted === 'yes' || isset( $settings->desc_line_height_unit_responsive ) && $settings->desc_line_height_unit_responsive != '' ){ ?> 
					line-height: <?php echo $settings->desc_line_height_unit_responsive; ?>em;
				<?php } else if( isset( $settings->desc_line_height_unit_responsive ) && $settings->desc_line_height_unit_responsive == '' && isset( $settings->desc_line_height['small'] ) && $settings->desc_line_height['small'] != '' ) { ?>
					line-height: <?php echo $settings->desc_line_height['small']; ?>px;
				<?php } ?>

			}	

		<?php
		}

		if( $settings->show_meta == 'yes' ) {
		?>
			.fl-node-<?php echo $id; ?> .uabb-blog-post-content .uabb-post-meta {

				<?php if( $converted === 'yes' || isset( $settings->meta_font_size_unit_responsive ) && $settings->meta_font_size_unit_responsive != '' ){ ?> 
					font-size: <?php echo $settings->meta_font_size_unit_responsive; ?>px;
				<?php } else if( isset( $settings->meta_font_size_unit_responsive ) && $settings->meta_font_size_unit_responsive == '' && isset( $settings->meta_font_size['small'] ) && $settings->meta_font_size['small'] != '' ) { ?>
					font-size: <?php echo $settings->meta_font_size['small']; ?>px;
				<?php } ?>  
			    
			    <?php if( isset( $settings->meta_font_size['small'] ) && $settings->meta_font_size['small'] == '' && isset( $settings->meta_line_height['small'] ) && $settings->meta_line_height['small'] != '' && $settings->meta_line_height_unit_responsive == '' && $settings->meta_line_height_unit_medium == '' && $settings->meta_line_height_unit == '' ) :?>
					line-height: <?php echo $settings->meta_line_height['small']; ?>px;
				<?php endif; ?>
                
	            <?php if( $converted === 'yes' || isset( $settings->meta_line_height_unit_responsive ) && $settings->meta_line_height_unit_responsive != '' ){ ?> 
					line-height: <?php echo $settings->meta_line_height_unit_responsive; ?>em;
				<?php } else if( isset( $settings->meta_line_height_unit_responsive ) && $settings->meta_line_height_unit_responsive == '' && isset( $settings->meta_line_height['small'] ) && $settings->meta_line_height['small'] != '' ) { ?>
					line-height: <?php echo $settings->meta_line_height['small']; ?>px;
				<?php } ?>
			}
		<?php
		}

		if( $settings->show_date_box == 'yes' ) {
		?>
		.fl-node-<?php echo $id; ?> .uabb-posted-on {
			<?php if( $converted === 'yes' || isset( $settings->date_font_size_unit_responsive ) && $settings->date_font_size_unit_responsive != '' ){ ?> 
				font-size: <?php echo $settings->date_font_size_unit_responsive; ?>px;
			<?php } else if( isset( $settings->date_font_size_unit_responsive ) && $settings->date_font_size_unit_responsive == '' && isset( $settings->date_font_size['small'] ) && $settings->date_font_size['small'] != '' ) { ?>
				font-size: <?php echo $settings->date_font_size['small']; ?>px;
			<?php } ?> 
		}
		<?php
		}

		if( isset( $settings->title_line_height['small'] )  || isset( $settings->title_font_size['small'] ) || isset( $settings->title_line_height_unit_responsive ) || isset( $settings->title_font_size_unit_responsive ) || isset( $settings->title_line_height_unit_medium ) || isset( $settings->title_line_height_unit ) && ( isset( $settings->post_layout ) && $settings->post_layout != 'custom' ) ) {
		?>
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:hover,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:focus,
		.fl-node-<?php echo $id; ?> <?php echo $settings->title_tag_selection; ?>.uabb-post-heading a:visited {
			
			<?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive != '' ){ ?> 
				font-size: <?php echo $settings->title_font_size_unit_responsive; ?>px;
			<?php } else if( isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive == '' && isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] != '' ) { ?>
				font-size: <?php echo $settings->title_font_size['small']; ?>px;
			<?php } ?> 
		    
		    <?php if( isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' && $settings->title_line_height_unit_responsive == '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) :?>
				    line-height: <?php echo $settings->title_line_height['small']; ?>px;
			<?php endif; ?>

            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_responsive ) && $settings->title_line_height_unit_responsive != '' ){ ?> 
				line-height: <?php echo $settings->title_line_height_unit_responsive; ?>em;
			<?php } else if( isset( $settings->title_line_height_unit_responsive ) && $settings->title_line_height_unit_responsive == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' ) { ?>
				line-height: <?php echo $settings->title_line_height['small']; ?>px;
			<?php } ?>     
		}

		<?php
		}
		else {
		?>
			.fl-node-<?php echo $id; ?> .uabb-post-heading,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:hover,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:focus,
			.fl-node-<?php echo $id; ?> .uabb-post-heading a:visited {
				
				<?php if( $converted === 'yes' || isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive != '' ){ ?> 
					font-size: <?php echo $settings->title_font_size_unit_responsive; ?>px;
				<?php } else if( isset( $settings->title_font_size_unit_responsive ) && $settings->title_font_size_unit_responsive == '' && isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] != '' ) { ?>
					font-size: <?php echo $settings->title_font_size['small']; ?>px;
				<?php } ?>	
			    
			    <?php if( isset( $settings->title_font_size['small'] ) && $settings->title_font_size['small'] == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' && $settings->title_line_height_unit_responsive == '' && $settings->title_line_height_unit_medium == '' && $settings->title_line_height_unit == '' ) :?>
				    line-height: <?php echo $settings->title_line_height['small']; ?>px;
				<?php endif; ?>

	            <?php if( $converted === 'yes' || isset( $settings->title_line_height_unit_responsive ) && $settings->title_line_height_unit_responsive != '' ){ ?> 
					line-height: <?php echo $settings->title_line_height_unit_responsive; ?>em;
				<?php } else if( isset( $settings->title_line_height_unit_responsive ) && $settings->title_line_height_unit_responsive == '' && isset( $settings->title_line_height['small'] ) && $settings->title_line_height['small'] != '' ) { ?>
					line-height: <?php echo $settings->title_line_height['small']; ?>px;
				<?php } ?>  
			}
		<?php
		}

		if( $settings->is_carousel == 'carousel' ) {
			if( $settings->post_per_grid_small == 1 ) {
			?>
			/*.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
				padding: 0;
			}*/
			.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
				margin: 0;
			}
			<?php
			} else {
			?>
			.fl-node-<?php echo $id; ?> .fl-node-content .slick-list {
				margin: 0 -<?php echo ( $settings->element_space != '' ) ? ( $settings->element_space / 2 ) : '7.5'; ?>px;
			}
			.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
				<?php
				echo ( $settings->element_space != '' ) ? 'padding-left: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-left: 7.5px;';
				echo ( $settings->element_space != '' ) ? 'padding-right: ' . ( $settings->element_space / 2 ) . 'px;' : 'padding-right: 7.5px;';
				?>
			}
			<?php
			}
		}
		?>
    	
    	.fl-node-<?php echo $id; ?> select.uabb-masonary-filters,
    	.fl-node-<?php echo $id; ?> ul.uabb-masonary-filters {
			<?php if( isset( $settings->taxonomy_filter_select_font_size_unit_responsive ) && $settings->taxonomy_filter_select_font_size_unit_responsive != '' ) : ?>
			font-size: <?php echo $settings->taxonomy_filter_select_font_size_unit_responsive; ?>px;
			<?php endif; ?>	
		}
    }

    @media ( max-width: <?php echo ( $global_settings->responsive_breakpoint - 1 ); ?>px ) {
    	<?php
    	if( $settings->is_carousel == 'carousel' ) {
			if( $settings->post_per_grid_small == 1 ) {
			?>
			.fl-node-<?php echo $id; ?> .uabb-blog-posts .uabb-post-wrapper {
				padding: 0;
			}
		<?php
			}
		}
    	?>
    }
<?php
}
?>