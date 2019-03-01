<?php

/**
 * @class BWAnimatedSVG
 *
 */
class BWAnimatedSVG extends BeaverWarriorFLModule {

    /**
     * The key in the JSON for the array of assets
     */
    const JSON_KEY_ASSETS      = 'assets';
    
    /**
     * The key in the JSON for the path in the assets
     */
    const JSON_KEY_ASSETS_PATH = 'u';

    /**
     * The key in the JSON for the iamge in the assets
     */
    const JSON_KEY_ASSETS_IMAGE = 'p';

    /**
     * Parent class constructor.
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'            => __('Animated SVG', 'fl-builder'),
                'description'     => __('A module used for animating SVGs using Lottie', 'fl-builder'),
                'category'        => __('Space Station', 'skeleton-warrior'),
                'dir'             => $this->getModuleDirectory( __DIR__ ),
                'url'             => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export'   => true,
                'enabled'         => true, 
                'partial_refresh' => true
            )
        );
    }

    /**
     * Method to check if we need to replace the JSON paths.
     *
     * @return boolean Whether or not the user has indicated that we need to replace the paths
     * in the JSON
     */
    private function replaceJSONPathsEnbled() {
        return $this->settings->animation_json_replace_paths === 'enabled';
    }

    /**
     * Method to get the new path for the assets in the JSON. This uses the supplied 
     * date to turn into a directory in WordPress.
     *
     * @return string  The absolute path
     */
    private function getNewPath() {
        // Get the date from the user and turn that into a time
        $asset_date           = strtotime( $this->settings->animation_json_replace_paths_upload_date );
        // Parse out the date fromat for the time paramter in wp_get_upload_dir (yyyy/mm)
        $asset_date_formatted = date('Y/m', $asset_date);
        // Get the upload directory
        $upload_directory     = wp_get_upload_dir( $asset_date_formatted );
        // Get the site URL
        $current_site_url     = get_site_url();
        // Format the url into a site pat
        $path                 = str_replace( $current_site_url, '', $upload_directory['baseurl'] ) . '/' . date('Y', $asset_date) . '/' . date('m', $asset_date) . '/';
        // Return the relative path
        return $path;
    }

    /**
     * Method used to replace the paths in the JSON array
     *
     * @param  object &$json The JSON object
     *
     * @return void        
     */
    private function replaceJSONPaths( &$json, string $new_asset_path ){
        // Get the assets
        $assets = property_exists( $json, self::JSON_KEY_ASSETS ) ? $json->{self::JSON_KEY_ASSETS} : null;
        // If the assets are an array, than loop through them and replace the paths
        if ( is_array($assets) ){
            // Start the loop
            for ( $i = 0; $i<count($assets); $i++){
                // Get the current node
                $current_node = $assets[$i];
                // If the path exists, replace it
                if ( property_exists( $current_node, self::JSON_KEY_ASSETS_PATH ) ){
                    // Get the file name
                    $file_name = property_exists( $current_node, self::JSON_KEY_ASSETS_IMAGE ) ? $current_node->{self::JSON_KEY_ASSETS_IMAGE} : '';
                    // Check that this file actually exists
                    if ( file_exists( rtrim( get_home_path(), '/' ) . $new_asset_path . $file_name ) ){
                        $current_node->{self::JSON_KEY_ASSETS_PATH} = $new_asset_path;
                    }
                }
            }
        }
    }

    /**
     * Static method to santize the value of the JSON (otherwise, it'll get
     * serialized an unable to unwrap after saving in Beaver Builder).
     *
     * @param  mixed $saved_value The saved value
     *
     * @return string The sanitized value
     */
    public static function getSanitizedJSON( $saved_value ){
        if ( is_object($saved_value) ){
            return json_encode($saved_value);
        }
        else {
            return $saved_value;
        }
    }

    /**
     * Method used to return whether or not a block of JSON is valid.
     *
     * @param  mixed $json The json to check
     *
     * @return boolean True if the JSON is valid
     */
    public function jsonIsValid( $json ){
        return is_object( $json );
    }

    /**
     * Method use to get the JSON for the module.
     *
     * @return mixed The module JSON
     */
    public function getModuleSettingJSON(){
        if ( $this->jsonIsValid( $this->settings->animation_json )){
            return $this->settings->animation_json;
        }
        else {
            return json_decode( $this->settings->animation_json );
        }
    }

    /**
     * Method use to get the JSON for the module.
     *
     * @return string The ID for the container
     */
    public function getLottieContainerUniqueID(){
        return 'lottie-' . $this->node;
    }

    /**
     * Method ot return the setting for looping.
     *
     * @return string True or false (as a string)
     */
    public function getModuleSettingLoopAnimation(){
        return $this->settings->animation_loop === 'enabled' ? 'true' : 'false';
    }

    /**
     * Method ot return the setting for scroll-based animation.
     *
     * @return string True or false (as a string)
     */
    public function getModuleSettingScrollBasedAnimation(){
        return $this->settings->animation_trigger_on_scroll === 'enabled' ? 'true' : 'false';
    }

    public function getFormattedJSON(){
        // Start by getting our JSON
        $json = $this->getModuleSettingJSON();
        // If it's not valid JSON, then return and log an error
        if ( !$this->jsonIsValid( $json ) ){
            error_log( sprintf("Invalid JSON in %s.", __CLASS__) );
            return;
        }
        // If we need to replace the path in the JSON for assset
        if ( $this->replaceJSONPathsEnbled() ){
            // Get the path to replace in the JSON
            $new_asset_path = $this->getNewPath();
            // Replace the paths
            $this->replaceJSONPaths( $json , $new_asset_path );
        }
        // Return the JSON
        return $json;
    }
}

FLBuilder::register_module( 
    'BWAnimatedSVG', array(
        'general' => array(
            'title' => __( 'General', 'fl-builder'),
            'sections' => array(
                'section_general' => array(
                    'title' => __( 'JSON', 'fl-builder'),
                    'fields' => array(
                        'animation_json' => array(
                            'label'    => __('Animation JSON', 'fl-builder'),
                            'type'     => 'code',
                            'rows'     => '2',
                            'editor'   => 'javascript',
                            'sanitize' => 'BWAnimatedSVG::getSanitizedJSON'
                        ),
                        'animation_json_replace_paths' => array(
                            'label'   => __('Replace Image Paths', 'fl-builder'),
                            'type'    => 'select',
                            'help'    => 'Sometimes, After Effects will hard-code the image paths in the JSON. If you\'ve uploaded the images for the animation on a specific date, you can elect to automatically replace the paths for these images by selecting the date you uploaded them.',
                            'default' => 'disabled',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'fields' => array(
                                        'animation_json_replace_paths_upload_date'
                                    )
                                )
                            )
                        ),
                        'animation_json_replace_paths_upload_date' => array(
                            'label'   => __('Image upload date', 'fl-builder'),
                            'type'    => 'date',
                            'default' => current_time( 'Y-m' )
                        )
                    )
                ),
                'section_animation' => array(
                    'title' => __( 'Animation', 'fl-builder'),
                    'fields' => array(
                        'animation_loop' => array(
                            'label'   => __('Loop Animation', 'fl-builder'),
                            'type'    => 'select',
                            'default' => 'disabled',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            )
                        ),
                        'animation_trigger_on_scroll' => array(
                            'label'   => __('Trigger Animation on scroll', 'fl-builder'),
                            'type'    => 'select',
                            'default' => 'disabled',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            )
                        )
                    )
                )
            )
        )
    ) 
);
