<?php

function spacestation_woocommerce_vars_fallback($vars) {
    if ( !isset($vars['woo-cats-add-button']) ) {
        $vars['woo-cats-add-button'] = 'none';
    }
    
    return $vars;
}
add_filter('fl_less_vars', 'spacestation_woocommerce_vars_fallback', 0);