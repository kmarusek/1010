<?php

$settings->post_bg_color = UABB_Helper::uabb_colorpicker($settings, "post_bg_color");

$settings->post_title_color = UABB_Helper::uabb_colorpicker($settings, "post_title_color");
$settings->post_title_font_size = UABB_Helper::uabb_colorpicker($settings, "post_title_font_size");
$settings->post_title_line_height = UABB_Helper::uabb_colorpicker($settings, "post_title_line_height");

$settings->post_excerpt_color = UABB_Helper::uabb_colorpicker($settings, "post_excerpt_color");
$settings->post_excerpt_font_size = UABB_Helper::uabb_colorpicker($settings, "post_excerpt_font_size");
$settings->post_excerpt_line_height = UABB_Helper::uabb_colorpicker($settings, "post_excerpt_line_height");

?>

.fl-node-<?php echo $id; ?> .Article-related_post {
    <?php if ($settings->post_bg_color != '') echo "background-color: " . uabb_theme_text_color($settings->post_bg_color) . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_title {
    <?php if ($settings->post_title_color != '') echo "color: " . uabb_theme_text_color($settings->post_title_color) . ";"; ?>
    <?php if ($settings->post_title_font_size != '') echo "font-size: " . $settings->post_title_font_size . "px;"; ?>
    <?php if ($settings->post_title_line_height != '') echo "line-height: " . $settings->post_title_line_height . "px;"; ?>
}


.fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
    <?php if ($settings->post_excerpt_color != '') echo "color: " . uabb_theme_text_color($settings->post_excerpt_color) . ";"; ?>
    <?php if ($settings->post_excerpt_font_size != '') echo "font-size: " . $settings->post_excerpt_font_size . "px;"; ?>
    <?php if ($settings->post_excerpt_line_height != '') echo "line-height: " . $settings->post_excerpt_line_height . "px;"; ?>
}
