/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import user from './modules/user';
import stat from './modules/stat';


Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    strict: debug,
    modules: {
        auth,
        user,
        stat,
    },
    state: {
        projectName: 'Wofh Tools',
        projectVer: '4.0',
    },
    getters: {
        projectName: state => state.projectName,
        projectVer: state => state.projectVer,
    },
});
