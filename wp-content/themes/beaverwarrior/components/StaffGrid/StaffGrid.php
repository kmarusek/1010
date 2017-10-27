<?php
    $id = uniqid();
    $bg = get_sub_field('staffgrid_bg');
?>

<section class="ContentSection ContentSection--<?php the_sub_field('staffgrid_bg_color'); ?>">
    <?php if ($bg !== false) { ?>
        <div class="ContentSection-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('staffgrid_bg_color'); ?>-background"></div>
    <?php } ?>
    <div class="StaffGrid Container">
        <?php

        $index = 0;

        while (have_rows('staffgrid_items')) {
            the_row('staffgrid_items');

            $image = get_sub_field('staffgrid_image'); ?>
            <button type="button" class="StaffGrid-item" data-target="#<?php echo $id; ?>" data-toggle="offcanvas" data-toggle-options="nohover" data-staffgrid-slider-index="<?php echo $index; ?>">
                <img src="<?php echo $image["url"]; ?>"
                     srcset="<?php echo skeletonwarrior_srcset($image); ?>"
                     alt="<?php echo $image["alt"]; ?>"
                     class="StaffGrid-image">
                <h3 class="StaffGrid-name"><?php the_sub_field('staffgrid_name'); ?></h3>
                <p class="StaffGrid-job_title"><?php the_sub_field('staffgrid_job_title'); ?></p>
            </button>
        <?php
            $index += 1;
        } ?>
    </div>
</section>

<section class="StaffGrid-modal Modal is-Offcanvas--closed" id="<?php echo $id; ?>" data-staffgrid-modal>
    <button type="button" class="Modal-close" data-dismiss="offcanvas">X</button>
    <div class="StaffGrid-modal_content Modal-content">
        <div class="StaffGrid-modal_slider_wrapper">
            <div data-staffgrid-slider>
                <?php while (have_rows('staffgrid_items')) {
                    the_row('staffgrid_items');
                    
                    $image = get_sub_field('staffgrid_image'); ?>
                    <div>
                        <div class="StaffGrid-modal_image" style="background-image: url('<?php echo $image["url"]; ?>')"></div>
                        <div class="StaffGrid-modal_info">
                            <h3 class="StaffGrid-name"><?php the_sub_field('staffgrid_name'); ?></h3>
                            <p class="StaffGrid-job_title"><?php the_sub_field('staffgrid_job_title'); ?></p>
                            <?php the_sub_field('staffgrid_body'); ?>

                            <button type="button" class="StaffGrid-next" data-staffgrid-next><span>Next</span></button>
                            <button type="button" class="StaffGrid-prev" data-staffgrid-prev><span>Prev</span></button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<div class="Offcanvas-backdrop is-Offcanvas--backdrop_inactive" data-offcanvas-backdrop="1" data-offcanvas-backdrop-for="<?php echo $id; ?>"></div>