/**
 * инициализации
 * npm install --global gulp
 * npm install --save-dev gulp
 * npm install --save-dev gulp-livereload gulp-ruby-sass gulp-autoprefixer gulp-jshint gulp-util gulp-imagemin gulp-sass gulp-clean-css gulp-uglify gulp-rename gulp-concat gulp-clean gulp-clean tiny-lr gulp-minify-css
 * gulp
 */

var gulp = require( 'gulp' ),
    imagemin = require( 'gulp-imagemin' ), 
    sass = require( 'gulp-ruby-sass' ),
    cleancss = require( 'gulp-clean-css' ),
    autoprefixer = require( 'gulp-autoprefixer' ),
    // jshint = require('gulp-jshint'),           
    uglify = require( 'gulp-uglify' ), 
    rename = require( 'gulp-rename' ), 
    concat = require( 'gulp-concat' ), 
    clean = require( 'gulp-clean' ), 
    tinylr = require( 'tiny-lr' ),
    server = tinylr( ),
    port = 35729,
    livereload = require( 'gulp-livereload' ); //livereload
var fs = require( "fs" );
var header = require( 'gulp-header' );
var footer = require( 'gulp-footer' );
var rev = require( 'gulp-rev-collector' );
var pkg = JSON.parse( fs.readFileSync( './package.json' ) );
var banner = [ '/**', ' ** @author ' + pkg.author.name + ' <' + pkg.author.email + '>', ' ** @url ' + pkg.author.url, ' ** @version v' + pkg.version, ' **/', '' ].join( '\n' );
// HTML Обработка
// gulp.task('html', function() {
//     var htmlSrc = './template/www/desktop/*.htm',
//         htmlDst = './template/www/';
//     gulp.src(htmlSrc)
//         .pipe(livereload(server))
//         // .pipe(gulp.dest(htmlDst))
// });
//CSS стили
gulp.task( 'css', function ( ) {
    var cssSrc = './template/www/desktop/static/css/ui.css',
        cssDst = './template/www/desktop/static/css/';
    gulp.src( cssSrc )
        // .pipe(sass({ style: 'expanded'}))
        // .pipe(sass({ style: 'compressed' }))
        .pipe( autoprefixer( 'last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4' ) )
        // .pipe(gulp.dest(cssDst))
        .pipe( rename( {
            suffix: '.min'
        } ) )
        // .pipe(cleancss())
        .pipe( livereload( server ) ).pipe( gulp.dest( cssDst ) );
} );
// Обработка изображений
// gulp.task('images', function(){
//     var imgSrc = './template/wwwsrc/static/img/*',
//         imgDst = './template/www/static/img/';
//     gulp.src(imgSrc)
//         .pipe(imagemin())
//         .pipe(livereload(server))
//         .pipe(gulp.dest(imgDst));
// })
// public js
gulp.task( 'public', function ( ) {
    // var uiSrc = './template/www/static/ui.js',
    //     uiDst = './template/www/static/',
    //     uijsSrc = ['./template/www/static/js/*.js','!./template/www/static/js/*.min.js'],
    //     uijsDst = './template/www/static/js/';
    // gulp.src(uiSrc)
    //     // .pipe(jshint('.jshintrc'))
    //     //.pipe(jshint.reporter('default'))
    //     //.pipe(concat('main.js'))
    //     //.pipe(gulp.dest(jsDst))
    //     .pipe(rename({ suffix: '.min' }))
    //     .pipe(uglify())
    //     // .pipe(concat("main.js"))
    //     .pipe(gulp.dest(uiDst))
    //     .pipe(livereload(server));
    // gulp.src(uijsSrc)
    //     .pipe(uglify())
    //     //.pipe(concat("vendor.js"))
    //     .pipe(rename({ suffix: '.min' }))
    //     .pipe(gulp.dest(uijsDst))
    //     .pipe(livereload(server));
    // var appSrc = './public/iCMS.APP.js',
    //     appDst = './public/',
    //     appjsSrc = ['./public/js/*.js','!./public/js/*.min.js'],
    //     appjsDst = './public/js/';
    // gulp.src(appSrc)
    //     .pipe(uglify())
    //     //.pipe(concat("vendor.js"))
    //     .pipe(rename({ suffix: '.min' }))
    //     .pipe(gulp.dest(appDst))
    //     .pipe(livereload(server));
    // gulp.src(appjsSrc)
    //     .pipe(uglify())
    //     //.pipe(concat("vendor.js"))
    //     .pipe(rename({ suffix: '.min' }))
    //     .pipe(gulp.dest(appjsDst))
    //     .pipe(livereload(server));
    //
    var publicSrc = './public/js/_src/',
        publicDst = './public/js/';

    function fetchScripts( ) {
        var sources = fs.readFileSync( publicDst+"iCMS.js" );
        sources = /\[([^\]]+\.js'[^\]]+)\]/.exec( sources );
        sources = sources[ 1 ].replace( /\/\/.*[\n\r]/g, '\n' ).replace( /'|"|\n|\t|\s/g, '' );
        sources = sources.split( "," );
        sources.forEach( function ( filepath, index ) {
            sources[ index ] = publicSrc + filepath;
        } );
        return sources;
    }
    gulp.src( fetchScripts( ) ).pipe( uglify( ) )
        //.pipe(concat("vendor.js"))
        // .pipe(rename({ suffix: '.min' }))
        //
        .pipe( concat( "iCMS.min.js" ) ).pipe( header( banner + '(function($){\n\n' ) ).pipe( footer( '\n\n})(jQuery)' ) ).pipe( gulp.dest( publicDst ) ).pipe( livereload( server ) );
} );

// gulp.task('clean', function() {
//     // gulp.src(['./dist/css', './dist/js/main.js','./dist/js/vendor', './dist/images'], {read: false})
//     //     .pipe(clean());
// });
// gulp.task('default', ['clean'], function(){
//     // gulp.start('html','css','images','js');
// });
gulp.task( 'default', function ( ) {
    gulp.start( 'public', 'css' );
} );
//Отслеживаем gulp watch
gulp.task( 'watch', function ( ) {
    server.listen( port, function ( err ) {
        if ( err ) {
            return console.log( err );
        }
        
        // gulp.watch('./template/www/*.htm', function(event){
        //     gulp.run('html');
        // })
        
        // gulp.watch('./template/www/static/*.css', function(){
        //     gulp.run('css');
        // });
        
        // gulp.watch('./template/www/static/img/*', function(){
        //     gulp.run('images');
        // });
        
        gulp.watch( [ './public/js/_src/*.js' ], function ( ) {
            gulp.run( 'public' );
        } );
    } );
} );
