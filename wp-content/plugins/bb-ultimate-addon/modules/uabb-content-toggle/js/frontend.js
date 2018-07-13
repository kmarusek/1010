(function($) {

	UABBContentToggle = function( settings )
	{   
		this.settings 	= settings;
		this.nodeClass  = '.fl-node-' + settings.id;
		this._init();	
	};

	UABBContentToggle.prototype = {

		settings	: {},
		node 		: '',
		nodeClass   : '',
		
		_init: function()
		{	
			this._contentToggleHandler();
		},

		/**
		 * Initializes all anchor links on the page for smooth scrolling.
		 *
		 * @since 1.6.7
		 * @access private
		 * @method _initAnchorLinks
		 */
		_contentToggleHandler: function()
		{   
			var nodeClass  		= jQuery(this.nodeClass);
			var node_id 		= nodeClass.data( 'node' );
			var node            = '.fl-node-'+node_id+' ';
			var rbs_wrapper     = nodeClass.find( '.uabb-rbs-wrapper' );
			var rbs_section_1   = nodeClass.find( ".uabb-rbs-section-1" );
			var rbs_section_2   = nodeClass.find( ".uabb-rbs-section-2" );
			var main_btn        = nodeClass.find( ".uabb-main-btn" );
			var switch_type     = jQuery( ".uabb-main-btn" ).attr( 'data-switch-type' );
			var current_class 	= '';
			var node            = '.fl-node-'+node_id+' ';

			
			switch ( switch_type ) {
				case 'round1':
					current_class = '.uabb-switch-round-1';
					break;
				case 'round2':
					current_class = '.uabb-switch-round-2';
					break;
				case 'rectangle':
					current_class = '.uabb-switch-rectangle';
					break;
				case 'label_box':
					current_class = '.uabb-switch-label-box';
					break;
				default:
					current_class = 'No Class Selected';
					break;
			}

			jQuery(node+current_class).on( 'click', function(){
                jQuery(node+'.uabb-rbs-content-1').toggle();
	        	jQuery(node+'.uabb-rbs-content-2').toggle();
	        	jQuery(node+'.uabb-rbs-section-1').toggle();
	        	jQuery(node+'.uabb-rbs-section-2').toggle();
	        	jQuery(window).resize();
		    });
		},		
	};
	
})(jQuery);