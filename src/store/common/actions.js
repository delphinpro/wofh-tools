/*!
 * WofhTools
 * File: store/common/actions.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';

export function updateProjectInfo({ commit, state }, data = null) {

  return Vue.axios.get('/info')
    .then(info => {
      commit('updateProjectInfo', {
        name     : info.name,
        version  : info.version,
        updatedAt: info.updatedAt ? info.updatedAt * 1000 : null,
      });
    });

}
