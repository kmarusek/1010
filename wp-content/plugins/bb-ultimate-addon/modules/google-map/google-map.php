<?php
/**
 *  UABB Google Map Module file
 *
 *  @package UABB Google Map Module
 */

/**
 * Function that initializes Google Map Module
 *
 * @class GoogleMapModule
 */
class GoogleMapModule extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Google Map Module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Google Map', 'uabb' ),
				'description'     => __( 'Google Map', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/google-map/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/google-map/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'location.svg',
			)
		);

		add_filter( 'fl_builder_render_settings_field', array( $this, 'uabb_google_map_settings_field' ), 10, 3 );

		$uabb_setting_options = UABB_Init::$uabb_options['fl_builder_uabb'];

		$google_api_key = '';

		if ( isset( $uabb_setting_options['uabb-google-map-api'] ) && ! empty( $uabb_setting_options['uabb-google-map-api'] ) ) {

			$google_api_key = $uabb_setting_options['uabb-google-map-api'];
		}

		$url          = 'https://maps.googleapis.com/maps/api/js';
		$this->notice = __( 'Notice', 'uabb' );
		if ( false != $google_api_key ) {
			$arr_params   = array(
				'key' => $google_api_key,
			);
			$url          = esc_url( add_query_arg( $arr_params, $url ) );
			$this->notice = '';
			$this->add_js( 'google-map', $url, array(), '', true );
		}
	}
	/**
	 * Function that renders the Maps Settings field
	 *
	 * @param string $field gets the string for the maps settings field.
	 * @param array  $name an array to get the names of the settings field.
	 * @param object $settings an object to get various settings.
	 */
	function uabb_google_map_settings_field( $field, $name, $settings ) {

		if ( isset( $settings->map_lattitude ) && isset( $settings->map_longitude ) ) {

			if ( isset( $settings->uabb_gmap_addresses ) && empty( $settings->uabb_gmap_addresses ) ) {
				$settings->uabb_gmap_addresses = array();
			}

			if ( empty( $settings->uabb_gmap_addresses[0] ) ) {
				$settings->uabb_gmap_addresses[0] = (object) array(
					'map_lattitude'    => '',
					'map_longitude'    => '',
					'marker_img_src'   => '',
					'marker_point'     => '',
					'open_marker'      => '',
					'info_window_text' => '',
				);
			}

			if ( '' == $settings->uabb_gmap_addresses[0]->map_lattitude && '' == $settings->uabb_gmap_addresses[0]->map_longitude ) {
				if ( isset( $settings->marker_point ) ) {
					$settings->uabb_gmap_addresses[0]->marker_point = ( '' != $settings->marker_point ) ? $settings->marker_point : 'default';
				}

				if ( isset( $settings->open_marker ) ) {
					$settings->uabb_gmap_addresses[0]->open_marker = ( '' != $settings->open_marker ) ? $settings->open_marker : 'no';
				}

				if ( isset( $settings->marker_img_src ) ) {
					$settings->uabb_gmap_addresses[0]->marker_img     = ( '' != $settings->marker_img ) ? $settings->marker_img : '';
					$settings->uabb_gmap_addresses[0]->marker_img_src = ( '' != $settings->marker_img_src ) ? $settings->marker_img_src : '';
				}
			}
			if ( '' == $settings->uabb_gmap_addresses[0]->map_lattitude ) {
				$settings->uabb_gmap_addresses[0]->map_lattitude = ( '' != $settings->map_lattitude ) ? $settings->map_lattitude : 40.76142;
			}

			if ( '' == $settings->uabb_gmap_addresses[0]->map_longitude ) {
				$settings->uabb_gmap_addresses[0]->map_longitude = ( '' != $settings->map_longitude ) ? $settings->map_longitude : -73.97712;
			}

			if ( isset( $settings->info_window_text ) ) {
				if ( '' == $settings->uabb_gmap_addresses[0]->info_window_text ) {
					$settings->uabb_gmap_addresses[0]->info_window_text = ( '' != $settings->info_window_text ) ? $settings->info_window_text : '';
				}
			}
		}

		return $field;
	}
}

$google_api_key = '';

$style1 = 'line-height: 1.45em; color: #a94442;';
$style2 = 'font-weight:bold;color: #a94442;';
$notice = sprintf( /* translators: %1$s: search term, %2$s: search term, %3$s: search term */
	__( '<span style="%1$s">To display customized Google Map without an issue, you need to configure Google Map API key in <span style="%2$s">General Settings</span>. Please configure API key from <a href="%3$s" class="uabb-google-map-notice" target="_blank" rel="noopener">here</a></span>.', 'uabb' ),
	$style1, $style2, admin_url( 'options-general.php?page=uabb-builder-settings#uabb' )
);


$uabb_setting_options = UABB_Init::$uabb_options['fl_builder_uabb'];

if ( isset( $uabb_setting_options['uabb-google-map-api'] ) && ! empty( $uabb_setting_options['uabb-google-map-api'] ) ) {
	$google_api_key = $uabb_setting_options['uabb-google-map-api'];
}
if ( '' != $google_api_key ) {
	$notice = '';
}
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'GoogleMapModule', array(
		'multiple_addresses' => array( // Tab.
			'title'       => __( 'Addresses', 'uabb' ), // Tab title.
			'description' => $notice,
			'sections'    => array( // Tab Sections.
				'title' => array( // Section.
					'title'  => __( 'Addresses', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'uabb_gmap_addresses' => array(
							'type'         => 'form',
							'label'        => __( 'Address', 'uabb' ),
							'form'         => 'uabb_google_map_addresses',
							'preview_text' => 'map_name',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'general'            => array( // Tab.
			'title'    => __( 'General', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'title' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'map_width'      => array(
							'type'        => 'text',
							'label'       => __( 'Width', 'uabb' ),
							'placeholder' => '100',
							'size'        => '6',
							'description' => '%',
						),
						'map_height'     => array(
							'type'        => 'text',
							'label'       => __( 'Height', 'uabb' ),
							'placeholder' => '300',
							'size'        => '6',
							'description' => 'px',
						),
						'map_type'       => array(
							'type'    => 'select',
							'label'   => __( 'Map type', 'uabb' ),
							'default' => 'ROADMAP',
							'options' => array(
								'ROADMAP'   => __( 'Roadmap', 'uabb' ),
								'SATELLITE' => __( 'Satellite', 'uabb' ),
								'HYBRID'    => __( 'Hybrid', 'uabb' ),
								'TERRAIN'   => __( 'Terrain', 'uabb' ),
							),
							'toggle'  => array(
								'ROADMAP'   => array(
									'fields' => array( 'map_skin' ),
								),
								'SATELLITE' => array(
									'fields' => array( '' ),
								),
								'HYBRID'    => array(
									'fields' => array( 'map_skin' ),
								),
								'TERRAIN'   => array(
									'fields' => array( 'map_skin' ),
								),
							),
						),
						'map_fit_marker' => array(
							'type'    => 'select',
							'label'   => __( 'Auto Zoom', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no' => array(
									'fields' => array( 'map_zoom' ),
								),
							),
						),
						'map_zoom'       => array(
							'type'    => 'select',
							'label'   => __( 'Map Zoom', 'uabb' ),
							'default' => '15',
							'options' => array(
								'1'  => __( '1', 'uabb' ),
								'2'  => __( '2', 'uabb' ),
								'3'  => __( '3', 'uabb' ),
								'4'  => __( '4', 'uabb' ),
								'5'  => __( '5', 'uabb' ),
								'6'  => __( '6', 'uabb' ),
								'7'  => __( '7', 'uabb' ),
								'8'  => __( '8', 'uabb' ),
								'9'  => __( '9', 'uabb' ),
								'10' => __( '10', 'uabb' ),
								'11' => __( '11', 'uabb' ),
								'12' => __( '12', 'uabb' ),
								'13' => __( '13', 'uabb' ),
								'14' => __( '14', 'uabb' ),
								'15' => __( '15', 'uabb' ),
								'16' => __( '16', 'uabb' ),
								'17' => __( '17', 'uabb' ),
								'18' => __( '18', 'uabb' ),
								'19' => __( '19', 'uabb' ),
								'20' => __( '20', 'uabb' ),
							),
						),
						'map_expand'     => array(
							'type'    => 'select',
							'label'   => __( 'Disable map zoom on mouse wheel scroll', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'control'            => array( // Tab.
			'title'    => __( 'Controls', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'title' => array( // Section.
					'title'  => __( 'Advanced Controls', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'street_view'           => array(
							'type'    => 'select',
							'label'   => __( 'Street view control', 'uabb' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'Yes', 'uabb' ),
								'false' => __( 'No', 'uabb' ),
							),
						),
						'map_type_control'      => array(
							'type'    => 'select',
							'label'   => __( 'Map type control', 'uabb' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'Yes', 'uabb' ),
								'false' => __( 'No', 'uabb' ),
							),
						),
						'zoom'                  => array(
							'type'    => 'select',
							'label'   => __( 'Zoom control', 'uabb' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'Yes', 'uabb' ),
								'false' => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'true' => array(
									'fields' => array( 'zoom_control_position' ),
								),
							),
						),
						'zoom_control_position' => array(
							'type'    => 'select',
							'label'   => __( 'Zoom control position', 'uabb' ),
							'default' => 'RIGHT_BOTTOM',
							'options' => array(
								'RIGHT_TOP'    => 'Right Top',
								'RIGHT_CENTER' => 'Right Center',
								'RIGHT_BOTTOM' => 'Right Bottom',
								'LEFT_TOP'     => 'Left Top',
								'LEFT_CENTER'  => 'Left Center',
								'LEFT_BOTTOM'  => 'Left Bottom',
							),
						),
						'dragging'              => array(
							'type'    => 'select',
							'label'   => __( 'Disable dragging on Mobile', 'uabb' ),
							'default' => 'false',
							'options' => array(
								'false' => __( 'Yes', 'uabb' ),
								'true'  => __( 'No', 'uabb' ),
							),
						),
					),
				),
			),
		),
		'style'              => array( // Tab.
			'title'    => __( 'JSON Style', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'title' => array( // Section.
					'title'  => __( 'Map Style', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'map_skin'  => array(
							'type'    => 'select',
							'label'   => __( 'Map Skin', 'uabb' ),
							'default' => 'custom',
							'options' => array(
								'standard'     => __( 'Standard', 'uabb' ),
								'silver'       => __( 'Silver', 'uabb' ),
								'retro'        => __( 'Retro', 'uabb' ),
								'dark'         => __( 'Dark', 'uabb' ),
								'night'        => __( 'Night', 'uabb' ),
								'aubergine'    => __( 'Aubergine', 'uabb' ),
								'aqua'         => __( 'Aqua', 'uabb' ),
								'classic_blue' => __( 'Classic Blue', 'uabb' ),
								'earth'        => __( 'Earth', 'uabb' ),
								'magnesium'    => __( 'Magnesium', 'uabb' ),
								'custom'       => __( 'Custom', 'uabb' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'map_style' ),
								),
							),
						),
						'map_style' => array(
							'type'          => 'editor',
							'label'         => '',
							'rows'          => 25,
							'media_buttons' => false,
							'description'   => __( '<br/><br/><a target="_blank" rel="noopener" href="https://mapstyle.withgoogle.com/">Click here</a> to get the style JSON code for styling your map.', 'uabb' ),
							'connections'   => array( 'string', 'html' ),
						),
					),
				),
			),
		),
	)
);

FLBuilder::register_settings_form(
	'uabb_google_map_addresses', array(
		'title' => __( 'Add Address', 'uabb' ),
		'tabs'  => array(
			'general'     => array(
				'title'    => __( 'General', 'uabb' ),
				'sections' => array(
					'features' => array(
						'title'  => __( 'Address', 'uabb' ),
						'fields' => array(
							'map_name'      => array(
								'type'        => 'text',
								'label'       => __( 'Name', 'uabb' ),
								'placeholder' => 'Name the Address',
								'help'        => __( 'Name the Address to identify while editing', 'uabb' ),
							),
							'map_lattitude' => array(
								'type'        => 'text',
								'label'       => __( 'Latitude', 'uabb' ),
								'placeholder' => '40.76142',
								'description' => __( '</br></br><a href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="noopener">Here is a tool</a> where you can find Latitude & Longitude of your location', 'uabb' ),
								'connections' => array( 'custom_field' ),
							),
							'map_longitude' => array(
								'type'        => 'text',
								'label'       => __( 'Longitude', 'uabb' ),
								'placeholder' => '-73.97712',
								'description' => __( '</br></br><a href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="noopener">Here is a tool</a> where you can find Latitude & Longitude of your location', 'uabb' ),
								'connections' => array( 'custom_field' ),
							),
						),
					),
				),
			),
			'marker'      => array(
				'title'    => __( 'Marker', 'uabb' ),
				'sections' => array(
					'features' => array(
						'title'  => '',
						'fields' => array(
							'marker_point' => array(
								'type'    => 'select',
								'label'   => __( 'Marker Point Icon', 'uabb' ),
								'default' => 'default',
								'options' => array(
									'default' => 'Default',
									'custom'  => 'Custom',
								),
								'toggle'  => array(
									'custom' => array(
										'fields' => array( 'marker_img' ),
									),
								),
							),
							'marker_img'   => array(
								'type'        => 'photo',
								'label'       => __( 'Custom Marker', 'uabb' ),
								'show_remove' => true,
							),
						),
					),
				),
			),
			'info_window' => array( // Tab.
				'title'    => __( 'Info Text', 'uabb' ), // Tab title.
				'sections' => array( // Tab Sections.
					'title' => array( // Section.
						'title'  => '', // Section Title.
						'fields' => array( // Section Fields.
							'enable_info'      => array(
								'type'    => 'select',
								'label'   => __( 'Show Info Text', 'uabb' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Yes', 'uabb' ),
									'no'  => __( 'No', 'uabb' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'info_window_text', 'open_marker' ),
									),
								),
							),
							'info_window_text' => array(
								'type'          => 'editor',
								'label'         => '',
								'media_buttons' => false,
								'connections'   => array( 'string', 'html' ),
							),
							'open_marker'      => array(
								'type'    => 'select',
								'label'   => __( 'Disable Info Window On Load', 'uabb' ),
								'default' => 'no',
								'options' => array(
									'yes' => __( 'Yes', 'uabb' ),
									'no'  => __( 'No', 'uabb' ),
								),
							),
						),
					),
				),
			),
		),
	)
);
