let mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    // .sass('resources/assets/sass/app.scss', 'public/css')
    .postCss('resources/assets/styles/app.pcss', 'public/css', [
        tailwindcss('./tailwind.js'),
    ])
    .extract(['vue', 'chart.js', 'moment']);

if (!mix.inProduction()) {
  mix.sourceMaps();
}

if (mix.inProduction()) {
  mix.version();
}
