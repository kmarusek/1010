<div class="<?php echo $main_class; ?>">
	<div class="pp-heading-wrapper">

		<span class="pp-infobox-title-prefix"><?php echo $settings->title_prefix; ?></span>

		<?php if( $settings->pp_infobox_link_type == 'title' ) { ?>
			<a class="pp-more-link pp-title-link" href="<?php echo $settings->link; ?>" target="<?php echo $settings->link_target; ?>">
		<?php } ?>
		<div class="pp-infobox-title-wrapper">
			<<?php echo $settings->title_tag; ?> class="pp-infobox-title"><?php echo $settings->title; ?></<?php echo $settings->title_tag; ?>>
		</div>
		<?php if( $settings->pp_infobox_link_type == 'title' ) { ?>
			</a>
		<?php } ?>
	</div>
	<div class="pp-infobox-description">
		<?php echo $settings->description; ?>
		<?php if( $settings->pp_infobox_link_type == 'read_more' || $settings->pp_infobox_link_type == 'button' ) { ?>
			<?php $module->render_link(); ?>
		<?php } ?>
	</div>
</div>