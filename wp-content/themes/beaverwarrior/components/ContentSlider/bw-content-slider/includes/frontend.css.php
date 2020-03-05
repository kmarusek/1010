.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev {
    color: #<?php echo $settings->left_arrow_bg_color; ?> !important;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev:hover,
.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev:focus {
    color: #<?php echo $settings->left_arrow_bg_color_hover; ?> !important;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next {
    color: #<?php echo $settings->right_arrow_bg_color; ?> !important;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next:hover,
.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next:focus {
    color: #<?php echo $settings->right_arrow_bg_color_hover; ?> !important;
}

<?php if ($settings->dots_style === "icon") {?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon {
        color: #<?php echo $settings->dots_color; ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon.active {
        color: #<?php echo $settings->dots_color_active; ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon:hover,
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon:focus {
        color: #<?php echo $settings->dots_color_hover; ?>
    }
<?php } else if ($settings->dots_style !== "none") { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot span {
        background-color: #<?php echo $settings->dots_color; ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot.active span {
        background-color: #<?php echo $settings->dots_color_active; ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot:hover span,
    .fl-node-<?php echo $id; ?> .ContentSlider-dot:focus span {
        background-color: #<?php echo $settings->dots_color_hover; ?>
    }
<?php } ?>

<?php if ($settings->dots_style === "icon") { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon {
        font-size: <?php echo $settings->dots_size; ?><?php echo $settings->dots_size_unit; ?>;
    }
<?php } else if ($settings->dots_style === "dots") { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_dots span {
        width: <?php echo $settings->dots_size; ?><?php echo $settings->dots_size_unit; ?>;
        height: <?php echo $settings->dots_size; ?><?php echo $settings->dots_size_unit; ?>;
    }
<?php } ?>