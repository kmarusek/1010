.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->left_arrow_bg_color); ?> !important;
    background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->left_arrow_bg_color_bg); ?> !important;
    transition: color 200ms ease-in-out, background-color 200ms ease-in-out;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev:hover,
.fl-node-<?php echo $id; ?> .ContentSlider-navigation--prev:focus {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->left_arrow_bg_color_hover); ?> !important;
    background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->left_arrow_bg_color_bg_hover); ?> !important;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->right_arrow_bg_color); ?> !important;
    background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->right_arrow_bg_color_bg); ?> !important;
    transition: color 200ms ease-in-out, background-color 200ms ease-in-out;
}

.fl-node-<?php echo $id; ?> .owl-item article.item {
    background: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_background_color); ?>;
}



<?php 
if($settings->arrows_offset) {
    FLBuilderCSS::responsive_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'arrows_offset',
    'selector'    => ".fl-node-$id .ContentSlider-navigation--prev",
    'prop'        => 'left',
    'unit' => 'px'
    ) );
    FLBuilderCSS::responsive_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'arrows_offset',
    'selector'    => ".fl-node-$id .ContentSlider-navigation--next",
    'prop'        => 'right',
    'unit' => 'px'
    ) );
}
?>

.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next:hover,
.fl-node-<?php echo $id; ?> .ContentSlider-navigation--next:focus {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->right_arrow_bg_color_hover); ?> !important;
    background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->right_arrow_bg_color_bg_hover); ?> !important;
}

.fl-node-<?php echo $id; ?> .ContentSlider-navigation,
.fl-node-<?php echo $id; ?> .ContentSlider-navigation:before,
.fl-node-<?php echo $id; ?> .owl-carousel .ContentSlider-navigation.owl-prev,
.fl-node-<?php echo $id; ?> .owl-carousel .ContentSlider-navigation.owl-next,
.fl-node-<?php echo $id; ?> .owl-carousel .ContentSlider-navigation.owl-prev:before,
.fl-node-<?php echo $id; ?> .owl-carousel .ContentSlider-navigation.owl-next:before {
    font-size: <?php echo $settings->arrows_size; ?><?php echo $settings->arrows_size_unit; ?>;
    width: <?php echo $settings->arrows_size; ?><?php echo $settings->arrows_size_unit; ?>;
    height: <?php echo $settings->arrows_size; ?><?php echo $settings->arrows_size_unit; ?>;
    box-sizing: content-box;
    transition: border 200ms ease-in-out;
}

<?php 
/**
 * Arrow Border styles
 */
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'arrow_border',
    'selector'    => ".fl-node-$id .owl-carousel .ContentSlider-navigation.owl-prev, .fl-node-$id .owl-carousel .ContentSlider-navigation.owl-next",
) );
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'arrow_border_hover',
    'selector'    => ".fl-node-$id .owl-carousel .ContentSlider-navigation.owl-prev:hover, .fl-node-$id .owl-carousel .ContentSlider-navigation.owl-next:hover",
) );
?>

<?php if ($settings->dots_style === "icon") {?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon {
        color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color); ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon.active {
        color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color_active); ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon:hover,
    .fl-node-<?php echo $id; ?> .ContentSlider-dot--style_icon:focus {
        color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color_hover); ?>
    }
<?php } else if ($settings->dots_style !== "none") { ?>
    .fl-node-<?php echo $id; ?> .ContentSlider-dot span {
        background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color); ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot.active span {
        background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color_active); ?>
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot:hover span,
    .fl-node-<?php echo $id; ?> .ContentSlider-dot:focus span {
        background-color: <?php echo FLBuilderColor::hex_or_rgb($settings->dots_color_hover); ?>
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
        margin-left: <?php echo $settings->dots_spacing; ?><?php echo $settings->dots_spacing_unit; // TODO this may alter existing slders ?>; 
    }

    .fl-node-<?php echo $id; ?> .ContentSlider-dot:first-child {
        padding-left: 0;
        margin-left: 0;
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
<?php }
/**
 * Dots border
 */
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'dots_border',
    'selector'    => ".fl-node-$id .ContentSlider-dot",
    'important' => true
) );
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'dots_border_hovers',
    'selector'    => ".fl-node-$id .ContentSlider-dot:hover",
    'important' => true
) )
?>
<?php if(!$settings->dots_border) : ?>
.fl-node-<?php echo $id; ?> .ContentSlider-dot {
    border: none !important;
}
<?php endif; ?>
.fl-node-<?php echo $id; ?> .ContentSlider-dots--style_undefined {
    display: none;
}
<?php 
/**
 * Image
 */
FLBuilderCSS::responsive_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'image_height',
    'selector'    => ".fl-node-$id .ContentSlider-contents_image",
    'prop'        => 'height',
    'unit' => 'px'
    ) );
?>
.fl-node-<?php echo $id; ?> .ContentSlider-contents_image {
    object-fit: <?php echo $settings->image_position; ?>;
}
<?php 
/**
 * Typography
 */
FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_typography', 
    'selector'    => ".fl-node-$id .ContentSlider-contents_title",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_two_typography', 
    'selector'    => ".fl-node-$id .ContentSlider-contents_title_two",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_description_typography', 
    'selector'    => ".fl-node-$id .ContentSlider-contents_description",
) );

FLBuilderCSS::typography_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_cta_typography', 
    'selector'    => ".fl-node-$id .ContentSlider-contents_cta",
) );

/**
 * Margins
 */
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_margin',
    'selector'    => ".fl-node-$id .ContentSlider-contents_title",
    'unit'        => 'px',
    'props'       => array(
        'margin-top'    => 'slide_title_margin_top',
        'margin-right'  => 'slide_title_margin_right',
        'margin-bottom' => 'slide_title_margin_bottom',
        'margin-left'   => 'slide_title_margin_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_two_margin',
    'selector'    => ".fl-node-$id .ContentSlider-contents_title-two",
    'unit'        => 'px',
    'props'       => array(
        'margin-top'    => 'slide_title_two_margin_top',
        'margin-right'  => 'slide_title_two_margin_right',
        'margin-bottom' => 'slide_title_two_margin_bottom',
        'margin-left'   => 'slide_title_two_margin_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_description_margin',
    'selector'    => ".fl-node-$id .ContentSlider-contents_description",
    'unit'        => 'px',
    'props'       => array(
        'margin-top'    => 'slide_description_margin_top',
        'margin-right'  => 'slide_description_margin_right',
        'margin-bottom' => 'slide_description_margin_bottom',
        'margin-left'   => 'slide_description_margin_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_cta_margin',
    'selector'    => ".fl-node-$id .ContentSlider-contents_cta",
    'unit'        => 'px',
    'props'       => array(
        'margin-top'    => 'slide_cta_margin_top',
        'margin-right'  => 'slide_cta_margin_right',
        'margin-bottom' => 'slide_cta_margin_bottom',
        'margin-left'   => 'slide_cta_margin_left',
    ),
) );

/**
 * Padding
 */
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_padding',
    'selector'    => ".fl-node-$id .ContentSlider-contents_title",
    'unit'        => 'px',
    'props'       => array(
        'padding-top'    => 'slide_title_padding_top',
        'padding-right'  => 'slide_title_padding_right',
        'padding-bottom' => 'slide_title_padding_bottom',
        'padding-left'   => 'slide_title_padding_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_title_two_padding',
    'selector'    => ".fl-node-$id .ContentSlider-contents_title-two",
    'unit'        => 'px',
    'props'       => array(
        'padding-top'    => 'slide_title_two_padding_top',
        'padding-right'  => 'slide_title_two_padding_right',
        'padding-bottom' => 'slide_title_two_padding_bottom',
        'padding-left'   => 'slide_title_two_padding_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_description_padding',
    'selector'    => ".fl-node-$id .ContentSlider-contents_description",
    'unit'        => 'px',
    'props'       => array(
        'padding-top'    => 'slide_description_padding_top',
        'padding-right'  => 'slide_description_padding_right',
        'padding-bottom' => 'slide_description_padding_bottom',
        'padding-left'   => 'slide_description_padding_left',
    ),
) );
FLBuilderCSS::dimension_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_cta_padding',
    'selector'    => ".fl-node-$id .ContentSlider-contents_cta",
    'unit'        => 'px',
    'props'       => array(
        'padding-top'    => 'slide_cta_padding_top',
        'padding-right'  => 'slide_cta_padding_right',
        'padding-bottom' => 'slide_cta_padding_bottom',
        'padding-left'   => 'slide_cta_padding_left',
    ),
) );

/**
 * Colors
 */
?>
.fl-node-<?php echo $id; ?> .ContentSlider-contents_title {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_title_color); ?>;
}
.fl-node-<?php echo $id; ?> .ContentSlider-contents_title-two {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_title_two_color); ?>;
}
.fl-node-<?php echo $id; ?> .ContentSlider-contents_description {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_description_color); ?>;
}
.fl-node-<?php echo $id; ?> .ContentSlider-contents_cta {
    transition: all 200ms ease-in-out;
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_cta_color); ?>;
    background: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_cta_color_background); ?>;
    text-decoration: none;
}
.fl-node-<?php echo $id; ?> .ContentSlider-contents_cta:hover {
    color: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_cta_color_hover); ?>;
    background: <?php echo FLBuilderColor::hex_or_rgb($settings->slide_cta_color_background_hover); ?>;
}

.fl-node-<?php echo $id; ?> .owl-nav * {
    overflow: visible;
}
<?php if($settings->stretched_link === 'stretched') : ?>
.fl-node-<?php echo $id; ?> .stretched-link:after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    pointer-events: auto;
    content: "";
    background-color: rgba(0,0,0,0);
}
<?php endif; ?>
<?php 
/**
 * CTA styles
 */
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_cta_border',
    'selector'    => ".fl-node-$id .ContentSlider-contents_cta",
) );
FLBuilderCSS::border_field_rule( array(
    'settings'    => $settings,
    'setting_name'    => 'slide_cta_border_hover',
    'selector'    => ".fl-node-$id .ContentSlider-contents_cta:hover",
) );
?>
.fl-node-<?php echo $id; ?> .ContentSlider-contents_cta {
    display: <?php echo ($settings->slide_cta_width === 'full') ? 'block' : 'inline-block';?>
}