<?php
$s=get_search_query();
$exclude_ids = array(3335, 1183, 882, 887, 2786, 2793, 2815, 2809, 2798, 1525, 1848, 2666, 2675, 1436, 918, 1860);
$args = array(
    's' =>$s,
    'posts_per_page' => 16,
    'post__not_in' => $exclude_ids,
    'post_type' => array( 'post', 'page', 'content_library', 'event', 'career' )
);
    // The Query
$the_query = new WP_Query( $args );

// var_dump($the_query);

if ( $the_query->have_posts() ) {
    ?>
    <div class="SearchResults-container container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php     _e("<h2 class='SearchResults-search-title'>Search Results for: <strong>\"".get_query_var('s')."\"</strong></h2>"); ?>
            </div>
        </div>
    <div class="row SearchResults-equalHeight">
        <?php
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        ?>

        <div class="SearchResults-post col-xs-6 col-md-6 col-lg-3"">
            <div class="SearchResults-content">

                <div class="SearchResults-title-container">
                    <h4 class="SearchResults-title"><?php the_title();?></h4>
                </div>
                <div class="SearchResults-excerpt-container">
                    <?php $excerpt = get_the_excerpt();?>
                    <p class="SearchResults-excerpt"><?php if(!empty($excerpt)){ echo substr($excerpt, 0, 250). '..';};?></p>
                </div>
                <div class="SearchResults-btn-container">
                    <a href="<?php the_permalink();?>"><?php echo $settings->btn_title?><i class="<?php echo $settings->btn_icon; ?>"></i></a>
                </div>

            </div>

        </div>
        <?php
    }?>
        </div>
    </div>
<?php }
else{
    ?>
    <h2 style='font-weight:bold;color:#000'>Nothing Found</h2>
    <div class="alert alert-warning">
        <p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
    </div>
<?php } ?>