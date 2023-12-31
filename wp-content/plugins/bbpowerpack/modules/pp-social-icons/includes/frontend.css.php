<?php
	// Icon - Spacing
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'spacing',
		'selector'		=> ".fl-node-$id .pp-social-icons-vertical .pp-social-icon",
		'prop'			=> 'margin-bottom',
		'unit'			=> 'px',
	) );

	// Icon - Spacing - Horizontal left aligned
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'spacing',
		'selector'		=> ".fl-node-$id .pp-social-icons-horizontal .pp-social-icon",
		'prop'			=> 'margin-right',
		'unit'			=> 'px',
	) );

	$breakpoints = array( '', 'large', 'medium', 'responsive' );
	$alignments  = array(
		'left'   => 'flex-start',
		'center' => 'center',
		'right'  => 'flex-end',
	);

	$align_settings = new stdClass;

	foreach ( $breakpoints as $breakpoint ) {
		$suffix       = empty( $breakpoint ) ? '' : "_{$breakpoint}";
		$setting_name = 'align' . $suffix;
		if ( isset( $settings->{$setting_name} ) && ! empty( $settings->{$setting_name} ) ) {
			$align_settings->{$setting_name} = $alignments[ $settings->{$setting_name} ];
		}
	}

	// Icon - Align
	FLBuilderCSS::responsive_rule( array(
		'settings'     => $align_settings,
		'setting_name' => 'align',
		'selector'     => ".fl-node-$id .pp-social-icons",
		'prop'         => $settings->direction == 'horizontal' ? 'justify-content' : 'align-items'
	) );
?>

.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon a,
.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon a:hover {
	text-decoration: none;
}

.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon a {
	display: inline-block;
	float: left;
	text-align: center;
	<?php if ( ! empty( $settings->color ) ) { ?>
		color: #<?php echo $settings->color; ?>;
	<?php } ?>
	<?php if ( isset( $settings->bg_color ) && ! empty( $settings->bg_color ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->bg_color ); ?>;
	<?php } ?>
	<?php if ( '' !== $settings->radius ) { ?>
		border-radius: <?php echo $settings->radius; ?>px;
	<?php } ?>
	<?php if ( '' !== $settings->border_width ) { ?>
		border: <?php echo $settings->border_width; ?>px solid #<?php echo $settings->border_color; ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon a:hover {
	<?php if ( ! empty( $settings->hover_color ) ) { ?>
		color: #<?php echo $settings->hover_color; ?>;
	<?php } ?>
	<?php if ( isset( $settings->bg_hover_color ) && ! empty( $settings->bg_hover_color ) ) { ?>
		background-color: <?php echo pp_get_color_value( $settings->bg_hover_color ); ?>;
	<?php } ?>
	<?php if ( '' !== $settings->border_width ) { ?>
		border-color: #<?php echo $settings->border_hover_color; ?>;
	<?php } ?>
}

<?php
	// Icon - Size
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'size',
		'selector'		=> ".fl-node-$id .fl-module-content .pp-social-icon a",
		'prop'			=> 'font-size',
		'unit'			=> 'px',
	) );

	// Icon - Box Size
	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'box_size',
		'selector'		=> ".fl-node-$id .fl-module-content .pp-social-icon a",
		'prop'			=> 'width',
		'unit'			=> 'px',
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'box_size',
		'selector'		=> ".fl-node-$id .fl-module-content .pp-social-icon a",
		'prop'			=> 'height',
		'unit'			=> 'px',
	) );

	FLBuilderCSS::responsive_rule( array(
		'settings'		=> $settings,
		'setting_name'	=> 'box_size',
		'selector'		=> ".fl-node-$id .fl-module-content .pp-social-icon a",
		'prop'			=> 'line-height',
		'unit'			=> 'px',
	) );
?>

<?php foreach ( $settings->icons as $i => $icon ) : ?>
	<?php if ( $icon->border_width !== '' ) : ?>
		.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon:nth-child(<?php echo $i + 1; ?>) a {
			<?php if ( '' !== $icon->border_width ) { ?>
				border: <?php echo $icon->border_width; ?>px solid #<?php echo $icon->border_color; ?>;
			<?php } ?>
		}
		.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon:nth-child(<?php echo $i + 1; ?>) a:hover {
			<?php if ( ! empty( $icon->border_hover_color ) ) { ?>
				border-color: #<?php echo $icon->border_hover_color; ?>;
			<?php } ?>
		}
	<?php endif; ?>
	<?php if ( isset( $icon->color ) || isset( $icon->bg_color ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon:nth-child(<?php echo $i + 1; ?>) a {
			<?php if ( ! empty( $icon->color ) ) { ?>
				color: #<?php echo $icon->color; ?>;
			<?php } ?>
			<?php if ( isset( $icon->bg_color ) && ! empty( $icon->bg_color ) ) { ?>
				background-color: <?php echo pp_get_color_value( $icon->bg_color ); ?>;
			<?php } ?>
		}
	<?php endif; ?>
	<?php if ( isset( $icon->hover_color ) || isset( $icon->bg_hover_color ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon:nth-child(<?php echo $i + 1; ?>) i:hover,
		.fl-node-<?php echo $id; ?> .fl-module-content .pp-social-icon:nth-child(<?php echo $i + 1; ?>) a:hover i {
			<?php if ( ! empty( $icon->hover_color ) ) { ?>
				color: #<?php echo $icon->hover_color; ?>;
			<?php } ?>
			<?php if ( isset( $icon->bg_hover_color ) && ! empty( $icon->bg_hover_color ) ) { ?>
				background-color: <?php echo pp_get_color_value( $icon->bg_hover_color ); ?>;
			<?php } ?>
		}
	<?php endif; ?>
<?php endforeach; ?>