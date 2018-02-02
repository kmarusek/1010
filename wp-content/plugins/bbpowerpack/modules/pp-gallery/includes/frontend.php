<?php
$item_class = 'pp-photo-gallery-item';
$item_class .= ( 'masonry' == $settings->gallery_layout ) ? ' pp-gallery-masonry-item' : '';
$item_class .= ( 'justified' == $settings->gallery_layout ) ? ' pp-gallery-justified-item' : '';
?>

<div class="pp-photo-gallery">
<?php foreach ( $module->get_photos() as $photo ) : ?>
	<div class="<?php echo $item_class; ?>">
		<div class="pp-photo-gallery-content">

			<?php
			if ( 'none' != $settings->click_action ) :
				$click_action_link = 'javascript:void(0)';
				if ( 'custom-link' == $settings->click_action ) {
					$click_action_target = $settings->custom_link_target;
					if ( ! empty( $photo->cta_link ) ) {
						$click_action_link = $photo->cta_link;
					}
				}

				if ( 'lightbox' == $settings->click_action ) {
					$click_action_link = $photo->link;
				}
			?>
			<a href="<?php echo $click_action_link; ?>" <?php if ( 'custom-link' == $settings->click_action ) { ?>target="<?php echo $click_action_target; ?>"<?php } ?> <?php if ( 'lightbox' == $settings->click_action ) { ?>class="fancybox-button" rel="fancybox-button"<?php } ?>>
			<?php endif; ?>

			<img class="pp-gallery-img" src="<?php echo $photo->src; ?>" alt="<?php echo $photo->alt; ?>" data-no-lazy="1" />
				<!-- Overlay Wrapper -->
				<div class="pp-gallery-overlay">
					<div class="pp-overlay-inner">

						<?php if ( 'hover' == $settings->show_captions ) : ?>
							<div class="pp-caption">
								<?php echo $photo->caption; ?>
							</div>
						<?php endif; ?>

						<?php if ( '1' == $settings->icon && '' != $settings->overlay_icon ) : ?>
						<div class="pp-overlay-icon">
							<span class="<?php echo $settings->overlay_icon; ?>"></span>
						</div>
						<?php endif; ?>

					</div>
				</div> <!-- Overlay Wrapper Closed -->

			<?php if ( 'none' != $settings->click_action ) : ?>
			</a>
			<?php endif; ?>
		</div>
		<?php if ( $photo && ! empty( $photo->caption ) && 'below' == $settings->show_captions ) : ?>
		<div class="pp-photo-gallery-caption pp-photo-gallery-caption-below" itemprop="caption"><?php echo $photo->caption; ?></div>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>

	<?php if ( 'masonry' == $settings->gallery_layout ) { ?>
		<div class="pp-photo-space"></div>
	<?php } ?>
</div>
