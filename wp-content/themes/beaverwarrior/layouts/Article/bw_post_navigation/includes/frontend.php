<?php
global $wp_query, $post;

// Get the post and query.
$original_post = $post;

if ( is_object( $original_post ) && 0 === $original_post->ID && isset( $wp_query->post ) ) {
	$post = $wp_query->post;
}

$args = apply_filters( 'fl_theme_builder_post_nav', array(
	'prev_text' => '&larr; %title',
	'next_text' => '%title &rarr;',
	'in_same_term' => $settings->in_same_term,
) );
the_post_navigation( $args );

// Related posts extension
$tags = wp_get_post_tags($post->ID);

if ($tags) {
    $tag_ids = array();

    foreach ($tags as $indiv_tag) {
        $tag_ids[] = $indiv_tag->term_id;
    }

    $related_query = new WP_Query(array (
        'tag__in' => $tag_ids,
        'post__not_in' => $post->ID,
        'posts_per_page' => 4,
        'caller_get_posts' => 1
    ));

    while ($related_query->have_posts()) {
        $related_query->the_post();

        ?>
        <?php the_title(); ?>
        <?php
    }
}

// Reset the global post variable.
$post = $original_post;
