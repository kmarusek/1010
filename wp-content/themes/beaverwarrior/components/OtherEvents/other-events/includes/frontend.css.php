<?php
/**
 * Typography
 */
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_main_title_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-title",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_type_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-type",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_date_from_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-date_from",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_date_to_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-date_to",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_description_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-description",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'other_events_link_button_typography', 
    'selector'    => ".fl-node-$id .OtherEvents-link_button",
) );

/**
 * Colors
 */
?>

.fl-node-<?php echo $id; ?> .OtherEvents-title {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_main_title_color); ?>;
}

.fl-node-<?php echo $id; ?> .OtherEvents-type {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_type_color); ?>;
}

.fl-node-<?php echo $id; ?> .OtherEvents-date_from {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_date_from_color); ?>;
}

.fl-node-<?php echo $id; ?> .OtherEvents-date_to {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_date_to_color); ?>;
}

.fl-node-<?php echo $id; ?> .OtherEvents-description {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_description_color); ?>;
}
.fl-node-<?php echo $id; ?> .OtherEvents-link_button {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->other_events_link_button_color); ?>;
}