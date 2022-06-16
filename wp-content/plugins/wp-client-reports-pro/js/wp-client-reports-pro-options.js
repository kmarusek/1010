(function( $ ) {
    "use strict";

	$( document ).ready(function() {

        $( '.wp-client-reports-pro-color-picker' ).wpColorPicker({defaultColor: '#007cba'});

        $('#wp-client-reports-pro-ga-remove-config').click(function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to remove the google analytics key file?") == true) {
                var dataString = 'action=wp_client_reports_pro_ga_remove_config';
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: dataString,
                    dataType: 'json',
                    success: function(data, err) {
                        if (data.status == 'success') {
                            location.reload();
                        }
                    }
                });
            }
        });

        $('#wp-client-reports-pro-ga-reset-list').click(function(e) {
            e.preventDefault();
            var dataString = 'action=wp_client_reports_pro_ga_reset_list';
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    if (data.status == 'success') {
                        location.reload();
                    }
                }
            });
        });

    });


    $("#wp_client_reports_pro_auto_send_period").change(function(){
        updateAutoSendSettings();
    });

    $("#wp_client_reports_pro_auto_send_day_weekly").change(function(){
        updateAutoSendSettings();
    });

    $("#wp_client_reports_pro_auto_send_day_monthly").change(function(){
        updateAutoSendSettings();
    });

    $("#wp_client_reports_pro_auto_send_time").change(function(){
        updateAutoSendSettings();
    });

    $("#wp_client_reports_pro_auto_send_type").change(function(){
        updateAutoSendSettings();
    });

    updateAutoSendSettings();

    function updateAutoSendSettings() {
        var period = $("#wp_client_reports_pro_auto_send_period").val();
        var day = null;
        if (period == 'weekly') {
            day = $("#wp_client_reports_pro_auto_send_day_weekly").val();
        } else if (period == 'monthly') {
            day = $("#wp_client_reports_pro_auto_send_day_monthly").val();
        }
        var time = $("#wp_client_reports_pro_auto_send_time").val();
        var type = $("#wp_client_reports_pro_auto_send_type").val();

        if (period == 'weekly') {
            $("#wp_client_reports_pro_auto_send_day_weekly").attr("name", "wp_client_reports_pro_auto_send_day").show();
            $("#wp_client_reports_pro_auto_send_time").attr("name", "wp_client_reports_pro_auto_send_time").show();
            $("#wp_client_reports_pro_auto_send_type").attr("name", "wp_client_reports_pro_auto_send_type").show();
            $("#wp-client-reports-pro-example-report-period").show();
            $("#wp_client_reports_pro_auto_send_day_monthly").removeAttr("name").hide();

            var js_date_format = getDateFormat();
            var daysOfWeek = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
            var dayOfWeekNumber = daysOfWeek.indexOf(day);
            var endDate = moment().day(dayOfWeekNumber).hour(time).minute(0).second(0);
            var today = moment();
            if (today > endDate) {
                endDate = moment().add(1, 'weeks').day(dayOfWeekNumber);
            }
            if (type == 'enddaybefore') {
                endDate.subtract(1, 'days');
            }
            var startDate = endDate.clone().subtract(7, 'days');

            $("#wp-client-reports-pro-example-report-start").text(startDate.format(js_date_format));
            $("#wp-client-reports-pro-example-report-end").text(endDate.format(js_date_format));

        } else if (period == 'monthly') {
            $("#wp_client_reports_pro_auto_send_day_monthly").attr("name", "wp_client_reports_pro_auto_send_day").show();
            $("#wp_client_reports_pro_auto_send_time").attr("name", "wp_client_reports_pro_auto_send_time").show();
            $("#wp_client_reports_pro_auto_send_type").attr("name", "wp_client_reports_pro_auto_send_type").show();
            $("#wp-client-reports-pro-example-report-period").show();
            $("#wp_client_reports_pro_auto_send_day_weekly").removeAttr("name").hide();

            var js_date_format = getDateFormat();
            var endDate = null;
            if (day == 'last') { 
                endDate = moment().endOf('month').hour(time).minute(0).second(0);
            } else {
                endDate = moment().date(day).hour(time).minute(0).second(0);
            }
            
            var today = moment();
            if (today > endDate) { 
                
                endDate = moment().add(1, 'months').date(day);
            }
            if (type == 'enddaybefore') {
                endDate.subtract(1, 'days');
            }
            var startDate = null;
            if ((day == 'last' && type == 'endthisday') || (parseInt(day) == 1 && type == 'enddaybefore')) { 
                startDate = endDate.clone().date(1);
            } else {
                startDate = endDate.clone().subtract(1, 'months').add(1, 'days');
            }

            $("#wp-client-reports-pro-example-report-start").text(startDate.format(js_date_format));
            $("#wp-client-reports-pro-example-report-end").text(endDate.format(js_date_format));

        } else if (period == 'never') {
            $("#wp_client_reports_pro_auto_send_day_weekly").removeAttr("name").hide();
            $("#wp_client_reports_pro_auto_send_day_monthly").removeAttr("name").hide();
            $("#wp_client_reports_pro_auto_send_time").removeAttr("name").hide();
            $("#wp_client_reports_pro_auto_send_type").removeAttr("name").hide();
            $("#wp-client-reports-pro-example-report-period").hide();
        }
    }
    

    $('input#wp_client_reports_pro_logo_media_manager').click(function(e) {

        e.preventDefault();
        var image_frame;
        if(image_frame){
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple : false,
            library : {
                type : 'image',
            }
        });

        image_frame.on('close',function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection =  image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            $('input#wp_client_reports_pro_logo').val(ids);
            refreshImage(ids);
        });

        image_frame.on('open',function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection =  image_frame.state().get('selection');
            var ids = $('input#wp_client_reports_pro_logo').val().split(',');
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });

        image_frame.open();
    });

    // Ajax request to refresh the image preview
    function refreshImage(the_id){
        var data = {
            action: 'wp_client_reports_pro_get_image',
            id: the_id
        };
        $.get(ajaxurl, data, function(response) {
            if(response.success === true) {
                $('#wp-client-reports-pro-logo-preview').replaceWith( response.data.image ).show();
            }
        });
    }

    function getDateFormat() {
        if (wp_client_reports_data.moment_date_format) {
            return wp_client_reports_data.moment_date_format;
        } else {
            return 'MM/DD/YYYY';
        }
    }

}(jQuery));
