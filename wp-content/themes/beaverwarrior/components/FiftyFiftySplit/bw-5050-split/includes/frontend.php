<?php

    if ( $settings->home_page_check === 'option-1') :  $wrapper_classes = "FiftyFiftySplit-wrap not-home-page";?>

<a id="FiftyFiftySplit-accordion-<?php echo esc_attr($id);?>" class="FiftyFiftySplit-accordion">
    <p><?php echo $settings->content_title ?></p>
    <p id="FiftyFiftySplit-toggle-icon-<?php echo esc_attr($id);?>">+</p>
</a>


<?php 
    elseif ( $settings->home_page_check === 'option-2' ) :

    $wrapper_classes = "FiftyFiftySplit-wrap FiftyFiftySplit-home-page";

endif;?>

<?php if($settings->image_side == 'option-1') : ?>
    <section id="FiftyFiftySplit-<?php echo esc_attr($id); ?>"  class="<?php echo $wrapper_classes;?> ">
  

        <div class="FiftyFiftySplit-image-side left">
            <div class="FiftyFiftySplit-background-img" style="background-image: url(<?php echo $settings->background_image_src ?>)">
                <div class="FiftyFiftySplit-lens FiftyFiftySplit-animation" style="background-image: url(<?php echo $settings->lens_image_src ?>)"></div>
                <img class="FiftyFiftySplit-primary-img FiftyFiftySplit-animation" src="<?php echo $settings->primary_image_src ?>" style="-webkit-mask-image: url(<?php echo $settings->mask_image_src;?>); background-image: url(<?php echo $settings->background_image_src;?>);" alt="">
            </div>
        </div>
        <div class="FiftyFiftySplit-content-side FiftyFiftySplit-animation right">
            <div class="FiftyFiftySplit-content-wrap ">
                <h2><?php echo $settings->content_title ?></h2>
                <p><?php echo $settings->content ?></p>
                <?php if ( $settings->button_url ): ?>
                    <div class="FiftyFiftySplit-buttonwrap">
                        <a class="FiftyFiftySplit-button" href="<?php echo $settings->button_url ?>" target="<?php echo $settings->button_url_target ?>" rel="<?php echo $settings->button_url_nofollow ?>">
                            <p><?php echo $settings->button_text ?></p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </section>

<?php elseif ($settings->image_side == 'option-2') : ?>

    <section id="FiftyFiftySplit-<?php echo esc_attr($id); ?>"  class="<?php echo $wrapper_classes;?> FiftyFiftySplit-reverse">

        <div class="FiftyFiftySplit-content-side FiftyFiftySplit-animation left">
            <div class="FiftyFiftySplit-content-wrap ">
                <h2><?php echo $settings->content_title ?></h2>
                <p><?php echo $settings->content ?></p>
                <?php if ( $settings->button_url ): ?>
                    <div class="FiftyFiftySplit-buttonwrap">
                        <a class="FiftyFiftySplit-button" href="<?php echo $settings->button_url ?>" target="<?php echo $settings->button_url_target ?>" rel="<?php echo $settings->button_url_nofollow ?>">
                            <p><?php echo $settings->button_text ?></p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="FiftyFiftySplit-image-side right">
            <div class="FiftyFiftySplit-background-img" style="background-image: url(<?php echo $settings->background_image_src ?>)">
                <div class="FiftyFiftySplit-lens FiftyFiftySplit-animation" style="background-image: url(<?php echo $settings->lens_image_src ?>)"></div>
                <img class="FiftyFiftySplit-primary-img FiftyFiftySplit-animation" src="<?php echo $settings->primary_image_src ?>" style="-webkit-mask-image: url(<?php echo $settings->mask_image_src ?>); background-image: url(<?php echo $settings->background_image_src;?>);" alt="">
            </div>
        </div>

    </section>
<?php else : ?>
    <h4>Something has gone wrong, please contact your developer.</h4>
<?php endif; ?>
