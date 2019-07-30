/*!
 * WofhTools
 * File: router/routes-user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { onlyGuest } from '@/router/helpers/authentication';


const LoginView = () => import(/* webpackChunkName: "user" */ '@/views/User/LoginView');

/** @var Array<RouteConfig> */
export const userRoutes = [
    {
        path: '/login',
        name: 'login',
        component: LoginView,
        beforeEnter: onlyGuest,
    },
];
