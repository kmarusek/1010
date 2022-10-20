(function( $ ) {
    "use strict";

    if ( $( ".happyforms-form" ).length ) {
        var form_id = $( ".happyforms-form" ).find('input[name = "happyforms_form_id"]').val();
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=happyforms&form_id=' + form_id;
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
