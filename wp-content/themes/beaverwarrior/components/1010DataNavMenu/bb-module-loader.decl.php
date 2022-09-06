<?php 
add_action('init', function() {
    if(class_exists('FLBuilder')) {
        require_once __DIR__ . '/1010data-nav-menu/1010data-nav-menu.php';
        require_once __DIR__ . '/1010data-nav-menu/class-tp-nav-menu-walker.php';
    }
}, 15);