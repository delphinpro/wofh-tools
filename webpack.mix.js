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
  mix.sass(`${src}/admin/sass/custom.scss`, `${dist}/admin`);
  mix.js(`${src}/entry-client.js`, dist);
  mix.browserSync({
    proxy: 'wofh-tools.project',
    browser: ['chrome'],
  });
  mix.extract();
  mix.copyDirectory(
    path.join(__dirname, `resources/assets/images`),
    path.join(__dirname, `public/${dist}/images`),
  );
  mix.copy(
    path.join(__dirname, 'node_modules/@fortawesome/fontawesome-free/sprites/regular.svg'),
    path.join(__dirname, `public/${dist}/regular.svg`),
  );
  mix.copy(
    path.join(__dirname, 'node_modules/@fortawesome/fontawesome-free/sprites/solid.svg'),
    path.join(__dirname, `public/${dist}/solid.svg`),
  );
  mix.copy(
    path.join(__dirname, `resources/assets/custom.svg`),
    path.join(__dirname, `public/${dist}/custom.svg`),
  );
}

if (IS_SERVER) {
  mix.js(`${src}/entry-server.js`, dist);
}

mix.webpackConfig(() => {
  const config = {};

  config.resolve = {
    alias: {
      '@': path.join(__dirname, src),
      'vue$': 'vue/dist/vue.runtime.esm.js',
    },
  };

  return config;
});

mix.options({
  extractVueStyles: false,
  processCssUrls: false,
  terser: {},
  purifyCss: false,
  //purifyCss: {},
  postCss: [require('autoprefixer')],
  clearConsole: false,
  cssNano: {
    // discardComments: {removeAll: true},
  }
});
