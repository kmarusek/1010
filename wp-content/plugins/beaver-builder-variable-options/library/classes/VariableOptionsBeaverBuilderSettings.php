<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;


class VariableOptionsBeaverBuilderSettings extends VariableOptions {

    public static function init(){
        // Register all of the hooks
        self::registerHooks();
    }

    public static function registerHooks(){
        add_filter( 'fl_builder_admin_settings_nav_items', array( __CLASS__, 'addBeaverBuilderAdminSetting' ), 10 );
        add_action( 'fl_builder_admin_settings_render_forms', array( __CLASS__, 'renderBeaverBuilderAdminForm' ), 10 );

        add_action( 'cmb2_admin_init', function(){

            $cmb = new_cmb2_box( array(
                'id'           => 'myprefix_option_metabox',
                'title'        => esc_html__( 'Site Options', 'myprefix' ),
                'object_types' => array( 'options-page' ),
            ) );

            $cmb->add_field( array(
                'name'       => 'Email',
                'id'         => 'wiki_email',
                'type'       => 'text_email',
            ) );

        });

    }

    public static function addBeaverBuilderAdminSetting( array $existing_settings = array() ){

        $existing_settings['beaver-builder-variable-settings'] = array(
            'title'    => 'Variables',
            'show'     => true,
            'priority' => 500
        );

        return $existing_settings;
    }

    public static function renderBeaverBuilderAdminForm(){

        include dirname( BBVO_FILE_MAIN ) . '/library/includes/admin-settings-beaver-builder-variables.php';

    }
}