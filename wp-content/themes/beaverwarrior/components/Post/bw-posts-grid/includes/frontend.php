<?php
wp_enqueue_script( 
    "pagination-js",
    get_stylesheet_directory_uri() . "/assets/vendor/pagination/pagination.min.js"
);

wp_enqueue_script( 
    "smooth-scroll",
    get_stylesheet_directory_uri() . "/assets/vendor/smooth-scroll/jquery.smooth-scroll.min.js"
);
// Start out by getting the posts
$posts          = $module->getPosts();
// Get the posts per page
$posts_per_page = $module->getPostsPerPage();
?>
<ul class="posts-container">
    <?php
    // Loop through all of the posts
    for ( $i=0; $i<count($posts); $i++ ){
        $current_post           = $posts[$i];
        // Get the post ID
        $post_id                = $current_post->ID;
        // Get the post title
        $post_title             = $current_post->post_title;
        // Get the post categories string
        $post_categories_string = $module->getPostCategoryString( $post_id );
        // Get the post content and excerpt
        $post_excerpt           = $module->getPostExcerpt( $current_post->post_content, $post_id );
        // The URL for the post
        $post_url               = get_permalink( $post_id );
        // By default, the post classes
        $post_classes = array( 'post' );
        // If the index is less than the posts per page, then it's active by default
        if ( $i < $posts_per_page || !$module->paginationIsEnabled() ){
            array_push( $post_classes, 'post-active' );
        }
        ?>
        <li class="<?php echo implode(' ', $post_classes ); ?>" data-post-id="<?php echo $post_id;?>">
            <a href="<?php echo $post_url;?>">
                <div class="featured-image-container">
                    <?php echo get_the_post_thumbnail( $post_id, 'ingredients-thumbnail', array( 'width' => '100%', 'height' => 'auto' ) ); ?>
                </div>
                <div class="post-category-container">
                    <span class="post-categories"><?php echo $post_categories_string;?></span>
                </div>
                <div class="post-title-container">
                    <h4 class="post-title"><?php echo $post_title;?></h4>
                </div>
                <div class="post-excerpt-container">
                    <p class="post-excerpt"><?php echo $post_excerpt?></p>
                </div>
                <div class="read-more-container">
                    <span class="read-more">Read More</span>
                </div>
            </a>
        </li>
        <?php
    }
    ?>
</ul>

<div class="posts-pagination-container"></div>