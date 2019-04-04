<?php

require_once __DIR__ . "/animated_background_row/settings.decl.php";

function beaverwarrior_load_AnimatedBackgrounds_modules() {
    if (class_exists("FLBuilder")) {
        require_once "bw-animation/bw-animation.php";
    }
}
add_action ('init', "beaverwarrior_load_AnimatedBackgrounds_modules", 15);