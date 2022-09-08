<?php
if ( $settings->home_page_check === 'not-homepage') : ?>

<section id="TenTenHero-<?php echo esc_attr($id); ?>"  class="TenTenHero-wrap not-homepage">

        <div class="TenTenHero-content-side TenTenHero-animation">
            <div class="TenTenHero-content-wrap">
                <?php if ($settings->subpage_subtitle): ?>
                <h4 class="TenTenHero-subtitle"><?php echo $settings->subpage_subtitle ?></h4>
                <?php endif;?>
                <h3 class="TenTenHero-title-selector"><?php echo $settings->content_title ?></h3>
                <h6><?php echo $settings->content ?></h6>
                <?php if ( $settings->button_url ): ?>
                    <div class="TenTenHero-buttonwrap">
                        <a class="TenTenHero-button" href="<?php echo $settings->button_url ?>" target="<?php echo $settings->button_url_target ?>" rel="<?php echo $settings->button_url_nofollow ?>">
                            <span><?php echo $settings->button_text ?></span><i class='<?php echo $settings->button_icon;?> TenTenHero-icon'></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="TenTenHero-image-side right">
            <div class="TenTenHero-background-img" style="background-image: url(<?php echo $settings->background_image_src ?>)">
                <div class="TenTenHero-lens TenTenHero-animation" style="background-image: url(<?php echo $settings->lens_image_src ?>)"></div>
                <div class="TenTenHero-mask TenTenHero-animation" style="-webkit-mask-image: url(<?php echo $settings->mask_image_src ?>);">
                    <div class="TenTenImage-wrap">
                <?php foreach ($settings->heros as $hero ): ?>
                    <img class="TenTenHero-slide" src="<?php echo $hero->hero_image_src; ?>" alt="">
                <?php endforeach; ?>
                    </div>
                </div>    
            </div>
        </div>

</section>

<?php 
elseif ( $settings->home_page_check === 'is-homepage' ) :;?>

<section id="TenTenHero-<?php echo esc_attr($id); ?>"  class="TenTenHero-wrap is-homepage">

        <div class="TenTenHero-content-side TenTenHero-animation">
            <div class="TenTenHero-content-wrap">
                <h2 class="TenTenHero-homepage-title-selector"><?php echo $settings->content_title ?></h2>
                <h4><?php echo $settings->content ?></h4>
                <?php if ( $settings->button_url ): ?>
                    <div class="TenTenHero-buttonwrap">
                        <a class="TenTenHero-button" href="<?php echo $settings->button_url ?>" target="<?php echo $settings->button_url_target ?>" rel="<?php echo $settings->button_url_nofollow ?>">
                            <span><?php echo $settings->button_text ?></span><i class='<?php echo $settings->button_icon;?> TenTenHero-icon'></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="TenTenHero-image-side right">
            <div class="TenTenHero-background-img" style="background-image: url(<?php echo $settings->background_image_src ?>)">
                <div class="TenTenHero-lens TenTenHero-animation" style="background-image: url(<?php echo $settings->lens_image_src ?>)"></div>
                <div class="TenTenHero-mask TenTenHero-animation" style="-webkit-mask-image: url(<?php echo $settings->mask_image_src ?>);">
                    <div class="TenTenImage-wrap">
                <?php foreach ($settings->heros as $hero ): ?>
                    <img class="TenTenHero-slide" src="<?php echo $hero->hero_image_src; ?>" alt="">
                <?php endforeach; ?>
                    </div>
                </div>    
            </div>
        </div>

    </section>

<?php
endif;?>
   
   
   