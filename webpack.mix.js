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
  mix.sass(`${src}/app-styles/app.scss`, dist);
  mix.sass(`${src}/app-styles/components.scss`, dist);
  mix.js(`${src}/entry-client.js`, dist);
  mix.browserSync({
    proxy  : 'wofh-tools.project',
    browser: ['chrome'],
  });
  mix.extract();
  mix.copyDirectory(
    path.join(__dirname, `resources/assets/images`),
    path.join(__dirname, `public/${dist}/images`),
  );
  mix.copyDirectory(
    path.join(__dirname, `resources/assets/admin`),
    path.join(__dirname, `public/${dist}/admin`),
  );
}

if (IS_SERVER) {
  mix.sass(`${src}/app-styles/components.scss`, dist);
  mix.js(`${src}/entry-server.js`, dist);
}

mix.webpackConfig(() => {
  const config = {};

  config.resolve = {
    alias: {
      '@'   : path.join(__dirname, src),
      'vue$': 'vue/dist/vue.runtime.esm.js',
    },
  };

  return config;
});

mix.options({
  extractVueStyles: true,
  globalVueStyles : path.join(__dirname, 'src/app-styles/config.scss'),
  processCssUrls  : false,
  terser          : {},
  purifyCss       : false,
  //purifyCss: {},
  postCss         : [require('autoprefixer')],
  clearConsole    : false,
  cssNano         : {
    // discardComments: {removeAll: true},
  },
});
