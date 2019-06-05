<?php
// Get the icons
$woocommerce_icons = $module->getWooCommerceIcons();
?>
<ul class="woocommerce-icon-container">
    <?php
    // Loop through all of the icons
    for ( $i=0; $i<count($woocommerce_icons); $i++){
        // Get the current icon
        $current_icon    = $woocommerce_icons[$i];
        // Get the URL for the link we have
        $icon_url        = $module->getWooCommerceLinkByIconType( $current_icon->icon_type );
        // The default icon classes
        $icon_classes = array( 'woocommerce-icon' );
        // Add the icon type
        array_push($icon_classes, $current_icon->icon_type);
        $additional_html = '';
        if ( $module->showCartCountIsEnabled( $current_icon ) ){
            // Get the cart item count
            $cart_item_count = $module->getCartItemCount();
            // Only show the badge if we have more than zero cart items
            if ($cart_item_count > 0){
                // Add the badge icon
                array_push( $icon_classes, 'woocommerce-icon-has-badge');
            }
            // Add the additional HTML
            $additional_html .= "<div class=\"cart-icon-badge-container\"><div class=\"cart-icon-badge\">$cart_item_count</div></div>";
        }
        echo sprintf(
            '<li class="%s"><a href="%s"><i class="icon %s"></i>%s</a></li>',
            // The classes
            implode( ' ', $icon_classes ),
            // The url
            $icon_url,
            // The icon class
            $current_icon->icon,
            // Any additional HTML
            $additional_html
        );
    }
    ?>
</ul>