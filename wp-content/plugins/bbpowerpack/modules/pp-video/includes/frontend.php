<?php
// Video type.
$video_type = $settings->video_type;

// Video URL.
$video_url = $module->get_video_url();

if ( empty( $video_url ) ) {
	return;
}

// Video HTML.
$video_html = $module->get_video_html();

// Do not render anything if there is no video HTML.
if ( empty( $video_html ) ) {
	echo esc_url( $video_url );

	return;
}

// Lightbox - enabled or not.
$lightbox = $module->has_lightbox();

// CSS classes for wrapper element.
$wrapper_classes = array(
	'pp-video-wrapper',
	'pp-aspect-ratio-' . $settings->aspect_ratio,
);

// Attributes for image overlay.
$overlay_attrs = array();

// Structured data.
$schema = $module->get_structured_data( $settings );
?>

<div class="<?php echo implode( ' ', $wrapper_classes ); ?>"<?php echo $schema ? ' itemscope itemtype="https://schema.org/VideoObject"' : ''; ?>>
	<?php
		if ( $schema ) {
			echo $schema;
		}
	?>
	<div class="pp-fit-aspect-ratio">
	<?php
	if ( ! $lightbox ) {
		echo $video_html; // XSS ok.
	}

	if ( $module->has_image_overlay() ) {
		$overlay_attrs['class'] = 'pp-video-image-overlay';

		if ( $lightbox ) {
			$wrapper_classes[] = 'pp-video-has-lightbox';
		} else {
			$overlay_attrs['style'] = 'background-image: url(' . $settings->custom_overlay_src . ');';
		}
		?>
		<div <?php echo $module->render_html_attributes( $overlay_attrs ); ?>>
			<?php if ( $lightbox ) {
				$attachment_data = FLBuilderPhoto::get_attachment_data( $settings->custom_overlay );
				echo sprintf(
					'<img class="%s" src="%s" title="%s" alt="%s" />',
					'wp-image-' . $settings->custom_overlay,
					$settings->custom_overlay_src,
					is_object( $attachment_data ) ? $attachment_data->title : '',
					is_object( $attachment_data ) ? $attachment_data->alt : ''
				);
			} ?>
			<?php if ( 'show' === $settings->play_icon ) { ?>
				<div class="pp-video-play-icon" role="button" tabindex="0">
					<?php echo apply_filters( 'pp_video_play_button_html', file_get_contents( BB_POWERPACK_DIR . 'modules/pp-video/play-button.svg' ), $settings ); ?>
					<span class="pp-screen-only"><?php _e( 'Play Video', 'bb-powerpack' ); ?></span>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	</div>
</div>
<?php if ( $lightbox ) { ?>
	<script type="text/html" class="pp-video-lightbox-content">
		<div class="pp-video-container">
			<div class="pp-fit-aspect-ratio">
			<?php echo $video_html; ?>
			</div>
		</div>
	</script>
<?php } ?>
