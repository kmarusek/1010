;(function( $ ) {

	PPVideo = function( settings ) {
		this.id			= settings.id;
		this.type		= settings.type;
		this.aspectRatio = settings.aspectRatio;
		this.lightbox 	= settings.lightbox;
		this.overlay	= settings.overlay;
		this.node		= $('.fl-node-' + this.id);

		this._init();
	};

	PPVideo.prototype = {
		_init: function() {
			if ( this.lightbox ) {
				this._initLightbox();
			} else if ( this.overlay ) {
				this._inlinePlay();
			}
		},

		_inlinePlay: function() {
			if ( this.node.find( '.pp-video-iframe' ).length > 0 ) {
				this.node.find( '.pp-video-iframe' )[0].src = this.node.find( '.pp-video-iframe' )[0].src.replace('&autoplay=1', '');
			}

			this.node.find('.pp-video-image-overlay').on('click', $.proxy(function() {
				this.node.find( '.pp-video-image-overlay' ).fadeOut(800, function() {
					this.remove();
				});

				if ( this.node.find( '.pp-video' ).length > 0 ) {
					this.node.find( '.pp-video' )[0].play();

					return;
				}

				var iframeSrc = this.node.find( '.pp-video-iframe' )[0].src.replace('&autoplay=0', '');
				this.node.find( '.pp-video-iframe' )[0].src = iframeSrc + '&autoplay=1';
			}, this));
		},

		_initLightbox: function() {
			var id = this.id;
			var options = {
				modal			: false,
				baseClass		: 'fancybox-' + id,
				buttons			: [
					'close'
				],
				wheel			: false,
				afterLoad		: function(current, previous) {
					$('.fancybox-' + id).find('.fancybox-bg').addClass('fancybox-' + id + '-overlay');
				},
			};

			var wrapperClasses = 'pp-aspect-ratio-' + this.aspectRatio;

			this.node.find('.pp-video-image-overlay').on('click', function(e) {
				e.stopPropagation();
				$.fancybox.open($('<div class="'+wrapperClasses+'"></div>').html( $(this).find('.pp-video-lightbox-content').html() ), options);
			});
		},
	};

})(jQuery);