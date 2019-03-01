<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;

use FLBuilderWhiteLabel;

class VariableOptions {

    const THEME_MOD_KEY_PREFIX       = 'beaver_builder_variable_option_';
    
    const BB_SETTING_FIELD_PREFIX    = 'bbvo_variable_';
    
    const BB_UNIT_FIELD_PREFIX       = '_bb_variable_unit';
    
    const BB_SETTING_USING_VARIABLE  = '_is_using_bb_variable';
    
    const BB_SETTING_ORIGINAL        = '_bb_variable_original_setting';
    
    const BB_SETTING_VARIABLE        =  '_bb_variable_value';
    
    const BB_SETTING_VARIABLE_CUSTOM =  'custom';

    const VARIABLE_UNIT_TYPES        = array(
        'px' => array(
            'title'                     => 'Pixels (px)',
            'title_repeater'            => 'Pixel Variables',
            'bb_field_type_eligibility' => array(
                'text'
            ),
            'unit_type' => array(
                'bbvo_pixel'
            ),
            'customizer_unit_type'   => 'number',
            'connection_unit_prefix' => null,
            'connection_unit_suffix' => 'px',

        ),
        'color_hex' => array(
            'title'                     => 'Color (hex)',
            'title_repeater'            => 'Hex Color',
            'bb_field_type_eligibility' => array(
                
            ),
            'unit_type' => array(
                'bbvo_color_hex'
            ),
            'customizer_unit_type' => 'color',
            'connection_unit_prefix' => null,
            'connection_unit_suffix' => null,
        ),
        'dual_color_hex' => array(
            'title'                     => 'Color (Dual color hex)',
            'title_repeater'            => 'Color (Dual color)',
            'bb_field_type_eligibility' => array(
                
            ),
            'unit_type' => array(
                'bbvo_color_pp_color_hex'
            ),
            'customizer_unit_type'               => 'color',
            'connection_unit_prefix'             => null,
            'connection_unit_suffix'             => null,
            'customizer_custom_callback'         => 'addCustomizerSectionPPColorDualHex'
        )
    );

    public static function init(){

        self::registerHooks();
    }

    public static function getFieldNameSuffixSettingVariableName(){
        return self::BB_SETTING_VARIABLE;
    }

    public static function getFieldNameSuffixIsUsingVariable(){
        return self::BB_SETTING_USING_VARIABLE;
    }

    public static function getFieldNameSuffixOriginalSetting(){
        return self::BB_SETTING_ORIGINAL;
    }

    public static function getFieldNameSuffixFieldPrefix(){
        return self::BB_UNIT_FIELD_PREFIX;
    }

    public static function getFieldNameCustom(){
        return self::BB_SETTING_VARIABLE_CUSTOM;
    }


    public static function registerHooks(){
        add_filter( 'all_plugins', array( __CLASS__ , 'updatePluginName' ) );
    }

    public static function updatePluginName( $all_plugins ){

        $plugin_basename =  plugin_basename( BBVO_FILE_MAIN );

        if ( isset( $all_plugins[ $plugin_basename ] ) && $all_plugins[ $plugin_basename ]['Name']) {

            $all_plugins[ $plugin_basename ]['Name'] = self::getBeaverBuilderWhiteLabelName() . " Variable Options";

        }

        return $all_plugins;

    }

    public static function getConnectionVariableUnit( object $connection_setting = object ){
        return isset($connection_setting->bbvo_variable_unit) ? $connection_setting->bbvo_variable_unit : null;
    }

    public static function getVariablesByUnitType( $unit = '' ){

        $mods = get_theme_mod( self::THEME_MOD_KEY_PREFIX . $unit );

        return is_array( $mods ) ? $mods : array();
    }

    public static function getBeaverBuilderWhiteLabelName(){

        if ( class_exists( 'FLBuilderWhiteLabel' )){
            return FLBuilderWhiteLabel::get_branding();
        }
        else {
            return "Beaver Builder";
        }
    }

}