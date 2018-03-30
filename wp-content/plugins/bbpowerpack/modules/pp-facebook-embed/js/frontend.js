; (function ($) {

	PPFacebookEmbed = function (settings) {
		this.id = settings.id;
		this.node = $('.fl-node-' + settings.id)[0];
		this.settings = settings;

		this._init();
	};

	PPFacebookEmbed.prototype = {
		id: '',
		node: '',
		settings: {},

		_init: function () {
			this._parse(this.node);
		},

		_parse: function (node) {
			// FB SDK is loaded, parse only current element
			if ('undefined' !== typeof FB) {
				FB.XFBML.parse(node);
			}
		}
	};

})(jQuery);