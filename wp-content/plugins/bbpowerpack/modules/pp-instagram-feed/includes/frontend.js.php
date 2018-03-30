(function($) {
	var layout 			= '<?php echo $settings->feed_layout; ?>',
	likes 				= '<?php echo $settings->likes; ?>',
	comments 			= '<?php echo $settings->comments; ?>',
	popup				= '<?php echo $settings->image_popup; ?>',
	custom_size			= '<?php echo $settings->image_custom_size; ?>',
	like_span           = (likes === 'yes') ? '<span class="likes"><i class="fa fa-heart"></i> {{likes}}</span>' : '',
	comments_span       = (comments === 'yes') ? '<span class="comments"><i class="fa fa-comment"></i> {{comments}}</span>' : '',

	feed = new Instafeed({
		get: '<?php echo ( 'yes' == $settings->feed_by_tags ) ? 'tagged' : 'user'; ?>',
		target: 'pp-instagram-<?php echo $id; ?>',
		accessToken: '<?php echo $settings->access_token; ?>',
		userId: '<?php echo $settings->user_id; ?>',
		clientId: '<?php echo $settings->client_id; ?>',
		<?php if ( 'yes' == $settings->feed_by_tags ) { ?>
			tagName: '<?php echo $settings->tag_name; ?>',
		<?php } ?>
		resolution: '<?php echo $settings->image_resolution; ?>',
		limit: <?php echo $settings->images_count; ?>,
		sortBy: '<?php echo $settings->sort_by; ?>',
		
		template:  function () {
			if ('carousel' === layout) {
					return '<div class="pp-feed-item swiper-slide">' +
							<?php if( ! empty( $settings->image_custom_size ) ) { ?>
								'<div class="pp-feed-item-inner" style="background-image: url({{image}})">' +
							<?php } ?>
							<?php if ( 'yes' == $settings->image_popup ) { ?>
								'<a href="{{image}}">' +
							<?php } ?>
							'<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
							<?php if( empty( $settings->image_custom_size ) ) { ?>
								'<img src="{{image}}" />' +
							<?php } ?>
							<?php if ( 'yes' == $settings->image_popup ) { ?>
								'</a>' +
							<?php } ?>
							<?php if( ! empty( $settings->image_custom_size ) ) { ?>
								'</div>' +
							<?php } ?>
							'</div>';
				} else {
					return '<div class="pp-feed-item">' +
							<?php if( 'square-grid' == $settings->feed_layout && ! empty( $settings->image_custom_size ) ) { ?>
								'<div class="pp-feed-item-inner" style="background-image: url({{image}})">' +
							<?php } ?>
							<?php if ( 'yes' == $settings->image_popup ) { ?>
								'<a href="{{image}}">' +
							<?php } ?>
							'<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
							<?php if( 'square-grid' != $settings->feed_layout || empty( $settings->image_custom_size ) ) { ?>
								'<img src="{{image}}" />' +
							<?php } ?>
							<?php if ( 'yes' == $settings->image_popup ) { ?>
								'</a>' +
							<?php } ?>
							<?php if( 'square-grid' == $settings->feed_layout && ! empty( $settings->image_custom_size ) ) { ?>
								'</div>' +
							<?php } ?>
							'</div>';
				}
		}(),
		after: function () {
			if ('carousel' === layout) {

					mySwiper = new Swiper( '.pp-instagram-feed-carousel .swiper-container', {
						direction				: 'horizontal',
						slidesPerView			: <?php echo absint( $settings->visible_items ); ?>,
						spaceBetween			: <?php echo $settings->images_gap; ?>,
						autoplay				: <?php echo 'yes' == $settings->autoplay ? $settings->autoplay_speed : 'false'; ?>,
						grabCursor				: <?php echo 'yes' == $settings->grab_cursor ? 'true' : 'false'; ?>,
						loop					: <?php echo 'yes' == $settings->infinite_loop ? 'true' : 'false'; ?>,
						pagination				: '.swiper-pagination',
						paginationClickable		: true,
						nextButton				: '.swiper-button-next',
						prevButton				: '.swiper-button-prev',
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
					});
			} else if( 'grid' === layout ) {
				var $grid = $('#pp-instagram-<?php echo $id; ?>').imagesLoaded( function() {
					$grid.masonry({
						itemSelector: '.pp-feed-item',
						percentPosition: true
					});
				});
			}
		}
	});
	feed.run();

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
