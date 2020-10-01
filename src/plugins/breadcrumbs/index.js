/*!
 * WofhTools
 * Breadcrumbs plugin
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { Breadcrumbs } from './Breadcrumbs';

export default {
  install(Vue, { router, store, elements }) {

    const breadcrumbs = new Breadcrumbs(router, store);

    if (typeof elements === 'function') {
      elements.call(breadcrumbs);
    }

    Vue.prototype.$crumbs = breadcrumbs;
  },
};
