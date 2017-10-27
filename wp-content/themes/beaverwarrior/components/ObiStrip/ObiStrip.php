<?php
    $bg = get_sub_field('obistrip_bg');
?>

<footer class="ContentSection ContentSection--<?php the_sub_field('background_color'); ?> ObiStrip ObiStrip--footer <?php echo $class; ?> ObiStrip--<?php echo get_sub_field('background_color'); ?><?php if ($bg !== false) { ?> ContentSection--with_background<?php } ?>">
    <?php if ($bg !== false) { ?>
        <div class="ObiStrip-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ObiStrip-background ObiStrip--<?php the_sub_field('background_color'); ?>-background"></div>
    <?php } ?>
    <div class="ObiStrip-content">
        <?php echo the_sub_field('obistrip_body'); ?>
    </div>
</footer>