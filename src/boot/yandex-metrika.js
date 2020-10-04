/*!
 * WofhTools
 * File: boot/yandex-metrika.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import VueYandexMetrika from 'vue-yandex-metrika';

export default ({ store, router, Vue, ssrContext }) => {

  let id = process.env.VUE_APP_COUNTER_ID || 0;
  let scriptSrc = process.env.VUE_APP_COUNTER_SRC || '';
  let informerSrc = process.env.VUE_APP_INFORMER_SRC || '';

  if (id && scriptSrc) store.commit('updateYaCounter', { id, src: scriptSrc });
  if (id && informerSrc) store.commit('updateYaInformer', { link: '', img: informerSrc });

  if (!ssrContext && id && scriptSrc) {
    Vue.use(VueYandexMetrika, {
      id,
      scriptSrc,
      router,
      env    : process.env.NODE_ENV,
      debug  : process.env.NODE_ENV === 'development',
      options: {
        clickmap           : true,
        trackLinks         : true,
        accurateTrackBounce: true,
      },
    });
  }

}
