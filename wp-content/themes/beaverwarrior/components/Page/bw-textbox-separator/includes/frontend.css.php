<?php
$border_styles    = $module->getModuleSettingBorder( 'textbox_border' );
$border_style     = isset( $border_styles['border-style'] ) ? $border_styles['border-style'] : null;
$border_width_top = isset( $border_styles['border-top-width'] ) ? $border_styles['border-top-width'] : null;
$border_color     = isset( $border_styles['border-color'] ) ? $border_styles['border-color'] : null;
$border_shadow    = isset( $border_styles['box-shadow'] ) ? $border_styles['box-shadow'] : null;

$custom_css_general = [
    '.textbox-separator-container' => [
        '&::before' => [
            'border-top-width'  => $border_width_top,
            'border-top-color'  => $border_color,
            'border-top-style'  => $border_style,
            'border-top-shadow' => $border_shadow
        ],
        '&::after' => [
            'border-top-width'  => $border_width_top,
            'border-top-color'  => $border_color,
            'border-top-style'  => $border_style,
            'border-top-shadow' => $border_shadow
        ]
    ],
    '.textbox' => array_merge(
        $module->getModuleSettingBorder( 'textbox_border' ),
        [
            'padding' => $module->getModuleSettingDimension( 'textbox_padding' ),
        ]
    ),
    '.textbox .textbox-header' => array_merge(
        $module->getTypography( 'textbox_typography' ),
        [
            'color' => $module->getModuleSettingColor( 'textbox_header_color' )
        ]
    )
];

$module->renderModuleCSS( $custom_css_general );