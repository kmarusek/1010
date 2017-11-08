<main class="SearchResults">
    <div class="SearchResults-content">
        <header class="SearchResults-header">
            <h1 class="SearchResults-heading"><?php echo sprintf( _x( 'Search results for: %s', 'Search results title.', 'fl-automator' ), get_search_query() ); ?></h1>

            <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="SearchResults-search_form SiteHeader-search SiteHeader-search--first_menu">
                <div class="FormItem">
                    <input type="search" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" />
                </div>
                <div class="FormItem--actions SiteHeader-form_button_wrapper">
                    <button type="submit" class="submit FormItem-action FormItem-action--primary SiteHeader-form_button SiteHeader-search_button" name="submit" id="searchsubmit">
                        <?php esc_attr_e( 'Search', 'twentyeleven' ); ?>
                    </button>
                </div>
            </form>
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
