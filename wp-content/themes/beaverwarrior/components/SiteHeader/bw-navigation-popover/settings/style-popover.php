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