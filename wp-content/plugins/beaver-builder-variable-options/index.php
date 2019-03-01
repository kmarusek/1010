<?php

namespace HuemorDesigns\Plugins\BeaverBuilderVariableOptions;

/**
 * Plugin Name: Beaver Builder Variable Options
 * Version: 1.0.0
 * Description: Use variables in your layouts.
 * Author: Huemor
 * Author URI: https://huemor.rocks
 **/

// Require the Composer autoloader
$composer_loader = require_once __DIR__ . '/library/vendor/autoload.php';
// Register our dependency classes
$composer_loader->addPsr4( __NAMESPACE__ . '\\' , __DIR__ . '/library/classes/' );

define( 'BBVO_FILE_MAIN',  __FILE__ );

// Init the customizer (the class will handle registering the hooks, so it's safe
// to init this immediatly)
VariableOptions::init();
// In the future, options should be housed in the Beaver Builder plugin.
// VariableOptionsBeaverBuilderSettings::init();
VariableOptionsCustomizer::init();
VariableOptionsRegisterUnitSettings::init();
VariableOptionsFilterModuleSettings::init();