/*!
 * WofhTools
 * File: store/modules/auth.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { AUTH_ERROR, AUTH_LOGOUT, AUTH_REQUEST, AUTH_SUCCESS } from '../actions/auth';
import { HTTP_HEADER_AUTHORIZATION, LS_KEY_TOKEN } from '@/utils/constants';


const state = {
    token: null,
};

try {

    state.token = localStorage.getItem(LS_KEY_TOKEN);

} catch ( e ) {}

const getters = {
    isAuth: state => !!state.token,
};

const mutations = {

    [AUTH_SUCCESS](state, resp) {
        state.token = resp.token;
        Vue.axios.defaults.headers.common[HTTP_HEADER_AUTHORIZATION] = `Bearer ${resp.token}`;
        localStorage.setItem(LS_KEY_TOKEN, resp.token);
    },

    [AUTH_ERROR](state) {
        state.token = null;
        delete Vue.axios.defaults.headers.common[HTTP_HEADER_AUTHORIZATION];
        localStorage.removeItem(LS_KEY_TOKEN);
    },

    [AUTH_LOGOUT](state) {
        state.token = null;
        delete Vue.axios.defaults.headers.common[HTTP_HEADER_AUTHORIZATION];
        localStorage.removeItem(LS_KEY_TOKEN);
    },

};

const actions = {
    [AUTH_REQUEST](ctx, user) {
        return Vue.axios
            .post('/login', user)
            .then(res => {
                if (res.data.payload && res.data.payload.token) {
                    ctx.commit(AUTH_SUCCESS, { token: res.data.payload.token });
                }
                return res.data;
            })
            .catch(err => console.log(err));
    },

    [AUTH_LOGOUT](ctx) {
        return new Promise((resolve, reject) => {
            ctx.commit(AUTH_LOGOUT);
            resolve();
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
