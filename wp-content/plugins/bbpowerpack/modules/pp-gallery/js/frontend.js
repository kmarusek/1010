(function($) {
	PPGallery = function(settings)
	{
		this.settings       = settings;
		this.nodeClass      = '.fl-node-' + settings.id;
		this.wrapperClass   = this.nodeClass + ' .pp-photo-gallery';
		this.itemClass      = this.wrapperClass + ' .pp-photo-gallery-item';

		if(this._hasItem()) {
			this._initLayout();
		}
	};

	PPGallery.prototype = {

		settings        : {},
		nodeClass       : '',
		wrapperClass    : '',
		itemClass       : '',
		gallery         : null,

		_hasItem: function()
		{
			return $(this.itemClass).length > 0;
		},

		_initLayout: function()
		{
			if ( this.settings.layout === 'masonry' ) {
				this._masonryLayout();
			}

			if ( this.settings.layout === 'justified' ) {
				this._justifiedLayout();
			}

			$(this.itemClass).css('visibility', 'visible');
		},

		_masonryLayout: function()
		{
			var wrap = $(this.wrapperClass);

			var isotopeData = {
				itemSelector: '.pp-gallery-masonry-item',
				percentPosition: true,
				transitionDuration: '0.6s',
				masonry: {
					columnWidth: '.pp-gallery-masonry-item',
					gutter: '.pp-photo-space'
				},
			};

			wrap.imagesLoaded( $.proxy( function() {
				$(this.nodeClass).find('.pp-photo-gallery').isotope(isotopeData);
			}, this ) );
		},

		_justifiedLayout: function()
		{
			var wrap = $(this.wrapperClass);

			wrap.imagesLoaded( $.proxy(function () {
				$(this.wrapperClass).justifiedGallery({
					margins: this.settings.spacing,
					rowHeight: this.settings.rowheight,
					maxRowHeight: this.settings.maxrowheight,
					lastRow: this.settings.lastrow,
				});
			}, this));
		},


	};

})(jQuery);
