<?php

function beaver_warrior_remove_bb_nav_menus() {
    unregister_nav_menu('bar');
    unregister_nav_menu('header');
    unregister_nav_menu('footer');
}
add_action('after_setup_theme', 'beaver_warrior_remove_bb_nav_menus', 11);
