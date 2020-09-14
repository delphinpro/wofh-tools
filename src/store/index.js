/*!
 * WofhTools
 * File: store/index.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from '@/store/modules/store-auth';
import user from '@/store/modules/store-user';
import stat from '@/store/modules/store-stat';
import { isDev } from '@/utils';

Vue.use(Vuex);

const strict = isDev();

export const LOADING_UP = 'loadingUp';
export const LOADING_DOWN = 'loadingDown';

let state = {
  projectName: 'Wofh Tools',
  projectVer : '4.0',
  loading    : 1,
};

const getters = {
  projectName: state => state.projectName,
  projectVer : state => state.projectVer,
  loading    : state => state.loading > 0,
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
