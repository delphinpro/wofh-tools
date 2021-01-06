/*!
 * WofhTools
 * File: store/stat/index.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import state from './state'
import * as getters from './getters'
import * as mutations from './mutations'
import * as actions from './actions'

export default {
  // namespaced: true,
  state,
  getters,
  mutations,
  actions
}
