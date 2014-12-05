// Get modules
var gulp = require('gulp');
//var changed     = require('gulp-changed');

var assetFrontendFolder = 'app/modules/frontend/assets/';
var assetAdminFolder = 'app/modules/admin/assets/';

var outputFrontendFolder = 'public/packages/module/frontend/assets/';
var outputAdminFolder = 'public/packages/module/admin/assets/';

gulp.task('moveAdmin', function () {
    return gulp.src(assetAdminFolder + '**')
           // .pipe(changed(assetAdminFolder + '**'))
            .pipe(gulp.dest(outputAdminFolder));
});

gulp.task('moveFrontend', function () {
    return gulp.src(assetFrontendFolder + '**')
           // .pipe(changed(assetAdminFolder + '**'))
            .pipe(gulp.dest(outputFrontendFolder));
});

// Task watch admin
gulp.task('watchAdmin', function () {
    console.log(assetAdminFolder + 'css/**');
    gulp.watch(assetAdminFolder + 'css/**/*', ['moveAdmin']);
    gulp.watch(assetAdminFolder + 'js/**/*', ['moveAdmin']);
    gulp.watch(assetAdminFolder + 'img/**/*', ['moveAdmin']);

});

// Task watch front
gulp.task('watchFrontend', function () {
    gulp.watch(assetFrontendFolder + 'css/**/*', ['moveFrontend']);
    gulp.watch(assetFrontendFolder + 'js/**/*', ['moveFrontend']);
    gulp.watch(assetFrontendFolder + 'img/**/*', ['moveFrontend']);

});

// The default task (called when you run `gulp` from cli)
gulp.task('admin', ['watchAdmin']);

gulp.task('front', ['watchFrontend']);







