<?php
/** 
*   Typography
*/
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_category_typography',
        'selector'  => ".TenTenFeaturedEvent .post_featured",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_date_typography',
        'selector'  => ".TenTenFeaturedEvent .post_text-date",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_title_typography',
        'selector'  => ".TenTenFeaturedEvent .post_text-title h4",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_excerpt_typography',
        'selector'  => ".TenTenFeaturedEvent .post_text-excerpt p",
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
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'event_padding',
    'selector'    => ".TenTenFeaturedEvent .TenTenFeaturedEvent-post",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'padding-top'    => 'event_padding_top', // As in $settings->padding_top
      'padding-right'  => 'event_padding_right',
      'padding-bottom' => 'event_padding_bottom',
      'padding-left'   => 'event_padding_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'date_margin',
    'selector'    => ".TenTenFeaturedEvent .post_text-cat_date",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'date_margin_top', // As in $settings->padding_top
      'margin-right'  => 'date_margin_right',
      'margin-bottom' => 'date_margin_bottom',
      'margin-left'   => 'date_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'title_margin',
    'selector'    => ".TenTenFeaturedEvent .post_text-title",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'title_margin_top', // As in $settings->padding_top
      'margin-right'  => 'title_margin_right',
      'margin-bottom' => 'title_margin_bottom',
      'margin-left'   => 'title_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'excerpt_margin',
    'selector'    => ".TenTenFeaturedEvent .post_text-excerpt",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'excerpt_margin_top', // As in $settings->padding_top
      'margin-right'  => 'excerpt_margin_right',
      'margin-bottom' => 'excerpt_margin_bottom',
      'margin-left'   => 'excerpt_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'link_margin',
    'selector'    => ".TenTenFeaturedEvent .post_text-link",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'link_margin_top', // As in $settings->padding_top
      'margin-right'  => 'link_margin_right',
      'margin-bottom' => 'link_margin_bottom',
      'margin-left'   => 'link_margin_left',
    ),
  ) );
  FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'filter_margin',
    'selector'    => ".TenTenFeaturedEvent-filter_wrap",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'filter_margin_top', // As in $settings->padding_top
      'margin-right'  => 'filter_margin_right',
      'margin-bottom' => 'filter_margin_bottom',
      'margin-left'   => 'filter_margin_left',
    ),
  ) );

// $custom_css_general = [
    // '.TenTenFeaturedEvent-post' => [
    //     'margin' => $module->getModuleSettingDimension( 'event_margin' ),
    //     'padding'  => $module->getModuleSettingDimension( 'event_padding' ),
    // ],
    // '.post_text-cat_date'  => [
    //     'margin' => $module->getModuleSettingDimension( 'date_margin' ),
    //     'padding' => $module->getModuleSettingDimension( 'date_padding' ),
    // ],
    // '.post_text-title'  => [
    //     'margin' => $module->getModuleSettingDimension( 'title_margin' ),
    //     'padding' => $module->getModuleSettingDimension( 'title_padding' ),
    // ],
    // '.post_text-excerpt'  => [
    //     'margin' => $module->getModuleSettingDimension( 'excerpt_margin' ),
    //     'padding' => $module->getModuleSettingDimension( 'excerpt_padding' ),
    // ],
    // '.post_text-link'  => [
    //     'margin' => $module->getModuleSettinlink_margin' ),
    //     'padding' => $module->getModuleSettingDimension( 'link_padding' ),
    // ],
    // '.TenTenFeaturedEvent-filter_wrap'  => [
    //     'margin' => $module->getModuleSettingDimension( 'filter_margin' ),
    //     'padding' => $module->getModuleSettingDimension( 'filter_padding' ),
    // ]
    
// ];

// $module->renderModuleCSS( $custom_css_general );


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


