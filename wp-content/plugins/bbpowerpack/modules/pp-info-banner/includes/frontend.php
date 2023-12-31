<div class="pp-info-banner-content <?php echo $settings->banner_image_arrangement; ?>">
	<div class="pp-info-banner-inner">
		<?php if ( $settings->banner_image_arrangement == 'background' && $settings->banner_image ) { ?>
			<div class="pp-info-banner-bg">
			</div>
		<?php } ?>
		<?php if ( $settings->banner_image_arrangement == 'static' && $settings->banner_image ) { ?>
		<img src="<?php echo $settings->banner_image_src; ?>" class="pp-info-banner-img img-<?php echo $settings->banner_image_alignment; ?> animated" data-animation-class="<?php echo $settings->banner_image_effect; ?>" alt="<?php echo ( ! empty( $settings->banner_image ) ) ? get_post_meta( $settings->banner_image, '_wp_attachment_image_alt', true ) : ''; ?>" />
		<?php } ?>
		<?php if ( '' !== $settings->banner_title || '' !== $settings->banner_description || '' !== $settings->button_link ) { ?>
		<div class="info-banner-wrap <?php echo $settings->banner_info_alignment; ?> animated" data-animation-class="<?php echo $settings->banner_info_animation; ?>">
			<div class="banner-title"><?php echo $settings->banner_title; ?></div>
			<div class="banner-description"><?php echo wpautop( $settings->banner_description ); ?></div>
			<?php if ( $settings->button_link != '' ) { ?>
			<a class="<?php echo $settings->link_type == 'button' ? 'banner-button' : 'banner-link'; ?>" href="<?php echo $settings->button_link; ?>" target="<?php echo $settings->button_link_target; ?>" role="button"<?php echo $module->get_rel(); ?>>
				<?php if ( $settings->link_type == 'button' ) { echo $settings->button_text; } ?>
			</a>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>
