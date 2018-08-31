var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var del = require('del');
var requireDir = require('require-dir');
var runSequence = require('run-sequence');
var cache = require('gulp-cache');
var imagemin = require('gulp-imagemin');
var browserSync = require('browser-sync');

var source = './source/';
var target = './webroot/';

var vendorPath = 'node_modules/';

//gulp.task('default', ['clean:dist']);
gulp.task('default', function(callback) {
  runSequence(['images', 'sass', 'fonts', 'js', 'vendor', 'browserSync'], 'watch',
  callback
)
});

gulp.task('build', function(callback) {
  runSequence(
    'clean:dist',
    ['sass', 'images', 'fonts', 'js', 'vendor'],
    callback
  )
});

// Watchers
gulp.task('watch', function() {
  gulp.watch(source + 'scss/**/*.scss', ['sass']);
  gulp.watch(source + '*.html', ['html']);
  gulp.watch('./plugins/Site/**/*.ctp', ['ctp']);
  gulp.watch(source + 'js/**/*.js', ['js']);
})

gulp.task('browserSync',[], function() {
  browserSync({
    proxy: 'http://localhost/lafarge-backend/admin',
  });
});

gulp.task('ctp', function() {
  return gulp.src('./plugins/Site/**/*.ctp')
  .pipe(browserSync.reload({ // Reloading with Browser Sync
    stream: true
  }));
});

gulp.task('sass', function () {
  return gulp.src(source + 'scss/*.scss')
  .pipe(sourcemaps.init())
  .pipe(sass({ includePaths: [vendorPath] })
        .on('error', sass.logError))
  .pipe(browserSync.reload({ // Reloading with Browser Sync
    stream: true
  }))
  .pipe(sass().on('error', sass.logError))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(target + 'css'));
});

gulp.task('images', function() {
  return gulp.src(source + 'img/**/*.+(png|jpg|jpeg|gif|svg)')
  .pipe(cache(imagemin({
    interlaced: true,
  })))
  .pipe(gulp.dest(target + 'img'))
});

gulp.task('fonts', function() {
  return gulp.src(source + 'fonts/**/*')
  .pipe(gulp.dest(target + 'fonts'))
});

gulp.task('sass:watch', function () {
  gulp.watch(source + 'sass/**/*.scss', ['sass']);
});

gulp.task('js', function() {
  return gulp.src(source + 'js/**/*.js')
  .pipe(gulp.dest(target + 'js/'))
  .pipe(browserSync.reload({ // Reloading with Browser Sync
    stream: true
  }));
});

gulp.task('vendor', function() {
    return gulp.src(source + 'vendor/**/*')
    .pipe(gulp.dest(target + 'vendor/'))
    .pipe(browserSync.reload({ // Reloading with Browser Sync
      stream: true
    }));
  });

gulp.task('clean:dist', function() {
  return del.sync([target+'/**/*', '!'+target+'/images', '!'+target+'/images/**/*', '!'+target+'index.php']);
});
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var del = require('del');
var requireDir = require('require-dir');
var runSequence = require('run-sequence');
var cache = require('gulp-cache');
var imagemin = require('gulp-imagemin');
var browserSync = require('browser-sync');

var source = './source/';
var target = './webroot/';

var vendorPath = 'node_modules/';

//gulp.task('default', ['clean:dist']);
gulp.task('default', function (callback) {
    runSequence(['images', 'sass', 'fonts', 'js', 'vendor', 'browserSync'], 'watch',
        callback
    )
});

gulp.task('build', function (callback) {
    runSequence(
        'clean:dist',
        ['sass', 'images', 'fonts', 'js', 'vendor'],
        callback
    )
});

// Watchers
gulp.task('watch', function () {
    gulp.watch(source + 'scss/**/*.scss', ['sass']);
    gulp.watch(source + '*.html', ['html']);
    gulp.watch('./plugins/Site/**/*.ctp', ['ctp']);
    gulp.watch(source + 'js/**/*.js', ['js']);
})

gulp.task('browserSync', [], function () {
    browserSync({
        proxy: 'http://localhost/lafarge-backend/admin',
    });
});

gulp.task('ctp', function () {
    return gulp.src('./plugins/Site/**/*.ctp')
        .pipe(browserSync.reload({ // Reloading with Browser Sync
            stream: true
        }));
});

gulp.task('sass', function () {
    return gulp.src(source + 'scss/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({ includePaths: [vendorPath] })
            .on('error', sass.logError))
        .pipe(browserSync.reload({ // Reloading with Browser Sync
            stream: true
        }))
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(target + 'css'));
});

gulp.task('images', function () {
    return gulp.src(source + 'img/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(cache(imagemin({
            interlaced: true,
        })))
        .pipe(gulp.dest(target + 'img'))
});

gulp.task('fonts', function () {
    return gulp.src(source + 'fonts/**/*')
        .pipe(gulp.dest(target + 'fonts'))
});

gulp.task('sass:watch', function () {
    gulp.watch(source + 'sass/**/*.scss', ['sass']);
});

gulp.task('js', function () {
    return gulp.src(source + 'js/**/*.js')
        .pipe(gulp.dest(target + 'js/'))
        .pipe(browserSync.reload({ // Reloading with Browser Sync
            stream: true
        }));
});

gulp.task('vendor', function () {
    return gulp.src(source + 'vendor/**/*')
        .pipe(gulp.dest(target + 'vendor/'))
        .pipe(browserSync.reload({ // Reloading with Browser Sync
            stream: true
        }));
});

gulp.task('clean:dist', function () {
    return del.sync([target + '/**/*', '!' + target + '/images', '!' + target + '/images/**/*', '!' + target + 'index.php']);
});
