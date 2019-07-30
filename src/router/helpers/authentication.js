/*!
 * WofhTools
 * File: router/helpers/authentication.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import store from '@/store';


export function onlyGuest(to, from, next) {
    if (!store.getters.isAuth) {
        next();
        return;
    }
    Vue.$toast.warn({ title: 'You are authenticated!', message: '' });
    next(false);
}

export function requireAuthenticated(to, from, next) {
    if (store.getters.isAuth) {
        next();
        return;
    }
    Vue.$toast.error({ title: 'Required authorization.', message: 'Please, sign in.' });
    next('/login');
}
