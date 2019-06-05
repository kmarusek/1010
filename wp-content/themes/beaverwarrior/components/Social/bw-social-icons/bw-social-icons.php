<?php
/**
 * @class BWSocialIcons
 *
 */
class BWSocialIcons extends BeaverWarriorFLModule {

    /**
     * An array of our social media platforms
     */
    const SOCIAL_PLATFORMS = array(
        'facebook' => array(
            'title'     => 'Facebook',
            'share_url' => 'https://www.facebook.com/sharer/sharer.php?u=%s'
        ),
        'linkedin'   => array(
            'title'     => 'LinkedIn',
            'share_url' => 'https://www.linkedin.com/shareArticle?mini=true&url=%s'
        ),
        'reddit'   => array(
            'title'     => 'Reddit',
            'share_url' => 'https://reddit.com/submit?url=%s'
        ),
        'twitter'  => array(
            'title'     => 'Twitter',
            'share_url' => 'https://twitter.com/home?status=%s'
        )
    );

    /**
     * Parent class constructor.
     * 
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Social Icons', 'fl-builder'),
                'description'     => __('A social icons module.', 'fl-builder'),
                'category'        => __('Social', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }  

    /**
     * Method to get the share icons we're using for this share sheet.
     *
     * @return array An array of share icons, titles, and share URLs
     */
    public function getShareIcons(){
        // Declare our return
        $return_array = array();
        // Loop through our current icons
        for ( $i=0; $i<count($this->settings->icons); $i++ ){
            // Get the current icon
            $current_icon = $this->settings->icons[$i];
            // Get the platform for this icon
            $platform_key = $current_icon->social_media_platform;
            // Add to the return
            array_push(
                $return_array,
                array(
                    'icon_class' => $current_icon->icon,
                    'title'      => $this->getPlatformName( $platform_key ),
                    'url'        => $this->getPlatformShareUrl( $platform_key )
                )
            );
        }
        // Return the array
        return $return_array;
    }

    /**
     * Method to get the name of a platform based on the platform key.
     *
     * @param  string $platform_key The platform key.
     *
     * @return string               The platform name
     */
    public function getPlatformName( $platform_key ){
        return array_key_exists( $platform_key, self::SOCIAL_PLATFORMS ) ? self::SOCIAL_PLATFORMS[ $platform_key ]['title'] : null;
    }

    /**
     * Method to get the share URL based on a specific platform
     *
     * @param  string $platform_key The platform key.
     *
     * @return string               The share URL
     */
    public function getPlatformShareUrl( $platform_key ){
        // The default return share url
        $return_string = '';
        if ( array_key_exists( $platform_key, self::SOCIAL_PLATFORMS ) ){
            $return_string = sprintf(
                self::SOCIAL_PLATFORMS[ $platform_key]['share_url'],
                urlencode( get_permalink() )
            );
        }
        return $return_string;
    }

    /**
     * Method to get the social media options array for a 
     * select field.
     *
     * @return array The options
     */
    public static function getSocialMediaOptions(){
        // The return
        $return_array = array();
        // Add to the return
        foreach (self::SOCIAL_PLATFORMS as $key => $value) {
            $return_array[ $key ] = $value['title'];
        }
        // Sort by value
        asort( $return_array );
        return $return_array;
    }
}

FLBuilder::register_module( 
    'BWSocialIcons', array(
        'icons' => array(
            'title' => __( 'Icons', 'fl-builder'),
            'sections' => array(
                'icons' => array(
                    'fields' => array(
                        'icons' => array(
                            'label'        => __('Icons', 'fl-builder'),
                            'type'         => 'form',
                            'form'         => 'bw_social_icons', 
                            'preview_text' => 'social_media_platform',
                            'multiple'     => true
                        )
                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'sheet_background_color' => array(
                            'type'       => 'color',
                            'label'      => __('Background color', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true,
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.social-icons-container .social-icons-inner',
                                'property' => 'background-color'
                            )
                        ),
                        'icon_color' => array(
                            'type'       => 'color',
                            'label'      => __('Icon color', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true,
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.social-icons-container i',
                                'property' => 'color'
                            )
                        ),
                        'icon_color_hover' => array(
                            'type'       => 'color',
                            'label'      => __('Icon color (hover)', 'fl-builder'),
                            'show_reset' => true,
                            'show_alpha' => true
                        )
                    )
                ),
                'desktop' => array(
                    'title' => __( 'Desktop', 'fl-builder'),
                    'fields' => array(
                        'desktop_affix_offset' => array(
                            'type'         => 'unit',
                            'label'        => __('Affix buffer', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider' => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 50,
                                    'step' => 1
                                )
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.social-icons-container',
                                'property' => 'padding-top',
                                'unit'     => 'px'
                            )
                        ),
                        'desktop_icon_container_padding' => array(
                            'type'         => 'dimension',
                            'label'        => __('Container padding', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'default'      => 20,
                            'slider' => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 50,
                                    'step' => 1
                                )
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.social-icons-inner',
                                'property' => 'padding',
                                'unit'     => 'px'
                            )
                        ),
                        'desktop_icon_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __('Margin bottom', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider' => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 50,
                                    'step' => 1
                                )
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.social-icons-list li:not(:last-child)',
                                'property' => 'margin-bottom',
                                'unit'     => 'px'
                            )
                        )
                    )
                ),
                'mobile' => array(
                    'title' => __( 'Mobile', 'fl-builder'),
                    'fields' => array(
                        'mobile_icon_list_padding' => array(
                            'type'         => 'dimension',
                            'label'        => __('Icon container padding', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'default'      => 20,
                            'slider' => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 50,
                                    'step' => 1
                                )
                            )
                        ),
                        'mobile_icon_margin' => array(
                            'type'         => 'dimension',
                            'label'        => __('Icon margin', 'fl-builder'),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'default'      => 10,
                            'slider' => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 50,
                                    'step' => 1
                                )
                            )
                        )
                    )
                )
            )
        ) //
    ) //
);

/**
 * Register the settings for each of the slides in the slider
 */
FLBuilder::register_settings_form('bw_social_icons', 
    array(
        'title' => __( 'Icon', 'fl-builder' ),
        'tabs'  => array(
            'general'      => array(
                'title' => __( 'General', 'fl-builder' ),
                'sections'      => array(
                    'general' => array(
                        'fields' => array(
                            'social_media_platform'=> array(
                                'label'   => __('Platform', 'fl-builder'),
                                'type'    => 'select',
                                'options' => BWSocialIcons::getSocialMediaOptions()
                            ),
                            'icon' => array(
                                'label'   => __('Icon', 'fl-builder'),
                                'type'    => 'icon'
                            )
                        )
                    )
                )
            )
        ) 
    ) 
);