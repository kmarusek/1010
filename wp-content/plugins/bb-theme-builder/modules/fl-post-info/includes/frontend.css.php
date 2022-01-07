.fl-node-<?php echo $id; ?> .fl-module-content {
	text-align: <?php echo $settings->align; ?>;
}

<?php if ( ! empty( $settings->font_size ) ) : ?>
.fl-node-<?php echo $id; ?> span,
.fl-node-<?php echo $id; ?> a {
	font-size: <?php echo $settings->font_size; ?>px;
}
<?php endif; ?>

<?php if ( ! empty( $settings->text_color ) ) : ?>
.fl-node-<?php echo $id; ?> .fl-module-content.fl-node-content span[class^='fl-post-info'],
.fl-node-<?php echo $id; ?> .fl-module-content.fl-node-content a {
	color: <?php echo FLBuilderColor::hex_or_rgb( $settings->text_color ); ?>;
}
<?php endif; ?>
