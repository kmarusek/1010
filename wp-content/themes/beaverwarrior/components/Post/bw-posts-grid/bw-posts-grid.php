<?php

/**
 * @class BWPostsGrid
 *
 */
class BWPostsGrid extends BeaverWarriorFLModule {

    /**
     * The taxonomy for post categories
     */
    const POST_TAXONOMY_CATEGORY = 'category';

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Post Grid', 'fl-builder'),
                'description'     => __('A posts grid module.', 'fl-builder'),
                'category'        => __('Posts', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
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
     * Method to return if pagination is enabled or not.
     *
     * @return boolean True if pagination is enabled
     */
    public function paginationIsEnabled(){
        $pagination_is_enabled = $this->settings->enable_pagination === 'enabled';
        // Do we have enough posts to warrant pagination?
        $post_count_larger_than_pagination = count( $this->getPosts() ) > $this->settings->max_posts_per_page;
        return $pagination_is_enabled && $post_count_larger_than_pagination;
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
            return wp_trim_words( $post_content, $this->settings->except_word_length ); 
        }
    }

    public function getPostsPerPage(){
        return $this->settings->max_posts_per_page;
    }

    /**
     * Method to get an array of post IDs for the pagination data source.
     *
     * @return array The post IDs
     */
    public function getPaginationDataSource(){
        // Declare our return
        $return_array = array();
        // Start by getting the slides
        $posts = $this->getPosts();
        // Loop through the slides and add the IDs to the data source array
        for ( $i=0; $i<count($posts); $i++ ){
            // Add the ID to the return array
            array_push($return_array, $posts[$i]->ID);
        }
        // Return the array
        return $return_array;
    }
}

FLBuilder::register_module( 
    'BWPostsGrid', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'posts_per_column' => array(
                            'type'       => 'unit',
                            'label'      => __( 'Posts per column', 'fl-builder' ),
                            'responsive' => array(
                                'default' => array(
                                    'default'    => 3,
                                    'medium'     => 2,
                                    'responsive' => 1
                                ),
                                'placeholder' => array(
                                    'default'    => 3,
                                    'medium'     => 2,
                                    'responsive' => 1
                                )
                            ),
                        ),
                        'except_word_length' => array(
                            'type'        => 'unit',
                            'label'       => __( 'Excerpt length', 'fl-builder' ),
                            'description' => 'words',
                            'default'     => 55,
                            'slider'      => array(
                                'min'  => 1,
                                'max'  => 200,
                                'step' => 5
                            )
                        )
                    )
                ),
                'post_attributes' => array(
                    'title' => __( 'Attributes', 'fl-builder'),
                    'fields' => array(
                        'show_categories' => array(
                            'type'    => 'select',
                            'label'   => __( 'Show categories', 'fl-builder' ),
                            'default' => 'enabled',
                            'options' => array(
                                'enabled'  => 'Enabled',
                                'disabled' => 'Disabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'sections' => array(
                                        'section_typography_post_categories',
                                        'section_style_post_categories'
                                    )
                                )
                            )
                        )
                    )
                ),
                'pagination' => array(
                    'title' => __( 'Pagination', 'fl-builder'),
                    'fields' => array(
                        'enable_pagination' => array(
                            'type'    => 'select',
                            'label'   => __( 'Enable pagination', 'fl-builder' ),
                            'default' => 'enabled',
                            'options' => array(
                                'enabled'  => 'Enabled',
                                'disabled' => 'Disabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'tabs' => array(
                                        'pagination'
                                    ),
                                    'sections' => array(
                                        'section_typography_pagination'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),
        'content' => array(
            'title' => __( 'Content', 'fl-builder'),
            'file'  => FL_BUILDER_DIR . 'includes/loop-settings.php'
        ),
        'pagination' => array(
            'title' => __( 'Pagination', 'fl-builder'),
            'sections' => array(
                'section_pagination' => array(
                    'fields' => array(
                        'max_posts_per_page' => array(
                            'type'    => 'unit',
                            'default' => 9,
                            'label'   => __( 'Posts per page', 'fl-builder' ),
                        )
                    )
                )
            )
        ),
        'typography' => array(
            'title' => __( 'Typography', 'fl-builder'),
            'sections' => array(
                'section_typography_post_categories' => array(
                    'title' => __( 'Categories', 'fl-builder'),
                    'fields' => array(
                        'post_categories_typography' => array(
                            'type'    => 'typography',
                            'label'   => __( 'Typography', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-categories'
                            )
                        ),
                        'post_categories_color' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-categories',
                                'property' => 'color'
                            )
                        ),
                        'post_categories_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Spacing', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'preview'      => array(
                                'type'     => 'css',
                                'selector' => '.post-categories',
                                'property' => 'margin-bottom'
                            )
                        )
                    )
                ),
                'section_typography_post_title' => array(
                    'title' => __( 'Title', 'fl-builder'),
                    'fields' => array(
                        'post_title_typography' => array(
                            'type'    => 'typography',
                            'label'   => __( 'Typography', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-title'
                            )
                        ),
                        'post_title_color' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-title',
                                'property' => 'color'
                            )
                        )
                    )
                ),
                'section_typography_excerpt' => array(
                    'title' => __( 'Excerpt', 'fl-builder'),
                    'fields' => array(
                        'post_excerpt_typography' => array(
                            'type'    => 'typography',
                            'label'   => __( 'Typography', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-excerpt'
                            )
                        ),
                        'post_excerpt_color' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-excerpt',
                                'property' => 'color'
                            )
                        )
                    )
                ),
                'section_typography_read_more' => array(
                    'title' => __( 'Read more', 'fl-builder'),
                    'fields' => array(
                        'read_more_typography' => array(
                            'type'    => 'typography',
                            'label'   => __( 'Typography', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.read-more'
                            )
                        ),
                        'read_more_color' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.read-more',
                                'property' => 'color'
                            )
                        )
                    )
                ),
                'section_typography_pagination' => array(
                    'title' => __( 'Pagination', 'fl-builder'),
                    'fields' => array(
                        'pagination_typography' => array(
                            'type'    => 'typography',
                            'label'   => __( 'Typography', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.paginationjs-pages li a'
                            )
                        ),
                        'pagination_color' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.paginationjs-pages li a',
                                'property' => 'color'
                            )
                        ),
                        'pagination_color_hover' => array(
                            'type'    => 'color',
                            'label'   => __( 'Color (active)', 'fl-builder' ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.paginationjs-pages li.active a',
                                'property' => 'color'
                            )
                        )
                    )
                )
            )
        ),  //
        'style' => array( //
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'posts_padding' => array(
                            'type'         => 'dimension',
                            'label'        => __( 'Post padding', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'default'      => 20,
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'      => 'css',
                                'selector'  => '.posts-container .post',
                                'property'  => 'padding'
                            )
                        )
                    )
                ),
                'section_spacing' => array(
                    'title' => __( 'Spacing', 'fl-builder'),
                    'fields' => array(
                        'featured_image_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Featured image', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider' => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview'      => array(
                                'type'      => 'css',
                                'selector'  => '.featured-image-container',
                                'property'  => 'margin-bottom'
                            )
                        ),
                        'post_categories_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Categories', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview'      => array(
                                'type'      => 'css',
                                'selector'  => '.post-category-container',
                                'property'  => 'margin-bottom'
                            )
                        ),
                        'post_title_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Title', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview'      => array(
                                'type'     => 'css',
                                'selector' => '.post-title-container',
                                'property' => 'margin-bottom'
                            )
                        ),
                        'post_excerpt_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Excerpt', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.post-excerpt-container',
                                'property' => 'margin-bottom'
                            )
                        ),
                        'read_more_margin_bottom' => array(
                            'type'         => 'unit',
                            'label'        => __( 'Read more', 'fl-builder' ),
                            'units'        => array( 'px' ),
                            'default_unit' => 'px',
                            'slider'       => array(
                                'min'  => 0,
                                'max'  => 200,
                                'step' => 1
                            ),
                            'preview' => array(
                                'type'     => 'css',
                                'selector' => '.read-more',
                                'property' => 'margin-bottom'
                            )
                        )
                    )
                ) //
            ) //
        ) //
    ) //
);












