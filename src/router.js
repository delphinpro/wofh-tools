/*!
 * WofhTools
 * File: router.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Router from 'vue-router';
import store from '@/store/index';
import Home from '@/views/Home';
import Statistic from '@/views/Statistic';
import PageNotFound from '@/views/PageNotFound';


Vue.use(Router);

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return;
    }
    next('/');
};

const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
        return;
    }
    next('/login');
};

export function createRouter() {

    return new Router({
        mode: 'history',
        base: process.env.BASE_URL,
        routes: [
            {
                path: '/',
                name: 'home',
                component: Home,
            },
            {
                path: '/stat',
                name: 'stat',
                component: Statistic,
            },
            {
                path: '/login',
                name: 'login',
                component: () => import('./views/Login.vue'),
                beforeEnter: ifNotAuthenticated,
            },
            {
                path: '/dashboard',
                name: 'dashboard',
                component: () => import('./views/Dashboard.vue'),
                children: [
                    { path: 'worlds', component: () => import('@/components/Dashboard/Worlds') },
                    { path: 'type', component: () => import('@/components/Dashboard/DemoType') },
                    { path: 'palette', component: () => import('@/components/Dashboard/DemoPalette') },
                    { path: 'boxes', component: () => import('@/components/Dashboard/DemoBoxes') },
                    { path: 'alerts', component: () => import('@/components/Dashboard/DemoAlerts') },
                    { path: 'icons', component: () => import('@/components/Dashboard/DemoIcons') },
                    { path: 'buttons', component: () => import('@/components/Dashboard/DemoButtons') },
                    { path: '*', component: PageNotFound },
                ],
            },
            { path: '*', component: PageNotFound },
        ],
    });

}
