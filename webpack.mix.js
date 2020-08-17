const path = require('path');

/** @type {Api} */
const mix = require('laravel-mix');

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

if (!process.env.NODE_ENV) process.env.NODE_ENV = 'production';

const IS_SERVER = process.env.BUNDLE === 'server';
const IS_CLIENT = !IS_SERVER;

const src = 'src';
const dist = 'assets';

console.log({ NODE_ENV: process.env.NODE_ENV, BUNDLE: process.env.BUNDLE });

if (!mix.inProduction() && IS_CLIENT) {
  mix.sourceMaps();
}

if (IS_CLIENT) {
  mix.sass(`${src}/_sass/main.scss`, dist);
  mix.browserSync({
    proxy: 'wofh-tools.project',
    browser: ['chrome'],
  });
}
