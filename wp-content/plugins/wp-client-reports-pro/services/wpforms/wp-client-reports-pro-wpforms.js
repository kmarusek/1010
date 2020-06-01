(function( $ ) {
    "use strict";

    if ( $( ".wpforms-form" ).length ) {
        var form_id = $( ".wpforms-form" ).data('formid');
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=wpforms&form_id=' + form_id;
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
