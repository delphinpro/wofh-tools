/*!
 * WofhTools
 * File: main.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import router from '@/router';
import store from '@/store';
import App from '@/components/App/App.vue';
import PageHeader from '@/components/App/PageHeader';
import FaIcon from '@/components/App/FaIcon';
import Alert from '@/components/Widgets/Alert';
import Inputbox from '@/components/Forms/Inputbox';
import Checkbox from '@/components/Forms/Checkbox';
import RadioButton from '@/components/Forms/RadioButton';

export function createApp({ state, url }, parameters = null) {

  //==
  //== Global Components
  //== ======================================= ==//

  Vue.component('FaIcon', FaIcon);
  Vue.component('PageHeader', PageHeader);
  Vue.component('Alert', Alert);
  Vue.component('Inputbox', Inputbox);
  Vue.component('Checkbox', Checkbox);
  Vue.component('RadioButton', RadioButton);

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

  return new Vue({
    router,
    store,
    render: h => h(App),
  });

}
