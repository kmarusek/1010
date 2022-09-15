(function($){
    
    $(function(){ 
 

$('.ContentLibrary-grid-container').isotope({
    itemSelector:'.ContentLibrary-post',
    percentPosition: true,
    masonry: {
        columnWidth: '.ContentLibrary-post-sizer',
        gutter: '.ContentLibrary-post-gutter-sizer'
    }
  });
  //filters items of gallery onClick of li selected
  $('.ContentLibrary-menu-items').on('click','.ContentLibrary-nav-item',function(){
    var filterValue = $(this).attr('data-filter');
    $('.ContentLibrary-grid-container').isotope({ filter:filterValue });
    $('.ContentLibrary-menu-items .ContentLibrary-nav-item').removeClass('active');
    $(this).addClass('active');
  });





});
//Mobile Hamburger menu
$('.ContentLibrary-mobile_menu button').click(function () {
            $(this).toggleClass('is-active');
            $('.ContentLibrary-menu-wrap').toggleClass('is-toggled');
        } );



})(jQuery);

