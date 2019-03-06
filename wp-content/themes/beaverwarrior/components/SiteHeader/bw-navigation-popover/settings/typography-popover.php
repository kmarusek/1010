<?php
/**
 * The typography settings for the top-level items in the navigation (obvy)
 *
 * @var array
 */
$settings = array(
    'typography_popover_header' => array(
        'type'       => 'typography',
        'responsive' => true,
        'label'      => __( 'Header font', 'skeleton-warrior' ),
        'preview'    => array(
            'type'      => 'css',
            'selector'  => '.section-title'
        )
    ),
    'color_popover_header' => array(
        'type'       => 'color',
        'label'      => __( 'Header color', 'skeleton-warrior' ),
        'default'    => '000000',
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector' => '.section-title',
            'property' => 'color'
        )
    ),
    'typography_popover_contents' => array(
        'type'       => 'typography',
        'responsive' => true,
        'label'      => __( 'Content font', 'skeleton-warrior' ),
        'preview'    => array(
            'type'      => 'css',
            'selector'  => '.popover .sub-menu li a'
        )
    ),
    'color_popover_content' => array(
        'type'       => 'color',
        'label'      => __( 'Content color', 'skeleton-warrior' ),
        'default'    => '000000',
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector'  => '.popover .popover-content .sub-menu > li:not(.contains-description) > a',
            'property' => 'color'
        )
    ),
    'color_popover_content_hover' => array(
        'type'       => 'color',
        'label'      => __( 'Content color (hover)', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true
    ),
    'background_color_popover_content' => array(
        'type'       => 'color',
        'label'      => __( 'Content background color', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector'  => '.sub-menu li a',
            'property' => 'background-color'
        )
    ),
    'background_color_popover_content_hover' => array(
        'type'       => 'color',
        'label'      => __( 'Content background color (hover)', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true
    ),
    'typography_popover_description' => array(
        'type'       => 'typography',
        'responsive' => true,
        'label'      => __( 'Description font', 'skeleton-warrior' ),
        'preview'    => array(
            'type'      => 'css',
            'selector'  => '.sub-menu.contains-description dl dd'
        )
    ),
    'color_popover_description' => array(
        'type'       => 'color',
        'label'      => __( 'Description color', 'skeleton-warrior' ),
        'default'    => '000000',
        'show_reset' => true,
        'show_alpha' => true,
        'preview'    => array(
            'type'     => 'css',
            'selector'  => '.sub-menu li.definition',
            'property' => 'color'
        )
    ),
    'color_popover_description_hover' => array(
        'type'       => 'color',
        'label'      => __( 'Description color (hover)', 'skeleton-warrior' ),
        'show_reset' => true,
        'show_alpha' => true
    ),
);