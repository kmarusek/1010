(function( $ ) {
    "use strict";

    if ( $( ".wpcf7-form" ).length ) {
        var form_id = $( ".wpcf7-form" ).find('input[name = "_wpcf7"]').val();
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=wpcf7&form_id=' + form_id;
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
