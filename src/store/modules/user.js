/*!
 * WofhTools
 * File: store/modules/user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { USER_ERROR, USER_REQUEST, USER_SUCCESS } from '@/store/actions/user';
import { AUTH_LOGOUT } from '@/store/actions/auth';


const state = {
    status: '',
    profile: {},
};

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name,
};

const mutations = {
    [USER_REQUEST](state) {
        state.status = 'loading';
    },

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
    [USER_REQUEST](ctx) {
        ctx.commit(USER_REQUEST);
        let user = {};
        return Vue.axios
            .get('/user/profile')
            .then(res => {
                return res.data.payload;
            });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
