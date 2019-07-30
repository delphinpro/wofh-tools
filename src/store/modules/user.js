/*!
 * WofhTools
 * File: store/modules/user.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { USER_ERROR, USER_REQUEST, USER_SUCCESS } from '../actions/user';
import { AUTH_LOGOUT } from '../actions/auth';
import apiCall from '@/utils/api';


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
        apiCall({ url: 'user/me' })
            .then(resp => {
                ctx.commit(USER_SUCCESS, resp);
            })
            .catch(resp => {
                ctx.commit(USER_ERROR);
                // if resp is unauthorized, logout, to
                ctx.dispatch(AUTH_LOGOUT);
            });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
