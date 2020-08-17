/*!
 * WofhTools
 * File: store/modules/store-auth.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { HTTP_HEADER_AUTHORIZATION, LS_KEY_TOKEN } from '@/constants';
import { mergeState } from '@/utils/mergeState';


export const AUTH_REQUEST = 'AUTH_REQUEST';
export const AUTH_SUCCESS = 'AUTH_SUCCESS';
export const AUTH_ERROR = 'AUTH_ERROR';
export const AUTH_LOGOUT = 'AUTH_LOGOUT';

const state = mergeState({
  token: null,
}, 'auth');

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
        if (res && res.token) {
          ctx.commit(AUTH_SUCCESS, { token: res.token });
        }
        return res;
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
