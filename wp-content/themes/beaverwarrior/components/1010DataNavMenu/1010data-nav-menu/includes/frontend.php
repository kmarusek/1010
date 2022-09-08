<?php
wp_enqueue_style('lineicons', 'https://cdn.lineicons.com/3.0/lineicons.css');
?>

<nav id="DataNavMenu-<?php echo $id; ?>" class="DataNavMenu">
    <div class="DataNavMenu-top_bar">
        <a href="<?php echo $settings->top_bar_link; ?>"><?php echo $settings->top_bar_text; ?></a>
    </div>
    <!--desktop-->
    <div class="DataNavMenu-main_wrapper <?php echo 'DataNavMenu-'.$settings->menu_type;?> ">
        <div class="DataNavMenu-logo_wrapper desktop">
            <a href="<?php echo home_url();  ?>"><img src="<?php echo $settings->logo_src ?>" alt="main logo"></a>
        </div>
        <div class="DataNavMenu-menu_items">
            <?php //echo main menu
            wp_nav_menu(array(
                'menu' => $settings->active_menu,
                'depth' => 4,
                'menu_class' => 'DataNavMenu-menu_wrapper',
                'walker' => new TPnavMenuWalker()
            ));
            //end main menu?>

            <div class="DataNavMenu-search_form">
                <a href="#">
                    <img src="/wp-content/uploads/2022/09/akar-icons-search.svg"/>
                </a>
                <?php get_search_form(); ?>
            </div>

            <div class="DataNavMenu-right_menu">
                <div class="Data-sigin_button DataNavMenu-menu_button">
                    <a href="<?php echo $settings->signin_button_link;?>"><?php echo $settings->signin_button; ?></a>
                </div>
                <div class="Data-primary_button DataNavMenu-menu_button">
                    <a href="<?php echo $settings->button_link;?>"><?php echo $settings->right_button; ?></a>
                </div>
            </div>
        </div>
        <!-- end desktop-->

        <!-- Mobile-->

        <div class="DataNavMenu-toggle_wrapper">
            <div class="DataNavMenu-logo_wrapper mobile">
                <a href="<?php echo home_url();  ?>"><img src="<?php echo $settings->logo_src ?>" alt="main logo"></a>
            </div>

            <div class="DataNavMenu-mobile_menu">
                <button class="hamburger hamburger--collapse" type="button">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
                </button>
            </div>
        </div>
        <!-- end mobile-->

        <div class="DataNavMenu-menu_buttons">
            <div class="Data-sigin_button DataNavMenu-menu_button">
                <a href="<?php echo $settings->signin_button_link;?>"><?php echo $settings->signin_button; ?><img src="/wp-content/uploads/2022/09/frame.svg"/></a>
            </div>
            <div class="Data-primary_button DataNavMenu-menu_button">
                <a href="<?php echo $settings->button_link;?>"><?php echo $settings->right_button; ?></a>
            </div>
        </div>

    </div>
</nav>