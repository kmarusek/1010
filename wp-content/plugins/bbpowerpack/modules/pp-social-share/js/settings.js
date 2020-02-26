(function($){

	FLBuilder.registerModuleHelper('pp_social_share_form', {
		
		rules: {
			size: {
				number: true,
				required: true
			}
		},

		_getField: function (name) {
			var form = $('.fl-builder-settings');
			var field = form.find('[name="' + name + '"]');

			return field;
		},

		init: function () {
			this._getField('social_share_type').on('change', $.proxy(this.hide_fields, this));
			this.hide_fields();
		},

		hide_fields: function () {
			var type = this._getField('social_share_type').val();

			if ('fb-messenger' === type) {
				$('#fl-field-social_share_type .fl-field-description').show();
			} else {
				$('#fl-field-social_share_type .fl-field-description').hide();
			}
		},

	});

})(jQuery);