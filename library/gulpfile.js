var gulp = require('gulp');
    concat = require('gulp-concat');
    sass = require('gulp-sass');
    prefix = require('gulp-autoprefixer');
    coffee = require('gulp-coffee');
    sourcemaps = require('gulp-sourcemaps');
    notify = require("gulp-notify");

gulp.task('sass', function () {
  gulp.src('./scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass())
      .on('error', notify.onError(function (error) {
        return error;
      }))
    .pipe(prefix("last 4 versions", "> 1%", "ie 8", "ie 7"))
    .pipe(sourcemaps.write('../sourcemaps'))
    .pipe(gulp.dest('./css'));
});

gulp.task('coffee', function () {
  gulp.src('./coffee/*.coffee')
    .pipe(sourcemaps.init())
    .pipe(coffee())
    .on('error', notify.onError(function (error) {
      return error;
    }))
    .pipe(sourcemaps.write('../sourcemaps'))
    .pipe(gulp.dest('./js'));
});

gulp.task('concat-js', function() {
  return gulp.src(['./js/youtube.js', './js/dropshop.js', './js/scripts.js'])
    .pipe(concat('all.js'))
    .pipe(gulp.dest('./js/'));
});
 
//Watch task
gulp.task('default',function() {
    gulp.watch('./scss/*.scss',['sass']);
    gulp.watch('./coffee/*.coffee',['coffee']);
    gulp.watch('./js/*.js',['concat-js']);
});