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
?>

<?php
    if($settings->fade_gradient === '1'){
        $color = $settings->fade_gradient_color;
    }else{
        $color = 'rgba(0,0,0,0)';
    }
?>

<div class='ContentSlider' data-contentslider<?php if ($settings->left_arrow_icon) { ?> data-contentslider-leftarrow="ContentSlider-navigation ContentSlider-navigation--prev <?php echo $settings->left_arrow_icon; ?>"<?php } ?><?php if ($settings->right_arrow_icon) { ?> data-contentslider-rightarrow="ContentSlider-navigation ContentSlider-navigation--next <?php echo $settings->right_arrow_icon; ?>"<?php } ?>>
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