;(function($) {

	<?php if ( 'lightbox' == $settings->click_action ) : ?>

		<?php
		if ( 'lightbox' == $settings->click_action && 'no' == $settings->show_lightbox_thumb ) {
			$selector = '.fancybox-button';
		} elseif ( 'yes' == $settings->show_lightbox_thumb ) {
			$selector = '.fancybox-thumb';
		}
		?>

		$(".fl-node-<?php echo $id; ?> .fancybox-button").fancybox({
			closeBtn		: true,
			closeClick		: true,
			modal			: false,
			wrapCSS			: 'fancybox-<?php echo $id; ?>',
			helpers		: {
				title	: { type : 'inside' },
				<?php if ( 'yes' == $settings->show_lightbox_thumb ) { ?>
					thumbs	: {
						width	: 50,
						height	: 50
					},
				<?php } ?>
			},
			afterLoad: function(current, previous) {
				$(".fancybox-<?php echo $id; ?>").parent().addClass('fancybox-<?php echo $id; ?>-overlay');
			}
		});

	<?php endif; ?>

	$(".fl-node-<?php echo $id; ?> .pp-photo-gallery-item, .fl-node-<?php echo $id; ?> .pp-gallery-masonry-item").find('.pp-photo-gallery-caption-below').parent().addClass('has-caption');

	<?php
	$row_height = '' == $settings->row_height ? 0 : $settings->row_height;
	$max_row_height = '' == $settings->max_row_height ? $row_height : $settings->row_height;
	?>

	new PPGallery({
		id: '<?php echo $id ?>',
		layout: '<?php echo $settings->gallery_layout; ?>',
		spacing: <?php echo '' == $settings->justified_spacing ? 0 : $settings->justified_spacing; ?>,
		rowheight: <?php echo $row_height; ?>,
		maxrowheight: <?php echo $max_row_height; ?>,
		lastrow: '<?php echo $settings->last_row; ?>',
	});
})(jQuery);
