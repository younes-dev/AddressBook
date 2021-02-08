let gulp = require('gulp');
let sass = require('gulp-sass');
let concat = require('gulp-concat');

gulp.task('sass', function(){
  return gulp.src('web/assets/scss/*.scss')
    .pipe(sass()) // Converts Sass to CSS with gulp-sass
    .pipe(gulp.dest('web/assets/build/css'))
});

gulp.task('pack-js', function () {
  return gulp.src(['node_modules/jquery/dist/jquery.js', 'node_modules/bootstrap/js/dist/*.js','web/assets/js/*.js'])
      .pipe(concat('bundle.js'))
      .pipe(gulp.dest('web/assets/build/js'));
});

gulp.task('watch', function(){
  gulp.watch('web/assets/scss/*.scss', ['sass']);
  gulp.watch('web/assets/js/*.js', ['pack-js']);
  // Other watchers
})