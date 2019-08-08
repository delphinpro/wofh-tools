/*!
 * WofhTools
 * File: store/modules/user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { USER_ERROR, USER_REQUEST, USER_SUCCESS } from '@/store/actions/user';
import { AUTH_LOGOUT } from '@/store/actions/auth';
import { mergeState } from '@/utils/mergeState';


const state = mergeState({
    profile: {},
}, 'user');

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name,
};

const mutations = {
    [USER_SUCCESS](state, resp) {
        state.status = 'success';
        Vue.set(state, 'profile', resp);
    },

    [USER_ERROR](state) {
        state.status = 'error';
    },

    [AUTH_LOGOUT](state) {
        state.profile = {};
    },
};

const actions = {
    [USER_REQUEST]() {
        return Vue.axios.get('/user/profile');
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
