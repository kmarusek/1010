<?php

class BWToastClass extends FLBuilderModule {
    public function __construct() {
        parent::__construct(array(
            'name' => __("Toast Button Module", 'skeleton-warrior'),
            'description' => __("A button which exposes a toast-type modal window with contents of your choosing.", 'skeleton-warrior'),
            'category' => __("Space Station", 'skeleton-warrior'),
            'dir' => get_stylesheet_directory() . "components/Modal/bw_toast/",
            'url' => get_stylesheet_directory_uri() . "components/Modal/bw_toast/",
        ));
    }

    public function get_modal_content( $settings ) {
        $content_type = $settings->content_type;
        switch($content_type) {
            case 'content':
                global $wp_embed;
                return wpautop( $wp_embed->autoembed( $settings->ct_content ) );
            break;
            case 'photo':
            	if ( isset( $settings->ct_photo_src ) ) {
                	return '<img src="' . $settings->ct_photo_src . '" />';
            	}
                return '<img src="" />';
            break;
            case 'video':
                global $wp_embed;
                return $wp_embed->autoembed($settings->ct_video);
            break;
            case 'iframe':
                return '<iframe src="' . $settings->iframe_url . '" class="uabb-content-iframe" frameborder="0" width="100%" height="100%" allowfullscreen></iframe>';
            break;
            case 'saved_rows':
                return '[fl_builder_insert_layout id="'.$settings->ct_saved_rows.'" type="fl-builder-template"]';
            case 'saved_modules':
            	return '[fl_builder_insert_layout id="'.$settings->ct_saved_modules.'" type="fl-builder-template"]';
            case 'saved_page_templates':
                return '[fl_builder_insert_layout id="'.$settings->ct_page_templates.'" type="fl-builder-template"]';
            break;
            case 'youtube':
            	return $this->get_video_embed();
            case 'vimeo':
            	return $this->get_video_embed();
            default:
                return;
            break;
        }
    }

    public function get_button_content($settings) {
        return $settings->btn_text;
    }
}

FLBuilder::register_module("BWToastClass", array(
    "general" => array(
        "title" => __("Content", "skeleton-warrior"),
        "sections" => array(
            'c2a' => array(
                'title'     => __('Trigger / C2A', "skeleton-warrior"),
                'fields'    => array (
                    'btn_text'  => array (
                        'type'  => 'text',
                        'label' => __("Text", "skeleton-warrior"),
                        'default' => __("Click Here", "skeleton-warrior")
                    )
                )
            ),
            'content_type' => array(
                'title'     => __('Content', 'uabb'),
                'fields'    => array(
                    'content_type'       => array(
                        'type'          => 'select',
                        'label'         => __('Type', 'uabb'),
                        'default'       => 'content',
						'options'       => array(
                            'content'       => __('Content', 'uabb'),
                            'photo'         => __('Photo', 'uabb'),
                            'video'         => __('Video Embed Code', 'uabb'),
                            'saved_rows'        => array(
                                'label'         => __('Saved Rows', 'uabb'),
                                'premium'       => true
                            ),
                            'saved_modules'     => array(
                                'label'         => __('Saved Modules', 'uabb'),
                                'premium'       => true
                            ),
                            'saved_page_templates'      => array(
                                'label'         => __('Saved Page Templates', 'uabb'),
                                'premium'       => true
                            ),
							'youtube'		=> __('YouTube', 'uabb'),
							'vimeo'			=> __('Vimeo', 'uabb'),
							'iframe'		=> __('iFrame', 'uabb' )
                        ),
                        'toggle'        => array(
                            'content'       => array(
								'sections' 		=> array( 'ct_content_typo' ),
                                'fields'        => array('ct_content')
                            ),
							'photo'        => array(
                                'fields'        => array('ct_photo')
                            ),
                            'video'         => array(
                                'fields'        => array('ct_video')
                            ),
                            'saved_rows'     => array(
                                'fields'        => array('ct_saved_rows')
                            ),
                            'saved_modules'     => array(
                                'fields'        => array('ct_saved_modules')
                            ),
                            'saved_page_templates'     => array(
                                'fields'        => array('ct_page_templates')
                            ),
							'youtube'	 => array(
								'sections'  => array( 'video_setting' )
							),
							'vimeo'	 => array(
								'sections'  => array( 'video_setting' )
							),
							'iframe'	 => array(
								'sections'  => array( 'iframe_setting' )
							),
                        )
                    ),
                    'ct_content'   => array(
                        'type'                  => 'editor',
                        'label'                 => '',
                        'default'       => __('Enter your content.','uabb'),
                        'connections'   => array( 'string', 'html' ),
                        'preview'         => array(
                            'type'          => 'text',
                            'selector'      => '.uabb-modal-content-data',
                        )
                    ),
                    'ct_photo'     => array(
                        'type'                  => 'photo',
                        'label'                 => __('Select Photo', 'uabb'),
                        'show_remove'           => true,
                        'connections'           => array( 'photo' )
                    ),
                    'ct_video'     => array(
                        'type'                  => 'textarea',
                        'label'                 => __('Embed Code / URL', 'uabb'),
                        'rows'                  => 6
                    ),
                    'ct_saved_rows'      => array(
                        'type'                  => 'select',
                        'label'                 => __('Select Row', 'uabb'),
                        'options'               => UABB_Model_Helper::get_saved_row_template(),
                    ),
                    'ct_saved_modules'      => array(
                        'type'                  => 'select',
                        'label'                 => __('Select Module', 'uabb'),
                        'options'               => UABB_Model_Helper::get_saved_module_template(),
                    ),
                    'ct_page_templates'      => array(
                        'type'                  => 'select',
                        'label'                 => __('Select Page Template', 'uabb'),
                        'options'               => UABB_Model_Helper::get_saved_page_template(),
                    )
                )
            )
        )
    )
));
