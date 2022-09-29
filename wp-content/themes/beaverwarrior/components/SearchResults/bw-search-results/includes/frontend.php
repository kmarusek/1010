<?php
$q = isset($_GET['s']) ? $_GET['s'] : null;
if (isset($q)) {
    $q = strtolower($q);
}else{
    $q = '';
}
// Start out by getting the posts
$posts          = $module->getPosts();
foreach($posts as $i => $post){
    $current_post_id = $post->ID;
    $current_post_title = strtolower($post->post_title);
    $current_post_content = strtolower($post->post_content);
    $current_post_excerpt = strtolower($module->getPostExcerpt( $post->post_content, $current_post_id ));
    if (strpos($current_post_title, $q) !== false || strpos($current_post_content, $q) !== false || strpos($current_post_excerpt, $q) !== false) {
            //do nothing
        }
    else{
        unset($posts[$i]);
    }
}
// Get the posts per page
$posts_per_page = $module->getPostsPerPage();


// var_dump($posts);

?>

<div class="SearchResults-container container-fluid">
    <div class="row SearchResults-equalHeight">
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
        $post_classes = array( 'SearchResults-post col-xs-12 col-md-6 col-lg-3' );

        if($post_type === 'news'){
        ?>

        <a class="<?php echo implode(' ', $post_classes ); ?>" href="<?php  the_field('article_link', $post_id);?>" data-post-id="<?php echo $post_id;?>">    
            <div class="SearchResults-content">       
                <div class="SearchResults-category-container">
                    <span class="SearchResults-categories"><?php echo the_field('article_author', $post_id);?></span>
                </div>
                <div class="SearchResults-title-container">
                    <h4 class="SearchResults-title"><?php  the_field('article_title', $post_id);?></h4>
                </div>
                <div class="SearchResults-excerpt-container">
                    <p class="SearchResults-excerpt"><?php  the_field('article_excerpt', $post_id)?>...</p>
                </div>
                <div class="SearchResults-post-date-container">
                        <span class="SearchResults-date"><?php  the_field('article_date', $post_id);?></span>
                        <!-- <span class="SearchResults-share">Share<i class="SearchResults-share-icon" style="background-image: url(<?php echo $settings->posts_share_icon_src; ?>);"></i></span> -->
                </div>
            </div>

        </a>

        <?php
        }
        elseif($post_type === 'post'){
        ?>
            <div class="<?php echo implode(' ', $post_classes ); ?>"  data-post-id="<?php echo $post_id;?>">
            <div class="SearchResults-content">       
                <!--<div class="SearchResults-category-container">
                    <span class="SearchResults-categories"><?php echo $post_categories_string;?></span>
                </div>-->
                <div class="SearchResults-title-container">
                    <h4 class="SearchResults-title"><?php echo $post_title;?></h4>
                </div>
                <div class="SearchResults-excerpt-container">
                    <p class="SearchResults-excerpt"><?php echo $post_excerpt?></p>
                </div>
                <div class="SearchResults-btn-container">
                    <a href="<?php echo $post_url;?>"><?php echo $settings->btn_title?><i class="<?php echo $settings->btn_icon; ?>"></i></a>
                </div>
                <!--<div class="SearchResults-post-date-container">
                        <span class="SearchResults-date"><?php echo $post_date;?></span>
                        <!-- <span class="SearchResults-share">Share<i class="SearchResults-share-icon" style="background-image: url(<?php echo $settings->posts_share_icon_src; ?>);"></i></span>
                </div>-->
            </div>

        </div>
<?php 
    }
}
    ?>
    </div>
</div>
<?php if(count($posts) > 0 ):?>
<!--<ul class="SearchResults-pagination pagination">
    
</ul>-->
<?php else: ?>
<h2 style="text-align: center;">Unfortunately, no search results where found.</h2>
<?php endif;?>