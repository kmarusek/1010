<?php 
    //Yes this is wrong, I don't care.
    //There should be a valid way to enqueue scripts in templates.
    wp_enqueue_script("google_maps_v3");
    
    $options = get_sub_field('gmap_options');
?>

<div class="GoogleMap" data-googlemap<?php foreach ($options as $k => $v) {
        echo " " . $v;
    } ?>>
    <?php while (have_rows('gmap_locations')) {
        the_row('gmap_locations'); ?>
        <div data-googlemap-marker data-googlemap-lat="<?php the_sub_field('gmap_loc_latitude'); ?>" data-googlemap-lng="<?php the_sub_field('gmap_loc_longitude'); ?>">
            <?php the_sub_field('gmap_loc_body'); ?>
        </div>
    <?php } ?>
</div>