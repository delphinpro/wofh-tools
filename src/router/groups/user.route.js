/*!
 * WofhTools
 * File: router/groups/user.route.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import ViewLogin from '@/views/User/ViewLogin';
import ViewProfile from '@/views/User/ViewProfile';
import { onlyGuest, requireAuthenticated } from '@/router/helpers/authentication';

/** @var Array<RouteConfig> */
export const userRoutes = [
  {
    path       : '/login',
    name       : 'login',
    component  : ViewLogin,
    beforeEnter: onlyGuest,
  },
  {
    path       : '/user/profile',
    name       : 'profile',
    component  : ViewProfile,
    beforeEnter: requireAuthenticated,
    meta       : {
      crumbsText: 'Ваш профиль',
      pageTitle : 'Ваш профиль',
    },
  },
];
