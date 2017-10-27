<?php 
    $image = get_sub_field('content_image');
    $image_align_class = 'ContentSection--split_50-valign_' . get_sub_field('content_image_alignment');
    $text_align_class = 'ContentSection--split_50-valign_' . get_sub_field('content_text_alignment');

    $caption = get_sub_field('content_caption');
    $has_caption = $caption !== NULL && $caption !== "";

    $order = get_sub_field('content_order');

    $bg = get_sub_field('content_bg');
?>
<section class="ContentSection ContentSection--<?php the_sub_field('content_bg_color'); ?> ContentSection--split_50<?php if ($bg !== false) { ?> ContentSection--with_background<?php } ?>">
    <?php if ($bg !== false) { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('content_bg_color'); ?>-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('content_bg_color'); ?>-background"></div>
    <?php } ?>
    <div class="Container ContentSection--split_50-row">
        <?php if ($order === "text_first") { ?>
            <div class="ContentSection-text ContentSection--split_50-text <?php echo $text_align_class; ?>">
                <?php the_sub_field('content_text'); ?>
            </div>
        <?php } ?>
        <figure class="ContentSection-figure ContentSection--split_50-figure <?php echo $image_align_class; ?>">
            <img src="<?php echo $image["url"]; ?>"
                 srcset="<?php echo skeletonwarrior_srcset($image); ?>"
                 alt="<?php echo $image["alt"]; ?>"
                 class="ContentSection-image ContentSection--split_50-image">
            <?php if ($has_caption) { ?>
                <figcaption class="ContentSection-caption ContentSection--split_50-caption">
                    <?php echo $caption; ?>
                </figcaption>
            <?php } ?>
        </figure>
        <?php if ($order === "image_first") { ?>
            <div class="ContentSection-text ContentSection--split_50-text <?php echo $text_align_class; ?>">
                <?php the_sub_field('content_text'); ?>
            </div>
        <?php } ?>
    </div>
</section>