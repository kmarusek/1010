<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 3;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

//Typography
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'post_categories_typography',
        'selector'  => ".ThreePostsGrid-categories",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'post_title_typography',
        'selector'  => ".ThreePostsGrid-title",
    )
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'post_excerpt_typography',
        'selector'  => ".ThreePostsGrid-excerpt",
    )
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'post_date_typography',
        'selector'  => ".ThreePostsGrid-date",
    )
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'post_share_typography',
        'selector'  => ".ThreePostsGrid-share_label",
    )
);
// Spacing
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'posts_margin',
    'selector'    => ".ThreePostsGrid-post",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'posts_margin_top', // As in $settings->padding_top
      'margin-right'  => 'posts_margin_right',
      'margin-bottom' => 'posts_margin_bottom',
      'margin-left'   => 'posts_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'post_container_padding',
    'selector'    => ".ThreePostsGrid-content",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'padding-top'    => 'post_container_padding_padding_top', // As in $settings->padding_top
      'padding-right'  => 'post_container_padding_padding_right',
      'padding-bottom' => 'post_container_padding_padding_bottom',
      'padding-left'   => 'post_container_padding_padding_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'post_categories_margin',
    'selector'    => ".ThreePostsGrid-category-container",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'post_categories_margin_top', // As in $settings->padding_top
      'margin-right'  => 'post_categories_margin_right',
      'margin-bottom' => 'post_categories_margin_bottom',
      'margin-left'   => 'post_categories_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'post_title_margin',
    'selector'    => ".ThreePostsGrid-title-container",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'post_title_margin_top', // As in $settings->padding_top
      'margin-right'  => 'post_title_margin_right',
      'margin-bottom' => 'post_title_margin_bottom',
      'margin-left'   => 'post_title_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'post_excerpt_margin',
    'selector'    => ".ThreePostsGrid-excerpt-container",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'post_excerpt_margin_top', // As in $settings->padding_top
      'margin-right'  => 'post_excerpt_margin_right',
      'margin-bottom' => 'post_excerpt_margin_bottom',
      'margin-left'   => 'post_excerpt_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'post_date_margin',
    'selector'    => ".ThreePostsGrid-post-date-container",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'post_date_margin_top', // As in $settings->padding_top
      'margin-right'  => 'post_date_margin_right',
      'margin-bottom' => 'post_date_margin_bottom',
      'margin-left'   => 'post_date_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'pagination_margin',
    'selector'    => ".ThreePostsGrid-pagination_wrap",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'pagination_margin_top', // As in $settings->padding_top
      'margin-right'  => 'pagination_margin_right',
      'margin-bottom' => 'pagination_margin_bottom',
      'margin-left'   => 'pagination_margin_left',
    ),
  ) );
// Color
$custom_css_general = [
    '.ThreePostsGrid-category-container' => [
        '.ThreePostsGrid-categories' => array_merge(
            [
                'color'         => $module->getModuleSettingColor( 'post_categories_color' )
            ]
        )
    ],
    '.ThreePostsGrid-title-container' => [
        '.ThreePostsGrid-title' => array_merge(
            [
                'color' => $module->getModuleSettingColor( 'post_title_color' )
            ]
        )
    ],
    '.ThreePostsGrid-excerpt-container' => [
        '.ThreePostsGrid-excerpt' => array_merge(
            [
                'color' => $module->getModuleSettingColor( 'post_excerpt_color' )
            ]
        )
    ],
    '.ThreePostsGrid-post-date-wrap' => [
        '.ThreePostsGrid-date' => array_merge(
            [
                'color' => $module->getModuleSettingColor( 'post_date_color' )
            ]
            ),

    ],
    '.ThreePostsGrid-share-btn_wrap' => [
        '.ThreePostsGrid-share_label' => array_merge(
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
