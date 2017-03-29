var hash = require('gulp-hash');
var gulp  = require("gulp"),
    useref = require('gulp-useref'),
    gulpif = require('gulp-if'),
    uglify = require('gulp-uglify'),
    filter = require('gulp-filter'),
    minifyCss = require('gulp-minify-css'),
    revReplace = require('gulp-rev-replace'),
    csso = require('gulp-csso'),
    rev =require("gulp-rev");


gulp.task("index", function() {
  var jsFilter = filter("**/*.js");
  var cssFilter = filter("**/*.css");

  var userefAssets = useref.assets();

  return gulp.src("web/test.html")
    .pipe(userefAssets)      // Concatenate with gulp-useref
        .pipe(gulpif('*.js', uglify()))
        .pipe(gulpif('*.css', minifyCss()))
    .pipe(jsFilter)
    .pipe(uglify())             // Minify any javascript sources
    .pipe(jsFilter.restore())
    .pipe(cssFilter)
    .pipe(csso())               // Minify any CSS sources
    .pipe(cssFilter.restore())
    .pipe(rev())                // Rename the concatenated files
    .pipe(userefAssets.restore())
    .pipe(useref())
    .pipe(revReplace())         // Substitute in new filenames
    .pipe(gulp.dest('web/build'));
});
