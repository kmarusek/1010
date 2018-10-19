<?php
/**
 *  UABB Video file
 *
 *  @package UABB Video Module
 */

/**
 * Function that initializes UABB Video Module
 *
 * @class UABBVideo
 */
class UABBVideo extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Video module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Video', 'uabb' ),
				'description'     => __( 'Video', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-video/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
				'icon'            => 'video-player.svg',
			)
		);
	}
	/**
	 * Function to get the icon for the Info Circle
	 *
	 * @since 1.11.0
	 * @method get_icons
	 * @param string $icon gets the icon for the module.
	 */
	public function get_icon( $icon = '' ) {
		// check if $icon is referencing an included icon.
		if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video/icon/' . $icon ) ) {
			$path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-video/icon/' . $icon;
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
	 * Returns Video ID.
	 *
	 * @since 1.11.0
	 * @access public
	 */
	public function get_video_id() {

		$id         = '';
		$video_type = $this->settings->video_type;

		if ( 'youtube' == $video_type ) {
			$url = $this->settings->youtube_link;
			if ( preg_match( '~^(?:https?://)? (?:www[.])?(?:youtube[.]com/watch[?]v=|youtu[.]be/)([^&]{11})~x', $url ) ) {
				if ( preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches ) ) {
					$id = $matches[1];
				}
			}
		} elseif ( 'vimeo' == $video_type ) {
			$url = $this->settings->vimeo_link;
			if ( preg_match( '/https?:\/\/(?:www\.)?vimeo\.com\/\d{8}/', $url ) ) {
				$id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );
			}
		}

		return $id;
	}
	/**
	 * Returns Video URL.
	 *
	 * @param array  $params Video Param array.
	 * @param string $id Video ID.
	 * @since 1.11.0
	 * @access public
	 */
	public function get_url( $params, $id ) {

		if ( 'vimeo' == $this->settings->video_type ) {
			$url = 'https://player.vimeo.com/video/';
		} else {
			$cookie = '';

			if ( 'yes' == $this->settings->yt_privacy ) {
				$cookie = '-nocookie';
			}
			$url = 'https://www.youtube' . $cookie . '.com/embed/';
		}

		$url = add_query_arg( $params, $url . $this->get_video_id() );

		$url .= ( empty( $params ) ) ? '?' : '&';

		$url .= 'autoplay=1';

		if ( 'vimeo' == $this->settings->video_type && '' != $this->settings->start ) {
			$time = date( 'H\hi\ms\s', $this->settings->start );

			$url .= '#t=' . $time;
		}
		return $url;
	}
	/**
	 * Returns Video Thumbnail Image.
	 *
	 * @param string $id Video ID.
	 * @since 1.11.0
	 * @access public
	 */
	public function get_video_thumb( $id ) {
		$id = $this->get_video_id();
		if ( '' == $this->get_video_id() ) {
			return '';
		}
		if ( 'yes' == $this->settings->show_image_overlay ) {
			$thumb = $this->settings->image_overlay_src;
		} else {
			if ( 'youtube' == $this->settings->video_type ) {
				$thumb = 'https://i.ytimg.com/vi/' . $id . '/' . apply_filters( 'uabb_video_youtube_image_quality', $this->settings->yt_thumbnail_size ) . '.jpg';
			} else {
				$vimeo = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$id.php" ) );
				$thumb = str_replace( '_640', '_840', $vimeo[0]['thumbnail_large'] );
			}
		}
		return $thumb;
	}

	/**
	 * Returns Vimeo Headers.
	 *
	 * @param string $id Video ID.
	 * @since 1.11.0
	 * @access public
	 */
	public function get_header_wrap( $id ) {
		if ( 'vimeo' != $this->settings->video_type ) {
			return;
		}
		$id = $this->get_video_id();
		if ( isset( $id ) && '' != $id ) {
			if ( 'yes' == $this->settings->vimeo_portrait ||
			'yes' == $this->settings->vimeo_title ||
			'yes' == $this->settings->vimeo_byline
			) {
				$vimeo = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$id.php" ) );
				?>
			<div class="uabb-vimeo-wrap">
				<?php if ( 'yes' == $this->settings->vimeo_portrait ) { ?>
				<div class="uabb-vimeo-portrait">
					<a href="<?php $vimeo[0]['user_url']; ?>"><img src="<?php echo $vimeo[0]['user_portrait_huge']; ?>"></a>
				</div>
			<?php } ?>
				<?php
				if ( 'yes' == $this->settings->vimeo_title ||
				'yes' == $this->settings->vimeo_byline
				) {
					?>
				<div class="uabb-vimeo-headers">
					<?php if ( 'yes' == $this->settings->vimeo_title ) { ?>
						<div class="uabb-vimeo-title">
							<a href="<?php $this->settings->vimeo_link; ?>"><?php echo $vimeo[0]['title']; ?></a>
						</div>
					<?php } ?>
					<?php if ( 'yes' == $this->settings->vimeo_byline ) { ?>
						<div class="uabb-vimeo-byline">
							<?php _e( 'from ', 'uabb' ); ?> <a href="<?php $this->settings->vimeo_link; ?>"><?php echo $vimeo[0]['user_name']; ?></a>
						</div>
					<?php } ?>
				</div>
				<?php } ?>
				</div>
			<?php } ?>
			<?php
		}
	}
	/**
	 * Render Video.
	 *
	 * @since 1.11.0
	 * @access public
	 */
	public function get_video_embed() {
		$id          = $this->get_video_id();
		$embed_param = $this->get_embed_params();
		$src         = $this->get_url( $embed_param, $id );

		$device = ( false !== ( stripos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ) ? 'true' : 'false' );

		if ( 'youtube' == $this->settings->video_type ) {
			$autoplay = ( 'yes' == $this->settings->yt_autoplay ) ? '1' : '0';
		} else {
			$autoplay = ( 'yes' == $this->settings->vimeo_autoplay ) ? '1' : '0';
		}
		if ( 'default' == $this->settings->play_source ) {
			if ( 'youtube' == $this->settings->video_type ) {
				$html = '<svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="uabb-youtube-icon-bg" d="m .66,37.62 c 0,0 .66,4.70 2.70,6.77 2.58,2.71 5.98,2.63 7.49,2.91 5.43,.52 23.10,.68 23.12,.68 .00,-1.3e-5 14.29,-0.02 23.81,-0.71 1.32,-0.15 4.22,-0.17 6.81,-2.89 2.03,-2.07 2.70,-6.77 2.70,-6.77 0,0 .67,-5.52 .67,-11.04 l 0,-5.17 c 0,-5.52 -0.67,-11.04 -0.67,-11.04 0,0 -0.66,-4.70 -2.70,-6.77 C 62.03,.86 59.13,.84 57.80,.69 48.28,0 34.00,0 34.00,0 33.97,0 19.69,0 10.18,.69 8.85,.84 5.95,.86 3.36,3.58 1.32,5.65 .66,10.35 .66,10.35 c 0,0 -0.55,4.50 -0.66,9.45 l 0,8.36 c .10,4.94 .66,9.45 .66,9.45 z" fill="#1f1f1e"></path><path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#fff"></path><path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#ccc"></path></svg>';
			}
			if('vimeo' === $this->settings->video_type ){
				$html = '<svg version="1.1" height="100%" width="100%"  viewBox="0 14.375 95 66.25"><path class="uabb-vimeo-icon-bg" d="M12.5,14.375c-6.903,0-12.5,5.597-12.5,12.5v41.25c0,6.902,5.597,12.5,12.5,12.5h70c6.903,0,12.5-5.598,12.5-12.5v-41.25c0-6.903-5.597-12.5-12.5-12.5H12.5z"/><polygon fill="#FFFFFF" points="39.992,64.299 39.992,30.701 62.075,47.5 "/></svg>';
			}
		} elseif ( 'icon' == $this->settings->play_source ) {
			$html = '';
		} else {
			$thumb = $this->settings->play_img_src;
			$html  = '<img src="' . $thumb . '" />';
		}
		?>
		<div class="uabb-video uabb-aspect-ratio-<?php echo $this->settings->aspect_ratio; ?>  uabb-subscribe-responsive-<?php echo $this->settings->subscribe_bar_responsive; ?>">
			<div class="uabb-video__outer-wrap" data-autoplay="<?php echo $autoplay; ?>" data-device="<?php echo $device; ?>">
				<?php $this->get_header_wrap( $id ); ?>
				<div class="uabb-video__play" data-src="<?php echo $src; ?>">
					<img class="uabb-video__thumb" src="<?php echo $this->get_video_thumb( $id ); ?>"/>
					<div class="uabb-video__play-icon <?php echo ( 'icon' == $this->settings->play_source ) ? $this->settings->play_icon : ''; ?> uabb-animation-<?php echo $this->settings->hover_animation; ?>">
						<?php echo $html; ?>
					</div>
				</div>
			</div>
			<?php
			if ( 'youtube' == $this->settings->video_type && 'yes' == $this->settings->yt_subscribe_enable ) {
				$channel_name = ( '' != $this->settings->yt_channel_name ) ? $this->settings->yt_channel_name : '';

				$channel_id = ( '' != $this->settings->yt_channel_id ) ? $this->settings->yt_channel_id : '';

				$youtube_text = ( '' != $this->settings->yt_subscribe_text ) ? $this->settings->yt_subscribe_text : '';

				$subscriber_count = ( 'yes' == $this->settings->show_count ) ? 'default' : 'hidden';
				?>
			<div class="uabb-subscribe-bar">
				<div class="uabb-subscribe-bar-prefix"><?php echo $youtube_text; ?></div>
				<div class="uabb-subscribe-content">
					<script src="https://apis.google.com/js/platform.js"></script> <!-- Need to be enqueued from someplace else -->
				<?php if ( 'channel_name' == $this->settings->select_options ) { ?>
					<div class="g-ytsubscribe" data-channel="<?php echo $channel_name; ?>" data-count="<?php echo $subscriber_count; ?>"></div>
				<?php } elseif ( 'channel_id' == $this->settings->select_options ) { ?>
					<div class="g-ytsubscribe" data-channelid="<?php echo $channel_id; ?>" data-count="<?php echo $subscriber_count; ?>"></div>
				<?php } ?>
				</div>
			</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	/**
	 * Render Video output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.11.0
	 * @access public
	 */
	public function render() {
		if ( '' == $this->settings->youtube_link && 'youtube' == $this->settings->video_type ) {
			return '';
		}
		if ( '' == $this->settings->vimeo_link && 'vimeo' == $this->settings->video_type ) {
			return '';
		}
		$this->get_video_embed();
	}

	/**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params() {
		$params = array();
		if ( 'youtube' === $this->settings->video_type ) {
			$youtube_options = array( 'autoplay', 'rel', 'controls', 'showinfo', 'mute', 'modestbranding' );
			foreach ( $youtube_options as $option ) {
				if ( 'autoplay' == $option ) {
					if ( 'yes' === $this->settings->yt_autoplay ) {
						$params[ $option ] = '1';
					}
					continue;
				}
				if ( 'rel' == $option ) {
					$value             = ( 'yes' === $this->settings->yt_suggested ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'controls' == $option ) {
					$value             = ( 'yes' === $this->settings->yt_controls ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'showinfo' == $option ) {
					$value             = ( 'yes' === $this->settings->yt_showinfo ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'mute' == $option ) {
					$value             = ( 'yes' === $this->settings->yt_mute ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'modestbranding' == $option ) {
					$value             = ( 'yes' === $this->settings->yt_modestbranding ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				$params['start'] = $this->settings->start;
				$params['end']   = $this->settings->end;
			}
		}
		if ( 'vimeo' === $this->settings->video_type ) {
			$vimeo_options = array( 'autoplay', 'loop', 'title', 'portrait', 'byline' );

			foreach ( $vimeo_options as $option ) {
				if ( 'autoplay' == $option ) {
					if ( 'yes' === $this->settings->vimeo_autoplay ) {
						$params[ $option ] = '1';
					}
					continue;
				}
				if ( 'loop' === $option ) {
					$value             = ( 'yes' === $this->settings->vimeo_loop ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'title' === $option ) {
					$value             = ( 'yes' === $this->settings->vimeo_title ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'portrait' === $option ) {
					$value             = ( 'yes' === $this->settings->vimeo_portrait ) ? '1' : '0';
					$params[ $option ] = $value;
				}
				if ( 'byline' === $option ) {
					$value             = ( 'yes' === $this->settings->vimeo_byline ) ? '1' : '0';
					$params[ $option ] = $value;
				}
			}
			$params['color']     = str_replace( '#', '', $this->settings->vimeo_color );
			$params['autopause'] = '0';
		}
		return $params;
	}

	/**
	 * Get help descriptions.
	 *
	 * @since 1.13.0
	 * @access public
	 */
	public static function get_description( $field ) {

		$style1 = 'line-height: 1em; padding-bottom:5px;';
		$style2 = 'line-height: 1em; padding-bottom:7px;';

		if ( 'youtube_link' === $field ) {

			$youtube_link_desc=sprintf(
			__( '<div style="%2$s"> Make sure you add the actual URL of the video and not the share URL.</div>
				<div style="%1$s"><b> Valid URL : </b>  https://www.youtube.com/watch?v=HJRzUQMhJMQ</div>
				<div style="%1$s"> <b> Invalid URL : </b> https://youtu.be/HJRzUQMhJMQ</div>', 'uabb' ), $style1, $style2
			);

			return $youtube_link_desc;
			
		} elseif ( 'vimeo_link' === $field ) {
			
			$vimeo_link_desc=sprintf(
			__( '<div style="%1$s">Make sure you add the actual URL of the video and not the share URL.</div>
				<div style="%1$s"><b> Valid URL : </b>  https://vimeo.com/274860274</div>
				<div style="%1$s"> <b> Invalid URL : </b> https://vimeo.com/channels/staffpicks/274860274</div>', 'uabb' ),$style1
			);

			return $vimeo_link_desc;
		}

		$branding_name       = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-name' );
		$branding_short_name = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-short-name' );

		if ( empty( $branding_name ) && empty( $branding_short_name ) ) {

			if ( 'yt_channel_id' === $field ) {

				$youtube_channel_id = sprintf( __( 'Click <a href="https://www.ultimatebeaver.com/docs/how-to-find-youtube-channel-name-and-channel-id/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to know how to find your YouTube Channel ID.', 'uabb' ) );

				return $youtube_channel_id;
			}

		} else {

			$youtube_channel_id = sprintf( __( 'Click <a href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank" rel="noopener"> <strong> here</strong> </a> to know how to find your YouTube Channel ID.', 'uabb' ) );

			return $youtube_channel_id;
		}

		if ( empty( $branding_name ) && empty( $branding_short_name ) ) {

			if ( 'yt_channel_name' === $field ) {

				$youtube_channel_name = sprintf( __( 'Click <a href="https://www.ultimatebeaver.com/docs/how-to-find-youtube-channel-name-and-channel-id/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=video-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to know how to find your YouTube Channel Name.', 'uabb' ) );

				return $youtube_channel_name;
			}
		} else {

			$youtube_channel_name = sprintf( __( 'Click <a href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank" rel="noopener"> <strong> here</strong> </a> to know how to find your YouTube Channel Name.', 'uabb' ) );

			return $youtube_channel_name;
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'UABBVideo',
	array(
		'general'          => array(
			'title'    => __( 'General', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'general'      => array( // Section.
					'title'  => __( 'Video', 'uabb' ), // Section Title.
					'fields' => array( // Section Fields.
						'video_type'   => array(
							'type'    => 'select',
							'label'   => __( 'Video Type', 'uabb' ),
							'default' => 'youtube',
							'options' => array(
								'youtube' => __( 'YouTube', 'uabb' ),
								'vimeo'   => __( 'Vimeo', 'uabb' ),
							),
							'toggle'  => array(
								'youtube' => array(
									'fields'   => array( 'youtube_link', 'end', 'start' ),
									'sections' => array( 'video_option' ),
									'tabs'     => array( 'yt_subscribe_bar' ),
								),
								'vimeo'   => array(
									'fields'   => array( 'vimeo_link', 'start' ),
									'sections' => array( 'vimeo_option' ),
								),
							),
						),
						'youtube_link' => array(
							'type'    => 'text',
							'label'   => __( 'Link', 'uabb' ),
							'default' => 'https://www.youtube.com/watch?v=HJRzUQMhJMQ',
							'description'=> UABBVideo::get_description( 'youtube_link' ),
							'connections' => array( 'url' ),
						),
						'vimeo_link'   => array(
							'type'    => 'text',
							'label'   => __( 'Link', 'uabb' ),
							'default' => 'https://vimeo.com/274860274',
							'description'=> UABBVideo::get_description( 'vimeo_link' ),
							'connections' => array( 'url' ),
						),
						'start'        => array(
							'type'        => 'unit',
							'label'       => __( 'Start Time', 'uabb' ),
							'default'     => '',
							'maxlength'   => '5',
							'size'        => '6',
							'description' => 'sec',
							'help'        => __( 'Specify a start time (in seconds).', 'uabb' ),
						),
						'end'          => array(
							'type'        => 'unit',
							'label'       => __( 'End Time', 'uabb' ),
							'default'     => '',
							'max-length'  => '5',
							'size'        => '6',
							'description' => 'sec',
							'help'        => __( 'Specify a End time (in seconds).', 'uabb' ),
						),
						'aspect_ratio' => array(
							'type'    => 'select',
							'label'   => __( 'Aspect Ratio', 'uabb' ),
							'default' => '16_9',
							'options' => array(
								'16_9' => __( '16:9', 'uabb' ),
								'4_3'  => __( '4:3', 'uabb' ),
								'3_2'  => __( '3:2', 'uabb' ),
							),
						),
					),
				),
				'video_option' => array(
					'title'  => __( 'Video Options', 'uabb' ),
					'fields' => array(
						'yt_autoplay'       => array(
							'type'    => 'select',
							'label'   => __( 'AutoPlay', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no' => array(
									'tabs' => array( 'thumbnail' ),
								),
							),
							'help'    => __( 'Thumbnail will not display if AutoPlay mode is enabled. ', 'uabb' ),
						),
						'yt_suggested'      => array(
							'type'    => 'select',
							'label'   => __( 'Suggested Videos', 'uabb' ),
							'default' => 'hide',
							'options' => array(
								'no'  => __( 'Hide', 'uabb' ),
								'yes' => __( 'Show', 'uabb' ),
							),
						),
						'yt_controls'       => array(
							'type'    => 'select',
							'label'   => __( 'Player Control', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'yt_modestbranding' ),
								),
							),
						),
						'yt_showinfo'       => array(
							'type'    => 'select',
							'label'   => __( 'Player Title & Actions', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
						),
						'yt_mute'           => array(
							'type'    => 'select',
							'label'   => __( 'Mute', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
						),
						'yt_modestbranding' => array(
							'type'    => 'select',
							'label'   => __( 'Modest Branding', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'This option lets you use a YouTube player that does not show a YouTube logo.', 'uabb' ),
						),
						'yt_privacy'        => array(
							'type'    => 'select',
							'label'   => __( 'Privacy Mode', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( "When you turn on privacy mode, YouTube won't store information about visitors on your website unless they play the video.", 'uabb' ),
						),
					),
				),
				'vimeo_option' => array(
					'title'  => __( 'Video option', 'uabb' ),
					'fields' => array(
						'vimeo_autoplay' => array(
							'type'    => 'select',
							'label'   => __( 'Autoplay', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no' => array(
									'tabs' => array( 'thumbnail' ),
								),
							),
							'help'    => __( 'Thumbnail will not display if AutoPlay mode is enabled.', 'uabb' ),
						),
						'vimeo_loop'     => array(
							'type'    => 'select',
							'label'   => __( 'Loop', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'help'    => __( 'Choose a video to play continuously in a loop. The video will automatically start again after reaching the end.', 'uabb' ),
						),
						'vimeo_title'    => array(
							'type'    => 'select',
							'label'   => __( 'Intro Title', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays title of the video.', 'uabb' ),
						),
						'vimeo_portrait' => array(
							'type'    => 'select',
							'label'   => __( 'Intro Portrait', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays the author’s profile image.', 'uabb' ),
						),
						'vimeo_byline'   => array(
							'type'    => 'select',
							'label'   => __( 'Intro Byline', 'uabb' ),
							'default' => 'show',
							'options' => array(
								'yes' => __( 'Show', 'uabb' ),
								'no'  => __( 'Hide', 'uabb' ),
							),
							'help'    => __( 'Displays the author’s name of the video.', 'uabb' ),
						),
						'vimeo_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Controls Color', 'uabb' ),
							'default'    => '',
							'show_reset' => 'true',
							'show_alpha' => 'true',
						),
					),
				),
			),
		),
		'thumbnail'        => array(
			'title'    => __( 'Thumbnail', 'uabb' ),
			'sections' => array(
				'section_image_overlay' => array(
					'title'  => __( 'Thumbnail & Overlay', 'uabb' ),
					'fields' => array(
						'show_image_overlay'  => array(
							'type'    => 'select',
							'label'   => __( 'Thumbnail', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Custom Thumbnail', 'uabb' ),
								'no'  => __( 'Default Thumbnail', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'image_overlay' ),
								),
								'no'  => array(
									'fields' => array( 'yt_thumbnail_size' ),
								),
							),
						),
						'yt_thumbnail_size'   => array(
							'type'    => 'select',
							'label'   => __( 'Default Thumbnail Size', 'uabb' ),
							'default' => 'maxresdefault',
							'options' => array(
								'maxresdefault' => __( 'Maximum Resolution', 'uabb' ),
								'hqdefault'     => __( 'High Quality', 'uabb' ),
								'mqdefault'     => __( 'Medium Quality', 'uabb' ),
								'sddefault'     => __( 'Standard Quality', 'uabb' ),
							),
						),
						'image_overlay'       => array(
							'type'        => 'photo',
							'label'       => __( 'Select Custom Thumbnail', 'uabb' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'image_overlay_color' => array(
							'type'       => 'color',
							'label'      => __( 'Overlay color', 'uabb' ),
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__outer-wrap:before',
								'property' => 'background',
							),
						),
					),
				),
				'section_play_icon'     => array(
					'title'  => __( 'Play Button', 'uabb' ),
					'fields' => array(
						'play_source'                => array(
							'type'    => 'select',
							'label'   => __( 'Image/Icon', 'uabb' ),
							'default' => 'default',
							'options' => array(
								'image'   => __( 'Image', 'uabb' ),
								'icon'    => __( 'Icon', 'uabb' ),
								'default' => __( 'Default', 'uabb' ),
							),
							'toggle'  => array(
								'image'   => array(
									'fields' => array(
										'play_img',
										'play_img_size',
									),
								),
								'icon'    => array(
									'fields' => array( 'play_icon', 'play_icon', 'play_icon_color', 'play_icon_hover_color' ),
								),
								'default' => array(
									'fields' => array(
										'play_default_icon_bg',
										'play_default_icon_bg_hover',
									),
								),
							),
						),
						'play_img'                   => array(
							'type'        => 'photo',
							'label'       => __( 'Select Image', 'uabb' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'play_icon'                  => array(
							'type'        => 'icon',
							'label'       => __( 'Select Icon', 'uabb' ),
							'default'     => 'far fa-play-circle',
							'show_remove' => true,
						),
						'play_icon_size'             => array(
							'type'        => 'unit',
							'label'       => __( 'Size', 'uabb' ),
							'default'     => '75',
							'maxlength'   => '5',
							'size'        => '6',
							'description' => 'px',
						),
						'play_default_icon_bg'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'uabb' ),
							'default'    => '',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-youtube-icon-bg,.uabb-vimeo-icon-bg',
								'property' => 'fill',
							),
						),
						'play_default_icon_bg_hover'=>array(
							'type'		=>'color',
							'label'		=>__('Hover Background Color','uabb'),
							'default'	=>'',
							'show_reset'=>'true',
							'show_alpha'=>'true',
							'preview'	=>array(
								'type'	=>'none',
							),
						),
						'play_icon_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'uabb' ),
							'default'    => '',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-video__play-icon',
								'property' => 'color',
							),
						),
						'play_icon_hover_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'uabb' ),
							'default'    => '',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'	=>array(
								'type'	=>'none',
							),
						),
						'hover_animation'            => array(
							'type'    => 'select',
							'label'   => __( 'Hover Animation', 'uabb' ),
							'default' => '',
							'options' => array(
								''                => __( 'None', 'uabb' ),
								'float'           => __( 'Float', 'uabb' ),
								'sink'            => __( 'Sink', 'uabb' ),
								'wobble-vertical' => __( 'Wobble Vertical', 'uabb' ),
							),
						),
					),
				),
			),
		),
		'yt_subscribe_bar' => array(
			'title'    => __( 'YouTube Subscribe Bar', 'uabb' ),
			'sections' => array(
				'enable_subscribe_bar'    => array(
					'title'  => __( 'YouTube Subscribe Bar', 'uabb' ),
					'fields' => array(
						'yt_subscribe_enable' => array(
							'type'    => 'select',
							'label'   => __( 'Enable Subscribe Bar', 'uabb' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'select_options', 'yt_subscribe_text' ),
									'sections' => array( 'subscribe_field_options' ),
								),
							),
						),
						'select_options'      => array(
							'type'    => 'select',
							'label'   => __( 'Select Channel ID/Channel Name', 'uabb' ),
							'default' => 'channel_id',
							'options' => array(
								'channel_id'   => __( 'Channel ID', 'uabb' ),
								'channel_name' => __( 'Channel Name', 'uabb' ),
							),
							'toggle'  => array(
								'channel_name' => array(
									'fields' => array( 'yt_channel_name' ),
								),
								'channel_id'   => array(
									'fields' => array( 'yt_channel_id' ),
								),
							),
						),
						'yt_channel_name'     => array(
							'type'    => 'text',
							'label'   => __( 'YouTube Channel Name', 'uabb' ),
							'default' => 'TheBrainstormForce',
							'description'=> UABBVideo::get_description( 'yt_channel_name' ),
						),
						'yt_channel_id'   => array(
							'type'    => 'text',
							'label'   => __( 'YouTube Channel ID', 'uabb' ),
							'default' => 'UCtFCcrvupjyaq2lax_7OQQg',
							'description'=> UABBVideo::get_description( 'yt_channel_id' ),
						),
						'yt_subscribe_text' => array(
							'type'       => 'text',
							'label'      => __( 'Subscribe to channel text', 'uabb' ),
							'default' 	 => 'Subscribe to our YouTube Channel',
							'connections' => array( 'string', 'html' ),
						),
					),
				),
				'subscribe_field_options' => array(
					'title'  => __( 'Settings', 'uabb' ),
					'fields' => array(
						'show_count'                    => array(
							'type'    => 'select',
							'label'   => __( 'Show Subscribers Count', 'uabb' ),
							'default' => 'yes',
							'options' => array(
								'no'  => __( 'No', 'uabb' ),
								'yes' => __( 'Yes', 'uabb' ),
							),
						),
						'subscribe_text_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'uabb' ),
							'default'    => 'ffffff',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '
								.uabb-subscribe-bar-prefix',
								'property' => 'color',
							),
						),
						'subscribe_text_bg_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'uabb' ),
							'default'    => '1b1b1b',
							'show_reset' => 'true',
							'show_alpha' => 'true',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-subscribe-bar',
								'property' => 'background-color',
							),
						),
						'subscribe_text_font'           => array(
							'type'    => 'font',
							'label'   => __( 'Font', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 300,
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-subscribe-bar-prefix',
							),
						),
						'subscribe_text_font_size'      => array(
							'type'        => 'unit',
							'label'       => __( 'Font size', 'uabb' ),
							'default'     => '',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-subscribe-bar-prefix',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'subscribe_text_line_height'    => array(
							'type'        => 'unit',
							'label'       => __( 'Line height', 'uabb' ),
							'default'     => '',
							'description' => 'em',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-subscribe-bar-prefix',
								'property' => 'line-height',
								'unit'     => 'em',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'subscribe_text_letter_spacing' => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'default'     => '',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-subscribe-bar-prefix',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'subscribe_text_transform'      => array(
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
								'selector' => '.uabb-subscribe-bar-prefix',
								'property' => 'text-transform',
							),
						),
						'subscribe_padding'             => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'uabb' ),
							'default'     => '',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-subscribe-bar',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'subscribe_bar_responsive'	=>array(
							'type'		=> 'select',
							'label'		=> __('Stack on','uabb'),
							'default'	=>'none',
							'options'	=>array(
								'none'    => __( 'None', 'uabb' ),
								'desktop' => __( 'Desktop', 'uabb' ),
								'tablet'  => __( 'Tablet', 'uabb' ),
								'mobile'  => __( 'Mobile', 'uabb' ),
							),
							'toggle'	=>array(
								'desktop'	=>array(
									'fields'=>array('subscribe_bar_spacing')
								),
								'tablet'	=>array(
									'fields'=>array('subscribe_bar_spacing')
								),
								'mobile'	=>array(
									'fields'=>array('subscribe_bar_spacing')
								),
							),
						),
						'subscribe_bar_spacing'	=>array(
							'type'			=> 'unit',
							'label'			=>__('Spacing','uabb'),
							'description'	=>'px',
							'preview'		=>array(
								'type'		=>'css',
								'selector'	=> '.uabb-subscribe-responsive-desktop .uabb-subscribe-bar-prefix',
								'property'	=>'margin-bottom',
								'unit'		=>'px',
							),
						),
					),
				),
			),
		),
	)
);
