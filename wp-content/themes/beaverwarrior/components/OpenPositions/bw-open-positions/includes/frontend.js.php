(function(){
	var ele = document.querySelector(".fl-module-bw-open-positions.fl-node-<?php echo $id; ?>");
	new OpenPositions({
		element: ele,
		rss_url: 'https://boards-api.greenhouse.io/v1/boards/1010data/jobs?content=true'
	})

})();

jQuery(document).ready(function ($) {

});


