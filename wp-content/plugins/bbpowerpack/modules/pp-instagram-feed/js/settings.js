(function($){

	FLBuilder.registerModuleHelper('pp-instagram-feed', {
		init: function() {
			var form = $('.fl-builder-settings');

			if ( form.find('input[name="image_overlay_type"]').val() === 'gradient' ) {
				form.find('#fl-field-image_overlay_angle').show();
			} else {
				form.find('#fl-field-image_overlay_angle').hide();
			}

			form.find('input[name="image_overlay_type"]').on('change', function() {
				if ( $(this).val() === 'gradient' ) {
					form.find('#fl-field-image_overlay_angle').show();
				} else {
					form.find('#fl-field-image_overlay_angle').hide();
				}
			});
			
			if ( form.find('input[name="image_hover_overlay_type"]').val() === 'gradient' ) {
				form.find('#fl-field-image_hover_overlay_angle').show();
			} else {
				form.find('#fl-field-image_hover_overlay_angle').hide();
			}

			form.find('input[name="image_hover_overlay_type"]').on('change', function() {
				if ( $(this).val() === 'gradient' ) {
					form.find('#fl-field-image_hover_overlay_angle').show();
				} else {
					form.find('#fl-field-image_hover_overlay_angle').hide();	
				}
			});

		}
	});

})(jQuery);