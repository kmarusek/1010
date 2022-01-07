<?php

$button = FLPageDataWooCommerce::get_add_to_cart_button();

if ( function_exists( 'YITH_YWRAQ_Frontend' ) ) {
	global $product;

	if ( ! empty( $product ) && 'yes' == get_option( 'ywraq_hide_add_to_cart' ) ) {
		YITH_YWRAQ_Frontend()->hide_add_to_cart_single();
	}

	echo $button;

	if ( is_object( $product ) ) {
		YITH_YWRAQ_Frontend()->add_button_single_page();
	}
} elseif ( class_exists( 'WC_Wishlists_Plugin' ) && ! empty( $button ) ) {
	global $product;

	if ( ! empty( $product ) ) {
		$wishlist_button      = do_shortcode( '[wc_wishlists_button]' );
		$add_to_cart_wishlist = str_replace( '</form>', $wishlist_button . '</form>', $button );
		echo $add_to_cart_wishlist;
	}
} else {
	echo $button;
}

if ( class_exists( 'WooCommerce_Waitlist_Plugin' ) ) {
	global $product;

	if ( ! ( empty( $product ) || $product->is_type( 'external' ) || $product->is_type( 'composite' ) || $product->is_type( 'variable' ) ) ) {
		echo do_shortcode( '[woocommerce_waitlist]' );
	}
}
