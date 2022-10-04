(function(){

    var url = new URL(window.location.href);
    var job_id = url.searchParams.get('id');
    var getJobsData = "https://boards-api.greenhouse.io/v1/boards/1010data/jobs/"+job_id+"?questions=true";


    //creating function to build html after API response
    function buildHtml(data, departmentName, content) {

        var html = `
        <div class="OpenPositionDetails-inner">
                 <h6 class="OpenPositionDetails-department">${departmentName}</h6>
                 <h2 class="OpenPositionDetails-title">${data.title}</h2>
                 <p class="OpenPositionDetails-location">${data.location.name}</p>
                 <div class="OpenPositionDetails-content">${content}</div>
        </div>`;

        var htmlForm = `
            <form id="OpenPositionDetails-form-<?php echo esc_attr($id);?>" class="job__form cf">
                            <input name="id" value="${job_id}" type="hidden">
                             <div class="cf">
                        <p class="job__form__helper"><span class="asterisk">*</span> Required</p>
                        <h2 class="section__head">Apply Now!</h2>
                    </div>
        `;

        var htmlFormFields = ``;

        function buildFormInput(field, fieldType, label, required, ast){
            htmlFormFields += `
            <div class="form-group">
             <label for="${field.name}">
                        ${label} <span class="asterisk">${ast}</span>                    </label><br/>
                        <input class="textbox form-control" name="${field.name}" type="${fieldType}" value="" ${required}>
                        							<span class="form-error">Please fill this field.</span>
              </div>`;

        }
        function buildFormTextarea(field){
            htmlFormFields += `
            <div class="form-group">
             <textarea class="form-control hidden"  name="${field.name}" type="" value=""></textarea>
              </div>`;

        }
        function buildFormMultiple(field, label, required, ast){
            htmlFormFields += `
            <div class="form-check">
               <label for="${field.name}">
                        ${label} <span class="asterisk">${ast}</span>
                </label>
                <br/><label for="html">No </label>&nbsp;
             <input type="radio" id="html" name="${field.name}" value="0" ${required}/>
                 <label for="css">Yes </label>&nbsp;
                 <input type="radio" id="css" name="${field.name}" value="1" ${required}/>
             <span class="form-error">Please fill this field.</span>
            </div>
              `;
        }

        data.questions.forEach((question) => {
            var label = question.label;
            var required = question.required;
            var ast = '*';
            if(!required){
                ast = '';
                required = "";
            }else{
                required = "required";
            }
            var fields = question.fields;
            fields.forEach((field) => {
                if (field.type.indexOf('input') > -1) {
                    var fieldType = field.type.split('_');
                    fieldType = fieldType[1];
                    buildFormInput(field, fieldType, label, required, ast);
                } else if (field.type.indexOf('textarea') > -1){
                    buildFormTextarea(field);
                }else{
                    buildFormMultiple(field, label, required, ast);
                }
            });
        });
        htmlForm = htmlForm + htmlFormFields + '<div class="form-group"><div class="g-recaptcha" data-sitekey="6LdZ970hAAAAAO6wMFijylzqylqgrzWD50UoVbl0"></div><span class="form-error recaptcha-error">Please check this, it is required.</span></div><button type="submit" class="btn fl-button" id="submitForm">Submit Application</button><p class="message">Thank you for your submission.</p></form>';
        document.querySelector(".OpenPositionDetails-details").innerHTML = html;
        document.querySelector(".OpenPositionDetails-form").innerHTML = htmlForm;
    }

    fetch(getJobsData).then(function (response) {
        return response.json();
    }).then(function (data) {
        var departmentName = data.departments[0].name.split(" - ");
        departmentName = departmentName[0];
        var content = data.content;
        content = content.replace(/&nbsp;/g, ' ').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&').replace(/&quot;/g, '\"');
        buildHtml(data, departmentName, content);
    });

})(jQuery);

jQuery(document).ready(function ($) {
    $('.OpenPositionDetails-form').on('focus', ':input[name=first_name]', function () {
        // trigger loading api.js (recaptcha.js) script
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://www.google.com/recaptcha/api.js';
        head.appendChild(script);
        // remove focus to avoid js error:
        // Uncaught Error: reCAPTCHA has already been rendered in this element at Object.kh
        $('form:not(.filter) :input:visible:enabled:first').off('focus');
    });
});
  /*  $(".OpenPositionDetails-form").on('click', '#submitForm', function(e) {
        e.preventDefault();
        var url = new URL(window.location.href);
        var job_id = url.searchParams.get('id');
        var urlHost = "https://boards-api.greenhouse.io/v1/boards/1010data/jobs/"+job_id;
        var recaptcha = $("#g-recaptcha-response").val();
        var jsonObj = {};
        //var recaptcha = $("#g-recaptcha-response").val();
        var valid = true;
        $(".OpenPositionDetails-form :input[required]:visible").each(function(){
            var input = $(this);
            var err = input.next(".form-error");
            if(input.attr('type') == 'text'){
                if(input.val() == '') {
                    valid = false;
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                }else{
                    err.hide();
                    input.css('border-bottom', '1px solid #ccc');
                }
                if(input.attr('name') == 'email' && input.val() != ''){
                    if(!isEmail(input.val())) {
                        valid = false;
                        err.text("Please enter a valid email address.");
                        err.css('display', 'block');
                        input.css('border-bottom', '1px solid #cc4b37');
                    }else{
                        err.hide();
                        input.css('border-bottom', '1px solid #ccc');
                    }
                }
            }
            if(input.attr('type') == 'file'){
                if(input.val() == '') {
                    valid = false;
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                } else if (!(/\.(pdf|doc|docx|txt|rtf)$/i).test(input.val())) {
                    valid = false;
                    err.text("Accepted files are pdf, doc, docx, txt and rtf.")
                    err.css('display', 'block');
                    input.css('border-bottom', '1px solid #cc4b37');
                }else{
                    err.hide();
                    input.css('border-bottom', '1px solid #ccc');
                }
            }
            if(input.attr('type') == 'radio'){
                if ($('input[name='+input.attr("name")+']:checked').length <= 0){
                    valid = false;
                    err.css('display', 'block');
                }else{
                    err.hide();
                }
            }
          //  console.log(input.attr('name'));
           // console.log(input.attr('type'));
            // console.log(input.val());
        });
        var phone = $(".OpenPositionDetails-form :input[name=phone]");
        var phoneErr = $(".OpenPositionDetails-form :input[name=phone]").next(".form-error");
        if(phone.val() != '' && !phone_validate(phone.val())){
            phoneErr.text('Please enter a valid phone number.');
            valid = false;
            phoneErr.css('display', 'block');
            phone.css('border-bottom', '1px solid #cc4b37');
        }else{
            phoneErr.hide();
            phone.css('border-bottom', '1px solid #ccc');
        }
        var coverLetter = $(".OpenPositionDetails-form :input[name=cover_letter]");
        var coverLetterErr = $(".OpenPositionDetails-form :input[name=cover_letter]").next(".form-error");
        if(coverLetter.val() != '' && !(/\.(pdf|doc|docx|txt|rtf)$/i).test(coverLetter.val())){
            coverLetterErr.text('Accepted files are pdf, doc, docx, txt and rtf.');
            valid = false;
            coverLetterErr.css('display', 'block');
            coverLetter.css('border-bottom', '1px solid #cc4b37');
        }else{
            coverLetterErr.hide();
            coverLetter.css('border-bottom', '1px solid #ccc');
        }
        if (recaptcha === "") {
            valid = false;
            $(".recaptcha-error").show();
            $(".rc-anchor-normal").css('border', '2px solid #cc4b37');
            return;
        }else{
            $(".recaptcha-error").hide();
        }
        // var form = $(this).not("#g-recaptcha-response, #captcha").serialize(),
        // 		action = $(this).attr('action');
        if(valid == true)  {
            $('#g-recaptcha-response').attr('disabled', 'disabled');
            $('#captcha').attr('disabled', 'disabled');
            $(".recaptcha-error").hide();
            $("#submitForm").attr("disabled", "disabled");
            $("#submitForm").attr("style", "background-color:#ccc !important");
            $(".OpenPositionDetails-form :input").each(function(){
                var input = $(this);
                if(input.attr('name') == 'first_name' || input.attr('name') == 'last_name' || input.attr('name') == 'email'){
                    jsonObj[input.attr('name')] = input.val();
                }
               /* if(input.attr('type') == 'radio'){
                    jsonObj[input.attr('name')] = $('input[name='+input.attr("name")+']:checked').val();
                }else{
                    jsonObj[input.attr('name')] = input.val();
                }*/
    /*        });
            console.log(urlHost);
            console.log(jsonObj);

            //jsonObj['action'] = 'open_positions_ajax_call';
            $.ajax({
                method: "POST",
                url: my_ajax_object.ajaxurl,
                data: {action: "open_positions_ajax_call"},
                success: function(data) {
                    console.log(data);
                    var message = $(".OpenPositionDetails-form .message");
                    message.css('display', 'block');
                },
                error: function(error) {
                    console.log(error);
                    var message = $(".OpenPositionDetails-form .message");
                    message.text('There was an error with you submission');
                    message.css('display', 'block');
                }
            });
      /*      $.post('http://localhost:8888/dev1010data/wp-admin/admin-ajax.php', JSON.stringify(jsonObj), function(response) {
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
    /*    }else{
            return;
        }
    });
    function phone_validate(phno)
    {
        var regexPattern=new RegExp(/^\d{7,}$/);    // regular expression pattern
        return regexPattern.test(phno);
    }
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});
*/