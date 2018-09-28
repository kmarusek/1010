(function($){

	FLBuilder.registerModuleHelper('pp-animated-headlines', {

		init: function()
		{
			var form = $('.fl-builder-settings');
			
			// Init hide function.
			this._toggleAnimatedSelectionFields();
			
			// Validation events.
			form.find('select[name=effect_type]').on('change', this._toggleTypeOptions);
			form.find('select[name=headline_style]').on('change', this._toggleAnimatedSelectionFields);
		},

		_toggleAnimatedSelectionFields: function()
		{
			var form = $('.fl-builder-settings');

			if ( 'highlight' === form.find('select[name=headline_style]').val() ) {
				form.find('#fl-field-animated_selection_bg_color').hide();
				form.find('#fl-field-animated_selection_color').hide();
			}
			if ( 'rotate' === form.find('select[name=headline_style]').val() ) {
				if ( 'typing' === form.find('select[name=animation_type]').val() ) {
					form.find('#fl-field-animated_selection_bg_color').show();
					form.find('#fl-field-animated_selection_color').show();
				}
			}
		}
	});

})(jQuery);