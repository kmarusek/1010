(function(){

    var url = new URL(window.location.href);
    var job_id = url.searchParams.get('id');
    var getJobsData = "https://boards-api.greenhouse.io/v1/boards/1010data/jobs/"+job_id;


    //creating function to build html after API response
    function buildHtml(data, departmentName, content) {

        var html = `
        <div class="OpenPositionDetails-inner">
                 <h6 class="OpenPositionDetails-department">${departmentName}</h6>
                 <h2 class="OpenPositionDetails-title">${data.title}</h2>
                 <p class="OpenPositionDetails-location">${data.location.name}</p>
                 <div class="OpenPositionDetails-content">${content}</div>
        </div>`;

        document.querySelector(".OpenPositionDetails-details").innerHTML = html;
    }

    fetch(getJobsData).then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
        var departmentName = data.departments[0].name.split(" - ");
        departmentName = departmentName[0];
        var content = data.content;
        content = content.replace(/&nbsp;/g, ' ').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
        buildHtml(data, departmentName, content);
    });
})(jQuery);