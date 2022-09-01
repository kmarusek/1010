<?php 

/**
 * Typography
 */

/**
 * Colors
 */
?>

.fl-node-<?php echo $id; ?> .TenTenHero-wrap {
   background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->background_color); ?>;
}

.fl-node-<?php echo $id; ?> .TenTenHero-content-wrap h2{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->title_color); ?>;
}

.fl-node-<?php echo $id; ?> .TenTenHero-content-wrap p{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->content_color); ?>;
}

.fl-node-<?php echo $id; ?> .TenTenHero-content-wrap .TenTenHero-buttonwrap .TenTenHero-button{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}

.fl-node-<?php echo $id; ?> .TenTenHero-content-wrap .TenTenHero-buttonwrap .TenTenHero-button p{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}
.fl-node-<?php echo $id; ?> .TenTenHero-content-wrap .TenTenHero-buttonwrap a.TenTenHero-button::before {
   background: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}



