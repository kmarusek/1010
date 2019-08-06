;(function($){

	new PPImageComparison({
		id: 				'<?php echo $id; ?>',
		beforeLabel:		'<?php echo $settings->before_img_label; ?>',
		afterLabel:			'<?php echo $settings->after_img_label; ?>',
		visibleRatio:		'<?php echo $settings->visible_ratio; ?>',
		imgOrientation:		'<?php echo $settings->img_orientation; ?>',
		sliderHandle:		<?php echo $settings->move_slider == 'drag' ? 'true' : 'false' ?>,
		sliderHover:		<?php echo $settings->move_slider == 'mouse_move' ? 'true' : 'false' ?>,
		sliderClick:		<?php echo $settings->move_slider == 'mouse_click' ? 'true' : 'false' ?>,
		isBuilderActive:	<?php echo FLBuilderModel::is_builder_active() ? 'true' : 'false'; ?>,
	});

})(jQuery);
