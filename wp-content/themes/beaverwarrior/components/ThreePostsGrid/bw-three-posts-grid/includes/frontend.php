<?php
// Start out by getting the posts
$posts          = $module->getPosts();
// Get the posts per page
$posts_per_page = $module->getPostsPerPage();


// var_dump($posts);
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
    // Get the post type
    $post_type              = $current_post->post_type;
    // Get the post categories string
    $post_categories_string = $module->getPostCategoryString( $post_id );
    // Get the post content and excerpt
    $post_excerpt           = $module->getPostExcerpt( $current_post->post_content, $post_id );
    // The URL for the post
    $post_url               = get_permalink( $post_id );
    // By default, the post classes
    $post_classes = array( 'ThreePostsGrid-post col-xs-12 col-md-6 col-lg-4' );
   
if($post_type === 'news'){
?>

           
            <div class="<?php echo implode(' ', $post_classes ); ?>" > 
                <div class="ThreePostsGrid-content">
                    <a class="ThreePostsGrid-link" href="<?php  the_field('article_link', $post_id);?>" data-post-id="<?php echo $post_id;?>">       
                        <div class="ThreePostsGrid-category-container">
                            <span class="ThreePostsGrid-categories"><?php echo the_field('article_author', $post_id);?></span>
                        </div>
                        <div class="ThreePostsGrid-title-container">
                            <h4 class="ThreePostsGrid-title"><?php  the_field('article_title', $post_id);?></h4>
                        </div>
                        <div class="ThreePostsGrid-excerpt-container">
                            <p class="ThreePostsGrid-excerpt"><?php  the_field('article_excerpt', $post_id)?>...</p>
                        </div>
                    </a>
                    <div class="ThreePostsGrid-share-date-wrap">
                        <div class="ThreePostsGrid-date"><?php  the_field('article_date', $post_id);?></div>
                        <div class="ThreePostsGrid-share-btn_wrap">
                            <span class="ThreePostsGrid-share_label">Share<i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_icon; ?>"></i></span>
                            <div class="ThreePostsGrid-share-container">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php  the_field('article_link', $post_id);?>"><i class="ThreePostsGrid-share-icon facebook<?php echo $settings->posts_share_facebook_icon; ?>" ></i></a>
                                <a href="https://twitter.com/share?url=<?php  the_field('article_link', $post_id);?>&text=1010data%20Blogvia=1010data"><i class="ThreePostsGrid-share-icon linkedin <?php echo $settings->posts_share_linkedin_icon; ?>" ></i></a>
                                <a href="https://twitter.com/share?url=<?php  the_field('article_link', $post_id);?>&text=<TEXT>via=<USERNAME>"><i class="ThreePostsGrid-share-icon twitter <?php echo $settings->posts_share_twitter_icon; ?>" ></i></a>
                                <a href="mailto:?subject=Checkout%20out%20this%20story&body=<?php  the_field('article_link', $post_id);?>"><i class="ThreePostsGrid-share-icon email <?php echo $settings->posts_share_email_icon; ?>" ></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

       

<?php
}
elseif($post_type === 'post'){
?>
            
        <div class="<?php echo implode(' ', $post_classes ); ?> "> 
            <div class="ThreePostsGrid-content">
                <a class="ThreePostsGrid-link" href="<?php echo $post_url;?>" data-post-id="<?php echo $post_id;?>">      
                    <div class="ThreePostsGrid-category-container">
                        <span class="ThreePostsGrid-categories"><?php echo $post_categories_string;?></span>
                    </div>
                    <div class="ThreePostsGrid-title-container">
                        <h4 class="ThreePostsGrid-title"><?php echo $post_title;?></h4>
                    </div>
                    <div class="ThreePostsGrid-excerpt-container">
                        <p class="ThreePostsGrid-excerpt"><?php echo $post_excerpt?></p>
                    </div>
                </a>
                <div class="ThreePostsGrid-share-date-wrap">
                    <div class="ThreePostsGrid-date"><?php echo $post_date;?></div>
                    <div class="ThreePostsGrid-share-btn_wrap">
                        <span class="ThreePostsGrid-share_label">Share<i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_icon; ?>"></i></span>
                        <div class="ThreePostsGrid-share-container">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url;?>"><i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_facebook_icon; ?>" ></i></a>
                            <a href="https://twitter.com/share?url=<?php echo $post_url;?>&text=1010data%20Blogvia=1010data"><i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_linkedin_icon; ?>" ></i></a>
                            <a href="https://twitter.com/share?url=<?php echo $post_url;?>&text=<TEXT>via=<USERNAME>"><i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_twitter_icon; ?>" ></i></a>
                            <a href="mailto:?subject=Checkout%20out%20this%20story&body=<?php echo $post_url;?>"><i class="ThreePostsGrid-share-icon <?php echo $settings->posts_share_email_icon; ?>" ></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      
<?php 
    }
}
?>
    </div>
</div>

    <ul class="ThreePostsGrid-pagination pagination"></ul>
