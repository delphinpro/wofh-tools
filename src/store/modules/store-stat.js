/*!
 * WofhTools
 * File: store/modules/store-stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { WORLDS_LIST } from '@/store/actions';
import { mergeState } from '@/utils/mergeState';
import { dateFormat } from '@/utils/date';


const state = mergeState({
    worlds: [],
}, 'stat');

const getters = {
    allWorlds: state => state.worlds.filter(item => true),
    activeWorlds: state => state.worlds.filter(item => item.working),
    closedWorlds: state => state.worlds.filter(item => !item.working),
};

const mutations = {
    [WORLDS_LIST]: (state, data) => state.worlds = data,
};

const actions = {
    async [WORLDS_LIST]({ commit }, data) {
        let force = data && data.force;
        if (state.worlds.length && !force) return;

        let { worlds } = await Vue.axios.get('/wofh/worlds');

        for (let i in worlds) {
            if (!worlds.hasOwnProperty(i)) continue;
            let sign = worlds[i].sign;
            worlds[i].signU = !sign ? sign : sign[0].toUpperCase() + sign.slice(1);
            worlds[i].fmtStarted = dateFormat(worlds[i].started);
            worlds[i].fmtLoadedStat = dateFormat(worlds[i].time_of_loaded_stat);
            worlds[i].fmtUpdatedStat = dateFormat(worlds[i].time_of_updated_stat);
            worlds[i].fmtUpdatedConst = dateFormat(worlds[i].time_of_updated_const);
            worlds[i].flag = /ru/i.test(sign) ? 'flag-russia' : 'flag-uk';
        }

        commit(WORLDS_LIST, worlds);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
