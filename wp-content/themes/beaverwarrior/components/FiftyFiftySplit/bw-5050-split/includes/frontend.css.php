<?php 

/**
 * Typography
 */

/**
 * Colors
 */
?>

.fl-node-<?php echo $id; ?> .FiftyFiftySplit-wrap {
   background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->background_color); ?>;
}

.fl-node-<?php echo $id; ?> .FiftyFiftySplit-content-wrap h2{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->title_color); ?>;
}

.fl-node-<?php echo $id; ?> .FiftyFiftySplit-content-wrap p{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->content_color); ?>;
}

.fl-node-<?php echo $id; ?> .FiftyFiftySplit-content-wrap .FiftyFiftySplit-buttonwrap .FiftyFiftySplit-button{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}

.fl-node-<?php echo $id; ?> .FiftyFiftySplit-content-wrap .FiftyFiftySplit-buttonwrap .FiftyFiftySplit-button p{
   color: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}
.fl-node-<?php echo $id; ?> .FiftyFiftySplit-content-wrap .FiftyFiftySplit-buttonwrap a.FiftyFiftySplit-button::before {
   background: <?php echo FLBuilderColor::hex_or_rgb($settings->button_color); ?>;
}



