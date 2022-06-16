(function( $ ) {
    "use strict";

	$( document ).ready(function() {

        $('.wp-client-reports-pro-note-select-icon a').click(function(e) {
            e.preventDefault();
            $('.wp-client-reports-pro-note-select-icon a').each(function( index ) {
                $(this).removeClass('selected');
            });
            $(this).addClass('selected');
            var iconType = $(this).data('icon');
            console.log(iconType);
            $("input[name='note_icon_type']").val(iconType);
        });

        var js_date_format = getDateFormat();
        var date = moment().format(js_date_format);

        $('#note-date').val(date);

        $('#note-date').datepicker();

        $("#wp-client-reports-pro-add-new-note").click(function(e) {
            var js_date_format = getDateFormat();
            var date = moment().format(js_date_format);
            $('#note-date').val(date);
            $('#note-text').val('');
            $("input[name='note_id']").val('');
            $('.wp-client-reports-pro-note-select-icon a').removeClass('selected');
            $(".wp-client-reports-pro-note-select-icon a[data-icon='info']").addClass('selected');
            $("input[name='note_icon_type']").val('info');
            $("input[name='note_action']").val('addedit');
            $("#wp-client-reports-pro-note-status").hide();
            $("#wp-client-reports-pro-add-note").show();
        });

        $("#wp-client-reports-pro-note-delete").click(function(e) {
            e.preventDefault();
            $("input[name='note_action']").val('delete');
            $("#wp-client-reports-pro-add-note").submit();
        });

        $( "#wp-client-reports-pro-notes-list" ).on( "click", "a.edit-note", function(e) {
            e.preventDefault();
            var note_id = $(this).data('id');
            var note_date = $(this).data('date');
            var note_icon = $(this).data('icon');
            var note_text = $(this).siblings('.wp-client-reports-note-text-contents').html();
            $("input[name='note_id']").val(note_id);
            $('#note-date').val(note_date);
            $('#note-text').val(note_text);
            $('.wp-client-reports-pro-note-select-icon a').removeClass('selected');
            $(".wp-client-reports-pro-note-select-icon a[data-icon='" + note_icon + "']").addClass('selected');
            $("input[name='note_icon_type']").val(note_icon);
            $("input[name='note_action']").val('addedit');
            $("#wp-client-reports-pro-note-status").hide();
            $("#wp-client-reports-pro-add-note").show();
            tb_show("", "#TB_inline?width=600&height=525&inlineId=wp-client-reports-pro-add-note-modal");
        });


        $("#wp-client-reports-pro-add-note").submit(function(e) {
            e.preventDefault();
            $("#save-note-spinner").show();
            $('#wp-client-reports-pro-add-note .button-primary').prop('disabled', true);
            var dataString = $("#wp-client-reports-pro-add-note").serialize();
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    if (data.status == 'success') {
                        $("#wp-client-reports-pro-add-note").hide();
                        $("#save-note-spinner").hide();
                        $("#wp-client-reports-pro-note-status").addClass('wp-client-reports-success').removeClass('wp-client-reports-error').show().find('p').text(data.message);
                        $('#wp-client-reports-pro-add-note .button-primary').prop('disabled', false);
                        var start_date_utc = moment($(".from_value").val()).utc().format("YYYY-MM-DD");
                        var end_date_utc = moment($(".to_value").val()).utc().format("YYYY-MM-DD");
                        $(document).trigger('wp_client_reports_pro_js_get_notes_data', [start_date_utc, end_date_utc]);
                    } else {
                        $("#wp-client-reports-pro-add-note").hide();
                        $("#save-note-spinner").hide();
                        $("#wp-client-reports-pro-note-status").addClass('wp-client-reports-error').removeClass('wp-client-reports-success').show().find('p').text(data.message);
                        $('#wp-client-reports-pro-add-note .button-primary').prop('disabled', false);
                    }
                }
            });
        });
        

    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-gforms').length) {
            $('#wp-client-reports-pro-gforms').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_gform_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-gform-views-count").text(data.views);
                    $("#wp-client-reports-pro-gform-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-gform-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-gforms').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-ninja-forms').length) {
            $('#wp-client-reports-pro-ninja-forms').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_ninja_forms_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-ninja-forms-views-count").text(data.views);
                    $("#wp-client-reports-pro-ninja-forms-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-ninja-forms-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-ninja-forms').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-wpforms').length) {
            $('#wp-client-reports-pro-wpforms').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_wpforms_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-wpforms-views-count").text(data.views);
                    $("#wp-client-reports-pro-wpforms-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-wpforms-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-wpforms').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-formidable').length) {
            $('#wp-client-reports-pro-formidable').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_formidable_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-formidable-views-count").text(data.views);
                    $("#wp-client-reports-pro-formidable-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-formidable-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-formidable').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-wpcf7').length) {
            $('#wp-client-reports-pro-wpcf7').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_wpcf7_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-wpcf7-views-count").text(data.views);
                    $("#wp-client-reports-pro-wpcf7-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-wpcf7-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-wpcf7').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-caldera-forms').length) {
            $('#wp-client-reports-pro-caldera-forms').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_caldera_forms_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-caldera-forms-views-count").text(data.views);
                    $("#wp-client-reports-pro-caldera-forms-entries-count").text(data.entries);
                    $("#wp-client-reports-pro-caldera-forms-conversion").text(data.conversion + '%');
                    $('#wp-client-reports-pro-caldera-forms').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-uptime-robot').length) {
            $('#wp-client-reports-pro-uptime-robot').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_uptime_robot_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-uptime-robot-uptime").text(data.uptime + '%');
                    $("#wp-client-reports-pro-uptime-robot-events").text(data.down_events_count);
                    if (data.downtime > 1440) {
                        var days = (data.downtime / 1440).toFixed(1);
                        $("#wp-client-reports-pro-uptime-robot-downtime").text(days);
                        $("#wp-client-reports-pro-uptime-robot-downtime").next("h3").text(wp_client_reports_pro_data.downtime_days_label);
                    } else if (data.downtime > 60) {
                        var hours = (data.downtime / 60).toFixed(1);
                        $("#wp-client-reports-pro-uptime-robot-downtime").text(hours);
                        $("#wp-client-reports-pro-uptime-robot-downtime").next("h3").text(wp_client_reports_pro_data.downtime_hours_label);
                    } else {
                        $("#wp-client-reports-pro-uptime-robot-downtime").text(data.downtime);
                    }
                    $("#wp-client-reports-pro-uptime-robot-events-list").html("");
                    $.each(data.down_events, function( index, down_event ) {
                        var newDownEvent = '<li><strong class="wp-client-reports-name">' + down_event.type + '</strong><span class="wp-client-reports-from-to">' + down_event.downtime_pretty + '</span><span class="wp-client-reports-date">' + down_event.date + '</span></li>';
                        $("#wp-client-reports-pro-uptime-robot-events-list").append(newDownEvent);
        
                    });
                    if (data.down_events_count === 0) {
                        $("#wp-client-reports-pro-uptime-robot-events-list").append('<li class="wp-client-reports-empty">' + wp_client_reports_pro_data.nodowntimeevents + '</li>');
                    }
                    $('#wp-client-reports-pro-uptime-robot').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-pingdom').length) {
            $('#wp-client-reports-pro-pingdom').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_pingdom_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-pingdom-uptime").text(data.uptime + '%');
                    $("#wp-client-reports-pro-pingdom-events").text(data.down_events_count);
                    $("#wp-client-reports-pro-pingdom-daysup").text(data.daysup);
                    $("#wp-client-reports-pro-pingdom-events-list").html("");
                    $.each(data.down_events, function( index, down_event ) {
                        var newDownEvent = '<li><strong class="wp-client-reports-name">' + down_event.type + '</strong><span class="wp-client-reports-from-to">' + down_event.downtime_pretty + '</span><span class="wp-client-reports-date">' + down_event.date + '</span></li>';
                        $("#wp-client-reports-pro-pingdom-events-list").append(newDownEvent);
        
                    });
                    if (data.down_events_count === 0) {
                        $("#wp-client-reports-pro-pingdom-events-list").append('<li class="wp-client-reports-empty">' + wp_client_reports_pro_data.nodowntimeevents + '</li>');
                    }
                    $('#wp-client-reports-pro-pingdom').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        get_notes_data(event, start_date_utc, end_date_utc);
    });

    $(document).on('wp_client_reports_pro_js_get_notes_data', function(event, start_date_utc, end_date_utc){
        get_notes_data(event, start_date_utc, end_date_utc);
    });

    function get_notes_data(event, start_date_utc, end_date_utc) {
        if ($('#wp-client-reports-pro-notes').length) {
            $('#wp-client-reports-pro-notes').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_notes_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-notes-list").html("");
                    $.each(data, function( index, note ) {
                        var editLink = '';
                        if (wp_client_reports_pro_data.user_is_admin == "1") {
                            editLink = " <a href='#' class='edit-note' data-id='" + note.id + "' data-date='" + note.date + "' data-icon='" + note.icon + "'>Edit</a>";
                        }
                        var newNote = '<li><span class="wp-client-reports-icon"><img src="' + note.icon_url + '" width="20" height="20"></span><span class="wp-client-reports-note-text"><span class="wp-client-reports-note-text-contents">' + note.note + '</span>' + editLink + '</span><span class="wp-client-reports-note-date">' + note.date + '</span></li>';
                        $("#wp-client-reports-pro-notes-list").append(newNote);
        
                    });
                    if (data.length === 0) {
                        $("#wp-client-reports-pro-notes-list").append('<li class="wp-client-reports-empty">' + wp_client_reports_pro_data.nonotes + '</li>');
                    }
                    $('#wp-client-reports-pro-notes').removeClass('loading');
                }
            });
        }
    }

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-mailchimp').length) {
            $('#wp-client-reports-pro-mailchimp').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_mailchimp_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-mailchimp-subscribes").text(data.subscribes);
                    $("#wp-client-reports-pro-mailchimp-unsubscribes").text(data.unsubscribes);
                    $("#wp-client-reports-pro-mailchimp-total").text(data.total);
                    if (data.warning) {
                        $("#wp-client-reports-pro-mailchimp-warning").show().find('p').text(data.warning);
                    } else {
                        $("#wp-client-reports-pro-mailchimp-warning").hide();
                    }
                    $('#wp-client-reports-pro-mailchimp').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-woocommerce').length) {
            $('#wp-client-reports-pro-woocommerce').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_woocommerce_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-woocommerce-gross-sales").text(data.gross_sales);
                    $("#wp-client-reports-pro-woocommerce-net-sales").text(data.net_sales);
                    $("#wp-client-reports-pro-woocommerce-orders-placed").text(data.orders_placed);
                    $("#wp-client-reports-pro-woocommerce-items-purchased").text(data.items_purchased);
                    $('#wp-client-reports-pro-woocommerce').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-edd').length) {
            $('#wp-client-reports-pro-edd').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_edd_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-edd-sales").text(data.sales);
                    $("#wp-client-reports-pro-edd-earnings").text(data.earnings);
                    $('#wp-client-reports-pro-edd').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-givewp').length) {
            $('#wp-client-reports-pro-givewp').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_givewp_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-givewp-total-donations").html(data.total);
                    $("#wp-client-reports-pro-givewp-average-donation").html(data.average);
                    $("#wp-client-reports-pro-givewp-total-donors").text(data.donors);
                    $("#wp-client-reports-pro-givewp-total-refunds").text(data.refunds);
                    $('#wp-client-reports-pro-givewp').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-stripe').length) {
            $('#wp-client-reports-pro-stripe').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_stripe_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-stripe-gross").text(data.gross);
                    $("#wp-client-reports-pro-stripe-customers").text(data.customers);
                    $("#wp-client-reports-pro-stripe-refunds").text(data.refunds);
                    $('#wp-client-reports-pro-stripe').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-updraftplus').length) {
            $('#wp-client-reports-pro-updraftplus').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_updraftplus_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-updraftplus-backups").text(data.backups);
                    $("#wp-client-reports-pro-updraftplus-data").text(data.data);
                    $('#wp-client-reports-pro-updraftplus').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-backwpup').length) {
            $('#wp-client-reports-pro-backwpup').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_backwpup_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-backwpup-backups").text(data.backups);
                    $("#wp-client-reports-pro-backwpup-data").text(data.data);
                    $('#wp-client-reports-pro-backwpup').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-wpengine-backups').length) {
            $('#wp-client-reports-pro-wpengine-backups').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_wpengine_backups_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-wpengine-backups-backups").text(data.backups);
                    $("#wp-client-reports-pro-wpengine-backups-schedule").text(data.schedule);
                    $("#wp-client-reports-pro-wpengine-backups-stored").text(data.stored);
                    $('#wp-client-reports-pro-wpengine-backups').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-backupbuddy').length) {
            $('#wp-client-reports-pro-backupbuddy').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_backupbuddy_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-backupbuddy-backups").text(data.backups);
                    $("#wp-client-reports-pro-backupbuddy-data").text(data.data);
                    $('#wp-client-reports-pro-backupbuddy').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-searchwp').length) {
            $('#wp-client-reports-pro-searchwp').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_searchwp_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-searchwp-total-searches").text(data.total_searches);
                    $("#wp-client-reports-pro-searchwp-unique-searches").text(data.unique_searches);
                    $("#wp-client-reports-pro-searchwp-empty-searches").text(data.empty_searches);
                    $('#wp-client-reports-pro-searchwp').removeClass('loading');
                }
            });
        }
    });

    $(document).on('wp_client_reports_js_get_data', function(event, start_date_utc, end_date_utc){
        if ($('#wp-client-reports-pro-google-analytics').length) {
            $('#wp-client-reports-pro-google-analytics').addClass('loading');
            var dataString = 'action=wp_client_reports_pro_ga_data&start=' + start_date_utc + '&end=' + end_date_utc;
            var js_date_format = getDateFormat();
            $.ajax({
                type: "GET",
                url: ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function(data, err) {
                    $("#wp-client-reports-pro-ga-users").text(data.users);
                    $("#wp-client-reports-pro-ga-new-users").text(data.new_users);
                    $("#wp-client-reports-pro-ga-sessions").text(data.sessions);
                    $("#wp-client-reports-pro-ga-sessions-per-user").text(data.sessions_per_user);
                    $("#wp-client-reports-pro-ga-pageviews").text(data.hits);
                    $("#wp-client-reports-pro-ga-pages-per-session").text(data.pageviews_per_session);
                    $("#wp-client-reports-pro-ga-avg-session-duration").text(data.avg_session_duration);
                    $("#wp-client-reports-pro-ga-bounce-rate").text(data.bounce_rate);
                    $('#wp-client-reports-pro-google-analytics').removeClass('loading');
                }
            });
        }
    });

    $(document).on('gform_post_render', function(event, form_id, current_page){
        var dataString = 'action=wp_client_reports_pro_gf_form_view&form_id=' + form_id;
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: dataString,
            dataType: 'json',
            success: function(data, err) {
                
            }
        });
    });

    function getDateFormat() {
        if (wp_client_reports_pro_data.moment_date_format) {
            return wp_client_reports_pro_data.moment_date_format;
        } else {
            return 'MM/DD/YYYY';
        }
    }

}(jQuery));
