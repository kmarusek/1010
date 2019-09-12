(function($) {

	$(function() {
		<?php
			if ( 'first' == $settings->expand_option ) {
				$defaultItem = '1';
			} elseif ( 'custom' == $settings->expand_option ) {
				$defaultItem = ( absint ( $settings->open_custom ) > 0 ? absint ( $settings->open_custom ) : 'false' );
			} else {
				$defaultItem = 'all';
			}
		?>
		new PPFAQ({
			id: '<?php echo $id ?>',
			defaultItem: '<?php echo $defaultItem; ?>',
			responsiveCollapse: <?php echo ( isset( $settings->responsive_collapse ) && 'yes' == $settings->responsive_collapse ) ? 'true' : 'false'; ?>
		});
	});

})(jQuery);
