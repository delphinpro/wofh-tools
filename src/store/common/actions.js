/*!
 * WofhTools
 * File: store/common/actions.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';

export function updateCommonInfo({ commit }) {

  return Vue.axios.get('/info')
    .then(info => {
      commit('updateProjectInfo', {
        name     : info.project.name,
        version  : info.project.version,
        updatedAt: info.project.updatedAt ? info.project.updatedAt * 1000 : null,
      });
    });

}
