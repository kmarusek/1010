<?php

/**
 * @class BWWorkHero
 *
 */
class BWContentLibrary extends BeaverWarriorFLModule {

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
            [
                'name'            => __('Content Library Grid', 'skeleton-warrior'),
                'description'     => __('Content library with pagination and search.', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true
            ]
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
     * Method to return if Show categories is enabled or not.
     *
     * @return boolean True if Show categories is enabled
     */
    public function showCategoriesIsEnabled(){
        $show_categories_is_enabled = $this->settings->show_categories === 'enabled';
  
        return $show_categories_is_enabled;
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
    'BWContentLibrary', array(
        'feature_post' => array(
            'title'     => __( 'Feature Post', 'fl-builder' ),
            'sections'    => array(
                'general' => array(
                    'fields'    => array(
                        'feature_post_category' => array(
                            'type'          => 'text',
                            'label'         => __( 'Feature Post Category', 'fl-builder' ),
                            'show_remove'   => true,
                        ),
                        'feature_post_title'    => array(
                            'type'          => 'text',
                            'label'         => __( 'Feature Post Title', 'fl-builder' ),
                            'show_remove'   => true,
                        ),
                        'feature_post_url'      => array(
                            'type'          => 'link',
                            'label'         => __( 'Feature Post URL', 'fl-builder' ),
                            'show_target' => true,
                            'show_nofollow' => true,
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
                        ),
                        'pagination_next_icon' => array(
                            'type'         => 'icon',
                            'label'        => __( 'Next Icon', 'fl-builder' ),
                            'show_remove'  => true,
                            'description'  => 'Icon for the next navigation on pagination controls',
                        ),
                        'pagination_prev_icon' => array(
                            'type'         => 'icon',
                            'label'        => __( 'Previous Icon', 'fl-builder' ),
                            'show_remove'  => true,
                            'description'  => 'Icon for the previous navigation on pagination controls',
                        ),

                    )
                )
            )
        ),
        'style' => array(
            'title' => __( 'Style', 'fl-builder'),
            'sections' => array(
                'general' => array(
                    'fields' => array(
                        'feature_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Feature Post Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'posts_anchor_icon' => array(
                            'type'          => 'icon',
                            'label'         => __( 'Posts Link Arrow Icon', 'fl-builder' ),
                            'show_remove'   => true
                        ),
                        'posts_margin' => array(
                            'type'         => 'dimension',
                            'label'        => __( 'Post margin', 'fl-builder' ),
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
                                'selector'  => '.ContentLibrary-container .post',
                                'property'  => 'margin'
                            )
                        )
                    )
                ),
                'section_spacing' => array(
                    'title' => __( 'Spacing', 'fl-builder'),
                    'fields' => array(
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
                                'selector'  => '.ContentLibrary-category-container',
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
                                'selector' => '.ContentLibrary-title-container',
                                'property' => 'margin-bottom'
                            )
                        ),
                       
                    )
                ) //
            ) //
        ) //
    ) //
);
