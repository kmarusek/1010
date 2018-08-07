(function($) {
	
	$( document ).ready(function() {

	    new UABBWooProducts({
	    	id: '<?php echo $id ?>',
	    	ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>",
	    	layout: "<?php echo $settings->layout; ?>",
	    	skin: "<?php echo $settings->skin; ?>",
	    	
	    	/* Slider */
	    	infinite: <?php echo ( $settings->infinite_loop == 'yes' ) ? 'true' : 'false'; ?>,
	    	dots: <?php echo ( $settings->enable_dots == 'yes' ) ? 'true' : 'false'; ?>,
	    	arrows: <?php echo ( $settings->enable_arrow == 'yes' ) ? 'true' : 'false'; ?>,
	    	desktop: <?php echo $settings->slider_columns_new; ?>,
	    	medium: <?php echo $settings->slider_columns_new_medium; ?>,
	    	small: <?php echo $settings->slider_columns_new_responsive; ?>,
			slidesToScroll: <?php echo ( $settings->slides_to_scroll != '' ) ? $settings->slides_to_scroll : 1; ?>,
			autoplay: <?php echo ( $settings->autoplay == 'yes' ) ? 'true' : 'false'; ?>,
	  		autoplaySpeed: <?php echo ( $settings->animation_speed != '' ) ? $settings->animation_speed : '1000'; ?>,
	  		small_breakpoint: <?php echo $global_settings->responsive_breakpoint; ?>,
	  		medium_breakpoint: <?php echo $global_settings->medium_breakpoint; ?>,
	  		module_settings: <?php echo json_encode( $settings ); ?>
	    });

	});

})(jQuery);