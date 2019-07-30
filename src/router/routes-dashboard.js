/*!
 * WofhTools
 * File: router/routes-dashboard.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { requireAuthenticated } from '@/router/helpers/authentication';
import { error404Route } from '@/router/error404';


const Dashboard = () => import(/* webpackChunkName: "dashboard" */ '@/views/Dashboard.vue');
const Worlds = () => import(/* webpackChunkName: "dashboard" */ '@/components/Dashboard/Worlds');
const CssElements = () => import(/* webpackChunkName: "dashboard" */ '@/components/CssElements/CssElements');

let children = [];

children.push({ path: 'worlds', component: Worlds });

if ((process.env.NODE_ENV === 'development') && (process.env.WEBPACK_TARGET !== 'node')) {
    children.push({ path: 'css/:id', component: CssElements });
}

children.push(error404Route);

export const dashboardRoutes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        beforeEnter: requireAuthenticated,
        children: children,
    },
];
