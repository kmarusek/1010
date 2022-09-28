<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 4;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

// The overall CSS
$custom_css_general = [
    '.SearchResults-post' => [
        'margin' => $module->getModuleSettingDimension( 'posts_margin' ),
    ],
    '.SearchResults-category-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_categories_margin_bottom' ),
        '.SearchResults-categories' => array_merge(
            $module->getTypography( 'post_categories_typography' ),
            [
                'color'         => $module->getModuleSettingColor( 'post_categories_color' )
            ]
        )
    ],
    '.SearchResults-title' => array_merge(
        $module->getTypography( 'post_title_typography' ),
        [
            'color' => $module->getModuleSettingColor( 'post_title_color' )
        ]
    ),
    '.SearchResults-title-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_title_margin_bottom' ),
        '.post-title' => array_merge(
            $module->getTypography( 'post_title_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_title_color' )
            ]
        )
    ],
    '.SearchResults-excerpt-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_excerpt_margin_bottom' ),
        '.SearchResults-excerpt' => array_merge(
            $module->getTypography( 'post_excerpt_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_excerpt_color' )
            ]
        )
    ],
    '.SearchResults-post-date-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_date_margin_bottom' ),
        '.SearchResults-date' => array_merge(
            $module->getTypography( 'post_date_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_date_color' )
            ]
            ),
    ],
    '.paginationjs-pages' => [
        '> ul li a' => array_merge(
            $module->getTypography( 'pagination_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'pagination_color' )
            ]
        ),
        '> ul li:hover a, ' => [
            'color' => $module->getModuleSettingColor( 'pagination_color_hover' )
        ],
        '> ul li.active a' => [
            'color' => $module->getModuleSettingColor( 'pagination_color_hover' )
        ]
    ]
];


$module->renderModuleCSS( $custom_css_general );
