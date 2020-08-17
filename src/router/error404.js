/*!
 * WofhTools
 * File: router/error404.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Error404 from '@/views/Error404';


export const error404Route = {
  path: '*',
  component: Error404,
};
