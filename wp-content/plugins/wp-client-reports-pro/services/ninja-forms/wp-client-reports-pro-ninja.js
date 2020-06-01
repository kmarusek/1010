// Create a new object for custom validation of a custom field.
var WPClientReportsProNinjaFormsView = Marionette.Object.extend( {
    initialize: function() {
        this.listenTo( nfRadio.channel( 'form' ), 'render:view', this.registerFormView );
    },
    registerFormView: function( view ) {
        var formModel = view.model;
        var form_id = formModel.get( 'id' );
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=ninja_forms&form_id=' + form_id;
        jQuery.ajax({
            type: "POST",
            url: wp_client_reports_pro.ajax_url,
            data: dataString,
            dataType: 'json',
            success: function(data, err) {
                
            }
        });
    },
});

jQuery( document ).ready( function( $ ) {
    new WPClientReportsProNinjaFormsView();
});