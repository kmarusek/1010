module.exports = function(grunt) {

    // An array of our custom modules paths
    // relative to theme
    let custom_modules_upstream = [
    "/components/SiteHeader/bw-navigation-popover/"
    ],

    custom_modules = get_all_custom_modules( custom_modules_upstream );

    function get_downstream_custom_modules(){
        // Declare our return
        let return_array          = [];
        // Check that our downstream file exists
        if ( grunt.file.exists( 'custom-modules-downstream.json') ){
            // Get the contents of the downstream files
            let downstream_custom_modules_file = grunt.file.readJSON( 'custom-modules-downstream.json' );
            // Get the downstream modules
            if ( downstream_custom_modules_file.custom_modules_downstream !== undefined && downstream_custom_modules_file.custom_modules_downstream.length > 0 ){
                // Redeclare the return array with the downstream modules
                return_array = downstream_custom_modules_file.custom_modules_downstream;
            }
        }
        // Return the array
        return return_array;
    }

    function get_all_custom_modules( custom_modules_upstream ){
        // Get the downstream custom modules
        let custom_modules_downstream = get_downstream_custom_modules(),
        // By default, our module return is just the upstream modules
        all_custom_modules = custom_modules_upstream;
        // If we have custom downstream modules, then add those to the return
        if ( custom_modules_downstream.length > 0 ){
            all_custom_modules = custom_modules_upstream.concat( custom_modules_downstream )
        }
        // Return the array
        return all_custom_modules;
    }

    /**
     * Function to get the module name
     *
     * @param  {string} The file path
     *
     * @return {string}  The name of the file
     */
     function get_module_name( file_path ){
        // Get the array of the file path
        let file_path_array = file_path.replace(/\/$/, "").split('/'),
        // Get the last item in the array (the name)
        file_name = file_path_array[file_path_array.length - 1];
        // Return the file name
        return file_name;
    }

    /**
     * Function to get the object used to parse out LESS files
     *
     * @return {object}  The LESS object
     */
     function get_less_object(){
        // Declare our return
        let return_array = {};
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i];
            // Add this modules CSS
            return_array['..' + current_module_path + 'css/frontend.css'] = '..' + current_module_path + 'css/frontend.less';
        }
        // Return the array
        return return_array;
    }

    /**
     * Function to get the object used to lint our CSS
     *
     * @return {array}  The CSS array
     */
     function get_css_lint_array(){
        // Declare our return
        let return_array = [];
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i];
            // Add this modules CSS
            return_array.push( '..' + current_module_path + 'css/frontend.css' );
        }
        // Return the array
        return return_array;
    }

    /**
     * Function to get the object used for minifying the CSS
     *
     * @return {object} CSS min object
     */
     function get_css_min_object(){
        // Declare our return
        let return_array = {};
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i],
            // Create the array for this item
            module_object = {
                files: [{
                    expand: true,
                    cwd: '..' + current_module_path + 'css/',
                    src: ['*.css', '!*.min.css'],
                    dest: '..' + current_module_path + 'css/',
                    ext: '.css'
                }]
            };
            return_array[get_module_name(current_module_path)] = module_object;
        }
        return return_array;
    }

    /**
     * Function to get the object used for uglification.
     *
     * @return {object} The uglify object
     */
     function get_uglify_object(){
        // Declare our return
        let return_array = {};
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i],
            // Create the array for this item
            module_object = {
                files: [{
                    expand: true,
                    cwd: '..' + current_module_path + 'js/',
                    src: ['**/*.js', '!**/*prebuilt.js','!**/*settings.js'],
                    dest: '..' + current_module_path + 'js/'
                }]
            };
            return_array[get_module_name(current_module_path)] = module_object;
        }
        return return_array;
    }

    /**
     * Function to get the object used for copying JS from custom modules.
     *
     * @return {object} The copy object object
     */
     function get_copy_object(){
        // Declare our return
        let return_array = {};
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i],
            // Create the array for this item
            module_object = {
                files: [{
                    expand: true,
                    cwd: '..' + current_module_path + 'js/',
                    src: ['**/*.js', '!**/frontend.js'],
                    dest: '..'+ current_module_path + 'js/',
                    rename: function(dest, src) {
                        return dest + src.replace(/\.prebuilt\.js$/, ".js");
                    }
                }]
            };
            return_array[get_module_name(current_module_path)] = module_object;
        }
        return return_array;
    }

    /**
     * Function to get the JS hint array
     *
     * @return {array} The JS hint array
     */
     function get_js_hint_array(){
        // Declare our return
        let return_array = [];
        // Add all of the custom modules
        for ( let i=0; i<custom_modules.length; i++){
            // Get the current moduke
            let current_module_path = custom_modules[i];
            // Add this modules CSS
            return_array.push( '..' + current_module_path + 'js/frontend.prebuilt.js' );
        }
        // Return the array
        return return_array;
    }

    // Get the LESS object
    let less_items = get_less_object(),
    // Get the uglify items
    uglify_items   = get_uglify_object(),
    // Get the CSS lint array
    css_lint_array = get_css_lint_array(),
    // Get the copy object
    copy_object    = get_copy_object(),
    // Get the JS Hint array
    js_hint_array  = get_js_hint_array(),
    // Get the JS Hint array
    css_min_object = get_css_min_object();
    grunt.initConfig({
        jshint: {
            options: {
                es3     : true,
                curly   : true,
                eqeqeq  : true,
                eqnull  : true,
                browser : true,
                globals: {
                    jQuery: true
                }
            },
            all: js_hint_array
        },
        scss2less: {
            convert: {
                options: {
                    paths: ["./"]
                },
                files: [
                {
                    expand: true,
                    cwd: '../components',
                    src: '**/*.scss',
                    dest: '../components',
                    ext: '.less',
                    rename: function(dest, src) { return dest + '/' + src.replace('_','');}
                },
                {
                    expand: true,
                    cwd: '../layouts',
                    src: '**/*.scss',
                    dest: '../layouts',
                    ext: '.less',
                    rename: function(dest, src) { return dest + '/' + src.replace('_','');}
                }]
            }
        },
        less: {   
            options:{
                sourcemap: 'none'
            },
            customModules: {           
                files: less_items
            }
        },
        watch: {
            component_css : {
                files: ['../components/**/*.scss'],
                tasks: ['buildCSS'],
                options: {
                    livereload : true,
                    debounceDelay: 750
                }
            },
            component_js : {
                files: ['../components/**/*.prebuilt.js'],
                tasks: ['buildJS'],
                options: {
                    livereload : true,
                    debounceDelay: 750
                }
            },
            layout_css : {
                files: ['../layouts/**/*.scss'],
                tasks: ['buildCSS'],
                options: {
                    livereload : true,
                    debounceDelay: 750
                }
            },
            layout_js : {
                files: ['../layouts/**/*.prebuilt.js'],
                tasks: ['buildJS'],
                options: {
                    livereload : true,
                    debounceDelay: 750
                }
            }
        },
        uglify: uglify_items,
        csslint: {
            strict: {
                options: {
                    'important'              : false,
                    'font-sizes'             : false,
                    'ids'                    : false,
                    'adjoining-classes'      : false,
                    'adjoining-classes'      : false,
                    'overqualified-elements' : false,
                    'unique-headings'        : false,
                },
                src: css_lint_array
            }
        },
        cssmin: css_min_object,
        removelogging: {
            dist: {
                src: "library/dist/js/**/*.js"
            },
        },
        copy: copy_object
    });

    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-csslint');
    grunt.loadNpmTasks("grunt-remove-logging");
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks("grunt-comment-toggler");
    grunt.loadNpmTasks('grunt-scss2less');

    grunt.task.registerTask('buildDev',  ['buildCSS', 'buildJS']);
    grunt.task.registerTask('buildCSS',  ['scss2less', 'less']);
    grunt.task.registerTask('buildJS',   ['copy','jshint']);
    grunt.task.registerTask('buildProd', ['buildJS','uglify','buildCSS','cssmin']);
};