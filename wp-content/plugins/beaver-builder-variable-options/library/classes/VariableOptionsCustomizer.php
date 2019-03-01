<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;

use Kirki;

/**
 * Class used to render the UI for creating new variables under the customizer.
 */
class VariableOptionsCustomizer extends VariableOptions {

    const KIRKI_THEME_CONFIG_ID = 'beaver_builder_variable_options_customizer';

    /**
     * All of our init functions.
     *
     * @return void
     */
    public static function init(){
        // Register all of the hooks
        self::registerHooks();
    }

    /**
     * Functions to add all of our actions and filters.
     *
     * @return void
     */
    public static function registerHooks(){

        add_action( 'init', array( __CLASS__, 'addCustomizerSection' ) );

    }

    /**
     * Method to add our customizer section where we'd add our variables
     * UI.  
     *
     * @param object $wp_customize The WP Customizer object
     */
    public static function addCustomizerSection( $wp_customize ){

        Kirki::add_config( self::KIRKI_THEME_CONFIG_ID, array(
            'capability'    => 'edit_theme_options',
            'option_type'   => 'theme_mod',
        ) );

        // Get the name for the plugin
        $plugin_name = self::getBeaverBuilderWhiteLabelName();

        Kirki::add_panel( 'beaver_builder_variable_options', array(
            'priority'    => 9000,
            'title'    => __($plugin_name . ' Variables' )
        ) );

        foreach ( self::VARIABLE_UNIT_TYPES as $variable_key => $variable_value ) {

            $section_name = 'bbvo_section_' . $variable_key;

            Kirki::add_section( $section_name, array(
                'title' => $variable_value['title'],
                'panel' => 'beaver_builder_variable_options'
            ) );

            if ( !isset($variable_value['customizer_custom_callback']) ){

                Kirki::add_field( self::KIRKI_THEME_CONFIG_ID, [
                    'type'        => 'repeater',
                    'label'       => esc_html__( $variable_value['title_repeater'] ),
                    'section'     => $section_name,
                    'priority'    => 10,
                    'row_label' => [
                        'type'  => 'text',
                        'value' => esc_html__('Variable' ),
                    ],
                    'button_label' => esc_html__( 'Add variable' ),
                    'settings'     => self::THEME_MOD_KEY_PREFIX . $variable_key,
                    'default'      => [
                    ],
                    'fields' => [
                        'variable_name' => [
                            'type'        => 'text',
                            'label'       => esc_html__( 'Variable Name' ),
                            'description' => esc_html__( 'This is the name of your variable. Choose this name carefully – this cannot be changed after being created.' ),
                            'default'     => '',
                        ],
                        'variable_value'  => [
                            'type'        => $variable_value['customizer_unit_type'],
                            'label'       => esc_html__( 'Variable Value' ),
                            'description' => esc_html__( 'This is the value of your variable.' ),
                            'default'     => '',
                        ]
                    ]
                ]);
            }
            else {
                self::{$variable_value['customizer_custom_callback']}( $section_name, $variable_key, $variable_value );
            }
        }
    }

    public static function addCustomizerSectionPPColorDualHex( $section_name, $variable_key, $variable_value ){

        Kirki::add_field( self::KIRKI_THEME_CONFIG_ID, [
            'type'        => 'repeater',
            'label'       => esc_html__( $variable_value['title_repeater'] ),
            'section'     => $section_name,
            'priority'    => 10,
            'row_label' => [
                'type'  => 'text',
                'value' => esc_html__('Variable' ),
            ],
            'button_label' => esc_html__( 'Add variable' ),
            'settings'     => self::THEME_MOD_KEY_PREFIX . $variable_key,
            'default'      => [
            ],
            'fields' => [
                'variable_name' => [
                    'type'        => 'text',
                    'label'       => esc_html__( 'Variable Name' ),
                    'description' => esc_html__( 'This is the name of your variable. Choose this name carefully – this cannot be changed after being created.' ),
                    'default'     => '',
                ],
                'variable_value_primary'  => [
                    'type'        => $variable_value['customizer_unit_type'],
                    'label'       => esc_html__( 'Primary Color' ),
                    'description' => esc_html__( 'This is the value of your variable.' ),
                    'default'     => '',
                ],
                'variable_value_secondary'  => [
                    'type'        => $variable_value['customizer_unit_type'],
                    'label'       => esc_html__( 'Secondary Color' ),
                    'description' => esc_html__( 'This is the value of your variable.' ),
                    'default'     => '',
                ]
            ]
        ]);

    }
}