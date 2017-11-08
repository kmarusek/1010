<main class="SearchResults">
    <div class="SearchResults-content">
        <header class="SearchResults-header">
            <h1 class="SearchResults-heading"><?php sprintf( _x( 'Search results for: %s', 'Search results title.', 'fl-automator' ), get_search_query() ); ?></h1>
            <?php get_search_form(); ?>
        </header>
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
</main>
