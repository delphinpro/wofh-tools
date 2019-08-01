/*!
 * WofhTools
 * File: router/routes-user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { onlyGuest, requireAuthenticated } from '@/router/helpers/authentication';


const LoginView = () => import(/* webpackChunkName: "user" */ '@/views/User/LoginView');
const ProfileView = () => import(/* webpackChunkName: "user" */ '@/views/User/ProfileView');

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
    },
];
