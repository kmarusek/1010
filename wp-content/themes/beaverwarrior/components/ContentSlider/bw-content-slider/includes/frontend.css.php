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
<?php } else if ($settings->dots_style === "line") { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_line span {
        width: <?php echo $settings->dots_width; ?><?php echo $settings->dots_width_unit; ?>;
        height: <?php echo $settings->dots_height; ?><?php echo $settings->dots_height_unit; ?>;
    }
<?php } ?>

<?php if ($settings->dots_orientation === 'top') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        top: <?php echo $settings->dots_edge_distance; ?><?php echo $settings->dots_edge_distance_unit; ?>;
    }
<?php } else if ($settings->dots_orientation === 'bottom') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        bottom: <?php echo $settings->dots_edge_distance; ?><?php echo $settings->dots_edge_distance_unit; ?>;
    }
<?php } else if ($settings->dots_orientation === 'left') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        left: <?php echo $settings->dots_edge_distance; ?><?php echo $settings->dots_edge_distance_unit; ?>;
    }
<?php } else if ($settings->dots_orientation === 'right') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        right: <?php echo $settings->dots_edge_distance; ?><?php echo $settings->dots_edge_distance_unit; ?>;
    }
<?php } ?>

<?php if ($settings->dots_orientation === 'top' || $settings->dots_orientation === 'bottom') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .fl-node-<?php echo $id; ?> .ContentSlider-dot {
        display: inline-block;
        padding-left: <?php echo $settings->dots_spacing; ?><?php echo $settings->dots_spacing_unit; ?>;
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot:first-child {
        padding-left: 0;
    }
<?php } else if ($settings->dots_orientation === 'left' || $settings->dots_orientation === 'right') { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dots {
        top: 50%;
        transform: translateY(-50%);
    }
    
    .fl-node-<?php echo $id; ?> .ContentSlider-dot {
        display: block;
        padding-top: <?php echo $settings->dots_spacing; ?><?php echo $settings->dots_spacing_unit; ?>;
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot:first-child {
        padding-top: 0;
    }
<?php } ?>