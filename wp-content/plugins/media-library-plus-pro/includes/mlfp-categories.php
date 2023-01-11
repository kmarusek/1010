<?php

	$labels = array(
		'name'                       => _x( 'Media Categories', 'Taxonomy General Name', 'maxgalleria-media-library' ),
		'singular_name'              => _x( 'Media Category', 'Taxonomy Singular Name', 'maxgalleria-media-library' ),
		'menu_name'                  => __( 'Media Categories', 'maxgalleria-media-library' ),
		'all_items'                  => __( 'All Media Categories', 'maxgalleria-media-library' ),
		'parent_item'                => __( 'Parent Media Category', 'maxgalleria-media-library' ),
		'parent_item_colon'          => __( 'Parent Media Category:', 'maxgalleria-media-library' ),
		'new_item_name'              => __( 'New Media Category Name', 'maxgalleria-media-library' ),
		'add_new_item'               => __( 'Add New Media Category', 'maxgalleria-media-library' ),
		'edit_item'                  => __( 'Edit Media Category', 'maxgalleria-media-library' ),
		'update_item'                => __( 'Update Media Category', 'maxgalleria-media-library' ),
		'view_item'                  => __( 'View Media Category', 'maxgalleria-media-library' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'maxgalleria-media-library' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'maxgalleria-media-library' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'maxgalleria-media-library' ),
		'popular_items'              => __( 'Popular Items', 'maxgalleria-media-library' ),
		'search_items'               => __( 'Search Media Categories', 'maxgalleria-media-library' ),
		'not_found'                  => __( 'Not Found', 'maxgalleria-media-library' ),
		'no_terms'                   => __( 'No items', 'maxgalleria-media-library' ),
		'items_list'                 => __( 'Items list', 'maxgalleria-media-library' ),
		'items_list_navigation'      => __( 'Items list navigation', 'maxgalleria-media-library' ),
	);
	$rewrite = array(
		'slug'                       => 'media_category',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( MAXGALLERIA_MEDIA_LIBRARY_CATEGORY, array( 'attachment' ), $args );

