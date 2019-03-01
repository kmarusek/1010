function beaverBuilderDidReloadNodeHook(){
    (function($){
        $('.field-connection-variable-value')
        .each(function(){   
            // Get the variable name
            var current_variable_name = $( this ).data( 'variable-name' ),
            // Get the prefix
            current_variable_prefix   = $( this ).data( 'variable-prefix' ),
            // Get the value
            current_variable_value    = $( this ).data( 'variable-value' ),
            // Get the sufix
            current_variable_suffix   = $( this ).data( 'variable-suffix' ),
            // Get the label
            field_connection_label    = $( this ).parents( '.fl-field' ).find( '.fl-field-connection-label' );

            if ( !field_connection_label.data( 'added-variable-name' ) ){
                field_connection_label
                .data( 'added-variable-name', true )
                .append( " <code class=\"bbvo-variable-name\">{" + current_variable_name + "}</code>" );

                field_connection_label
                .find( 'code.bbvo-variable-name' )
                .tooltip({
                    html    : true,
                    title   : current_variable_prefix + current_variable_value + current_variable_suffix
                });
            }
        });
    })(jQuery);
}

/**
 * Function used to remove some unnecessary connection fields after
 * we've added our variable connections. This function is called after every 
 * setting (i.e., one with an added connection property) is added
 * to a form.
 *
 * @param  {string} selector The name of the field
 *
 * @return {void}         
 */
function beaverBuilderUpdateFieldConnectionsObject( selector ){
    (function($){
        // Get the data
        var menu_data = FLThemeBuilderFieldConnections._menus[selector];
        // If we have variable options, then proceed in removing the extra connections
        if ( FLThemeBuilderFieldConnections._menus[selector][bbvo_object.variable_option_key_connection] !== undefined ){
            // Loop through our items to remove from the array
            for ( var i=0; i<bbvo_object.connection_remove_menu_items.length; i++){
                // Get the current values for the key
                var current_item_to_remove = bbvo_object.connection_remove_menu_items[i];
                // Remove it from the object
                delete FLThemeBuilderFieldConnections._menus[selector][current_item_to_remove];
            }
        }
    })(jQuery);
}

(function($){
    $(function(){
        FLBuilder
        .addHook( 'didStartNodeLoading', function( node ){
            beaverBuilderDidReloadNodeHook();
        });
    });
})(jQuery);