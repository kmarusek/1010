<?php

$settings->post_bg_color = UABB_Helper::uabb_colorpicker($settings, "post_bg_color");

$settings->post_title_color = UABB_Helper::uabb_colorpicker($settings, "post_title_color");
$settings->post_meta_color = UABB_Helper::uabb_colorpicker($settings, "post_meta_color");
$settings->post_excerpt_color = UABB_Helper::uabb_colorpicker($settings, "post_excerpt_color");
$settings->post_permalink_color = UABB_Helper::uabb_colorpicker($settings, "post_permalink_color");

?>

.fl-node-<?php echo $id; ?> .Article-related_post_gutter {
    <?php if (isset($settings->post_margin)) { echo $settings->post_margin; } ?>
    <?php if (isset($settings->post_margin_top)) { echo "padding-top: " . $settings->post_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_margin_left)) { echo "padding-left: " . $settings->post_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_margin_right)) { echo "padding-right: " . $settings->post_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_margin_bottom)) { echo "padding-bottom: " . $settings->post_margin_bottom . "px;"; } ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post {
    <?php if ($settings->post_bg_color != '') echo "background-color: " . uabb_theme_text_color($settings->post_bg_color) . ";"; ?>
    <?php if (isset($settings->post_padding)) { echo $settings->post_padding; } ?>
    <?php if (isset($settings->post_padding_top)) { echo "padding-top: " . $settings->post_padding_top . "px;"; } ?>
    <?php if (isset($settings->post_padding_left)) { echo "padding-left: " . $settings->post_padding_left . "px;"; } ?>
    <?php if (isset($settings->post_padding_right)) { echo "padding-right: " . $settings->post_padding_right . "px;"; } ?>
    <?php if (isset($settings->post_padding_bottom)) { echo "padding-bottom: " . $settings->post_padding_bottom . "px;"; } ?>

    <?php if ($settings->post_align != '') echo "text-align: " . $settings->post_align . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_thumbnail_wrapper {
    <?php if (isset($settings->post_image_margin)) { echo $settings->post_image_margin; } ?>
    <?php if (isset($settings->post_image_margin_top)) { echo "padding-top: " . $settings->post_image_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_image_margin_left)) { echo "padding-left: " . $settings->post_image_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_image_margin_right)) { echo "padding-right: " . $settings->post_image_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_image_margin_bottom)) { echo "padding-bottom: " . $settings->post_image_margin_bottom . "px;"; } ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_title {
    <?php if (isset($settings->post_title_margin)) { echo $settings->post_title_margin; } ?>
    <?php if (isset($settings->post_title_margin_top)) { echo "padding-top: " . $settings->post_title_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_title_margin_left)) { echo "padding-left: " . $settings->post_title_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_title_margin_right)) { echo "padding-right: " . $settings->post_title_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_title_margin_bottom)) { echo "padding-bottom: " . $settings->post_title_margin_bottom . "px;"; } ?>
    <?php if ($settings->post_title_color != '') echo "color: " . uabb_theme_text_color($settings->post_title_color) . ";"; ?>
    <?php if ($settings->post_title_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_title_font); ?>
    <?php if ($settings->post_title_font_size != '') echo "font-size: " . $settings->post_title_font_size . "px;"; ?>
    <?php if ($settings->post_title_line_height != '') echo "line-height: " . $settings->post_title_line_height . "px;"; ?>
    <?php if ($settings->post_title_text_transform != '') echo "text-transform: " . $settings->post_title_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_title a {
    <?php if ($settings->post_title_color != '') echo "color: " . uabb_theme_text_color($settings->post_title_color) . ";"; ?>
    <?php if ($settings->post_title_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_title_font); ?>
    <?php if ($settings->post_title_font_size != '') echo "font-size: " . $settings->post_title_font_size . "px;"; ?>
    <?php if ($settings->post_title_line_height != '') echo "line-height: " . $settings->post_title_line_height . "px;"; ?>
    <?php if ($settings->post_title_text_transform != '') echo "text-transform: " . $settings->post_title_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_meta {
    <?php if (isset($settings->post_meta_margin)) { echo $settings->post_meta_margin; } ?>
    <?php if (isset($settings->post_meta_margin_top)) { echo "padding-top: " . $settings->post_meta_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_meta_margin_left)) { echo "padding-left: " . $settings->post_meta_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_meta_margin_right)) { echo "padding-right: " . $settings->post_meta_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_meta_margin_bottom)) { echo "padding-bottom: " . $settings->post_meta_margin_bottom . "px;"; } ?>
    <?php if ($settings->post_meta_color != '') echo "color: " . uabb_theme_text_color($settings->post_meta_color) . ";"; ?>
    <?php if ($settings->post_meta_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_meta_font); ?>
    <?php if ($settings->post_meta_font_size != '') echo "font-size: " . $settings->post_meta_font_size . "px;"; ?>
    <?php if ($settings->post_meta_line_height != '') echo "line-height: " . $settings->post_meta_line_height . "px;"; ?>
    <?php if ($settings->post_meta_text_transform != '') echo "text-transform: " . $settings->post_meta_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
    <?php if (isset($settings->post_excerpt_margin)) { echo $settings->post_excerpt_margin; } ?>
    <?php if (isset($settings->post_excerpt_margin_top)) { echo "padding-top: " . $settings->post_excerpt_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_excerpt_margin_left)) { echo "padding-left: " . $settings->post_excerpt_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_excerpt_margin_right)) { echo "padding-right: " . $settings->post_excerpt_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_excerpt_margin_bottom)) { echo "padding-bottom: " . $settings->post_excerpt_margin_bottom . "px;"; } ?>
    <?php if ($settings->post_excerpt_color != '') echo "color: " . uabb_theme_text_color($settings->post_excerpt_color) . ";"; ?>
    <?php if ($settings->post_excerpt_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_excerpt_font); ?>
    <?php if ($settings->post_excerpt_font_size != '') echo "font-size: " . $settings->post_excerpt_font_size . "px;"; ?>
    <?php if ($settings->post_excerpt_line_height != '') echo "line-height: " . $settings->post_excerpt_line_height . "px;"; ?>
    <?php if ($settings->post_excerpt_text_transform != '') echo "text-transform: " . $settings->post_excerpt_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_permalink {
    <?php if (isset($settings->post_permalink_margin)) { echo $settings->post_permalink_margin; } ?>
    <?php if (isset($settings->post_permalink_margin_top)) { echo "padding-top: " . $settings->post_permalink_margin_top . "px;"; } ?>
    <?php if (isset($settings->post_permalink_margin_left)) { echo "padding-left: " . $settings->post_permalink_margin_left . "px;"; } ?>
    <?php if (isset($settings->post_permalink_margin_right)) { echo "padding-right: " . $settings->post_permalink_margin_right . "px;"; } ?>
    <?php if (isset($settings->post_permalink_margin_bottom)) { echo "padding-bottom: " . $settings->post_permalink_margin_bottom . "px;"; } ?>
    <?php if ($settings->post_permalink_color != '') echo "color: " . uabb_theme_text_color($settings->post_permalink_color) . ";"; ?>
    <?php if ($settings->post_permalink_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_permalink_font); ?>
    <?php if ($settings->post_permalink_font_size != '') echo "font-size: " . $settings->post_permalink_font_size . "px;"; ?>
    <?php if ($settings->post_permalink_line_height != '') echo "line-height: " . $settings->post_permalink_line_height . "px;"; ?>
    <?php if ($settings->post_permalink_text_transform != '') echo "text-transform: " . $settings->post_permalink_text_transform . ";"; ?>
}


<?php if( $global_settings->responsive_enabled ) { ?>
    @media ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_gutter {
            <?php if (isset($settings->post_margin_top_medium)) { echo "padding-top: " . $settings->post_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_margin_left_medium)) { echo "padding-left: " . $settings->post_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_margin_right_medium)) { echo "padding-right: " . $settings->post_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_margin_bottom_medium . "px;"; } ?>
        }
        
        .fl-node-<?php echo $id; ?> .Article-related_post {
            <?php if (isset($settings->post_padding_top_medium)) { echo "padding-top: " . $settings->post_padding_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_padding_left_medium)) { echo "padding-left: " . $settings->post_padding_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_padding_right_medium)) { echo "padding-right: " . $settings->post_padding_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_padding_bottom_medium)) { echo "padding-bottom: " . $settings->post_padding_bottom_medium . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_thumbnail_wrapper {
            <?php if (isset($settings->post_image_margin_top_medium)) { echo "padding-top: " . $settings->post_image_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_left_medium)) { echo "padding-left: " . $settings->post_image_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_right_medium)) { echo "padding-right: " . $settings->post_image_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_image_margin_bottom_medium . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_title {
            <?php if (isset($settings->post_title_margin_top_medium)) { echo "padding-top: " . $settings->post_title_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_left_medium)) { echo "padding-left: " . $settings->post_title_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_right_medium)) { echo "padding-right: " . $settings->post_title_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_title_margin_bottom_medium . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_title,
        .fl-node-<?php echo $id; ?> .Article-related_post_title a {
            <?php if ($settings->post_title_font_size_medium != '') echo "font-size: " . $settings->post_title_font_size_medium . "px;"; ?>
            <?php if ($settings->post_title_line_height_medium != '') echo "line-height: " . $settings->post_title_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if (isset($settings->post_meta_margin_top_medium)) { echo "padding-top: " . $settings->post_meta_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_left_medium)) { echo "padding-left: " . $settings->post_meta_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_right_medium)) { echo "padding-right: " . $settings->post_meta_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_meta_margin_bottom_medium . "px;"; } ?>
            <?php if ($settings->post_meta_font_size_medium != '') echo "font-size: " . $settings->post_meta_font_size_medium . "px;"; ?>
            <?php if ($settings->post_meta_line_height_medium != '') echo "line-height: " . $settings->post_meta_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if (isset($settings->post_excerpt_margin_top_medium)) { echo "padding-top: " . $settings->post_excerpt_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_left_medium)) { echo "padding-left: " . $settings->post_excerpt_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_right_medium)) { echo "padding-right: " . $settings->post_excerpt_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_excerpt_margin_bottom_medium . "px;"; } ?>
            <?php if ($settings->post_excerpt_font_size_medium != '') echo "font-size: " . $settings->post_excerpt_font_size_medium . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height_medium != '') echo "line-height: " . $settings->post_excerpt_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if (isset($settings->post_permalink_margin_top_medium)) { echo "padding-top: " . $settings->post_permalink_margin_top_medium . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_left_medium)) { echo "padding-left: " . $settings->post_permalink_margin_left_medium . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_right_medium)) { echo "padding-right: " . $settings->post_permalink_margin_right_medium . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_bottom_medium)) { echo "padding-bottom: " . $settings->post_permalink_margin_bottom_medium . "px;"; } ?>
            <?php if ($settings->post_permalink_font_size_medium != '') echo "font-size: " . $settings->post_permalink_font_size_medium . "px;"; ?>
            <?php if ($settings->post_permalink_line_height_medium != '') echo "line-height: " . $settings->post_permalink_line_height_medium . "px;"; ?>
        }
    }

    @media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_gutter {
            <?php if (isset($settings->post_margin_top_responsive)) { echo "padding-top: " . $settings->post_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_margin_left_responsive)) { echo "padding-left: " . $settings->post_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_margin_right_responsive)) { echo "padding-right: " . $settings->post_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_margin_bottom_responsive . "px;"; } ?>
        }
        
        .fl-node-<?php echo $id; ?> .Article-related_post {
            <?php if (isset($settings->post_padding_top_responsive)) { echo "padding-top: " . $settings->post_padding_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_padding_left_responsive)) { echo "padding-left: " . $settings->post_padding_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_padding_right_responsive)) { echo "padding-right: " . $settings->post_padding_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_padding_bottom_responsive)) { echo "padding-bottom: " . $settings->post_padding_bottom_responsive . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_thumbnail_wrapper {
            <?php if (isset($settings->post_image_margin_top_responsive)) { echo "padding-top: " . $settings->post_image_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_left_responsive)) { echo "padding-left: " . $settings->post_image_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_right_responsive)) { echo "padding-right: " . $settings->post_image_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_image_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_image_margin_bottom_responsive . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_title {
            <?php if (isset($settings->post_title_margin_top_responsive)) { echo "padding-top: " . $settings->post_title_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_left_responsive)) { echo "padding-left: " . $settings->post_title_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_right_responsive)) { echo "padding-right: " . $settings->post_title_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_title_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_title_margin_bottom_responsive . "px;"; } ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_title,
        .fl-node-<?php echo $id; ?> .Article-related_post_title a {
            <?php if ($settings->post_title_font_size_responsive != '') echo "font-size: " . $settings->post_title_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_title_line_height_responsive != '') echo "line-height: " . $settings->post_title_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if (isset($settings->post_meta_margin_top_responsive)) { echo "padding-top: " . $settings->post_meta_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_left_responsive)) { echo "padding-left: " . $settings->post_meta_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_right_responsive)) { echo "padding-right: " . $settings->post_meta_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_meta_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_meta_margin_bottom_responsive . "px;"; } ?>
            <?php if ($settings->post_meta_font_size_responsive != '') echo "font-size: " . $settings->post_meta_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_meta_line_height_responsive != '') echo "line-height: " . $settings->post_meta_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if (isset($settings->post_excerpt_margin_top_responsive)) { echo "padding-top: " . $settings->post_excerpt_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_left_responsive)) { echo "padding-left: " . $settings->post_excerpt_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_right_responsive)) { echo "padding-right: " . $settings->post_excerpt_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_excerpt_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_excerpt_margin_bottom_responsive . "px;"; } ?>
            <?php if ($settings->post_excerpt_font_size_responsive != '') echo "font-size: " . $settings->post_excerpt_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height_responsive != '') echo "line-height: " . $settings->post_excerpt_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if (isset($settings->post_permalink_margin_top_responsive)) { echo "padding-top: " . $settings->post_permalink_margin_top_responsive . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_left_responsive)) { echo "padding-left: " . $settings->post_permalink_margin_left_responsive . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_right_responsive)) { echo "padding-right: " . $settings->post_permalink_margin_right_responsive . "px;"; } ?>
            <?php if (isset($settings->post_permalink_margin_bottom_responsive)) { echo "padding-bottom: " . $settings->post_permalink_margin_bottom_responsive . "px;"; } ?>
            <?php if ($settings->post_permalink_font_size_responsive != '') echo "font-size: " . $settings->post_permalink_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_permalink_line_height_responsive != '') echo "line-height: " . $settings->post_permalink_line_height_responsive . "px;"; ?>
        }
    }
<?php } ?>
