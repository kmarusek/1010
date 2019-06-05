<?php
$custom_css_general = [
    '.social-icons-container' => [
        '.social-icons-inner' => [
            'background-color' => $module->getModuleSettingColor( 'sheet_background_color' ) 
        ],
        '.social-icons-inner li' => [
            '> a i' => [
                'color' => $module->getModuleSettingColor( 'icon_color' )
            ],
            '&:hover > a i' => [
                'color' => $module->getModuleSettingColor( 'icon_color_hover' )
            ]
        ]
    ]
];

$custom_css_mobile = [
    '&.fl-module-bw-social-icons' => [
        'text-align' => 'center',
        '.social-icons-container' => [
            'position' => 'relative',
            'display'  => 'inline-block',
            '&.affix' => [
                'position' => 'relative',
                'top'      => '0 !important'
            ]
        ],
        '.social-icons-list' => [
            'padding'            => $module->getModuleSettingDimension( 'mobile_icon_list_padding' ),
            'display'            => 'flex',
            'box-sizing'         => 'border-box',
            'flex-direction'     => 'row',
            '-webkit-box-flex'   => '0',
            '-moz-box-flex'      => '0',
            '-ms-box-flex'       => '0',
            '-ms-flex'           => '0 1 auto',
            'flex'               => '0 1 auto',
            '-ms-flex-direction' => 'row',
            '-ms-flex-wrap'      => 'wrap',
            'flex-wrap'          => 'wrap',
            'align-items'        => 'center',
            'justify-content'    => 'center',
            'li' => [
                'margin' => $module->getModuleSettingDimension( 'mobile_icon_margin' )
            ]
        ],
        '.social-icons-list i' => [
            'font-size' => '18px' /* @todo STUB */
        ]
    ]
];

$custom_css_desktop = [
    '&.fl-module-bw-social-icons' => [
        '.social-icons-container' => [
            'position'    => 'absolute',
            'z-index'     => '10',
            'top'         => '0px',
            'left'        => '0px',
            'margin'      => '0px',
            'padding-top' => $module->getModuleSettingWithUnits( 'desktop_affix_offset' ),
            '&.affix' => [
                'position' => 'fixed'
            ]
        ],
        '.social-icons-inner' => [
            'padding' => $module->getModuleSettingDimension( 'desktop_icon_container_padding' )
        ],
        '.social-icons-list' => [
            'li' => [
                'text-align' => 'center',
                '&:not(:last-child)' => [
                    'margin-bottom' => $module->getModuleSettingWithUnits( 'desktop_icon_margin_bottom' )
                ]
            ]
        ],
        '.social-icons-list i' => [
            'font-size' => '18px' /* @todo STUB */
        ]
    ]
];

// Get the settings for our mobile (i.e., responsive + tablet) settings
$global_viewport_settings = FLBuilderModel::get_global_settings();
// Get the max width for tablet
$tablet_max_width = $global_viewport_settings->medium_breakpoint - 1;

$module->renderModuleCSS( $custom_css_general );
$module->renderModuleCSSResponsive( ['max' => $tablet_max_width], $custom_css_mobile );
$module->renderModuleCSSResponsiveDesktop( $custom_css_desktop );