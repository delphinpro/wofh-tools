/*!
 * WofhTools
 * File: main.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import quasar, { Dark } from 'quasar';
import router from '@/router';
import store from '@/store';
import breadcrumbs from '@/plugins/breadcrumbs';
import crumbs from '@/router/breadcrumbs';
import App from '@/components/App/App.vue';
import PageHeader from '@/components/App/PageHeader';

export function createApp({ state, url }, parameters = null) {

  //==
  //== Global Components
  //== ======================================= ==//

  Vue.component('PageHeader', PageHeader);

  const newState = {
    ...store.state,
    ...state,
  };

  store.replaceState(newState);

  if (url) router.push(url);

  if ((parameters && typeof parameters === 'object' && !Array.isArray(parameters))) { // isObject()
    if (typeof parameters.onBeforeCreateApp === 'function') {
      parameters.onBeforeCreateApp(store, router);
    }
  }

  Vue.use(quasar);
  Vue.use(breadcrumbs, crumbs);
  Dark.set(true);

  return new Vue({
    router,
    store,
    render: h => h(App),
  });

}
