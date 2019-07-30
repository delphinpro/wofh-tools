/*!
 * WofhTools
 * File: router/routes-user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { onlyGuest } from '@/router/helpers/authentication';


const Login = () => import(/* webpackChunkName: "user" */ '@/views/Login.vue');

/** @var Array<RouteConfig> */
export const userRoutes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: onlyGuest,
    },
];
