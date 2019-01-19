/**
 * Vue config
 */

const path = require('path');

const NODE_ENV = process.env.NODE_ENV === 'development'
    ? 'development'
    : 'production';

const TARGET_NODE = process.env.WEBPACK_TARGET === 'node';

const target = process.env.WEBPACK_TARGET === 'node' ? 'server' : 'client';

const assetsDir = 'static';

module.exports = {
    outputDir: 'public_html',
    assetsDir,
    indexPath: 'index.php',
    productionSourceMap: false,

    configureWebpack: () => ({
        entry: `./src/entry-${target}`,
        target: TARGET_NODE ? 'node' : 'web',
        node: TARGET_NODE ? undefined : false,

        externals: undefined,

        output: {
            filename: path.join(assetsDir, `js/${target}${TARGET_NODE ? '' : '.[hash:8]'}.js`).replace(/\\/g, '/'),
        },

        optimization: {
            splitChunks: TARGET_NODE ? false : undefined,
        },
    }),

    chainWebpack: config => {
        config.module
              .rule('vue')
              .use('vue-loader')
              .tap(options => {
                      return {
                          ...options,
                          optimizeSSR: false,
                      };
                  },
              )
        ;
        config
            .plugin('html')
            .tap(args => {
                args[0].minify = false;
                return args;
            });
    },

    css: {
        sourceMap: true,
        extract: NODE_ENV === 'production' && !TARGET_NODE,
        loaderOptions: {
            sass: {
                data: `@import "@/styles/config/env-${NODE_ENV}.scss";`,
            },
        },
    },
};
