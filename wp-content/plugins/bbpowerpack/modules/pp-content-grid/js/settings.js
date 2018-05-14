;(function($){

	FLBuilder.registerModuleHelper('pp-content-grid', {

		/**
         * The 'init' method is called by the builder when
         * the settings form is opened.
         *
         * @method init
         */
        init: function()
        {
			var form 			= $('.fl-builder-settings'),
				button_sections = ['button_colors', 'button_typography'],
				self 			= this;

			if( $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'product' || $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'download' ) {
                $('#fl-builder-settings-section-product-settings').show();
                $('#fl-field-more_link_text').hide();
                if ( $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'download' ) {
                    $('#fl-field-product_rating, #fl-field-product_rating_color').hide();
                }
		   	}

			$('#fl-builder-settings-section-general select[name="post_type"]').on('change', function() {
				if( $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'product' || $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'download' ) {
                    $('#fl-builder-settings-section-product-settings').show();
                    $('#fl-field-more_link_text').hide();
                    if ( $('#fl-builder-settings-section-general select[name="post_type"]').val() == 'download' ) {
                        $('#fl-field-product_rating, #fl-field-product_rating_color').hide();
                    }
			   	} else {
				   $('#fl-builder-settings-section-product-settings').hide();
                   self._showField( 'more_link_text', form.find( 'select[name="more_link_type"]' ).val() === 'button' );
			   	}
			});

			$('#fl-builder-settings-section-general select[name="post_type"]').trigger('change');

			// Show more link text field if more_link_type is button.
			self._showField( 'more_link_text', form.find( 'select[name="more_link_type"]' ).val() === 'button' );
			// Hide more link text field if more_link_type is not button.
			self._hideField( 'more_link_text', form.find( 'select[name="more_link_type"]' ).val() !== 'button' );

			if ( form.find( 'input[name="event_enable"]' ).val() === 'yes' || form.find( 'select[name="more_link_type"]' ).val() === 'button' ) {
				self._showSection( button_sections );
			} else {
				self._hideSection( button_sections );
			}

			form.find( 'input[name="event_enable"]' ).on('change', function() {
				self._showSection( button_sections, $(this).val() === 'yes' );
				self._hideSection( button_sections, ( $(this).val() === 'no' && form.find( 'select[name="more_link_type"]' ).val() !== 'button' ) );
			});

			form.find( 'select[name="more_link_type"]' ).on('change', function() {
				self._showSection( button_sections, ( $(this).val() !== 'button' && form.find( 'input[name="event_enable"]' ).val() === 'yes' ) );
				self._showField( 'more_link_text', $(this).val() === 'button' );
			});
		},

		_showSection: function(section_ids, condition = true)
		{
			if ( ! condition ) {
				return;
			}

			var form = $('.fl-builder-settings');

			if ( typeof section_ids === 'object' ) {
				section_ids.forEach( function( section_id ) {
					form.find('#fl-builder-settings-section-' + section_id).show();
				} );
			}

			if ( typeof section_ids === 'string' ) {
				form.find('#fl-builder-settings-section-' + section_ids).show();
			}
		},

		_hideSection: function(section_ids, condition = true)
		{
			if ( ! condition ) {
				return;
			}

			var form = $('.fl-builder-settings');
			
			if ( typeof section_ids === 'object' ) {
				section_ids.forEach( function( section_id ) {
					form.find('#fl-builder-settings-section-' + section_id).hide();
				} );
			}

			if ( typeof section_ids === 'string' ) {
				form.find('#fl-builder-settings-section-' + section_ids).hide();
			}
		},

		_showField: function(field_ids, condition = true)
		{
			if ( ! condition ) {
				return;
			}

			var form = $('.fl-builder-settings');

			if ( typeof field_ids === 'object' ) {
				field_ids.forEach( function( field_id ) {
					form.find('#fl-field-' + field_id).show();
				} );
			}

			if ( typeof field_ids === 'string' ) {
				form.find('#fl-field-' + field_ids).show();
			}
		},

		_hideField: function(field_ids, condition = true)
		{
			if ( ! condition ) {
				return;
			}

			var form = $('.fl-builder-settings');
			
			if ( typeof field_ids === 'object' ) {
				field_ids.forEach( function( field_id ) {
					form.find('#fl-field-' + field_id).hide();
				} );
			}

			if ( typeof field_ids === 'string' ) {
				form.find('#fl-field-' + field_ids).hide();
			}
		}

	});

})(jQuery);
