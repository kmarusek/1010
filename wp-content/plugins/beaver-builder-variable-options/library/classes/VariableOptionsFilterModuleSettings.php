<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;

/**
 * Class used to actually render the connected variable value with the module.
 */
class VariableOptionsFilterModuleSettings extends VariableOptions {

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

        add_filter( 'filter_variable_value_color_hex', array( __CLASS__, 'filterNodeSettingsColorHex'), 10,  1 );

        add_filter( 'filter_variable_value_dual_color_hex', array( __CLASS__, 'filterNodeSettingsDualColorHex'), 10,  1 );

    }

    public static function returnVariableValue( object $settings, array $node ){
        // Get the variable name
        $variable_name          = $settings->bbvo_variable_name;
        // Get the variable name
        $variable_unit          = $settings->bbvo_variable_unit;
        // Get the value
        $setting_variable_value = self::getSettingVariableValue( $variable_name, $variable_unit );
        // Return the value
        return $setting_variable_value;
    }

    /**
     * Method used to get the value of a specific variable by unit type.
     *
     * @param  string $setting_variable_id The variable ID
     * @param  string $setting_unit        The unit for the setting
     *
     * @return string                      The value of the variable
     */
    public static function getSettingVariableValue( $setting_variable_id, $setting_unit ){
        // Get all current variable registered to this unit type
        $available_variable_for_unit_type = self::getVariablesByUnitType( $setting_unit );
        // Declare our sentinel
        $located_value = false;
        // Declare our index
        $i = 0;
        while ( !$located_value && $i < count( $available_variable_for_unit_type) ) {
            // Get the current variable
            $current_variable = $available_variable_for_unit_type[$i];
            if ( $current_variable['variable_name'] === $setting_variable_id ){
                // Most registered variables will be straightforward (one value)
                if ( isset( $current_variable['variable_value']) ){
                    return apply_filters( 'filter_variable_value_' . $setting_unit , $current_variable['variable_value'] ) ;
                    $located_value = true;
                }
                // But some might be multidimensional 
                else {
                    return apply_filters( 'filter_variable_value_' . $setting_unit , $current_variable ) ;
                    $located_value = true;
                }
            }
            $i++;
        }
    }


    /**
     * Method used for special handeling of the dual color hex tuple.
     *
     * @param  array $current_variable The current variable
     *
     * @return array                   Our tuple (kind of)
     */
    public static function filterNodeSettingsDualColorHex( $current_variable ){
        return array (
            'primary'   => self::filterNodeSettingsColorHex( $current_variable['variable_value_primary'] ),
            'secondary' => self::filterNodeSettingsColorHex( $current_variable['variable_value_secondary'] )
        );
    }

    /**
     * Method used as a filter for the color hex variable values
     *
     * @param  string $variable_value The color variable
     *
     * @return string                The filtered value
     */
    public static function filterNodeSettingsColorHex( $variable_value ){

        // If the first character is a hex, then remove it
        if ( substr( $variable_value, 0, 1) === '#' ){
            return substr( $variable_value, 1);
        }
        else {
            return $variable_value;
        }
    }
}








