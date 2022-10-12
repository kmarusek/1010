<?php
/** 
*   Typography
*/
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_date_typography',
        'selector'  => ".TenTenEvents .post_text-date",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_title_typography',
        'selector'  => ".TenTenEvents .post_text-title h4",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_excerpt_typography',
        'selector'  => ".TenTenEvents .post_text-excerpt p",
    ),
);
FLBuilderCSS::typography_field_rule( 
    array(
        'settings'  => $settings,
        'setting_name' => 'event_link_typography',
        'selector'  => ".TenTenEvents .post_text-link a",
    ),
);
/**
 * Margin / Padding
 */
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'event_margin',
    'selector'    => ".TenTenEvents-post",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'event_margin_top', // As in $settings->padding_top
      'margin-right'  => 'event_margin_right',
      'margin-bottom' => 'event_margin_bottom',
      'margin-left'   => 'event_margin_left',
    ),
  ) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'event_padding',
    'selector'    => ".TenTenEvents-post",
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
    'selector'    => ".TenTenEvents .post_text-cat_date",
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
    'selector'    => ".TenTenEvents .post_text-title",
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
    'selector'    => ".TenTenEvents .post_text-excerpt",
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
    'selector'    => ".TenTenEvents .post_text-link",
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
    'selector'    => ".TenTenEvents-filter_wrap",
    'unit'        => 'px', // Omit if custom unit select is used.
    'props'       => array(
      'margin-top'    => 'filter_margin_top', // As in $settings->padding_top
      'margin-right'  => 'filter_margin_right',
      'margin-bottom' => 'filter_margin_bottom',
      'margin-left'   => 'filter_margin_left',
    ),
  ) );

