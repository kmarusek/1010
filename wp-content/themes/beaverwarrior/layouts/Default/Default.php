<main>
    <?php if ( have_posts() ) : ?>
        <?php while (have_posts()) {
            the_post(); ?>

            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        <?php }

        // Previous/next page navigation.
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
            'next_text'          => __( 'Next page', 'twentyfifteen' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
        ) );
    endif;
    ?>
</main>