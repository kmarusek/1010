<?php //echo '<xmp>'; print_r($settings); echo '</xmp>'; ?>
<div class="uabb-module-content uabb-list-icon">
<?php
foreach($settings->list_items as $item) {

	if( !is_object($item) ) {
		continue;
	}

	// check if themer connection is set.
	if( ! empty( $item->connections->title ) && empty( $item->title ) && ! FLBuilderModel::is_builder_active() ) {
		echo "";
	}
	else { ?>
		<div class="uabb-list-icon-wrap">
			<?php $module->render_image(); ?><!-- Inline Block Space Fix
		--><div class="uabb-list-icon-text">
				<?php if( isset( $item->title ) ): ?>
					<<?php echo $settings->typography_tag_selection; ?> class="uabb-list-icon-text-heading"><?php echo $item->title;  ?></<?php echo $settings->typography_tag_selection; ?>>
				<?php endif; ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>
</div>
