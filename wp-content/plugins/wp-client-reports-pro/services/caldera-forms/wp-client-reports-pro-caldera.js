(function( $ ) {
    "use strict";

    if ( $( ".caldera_forms_form" ).length ) {
        var form_id = $( ".caldera_forms_form" ).data('form-id');
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=caldera_forms&form_id=' + form_id;
        $.ajax({
            type: "POST",
            url: wp_client_reports_pro.ajax_url,
            data: dataString,
            dataType: 'json',
            success: function(data, err) {
                
            }
        });
    }

}(jQuery));
