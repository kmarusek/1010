<?php

// Make sure we have a way to determine if WooCommerce is activated
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
    function is_woocommerce_activated() {
        if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
    }
}

// This module obviously requires that WooCommerce is activated, so it'd be foolish to 
// include this module if WooCommerce wasn't activated
if ( !is_woocommerce_activated() ){
    return;
}

/**
 * @class BWWooCommerceIcons
 *
 */
class BWWooCommerceIcons extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct()
    {
        FLBuilderModule::__construct(
            array(
                'name'            => __('WooCommerce Icons', 'fl-builder'),
                'description'     => __('A module to show the cart icon along with the number of items currently in the cart.', 'fl-builder'),
                'category'        => __('WooCommerce', 'fl-builder'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }

    /**
     * Method to check whether or not the current icon is both a cart and has the cart
     * count badge enabled.
     *
     * @param  object $icon_to_check The repeater to check
     *
     * @return boolean                True if both is a cart and has the cart count enabled
     */
    public function showCartCountIsEnabled( $icon_to_check ){
        // Whether or not this is a car
        $is_cart            = isset( $icon_to_check->icon_type ) && $icon_to_check->icon_type === 'cart';
        // Whether or not the cart count is enabled
        $cart_count_enabled = isset( $icon_to_check->cart_show_item_count ) && $icon_to_check->cart_show_item_count === 'enabled';
        // Return it
        return $is_cart && $cart_count_enabled;
    }

    /**
     * Method used to get the current count of the cart items.
     *     
     * @return int The current cart item count
     */
    public function getCartItemCount(){
        // Be default, we'll assume we have zero items in the cart
        $cart_items = 0;
        // If we're in the admin section, put in a fake number of items in the cart
        if ( $this->isViewingInEditor() ){
            $cart_items = 3;
        }
        // Otherwise, get the real number of items in the cart (assuming that WC is enabled)
        else if ( function_exists( 'WC' ) ){
            // Get the total number of items in the cart
            $cart_items =  WC()->cart->get_cart_contents_count();
        }
        return $cart_items;
    }

    /**
     * Method to retrieve all of the WooCommerce icons.
     *
     * @return array The WooCommerce icons
     */
    public function getWooCommerceIcons(){
        return is_array( $this->settings->woocommerce_icon_repeater ) ? $this->settings->woocommerce_icon_repeater : array();
    }

    public function getWooCommerceLinkByIconType( $icon_type ){
        // Declare our return
        $return_url = '';

        switch ($icon_type) {

            // For the My Account page
            case 'account':
            $return_url =  wc_get_page_permalink( 'myaccount' );
            break;

            // For the Cart
            case 'cart':
            $return_url =  wc_get_cart_url();
            break;
            
        }
        return $return_url;
    }
}

FLBuilder::register_module( 
    'BWWooCommerceIcons', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields'=> array(
                        'woocommerce_icon_repeater'   => array(
                            'type'         => 'form',
                            'label'        => __('Icon', 'fl-builder'),
                            'form'         => 'woocommerce_icon_repeater_form',
                            'preview_text' => 'icon_type',
                            'multiple'     => true
                        )
                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'style+general' => array(
                    'fields'=> array(
                        'icon_margin'   => array(
                            'type'         => 'dimension',
                            'label'        => __('Icon margin', 'fl-builder'),
                            'units' => array(
                                'px'
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.woocommerce-icon-container li',
                                'property' => 'margin'
                            )
                        ),
                        'icon_color'   => array(
                            'type'       => 'color',
                            'label'      => __('Icon color', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.woocommerce-icon-container .icon',
                                'property' => 'color'
                            )
                        ),
                        'icon_hover_color'   => array(
                            'type'       => 'color',
                            'label'      => __('Icon color (hover)', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true
                        ),
                        'icon_alignment'   => array(
                            'type'    => 'select',
                            'label'   => __('Icon alignment', 'fl-builder'),
                            'default' => 'flex-end',
                            'options' => array(
                                'flex-start' => 'Left',
                                'center'     => 'Center',
                                'flex-end'   => 'Right'
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.woocommerce-icon-container',
                                'property' => 'justify-content'
                            )
                        ),
                        'icon_size'   => array(
                            'type'         => 'unit',
                            'label'        => __('Icon size', 'fl-builder'),
                            'help'         => 'This will be the default font size for all icons. You can also specifiy individual font sizes per icon',
                            'units'        => array( 'px' ),
                            'slider'       => true,
                            'default'      => 18,
                            'default_unit' => 'px',
                            'preview'      => array(
                                'type'     => 'css',
                                'selector' => '.woocommerce-icon-container .icon',
                                'property' => 'font-size'
                            )
                        )
                    )
                )
            )
        )
    )
);

FLBuilder::register_settings_form(
    'woocommerce_icon_repeater_form', 
    array(
        'title' => __( 'Icon', 'fl-builder' ),
        'tabs'  => array(
            'general'      => array(
                'title' => __( 'General', 'fl-builder' ),
                'sections'      => array(
                    'general' => array(
                        'fields' => array(
                            'icon_type' => array(
                                'type'    => 'select',
                                'label'   => __('Icon type', 'fl-builder'),
                                'default' => 'cart',
                                'options' => array(
                                    'cart'    => 'Cart',
                                    'account' => 'Account'
                                ),
                                'toggle' => array(
                                    'cart' => array(
                                        'sections' => array(
                                            'section_cart_options'
                                        )
                                    )
                                )
                            ),
                            'icon' => array(
                                'type'    => 'icon',
                                'label'   => __('Icon', 'fl-builder'),
                                'default' => 'fi-shopping-cart'
                            )
                        )
                    ),
                    'section_cart_options' => array(
                        'title' => __( 'Cart options', 'fl-builder' ),
                        'fields' => array(
                            'cart_show_item_count' => array(
                                'type'    => 'select',
                                'label'   => __('Show item count', 'fl-builder'),
                                'default' => 'disabled',
                                'options' => array(
                                    'disabled' => 'Disabled',
                                    'enabled'  => 'Enabled'
                                ),
                                'toggle' => array(
                                    'enabled' => array(
                                        'sections' => array(
                                            'style_cart_count'
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'style' => array(
                'title' => __( 'Style', 'fl-builder' ),
                'sections' => array(
                    'style_general' => array(
                        'fields' => array(
                            'icon_size'   => array(
                                'type'         => 'unit',
                                'label'        => __('Icon size', 'fl-builder'),
                                'units'        => array( 'px' ),
                                'slider'       => true,
                                'default_unit' => 'px',
                            )
                        )
                    ),
                    'style_cart_count' => array(
                        'title' => __( 'Cart count', 'fl-builder' ),
                        'fields' => array(
                            'cart_count_typography'   => array(
                                'type'  => 'typography',
                                'label' => __('Typography', 'fl-builder'),
                                'preview' => array(
                                    'type'      => 'css',
                                    'selector'  => '.woocommerce-icon-has-badge a::after',
                                    'important' => true
                                )
                            ),
                            'cart_count_color'   => array(
                                'type'       => 'color',
                                'label'      => __('Color', 'fl-builder'),
                                'show_reset' => true,
                                'preview' => array(
                                    'type' => 'css',
                                    'selector'  => '.woocommerce-icon-has-badge a::after',
                                    'property'  => 'color',
                                    'important' => true
                                )
                            ),
                            'cart_count_background_color'   => array(
                                'type'         => 'color',
                                'label'        => __('Background color', 'fl-builder'),
                                'show_reset' => true,
                                'preview' => array(
                                    'type' => 'css',
                                    'selector'  => '.woocommerce-icon-has-badge a::after',
                                    'property'  => 'background-color',
                                    'important' => true
                                )
                            )
                        )
                    )
                )
            )
        )
    ) //
);
