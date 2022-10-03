<?php
/**
 * Created by PhpStorm.
 * User: stefanmitrevski
 * Date: 10.3.22
 * Time: 15:03
 */
$args = array(
        'post_type' => 'content_library',
        'posts_per_page' => -1,
        'meta_key' => 'is_featured_resource',
        'meta_value' => '1'
    );

$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
?>
<div class="FeaturedResources">
    <h4 class="FeaturedResources-subheading"><?php echo $settings->fr_subheading?></h4>
    <h2 class="FeaturedResources-heading"><?php echo $settings->fr_heading?></h2>
    <div class="FeaturedResources-container" id="FeaturedResources-container-<?php echo esc_attr($id)?>">
        <?php
        while ( $the_query->have_posts() ) {
        $the_query->the_post();
        ?>
            <div class="FeaturedResources-item" style="background-image: url('/wp-content/uploads/2022/10/shere_matrix.png')">
                <a class="FeaturedResources-item_link" href="<?php the_permalink();?>">
                    <div class="FeaturedResources-content">
                        <h4 class="FeaturedResources-category"><?php $cats =  get_the_category(get_the_ID()); foreach ( $cats as $key => $value) { echo $value->category_nicename . " "; }  ?></h4>
                        <h3 class="FeaturedResources-title"><?php the_title();?></h3>
                    </div>
                </a>
                <div class="FeaturedResources-overlay" style="background-color: rgba(<?php the_field('background_color'); ?>)"></div>
            </div>
            <?php
        }?>
    </div>
    <div class="FeaturedResources-learn_more">
        <a href="<?php echo $settings->fr_btn_link?>" class="FeaturedResources-link">
            <span class="fl-button-text"><?php echo $settings->fr_btn_text?></span>
        </a>
    </div>
</div>
<?php }
else{
    ?>
    <h2 style='font-weight:bold;color:#000'>No Featured Resources selected</h2>
<?php } ?>