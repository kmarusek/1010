<?php
add_action ('init', function(){
    if (class_exists("FlBuilder")) {
        require_once "bw-content-slider/bw-content-slider.php";
    }

}, 15);