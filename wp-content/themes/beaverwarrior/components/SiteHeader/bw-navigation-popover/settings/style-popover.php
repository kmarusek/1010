<?php
/**
 * The typography settings for the top-level items in the navigation (obvy)
 *
 * @var array
 */
$settings = array(
    'min_width_popover' => array(
        'type'    => 'unit',
        'label'   => __( 'Min width', 'skeleton-warrior' ),
        'default' => 350,
        'units'   => array( 'px' ),
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.popover',
            'property' => 'min-width'
        )
    ),
    'padding_popover_content' => array(
        'type'    => 'dimension',
        'label'   => __( 'Padding', 'skeleton-warrior' ),
        'preview' => array(
            'type'     => 'css',
            'selector' => '.popover-content',
            'property' => 'padding'
        ),
        'placeholder' => array(
            'top'    => 20,
            'bottom' => 5,
            'left'   => 0,
            'right'  => 0
        )
    ),
    'margin_popover_submenu' => array(
        'type'    => 'dimension',
        'label'   => __( 'Sub-menu margin', 'skeleton-warrior' ),
        'preview' => array(
            'type'     => 'css',
            'selector' => '.popover-content .sub-menu',
            'property' => 'margin'
        ),
        'placeholder' => array(
            'top'    => 0,
            'bottom' => 0,
            'left'   => 0,
            'right'  => 0
        )
    ),
    'padding_popover_submenu_item' => array(
        'type'    => 'dimension',
        'label'   => __( 'Sub-menu item padding', 'skeleton-warrior' ),
        'preview' => array(
            'type'     => 'css',
            'selector' => '.popover-content .sub-menu > li',
            'property' => 'padding'
        ),
        'placeholder' => array(
            'top'    => 10,
            'bottom' => 10,
            'left'   => 40,
            'right'  => 40
        )
    ),
    'border_color_popover' => array(
        'type'       => 'color',
        'label'      => __( 'Border color', 'skeleton-warrior' ),
        'default'    => 'a2a2a2',
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.popover',
            'property' => 'border-color'
        )
    ),
    'border_width_popover' => array(
        'type'    => 'unit',
        'label'   => __( 'Border width', 'skeleton-warrior' ),
        'default' => '1',
        'units'   => array( 'px' ),
        'preview' => array(
            'type'     => 'css',
            'selector' => '.popover',
            'property' => 'border-width'
        )
    ),
    'border_radius_popover' => array(
        'type'    => 'unit',
        'label'   => __( 'Border radius', 'skeleton-warrior' ),
        'default' => '1',
        'units'   => array( 'px' ),
        'preview' => array(
            'type'     => 'css',
            'selector' => '.popover-content',
            'property' => 'border-radius'
        )
    )
);