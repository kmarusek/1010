;(function($) {

<?php if ( 'yes' == $settings->dual_pricing ) : ?>
	$('.fl-node-<?php echo $id; ?> .pp-pricing-table .pp-pricing-table-button').on('click', function(e) {
		e.preventDefault();

		var activePrice = $(this).data('activate-price');

		$(this).parent('.pp-pricing-table-buttons').find('.pp-pricing-table-button').removeClass('pp-pricing-button-active');
		$(this).addClass( 'pp-pricing-button-active' );

		if ( 'primary' === activePrice ) {
			$('.fl-node-<?php echo $id; ?> .pp-pricing-table-price.pp-price-primary').show();
			$('.fl-node-<?php echo $id; ?> .pp-pricing-table-price.pp-price-secondary').hide();
		}
		if ( 'secondary' === activePrice ) {
			$('.fl-node-<?php echo $id; ?> .pp-pricing-table-price.pp-price-primary').hide();
			$('.fl-node-<?php echo $id; ?> .pp-pricing-table-price.pp-price-secondary').show();
		}

		$('.fl-node-<?php echo $id; ?> .pp-pricing-package-button').each(function() {
			var link = $(this).data( activePrice + '-link' );
			$(this).attr('href', link);
		});
	});
<?php endif; ?>

})(jQuery);