<?php

add_action ('init', function(){
        if (class_exists("FLBuilder")) {
        require_once "bw-team-grid/bw-team-grid.php";
    }

}, 15);