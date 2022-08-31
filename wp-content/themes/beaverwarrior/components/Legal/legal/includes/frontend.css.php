<?php
/**
 * Typography
 */
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'legal_option_typography', 
    'selector'    => ".fl-node-$id .Legal-title",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'modal_name_typography', 
    'selector'    => ".fl-node-$id .Legal-name",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'modal_text_typography', 
    'selector'    => ".fl-node-$id .Legal-position p",
) );


/**
 * Colors
 */
?>

.fl-node-<?php echo $id; ?> .Legal-title {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->legal_option_color); ?>;
}

.fl-node-<?php echo $id; ?> .Legal-name {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->modal_name_color); ?>;
}

.fl-node-<?php echo $id; ?> .Legal-position p {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->modal_text_color); ?>;
}
