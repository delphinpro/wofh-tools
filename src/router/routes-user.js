/*!
 * WofhTools
 * File: router/routes-user.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { onlyGuest, requireAuthenticated } from '@/router/helpers/authentication';
import LoginView from '@/views/User/LoginView';
import ProfileView from '@/views/User/ProfileView';


/** @var Array<RouteConfig> */
export const userRoutes = [
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    beforeEnter: onlyGuest,
  },
  {
    path: '/user/profile',
    name: 'profile',
    component: ProfileView,
    beforeEnter: requireAuthenticated,
    meta: {
      crumbsText: 'Ваш профиль',
      pageTitle: 'Ваш профиль',
    },
  },
];
