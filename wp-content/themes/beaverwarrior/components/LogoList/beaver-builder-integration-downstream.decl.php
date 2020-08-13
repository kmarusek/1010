<?php

add_action ('init', function(){
        if (class_exists("FLBuilder")) {
        require_once "bw-logo-list/bw-logo-list.php";
    }

}, 15);