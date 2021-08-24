<?php
// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );
define( 'FL_CHILD_THEME_URL_RELATIVE', get_stylesheet_directory_uri_relative() );
define( 'BEAVER_BUILDER_CACHE_BUST_QUERY_STRINGS', 
    array(
        '(.*)fl_builder(.*)',
        '(.*)fl_builder_load_settings_config(.*)'
    )
);

// Classes
require_once 'classes/lessc.inc.php';
require_once 'classes/class-bw-customizer-less.php';
require_once 'classes/beaverbuilder_integration.decl.php';
require_once 'classes/woocommerce-fixes.decl.php';

/**
 * Function used by .decl files that include a file if this site is 
 * using Beaver Builder. Additionally, it checks if the file
 * actually exists so we can avoid fatal errors for missing or
 * incomplete plugins.  
 *
 * Notably, this must be above the part of functions.php that 
 * include .decl files.
 *
 * @param  string $file_path The absolute file path
 *
 * @return void
 */
function register_beaver_warrior_module( string $file_path ){
    add_action ('init', function() use ( $file_path) {
        if ( class_exists("FLBuilder") && file_exists( $file_path ) ) {
            require_once $file_path;
        }
    }, 15);
}

//We support splitting functions.php into separate files, so do so whenever possible.
$theme_path = get_stylesheet_directory();

$dir = opendir($theme_path . "/components");
while ($dir !== FALSE && FALSE !== ($entry = readdir($dir))) {
    if (is_dir($theme_path . "/components/" . $entry)) {
        foreach (glob($theme_path . "/components/" . $entry . "/*.decl.php") as $file) {
            require_once "$file";
        }
    }
}

$dir2 = opendir($theme_path . "/layouts");
while ($dir2 !== FALSE && FALSE !== ($entry = readdir($dir2))) {
    if (is_dir($theme_path . "/layouts/" . $entry)) {
        foreach (glob($theme_path . "/layouts/" . $entry . "/*.decl.php") as $file) {
            require_once "$file";
        }
    }
}

// Some legacy sites might already include the BeaverWarriorFLModule class, so only include
// the following file if that class doesn't already exist
if ( class_exists( 'FLBuilder') && !class_exists( 'BeaverWarriorFLModule' ) ){
    if ( file_exists( get_stylesheet_directory() . '/classes/class-beaver-warrior-fl-module.php' )){
        require_once get_stylesheet_directory() . '/classes/class-beaver-warrior-fl-module.php';
    }
}

// Adds support for the downstream functions file. This should be below the inclusion of the BeaverWarriorFLModule
// class since functions in this class might referenec the BeaverWarriorFLModule class.
if ( file_exists( get_stylesheet_directory() . '/functions-downstream.php' ) ){
    require_once get_stylesheet_directory() . '/functions-downstream.php';
}

class SkeletonWarrior_Renderer {
    private $args;
    private $file;

    public function __get($name) {
        return $this->args[$name];
    }

    public function __construct($file, $args = array()) {
        $this->file = $file;
        $this->args = $args;
    }

    public function __isset($name){
        return isset( $this->args[$name] );
    }

    public function render() {
        //Pollute current scope with arguments.
        foreach ($this->args as $__k => $__v) {
            $$__k = $__v;
        }

        if( locate_template($this->file) ){
            include( locate_template($this->file) );//Theme Check free. Child themes support.
        }
    }
}

function get_template_component($component, $slug = null, $name = null, $args = array()) {
    if (!isset($slug)) {
        $slug = $component;
    }

    do_action( "get_template_part_{$component}/{$slug}", $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = "components/{$component}/{$slug}-{$name}.php";

    $templates[] = "components/{$component}/{$slug}.php";

    $template_renderer = new SkeletonWarrior_Renderer($templates, $args);
    $template_renderer->render();
}

function get_template_layout($layout, $slug = null, $name = null, $args = array()) {
    if (!isset($slug)) {
        $slug = $layout;
    }

    do_action( "get_template_part_{$layout}/{$slug}", $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = "layouts/{$layout}/{$slug}-{$name}.php";

    $templates[] = "layouts/{$layout}/{$slug}.php";

    $template_renderer = new SkeletonWarrior_Renderer($templates, $args);
    $template_renderer->render();
}

function skeletonwarrior_enqueue_scripts() {
    //wp_register_style('space-station-main', get_stylesheet_directory_uri() . '/build/main.css', false, null, "all");
    wp_register_style('space-station-main', BWCustomizerLess::css_url(), array("bootstrap"), null, "all");
    define( 'SCRIPTS_PATH_RELATIVE_TO_THEME', '/build/script.js' );
    // Default file version number
    $file_version = null;
    // Get the mod date of the scripts file
    if (file_exists(get_stylesheet_directory() . SCRIPTS_PATH_RELATIVE_TO_THEME )){
        // Get file version
        $file_version = filemtime(get_stylesheet_directory() . SCRIPTS_PATH_RELATIVE_TO_THEME);
    }

    wp_register_script('scripts', get_stylesheet_directory_uri() . SCRIPTS_PATH_RELATIVE_TO_THEME, array('jquery'), $file_version, true);

    //Vendored copy of Slick Slider
    wp_register_style('slick-slider', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick/slick.css', false, null, "all");
    wp_register_script('slick-slider', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick/slick.min.js', false, null, true);

    // Intersection Observer polyfill
    wp_register_script('intersection-observer-polyfill', get_stylesheet_directory_uri() . '/assets/vendor/google/polyfill/intersection-observer.js' );

    // Lottie web
    wp_register_script('lottie-web', get_stylesheet_directory_uri() . '/assets/vendor/airbnb/lottie-web/lottie.min.js', array(), false, true );
    wp_register_script('lottie-web-5-5-2', get_stylesheet_directory_uri() . '/assets/vendor/airbnb/lottie-web/lottie-5.5.2.min.js', array(), '5-5-2', true );
    
    wp_localize_script('scripts', 'scripts_data', apply_filters( 'bw_scripts_data', array() ) );
    wp_enqueue_script('scripts');
    wp_enqueue_style('space-station-main');
    
    //If you are not using Slick sliders, or any component functionality that
    //uses Slick sliders (such as StaffGrid with it's slider option) then it is
    //safe to remove the following enqueues in order to save ~43KB and two
    //pre-minifier requests. If you are not using PageTransition then it is safe
    //to conditionally enqueue these scripts instead.
    wp_enqueue_script('slick-slider');
    wp_enqueue_style('slick-slider');
}
add_action('wp_enqueue_scripts', 'skeletonwarrior_enqueue_scripts');

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

/* Allow SVG uploads.
 */
function skeletonwarrior_check_filetype_and_ext($data, $file, $filename, $mimes) {
    global $wp_version;
    if( $wp_version == '4.7' || ( (float) $wp_version < 4.7 ) ) {
        return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return array(
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    );
}
add_filter('wp_check_filetype_and_ext', 'skeletonwarrior_check_filetype_and_ext', 10, 4 );

/* Add SVG as a mime type.
 */
function skeletonwarrior_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'skeletonwarrior_mime_types' );

/* Ensure SVGs don't break the admin environment.
 */
function skeletonwarrior_fix_svg() {
  echo '<style type="text/css">
  .attachment-266x266, .thumbnail img {
   width: 100% !important;
   height: auto !important;
}
</style>';
}
add_action( 'admin_head', 'skeletonwarrior_fix_svg' );

/* Generates SRCSET attribute from an image structure.
 */
function skeletonwarrior_srcset($image) {
    $srcset = $image["url"] . " " . $image["width"] . "w";

    foreach ($image["sizes"] as $k => $v) {
        if (endsWith($k, "-width") || endsWith($k, "-height")) continue;

        $srcset .= ", " . $v . " " . $image["sizes"][$k . "-width"] . "w";
    }

    return $srcset;
}

/* Create a new WP_Query and set it as the default loop.
 */
function enter_post_archive($post_type, $addl_keys) {
    global $wp_query;
    global $wp_query_stack;

    if (!isset($wp_query_stack)) {
        $wp_query_stack = array();
    }

    array_push($wp_query_stack, $wp_query);

    if (!isset($addl_keys)) {
        $addl_keys = array();
    }

    $addl_keys["post_type"] = $post_type;

    $wp_query = new WP_Query($addl_keys);
};

/* Enter a single post object.
 */
function enter_post($new_post) {
    global $post;

    $post = $new_post;
};

/* Reset the state of the main loop after a call to enter_post_archive.
 *
 * You MUST call this function after you are finished with the secondary loop,
 * or things in WordPress will be broke.
 */
function exit_loop() {
    global $wp_query;
    global $wp_query_stack;

    $wp_query = array_pop($wp_query_stack);
    wp_reset_postdata();
}

/* Customizable login page.
 */
function skeletonwarrior_custom_login_image() {
    echo "<style>
    body.login #login h1 a {
        background: url('".get_stylesheet_directory_uri()."/assets/img/login_logo.svg') 8px 0 no-repeat transparent;
        background-position: center center;
        background-size:50%;
        height:150px;
        width:320px; }

        </style>";
    }
    add_action("login_head", "skeletonwarrior_custom_login_image");

/* Custom post type for widgets.
 */
function skeletonwarrior_widget_post_type() {
    register_post_type('skw_widget', array(
        'labels' => array(
            'name' => __("Widget Settings"),
            'singular_name' => __("Widget Setting"),
        ),
        'description' => __("Widget settings to be used by certain widget types.", 'skeleton_warrior'),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'menu_position' => 100,
        'menu_icon' => 'dashicons-schedule',
        'supports' => array('title', 'revisions'),
    ));
}
add_action("init", "skeletonwarrior_widget_post_type");

/* ACF JSON */
function skeletonwarrior_json_load_point($paths) {
    $theme_path = get_stylesheet_directory();
    
    if (is_dir($theme_path . "/assets/acf-fieldgroups")) {
        $paths[] = $theme_path . "/assets/acf-fieldgroups";
    }
    
    $dir = opendir($theme_path . "/components");
    while ($dir !== FALSE && FALSE !== ($entry = readdir($dir))) {
        if (is_dir($theme_path . "/components/" . $entry . "/acf-fieldgroups")) {
            $paths[] = $theme_path . "/components/" . $entry . "/acf-fieldgroups";
        }
    }
    
    $dir2 = opendir($theme_path . "/layouts");
    while ($dir2 !== FALSE && FALSE !== ($entry = readdir($dir2))) {
        if (is_dir($theme_path . "/layouts/" . $entry . "/acf-fieldgroups")) {
            $paths[] = $theme_path . "/layouts/" . $entry . "/acf-fieldgroups";
        }
    }
    
    return $paths;
}
add_filter('acf/settings/load_json', 'skeletonwarrior_json_load_point');

//add inline validation to comments on blog
function comment_validation_init() {
    if(is_singular() && comments_open() ) { ?>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#commentform').validate({
                    onfocusout: function(element) {
                        this.element(element);
                    },
                    rules: {
                        author: {
                            required: true,
                            minlength: 2,
                            normalizer: function(value) { return $.trim(value); }
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        comment: {
                            required: true,
                            minlength: 20,
                            normalizer: function(value) { return $.trim(value); }
                        }
                    },
                    messages: {
                        author: "Please enter in your name.",
                        email: "Please enter a valid email address.",
                        comment: "Message box can't be empty!"
                    },
                    errorElement: "div",
                    errorPlacement: function(error, element) {
                        element.before(error);
                    }
                });
            });
        </script>
    <?php }
}
add_action('wp_footer', 'comment_validation_init');


//Don't cache Beaver Builder.
function beaver_warrior_bb_cache_buster() {
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
}

// Only run if we have a query string to check
if ( isset($_SERVER['QUERY_STRING'] ){
    $clear_cache_sentinal = false;
    $clear_cache_index    = 0;
    while ( !$clear_cache_sentinal && $clear_cache_index < count(BEAVER_BUILDER_CACHE_BUST_QUERY_STRINGS) ){
        if (preg_match('/' . BEAVER_BUILDER_CACHE_BUST_QUERY_STRINGS[$clear_cache_index] .'/', $_SERVER['QUERY_STRING'])) {
            $clear_cache_sentinal = true;
        }
        $clear_cache_index++;
    }
    if ( $clear_cache_sentinal ){
        add_action('send_headers', 'beaver_warrior_bb_cache_buster', 15);
    }
}


//Add post thumbnail / featured image support
function beaver_warrior_theme_support() {
    add_theme_support("post-thumbnails");
}
add_action("after_setup_theme", "beaver_warrior_theme_support");

//Disable BB's builtin less compiler (we're replacing it lols)
function beaver_warrior_less_paths($lesssrc) {
    return [];
}

/**
 * Function to check if the Huemor dev pack constant is enabled and, if so, to add some herbs and spices
 * that'll help developers.
 *
 * @return void 
 */
function beaver_warrior_huemor_dev_pack(){
        // If the user is logged in and not on Pantheon
    if ( is_user_logged_in() && !isset( $_ENV['PANTHEON_ENVIRONMENT'] ) && defined( 'HUEMOR_DEV_PACK_ENABLED' ) && HUEMOR_DEV_PACK_ENABLED ) {
        // If debug is also enabled, then let's display all errors
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ){
            if ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ){
                @ini_set( 'display_errors', 1 );
                @ini_set( 'display_startup_errors', 1 );
            }
            @error_reporting( E_ALL );
        }
        // Clear the cache
        add_action( 'template_redirect' , 'beaver_warrior_clear_style_cache' );
    }
}

/**
 * Function to clear all the style caches every time a page is loaded.
 *
 * @return void
 */
function beaver_warrior_clear_style_cache(){
    // Get the current post
    $current_post_id = get_the_ID();
    // Remove layouts and partials
    exec("rm wp-content/uploads/bb-plugin/cache/$current_post_id-*");
    exec("rm wp-content/uploads/beaverwarrior/*");
}

/**
 * Function used to get the relative path to the theme director from the server root
 *
 * @return string The path
 */
function get_stylesheet_directory_uri_relative(){
    // Start by getting the stylesheet directory
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', get_stylesheet_directory() );
}

/**
 * Function to add the wildcard selector back into the CSS for the FLRichTextModule. Beaver Builder 
 * 2.2.2.1 removed this focus from this area, so this filter will effectively put it back in
 *
 * @param  string $css             The existing CSS
 * @param  object $nodes           All the nodes for the page
 * @param  object $global_settings The global settings
 * @param  boolean $include_global  Whether or not to include the global settings
 *
 * @return string               The CSS for the page
 */
function beaver_warrior_add_rich_text_wildcard_styles( $css, $nodes, $global_settings, $include_global ){
    // Only apply to modules
    if ( array_key_exists('modules', $nodes) ){
        // Loop through the modules
        foreach ($nodes['modules'] as $module_key => $module_values ) {
            // If the the values are actually an object and it's the Rich Text Module
            if ( is_object( $module_values) && is_a( $module_values, 'FLRichTextModule' ) ){
                // Get the element's node ID
                $module_id       = $module_key;
                // Get the settings. We need to reinstate the wildcard selector for this element
                $module_settings = isset( $module_values->settings ) ? $module_values->settings : new stdClass;
                // For the colors
                if ( ! empty( $module_settings->color ) ) {
                    // See if we need to as a hex
                    $hex_hash = ctype_xdigit($module_settings->color) ? '#' : '';
                    $css .= ".fl-node-$module_id .fl-rich-text * { color: " . $hex_hash . $module_settings->color . ";}";
                }
                // If we have any set typography
                if ( ! empty( $module_settings->typography ) ) {
                    if ( class_exists( 'FLBuilderCSS') && method_exists('FLBuilderCSS', 'typography_field_props') ){
                        $typography_array = FLBuilderCSS::typography_field_props( $module_settings->typography );
                        $typography_string = ".fl-node-$module_id .fl-rich-text * {";
                        // Loop through all of the values and add to the CSS
                        foreach ($typography_array as $style_property => $style_value ) {
                           // Only do anything with nonnull values
                            if ( $style_value ){
                                $typography_string .= $style_property . ':' . $style_value . ';';
                            }
                        }
                        // Add the closing bracket
                        $typography_string .= '}';
                        // Add the CSS
                        $css .= $typography_string;
                    }
                }
            }
        }
    }
    // Return the CSS no matter what
    return $css;
}

/**
 * Function to register a new custom image size with a retina version
 *
 * @param  string       $name   The name of the custom image size
 * @param  int          $width  The width
 * @param  int          $height The height
 * @param  bool|boolean $crop   Whether or not to crop
 *
 * @return void             
 */
function register_custom_image_size( string $name, int $width, int $height, bool $crop = true ){
    // Regular image
    add_image_size( $name, $width, $height, $crop );
    // 2x image
    add_image_size( $name . '_2x', $width * 2, $height * 2, $crop );
}

/**
 * Function to enqueue Sentry.io (used by the custom module frontend.js super class).
 *
 * @return void
 */
function beaver_warrior_add_sentry_io(){
    wp_register_script( 'sentry-io', 'https://browser.sentry-cdn.com/5.2.0/bundle.min.js');
    wp_localize_script(
        'sentry-io',
        'sentry_data',
        array(
            'url'         => get_site_url(),
            'environment' => $_ENV['PANTHEON_ENVIRONMENT'] ?? $_ENV['SERVER_NAME'] ?? ''
        )
    );
    wp_enqueue_script( 'sentry-io' );
}

add_action("fl_theme_compile_less_paths", "beaver_warrior_less_paths");

// Theme Actions
add_action( 'after_switch_theme',    'BWCustomizerLess::refresh_css' );
// Customizer
add_action( 'customize_preview_init',                    'BWCustomizerLess::preview_init' );
add_action( 'customize_controls_enqueue_scripts',        'BWCustomizerLess::controls_enqueue_scripts' );
add_action( 'customize_controls_print_footer_scripts',   'BWCustomizerLess::controls_print_footer_scripts' );
//add_action( 'customize_register',                        'BWCustomizerLess::register' );
add_action( 'customize_save_after',                      'BWCustomizerLess::save' );
// The Huemor Dev pack
add_action( 'init' , 'beaver_warrior_huemor_dev_pack' );
// Add the filter to make sure that rich-text styles are generiouslly applied to its children.
add_filter( 'fl_builder_render_css', 'beaver_warrior_add_rich_text_wildcard_styles', 10, 4 );
add_action( 'wp_enqueue_scripts', 'beaver_warrior_add_sentry_io');

/**
 * Convert an associative array of attribute keys and values into an HTML
 * attribute string.
 * 
 * The provided attributes may have string or array values. Arrays will be
 * joined with spaces, this allows, e.g. providing an array of CSS classes that
 * will be merged into a single class attribute.
 * 
 * All provided or computed strings are escaped for inclusion into HTML.
 * Attributes that generate an empty string will only emit the key value as
 * allowed by HTML.
 */
function spacestation_render_attributes(array $wrapper_attributes) {
    $attributes = "";
    foreach ($wrapper_attributes as $key => $value) {
        $attributes .= " " . $key;
        if (is_array($value)) {
            $attr_value = esc_attr(implode(" ", $value));
        } else {
            $attr_value = esc_attr($value);
        }

        if (strlen($attr_value) > 0) {
            $attributes .= '="' . $attr_value . '"';
        }
    }
    
    return $attributes;
}

/**
 * Warn about use of "Minimal Bootstrap 3"
 */
function spacestation_warn_minimal_bs3() {
    $framework = get_theme_mod('fl-framework');

    if ($framework === "base") {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>"Minimal Bootstrap 3" is enabled in the Space Station theme.
            This will cause various errors, up to and including a complete loss
            of theme CSS. Please change it to "Full Bootstrap 3" immediately.</p>
        </div>
        <?php
    }
}
add_filter("admin_notices", "spacestation_warn_minimal_bs3");

add_action('admin_bar_menu', function ($bar) {
    $bar->add_node(
        [
            'parent' => 'fl-builder-frontend-edit-link',
            'id' => 'purge-spacestation-assets',
            'title' => 'Purge Assets',
            'href' => add_query_arg('spacestation-purge-assets', 1),
        ]);
}, 100);


add_action('init', function () {
    if (!isset($_GET['spacestation-purge-assets']) || !is_user_logged_in()) {
        return;
    }

    if (is_callable('BWCustomizerLess::refresh_css')) {
        BWCustomizerLess::refresh_css();
    }

    if (is_callable('FLBuilderModel::delete_asset_cache_for_all_posts')) {
        FLBuilderModel::delete_asset_cache_for_all_posts();
    }

    if (is_callable('FLCustomizer::clear_all_css_cache')) {
        FLCustomizer::clear_all_css_cache();
    }
}, 1);