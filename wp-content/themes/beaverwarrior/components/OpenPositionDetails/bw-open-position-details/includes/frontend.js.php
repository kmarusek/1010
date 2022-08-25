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
            <form class="job__form cf" action="${url}" enctype="multipart/form-data" method="post">
                            <input name="jobId" value="${job_id}" type="hidden">
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
                        <input class="textbox form-control" required="${required}  name="${field.name}" type="${fieldType}" value="">
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
                <br/>
                 <input type="radio" id="html" name="${field.name}" value="0" required="${required}>
                 <label for="html">No</label>
                 <input type="radio" id="css" name="${field.name}" value="1" required="${required}>
                 <label for="css">Yes</label>
            </div>
              `;
        }

        data.questions.forEach((question) => {
            var label = question.label;
            var required = question.required;
            var ast = '*';
            if(!required){
                ast = '';
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
        htmlForm = htmlForm + htmlFormFields + '<button type="submit" class="btn" id="submitForm">Submit Application</button></form>';
        document.querySelector(".OpenPositionDetails-details").innerHTML = html;
        document.querySelector(".OpenPositionDetails-form").innerHTML = htmlForm;
    }

    fetch(getJobsData).then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
        var departmentName = data.departments[0].name.split(" - ");
        departmentName = departmentName[0];
        var content = data.content;
        content = content.replace(/&nbsp;/g, ' ').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&').replace(/&quot;/g, '\"');
        buildHtml(data, departmentName, content);
    });
})(jQuery);