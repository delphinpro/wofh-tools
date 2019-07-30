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

let indexTemplate = NODE_ENV === 'production'
    ? path.join(__dirname, 'private/templates/layouts/index.twig')
    : path.join(__dirname, 'private/templates/layouts/index.html');

let devServerProxySettings = {
    target: 'http://wofh-tools.project',
    ws: true,
    changeOrigin: true,
};

// noinspection JSUnusedGlobalSymbols
module.exports = {
    outputDir: 'public_html',
    assetsDir,
    indexPath: NODE_ENV === 'production' ? '../private/templates/layouts/base.twig' : 'index.html',
    productionSourceMap: false,

    devServer: {
        proxy: {
            '^/api': devServerProxySettings,
        },
    },

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
        config.module
            .rule('images')
            .use('url-loader')
            .tap(options => ({
                ...options,
                limit: 1024,
            }))
        ;
        config
            .plugin('html')
            .tap(args => {
                args[0].minify = false;
                args[0].template = indexTemplate;
                return args;
            });
    },

    css: {
        sourceMap: false,
        extract: NODE_ENV === 'production' && !TARGET_NODE,
        loaderOptions: {
            sass: {
                data: `@import "@/styles/config/env-${NODE_ENV}.scss";`,
            },
        },
    },
};
