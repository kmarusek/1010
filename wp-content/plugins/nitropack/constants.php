<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

use \NitroPack\SDK\Filesystem;

function nitropack_trailingslashit($string) {
    return rtrim( $string, '/\\' ) . '/';
}

define( 'NITROPACK_VERSION', '1.7.1' );
define( 'NITROPACK_OPTION_GROUP', 'nitropack' );

if (!defined("NITROPACK_USE_REDIS")) define("NITROPACK_USE_REDIS", false); // Set this to true to enable storing cache in Redis
if (!defined("NITROPACK_REDIS_HOST")) define("NITROPACK_REDIS_HOST", "127.0.0.1"); // Set this to the IP of your Redis server
if (!defined("NITROPACK_REDIS_PORT")) define("NITROPACK_REDIS_PORT", 6379); // Set this to the port of your Redis server
if (!defined("NITROPACK_REDIS_PASS")) define("NITROPACK_REDIS_PASS", NULL); // Set this to the password of your redis server if authentication is needed
if (!defined("NITROPACK_REDIS_DB")) define("NITROPACK_REDIS_DB", NULL); // Set this to the number of the Redis DB if you'd like to not use the default one

if (!defined("NITROPACK_CACHE_DIR_NAME")) define("NITROPACK_CACHE_DIR_NAME", substr(md5(__FILE__), 0, 7) . "-nitropack");

$oldNitroDir = nitropack_trailingslashit(WP_CONTENT_DIR) . 'nitropack';
$newNitroDir = nitropack_trailingslashit(WP_CONTENT_DIR) . 'cache/' . NITROPACK_CACHE_DIR_NAME;
$nitroDir = $newNitroDir;
$nitroDirMigrated = false;

if ( !Filesystem::fileExists($newNitroDir) && Filesystem::fileExists($oldNitroDir) && !NITROPACK_USE_REDIS) {
	// Existing installation, move to the new location
	if (Filesystem::createDir(dirname($newNitroDir)) && Filesystem::rename($oldNitroDir, $newNitroDir)) {
		$nitroDirMigrated = true;
	} else {
		define('NITROPACK_DATA_DIR_WARNING', 'Unable to initialize cache dir because the PHP user does not have permission to create/rename directories under wp-content/. Running in legacy mode. Please contact support for help.');
		$nitroDir = $oldNitroDir;
	}
}

define( 'NITROPACK_DATA_DIR', $nitroDir );
define( 'NITROPACK_CONFIG_FILE', nitropack_trailingslashit(NITROPACK_DATA_DIR) . 'config.json' );
define( 'NITROPACK_PLUGIN_DIR', nitropack_trailingslashit(dirname(__FILE__)));
define( 'NITROPACK_CLASSES_DIR', nitropack_trailingslashit(NITROPACK_PLUGIN_DIR . 'classes') );
define( 'NITROPACK_HEARTBEAT_INTERVAL', 60*5); // 5min

add_action( 'plugins_loaded', function() {
	define( 'NITROPACK_PLUGIN_DIR_URL', nitropack_trailingslashit( plugin_dir_url( __FILE__ ) ));
}, 1 );

if (!defined("NITROPACK_SUPPORT_BUBBLE_VISIBLE")) define("NITROPACK_SUPPORT_BUBBLE_VISIBLE", true);
if (!defined("NITROPACK_SUPPORT_BUBBLE_URL")) define("NITROPACK_SUPPORT_BUBBLE_URL", "https://support.nitropack.io/");

spl_autoload_register(function($class) {
    $filename = str_replace("\\", "/", $class) . ".php";
    $filename = str_replace("NitroPack/", "", $filename);
    $filepath = NITROPACK_CLASSES_DIR . ltrim($filename, "/");
    if (file_exists($filepath)) {
        require_once $filepath;
    }
});

if (Filesystem::fileExists(NITROPACK_CONFIG_FILE) && $nitroDirMigrated) {
	// Update the config_path according to the new location of the file.
	// Otherwise it will be ignored later and the plugin will appear disconnected.
	(function() {
		$config = new NitroPack\WordPress\Config();
		$config->updateConfigPath();
	})();
}