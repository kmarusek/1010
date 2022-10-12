<?php
/**
 * @class BW1010Events
 *
 */
class BW1010Events extends BeaverWarriorFLModule {

    /**
     * The taxonomy for post categories
     */
    const POST_TAXONOMY_CATEGORY = 'event_category';

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            [
                'name'            => __('Events', 'skeleton-warrior'),
                'description'     => __('Events Module', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true
            ]
        );
        
        // register_custom_image_size( 'testimonial_quotes', 34, 40, true );
    }
    
        /**
     * Method to retrieve the posts for the module.
     *
     * @return array An array of posts
     */
    public function getPosts(){ 

        $settings = $this->settings;
        $settings->posts_per_page = -1;

        $query  = FLBuilderLoop::query( $settings );

        return isset( $query->posts ) ? $query->posts : array();
    }


    /**
     * Method used to get a string of a posts' categories.
     *
     * @param  int $post_id The post Id
     *
     * @return string          A string version of all the categories for a post
     */
    public function getPostCategoryString( $post_id ){
        // Make an array for our post terms
        $post_categories_array = array();
        // Get the categories
        $post_categories = wp_get_post_terms( $post_id, self::POST_TAXONOMY_CATEGORY );
        // Add all categories to the stored array
        for ( $i=0; $i<count($post_categories); $i++ ){
            array_push( 
                $post_categories_array, 
                $post_categories[$i]->name
            );
        }
        // Return a string version of the categories
        return implode(', ', $post_categories_array );
    }

    /**
     * Method to get the excerpt based on the set number of words in this module.
     *
     * @param  string $post_content The post content to truncate
     * @param  int $post_id The post ID
     *
     * @return string               The truncated content
     */
    public function getPostExcerpt( $post_content, $post_id ){
        // If we have an excerpt, use that instead 
        if ( has_excerpt( $post_id ) ){
            return get_the_excerpt( $post_id );
        }
        // Otherwise, return truncated content
        else {
            return wp_trim_words( $post_content, $this->settings->excerpt_word_length ); 
        }
    }

};

FLBuilder::register_module('BW1010Events', array(
    'events_loop'  => array (
        'title' =>  __( 'Events Loop' , 'skeleton-warrior' ),
        'file'  =>  FL_BUILDER_DIR  . 'includes/loop-settings.php'
    ),
    'events_icons' => array (
        'title' => __('Icons','skeleton-warrior'),
        'sections' => array (
            'section_icon' => array(
                'title' => __( 'Button Icon', 'fl-builder' ),
                'fields' => array(
                    'event_anchor_icon' => array(
                        'type'         => 'icon',
                        'label'        => __( 'Button Icon', 'fl-builder' ),
                        'show_remove'  => true,
                        'description'  => 'Icon for "learn more" button',
                    )
                )
            )
        )
    ),
    'events_typography' => array (
        'title' => __('Typography','skeleton-warrior'),
        'sections' => array (
            'section_date_typography' => array (
                'title' => __( 'Date Typography', 'fl-builder'),
                'fields' => array(
                    'event_date_typography' => array(
                        'type'    => 'typography',
                        'label'   => __( 'Typography', 'fl-builder' ),
                        'responsive' => true,
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.post_text-date'
                        )
                    ),
                ),
            ),
            'section_title_typography' => array (
                'title' => __( 'Title Typography', 'fl-builder'),
                'fields' => array(
                    'event_title_typography' => array(
                        'type'    => 'typography',
                        'label'   => __( 'Typography', 'fl-builder' ),
                        'responsive' => true,
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.post_text-title h4'
                        )
                    ),
                ),
            ),
            'section_excerpt_typography' => array (
                'title' => __( 'Excerpt Typography', 'fl-builder'),
                'fields' => array(
                    'event_excerpt_typography' => array(
                        'type'    => 'typography',
                        'label'   => __( 'Typography', 'fl-builder' ),
                        'responsive' => true,
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.post_text-excerpt p'
                        )
                    ),
                ),
            ),
            'section_link_typography' => array (
                'title' => __( 'Button Text Typography', 'fl-builder'),
                'fields' => array(
                    'event_link_typography' => array(
                        'type'    => 'typography',
                        'label'   => __( 'Typography', 'fl-builder' ),
                        'responsive' => true,
                        'preview' => array(
                            'type'     => 'css',
                            'selector' => '.post_text-link a'
                        )
                    ),
                ),
            ),
        ),
    ),
    'events_spacing' => array (
        'title' => __('Spacing','skeleton-warrior'),
        'sections' => array (
            'card_margin' => array (
                'title' => __( 'Event Card Margin & Padding', 'fl-builder'),
                'fields' => array (
                    'event_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for each individual card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.TenTenEvents-post',
                            'property'  => 'margin'
                        )
                    ),
                    'event_padding' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Padding for each individual card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.TenTenEvents-post',
                            'property'  => 'margin'
                        )
                    )
            
                )
            ),
            'content_margin' => array (
                'title' => __( 'Event Content Margins', 'fl-builder'),
                'fields' => array (
                    'date_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for date section of card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.post_text-date',
                            'property'  => 'margin'
                        )
                    ),
                    'title_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for title section of card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.post_text-title',
                            'property'  => 'margin'
                        )
                    ),
                    'excerpt_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for excerpt section of card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.post_text-excerpt',
                            'property'  => 'margin'
                        )
                    ),
                    'link_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for link section of card', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.post_text-link',
                            'property'  => 'margin'
                        )
                    ),
                )
            ),
            'filter_margin' => array (
                'title' => __( 'Filter Dropdown', 'fl-builder'),
                'fields' => array (
                    'filter_margin' => array(
                        'type'         => 'dimension',
                        'label'        => __( 'Margin for filter section', 'fl-builder' ),
                        'units'        => array( 'px' ),
                        'responsive' => true,
                        'default_unit' => 'px',
                        'default'      => 0,
                        'slider'       => array(
                            'min'  => 0,
                            'max'  => 200,
                            'step' => 1
                        ),
                        'preview' => array(
                            'type'      => 'css',
                            'selector'  => '.TenTenEvents-filter_wrap',
                            'property'  => 'margin'
                        )
                        ),
                        'filter_padding' => array(
                            'type'         => 'dimension',
                            'label'        => __( 'Padding for filter section', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'responsive' => true,
                            'default_unit' => 'px',
                            'default'      => 0,
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'      => 'css',
                                'selector'  => '.TenTenEvents-filter_wrap',
                                'property'  => 'margin'
                            )
                        )
            
                )
            ),
        ),
    ),
)
);

