<?php
$activeTabIndex = absint( $settings->tab_default );
$activeTabIndex = $activeTabIndex > count( $settings->items ) ? 0 : $activeTabIndex;
$activeTabIndex = $activeTabIndex < 1 ? 0 : $activeTabIndex - 1;
$items          = $module->get_tabs_items( $id );
?>

<div class="pp-tabs pp-tabs-<?php echo $settings->layout; ?><?php echo isset( $settings->vertical_position ) ? ' pp-tabs-vertical-' . $settings->vertical_position : ''; ?> pp-tabs-<?php echo $settings->tab_style; ?> pp-clearfix" role="tablist">

	<div class="pp-tabs-labels pp-clearfix">
		<?php
		for ( $i = 0; $i < count( $items ); $i++ ) :
			if ( ! is_object( $items[ $i ] ) ) {
				continue;
			}
			$item = $items[ $i ];
		?>
		<div id="<?php echo $item->html_id; ?>" class="pp-tabs-label<?php echo ( $i == $activeTabIndex ) ? ' pp-tab-active' : ''; ?> <?php echo 'pp-tab-icon-' . $settings->tab_icon_position; ?>" data-index="<?php echo $i; ?>" role="tab" tabindex="-1" aria-selected="<?php echo ( $i == $activeTabIndex ) ? 'true' : 'false'; ?>" aria-controls="<?php echo $item->html_id; ?>-content">
			<div class="pp-tab-label-inner">
				<div class="pp-tab-label-flex">
				<?php if ( $settings->tab_icon_position == 'left' || $settings->tab_icon_position == 'top' ) { ?>
					<?php $module->render_tab_item_icon( $item ); ?>
				<?php } ?>

				<div class="pp-tab-title"><?php echo $item->label; ?></div>

				<?php if ( $settings->tab_icon_position == 'right' || $settings->tab_icon_position == 'bottom' ) { ?>
					<?php $module->render_tab_item_icon( $item ); ?>
				<?php } ?>
				</div>
				<?php if ( isset( $item->description ) && ! empty( $item->description ) ) { ?>
					<div class="pp-tab-description">
						<?php echo $item->description; ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php endfor; ?>
	</div>

	<div class="pp-tabs-panels pp-clearfix">
		<?php
		for ( $i = 0; $i < count( $items ); $i++ ) :
			if ( ! is_object( $items[ $i ] ) ) {
				continue;
			}
			$item = $items[ $i ];
		?>
		<div class="pp-tabs-panel"<?php if ( ! empty( $settings->id ) ) { echo ' id="' . sanitize_html_class( $settings->id ) . '-' . $i . '"';} ?>>
			<div class="pp-tabs-label pp-tabs-panel-label<?php if ( $i == $activeTabIndex ) { echo ' pp-tab-active';} ?> <?php echo 'pp-tab-icon-' . $settings->tab_icon_position; ?>" data-index="<?php echo $i; ?>" role="tab">
				<div class="pp-tab-label-inner">
					<div class="pp-tab-label-flex">
						<?php if ( $settings->tab_icon_position == 'left' || $settings->tab_icon_position == 'top' ) { ?>
							<?php if ( $item->tab_font_icon ) { ?>
								<span class="pp-tab-icon <?php echo $item->tab_font_icon; ?>"></span>
							<?php } ?>
						<?php } ?>
						<div class="pp-tab-label-wrap">
							<div class="pp-tab-title"><?php echo $item->label; ?></div>
							<?php if ( $settings->tab_icon_position == 'right' || $settings->tab_icon_position == 'bottom' ) { ?>
								<?php if ( $item->tab_font_icon ) { ?>
									<span class="pp-tab-icon <?php echo $item->tab_font_icon; ?>"></span>
								<?php } ?>
							<?php } ?>
							<?php if ( isset( $item->description ) && ! empty( $item->description ) ) { ?>
								<div class="pp-tab-description">
									<?php echo $item->description; ?>
								</div>
							<?php } ?>
						</div>
					</div>

					<?php if ( $settings->tab_open_icon != '' ) { ?>
						<span class="pp-toggle-icon pp-tab-open <?php echo $settings->tab_open_icon; ?>"></span>
					<?php } else { ?>
						<i class="pp-toggle-icon pp-tab-open fa fa-plus"></i>
					<?php } ?>

					<?php if ( $settings->tab_close_icon != '' ) { ?>
						<span class="pp-toggle-icon pp-tab-close <?php echo $settings->tab_close_icon; ?>"></span>
					<?php } else { ?>
						<i class="pp-toggle-icon pp-tab-close fa fa-minus"></i>
					<?php } ?>
				</div>
			</div>
			<div id="<?php echo $item->html_id; ?>-content" class="pp-tabs-panel-content pp-clearfix<?php if ( $i == $activeTabIndex ) { echo ' pp-tab-active';} ?>" data-index="<?php echo $i; ?>" role="tabpanel" aria-labelledby="<?php echo $item->html_id; ?>">
				<?php $module->render_content( $item ); ?>
			</div>
		</div>
		<?php endfor; ?>
	</div>

</div>
