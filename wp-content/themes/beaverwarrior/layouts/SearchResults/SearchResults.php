<main class="SearchResults">
    <?php FLTheme::sidebar('left'); ?>
    <div class="SearchResults-content">
        <?php if ( have_posts() ) : ?>
            <?php while (have_posts()) {
                the_post(); ?>
                <a class="SearchResults-item" href="<?php echo get_permalink(); ?>">
                    <?php the_post_thumbnail('post_thumbnail', array('class' => "SearchResults-item_image")); ?>
                    <h2 class="SearchResults-item_title"><?php the_title(); ?></h2>
                </a>
            <?php }

            // Previous/next page navigation.
            the_posts_pagination( array(
                'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
                'next_text'          => __( 'Next page', 'twentyfifteen' ),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
            ) );
        endif;
        ?>
    </div>
    <?php FLTheme::sidebar('right'); ?>
</main>
