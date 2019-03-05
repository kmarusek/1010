<?php

$custom_css = [
    '&.fl-module-bw-navigation-popover' => [
        '.mega-menu-container' => [
            '> li' => [
                'margin'           => $module->getModuleSettingDimension( 'margin_top_level' ),
                'padding'          => $module->getModuleSettingDimension( 'padding_top_level' ),
                'background-color' => $module->getModuleSettingColor( 'background_color_top_level' ),
                '> a' =>  array_merge(
                    [
                        'color' => $module->getModuleSettingColor( 'color_top_level' )
                    ],
                    $module->getTypography( 'typography_top_level' )
                ),
                '&:hover' => [
                    'background-color' => $module->getModuleSettingColor( 'background_color_hover_top_level' ),
                    'a' =>[
                        'color' => $module->getModuleSettingColor( 'color_hover_top_level' )
                    ]
                ]
            ]
        ],
        '.popover' => [
            'min-width'     => $module->getModuleSettingWithUnits( 'min_width_popover' ),
            'border-width'  => $module->getModuleSettingWithUnits( 'border_width_popover' ),
            'border-color'  => $module->getModuleSettingColor( 'border_color_popover' ),
            'border-radius' => $module->getModuleSettingWithUnits( 'border_radius_popover' ),
            '&.bottom .triangle-container .triangle' => [
                'border-color' => $module->getModuleSettingColor( 'border_color_popover' ),
                'border-width' => $module->getModuleSettingWithUnits( 'border_width_popover' ),
            ],
            '.popover-content' => [
                'border-radius' => $module->getModuleSettingWithUnits( 'border_radius_popover' ),
                '.section-title'=> array_merge(
                    [
                        'color' => $module->getModuleSettingColor( 'color_popover_header' )
                    ],
                    $module->getTypography( 'typography_popover_header' )
                ),
                '.sub-menu' => [
                    '> li' => [
                        '&:not(.contains-description)' => [
                            '> a' => array_merge(
                                [
                                    'color'            => $module->getModuleSettingColor( 'color_popover_content' ),
                                    'background-color' => $module->getModuleSettingColor( 'background_color_popover_content' )
                                ],
                                $module->getTypography( 'typography_popover_contents' )
                            ),
                            '&:hover > a' => [
                                'color'            => $module->getModuleSettingColor( 'color_popover_content_hover' ),
                                'background-color' => $module->getModuleSettingColor( 'background_color_popover_content_hover' )
                            ]
                        ],
                        '&.contains-description' => [
                            'ul' => [
                                'background-color' => $module->getModuleSettingColor( 'background_color_popover_content' ),
                                '.term' => array_merge(
                                    [
                                        'color' => $module->getModuleSettingColor( 'color_popover_content' ),
                                    ],
                                    $module->getTypography( 'typography_popover_contents' )
                                ),
                                '.description' => array_merge(
                                    [
                                        'color' => $module->getModuleSettingColor( 'color_popover_description' ),
                                    ],
                                    $module->getTypography( 'typography_popover_description' )
                                )
                            ],
                            '&:hover' => [
                                'background-color' => $module->getModuleSettingColor( 'background_color_popover_content_hover' ),
                                '.term' => [
                                    'color' => $module->getModuleSettingColor( 'color_popover_content_hover' ),
                                ],
                                '.description' => [
                                    'color' => $module->getModuleSettingColor( 'color_popover_description_hover' ),
                                ]
                            ]
                        ]
                    ] 
                ]
            ]
        ]
    ]
];

// If we're using the menu icons
if ( $module->menuIconsAreEnabled() ){
    // Get the icons
    $menu_icons = $settings->menu_icons_repeater;
    // Add the location for the icons
    $custom_css['&.fl-module-bw-navigation-popover']['.submenu-icon'] = [];
    // Loop through all icons
    for ( $i = 0; $i < count( $menu_icons ); $i++ ){
        // Get the current icon
        $current_icon = $menu_icons[$i];
        $custom_css['&.fl-module-bw-navigation-popover']['.submenu-icon']['&.' . $current_icon->menu_icon_class_name . ' .icon'] = [
            'background-image' => 'url(\'' . $current_icon->menu_icon_image_src . '\')',
        ];
    }
}
// Otherwise, hide icons
else {
    $custom_css['&.fl-module-bw-navigation-popover']['.popover .popover-content .submenu-icon .icon'] = [
        'display' => 'none'
    ];
}

// If we have the menu icon enabled
if ( $module->topLevelMenuIconEnabled() ){
    // Add the font sizes for the icons
    $custom_css['&.fl-module-bw-navigation-popover']['.mega-menu-container']['> li .top-level-item-icon'] = [
        '.icon-primary' => [
            'font-size' => $module->getModuleSettingWithUnits( 'font_size_top_level_menu_icon' )
        ],
        '.icon-hover' => [
            'font-size' => $module->getModuleSettingWithUnits( 'font_size_top_level_menu_icon_hover' )
        ]
    ];
}

$module->renderModuleCSS( $custom_css );

$mobile_css = [
    '&.fl-module-bw-navigation-popover' => [
        '.mega-menu-container' => [
            '> li ' => [
                'a' => $module->getTypography( 'typography_top_level_responsive' )
            ]
        ],
        '.popover' => [
            '.popover-content' => [
                '.section-title' => $module->getTypography( 'typography_popover_header_responsive' ),
                '.sub-menu' => [
                    'li' => [
                        'a' => [
                            $module->getTypography( 'typography_popover_contents_responsive' )
                        ],
                        '&.contains-description' => [
                            'ul .description' => [
                                $module->getTypography( 'typography_popover_description_responsive' )
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$module->renderModuleCSSResponsiveMobile( $mobile_css );

$tablet_css = [
    '&.fl-module-bw-navigation-popover' => [
        '.mega-menu-container' => [
            '> li ' => [
                'a' =>  $module->getTypography( 'typography_top_level_medium' )
            ]
        ],
        '.popover' => [
            '.popover-content' => [
                '.section-title' => $module->getTypography( 'typography_popover_header_medium' ),
                '.sub-menu' => [
                    'li' => [
                        'a' => [
                            $module->getTypography( 'typography_popover_contents_medium' )
                        ],
                        '&.contains-description' => [
                            'ul .description' => [
                                $module->getTypography( 'typography_popover_description_medium' )
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$module->renderModuleCSSResponsiveTablet( $tablet_css );

$desktop_css = [
    '&.fl-module-bw-navigation-popover' => [
    ]
];

$module->renderModuleCSSResponsiveDesktop( $desktop_css );
