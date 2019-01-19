/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import Vue from 'vue';
import Router from 'vue-router';
import store from '@/store/index';
import Home from '@/views/Home.vue';
import PageNotFound from '@/views/PageNotFound.vue';


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
                path: '/login',
                name: 'login',
                component: () => import('./views/Login.vue'),
                beforeEnter: ifNotAuthenticated,
            },
            { path: '*', component: PageNotFound },
        ],
    });

}
