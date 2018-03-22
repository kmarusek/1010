<?php

function beaverwarrior_ConfirmationPage_query_var_shortcode($atts) {
    return esc_html($_GET[$atts["var"]]);
}

function beaverwarrior_ConfirmationPage_add_query_shortcode() {
    add_shortcode('bw_query_var', 'beaverwarrior_ConfirmationPage_query_var_shortcode');
}
add_action('init', 'beaverwarrior_ConfirmationPage_add_query_shortcode');
