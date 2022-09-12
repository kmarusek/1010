<?php
$barClass = 'fa-bars';
$closeClass = 'fa-times';
?>
(function($) {
    var windowsize = $(window).width();

    if(windowsize > 900){
        $('.DataNavMenu-menu_items .menu-item-has-children').hover(function () {
            $('.DataNavMenu-sub_menu').each(function (){
                if( $(this).hasClass('DataNavMenu-type--cta_menu')) {
                    $('.DataNavMenu-depth_2').each(function (){
                        $(this).removeClass('active');
                    });
                    let subMenu3 = $(this).find('.DataNavMenu-depth_2').height();
                    $(this).find('.DataNavMenu-depth_1').height(subMenu3 - 41);
                    let menuDepth1 =  $(this).find('.DataNavMenu-depth_1');
                    $(this).find('.DataNavMenu-depth_1 > li:first').addClass('active');
                    $(this).find('.DataNavMenu-depth_2:first').addClass('active');
                    $(this).find('.DataNavMenu-depth_1 > .DataNavMenu-inner_depth_1').hover(function(){
                        $('.DataNavMenu-inner_depth_1').each(function (){
                            $(this).removeClass('active');
                        });
                        $('.DataNavMenu-depth_2').each(function (){
                            $(this).removeClass('active');
                        });
                        $(this).find('.DataNavMenu-depth_2').addClass('active');
                        let itemHeight = $(this).find('.DataNavMenu-depth_2').height();
                        if(itemHeight + 120 > 367){
                            menuDepth1.height(itemHeight - 41);
                        }else{
                            menuDepth1.height(271);
                        }
                    });
                }
                if( $(this).hasClass('DataNavMenu-type--simple')) {
                    if($(this).find(".DataNavMenu-depth_1").children().length-1 >= 4){
                        $(this).find(".DataNavMenu-depth_1").css({"overflow":"scroll", "max-height":"600px", "padding-bottom": "100px !important"});
                    }
                }
            });
        });
        $(".DataNavMenu-search_form a").click(function () {
            $(this).hide();
            $("#searchform").addClass('active');
        });
    }


})(jQuery);

(function($) {

    //mobile menu toggle
    $(function() {
        $('.DataNavMenu-mobile_menu button').click(function () {
            $(this).toggleClass('is-active');
            $('html, body').toggleClass('is-toggled');

            //if main menu is closed, close sub menus
            if( !$(this).hasClass('is-active')) {
                $('.DataNavMenu-sub_menu').each(function () {
                    $(this).removeClass('is-open');
                    $('.DataNavMenu-expand').addClass('collapsed');
                })
            }
            $('.DataNavMenu-type--full_width .DataNavMenu-depth_1 > li > .DataNavMenu-depth_2').remove();

        } );
    });

    //switch dark / light mode
/*
    $( ".DataNavMenu, .DataNavMenu-toggle_wrapper" ).dblclick(function(e) {
        let menu = $('.DataNavMenu-main_wrapper');
        menu.toggleClass('DataNavMenu-dark');

        if ( menu.hasClass('DataNavMenu-dark')) {
            menu.parent().parent().css('background-color', '#21014f');
        } else {
            menu.parent().parent().css('background-color', 'white');
        }
    });*/
})(jQuery);


(function($) {
    //calculate menu width
    $(function() {
        $('.DataNavMenu-sub_menu').each(function (i){

            if( $(this).hasClass('DataNavMenu-type--cta_menu')){
                $(this).find('.DataNavMenu-depth_1').width(200);
                let subMenu1 = $(this).find('.DataNavMenu-depth_1').width();
                let subMenu2 = $(this).find('.DataNavMenu-depth_2').width();
                $(this).width(subMenu1 + subMenu2 + 240);
            } else if ($(this).hasClass('DataNavMenu-type--2column')) {
                $(this).find('.DataNavMenu-depth_1').width(350);
                let subMenu1 = $(this).find('.DataNavMenu-depth_1').width();
                let subMenu2 = $(this).find('.DataNavMenu-depth_2').width();
                $(this).width(subMenu1 + subMenu2 + 160);
            }

        })


        //mobile menu

        //full width menu,
     //   $('.DataNavMenu-type--full_width .DataNavMenu-depth_2').clone().appendTo($('.DataNavMenu-type--full_width .DataNavMenu-depth_1'));


        //clone mobile menu and button on each sub menu
       // $('.DataNavMenu-right_menu').clone().appendTo($('.DataNavMenu-depth_1'));

        //clone parent menu item and prepend to level 1 sub-menu (back navigation)
        $('.DataNavMenu-menu_wrapper li').each(function (i){
            $(this).find('.DataNavMenu-parent_item').clone().prependTo($(this).find('.DataNavMenu-depth_1'));
        })

        $('.DataNavMenu-inner_depth_1').each(function (i){
            $(this).find('>:first-child').clone().prependTo($(this).find('.DataNavMenu-depth_2'));
        })

        //open submenu
        var windowsize = $(window).width();
        if(windowsize <= 900) {
            $('.DataNavMenu-menu_wrapper > .menu-item-has-children > a').on("click", function (e) {
                e.preventDefault();

                if ($('body').hasClass('is-toggled')) {
                    let expand = $(this).find('.DataNavMenu-expand');
                    let parent_menu = $(this).parent().find('> .DataNavMenu-sub_menu');
                    if (!parent_menu.hasClass('is-open')) {
                        $('.DataNavMenu-sub_menu').each(function () {
                            $(this).removeClass('is-open');
                        })
                        $('.DataNavMenu-expand').each(function () {
                            $(this).addClass('collapsed');
                        });
                        expand.removeClass('collapsed');
                        parent_menu.addClass('is-open');
                    } else {
                        parent_menu.removeClass('is-open');
                        expand.addClass('collapsed');
                    }
                }
            })
            $('.DataNavMenu-type--cta_menu .DataNavMenu-inner_depth_1 > a').on("click", function (e) {
                e.preventDefault();
                if ($('body').hasClass('is-toggled')) {
                    let menu = $(this).parent().find('> .DataNavMenu-depth_2');
                    if (!menu.hasClass('is-open')) {
                        menu.addClass('is-open');
                    }
                }
            })
            $('.DataNavMenu-type--cta_menu .DataNavMenu-inner_depth_1').on("click", ".DataNavMenu-depth_2 > a", function (e) {
                e.preventDefault();
                if ($('body').hasClass('is-toggled')) {
                    let parent_menu = $(this).parent();
                    parent_menu.removeClass('is-open');
                }
            });
        }

    });

  /*  $(function() {
        $('.DataNavMenu-parent_item ').on('click', function() {
            $(this).find('.DataNavMenu-expand').toggleClass('collapsed');
        });
    });*/
})(jQuery);