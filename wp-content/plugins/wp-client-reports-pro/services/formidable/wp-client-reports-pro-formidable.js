(function( $ ) {
    "use strict";

    if ( $( ".frm-show-form" ).length ) {
        var form_id = $( ".frm-show-form" ).find('input[name = "form_id"]').val();
        var dataString = 'action=wp_client_reports_pro_form_view&plugin=formidable&form_id=' + form_id;
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
