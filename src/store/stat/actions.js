import Vue from 'vue';

/**
 * Получает список миров с сервера
 */
export function updateWorlds({ commit, state }, data = null) {
  if (state.worlds.length && !data?.force) return;
  return Vue.axios.get('/world?active=true')
    .then(worlds => {
      commit('updateWorlds', worlds);
    });
}

export function updateCommonStat({ commit }, sign) {
  return Vue.axios.get(`/stat/${sign}/last`)
    .then(stat => {
      commit('setCommonStat', stat);
    });
}
