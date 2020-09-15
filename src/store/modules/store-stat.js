/*!
 * WofhTools
 * File: store/modules/store-stat.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';

const state = {
  worlds      : [],
  currentWorld: null,
  common      : [],
};

const getters = {
  allWorlds   : state => state.worlds,
  activeWorlds: state => state.worlds.filter(item => item.working),
  closedWorlds: state => state.worlds.filter(item => !item.working),
  currentWorld: state => state.currentWorld,
};

const mutations = {
  updateWorlds   : (state, worlds) => state.worlds = worlds,
  setCurrentWorld: (state, world) => state.currentWorld = world,
};

const actions = {
  /**
   * Получает список миров с сервера
   * @returns {Promise<void>}
   */
  async updateWorlds({ commit, state }, data) {
    let force = data && data.force;
    if (state.worlds.length && !force) return;

    let worlds = await Vue.axios.get('/world?active=true');
    commit('updateWorlds', worlds);
  },

  /**
   * Устанавливает текущий (выбранный) мир
   * @returns {Promise<void>}
   */
  async setCurrentWorld({ commit, state }, sign) {
    let world = [...state.worlds].filter(w => w.sign === sign);
    if (world.length) commit('setCurrentWorld', world[0]);
  },
};

export default {
  state,
  getters,
  actions,
  mutations,
};
