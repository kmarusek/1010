<?php
$args = apply_filters( 'fl_theme_builder_post_nav', array(
	'prev_text' => '&larr; %title',
	'next_text' => '%title &rarr;',
	'in_same_term' => $settings->in_same_term,
) );
the_post_navigation( $args );
