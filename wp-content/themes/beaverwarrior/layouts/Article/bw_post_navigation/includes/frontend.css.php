<?php

$settings->post_bg_color = UABB_Helper::uabb_colorpicker($settings, "post_bg_color");

$settings->post_title_color = UABB_Helper::uabb_colorpicker($settings, "post_title_color");
$settings->post_meta_color = UABB_Helper::uabb_colorpicker($settings, "post_meta_color");
$settings->post_excerpt_color = UABB_Helper::uabb_colorpicker($settings, "post_excerpt_color");
$settings->post_permalink_color = UABB_Helper::uabb_colorpicker($settings, "post_permalink_color");

?>

.fl-node-<?php echo $id; ?> .Article-related_post_gutter {
    <?php echo $settings->post_margin; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post {
    <?php if ($settings->post_bg_color != '') echo "background-color: " . uabb_theme_text_color($settings->post_bg_color) . ";"; ?>
    <?php echo $settings->post_padding; ?>

    <?php if ($settings->post_align != '') echo "text-align: " . $settings->post_align . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_thumbnail_wrapper {
    <?php echo $settings->post_image_margin; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_title {
    <?php echo $settings->post_title_margin; ?>
    <?php if ($settings->post_title_color != '') echo "color: " . uabb_theme_text_color($settings->post_title_color) . ";"; ?>
    <?php if ($settings->post_title_font_size["desktop"] != '') echo "font-size: " . $settings->post_title_font_size["desktop"] . "px;"; ?>
    <?php if ($settings->post_title_line_height["desktop"] != '') echo "line-height: " . $settings->post_title_line_height["desktop"] . "px;"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_meta {
    <?php echo $settings->post_meta_margin; ?>
    <?php if ($settings->post_meta_color != '') echo "color: " . uabb_theme_text_color($settings->post_meta_color) . ";"; ?>
    <?php if ($settings->post_meta_font_size["desktop"] != '') echo "font-size: " . $settings->post_meta_font_size["desktop"] . "px;"; ?>
    <?php if ($settings->post_meta_line_height["desktop"] != '') echo "line-height: " . $settings->post_meta_line_height["desktop"] . "px;"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
    <?php echo $settings->post_excerpt_margin; ?>
    <?php if ($settings->post_excerpt_color != '') echo "color: " . uabb_theme_text_color($settings->post_excerpt_color) . ";"; ?>
    <?php if ($settings->post_excerpt_font_size["desktop"] != '') echo "font-size: " . $settings->post_excerpt_font_size["desktop"] . "px;"; ?>
    <?php if ($settings->post_excerpt_line_height["desktop"] != '') echo "line-height: " . $settings->post_excerpt_line_height["desktop"] . "px;"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_permalink {
    <?php echo $settings->post_permalink_margin; ?>
    <?php if ($settings->post_permalink_color != '') echo "color: " . uabb_theme_text_color($settings->post_permalink_color) . ";"; ?>
    <?php if ($settings->post_permalink_font_size["desktop"] != '') echo "font-size: " . $settings->post_permalink_font_size["desktop"] . "px;"; ?>
    <?php if ($settings->post_permalink_line_height["desktop"] != '') echo "line-height: " . $settings->post_permalink_line_height["desktop"] . "px;"; ?>
}


<?php if( $global_settings->responsive_enabled ) { ?>
    @media ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_title {
            <?php if ($settings->post_title_font_size["medium"] != '') echo "font-size: " . $settings->post_title_font_size["medium"] . "px;"; ?>
            <?php if ($settings->post_title_line_height["medium"] != '') echo "line-height: " . $settings->post_title_line_height["medium"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if ($settings->post_meta_font_size["medium"] != '') echo "font-size: " . $settings->post_meta_font_size["medium"] . "px;"; ?>
            <?php if ($settings->post_meta_line_height["medium"] != '') echo "line-height: " . $settings->post_meta_line_height["medium"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if ($settings->post_excerpt_font_size["medium"] != '') echo "font-size: " . $settings->post_excerpt_font_size["medium"] . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height["medium"] != '') echo "line-height: " . $settings->post_excerpt_line_height["medium"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if ($settings->post_permalink_font_size["medium"] != '') echo "font-size: " . $settings->post_permalink_font_size["medium"] . "px;"; ?>
            <?php if ($settings->post_permalink_line_height["medium"] != '') echo "line-height: " . $settings->post_permalink_line_height["medium"] . "px;"; ?>
        }
    }

    @media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_title {
            <?php if ($settings->post_title_font_size["small"] != '') echo "font-size: " . $settings->post_title_font_size["small"] . "px;"; ?>
            <?php if ($settings->post_title_line_height["small"] != '') echo "line-height: " . $settings->post_title_line_height["small"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if ($settings->post_meta_font_size["small"] != '') echo "font-size: " . $settings->post_meta_font_size["small"] . "px;"; ?>
            <?php if ($settings->post_meta_line_height["small"] != '') echo "line-height: " . $settings->post_meta_line_height["small"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if ($settings->post_excerpt_font_size["small"] != '') echo "font-size: " . $settings->post_excerpt_font_size["small"] . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height["small"] != '') echo "line-height: " . $settings->post_excerpt_line_height["small"] . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if ($settings->post_permalink_font_size["small"] != '') echo "font-size: " . $settings->post_permalink_font_size["small"] . "px;"; ?>
            <?php if ($settings->post_permalink_line_height["small"] != '') echo "line-height: " . $settings->post_permalink_line_height["small"] . "px;"; ?>
        }
    }
<?php } ?>
