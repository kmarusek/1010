<?php
// Start out by getting the posts
$posts          = $module->getPosts();

$featured_events_wrapper_classes = [
  "class" => ["TenTenFeaturedEvent"],
  "id" => 'TenTenFeaturedEvent-'.$id,
];
?>



<section <?php echo spacestation_render_attributes($featured_events_wrapper_classes); ?>>
  
  <ul class="TenTenFeaturedEvent-list list">
    <?php
    // Loop through all of the posts
    for ( $i=0; $i<count($posts); $i++ ){
        $current_post           = $posts[$i];
        // Get the post ID
        $post_id                = $current_post->ID;
        // Get the post title
        $post_title             = $current_post->post_title;
        // Get the post date
        $post_date              = date('M j Y', strtotime($current_post->post_date));
        // Get the post type
        $post_type              = $current_post->post_type;
        // Get the post categories string
        $post_categories_string = $module->getPostCategoryString( $post_id );
        // Get the post content and excerpt
        $post_excerpt           = $module->getPostExcerpt( $current_post->post_content, $post_id );
        // The URL for the post
        $post_url               = get_permalink( $post_id );
        // Post Category Class 
        $categoriesClass        = $module->getPostCategoryString( $post_id )
        
     
?>
      <li class="TenTenFeaturedEvent-post" data-category="<?php echo $categoriesClass = str_replace(' ','-', $categoriesClass);?>" data-post-id="<?php echo $post_id;?>">
          <div class="TenTenFeaturedEvent-post_text">
              <div class="post_text-cat_date">
                <div class="post_featured">Featured Event</div>
                <div class="post_text-date"><?php  the_field('date', $post_id);?></div>
              </div>
              <div class="post_text-title">
                <h4><?php echo $post_title;?></h4>
              </div>
              <div class="post_text-excerpt">
                <p><?php echo $post_excerpt?></p>
              </div>
              <div class="post_text-link">
                <a class="TenTenFeaturedEvent-link_button" href="<?php echo $post_url?>">
                  <p>Learn More</p>
                </a>
              </div>
          </div>
          <div class="TenTenFeaturedEvent-post_image">
            <?php echo get_the_post_thumbnail( $post_id, 'ingredients-thumbnail', array( 'width' => '100%', 'height' => 'auto' ) ); ?>
          </div>
      </li>

<?php
}
?>
    </ul>
</section>