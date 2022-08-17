
(function($){

    $(function(){ 
       
        'use strict';


    /*
    * Initial Setup for pagination; Find  number of post and pages and save that data in variables.
    */

        //First we have to find how many post we have and the limit of post were suppose to display based on user input.
        var numberOfItems = $(".ThreePostsGrid-container a").length;
        var limitPerPage = <?php echo $module->getPostsPerPage(); ?>;

        //Now we have to hide the items that exceed the number provided by the user.
        $(".ThreePostsGrid-container .ThreePostsGrid-post:gt(" + (limitPerPage - 1) +")").hide();

        //Next we need to find the number of pages based on the limit per page and total posts.
        var totalPages =    Math.ceil(numberOfItems / limitPerPage);

    /*
    * Loop through all of our pages and ensure our pagination navigation is properly being displayed with the correct data.
    */

        //Display our first page and set it to active.
        $(".ThreePostsGrid-pagination").append("<li class='ThreePostsGrid-page-item ThreePostsGrid-current-page active'><a href='javascript:void(0)'>" +  1  + "</a></li>");
        //Now we need to loop through all additional pages and display them in pagination. 
        for( var i = 2; i <= totalPages; i++){
            $(".ThreePostsGrid-pagination").append("<li class='ThreePostsGrid-page-item ThreePostsGrid-current-page'><a href='javascript:void(0)'>" +  i  + "</a></li>");
        }
        //Make sure we append the Next button at the end of the pagination loop.
        $(".ThreePostsGrid-pagination").append("<li id='ThreePostsGrid-next' class='ThreePostsGrid-page-item page-item'><a class='page-link' href='javascript:void(0)'><i class='<?php echo $settings->pagination_next_icon;?>'></i></a></li>");
       

    /*
    * Add the onClick functionality to pagination navigation
    */


        $(".ThreePostsGrid-pagination li.ThreePostsGrid-current-page").on("click", function(){
            if($(this).hasClass("active")){
                return false;
            } else {
                    
                    var currentPage = $(this).index();
                    $(".ThreePostsGrid-pagination li").removeClass("active");
                    $(this).addClass("active");
                    $(".ThreePostsGrid-container .ThreePostsGrid-post").hide();

                    var grandTotal = limitPerPage * currentPage;

                    for(var i = grandTotal - limitPerPage; i < grandTotal ; i++ ) {
                        $(".ThreePostsGrid-container .ThreePostsGrid-post:eq(" + i + ")").show();
                    }
                   
            }
            

        });

        $("#ThreePostsGrid-next").on("click", function(){
           var currentPage = $(".ThreePostsGrid-pagination li.active").index();
           if ( currentPage === totalPages) {
                return false;
           } else {
                currentPage++;
                $(".ThreePostsGrid-pagination li").removeClass("active");
                $(".ThreePostsGrid-container .ThreePostsGrid-post").hide();

                var grandTotal = limitPerPage * currentPage;

                for(var i = grandTotal - limitPerPage; i < grandTotal ; i++ ) {
                    $(".ThreePostsGrid-container .ThreePostsGrid-post:eq(" + i + ")").show();
                }
                $(".ThreePostsGrid-pagination li.ThreePostsGrid-current-page:eq(" + (currentPage - 1) +")").addClass("active");
           }
        });

        $("#ThreePostsGrid-prev").on("click", function(){
           var currentPage = $(".ThreePostsGrid-pagination li.active").index();
           if ( currentPage === 1) {
                return false;
           } else {
                currentPage--;
                $(".ThreePostsGrid-pagination li").removeClass("active");
                $(".ThreePostsGrid-container .ThreePostsGrid-post").hide();

                var grandTotal = limitPerPage * currentPage;

                for(var i = grandTotal - limitPerPage; i < grandTotal ; i++ ) {
                    $(".ThreePostsGrid-container .ThreePostsGrid-post:eq(" + i + ")").show();
                }
                $(".ThreePostsGrid-pagination li.ThreePostsGrid-current-page:eq(" + (currentPage - 1) +")").addClass("active");
           }
        });


    });
})(jQuery);
