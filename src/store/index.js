/*!
 * WofhTools
 * File: store/index.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import user from './modules/user';
import stat from './modules/stat';
import { LOADING_DOWN, LOADING_UP } from '@/store/actions';


Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    strict: debug,
    modules: {
        auth,
        user,
        stat,
    },
    state: {
        projectName: 'Wofh Tools',
        projectVer: '4.0',
        loading: 1,
    },
    getters: {
        projectName: state => state.projectName,
        projectVer: state => state.projectVer,
        loading: state => state.loading > 0,
    },
    mutations: {
        [LOADING_UP](state) {
            state.loading = state.loading + 1;
        },
        [LOADING_DOWN](state) {
            state.loading = Math.max(state.loading - 1, 0);
        },
    },
});
