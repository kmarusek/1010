<?php
wp_enqueue_script( "list.js", "https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js");

?>
<?php
// Start out by getting the posts
$posts          = $module->getPosts();
// Get the posts per page
$posts_per_page = $module->getPostsPerPage();

// var_dump($posts);

//Gather all Catergories for CPT 
if( !function_exists('get_terms_by_post_type') ){

    function get_terms_by_post_type( $postType = 'post', $taxonomy = 'category'){

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

}//

$postCats = get_terms_by_post_type($settings->post_type);


if( !empty( $postCats ) ){
?>
<div id="ContentLibrary-list">
    <div class="ContentLibrary-menu-wrap">
        <div class="ContentLibrary-mobile_menu">
            <button class="hamburger hamburger--collapse" type="button">
                <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>

        <div class="ContentLibrary-filter-wrap">
            <div class="ContentLibrary-category-menu">
                <div class="ContentLibrary-radio-inline">
                    <input class="ContentLibrary-input filter-all" type="radio" value="all" name="category" id="category-all" checked/>
                    <label for="category-all">All</label>
                </div>
<?php
//sort through all categories and push value into string to be called by loop and inserted into HTML.
foreach( $postCats as $cat ){
        $categoryName = $cat['name'];
?>
                <div class="ContentLibrary-radio-inline">
                        <input class="ContentLibrary-input filter" type="radio" value="<?php echo $categoryName = str_replace(' ','-',$categoryName);?>" name="category" id="category-<?php echo $categoryName = str_replace(' ','-',$categoryName);?>" /> 
                        <label for="category-<?php echo $categoryName = str_replace(' ','-',$categoryName);?>"><?php echo esc_html($cat['name']);?></label>
                </div>
            
<?php   
} } 
?>  
                <div class="ContentLibrary-search">
					<input type="text" class="fuzzy-search" onclick="resetList();" placeholder="   " />
                    <i class="ContentLibrary-icon <?php echo $settings->search_icon; ?>"></i>
				</div>
            </div>
        </div>
    </div>
<?php
// End of Category Navigation

// Start of Feature Post and Post Loop 
?>
<div class="ContentLibrary-post-container">
    <ul class="list ContentLibrary-post-grid">


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
         <a class="" href="<?php echo $post_url?>" data-category="<?php echo $categoriesClass = str_replace(' ','-', $categoriesClass);?>" data-post-id="<?php echo $post_id;?>">
            <li class="ContentLibrary-post <?php echo $categoriesClass = str_replace(' ','-', $categoriesClass);?>" > 
                    <div class="ContentLibrary-content-container">
                        <p class="ContentLibrary-categories categories"><?php echo $post_categories_string;?></p>
                        <h5 class="ContentLibrary-title title"><?php  echo $post_title;?></h5>
                    </div>
                    <div class="ContentLibrary-post-link-container">
                            <i class="ContentLibrary-icon <?php echo $settings->posts_anchor_icon; ?>"></i>
                    </div>
            </li>
        </a>
<?php
}
?>
        </ul>
        <div class="ContentLibrary-pagination">
            <ul class="pagination">
            </ul>
        </div>
    </div>
</div>