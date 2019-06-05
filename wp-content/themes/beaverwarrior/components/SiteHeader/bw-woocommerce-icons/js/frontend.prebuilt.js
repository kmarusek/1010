/* jshint ignore:start */
@import '../../../../assets/js/class-bw-module-frontend.js';
/* jshint ignore:end */

/**
 * The main class used for the social icons.
 */
 class BWWooCommerceIcons extends BWModuleFrontend {

    /**
     * Method automatically called by the superclass
     *
     * @return {void} 
     */
     init(){
        // Handle clicking for the Add to Cart buttons
        this._handleUpdateCartQuantities()
    }

    /**
     * Method designated to listen for an event that tells us that an item
     * has been added to the WC cart via AJAX.
     *
     * @return {void}
     */
     _handleUpdateCartQuantities(){
        const elem = document.querySelector('html');
        elem.addEventListener('wc-update-cart-quantity', (e) => {
            this._updateCartQuantityBadge(e);
        }, false)
    }

    _updateCartQuantityBadge(data){
        const icon_badges = this.element.querySelectorAll('.cart-icon-badge')
        const new_quantity = data.detail.cartItemQuantity
        // It's possible to have multiple badges in the same module even though that'd
        // be dumb
        for ( let i=0; i<icon_badges.length; i++){
            const badge        = icon_badges[i]
            const badge_parent = badge.closest( '.woocommerce-icon' )

            if ( data.detail.cartItemQuantity > 0 ){
                badge_parent.classList.add( 'woocommerce-icon-has-badge' )
            }
            else {
                badge_parent.classList.remove( 'woocommerce-icon-has-badge' )
            }
            badge.innerHTML = new_quantity
        }
    }
}