/*!
 * WofhTools
 * File: store/index.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';

import common from './common';
import stat from './stat';

Vue.use(Vuex);

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default function (/* { ssrContext } */) {
  const Store = new Vuex.Store({
    modules: {
      common,
      stat,
    },

    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEV,
  });

  // noinspection JSUnresolvedVariable
  if (process.env.DEV && module.hot) {
    module.hot.accept(['./common'], () => Store.hotUpdate({ modules: { common: require('./common').default } }));
    module.hot.accept(['./stat'], () => Store.hotUpdate({ modules: { stat: require('./stat').default } }));
  }

  return Store;
}
