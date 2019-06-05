<?php
/**
 * @class BWVideoPlayer
 *
 */
class BWVideoPlayer extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Video Player', 'fl-builder'),
                'description'     => __('A video player.', 'fl-builder'),
                'category'        => __('Content', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }   

    /**
     * Method to get the player type for this instance.
     *
     * @return string The type of player
     */
    public function getPlayerType(){
        return $this->settings->video_player_type;
    }

    /**
     * Method to check if the player type is a modal
     *
     * @return boolean True if the player type is a modal
     */
    public function isPlayerTypeModal(){
        return $this->getPlayerType() === 'modal';
    }
}

FLBuilder::register_module( 
    'BWVideoPlayer', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'video' => array(
                            'type'  => 'video',
                            'label' => __( 'Video', 'fl-builder' )
                        ),
                        'placeholder_image' => array(
                            'type'  => 'photo',
                            'label' => __( 'Placeholder image', 'fl-builder' )
                        )
                    )
                ),
                'player_type' => array(
                    'title' => __( 'Player type', 'fl-builder'),
                    'fields' => array(
                        'video_player_type' => array(
                            'type'  => 'select',
                            'label' => __( 'Player type', 'fl-builder' ),
                            'default' => 'modal',
                            'options' => array(
                                'inline' => 'Inline',
                                'modal'  => 'Modal'
                            ),
                            'toggle' => array(
                                'modal' => array(
                                    'sections' => array(
                                        'style_modal'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'style_icon' => array(
                    'title' => __( 'Play icon', 'fl-builder'),
                    'fields' => array(
                        'play_icon' => array(
                            'type'  => 'icon',
                            'label' => __( 'Play icon', 'fl-builder' )
                        ),
                        'play_icon_size' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Icon size', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider'       => true,
                            'default'      => 40,
                            'preview'      => array(
                                'type'      => 'css',
                                'selector'  => '.video-play-icon',
                                'property'  => 'font-size',
                                'important' => true
                            )
                        ),
                        'play_icon_color' => array(
                            'type'       => 'color',
                            'label'      => __( 'Icon color', 'fl-builder' ),
                            'default'    => 'ffffff',
                            'show_alpha' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.video-play-icon',
                                'property' => 'color',
                                'important' => true
                            )
                        ),
                        'play_icon_hover_color' => array(
                            'type'         => 'color',
                            'label'        => __( 'Icon color (hover)', 'fl-builder' ),
                            'show_alpha' => true
                        )
                    )
                ),
                'style_modal' => array(
                    'title' => __( 'Modal', 'fl-builder'),
                    'fields' => array(
                        'modal_overlay_background_color' => array(
                            'type'       => 'color',
                            'label'      => __( 'Overlay color', 'fl-builder' ),
                            'show_alpha' => true
                        ),
                        'modal_overlay_background_blur' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Background blur', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default'      => 2,
                            'default_unit' => 'px',
                            'show_alpha'   => true
                        )
                    )
                )
            )
        )
    )
);