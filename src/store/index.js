/*!
 * WofhTools
 * File: store/index.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from '@/store/modules/auth.store';
import user from '@/store/modules/user.store';
import stat from '@/store/modules/stat.store';
import { isDev } from '@/utils';

Vue.use(Vuex);

let state = {
  projectName: 'Wofh Tools',
  projectVer : '4.0',
  loading    : 0,
};

const getters = {
  projectName: state => state.projectName,
  projectVer : state => state.projectVer,
  loading    : state => state.loading > 0,
};

const mutations = {
  loadingUp(state) { state.loading = state.loading + 1; },
  loadingDown(state) { state.loading = Math.max(state.loading - 1, 0); },
};

const actions = {
  loadingOn({ commit }) { commit('loadingUp'); },
  loadingOff({ commit }) { commit('loadingDown'); },
};

export default new Vuex.Store({
  strict : isDev(),
  modules: {
    auth,
    user,
    stat,
  },
  state,
  getters,
  mutations,
  actions,
});
