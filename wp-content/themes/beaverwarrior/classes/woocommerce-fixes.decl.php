<?php

function spacestation_woocommerce_vars_fallback($vars) {
    if ( !FLTheme::is_plugin_active( 'woocommerce' ) ) {
        $vars['woo-cats-add-button'] = 'none';
    }
    
    return $vars;
}
add_filter('fl_less_vars', 'spacestation_woocommerce_vars_fallback');