var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

elixir(function(mix) {
    mix.browserify('main.js', 'public/js/all.js');
    mix.sass('main.scss', 'public/css/all.css', {precision: 10});
    mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/*.*', 'public/fonts');
    mix.copy('node_modules/font-awesome/fonts/*.*', 'public/fonts');
});
