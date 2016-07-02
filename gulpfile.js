var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less')
        .styles([
            'style.css',
            'easy-autocomplete.min.css',
            'easy-autocomplete.themes.min.css'
        ], 'public/css/style.min.css')
    .scripts(
        [
            'jquery.easy-autocomplete.min.js',
            'actions.js',
            'autocomplete.js'
        ], 'public/lib/app.min.js'
    )
});

require('laravel-elixir-wiredep');
require('laravel-elixir-imagemin');

elixir(function(mix) {
   mix.imagemin();
});

elixir(function(mix) {
   mix.wiredep({src: 'app.blade.php'});
});