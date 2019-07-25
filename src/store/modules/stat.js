/*!
 * WofhTools
 * File: store/modules/stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';


const state = {
    dd: 123,

    charts: {
        accounts: {
            series: [],
        },
    },
};

const getters = {
    demoValue: state => state.dd,
    accountsChart: state => state.charts.accounts,
};

const actions = {
    setDemo: ({ commit }, data) => {
        commit('setDemo', data);
    },

    setChart: ({ commit }, data) => {
        commit('setChart', data);
    },
};

const mutations = {
    setDemo: (state, data) => {
        Vue.set(state, 'dd', data);
    },
    setChart: (state, data) => {
        Vue.set(state.charts, 'accounts', data);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
