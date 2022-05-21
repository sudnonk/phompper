const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.typeScript([
    'resources/ts/main.ts',
    'resources/ts/phompper.ts',
    'resources/ts/phompper_form.ts',
    'resources/ts/phompper_map.ts',
    'resources/ts/phompper_util.ts'
], 'public/js');

