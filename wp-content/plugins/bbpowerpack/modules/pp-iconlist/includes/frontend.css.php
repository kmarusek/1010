.fl-node-<?php echo $id; ?> .pp-icon-list:before {
	content: "" !important;
}
.fl-node-<?php echo $id; ?> .pp-icon-list:not(.pp-user-agent-ie) [class^="pp-icon-list"] {
	font-family: unset !important;
}
.fl-node-<?php echo $id; ?> .pp-icon-list .pp-icon-list-items .pp-icon-list-item {
	display: table;
	margin-bottom: <?php echo $settings->item_margin; ?>px;
}
.fl-node-<?php echo $id; ?> .pp-icon-list .pp-icon-list-items .pp-icon-list-item .pp-list-item-icon {
	float: left;
	margin-right: <?php echo $settings->icon_space; ?>px;
	background-color: #<?php echo $settings->icon_bg; ?>;
	color: #<?php echo $settings->icon_color; ?>;
	font-size: <?php echo $settings->icon_size; ?>px;
	padding: <?php echo $settings->icon_padding; ?>px;
	border: <?php echo $settings->icon_border_width; ?>px <?php echo $settings->icon_border_type; ?> #<?php echo $settings->icon_border_color; ?>;
	border-radius: <?php echo $settings->icon_border_radius; ?>px;
	text-align: center;
	display: inline-block;
	line-height: <?php echo ('' != $settings->icon_size) ? $settings->icon_size : ''; ?>px;
	<?php if ( $settings->list_type == 'number' || $settings->icon_bg != '' ) : ?>
		width: <?php echo ('' != $settings->icon_size) ? $settings->icon_size * 2 : ''; ?>px;
		height: <?php echo ('' != $settings->icon_size) ? $settings->icon_size * 2 : ''; ?>px;
		line-height: <?php echo ('' != $settings->icon_size) ? $settings->icon_size * 2 : ''; ?>px;
	<?php endif; ?>
	vertical-align: middle;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}
.fl-node-<?php echo $id; ?> .pp-icon-list .pp-icon-list-items .pp-icon-list-item:hover .pp-list-item-icon {
	background-color: #<?php echo $settings->icon_bg_hover; ?>;
	color: #<?php echo $settings->icon_color_hover; ?>;
	border-color: #<?php echo $settings->icon_border_color_hover; ?>;
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
}
.fl-node-<?php echo $id; ?> .pp-icon-list .pp-icon-list-items.pp-list-type-number .pp-icon-list-item .pp-list-item-icon {
	<?php if( $settings->text_font['family'] != 'Default' ) { FLBuilderFonts::font_css( $settings->text_font ); } ?>
}
.fl-node-<?php echo $id; ?> .pp-icon-list .pp-icon-list-items .pp-icon-list-item .pp-list-item-text {
	display: table-cell;
	color: #<?php echo $settings->text_color; ?>;
	<?php if( $settings->text_font['family'] != 'Default' ) { FLBuilderFonts::font_css( $settings->text_font ); } ?>
	font-size: <?php echo $settings->text_size; ?>px;
	line-height: <?php echo $settings->text_line_height; ?>;
	vertical-align: middle;
}
