<?php
// Start out by getting the posts
$posts          = $module->getPosts();
// Get the posts per page
$posts_per_page = $module->getPostsPerPage();

?>
<div class="ThreePostsGrid-container container-fluid">
    <div class="row ThreePostsGrid-equalHeight">
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

        
        // Get the post categories string
        $post_categories_string = $module->getPostCategoryString( $post_id );
        // Get the post content and excerpt
        $post_excerpt           = $module->getPostExcerpt( $current_post->post_content, $post_id );
        // The URL for the post
        $post_url               = get_permalink( $post_id );
        // By default, the post classes
        $post_classes = array( 'ThreePostsGrid-post col-xs-12 col-md-6 col-lg-4' );
    
        ?>
        <a class="<?php echo implode(' ', $post_classes ); ?>" href="<?php echo $post_url;?>" data-post-id="<?php echo $post_id;?>">    
            <div class="ThreePostsGrid-content">       
                <div class="ThreePostsGrid-category-container">
                    <span class="ThreePostsGrid-categories"><?php echo $post_categories_string;?></span>
                </div>
                <div class="ThreePostsGrid-title-container">
                    <h4 class="ThreePostsGrid-title"><?php echo $post_title;?></h4>
                </div>
                <div class="ThreePostsGrid-excerpt-container">
                    <p class="ThreePostsGrid-excerpt"><?php echo $post_excerpt?></p>
                </div>
                <div class="ThreePostsGrid-post-date-container">
                        <span class="ThreePostsGrid-date"><?php echo $post_date;?></span>
                        <span class="ThreePostsGrid-share">Share<i class="ThreePostsGrid-share-icon" style="background-image: url(<?php echo $settings->posts_share_icon_src; ?>);"></i></span>
                </div>
            </div>

        </a>
        <?php
    }
    ?>
    </div>
</div>

<ul class="ThreePostsGrid-pagination pagination">
    
</ul>
