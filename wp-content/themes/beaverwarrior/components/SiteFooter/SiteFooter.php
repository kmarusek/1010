<?php
$has_legal_decl = get_theme_mod('skeleton_warrior_legaldecl') !== false &&
                  get_theme_mod('skeleton_warrior_legaldecl') !== "";
?>

<?php dynamic_sidebar('hidden_modals'); ?>

<footer class="SiteFooter">
    <div class="SiteFooter-container">
        <?php if (has_nav_menu('primary_navigation_footer')) { ?>
            <nav class="SiteFooter-menu">
                <h3 class="SiteFooter-menu_title"><?php
                    $locations = get_nav_menu_locations();
                    echo wp_get_nav_menu_object($locations['primary_navigation_footer'])->name;
                ?></h3>
                <?php wp_nav_menu( array(
                        'theme_location' => 'primary_navigation_footer',
                        'items_wrap' => '<ul id="%1$s" class="SiteFooter-menu_list %2$s">%3$s',
                        )); ?>
            </nav>
        <?php } ?>
        <?php if (has_nav_menu('secondary_navigation_footer')) { ?>
            <nav class="SiteFooter-menu">
                <h3 class="SiteFooter-menu_title"><?php
                    $locations = get_nav_menu_locations();
                    echo wp_get_nav_menu_object($locations['secondary_navigation_footer'])->name;
                ?></h3>
                <?php wp_nav_menu( array(
                        'theme_location' => 'secondary_navigation_footer',
                        'items_wrap' => '<ul id="%1$s" class="SiteFooter-menu_list %2$s">%3$s',
                        )); ?>
            </nav>
        <?php } ?>
        <?php if (has_nav_menu('tertiary_navigation_footer')) { ?>
            <nav class="SiteFooter-menu">
                <h3 class="SiteFooter-menu_title"><?php
                    $locations = get_nav_menu_locations();
                    echo wp_get_nav_menu_object($locations['tertiary_navigation_footer'])->name;
                ?></h3>
                <?php wp_nav_menu( array(
                        'theme_location' => 'tertiary_navigation_footer',
                        'items_wrap' => '<ul id="%1$s" class="SiteFooter-menu_list %2$s">%3$s',
                        )); ?>
            </nav>
        <?php } ?>
        <?php if (has_nav_menu('quaternary_navigation_footer')) { ?>
            <nav class="SiteFooter-menu">
                <h3 class="SiteFooter-menu_title"><?php
                    $locations = get_nav_menu_locations();
                    echo wp_get_nav_menu_object($locations['quaternary_navigation_footer'])->name;
                ?></h3>
                <?php wp_nav_menu( array(
                        'theme_location' => 'quaternary_navigation_footer',
                        'items_wrap' => '<ul id="%1$s" class="SiteFooter-menu_list %2$s">%3$s',
                        )); ?>
            </nav>
        <?php } ?>
        <?php if (is_active_sidebar('site_footer')) { ?>
            <div class="SiteFooter-newsletter_signup">
                <?php dynamic_sidebar('site_footer'); ?>
            </div>
        <?php } ?>
        <?php if ($has_legal_decl) { ?>
            <div class="SiteFooter-legal">
                <?php echo get_theme_mod('skeleton_warrior_legaldecl'); ?>
            </div>
        <?php } ?>
        <?php if (is_active_sidebar('site_footer_icons')) { ?>
            <?php dynamic_sidebar('site_footer_icons'); ?>
        <?php } ?>
        <?php if (has_nav_menu('site_footer_icons_menu')) { ?>
            <nav class="SiteFooter-social">
                <?php wp_nav_menu( array(
                        'theme_location' => 'site_footer_icons_menu',
                        'items_wrap' => '<ul id="%1$s" class="SiteFooter-social_list %2$s">%3$s',
                        )); ?>
            </nav>
        <?php } ?>
    </div>
</footer>