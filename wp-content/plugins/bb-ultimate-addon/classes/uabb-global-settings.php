<?php

/**
 * Global Styling
 */

class UABB_Global_Styling {

	/**
	*  Constructor
	*/

	public function __construct() {

		//self::add_options();
		self::init_actions();
	}

	function add_options() {

		$global_options = UABB_Init::$uabb_options['uabb_global_settings'];

		if( ! isset($global_options) || $global_options == '' || !is_array($global_options) ) {

			$default = array(
				'enable_global' 			=> 'yes',
				'theme_color' 				=> 'f7b91a',
				'theme_text_color' 			=> '808285',
				'btn_bg_color' 				=> 'f7b91a',
				'btn_bg_color_opc' 			=> '',
				'btn_bg_hover_color' 		=> '000000',
				'btn_bg_hover_color_opc' 	=> '',
				'btn_text_color' 			=> 'ffffff',
				'btn_text_hover_color' 		=> 'ffffff',
				'btn_font_size' 			=> '',
				'btn_line_height' 			=> '',
				'btn_letter_spacing' 		=> '',
				'btn_text_transform' 		=> 'none',
				'btn_border_radius' 		=> '5',
				'btn_vertical_padding' 		=> '',
				'btn_horizontal_padding' 	=> ''
			);

			/**
			 *	For Performance
			 *	Update UABB static object from database.
			 */
			UABB_Init::set_uabb_options();

			update_option( '_uabb_global_settings', $default );
		}
	}

	static public function init_actions()
	{
		FLBuilderAJAX::add_action( 'render_uabb_global_settings', 'UABB_Global_Styling::render_uabb_global_settings' );
		FLBuilderAJAX::add_action( 'save_uabb_global_settings', 'UABB_Global_Styling::save_uabb_global_settings', array( 'settings' ) );
	}

	static public function render_uabb_global_settings() {

		$settings 	= self::get_uabb_global_settings();
		$form 		= FLBuilderModel::$settings_forms['uabb-global'];

		return FLBuilder::render_settings(array(
			'class'   	=> 'fl-builder-uabb-global-settings',
			'title'   	=> $form['title'],
			'tabs'    	=> $form['tabs'],
			'resizable' => true
		), $settings);
	}

	static public function get_uabb_global_settings()
	{
		$settings = UABB_Init::$uabb_options['uabb_global_settings'];

		$defaults = FLBuilderModel::get_settings_form_defaults( 'uabb-global' );

		//var_dump( $settings  );
		//die();

		if( !$settings ) {
			$settings = new StdClass();
		}

		return (object)array_merge((array)$defaults, (array)$settings);
	}

	static public function save_uabb_global_settings($settings = array())
	{
		$old_settings = self::get_uabb_global_settings();
		$new_settings = (object)array_merge((array)$old_settings, (array)$settings);

		FLBuilderModel::delete_asset_cache_for_all_posts();

		/**
		 *	For Performance
		 *	Update UABB static object from database.
		 */
		UABB_Init::set_uabb_options();

		return update_option('_uabb_global_settings', $settings);
	}
}

new UABB_Global_Styling();
