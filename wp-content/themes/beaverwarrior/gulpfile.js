/*jslint nomen: true*/
/*global require, console, __dirname*/

var i, j,
    gulp = require('gulp'),
    watch = require('gulp-watch'),
    notify = require('gulp-notify'),
    sourcemaps = require('gulp-sourcemaps'),
    sass = require('gulp-sass'),
    less = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    gulp_handlebars = require('gulp-handlebars'),
    wrap = require('gulp-wrap'),
    declare = require('gulp-declare'),
    iconfont = require('gulp-iconfont'),
    consolidate = require('gulp-consolidate'),
    Handlebars = require('handlebars'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    path = require('path'),
    merge = require('gulp-merge'),
    assetbuilder = require('asset-builder'),
    manifest_chain = [],
    globs = {
        "script.js": []
    },
    allScriptFiles = [],
    bannedGlobs = [],
    iconPaths = [],
    sassPaths = ["."],
    successPic,
    failurePic,
    iconFontTmpl,
    my_theme_name,
    scriptTasks = [],
    watchScriptTasks = [],
    build_directory, //Where to put build products that don't need to be user-accessible
    asset_directory; //Where to put compiled assets that need to have a valid URL

// Initial manifest loading. This should be refactored into a separate CommonJS
// module.
function processManifest(basePath) {
    "use strict";
    var manifest = assetbuilder(path.join(basePath, "manifest.json"));

    if (my_theme_name === undefined) {
        my_theme_name = manifest.config.theme_name;
    }

    iconPaths.push(path.join(basePath, "assets/icons/*.svg"));

    if (basePath !== ".") {
        sassPaths.push(path.resolve(path.join(basePath, ".")));
    }
    manifest_chain.push(manifest);

    if (manifest.config.base_theme !== undefined) {
        //Recursively process parent theme's manifest, too.
        processManifest(manifest.config.base_theme);
    } else {
        //Success/failure notification images come from the lowest theme
        //in the chain.
        successPic = path.join(basePath, 'assets/img/huemor_logo.png');
        failurePic = path.join(basePath, 'assets/img/sad_onions.png');
        iconFontTmpl = path.join(basePath, "assets/tmpl/icon_manifest.less.hbs");
        build_directory = path.join(basePath, manifest.config.build_directory || "build");
        asset_directory = path.join(basePath, manifest.config.asset_directory || "build");
    }
}

//Find base themes, if any.
processManifest(".");

//Only process our own manifest for JS globs.
//We assume parent themes will be generating their own HBS, JS etc.
//SASS/LESS gets processed a different way (base theme files can be referenced)
manifest_chain[0].forEachDependency("js", function (dep) {
    "use strict";

    if (dep.name === "script.js" || manifest_chain[0].config.no_concat.indexOf(dep.name) === -1) {
        globs["script.js"] = globs["script.js"].concat(dep.globs);
    } else {
        globs[dep.name] = dep.globs;
    }

    allScriptFiles = allScriptFiles.concat(dep.globs, {newLine: ';'});
});

for (i = 0; i < globs["script.js"].length; i += 1) {
    for (j = 0; j < manifest_chain[0].config.no_include.length; j += 1) {
        if (globs["script.js"][i].indexOf(manifest_chain[0].config.no_include[j]) !== -1) {
            bannedGlobs.unshift(i);
        }
    }
}

for (i = 0; i < bannedGlobs.length; i += 1) {
    globs["script.js"].splice(bannedGlobs[i], 1);
}

function build_templates() {
    "use strict";
    return gulp.src(["./components/**/*.hbs", "./layouts/**/*.hbs"], { allowEmpty: true })
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Template error.",
                    "message": "<%= error.message %>",
                    "icon": failurePic
                })(err);
                this.emit('end');
            }
        }))
        .pipe(gulp_handlebars({
            "handlebars": Handlebars
        }))
        .pipe(wrap('Handlebars.template(<%= contents %>)'))
        .pipe(declare({
            namespace: my_theme_name,
            noRedeclare: true,
            processName: function(filePath) {
                var filePath = path.relative(process.cwd(), filePath),
                    parts = filePath.split(path.sep),
                    templateName = path.basename(parts.pop(), '.js');
                
                for (i = 0; i < parts.length; i += 1) {
                    while (parts[i] === "components" || parts[i] === "layout") {
                        parts.splice(i, 1);
                    }
                }
                
                parts.push(templateName);
                
                return parts.join('.');
            }
        }))
        .pipe(concat('handlebars.js', {newLine: ';'}))
        .pipe(gulp.dest(build_directory))
        .pipe(notify({
            "title": "Templates compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": successPic
        }));
}
gulp.task('templates', build_templates);

for (key in globs) {
    if (globs.hasOwnProperty(key)) {
        //Capture key as a closure variable
        (function (key) {
            "use strict";
            function build_scripts() {
                function handle_error(err) {
                    notify.onError({
                        "title": "Scripts error.",
                        "message": "<%= error.message %>",
                        "icon": failurePic
                    })(err);
                    console.log(err);
                    this.emit('end');
                }

                return gulp.src(globs[key], { allowEmpty: true })
                    .pipe(plumber({
                        errorHandler: handle_error
                    }))
                    .pipe(sourcemaps.init())
                    .pipe(concat('./' + key, {newLine: ';'}))
                    //.pipe(uglify({
                    //    "compress": {
                    //        "drop_debugger": false
                    //    }
                    //}))
                    .pipe(sourcemaps.write("./debug"))
                    .pipe(gulp.dest(asset_directory))
                    .pipe(notify({
                        "title": "Scripts compiled.",
                        "message": "Compiled <%= file.relative %>",
                        "icon": successPic
                    }));
            }
            Object.defineProperty(build_scripts, "name", {value: build_scripts.name + "_" + key});
            
            gulp.task('scripts_only:' + key, build_scripts);
            gulp.task('scripts:' + key, gulp.series(build_templates, build_scripts));

            scriptTasks.push("scripts:" + key);
        }(key));
    }
}

gulp.task('scripts', gulp.parallel.apply(gulp, scriptTasks));

function build_iconfont() {
    "use strict";
    return gulp.src(iconPaths, { allowEmpty: true })
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Icon fonts error.",
                    "message": "<%= error.message %>",
                    "icon": failurePic
                })(err);
                this.emit('end');
            }
        }))
        .pipe(iconfont({
            fontName: 'icon',
            appendUnicode: true
        }))
        .on('glyphs', function (glyphs, options) {
            gulp.src(iconFontTmpl)
                .pipe(plumber({
                    errorHandler: function (err) {
                        notify.onError({
                            "title": "Icon fonts error.",
                            "message": "<%= error.message %>",
                            "icon": failurePic
                        })(err);
                        this.emit('end');
                    }
                }))
                .pipe(consolidate("handlebars", {
                    glyphs: glyphs,
                    fontName: 'icon',
                    fontPath: '../' + asset_directory + "/",
                    nonce: Math.floor(Math.random() * 10000)
                }))
                .pipe(notify({
                    "title": "Icon fonts compiled.",
                    "message": "Compiled <%= file.relative %>",
                    "icon": successPic
                }))
                .pipe(rename("icon_manifest.less"))
                .pipe(gulp.dest(build_directory));
        })
        .pipe(notify({
            "title": "Icon fonts compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": successPic
        }))
        .pipe(gulp.dest(asset_directory));
}
gulp.task('iconfont', build_iconfont);

Handlebars.registerHelper("codepoint", function (codepoint) {
    "use strict";
    console.log(codepoint[0]);
    return "\\" + codepoint[0].charCodeAt().toString(16).toUpperCase();
});

function build_styles() {
    "use strict";
    return gulp.src(["./stylesheets/*.less"])
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Styles error.",
                    "message": "<%= error.message %>",
                    "icon": failurePic
                })(err);
                this.emit('end');
            }
        }))
        //.pipe(sourcemaps.init())
        .pipe(less())
        .pipe(autoprefixer({
            "remove": true
        }))
        //.pipe(sourcemaps.write("./debug"))
        .pipe(gulp.dest(asset_directory))
        .pipe(notify({
            "title": "Stylesheets compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": successPic
        }));
}
gulp.task('styles', build_styles);

function watch_iconfont() {
    "use strict";
    return gulp.watch(["./assets/icons/*.svg", iconFontTmpl], gulp.series(["iconfont"]));
}
gulp.task('watch:iconfont', watch_iconfont);

function watch_styles() {
    "use strict";
    return gulp.watch(['./**/*.less'], gulp.series(["styles"]));
}
gulp.task('watch:styles', watch_styles);

function watch_templates() {
    "use strict";
    return gulp.watch(["./components/**/*.hbs", "./layouts/**/*.hbs"], gulp.series(["templates"]));
}
gulp.task('watch:templates', watch_templates);

for (key in globs) {
    if (globs.hasOwnProperty(key)) {
        function watch_scripts() {
            "use strict";
            return gulp.watch(globs[key], gulp.series(["scripts_only:" + key]));
        }
        Object.defineProperty(watch_scripts, "name", {value: watch_scripts.name + "_" + key});
        
        gulp.task('watch:scripts:' + key, watch_scripts);
        watchScriptTasks.push("watch:scripts:" + key);
    }
}

gulp.task('watch:scripts', gulp.parallel.apply(gulp, watchScriptTasks));

gulp.task('watch', gulp.parallel('watch:iconfont', 'watch:styles', 'watch:templates', 'watch:scripts'));
gulp.task('build', gulp.parallel('iconfont', 'styles', 'templates', 'scripts'));
gulp.task('default', gulp.parallel('build', 'watch'));
