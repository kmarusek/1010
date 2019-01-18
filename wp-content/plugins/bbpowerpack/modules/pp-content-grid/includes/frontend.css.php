
<?php
$space_desktop = ( $settings->post_grid_count['desktop'] - 1 ) * $settings->post_spacing;
$space_tablet = ( $settings->post_grid_count['tablet'] - 1 ) * $settings->post_spacing;
$space_mobile = ( $settings->post_grid_count['mobile'] - 1 ) * $settings->post_spacing;
$post_columns_desktop = ( 100 - $space_desktop ) / $settings->post_grid_count['desktop'];
$post_columns_tablet = ( 100 - $space_tablet ) / $settings->post_grid_count['tablet'];
$post_columns_mobile = ( 100 - $space_mobile ) / $settings->post_grid_count['mobile'];
$responsive_filter = $settings->responsive_filter;
?>

<?php if(isset( $settings->post_grid_filters_display ) && $settings->post_grid_filters_display == 'yes') { ?>
.fl-node-<?php echo $id; ?> .pp-content-post {
    position: relative;
    float: left;
}
.fl-node-<?php echo $id; ?> ul.pp-post-filters {
	text-align: <?php echo $settings->filter_alignment; ?>;
}
.fl-node-<?php echo $id; ?> ul.pp-post-filters li {
	background: <?php echo ($settings->filter_background['primary']) ? '#'.$settings->filter_background['primary'] : 'transparent'; ?>;
	<?php if( $settings->filter_border_color['primary'] ) { ?>border-color: #<?php echo $settings->filter_border_color['primary']; ?>;<?php } ?>
	<?php if( $settings->filter_border_radius >= 0 ) { ?>border-radius: <?php echo $settings->filter_border_radius; ?>px;<?php } ?>
	<?php if( $settings->filter_border != 'none') { ?>
	border-top-width: <?php echo $settings->filter_border_width['top']; ?>px;
	border-bottom-width: <?php echo $settings->filter_border_width['bottom']; ?>px;
	border-left-width: <?php echo $settings->filter_border_width['left']; ?>px;
	border-right-width: <?php echo $settings->filter_border_width['right']; ?>px;
	<?php } ?>
	<?php if( $settings->filter_border ) { ?>border-style: <?php echo $settings->filter_border; ?>;<?php } ?>
	<?php if( $settings->filter_border_color['primary'] ) { ?>
		border-color: #<?php echo $settings->filter_border_color['primary']; ?>;
	<?php } else { ?>
		border-color: transparent;
	<?php } ?>
	padding-top: <?php echo $settings->filter_padding['top']; ?>px;
	padding-right: <?php echo $settings->filter_padding['right']; ?>px;
	padding-bottom: <?php echo $settings->filter_padding['bottom']; ?>px;
	padding-left: <?php echo $settings->filter_padding['left']; ?>px;
	<?php if( $settings->filter_color['primary'] ) { ?>color: #<?php echo $settings->filter_color['primary']; ?>;<?php } ?>
	margin-right: <?php echo $settings->filter_margin; ?>px;
	margin-bottom: <?php echo ($settings->filter_margin / 2); ?>px;
	<?php if( $settings->filter_font['family'] != 'Default' ) { ?>
	   <?php FLBuilderFonts::font_css( $settings->filter_font ); ?>
   <?php } ?>
   font-size: <?php echo $settings->filter_font_size['desktop']; ?>px;

   <?php if( 'default' != $settings->filter_text_transform ) { ?>
   text-transform: <?php echo $settings->filter_text_transform; ?>
   <?php } ?>
}

.fl-node-<?php echo $id; ?> ul.pp-post-filters li:hover,
.fl-node-<?php echo $id; ?> ul.pp-post-filters li.pp-filter-active {
	background: <?php echo ($settings->filter_background['secondary']) ? '#'.$settings->filter_background['secondary'] : 'transparent'; ?>;
	<?php if( $settings->filter_color['secondary'] ) { ?>color: #<?php echo $settings->filter_color['secondary']; ?>;<?php } ?>
	<?php if( $settings->filter_border_color['secondary'] ) { ?>border-color: #<?php echo $settings->filter_border_color['secondary']; ?>;<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post-grid.pp-is-filtering:after {
	background-image: url(<?php echo BB_POWERPACK_URL; ?>assets/images/spinner.gif);
}

.fl-node-<?php echo $id; ?> .pp-post-filters-toggle {
	background: <?php echo ( isset( $settings->filter_toggle_bg ) && ! empty( $settings->filter_toggle_bg ) ) ? pp_get_color_value( $settings->filter_toggle_bg ) : 'none'; ?>;
	<?php if ( isset( $settings->filter_toggle_color ) && ! empty( $settings->filter_toggle_color ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->filter_toggle_color ); ?>;
	<?php } ?>
	border-width: <?php echo ( isset( $settings->filter_toggle_border ) && ! empty( $settings->filter_toggle_border ) ) ? $settings->filter_toggle_border : 1; ?>px;
	border-style: solid;
	border-color: <?php echo ( isset( $settings->filter_toggle_border_color ) && ! empty( $settings->filter_toggle_border_color ) ) ? pp_get_color_value( $settings->filter_toggle_border_color ) : 'transparent'; ?>;
	<?php if ( isset( $settings->filter_toggle_radius ) && ! empty( $settings->filter_toggle_radius ) ) { ?>
	border-radius: <?php echo $settings->filter_toggle_radius; ?>px;
	<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .pp-content-grid-pagination {
	<?php if ( isset( $settings->pagination_align ) ) { ?>
		text-align: <?php echo $settings->pagination_align; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-content-grid-pagination.fl-builder-pagination {
    padding-top: <?php echo $settings->pagination_spacing_v; ?>px;
    padding-bottom: <?php echo $settings->pagination_spacing_v; ?>px;
}

.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li a.page-numbers,
.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li span.page-numbers {
	background-color: <?php echo ($settings->pagination_background_color['primary']) ? '#' . $settings->pagination_background_color['primary'] : 'transparent'; ?>;
	border-style: <?php echo $settings->pagination_border; ?>;
	<?php if( $settings->pagination_border_width && $settings->pagination_border != 'none' ) { ?>border-width: <?php echo $settings->pagination_border_width; ?>px; <?php } ?>
	<?php if( $settings->pagination_border_color ) { ?> border-color: #<?php echo $settings->pagination_border_color; ?>; <?php } ?>
	<?php if( $settings->pagination_border_radius >= 0 ) { ?> border-radius: <?php echo $settings->pagination_border_radius; ?>px; <?php } ?>
	<?php if( $settings->pagination_text_color['primary'] ) { ?> color: #<?php echo $settings->pagination_text_color['primary']; ?>; <?php } ?>
	padding-top: <?php echo $settings->pagination_padding['top']; ?>px;
	padding-right: <?php echo $settings->pagination_padding['right']; ?>px;
	padding-bottom: <?php echo $settings->pagination_padding['bottom']; ?>px;
	padding-left: <?php echo $settings->pagination_padding['left']; ?>px;
	margin-right: <?php echo $settings->pagination_spacing; ?>px;
	font-size: <?php echo $settings->pagination_font_size['desktop']; ?>px;
}

.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li a.page-numbers:hover,
.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li span.current {
	background-color: <?php echo ($settings->pagination_background_color['secondary']) ? '#' . $settings->pagination_background_color['secondary'] : 'transparent'; ?>;
	<?php if( $settings->pagination_text_color['secondary'] ) { ?> color: #<?php echo $settings->pagination_text_color['secondary']; ?>; <?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-grid-load-more {
	margin-top: <?php echo $settings->pagination_spacing_v; ?>px;
	<?php if ( isset( $settings->pagination_align ) ) { ?>
		text-align: <?php echo $settings->pagination_align; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-content-grid-load-more a {
	<?php if ( ! empty( $settings->pagination_background_color['primary'] ) ) { ?>
		background: #<?php echo $settings->pagination_background_color['primary']; ?>;
	<?php } ?>
	<?php if( $settings->pagination_text_color['primary'] ) { ?>
		color: #<?php echo $settings->pagination_text_color['primary']; ?>;
	<?php } ?>
	border-style: <?php echo $settings->pagination_border; ?>;
	<?php if( $settings->pagination_border_width && $settings->pagination_border != 'none' ) { ?>
		border-width: <?php echo $settings->pagination_border_width; ?>px;
	<?php } ?>
	<?php if( $settings->pagination_border_color ) { ?>
		border-color: #<?php echo $settings->pagination_border_color; ?>;
	<?php } ?>
	<?php if( $settings->pagination_border_radius >= 0 ) { ?>
		border-radius: <?php echo $settings->pagination_border_radius; ?>px;
	<?php } ?>
	padding-top: <?php echo $settings->pagination_padding['top']; ?>px;
	padding-right: <?php echo $settings->pagination_padding['right']; ?>px;
	padding-bottom: <?php echo $settings->pagination_padding['bottom']; ?>px;
	padding-left: <?php echo $settings->pagination_padding['left']; ?>px;
	font-size: <?php echo $settings->pagination_font_size['desktop']; ?>px;
	text-align: center;
	text-decoration: none;
	transition: all 0.2s ease-in-out;
}
.fl-node-<?php echo $id; ?> .pp-content-grid-load-more a:hover {
	<?php if ( ! empty( $settings->pagination_background_color['secondary'] ) ) { ?>
		background: #<?php echo $settings->pagination_background_color['secondary']; ?>;
	<?php } ?>
	<?php if ( ! empty( $settings->pagination_text_color['secondary'] ) ) { ?>
		color: #<?php echo $settings->pagination_text_color['secondary']; ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title {
	<?php if ( isset( $settings->show_title ) && 'no' == $settings->show_title ) { ?>
		display: none;
	<?php } ?>
	<?php if( $settings->title_font['family'] != 'Default' ) { ?>
	   <?php FLBuilderFonts::font_css( $settings->title_font ); ?>
   <?php } ?>

	<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['desktop'] ) { ?>
		font-size: <?php echo $settings->title_custom_font_size['desktop']; ?>px;
	<?php } ?>

	<?php if( $settings->title_line_height_toggle == 'custom' && $settings->title_custom_line_height['desktop'] ) { ?>
		line-height: <?php echo $settings->title_custom_line_height['desktop']; ?>;
	<?php } ?>
	<?php if( $settings->title_font_color ) { ?>
		color: #<?php echo $settings->title_font_color; ?>;
	<?php } ?>
	<?php if( $settings->title_margin['top'] >= 0 ) { ?>
		margin-top: <?php echo $settings->title_margin['top']; ?>px;
	<?php } ?>
	<?php if( $settings->title_margin['bottom'] >= 0 ) { ?>
		margin-bottom: <?php echo $settings->title_margin['bottom']; ?>px;
	<?php } ?>
	<?php if( $settings->title_text_transform != 'default' ) { ?>
		text-transform: <?php echo $settings->title_text_transform; ?>;
	<?php } ?>
}

<?php if ( 'tribe_events' == $settings->post_type && 'style-9' == $settings->post_grid_style_select ) { ?>
	<?php if ( ( isset( $settings->event_date ) && 'yes' == $settings->event_date )
		|| ( isset( $settings->event_venue ) && 'yes' == $settings->event_venue ) 
		|| ( isset( $settings->event_cost ) && 'yes' == $settings->event_cost ) ) { ?>
		.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title {
			margin-bottom: auto;
		}
	<?php } ?>
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-event-calendar-meta {
		<?php if( $settings->title_margin['bottom'] >= 0 ) { ?>
			margin-bottom: <?php echo $settings->title_margin['bottom']; ?>px;
		<?php } ?>
	}
<?php } ?>

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title a {
	<?php if( $settings->title_font_color ) { ?>
		color: #<?php echo $settings->title_font_color; ?>;
	<?php } ?>
	<?php if( $settings->title_font['family'] != 'Default' ) { ?>
	   <?php FLBuilderFonts::font_css( $settings->title_font ); ?>
   <?php } ?>
	<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['desktop'] ) { ?>
		font-size: <?php echo $settings->title_custom_font_size['desktop']; ?>px;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-content {
	<?php if( $settings->content_font['family'] != 'Default' ) { ?>
	   <?php FLBuilderFonts::font_css( $settings->content_font ); ?>
   <?php } ?>
	<?php if( $settings->content_font_size_toggle == 'custom' && $settings->content_custom_font_size['desktop'] ) { ?>
		font-size: <?php echo $settings->content_custom_font_size['desktop']; ?>px;
	<?php } ?>
	<?php if( $settings->content_line_height_toggle == 'custom' && $settings->content_custom_line_height['desktop'] ) { ?>
		line-height: <?php echo $settings->content_custom_line_height['desktop']; ?>;
	<?php } ?>
	<?php if( $settings->content_font_color ) { ?>
		color: #<?php echo $settings->content_font_color; ?>;
	<?php } ?>
	<?php if( $settings->description_margin['top'] >= 0 ) { ?>
		margin-top: <?php echo $settings->description_margin['top']; ?>px;
	<?php } ?>
	<?php if( $settings->description_margin['bottom'] >= 0 ) { ?>
		margin-bottom: <?php echo $settings->description_margin['bottom']; ?>px;
	<?php } ?>
}

<?php /* The Events Calendar */ ?>
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-date,
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-date span {
	<?php if ( isset( $settings->event_date_color ) && ! empty( $settings->event_date_color ) ) { ?>
		color: #<?php echo $settings->event_date_color; ?>;
	<?php } ?>
	<?php if ( isset( $settings->event_date_case ) && 'default' != $settings->event_date_case ) { ?>
		text-transform: <?php echo $settings->event_date_case; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-venue,
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-venue span.tribe-address {
	<?php if ( isset( $settings->event_venue_color ) && ! empty( $settings->event_venue_color ) ) { ?>
		color: #<?php echo $settings->event_venue_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-cost,
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-cost span.ticket-cost {
	<?php if ( isset( $settings->event_cost_color ) && ! empty( $settings->event_cost_color ) ) { ?>
		color: #<?php echo $settings->event_cost_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-cost form {
	margin-top: 10px;
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-more-link-button,
.fl-node-<?php echo $id; ?> .pp-content-post .pp-add-to-cart a,
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-cost form .tribe-button {
	<?php if((isset( $settings->more_link_type ) && $settings->more_link_type == 'button') || (isset($settings->product_button) && $settings->product_button == 'yes') ) { ?>
		background: <?php echo ($settings->button_background['primary']) ? '#'.$settings->button_background['primary'] : 'transparent'; ?>;
		<?php if( $settings->button_border_color['primary'] ) { ?>border-color: #<?php echo $settings->button_border_color['primary']; ?>;<?php } ?>
		<?php if( $settings->button_border_radius >= 0 ) { ?>border-radius: <?php echo $settings->button_border_radius; ?>px;<?php } ?>
		<?php if( $settings->button_border != 'none') { ?>
		border-top-width: <?php echo $settings->button_border_width['top']; ?>px;
		border-bottom-width: <?php echo $settings->button_border_width['bottom']; ?>px;
		border-left-width: <?php echo $settings->button_border_width['left']; ?>px;
		border-right-width: <?php echo $settings->button_border_width['right']; ?>px;
		<?php } ?>
		<?php if( $settings->button_border ) { ?>border-style: <?php echo $settings->button_border; ?>;<?php } ?>

		padding-top: <?php echo $settings->button_padding['top']; ?>px;
		padding-right: <?php echo $settings->button_padding['right']; ?>px;
		padding-bottom: <?php echo $settings->button_padding['bottom']; ?>px;
		padding-left: <?php echo $settings->button_padding['left']; ?>px;

		<?php if( $settings->button_width == 'full' ) { ?>
	 	   width: 100%;
	    <?php } ?>
	<?php } ?>

	<?php if( $settings->button_color['primary'] ) { ?>color: #<?php echo $settings->button_color['primary']; ?>;<?php } ?>

	<?php if( $settings->button_font['family'] != 'Default' ) { ?>
	   <?php FLBuilderFonts::font_css( $settings->button_font ); ?>
   <?php } ?>
   <?php if($settings->button_font_size['desktop'] ) { ?>
	   font-size: <?php echo $settings->button_font_size['desktop']; ?>px;
   <?php } ?>

   <?php if( 'default' != $settings->button_text_transform ) { ?>
   text-transform: <?php echo $settings->button_text_transform; ?>;
   <?php } ?>
   cursor: pointer;
}

<?php if ( $settings->match_height == 'yes' ) { ?>
.fl-node-<?php echo $id; ?> .pp-content-post-data.pp-content-relative {
	position: relative;
}

.fl-node-<?php echo $id; ?> .pp-content-post-data.pp-content-relative .pp-more-link-button {
	position: absolute;
	bottom: 0;
	<?php if( $settings->post_content_alignment == 'center' ) { ?>
	left: 50%;
    transform: translateX(-50%);
	<?php } else if( $settings->post_content_alignment == 'left' ) { ?>
	left: 0;
	<?php } else { ?>
	right: 0;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-grid-style-5 .pp-content-post-data.pp-content-relative .pp-more-link-button {
	left: 0;
	transform: none;
}

.fl-node-<?php echo $id; ?> .pp-grid-style-6 .pp-content-post-data.pp-content-relative .pp-more-link-button {
	left: 50%;
    transform: translateX(-50%);
}
<?php } ?>



.fl-node-<?php echo $id; ?> .pp-content-grid-post .pp-content-grid-more:hover,
.fl-node-<?php echo $id; ?> .pp-content-carousel-post .pp-content-carousel-more:hover,
.fl-node-<?php echo $id; ?> .pp-content-grid-post .pp-add-to-cart a:hover,
.fl-node-<?php echo $id; ?> .pp-post-event-calendar-cost form .tribe-button:hover {
	<?php if( $settings->button_background['secondary'] ) { ?>background: #<?php echo $settings->button_background['secondary']; ?>;<?php } ?>
	<?php if( $settings->button_color['secondary'] ) { ?>color: #<?php echo $settings->button_color['secondary']; ?>;<?php } ?>
	<?php if( $settings->button_border_color['secondary'] ) { ?>border-color: #<?php echo $settings->button_border_color['secondary']; ?>;<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title-divider {
	background-color: #<?php echo $settings->post_title_divider_color; ?>;
}

<?php if ( $settings->post_grid_style_select == 'style-8' && $settings->show_image == 'yes' ) { ?>
.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-image {
    float: left;
    width: 40%;
}
<?php } ?>

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-image .pp-content-category-list {
	background-color: #<?php echo $settings->post_category_bg_color; ?>;
	color: #<?php echo $settings->post_category_text_color; ?>;
	<?php if($settings->post_category_position == 'right') { ?>
	right: 0;
	left: auto;
	<?php } else { ?>
	right: auto;
	left: 0;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-image .pp-content-category-list a {
	color: #<?php echo $settings->post_category_text_color; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-5 .pp-content-post-date span.pp-post-day {
	background-color: #<?php echo $settings->post_date_day_bg_color; ?>;
	color: #<?php echo $settings->post_date_day_text_color; ?>;
	border-top-left-radius: <?php echo $settings->post_date_border_radius; ?>px;
	border-top-right-radius: <?php echo $settings->post_date_border_radius; ?>px;
}
.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-5 .pp-content-post-date span.pp-post-month {
	background-color: #<?php echo $settings->post_date_month_bg_color; ?>;
	color: #<?php echo $settings->post_date_month_text_color; ?>;
	border-bottom-left-radius: <?php echo $settings->post_date_border_radius; ?>px;
	border-bottom-right-radius: <?php echo $settings->post_date_border_radius; ?>px;
}

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-6 .pp-post-image .pp-content-post-date {
	background-color: #<?php echo $settings->post_date_bg_color; ?>;
	color: #<?php echo $settings->post_date_text_color; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-image .pp-post-title {
	background: <?php echo ($settings->post_title_overlay_color) ? pp_hex2rgba('#'.$settings->post_title_overlay_color, ($settings->post_title_overlay_opacity/ 100)) : 'transparent'; ?>;
	text-align: <?php echo $settings->post_content_alignment; ?>;
}


.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-meta {
	<?php if( $settings->post_meta_font['family'] != 'Default' ) { ?>
		<?php FLBuilderFonts::font_css( $settings->post_meta_font ); ?>
	<?php } ?>
	<?php if( $settings->post_meta_font_size >= 0 ) { ?>
	font-size: <?php echo $settings->post_meta_font_size['desktop']; ?>px;
	<?php } ?>
	color: #<?php echo $settings->post_meta_font_color; ?>;
	<?php if( $settings->post_meta_text_transform != 'default' ) { ?>
	text-transform: <?php echo $settings->post_meta_text_transform; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-meta span {
	<?php if ( 'style-9' == $settings->post_grid_style_select && isset( $settings->post_meta_bg_color ) && ! empty( $settings->post_meta_bg_color ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->post_meta_bg_color ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-grid-post .pp-content-category-list,
.fl-node-<?php echo $id; ?> .pp-content-carousel-post .pp-content-category-list {
	border-top-color: #<?php echo FLBuilderColor::adjust_brightness( $settings->post_background['primary'], 12, 'darken' ); ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-7 .pp-content-post-meta {
	border-bottom-color: #<?php echo FLBuilderColor::adjust_brightness( $settings->post_background['primary'], 12, 'darken' ); ?>
}
.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-7:hover .pp-content-post-meta {
	border-bottom-color: #<?php echo FLBuilderColor::adjust_brightness( $settings->post_background['secondary'], 12, 'darken' ); ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-meta a {
	color: #<?php echo $settings->post_meta_font_color; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post-carousel .owl-theme .owl-dots .owl-dot span {
	opacity: 1;
    <?php if( $settings->post_slider_dot_bg_color ) { ?>
    background: #<?php echo $settings->post_slider_dot_bg_color; ?>;
    <?php } ?>
    <?php if( $settings->post_slider_dot_width >= 0 ) { ?>
    width: <?php echo $settings->post_slider_dot_width; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_dot_width >= 0 ) { ?>
    height: <?php echo $settings->post_slider_dot_width; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_dot_border_radius >= 0 ) { ?>
    border-radius: <?php echo $settings->post_slider_dot_border_radius; ?>px;
    <?php } ?>
    box-shadow: none;
}

.fl-node-<?php echo $id; ?> .pp-content-post-carousel .owl-theme .owl-dots .owl-dot.active span,
.fl-node-<?php echo $id; ?> .pp-content-post-carousel .owl-theme .owl-dots .owl-dot:hover span {
    <?php if( $settings->post_slider_dot_bg_hover ) { ?>
	background: #<?php echo $settings->post_slider_dot_bg_hover; ?>;
    <?php } ?>
	opacity: 1;
    box-shadow: none;
}

.fl-node-<?php echo $id; ?> .pp-content-post-carousel .owl-theme .owl-nav button svg {
	width: <?php echo ( $settings->post_slider_arrow_font_size * 1.7 ); ?>px;
	height: <?php echo ( $settings->post_slider_arrow_font_size * 1.7 ); ?>px;
	<?php if( $settings->post_slider_arrow_color['primary'] ) { ?>
	color: #<?php echo $settings->post_slider_arrow_color['primary']; ?>;
    <?php } ?>
    background: <?php echo ($settings->post_slider_arrow_bg_color['primary']) ? pp_hex2rgba('#'.$settings->post_slider_arrow_bg_color['primary'], 0.5) : 'transparent'; ?>;
    <?php if( $settings->post_slider_arrow_border_radius >= 0 ) { ?>
    border-radius: <?php echo $settings->post_slider_arrow_border_radius; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_padding['top'] >= 0 ) { ?>
    padding-top: <?php echo $settings->post_slider_arrow_padding['top']; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_padding['bottom'] >= 0 ) { ?>
    padding-bottom: <?php echo $settings->post_slider_arrow_padding['bottom']; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_padding['left'] >= 0 ) { ?>
    padding-left: <?php echo $settings->post_slider_arrow_padding['left']; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_padding['right'] >= 0 ) { ?>
    padding-right: <?php echo $settings->post_slider_arrow_padding['right']; ?>px;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_border_style ) { ?>
    border-style: <?php echo $settings->post_slider_arrow_border_style; ?>;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_border_width >= 0 ) { ?>
    border-width: <?php echo $settings->post_slider_arrow_border_width; ?>px;
    <?php } ?>
	<?php if( $settings->post_slider_arrow_border_color['primary'] ) { ?>
    border-color: #<?php echo $settings->post_slider_arrow_border_color['primary']; ?>;
    <?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post-carousel .owl-theme .owl-nav button:hover svg {
    <?php if( $settings->post_slider_arrow_color['secondary'] ) { ?>
    color: #<?php echo $settings->post_slider_arrow_color['secondary']; ?>;
    <?php } ?>
    <?php if( isset( $settings->post_slider_arrow_bg_color['secondary'] ) ) { ?>
    background: <?php echo ($settings->post_slider_arrow_bg_color['secondary']) ? pp_hex2rgba('#' . $settings->post_slider_arrow_bg_color['secondary'], 1) : 'transparent'; ?>;
    <?php } ?>
    <?php if( $settings->post_slider_arrow_border_color['secondary'] ) { ?>
    border-color: #<?php echo $settings->post_slider_arrow_border_color['secondary']; ?>;
    <?php } ?>
}

<?php
/* Grid & Carousel Setting Layout */
if($settings->layout == 'grid' || $settings->layout == 'carousel') { // GRID ?>

.fl-node-<?php echo $id; ?> .pp-content-post {
	<?php if( $settings->post_grid_padding['top'] >= 0 ) { ?>
	padding-top: <?php echo $settings->post_grid_padding['top']; ?>px;
	<?php } ?>
	<?php if( $settings->post_grid_padding['right'] >= 0 ) { ?>
	padding-right: <?php echo $settings->post_grid_padding['right']; ?>px;
	<?php } ?>
	<?php if( $settings->post_grid_padding['bottom'] >= 0 ) { ?>
	padding-bottom: <?php echo $settings->post_grid_padding['bottom']; ?>px;
	<?php } ?>
	<?php if( $settings->post_grid_padding['left'] >= 0 ) { ?>
	padding-left: <?php echo $settings->post_grid_padding['left']; ?>px;
	<?php } ?>
    opacity: 1;
	text-align: <?php echo 'style-6' != $settings->post_grid_style_select ? $settings->post_content_alignment : 'center'; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post:hover {
	background-color: <?php echo ($settings->post_background['secondary']) ? '#' . $settings->post_background['secondary'] : 'transparent'; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-7 .pp-content-body {
	background-color: <?php echo ($settings->post_background['primary']) ? '#' . $settings->post_background['primary'] : 'transparent'; ?>;
}
.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-7:hover .pp-content-body {
	background-color: <?php echo ($settings->post_background['secondary']) ? '#' . $settings->post_background['secondary'] : 'transparent'; ?>;
}

.woocommerce .fl-node-<?php echo $id; ?> .pp-content-post {
	<?php if( 'grid' == $settings->layout ) { ?>
	margin-bottom: <?php echo $settings->post_spacing; ?>%;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post {
	position: relative;
    <?php if( 'grid' == $settings->layout ) { ?>
	float: left;
    <?php } ?>
	<?php if( 'grid' == $settings->layout ) { ?>
	margin-bottom: <?php echo $settings->post_spacing; ?>%;
	width: <?php echo $post_columns_desktop; ?>%;
	<?php } ?>
	<?php if( 'carousel' == $settings->layout ) { ?>
	margin-left: <?php echo ($settings->post_spacing / 2); ?>%;
	margin-right: <?php echo ($settings->post_spacing / 2); ?>%;
	<?php } ?>
	<?php if ( isset( $settings->post_background['primary'] ) ) { ?>
	background-color: <?php echo pp_get_color_value($settings->post_background['primary']); ?>;
	<?php } ?>
	border-style: <?php echo $settings->post_border; ?>;
	<?php if( $settings->post_border_width && $settings->post_border != 'none' ) { ?>
		<?php if( '' == $settings->post_border_position ) { ?>
			border-width: <?php echo $settings->post_border_width; ?>px;
		<?php } else { ?>
			border-width: 0;
			border-<?php echo $settings->post_border_position; ?>-width: <?php echo $settings->post_border_width; ?>px;
		<?php } ?>
	<?php } ?>
	<?php if( $settings->post_border_color ) { ?> border-color: #<?php echo $settings->post_border_color; ?>; <?php } ?>
	<?php if( $settings->post_border_radius >= 0 ) { ?> border-radius: <?php echo $settings->post_border_radius; ?>px; <?php } ?>
	<?php if ( 'yes' == $settings->post_shadow_display ) { ?>
    -webkit-box-shadow: <?php echo $settings->post_shadow['horizontal']; ?>px <?php echo $settings->post_shadow['vertical']; ?>px <?php echo $settings->post_shadow['blur']; ?>px <?php echo $settings->post_shadow['spread']; ?>px <?php echo pp_hex2rgba( '#'.$settings->post_shadow_color, $settings->post_shadow_opacity / 100 ); ?>;
    -moz-box-shadow: <?php echo $settings->post_shadow['horizontal']; ?>px <?php echo $settings->post_shadow['vertical']; ?>px <?php echo $settings->post_shadow['blur']; ?>px <?php echo $settings->post_shadow['spread']; ?>px <?php echo pp_hex2rgba( '#'.$settings->post_shadow_color, $settings->post_shadow_opacity / 100 ); ?>;
    -o-box-shadow: <?php echo $settings->post_shadow['horizontal']; ?>px <?php echo $settings->post_shadow['vertical']; ?>px <?php echo $settings->post_shadow['blur']; ?>px <?php echo $settings->post_shadow['spread']; ?>px <?php echo pp_hex2rgba( '#'.$settings->post_shadow_color, $settings->post_shadow_opacity / 100 ); ?>;
    box-shadow: <?php echo $settings->post_shadow['horizontal']; ?>px <?php echo $settings->post_shadow['vertical']; ?>px <?php echo $settings->post_shadow['blur']; ?>px <?php echo $settings->post_shadow['spread']; ?>px <?php echo pp_hex2rgba( '#'.$settings->post_shadow_color, $settings->post_shadow_opacity / 100 ); ?>;
    <?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-grid-space {
	width: <?php echo $settings->post_spacing; ?>%;
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-content-grid-more-link,
.fl-node-<?php echo $id; ?> .pp-content-post .pp-add-to-cart {
	margin-top: <?php echo $settings->button_margin['top']; ?>px;
	margin-bottom: <?php echo $settings->button_margin['bottom']; ?>px;
    position: relative;
    z-index: 2;
}

<?php if ( $settings->match_height == 'yes' ) { ?>
.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['desktop']; ?>n) {
    margin-right: 0;
}
.fl-node-<?php echo $id; ?> .pp-content-post-grid.pp-filters-active .pp-content-grid-post {
	margin-right: 0;
}
	<?php if ( 'load_more' == $settings->pagination || 'scroll' == $settings->pagination ) { ?>
		.fl-node-<?php echo $id; ?> .pp-content-post-grid .pp-content-grid-post {
			margin-right: 0;
		}
	<?php } ?>
<?php } ?>


.fl-node-<?php echo $id; ?> .pp-content-post .pp-content-body {
    <?php if ( $settings->post_grid_style_select == 'style-8' ) { ?>
        float: left;
        width: <?php echo $settings->show_image == 'yes' ? '60%' : '100%'; ?>;
    <?php } ?>
	padding-top: <?php echo $settings->post_content_padding['top']; ?>px;
	padding-right: <?php echo $settings->post_content_padding['right']; ?>px;
	padding-bottom: <?php echo $settings->post_content_padding['bottom']; ?>px;
	padding-left: <?php echo $settings->post_content_padding['left']; ?>px;
}

/* Woocommerce Style */

.fl-node-<?php echo $id; ?> .pp-content-post .star-rating {
    <?php if( $settings->post_content_alignment == 'left' ) { ?>
        margin-left: 0;
    <?php } else if( $settings->post_content_alignment == 'right' ) { ?>
        margin-right: 0;
    <?php } else { ?>
        margin: 0 auto;
    <?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-5 .star-rating {
    margin-left: 0;
}

.fl-node-<?php echo $id; ?> .pp-content-post .star-rating:before,
.fl-node-<?php echo $id; ?> .pp-content-post .star-rating span:before {
    color: #<?php echo isset( $settings->product_rating_color ) ? $settings->product_rating_color : ''; ?>;
}

.fl-node-<?php echo $id; ?> .pp-content-post .pp-product-price {
    color: #<?php echo isset( $settings->product_price_color ) ? $settings->product_price_color : ''; ?>;
    <?php if( $settings->post_meta_font_size >= 0 ) { ?>
    font-size: <?php echo $settings->post_meta_font_size['desktop']; ?>px;
    <?php } ?>
}

<?php } ?>

.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-9 {
	<?php if ( isset( $settings->custom_height ) && ! empty( $settings->custom_height ) ) { ?>
		height: <?php echo $settings->custom_height; ?>px;
	<?php } ?>
}

<?php if ( $responsive_filter != 'no' ) { ?>
	<?php if ( $responsive_filter == 'all' ) { ?>
	<?php } elseif ( $responsive_filter == 'large' ) { ?>
		@media screen and (min-width: <?php echo intval( $global_settings->medium_breakpoint ) - 1; ?>px) {
	<?php } elseif ( $responsive_filter == 'large_medium' ) { ?>
		@media screen and (min-width: <?php echo intval( $global_settings->responsive_breakpoint ) - 1; ?>px) {
	<?php } elseif ( $responsive_filter == 'medium' ) { ?>
		@media screen and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) and (min-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
	<?php } elseif ( $responsive_filter == 'medium_small' ) { ?>
		@media screen and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
	<?php } elseif ( $responsive_filter == 'yes' ) { ?>
		@media screen and (max-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
	<?php } ?>
			.fl-node-<?php echo $id; ?> .pp-post-filters-toggle {
				display: block;
			}
			.fl-node-<?php echo $id; ?> ul.pp-post-filters {
				display: none;
			}
			.fl-node-<?php echo $id; ?> ul.pp-post-filters li {
				display: block;
				float: none;
				margin: 0 !important;
				text-align: left;
			}
		<?php if ( $responsive_filter != 'all' ) { ?>	
		}
		<?php } ?>
<?php } ?>

@media screen and (max-width: <?php echo $global_settings->medium_breakpoint; ?>px) {
	.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-9 {
		<?php if ( isset( $settings->custom_height_medium ) && ! empty( $settings->custom_height_medium ) ) { ?>
			height: <?php echo $settings->custom_height_medium; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-post {
		width: <?php echo $post_columns_tablet; ?>%;
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['desktop']; ?>n+1){
	    clear: none;
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['tablet']; ?>n+1) {
	    clear: left;
	}

	/*
	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['desktop']; ?>n) {
	    margin-right: <?php echo $settings->post_spacing; ?>%;
	}
	*/

	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['tablet']; ?>n) {
	    margin-right: 0;
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-more-link-button,
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-add-to-cart a {
		<?php if($settings->button_font_size['tablet'] ) { ?>
		   font-size: <?php echo $settings->button_font_size['tablet']; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title {
		<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['tablet'] ) { ?>
			font-size: <?php echo $settings->title_custom_font_size['tablet']; ?>px;
		<?php } ?>

		<?php if( $settings->title_line_height_toggle == 'custom' && $settings->title_custom_line_height['tablet'] ) { ?>
			line-height: <?php echo $settings->title_custom_line_height['tablet']; ?>;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title a {
		<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['tablet'] ) { ?>
			font-size: <?php echo $settings->title_custom_font_size['tablet']; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-content {
		<?php if( $settings->content_font_size_toggle == 'custom' && $settings->content_custom_font_size['tablet'] ) { ?>
			font-size: <?php echo $settings->content_custom_font_size['tablet']; ?>px;
		<?php } ?>
		<?php if( $settings->content_line_height_toggle == 'custom' && $settings->content_custom_line_height['tablet'] ) { ?>
			line-height: <?php echo $settings->content_custom_line_height['tablet']; ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-meta,
    .fl-node-<?php echo $id; ?> .pp-content-post .pp-product-price {
	   <?php if( $settings->post_meta_font_size >= 0 ) { ?>
	   font-size: <?php echo $settings->post_meta_font_size['tablet']; ?>px;
	   <?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li a.page-numbers,
	.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li span.page-numbers {
		font-size: <?php echo $settings->pagination_font_size['tablet']; ?>px;
	}
	.fl-node-<?php echo $id; ?> .pp-content-grid-load-more a {
		font-size: <?php echo $settings->pagination_font_size['tablet']; ?>px;
	}
	.fl-node-<?php echo $id; ?> ul.pp-post-filters li {
	   font-size: <?php echo $settings->filter_font_size['tablet']; ?>px;
	}
}

@media screen and (max-width: <?php echo $global_settings->responsive_breakpoint; ?>px) {
	.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-9 {
		<?php if ( isset( $settings->custom_height_responsive ) && ! empty( $settings->custom_height_responsive ) ) { ?>
			height: <?php echo $settings->custom_height_responsive; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-post {
		width: <?php echo $post_columns_mobile; ?>%;
	}
	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['tablet']; ?>n+1) {
	    clear: none;
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['mobile']; ?>n+1) {
	    clear: left;
	}

	/*
	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['tablet']; ?>n) {
	    margin-right: <?php echo $settings->post_spacing; ?>%;
	}
	*/

	.fl-node-<?php echo $id; ?> .pp-content-grid-post:nth-of-type(<?php echo $settings->post_grid_count['mobile']; ?>n) {
	    margin-right: 0;
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-more-link-button,
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-add-to-cart a {
		<?php if($settings->button_font_size['mobile'] ) { ?>
		   font-size: <?php echo $settings->button_font_size['mobile']; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title {
		<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['mobile'] ) { ?>
			font-size: <?php echo $settings->title_custom_font_size['mobile']; ?>px;
		<?php } ?>

		<?php if( $settings->title_line_height_toggle == 'custom' && $settings->title_custom_line_height['mobile'] ) { ?>
			line-height: <?php echo $settings->title_custom_line_height['mobile']; ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-title a {
		<?php if( $settings->title_font_size_toggle == 'custom' && $settings->title_custom_font_size['mobile'] ) { ?>
			font-size: <?php echo $settings->title_custom_font_size['mobile']; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-content {
		<?php if( $settings->content_font_size_toggle == 'custom' && $settings->content_custom_font_size['mobile'] ) { ?>
			font-size: <?php echo $settings->content_custom_font_size['mobile']; ?>px;
		<?php } ?>
		<?php if( $settings->content_line_height_toggle == 'custom' && $settings->content_custom_line_height['mobile'] ) { ?>
			line-height: <?php echo $settings->content_custom_line_height['mobile']; ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-8 .pp-post-image,
	.fl-node-<?php echo $id; ?> .pp-content-post.pp-grid-style-8 .pp-content-body {
		float: none;
		width: 100%;
	}

	.fl-node-<?php echo $id; ?> .pp-content-post .pp-post-meta,
    .fl-node-<?php echo $id; ?> .pp-content-post .pp-product-price {
	   <?php if( $settings->post_meta_font_size >= 0 ) { ?>
	   font-size: <?php echo $settings->post_meta_font_size['mobile']; ?>px;
	   <?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li a.page-numbers,
	.fl-node-<?php echo $id; ?> .pp-content-grid-pagination li span.page-numbers {
		font-size: <?php echo $settings->pagination_font_size['mobile']; ?>px;
	}
	.fl-node-<?php echo $id; ?> .pp-content-grid-load-more a {
		font-size: <?php echo $settings->pagination_font_size['mobile']; ?>px;
	}
	.fl-node-<?php echo $id; ?> ul.pp-post-filters li {
	   	font-size: <?php echo $settings->filter_font_size['mobile']; ?>px;
	}
}