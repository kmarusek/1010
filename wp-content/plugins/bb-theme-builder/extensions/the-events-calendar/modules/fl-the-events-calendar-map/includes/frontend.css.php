<?php if ( ( 'custom' === $settings->height_type ) && is_numeric( $settings->custom_height ) ) : ?>
.fl-node-<?php echo $id; ?> .tribe-events-venue-map iframe {
	height: <?php echo $settings->custom_height; ?>px !important;
}
<?php endif; ?>
