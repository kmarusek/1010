<?php
wp_enqueue_script(
    "owl-carousel-2.0-js",
    get_stylesheet_directory_uri() . "/assets/vendor/owl-carousel/owl.carousel.min.js",
    array("jquery")
);

wp_enqueue_style(
    "owl-carouel-2.0-js",
    get_stylesheet_directory_uri() . "/assets/vendor/owl-carousel/assets/owl.carousel.min.css"
);

if($settings->fade_gradient === '1'){
    $color = $settings->fade_gradient_color;
}else{
    $color = 'rgba(0,0,0,0)';
}

$wrapper_attributes = [
    "class" => ["ContentSlider"],
    "data-contentslider" => "",
];

if ($settings->left_arrow_icon) {
    $wrapper_attributes["data-contentslider-leftarrow"][] = "ContentSlider-navigation";
    $wrapper_attributes["data-contentslider-leftarrow"][] = "ContentSlider-navigation--prev";
    $wrapper_attributes["data-contentslider-leftarrow"][] = $settings->left_arrow_icon;
}

if ($settings->right_arrow_icon) {
    $wrapper_attributes["data-contentslider-rightarrow"][] = "ContentSlider-navigation";
    $wrapper_attributes["data-contentslider-rightarrow"][] = "ContentSlider-navigation--next";
    $wrapper_attributes["data-contentslider-rightarrow"][] = $settings->right_arrow_icon;
}

if ($settings->dots_style !== "none") {
    $wrapper_attributes["data-contentslider-dots"][] = $settings->dots_style;
}

if ($settings->dots_style === "icon") {
    $wrapper_attributes["data-contentslider-dotsicon"][] = $settings->dots_icon;
}

if ($settings->play_loop === "loop") {
    $wrapper_attributes["data-contentslider-loop"][] = "";
}

if ($settings->play_auto === "autoplay") {
    if ($settings->play_delay_unit === "s") {
        $wrapper_attributes["data-contentslider-autoplay"] = $settings->play_delay * 1000;
    } else if ($settings->play_delay_unit === "ms") {
        $wrapper_attributes["data-contentslider-autoplay"] = $settings->play_delay * 1;
    }
    
    if ($settings->play_hoverpause === "hoverpause") {
        $wrapper_attributes["data-contentslider-hoverpause"] = "";
    }
}

if ($settings->slide_margin !== '' && $settings->slide_margin !== null) {
    $wrapper_attributes["data-contentslider-itemmargin"][] = $settings->slide_margin;
} else {
    $wrapper_attributes["data-contentslider-itemmargin"][] = '0';
}

if ($settings->items_per_view !== '' && $settings->items_per_view !== null) {
    $wrapper_attributes["data-contentslider-itemsperview"][] = $settings->items_per_view;
    $wrapper_attributes["data-contentslider-center"] = "false";
} else {
    $wrapper_attributes["data-contentslider-itemsperview"][] = '1';
    $wrapper_attributes["data-contentslider-center"] = "true";
}
if ($settings->items_per_view_medium !== '' && $settings->items_per_view_medium !== null) {
    $wrapper_attributes["data-contentslider-itemsperviewmedium"][] = $settings->items_per_view_medium;
    $wrapper_attributes["data-contentslider-centermedium"] = "false";
} else {
    $wrapper_attributes["data-contentslider-itemsperviewmedium"][] = '1';
    $wrapper_attributes["data-contentslider-centermedium"] = "true";
}
if ($settings->items_per_view_responsive !== '' && $settings->items_per_view_responsive !== null) {
    $wrapper_attributes["data-contentslider-itemsperviewresponsive"][] = $settings->items_per_view_responsive;
    $wrapper_attributes["data-contentslider-centerresponsive"] = "false";
} else {
    $wrapper_attributes["data-contentslider-itemsperviewresponsive"][] = '1';
    $wrapper_attributes["data-contentslider-centerresponsive"] = "true";
}

if ($settings->slide_stage_padding !== '' && $settings->slide_stage_padding !== null) {
    $wrapper_attributes["data-contentslider-stagepadding"][] = $settings->slide_stage_padding;
} else {
    $wrapper_attributes["data-contentslider-stagepadding"][] = '0';
}
if ($settings->slide_stage_padding_medium !== '' && $settings->slide_stage_padding_medium !== null) {
    $wrapper_attributes["data-contentslider-stagepaddingmedium"][] = $settings->slide_stage_padding_medium;
} else {
    $wrapper_attributes["data-contentslider-stagepaddingmedium"][] = '0';
}
if ($settings->slide_stage_padding_responsive !== '' && $settings->slide_stage_padding_responsive !== null) {
    $wrapper_attributes["data-contentslider-stagepaddingresponsive"][] = $settings->slide_stage_padding_responsive;
} else {
    $wrapper_attributes["data-contentslider-stagepaddingresponsive"][] = '0';
}

?>
<div<?php echo spacestation_render_attributes($wrapper_attributes); ?>>
    <div class='owl-carousel'>
        <?php for ($i = 0; $i<count($settings->slides); $i++) { ?>
            <?php $same_contents = $settings->slides[$i]->same_row == 1; ?>
            <article class="item">
                <div class="ContentSlider-contents<?php if (!$same_contents) { ?> ContentSlider-contents--desktop<?php } ?>">
                    <?php 
                        if (FLBuilderModel::is_builder_active()) {
                            echo "<div class='ContentSlider-placeholder'><span>Slider rows are not rendered while builder is active</span></div>";
                        } else if ($settings->slides[$i]->content_type !== 'standard' && $settings->slides[$i]->saved_content_row) {
                            FLBuilder::render_query([
                                'post_type' => 'fl-builder-template',
                                'p' => $settings->slides[$i]->saved_content_row
                            ]);
                        } else if ($settings->slides[$i]->content_type === 'standard'){ ?>
                            <?php if($settings->slides[$i]->slide_image_src) : ?>
                                <img src="<?php echo $settings->slides[$i]->slide_image_src; ?>" alt="<?php echo $settings->slides[$i]->slide_title; ?>" class="ContentSlider-contents_image">
                            <?php endif; ?>
                            <?php if($settings->slides[$i]->slide_title) : ?>
                                <<?php echo $settings->slide_title_tag; ?> class="ContentSlider-contents_title<?php if($settings->stretched_link !== 'normal'){ echo ' ContentSlider-contents_title-hover'; } ; ?>"><?php echo $settings->slides[$i]->slide_title; ?></<?php echo $settings->slide_title_tag; ?>>
                            <?php endif; ?>
                            <?php if($settings->slides[$i]->slide_title_two) : ?>
                                <<?php echo $settings->slide_title_two_tag; ?> class="ContentSlider-contents_title-two"><?php echo $settings->slides[$i]->slide_title_two; ?></<?php echo $settings->slide_title_two_tag; ?>>
                            <?php endif; ?>
                            <?php if($settings->slides[$i]->slide_description) : ?>
                                <<?php echo $settings->slide_description_tag; ?> class="ContentSlider-contents_description"><?php echo $settings->slides[$i]->slide_description; ?></<?php echo $settings->slide_description_tag; ?>>
                            <?php endif; ?>
                            <?php if($settings->slides[$i]->slide_cta) : ?>
                                <a href="<?php echo $settings->slides[$i]->slide_cta_link; ?>" target="<?php echo $settings->slides[$i]->slide_cta_link_target; ?>"<?php echo $settings->slides[$i]->slide_cta_link_nofollow ? ' rel="noopener noreferrer"' : ''; ?> class="ContentSlider-contents_cta<?php if($settings->stretched_link !== 'normal'){ echo ' stretched-link'; } ; ?>"><?php echo $settings->slides[$i]->slide_cta; ?></a>
                            <?php endif; ?>
                        <?php } else {
                            echo "Please select a content row.";
                        }
                    ?>
                </div>
                <?php if (!$same_contents) { ?>
                    <div class="ContentSlider-contents ContentSlider-contents--mobile">
                        <?php 
                            if (FLBuilderModel::is_builder_active()) {
                                echo "<div class='ContentSlider-placeholder'><span>Slider rows are not rendered while builder is active</span></div>";
                            } else     if ($settings->slides[$i]->mobile_saved_row) {
                                FLBuilder::render_query([
                                    'post_type' => 'fl-builder-template',
                                    'p' => $settings->slides[$i]->mobile_saved_row
                                ]);
                            } else {
                                echo "Please select a mobile content row.";
                            }
                        ?>
                    </div>
                <?php } ?>
            </article>
        <?php } ?>
    </div>
</div>