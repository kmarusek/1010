<div class="pp-product-price">
    <?php if ( in_array( 'product', (array) $post_type ) ) {
		global $product;
		?>
        <p>
            <?php
            // Updated function woocommerce_get_template to wc_get_template
            // @since 1.2.7
            if( function_exists( 'wc_get_template' ) && is_object( $product ) ) {
                wc_get_template( 'loop/price.php', array( 'product' => $product ) );
            }
            ?>
        </p>
    <?php } ?>
    <?php if ( in_array( 'download', (array) $post_type ) && function_exists( 'edd_price' ) ) {
           if ( edd_has_variable_prices( get_the_ID() ) ) {
               // if the download has variable prices, show the first one as a starting price
                _e('Starting at: ','bb-powerpack');
                edd_price( get_the_ID() );
           } else {
            	edd_price( get_the_ID() );
           }
        }
   ?>
</div>
