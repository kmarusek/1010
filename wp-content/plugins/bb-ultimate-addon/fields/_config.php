<?php

/*
 * Custom Fields Config File
 * Description: This is custom fields config file. Require your custom field's "main" file here.
 *
*/

// require_once 'uabb-fields.php';

require_once 'uabb-msg-box/uabb-msg-box.php';
require_once 'uabb-gradient/uabb-gradient.php';
require_once 'uabb-hotspot-draggable/uabb-hotspot-draggable.php';
require_once 'uabb-sortable/uabb-sortable.php';
require_once 'uabb-date/uabb-date.php';

if( !class_exists('UABB_Custom_Field_Scripts') ) {
	class UABB_Custom_Field_Scripts
	{
		function __construct() {	
			add_action( 'wp_enqueue_scripts', array( $this, 'custom_field_scripts' ) );
		}
	

		function custom_field_scripts() {
		    if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

		        /* uabb-msgbox field */
		   		wp_enqueue_style( 'msg_field-styles', BB_ULTIMATE_ADDON_URL . 'fields/uabb-msg-box/css/uabb-msg-field.css' );

		        /* uabb-gradient field */
				wp_enqueue_style( 'uabb-gradient', BB_ULTIMATE_ADDON_URL . 'fields/uabb-gradient/css/uabb-gradient.css', array(), '' );
				wp_enqueue_script( 'uabb-gradient', BB_ULTIMATE_ADDON_URL . 'fields/uabb-gradient/js/uabb-gradient.js', array(), '', true );

				wp_enqueue_style( 'uabb_sortable-styles', BB_ULTIMATE_ADDON_URL . 'fields/uabb-sortable/css/uabb-sortable.css', array(), '' );
                wp_enqueue_script( 'uabb_sortable-scripts', BB_ULTIMATE_ADDON_URL . 'fields/uabb-sortable/js/uabb-sortable.js', array(), '', true );

                wp_enqueue_style( 'uabb-hotspot-draggable', BB_ULTIMATE_ADDON_URL . 'fields/uabb-hotspot-draggable/css/uabb-hotspot-draggable.css', array(), '' );
				wp_enqueue_script( 'uabb-hotspot-draggable', BB_ULTIMATE_ADDON_URL . 'fields/uabb-hotspot-draggable/js/uabb-hotspot-draggable.js', array(), '', true );

				 /* uabb-simplyfy field */
				wp_enqueue_style( 'uabb-date', BB_ULTIMATE_ADDON_URL . 'fields/uabb-date/css/uabb-date.css', array(), '' );


		        /* uabb-color field ( Not included for now ) */
			}
		}
	}

	$UABB_Custom_Field_Scripts = new UABB_Custom_Field_Scripts();
}