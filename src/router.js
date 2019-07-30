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
import Error404 from '@/views/Error404';


Vue.use(Router);

const onlyGuest = (to, from, next) => {
    if (!store.getters.isAuth) {
        next();
        return;
    }
    Vue.$toast.warn({ title: 'You are authenticated!', message: '' });
    next(false);
};

const requireAuthenticated = (to, from, next) => {
    if (store.getters.isAuth) {
        next();
        return;
    }
    Vue.$toast.error({ title: 'Required authorization.', message: 'Please, sign in.' });
    next('/login');
};

const includeCssDemo = (process.env.NODE_ENV === 'development') && (process.env.WEBPACK_TARGET !== 'node');

export function createRouter() {


    const dashboardRoutes = [
        { path: 'worlds', component: () => import('@/components/Dashboard/Worlds') },
    ];

    if (includeCssDemo) {
        dashboardRoutes.push(
            { path: 'css/:id', component: () => import('@/components/CssElements/CssElements') },
        );
    }

    dashboardRoutes.push({ path: '*', component: Error404 });


    let router = new Router({
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
                beforeEnter: onlyGuest,
            },
            {
                path: '/dashboard',
                name: 'dashboard',
                component: () => import('./views/Dashboard.vue'),
                beforeEnter: requireAuthenticated,
                children: dashboardRoutes,
            },

            { path: '*', component: Error404 },
        ],
    });

    router.beforeEach((to, from, next) => {
        Vue.$toast.removeAll();
        next();
    });

    return router;
}
