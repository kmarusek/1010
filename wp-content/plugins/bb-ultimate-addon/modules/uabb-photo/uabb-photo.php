<?php
/**
 *  UABB Photo Module file
 *
 *  @package UABB Photo Module
 */

/**
 * Function that initializes UABB Photo Module
 *
 * @class UABBPhotoModule
 */
class UABBPhotoModule extends FLBuilderModule {

	/**
	 * Variable for Photo module
	 *
	 * @property $data
	 * @var $data
	 */
	public $data = null;

	/**
	 * Variable for Photo module
	 *
	 * @property $_editor
	 * @protected
	 * @var $_editor
	 */
	protected $_editor = null;

	/**
	 * Constructor function that constructs default values for the Photo module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Photo', 'uabb' ),
				'description'     => __( 'Upload a photo or display one from the media library.', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-photo/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-photo/',
				'partial_refresh' => true,
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'icon'            => 'format-image.svg',
			)
		);
	}

	/**
	 * Function that enqueue scripts
	 *
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {
		if ( $this->settings && 'lightbox' == $this->settings->link_type ) {
			$this->add_js( 'jquery-magnificpopup' );
			$this->add_css( 'jquery-magnificpopup' );
		}
	}

	/**
	 * Function that upadte the photo source
	 *
	 * @method update
	 * @param object $settings {object}.
	 */
	public function update( $settings ) {
		// Make sure we have a photo_src property.
		if ( ! isset( $settings->photo_src ) ) {
			$settings->photo_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data( $settings->photo );

		if ( $data ) {
			$settings->data = $data;
		}

		// Save a crop if necessary.
		$this->crop();

		return $settings;
	}

	/**
	 * Function that deletes the cropped path
	 *
	 * @method delete
	 */
	public function delete() {
		$cropped_path = $this->_get_cropped_path();

		if ( file_exists( $cropped_path['path'] ) ) {
			unlink( $cropped_path['path'] );
		}
	}

	/**
	 * Function that delete an existing crop if it exists
	 *
	 * @method crop
	 */
	public function crop() {
		// Delete an existing crop if it exists.
		$this->delete();

		// Do a crop.
		if ( ! empty( $this->settings->style ) && 'simple' != $this->settings->style && 'custom' != $this->settings->style ) {

			$editor = $this->_get_editor();

			if ( ! $editor || is_wp_error( $editor ) ) {
				return false;
			}

			$cropped_path = $this->_get_cropped_path();
			$size         = $editor->get_size();
			$new_width    = $size['width'];
			$new_height   = $size['height'];

			// Get the crop ratios.
			if ( 'landscape' == $this->settings->style ) {
				$ratio_1 = 1.43;
				$ratio_2 = .7;
			} elseif ( 'panorama' == $this->settings->style ) {
				$ratio_1 = 2;
				$ratio_2 = .5;
			} elseif ( 'portrait' == $this->settings->style ) {
				$ratio_1 = .7;
				$ratio_2 = 1.43;
			} elseif ( 'square' == $this->settings->style ) {
				$ratio_1 = 1;
				$ratio_2 = 1;
			} elseif ( 'circle' == $this->settings->style ) {
				$ratio_1 = 1;
				$ratio_2 = 1;
			}

			// Get the new width or height.
			if ( $size['width'] / $size['height'] < $ratio_1 ) {
				$new_height = $size['width'] * $ratio_2;
			} else {
				$new_width = $size['height'] * $ratio_1;
			}

			// Make sure we have enough memory to crop, removed @ini_set( 'memory_limit', '300M' );.
			ini_set( 'memory_limit', '300M' );

			// Crop the photo.
			$editor->resize( $new_width, $new_height, true );

			// Save the photo.
			$editor->save( $cropped_path['path'] );

			// Return the new url.
			return $cropped_path['url'];
		}

		return false;
	}

	/**
	 * Function that gets the data
	 *
	 * @method get_data
	 */
	public function get_data() {
		if ( empty( $this->data ) ) {

			// Photo source is set to "url".
			if ( 'url' == $this->settings->photo_source ) {
				$this->data                = new stdClass();
				$this->data->alt           = $this->settings->caption;
				$this->data->caption       = $this->settings->caption;
				$this->data->link          = $this->settings->photo_url;
				$this->data->url           = $this->settings->photo_url;
				$this->settings->photo_src = $this->settings->photo_url;
			} elseif ( is_object( $this->settings->photo ) ) {
				// Photo source is set to "library".
				$this->data = $this->settings->photo;
			} else {
				$this->data = FLBuilderPhoto::get_attachment_data( $this->settings->photo );
			}

			// Data object is empty, use the settings cache.
			if ( ! $this->data && isset( $this->settings->data ) ) {
				$this->data = $this->settings->data;
			}
		}

		return $this->data;
	}

	/**
	 * Function that gets classes for the Photo
	 *
	 * @method get_classes
	 */
	public function get_classes() {
		$classes = array( 'uabb-photo-img' );

		if ( ! empty( $this->settings->photo ) ) {

			$data = self::get_data();

			if ( is_object( $data ) ) {

				$classes[] = 'wp-image-' . $data->id;

				if ( isset( $data->sizes ) ) {

					foreach ( $data->sizes as $key => $size ) {

						if ( $size->url == $this->settings->photo_src ) {
							$classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}
		}

		return implode( ' ', $classes );
	}

	/**
	 * Function that gets source
	 *
	 * @method get_src
	 */
	public function get_src() {
		$src = $this->_get_uncropped_url();

		// Return a cropped photo.
		if ( $this->_has_source() && ! empty( $this->settings->style ) ) {

			$cropped_path = $this->_get_cropped_path();

			// See if the cropped photo already exists.
			if ( file_exists( $cropped_path['path'] ) ) {
				$src = $cropped_path['url'];
			} elseif ( stristr( $src, FL_BUILDER_DEMO_URL ) && ! stristr( FL_BUILDER_DEMO_URL, $_SERVER['HTTP_HOST'] ) ) {
				// It doesn't, check if this is a demo image.
				$src = $this->_get_cropped_demo_url();
			} elseif ( stristr( $src, FL_BUILDER_OLD_DEMO_URL ) ) {
				// It doesn't, check if this is a OLD demo image.
				$src = $this->_get_cropped_demo_url();
			} else {
				// A cropped photo doesn't exist, try to create one.
				$url = $this->crop();

				if ( $url ) {
					$src = $url;
				}
			}
		}

		return $src;
	}

	/**
	 * Function that gets link
	 *
	 * @method get_link
	 */
	public function get_link() {
		$photo = $this->get_data();

		if ( 'url' == $this->settings->link_type ) {
			$link = $this->settings->link_url;
		} elseif ( 'lightbox' == $this->settings->link_type ) {
			$link = $photo->url;
		} elseif ( 'file' == $this->settings->link_type ) {
			$link = $photo->url;
		} elseif ( 'page' == $this->settings->link_type ) {
			$link = $photo->link;
		} else {
			$link = '';
		}

		return $link;
	}

	/**
	 * Function that gets the alt
	 *
	 * @method get_alt
	 */
	public function get_alt() {
		$photo = $this->get_data();

		if ( ! empty( $photo->alt ) ) {
			return htmlspecialchars( $photo->alt );
		} elseif ( ! empty( $photo->description ) ) {
			return htmlspecialchars( $photo->description );
		} elseif ( ! empty( $photo->caption ) ) {
			return htmlspecialchars( $photo->caption );
		} elseif ( ! empty( $photo->title ) ) {
			return htmlspecialchars( $photo->title );
		}
	}

	/**
	 * Function to get the attributes
	 *
	 * @method get_attributes
	 */
	public function get_attributes() {
		$attrs = '';

		if ( isset( $this->settings->attributes ) ) {
			foreach ( $this->settings->attributes as $key => $val ) {
				$attrs .= $key . '="' . $val . '" ';
			}
		}

		return $attrs;
	}

	/**
	 * Function to get the source
	 *
	 * @method _has_source
	 * @protected
	 */
	protected function _has_source() {
		if ( 'url' == $this->settings->photo_source && ! empty( $this->settings->photo_url ) ) {
			return true;
		} elseif ( 'library' == $this->settings->photo_source && ! empty( $this->settings->photo_src ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Function that gets the editor
	 *
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor() {
		if ( $this->_has_source() && null === $this->_editor ) {

			$url_path  = $this->_get_uncropped_url();
			$file_path = str_ireplace( home_url(), ABSPATH, $url_path );

			if ( file_exists( $file_path ) ) {
				$this->_editor = wp_get_image_editor( $file_path );
			} else {
				$this->_editor = wp_get_image_editor( $url_path );
			}
		}

		return $this->_editor;
	}

	/**
	 * Function that gets the cropped path
	 *
	 * @method _get_cropped_path
	 * @protected
	 */
	protected function _get_cropped_path() {
		$crop      = empty( $this->settings->style ) ? 'none' : $this->settings->style;
		$url       = $this->_get_uncropped_url();
		$cache_dir = FLBuilderModel::get_cache_dir();

		if ( empty( $url ) ) {
			$filename = uniqid(); // Return a file that doesn't exist.
		} else {

			if ( stristr( $url, '?' ) ) {
				$parts = explode( '?', $url );
				$url   = $parts[0];
			}

			$pathinfo = pathinfo( $url );
			$dir      = $pathinfo['dirname'];
			$ext      = isset( $pathinfo['extension'] ) ? $pathinfo['extension'] : '';
			$name     = wp_basename( $url, ".$ext" );
			$new_ext  = strtolower( $ext );
			$filename = "{$name}-{$crop}.{$new_ext}";
		}

		return array(
			'filename' => $filename,
			'path'     => $cache_dir['path'] . $filename,
			'url'      => $cache_dir['url'] . $filename,
		);
	}

	/**
	 * Function that gets the uncropped URL
	 *
	 * @method _get_uncropped_url
	 * @protected
	 */
	protected function _get_uncropped_url() {
		if ( 'url' == $this->settings->photo_source ) {
			$url = $this->settings->photo_url;
		} elseif ( ! empty( $this->settings->photo_src ) ) {
			$url = $this->settings->photo_src;
		} else {
			$url = FL_BUILDER_URL . 'img/pixel.png';
		}

		return $url;
	}

	/**
	 * Function that gets the cropped demo URL
	 *
	 * @method _get_cropped_demo_url
	 * @protected
	 */
	protected function _get_cropped_demo_url() {
		$info = $this->_get_cropped_path();

		return FL_BUILDER_DEMO_CACHE_URL . $info['filename'];
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'UABBPhotoModule', array(
		'general' => array( // Tab.
			'title'    => __( 'General', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'general' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'photo_source'     => array(
							'type'    => 'select',
							'label'   => __( 'Photo Source', 'uabb' ),
							'default' => 'library',
							'options' => array(
								'library' => __( 'Media Library', 'uabb' ),
								'url'     => __( 'URL', 'uabb' ),
							),
							'toggle'  => array(
								'library' => array(
									'fields' => array( 'photo' ),
								),
								'url'     => array(
									'fields' => array( 'photo_url', 'caption' ),
								),
							),
						),
						'photo'            => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'uabb' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'photo_size'       => array(
							'type'        => 'unit',
							'label'       => __( 'Photo Size', 'uabb' ),
							'description' => 'px',
							'size'        => '8',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-photo-content img',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
						'photo_url'        => array(
							'type'        => 'text',
							'label'       => __( 'Photo URL', 'uabb' ),
							'placeholder' => 'http://www.example.com/my-photo.jpg',
						),
						'align'            => array(
							'type'    => 'select',
							'label'   => __( 'Alignment', 'uabb' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-photo',
								'property' => 'text-align',
							),
						),
						'responsive_align' => array(
							'type'    => 'select',
							'label'   => __( 'Mobile Alignment', 'uabb' ),
							'default' => 'center',
							'help'    => __( 'This alignment will apply on Mobile', 'uabb' ),
							'options' => array(
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
						),
					),
				),
				'caption' => array(
					'title'  => __( 'Caption', 'uabb' ),
					'fields' => array(
						'show_caption' => array(
							'type'    => 'select',
							'label'   => __( 'Show Caption', 'uabb' ),
							'default' => '0',
							'options' => array(
								'0'     => __( 'Never', 'uabb' ),
								'hover' => __( 'On Hover', 'uabb' ),
								'below' => __( 'Below Photo', 'uabb' ),
							),
						),
						'caption'      => array(
							'type'    => 'text',
							'label'   => __( 'Caption', 'uabb' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.uabb-photo-caption',
							),
						),
					),
				),
				'link'    => array(
					'title'  => __( 'Link', 'uabb' ),
					'fields' => array(
						'link_type'   => array(
							'type'    => 'select',
							'label'   => __( 'Link Type', 'uabb' ),
							'options' => array(
								''         => _x( 'None', 'Link type.', 'uabb' ),
								'url'      => __( 'URL', 'uabb' ),
								'lightbox' => __( 'Lightbox', 'uabb' ),
								'file'     => __( 'Photo File', 'uabb' ),
								'page'     => __( 'Photo Page', 'uabb' ),
							),
							'toggle'  => array(
								''     => array(),
								'url'  => array(
									'fields' => array( 'link_url', 'link_target', 'link_nofollow' ),
								),
								'file' => array(),
								'page' => array(),
							),
							'help'    => __( 'Link type applies to how the image should be linked on click. You can choose a specific URL, the individual photo or a separate page with the photo.', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'link_url'    => array(
							'type'    => 'link',
							'label'   => __( 'Link URL', 'uabb' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'link_target' => array(
							'type'    => 'select',
							'label'   => __( 'Link Target', 'uabb' ),
							'default' => '_self',
							'options' => array(
								'_self'  => __( 'Same Window', 'uabb' ),
								'_blank' => __( 'New Window', 'uabb' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'link_nofollow' => array(
							'type'        => 'select',
							'label'       => __( 'Link Nofollow', 'uabb' ),
							'description' => '',
							'default'     => '0',
							'help'        => __( 'Enable this to make this link nofollow.', 'uabb' ),
							'options'     => array(
								'1' => __( 'Yes', 'uabb' ),
								'0' => __( 'No', 'uabb' ),
							),
						),
					),
				),
			),
		),
		'style'   => array( // Tab.
			'title'    => __( 'Style', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'general' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'style'                   => array(
							'type'    => 'select',
							'label'   => __( 'Photo Style', 'uabb' ),
							'default' => 'simple',
							'options' => array(
								'simple'    => __( 'Simple', 'uabb' ),
								'circle'    => __( 'Circle Background', 'uabb' ),
								'square'    => __( 'Square Background', 'uabb' ),
								'landscape' => __( 'Landscape', 'uabb' ),
								'panorama'  => __( 'Panorama', 'uabb' ),
								'portrait'  => __( 'Portrait', 'uabb' ),
								'custom'    => __( 'Custom', 'uabb' ),
							),
							'toggle'  => array(
								'simple'    => array(
									'fields' => array(),
								),
								'circle'    => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size' ),
								),
								'square'    => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size' ),
								),
								'landscape' => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size' ),
								),
								'panorama'  => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size' ),
								),
								'portrait'  => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size' ),
								),
								'custom'    => array(
									'fields' => array( 'style_bg_color', 'style_bg_color_opc', 'bg_size', 'bg_border_radius' ),
								),
							),
						),

						'bg_size'                 => array(
							'type'        => 'unit',
							'label'       => __( 'Background Size', 'uabb' ),
							'default'     => '',
							'help'        => __( 'Space between icon and background', 'uabb' ),
							'maxlength'   => '3',
							'size'        => '4',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-photo .uabb-photo-content',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),

						'style_bg_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'uabb' ),
							'default'    => '',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-photo .uabb-photo-content',
								'property' => 'background-color',
							),
						),
						'style_bg_color_opc'      => array(
							'type'        => 'text',
							'label'       => __( 'Opacity', 'uabb' ),
							'default'     => '',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
						),
						'bg_border_radius'        => array(
							'type'        => 'unit',
							'label'       => __( 'Border Radius ( For Background )', 'uabb' ),
							'default'     => '',
							'maxlength'   => '3',
							'size'        => '4',
							'description' => 'px',
						),
						'hover_effect'            => array(
							'type'    => 'select',
							'label'   => __( 'Image Effect', 'uabb' ),
							'default' => 'style1',
							'options' => array(
								'style1' => __( 'Opacity', 'uabb' ),
								'style2' => __( 'Grayscale', 'uabb' ),
								'style6' => __( 'Hue Rotate', 'uabb' ),
								'simple' => __( 'Simple', 'uabb' ),
							),
							'toggle'  => array(
								'style1' => array(
									'fields' => array( 'opacity', 'hover_opacity' ),
								),
								'style2' => array(
									'fields' => array( 'img_grayscale_grayscale' ),
								),
								'simple' => array(
									'fields' => array( 'img_grayscale_simple' ),
								),
							),
						),
						'opacity'                 => array(
							'type'        => 'unit',
							'label'       => __( 'Image Opacity', 'uabb' ),
							'default'     => '100',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '100',
						),
						'hover_opacity'           => array(
							'type'        => 'unit',
							'label'       => __( 'Image Hover Opacity', 'uabb' ),
							'default'     => '100',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '100',
						),
						'img_grayscale_simple'    => array(
							'type'    => 'select',
							'label'   => __( 'Image Hover Effect', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes'        => 'Simple',
								'color_gray' => 'Grayscale on Hover',
							),
						),
						'img_grayscale_grayscale' => array(
							'type'    => 'select',
							'label'   => __( 'Image Hover Effect', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes'        => 'Simple',
								'gray_color' => 'Color on Hover',
							),
						),
					),
				),
			),
		),
	)
);
