<?php

function beaverwarrior_load_Article_modules() {
    if (class_exists("FLBuilder") && class_exists('UABB_Helper')) {
        require_once "bw_post_navigation/bw_post_navigation.php";
    }
}
add_action ('init', "beaverwarrior_load_Article_modules");
