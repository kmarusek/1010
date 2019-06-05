<?php
$custom_css_general = [
    '.descriptive-list-container' => [
        'li' => [
            'margin-top' => $module->getModuleSettingWithUnits( 'dt_margin_top' ),
            '.icon-container' => [
                'margin-right' => $module->getModuleSettingWithUnits( 'icon_margin_right' )
            ],
        ],
        'dl' => [
            'dt' => array_merge(
                $module->getTypography( 'dt_typography' ),
                [
                    'color'      => $module->getModuleSettingColor( 'dt_color' )
                ]
            ),
            'dd' => array_merge(
                $module->getTypography( 'dd_typography' ),
                [
                    'color'      => $module->getModuleSettingColor( 'dd_color' ),
                    'margin-top' => $module->getModuleSettingWithUnits( 'dd_margin_top' )
                ]
            )
        ]
    ]
];

// Get all of the list items
$list_items = $module->getListItems();
// Loop through the list items
for ( $i=0; $i<count($list_items); $i++ ){
    // The current item
    $current_list_item = $list_items[$i];
    // If the icon is enabled, add the CSS
    if ( $current_list_item->icon_enabled === 'enabled' ){
        // Get the icon color
        $icon_color = '#' . $current_list_item->icon_color;
        // Get the icon size
        $icon_size  = $current_list_item->icon_size . 'px';
        // Get the nth-child selector
        $nth_child = $i + 1;
        // Calculate the width to subtract
        $width_to_subtract = $current_list_item->icon_size + $settings->icon_margin_right;
        // Add to the CSS to be rendered
        $custom_css_general['.descriptive-list-container li:nth-child(' . $nth_child . ') .dl-icon'] = [
            'color'     => $icon_color,
            'font-size' => $icon_size
        ];
        $custom_css_general['.descriptive-list-container li:nth-child(' . $nth_child . ') .content-container'] = [
            'width' => 'calc(100% - ' . $width_to_subtract . 'px)'
        ];
    }
}

$module->renderModuleCSS( $custom_css_general );