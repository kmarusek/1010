<?php
/**
 * The typography settings for the top-level items in the navigation (obvy)
 *
 * @var array
 */
$settings = array(
    'padding_top_level' => array(
        'type'    => 'dimension',
        'units'   => array( 'px' ),
        'label'   => __( 'Padding', 'skeleton-warrior' ),
        'default' => 10,
        'preview' => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li',
            'property' => 'padding'
        )
    ),
    'margin_top_level' => array(
        'type'    => 'dimension',
        'units'   => array( 'px' ),
        'label'   => __( 'Margin', 'skeleton-warrior' ),
        'default' => 1,
        'preview' => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li',
            'property' => 'margin'
        )
    )
);