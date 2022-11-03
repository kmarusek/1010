<?php

/**
 * @class BWWorkHero
 *
 */
class BWContentLibrary extends BeaverWarriorFLModule {

    /**
     * The taxonomy for post categories
     */
    const POST_TAXONOMY_CATEGORY = 'content_categories';

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
     * Method used to get a post per page number determined by user on UI.
     *
     * @return string          A number for number of post to display per page
     */
    public function getPostsPerPage(){
        return $this->settings->max_posts_per_page;
    }


}

FLBuilder::register_module( 
    'BWContentLibrary', array(
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
                            'default' => 12,
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
                        'webinars_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Webinars Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'case_study_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Case Study Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'datasheets_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Datasheets Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'videos_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Videos Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'white_papers_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('White Papers Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'spending_guides_posts_background_image' => array(
                            'type'      => 'photo',
                            'label'     => __('Spending Guides Background Image', 'fl-builder'),
                            'show_remove' => true,
                        ),
                        'posts_anchor_icon' => array(
                            'type'          => 'icon',
                            'label'         => __( 'Posts Link Arrow Icon', 'fl-builder' ),
                            'show_remove'   => true
                        ),
                        'search_icon' => array(
                            'type'         => 'icon',
                            'label'        => __( 'Search Icon', 'fl-builder' ),
                            'show_remove'  => true,
                            'description'  => 'Icon for the search bar',
                        ),
                    )
                ),
            ) //
        ) //
    ) //
);
