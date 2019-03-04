<?php
/**
 * The typography settings for the top-level items in the navigation (obvy)
 *
 * @var array
 */
$settings = array(
    'padding_top_level' => array(
        'type'       => 'dimension',
        'label'      => __( 'Padding', 'skeleton-warrior' ),
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li',
            'property' => 'padding'
        )
    ),
    'margin_top_level' => array(
        'type'       => 'dimension',
        'label'      => __( 'Margin', 'skeleton-warrior' ),
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li',
            'property' => 'margin'
        )
    )
);