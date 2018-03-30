;(function($) {

	PPFacebookPage = function( settings ) {
		this.id			= settings.id;
		this.node		= $('.fl-node-' + settings.id)[0];
		this.settings 	= settings;

		this._init();
	};

	PPFacebookPage.prototype = {
		id: '',
		node: '',
		settings: {},

		_init: function()
		{
			$('body').delegate('.fl-builder-pp-facebook-page-settings #fl-field-layout select', 'change', $.proxy( this._change, this ));
			
			this._parse( this.node );
		},

		_change: function(e)
		{
			e.stopPropagation();
			
			var node = this.node,
				tabs = $('.fl-builder-pp-facebook-page-settings #fl-field-layout select').val();

			$(node).find('.fb-page').attr({
				'data-tabs': tabs
			});

			this._parse( node );
		},

		_parse: function(node)
		{
			// FB SDK is loaded, parse only current element
			if ('undefined' !== typeof FB) {
				FB.XFBML.parse( node );
			}
		}
	};

})(jQuery);