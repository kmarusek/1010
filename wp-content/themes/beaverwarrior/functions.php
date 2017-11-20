<?php
// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );


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
    wp_register_style('main', get_stylesheet_directory_uri() . '/build/main.css', false, null, "all");
    wp_register_script('scripts', get_stylesheet_directory_uri() . '/build/script.js', array('jquery'), null, true);
    
    //Vendored copy of Slick Slider
    wp_register_style('slick-slider', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick/slick.css', false, null, "all");
    wp_register_script('slick-slider', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick/slick.min.js', false, null, true);
    
    wp_enqueue_script('scripts');
    wp_enqueue_style('main');
    
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
    background: url('".get_bloginfo('template_url')."/assets/img/login_logo.svg') 8px 0 no-repeat transparent;
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
        <script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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

if ($_SERVER["QUERY_STRING"] == "fl_builder") {
    add_action('send_headers', 'beaver_warrior_bb_cache_buster', 15);
}

//Add post thumbnail / featured image support
function beaver_warrior_theme_support() {
    add_theme_support("post-thumbnails");
}
add_action("after_setup_theme", "beaver_warrior_theme_support");

//Queue our LESS for inclusion with the BB Customizer
function beaver_warrior_less_paths($paths) {
    $paths[] = FL_CHILD_THEME_DIR . "/stylesheets/main.less";
}
add_action("fl_theme_compile_less_paths", "beaver_warrior_less_paths");
