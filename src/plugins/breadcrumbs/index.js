/*!
 * WofhTools
 * File:
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export default function breadcrumbsPlugin(Vue, crumbs) {

  if (breadcrumbsPlugin.installed) return;
  breadcrumbsPlugin.installed = true;

  Vue.$crumbs = crumbs;

  Object.defineProperties(Vue.prototype, {
    $crumbs: {
      get() { return crumbs; },
    },
  });

}
