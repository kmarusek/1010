<?php

function beaverwarrior_load_Offcanvas_modules() {
    if (class_exists("FLBuilder") && class_exists("UABB_Model_Helper") ) {
        require_once "bw_toast/bw_toast.php";
    }
}
add_action ('init', "beaverwarrior_load_Offcanvas_modules");
