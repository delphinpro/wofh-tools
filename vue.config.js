const env = process.env.NODE_ENV === 'development'
    ? 'development'
    : 'production';

module.exports = {
    outputDir: 'public_html',
    assetsDir: 'design',
    indexPath: 'index.html',
    filenameHashing: false,

    css: {
        sourceMap: true,
        loaderOptions: {
            sass: {
                data: `@import "@/styles/config/env-${env}.scss";`,
            },
        },
    },
};
