<?php

	$filter_labels = $module->get_gallery_filter_ids($settings->gallery_filter, true);

	if ( count( $filter_labels ) ) :

		echo '<div class="pp-gallery-filters-wrapper">';
		echo '<ul class="pp-gallery-filters">';
		if( $settings->show_custom_all_text == 'yes' && $settings->custom_all_text != '' ) {
			echo '<li class="pp-gallery-filter-label pp-filter-active all" data-filter="*">'.$settings->custom_all_text.'</li>';
		} else {
			echo '<li class="pp-gallery-filter-label pp-filter-active all" data-filter="*">'.esc_html__('All', 'bb-powerpack').'</li>';
		}
			for ( $i=0; $i < count($settings->gallery_filter); $i++ ) :

				if ( !is_object($settings->gallery_filter[$i])) continue;

					$filter = $settings->gallery_filter[$i];
					$filter_label = $filter->filter_label;
					$label_lower = strtolower($filter_label);
					$label_str = str_replace( " ", "-", $label_lower );

					$final_label_str = preg_replace('/[^A-Za-z0-9\-\']/', '-', $label_str);

					if ( !empty( $filter_label ) ) {
						echo '<li class="pp-gallery-filter-label" data-filter=".' . $final_label_str . '">' . $filter_label . '</li>';
					}

			endfor;
		echo '</ul>';
		echo '</div>';
?>

	<?php if($settings->gallery_layout == 'grid' ) :  ?>
	<div class="pp-gallery-grid pp-photo-gallery pp-gallery-grid<?php echo $settings->photo_grid_count['desktop']; ?> <?php echo ( $settings->hover_effects != 'none' ) ? $settings->hover_effects : ''; ?>"><?php

		foreach($module->get_photos() as $photo) :

			$photo_filter_label = $filter_labels[$photo->id];
			$final_photo_filter_label = preg_replace('/[^\sA-Za-z0-9]/', '-', $photo_filter_label); ?>

		<div class="pp-gallery-grid-item pp-gallery-item pp-photo-gallery-item <?php echo $final_photo_filter_label; ?> <?php echo ( ( $settings->click_action != 'none' ) && !empty( $photo->link ) ) ? 'pp-photo-gallery-link' : ''; ?>">
			<div class="pp-photo-gallery-content">

				<?php if( $settings->click_action != 'none' ) : ?>
					<?php $click_action_link = '#';
						  $click_action_target = $settings->custom_link_target;

						if ( $settings->click_action == 'custom-link' ) {
							if ( ! empty( $photo->cta_link ) ) {
								$click_action_link = $photo->cta_link;
							}
						}

						if ( $settings->click_action == 'lightbox' ) {
							$click_action_link = $photo->link;
						}

					?>
				<a href="<?php echo $click_action_link; ?>" target="<?php echo $click_action_target; ?>">
				<?php endif; ?>

				<img class="pp-gallery-img" src="<?php echo $photo->src; ?>" alt="<?php echo $photo->alt; ?>" />

				<?php if( $settings->hover_effects != 'none' || $settings->overlay_effects != 'none' ) : ?>
					<!-- Overlay Wrapper -->
					<div class="pp-gallery-overlay">
						<div class="pp-overlay-inner">

							<?php if( $settings->show_captions == 'hover' ) : ?>
								<div class="pp-caption">
									<?php echo $photo->caption; ?>
								</div>
							<?php endif; ?>

							<?php if( $settings->icon == '1' && $settings->overlay_icon != '' ) : ?>
							<div class="pp-overlay-icon">
								<span class="<?php echo $settings->overlay_icon; ?>" ></span>
							</div>
							<?php endif; ?>

						</div>
					</div> <!-- Overlay Wrapper Closed -->
				<?php endif; ?>

				<?php if( $settings->click_action != 'none' ) : ?>
				</a>
				<?php endif; ?>
			</div>
			<?php if($photo && !empty($photo->caption) && 'below' == $settings->show_captions) : ?>
			<div class="pp-photo-gallery-caption pp-photo-gallery-caption-below" itemprop="caption"><?php echo $photo->caption; ?></div>
			<?php endif; ?>
		</div>
		<?php
		endforeach; ?>
		<div class="pp-photo-space"></div>
	</div>
	<?php else : ?>
	<div class="pp-masonry">
		<div class="pp-masonry-content pp-gallery-masonry <?php echo ( $settings->hover_effects != 'none' ) ? $settings->hover_effects : ''; ?>">
			<div class="pp-grid-sizer"></div>
			<?php foreach($module->get_photos() as $photo) : ?>
			<div class="pp-gallery-masonry-item pp-gallery-item pp-masonry-item <?php echo $filter_labels[$photo->id]; ?>">
				<div class="pp-photo-gallery-content <?php echo ( ( $settings->click_action != 'none' ) && !empty( $photo->link ) ) ? 'pp-photo-gallery-link' : ''; ?>">
					<?php if( $settings->click_action != 'none' ) : ?>
						<?php $click_action_link = '#';
							  $click_action_target = $settings->custom_link_target;

							if ( $settings->click_action == 'custom-link' ) {
								if ( ! empty( $photo->cta_link ) ) {
									$click_action_link = $photo->cta_link;
								}
							}

							if ( $settings->click_action == 'lightbox' ) {
								$click_action_link = $photo->link;
							}

						?>
					<a href="<?php echo $click_action_link; ?>" target="<?php echo $click_action_target; ?>">
					<?php endif; ?>

					<img class="pp-gallery-img" src="<?php echo $photo->src; ?>" alt="<?php echo $photo->alt; ?>" />
					<?php if( $settings->hover_effects != 'none' || $settings->overlay_effects != 'none' ) : ?>
					<!-- Overlay Wrapper -->
					<div class="pp-gallery-overlay">
						<div class="pp-overlay-inner">

							<?php if( $settings->show_captions == 'hover' ) : ?>
								<div class="pp-caption">
									<?php echo $photo->caption; ?>
								</div>
							<?php endif; ?>

							<?php if( $settings->icon == '1' && $settings->overlay_icon != '' ) : ?>
							<div class="pp-overlay-icon">
								<span class="<?php echo $settings->overlay_icon; ?>" ></span>
							</div>
							<?php endif; ?>

						</div>
					</div> <!-- Overlay Wrapper Closed -->
				<?php endif; ?>
					<?php if( $settings->click_action != 'none' ) : ?>
					</a>
					<?php endif; ?>
				</div>
				<?php if($photo && !empty($photo->caption) && 'below' == $settings->show_captions) : ?>
				<div class="pp-photo-gallery-caption pp-photo-gallery-caption-below" itemprop="caption"><?php echo $photo->caption; ?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>

			<div class="pp-photo-space"></div>
		</div>
		<div class="pp-clear"></div>
	</div>
	<?php endif; ?>
<?php else: ?>
	<p><?php _e('Please add photos to the gallery.', 'bb-powerpack'); ?></p>
<?php endif; ?>