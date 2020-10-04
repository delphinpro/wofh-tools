/*!
 * WofhTools
 * File: boot/yandex-metrika.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import VueYandexMetrika from 'vue-yandex-metrika';

export default async ({ app, router, store, Vue, ssrContext }) => {

  if (!ssrContext
    && store.getters.yaCounter.id
    && store.getters.yaCounter.src
  ) {
    Vue.use(VueYandexMetrika, {
      id       : +store.getters.yaCounter.id,
      scriptSrc: store.getters.yaCounter.src,
      router   : router,
      env      : 'production',//process.env.NODE_ENV,
      options  : {
        clickmap           : true,
        trackLinks         : true,
        accurateTrackBounce: true,
      },
    });
  }

}
