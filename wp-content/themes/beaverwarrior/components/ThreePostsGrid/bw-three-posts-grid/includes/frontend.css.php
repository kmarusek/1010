<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 3;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

// The overall CSS
$custom_css_general = [
    '.ThreePostsGrid-post' => [
        'margin' => $module->getModuleSettingDimension( 'posts_margin' ),
    ],
    '.ThreePostsGrid-category-container' => [
        'margin' => $module->getModuleSettingDimension( 'post_categories_margin' ),
        '.ThreePostsGrid-categories' => array_merge(
            $module->getTypography( 'post_categories_typography' ),
            [
                'color'         => $module->getModuleSettingColor( 'post_categories_color' )
            ]
        )
    ],
    '.ThreePostsGrid-title-container' => [
        'margin' => $module->getModuleSettingDimension( 'post_title_margin' ),
        '.ThreePostsGrid-title' => array_merge(
            $module->getTypography( 'post_title_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_title_color' )
            ]
        )
    ],
    '.ThreePostsGrid-excerpt-container' => [
        'margin' => $module->getModuleSettingDimension( 'post_excerpt_margin' ),
        '.ThreePostsGrid-excerpt' => array_merge(
            $module->getTypography( 'post_excerpt_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_excerpt_color' )
            ]
        )
    ],
    '.ThreePostsGrid-post-date-wrap' => [
        'margin' => $module->getModuleSettingDimension( 'post_date_margin' ),
        '.ThreePostsGrid-date' => array_merge(
            $module->getTypography( 'post_date_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_date_color' )
            ]
            ),

    ],
    '.ThreePostsGrid-share-btn_wrap' => [
        '.ThreePostsGrid-share_label' => array_merge(
            $module->getTypography( 'post_share_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_share_color' )
            ]
            ),
    ],
    '.ThreePostsGrid-pagination' => [
        'margin' => $module->getModuleSettingDimension( 'pagination_margin' ),
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
