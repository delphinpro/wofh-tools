/*!
 * WofhTools
 * File: boot/i18n.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from '@/i18n';

Vue.use(VueI18n);

const i18n = new VueI18n({
  locale        : 'en-us',
  fallbackLocale: 'en-us',
  messages,
});

export default ({ app }) => {
  // Set i18n instance on app
  app.i18n = i18n;
}

export { i18n };
