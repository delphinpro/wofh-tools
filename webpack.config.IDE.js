/*
 * Этот файл НЕ используется в сборке.
 * Он нужен только для корректного автокомплита в IDE phpStorm.
 * Эта IDE почему-то не работает как следует, при указании реально использующегося
 * файла концигурации, расположенного не в корне проекта (node_modules/laravel-mix/setup/webpack.config.js)
 */

const path = require('path');

module.exports = {
  resolve: {
    extensions: ['*', '.wasm', '.mjs', '.js', '.jsx', '.json', '.vue'],
    alias: {
      'vue$': 'vue/dist/vue.runtime.esm.js',
      '@': path.join(__dirname, 'src'),
    },
  },
};
