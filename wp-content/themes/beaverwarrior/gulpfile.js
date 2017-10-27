/*jslint nomen: true*/
/*global require, console, __dirname*/

var i, j,
    gulp = require('gulp'),
    watch = require('gulp-watch'),
    notify = require('gulp-notify'),
    sourcemaps = require('gulp-sourcemaps'),
    less = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    handlebars = require('gulp-handlebars'),
    wrap = require('gulp-wrap'),
    declare = require('gulp-declare'),
    iconfont = require('gulp-iconfont'),
    consolidate = require('gulp-consolidate'),
    Handlebars = require('handlebars'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    path = require('path'),
    merge = require('gulp-merge'),
    manifest = require('asset-builder')('./manifest.json'),
    globs = {
        "script.js": []
    },
    allScriptFiles = [],
    bannedGlobs = [];

manifest.forEachDependency("js", function (dep) {
    "use strict";

    if (dep.name === "script.js" || manifest.config.no_concat.indexOf(dep.name) === -1) {
        globs["script.js"] = globs["script.js"].concat(dep.globs);
    } else {
        globs[dep.name] = dep.globs;
    }

    allScriptFiles = allScriptFiles.concat(dep.globs);
});

for (i = 0; i < globs["script.js"].length; i += 1) {
    for (j = 0; j < manifest.config.no_include.length; j += 1) {
        if (globs["script.js"][i].indexOf(manifest.config.no_include[j]) !== -1) {
            bannedGlobs.unshift(i);
        }
    }
}

for (i = 0; i < bannedGlobs.length; i += 1) {
    globs["script.js"].splice(bannedGlobs[i], 1);
}

console.log(globs);

gulp.task('templates', function () {
    "use strict";
    return gulp.src(["./components/**/*.hbs", "./layouts/**/*.hbs"])
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Template error.",
                    "message": "<%= error.message %>",
                    "icon": path.join(__dirname, 'assets/img/sad_onions.png')
                })(err);
                this.emit('end');
            }
        }))
        .pipe(handlebars())
        .pipe(wrap('Handlebars.template(<%= contents %>)'))
        .pipe(declare({
            namespace: 'Dragonfruit.templates',
            noRedeclare: true
        }))
        .pipe(concat('interim/handlebars.js'))
        .pipe(gulp.dest('./build'))
        .pipe(notify({
            "title": "Templates compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": path.join(__dirname, 'assets/img/huemor_logo.png')
        }));
});

gulp.task('scripts', function () {
    "use strict";
    var depPipes = [], key;

    function handle_error(err) {
        notify.onError({
            "title": "Scripts error.",
            "message": "<%= error.message %>",
            "icon": path.join(__dirname, 'assets/img/sad_onions.png')
        })(err);
        console.log(err);
        this.emit('end');
    }

    for (key in globs) {
        if (globs.hasOwnProperty(key)) {
            depPipes.push(gulp.src(globs[key])
                .pipe(plumber({
                    errorHandler: handle_error
                }))
                .pipe(sourcemaps.init())
                .pipe(concat('./' + key))
                //.pipe(uglify({
                //    "compress": {
                //        "drop_debugger": false
                //    }
                //}))
                .pipe(sourcemaps.write("./debug"))
                .pipe(gulp.dest("./build"))
                .pipe(notify({
                    "title": "Scripts compiled.",
                    "message": "Compiled <%= file.relative %>",
                    "icon": path.join(__dirname, 'assets/img/huemor_logo.png')
                }))
                );
        }
    }

    return merge.call(this, depPipes);
});

gulp.task('iconfont', function () {
    "use strict";
    return gulp.src(["./assets/icons/*.svg"])
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Icon fonts error.",
                    "message": "<%= error.message %>",
                    "icon": path.join(__dirname, 'assets/img/sad_onions.png')
                })(err);
                this.emit('end');
            }
        }))
        .pipe(iconfont({
            fontName: 'icon',
            appendUnicode: true
        }))
        .on('glyphs', function (glyphs, options) {
            gulp.src("./assets/tmpl/icon_manifest.less.hbs")
                .pipe(plumber({
                    errorHandler: function (err) {
                        notify.onError({
                            "title": "Icon fonts error.",
                            "message": "<%= error.message %>",
                            "icon": path.join(__dirname, 'assets/img/sad_onions.png')
                        })(err);
                        this.emit('end');
                    }
                }))
                .pipe(consolidate("handlebars", {
                    glyphs: glyphs,
                    fontName: 'icon',
                    fontPath: '../build/fonts/'
                }))
                .pipe(rename({
                    dirname: "interim",
                    extname: ""
                }))
                .pipe(notify({
                    "title": "Icon fonts compiled.",
                    "message": "Compiled <%= file.relative %>",
                    "icon": path.join(__dirname, 'assets/img/huemor_logo.png')
                }))
                .pipe(gulp.dest("./build"));
        })
        .pipe(notify({
            "title": "Icon fonts compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": path.join(__dirname, 'assets/img/huemor_logo.png')
        }))
        .pipe(gulp.dest('./build/fonts/'));
});

Handlebars.registerHelper("codepoint", function (codepoint) {
    "use strict";
    console.log(codepoint[0]);
    return "\\" + codepoint[0].charCodeAt().toString(16).toUpperCase();
});

gulp.task('styles', function () {
    "use strict";
    return gulp.src(["./stylesheets/*.less"])
        .pipe(plumber({
            errorHandler: function (err) {
                notify.onError({
                    "title": "Styles error.",
                    "message": "<%= error.message %>",
                    "icon": path.join(__dirname, 'assets/img/sad_onions.png')
                })(err);
                this.emit('end');
            }
        }))
        //.pipe(sourcemaps.init())
        .pipe(less())
        .pipe(autoprefixer({
            "browsers": ['IE 8', '> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1'],
            "remove": true
        }))
        //.pipe(sourcemaps.write("./debug"))
        .pipe(gulp.dest("./build"))
        .pipe(notify({
            "title": "Stylesheets compiled.",
            "message": "Compiled <%= file.relative %>",
            "icon": path.join(__dirname, 'assets/img/huemor_logo.png')
        }));
});

gulp.task('watch:iconfont', function () {
    "use strict";
    return gulp.watch(["./assets/icons/*.svg", "./assets/tmpl/icon_manifest.less.hbs"], ["iconfont"]);
});

gulp.task('watch:styles', function () {
    "use strict";
    return gulp.watch('./**/*.less', ["styles"]);
});

gulp.task('watch:templates', function () {
    "use strict";
    return gulp.watch(["./components/**/*.hbs", "./layouts/**/*.hbs"], ["templates"]);
});

gulp.task('watch:scripts', function () {
    "use strict";
    return gulp.watch(allScriptFiles, ["scripts"]);
});

gulp.task('watch', ['watch:iconfont', 'watch:styles', 'watch:templates', 'watch:scripts']);
gulp.task('build', ['iconfont', 'styles', 'templates', 'scripts']);
gulp.task('default', ['build', 'watch']);
