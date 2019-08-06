;(function($) {
	
	FLBuilder.registerModuleHelper( 'pp-video', {
		init: function() {
			var form = $('.fl-builder-settings');
			var self = this;
			
			self._toggleOverlayFields();

			form.find('#fl-field-custom_overlay').on('DOMSubtreeModified', function() {
				self._toggleOverlayFields();
			});
		},

		_toggleOverlayFields: function() {
			var form = $('.fl-builder-settings');
			var field = form.find('input[name="custom_overlay"]');
			if ( '' === field.val() || 'default' === form.find('input[name="overlay"]').val() ) {
				form.find('#fl-field-play_icon').hide();
				form.find('#fl-field-lightbox').hide();
			} else {
				form.find('#fl-field-play_icon').show();
				form.find('#fl-field-lightbox').show();
			}
		}
	});
})(jQuery);