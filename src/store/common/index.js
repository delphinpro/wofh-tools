/*!
 * WofhTools
 * File: store/common/index.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import state from './state';
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

export default {
  // namespaced: true,
  getters,
  mutations,
  actions,
  state,
};
