<?php
$class = 'carousel' == $settings->layout ? 'pp-content-carousel-post' : 'pp-content-grid-post';
?>

<div <?php post_class('pp-content-post ' . $class . ' pp-grid-' . $settings->post_grid_style_select); ?> itemscope itemtype="<?php PPContentGridModule::schema_itemtype(); ?>" data-id="<?php echo get_the_ID(); ?>">

	<?php
	
	PPContentGridModule::schema_meta();

	$custom_layout = $settings->custom_layout;
	$custom_layout = (array) $custom_layout;

	echo do_shortcode( FLThemeBuilderFieldConnections::parse_shortcodes( stripslashes( $custom_layout['html'] ) ) );
	
	?>

</div>