<div class="pp-reviews-wrapper pp-content-wrapper">
	<!-- Slider container -->
	<div class="pp-reviews-swiper swiper-container">
		<!-- Slides wrapper -->
		<div class="swiper-wrapper">
			<!-- Slides -->
			<?php
			$number_reviews = count( $settings->reviews );
			for ( $i = 0; $i < $number_reviews; $i++ ) {
				$review = $settings->reviews[ $i ];
				if ( ! is_object( $review ) ) {
					continue;
				}
				?>
					<div class="pp-review-item pp-review-item-<?php echo $i; ?> swiper-slide">
						<div class="pp-review">
							<div class="pp-review-header">
								<div class="pp-review-image">
									<img src="<?php echo $review->image_src; ?>" alt="<?php echo $review->name; ?>">
								</div>
								<cite class="pp-review-cite">
									<span class="pp-review-name"><?php echo $review->name; ?></span>
									<?php
									$rating = (float) $review->rating > 5 ? 5 : $review->rating;
									if ( $rating > 0 ) {
										?>
									<div class="pp-rating">
										<?php
										$icon = '&#9733;';

										if ( 'outline' === $settings->star_style ) {
											$icon = '&#9734;';
										}

										$floored_rating = (int) $rating;
										$stars_html     = '';

										for ( $stars = 1; $stars <= 5; $stars++ ) {
											if ( $stars <= $floored_rating ) {
												$stars_html .= '<i class="pp-star-full">' . $icon . '</i>';
											} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
												$stars_html .= '<i class="pp-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
											} else {
												$stars_html .= '<i class="pp-star-empty">' . $icon . '</i>';
											}
										}

										echo $stars_html;
										?>
									</div>
									<?php } ?>
									<span class="pp-review-title"><?php echo $review->title; ?></span>
								</cite>
								<div class="pp-review-icon pp-icon"><i class="<?php echo $review->icon; ?>" aria-hidden="true"></i></div>
							</div>
							<div class="pp-review-content">
								<div class="pp-review-text">
									<?php echo $review->review; ?>
								</div>
							</div>
						</div>
					</div>
				<?php
			}
			?>
		</div>
		<!-- Pagination, if required -->
		<div class="swiper-pagination"></div>

	</div>
</div>
<?php if ( 'yes' === $settings->slider_navigation ) { ?>
		<!-- navigation arrows -->
		<div class="pp-swiper-button pp-swiper-button-prev"><span class="fa fa-angle-left"></span></div>
		<div class="pp-swiper-button pp-swiper-button-next"><span class="fa fa-angle-right"></span></div>
<?php } ?>
