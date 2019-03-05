<?php

// Required for uniqueId method
wp_enqueue_script( 'jquery-ui-core' );

if ( !$module->isViewingAsThemerLayout() && $module->getMenuID() ){
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