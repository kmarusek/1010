<?php
global $wp_query, $post;

// Get the post and query.
$original_post = $post;

if ( is_object( $original_post ) && 0 === $original_post->ID && isset( $wp_query->post ) ) {
	$post = $wp_query->post;
}

// Related posts extension
$tags = wp_get_post_tags($post->ID);

if ($tags) {
    $tag_ids = array();

    foreach ($tags as $indiv_tag) {
        $tag_ids[] = $indiv_tag->term_id;
    }

    $related_query = new WP_Query(array (
        'post_type' => $post->post_type,
        'tag__in' => $tag_ids,
        'post__not_in' => array($post->ID),
        'posts_per_page' => $settings->post_limit,
        'caller_get_posts' => 1
    ));
} else {
    $related_query = new WP_Query(array (
        'post_type' => $post->post_type,
        'post__not_in' => array($post->ID),
        'posts_per_page' => $settings->post_limit,
        'caller_get_posts' => 1
    ));
}

?><div class="Article-related_posts Article-related_posts--<?php echo $settings->post_limit; ?>up"><?php

while ($related_query->have_posts()) {
    $related_query->the_post();

    ?>
    <div class="Article-related_post_gutter">
        <div class="Article-related_post">
            <?php
                $layout_sequence = explode( ',', $settings->layout_sort_order );

                foreach( $layout_sequence as $sq ) {
                    switch ( $sq ) {
                        case 'img':
                            if ($settings->show_featured_image === 'yes' || !isset($settings->show_featured_image)) { ?>
                                <div class="Article-related_post_thumbnail_wrapper">
                                    <a href="<?php echo get_permalink(); ?>">
                                       <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="Article-related_post_thumbnail">
                                    </a>
                                </div>
                            <?php }
                            break;
                        case 'title':
                            if ($settings->show_title === 'yes' || !isset($settings->show_title)) { ?>
                                <h3 class="Article-related_post_title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php }
                            break;
                        case 'meta':
                            if ($settings->show_meta === 'yes' || !isset($settings->show_meta)) { ?>
                                <div class="Article-related_post_meta">
                                    <?php $meta_layout_sequence = explode( ',', $settings->meta_sort_order );
                                        $meta_first = true;

                                        foreach( $meta_layout_sequence as $sq ) {
                                            switch ( $sq ) {
                                                case 'author':
                                                    if ($settings->show_meta_author === 'yes' || !isset($settings->show_meta_author)) {
                                                        if ($meta_first) $meta_first = false;
                                                        else echo ' | ';
                                                        ?>
                                                            By <a class="Article-related_post_author" href="<?php echo get_author_posts_url( $obj->post_author ); ?>"><?php

                                                                $author = ( get_the_author_meta( 'display_name', $obj->post_author ) != '' ) ? get_the_author_meta( 'display_name', $obj->post_author ) : get_the_author_meta( 'user_nicename', $obj->post_author );

                                                                echo $author; ?>
                                                            </a>
                                                        <?php
                                                    }
                                                    break;
                                                case 'date':
                                                    if ($settings->show_meta_date === 'yes' || !isset($settings->show_meta_date)) {
                                                        if ($meta_first) $meta_first = false;
                                                        else echo ' | ';
                                                        ?>
                                                            <span class="Article-related_post_date"><?php echo date_i18n( 'M j, Y', strtotime( $post->post_date ) ); ?></span>
                                                        <?php
                                                    }
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                    ?>
                                </div>
                            <?php }
                            break;
                        case 'content':
                            if ($settings->show_excerpt === 'yes' || !isset($settings->show_excerpt)) { ?>
                                <div class="Article-related_post_excerpt"><?php echo get_the_excerpt(); ?></div>
                            <?php }
                            break;
                        case 'cta':
                            if ($settings->show_cta === 'yes' || !isset($settings->show_cta)) { ?>
                                <a href="<?php echo get_permalink(); ?>" class="Article-related_post_permalink">Read More</a>
                            <?php }
                            break;
                        default:
                            break;
                    }
                }
            ?>
        </div>
    </div>
    <?php
}

?></div><?php

// Reset the global post variable.
$post = $original_post;
