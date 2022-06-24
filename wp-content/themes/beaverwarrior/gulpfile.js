import gulp from 'gulp';
import * as nanoassembler from '@huemor-designs/nanoassembler';

var config = nanoassembler.processManifest("."),
    handlebars_tasks = nanoassembler.handlebars.register_tasks(config),
    tasks = [handlebars_tasks,
        nanoassembler.scripts.register_tasks(config, handlebars_tasks.build),
        nanoassembler.iconfont.register_tasks(config),
        nanoassembler.wordpress.bb_module.register_tasks(config)
    ];

gulp.task('build', nanoassembler.omnibus(tasks, "build"));
gulp.task('watch', nanoassembler.omnibus(tasks, "watch"));
gulp.task('styles', nanoassembler.omnibus(tasks, "styles"));
gulp.task('scripts', nanoassembler.omnibus(tasks, "scripts"));

gulp.task('default', gulp.parallel('build', 'watch'));