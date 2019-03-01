<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;

use FLPageData,FLBuilderModel;

/**
 * Class used to register all settings
 */
class VariableOptionsRegisterUnitSettings extends VariableOptions {

    const DIRECTORY_MODIFIED_SETTINGS              = __DIR__ . '/../includes/';
    
    const CLASS_SETTING_IS_ELIGIBLE_FOR_CONNECTION = 'bbvo-variable-has-connection';
    /**
     * All of our init functions.
     *
     * @return void
     */
    public static function init(){

        self::registerHooks();

    }

    /**
     * Functions to add all of our actions and filters.
     *
     * @return void
     */
    public static function registerHooks(){

        add_filter( 'fl_builder_register_settings_form', array( __CLASS__ , 'addVariableOptionsToExistingModuleSettings' ), 20, 2);

        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'addAdminAssets' ) );

        add_action( 'fl_page_data_add_properties', array( __CLASS__, 'registerConnections' ) );
        // Alter the UI for the settings form
        add_action( 'fl_builder_before_control', array( __CLASS__, 'addVariableNameToSettingsForm'), 20, 4 );
        // Alter the data object used for connections. This has to be after the control (because the with the connections
        // needs to be added before we alter it...duh)
        add_action( 'fl_builder_after_control', array( __CLASS__, 'updateFieldConnectionsObject'), 20, 4 );

    }

    public static function addConnectionClassToSetting( &$existing_setting ){
        if ( isset( $existing_setting['class'] ) ){
            if ( is_array( $existing_setting['class'] )){
                array_push( $existing_setting['class'], self::CLASS_SETTING_IS_ELIGIBLE_FOR_CONNECTION );
            }
            else {
                $existing_setting['class'] .= ' ' . self::CLASS_SETTING_IS_ELIGIBLE_FOR_CONNECTION;
            }
        }
        else {
            $existing_setting['class'] = ' ' . self::CLASS_SETTING_IS_ELIGIBLE_FOR_CONNECTION;
        }
    }

    /**
     * Method to change the data object to remove the posts options from the connections for fields where we have
     * registered our connections.
     *
     * @param string            $name     The name of the form field
     * @param string | array    $value    The current value of the form field
     * @param string            $field    The field settings
     * @param object            $settings All the field settings
     *
     * @return void 
     */
    public static function updateFieldConnectionsObject( $name, $value, $field, $settings ){
        $has_connection       = isset( $settings->connections ) && is_array( $settings->connections ) && count( $settings->connections ) > 0;
        $has_connection_class = isset( $field['class'] ) && strpos($field['class'], self::CLASS_SETTING_IS_ELIGIBLE_FOR_CONNECTION ) !== false;

        // Locate the connections
        if ( $has_connection_class ){
            echo "<script type=\"text/javascript\">beaverBuilderUpdateFieldConnectionsObject( '" . $name . "' );</script>";
        }
    }

    /**
     * Method to add the variable name to the settings form.
     *
     * @param string            $name     The name of the form field
     * @param string | array    $value    The current value of the form field
     * @param string            $field    The field settings
     * @param object            $settings All the field settings
     *
     * @return void 
     */
    public static function addVariableNameToSettingsForm( $name, $value, $field, $settings ){

        // Locate the connections
        $connections = isset($settings->connections) && is_array( $settings->connections ) ? $settings->connections : array();
        // If it's an object, than it's holding settings
        if ( isset( $connections[$name] ) && is_object( $connections[$name]) ){

            $variable_name  = $connections[$name]->settings->bbvo_variable_name;
            $variable_unit  = $connections[$name]->settings->bbvo_variable_unit;
            $varible_prefix = self::VARIABLE_UNIT_TYPES[$variable_unit]['connection_unit_prefix'];
            $varible_suffix = self::VARIABLE_UNIT_TYPES[$variable_unit]['connection_unit_suffix'];

            $setting_variable_value = VariableOptionsFilterModuleSettings::getSettingVariableValue( $variable_name, $variable_unit );
            // For the regular color
            if ( $variable_unit === 'color_hex' ){
                $varible_suffix =  htmlentities( 
                    sprintf(
                        '<span class="popover-color-preview" style="background-color:#%s;"></span>',
                        $setting_variable_value
                    )
                );
            }
            // Handeling for the dual hex
            else if ( $variable_unit === 'dual_color_hex' ){
                $varible_suffix =  htmlentities( 
                    sprintf(
                        '<span class="popover-color-preview" style="background: linear-gradient(to bottom,#%s 0%%,#%s 100%);"></span>',
                        $setting_variable_value['primary'],
                        $setting_variable_value['secondary']
                    )
                );
            }

            echo '<script type="text/javascript">beaverBuilderDidReloadNodeHook();</script>';
            echo sprintf('
                <div 
                class="hidden field-connection-variable-value" 
                data-variable-name="%s" 
                data-variable-prefix="%s"
                data-variable-value="%s"
                data-variable-suffix="%s"
                ></div>',
                $variable_name,
                $varible_prefix,
                is_array( $setting_variable_value ) ? implode(", ", $setting_variable_value) : $setting_variable_value,
                $varible_suffix
            );
        }
    }

    /**
     * Register the connections for each of the types of variables
     *
     * @return void 
     */
    public static function registerConnections(){

        FLPageData::add_group(
            'bbvo_variable_options', 
            array(
                'label' => 'Space Station Variables'
            )
        );

        // Loop through all the registered modified settings
        foreach (self::VARIABLE_UNIT_TYPES as $setting_field_key => $setting_field_values) {

            FLPageData::add_site_property(
                self::BB_SETTING_FIELD_PREFIX . $setting_field_key,
                array(
                    'label'   => $setting_field_values['title_repeater'],
                    'group'   => 'bbvo_variable_options',
                    'type'    => $setting_field_values['unit_type'],
                    'getter'  => __NAMESPACE__ . '\VariableOptionsFilterModuleSettings::returnVariableValue',
                )
            );

            // All settings for this unit type
            $all_available_settings = self::getAvailableSettingVariablesByUnitType($setting_field_key);

            // Declare our array of available variables
            $available_variables = array();

            // Loop though all settings and add them to the group
            for ($i=0; $i<count($all_available_settings); $i++){

                $current_setting = $all_available_settings[$i];

                $setting_variable_value = VariableOptionsFilterModuleSettings::getSettingVariableValue( $current_setting['variable_key'], $setting_field_key );
                if ( is_array( $setting_variable_value )){
                    $setting_variable_value = implode(", ", $setting_variable_value);
                }
                $varible_prefix = self::VARIABLE_UNIT_TYPES[$setting_field_key]['connection_unit_prefix'];
                $varible_suffix = self::VARIABLE_UNIT_TYPES[$setting_field_key]['connection_unit_suffix'];

                $available_variables[$current_setting['variable_key']] = $current_setting['variable_label'] . " ($varible_prefix$setting_variable_value$varible_suffix)";
            }

            $form = array(
                'bbvo_variable_name' => array(
                    'type'    => 'select',
                    'label'   => __( 'Variable', 'fl-theme-builder' ),
                    'class'   => 'bbvo-variable-name-input',
                    'options' => $available_variables
                ),
                'bbvo_variable_unit' => array(
                    'type'    => 'text',
                    'default' => $setting_field_key,
                    'class'   => 'bbvo-variable-unit-input',
                )
            );

            FLPageData::add_site_property_settings_fields( self::BB_SETTING_FIELD_PREFIX . $setting_field_key, $form );
        }

    }

    /**
     * Add the necessary CSS and JS for using this plugin.
     */
    public static function addAdminAssets(){

        if ( FLBuilderModel::is_builder_active() ) {

            wp_enqueue_style(
                'beaver-builder-variable-options-bootstrap-css',
                plugins_url( '/library/dist/vendor/bootstrap/css/bootstrap-grid.min.css', BBVO_FILE_MAIN )
            );


            wp_enqueue_script(
                'beaver-builder-variable-options-bootstrap-js',
                plugins_url( '/library/dist/vendor/bootstrap/js/bootstrap.min.js', BBVO_FILE_MAIN ),
                array(
                    'jquery'
                )
            );

            wp_enqueue_style(
                'beaver-builder-variable-options-css',
                plugins_url( '/library/dist/css/beaver-builder-variable-options.min.css', BBVO_FILE_MAIN )
            );

            wp_register_script(
                'beaver-builder-variable-options-js',
                plugins_url( '/library/dist/js/beaver-builder-variable-options.built.min.js', BBVO_FILE_MAIN ),
                array(
                    'jquery'
                )
            );

            wp_localize_script(
                'beaver-builder-variable-options-js',
                'bbvo_object',
                array(
                    'variable_option_key_custom'     => self::getFieldNameCustom(),
                    'variable_option_key_connection' => 'bbvo_variable_options',
                    'connection_remove_menu_items' => array(
                        'posts',
                        'author',
                        'user'
                    )
                )
            );

            wp_enqueue_script( 'beaver-builder-variable-options-js' );

        }
    }

    /**
     * Method to add variable options to existing modules.
     *
     * @param array     $form     The form we're adding options to
     * @param object    $instance The instance we're adding options to
     */
    public static function addVariableOptionsToExistingModuleSettings( $form, $instance ){

        // Loop through the array
        foreach ($form as $tab_key => $tab_values ) {
            if ( isset( $tab_values['sections'] ) ){
                // Loop through the sections
                foreach ($tab_values['sections'] as $section_key => $section_values ) {
                    // Loop through the fields
                    if ( isset( $section_values['fields'] ) ){
                        foreach ($section_values['fields'] as $fields_key => $fields_values ) {
                            // Only support for non-responsive fields
                            if ( !isset($fields_values['responsive']) || !$fields_values['responsive'] ){
                                // Get the field location
                                $field_location = &$form[$tab_key]['sections'][$section_key]['fields'][$fields_key];
                                // Switch through types to replace
                                switch ( $fields_values['type'] ) {

                                    // Update text
                                    case 'text':
                                    self::maybeAddModuleSettingConnectionTextPixel( $fields_values, $field_location );
                                    break;

                                    // Update unit
                                    case 'unit':
                                    self::maybeAddModuleSettingConnectionUnit( $fields_values, $field_location );
                                    break;

                                    // Update Colors
                                    case 'color':
                                    self::addModuleSettingConnectionColorHex( $field_location );
                                    break;

                                    // Update Gradients
                                    case 'pp-color':
                                    self::addModuleSettingConnectionPPColorGradient( $field_location );
                                    break;

                                    default: 
                                    // error_log( "Field type: " . $fields_values['type'] );
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $form;
    }

    /**
     * Method to get all variables of a specific registered type.
     *
     * @param  string $unit The unit we're getting variables for
     *
     * @return array An array of variables
     */
    public static function getAvailableSettingVariablesByUnitType( string $unit ){

        $return_array = array();
        // All available variables
        $variables = self::getVariablesByUnitType( $unit );

        for ( $i=0; $i<count($variables); $i++ ){
            // Get the current variable
            $current_variable = $variables[$i];
            array_push(
                $return_array, 
                array(
                    'variable_key'   => $current_variable['variable_name'],
                    'variable_label' => $current_variable['variable_name']
                ) 
            );
        }

        return $return_array;
    }

    /**
     * Method that may be used to add relevant conenctions to pixels. Since some modules are
     * of a text type, we only want to add this option if the description happens to be "px" 
     * (is that hacky? Maybe. But that's also hacky on the part of the module).
     *
     * @param  array $fields_values   The field values
     * @param  array &$field_location The field location
     *
     * @return void                  
     */
    public static function maybeAddModuleSettingConnectionTextPixel( $fields_values, &$field_location ){
        if ( isset($fields_values['description']) && $fields_values['description'] === 'px' ){
            self::addModuleSettingConnectionUnitPixel( $field_location );
        }
    }

    public static function maybeAddModuleSettingConnectionUnit( $fields_values, &$field_location ){
        if ( isset( $fields_values['units'] ) && is_array( $fields_values['units'] ) ) {
            // Our units
            $available_units = $fields_values['units'];
            // If the unit includes pixels
            if ( in_array( 'px' , $available_units ) ){
                self::addModuleSettingConnectionUnitPixel( $field_location );
            }
        }
    }

    /**
     * Method that is used to add connections to the pixel unit types
     *
     * @param  array &$field_location The field location
     *
     * @return void                  
     */
    public static function addModuleSettingConnectionUnitPixel( &$field_location ){
        $field_location['connections'] = array( 'bbvo_pixel' );
        self::addConnectionClassToSetting( $field_location );
    }

    /**
     * Method that is used to add connections to the color unit types
     *
     * @param  array &$field_location The field location
     *
     * @return void                  
     */
    public static function addModuleSettingConnectionColorHex( &$field_location ){
        $field_location['connections'] = array( 'bbvo_color_hex' );
        self::addConnectionClassToSetting( $field_location );
    }

    /**
     * Method that is used to add connections to the color gradient types
     *
     * @param  array &$field_location The field location
     *
     * @return void                  
     */
    public static function addModuleSettingConnectionPPColorGradient( &$field_location ){
        $field_location['connections'] = array( 'bbvo_color_pp_color_hex' );
        self::addConnectionClassToSetting( $field_location );
    }

    /**
     * Function to get the name of an altered Beaver Buider setting name for a specific
     * setting type and unit type.  
     *
     * @param  string $field_type The field type
     * @param  string $field_unit The field unit
     *
     * @return string             The setting name
     */
    public static function getVariableBeaverBuilderSettingName( $field_type, $field_unit ){

        return self::BB_SETTING_FIELD_PREFIX . $field_type . '_' . $field_unit;
    }

}