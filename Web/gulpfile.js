var gulp = require('gulp');
var del = require('del');
var typescript = require('gulp-typescript');
var tscConfig = require('./tsconfig.json');
var sourcemaps = require('gulp-sourcemaps');
var tslint = require('gulp-tslint');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');

// Directories

var templateSource = 'templates/app/**/';
var tsSource = 'typescript/**/';
var lessSource = 'less/**/';

var tsOut = 'public/app/';
var libOut = 'public/lib/';
var cssOut = 'public/css/';

// Clean the compiled typescript and templates
gulp.task('clean:app', function () {
  return del([tsOut + '**/*']);
});

// Clean the copied javascript libraries
gulp.task('clean:lib', function () {
  return del([libOut + '**/*']);
});

// Clean the compiled and copied stylesheets
gulp.task('clean:css', function () {
  return del([cssOut + '**/*']);
});

// Clean the compiled and copied javascript and style resources
gulp.task('clean', ['clean:app', 'clean:css', 'clean:lib']);

// TypeScript lint
gulp.task('tslint', function() {
  return gulp
    .src(tsSource + '*.ts')
    .pipe(tslint())
    .pipe(tslint.report('verbose'));
});

// Compile LESS to CSS
gulp.task('less', ['clean:css'], function() {
  return gulp
    .src(lessSource + '*.less')
    .pipe(less())
    .pipe(gulp.dest(cssOut));
});

// Copy non-less CSS files
gulp.task('copy:css', ['clean:css'], function() {
  return gulp
    .src([lessSource + '*', '!' + lessSource + '*.less'],
         { base : './less' })
    .pipe(gulp.dest(cssOut));
});

// TypeScript compile
gulp.task('compile', ['clean:app'], function() {
  return gulp
    .src(tsSource + '*.ts')
    .pipe(sourcemaps.init())
    .pipe(typescript(tscConfig.compilerOptions))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(tsOut));
});

// Copy app templates
gulp.task('copy:templates', ['clean:app'], function() {
  return gulp
    .src([templateSource + '*'],
         { base : './templates/app' })
    .pipe(gulp.dest(tsOut));
});

// Copy dependencies
gulp.task('copy:libs', ['clean:lib'], function() {
  return gulp
    .src([
    'node_modules/es6-shim/es6-shim.js',
    'node_modules/es6-shim/es6-shim.min.js',
    'node_modules/es6-shim/es6-shim.map',

    'node_modules/angular2/bundles/angular2-polyfills.js',
    'node_modules/angular2/bundles/angular2-polyfills.min.js',

    'node_modules/angular2/bundles/angular2.js',
    'node_modules/angular2/bundles/angular2.min.js',
    'node_modules/angular2/bundles/angular2.dev.js',

    'node_modules/systemjs/dist/system.js',
    'node_modules/systemjs/dist/system.js.map',
    'node_modules/systemjs/dist/system.src.js',

    'node_modules/systemjs/dist/system-polyfills.js',
    'node_modules/systemjs/dist/system-polyfills.js.map',
    'node_modules/systemjs/dist/system-polyfills.src.js',

    'node_modules/angular2/bundles/router.js',
    'node_modules/angular2/bundles/router.min.js',
    'node_modules/angular2/bundles/router.dev.js',

    'node_modules/rxjs/bundles/Rx.js',
    'node_modules/rxjs/bundles/Rx.min.js',
    'node_modules/rxjs/bundles/Rx.min.js.map',
  ])
  .pipe(gulp.dest(libOut));
});

gulp.task('build', [
    'tslint',
    'less',
    'copy:css',
    'compile',
    'copy:templates',
    'copy:libs'
  ]);

gulp.task('default', ['build']);

