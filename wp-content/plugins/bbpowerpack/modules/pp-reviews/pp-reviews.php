<?php

/**
 * This is an example module with only the basic
 * setup necessary to get it working.
 *
 * @class PPImageCarouselModule
 */
class PPReviewsModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Reviews', 'bb-powerpack' ),
				'description'     => __( 'A module for reviews.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-reviews/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-reviews/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);
		$this->add_css( 'jquery-swiper' );
		$this->add_js( 'jquery-swiper' );
		$this->add_css( BB_POWERPACK()->fa_css );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPReviewsModule',
	array(
		'reviews'    => array( // Tab.
			'title'    => __( 'Reviews', 'bb-powerpack' ), // Tab title.
			'sections' => array( // Tab Sections.
				'general' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'reviews' => array(
							'type'         => 'form',
							'label'        => __( 'Review', 'bb-powerpack' ),
							'form'         => 'pp_reviews_form', // ID from registered form below.
							'preview_text' => 'title', // Name of a field to use for the preview text.
							'multiple'     => true,
						),
					),
				),
			),
		),
		'settings'   => array(
			'title'    => __( 'Settings', 'bb-powerpack' ),
			'sections' => array(
				'general'        => array( // Section.
					'title'  => '',
					'fields' => array( // Section Fields.
						'carousel_type'    => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-powerpack' ),
							'default' => 'carousel',
							'options' => array(
								'carousel'  => __( 'Carousel', 'bb-powerpack' ),
								'coverflow' => __( 'Coverflow', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'carousel'  => array(
									'fields' => array( 'pagination_type', 'effect' ),
								),
								'coverflow' => array(
									'fields' => array( 'pagination_type', 'columns' ),
								),
							),
						),
						'effect'           => array(
							'type'    => 'select',
							'label'   => __( 'Effect', 'bb-powerpack' ),
							'default' => 'slide',
							'options' => array(
								'slide' => __( 'Slide', 'bb-powerpack' ),
								'fade'  => __( 'Fade', 'bb-powerpack' ),
								'cube'  => __( 'Cube', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'slide' => array(
									'fields' => array( 'columns' ),
								),
							),
						),
						'columns'          => array(
							'type'       => 'unit',
							'label'      => __( 'Slides Per View', 'bb-powerpack' ),
							'default'    => 3,
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
						),
						'slides_to_scroll' => array(
							'type'       => 'unit',
							'label'      => __( 'Slides to Scroll', 'bb-powerpack' ),
							'default'    => 1,
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '1',
									'medium'     => '1',
									'responsive' => '1',
								),
							),
							'help'       => __( 'Set numbers of slides to move at a time.', 'bb-powerpack' ),
						),
						'spacing'          => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'default'    => 20,
							'units'      => array( 'px' ),
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '20',
									'medium'     => '20',
									'responsive' => '20',
								),
							),
						),
						'carousel_height'  => array(
							'type'       => 'unit',
							'label'      => __( 'Height', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
					),
				),
				'slide_settings' => array(
					'title'  => __( 'Slide Settings', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'transition_speed'     => array(
							'type'        => 'text',
							'label'       => __( 'Transition Speed', 'bb-powerpack' ),
							'default'     => '1000',
							'size'        => '5',
							'description' => _x( 'ms', 'Value unit for form field of time in mili seconds. Such as: "500 ms"', 'bb-powerpack' ),
						),
						'autoplay'             => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Auto Play', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'autoplay_speed' ),
								),
							),
						),
						'autoplay_speed'       => array(
							'type'        => 'text',
							'label'       => __( 'Auto Play Speed', 'bb-powerpack' ),
							'default'     => '5000',
							'size'        => '5',
							'description' => _x( 'ms', 'Value unit for form field of time in mili seconds. Such as: "500 ms"', 'bb-powerpack' ),
						),
						'pause_on_interaction' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Pause on Interaction', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
					),
				),
				'navigation'     => array( // Section.
					'title'  => __( 'Navigation', 'bb-powerpack' ), // Section Title.
					'collapsed' => true,
					'fields' => array( // Section Fields.
						'slider_navigation' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Navigation Arrows?', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'sections' => array( 'arrow_style' ),
								),
							),
						),
						'pagination_type'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Pagination Type', 'bb-powerpack' ),
							'default' => 'bullets',
							'options' => array(
								'none'     => __( 'None', 'bb-powerpack' ),
								'bullets'  => __( 'Dots', 'bb-powerpack' ),
								'fraction' => __( 'Fraction', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'bullets'  => array(
									'sections' => array( 'pagination_style' ),
									'fields'   => array( 'bullets_width', 'bullets_border_radius' ),
								),
								'fraction' => array(
									'sections' => array( 'pagination_style' ),
								),
							),
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'bb-powerpack' ),
			'sections' => array(
				'name_style'	=> array(
					'title'		=> __( 'Name', 'bb-powerpack' ),
					'fields'	=> array(
						'name_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'color',
							),
						),
						'name_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Top Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'name_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'title_style'	=> array(
					'title'		=> __( 'Title', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'	=> array(
						'title_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'color',
							),
						),
						'title_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Top Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'title_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'review_style'	=> array(
					'title'			=> __( 'Review', 'bb-powerpack' ),
					'collapsed'		=> true,
					'fields'		=> array(
						'content_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'color',
							),
						),
						'content_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Top Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'content_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'     => '',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'general'    => array(
					'title'  => __( 'Slide', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'slide_border'           => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'border',
							),
						),
						'slide_padding'          => array(
							'type'       => 'unit',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'separator'              => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Separator', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
						),
						'slide_background'       => array(
							'type'        => 'color',
							'label'       => __( 'Background color', 'bb-powerpack' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'background-color',
							),
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'slide_background_hover' => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover color', 'bb-powerpack' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review:hover',
								'property' => 'background-color',
							),
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'separator_color'        => array(
							'type'        => 'color',
							'label'       => __( 'Separator Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-header',
								'property' => 'border-bottom-color',
							),
						),
						'separator_color_hover'  => array(
							'type'        => 'color',
							'label'       => __( 'Separator Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
					),
				),
				'image'      => array(
					'title'  => __( 'Image', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'image_size'    => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'    => 36,
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-review-image img',
										'property' => 'height',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-review-image img',
										'property' => 'width',
										'unit'     => 'px',
									),
								),
							),
						),
						'image_border'  => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-image img',
								'property' => 'border',
							),
						),
						'image_vertical_alignment'	=> array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'bb-powerpack' ),
							'default' => 'top',
							'options' => array(
								'flex-start'   	=> __( 'Top', 'bb-powerpack' ),
								'center' 	=> __( 'Middle', 'bb-powerpack' ),
								'flex-end' 	=> __( 'Bottom', 'bb-powerpack' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-image',
								'property' => 'align-self',
							),
						),
						'image_spacing' => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-cite',
								'property' => 'margin-left',
								'unit'     => 'px',
							),
						),
					),
				),
				'icon'       => array(
					'title'  => __( 'Icon', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'icon_size' => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon i, .pp-review-icon i:before',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'icon_spacing' => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon',
								'property' => 'margin-left',
								'unit'     => 'px',
							),
						),
						'icon_vertical_alignment'	=> array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'bb-powerpack' ),
							'default' => 'top',
							'options' => array(
								'flex-start'   	=> __( 'Top', 'bb-powerpack' ),
								'center' 	=> __( 'Middle', 'bb-powerpack' ),
								'flex-end' 	=> __( 'Bottom', 'bb-powerpack' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon i',
								'property' => 'vertical-align',
							),
						),
						'icon_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => '000000',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon.pp-icon i',
								'property' => 'color',

							),
							'connections' => array( 'color' ),
						),
						'icon_color_hover'          => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review:hover .pp-review-icon.pp-icon i',
								'property' => 'color',

							),
							'connections' => array( 'color' ),
						),
					),
				),
				'rating_section'     => array(
					'title'  => __( 'Rating', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'star_style'          => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'bb-powerpack' ),
							'default' => 'solid',
							'options' => array(
								'solid'   => __( 'Solid', 'bb-powerpack' ),
								'outline' => __( 'Outline', 'bb-powerpack' ),
							),
						),
						'star_size'           => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => 20,
							'responsive'  => 'true',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating i',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'slider'  => true,
						),
						'star_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => 'f0ad4e',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i:before',
								'property' => 'color',

							),
						),
						'star_unmarked_color' => array(
							'type'       => 'color',
							'label'      => __( 'Unmarked Color', 'bb-powerpack' ),
							'default'    => 'efecdc',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i',
								'property' => 'color',

							),
						),
						'star_spacing'        => array(
							'type'        => 'unit',
							'label'       => __( 'Spacing', 'bb-powerpack' ),
							'default'     => '',
							'description' => 'px',
							'slider'      => 'true',
							'responsive'  => 'true',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i',
								'property' => 'margin-right',
								'unit'     => 'px',
							),
						),
						'star_alignment'      => array(
							'type'    => 'align',
							'label'   => __( 'Alignment', 'bb-powerpack' ),
							'default' => 'left',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating',
								'property' => 'text-align',
							),
						),
					),
				),
				'arrow_style'      => array( // Section.
					'title'  => __( 'Arrow', 'bb-powerpack' ), // Section Title.
					'collapsed' => true,
					'fields' => array( // Section Fields.
						'arrow_font_size'          => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => '24',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-swiper-button',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'arrow_bg_color'           => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_bg_hover'           => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_color'              => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '000000',
							'connections' => array( 'color' ),
						),
						'arrow_color_hover'        => array(
							'type'        => 'color',
							'label'       => __( 'Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_border'             => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-swiper-button',
								'property' => 'border',
							),
						),
						'arrow_border_hover'       => array(
							'type'        => 'color',
							'label'       => __( 'Border Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_horizontal_padding' => array(
							'type'    => 'unit',
							'label'   => __( 'Horizontal Padding', 'bb-powerpack' ),
							'default' => '13',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-left',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-right',
										'unit'     => 'px',
									),
								),
							),
						),
						'arrow_vertical_padding'   => array(
							'type'    => 'unit',
							'label'   => __( 'Vertical Padding', 'bb-powerpack' ),
							'default' => '5',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-top',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-bottom',
										'unit'     => 'px',
									),
								),
							),
						),
						'arrow_spacing'            => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'bb-powerpack' ),
							'default' => '',
							'slider'  => true,
						),
						'arrow_opacity'            => array(
							'type'    => 'unit',
							'label'   => __( 'Opacity', 'bb-powerpack' ),
							'default' => '1',
							'slider'  => array(
								'min'  => 0,
								'max'  => 1,
								'step' => .1,
							),
						),
					),
				),
				'pagination_style' => array(
					'title'  => __( 'Pagination', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'pagination_bg_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => '999999',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
								'property' => 'background-color',
							),
						),
						'pagination_bg_hover'   => array(
							'type'        => 'color',
							'label'       => __( 'Active Background Color', 'bb-powerpack' ),
							'default'     => '000000',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet:hover, .pp-reviews-swiper .swiper-pagination-bullet-active',
								'property' => 'background',
							),
						),
						'bullets_width'         => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '10',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
										'property' => 'width',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
										'property' => 'height',
										'unit'     => 'px',
									),
								),
							),
						),
						'bullets_border_radius' => array(
							'type'    => 'unit',
							'label'   => __( 'Border Radius', 'bb-powerpack' ),
							'default' => '100',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
								'property' => 'border-radius',
								'unit'     => 'px',
							),
						),
						'bullets_spacing'      => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'default'    => '5',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.swiper-pagination-bullets .swiper-pagination-bullet',
										'property' => 'margin-left',
										'unit'     => 'px !important',
									),
									array(
										'selector' => '.swiper-pagination-bullets .swiper-pagination-bullet',
										'property' => 'margin-right',
										'unit'     => 'px !important',
									),
								),
							),
						),
						'bullets_top_margin' => array(
							'type'       => 'unit',
							'label'      => __( 'Top Margin', 'bb-powerpack' ),
							'default'    => '5',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.swiper-pagination',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'bb-powerpack' ),
			'sections' => array(
				'name_fonts'   => array(
					'title'  => __( 'Name', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'name_typography'    => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
							),
						),
					),
				),
				'title_fonts'  => array(
					'title'  => __( 'Title', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'title_typography'    => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
							),
						),
					),
				),
				'review_fonts' => array(
					'title'  => __( 'Review', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'content_typography'    => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-text',
							),
						),
					),
				),
			),
		),
	)
);


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form(
	'pp_reviews_form',
	array(
		'title' => __( 'Add Review', 'bb-powerpack' ),
		'tabs'  => array(
			'general'     => array( // Tab.
				'title'    => __( 'General', 'bb-powerpack' ), // Tab title.
				'sections' => array( // Tab Section.
					'review_fields' => array(
						'title'  => '',
						'fields' => array(
							'image'  => array(
								'type'        => 'photo',
								'label'       => __( 'Image', 'bb-powerpack' ),
								'show_remove' => true,
								'connections' => array( 'photo' ),
							),
							'name'   => array(
								'type'        => 'text',
								'label'       => __( 'Name', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
							'title'  => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
							'rating' => array(
								'type'    => 'unit',
								'label'   => __( 'Rating', 'bb-powerpack' ),
								'default' => 3,
							),
							'icon'   => array(
								'type'        => 'icon',
								'label'       => 'Icon',
								'show_remove' => true,
							),
							'link'   => array(
								'type'          => 'link',
								'label'         => 'Link',
								'connections'   => array( 'string', 'html', 'url' ),
								'show_target'   => true,
								'show_nofollow' => true,
							),
							'review' => array(
								'type'        => 'editor',
								'label'       => 'Review',
								'connections' => array( 'string', 'html', 'url' ),
							),

						),
					),
				),
			),
			'review_styles' => array(
				'title'    => __( 'Style', 'bb-powerpack' ),
				'sections' => array(
					'styles'    => array(
						'title'  => '',
						'fields' => array(
							'slide_background'       => array(
								'type'        => 'color',
								'label'       => __( 'Background color', 'bb-powerpack' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review',
									'property' => 'background-color',
								),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
							'slide_background_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review:hover',
									'property' => 'background-color',
								),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'separator' => array(
						'title'  => 'Separator',
						'fields' => array(
							'separator_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_reset'  => true,
								'show_alpha'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-header',
									'property' => 'border-bottom-color',
								),
							),
							'separator_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_reset'  => true,
								'show_alpha'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'icon'      => array(
						'title'  => 'Icon',
						'fields' => array(
							'icon_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-icon',
									'property' => 'color',
								),
							),
							'icon_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'name'      => array(
						'title'  => 'Name',
						'fields' => array(
							'name_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Name Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-name',
									'property' => 'color',
								),
							),
							'name_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Name Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'title'     => array(
						'title'  => 'Title',
						'fields' => array(
							'title_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-title',
									'property' => 'color',
								),
							),
							'title_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'content'   => array(
						'title'  => __( 'Content', 'bb-powerpack' ),
						'fields' => array(
							'content_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-title',
									'property' => 'color',
								),
							),
							'content_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
				),
			),
		),
	)
);
