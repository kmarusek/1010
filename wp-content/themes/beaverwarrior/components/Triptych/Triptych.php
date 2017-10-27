<?php

$column_class = get_sub_field('triptych_column_count');
$bg = get_sub_field('triptych_bg');
$bg_color = get_sub_field('triptych_bg_color');

//This is mostly guesswork tbh
$sizes = "100vw";
if ($column_class === "3up") {
    $sizes = "(min-width: 768px) 33vw, 100vw";
} else if ($column_class === "4up") {
    $sizes = "(min-width: 768px) 25vw, 100vw";
}

if (have_rows('triptych_items')) { ?>
    <section class="ContentSection ContentSection--<?php echo $bg_color; ?>">
        <?php if ($bg !== false) { ?>
            <div class="ContentSection-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
        <?php } else { ?>
            <div class="ContentSection-background ContentSection--<?php echo $bg_color; ?>-background"></div>
        <?php } ?>
        <div class="Container Triptych Triptych--<?php echo $column_class; ?>">
            <div class="Triptych-wrapper Triptych--<?php echo $column_class; ?>-wrapper">
                <?php while (have_rows('triptych_items')) {
                    the_row();

                    $image = get_sub_field('triptych_image'); ?>
                    <section class="Triptych-item">
                        <?php if($image) { ?>
                            <img src="<?php echo $image["url"]; ?>"
                                alt="<?php echo $image["alt"]; ?>"
                                class="Triptych-image">
                        <?php } ?>
                        <?php if (get_sub_field('triptych_title') !== NULL && get_sub_field('triptych_title') !== "") { ?>
                            <h4 class="Triptych-title"><?php the_sub_field('triptych_title'); ?></h4>
                        <?php } ?>
                        <div class="Triptych-text"><?php the_sub_field('triptych_body'); ?></div>
                    </section>
                <?php } ?>
            </div>
        </div>
    </section>
<?php }