/*!
 * WofhTools
 * File: router/index.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Router from 'vue-router';
import HomeView from '@/views/HomeView';
import { userRoutes } from '@/router/routes-user';
import { dashboardRoutes } from '@/router/routes-dashboard';
import { statRoutes } from '@/router/routes-stat';
import { error404Route } from '@/router/error404';
import { isServerBundle } from '@/utils/mergeState';


Vue.use(Router);

export function createRouter() {

    let router = new Router({
        mode: 'history',

        base: process.env.BASE_URL,

        routes: [
            {
                path: '/',
                name: 'home',
                component: HomeView,
            },
        ],
    });

    router.addRoutes(userRoutes);
    router.addRoutes(statRoutes);
    router.addRoutes(dashboardRoutes);
    router.addRoutes([error404Route]);

    if (!isServerBundle()) {
        router.beforeEach((to, from, next) => {
            Vue.$toast.removeAll();
            next();
        });
    }

    return router;
}
