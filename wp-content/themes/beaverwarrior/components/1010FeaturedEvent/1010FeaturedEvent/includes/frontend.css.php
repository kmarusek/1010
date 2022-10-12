<?php
/** 
*   Typography
*/
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_category_typography',
        'selector'  => ".post_featured",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_date_typography',
        'selector'  => ".post_text-date",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_title_typography',
        'selector'  => ".post_text-title h4",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_excerpt_typography',
        'selector'  => ".post_text-excerpt p",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_link_typography',
        'selector'  => ".post_text-link a",
    ),
);

/**
 * Margin / Padding
 */
$custom_css_general = [
    '.TenTenFeaturedEvent-post' => [
        'margin' => $module->getModuleSettingDimension( 'event_margin' ),
        'padding'  => $module->getModuleSettingDimension( 'event_padding' ),
    ],
    '.post_text-cat_date'  => [
        'margin' => $module->getModuleSettingDimension( 'date_margin' ),
        'padding' => $module->getModuleSettingDimension( 'date_padding' ),
    ],
    '.post_text-title'  => [
        'margin' => $module->getModuleSettingDimension( 'title_margin' ),
        'padding' => $module->getModuleSettingDimension( 'title_padding' ),
    ],
    '.post_text-excerpt'  => [
        'margin' => $module->getModuleSettingDimension( 'excerpt_margin' ),
        'padding' => $module->getModuleSettingDimension( 'excerpt_padding' ),
    ],
    '.post_text-link'  => [
        'margin' => $module->getModuleSettingDimension( 'link_margin' ),
        'padding' => $module->getModuleSettingDimension( 'link_padding' ),
    ],
    '.TenTenFeaturedEvent-filter_wrap'  => [
        'margin' => $module->getModuleSettingDimension( 'filter_margin' ),
        'padding' => $module->getModuleSettingDimension( 'filter_padding' ),
    ]
    
];

$module->renderModuleCSS( $custom_css_general );


/**
 * Colors
 */
?>

.fl-node-<?php echo $id; ?> .FeaturedEvent-title {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_main_title_color); ?>;
}

.fl-node-<?php echo $id; ?> .FeaturedEvent-type {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_type_color); ?>;
}

.fl-node-<?php echo $id; ?> .FeaturedEvent-date_from {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_date_from_color); ?>;
}

.fl-node-<?php echo $id; ?> .FeaturedEvent-date_to {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_date_to_color); ?>;
}

.fl-node-<?php echo $id; ?> .FeaturedEvent-description {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_description_color); ?>;
}
.fl-node-<?php echo $id; ?> .FeaturedEvent-link_button {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->featured_event_link_button_color); ?>;
}


