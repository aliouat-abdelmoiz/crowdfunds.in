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
            'easy-autocomplete.themes.min.css',
            '../../../public/bower_components/animate.css/animate.css',
            '../../../public/bower_components/sweetalert/dist/sweetalert.css',
            '../../../public/bower_components/dropzone/dist/min/dropzone.min.css',
        ], 'public/css/style.min.css')
    .scripts(
        [
            '../../../public/bower_components/jquery/dist/jquery.min.js',
            '../../../public/bower_components/bootstrap/dist/js/bootstrap.js',
            '../../../public/bower_components/jquery.maskedinput/dist/jquery.maskedinput.js',
            '../../../public/bower_components/sweetalert/dist/sweetalert.min.js',
            '../../../public/bower_components/jquery-infinite-scroll/jquery.infinitescroll.js',
            '../../../public/bower_components/dropzone/dist/min/dropzone.min.js',
            '../../../public/bower_components/EasyAutocomplete/dist/jquery.easy-autocomplete.min.js',
            '../../../public/bower_components/vue/dist/vue.js',
            '../../../public/bower_components/vue-resource/dist/vue-resource.js',
            'actions.js',
            'autocomplete.js'
        ], 'public/lib/app.min.js'
    )
});

require('laravel-elixir-wiredep');
require('laravel-elixir-imagemin');
require('laravel-elixir-bower');

elixir(function(mix) {
   mix.imagemin();
});

elixir(function(mix) {
   mix.wiredep({src: 'app.blade.php'});
});