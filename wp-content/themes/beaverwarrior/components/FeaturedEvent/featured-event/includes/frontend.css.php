<?php
/**
 * Typography
 */
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_main_title_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-title",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_type_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-type",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_date_from_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-date_from",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_date_to_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-date_to",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_description_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-description",
) );
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'featured_event_link_button_typography', 
    'selector'    => ".fl-node-$id .FeaturedEvent-link_button",
) );

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