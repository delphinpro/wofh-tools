/*!
 * WofhTools
 * File: store/index.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/store-auth';
import user from './modules/store-user';
import stat from './modules/store-stat';
import { LOADING_DOWN, LOADING_UP } from '@/store/actions';
import { mergeState } from '@/utils/mergeState';


Vue.use(Vuex);

const strict = process.env.NODE_ENV !== 'production';

let state = mergeState({
    projectName: 'Wofh Tools',
    projectVer: '4.0',
    loading: 1,
});

const getters = {
    projectName: state => state.projectName,
    projectVer: state => state.projectVer,
    loading: state => state.loading > 0,
};

const mutations = {
    [LOADING_UP](state) { state.loading = state.loading + 1; },
    [LOADING_DOWN](state) { state.loading = Math.max(state.loading - 1, 0); },
};

export default new Vuex.Store({
    strict,
    modules: {
        auth,
        user,
        stat,
    },
    state,
    getters,
    mutations,
});
