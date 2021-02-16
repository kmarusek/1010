var pp_feed_<?php echo $id; ?>;
(function($) {
	var layout 			= '<?php echo $settings->feed_layout; ?>',
		likes 				= '<?php echo $settings->likes; ?>',
		comments 			= '<?php echo $settings->comments; ?>',
		popup				= '<?php echo $settings->image_popup; ?>',
		custom_size			= '<?php echo $settings->image_custom_size; ?>',
		carouselOpts		= {
			direction				: 'horizontal',
			slidesPerView			: <?php echo absint( $settings->visible_items ); ?>,
			spaceBetween			: <?php echo $settings->images_gap; ?>,
			autoplay				: <?php echo 'yes' == $settings->autoplay ? $settings->autoplay_speed : 'false'; ?>,
			<?php if ( 'yes' == $settings->autoplay ) { ?>
			autoplay				: {
				delay: <?php echo $settings->autoplay_speed; ?>,
			},
			<?php } else { ?>
			autoplay				: false,
			<?php } ?>
			grabCursor				: <?php echo 'yes' == $settings->grab_cursor ? 'true' : 'false'; ?>,
			loop					: <?php echo 'yes' == $settings->infinite_loop ? 'true' : 'false'; ?>,
			pagination				: {
				el: '.fl-node-<?php echo $id; ?> .swiper-pagination',
				clickable: true
			},
			navigation				: {
				prevEl: '.fl-node-<?php echo $id; ?> .swiper-button-prev',
				nextEl: '.fl-node-<?php echo $id; ?> .swiper-button-next'
			},
			breakpoints: {
				<?php echo $global_settings->medium_breakpoint; ?>: {
					slidesPerView:  <?php echo ( $settings->visible_items_medium ) ? absint( $settings->visible_items_medium ) : 2; ?>,
					spaceBetween:   <?php echo ( '' != $settings->images_gap_medium ) ? $settings->images_gap_medium : 10; ?>,
				},
				<?php echo $global_settings->responsive_breakpoint; ?>: {
					slidesPerView:  <?php echo ( $settings->visible_items_responsive ) ? absint( $settings->visible_items_responsive ) : 1; ?>,
					spaceBetween:   <?php echo ( '' != $settings->images_gap_responsive ) ? $settings->images_gap_responsive : 10; ?>,
				},
			}
		};

	pp_feed_<?php echo $id; ?> = new PPInstagramFeed({
		id: '<?php echo $id; ?>',
		layout: '<?php echo $settings->feed_layout; ?>',
		limit: <?php echo ! empty ( $settings->images_count ) ? $settings->images_count : 8; ?>,
		/*
		likes_count: <?php echo 'yes' == $settings->likes ? 'true' : 'false'; ?>,
		comments_count: <?php echo 'yes' == $settings->comments ? 'true' : 'false'; ?>,
		*/
		on_click: '<?php echo $settings->image_popup; ?>',
		carousel: carouselOpts,
		image_size: <?php echo ! empty( $settings->image_custom_size ) ? $settings->image_custom_size : '0'; ?>,
		isBuilderActive: <?php echo FLBuilderModel::is_builder_active() ? 'true' : 'false'; ?>,
	});

	<?php if ( 'yes' == $settings->image_popup ) { ?>
		$('.fl-node-<?php echo $id; ?> .pp-instagram-feed').magnificPopup({
			delegate: '.pp-feed-item a',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0, 1]
			},
			type: 'image'
		});
	<?php } ?>

})(jQuery);
