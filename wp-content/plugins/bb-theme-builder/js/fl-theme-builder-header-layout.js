(function($){

	/**
	 * Helper class for header layout logic.
	 *
	 * @since 1.0
	 * @class FLThemeBuilderHeaderLayout
	 */
	FLThemeBuilderHeaderLayout = {

		/**
		 * A reference to the window object for this page.
		 *
		 * @since 1.0
		 * @property {Object} win
		 */
		win : null,

		/**
		 * A reference to the body object for this page.
		 *
		 * @since 1.0
		 * @property {Object} body
		 */
		body : null,

		/**
		 * A reference to the header object for this page.
		 *
		 * @since 1.0
		 * @property {Object} header
		 */
		header : null,

		/**
		 * Whether this header overlays the content or not.
		 *
		 * @since 1.0
		 * @property {Boolean} overlay
		 */
		overlay : false,

		/**
		 * Whether the page has the WP admin bar or not.
		 *
		 * @since 1.0
		 * @property {Boolean} hasAdminBar
		 */
		hasAdminBar : false,

		/**
		 * Breakpoint for when the sticky header should apply.
		 *
		 * @since 1.4
		 * @property {String} stickyOn
		 */
		stickyOn: '',

		/**
		 * A reference of the sticky and shrink header breakpoint.
		 *
		 * @since 1.2.5
		 * @property {Number} breakpointWidth
		 */
		breakpointWidth: 0,

		/**
		 * Initializes header layout logic.
		 *
		 * @since 1.0
		 * @method init
		 */
		init: function()
		{
			var editing    = $( 'html.fl-builder-edit' ).length,
				header     = $( '.fl-builder-content[data-type=header]' ),
				breakpoint = null;

			if ( ! editing && header.length ) {

				header.imagesLoaded( $.proxy( function() {

					this.win         = $( window );
					this.body        = $( 'body' );
					this.header      = header.eq( 0 );
					this.overlay     = !! Number( header.attr( 'data-overlay' ) );
					this.hasAdminBar = !! $( 'body.admin-bar' ).length;
					this.stickyOn    = this.header.data( 'sticky-on' );
					breakpoint       = this.header.data( 'sticky-breakpoint' );

					if ( '' == this.stickyOn ) {
						if ( typeof FLBuilderLayoutConfig.breakpoints[ breakpoint ] !== undefined ) {
							this.breakpointWidth = FLBuilderLayoutConfig.breakpoints[ breakpoint ];
						}
						else {
							this.breakpointWidth = FLBuilderLayoutConfig.breakpoints.medium;
						}						
					}

					if ( Number( header.attr( 'data-sticky' ) ) ) {

						this.header.data( 'original-top', this.header.offset().top );
						this.win.on( 'resize', $.throttle( 500, $.proxy( this._initSticky, this ) ) );
						this._initSticky();

					}

				}, this ) );
			}
		},

		/**
		 * Initializes sticky logic for a header.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initSticky
		 */
		_initSticky: function( e )
		{
			var header     = $('.fl-builder-content[data-type=header]'),
				windowSize = this.win.width(),
				makeSticky = false;

			makeSticky = this._makeWindowSticky( windowSize );
			if ( makeSticky || ( this.breakpointWidth > 0 && windowSize >= this.breakpointWidth ) ) {
				this.win.on( 'scroll.fl-theme-builder-header-sticky', $.proxy( this._doSticky, this ) );
				//
				// Check if Event Type is 'resize' then invoke this._doSticky() 
				// only if the 'fl-theme-builder-header-sticky' class is already present.
				// 
				if ( e && 'resize' === e.type ) {
					if ( this.header.hasClass( 'fl-theme-builder-header-sticky' ) ) {
						this._doSticky();
					}
					this._adjustStickyHeaderWidth(); 
				}

				if ( Number( header.attr( 'data-shrink' ) ) ) {
					this.header.data( 'original-height', this.header.outerHeight() );
					this.win.on( 'resize', $.throttle( 500, $.proxy( this._initShrink, this ) ) );
					this._initShrink();
				}

			} else {
				this.win.off( 'scroll.fl-theme-builder-header-sticky' );
				this.win.off( 'resize.fl-theme-builder-header-sticky' );
				
				this.header.removeClass( 'fl-theme-builder-header-sticky' );
				this.header.removeAttr( 'style' );
				this.header.parent().css( 'padding-top', '0' );
			}
		},

		/**
		 * Check if Header should be sticky at a particular Window size.
		 *
		 * @since 1.4
		 * @access private
		 * @param  widowSize
		 * @method _makeWindowSticky
		 */
		_makeWindowSticky: function ( windowSize )
		{
			var makeSticky = false;

			switch (this.stickyOn) {
				case '':
				case 'desktop':
					makeSticky = windowSize >= FLBuilderLayoutConfig.breakpoints['medium'];
					break;
				case 'desktop-medium':
					makeSticky = windowSize > FLBuilderLayoutConfig.breakpoints['small'];
					break;
				case 'medium':
					makeSticky = ( windowSize <= FLBuilderLayoutConfig.breakpoints['medium'] && windowSize > FLBuilderLayoutConfig.breakpoints['small'] );
					break;
				case 'medium-mobile':
					makeSticky = (windowSize <= FLBuilderLayoutConfig.breakpoints['medium']);
					break;
				case 'mobile':
					makeSticky = (windowSize <= FLBuilderLayoutConfig.breakpoints['small']);
					break;
				case 'all':
					makeSticky = true;
					break;
			}

			return makeSticky;
		},

		/**
		 * Sticks the header when the page is scrolled.
		 *
		 * @since 1.0
		 * @access private
		 * @method _doSticky
		 */
		_doSticky: function( e )
		{
			var winTop    		  = Math.floor( this.win.scrollTop() ),
				headerTop 		  = Math.floor( this.header.data( 'original-top' ) ),
				hasStickyClass    = this.header.hasClass( 'fl-theme-builder-header-sticky' ),
				hasScrolledClass  = this.header.hasClass( 'fl-theme-builder-header-scrolled' ),
				beforeHeader      = this.header.prevAll( '.fl-builder-content' ),
				bodyTopPadding    = parseInt( jQuery('body').css('padding-top') ),
				headerHeight      = 0;

			if ( isNaN( bodyTopPadding ) ) {
				bodyTopPadding = 0;
			}
			
			if ( this.hasAdminBar && this.win.width() > 600 ) {
				winTop += Math.floor( $('#wpadminbar').outerHeight() );
			}

			if ( winTop > headerTop ) {
				if ( ! hasStickyClass ) {
					if ( e && 'scroll' === e.type ) {
					 	this.header.addClass( 'fl-theme-builder-header-sticky' );
					}

					if ( ! this.overlay ) {
						this._adjustHeaderHeight();
					}
				}
			}
			else if ( hasStickyClass ) {
				this.header.removeClass( 'fl-theme-builder-header-sticky' );
				this.header.removeAttr( 'style' );
				this.header.parent().css( 'padding-top', '0' );
			}
			
			this._adjustStickyHeaderWidth();

			if ( winTop > headerTop ) {
				if ( ! hasScrolledClass ) {
					this.header.addClass( 'fl-theme-builder-header-scrolled' );
				}
			} else if ( hasScrolledClass ) {
				this.header.removeClass( 'fl-theme-builder-header-scrolled' );
			}
		},

		/**
		 * Adjust sticky header width if BB Theme Boxed Layout is used.
		 *
		 * @since 1.4
		 * @access private
		 * @method _adjustStickyHeaderWidth
		 */
		_adjustStickyHeaderWidth: function () {
			if ( $('body').hasClass( 'fl-fixed-width' ) ) {
				var parentWidth = this.header.parent().width();

				// Better if this is set in the stylesheet file.
				this.header.css({
					'margin': '0 auto',
					'max-width': parentWidth,
				});
				
			}
		},

		/**
		 * Adjust Sticky Header Height
		 *
		 * @since 1.4
		 * @access private
		 * @method _adjustHeaderHeight
		 */
		_adjustHeaderHeight: function () {
			var beforeHeader = this.header.prevAll('.fl-builder-content'),
				beforeHeaderHeight = 0,
				beforeHeaderFix = 0,
				headerHeight = Math.floor( this.header.outerHeight() ),
				bodyTopPadding = parseInt( $( 'body' ).css( 'padding-top' ) ),
				wpAdminBarHeight = 0,
				totalHeaderHeight = 0;
			
			if ( isNaN( bodyTopPadding ) ) {
				bodyTopPadding = 0;
			}

			if ( beforeHeader.length ) {
				$.each( beforeHeader, function() {
					beforeHeaderHeight += Math.floor( $(this).outerHeight() );
				});
				// Subtract this value from the header parent's top padding.
				beforeHeaderFix = 2;
			}

			if ( this.hasAdminBar && this.win.width() <= 600 ) {
				wpAdminBarHeight = Math.floor( $('#wpadminbar').outerHeight() );
			}

			totalHeaderHeight = Math.floor( beforeHeaderHeight + headerHeight);

			if ( headerHeight > 0 ) {
				var headerParent = this.header.parent(),
					headerParentTopPadding = 0;

				// If the header's parent container is the BODY tag ignore its top padding.
				if ( $( headerParent ).is('body') ) {
					headerParentTopPadding = Math.floor( headerHeight - wpAdminBarHeight );
				} else {
					headerParentTopPadding = Math.floor( headerHeight - bodyTopPadding - wpAdminBarHeight );
				}

				$( headerParent ).css( 'padding-top',  ( headerParentTopPadding - beforeHeaderFix ) + 'px' );
				
				this.header.css({
					'-webkit-transform': 'translate(0px, -' + totalHeaderHeight + 'px)',
					'-ms-transform': 'translate(0px, -' + totalHeaderHeight + 'px)',
					'transform': 'translate(0px, -' + totalHeaderHeight + 'px)'
				});

			}

		},

		/**
		 * Initializes shrink logic for a header.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initShrink
		 */
		_initShrink: function( e )
		{
			if ( this.win.width() >= this.breakpointWidth ) {
				this.win.on( 'scroll.fl-theme-builder-header-shrink', $.proxy( this._doShrink, this ) );
				this._setImageMaxHeight();

				if ( this.win.scrollTop() > 0 ){
					this._doShrink();
				}

			} else {
				this.header.parent().css( 'padding-top', '0' );
				this.win.off( 'scroll.fl-theme-builder-header-shrink' );
				this._removeShrink();
				this._removeImageMaxHeight();
			}
		},

		/**
		 * Shrinks the header when the page is scrolled.
		 *
		 * @since 1.0
		 * @access private
		 * @method _doShrink
		 */
		_doShrink: function( e )
		{
			var winTop 			  = this.win.scrollTop(),
				headerTop 		  = this.header.data('original-top'),
				headerHeight 	  = this.header.data('original-height'),
				shrinkImageHeight = this.header.data('shrink-image-height'),
				windowSize   	  = this.win.width(),
				makeSticky   	  = this._makeWindowSticky( windowSize ),
				hasClass     	  = this.header.hasClass( 'fl-theme-builder-header-shrink' );

				
			if ( this.hasAdminBar ) {
				winTop += 32;
			}

			if ( makeSticky && ( winTop > headerTop + headerHeight ) ) {
				if ( ! hasClass ) {

					this.header.addClass( 'fl-theme-builder-header-shrink' );
					this.header.find( 'img' ).css( 'max-height', shrinkImageHeight );
					this.header.find( '.fl-row-content-wrap' ).each( function() {

						var row = $( this );

						if ( parseInt( row.css( 'padding-bottom' ) ) > 5 ) {
							row.addClass( 'fl-theme-builder-header-shrink-row-bottom' );
						}

						if ( parseInt( row.css( 'padding-top' ) ) > 5 ) {
							row.addClass( 'fl-theme-builder-header-shrink-row-top' );
						}
					} );

					this.header.find( '.fl-module-content' ).each( function() {

						var module = $( this );

						if ( parseInt( module.css( 'margin-bottom' ) ) > 5 ) {
							module.addClass( 'fl-theme-builder-header-shrink-module-bottom' );
						}

						if ( parseInt( module.css( 'margin-top' ) ) > 5 ) {
							module.addClass( 'fl-theme-builder-header-shrink-module-top' );
						}
					} );
				}
			} else if (hasClass) {
				this.header.find( 'img' ).css( 'max-height', '' );
				this._removeShrink();
			}

			// Fixes Shrink header issue with BB Theme when window is scrolled then resized and back.
			if ( 'undefined' === typeof( e ) && $('body').hasClass( 'fl-fixed-width' ) ) {
				if ( ! this.overlay ) {
					this._adjustHeaderHeight();
				}
			}

		},

		/**
		 * Removes the header shrink effect.
		 *
		 * @since 1.0
		 * @access private
		 * @method _removeShrink
		 */
		_removeShrink: function()
		{
			var rows    = this.header.find( '.fl-row-content-wrap' ),
				modules = this.header.find( '.fl-module-content' );

			rows.removeClass( 'fl-theme-builder-header-shrink-row-bottom' );
			rows.removeClass( 'fl-theme-builder-header-shrink-row-top' );
			modules.removeClass( 'fl-theme-builder-header-shrink-module-bottom' );
			modules.removeClass( 'fl-theme-builder-header-shrink-module-top' );
			this.header.removeClass( 'fl-theme-builder-header-shrink' );
		},

		/**
		 * Adds max height to images in modules for smooth scrolling.
		 *
		 * @since 1.1.1
		 * @access private
		 * @method _setImageMaxHeight
		 */
		_setImageMaxHeight: function()
		{
			var head = $( 'head' ),
				stylesId = 'fl-header-styles-' + this.header.data( 'post-id' ),
				styles = '',
				images = this.header.find( '.fl-module-content img' );

			if ( $( '#' + stylesId ).length ) {
				return;
			}

			images.each( function( i ) {
				var image = $( this ),
					height = image.height(),
					node = image.closest( '.fl-module' ).data( 'node' ),
					className = 'fl-node-' + node + '-img-' + i;

				image.addClass( className );
				styles += '.' + className + ' { max-height: ' + height + 'px }';
			} );

			if ( '' !== styles ) {
				head.append( '<style id="' + stylesId + '">' + styles + '</style>' );
			}
		},

		/**
		 * Removes max height on images in modules for smooth scrolling.
		 *
		 * @since 1.1.1
		 * @access private
		 * @method _removeImageMaxHeight
		 */
		_removeImageMaxHeight: function()
		{
			$( '#fl-header-styles-' + this.header.data( 'post-id' ) ).remove();
		},
	};

	$( function() { FLThemeBuilderHeaderLayout.init(); } );

})(jQuery);
