jQuery(document).ready(function ($) {
    $(".OpenPositionDetails-form").on('click', '#submitForm', function (e) {
        e.preventDefault();
        var fd = new FormData();
        var url = new URL(window.location.href);
        var job_id = url.searchParams.get('gh_jid');
        var urlHost = "https://boards-api.greenhouse.io/v1/boards/1010data/jobs/" + job_id;
        var recaptcha = $("#g-recaptcha-response").val();
        var jsonObj = {};
        //var recaptcha = $("#g-recaptcha-response").val();
        var valid = true;
        $(".OpenPositionDetails-form :input[required]:visible").each(function () {
            var input = $(this);
            var err = input.next(".form-error");
            if (input.attr('type') == 'text') {
                if (input.val() == '') {
                    valid = false;
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                } else {
                    err.hide();
                    input.css('border-bottom', '1px solid #ccc');
                }
                if (input.attr('name') == 'email' && input.val() != '') {
                    if (!isEmail(input.val())) {
                        valid = false;
                        err.text("Please enter a valid email address.");
                        err.css('display', 'block');
                        input.css('border-bottom', '1px solid #cc4b37');
                    } else {
                        err.hide();
                        input.css('border-bottom', '1px solid #ccc');
                    }
                }
            }
            if (input.attr('type') == 'file') {
                if (input.val() == '') {
                    valid = false;
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                } else if (!(/\.(pdf|doc|docx|txt|rtf)$/i).test(input.val())) {
                    valid = false;
                    err.text("Accepted files are pdf, doc, docx, txt and rtf.")
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                } else {
                    err.hide();
                    input.css('border-bottom', '1px solid #ccc');
                }
            }
            if (input.attr('type') == 'radio') {
                if ($('input[name=' + input.attr("name") + ']:checked').length <= 0) {
                    valid = false;
                    err.css('display', 'block');
                } else {
                    err.hide();
                }
            }
            //  console.log(input.attr('name'));
            // console.log(input.attr('type'));
            // console.log(input.val());
        });
        var phone = $(".OpenPositionDetails-form :input[name=phone]");
        var phoneErr = $(".OpenPositionDetails-form :input[name=phone]").next(".form-error");
        if (phone.val() != '' && !phone_validate(phone.val())) {
            phoneErr.text('Please enter a valid phone number.');
            valid = false;
            phoneErr.css('display', 'block');
            phone.css('border-bottom', '1px solid #cc4b37');
        } else {
            phoneErr.hide();
            phone.css('border-bottom', '1px solid #ccc');
        }
        var coverLetter = $(".OpenPositionDetails-form :input[name=cover_letter]");
        var coverLetterErr = $(".OpenPositionDetails-form :input[name=cover_letter]").next(".form-error");
        if (coverLetter.val() != '' && !(/\.(pdf|doc|docx|txt|rtf)$/i).test(coverLetter.val())) {
            coverLetterErr.text('Accepted files are pdf, doc, docx, txt and rtf.');
            valid = false;
            coverLetterErr.css('display', 'block');
            coverLetter.css('border-bottom', '1px solid #cc4b37');
        } else {
            coverLetterErr.hide();
            coverLetter.css('border-bottom', '1px solid #ccc');
        }
        if (recaptcha === "") {
            valid = false;
            $(".recaptcha-error").show();
            $(".rc-anchor-normal").css('border', '2px solid #cc4b37');
            return;
        } else {
            $(".recaptcha-error").hide();
        }
        // var form = $(this).not("#g-recaptcha-response, #captcha").serialize(),
        // 		action = $(this).attr('action');
        if (valid == true) {
            $('#g-recaptcha-response').attr('disabled', 'disabled');
            $('#captcha').attr('disabled', 'disabled');
            $(".recaptcha-error").hide();
            $("#submitForm").attr("disabled", "disabled");
            $("#submitForm").attr("style", "background-color:#ccc !important");
            $(".OpenPositionDetails-form :input").each(function () {
                var input = $(this);
                /*if (input.attr('name') == 'first_name' || input.attr('name') == 'last_name' || input.attr('name') == 'email') {
                    jsonObj[input.attr('name')] = input.val();
                }*/
                 if(input.val()){
                     if(input.attr('type') == 'radio'){
                         jsonObj[input.attr('name')] = $('input[name='+input.attr("name")+']:checked').val();
                         fd.append(input.attr('name'), $('input[name='+input.attr("name")+']:checked').val());
                     } else if (input.attr('type') == 'file'){
                             fd.append('file[]', input[0].files[0]);
                     }
                     else if (input.attr('name') != 'g-recaptcha-response' && input.attr('name') != 'undefined'){
                         jsonObj[input.attr('name')] = input.val();
                         fd.append(input.attr('name'), input.val());
                     }
                 }
            });
//            console.log(jsonObj);
            fd.append('action', 'open_positions_ajax_call');
            fd.append('url', urlHost);
            //jsonObj['action'] = 'open_positions_ajax_call';
            $.ajax({
                type: "POST",
                url: my_ajax_object.ajaxurl,
                data: fd,
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                    var message = $(".OpenPositionDetails-form .message");
                    message.css('display', 'block');
                    window.location.href = '/form-confirmation';
                },
                error: function(error) {
                    console.log(error);
                    var message = $(".OpenPositionDetails-form .message");
                    message.text('There was an error with you submission');
                    message.css('display', 'block');
                }
            });
            /*      $.post(my_ajax_object.ajaxurl, JSON.stringify(jsonObj), function(response) {
                      var message = $(".OpenPositionDetails-form .message");
                      $(".OpenPositionDetails-form .message").text(response);
                      message.css('display', 'block');
                  });
                  //return false;

                  /*
                  $.ajax({
                      method: "POST",
                      url: urlHost,
                      crossDomain: true,
                      data: JSON.stringify(jsonObj),
                      contentType: "application/json",
                      dataType: "json",
                      headers: {"Authorization": "Basic 62a32faac59b9475d0124ffe62935f7c-1"},
                      success: function(data) {
                          console.log(data);
                          var message = $(".OpenPositionDetails-form .message");
                          message.css('display', 'block');
                      },
                      error: function() {
                          var message = $(".OpenPositionDetails-form .message");
                          message.text('There was an error with you submission');
                          message.css('display', 'block');
                      }
                  });*/
        } else {
            return;
        }
    });

    function phone_validate(phno) {
        var regexPattern = new RegExp(/^\d{7,}$/);    // regular expression pattern
        return regexPattern.test(phno);
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});