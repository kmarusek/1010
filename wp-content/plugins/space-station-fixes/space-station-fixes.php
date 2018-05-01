<?php
/**
 * Plugin Name: Space Station Extras
 * Description: Adds bug fixes and other things to Wordpress.
 * Version: 0.0.0
 * Author: HUEMOR
 * Author URI: http://pantheon.io
 * Text Domain: skeleton-warrior
 **/

function space_station_fix_wp_ajax_add_menu_item() {
	check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );

	if ( ! current_user_can( 'edit_theme_options' ) )
		wp_die( -1 );

	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

	// For performance reasons, we omit some object properties from the checklist.
	// The following is a hacky way to restore them when adding non-custom items.

	$menu_items_data = array();
	foreach ( (array) $_POST['menu-item'] as $menu_item_data ) {
		if (
			! empty( $menu_item_data['menu-item-type'] ) &&
			'custom' != $menu_item_data['menu-item-type'] &&
			! empty( $menu_item_data['menu-item-object-id'] )
		) {
			switch( $menu_item_data['menu-item-type'] ) {
				case 'post_type' :
					$_object = get_post( $menu_item_data['menu-item-object-id'] );
				break;

				case 'post_type_archive' :
					$_object = get_post_type_object( $menu_item_data['menu-item-object'] );
				break;

				case 'taxonomy' :
					$_object = get_term( $menu_item_data['menu-item-object-id'], $menu_item_data['menu-item-object'] );
				break;
			}

			$_menu_items = array_map( 'wp_setup_nav_menu_item', array( $_object ) );
			$_menu_item = reset( $_menu_items );
          
          //Removed from here was code to autopopulate the description
		}

		$menu_items_data[] = $menu_item_data;
	}

	$item_ids = wp_save_nav_menu_items( 0, $menu_items_data );
	if ( is_wp_error( $item_ids ) )
		wp_die( 0 );

	$menu_items = array();

	foreach ( (array) $item_ids as $menu_item_id ) {
		$menu_obj = get_post( $menu_item_id );
		if ( ! empty( $menu_obj->ID ) ) {
			$menu_obj = wp_setup_nav_menu_item( $menu_obj );
			$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
			$menu_items[] = $menu_obj;
		}
	}

	/** This filter is documented in wp-admin/includes/nav-menu.php */
	$walker_class_name = apply_filters( 'wp_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', $_POST['menu'] );

	if ( ! class_exists( $walker_class_name ) )
		wp_die( 0 );

	if ( ! empty( $menu_items ) ) {
		$args = array(
			'after' => '',
			'before' => '',
			'link_after' => '',
			'link_before' => '',
			'walker' => new $walker_class_name,
		);
		echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
	}
	wp_die();
}
add_filter("wp_ajax_add-menu-item", "space_station_fix_wp_ajax_add_menu_item", -9999);