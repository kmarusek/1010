<?php
/**
 * The typography settings for the top-level items in the navigation (obvy)
 *
 * @var array
 */
$settings = array(
    'typography_top_level' => array(
        'type'       => 'typography',
        'responsive' => true,
        'label'      => __( 'Font', 'skeleton-warrior' ),
        'preview'    => array(
            'type'      => 'css',
            'selector'  => '.mega-menu-container > li a'
        )
    ),
    'color_top_level' => array(
        'type'       => 'color',
        'label'      => __( 'Color', 'skeleton-warrior' ),
        'default'    => '000000',
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li a',
            'property' => 'color'
        )
    ),
    'color_hover_top_level' => array(
        'type'       => 'color',
        'label'      => __( 'Color (hover)', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true
    ),
    'background_color_top_level' => array(
        'type'       => 'color',
        'label'      => __( 'Background color', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.mega-menu-container > li',
            'property' => 'background-color'
        )
    ),
    'background_color_hover_top_level' => array(
        'type'       => 'color',
        'label'      => __( 'Background color (hover)', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true
    )
);