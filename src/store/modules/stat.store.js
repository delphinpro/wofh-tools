/*!
 * WofhTools
 * File: store/modules/stat.store.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';

const state = {
  worlds: [],
  // currentWorld: null,
  common: [],
};

const getters = {
  allWorlds   : state => state.worlds,
  activeWorlds: state => state.worlds.filter(item => item.working),
  closedWorlds: state => state.worlds.filter(item => !item.working),
  worldBySign : state => sign => {
    let world = [...state.worlds].filter(w => w.sign === sign);
    if (world.length) return world[0];
  },
  playerById  : state => id => 'Тестовый игрок (' + id + ')',
};

const mutations = {
  updateWorlds: (state, worlds) => state.worlds = worlds,
  // setCurrentWorld: (state, world) => state.currentWorld = world,
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
};

export default {
  state,
  getters,
  actions,
  mutations,
};
