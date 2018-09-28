(function($){

	FLBuilder.registerModuleHelper('pp-advanced-accordion', {

		rules: {
			item_spacing: {
				required: true,
				number: true
			}
		},

		_templates: {
			module: '',
			row: '',
			layout: ''
		},

		init: function()
		{
			var form            = $('.fl-builder-settings'),
				itemSpacing     = form.find('input[name=item_spacing]');

			itemSpacing.on('keyup', this._previewItemSpacing);

			$('body').delegate( '.fl-builder-settings select[name="content_type"]', 'change', $.proxy(this._contentTypeChange, this) );
		},

		_previewLabelSize: function()
		{
			wrap  = FLBuilder.preview.elements.node.find('.pp-accordion');
		},

		_previewItemSpacing: function()
		{
			var spacing = parseInt($('.fl-builder-settings input[name=item_spacing]').val(), 10),
				items   = FLBuilder.preview.elements.node.find('.pp-accordion-item');

			items.attr('style', '');

			if(isNaN(spacing) || spacing === 0) {
				items.css('margin-bottom', '0px');
				items.not(':last-child').css('border-bottom', 'none');
			}
			else {
				items.css('margin-bottom', spacing + 'px');
			}
		},

		_contentTypeChange: function(e)
		{
			var type = $(e.target).val();

			if ( 'module' === type ) {
				this._setTemplates('module');
			}
			if ( 'row' === type ) {
				this._setTemplates('row');
			}
			if ( 'layout' === type ) {
				this._setTemplates('layout');
			}
		},

		_getTemplates: function(type, callback)
		{
			if ( 'undefined' === typeof type ) {
				return;
			}

			if ( 'undefined' === typeof callback ) {
				return;
			}

			var self = this;

			$.post(
				ajaxurl,
				{
					action: 'pp_get_saved_templates',
					type: type
				},
				function( response ) {
					callback(response);
				}
			);
		},

		_setTemplates: function(type)
		{
			var form = $('.fl-builder-settings'),
				select = form.find( 'select[name="content_' + type + '"]' ),
				value = '', self = this;

			if ( 'undefined' !== typeof FLBuilderSettingsForms && 'undefined' !== typeof FLBuilderSettingsForms.config ) {
				if ( "pp_accordion_items_form" === FLBuilderSettingsForms.config.id ) {
					value = FLBuilderSettingsForms.config.settings['content_' + type];
				}
			}

			if ( this._templates[type] !== '' ) {
				select.html( this._templates[type] );
				select.find( 'option[value="' + value + '"]').attr('selected', 'selected');

				return;
			}

			this._getTemplates(type, function(data) {
				var response = JSON.parse( data );

				if ( response.success ) {
					self._templates[type] = response.data;
					select.html( response.data );
					if ( '' !== value ) {
						select.find( 'option[value="' + value + '"]').attr('selected', 'selected');
					}
				}
			});
		}
	});

})(jQuery);
