var gulp       = require('gulp'),
    livereload = require('gulp-livereload');


gulp.task('live', function() {

    // Create LiveReload server
    livereload.listen();
    // Watch any files in dist/, reload on change

    gulp.watch([

        'application/caoliu/*',
        'class/**',
        'function/**'

    ]).on('change', livereload.changed);
});