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
     * The key in the JSON for the image name in the assets
     */
    const JSON_KEY_ASSETS_IMAGE = 'p';

    /**
     * The key in the JSON for the image width in the assets
     */
    const JSON_KEY_ASSETS_WIDTH = 'w';

    /**
     * The key in the JSON for the image height in the assets
     */
    const JSON_KEY_ASSETS_HEIGHT = 'h';

    /**
     * The max numbers of image variations to check if the height and width
     * of an image don't match.
     */
    const MAX_NUMBER_OF_ALTERNATIVE_IMAGES_TO_CHECK = 20;

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
     * Static method to sanitize the value of the JSON (otherwise, it'll get
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
     * Method to return the setting for scroll-based animation.
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
        // Make sure the images are all good to go.
        $this->validateJSONImages( $json );
        // Return the JSON
        return $json;
    }

    /**
     * Method used to validate the JSON images. We need to do this because frequently, 
     * the images in the JSON use a standard name (i.e., img-1.png) which may not
     * actually be the name of the file if many images were uploaded
     * on the same date. This method will check that the image sizes line up with what's
     * in the file.
     *
     * @param  object &$json The JSON
     *
     * @return {void}
     */
    public function validateJSONImages( &$json ){

        // Loop through the JSON assets
        if ( property_exists($json, self::JSON_KEY_ASSETS ) ){
            for ( $i=0; $i<count($json->{self::JSON_KEY_ASSETS}); $i++){
                $current_node = $json->{self::JSON_KEY_ASSETS}[$i];
                // Get the defined height and width
                $set_image_height = property_exists($current_node, self::JSON_KEY_ASSETS_HEIGHT ) ? $current_node->{self::JSON_KEY_ASSETS_HEIGHT} : '';
                $set_image_width  = property_exists($current_node, self::JSON_KEY_ASSETS_WIDTH ) ? $current_node->{self::JSON_KEY_ASSETS_WIDTH} : '';
                // Get the image ID for this image
                $site_url         = get_site_url();
                $image_directory  = property_exists($current_node, self::JSON_KEY_ASSETS_PATH ) ? $current_node->{self::JSON_KEY_ASSETS_PATH} : '';
                $image_name       = property_exists($current_node, self::JSON_KEY_ASSETS_IMAGE ) ? $current_node->{self::JSON_KEY_ASSETS_IMAGE} : '';
                $image_url        = $site_url . $image_directory . $image_name;
                $image_id         = $this->getAttchmentIDByURL( $image_url );
                // Get the image attachment metadata
                $image_meta_data  = wp_get_attachment_metadata( $image_id );
                // If the height or width do not match, throw a warning
                if ( $set_image_width !== $image_meta_data['width'] || $set_image_height !== $image_meta_data['height'] ){
                    // Try to locate alternatives 
                    $attempt_to_locate_alternatives = true;
                    $original_image_name_array = explode( '.', $image_name );
                    $counter = 0;
                    while ( $attempt_to_locate_alternatives && $counter <= self::MAX_NUMBER_OF_ALTERNATIVE_IMAGES_TO_CHECK ){
                        // Increment no matter what
                        $counter++;
                        $new_image_name_array = $original_image_name_array;
                        $file_name = $original_image_name_array[ count($original_image_name_array) - 2 ];
                        $new_image_name_array[ count($original_image_name_array) - 2 ] = $file_name . '-' . $counter;
                        $new_image_file_name = implode( '.', $new_image_name_array );
                        // Make sure the image actually exists
                        $alt_image_doesnt_exist = !file_exists( rtrim( get_home_path(), '/' ) . $image_directory . $new_image_file_name );
                        if ( $alt_image_doesnt_exist ){
                            continue;
                        }
                        // Make our alt image URL
                        $alt_image_url       = $site_url . $image_directory . $new_image_file_name;
                        // Get the alt image id
                        $alt_image_id        = $this->getAttchmentIDByURL( $alt_image_url );
                        // Get the alt image attachment metadata
                        $alt_image_meta_data = wp_get_attachment_metadata( $alt_image_id );
                        // If we have a match...
                        if ( $set_image_width === $alt_image_meta_data['width'] || $set_image_height === $alt_image_meta_data['height'] ){
                            // Kill the loop
                            $attempt_to_locate_alternatives = false;
                            $json->{self::JSON_KEY_ASSETS}[$i]->{self::JSON_KEY_ASSETS_IMAGE} = $new_image_file_name;
                        }
                    }
                }
            }
        }
    }

    /**
     * Method used to get the attachment ID by the image URL
     *
     * @param  string $image_url The URL for the image
     *
     * @return int            The attachment ID
     */
    private function getAttchmentIDByURL( string $image_url ){
        global $wpdb;
        $attachment_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE guid='%s' LIMIT 1;", $image_url 
            )
        ); 
        return $attachment_id;
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
                            'default' => current_time( 'Y-m-d' )
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
