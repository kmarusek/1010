;(function($) {

	<?php if($settings->click_action == 'lightbox') : ?>
	$(window).load(function() {
		var gallery_selector = $( '.fl-node-<?php echo $id; ?> .pp-photo-gallery' );
		if( gallery_selector.length && typeof $.fn.magnificPopup !== 'undefined') {
			gallery_selector.magnificPopup({
				delegate: '.pp-gallery-item:visible .pp-photo-gallery-content a',
				closeBtnInside: true,
				type: 'image',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
				},
				'image': {
					titleSrc: function(item) {
						<?php if( isset( $settings->lightbox_caption ) && $settings->lightbox_caption == 'yes' ) : ?> 
							<?php if($settings->show_captions == 'below') : ?>
								return item.el.parents('.pp-gallery-item').find('.pp-photo-gallery-caption').html();
							<?php elseif($settings->show_captions == 'hover') : ?>
								return item.el.parent().find('.pp-caption').html();
							<?php endif; ?>
						<?php else: ?>
							return '';
						<?php endif; ?>
					}
				},
				mainClass: 'mfp-<?php echo $id; ?>'
			});
		}
	});
	<?php endif; ?>

	var options = {
		id: '<?php echo $id ?>',
		layout: '<?php echo $settings->gallery_layout; ?>',
	};

	new PPFilterableGallery(options);

	// expandable row fix.
	var state = 0;
	$(document).on('pp_expandable_row_toggle', function(e, selector) {
		if ( selector.is('.pp-er-open') && state === 0 ) {
			new PPFilterableGallery(options);
			state = 1;
		}
	});

})(jQuery);
