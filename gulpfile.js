const gulp         = require("gulp");
const sass         = require("gulp-sass");
const rename       = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const uglify       = require('gulp-uglify');
const plumber      = require("gulp-plumber");
const browserSync  = require('browser-sync');
const cmq          = require('gulp-group-css-media-queries');
const cleanCSS     = require('gulp-clean-css');


const changed      = require('gulp-changed');
const imagemin     = require('gulp-imagemin');
const imageminJpg  = require('imagemin-jpeg-recompress');
const imageminPng  = require('imagemin-pngquant');
const imageminGif  = require('imagemin-gifsicle');
const svgmin       = require('gulp-svgmin');

//パスを設定設定してください
var paths = {
  'public' : './src/', // 編集先のパス（このままでOK）
  'dist'   : 'C:/xampp/htdocs/00_wp_gulp/',  //　出力先のパス
  'ip'     : 'wp_gulp'  //　ipアドレスまたはシンボリックドメイン（XAMMPなどと連携時に設定）
}

// 保存時のリロード
gulp.task( 'browser-sync', function(done) { browserSync({　proxy: paths.ip }); });
gulp.task( 'bs-reload', function(done) {
	browserSync.reload();
	done();
});

// 起動時と更新時のファイル出力処理
gulp.task('static', function(){
  return gulp.src( paths.public + '**/*')
    .pipe(changed( paths.dist ))
    .pipe(gulp.dest( paths.dist ));
});


// ★★★ scss圧縮 ★★★
gulp.task('sass', function() {
  return gulp
    .src( paths.public + "**/*.scss" )
    .pipe(gulp.dest(paths.dist))//コンパイル後の出力先(min前)
    .pipe(sass())
    .pipe(cmq())
    .pipe(plumber())
    .pipe(autoprefixer())
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min', }))
    .pipe(gulp.dest(paths.dist));//コンパイル後の出力先
});


// ★★★ JS圧縮 ★★★
gulp.task('js', function() {
  return gulp
    .src(paths.public + "**/*.js")
    .pipe(gulp.dest(paths.dist))//コンパイル後の出力先(min前)
    .pipe(plumber())
    .pipe(uglify())
    .pipe(rename({ suffix: '.min', }))
    .pipe(gulp.dest(paths.dist));//コンパイル後の出力先
});


// ★★★ jpg,png,gif画像の圧縮 ★★★
gulp.task('imagemin', function(){
    return gulp.src( paths.public + '**/*.+(jpg|jpeg|png|gif)' )
    .pipe(changed( paths.dist))
    .pipe(imagemin([
        imageminPng(),
        imageminJpg(),
        imageminGif({
            interlaced: false,
            optimizationLevel: 3,
            colors:180
        })
    ]
    ))
    .pipe(gulp.dest( paths.dist));
});
// ★★★ svg画像の圧縮 ★★★
gulp.task('svgmin', function(){
    return gulp.src( paths.public + '**/*.+(svg)' )
    .pipe(changed( paths.dist))
    .pipe(svgmin())
    .pipe(gulp.dest( paths.dist));
});


// 監視一覧
gulp.task( 'watch', function(done) {

  gulp.watch( // ★★★ scss圧縮 ★★★
    paths.public + "**/*.scss",
    gulp.series(gulp.parallel('bs-reload', 'sass'))
  );
  gulp.watch( // ★★★ JS圧縮 ★★★
    paths.public + "**/*.js",
    gulp.series(gulp.parallel('bs-reload', 'js'))
  );
  gulp.watch( // ★★★ jpg,png,gif画像の圧縮 ★★★
    paths.public + '**/*.+(jpg|jpeg|png|gif)',
    gulp.series(gulp.parallel('bs-reload', 'imagemin'))
  );
  gulp.watch( // ★★★ svg画像の圧縮 ★★★
    paths.public + '**/*.+(svg)',
    gulp.series(gulp.parallel('bs-reload', 'svgmin'))
  );
  gulp.watch( // ★★★ 特別な処理をしないファイル群（拡張子）を出力先に同期するための処理。 ★★★
	  paths.public + '**/*.+(html|php|css|ico|htaccess|txt|xml|otf|ttf|woff|woff2|eot)',
	  gulp.series(gulp.parallel('bs-reload', 'static'))
  );

});

// npx gulp コマンドのデフォルトタスク
gulp.task('default', gulp.series(gulp.parallel(
    'browser-sync',
    'watch',
    'static',
    'sass',
    'js',
    'imagemin',
    'svgmin'
)));

