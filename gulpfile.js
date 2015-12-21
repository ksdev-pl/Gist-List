process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix
        .sass('libs.scss', 'public/css/libs.css', {precision: 10})
        .styles([
            'public/css/libs.css',
            'resources/assets/bower/messenger/build/css/messenger.css',
            'resources/assets/bower/messenger/build/css/messenger-theme-flat.css'
        ], 'public/css/all.css', '.')
        .sass('main.scss', 'public/css/main.css', {precision: 10})
        .styles([
            'public/css/all.css',
            'public/css/main.css'
        ], 'public/css/all.css', '.')
        .scripts([
            'resources/assets/bower/jquery/dist/jquery.js',
            'resources/assets/bower/bootstrap-sass/assets/javascripts/bootstrap.js',
            'resources/assets/bower/messenger/build/js/messenger.js',
            'resources/assets/bower/messenger/build/js/messenger-theme-flat.js',
            'resources/assets/bower/vue/dist/vue.js',
            'resources/assets/bower/vue-resource/dist/vue-resource.js',
            'resources/assets/bower/lodash/lodash.js',
            'resources/assets/bower/tinycolor/tinycolor.js',
            'resources/assets/js/main.js'
        ], 'public/js/all.js', '.')
        .version(['css/all.css', 'js/all.js'])
        .copy('resources/assets/bower/bootstrap-sass/assets/fonts/bootstrap/*.*', 'public/fonts')
        .copy('resources/assets/bower/font-awesome/fonts/*.*', 'public/fonts');
});
