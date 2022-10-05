(function($){
    
    $(function(){ 
   
    // Returns an array of maxLength (or less) page numbers
    // where a 0 in the returned array denotes a gap in the series.
    // Parameters:
    //   totalPages:     total number of pages
    //   page:           current page
    //   maxLength:      maximum size of returned array


    function getPageList(totalPages, page, maxLength) {
        if (maxLength < 5) throw "maxLength must be at least 5";

        function range(start, end) {
            return Array.from(Array(end - start + 1), (_, i) => i + start); 
        }

        var sideWidth = maxLength < 9 ? 1 : 2;
        var leftWidth = (maxLength - sideWidth*2 - 3) >> 1;
        var rightWidth = (maxLength - sideWidth*2 - 2) >> 1;
        if (totalPages <= maxLength) {
            // no breaks in list
            return range(1, totalPages);
        }
        if (page <= maxLength - sideWidth - 1 - rightWidth) {
            // no break on left of page
            return range(1, maxLength - sideWidth - 1)
                .concat(0, range(totalPages - sideWidth + 1, totalPages));
        }
        if (page >= totalPages - sideWidth - 1 - rightWidth) {
            // no break on right of page
            return range(1, sideWidth)
                .concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
        }
        // Breaks on both sides
        return range(1, sideWidth)
            .concat(0, range(page - leftWidth, page + rightWidth),
                    0, range(totalPages - sideWidth + 1, totalPages));
    }
    
    // Number of items and limits the number of items per page
    var numberOfItems = $(".ThreePostsGrid-container .ThreePostsGrid-post").length;
    var limitPerPage = <?php echo $module->getPostsPerPage(); ?>;
    // Total pages rounded upwards
    var totalPages = Math.ceil(numberOfItems / limitPerPage);
    // Number of buttons at the top, not counting prev/next,
    // but including the dotted buttons.
    // Must be at least 5:
    var paginationSize = 7; 
    var currentPage;

    function showPage(whichPage) {
        if (whichPage < 1 || whichPage > totalPages) return false;
        currentPage = whichPage;
        $(".ThreePostsGrid-container .ThreePostsGrid-post").hide()
            .slice((currentPage-1) * limitPerPage, 
                    currentPage * limitPerPage).show();
        // Replace the navigation items (not prev/next):            
        $(".ThreePostsGrid-pagination li").slice(1, -1).remove();
        getPageList(totalPages, currentPage, paginationSize).forEach( item => {
            $("<li>").addClass("ThreePostsGrid-page-item")
                     .addClass(item ? "ThreePostsGrid-current-page" : "disabled")
                     .toggleClass("active", item === currentPage).append(
                $("<a>").addClass("page-link").attr({
                    href: "javascript:void(0)"}).text(item || "...")
            ).insertBefore("#ThreePostsGrid-next");
        });
        // Disable prev/next when at first/last page:
        $("#ThreePostsGrid-prev").toggleClass("disabled", currentPage === 1);
        $("#ThreePostsGrid-next").toggleClass("disabled", currentPage === totalPages);
        return true;
    }

    // Include the prev/next buttons:
    $(".ThreePostsGrid-pagination.pagination").append(
        $("<li>").addClass("ThreePostsGrid-page-item").attr({ id: "ThreePostsGrid-prev" }).append(
            $("<a>").addClass("page-link").attr({
                href: "javascript:void(0)"}).html("<i class='<?php echo $settings->pagination_prev_icon;?>'></i>")
        ),
        $("<li>").addClass("ThreePostsGrid-page-item").attr({ id: "ThreePostsGrid-next" }).append(
            $("<a>").addClass("page-link").attr({
                href: "javascript:void(0)"}).html("<i class='<?php echo $settings->pagination_next_icon;?>'></i>")
        )
    );
    // Show the page links
    $(".ThreePostsGrid-container").show();
    showPage(1);

    // Use event delegation, as these items are recreated later    
    $(document).on("click", ".ThreePostsGrid-pagination li.ThreePostsGrid-current-page:not(.active)", function () {
        return showPage(+$(this).text());
    });
    $("#ThreePostsGrid-next").on("click", function () {
        return showPage(currentPage+1);
    });

    $("#ThreePostsGrid-prev").on("click", function () {
        return showPage(currentPage-1);
    });


 

    });
})(jQuery);