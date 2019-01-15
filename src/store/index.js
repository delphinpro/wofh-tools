/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';


Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    strict: debug,
    state: {
        projectName: 'Wofh Tools',
        projectVer: '4.0',
    },
    getters: {
        projectName: state => state.projectName,
        projectVer: state => state.projectVer,
    },
});
