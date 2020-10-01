/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 */

const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

// Configuration for your app
// https://quasar.dev/quasar-cli/quasar-conf-js

module.exports = function (/* ctx */) {

  return {
    supportTS: false, // https://quasar.dev/quasar-cli/supporting-ts

    preFetch: true, // https://quasar.dev/quasar-cli/prefetch-feature

    boot: [
      // app boot file (/src/boot)
      // --> boot files are part of "main.js"
      // https://quasar.dev/quasar-cli/boot-files

      'i18n',
      'axios',
      'breadcrumbs',
    ],

    css: [
      // https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-css

      'app.scss',
    ],

    extras: [
      // https://github.com/quasarframework/quasar/tree/dev/extras

      // 'ionicons-v4',
      // 'mdi-v5',
      // 'fontawesome-v5',
      // 'eva-icons',
      // 'themify',
      // 'line-awesome',
      // 'roboto-font-latin-ext', // this or either 'roboto-font', NEVER both!

      // 'roboto-font', // optional, you are not bound to it
      // 'material-icons', // optional, you are not bound to it
    ],

    build: {
      // Full list of options: https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-build

      vueRouterMode: 'history',

      // transpile: false,

      // Add dependencies for transpiling with Babel (Array of string/regex)
      // (from node_modules, which are by default not transpiled).
      // Applies only if "transpile" is set to true.
      // transpileDependencies: [],

      // preloadChunks     : true,
      // showProgress      : false,
      // gzip              : true,
      // analyze           : true,
      rtl               : false, // https://quasar.dev/options/rtl-support
      ignorePublicFolder: true,
      htmlFilename      : 'index.html',

      // Options below are automatically set depending on the env, set them if you want to override
      extractCSS: true,

      extendWebpack(cfg, { isServer, isClient }) {
        // https://quasar.dev/quasar-cli/handling-webpack

        cfg.resolve.alias = {
          ...cfg.resolve.alias, // This adds the existing alias
          '@': path.resolve(__dirname, './src'),
        };

        cfg.plugins.push(
          new CopyWebpackPlugin({
            patterns: [
              { from: 'public/favicon.ico', to: 'favicon.ico' },
              { from: 'public/favicon', to: 'favicon' },
              { from: 'public/images', to: 'images' },
            ],
          }),
        );
      },
    },

    devServer: {
      // Full list of options: https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-devServer
      https: false,
      port : 3000,
      open : 'chrome',
      proxy: {
        '/api': {
          target      : process.env.VUE_APP_DEV_SERVER_PROXY ?? 'http://wofh-tools.project:8080',
          changeOrigin: true,
        },
      },
    },

    framework: {
      // https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-framework
      // iconSet: 'material-icons', // Quasar icon set
      lang: 'ru', // Quasar language pack

      // Possible values for "importStrategy":
      // * 'auto' - (DEFAULT) Auto-import needed Quasar components & directives
      // * 'all'  - Manually specify what to import
      importStrategy: 'auto',

      // For special cases outside of where "auto" importStrategy can have an impact
      // (like functional components as one of the examples),
      // you can manually specify Quasar components/directives to be available everywhere:
      //
      // components: [],
      // directives: [],

      // Quasar plugins
      plugins: [
        'Loading',
      ],

      config: {
        loading: {},
      },
    },

    animations: [
      // animations: 'all', // --- includes all animations
      // https://quasar.dev/options/animations
    ],

    ssr: {
      // https://quasar.dev/quasar-cli/developing-ssr/configuring-ssr
      pwa: false,
    },

  };
};
