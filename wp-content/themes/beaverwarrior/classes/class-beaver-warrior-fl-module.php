<?php

/**
 * Parent class for all Beaver Warrier components
 */
class BeaverWarriorFLModule extends FLBuilderModule {

    const POST_TYPE_SAVED_BUILDER_ITEM        = 'fl-builder-template';

    const TAXONOMY_SAVED_BUILDER_ITEM_TYPE    = 'fl-builder-template-type';

    const TERM_SAVED_BUILDER_ITEM_TYPE_MODULE = 'module';

    const TERM_SAVED_BUILDER_ITEM_TYPE_ROW    = 'row';

    /**
     * The post type for themer layouts.
     */
    const FL_LAYOUT_POST_TYPE                 = 'fl-theme-layout';

    /**
     * An array of characters that, when are the first character of a parent element in the
     * array, are considered CSS selectors and not property keys.
     */
    const RENDER_MODULE_CSS_SELECTORS         =  array( '.','#','&','>' );

    /**
     * The HTML tags used by the CSS parser
     */
    const RENDER_MODULE_CSS_HTML_TAGS         = array( 'div','a','p','span','ul','li','h1','h2','h3','h4','h5','h6','table','thead','tbody','tr','td','th', 'dl', 'dd', 'dt', 'img' );

    /**
     * Function to return a dummy image
     * @param  int         $width            The image width
     * @param  int         $height           The image height
     * @param  string      $background_color The image background color
     * @param  string      $text_color       The text color
     * @param  string|null $text             The text
     *
     * @return string                        The default image URL
     */
    public function getDefaultImage( int $width, int $height, string $background_color = '000', string $text_color = 'fff', ?string $text = null){
        // Get the image src
        $the_url = $this->getDefaultImageSrc( $width, $height, $background_color, $text_color, $text );
        // Return the image
        return sprintf(
            '<img width="%d" height="%d" src="%s" />',
            // The width
            $width,
            // The height
            $height,
            // The src
            $the_url
        );
    }

    /**
     * Method to get the menus the user has regitered. 
     *
     * @return array  An array of menus the user has registerd.
     *
     * @see self::_getMenus()
     */
    public static function _get_menus(){
        return self::_getMenus();
    }

    public function renderSavedRow( int $post_id ){
        if ( $post_id ){
            FLBuilder::render_query( 
                array(
                    'post_type' => self::POST_TYPE_SAVED_BUILDER_ITEM,
                    'p'         => $post_id
                )
            );
        }
    }
    /**
     * Method to get the menus the user has regitered. 
     *
     * @return array  An array of menus the user has registerd.
     */
    public static function _getMenus(){
        // Get the menu terms
        $menus = get_terms( 'nav_menu' );
        // Format the array so that the menu ID is the key and the name of the menu is
        // the value
        $menus = array_combine( wp_list_pluck( $menus, 'term_id' ), wp_list_pluck( $menus, 'name' ) );
        // Return the array
        return $menus;
    }

    public static function _getSavedRows(){

        $return_array = array();

        $args = array(
            'post_type'      => self::POST_TYPE_SAVED_BUILDER_ITEM,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'asc',
            'tax_query' => array(
                array(
                    'taxonomy' => self::TAXONOMY_SAVED_BUILDER_ITEM_TYPE,
                    'field'    => 'slug',
                    'terms'    => self::TERM_SAVED_BUILDER_ITEM_TYPE_ROW,
                )
            )
        );


        $query = new WP_Query( $args );

        $saved_modules = $query->posts;

        for ( $i=0;$i<count($saved_modules); $i++ ){
            $current_saved_module = $saved_modules[$i];
            $return_array[$current_saved_module->ID] = $current_saved_module->post_title;
        }

        return $return_array;
    }


    public static function _getSavedModules(){

        $return_array = array();

        $args = array(
            'post_type'      => self::POST_TYPE_SAVED_BUILDER_ITEM,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'asc',
            'tax_query' => array(
                array(
                    'taxonomy' => self::TAXONOMY_SAVED_BUILDER_ITEM_TYPE,
                    'field'    => 'slug',
                    'terms'    => self::TERM_SAVED_BUILDER_ITEM_TYPE_MODULE,
                )
            )
        );


        $query = new WP_Query( $args );

        $saved_modules = $query->posts;

        for ( $i=0;$i<count($saved_modules); $i++ ){
            $current_saved_module = $saved_modules[$i];
            $return_array[$current_saved_module->ID] = $current_saved_module->post_title;
        }
        return $return_array;
    }

    /**
     * Function to return a dummy image
     *
     * @param  int         $width            The image width
     * @param  int         $height           The image height
     * @param  string      $background_color The image background color
     * @param  string      $text_color       The text color
     * @param  string|null $text             The text
     *
     * @return string                        The default image URL
     */
    public function getDefaultImageSrc( int $width, int $height, string $background_color = '000', string $text_color = 'fff', ?string $text = null){
        // The image URL
        $image_url = 'https://dummyimage.com';
        // The image array src
        $image_src_array = array();
        // Add the domain
        array_push($image_src_array, $image_url );
        // Add the dimensions
        array_push($image_src_array, $width . 'x' . $height );
        // Add the background color
        array_push($image_src_array, $background_color);
        // Add the foreground color
        array_push($image_src_array, $text_color);
        // If we have text
        if ( $text ){
            // Add the text color
            array_push( $image_src_array, '&text=' . $text );
        }
        // Make our url
        $image_string = implode( '/', $image_src_array );
        // Return the image string
        return $image_string;
    }

    /**
     * Method to get the module directory for registering new modules.
     *
     * @param  string $module_directory The directory of the moduel we're registering
     *
     * @return string The module directory
     */
    protected function getModuleDirectory( string $module_directory ){
        // Add the trailing slash
        return $module_directory . '/';
    }

    /**
     * Method to get the moduel URI for registering new modules
     *
     * @param  string $module_directory The directory of the moduel we're registering
     *
     * @return string The module directory
     */
    protected function getModuleDirectoryURI( string $module_directory ){
        // Get the theme directory
        $theme_directory  = get_stylesheet_directory();
        // Get the theme uri
        $theme_uri        = get_stylesheet_directory_uri();
        // Get the module directory relative to the theme root
        $module_directory = str_replace($theme_directory, $theme_uri, $module_directory);
        // Add the trailing slash
        return $module_directory . '/';
    }

    /**
     * Method to determine if we're vieiwng as the themer layout
     *
     * @return boolean True if viewing as themer layout
     */
    public function isViewingAsThemerLayout(){
        return get_post_type( get_the_ID() ) === self::FL_LAYOUT_POST_TYPE;
    }

     /**
     * Method to determine if we're vieiwng as the themer layout
     *
     * @return boolean True if viewing as themer layout
     */
     public function isViewingInEditor(){
        return is_user_logged_in() && strpos($_SERVER['REQUEST_URI'], "fl_builder" ) !== false;
    }

     /**
     * Method to determine if we're vieiwng as the themer layout
     *
     * @return boolean True if viewing as themer layout
     */
     public function isViewingInThemerLayout(){
        return is_user_logged_in() && strpos($_SERVER['REQUEST_URI'], "fl-theme-layout" ) !== false;
    }

    /**
     * Method used to get the typography array from the typograhy setting.
     *
     * @param  string $key The key used for the settings
     *
     * @return array      An array of settings
     */
    public function getTypography( string $key ){

        // Declare our return
        $return_array = array();
        // Get the typography settings
        $typography_settings = is_array( $this->settings->$key) ? $this->settings->$key : array();
        // Add the font family
        $this->addTypographySettingToReturnArray( $return_array, 'font-family', $typography_settings, 'font_family' );
        // Add the font weight
        $this->addTypographySettingToReturnArray( $return_array, 'font-weight', $typography_settings, 'font_weight' );
        // Add the text align
        $this->addTypographySettingToReturnArray( $return_array, 'text-align', $typography_settings, 'text_align' );
        // Add the text transform
        $this->addTypographySettingToReturnArray( $return_array, 'text-transform', $typography_settings, 'text_transform' );
        // Add the font style
        $this->addTypographySettingToReturnArray( $return_array, 'font-style', $typography_settings, 'font_style' );
        // Add the font variant
        $this->addTypographySettingToReturnArray( $return_array, 'font-variant', $typography_settings, 'font_variant' );
        // Add the font size
        $this->addTypographyLengthAndUnitToReturnArray( $return_array, 'font-size', $typography_settings, 'font_size' );
        // Add the line height
        $this->addTypographyLengthAndUnitToReturnArray( $return_array, 'line-height', $typography_settings, 'line_height' );
        // Add the letting spacing
        $this->addTypographyLengthAndUnitToReturnArray( $return_array, 'letter-spacing', $typography_settings, 'letter_spacing' );
        // Add the text shadow
        $this->getTypographyTextShadow( $return_array, 'text-shadow', $typography_settings, 'text_shadow' );
        // Return our array
        return $return_array;
    }

    /**
     * Method to conditional add typography settings to the return array if it exists.
     *
     * @param array  &$return_array    The return array form the getTypography method
     * @param string $return_array_key The key to use for the setting if it exists
     * @param array  $settings_array   The typography settings array
     * @param string $setting_key      The key used for the setting we're looking for
     */
    private function addTypographySettingToReturnArray( array &$return_array, string $return_array_key = '', array $settings_array = array(), string $setting_key = '' ){
        // Only do something if the key exists
        if ( isset( $settings_array[ $setting_key ] ) && $settings_array[ $setting_key ] ){
            // If the value is either font weight or family, check that there is a non-default setting being used
            if ( $return_array_key === 'font-weight' || $return_array_key === 'font-family' ){
                // If the value is default, then don't do anything
                if ( strtolower( $settings_array[ $setting_key ] ) === 'default') {
                    return;
                }
            }
            $return_array[ $return_array_key ] = $settings_array[ $setting_key ];
        }
    }

    /**
     * Method to get the string used for a text shadow. This uses the settings from the typography setting.
     *
     * @param array  &$return_array    The return array form the getTypography method
     * @param string $return_array_key The key to use for the setting if it exists
     * @param array  $settings_array   The typography settings array
     * @param string $setting_key      The key used for the setting we're looking for
     *
     */
    protected function getTypographyTextShadow( array &$return_array, string $return_array_key = '', array $settings_array = array(), string $setting_key = '' ){
        // Make sure we have the basic settings. We need four elements (h, v, b, and color)
        if ( isset( $settings_array[ $setting_key ] ) ){
            // Require all values to make the text shadow
            if ( $settings_array[ $setting_key ]['horizontal'] && $settings_array[ $setting_key ]['vertical'] && $settings_array[ $setting_key ]['blur'] && $settings_array[ $setting_key ]['color'] ){
                // Add to the return array
                $return_array[ $return_array_key ] = sprintf( 
                    "%s %s %s %s",
                    $settings_array[ $setting_key ]['horizontal'],
                    $settings_array[ $setting_key ]['vertical'],
                    $settings_array[ $setting_key ]['blur'],
                    $settings_array[ $setting_key ]['color']
                );
            }
        }
    }

    /**
     * Method to get the length and unit string for a typography setting.
     *
     * @param array  &$return_array    The return array form the getTypography method
     * @param string $return_array_key The key to use for the setting if it exists
     * @param array  $settings_array   The typography settings array
     * @param string $setting_key      The key used for the setting we're looking for
     */
    protected function addTypographyLengthAndUnitToReturnArray( array &$return_array, string $return_array_key = '', array $settings_array = array(), string $setting_key = '' ){
        // Declare our length and unit array
        $length_unit_array = array();
        // Add the length, if it exists
        if ( isset( $settings_array[ $setting_key ] ) && isset( $settings_array[ $setting_key ]['length'] ) && $settings_array[ $setting_key ]['length'] ){
            array_push( $length_unit_array, $settings_array[ $setting_key ]['length'] );
        }
        // Add the unit, if it exists
        if ( isset( $settings_array[ $setting_key ] ) && isset( $settings_array[ $setting_key ]['unit'] ) && $settings_array[ $setting_key ]['unit'] ){
            array_push( $length_unit_array, $settings_array[$setting_key]['unit']);
        }
        // If we have settings, add them to the return
        if ( count( $length_unit_array ) > 0 && isset( $settings_array[ $setting_key ]['length'] ) && $settings_array[ $setting_key ]['length'] ){
            // For some reason, BB doesn't return the units for letter spacing
            if ( $setting_key === 'letter_spacing' ){
                array_push( $length_unit_array, 'px' );
            }
            $return_array[$return_array_key] = implode('', $length_unit_array);
        }
    }

    /**
     * Method to return a value from an array of settings from a custom module.
     *
     * @param  string $key          The key to look for in the settings
     * @param  string $unit         The unit
     *
     * @return string | null        Either the value suffixed with the unit or null
     */
    public function getModuleSettingWithUnits( string $key, string $unit = 'px' ){
        // Check for custom unit
        $custom_unit_name = $key . '_unit';
        if ( isset($this->settings->$custom_unit_name) ){
            $unit = $this->settings->$custom_unit_name;
        }
        else if ( !$unit ){
            $unit = 'px';
        }
        // Drop everything! If we have a connection and we're using our handy BBVO plugin, use that unit instead
        if ( isset( $this->settings->connections[$key] ) && isset( $this->settings->connections[$key]->settings ) && class_exists( 'HuemorDesigns\Plugins\BeaverBuilderVariableOptions\VariableOptions' ) && is_object( $this->settings->connections[$key]->settings )){
            $unit = HuemorDesigns\Plugins\BeaverBuilderVariableOptions\VariableOptions::getConnectionVariableUnit( $this->settings->connections[$key]->settings );
        }
        return isset( $this->settings->$key ) && $this->settings->$key ? "{$this->settings->$key}$unit" : null;
    }

    /**
     * Method to return a color from an array of settings from a custom module.
     *
     * @param  string $key      The key to look for in the settings
     * @param  string $option      The option color (for pp-color types)
     *
     * @return string | null           Either the color prefixed with a hex or null
     */
    public function getModuleSettingColor( string $key, string $option = null ){
        // First, check if we have the value for when we're looking for the normal value of the object (no option)
        if ( isset( $this->settings->$key ) && $this->settings->$key && !is_array($this->settings->$key) && !$option){
            // Check if this is a hex value
            $hex_hash = ctype_xdigit($this->settings->$key) ? '#' : '';
            // Return the value
            return "$hex_hash{$this->settings->$key}";
        }
        // Otherwise, if we're looking for a specific option
        if ( isset( $this->settings->$key[$option] ) && $this->settings->$key[$option] && is_array($this->settings->$key) && $option){
            // Check if this is a hex value
            $hex_hash = ctype_xdigit($this->settings->$key[$option]) ? '#' : '';
            // Return the value
            return "$hex_hash{$this->settings->$key[$option]}";
        }
        // Otherwise, we have nothing
        else {
            return null;
        }
    }

    /**
     * Method to return a font family from an array of settings from a custom module.
     *
     * @param  string $key          The key to look for in the settings
     *
     * @return string | null       
     */
    public function getModuleSettingFontFamily( string $key){
        return isset( $this->settings->$key['family'] ) && $this->settings->$key['family'] ? $this->settings->$key['family'] : null;
    }


    /**
     * Method to return a font weight from an array of settings from a custom module.
     *
     * @param  string $key          The key to look for in the settings
     *
     * @return string | null       
     */
    public function getModuleSettingFontWeight( string $key){
        return isset( $this->settings->$key['weight'] ) && $this->settings->$key['weight']? $this->settings->$key['weight'] : null;
    }


    /**
     * Method to return a font weight from an array of settings from a custom module.
     *
     * @param  string $key          The key to look for in the settings
     *
     * @return string | null       
     */
    public function getModuleSettingWidthPercentage( string $key ){

        if ( isset( $this->settings->$key ) ){
            // Get the setting value
            $setting_value = $this->settings->$key;
            // Get the width
            $width = floor( ( 100 / $setting_value ) * 100 ) / 100;
            return $width . '%';
        }
        else {
            return null;
        }
    }

    /**
     * Method to get the module settings for a dimension
     *
     * @param  string  $key  The key
     * @param  string  $unit The default unit
     * @param  boolean $type If there's a specific type we're looking for (i.e., responsive)
     *
     * @return array        An array of dimension
     */
    public function getModuleSettingDimension( string $key, $unit = 'px', $type = false ){
        if ( $type ){
            $custom_unit_name = $key . '_' . $type . '_unit';
        }
        else {
            $custom_unit_name = $key . '_unit';
        }
        if ( isset($this->settings->$custom_unit_name) ){
            $unit = $this->settings->$custom_unit_name;
        }
        if ( $type ){
            $top_dimension    = $key . '_top' . '_' . $type;
            $right_dimension  = $key . '_right' . '_' . $type;
            $bottom_dimension = $key . '_bottom' . '_' . $type;
            $left_dimension   = $key . '_left' . '_' . $type;
        }
        else {
            $top_dimension    = $key . '_top';
            $right_dimension  = $key . '_right';
            $bottom_dimension = $key . '_bottom';
            $left_dimension   = $key . '_left';
        }
        // Declare our dimension array
        $dimension_array = array();
        // Add our top dimension
        array_push($dimension_array, $this->getModuleSettingWithUnits( $top_dimension, $unit ) );
        // Add our right dimension
        array_push($dimension_array, $this->getModuleSettingWithUnits( $right_dimension, $unit ) );
        // Add our bottom dimension
        array_push($dimension_array, $this->getModuleSettingWithUnits( $bottom_dimension, $unit ) );
        // Add our left dimension
        array_push($dimension_array, $this->getModuleSettingWithUnits( $left_dimension, $unit ) );
        // Return the string
        return implode(' ', $dimension_array);
    }


    public function renderModuleCSSResponsiveMobile( array $custom_css = array(), bool $echo = true, bool $debug = false  ){
        $global_settings = FLBuilderModel::get_global_settings();
        $this->renderModuleCSSResponsive( 
            array(
                'max' => $global_settings->responsive_breakpoint - 1
            ), 
            $custom_css, 
            $echo, 
            $debug 
        );
    }

    public function renderModuleCSSResponsiveTablet( array $custom_css = array(), bool $echo = true, bool $debug = false  ){
        $global_settings = FLBuilderModel::get_global_settings();
        $this->renderModuleCSSResponsive( 
            array(
                'min' => $global_settings->responsive_breakpoint - 1,
                'max' => $global_settings->medium_breakpoint - 1
            ), 
            $custom_css, 
            $echo, 
            $debug 
        );
    }

    public function renderModuleCSSResponsiveDesktop( array $custom_css = array(), bool $echo = true, bool $debug = false  ){
        $global_settings = FLBuilderModel::get_global_settings();
        $this->renderModuleCSSResponsive( 
            array(
                'min' => $global_settings->medium_breakpoint
            ), 
            $custom_css, 
            $echo, 
            $debug 
        );
    }

    public function renderModuleCSSResponsive( array $responsive_dimensions, array $custom_css = array(), bool $echo = true, bool $debug = false ){
        // Get the minimum width
        $min_width = isset( $responsive_dimensions['min'] ) ? $responsive_dimensions['min'] : null;        
        // Get the maximum width
        $max_width = isset( $responsive_dimensions['max'] ) ? $responsive_dimensions['max'] : null;     
        // Create our media query argument array
        $media_query_array = array();
        // If we have a min width, add that to the array
        if ( isset( $responsive_dimensions['min'] ) ){
            array_push($media_query_array, "(min-width: " . $responsive_dimensions['min'] . "px)");
        }
         // If we have a max width, add that to the array
        if ( isset( $responsive_dimensions['max'] ) ){
            array_push($media_query_array, "(max-width: " . $responsive_dimensions['max'] . "px)");
        }
        // Create a string we can use
        $media_query_string = implode(' and ', $media_query_array);
        // Declare our indent
        $indent = $debug ? '    ' : '';
        // Declare our seperator
        $seperator = $debug ? '<br>' : '';
        // Declare our return string
        $return_string = '@media screen and ' . $media_query_string . '{' . $seperator;
        // Render the CSS
        $return_string .=  $indent . $this->renderModuleCSS( $custom_css, 0, $debug );
        // Add the closing bracket for the query
        $return_string .= $seperator . "}";

        // If we want to echo the return string.
        if ($echo){
            echo $return_string;
        }
        // Otherwise return the string
        else {
            return $return_string;
        }

        if ($debug){
            echo "</pre>";
            die;
        }
    }

    /**
     * Method to render the custom CSS for the module
     *
     * @param  array  $custom_css The custom CSS
     *
     * @return void
     */
    public function renderModuleCSS( array $custom_css = array(), bool $echo = true, bool $debug = false ){
        // Get the ID for this element
        $element_id = $this->node;
        if ( $echo ){
            // Render the CSS
            self::renderCustomCSS( $element_id, $custom_css, $echo, $debug );
        }
        else {
            // Return the CSS
            return self::renderCustomCSS( $element_id, $custom_css, $echo, $debug );
        }
    }

    public static function renderCustomCSSResponsive( array $responsive_dimensions, string $element_id, array $custom_css = array(), bool $echo = true, bool $debug = false ){
        // If debug is true, then echo must be true too
        if ( $debug && !$echo ){
            $echo = true;
        }
        // Get the minimum width
        $min_width = isset( $responsive_dimensions['min'] ) ? $responsive_dimensions['min'] : null;        
        // Get the maximum width
        $max_width = isset( $responsive_dimensions['max'] ) ? $responsive_dimensions['max'] : null;     
        // Create our media query argument array
        $media_query_array = array();
        // If we have a min width, add that to the array
        if ( isset( $responsive_dimensions['min'] ) ){
            array_push($media_query_array, "(min-width: " . $responsive_dimensions['min'] . "px)");
        }
        // If we have a max width, add that to the array
        if ( isset( $responsive_dimensions['max'] ) ){
            array_push($media_query_array, "(max-width: " . $responsive_dimensions['max'] . "px)");
        }
        // Create a string we can use
        $media_query_string = implode(' and ', $media_query_array);
        // Declare our indent
        $indent = $debug ? '    ' : '';
        // Declare our seperator
        $seperator = $debug ? '<br>' : '';
        // Declare our return string
        $return_string = '@media screen and ' . $media_query_string . '{' . $seperator;
        // Render the CSS
        $return_string .=  $indent . self::renderCustomCSS( $element_id, $custom_css, 0, $debug );
        // Add the closing bracket for the query
        $return_string .= $seperator . "}";
        // If we want to echo the return string.
        if ($echo){
            echo $return_string;
        }
        // Otherwise return the string
        else {
            return $return_string;
        }

        if ($debug){
            echo "</pre>";
            die;
        }
    }


    /**
    * Function to render the custom CSS for the module
    *
    * @param  array  $custom_css [description]
    *
    * @return void
    */
    public static function renderCustomCSS( string $element_id, array $custom_css = array(), bool $echo = true, bool $debug = false ){
        // Don't do anyhing if the array is empty
        if ( count($custom_css) < 1){
            return;
        }
        // Create a new RecursiveIteratorIterator object
        $custom_css_iterator = new \RecursiveIteratorIterator( new \RecursiveArrayIterator($custom_css) );
        // Create the array we'll store info in
        $custom_css_formatted = array();
        // Run through each of the CSS properties
        foreach ($custom_css_iterator as $property_name => $property_value) {
            // Don't both doing anything if the property is null
            if ( !$property_value ){
                continue;
            }
            // Our multiple property array
            $multiple_value_property = false;
            // Start by getting the selector we're working with
            $selector = array();
            // Find all ancestral keys
            for ( $i=0; $i<$custom_css_iterator->getDepth(); $i++ ) {
                // Get the parent selector
                $parent_selector = $custom_css_iterator->getSubIterator($i)->key();
                // Get the first character of the selector
                $parent_selector_first_char = $parent_selector[0];
                // If the selector is a valid CSS selector (in the self::RENDER_MODULE_CSS_SELECTORS array) then
                // add to the selector array
                if ( in_array($parent_selector_first_char, self::RENDER_MODULE_CSS_SELECTORS) || in_array($parent_selector, self::RENDER_MODULE_CSS_HTML_TAGS)){
                    // Add the parent selector to the selector array
                    array_push($selector, $parent_selector);
                }
                // Otherwise, we must be working with a property that has multiple values
                else {
                    // This is a multiple value property
                    $multiple_value_property = $parent_selector;
                }
            }
            // Turn the selector into a string
            $selector = implode(' ', $selector);
            // Replace apersands
            $selector = str_replace(" &", "", $selector);
            // If the selector isn't already in the array
            if ( !array_key_exists($selector, $custom_css_formatted) ){
                // Add the selector
                $custom_css_formatted[$selector] = array();
            }
            // If this is a vanilla k/v property
            if ( !$multiple_value_property ){
                // Add the property and property value. Use array push to add support for properties with multiple
                // values
                $custom_css_formatted[$selector][$property_name] = $property_value;
            }
            // Otherwise this is a multiple value property
            else {
                // Make sure we have an array at this property
                if ( !is_array($custom_css_formatted[$selector][$multiple_value_property]) ){
                    $custom_css_formatted[$selector][$multiple_value_property] = array();
                }
                // Add the multiple value to this property
                array_push($custom_css_formatted[$selector][$multiple_value_property], $property_value);
            }
        }
        // Declare our return string
        $return_string = '';
        // Declare our seperator
        $seperator = $debug ? '<br>' : '';
        // Declare our indent
        $indent = $debug ? '    ' : '';
        // Loop through the formatted array
        foreach ($custom_css_formatted as $selector => $properties) {
            // Create the selector and node
            $selector_and_node = ".fl-node-$element_id $selector";
            // Replace ampersands
            $selector_and_node = str_replace(" &", "", $selector_and_node);
            // Start the selector
            $return_string .= "$selector_and_node {" . $seperator;
            $property_node = isset( $properties[$i] ) ? $properties[$i] : null;
            foreach ($properties as $property_key => $property_value) {
                // If the property value is not an array, this this property can be echoed
                if ( !is_array($property_value) ){
                    $return_string .= "$indent$property_key: $property_value;$seperator";
                }
                // Otherwise, we have more than one property value we need to echo
                else {
                    // Loop through keys
                    foreach ($property_value as $property_multiple_value) {
                        $return_string .= "$indent$property_key: $property_multiple_value;$seperator";
                    }
                }
            }
            // End the selector
            $return_string .=  "}$seperator$seperator";
        }
        if ($debug){
            echo "<pre>";
        }

        // If we want to echo the return string.
        if ($echo){
            echo $return_string;
        }
        // Otherwise return the string
        else {
            return $return_string;
        }

        if ($debug){
            echo "</pre>";
            die;
        }
    }

}
