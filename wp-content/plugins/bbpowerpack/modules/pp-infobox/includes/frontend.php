<?php
$layout 		= $settings->layouts;
$wrap_class 	= 'pp-infobox-wrap';
$main_class 	= 'pp-infobox layout-' . $layout;
$button_class 	= ( 'button' == $settings->pp_infobox_link_type && '' != $settings->link_css_class ) ? ' ' . $settings->link_css_class : '';

?>
<div class="<?php echo $wrap_class; ?>">
	<?php
	if( $settings->pp_infobox_link_type == 'box' ) { ?>
		<a class="pp-more-link" href="<?php echo $settings->link; ?>" target="<?php echo $settings->link_target; ?>">
	<?php }
	
	include apply_filters( 'pp_infobox_layout_path', $module->dir . 'includes/layout-' . $layout . '.php', $layout, $settings );
	
	if( $settings->pp_infobox_link_type == 'box' ) { ?>
		</a>
	<?php } ?>
</div>
