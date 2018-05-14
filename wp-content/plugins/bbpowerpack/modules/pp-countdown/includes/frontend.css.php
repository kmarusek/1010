<?php
$settings->block_bg_color_opc = ( '' != $settings->block_bg_color_opc ) ? $settings->block_bg_color_opc : 100;
$block_spacing = ( isset( $settings->block_spacing ) && ! empty( $settings->block_spacing ) ) ? $settings->block_spacing : 10;
?>

<?php
/* Over All Alignment */
if ( isset( $settings->counter_alignment ) && 'right' == $settings->counter_alignment ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer,
	.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer {
		text-align: right;
	}
<?php }
if ( isset( $settings->counter_alignment ) && 'center' == $settings->counter_alignment ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer,
	.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer {
		text-align: center;
	}
<?php } ?>
.fl-node-<?php echo $id;?> .pp-countdown-item {
	<?php
	if ( isset( $settings->counter_alignment ) && 'right' == $settings->counter_alignment ) {
		if ( $block_spacing ) {
			echo 'margin-left: ' . $block_spacing . 'px;';
		} else {
			echo 'margin-left: 20px;';
		}
	}

	if ( isset( $settings->counter_alignment ) && 'left' == $settings->counter_alignment ) {
		if ( $block_spacing ) {
			echo 'margin-right: ' . $block_spacing . 'px;';
		} else {
			echo 'margin-right: 20px;';
		}
	}

	if ( isset( $settings->counter_alignment ) && 'center' == $settings->counter_alignment ) {
		if ( $block_spacing ) {
			$margin_val = $block_spacing / 2;
			echo 'margin-left: ' . $margin_val . 'px;';
			echo 'margin-right: ' . $margin_val . 'px;';
		} else {
			$margin_val = 10 / 2;
			echo 'margin-left: ' . $margin_val . 'px;';
			echo 'margin-right: ' . $margin_val . 'px;';
		}
	}
	?>

}
<?php if ( isset( $settings->block_style ) && 'default' != $settings->block_style && ( ( isset( $settings->label_position ) && 'outside' == $settings->label_position && isset( $settings->label_outside_position ) && 'out_right' == $settings->label_outside_position ) || ( isset( $settings->label_position ) && 'outside' == $settings->label_position && isset( $settings->label_outside_position ) && 'out_left' == $settings->label_outside_position ) ) ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-item.circle,
	.fl-node-<?php echo $id;?> .pp-countdown-item.square,
	.fl-node-<?php echo $id;?> .pp-countdown-item.normal,
	.fl-node-<?php echo $id;?> .pp-countdown-item.custom {
		display: inline-flex;
		align-items: center;
		<?php if ( isset( $settings->label_position ) && 'outside' == $settings->label_position && isset( $settings->label_outside_position ) && 'out_right' == $settings->label_outside_position ) { ?>
			direction: rtl;
		<?php } if ( isset( $settings->label_position ) && 'outside' == $settings->label_position && isset( $settings->label_outside_position ) && 'out_left' == $settings->label_outside_position ) { ?>
			direction: ltr;
		<?php } ?>
	}
<?php } ?>


<?php if ( 'default' == $settings->block_style && $block_spacing ) { ?>
.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-colon .pp-countdown-item .pp-countdown-digit-wrapper {
	padding-left: <?php echo $block_spacing; ?>px;
	padding-right: <?php echo $block_spacing; ?>px;
}
<?php } ?>

<?php if ( 'yes' == $settings->show_separator ) : ?>
	<?php if ( 'colon' == $settings->separator_type ) : ?>
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-colon .pp-countdown-item .pp-countdown-digit-wrapper:after {
			<?php
			if ( isset( $settings->separator_size ) ) {
				echo 'font-size:' . $settings->separator_size . 'px;';
			} ?>
			<?php if ( isset( $settings->separator_color ) ) { ?>
				color: <?php echo ( false === strpos( $settings->separator_color, 'rgb' ) ) ? '#' . $settings->separator_color : $settings->separator_color; ?>;
			<?php } ?>
			right: -<?php echo $block_spacing / 2; ?>px;
		}
	<?php endif; ?>

	<?php if ( 'line' == $settings->separator_type ) : ?>
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-line .pp-countdown-item:after {
			right: 0;
			<?php if ( isset( $settings->separator_color ) ) { ?>
				border-color: <?php echo ( false === strpos( $settings->separator_color, 'rgb' ) ) ? '#' . $settings->separator_color : $settings->separator_color; ?>;
			<?php } ?>
		}
		<?php if ( $block_spacing ) { ?>
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-line .pp-countdown-item {
			padding-left: <?php echo ( ( $block_spacing / 2 ) ); ?>px;
			padding-right: <?php echo ( ( $block_spacing / 2 ) ); ?>px;
		}
		<?php } ?>

		<?php if ( 'default' == $settings->block_style && $block_spacing ) { ?>
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-line .pp-countdown-item {
			padding-left: <?php echo $block_spacing; ?>px;
			padding-right: <?php echo $block_spacing; ?>px;
		}
		<?php } ?>
	<?php endif; ?>
<?php endif; ?>

<?php
/* CountDown Styling */
if ( isset( $settings->block_style ) && 'circle' == $settings->block_style ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit-wrapper.circle {
		width: <?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo  $settings->block_width; } else { echo 100; } ?>px;
		height: <?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo  $settings->block_width; } else { echo 100; } ?>px;
		border: <?php if ( isset( $settings->block_border_width ) && '' != $settings->block_border_width ) { echo  $settings->block_border_width; } else { echo 5; } ?>px <?php if ( isset( $settings->block_border_style ) ) { echo $settings->block_border_style; } ?> <?php if ( isset( $settings->block_border_color ) ) { echo '#' . $settings->block_border_color; } ?>;
		border-radius: 50%;
		<?php if ( 'solid' == $settings->block_bg_type && '' != $settings->block_bg_color ) { ?>
			background: <?php echo ( $settings->block_bg_color ) ? pp_hex2rgba('#' . $settings->block_bg_color, $settings->block_bg_color_opc / 100 ) : 'transparent'; ?>;
		<?php } ?>
		<?php if ( 'gradient' == $settings->block_bg_type && '' != $settings->block_primary_color && '' != $settings->block_secondary_color ) { ?>
			background: <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?>; /* Old browsers */
			background: -moz-linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* FF3.6-15 */
			background: -webkit-linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* Chrome10-25,Safari5.1-6 */
			background: linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?>', endColorstr='<?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?>',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
		<?php } ?>
		<?php if ( 'yes' == $settings->show_block_shadow ) { ?>
			-webkit-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
				-moz-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
					-o-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
						box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
		<?php } ?>
		padding:<?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo $settings->block_width / 5; } else { echo 100 / 5; } ?>px;
		<?php if ( isset( $settings->label_position ) && 'default' == $settings->label_position || 'outside' == $settings->label_position ) { ?>
		display: flex;
		justify-content: center;
		align-items: center;
		<?php } if ( isset( $settings->label_position ) && 'inside' == $settings->label_position ) { ?>
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
		<?php } ?>
	}
<?php }

if ( isset( $settings->block_style ) && 'square' == $settings->block_style ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit-wrapper.square {
		width: <?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo  $settings->block_width; } else { echo 100; }?>px;
		height: <?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo  $settings->block_width; } else { echo 100; }?>px;
		border: <?php if ( isset( $settings->block_border_width ) && '' != $settings->block_border_width ) { echo $settings->block_border_width; } else { echo 5 ; } ?>px <?php if ( isset( $settings->block_border_style ) ) { echo $settings->block_border_style; } ?> <?php if ( isset( $settings->block_border_color ) ) { echo '#' . $settings->block_border_color; } ?>;
		border-radius: <?php if ( isset( $settings->block_border_radius ) && '' != $settings->block_border_radius ) { echo $settings->block_border_radius; } else { echo 5 ; } ?>px;
		<?php if ( 'solid' == $settings->block_bg_type && '' != $settings->block_bg_color ) { ?>
			background: <?php echo ( $settings->block_bg_color ) ? pp_hex2rgba('#' . $settings->block_bg_color, $settings->block_bg_color_opc / 100 ) : 'transparent'; ?>;
		<?php } ?>
		<?php if ( 'gradient' == $settings->block_bg_type && '' != $settings->block_primary_color && '' != $settings->block_secondary_color ) { ?>
			background: <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?>; /* Old browsers */
			background: -moz-linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* FF3.6-15 */
			background: -webkit-linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* Chrome10-25,Safari5.1-6 */
			background: linear-gradient(45deg, <?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?> 0%, <?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?> 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo pp_hex2rgba('#' . $settings->block_primary_color, $settings->block_bg_color_opc / 100 ); ?>', endColorstr='<?php echo pp_hex2rgba('#' . $settings->block_secondary_color, $settings->block_bg_color_opc / 100 ); ?>',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
		<?php } ?>
		<?php if ( 'yes' == $settings->show_block_shadow ) { ?>
			-webkit-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
				-moz-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
					-o-box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
						box-shadow: <?php echo $settings->block_shadow['horizontal']; ?>px <?php echo $settings->block_shadow['vertical']; ?>px <?php echo $settings->block_shadow['blur']; ?>px <?php echo $settings->block_shadow['spread']; ?>px <?php echo ( false === strpos( $settings->block_shadow_color, 'rgb' ) ) ? '#' . $settings->block_shadow_color : $settings->block_shadow_color; ?>;
		<?php } ?>
		padding:<?php if ( isset( $settings->block_width ) && '' != $settings->block_width ) { echo $settings->block_width / 4; } else { echo 100 / 4; }?>px;
	}
<?php }
if ( isset( $settings->block_style ) && 'default' != $settings->block_style && isset( $settings->label_position ) && 'inside' == $settings->label_position && isset( $settings->label_inside_position ) && ( 'in_below' == $settings->label_inside_position || 'in_above' == $settings->label_inside_position ) ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit-content {
		<?php if ( isset( $settings->label_inside_position ) && 'in_below' == $settings->label_inside_position ) { ?>
		margin-bottom: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>

	}
	.fl-node-<?php echo $id;?> .pp-countdown-digit {
		<?php if ( isset( $settings->label_inside_position ) && 'in_above' == $settings->label_inside_position ) { ?>
		margin-top: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
	}
<?php }
if ( isset( $settings->block_style ) && 'default' != $settings->block_style && isset( $settings->label_position ) && 'outside' == $settings->label_position && isset( $settings->label_outside_position ) && ( 'out_above' == $settings->label_outside_position || 'out_below' == $settings->label_outside_position || 'out_right' == $settings->label_outside_position || 'out_left' == $settings->label_outside_position ) ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit-wrapper {
		<?php if ( isset( $settings->label_outside_position ) && 'out_below' == $settings->label_outside_position ) { ?>
		margin-bottom: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
		<?php if ( isset( $settings->label_outside_position ) && 'out_above' == $settings->label_outside_position ) { ?>
		margin-top: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
		<?php if ( isset( $settings->label_outside_position ) && 'out_right' == $settings->label_outside_position ) { ?>
		margin-right: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
		<?php if ( isset( $settings->label_outside_position ) && 'out_left' == $settings->label_outside_position ) { ?>
		margin-left: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
	}
<?php }
if ( isset( $settings->block_style ) && 'default' == $settings->block_style && isset( $settings->default_position ) && ( 'normal_below' == $settings->default_position || 'normal_above' == $settings->default_position ) ) {	?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit {
		<?php if ( isset( $settings->default_position ) && 'normal_below' == $settings->default_position ) { ?>
			margin-bottom: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
		<?php if ( isset( $settings->default_position ) && 'normal_above' == $settings->default_position ) { ?>
			margin-top: <?php if ( isset( $settings->digit_label_spacing ) && '' != $settings->digit_label_spacing ) { echo $settings->digit_label_spacing; } else { echo 10; } ?>px;
		<?php } ?>
	}
<?php
}

if( 'no' == $settings->show_labels ) { ?>
	.fl-node-<?php echo $id;?> .pp-countdown-digit,
	.fl-node-<?php echo $id;?> .pp-countdown-digit-content,
	.fl-node-<?php echo $id;?> .pp-countdown-digit-wrapper {
		margin: 0;
	}
<?php }


/* Typography style Assign CSS for message after expires*/
if ( ( isset( $settings->fixed_timer_action ) && 'msg' == $settings->fixed_timer_action ) || ( isset( $settings->evergreen_timer_action ) && 'msg' == $settings->evergreen_timer_action ) ) {
?>
.fl-node-<?php echo $id;?> .pp-countdown-expire-message {
	<?php if ( isset( $settings->message_font_family['family'] ) && 'Default' != $settings->message_font_family['family'] ) : ?>
		<?php FLBuilderFonts::font_css( $settings->message_font_family ); ?>
	<?php endif; ?>
	<?php if ( 'custom' == $settings->message_font_size && '' != $settings->message_custom_font_size ) : ?>
		font-size: <?php echo $settings->message_custom_font_size; ?>px;
	<?php endif; ?>
	<?php if ( isset( $settings->message_line_height ) && '' != $settings->message_line_height ) : ?>
		line-height: <?php if ( isset( $settings->message_line_height ) ) { echo $settings->message_line_height; } ?>;
	<?php endif; ?>
	<?php if ( isset( $settings->message_color ) && '' != $settings->message_color ) : ?>
		color: <?php echo '#' . $settings->message_color; ?>;
	<?php endif; ?>
}
<?php
}

/* Typography style starts here  */
if ( ( isset( $settings->digit_font_family['family'] ) && 'Default' != $settings->digit_font_family['family'] ) || ( '' != $settings->digit_custom_font_size ) || ( '' != $settings->digit_line_height ) || ( isset( $settings->digit_color ) && '' != $settings->digit_color ) ) { ?>

	.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-digit,
	.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-digit {

		<?php if ( isset( $settings->digit_font_family['family'] ) && 'Default' != $settings->digit_font_family['family'] ) : ?>
			<?php FLBuilderFonts::font_css( $settings->digit_font_family ); ?>
		<?php endif; ?>
		<?php if ( 'custom' == $settings->digit_font_size  && '' != $settings->digit_custom_font_size ) : ?>
			font-size: <?php echo $settings->digit_custom_font_size; ?>px;
		<?php endif; ?>
		<?php if ( '' != $settings->digit_line_height ) : ?>
			line-height: <?php echo $settings->digit_line_height; ?>;
		<?php endif; ?>
		<?php if ( isset( $settings->digit_color ) && '' != $settings->digit_color ) : ?>
			color: <?php echo '#' . $settings->digit_color; ?>;
		<?php endif; ?>
	}
<?php } ?>

<?php if ( ( isset( $settings->label_font_family['family'] ) && 'Default' != $settings->label_font_family['family'] ) || ( isset( $settings->label_font_size ) && '' != $settings->label_font_size ) || ( isset( $settings->label_line_height ) && '' != $settings->label_line_height ) || ( isset( $settings->label_color ) && '' != $settings->label_color ) ) { ?>

	.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-label,
	.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-label {
		<?php if ( isset( $settings->label_font_family['family'] ) && 'Default' != $settings->label_font_family['family'] ) : ?>
			<?php FLBuilderFonts::font_css( $settings->label_font_family ); ?>
		<?php endif; ?>
		<?php if ( 'custom' == $settings->label_font_size && '' != $settings->label_custom_font_size ) : ?>
			font-size: <?php echo $settings->label_custom_font_size; ?>px;
		<?php endif; ?>
		<?php if ( '' != $settings->label_line_height ) : ?>
			line-height: <?php echo $settings->label_line_height; ?>;
		<?php endif; ?>
		<?php if ( isset( $settings->label_color ) && '' != $settings->label_color ) : ?>
			color: <?php echo '#' . $settings->label_color; ?>;
		<?php endif; ?>
		<?php if ( isset( $settings->label_letter_spacing ) && '' != $settings->label_letter_spacing ) : ?>
			letter-spacing: <?php echo $settings->label_letter_spacing; ?>px;
		<?php endif; ?>
		<?php if ( 'default' != $settings->label_text_transform ) : ?>
			text-transform: <?php echo $settings->label_text_transform; ?>;
		<?php endif; ?>
		<?php if ( isset( $settings->label_bg_color ) && '' != $settings->label_bg_color ) : ?>
			background-color: <?php echo ( false === strpos( $settings->label_bg_color, 'rgb' ) ) ? '#' . $settings->label_bg_color : $settings->label_bg_color; ?>;
		<?php endif; ?>
		<?php if ( isset( $settings->label_vertical_padding ) && '' != $settings->label_vertical_padding ) : ?>
			padding-top: <?php echo $settings->label_vertical_padding; ?>px;
			padding-bottom: <?php echo $settings->label_vertical_padding; ?>px;
		<?php endif; ?>
		<?php if ( isset( $settings->label_horizontal_padding ) && '' != $settings->label_horizontal_padding ) : ?>
			padding-left: <?php echo $settings->label_horizontal_padding; ?>px;
			padding-right: <?php echo $settings->label_horizontal_padding; ?>px;
		<?php endif; ?>
	}
<?php } ?>


/* Media Queries */

@media only screen and ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {

	<?php if ( 'custom' == $settings->digit_font_size || '' != $settings->digit_line_height_medium ) { ?>
		.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-digit,
		.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-digit {
			<?php if ( 'custom' == $settings->digit_font_size && '' != $settings->digit_custom_font_size_medium ) : ?>
				font-size: <?php echo $settings->digit_custom_font_size_medium; ?>px;
			<?php endif; ?>
			<?php if ( '' != $settings->digit_line_height_medium ) : ?>
				line-height: <?php echo $settings->digit_line_height_medium; ?>;
			<?php endif; ?>
		}
	<?php } ?>

	<?php if ( ( 'custom' == $settings->label_font_size && '' != $settings->label_custom_font_size_medium ) || '' != $settings->label_line_height_medium ) { ?>
		.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-label,
		.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-label {
			<?php if ( 'custom' == $settings->label_font_size && '' != $settings->label_custom_font_size_medium ) : ?>
				font-size: <?php echo $settings->label_custom_font_size_medium; ?>px;
			<?php endif; ?>
			<?php if ( '' != $settings->label_line_height_medium ) : ?>
				line-height: <?php echo $settings->label_line_height_medium; ?>;
			<?php endif; ?>
		}
	<?php } ?>

	<?php if ( ( 'custom' == $settings->message_font_size && '' != $settings->message_custom_font_size_medium ) || ( isset( $settings->message_line_height_medium ) && '' != $settings->message_line_height_medium ) ) {
		if ( ( isset( $settings->evergreen_timer_action ) && 'msg' == $settings->evergreen_timer_action ) || ( isset( $settings->fixed_timer_action ) && 'msg' == $settings->fixed_timer_action ) ) { ?>
			.fl-node-<?php echo $id;?> .pp-countdown-expire-message {
				<?php if ( isset( $settings->message_custom_font_size_medium ) && '' != $settings->message_custom_font_size_medium ) : ?>
					font-size: <?php echo $settings->message_custom_font_size_medium; ?>px;
				<?php endif; ?>
				<?php if ( isset( $settings->message_line_height_medium ) && '' != $settings->message_line_height_medium ) : ?>
					line-height: <?php echo $settings->message_line_height_medium; ?>;
				<?php endif; ?>
			}
			<?php
		}
	} ?>
}

@media only screen and ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {

	<?php if( 'default' != $settings->responsive_counter_alignment ) { ?>
		.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer,
		.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer {
			text-align: <?php echo $settings->responsive_counter_alignment; ?>;
		}
	<?php } ?>

	<?php if ( ( 'custom' == $settings->digit_font_size && '' != $settings->digit_custom_font_size_responsive ) || ( '' != $settings->digit_line_height_responsive ) ) { ?>
		.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-digit,
		.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-digit {
			<?php if ( 'custom' == $settings->digit_font_size && '' != $settings->digit_custom_font_size_responsive ) : ?>
				font-size: <?php echo $settings->digit_custom_font_size_responsive; ?>px;
			<?php endif; ?>
			<?php if ( '' != $settings->digit_line_height_responsive ) : ?>
				line-height: <?php echo $settings->digit_line_height_responsive; ?>;
			<?php endif; ?>
		}
	<?php } ?>

	<?php if ( ( 'custom' == $settings->label_font_size && '' != $settings->label_custom_font_size_responsive ) || ( '' != $settings->label_line_height_responsive ) ) { ?>
		.fl-node-<?php echo $id;?> .pp-countdown-fixed-timer .pp-countdown-label,
		.fl-node-<?php echo $id;?> .pp-countdown-evergreen-timer .pp-countdown-label {
			<?php if ( 'custom' == $settings->label_font_size && '' != $settings->label_custom_font_size_responsive ) : ?>
				font-size: <?php echo $settings->label_custom_font_size_responsive; ?>px;
			<?php endif; ?>
			<?php if ( '' != $settings->label_line_height_responsive ) : ?>
				line-height: <?php echo $settings->label_line_height_responsive; ?>;
			<?php endif; ?>
		}
	<?php } ?>

	<?php if ( '' != $settings->message_custom_font_size_responsive || '' != $settings->message_line_height_responsive ) {
		if ( ( isset( $settings->evergreen_timer_action ) && 'msg' == $settings->evergreen_timer_action ) || ( isset( $settings->fixed_timer_action ) && 'msg' == $settings->fixed_timer_action ) ) { ?>
			.fl-node-<?php echo $id;?> .pp-countdown-expire-message {
				<?php if ( 'custom' == $settings->message_font_size && '' != $settings->message_custom_font_size_responsive ) : ?>
					font-size: <?php echo $settings->message_custom_font_size_responsive; ?>px;
				<?php endif; ?>
				<?php if ( isset( $settings->message_line_height_responsive ) && '' != $settings->message_line_height_responsive ) : ?>
					line-height: <?php echo $settings->message_line_height_responsive; ?>;
				<?php endif; ?>
			}
		<?php }
	} ?>

	<?php if( 'yes' == $settings->hide_separator ) { ?>
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-colon .pp-countdown-item:after,
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-line .pp-countdown-item:after {
			display: none;
		}
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-line .pp-countdown-item,
		.fl-node-<?php echo $id; ?> .pp-countdown.pp-countdown-separator-colon .pp-countdown-item {
			padding-left: <?php echo $settings->block_spacing; ?>px;
			padding-right: <?php echo $settings->block_spacing; ?>px;
		}
	<?php } ?>
}
