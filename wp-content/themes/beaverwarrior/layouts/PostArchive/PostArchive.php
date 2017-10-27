<?php $archive_page_id = get_queried_object_id(); ?>

<?php print get_template_component('HeroHeader', null, null, array("post_id" => $archive_page_id)); ?>

<main>
    <?php if ( have_posts() ) : ?>
        <?php while (have_posts()) {
            the_post(); ?>
            <article class="Article Article--teaser">
                <div class="Article-body">
                    <h2 class="Article-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                    <a href="<?php echo get_permalink(); ?>" class="Article-read_more"><?php echo __("Read More", "skeleton_warrior"); ?></a>
                </div>
            </article>
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