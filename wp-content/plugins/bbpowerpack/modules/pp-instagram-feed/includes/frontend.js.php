(function($) {
	var layout 			= '<?php echo $settings->feed_layout; ?>',
	likes 				= '<?php echo $settings->likes; ?>',
	comments 			= '<?php echo $settings->comments; ?>',
	popup			= '<?php echo $settings->image_popup; ?>',
	like_span           = (likes === 'yes') ? '<span class="likes"><i class="fa fa-heart"></i> {{likes}}</span>' : '',
	comments_span       = (comments === 'yes') ? '<span class="comments"><i class="fa fa-comment"></i> {{comments}}</span>' : '',

	feed = new Instafeed({
		get: 'user',
		target: 'pp-instagram-<?php echo $id; ?>',
		clientId: '<?php echo $settings->client_id; ?>',
		userId: '<?php echo $settings->user_id; ?>',
		accessToken: '<?php echo $settings->access_token; ?>',
		resolution: '<?php echo $settings->image_resolution; ?>',
		limit: <?php echo $settings->images_count; ?>,
		sortBy: '<?php echo $settings->sort_by; ?>',
		template:  function () {
			if ('yes' === popup) {
				if ('carousel' === layout) {
					return '<div class="pp-feed-item swiper-slide"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
				} else {
					return '<div class="pp-feed-item"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
				}
			} else {
				if ('carousel' === layout) {
					return '<div class="pp-feed-item swiper-slide">' +
						'<a href="{{link}}">' +
							'<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
							'<img src="{{image}}" />' +
						'</a>' +
						'</div>';
				} else {
					return '<div class="pp-feed-item">' +
						'<a href="{{link}}">' +
							'<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
							'<img src="{{image}}" />' +
						'</a>' +
						'</div>';
				}
			}
		}(),
		after: function () {
			if ('carousel' === layout) {

					mySwiper = new Swiper( '.pp-instagram-feed-carousel .swiper-container', {
						direction:              'horizontal',
						slidesPerView: <?php echo absint( $settings->visible_items ); ?>,
						spaceBetween: <?php echo $settings->images_gap; ?>,
						autoplay: <?php echo 'yes' == $settings->autoplay ? $settings->autoplay_speed : 'false'; ?>,
						grabCursor: <?php echo 'yes' == $settings->grab_cursor ? 'true' : 'false'; ?>,
						loop: <?php echo 'yes' == $settings->infinite_loop ? 'true' : 'false'; ?>,
						pagination:             '.swiper-pagination',
						paginationClickable:    true,
						nextButton:             '.swiper-button-next',
						prevButton:             '.swiper-button-prev',
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
