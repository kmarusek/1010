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
    <?php echo $settings->post_meta_margin; ?>
    <?php if ($settings->post_meta_color != '') echo "color: " . uabb_theme_text_color($settings->post_meta_color) . ";"; ?>
    <?php if ($settings->post_meta_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_meta_font); ?>
    <?php if ($settings->post_meta_font_size != '') echo "font-size: " . $settings->post_meta_font_size . "px;"; ?>
    <?php if ($settings->post_meta_line_height != '') echo "line-height: " . $settings->post_meta_line_height . "px;"; ?>
    <?php if ($settings->post_meta_text_transform != '') echo "text-transform: " . $settings->post_meta_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
    <?php echo $settings->post_excerpt_margin; ?>
    <?php if ($settings->post_excerpt_color != '') echo "color: " . uabb_theme_text_color($settings->post_excerpt_color) . ";"; ?>
    <?php if ($settings->post_excerpt_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_excerpt_font); ?>
    <?php if ($settings->post_excerpt_font_size != '') echo "font-size: " . $settings->post_excerpt_font_size . "px;"; ?>
    <?php if ($settings->post_excerpt_line_height != '') echo "line-height: " . $settings->post_excerpt_line_height . "px;"; ?>
    <?php if ($settings->post_excerpt_text_transform != '') echo "text-transform: " . $settings->post_excerpt_text_transform . ";"; ?>
}

.fl-node-<?php echo $id; ?> .Article-related_post_permalink {
    <?php echo $settings->post_permalink_margin; ?>
    <?php if ($settings->post_permalink_color != '') echo "color: " . uabb_theme_text_color($settings->post_permalink_color) . ";"; ?>
    <?php if ($settings->post_permalink_font['family'] != 'Default') UABB_Helper::uabb_font_css($settings->post_permalink_font); ?>
    <?php if ($settings->post_permalink_font_size != '') echo "font-size: " . $settings->post_permalink_font_size . "px;"; ?>
    <?php if ($settings->post_permalink_line_height != '') echo "line-height: " . $settings->post_permalink_line_height . "px;"; ?>
    <?php if ($settings->post_permalink_text_transform != '') echo "text-transform: " . $settings->post_permalink_text_transform . ";"; ?>
}


<?php if( $global_settings->responsive_enabled ) { ?>
    @media ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_title,
        .fl-node-<?php echo $id; ?> .Article-related_post_title a {
            <?php if ($settings->post_title_font_size_medium != '') echo "font-size: " . $settings->post_title_font_size_medium . "px;"; ?>
            <?php if ($settings->post_title_line_height_medium != '') echo "line-height: " . $settings->post_title_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if ($settings->post_meta_font_size_medium != '') echo "font-size: " . $settings->post_meta_font_size_medium . "px;"; ?>
            <?php if ($settings->post_meta_line_height_medium != '') echo "line-height: " . $settings->post_meta_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if ($settings->post_excerpt_font_size_medium != '') echo "font-size: " . $settings->post_excerpt_font_size_medium . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height_medium != '') echo "line-height: " . $settings->post_excerpt_line_height_medium . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if ($settings->post_permalink_font_size_medium != '') echo "font-size: " . $settings->post_permalink_font_size_medium . "px;"; ?>
            <?php if ($settings->post_permalink_line_height_medium != '') echo "line-height: " . $settings->post_permalink_line_height_medium . "px;"; ?>
        }
    }

    @media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .Article-related_post_title,
        .fl-node-<?php echo $id; ?> .Article-related_post_title a {
            <?php if ($settings->post_title_font_size_responsive != '') echo "font-size: " . $settings->post_title_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_title_line_height_responsive != '') echo "line-height: " . $settings->post_title_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_meta {
            <?php if ($settings->post_meta_font_size_responsive != '') echo "font-size: " . $settings->post_meta_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_meta_line_height_responsive != '') echo "line-height: " . $settings->post_meta_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_excerpt {
            <?php if ($settings->post_excerpt_font_size_responsive != '') echo "font-size: " . $settings->post_excerpt_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_excerpt_line_height_responsive != '') echo "line-height: " . $settings->post_excerpt_line_height_responsive . "px;"; ?>
        }

        .fl-node-<?php echo $id; ?> .Article-related_post_permalink {
            <?php if ($settings->post_permalink_font_size_responsive != '') echo "font-size: " . $settings->post_permalink_font_size_responsive . "px;"; ?>
            <?php if ($settings->post_permalink_line_height_responsive != '') echo "line-height: " . $settings->post_permalink_line_height_responsive . "px;"; ?>
        }
    }
<?php } ?>
