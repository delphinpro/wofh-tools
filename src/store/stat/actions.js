import Vue from 'vue';

/**
 * Получает список миров с сервера
 * @returns {Promise<void>}
 */
export function updateWorlds({ commit, state }, data = null) {
  // let force = data && data.force;
  if (state.worlds.length && !data?.force) return;

  return Vue.axios.get('/world?active=true')
    .then(worlds => {
      commit('updateWorlds', worlds);
    });
}
