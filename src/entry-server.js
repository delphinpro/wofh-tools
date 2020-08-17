/**!
 * WofhTools
 * File: entry-server.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 *
 * @external dispatch
 * @external context
 */

import renderVueComponentToString from 'vue-server-renderer/basic';
import { createApp } from '@/app';


renderVueComponentToString(createApp(context.state, context.url), (err, html) => {
  if (err) throw new Error(err);
  dispatch(html);
});
