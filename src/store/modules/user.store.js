/*!
 * WofhTools
 * File: store/modules/user.store.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { AUTH_LOGOUT } from '@/store/modules/auth.store';

export const USER_REQUEST = 'USER_REQUEST';
export const USER_SUCCESS = 'USER_SUCCESS';
export const USER_ERROR = 'USER_ERROR';

const state = {
  profile: {},
};

const getters = {
  getProfile     : state => state.profile,
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
