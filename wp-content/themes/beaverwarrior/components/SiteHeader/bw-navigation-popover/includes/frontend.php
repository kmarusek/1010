<?php

// Required for uniqueId method
wp_enqueue_script( 'jquery-ui-core' );

if ( !$module->isViewingAsThemerLayout() && $module->getMenuID() ){
    ?>
    <div class="mega-menu-mobile-icon">
        <div class="mobile-menu-line"></div>
        <div class="mobile-menu-line"></div>
    </div>
    <?php
    echo "<div class=\"content\">";
    // Render the menu
    wp_nav_menu( 
        array(
            'menu'       => $module->getMenuID(),
            'walker'     => new BWNavigationPopoverMenuWalker( $module ),
            'menu_class' => 'mega-menu-container',
            'depth'      => 2
        )
    );
    echo "</div>";
}