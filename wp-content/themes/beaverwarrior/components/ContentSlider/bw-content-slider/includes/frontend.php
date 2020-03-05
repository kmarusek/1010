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
    $wrapper_attributes["data-contentslider-dotsicon"][] = $settings->dots_icon;
}

?>
<div<?php echo spacestation_render_attributes($wrapper_attributes); ?>>
    <div class='owl-carousel'>
        <?php for ($i = 0; $i<count($settings->slides); $i++) { ?>
            <?php $same_contents = $settings->slides[$i]->same_row == 1; ?>
            <article class="item">
                <div class="ContentSlider-contents<?php if (!$same_contents) { ?> ContentSlider-contents--desktop<?php } ?>">
                    <?php 
                        if ($settings->slides[$i]->saved_content_row) {
                            FLBuilder::render_query([
                                'post_type' => 'fl-builder-template',
                                'p' => $settings->slides[$i]->saved_content_row
                            ]);
                        } else {
                            echo "Please select a content row.";
                        }
                    ?>
                </div>
                <?php if (!$same_contents) { ?>
                    <div class="ContentSlider-contents ContentSlider-contents--mobile">
                        <?php 
                            if ($settings->slides[$i]->mobile_saved_row) {
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