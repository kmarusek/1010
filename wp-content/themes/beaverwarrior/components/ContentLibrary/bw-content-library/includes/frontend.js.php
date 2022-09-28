(function($){
    
  $(function(){ 

      //Mobile Hamburger menu
      $('.ContentLibrary-mobile_menu button').click(function () {
                $(this).toggleClass('is-active');
                $('.ContentLibrary-menu-wrap').toggleClass('is-toggled');
            } );


  });//End hamburger


  $(function(){ 

    var options = {
	    valueNames: [
        'categories',
        'title',
		    { data: ['category']}
	    ],
	    page: <?php echo $module->getPostsPerPage(); ?>,
	    pagination: {
            innerWindow: 4,
            outerWindow: 1
        }
    };


    $('.ContentLibrary-pagination').append('<div id="ContentLibrary-next"><i class="<?php echo $settings->pagination_next_icon;?>"></i></div>');
    $('.ContentLibrary-pagination').prepend('<div id="ContentLibrary-prev"><i class="<?php echo $settings->pagination_prev_icon;?>"></i></div>');

    $('#ContentLibrary-next').on('click', function(){
        $('.pagination .active').next().trigger('click');
    });
    $('#ContentLibrary-prev').on('click', function(){
    	$('.pagination .active').prev().trigger('click');
    });
    
    var contentLibraryList = new List('ContentLibrary-list', options);

    function resetList(){
      contentLibraryList.search();
	    contentLibraryList.filter();
	    contentLibraryList.update();
	    contentLibraryList.filter();
      $(".filter-all").prop('checked', true);
	    $('.filter').prop('checked', false);
	    $('.search').val('');
	    console.log('Reset Successfully!');
    };

    function updateList(){
      var values_category = $("input[name=category]:checked").val();
	    console.log(values_category);
    
      contentLibraryList.filter(function (item) {
		    var categoryFilter = false;
	

		    if(values_category == "all")
		    { 
		    	categoryFilter = true;
		    } else {
		    	categoryFilter = item.values().category == values_category;
        
		    }
		   
		    return categoryFilter
	    });
	    contentLibraryList.update();
	    //console.log('Filtered: ' + values_gender);
    }


    $("input[name=category]").change(updateList);
	
    contentLibraryList.on('updated', function (list) {
      if (list.matchingItems.length > 0) {
        $('.no-result').hide()
      } else {
        $('.no-result').show()
      }
    });

  });// End Isotope and Pagination JS
})(jQuery);

