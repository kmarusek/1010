<?php
/**
 * Created by PhpStorm.
 * User: stefanmitrevski
 * Date: 6.7.22
 * Time: 13:07
 */
?>

jQuery(function($) {
    $("#FeaturedResources-container-<?php echo esc_js($id)?>").slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        padding: '30px',
        swipeToSlide: true,
        prevArrow:"<button type='button' class='slick-prev'><i class='fas fa-arrow-left' aria-hidden='true'></i></button>",
        nextArrow:"<button type='button' class='slick-next'><i class='fas fa-arrow-right' aria-hidden='true'></i></button>",
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2.5,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1.5,
                }
            },
        ]
    });
});
