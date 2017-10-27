<?php $bg = get_sub_field("content_bg"); ?>

<section class="ContentSection ContentSection--<?php the_sub_field('content_bg_color'); ?> ContentSection--body<?php if ($bg !== false) { ?> ContentSection--with_background<?php } ?>">
    <?php if ($bg !== false) { ?>
        <div class="ContentSection-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('content_bg_color'); ?>-background"></div>
    <?php } ?>
    <div class="Container">
        <div class="ContentSection-text ContentSection--body-text">
            <?php the_sub_field('content_text'); ?>
        </div>
    </div>
</section>