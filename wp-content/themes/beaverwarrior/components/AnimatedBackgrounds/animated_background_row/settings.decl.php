<?php

class BWAnimatedBackgroundsSettingsCompat extends FLBuilderSettingsCompatRow {
    public function filter_settings($settings) {
        $settings = parent::filter_settings($settings);
        
        $new_layer_id = 0;
        
        for ($i = 1; $i <= 8; $i += 1) {
            $legacy_layer_setting = 'bw_ab_layer_' . $i . '_enable';
            $legacy_layer_image = 'bw_ab_layer_' . $i . '_image';
            $legacy_layer_image_src = 'bw_ab_layer_' . $i . '_image_src';
            $legacy_layer_animdata = 'bw_ab_layer_' . $i . '_animdata';
            $legacy_layer_depth = 'bw_ab_layer_' . $i . '_depth';
            $legacy_layer_loop = 'bw_ab_layer_' . $i . '_loop';
            $legacy_layer_bob = 'bw_ab_layer_' . $i . '_bob';
            
            if (($settings->$legacy_layer_setting ?? '') !== 'no') {
                if (!isset($settings->bw_anim_layers)) {
                    $settings->bw_anim_layers = array();
                }
                
                $settings->bw_anim_layers[$new_layer_id++] = array(
                    'layer_label' => sprintf(__('Layer %d', 'skeleton-warrior'), $i),
                    'layer_enable' => $settings->$legacy_layer_setting ?? '',
                    'layer_image' => $settings->$legacy_layer_image ?? '',
                    'layer_image_src' => $settings->$legacy_layer_image_src ?? '',
                    'layer_animdata' => $settings->$legacy_layer_animdata ?? '',
                    'layer_depth' => $settings->$legacy_layer_depth ?? '',
                    'layer_loop' => $settings->$legacy_layer_loop ?? '',
                    'layer_bob' => $settings->$legacy_layer_bob ?? ''
                );
                
                $settings->$legacy_layer_setting = 'no';
            }
        }
        
        return $settings;
    }
    
    public static function saved_row_select_list($addl = array()) {
        $results = array();
        $args    = array(
            'post_type'      => 'fl-builder-template',
            'post_status'    => array( 'publish' ),
            'posts_per_page' => -1,
        );
        
        $query   = new WP_Query( $args );
        $posts   = $query->posts;
        
        foreach ( $posts as $post ) {
            $results[$post->ID] = $post->post_title;
        }
        
        foreach ($addl as $k => $v) {
            $results[$k] = $v;
        }
        
        return $results;
    }
    
    static function render_content_js_by_id($post_id, $include_global = false) {
        FLBuilderModel::set_post_id( $post_id );
        
        // Get info on the new file.
        $nodes           = FLBuilderModel::get_categorized_nodes();
        $global_settings = FLBuilderModel::get_global_settings();
        $layout_settings = FLBuilderModel::get_layout_settings();
        $rows            = FLBuilderModel::get_nodes( 'row' );
        $asset_info      = FLBuilderModel::get_asset_info();
        $enqueuemethod   = FLBuilderModel::get_asset_enqueue_method();
        $js              = '';
        $path            = $include_global ? $asset_info['js'] : $asset_info['js_partial'];

        // Render the global js.
        if ( $include_global && ! isset( $_GET['safemode'] ) ) {
            $js .= FLBuilder::render_global_js();
        }

        // Loop through the rows.
        foreach ( $nodes['rows'] as $row ) {
            $js .= FLBuilder::render_row_js( $row );
        }

        // Loop through the modules.
        foreach ( $nodes['modules'] as $module ) {
            $js .= FLBuilder::render_module_js( $module );
        }

        // Add the layout settings JS.
        if ( ! isset( $_GET['safemode'] ) ) {
            $js .= FLBuilder::render_global_nodes_custom_code( 'js' );
            $js .= ( is_array( $layout_settings->js ) || is_object( $layout_settings->js ) ) ? json_encode( $layout_settings->js ) : $layout_settings->js;
        }

        // Call the FLBuilder._renderLayoutComplete method if we're currently editing.
        if ( stristr( $asset_info['js'], '-draft.js' ) || stristr( $asset_info['js'], '-preview.js' ) ) {
            $js .= "; if(typeof FLBuilder !== 'undefined' && typeof FLBuilder._renderLayoutComplete !== 'undefined') FLBuilder._renderLayoutComplete();";
        }

        // Include FLJSMin
        if ( ! class_exists( 'FLJSMin' ) ) {
            include FL_BUILDER_DIR . 'classes/class-fl-jsmin.php';
        }

        /**
         * Use this filter to modify the JavaScript that is compiled and cached for each builder layout.
         * @see fl_builder_render_js
         * @link https://kb.wpbeaverbuilder.com/article/117-plugin-filter-reference
         */
        $js = apply_filters( 'fl_builder_render_js', $js, $nodes, $global_settings, $include_global );

        // Only proceed if we have JS.
        if ( ! empty( $js ) ) {

            // Minify the JS.
            if ( ! FLBuilder::is_debug() ) {
                try {
                    $min = FLJSMin::minify( $js );
                } catch ( Exception $e ) {
                }

                if ( isset( $min ) ) {
                    $js = $min;
                }
            }

            // Save the JS.
            if ( 'file' === $enqueuemethod ) {
                fl_builder_filesystem()->file_put_contents( $path, $js );
            }

            /**
            * After JS is compiled.
            * @see fl_builder_after_render_js
            */
            do_action( 'fl_builder_after_render_js' );
        }
        
        FLBuilderModel::reset_post_id();

        return $js;
    }
    
    static public function render_content_css_by_id( $post_id, $include_global = false ) {
        global $wp_the_query;
        
        FLBuilderModel::set_post_id( $post_id );

        $active          = FLBuilderModel::is_builder_active();
        $nodes           = FLBuilderModel::get_categorized_nodes();
        $node_status     = FLBuilderModel::get_node_status();
        $global_settings = FLBuilderModel::get_global_settings();
        $asset_info      = FLBuilderModel::get_asset_info();
        $enqueuemethod   = FLBuilderModel::get_asset_enqueue_method();
        $post_id         = FLBuilderModel::get_post_id();
        $post            = get_post( $post_id );
        $css             = '';
        $path            = $include_global ? $asset_info['css'] : $asset_info['css_partial'];

        // Render the global css.
        if ( $include_global ) {
            $css .= FLBuilder::render_global_css();
        }

        // Loop through rows
        foreach ( $nodes['rows'] as $row ) {
            // Instance row css
            $settings = $row->settings;
            $id       = $row->node;
            ob_start();
            include FL_BUILDER_DIR . 'includes/row-css.php';
            FLBuilderCSS::render();
            $css .= ob_get_clean();

            // Instance row margins
            $css .= FLBuilder::render_row_margins( $row );

            // Instance row padding
            $css .= FLBuilder::render_row_padding( $row );

            // Instance row animation
            $css .= FLBuilder::render_node_animation_css( $row->settings );
        }

        // Loop through the columns.
        foreach ( $nodes['columns'] as $col ) {
            // Instance column css
            $settings = $col->settings;
            $id       = $col->node;
            ob_start();
            include FL_BUILDER_DIR . 'includes/column-css.php';
            FLBuilderCSS::render();
            $css .= ob_get_clean();

            // Instance column margins
            $css .= FLBuilder::render_column_margins( $col );

            // Instance column padding
            $css .= FLBuilder::render_column_padding( $col );

            // Instance column animation
            $css .= FLBuilder::render_node_animation_css( $col->settings );
        }

        // Loop through the modules.
        foreach ( $nodes['modules'] as $module ) {
            // Global module css
            $file            = $module->dir . 'css/frontend.css';
            $file_responsive = $module->dir . 'css/frontend.responsive.css';

            // Only include global module css that hasn't been included yet.
            // Add to the compiled array so we don't include it again.
            // Or we would - but FLBuilder won't let us touch the global
            // asset list, so...

            // Get the standard module css.
            if ( fl_builder_filesystem()->file_exists( $file ) ) {
                $css .= fl_builder_filesystem()->file_get_contents( $file );
            }

            // Get the responsive module css.
            if ( $global_settings->responsive_enabled && fl_builder_filesystem()->file_exists( $file_responsive ) ) {
                $css .= '@media (max-width: ' . $global_settings->responsive_breakpoint . 'px) { ';
                $css .= fl_builder_filesystem()->file_get_contents( $file_responsive );
                $css .= ' }';
            }

            // Instance module css
            $file     = $module->dir . 'includes/frontend.css.php';
            $settings = $module->settings;
            $id       = $module->node;
            
            ob_start();
            include $file;
            FLBuilderCSS::render();
            $css .= ob_get_clean();

            // Instance module margins
            $css .= FLBuilder::render_module_margins( $module );

            if ( ! isset( $global_settings->auto_spacing ) || $global_settings->auto_spacing ) {
                $css .= FLBuilder::render_responsive_module_margins( $module );
            }

            // Instance module animation
            $css .= FLBuilder::render_node_animation_css( $module->settings );
        }

        // Render all animation CSS when the builder is active.
        if ( $active ) {
            $css .= FLBuilder::render_all_animation_css();
        }

        // Custom Global CSS (included here for proper specificity)
        if ( 'published' == $node_status && $include_global ) {
            $css .= $global_settings->css;
        }

        // Custom Global Nodes CSS
        $css .= FLBuilder::render_global_nodes_custom_code( 'css' );

        // Custom Layout CSS
        if ( 'published' == $node_status || $post_id !== $wp_the_query->post->ID ) {
            $css .= FLBuilderModel::get_layout_settings()->css;
        }

        /**
         * Use this filter to modify the CSS that is compiled and cached for each builder layout.
         * @see fl_builder_render_css
         * @link https://kb.wpbeaverbuilder.com/article/117-plugin-filter-reference
         */
        $css = apply_filters( 'fl_builder_render_css', $css, $nodes, $global_settings, $include_global );

        // Minify the CSS.
        if ( ! FLBuilder::is_debug() ) {
            $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
            $css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
        }

        // Save the CSS.
        if ( 'file' === $enqueuemethod ) {
            fl_builder_filesystem()->file_put_contents( $path, $css );
        }

        /**
         * After CSS is compiled.
         * @see fl_builder_after_render_css
         */
        do_action( 'fl_builder_after_render_css' );
        
        FLBuilderModel::reset_post_id();

        return $css;
    }
}

FLBuilderSettingsCompat::register_helper('row', 'BWAnimatedBackgroundsSettingsCompat');

function bw_animated_background_fix_json($json) {
    if (!is_string($json)) $json = json_encode($json);
    
    return $json;
}

function bw_animated_background_row_settings($form, $id) {
    if ($id === "row") {
        $form['tabs']['bw_animated_background'] = array(
            'title' => __('Animated Background', 'skeleton-warrior'),
            'sections' => array(
                'bw_ab_enable' => array(
                    'fields' => array(
                        'bw_ab_enable' => array(
                            'type' => 'select',
                            'label' => __("Animated Background", 'skeleton-warrior'),
                            'options' => array(
                                'yes' => __("Yes"),
                                'no' => __("No"),
                            ),
                            'default' => 'no',
                        ),
                    ),
                ),
                'bw_ab_load' => array(
                    'title' => __("Preload appearance", 'skeleton-warrior'),
                    'fields' => array(
                        'bw_anim_load' => array(
                            'type' => 'select',
                            'label' => __("Load behavior", 'skeleton-warrior'),
                            'description' => __("Select an option for how the animation should look when loading", 'skeleton-warrior'),
                            'options' => array(
                                'color' => __("Show a solid color when loading"),
                                'image' => __("Show a loading image"),
                                'content' => __("Show a saved row"),
                                'none' => __("Do not apply a load behavior (not recommended)")
                            ),
                            'default' => 'none',
                            'toggle' => array(
                                'color' => array(
                                    'fields' => array('bw_anim_load_color', 'bw_anim_load_fade')
                                ),
                                'image' => array(
                                    'fields' => array('bw_anim_load_color', 'bw_anim_load_image', 'bw_anim_load_bgsize', 'bw_anim_load_fade')
                                ),
                                'content' => array(
                                    'fields' => array('bw_anim_load_color', 'bw_anim_load_image', 'bw_anim_load_bgsize', 'bw_anim_load_content', 'bw_anim_load_fade')
                                ),
                                'none' => array()
                            )
                        ),
                        'bw_anim_load_content' => array(
                            'type' => 'select',
                            'label' => __("Load content row"),
                            'options' => BWAnimatedBackgroundsSettingsCompat::saved_row_select_list(),
                            'description' => __("")
                        ),
                        'bw_anim_load_image' => array(
                            'type' => 'photo',
                            'label' => __("Load image", 'skeleton-warrior'),
                            'description' => __("Select an image to hide the animation with until it loads.", 'skeleton-warrior')
                        ),
                        'bw_anim_load_bgsize' => array(
                            'type' => 'select',
                            'label' => __("Load image sizing", 'skeleton-warrior'),
                            'options' => array(
                                'cover' => __("Cover the whole animation", 'skeleton-warrior'),
                                'contain' => __("Fit the size of the animation without cropping", 'skeleton-warrior'),
                            ),
                            'default' => 'cover'
                        ),
                        'bw_anim_load_color' => array(
                            'type' => 'color',
                            'label' => __("Load color", 'skeleton-warrior'),
                            'description' => __("Select a color to hide the animation with until it loads.", 'skeleton-warrior'),
                            'default' => 'ffffff'
                        ),
                        'bw_anim_load_fade' => array(
                            'type' => 'unit',
                            'label' => __("Fade time", 'skeleton-warrior'),
                            'slider' => true,
                            'units' => array('s'),
                            'default' => 0
                        ),
                        'bw_anim_load_min' => array(
                            'type' => 'unit',
                            'label' => __("Minimum load time", 'skeleton-warrior'),
                            'slider' => true,
                            'units' => array('s'),
                            'default' => 0
                        ),
                        'bw_ab_loadanim' => array(
                            'type' => 'select',
                            'label' => __("Custom load animation present", 'skeleton-warrior'),
                            'description' => __("Indicates that a custom load animation has been applied in CSS. When indicated, backgrounds will not start to animate until indicated pre-load animations have completed. Should not be enabled alongisde a load behavior AS THIS DOES NOT ENABLE ANIMATIONS AND WILL CAUSE DELAYS IN YOUR ANIMATION.", 'skeleton-warrior'),
                            'options' => array(
                                'yes' => __("Load animation with fade out present, wait for it to completely fade out"),
                                'no' => __("No load animation present, animate backgrounds in sync"),
                            ),
                            'default' => 'no',
                        )
                    )
                ),
                'bw_anim_layers' => array(
                    'title' => __("Animation Layers", 'skeleton-warrior'),
                    'fields' => array(
                        'bw_anim_layers' => array(
                            'type' => 'form',
                            'label' => __("Animation Layers", 'skeleton-warrior'),
                            'form' => 'bw_anim_layer',
                            'preview_text' => 'layer_label',
                            'multiple' => true
                        ),
                    )
                )
            )
        );
    }
    
    return $form;
}
add_filter('fl_builder_register_settings_form', 'bw_animated_background_row_settings', 10, 2);

function bw_animated_background_before_row_bg($rows) {
    if ($rows->settings->bw_ab_enable == 'yes') {
        include "before_row_bg.php";
    }
}
add_action('fl_builder_before_render_row_bg', 'bw_animated_background_before_row_bg', 10, 1);

function bw_animated_background_render_css_internal($module, $id, $settings) {
    if ($settings->bw_ab_enable == 'yes') {
        include "before_row_bg.css.php";
    }
}

function bw_animated_background_render_css($css, $nodes, $global_settings) {
    foreach ($nodes["rows"] as $key => $row) {
        ob_start();
        bw_animated_background_render_css_internal($row, $key, $row->settings);
        $css .= ob_get_clean() . " ";
    }
    
    return $css;
}
add_filter('fl_builder_render_css', 'bw_animated_background_render_css', 10, 3);

function bw_animated_background_render_js_internal($module, $id, $settings) {
    if ($settings->bw_ab_enable == 'yes') {
        include "before_row_bg.js.php";
    }
}

function bw_animated_background_render_js($js, $nodes, $global_settings) {
    foreach ($nodes["rows"] as $key => $row) {
        ob_start();
        bw_animated_background_render_js_internal($row, $key, $row->settings);
        $js .= ob_get_clean() . " ";
    }
    
    return $js;
}
add_filter('fl_builder_render_js', 'bw_animated_background_render_js', 10, 3);