/*!
 * WofhTools
 * File: router/groups/user.route.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import ViewLogin from '@/views/User/ViewLogin';
import ViewProfile from '@/views/User/ViewProfile';
import { onlyGuest, requireAuthenticated } from '@/router/helpers/authentication';
import { ROUTE_LOGIN, ROUTE_PROFILE } from '@/constants.js';

/** @var Array<RouteConfig> */
export const userRoutes = [
  {
    path       : '/login',
    name       : ROUTE_LOGIN,
    component  : ViewLogin,
    beforeEnter: onlyGuest,
  },
  {
    path       : '/user/profile',
    name       : ROUTE_PROFILE,
    component  : ViewProfile,
    beforeEnter: requireAuthenticated,
    meta       : {
      crumbsText: 'Ваш профиль',
      pageTitle : 'Ваш профиль',
    },
  },
];
