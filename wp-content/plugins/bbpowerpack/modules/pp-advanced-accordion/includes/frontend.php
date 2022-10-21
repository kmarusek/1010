<?php
$items         = $module->get_accordion_items( $id );
$icon_position = $settings->accordion_icon_position;

if ( ! is_array( $items ) || empty( $items ) ) {
	return;
}

if ( ! empty( $settings->accordion_open_icon ) ) {
	$open_icon_class = 'pp-accordion-button-icon pp-accordion-open ' . $settings->accordion_open_icon . ' pp-accordion-icon-' . $icon_position;
} else {
	$open_icon_class = 'pp-accordion-button-icon pp-accordion-open fa fa-plus pp-accordion-icon-' . $icon_position;
}
if ( ! empty( $settings->accordion_close_icon ) ) {
	$close_icon_class = 'pp-accordion-button-icon pp-accordion-close ' . $settings->accordion_close_icon . ' pp-accordion-icon-' . $icon_position;
} else {
	$close_icon_class = 'pp-accordion-button-icon pp-accordion-close fa fa-minus pp-accordion-icon-' . $icon_position;
}
?>

<div class="pp-accordion<?php echo ( $settings->collapse ) ? ' pp-accordion-collapse' : ''; ?>">
	<?php
	for ( $i = 0; $i < count( $items ); $i++ ) :
		if ( empty( $items[ $i ] ) ) {
			continue;
		}

		?>
		<div id="<?php echo $items[ $i ]->html_id; ?>" class="pp-accordion-item" role="tablist">
			<div class="pp-accordion-button" id="pp-accordion-<?php echo $module->node; ?>-tab-<?php echo $i; ?>" aria-selected="false" aria-controls="pp-accordion-<?php echo $module->node; ?>-panel-<?php echo $i; ?>" aria-expanded="<?php echo ( $i > 0 || ! $settings->open_first ) ? 'false' : 'true'; ?>" role="tab" tabindex="0">
				<?php if ( 'left' === $icon_position ) { ?>
					<span class="<?php echo $open_icon_class; ?>" aria-hidden="true" aria-label="Close"></span>
					<span class="<?php echo $close_icon_class; ?>" aria-hidden="true" aria-label="Expand"></span>
				<?php } ?>

				<?php $module->render_accordion_item_icon( $items[ $i ] ); ?>

				<span class="pp-accordion-button-label" itemprop="name description"><?php echo $items[ $i ]->label; ?></span>

				<?php if ( 'right' === $icon_position ) { ?>
					<span class="<?php echo $open_icon_class; ?>" aria-hidden="true" aria-label="Close"></span>
					<span class="<?php echo $close_icon_class; ?>" aria-hidden="true" aria-label="Expand"></span>
				<?php } ?>
			</div>

			<div class="pp-accordion-content fl-clearfix" id="pp-accordion-<?php echo $module->node; ?>-panel-<?php echo $i; ?>" aria-labelledby="pp-accordion-<?php echo $module->node; ?>-tab-<?php echo $i; ?>" aria-hidden="<?php echo ( $i > 0 || ! $settings->open_first ) ? 'true' : 'false'; ?>" role="tabpanel" aria-live="polite">
				<?php
				echo $module->render_content( $items[ $i ] );
				?>
			</div>
		</div>
	<?php endfor; ?>
</div>
