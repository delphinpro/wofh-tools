/*!
 * WofhTools
 * File: store/modules/store-stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { mergeState } from '@/utils/mergeState';
import { dateFormat } from '@/utils/date';
import { ucFirst } from '@/utils';


export const WORLDS_LIST = 'WORLDS_LIST';
export const CURRENT_WORLD = 'CURRENT_WORLD';

const state = mergeState({
    worlds: [],
    currentWorld: null,
}, 'stat');

const getters = {
    allWorlds: state => state.worlds.filter(item => true),
    activeWorlds: state => state.worlds.filter(item => item.working),
    closedWorlds: state => state.worlds.filter(item => !item.working),
    currentWorld: state => state.currentWorld,
};

const mutations = {
    [WORLDS_LIST]: (state, data) => state.worlds = data,
    [CURRENT_WORLD]: (state, world) => state.currentWorld = world,
};

const actions = {
    /**
     * Получает список миров с сервера
     *
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async [WORLDS_LIST]({ commit }, data) {
        let force = data && data.force;
        if (state.worlds.length && !force) return;

        let { worlds } = await Vue.axios.get('/wofh/worlds');

        for (let i in worlds) {
            if (!worlds.hasOwnProperty(i)) continue;
            let sign = worlds[i].sign;
            worlds[i].signU = ucFirst(sign);
            worlds[i].fmtStarted = dateFormat(worlds[i].started);
            worlds[i].fmtLoadedStat = dateFormat(worlds[i].time_of_loaded_stat);
            worlds[i].fmtUpdatedStat = dateFormat(worlds[i].time_of_updated_stat);
            worlds[i].fmtUpdatedConst = dateFormat(worlds[i].time_of_updated_const);
            worlds[i].flag = /ru/i.test(sign) ? 'flag-russia' : 'flag-uk';
        }

        commit(WORLDS_LIST, worlds);
    },

    /**
     * Устанавливает текущий (выбранный) мир
     *
     * @param commit
     * @param sign
     * @returns {Promise<void>}
     */
    async [CURRENT_WORLD]({ commit }, sign) {
        let world = [...state.worlds].filter(w => w.sign === sign);
        if (world.length) commit(CURRENT_WORLD, world[0]);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
