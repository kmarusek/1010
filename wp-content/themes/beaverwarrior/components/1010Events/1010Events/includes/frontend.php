<?php
wp_enqueue_script( "Eventslist.js", "https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js");

$events_wrapper_classes = [
  "class" => ["TenTenEvents"],
  "id" => 'TenTenEvents-'.$id,
];
?>

<section <?php echo spacestation_render_attributes($events_wrapper_classes); ?>>
<?php

// Start out by getting the posts
$posts          = $module->getPosts();

// var_dump($posts);

//Gather all Catergories for CPT Filter
if( !function_exists('get_terms_by_post_type') ){

    function get_terms_by_post_type( $postType = 'post', $taxonomy = 'event_category'){

        /**
         * 
         * Get Terms by post type
         * @author Amit Biswas (https://templateartist.com/)
         * @link (https://templateartist.com/2020/05/18/get-categories-by-post-type-in-wordpress/)
         * 
         * 
         * @param postType default 'post'
         * @param taxonomy default 'category'
         * 
         * @return array of terms for a given $posttype and $taxonomy
         * 
         * 1. Get all posts by post type
         * 2. Loop through the posts array and retrieve the terms attached to those posts
         * 3. Store the new terms objects within `$post_terms`
         * 4. loop through `$post_terms` as it's a array of term objects.
         * 5. store the terms with our desired key, value pair inside `$post_terms_array`
         */

        //1. Get all posts by post type
        $get_all_posts = get_posts( array(
            'post_type'     => esc_attr( $postType ),
            'post_status'   => 'publish',
            'numberposts'   => -1
        ) );

        if( !empty( $get_all_posts ) ){

            //First Empty Array to store the terms
            $post_terms = array();
            
            //2. Loop through the posts array and retrieve the terms attached to those posts
            foreach( $get_all_posts as $all_posts ){

                /**
                 * 3. Store the new terms objects within `$post_terms`
                 */
                $post_terms[] = get_the_terms( $all_posts->ID, esc_attr( $taxonomy ) );

            }

            //Second Empty Array to store final term data in key, value pair
            $post_terms_array = array();

            /**
             * 4. loop through `$post_terms` as it's a array of term objects.
             */

            foreach($post_terms as $new_arr){
                foreach($new_arr as $arr){

                    /**
                     * 5. store the terms with our desired key, value pair inside `$post_terms_array`
                     */
                    $post_terms_array[] = array(
                        'name'      => $arr->name,
                        'term_id'   => $arr->term_id,
                        'slug'      => $arr->slug,
                        'url'       => get_term_link( $arr->term_id )
                    );
                }
            }

            //6. Make that array unique as duplicate entries can be there
            $terms = array_unique($post_terms_array, SORT_REGULAR);

            //7. Return the final array
            return $terms;

        }

    }

}

$postCats = get_terms_by_post_type($settings->post_type);
?>

<?php
//End Post Type Category retrieval 

// Start of Post Loop 
?>
<div class="TenTenEvents-post_container">

<?php
if( !empty( $postCats ) ){
?>
<div id="TenTenEvents-list">
      <div class="TenTenEvents-filter_wrap">
        <select class="TenTenEvents-category_menu" id="TenTenEvents-filter">
          <option value="all"  checked>Event Type</option>

<?php
//sort through all categories and push value into string to be called by loop and inserted into HTML.
foreach( $postCats as $cat ){
        $categoryName = $cat['name'];
?>
         <option value="<?php echo $categoryName = str_replace(' ','-',$categoryName);?>" ><?php echo esc_html($cat['name']);?></option>            
<?php   
} } 
?>  
      </select>
    </div>
    <ul class="TenTenEvents-list list">
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
      <li class="TenTenEvents-post" data-category="<?php echo $categoriesClass = str_replace(' ','-', $categoriesClass);?>" data-post-id="<?php echo $post_id;?>">
          <div class="TenTenEvents-post_text">
              <div class="post_text-cat_date">
                <div class="post_text-date"><?php  the_field('date', $post_id);?></div>
              </div>
              <div class="post_text-title">
                <h4><?php echo $post_title;?></h4>
              </div>
              <div class="post_text-excerpt">
                <p><?php echo $post_excerpt?></p>
              </div>
              <div class="post_text-link">
                <a class="TenTenEvents-link_button" href="<?php echo $post_url?>">
                  Learn More <i class="TenTenEvents-icon <?php echo $settings->event_anchor_icon; ?>"></i>
                </a>
              </div>
          </div>
          <div class="TenTenEvents-post_image">
            <?php echo get_the_post_thumbnail( $post_id, 'ingredients-thumbnail', array( 'width' => '100%', 'height' => 'auto' ) ); ?>
          </div>
      </li>

<?php
}//End of Post Loop
?>
        </ul>
    </div>
</div>
</section>