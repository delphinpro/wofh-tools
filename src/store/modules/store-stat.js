/*!
 * WofhTools
 * File: store/modules/store-stat.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { mergeState } from '@/utils/mergeState';
import { dateFormat } from '@/utils/date';
import { ucFirst } from '@/utils';

const state = mergeState({
  worlds      : [],
  currentWorld: null,
}, 'stat');

const getters = {
  allWorlds   : state => state.worlds.filter(() => true),
  activeWorlds: state => state.worlds.filter(item => item.working),
  closedWorlds: state => state.worlds.filter(item => !item.working),
  currentWorld: state => state.currentWorld,
};

const mutations = {
  updateWorlds: (state, worlds) => {
    for (let i in worlds) {
      if (!worlds.hasOwnProperty(i)) continue;
      let sign = worlds[i].sign;
      worlds[i].signU = ucFirst(sign);
      worlds[i].fmtStarted = dateFormat(worlds[i].started_at);
      worlds[i].fmtLoadedStat = dateFormat(worlds[i].stat_loaded_at);
      worlds[i].fmtUpdatedStat = dateFormat(worlds[i].stat_updated_at);
      worlds[i].fmtUpdatedConst = dateFormat(worlds[i].const_updated_at);
      worlds[i].flag = /ru/i.test(sign) ? 'flag-ru' : 'flag-uk';
    }
    state.worlds = worlds;
  },

  setCurrentWorld: (state, world) => state.currentWorld = world,
};

const actions = {
  /**
   * Получает список миров с сервера
   *
   * @param commit
   * @param data
   * @returns {Promise<void>}
   */
  async updateWorlds({ commit }, data) {
    let force = data && data.force;
    if (state.worlds.length && !force) return;

    let { worlds } = await Vue.axios.get('/worlds');

    commit('updateWorlds', worlds);
  },

  /**
   * Устанавливает текущий (выбранный) мир
   *
   * @param commit
   * @param sign
   * @returns {Promise<void>}
   */
  async setCurrentWorld({ commit }, sign) {
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
