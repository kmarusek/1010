(function($){
	FLBuilder.registerModuleHelper('uabb-woo-add-to-cart', {

		init: function()
		{
			var form        = $('.fl-builder-settings'),
				btn_style   = form.find('select[name=style]');

			// Init validation events.
			this._btn_styleChanges();
			
			// Validation events.
			btn_style.on('change',  $.proxy( this._btn_styleChanges, this ) );
		},

		_btn_styleChanges: function()
		{
			var form        = $('.fl-builder-settings'),
				btn_style   = form.find('select[name=style]').val();
				
			if( btn_style == 'transparent' ) {
            	form.find('#fl-field-bg_color th label').text('Border Color');
            } else {
            	form.find('#fl-field-bg_color th label').text('Background Color');
            }
		}
		
	});

})(jQuery);