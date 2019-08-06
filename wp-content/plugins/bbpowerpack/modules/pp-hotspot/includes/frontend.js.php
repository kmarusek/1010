;(function($){

	$(document).ready(function() {
		new PPHotspot({
			id: 				'<?php echo $id; ?>',
			markerLength:		'<?php echo sizeof($settings->markers_content); ?>',
			tooltipEnable:		'<?php echo $settings->tooltip; ?>',
			tooltipPosition:	'<?php echo $settings->tooltip_position; ?>',
			tooltipTrigger:		'<?php echo $settings->tooltip_trigger; ?>',
			tooltipDistance:	'<?php echo $settings->tooltip_distance; ?>',
			tooltipAnimation:	'<?php echo $settings->tooltip_animation; ?>',
			tooltipWidth:		'<?php echo $settings->tooltip_max_width; ?>',
			animationDur:		'<?php echo $settings->animation_duration; ?>',
			tourEnable:			'<?php echo $settings->enable_tour; ?>',
			tourRepeat:			'<?php echo $settings->repeat_tour; ?>',
			tourAutoplay:		'<?php echo $settings->autoplay_tour; ?>',
			tooltipInterval:	'<?php echo $settings->tooltip_interval; ?>',
			launchTourOn:		'<?php echo $settings->launch_tour; ?>',
			nonActiveMarker:	'<?php echo $settings->non_active_marker; ?>',
			tooltipZindex:		'<?php echo $settings->tooltip_zindex; ?>',
			adminTitlePreview:	'<?php echo $settings->admin_title_preview; ?>',
			tooltipPreview:		'<?php echo $settings->tooltip_preview; ?>',
			viewport:			90,
			tooltipArrow:		<?php echo 'show' == $settings->tooltip_arrow ? 'true' : 'false'; ?>,
			isBuilderActive:	<?php echo FLBuilderModel::is_builder_active() ? 'true' : 'false'; ?>,
		});
	});

})(jQuery);
