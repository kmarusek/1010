(function($){
    FLBuilder.registerModuleHelper('uabb-content-toggle', {
        init: function()
        {   
            var form        = $('.fl-builder-settings'),
                advanced    = form.find('select[name=advanced]'),
                border_type = form.find('select[name=border_type]'),
                border_width_head = form.find('select[name=border_width_head]'),
                border_color_head = form.find('select[name=border_color_head]'),
                advanced_sec = form.find('select[name=advanced_sec]'),
                border_type_sec = form.find('select[name=border_type_sec]'),
                border_width_sec = form.find('select[name=border_width_sec]'),
                border_color_sec = form.find('select[name=border_color_sec]'),
                cont1_section = form.find('select[name=cont1_section]'),
                cont2_section = form.find('select[name=cont2_section]');

            advanced.on('change', $.proxy( this._toggleContent, this ) );
            advanced_sec.on('change', $.proxy( this._toggleContent, this ) );
            cont1_section.on('change', $.proxy( this._toggleSection, this ) );
            cont2_section.on('change', $.proxy( this._toggleSection, this ) );

            $( this._toggleContent, this );
            $( this._toggleSection, this );

            var toggle_settings = $('.fl-builder-uabb-content-toggle-settings').find('.fl-builder-settings-tabs a');
            toggle_settings.on('click', this._contentTwoTab);

            var colorTwo =$('.fl-builder-uabb-content-toggle-settings').find('#fl-field-color2 .fl-color-picker-color'); 
            colorTwo.on('click', this._colorTwoToggle);

            var colorOne =$('.fl-builder-uabb-content-toggle-settings').find('#fl-field-color1 .fl-color-picker-color'); 
            colorOne.on('click', this._colorOneToggle);

        },

        _colorTwoToggle: function()
        {
             var form           = $('.fl-builder-settings'),
                node_id         = form.attr('data-node');
             if(!($('.fl-node-' + node_id + ' .uabb-clickable').is(":checked")))
                { 
                    $('.fl-node-' + node_id + ' .switch1').trigger("click");
                    $('.fl-node-' + node_id + ' .switch2').trigger("click");
                    $('.fl-node-' + node_id + ' .switch3').trigger("click");
                    $('.fl-node-' + node_id + ' .switch4').trigger("click");
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-2').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-2').toggle();
                } 
        },
        _colorOneToggle: function()
        {       var form           = $('.fl-builder-settings'),
                node_id         = form.attr('data-node');      
            if(($('.fl-node-' + node_id + ' .uabb-clickable').is(":checked")))
                {
                    $('.fl-node-' + node_id + ' .switch1').trigger("click");
                    $('.fl-node-' + node_id + ' .switch2').trigger("click");
                    $('.fl-node-' + node_id + ' .switch3').trigger("click");
                    $('.fl-node-' + node_id + ' .switch4').trigger("click");
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-2').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-2').toggle();
                }
        },
       _contentTwoTab: function() {
            var anchorHref = $(this).attr('href');
            var form           = $('.fl-builder-settings'),
                node_id         = form.attr('data-node');    
            if( anchorHref == '#fl-builder-settings-tab-general_content2' ){
                 if(!($('.fl-node-' + node_id + ' .uabb-clickable').is(":checked")))
                {
                    
                    $('.fl-node-' + node_id + ' .switch1').trigger("click");
                    $('.fl-node-' + node_id + ' .switch2').trigger("click");
                    $('.fl-node-' + node_id + ' .switch3').trigger("click");
                    $('.fl-node-' + node_id + ' .switch4').trigger("click");
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-2').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-2').toggle();
                }
            } 
            if( anchorHref == '#fl-builder-settings-tab-general_content1' ){
                 if(($('.fl-node-' + node_id + ' .uabb-clickable').is(":checked")))
                {   
                    $('.fl-node-' + node_id + ' .switch1').trigger("click");
                    $('.fl-node-' + node_id + ' .switch2').trigger("click");
                    $('.fl-node-' + node_id + ' .switch3').trigger("click");
                    $('.fl-node-' + node_id + ' .switch4').trigger("click");
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-content-2').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-1').toggle();
                    jQuery('.fl-node-' + node_id + ' .uabb-rbs-section-2').toggle();
                }
            }     
        },
        
        _toggleContent: function() {
            var form        = $('.fl-builder-settings'),
                advanced = form.find('select[name=advanced]').val(),
                border_type = form.find('select[name=border_type]').val(),
                advanced_sec = form.find('select[name=advanced_sec]').val(),
                border_type_sec = form.find('select[name=border_type_sec]').val();

            if( advanced == 'off' ) {
                form.find('#fl-field-border_width_head').hide();
                form.find('#fl-field-border_color_head').hide();
            } else if(advanced == 'on' && border_type != 'none'){
                form.find('#fl-field-border_width_head').show();
                form.find('#fl-field-border_color_head').show();
            }

            if( advanced_sec == 'off' ) {
                form.find('#fl-field-border_width_sec').hide();
                form.find('#fl-field-border_color_sec').hide();
            } else if(advanced_sec == 'on' && border_type_sec != 'none'){
                form.find('#fl-field-border_width_sec').show();
                form.find('#fl-field-border_color_sec').show();
            }
        },

        _toggleSection: function() {
            var form        = $('.fl-builder-settings'),
                cont1_section = form.find('select[name=cont1_section]').val(),
                cont2_section = form.find('select[name=cont2_section]').val();
                
            if( cont1_section == 'content' ) {
                form.find('#fl-builder-settings-section-content1_typo').show();
            } else {
                form.find('#fl-builder-settings-section-content1_typo').hide();
            }

            if( cont2_section == 'content_head2' ) {
                form.find('#fl-builder-settings-section-content2_typo').show();
            } else {
                form.find('#fl-builder-settings-section-content2_typo').hide();
            }
        }
    });

})(jQuery);