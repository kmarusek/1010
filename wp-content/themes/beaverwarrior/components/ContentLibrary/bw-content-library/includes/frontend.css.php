<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 3;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

// The overall CSS
$custom_css_general = [
    '.ContentLibrary-post' => [
        'margin' => $module->getModuleSettingDimension( 'posts_margin' ),
    ],
    '.ContentLibrary-category-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_categories_margin_bottom' ),
        '.ContentLibrary-categories' => array_merge(
            $module->getTypography( 'post_categories_typography' ),
            [
                'color'         => $module->getModuleSettingColor( 'post_categories_color' )
            ]
        )
    ],
    '.ContentLibrary-title' => array_merge(
        $module->getTypography( 'post_title_typography' ),
        [
            'color' => $module->getModuleSettingColor( 'post_title_color' )
        ]
    ),
    '.ContentLibrary-title-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_title_margin_bottom' ),
        '.post-title' => array_merge(
            $module->getTypography( 'post_title_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_title_color' )
            ]
        )
    ],
    '.ContentLibrary-excerpt-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_excerpt_margin_bottom' ),
        '.ContentLibrary-excerpt' => array_merge(
            $module->getTypography( 'post_excerpt_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_excerpt_color' )
            ]
        )
    ],
    '.ContentLibrary-post-date-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_date_margin_bottom' ),
        '.ContentLibrary-date' => array_merge(
            $module->getTypography( 'post_date_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_date_color' )
            ]
            ),
        '.ContentLibrary-share' => array_merge(
            $module->getTypography( 'post_share_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_share_color' )
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
