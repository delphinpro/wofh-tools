/*!
 * WofhTools
 * File: store/modules/stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { mergeState } from '@/utils/mergeState';


const state = mergeState({
    dd: 123,

    charts: {
        accounts: {
            series: [],
        },
    },
}, 'stat');

const getters = {
    demoValue: state => state.dd,
    accountsChart: state => state.charts.accounts,
};

const mutations = {
    setDemo(state, data) {
        Vue.set(state, 'dd', data);
    },
    setChart(state, data) {
        Vue.set(state.charts, 'accounts', data);
    },
};

const actions = {
    setDemo(ctx, data) {
        ctx.commit('setDemo', data);
    },

    setChart(ctx, data) {
        ctx.commit('setChart', data);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
