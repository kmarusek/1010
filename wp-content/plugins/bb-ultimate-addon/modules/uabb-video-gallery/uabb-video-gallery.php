<?php
/**
 *  UABB Video Gallery Module file
 *
 *  @package UABB Video Gallery Module
 */

/**
 * Function that initializes UABB Video Gallery Module
 *
 * @class UABBVideoGallery
 */
class UABBVideoGallery extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Social Share module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Video  Gallery', 'uabb' ),
				'description'     => __( 'Video Gallery', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video-gallery/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-video-gallery/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'video-gallery.svg',
			)
		);
		$this->add_js( 'isotope', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/jquery-masonary.js', array( 'jquery' ), '', true );
		$this->add_js( 'carousel', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/jquery-carousel.js', array( 'jquery' ), '', true );
		$this->add_js( 'imagesloaded-uabb', BB_ULTIMATE_ADDON_URL . 'assets/js/global-scripts/imagesloaded.min.js', array( 'jquery' ), '', true );
		$this->add_js( 'jquery-magnificpopup' );
		$this->add_css( 'jquery-magnificpopup' );
		$this->add_css( 'font-awesome' );
	}
	/**
	 * Function to get the icon for the Video Gallery
	 *
	 * @since 1.13.0
	 * @method get_icons
	 * @param string $icon gets the icon for the module.
	 */
	public function get_icon( $icon = '' ) {

		// check if $icon is referencing an included icon.
		if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video-gallery/icon/' . $icon ) ) {
			$path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video-gallery/icon/' . $icon;
		}

		if ( file_exists( $path ) ) {
			$remove_icon = apply_filters( 'uabb_remove_svg_icon', false, 10, 1 );
			if ( true === $remove_icon ) {
				return;
			} else {
				return file_get_contents( $path );
			}
		} else {
			return '';
		}
	}
	/**
	 * Render Placeholder Image HTML.
	 *
	 * @param Array $item Current video array.
	 * @since 1.13.0
	 * @access public
	 */
	public function get_placeholder_image( $item ) {
		$url    = '';
		$vid_id = '';

		if ( 'youtube' === $item->video_type ) {
			$video_url = $item->youtube_link;
		} elseif ( 'vimeo' === $item->video_type ) {
			$video_url = $item->vimeo_link;
		}

		if ( 'youtube' === $item->video_type ) {
			if ( preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches ) ) {
				$vid_id = $matches[1];
			}
		} elseif ( 'vimeo' === $item->video_type ) {

			$vid_id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $video_url, '/' ) );
		}

		if ( 'yes' === $item->custom_placeholder ) {

			$url = $item->placeholder_image_src;

		} else {
			if ( 'youtube' === $item->video_type ) {

				$url = 'https://i.ytimg.com/vi/' . $vid_id . '/' . apply_filters( 'uabb_vg_youtube_image_quality', $item->yt_thumbnail_size ) . '.jpg';
			} elseif ( 'vimeo' === $item->video_type ) {
				if ( '' !== $vid_id && 0 !== $vid_id ) {
					$vimeo = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$vid_id.php" ) );
					$url   = $vimeo[0]['thumbnail_large'];
				}
			}
		}
		return array(
			'url'      => $url,
			'video_id' => $vid_id,
		);
	}
	/**
	 * Render Play Button.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public function get_play_button() {

		if( 'default' === $this->settings->play_source ) {
		?>
			<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="uabb-video-gallery-icon-bg" d="m .66,37.62 c 0,0 .66,4.70 2.70,6.77 2.58,2.71 5.98,2.63 7.49,2.91 5.43,.52 23.10,.68 23.12,.68 .00,-1.3e-5 14.29,-0.02 23.81,-0.71 1.32,-0.15 4.22,-0.17 6.81,-2.89 2.03,-2.07 2.70,-6.77 2.70,-6.77 0,0 .67,-5.52 .67,-11.04 l 0,-5.17 c 0,-5.52 -0.67,-11.04 -0.67,-11.04 0,0 -0.66,-4.70 -2.70,-6.77 C 62.03,.86 59.13,.84 57.80,.69 48.28,0 34.00,0 34.00,0 33.97,0 19.69,0 10.18,.69 8.85,.84 5.95,.86 3.36,3.58 1.32,5.65 .66,10.35 .66,10.35 c 0,0 -0.55,4.50 -0.66,9.45 l 0,8.36 c .10,4.94 .66,9.45 .66,9.45 z" fill="#1f1f1e"></path><path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#fff"></path><path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#ccc"></path></svg>
		<?php
		}
		else if ( 'icon' === $this->settings->play_source ) {
			?>
			<i class="<?php echo $this->settings->play_icon . ' uabb-animation-' . $this->settings->hover_animation; ?> uabb-vg__play-icon"></i>
			<?php
		} else if ( 'img' === $this->settings->play_source ) {

			$url = $this->settings->play_img_src;

			?>
				<img class="uabb-vg__play-image <?php echo 'uabb-animation-' . $this->settings->hover_animation; ?>" src="<?php echo $url; ?>" />
			<?php
		}
	}
	/**
	 * Render Tag Classes.
	 *
	 * @param Array $item Current video array.
	 * @since 1.13.0
	 * @access public
	 */
	public function get_tag_class( $item ) {
		$tags = explode( ',', $item->tags );
		$tags = array_map( 'trim', $tags );

		$tags_array = array();

		foreach ( $tags as $key => $value ) {
			$tags_array[ $this->clean( $value ) ] = $value;
		}

		return $tags_array;
	}
	/**
	 * Clean string - Removes spaces and special chars.
	 *
	 * @since 1.13.0
	 * @param String $string String to be cleaned.
	 * @return array Google Map languages List.
	 */
	public function clean( $string ) {

		// Replaces all spaces with hyphens.
		$string = str_replace( ' ', '-', $string );

		// Removes special chars.
		$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string );

		// Turn into lower case characters.
		return strtolower( $string );
	}
	/**
	 * Render Gallery Data.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public function render_gallery_inner_data() {

		$gallery     = $this->settings->form_gallery;
		$new_gallery = array();
		$href        = '';
		if ( 'rand' === $this->settings->gallery_rand ) {
			$keys = array_keys( $gallery );
			shuffle( $keys );

			foreach ( $keys as $key ) {

				$new_gallery[ $key ] = $gallery[ $key ];

			}
		} else {
			$new_gallery = $gallery;
		}

		foreach ( $new_gallery as $index => $item ) {

			if ( 'youtube' === $item->video_type ) {

				$href = $item->youtube_link;

			} elseif ( 'vimeo' === $item->video_type ) {

				$href = $item->vimeo_link;
			}

			$url = $this->get_placeholder_image( $item );

			if ( 'yes' === $this->settings->show_filter && 'grid' === $this->settings->layout ) {

				if ( '' !== $item->tags ) {

					$tags     = $this->get_tag_class( $item );
					$tags_key = implode( ' ', array_keys( $tags ) );
				}
			}
			if ( 'youtube' === $item->video_type ) {
				$vurl = 'https://www.youtube.com/embed/' . $url['video_id'] . '?autoplay=1&version=3&enablejsapi=1';
			} else {
				$vurl = 'https://player.vimeo.com/video/' . $url['video_id'] . '?autoplay=1&version=3&enablejsapi=1';
			}
			if ( 'inline' !== $this->settings->click_action ) {
					$html = '<a href="' . $href . '" data-fancybox="uabb-video-gallery" data-url="' . $vurl . '"class="uabb-video-gallery-fancybox ">';
			} else {
				if ( 'youtube' === $item->video_type ) {

					$vurl = 'https://www.youtube.com/embed/' . $url['video_id'] . '?autoplay=1&version=3&enablejsapi=1';
				} else {
					$vurl = 'https://player.vimeo.com/video/' . $url['video_id'] . '?autoplay=1&version=3&enablejsapi=1';
				}
				$html = '<a href="' . $href . '" class="uabb-clickable uabb-vg__play_full" data-url="' . $vurl . '">';
			}

			?>
			<div  class="uabb-video__gallery-item <?php echo ( isset( $tags_key ) ) ? $tags_key : '' ; ?> ">
				<div class="uabb-video__gallery-iframe" style="background-image:url('<?php echo $url['url']; ?>');">
					<?php echo $html; ?>
						<div class="uabb-video__content-wrap">
							<div class="uabb-video__content">
								<?php $this->get_caption( $item ); ?>
									<div class="uabb-vg__play <?php echo ( 'default' === $this->settings->play_source ) ? 'uabb-animation-' . $this->settings->hover_animation : '' ; ?>">
										<?php $this->get_play_button(); ?>
									</div>
									<?php $this->get_tag( $item ); ?>
							</div>
						</div>
					<?php echo '</a>'; ?>
				</div>
				<div class="uabb-vg__overlay"></div>
			</div>
			<?php
		}
	}
	/**
	 * Returns the Caption HTML.
	 *
	 * @param Array $item Current video array.
	 * @since 1.13.0
	 * @access public
	 */
	public function get_caption( $item ) {

		if ( '' == $item->title ) {
			return '';
		}
		if ( 'yes' !== $this->settings->show_caption ) {
			return '';
		}
		?>
		<h4 class="uabb-video__caption"><?php echo $item->title; ?></h4>
		<?php
	}
	/**
	 * Returns the Filter HTML.
	 *
	 * @param Array $item Current video array.
	 * @since 1.13.0
	 * @access public
	 */
	public function get_tag( $item ) {

		if ( '' == $item->tags ) {
			return '';
		}
		if ( 'yes' !== $this->settings->show_tag ) {
			return '';
		}
		?>
		<span class="uabb-video__tags"><?php echo $item->tags; ?></span>
		<?php
	}
	/**
	 * Get Filter taxonomy array.
	 *
	 * Returns the Filter array of objects.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public function get_filter_values() {

		$filters = array();

		if ( ! empty( $this->settings->form_gallery ) ) {

			foreach ( $this->settings->form_gallery as $key => $value ) {

				$tags = $this->get_tag_class( $value );

				if ( ! empty( $tags ) ) {

					$filters = array_unique( array_merge( $filters, $tags ) );
				}
			}
		}

		return $filters;
	}
	/**
	 * Get Filters.
	 *
	 * Returns the Filter HTML.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public function render_gallery_filters() {

		$filters = $this->get_filter_values();

		$filters = apply_filters( 'uabb_video_gallery_filters', $filters );
		$default = '';

		if ( 'yes' === $this->settings->default_filter_switch && '' !== $this->settings->default_filter ) {

			$default = '.' . trim( $this->settings->default_filter );
			$default = strtolower( str_replace( ' ', '-', $default ) );

		}
		?>
		<div class="uabb-video-gallery-filters-wrap">
			<?php if ( 'yes' === $this->settings->show_filter_title ) { ?>
				<div class="uabb-video-gallery-title-filters">
					<div class="uabb-video-gallery-title">
						<<?php echo $this->settings->filter_title_tag; ?> class="uabb-video-gallery-title-text"><?php echo $this->settings->filters_heading_text; ?></<?php echo $this->settings->filter_title_tag; ?>> 
					</div>
			<?php } ?>
					<ul class="uabb-video__gallery-filters" data-default="<?php echo ( isset( $default ) ) ? $default : '';
					?>">
						<li class="uabb-video__gallery-filter uabb-filter__current" data-filter="*">
							<?php echo $this->settings->filters_all_text; ?>
						</li>
						<?php foreach ( $filters as $key => $value ) { ?>
							<li class="uabb-video__gallery-filter" data-filter="<?php echo '.' . $key; ?>">
								<?php echo $value; ?>
							</li>
						<?php } ?>
					</ul>
				<?php if ( 'yes' === $this->settings->show_filter_title ) { ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
	/**
	 * Render Buttons output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.13.0
	 * @access protected
	 */
	public function render() {

		if ( 'yes' === $this->settings->show_filter && 'grid' === $this->settings->layout && 'carousel' !== $this->settings->layout ) {

			$filters = $this->get_filter_values();

			$filter_data = json_encode( array_keys( $filters ) );
				$this->render_gallery_filters();
			?>
			<div class="uabb-video-gallery-wrap uabb-video-gallery-filter uabb-vg__layout-<?php echo $this->settings->layout; ?> uabb-vg__action-<?php echo $this->settings->click_action; ?> uabb-aspect-ratio-<?php echo $this->settings->video_ratio; ?>" data-action ="<?php echo $this->settings->click_action; ?>" data-layout="<?php echo $this->settings->layout; ?>" data-all-filters=<?php echo ( isset( $filter_data ) ) ? $filter_data : '';?>>
				<?php $this->render_gallery_inner_data(); ?>
			</div>
			<?php
		} else {
			?>

			<div class="uabb-video-gallery-wrap uabb-vg__layout-<?php echo $this->settings->layout; ?> uabb-vg__action-<?php echo $this->settings->click_action; ?> uabb-aspect-ratio-<?php echo $this->settings->video_ratio; ?>" data-action ="<?php echo $this->settings->click_action; ?>" data-layout="<?php echo $this->settings->layout; ?>" data-vg_slider=<?php echo $this->get_slider_attr(); ?>>

			<?php $this->render_gallery_inner_data(); ?>

			</div>
			<?php
		}
	}
	/**
	 * Get Wrapper Classes.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public function get_slider_attr() {

		if ( 'carousel' !== $this->settings->layout ) {
			return;
		}
		$is_rtl        = is_rtl();
		$direction     = $is_rtl ? 'rtl' : 'ltr';
		$slick_options = array(
			'slidesToShow'   => ( $this->settings->gallery_columns ) ? absint( $this->settings->gallery_columns ) : 4,
			'slidesToScroll' => ( $this->settings->slides_to_scroll ) ? absint( $this->settings->slides_to_scroll ) : 1,
			'autoplaySpeed'  => ( $this->settings->autoplay_speed ) ? absint( $this->settings->autoplay_speed ) : 5000,
			'autoplay'       => ( 'yes' === $this->settings->autoplay ),
			'infinite'       => ( 'yes' === $this->settings->infinite ),
			'pauseOnHover'   => ( 'yes' === $this->settings->pause_on_hover ),
			'speed'          => ( $this->settings->transition_speed ) ? absint( $this->settings->transition_speed ) : 500,
			'arrows'         => ( 'yes' === $this->settings->enable_arrow ),
			'dots'           => ( 'yes' === $this->settings->enable_dots ),
		);

		if ( $this->settings->gallery_columns_medium || $this->settings->gallery_columns_responsive ) {

			$slick_options['responsive'] = array();
			if ( $this->settings->gallery_columns_medium ) {

				$medium_show   = absint( $this->settings->gallery_columns_medium );
				$medium_scroll = $medium_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 992,
					'settings'   => [
						'slidesToShow'   => $medium_show,
						'slidesToScroll' => $medium_scroll,
					],
				];
			}
			if ( $this->settings->gallery_columns_responsive ) {

				$responsive_show   = absint( $this->settings->gallery_columns_responsive );
				$responsive_scroll = $responsive_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 768,
					'settings'   => [
						'slidesToShow'   => $responsive_show,
						'slidesToScroll' => $responsive_scroll,
					],
				];
			}
		}
		return wp_json_encode( $slick_options );
	}

	/**
	 * Get help descriptions.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public static function get_description( $field ) {

		$style1            = 'line-height: 1em; padding-bottom:5px;';
		$style2            = 'line-height: 1em; padding-bottom:7px;';

		if ( 'youtube_link' === $field ) {
			$youtube_link_desc = sprintf(
				__( '<div style="%2$s"> Make sure you add the actual URL of the video and not the share URL.</div>
			        <div style="%1$s"><b> Valid URL : </b>  https://www.youtube.com/watch?v=HJRzUQMhJMQ</div>
			        <div style="%1$s"> <b> Invalid URL : </b> https://youtu.be/HJRzUQMhJMQ</div>',
					'uabb'
				), $style1,	$style2
			);

			return $youtube_link_desc;
			
		} elseif ( 'vimeo_link' === $field ) {
			
			$vimeo_link_desc   = sprintf(
				__( '<div style="%1$s">Make sure you add the actual URL of the video and not the categorized URL.</div>
			        <div style="%1$s"><b> Valid URL : </b>  https://vimeo.com/274860274</div>
			        <div style="%1$s"> <b> Invalid URL : </b> https://vimeo.com/channels/staffpicks/274860274</div>',
					'uabb'
				), $style1
			);

			return $vimeo_link_desc;
		}
	}
}
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'UABBVideoGallery',
	array(
		'video_gallery'             => array(
			'title'    => __( 'Video', 'uabb' ),
			'sections' => array(
				'gallery'                => array(
					'title'  => __( 'Gallery', 'uabb' ),
					'fields' => array(
						'form_gallery' => array(
							'type'         => 'form',
							'label'        => __( 'Video', 'uabb' ),
							'form'         => 'uabb_video_gallery_form',
							'multiple'     => true,
							'preview_text' => 'title',
							'default'	   =>array(
								array(
									'video_type' => 'youtube',
									'youtube_link' =>'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
									'title'		   =>'First Video',
									'tags'		   =>'YouTube'
								),
								array(
									'video_type' => 'vimeo',
									'youtube_link' =>'https://vimeo.com/274860274',
									'title'		   =>'Second Video',
									'tags'		   =>'Vimeo'
								),
								array(
									'video_type' => 'youtube',
									'youtube_link' =>'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
									'title'		   =>'Third Video',
									'tags'		   =>'YouTube'
								),
								array(
									'video_type' => 'vimeo',
									'youtube_link' =>'https://vimeo.com/274860274',
									'title'		   =>'Fourth Video',
									'tags'		   =>'Vimeo'

								),
								array(
									'video_type' => 'youtube',
									'youtube_link' =>'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
									'title'		   =>'Fifth Video',
									'tags'		   =>'YouTube'

								),
								array(
									'video_type' => 'vimeo',
									'youtube_link' =>'https://vimeo.com/274860274',
									'title'		   =>'Sixth Video',
									'tags'		   =>'Vimeo'
								),
							),
						),
					),
				),
				'general'                => array(
					'title'  => __( 'General', 'uabb' ),
					'fields' => array(
						'layout'          => array(
							'type'    => 'select',
							'label'   => __( 'Layout', 'uabb' ),
							'default' => 'grid',
							'options' => array(
								'grid'     => __( 'Grid', 'uabb' ),
								'carousel' => __( 'Carousel', 'uabb' ),
							),
							'toggle'  => array(
								'grid'     => array(
									'sections' => array( 'section_filter_content' ),
								),
								'carousel' => array(
									'sections' => array( 'section_slider_options' ),
								),
							),
						),
						'gallery_columns' => array(
							'type'       => 'unit',
							'label'      => __( 'Columns', 'uabb' ),
							'maxlength'  => '5',
							'size'       => '6',
							'responsive' => array(
								'default' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
						),
						'video_ratio'     => array(
							'type'    => 'select',
							'label'   => __( 'Aspect Ratio', 'uabb' ),
							'default' => '16_9',
							'options' => array(
								'16_9' => __( '16:9', 'uabb' ),
								'4_3'  => __( '4:3', 'uabb' ),
								'3_2'  => __( '3:2', 'uabb' ),
							),
						),
						'click_action'    => array(
							'type'    => 'select',
							'label'   => __( 'Click Action', 'uabb' ),
							'default' => 'lightbox',
							'options' => array(
								'lightbox' => __( 'Play in Lightbox', 'uabb' ),
								'inline'   => __( 'Play Inline', 'uabb' ),
							),
						),
						'gallery_rand'    => array(
							'type'    => 'select',
							'label'   => __( 'Ordering', 'uabb' ),
							'default' => '',
							'options' => array(
								''     => __( 'Default', 'uabb' ),
								'rand' => __( 'Random', 'uabb' ),
							),
						),
					),
				),
				'section_filter_content' => array(
					'title'  => __( 'Filterable Tabs', 'uabb' ),
					'fields' => array(
						'show_filter'           => array(
							'type'    => 'select',
							'label'   => __( 'Filterable Video Gallery', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'filters_all_text', 'default_filter_switch', 'show_filter_title' ),
									'sections' => array( 'section_typography_cat_filters' ),
								),
							),
						),
						'filters_all_text'      => array(
							'type'        => 'text',
							'label'       => __( '"All" Tab Label', 'uabb' ),
							'default'     => __( 'All', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
						'default_filter_switch' => array(
							'type'    => 'select',
							'label'   => __( 'Default Tab on Page Load', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'no'  => __( 'First', 'uabb' ),
								'yes' => __( 'Custom', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'default_filter' ),
								),
							),
						),
						'default_filter'        => array(
							'type'        => 'text',
							'label'       => __( 'Enter Category Name', 'uabb' ),
							'help'        => __( 'Enter the category name that you wish to set as a default on page load.', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
						'show_filter_title'     => array(
							'type'    => 'select',
							'label'   => __( 'Title for Filterable Tab', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'filters_heading_text' ),
									'sections' => array( 'section_title_typography' ),
								),
							),
						),
						'filters_heading_text'  => array(
							'type'        => 'text',
							'label'       => __( 'Title Text', 'uabb' ),
							'default'     => __( 'My Videos', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
					),
				),
				'section_slider_options' => array(
					'title'  => __( 'Carousel', 'uabb' ),
					'fields' => array(
						'slides_to_scroll' =>array(
							'type'			=>'unit',
							'label'			=>__('Slides To Scroll','uabb'),
							'default'		=> '1',
							'maxlength'  	=> '5',
							'size'       	=> '6',
						),
						'enable_dots'      => array(
							'type'    => 'select',
							'label'   => __( 'Enable Dots', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'dots_size','dots_color' ),
								),
							),
						),
						'enable_arrow'     => array(
							'type'    => 'select',
							'label'   => __( 'Enable arrow', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'arrows_size','arrows_color','arrow_border_radius','arrows_border_size' ),
								),
							),
						),
						'autoplay'         => array(
							'type'    => 'select',
							'label'   => __( 'Autoplay', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'autoplay_speed', 'pause_on_hover' ),
								),
							),
						),
						'autoplay_speed'   => array(
							'type'    => 'unit',
							'label'   => __( 'Autoplay Speed', 'uabb' ),
							'default' => '5000',
							'description'=>__('ms','uabb'),
						),
						'pause_on_hover'   => array(
							'type'    => 'select',
							'label'   => __( 'Pause on Hover', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
						'infinite'         => array(
							'type'    => 'select',
							'label'   => __( 'Infinite Loop', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
						'transition_speed' => array(
							'type'    => 'unit',
							'label'   => __( 'Transition Speed', 'uabb' ),
							'default' => '500',
							'description'=>__('ms','uabb'),
						),
					),
				),
			),
		),
		'section_design_layout'     => array(
			'title'    => __( 'Style', 'uabb' ),
			'sections' => array(
				'spacing_issue'               => array(
					'title'  => __( 'Spacing', 'uabb' ),
					'fields' => array(
						'column_gap' => array(
							'type'        => 'unit',
							'label'       => __( 'Columns Gap', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'default' => array(
									'default'    => '10',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'row_gap'    => array(
							'type'        => 'unit',
							'label'       => __( 'Rows Gap', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'default' => array(
									'default'    => '10',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
					),
				),
				'section_style_title_filters' => array(
					'title'  => __( 'Title', 'uabb' ),
					'fields' => array(
						'filter_title_color'        => array(
							'type'        => 'color',
							'label'       => __( 'Title Color', 'uabb' ),
							'show_alpha'  => 'true',
							'show_resset' => 'true',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-title-text',
								'property' => 'color',
							),
						),
						'filters_tab_heading_stack' => array(
							'type'    => 'select',
							'label'   => __( 'Stack On', 'uabb' ),
							'default' => 'mobile',
							'options' => array(
								'none'   => __( 'None', 'uabb' ),
								'tablet' => __( 'Tablet', 'uabb' ),
								'mobile' => __( 'Mobile', 'uabb' ),
							),
							'help'    => __( 'Choose at what breakpoint the Title & Filter Tabs will stack.', 'uabb' ),
						),
					),
				),
				'section_style_cat_filters'   => array(
					'title'  => __( 'Filterable Tabs', 'uabb' ),
					'fields' => array(
						'cat_filter_align'               => array(
							'type'    => 'select',
							'label'   => __( 'Tab Alignment', 'uabb' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filters',
								'property' => 'text-align',
							),
						),
						'cat_filter_padding'             => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'default' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							)
						),
						'cat_filter_bet_spacing'         => array(
							'type'        => 'unit',
							'label'       => __( 'Spacing Between Tabs', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'default' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),

						),
						'cat_filter_spacing'             => array(
							'type'        => 'unit',
							'label'       => __( 'Tabs Bottom Spacing', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'default' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'cat_filter_color'               => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'uabb' ),
							'show_alpha' => 'true',
							'show_reset' => 'true',
							'default'    => '',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'color',
							),
						),
						'cat_filter_hover_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Text Active / Hover', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter.uabb-filter__current',
								'property' => 'color',
							),
						),
						'cat_filter_bg_color_border'     => array(
							'type'    => 'select',
							'label'   => __( 'Background Color / Border ', 'uabb' ),
							'default' => 'background_color',
							'options' => array(
								'background_color' => __( 'Background Color', 'uabb' ),
								'border'           => __( 'Bottom Border', 'uabb' ),
							),
							'toggle'  => array(
								'background_color' => array(
									'fields' => array( 'cat_filter_bg_color', 'cat_filter_bg_hover_color' ),
								),
								'border'           => array(
									'fields' => array( 'cat_filter_border_type', 'cat_filter_border', 'cat_filter_border_color', 'cat_filter_border_active_type', 'cat_filter_border_active', 'cat_filter_border_color_active' ),
								),
							),
						),
						'cat_filter_bg_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'uabb' ),
							'show_reset' => 'true',
							'default'    => '',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'background',
							),
						),
						'cat_filter_bg_hover_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Background Active / Hover Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'default'    => '00b524',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter.uabb-filter__current',
								'property' => 'background-color',
							),
						),
						'cat_filter_border_type'         => array(
							'type'    => 'select',
							'label'   => __( 'Bottom Border Type', 'uabb' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'uabb' ),
								'solid'  => __( 'Solid', 'uabb' ),
								'dashed' => __( 'Dashed', 'uabb' ),
								'dotted' => __( 'Dotted', 'uabb' ),
								'double' => __( 'Double', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filters',
								'property' => 'border-bottom-style',
							),
						),
						'cat_filter_border'              => array(
							'type'    => 'unit',
							'label'   => __( 'Bottom Border width', 'uabb' ),
							'default' => '',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filters',
								'property' => 'border-bottom-width',
								'unit'     => 'px',
							),
						),
						'cat_filter_border_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Bottom Border Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filters',
								'property' => 'border-color',
							),
						),
						'cat_filter_border_active_type'  => array(
							'type'    => 'select',
							'label'   => __( 'Active Bottom Border Type', 'uabb' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'uabb' ),
								'solid'  => __( 'Solid', 'uabb' ),
								'dashed' => __( 'Dashed', 'uabb' ),
								'dotted' => __( 'Dotted', 'uabb' ),
								'double' => __( 'Double', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter:hover,.uabb-video__gallery-filter.uabb-filter__current',
								'property' => 'border-bottom-style',
							),
						),
						'cat_filter_border_active'       => array(
							'type'    => 'unit',
							'label'   => __( 'Active Bottom Border Width ', 'uabb' ),
							'default' => '',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter:hover,.uabb-video__gallery-filter.uabb-filter__current',
								'property' => 'border-bottom-width',
								'unit'     => 'px',
							),
						),
						'cat_filter_border_color_active' => array(
							'type'       => 'color',
							'label'      => __( ' Active Bottom Border Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter:hover,.uabb-video__gallery-filter.uabb-filter__current',
								'property' => 'border-color',
							),
						),
					),
				),
				'section_design_play'         => array(
					'title'  => __( 'Play Button', 'uabb' ),
					'fields' => array(
						'play_source'           => array(
							'type'    => 'select',
							'label'   => __( 'Image/Icon', 'uabb' ),
							'default' => 'default',
							'options' => array(
								'default'=>__('Default','uabb'),
								'img'  => __( 'Image', 'uabb' ),
								'icon' => __( 'Icon', 'uabb' ),
							),
							'toggle'  => array(
								'img'  => array(
									'fields' => array( 'play_img' ),
								),
								'icon' => array(
									'fields' => array( 'play_icon', 'play_icon_color', 'play_icon_hover_color' ),
								),
								'default'=>array(
									'fields' => array( 'play_icon_color', 'play_icon_hover_color' ),
								),
							),
						),
						'play_img'              => array(
							'type'        => 'photo',
							'label'       => __( 'Select Image', 'uabb' ),
							'show_reset'  => 'true',
							'connections' => array( 'photo' ),
						),
						'play_icon'             => array(
							'type'       => 'icon',
							'label'      => __( 'Select Icon', 'uabb' ),
							'default'    => 'far fa-play-circle',
							'show_reset' => 'true',
						),
						'play_icon_size'        => array(
							'type'        => 'unit',
							'label'       => __( 'Size', 'uabb' ),
							'default'     => '60',
							'description' => 'px',
							'responsive'  => array(
								'default' => array(
									'default'    => '60',
									'medium'     => '',
									'responsive' => '',
								),
							),

						),
						'hover_animation'       => array(
							'type'    => 'select',
							'label'   => __( 'Hover Animation', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'None', 'uabb' ),
								'sink'       => __( 'Sink', 'uabb' ),
								'float'      => __( 'Float', 'uabb' ),
								'pulse-grow' => __( 'Pulse-Grow', 'uabb' ),
								'shrink'     => __( 'Shrink', 'uabb' ),
								'pulse'      => __( 'Pulse', 'uabb' ),
							),
						),
						'play_icon_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'default'    => '',
							'preview'    => array(
								'type'     => 'css',
								'rules' => array(
									array(
										'selector' => '.uabb-video__content i,.uabb-vg__play i.uabb-vg__play-icon',
										'property' => 'color',
									),
									array(
										'selector' => '.uabb-video-gallery-icon-bg',
										'property' => 'fill',
									),
								), 
							),
						),
						'play_icon_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type' => 'none',
							),
						),
					),
				),
				'section_design_caption'      => array(
					'title'  => __( 'Content', 'uabb' ),
					'fields' => array(
						'overlay_background_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-vg__overlay',
								'property' => 'background-color',
							),
						),
						'overlay_background_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Hover Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type' => 'none',
							),
						),
						'show_caption'                   => array(
							'type'    => 'select',
							'label'   => __( 'Show Caption on Hover', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'caption_color', 'caption_font', 'caption_font_size_unit', 'caption_line_height_unit', 'caption_letter_spacing', 'caption_transform' ),
									'sections' => array( 'section_typography_caption' ),
								),
							),
						),
						'caption_color'                  => array(
							'type'       => 'color',
							'label'      => __( 'Caption Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__caption',
								'property' => 'color',
							),
						),
						'show_tag'                       => array(
							'type'    => 'select',
							'label'   => __( 'Show Category on Hover', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'tag_color', 'tag_font', 'tag_font_size_unit', 'tag_line_height_unit', 'tag_letter_spacing', 'tag_transform' ),
									'sections' => array( 'section_typography_tag' ),
								),
							),
						),
						'tag_color'                      => array(
							'type'       => 'color',
							'label'      => __( 'Category Color', 'uabb' ),
							'default'    => '',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__tags',
								'property' => 'color',
							),
						),
					),
				),
				'section_style_navigation'    => array(
					'title'  => __( 'Navigation', 'uabb' ),
					'fields' => array(
						'arrows_size'         => array(
							'type'        => 'unit',
							'label'       => __( 'Arrows Size', 'uabb' ),
							'description' => 'px',
						),
						'arrows_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Arrows Color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'rules' => array(
									array(
										'selector' => '.uabb-video-gallery-wrap.slick-slider .slick-arrow i',
										'property' => 'color',
									),
									array(
										'selector' => '.uabb-video-gallery-wrap.slick-slider .slick-arrow',
										'property' => 'border-color',
									),
								), 
							),
						),
						'arrows_border_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Arrows Border Size', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-wrap.slick-slider .slick-arrow',
								'property' => 'border-width',
								'unit'     => 'px',
							),
						),
						'arrow_border_radius' => array(
							'type'        => 'unit',
							'label'       => __( 'Border Radius', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-wrap.slick-slider .slick-arrow',
								'property' => 'border-radius',
								'unit'     => 'px',
							),
						),
						'dots_size'             =>array(
							'type'              =>'unit',
							'label'             =>__('Dots Size', 'uabb'),
							'description'       =>'px',
							'preview'   =>array(
								'type'      =>'css',
								'selector'  =>'.uabb-video-gallery-wrap .slick-dots li button:before',
								'property'  =>'font-size',
								'unit'      =>'px',
							),
						),
						'dots_color'            =>array(
							'type'              =>'color',
							'label'             =>__('Dots Color', 'uabb'),
							'show_alpha'        =>'true',
							'show_reset'        =>'true',
							'preview'   =>array(
								'type'      =>'css',
								'selector'  =>'.uabb-video-gallery-wrap .slick-dots li button:before,.uabb-video-gallery-wrap ul.slick-dots li.slick-active button:before',
								'property'  =>'color',
							),
						),
					),
				),
			),
		),
		'section_Typography_layout' => array(
			'title'    => __( 'Typography', 'uabb' ),
			'sections' => array(
				'section_title_typography'       => array(
					'title'  => __( 'Title', 'uabb' ),
					'fields' => array(
						'filter_title_tag'              => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'uabb' ),
							'default' => 'h3',
							'options' => array(
								'h1'  => __( 'H1', 'uabb' ),
								'h2'  => __( 'H2', 'uabb' ),
								'h3'  => __( 'H3', 'uabb' ),
								'h4'  => __( 'H4', 'uabb' ),
								'h5'  => __( 'H5', 'uabb' ),
								'h6'  => __( 'H6', 'uabb' ),
								'div' => __( 'div', 'uabb' ),
								'p'   => __( 'p', 'uabb' ),
							),
						),
						'filter_title_font'             => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-video-gallery-title-text',
							),
						),
						'filter_title_font_size_unit'   => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-title-text',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'filter_title_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line height', 'uabb' ),
							'description' => 'em',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-title-text',
								'property' => 'line-height',
								'unit'     => 'em',
							),
						),
						'filter_title_letter_spacing'   => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-title-text',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
						'filter_title_transform'        => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video-gallery-title-text',
								'property' => 'text-transform',
							),
						),
					),
				),
				'section_typography_cat_filters' => array(
					'title'  => __( 'Filterable Tabs', 'uabb' ),
					'fields' => array(
						'cat_font'                 => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-video__gallery-filter',
							),
						),
						'cat_font_size_unit'       => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'cat_line_height_unit'     => array(
							'type'        => 'unit',
							'label'       => __( 'Line height', 'uabb' ),
							'description' => 'em',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'line-height',
								'unit'     => 'em',
							),
						),
						'cat_title_letter_spacing' => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
						'cat_title_transform'      => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__gallery-filter',
								'property' => 'text-transform',
							),
						),
					),
				),
				'section_typography_caption'     => array(
					'title'  => __( 'Caption', 'uabb' ),
					'fields' => array(
						'caption_font'             => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-video__caption',
							),
						),
						'caption_font_size_unit'   => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__caption',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'caption_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line Height', 'uabb' ),
							'description' => 'em',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__caption',
								'property' => 'line-height',
								'unit'     => 'em',
							),
						),
						'caption_letter_spacing'   => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__caption',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
						'caption_transform'        => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__caption',
								'property' => 'text-transform',
							),
						),
					),
				),
				'section_typography_tag'         => array(
					'title'  => __( 'Category', 'uabb' ),
					'fields' => array(
						'tag_font'             => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-video__tags',
							),
						),
						'tag_font_size_unit'   => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__tags',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'tag_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line Height', 'uabb' ),
							'description' => 'em',
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__tags',
								'property' => 'line-height',
								'unit'     => 'em',
							),
						),
						'tag_letter_spacing'   => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-video__tags',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
						'tag_transform'        => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-video__tags',
								'property' => 'text-transform',
							),
						),
					),
				),
			),
		),
	)
);

// Add Video Gallery Items.
FLBuilder::register_settings_form(
	'uabb_video_gallery_form',
	array(
		'title' => __( 'Add Gallery Item', 'uabb' ),
		'tabs'  => array(
			'video_gallery_item' => array(
				'title'    => __( 'General', 'uabb' ),
				'sections' => array(
					'video_gallery_basic' => array(
						'title'  => __( 'Video Gallery', 'uabb' ),
						'fields' => array(
							'video_type'         => array(
								'type'    => 'select',
								'label'   => __( 'Video Type', 'uabb' ),
								'default' => 'youtube',
								'options' => array(
									'youtube' => __( 'YouTube', 'uabb' ),
									'vimeo'   => __( 'Vimeo', 'uabb' ),
								),
								'toggle'  => array(
									'youtube' => array(
										'fields' => array( 'youtube_link', 'yt_thumbnail_size' ),
									),
									'vimeo'   => array(
										'fields' => array( 'vimeo_link' ),
									),
								),
							),
							'youtube_link'       => array(
								'type'        => 'text',
								'label'       => __( 'Link', 'uabb' ),
								'default'     => 'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
								'connections' => array( 'url' ),
								'description' => UABBVideoGallery::get_description( 'youtube_link' ),
							),
							'vimeo_link'         => array(
								'type'        => 'text',
								'label'       => __( 'Link', 'uabb' ),
								'default'     => 'https://vimeo.com/274860274',
								'connections' => array( 'url' ),
								'description' => UABBVideoGallery::get_description( 'vimeo_link' ),
							),
							'title'              => array(
								'type'        => 'text',
								'label'       => __( 'Caption', 'uabb' ),
								'default'     => 'First Video',
								'help'        => __( 'This title will be visible on hover.', 'uabb' ),
								'connections' => array( 'string', 'html' ),
							),
							'tags'               => array(
								'type'        => 'text',
								'label'       => __( 'Categories', 'uabb' ),
								'default'     => 'YouTube',
								'help'        => __( 'Add comma separated categories. These categories will be shown for filteration.', 'uabb' ),
								'connections' => array( 'string', 'html' ),
							),
							'custom_placeholder' => array(
								'type'    => 'select',
								'label'   => __( 'Thumbnail', 'uabb' ),
								'default' => 'no',
								'options' => array(
									'yes' => __( 'Custom Thumbnail', 'uabb' ),
									'no'  => __( 'Default Thumbnail', 'uabb' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'placeholder_image' ),
									),
									'no'  => array(
										'fields' => array( 'yt_thumbnail_size' ),
									),
								),
							),
							'yt_thumbnail_size'  => array(
								'type'    => 'select',
								'label'   => __( 'Thumbnail Size', 'uabb' ),
								'default' => 'hqdefault',
								'options' => array(
									'maxresdefault' => __( 'Maximum Resolution', 'uabb' ),
									'hqdefault'     => __( 'High Quality', 'uabb' ),
									'mqdefault'     => __( 'Medium Quality', 'uabb' ),
									'sddefault'     => __( 'Standard Quality', 'uabb' ),
								),
							),
							'placeholder_image'  => array(
								'type'        => 'photo',
								'label'       => __( 'Select Image', 'uabb' ),
								'show_remove' => true,
								'connections' => array( 'photo' ),
							),
						),
					),
				),
			),
		),
	)
);
