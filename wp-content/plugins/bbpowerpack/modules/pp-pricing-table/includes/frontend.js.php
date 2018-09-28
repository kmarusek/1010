;(function($) {

	new PPPricingTable({
		id: '<?php echo $id; ?>',
		dualPricing: <?php echo ( 'yes' == $settings->dual_pricing ) ? 'true' : 'false'; ?>
	});

})(jQuery);