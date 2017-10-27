<?php $primary_uniqid = uniqid(); 
$has_hello_bar = get_theme_mod('skeleton_warrior_hellobar') !== false &&
                 get_theme_mod('skeleton_warrior_hellobar') !== "";
?>

<header class="SiteHeader">
    <div class="SiteHeader-container">
        <div class="SiteHeader-banner">
            <a class="SiteHeader-brand<?php if (has_nav_menu('secondary_navigation')) { ?> SiteHeader-brand--centered<?php } ?><?php if ($has_hello_bar) { ?> SiteHeader-banner--with_notice_bar<?php } ?>" href="/">
                <?php $custom_logo_id = get_theme_mod( 'custom_logo' );
                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                if ( has_custom_logo() ) {
                    echo '<img class="SiteHeader-logo" src="'. esc_url( $logo[0] ) .'" alt="' . get_bloginfo('name', 'display') . '">';
                } else {
                    echo esc_attr( get_bloginfo( 'name' ) );
                } ?>
            </a>
            
            <?php if (is_active_sidebar('site_header_banner')) { ?>
                <nav class="SiteHeader-navigation SiteHeader-navigation--exposed<?php if ($has_hello_bar) { ?> SiteHeader-banner--with_notice_bar<?php } ?>">
                    <?php dynamic_sidebar('site_header_banner'); ?>
                </nav>
            <?php } ?>
            
            <button type="button" class="SiteHeader-toggle Button Button--menu_toggle" data-toggle="offcanvas" data-target="#<?php echo $primary_uniqid; ?>">
                <span class="Button--menu_toggle-bar Button--menu_toggle-bar--first"></span>
                <span class="Button--menu_toggle-bar Button--menu_toggle-bar--second"></span>
                <span class="Button--menu_toggle-bar Button--menu_toggle-bar--third"></span>
                <span class="sr-only">Open/close menu</span>
            </button>
        </div>
        <div class="SiteHeader-content<?php if ($has_hello_bar) { ?> SiteHeader-content--with_notice_bar<?php } ?> is-Offcanvas--closed" id="<?php echo $primary_uniqid; ?>">
            <div class="Offcanvas-scroller">
                <?php if ($has_hello_bar) { ?>
                    <div class="SiteHeader-notice_bar">
                        <div class="SiteHeader-notice">
                            <?php echo get_theme_mod('skeleton_warrior_hellobar'); ?>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if (has_nav_menu('secondary_navigation')) { ?>
                    <nav class="SiteHeader-navigation SiteHeader-navigation--second_menu">
                        <?php wp_nav_menu( array(
                                'theme_location' => 'secondary_navigation',
                                'menu_class' => 'NavMenu NavMenu--main_menu',
                                'walker' => new DragonfruitNavWalker(),
                                'items_wrap' => '<ul id="%1$s" class="NavMenu NavMenu--main_menu %2$s">%3$s',
                                )); ?>
                    </nav>
                <?php } ?>
                
                <?php if (has_nav_menu('primary_navigation') || is_active_sidebar('site_header_primary_navigation')) { ?>
                    <nav class="SiteHeader-navigation SiteHeader-navigation--first_menu">
                        <?php wp_nav_menu( array(
                                'theme_location' => 'primary_navigation',
                                'menu_class' => 'NavMenu NavMenu--main_menu',
                                'walker' => new DragonfruitNavWalker(),
                                'items_wrap' => '<ul id="%1$s" class="NavMenu NavMenu--main_menu %2$s">%3$s',
                                )); ?>
                        <?php dynamic_sidebar('site_header_primary_navigation'); ?>
                    </nav>
                <?php } ?>
                
                <?php if (has_nav_menu('tertiary_navigation')) { ?>
                    <nav class="SiteHeader-navigation SiteHeader-navigation--third_menu">
                        <?php wp_nav_menu( array(
                                'theme_location' => 'tertiary_navigation',
                                'menu_class' => 'NavMenu NavMenu--main_menu',
                                'walker' => new DragonfruitNavWalker(),
                                'items_wrap' => '<ul id="%1$s" class="NavMenu NavMenu--main_menu %2$s">%3$s',
                                )); ?>
                    </nav>
                <?php } ?>
            </div>
        </div>
    </div>
</header>