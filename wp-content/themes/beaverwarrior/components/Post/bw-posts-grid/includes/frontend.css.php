<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 3;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

// The overall CSS
$custom_css_general = [
    '.post' => [
        'padding' => $module->getModuleSettingDimension( 'posts_padding' )
    ],
    '.featured-image-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'featured_image_margin_bottom' )
    ],
    '.post-category-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_categories_margin_bottom' ),
        '.post-categories' => array_merge(
            $module->getTypography( 'post_categories_typography' ),
            [
                'color'         => $module->getModuleSettingColor( 'post_categories_color' )
            ]
        )
    ],
    '.post-title' => array_merge(
        $module->getTypography( 'post_title_typography' ),
        [
            'color' => $module->getModuleSettingColor( 'post_title_color' )
        ]
    ),
    '.post-title-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_title_margin_bottom' ),
        '.post-title' => array_merge(
            $module->getTypography( 'post_title_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_title_color' )
            ]
        )
    ],
    '.post-excerpt-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'post_excerpt_margin_bottom' ),
        '.post-excerpt' => array_merge(
            $module->getTypography( 'post_excerpt_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'post_excerpt_color' )
            ]
        )
    ],
    '.read-more-container' => [
        'margin-bottom' => $module->getModuleSettingWithUnits( 'read_more_margin_bottom' ),
        '.read-more' => array_merge(
            $module->getTypography( 'read_more_typography' ),
            [
                'color' => $module->getModuleSettingColor( 'read_more_color' )
            ]
        )
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

$custom_css_responsive = [
    '.posts-container' => [
        '.post' => [
            'width' => round( 100 / $post_per_column_mobile, 2, PHP_ROUND_HALF_DOWN ) . '%'
        ]
    ]
];

$custom_css_tablet = [
    '.posts-container' => [
        '.post' => [
            'width' => round( 100 / $post_per_column_tablet, 2, PHP_ROUND_HALF_DOWN ) . '%'
        ]
    ]
];

$custom_css_desktop = [
    '.posts-container' => [
        '.post' => [
            'width' => round( 100 / $post_per_column_desktop, 2, PHP_ROUND_HALF_DOWN ) . '%'
        ]
    ]
];

$module->renderModuleCSS( $custom_css_general );
$module->renderModuleCSSResponsiveMobile( $custom_css_responsive );
$module->renderModuleCSSResponsiveTablet( $custom_css_tablet );
$module->renderModuleCSSResponsiveDesktop( $custom_css_desktop );