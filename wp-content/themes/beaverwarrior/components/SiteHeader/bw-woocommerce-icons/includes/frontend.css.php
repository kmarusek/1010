<?php

$custom_css = [
    '.woocommerce-icon-container' => [
        'justify-content'   => $settings->icon_alignment,
        '.woocommerce-icon' => [
            'margin' => $module->getModuleSettingDimension( 'icon_margin' ),
            '.icon'  => [
                'color'     => $module->getModuleSettingColor( 'icon_color' ),
                'font-size' => $module->getModuleSettingWithUnits( 'icon_size' )
            ],
            '&:hover .icon' => [
                'color'     => $module->getModuleSettingColor( 'icon_hover_color' ),
            ]
        ]
    ]
];
// Get the icons (for custom icon sizes)
$woocommerce_icons = $module->getWooCommerceIcons();
// Loop through all of them
for ( $i=0; $i<count($woocommerce_icons); $i++ ){
    // Get the current icon
    $current_icon = $woocommerce_icons[$i];
    // If we have a custom icon size
    if ( isset( $current_icon->icon_size ) && $current_icon->icon_size ){
        // Then add to the CSS
        $custom_css['.woocommerce-icon-container .woocommerce-icon:nth-child(' . ( $i + 1 ) . ') .icon'] = [
            'font-size' => $current_icon->icon_size . 'px'
        ];
    }

    // If we're using a cart count badge, add the CSS here
    if ( $module->showCartCountIsEnabled( $current_icon ) ){

        // Convert the typography object into an associative array
        $typography_array = json_decode( json_encode($current_icon->cart_count_typography), true );
        $typography       = FLBuilderCSS::typography_field_props( $typography_array );

        $custom_css['.woocommerce-icon-container .woocommerce-icon:nth-child(' . ( $i + 1 ) . ') a .cart-icon-badge-container'] = [
            'background'     => '#' . $current_icon->cart_count_background_color
        ];

        $custom_css['.woocommerce-icon-container .woocommerce-icon:nth-child(' . ( $i + 1 ) . ') a .cart-icon-badge'] = array_merge(
            [
                'color'          => '#' . $current_icon->cart_count_color
            ],
            $typography
        );
    }
}
$module->renderModuleCSS( $custom_css );

// WrldTkOvr2018!